<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//this controller also used for static/design pages, index() / referral_program() / secure_payments_process() / contact() / we_vs_them() / privacy_policy() / terms_and_conditions() / trust_and_safety() / faq() / about_us() / fees_and_charges() / code_of_conduct()
class User extends MX_Controller {

    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password 
     */
    public function __construct() {
        $this->load->helper('text');
        $this->load->model('User_model');
        $this->load->model('projects/Projects_model');
        $this->load->model('bidding/Bidding_model');
		$this->load->model('dashboard/Dashboard_model');
		$this->load->model('escrow/Escrow_model');
		$this->load->library('pagination');
		 $this->load->library('image_lib');
        parent::__construct();
    }

    // this is the default action which call the home page	
    public function home() {
        $this->checkCurrentUser();
        $data['current_page'] = 'home';
		
		$home_title_meta_tag = $this->config->item('home_page_title_meta_tag');
		$home_description_meta_tag = $this->config->item('home_page_description_meta_tag');
		$data['meta_tag'] = '<title>' . $home_title_meta_tag . '</title><meta name="description" content="' . $home_description_meta_tag . '"/>';
        $data['count_users'] = 0;
        $data['count_projects'] = 0;
        $data['count_project_completed'] = 0;

        $this->layout->view('home', '', $data, 'normal');
    }
	
    public function logout() {
		$this->load->library('user_agent');
		$referer = $this->agent->referrer();
        // @sid
        if ($this->session->userdata('is_authorized') == 1) {
            $log_str = 'user ' . $_SERVER['REMOTE_ADDR'] . '/' . $this->session->userdata('user_log_id') . ' Logged Out.';
        }
        $data = [
            'id' => $this->session->userdata('user_log_id')
        ];
        $user = $this->session->userdata('user');

        $user_id = $user[0]->user_id;
        ########## remove the obsolete entries of  user from user_log table ####
        $this->db->delete('user_log', array('user_id' => $user_id, 'session_expiration_time <= ' => date('Y-m-d H:i:s')));



        $current_date = date('Y-m-d H:i:s');

        $this->db->where('user_id', $user_id);
        $this->db->update('users', array('latest_logout_date' => $current_date));
        $this->User_model->remove_from_user_log($data);

        $this->db->where('user_id', $user_id);
        $updated_user = $this->db->get('users')->row_array();

        $user_display_str = $this->config->item('logout_user_activity_log_displayed_message');
        $user_display_str = str_replace('{user_connection_source_ip}', $_SERVER['REMOTE_ADDR'], $user_display_str);
        $user_display_str = str_replace('{user_valid_connections_online_count}', $updated_user['currently_online'], $user_display_str);
        user_display_log($user_display_str);

		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('user');
		$this->session->unset_userdata('is_logged');
		$this->session->unset_userdata('is_authorized');
        $this->session->sess_destroy();

        if(!empty($this->input->get('isred')) && $this->input->get('isred') == '1'){
			 if(!empty($this->input->get('project_id'))){
				 $check_awaiting_moderation = $this->db->where(['project_id' => $this->input->get('project_id')])->from('projects_awaiting_moderation')->count_all_results();
				 if($check_awaiting_moderation > 0){
					redirect(base_url());
				 }else{
				 
					$project_status_table_array = $this->Projects_model->get_project_status_table_name($this->input->get('project_id'));
					
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') { 
						$get_project_data_data = $this->db // get the user detail
						->select('pd.hidden')
						->from($project_status_table_array['table_name'].' pd')
						->where('pd.fulltime_project_id', $this->input->get('project_id'))
						->get()->row_array();
						

					} else {
						$get_project_data_data = $this->db // get the user detail
						->select('pd.hidden')
						->from($project_status_table_array['table_name'].' pd')
						->where('pd.project_id', $this->input->get('project_id'))
						->get()->row_array();
					}
					if($get_project_data_data['hidden'] == 'Y'){
						redirect(VPATH);
					}else{
						redirect($referer);
					}
				 }
			 }else{
				redirect($referer);
			 }
        } else {
            redirect(VPATH);
        }        
		return;
    }

	/*
	// Description: This function is used for account overview page in account management section. 
	*/
	public function account_management_account_overview() {
	
        if(!$this->session->userdata('user')) {
            redirect(base_url());
        }
        if(check_session_validity()) {
            $user = $this->session->userdata('user');
            $user_id = $user[0]->user_id;
            $data['current_page'] = 'account-management-account-overview';
			 $user_detail = $this->db // get the user detail
			->select('u.user_id, u.profile_name, u.account_type, u.first_name,u.is_authorized_physical_person, u.last_name, u.company_name, u.email,u.gender, ud.user_avatar, ud.current_membership_plan_id, mp.membership_plan_name')
			->from('users u')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id', 'left')
			->where('u.user_id', $user_id)
			->get()->row_array();
			
            $name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];
			$data['user_detail']= $user_detail;
            $account_management_account_overview_page_title_meta_tag = $this->config->item('account_management_account_overview_page_title_meta_tag');
            $account_management_account_overview_page_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_account_overview_page_title_meta_tag);
            $account_management_account_overview_page_description_meta_tag = $this->config->item('account_management_account_overview_page_description_meta_tag');
            $account_management_account_overview_page_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_account_overview_page_description_meta_tag);
            $data['meta_tag'] = '<title>' . $account_management_account_overview_page_title_meta_tag . '</title><meta name="description" content="' . $account_management_account_overview_page_description_meta_tag . '"/>';

          ########## set the profile title tag start end #########
          $this->layout->view ('user_account_management_account_overview', '', $data, 'include');
        }
    }
	
	/*
		// Description: This function is used to update the account management section account overview tab.
	*/
	public function update_user_account_management_account_overview_membership_plan_tab(){
		if($this->input->is_ajax_request ()){
			if ($this->input->post () )
			{
				if(check_session_validity()){ 
					$user = $this->session->userdata('user');
					$user_id = $user[0]->user_id;
					if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}

					$data['current_page'] = 'account-management-account-overview';
					 $user_detail = $this->db // get the user detail
					->select('u.user_id, ud.current_membership_plan_id, mp.membership_plan_name')
					->from('users u')
					->join('users_details ud', 'ud.user_id = u.user_id', 'left')
					->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id', 'left')
					->where('u.user_id', $user_id)
					->get()->row_array();
					$user_plan_name = '';
					$plans_names = $this->config->item('membership_plans_names');
					if(!empty($plans_names) && array_key_exists($user_detail['current_membership_plan_id'], $plans_names)) {
						$user_plan_name = $plans_names[$user_detail['current_membership_plan_id']];
					}
					echo json_encode(['status' => 200,'plan_name'=>$user_plan_name]);
					die;
				}else{
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
				}
			}else{
				show_custom_404_page(); //show custom 404 page
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}	
	
	/*
	// Description: This function is used for user avatar page in account management section. 
	*/
	 public function account_management_avatar() {
	 
        if(!$this->session->userdata('user')) {
            redirect(base_url());
        }
        if(check_session_validity()) {
            $user = $this->session->userdata('user');
            $user_id = $user[0]->user_id;
            $data['current_page'] = 'account-management-avatar';
			
			$user_detail = $this->db // get the user detail
			->select('u.user_id,u.profile_name ,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.is_authorized_physical_person,ud.user_avatar')
			->from('users u')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('u.user_id', $user_id)
			->get()->row_array();
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] = FTP_PORT;
			$config['debug'] = TRUE;
			$this->ftp->connect($config);
			$users_ftp_dir = USERS_FTP_DIR;
			$profile_folder = $user[0]->profile_name;
			$upload_folder = USER_AVATAR;
			$source_path = $users_ftp_dir . $profile_folder . $upload_folder ;
			$check_avatar = $this->ftp->get_filesize($source_path . $user_detail['user_avatar']);
			$user_avatar_exist_status = false;
			if ($check_avatar != '-1') {
				$user_avatar_exist_status = true;
			}else{
			
				if(!empty($this->ftp->check_ftp_directory_exist($source_path . $user_detail['user_avatar']))){
					$this->ftp->delete_dir($source_path . $user_detail['user_avatar']);
				}
				$this->db->update('users_details', array('user_avatar'=>''), ['user_id'=> $user_id]);
				
				// profile completeion script start //	
				$user_profile_completion_data = array();
				$user_profile_completion_data['has_avatar'] = 'N';
				$user_profile_completion_data['avatar_strength_value'] =0;
				if($user_profile_completion_data){
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);

				}
				// profile completeion script end //
			}
			$this->ftp->close();
			
			$data['user_avatar_exist_status'] = $user_avatar_exist_status;
			
            $name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];
			$data['user_detail'] = $user_detail;
			
            $account_management_avatar_page_title_meta_tag = $this->config->item('account_management_avatar_page_title_meta_tag');
            $account_management_avatar_page_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_avatar_page_title_meta_tag);
            $account_management_avatar_page_description_meta_tag = $this->config->item('account_management_avatar_page_description_meta_tag');
            $account_management_avatar_page_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_avatar_page_description_meta_tag);
            $data['meta_tag'] = '<title>' . $account_management_avatar_page_title_meta_tag . '</title><meta name="description" content="' . $account_management_avatar_page_description_meta_tag . '"/>';

          ########## set the profile title tag start end #########
          $this->layout->view ('user_account_management_avatar', '', $data, 'include');
        }
    }
	
	
	/**
    // Description: This function is used to fetch the user avatar.
	*/
    public function fetch_user_avatar_picture() {
		if ($this->input->is_ajax_request()) {
			if (check_session_validity()) {
				$user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$msg['location'] = '';
				$user_detail = $this->db // get the user detail
				->select('u.user_id,ud.user_avatar,u.account_type,u.gender')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user_id)
					->get()->row_array();
				$avatar_file = 	$original_image_name = '';
				if(!empty($user_detail['user_avatar'])){	
					$avatar_file = $user_detail['user_avatar'];
					$ex = explode('.', $user_detail['user_avatar']);
					$original_image_name = $ex[0] . "_original.png";
				}
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] = FTP_PORT;
				$config['debug'] = TRUE;
				$this->ftp->connect($config);

				$users_ftp_dir = USERS_FTP_DIR;
				$profile_folder =$user[0]->profile_name;
				$upload_folder = USER_AVATAR;

				//echo $users_ftp_dir.$profile_folder.$upload_folder.$data['org_image_name'];exit;
				$source_path_original = $users_ftp_dir . $profile_folder . $upload_folder . $original_image_name;
				$source_path_crop = $users_ftp_dir . $profile_folder . $upload_folder . $avatar_file;
				
				$file_size_original = $this->ftp->get_filesize($source_path_original);
				$file_size_crop = $this->ftp->get_filesize($source_path_crop);
				$file_exists = '0';
				if ($file_size_original == '-1' || $file_size_crop == '-1') {
					if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
						if($user_detail['gender'] == 'M'){
							$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
						}if($user_detail['gender'] == 'F'){
						   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
						}
					} else {
						$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
					}
					
					//code for remove the avatar picture from database and disk_free_space
					if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.		$upload_folder))){
						$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$upload_folder);
					}
					$this->db->update('users_details', array('user_avatar'=>''), ['user_id'=> $user_id]);
					
					// update user account profile completion avatar parameter start //
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] = 0;
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
					// update user account profile completion avatar parameter end //
					
					
				} else {
					$file_exists = '1';
					$this->ftp->download($users_ftp_dir . $profile_folder . $upload_folder . $original_image_name, FCPATH . TEMP_DIR . $original_image_name, 'auto', 0777);
					$user_profile_picture = URL . TEMP_DIR . $original_image_name;
				}
				$this->ftp->close();
				$msg['user_profile_picture'] = $user_profile_picture;
				$msg['file_exists'] = $file_exists;
				$msg['status'] = 200;
			} else {
				$msg['status'] = 400;
                $msg['location'] = VPATH;
			}
			echo json_encode($msg);die;
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	/**
    // Description: This function is used to save/update the user avatar into database and disk.
	*/
    public function save_user_avatar_picture() {
        if ($this->input->is_ajax_request()) {
            if (check_session_validity()) {
                $user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$msg['location'] = '';
				
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] = FTP_PORT;
				$config['debug'] = TRUE;
				$this->ftp->connect($config);
				$users_ftp_dir = USERS_FTP_DIR;
				$profile_folder = $user[0]->profile_name;
				$upload_folder = USER_AVATAR;
				
				if(empty($this->ftp->check_ftp_directory_exist($users_ftp_dir))){
					echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
				}
				if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir . $profile_folder.DIRECTORY_SEPARATOR)){
					echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
				}
				
				if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir . $profile_folder . $upload_folder)){
					echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
				}
				
				
				
				$userdata = $this->db->get_where('users_details', array('user_id' => $user_id))->row_array();
				
				if(!empty($userdata) && !empty($userdata['user_avatar'])){
					$old_avatar_file = $userdata['user_avatar'];
					
					if($this->input->post ('type') == 'different'){
						$ex = explode('.', $userdata['user_avatar']);
						$old_avatar_org_file = $ex[0] . '_original.png';
					}
					
					$source_path = $users_ftp_dir . $profile_folder . $upload_folder ;
					$check_avatar = $this->ftp->get_filesize($source_path . $old_avatar_file);
					$check_avatar_org = $this->ftp->get_filesize($source_path . $old_avatar_org_file );
					
					if ($check_avatar != '-1') {
						$this->ftp->delete_file($source_path . $old_avatar_file);
					}
					if ($this->input->post ('type') == 'different' && $check_avatar_org != '-1') {
						$this->ftp->delete_file($source_path . $old_avatar_org_file);
					}
					
					if(!empty($old_avatar_org_file)){
						if( file_exists(FCPATH . TEMP_DIR . $old_avatar_org_file)){							 
						 	 unlink(FCPATH . TEMP_DIR . $old_avatar_org_file);
						}
					}				
				}
				
				
				$newImageBase64string = $this->input->post("image");
				if($this->input->post ('type') == 'different'){
                $originalImageBase64string = $this->input->post("original");
				}
				
				if($this->input->post ('type') == 'different'){
					$tt = "avatar_".round(microtime(true) * 1000);
				}else{
					if(!empty($userdata) && !empty($userdata['user_avatar'])){
						$old_avatar_file = $userdata['user_avatar'];
						$explode_name = explode('.png', $userdata['user_avatar']);
						$ext = explode("_",$explode_name[0]);
						$tt ="avatar_".$ext[1];
					}
				}
               
                $image_name = $tt . '.png';
				if($this->input->post ('type') == 'different'){
                $original_image_name = $tt . "_original.png";
				}
                
                file_put_contents(FCPATH . TEMP_DIR . $image_name, base64_decode(explode(',',$newImageBase64string)[1]));
				
				if($this->input->post ('type') == 'different'){
                file_put_contents(FCPATH . TEMP_DIR . $original_image_name, base64_decode(explode(',',$originalImageBase64string)[1]));
				}
				
				
				
				//	make profile folder
				//$this->ftp->mkdir($users_ftp_dir . $profile_folder, 0777);
				//$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
				
				
				
				//$this->ftp->mkdir($users_ftp_dir . $profile_folder . $upload_folder, 0777);
				
				$this->ftp->upload(FCPATH . TEMP_DIR . $image_name, $users_ftp_dir . $profile_folder . $upload_folder .$image_name, 'auto', 0777);
				
				if($this->input->post ('type') == 'different'){
				$this->ftp->upload(FCPATH . TEMP_DIR . $original_image_name, $users_ftp_dir . $profile_folder . $upload_folder .$original_image_name, 'auto', 0777);
				}
				
				unlink(FCPATH . TEMP_DIR . $image_name);
				if($this->input->post ('type') == 'different'){
				unlink(FCPATH . TEMP_DIR . $original_image_name);
				}else{
					unlink(FCPATH . TEMP_DIR . $tt . "_original.png");
				}
				
				
				$this->db->update('users_details', array('user_avatar' => $image_name), array("user_id" => $user[0]->user_id));
				
				// update user account profile completion avatar parameter start //
				$user_profile_completion_data['has_avatar'] = 'Y';
				if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y') ){
					$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
					$user_profile_completion_data['avatar_strength_value'] = $profile_completion_parameters['avatar_strength_value'];
					
				}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
					$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
					$user_profile_completion_data['avatar_strength_value'] = $profile_completion_parameters['avatar_strength_value'];
					
				}
				$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
				// update user account profile completion avatar parameter end //				
				
                $msg['status'] = 200;
                $msg['avatar_picture'] = $image_name;
            } else {
                $msg['status'] = 400;
                $msg['location'] = VPATH;
            }
			echo json_encode($msg);die;
        } else {
            show_custom_404_page(); //show custom 404 page
        }
    }

	/**
	// Description:This function is used to cancel the user avatar(check into disk and database then perform action).
	*/
	public function cancel_user_avatar_picture() {
		 if ($this->input->is_ajax_request()) {
			if (check_session_validity()) {
				$user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$msg['location'] = '';
				$user_detail = $this->db // get the user detail
				->select('u.user_id,ud.user_avatar,u.account_type,u.gender,u.is_authorized_physical_person')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user_id)
					->get()->row_array();
				$avatar_file = 	$original_image_name = '';
				if(!empty($user_detail['user_avatar'])){	
					$avatar_file = $user_detail['user_avatar'];
					$ex = explode('.', $user_detail['user_avatar']);
					$original_image_name = $ex[0] . "_original.png";
				}
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] = FTP_PORT;
				$config['debug'] = TRUE;
				$this->ftp->connect($config);

				$users_ftp_dir = USERS_FTP_DIR;
				$profile_folder =$user[0]->profile_name;
				$upload_folder = USER_AVATAR;

				//echo $users_ftp_dir.$profile_folder.$upload_folder.$data['org_image_name'];exit;
				$source_path_original = $users_ftp_dir . $profile_folder . $upload_folder . $original_image_name;
				$source_path_crop = $users_ftp_dir . $profile_folder . $upload_folder . $avatar_file;
				
				$file_size_original = $this->ftp->get_filesize($source_path_original);
				$file_size_crop = $this->ftp->get_filesize($source_path_crop);
				$file_exists = '0';
				if ($file_size_original == '-1' || $file_size_crop == '-1') {
					if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')){
						if($user_detail['gender'] == 'M'){
							$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
						}if($user_detail['gender'] == 'F'){
						   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
						}
					} else {
						$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
					}
					
					//code for remove the avatar picture from database and disk_free_space
					if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$upload_folder))){
						$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$upload_folder);
					}
					$this->db->update('users_details', array('user_avatar'=>''), ['user_id'=> $user_id]);
					// update user account profile completion avatar parameter start //
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] = 0;
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
					// update user account profile completion avatar parameter end //
					
					
					
				} else {
					$file_exists = '1';
					$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$profile_folder.USER_AVATAR.$user_detail['user_avatar'];
				}
				$this->ftp->close();
				$msg['user_profile_picture'] = $user_profile_picture;
				$msg['file_exists'] = $file_exists;
				$msg['status'] = 200;
				//echo URL .TEMP_DIR . $original_image_name;
			} else {
				$msg['status'] = 400;
                $msg['location'] = VPATH;
			}
			echo json_encode($msg);die;
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	
	/**
	// Description:This function is used to delete the user avatar from disk and database.
	*/
	public function delete_user_avatar_picture (){
        if ($this->input->is_ajax_request ())
        {
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$msg['location'] = '';
				$user_detail = $this->db // get the user detail
				->select('u.user_id,u.profile_name,u.gender,u.account_type,u.is_authorized_physical_person,ud.user_avatar')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user_id)
				->get()->row_array();
				######## connectivity of remote server start#########
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] 	= FTP_PORT;
				$config['debug']    = TRUE;
				$this->ftp->connect($config); 
				######## connectivity of remote server end #######
				$users_ftp_dir = USERS_FTP_DIR;
				$profile_folder = $user_detail['profile_name'];
				$upload_folder = USER_AVATAR;
				if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.		$upload_folder))){
					$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$upload_folder);
				}
				$this->ftp->close();
				$this->db->update('users_details', array('user_avatar'=>''), ['user_id'=> $user_id]);
				
				
				
				// update user account profile completion avatar parameter start //
				$user_profile_completion_data['has_avatar'] = 'N';
				$user_profile_completion_data['avatar_strength_value'] = 0;
				$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
				// update user account profile completion avatar parameter end //
				
				if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')){
					if($user_detail['gender'] == 'M'){
						$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
					}if($user_detail['gender'] == 'F'){
						$user_profile_picture = URL . 'assets/images/avatar_default_female.png';
					}
				   
				} else {
					$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
					
				}
				
				$msg['data'] = $user_profile_picture;
				$msg['status'] = 200;
				$msg['location'] = '';
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
			}
			echo json_encode($msg);die;
        }else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	/*
	// Description: This function is used for address page in account management section. 
	*/
	public function account_management_address() {
		
		
		if(!$this->session->userdata('user')) {
				redirect(base_url());
		}
		
		if(check_session_validity()) {
			$user = $this->session->userdata('user');
			$user_id = $user[0]->user_id;
			
			$this->User_model->remove_scrambled_user_address_entries($user_id);
			
			$user_detail = $this->db // get the user detail
			->select('u.user_id, u.account_type,u.is_authorized_physical_person, u.first_name, u.last_name, u.company_name, uad.street_address, uad.locality_id, uad.county_id,uad.country_id, uad.postal_code_id, counties.name as county_name, localities.name as locality_name, postal_codes.postal_code,countries.country_name,countries.country_code')
			->from('users u')
			->join('users_address_details uad', 'uad.user_id = u.user_id', 'left')
			->join('counties', 'counties.id = uad.county_id', 'left')
			->join('countries', 'countries.id = uad.country_id', 'left')
			->join('localities', 'localities.id = uad.locality_id', 'left')
			->join('postal_codes', 'postal_codes.id = uad.postal_code_id', 'left')
			->where('u.user_id', $user_id)
			->get()->row_array();
			
			$data['countries'] = $this->Dashboard_model->get_countries(); // drop down options of countries
			$data['is_authorized_physical_person'] = $user_detail['is_authorized_physical_person']; 
			$data['counties'] = $this->Dashboard_model->get_counties(); // drop down options of counties
			$data['address_details'] = '';
			if(!empty($user_detail['country_id'])){
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
						$data['address_details'] .= '<small class="street_address_nospace street_address_view default_black_bold_bigger">'.htmlspecialchars($user_detail['street_address'], ENT_QUOTES).',</small>';
					} else {
						$data['address_details'] .= '<small class="street_address_view default_black_bold_bigger">'.htmlspecialchars($user_detail['street_address'], ENT_QUOTES).',</small>';
					}
				}
				
				if(!empty($localityArr['name']) && !empty($postalCodeArr['postal_code'])){
					$data['address_details'] .= '<small class="default_black_bold_bigger">'.$localityArr['name'].' '.$postalCodeArr['postal_code'].',</small>';
				}
				if(empty($localityArr['name']) && !empty($postalCodeArr['postal_code'])){
					$data['address_details'] .= '<small class="default_black_bold_bigger"> '.$postalCodeArr['postal_code'].',</small>';
				}
				if(!empty($localityArr['name']) && empty($postalCodeArr['postal_code'])){
					$data['address_details'] .= '<small class="default_black_bold_bigger">'.$localityArr['name'].',</small>';
				}
				if(!empty($countyArr['name'])){
					$data['address_details'] .= '<small class="default_black_bold_bigger">'.$countyArr['name'].',</small>';
				}
				$country_flag = ASSETS .'images/countries_flags/'.strtolower($user_detail['country_code']).'.png';
				$data['address_details'] .= '<small class="default_black_bold_bigger">'.$user_detail['country_name'].'<div class="default_user_location_flag" style="background-image: url('.$country_flag.');"></div></small>';	
			}
			
			$name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];
			if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
				$account_management_address_page_title_meta_tag = $this->config->item('pa_account_management_address_details_page_title_meta_tag');
				$account_management_address_page_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_address_page_title_meta_tag);
				$account_management_address_page_description_meta_tag = $this->config->item('pa_account_management_address_details_page_description_meta_tag');
				$account_management_address_page_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_address_page_description_meta_tag);
			} else if($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y') {
				$account_management_address_page_title_meta_tag = $this->config->item('ca_app_account_management_address_details_page_title_meta_tag');
				$account_management_address_page_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_address_page_title_meta_tag);
				$account_management_address_page_description_meta_tag = $this->config->item('ca_app_account_management_address_details_page_description_meta_tag');
				$account_management_address_page_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_address_page_description_meta_tag);
			} else {
				$account_management_address_page_title_meta_tag = $this->config->item('ca_account_management_address_details_page_title_meta_tag');
				$account_management_address_page_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_address_page_title_meta_tag);
				$account_management_address_page_description_meta_tag = $this->config->item('ca_account_management_address_details_page_description_meta_tag');
				$account_management_address_page_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_address_page_description_meta_tag);
			}
			if($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE) {
				$data['branches'] = $this->User_model->get_all_business_account_additional_branches_addresses_by_user_id($user_id);
			}
			
			$data['meta_tag'] = '<title>' . $account_management_address_page_title_meta_tag . '</title><meta name="description" content="' . $account_management_address_page_description_meta_tag . '"/>';
			

			########## set the profile title tag start end #########
			if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) { 
				$data['current_page'] = 'account-management-address';
				$this->layout->view ('user_account_management_address', '', $data, 'include');
			} else {
				$data['current_page'] = 'account-management-company-address';
				$this->layout->view ('user_account_management_company_address', '', $data, 'include');
			}
		}
	}
	
	/*
	// Description: This function is used to save the address details of user
	*/
	public function save_user_account_management_address_details(){

		if ($this->input->is_ajax_request()) {
			$msg['location'] = '';
			if (check_session_validity()) {
				$user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				if($user_id != Cryptor::doDecrypt($post_data['u_id'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$validation_data_array = $this->User_model->user_address_form_validation($post_data);
				if ($validation_data_array['status'] == 'SUCCESS') {
					
					$this->User_model->remove_scrambled_user_address_entries($user_id);
					
					//start check headline
					$msg['status'] = 'SUCCESS';
					$country_id = 0;$county_id = 0;$locality_id = 0;$postal_code_id=0;
					if($this->input->post('address_details_street_address')) {
						$street_address = trim($this->input->post('address_details_street_address'));
					}
					if($this->input->post('address_country_id')) {
						$country_id = $this->input->post('address_country_id');
					}
					if($this->input->post('address_county_id') && $country_id == $this->config->item('reference_country_id')) {
						$county_id  = $this->input->post('address_county_id');
					}
					if($this->input->post('address_locality_id') && $country_id == $this->config->item('reference_country_id')) {
						$locality_id = $this->input->post('address_locality_id');
					}
					if($this->input->post('address_postal_code_id') && $country_id == $this->config->item('reference_country_id')) {
						$postal_code_id = $this->input->post('address_postal_code_id');
					}
					$add_another_location   = $this->input->post('add_another_location');
					if(isset($_GET['branch']) && $_GET['branch'] != 'undefined' && $_GET['branch'] == 1) {
						$add_another_location = 1;
					}
					$extra_location_id  = '';
					if($this->input->post('extra_location_id') && $this->input->post('extra_location_id') != 'undefined' ) {
						$add_another_location = 1;
						$extra_location_id  = $this->input->post('extra_location_id');
						$msg['headquarter_exist'] = $this->db->where(['user_id' => $user_id])->from('users_address_details')->count_all_results();
						if($msg['headquarter_exist'] == 0) {
							echo json_encode($msg);
							return;
						}
					}

						$table = 'users_address_details';
						$where = array("user_id" => $user_id);
						// $arrWhr = array('uad.user_id'=> $user_id);
						if($add_another_location==1) {
							$table = 'users_company_accounts_additional_branches_addresses';
							if($extra_location_id != '') {
								$where = array("id" => $extra_location_id);
							} else {
								$where = array("id" => 0);
							}
						}
						$this->db->select('id');
						$this->db->where($where);
						$query = $this->db->get($table);
						$num = $query->num_rows(); 
						$id = '';
						if ($num>0) {
							if($user[0]->account_type == 2) { 

								if($table == 'users_address_details') {
									$check_headquarter_valid_location = 0;
									$check_branch_valid_location = $this->db->from('users_company_accounts_additional_branches_addresses')->where(array("user_id" => $user_id,'country_id'=>$country_id, 'street_address' => $street_address, 'county_id' => $county_id, 'locality_id' => $locality_id, 'postal_code_id' => $postal_code_id, 'id !=' => $extra_location_id))->count_all_results();
								} else {
									$check_headquarter_valid_location = $this->db->from('users_address_details')->where(array("user_id" => $user_id,'country_id'=>$country_id, 'street_address' => $street_address, 'county_id' => $county_id, 'locality_id' => $locality_id, 'postal_code_id' => $postal_code_id))->count_all_results();
									$check_branch_valid_location = $this->db->from('users_company_accounts_additional_branches_addresses')->where(array("user_id" => $user_id,'country_id'=>$country_id, 'street_address' => $street_address, 'county_id' => $county_id, 'locality_id' => $locality_id, 'postal_code_id' => $postal_code_id, 'id !=' => $extra_location_id))->count_all_results();
								}
								
								$i = 0;
								if($check_headquarter_valid_location == 1 || $check_branch_valid_location == 1) {
									$msg['status'] = 'FAILED';
									$msg['errors'][$i]['id'] = 'duplicate_address';
									$msg['errors'][$i]['message'] = $this->config->item('account_management_address_details_duplicate_location_error_message');
									$i ++;
									echo json_encode($msg);
									return;
								}
							}
							
							$this->db->update($table, array('country_id'=>$country_id,'street_address' => $street_address, 'county_id' => $county_id, 'locality_id' => $locality_id, 'postal_code_id' => $postal_code_id), $where);
							$id = $extra_location_id;
						} else {
							if($user[0]->account_type == 2) { 
								$check_headquarter_valid_location = $this->db->from('users_address_details')->where(array("user_id" => $user_id,'country_id'=>$country_id, 'street_address' => $street_address, 'county_id' => $county_id, 'locality_id' => $locality_id, 'postal_code_id' => $postal_code_id))->count_all_results();
								$check_branch_valid_location = $this->db->from('users_company_accounts_additional_branches_addresses')->where(array("user_id" => $user_id,'country_id'=>$country_id, 'street_address' => $street_address, 'county_id' => $county_id, 'locality_id' => $locality_id, 'postal_code_id' => $postal_code_id))->count_all_results();
								$i = 0;
								if($check_headquarter_valid_location == 1 || $check_branch_valid_location == 1) {
									$msg['status'] = 'FAILED';
									$msg['errors'][$i]['id'] = 'duplicate_address';
									$msg['errors'][$i]['message'] = $this->config->item('account_management_address_details_duplicate_location_error_message');
									$i ++;
									echo json_encode($msg);
									return;
								}
							}

							$this->db->insert($table, array("user_id" => $user_id,'country_id'=>$country_id, 'street_address' => $street_address, 'county_id' => $county_id, 'locality_id' => $locality_id, 'postal_code_id' => $postal_code_id));
							
							//$table == 'users_address_details'
							if($add_another_location==1) {
								$id = $this->db->insert_id();
							}
						}
						$address = $this->db->get_where($table, ['id' => $id])->row_array();
						if(!empty($address)) {
							$country_id = $address['country_id'];
							$county_id = $address['county_id'];
							$locality_id = $address['locality_id'];
							$postal_code_id = $address['postal_code_id'];
						}

						$msg['id'] = $id;
						$msg['val'] = '';
						if($user[0]->account_type == 1) {
							$users_address_details = '<span><i class="fas fa-map-marker-alt"></i></span>';
						} else {
							$users_address_details = '<span><i class="far fa-building"></i></span>';
						}
						if($table == 'users_company_accounts_additional_branches_addresses') {
							$users_address_details = '<span><i class="fas fa-map-marker-alt"></i></span>';
						}

						$this->db->select('country_name,country_code');
						$this->db->where('id', $country_id);
						$qryC = $this->db->get('countries');
						$CountryArr = $qryC->row_array();
						if(!empty($county_id)) {
							$this->db->select('name');
							$this->db->where('id', $county_id);
							$qryC = $this->db->get('counties');
							$countyArr = $qryC->row_array();
							$county = $countyArr['name'];

							$this->db->select('name');
							$this->db->where('county_id', $county_id);
							$this->db->where('id', $locality_id);
							$qryL = $this->db->get('localities');
							$localityArr = $qryL->row_array();
							$locality = $localityArr['name'];

							$this->db->select('postal_code');
							$this->db->where('locality_id', $locality_id);
							$this->db->where('id', $postal_code_id);
							$qryP = $this->db->get('postal_codes');
							$postalCodeArr = $qryP->row_array();
							$postal_code = $postalCodeArr['postal_code'];
						} 
						
						if(!empty($street_address)){
							if(!preg_match('/\s/',$street_address)) {
								$users_address_details .= '<small id="locationRec'.$id.'" class="street_address_nospace default_black_bold_bigger">'.htmlspecialchars($street_address, ENT_QUOTES).',</small>';
							} else {
								$users_address_details .= '<small id="locationRec'.$id.'" class="default_black_bold_bigger">'.htmlspecialchars($street_address, ENT_QUOTES).',</small>';
							}
						}

						if(!empty($localityArr['name']) && !empty($postalCodeArr['postal_code'])){
							$users_address_details .= '<small class="default_black_bold_bigger">'.$localityArr['name'].' '.$postalCodeArr['postal_code'].',</small>';
						}
						if(empty($localityArr['name']) && !empty($postalCodeArr['postal_code'])){
							$users_address_details .= '<small class="default_black_bold_bigger"> '.$postalCodeArr['postal_code'].',</small>';
						}
						if(!empty($localityArr['name']) && empty($postalCodeArr['postal_code'])){
							$users_address_details .= '<small class="default_black_bold_bigger">'.$localityArr['name'].',</small>';
						}
						if(!empty($countyArr['name'])){
							$users_address_details .= '<small class="default_black_bold_bigger">'.$countyArr['name'].',</small>';
						}

						$country_flag = ASSETS .'images/countries_flags/'.strtolower($CountryArr['country_code']).'.png';
						$users_address_details .= '<small class="default_black_bold_bigger">'.$CountryArr['country_name'].'<div class="default_user_location_flag" style="background-image: url('.$country_flag.');"></div></small>';	
						if($add_another_location == 1) {
							if($extra_location_id == '' || $extra_location_id != '') {
								$msg['val'] .= '<div id="location'.$id.'" class="location_topAdjust">';
							}
							$msg['val'] .= '<div class="default_user_location adEdit default_bottom_border " >'.$users_address_details.'</div>';
							$msg['val'] .= '<div class="amBtn amEdDe">';
							$msg['val'] .= '	<button type="button" class="btn default_btn red_btn address_details_remove" id="" data-id='.$id.'>'.$this->config->item('delete_btn_txt').'</button>';
							$msg['val'] .= '	<button type="button" class="btn default_btn green_btn address_details_edit" id="" data-id='.$id.'>'.$this->config->item('edit_btn_txt').'</button>';
							$msg['val'] .= '</div>';
							$msg['val'] .= '</div>';
							if($extra_location_id == '' || $extra_location_id != '') {
								$msg['val'] .= '</div>';
							}
						} else {
							$msg['val'] = $users_address_details;
						}
						
						$users_address_details_data = $this->db // get the user detail
						->select('*')
						->from('users_address_details')
						->where('user_id', $user_id)
						->get()->row_array();
						
						
						// update user account profile completion address parameter start //
						
						if(!empty($users_address_details_data) && !empty($users_address_details_data['country_id']) && $users_address_details_data['country_id'] != 0){
							
							if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
								$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
								
							}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
								$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
							}
							$user_profile_completion_data = array();
							$user_profile_completion_data['has_country_address_indicated'] = 'Y';	
							$user_profile_completion_data['country_address_strength_value'] = $profile_completion_parameters['country_address_strength_value'];
							if($users_address_details_data['country_id'] == $this->config->item('reference_country_id')){
								$user_profile_completion_data['country_is_cz'] = 'Y';
								$user_profile_completion_data['street_address_when_country_not_cz_strength_value'] =0;
								if(!empty($users_address_details_data['street_address'])){
									$user_profile_completion_data['has_street_address_indicated'] = 'Y';
									$user_profile_completion_data['street_address_strength_value'] = $profile_completion_parameters['street_address_strength_value'];
								}else{
									$user_profile_completion_data['has_street_address_indicated'] = 'N';
									$user_profile_completion_data['street_address_strength_value'] = 0;
								}
								if(!empty($users_address_details_data['county_id']) && $users_address_details_data['county_id'] !=0){
									
									$user_profile_completion_data['has_county_address_indicated'] = 'Y';
									$user_profile_completion_data['county_address_strength_value'] = $profile_completion_parameters['county_address_strength_value'];
									
								}else{
									$user_profile_completion_data['has_county_address_indicated'] = 'N';
									$user_profile_completion_data['county_address_strength_value'] = 0;
								
								}
								if(!empty($users_address_details_data['locality_id']) && $users_address_details_data['locality_id'] !=0){
									
									$user_profile_completion_data['has_locality_address_indicated'] = 'Y';
									$user_profile_completion_data['locality_address_strength_value'] = $profile_completion_parameters['locality_address_strength_value'];
								}else{
									$user_profile_completion_data['has_locality_address_indicated'] = 'N';
									$user_profile_completion_data['locality_address_strength_value'] = 0;
								}
								
							}else{
								$user_profile_completion_data['country_is_cz'] = 'N';
								if(!empty($users_address_details_data['street_address'])){
									$user_profile_completion_data['has_street_address_indicated'] = 'Y';
									$user_profile_completion_data['street_address_strength_value'] = 0;
									$user_profile_completion_data['street_address_when_country_not_cz_strength_value'] = $profile_completion_parameters['street_address_when_country_not_cz_strength_value'];
								}else{
									$user_profile_completion_data['has_street_address_indicated'] = 'N';
									$user_profile_completion_data['street_address_strength_value'] = 0;
									$user_profile_completion_data['street_address_when_country_not_cz_strength_value'] = 0;
								}
								$user_profile_completion_data['has_county_address_indicated'] = 'N';
								$user_profile_completion_data['county_address_strength_value'] = 0;
								$user_profile_completion_data['has_locality_address_indicated'] = 'N';
								$user_profile_completion_data['locality_address_strength_value'] = 0;
								
							}
							if(!empty($user_profile_completion_data)){
								$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
							}
						}
						// update user account profile completion address parameter end //
				
				} else{
					echo json_encode ($validation_data_array);
					die;
				}
			} else {
				$msg['status'] = 400;
				$msg['location'] = VPATH;
			}
			echo json_encode($msg);
		} else {
			show_custom_404_page(); //show custom 404 page
		}	
	}
	
	/*
	// Description: This function is used to check the address details of user
	*/
    public function fetch_user_account_management_address_details() {
        if ($this->input->is_ajax_request()) {
            $msg['location'] = '';
            if (check_session_validity()) {
                $user = $this->session->userdata('user');
                $user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$this->User_model->remove_scrambled_user_address_entries($user_id);
                $extra_location_id      = $this->input->post('id');
                $table = 'users_address_details';
                $where = array("user_id" => $user_id);
                if(isset($extra_location_id) && $extra_location_id != '') {
                    $table = 'users_company_accounts_additional_branches_addresses';
					$where = array("id" => $extra_location_id);
					$msg['branch_exist'] = $this->db->where(['user_id' => $user_id, 'id' => $extra_location_id ])->from($table)->count_all_results();
                }
				$msg['headquarter_exist'] = $this->db->where(['user_id' => $user_id])->from('users_address_details')->count_all_results();
                $this->db->select('*');
                $this->db->where($where);
				$user_detail = $this->db->get($table)->row_array();
                $msg['status'] = 200;
                
                $msg['street_address'] = '';
                if($user_detail['street_address']) {
                    $msg['street_address'] = $user_detail['street_address'];
                }
				
                $msg['county_id'] = '';
                if($user_detail['county_id'] != 0) {
                    $msg['county_id'] = $user_detail['county_id'];
                }
                $msg['locality_id'] = '';
                if($user_detail['locality_id'] != 0) {
                    $msg['locality_id'] = $user_detail['locality_id'];
                }
                $msg['postal_code_id'] = '';
                if($user_detail['postal_code_id'] != 0) {
                    $msg['postal_code_id'] = $user_detail['postal_code_id'];
                }
				$msg['country_id'] = '';
				if($user_detail['country_id'] != 0) {
					$msg['country_id'] = $user_detail['country_id'];
				}
                if($user_detail['county_id'] != 0) {
					
                    $this->db->select ('id,name');
                    $this->db->order_by('name', 'ASC');
                    $this->db->where ('county_id', $user_detail['county_id']);
                    $res = $this->db->get ('localities'); 
                    $optionsloc = '';
										$optionsloc .= "<option value=''>".$this->config->item('select_locality')."</option>";
                    foreach ($res->result () as $row) {
                        $selectedLoc = ($row->id == $user_detail['locality_id']) ? 'selected' : '';
                        $optionsloc .= "<option value='".$row->id ."' ".$selectedLoc.">".$row->name."</option>";
                    }
                    $msg['localities'] = $optionsloc;
                    if($user_detail['locality_id'] != 0) {
                        $this->db->select ('id,postal_code');
                        $this->db->order_by('id', 'ASC');
                        $this->db->where ('locality_id', $user_detail['locality_id']);
                        $res = $this->db->get ('postal_codes'); 
					   $optionspc .= "<option value='' >".$this->config->item('select_postal_code')."</option>";
					   $is_options_exist_pc = '0';
                        foreach ($res->result () as $row) {
							$selectedPc = ($row->id == $user_detail['postal_code_id']) ? 'selected' : '';
							if(!empty($row->id) && !empty($row->postal_code)){
								$optionspc .= "<option value='".$row->id ."' ".$selectedPc.">".$row->postal_code."</option>";								
								$is_options_exist_pc = '1';
							}
                        }
						$msg['postal_codes'] = $optionspc;
						$msg['is_options_exist_pc'] = $is_options_exist_pc;
                        $msg['country_id'] = $user_detail['country_id'];
                    } else {
						$optionspc = "<option value='' >".$this->config->item('select_postal_code')."</option>";
						$is_options_exist_pc = '0';
						$msg['postal_codes'] = $optionspc;
						$msg['is_options_exist_pc'] = $is_options_exist_pc;

					}
                } else {
					$optionsloc = "<option value=''>".$this->config->item('select_locality')."</option>";
					$msg['localities'] = $optionsloc;
					$optionspc = "<option value='' >".$this->config->item('select_postal_code')."</option>";
					$is_options_exist_pc = '0';
					$msg['postal_codes'] = $optionspc;
					$msg['is_options_exist_pc'] = $is_options_exist_pc;

				}
				
				// Fetch info for view  start


				$msg['val'] = '';
				if($user[0]->account_type == 1) {
					$users_address_details = '<label class="labelFirst"><i class="fas fa-map-marker-alt"></i></label><label class="labelSecond">';
				} else {
					$users_address_details = '<label class="labelFirst"><i class="far fa-building"></i></label><label class="labelSecond">';
				}
				if($table == 'users_company_accounts_additional_branches_addresses') {
					$users_address_details = '<label class="labelFirst"><i class="fas fa-map-marker-alt"></i></label><label class="labelSecond">';
				}
				
				$country_id = $user_detail['country_id'];
				$county_id = $user_detail['county_id'];
				$postal_code_id = $user_detail['postal_code_id'];
				$locality_id = $user_detail['locality_id'];
				$street_address = $user_detail['street_address'];
				
				$this->db->select('country_name,country_code');
				$this->db->where('id', $country_id);
				$qryC = $this->db->get('countries');
				$CountryArr = $qryC->row_array();
				if(!empty($county_id)) {
					$this->db->select('name');
					$this->db->where('id', $county_id);
					$qryC = $this->db->get('counties');
					$countyArr = $qryC->row_array();
					$county = $countyArr['name'];

					$this->db->select('name');
					$this->db->where('county_id', $county_id);
					$this->db->where('id', $locality_id);
					$qryL = $this->db->get('localities');
					$localityArr = $qryL->row_array();
					$locality = $localityArr['name'];

					$this->db->select('postal_code');
					$this->db->where('locality_id', $locality_id);
					$this->db->where('id', $postal_code_id);
					$qryP = $this->db->get('postal_codes');
					$postalCodeArr = $qryP->row_array();
					$postal_code = $postalCodeArr['postal_code'];
				} 
				
				if(!empty($street_address)){
					if(!preg_match('/\s/',$street_address)) {
						$users_address_details .= '<small id="locationRec'.$id.'" class="street_address_nospace street_address_view default_black_bold_bigger">'.htmlspecialchars($street_address, ENT_QUOTES).',</small>';
					} else {
						$users_address_details .= '<small id="locationRec'.$id.'" class="street_address_view default_black_bold_bigger">'.htmlspecialchars($street_address, ENT_QUOTES).',</small>';
					}
				}

				if(!empty($localityArr['name']) && !empty($postalCodeArr['postal_code'])){
					$users_address_details .= '<small class="default_black_bold_bigger">'.$localityArr['name'].' '.$postalCodeArr['postal_code'].',</small>';
				}
				if(empty($localityArr['name']) && !empty($postalCodeArr['postal_code'])){
					$users_address_details .= '<small class="default_black_bold_bigger"> '.$postalCodeArr['postal_code'].',</small>';
				}
				if(!empty($localityArr['name']) && empty($postalCodeArr['postal_code'])){
					$users_address_details .= '<small class="default_black_bold_bigger">'.$localityArr['name'].',</small>';
				}
				if(!empty($countyArr['name'])){
					$users_address_details .= '<small class="default_black_bold_bigger">'.$countyArr['name'].',</small>';
				}

				$country_flag = ASSETS .'images/countries_flags/'.strtolower($CountryArr['country_code']).'.png';
				$users_address_details .= '<small class="default_black_bold_bigger">'.$CountryArr['country_name'].'<div class="default_user_location_flag" style="background-image: url('.$country_flag.');"></div></small></label><div class="clearfix"></div>';
				$msg['val'] .= '<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="adEdit"><div class="row"><div class="col-md-1 col-sm-1 col-12"><div class="marKer"><i class="fas fa-map-marker-alt"></i></div> </div><div class="col-md-11 col-sm-11 col-12 plN"><div class="locationval">'.$users_address_details.'</label><div class="clearfix"></div></div>  </div></div></div></div><div class="col-md-12 col-sm-12 col-xs-12"><div class="amBtn amEdDe"><button type="button" class="btn btnEdit address_details_edit" id="'.$id.'">Edit</button><button type="button" class="btn btnCancel address_details_remove" id="'.$id.'">Delete</button></div></div>';
				$msg['val'] = $users_address_details;
				// End
				
				// update user account profile completion address parameter start //
				$users_address_details_data = $this->db // get the user detail
				->select('*')
				->from('users_address_details')
				->where('user_id', $user_id)
				->get()->row_array();		
				if(!empty($users_address_details_data) && !empty($users_address_details_data['country_id']) && $users_address_details_data['country_id'] != 0){
					
					if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
						$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
						
					}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
						$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
					}
					
					$user_profile_completion_data['has_country_address_indicated'] = 'Y';	
					$user_profile_completion_data['country_address_strength_value'] = $profile_completion_parameters['country_address_strength_value'];
					if($users_address_details_data['country_id'] == $this->config->item('reference_country_id')){
						$user_profile_completion_data['country_is_cz'] = 'Y';
						$user_profile_completion_data['street_address_when_country_not_cz_strength_value'] =0;
						if(!empty($users_address_details_data['street_address'])){
							$user_profile_completion_data['has_street_address_indicated'] = 'Y';
							$user_profile_completion_data['street_address_strength_value'] = $profile_completion_parameters['street_address_strength_value'];
						}
						if(!empty($users_address_details_data['county_id']) && $users_address_details_data['county_id'] !=0){
							
							$user_profile_completion_data['has_county_address_indicated'] = 'Y';
							$user_profile_completion_data['county_address_strength_value'] = $profile_completion_parameters['county_address_strength_value'];
							
						}
						if(!empty($users_address_details_data['locality_id']) && $users_address_details_data['locality_id'] !=0){
							
							$user_profile_completion_data['has_locality_address_indicated'] = 'Y';
							$user_profile_completion_data['locality_address_strength_value'] = $profile_completion_parameters['locality_address_strength_value'];
						}
						
					}else{
						$user_profile_completion_data['country_is_cz'] = 'N';
						if(!empty($users_address_details_data['street_address'])){
							$user_profile_completion_data['has_street_address_indicated'] = 'Y';
							$user_profile_completion_data['street_address_strength_value'] = 0;
							$user_profile_completion_data['street_address_when_country_not_cz_strength_value'] = $profile_completion_parameters['street_address_when_country_not_cz_strength_value'];
						}
						$user_profile_completion_data['has_county_address_indicated'] = 'N';
						$user_profile_completion_data['county_address_strength_value'] = 0;
						$user_profile_completion_data['has_locality_address_indicated'] = 'N';
						$user_profile_completion_data['locality_address_strength_value'] = 0;
						
					}
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
				}else{
					// update user account profile completion address parameters start //
					$user_profile_completion_data['has_street_address_indicated'] = 'N';
					$user_profile_completion_data['street_address_strength_value'] = 0;
					$user_profile_completion_data['street_address_when_country_not_cz_strength_value'] = 0;
					
					$user_profile_completion_data['has_county_address_indicated'] = 'N';
					$user_profile_completion_data['county_address_strength_value'] = 0;
					
					$user_profile_completion_data['has_locality_address_indicated'] = 'N';
					$user_profile_completion_data['locality_address_strength_value'] = 0;
					
					$user_profile_completion_data['has_country_address_indicated'] = 'N';
					$user_profile_completion_data['country_address_strength_value'] = 0;
					
					$user_profile_completion_data['country_is_cz'] = 'N';
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
					// update user account profile completion address parameters end //
				}
				// update user account profile completion address parameter end //
				
				
            } else {
                $msg['status'] = 400;
                $msg['location'] = VPATH;
            }
            echo json_encode($msg);
        } else {
            show_custom_404_page(); //show custom 404 page
        }
    }
    
	/*
	// Description: This function is used to fetch the locality of selected county
	*/
	public function get_account_management_address_details_localities_selected_county(){
		if($this->input->is_ajax_request () && !empty($this->input->post ('address_county_id'))){
			$address_county_id = $this->input->post ('address_county_id');
			$count_address_locality = $this->db
			->select ('id')
			->from ('counties')
			->get ()->num_rows ();
			$options = "<option value=''>".$this->config->item('select_locality')."</option>";
			$is_options_exist = '0';
			if(!empty($count_address_locality))
			{
				$count_address_locality = $this->db
				->select ('id')
				->from ('localities')
				->where ('county_id', $address_county_id)
				->get ()->num_rows ();

				if($count_address_locality > 0 )
				{
						$this->db->select ('id,name');
						$this->db->order_by('name', 'ASC');
						$this->db->where ('county_id', $address_county_id);
						$res = $this->db->get ('localities'); 
						foreach ($res->result () as $row)
						{
								$options .= "<option value='".$row->id ."'>".$row->name."</option>";
						}
						$is_options_exist = '1';
				}
			}
			$msg['is_options_exist'] = $is_options_exist;
			$msg['address_details_localties_options'] = $options;
			$msg['address_details_localties_postal_code_options'] = '0';
			echo json_encode ($msg);die;
		}else{
				show_custom_404_page(); //show custom 404 page
		}

    }
	
	/*
	// Description: This function is used to fetch the postal codes  of selected locality
	*/
    public function get_account_management_address_details_postal_code_selected_locality(){
        if($this->input->is_ajax_request () && !empty($this->input->post ('address_locality_id'))){
			$address_locality_id = $this->input->post ('address_locality_id');
			$count_address_locality = $this->db
					->select ('id')
					->from ('localities')
					->get ()->num_rows ();
			$options = "<option value=''>".$this->config->item('select_postal_code')."</option>";
			$is_options_exist = '0';
			$number_postal_codes = '0';
			if(!empty($count_address_locality))
			{
				$count_address_postal_codes = $this->db
				->select ('id')
				->from ('postal_codes')
				->where ('locality_id', $address_locality_id)
				->get ()->num_rows ();

				if($count_address_postal_codes > 0 )
				{
						$this->db->select ('id,postal_code');
						$this->db->order_by('id', 'ASC');
						$this->db->where ('locality_id', $address_locality_id);
						$res = $this->db->get ('postal_codes'); 
						foreach ($res->result () as $row)
						{
								if(!empty($row->id) && !empty($row->postal_code)){
									$options .= "<option value='".$row->id ."'>".$row->postal_code."</option>";
									$is_options_exist = '1';
								}
						}
						$number_postal_codes = $count_address_postal_codes;
				}
			}
			$msg['number_postal_codes'] = $number_postal_codes ;
			$msg['is_options_exist'] = $is_options_exist;
			$msg['address_postal_codes_options'] = $options;
			echo json_encode ($msg);die;
        }else{
                show_custom_404_page(); //show custom 404 page
        }
	}

  /**
	* Description: This function is used to delete the address details of user
	*/
	public function delete_user_account_management_address_details() {
		if ($this->input->is_ajax_request()) {
			$msg['location'] = '';
			if (check_session_validity()) {
				$user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				$id = $this->input->post('id');
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$this->User_model->remove_scrambled_user_address_entries($user_id);
				//start check
				$msg['status'] = 200;
				if($id != '' && $id != 0) {
					$this->db->delete('users_company_accounts_additional_branches_addresses', array("id" => $id));
					$this->db->delete('users_company_accounts_opening_hours', ['user_id' => $user_id, 'company_location_id' => $id, 'is_company_headquarter' => 'N']);
				} else {
					$this->db->delete('users_address_details', array("user_id" => $user_id));
					$this->db->delete('users_company_accounts_opening_hours', ['user_id' => $user_id]);
				}

				
				
				// update user account profile completion address parameters start //
				$user_profile_completion_data['has_street_address_indicated'] = 'N';
				$user_profile_completion_data['street_address_strength_value'] = 0;
				$user_profile_completion_data['street_address_when_country_not_cz_strength_value'] = 0;
				
				$user_profile_completion_data['has_county_address_indicated'] = 'N';
				$user_profile_completion_data['county_address_strength_value'] = 0;
				
				$user_profile_completion_data['has_locality_address_indicated'] = 'N';
				$user_profile_completion_data['locality_address_strength_value'] = 0;
				
				$user_profile_completion_data['has_country_address_indicated'] = 'N';
				$user_profile_completion_data['country_address_strength_value'] = 0;
				
				$user_profile_completion_data['country_is_cz'] = 'N';
				$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
				// update user account profile completion address parameters end //
				
				$msg['headquarter_exist'] =  $this->db->where(['user_id' => $user_id])->from('users_address_details')->count_all_results();		
			} else {
				$msg['status'] = 400;
				$msg['location'] = VPATH;
			}
			echo json_encode($msg);
		} else {
				show_custom_404_page(); //show custom 404 page
		}
	}
	
	/*
	// Description: This function is used for user contact details page
	*/
	public function account_management_contact_details() {
		
        if(!$this->session->userdata('user')) {
            redirect(base_url());
        }
        if(check_session_validity()) {
            $user = $this->session->userdata('user');
            $user_id = $user[0]->user_id;
            $data['current_page'] = 'account-management-contact-details';
			
			$user_detail = $this->db // get the user detail
			->select('u.user_id,u.account_type,u.first_name,u.last_name,u.company_name,u.is_authorized_physical_person')
			->from('users u')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('u.user_id', $user_id)
			->get()->row_array();
			
			
			$contact_detail = $this->db // get the user detail
			->select('cd.*')
			->from('users_contact_details cd')
			->where('cd.user_id', $user_id)
			->get()->row_array();
			// profile completeion script start //	
			$user_profile_completion_data = array();
			if(!empty($contact_detail) && empty($contact_detail['skype_id']) && empty($contact_detail['phone_number']) && empty($contact_detail['mobile_phone_number']) && empty($contact_detail['additional_phone_number']) && empty($contact_detail['contact_email']) && empty($contact_detail['website_url'])){
				$this->db->delete('users_contact_details', array("user_id" => $user_detail['user_id']));
				
				
				$user_profile_completion_data = array();
				$user_profile_completion_data['has_phone_or_mobile_number_indicated'] = 'N';
				$user_profile_completion_data['phone_or_mobile_number_strength_value'] =0;
				$user_profile_completion_data['has_contact_email_indicated'] = 'N';
				$user_profile_completion_data['contact_email_strength_value'] =0;
				
				
			}else{
				if(empty($contact_detail['mobile_phone_number']) && empty($contact_detail['phone_number'])){
					$user_profile_completion_data['has_phone_or_mobile_number_indicated'] = 'N';
					$user_profile_completion_data['phone_or_mobile_number_strength_value'] =0;
				}
				if(empty($contact_detail['contact_email'])){
					$user_profile_completion_data['has_contact_email_indicated'] = 'N';
					$user_profile_completion_data['contact_email_strength_value'] =0;
				}
			}
			if(!empty($user_profile_completion_data)){
				$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
			}
			// profile completeion script end //
			$contact_detail = $this->db // get the user detail
			->select('cd.*')
			->from('users_contact_details cd')
			->where('cd.user_id', $user_id)
			->get()->row_array();
			$data['contact_detail'] = $contact_detail;
             $name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];

            $account_management_contact_details_page_title_meta_tag = $this->config->item('account_management_contact_details_page_title_meta_tag');
            $account_management_contact_details_page_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_contact_details_page_title_meta_tag);
            $account_management_contact_details_page_description_meta_tag = $this->config->item('account_management_page_description_meta_tag');
            $account_management_contact_details_page_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_contact_details_page_description_meta_tag);
            $data['meta_tag'] = '<title>' . $account_management_contact_details_page_title_meta_tag . '</title><meta name="description" content="' . $account_management_contact_details_page_description_meta_tag . '"/>';
			
			

          ########## set the profile title tag start end #########
          $this->layout->view ('user_account_management_contact_details', '', $data, 'include');
        }
    }
	
	/*
	This function update the contact details tabs data
	*/
	public function update_account_management_contact_details_tabs(){
		if($this->input->is_ajax_request ()){
			if ($this->input->post () )
			{
				if(check_session_validity()){ 
					$user = $this->session->userdata ('user');
					$tab_type = $this->input->post ('tab_type');
					$user_id = $user[0]->user_id;
					
					if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);die;
						
					}
					
					
					$contact_detail = $this->db // get the user detail
					->select('cd.*')
					->from('users_contact_details cd')
					->where('cd.user_id', $user_id)
					->get()->row_array();
					if(!empty($contact_detail) && empty($contact_detail['skype_id']) && empty($contact_detail['phone_number']) && empty($contact_detail['mobile_phone_number']) && empty($contact_detail['additional_phone_number']) && empty($contact_detail['contact_email']) && empty($contact_detail['website_url'])){
						
						$this->db->delete('users_contact_details', array("user_id" => $user_id));
						// profile completeion script start //	
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_phone_or_mobile_number_indicated'] = 'N';
						$user_profile_completion_data['phone_or_mobile_number_strength_value'] =0;
						$user_profile_completion_data['has_contact_email_indicated'] = 'N';
						$user_profile_completion_data['contact_email_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
							
						}
						// profile completeion script end //
					}
					
					$contact_detail = $this->db // get the user detail
					->select('cd.*')
					->from('users_contact_details cd')
					->where('cd.user_id', $user_id)
					->get()->row_array();
					
					
					$website_url = '';
					$skype_id  = '';
					$contact_email  = '';
					$phone_number  = '';
					$additional_phone_number  = '';
					$mobile_phone_number  = '';
					
					if($tab_type == 'phoneTab'){
						
						if(!empty($contact_detail) && !empty(trim($contact_detail['phone_number']))){
							$phone_number = trim($contact_detail['phone_number']);
						}
						
						$data['phone_number'] = $phone_number;
						echo json_encode(['status' => 200,'result'=>$phone_number,'data'=>$this->load->view('user/user_account_management_contact_details_phone_number',$data, true)]);die;
						
					}
					if($tab_type == 'mobileTab'){
						
						if(!empty($contact_detail) && !empty(trim($contact_detail['mobile_phone_number']))){
							$mobile_phone_number = trim($contact_detail['mobile_phone_number']);
						}
						
						$data['mobile_phone_number'] = $mobile_phone_number;
						echo json_encode(['status' => 200,'result'=>$mobile_phone_number,'data'=>$this->load->view('user/user_account_management_contact_details_mobile_phone_number',$data, true)]);die;
						
					}
					if($tab_type == 'altphTab'){
						
						if(!empty($contact_detail) && !empty(trim($contact_detail['additional_phone_number']))){
							$additional_phone_number = trim($contact_detail['additional_phone_number']);
						}
						
						$data['additional_phone_number'] = $additional_phone_number;
						echo json_encode(['status' => 200,'result'=>$additional_phone_number,'data'=>$this->load->view('user/user_account_management_contact_details_additional_phone_number',$data, true)]);die;
						
					}					
					if($tab_type == 'envelopTab'){
						if(!empty($contact_detail) && !empty(trim($contact_detail['contact_email']))){
							$contact_email = trim($contact_detail['contact_email']);
						}
						
						$data['contact_email'] = $contact_email;
						echo json_encode(['status' => 200,'result'=>$contact_email,'data'=>$this->load->view('user/user_account_management_contact_details_contact_email',$data, true)]);die;
					}
					if($tab_type == 'skypeTab'){
						if(!empty($contact_detail) && !empty(trim($contact_detail['skype_id']))){
							$skype_id = trim($contact_detail['skype_id']);
						}
						
						$data['skype_id'] = $skype_id;
						echo json_encode(['status' => 200,'result'=>$skype_id,'data'=>$this->load->view('user/user_account_management_contact_details_skype_id',$data, true)]);die;
					}
					if($tab_type == 'websiteTab'){
						if(!empty($contact_detail) && !empty(trim($contact_detail['website_url']))){
							$website_url = trim($contact_detail['website_url']);
						}
						$data['website_url'] = $website_url;
						echo json_encode(['status' => 200,'result'=>$website_url,'data'=>$this->load->view('user/user_account_management_contact_details_website_url',$data, true)]);die;
					}
				
				}else{
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
				}
			}else{
				show_custom_404_page(); //show custom 404 page
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	
	}
	
	
	//This function is used to fetch contact details of user from database
	public function get_account_management_contact_details(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				if($user_id != Cryptor::doDecrypt($post_data['uid'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				
				$contact_detail = $this->db // get the user detail
				->select('cd.*')
				->from('users_contact_details cd')
				->where('cd.user_id', $user_id)
				->get()->row_array();
				if(!empty($contact_detail) && empty($contact_detail['skype_id']) && empty($contact_detail['phone_number']) && empty($contact_detail['mobile_phone_number']) && empty($contact_detail['additional_phone_number']) && empty($contact_detail['contact_email']) && empty($contact_detail['website_url'])){
					$this->db->delete('users_contact_details', array("user_id" => $user_id));
					// profile completeion script start //	
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_phone_or_mobile_number_indicated'] = 'N';
					$user_profile_completion_data['phone_or_mobile_number_strength_value'] =0;
					$user_profile_completion_data['has_contact_email_indicated'] = 'N';
					$user_profile_completion_data['contact_email_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
						
					}
					// profile completeion script end //
				}
				$contact_detail = $this->db // get the user detail
				->select('cd.*')
				->from('users_contact_details cd')
				->where('cd.user_id', $user_id)
				->get()->row_array();
				$website_url  = '';
				$skype_id  = '';
				$contact_email  = '';
				$phone_number  = '';
				$mobile_phone_number  = '';
				$additional_phone_number  = '';
				$country_code  = '';
				if(!empty($contact_detail)){
					
					if(!empty($contact_detail['website_url'])){
						$website_url = $contact_detail['website_url'];
					}
					if(!empty($contact_detail['skype_id'])){
						$skype_id = $contact_detail['skype_id'];
					}
					if(!empty($contact_detail['contact_email'])){
						$contact_email = $contact_detail['contact_email'];
					}
					if(!empty($contact_detail['phone_number'])){
						$phone_number = $contact_detail['phone_number'];
					}
					if(!empty($contact_detail['mobile_phone_number'])){
						$mobile_phone_number = $contact_detail['mobile_phone_number'];
					}
					if(!empty($contact_detail['additional_phone_number'])){
						$additional_phone_number = $contact_detail['additional_phone_number'];
					}
					if($post_data['section_name'] == 'phone_number'){
						if(isset($phone_number) && !empty($phone_number)){ 
							$phone_number_array = explode("##",$phone_number);
							if($post_data['action_type'] == 'edit'){
							
								$phone_number =$phone_number_array[2];
							}else{
								$phone_number = $phone_number_array[1]." ".$phone_number_array[2];
							}
							$country_code = $phone_number_array[0].'##'.$phone_number_array[1];
						}
					}
					if($post_data['section_name'] == 'mobile_phone_number'){
						if(isset($mobile_phone_number) && !empty($mobile_phone_number)){ 
							$mobile_phone_number_array = explode("##",$mobile_phone_number);
							if($post_data['action_type'] == 'edit'){
							
								$mobile_phone_number =$mobile_phone_number_array[2];
							}else{
								$mobile_phone_number = $mobile_phone_number_array[1]." ".$mobile_phone_number_array[2];
							}
							$country_code = $mobile_phone_number_array[0].'##'.$mobile_phone_number_array[1];
						}
					}
					if($post_data['section_name'] == 'additional_phone_number'){
						if(isset($additional_phone_number) && !empty($additional_phone_number)){ 
							
							$additional_phone_number_array = explode("##",$additional_phone_number);
							if($post_data['action_type'] == 'edit'){
							
								$additional_phone_number =$additional_phone_number_array[2];
							}else{
								$additional_phone_number = $additional_phone_number_array[1]." ".$additional_phone_number_array[2];
							}
							$country_code = $additional_phone_number_array[0].'##'.$additional_phone_number_array[1];
						}
					}
					
				}
				
				if($post_data['section_name'] == 'phone_number'){
					$data = $phone_number;
				}
				if($post_data['section_name'] == 'mobile_phone_number'){
					$data = $mobile_phone_number;
				}
				if($post_data['section_name'] == 'additional_phone_number'){
					$data = $additional_phone_number;
				}
				if($post_data['section_name'] == 'contact_email'){
					$data = $contact_email;
				}
				if($post_data['section_name'] == 'skype'){
					$data = $skype_id;
				}
				if($post_data['section_name'] == 'website_url'){
					$data = $website_url;
				}
				echo json_encode(['status' => 200,'message'=>'','location'=>'','data'=>$data,'country_code'=>$country_code]);
				die;
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH;
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	//This function is used to save contact details of user into database
	public function save_account_management_contact_details(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				if($user_id != Cryptor::doDecrypt($post_data['uid'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$validation_data_array = $this->User_model->user_contact_details_validation($post_data);
				if ($validation_data_array['status'] == 'SUCCESS')
				{
					$data = '';
					$contact_detail = $this->db // get the user detail
					->select('cd.*')
					->from('users_contact_details cd')
					->where('cd.user_id', $user_id)
					->get()->row_array();
					if($post_data['section_name'] == 'phone_number'){
						$country_code = $post_data['country_code'];
						$phone_number = trim($country_code."##".$post_data['data']);
						$contact_details_data['phone_number'] = $phone_number;
						$contact_details_data['user_id'] = $user_id;
						$phone_number_array = explode("##",$phone_number);
					}
					if($post_data['section_name'] == 'mobile_phone_number'){
						$country_code = $post_data['country_code'];
						$mobile_phone_number = trim($country_code."##".$post_data['data']);
						$contact_details_data['mobile_phone_number'] = $mobile_phone_number;
						$contact_details_data['user_id'] = $user_id;
						$mobile_phone_number_array = explode("##",$mobile_phone_number);
					}
					if($post_data['section_name'] == 'additional_phone_number'){
						$country_code = $post_data['country_code'];
						$additional_phone_number = trim($country_code."##".$post_data['data']);
						$contact_details_data['additional_phone_number'] = $additional_phone_number;
						$contact_details_data['user_id'] = $user_id;
						$additional_phone_number_array = explode("##",$additional_phone_number);
					}
					if($post_data['section_name'] == 'contact_email'){
						$contact_details_data['contact_email'] = trim($post_data['data']);
						$contact_details_data['user_id'] = $user_id;
						//$data = $post_data['data'];
					}
					if($post_data['section_name'] == 'skype'){
						$contact_details_data['skype_id'] = trim($post_data['data']);
						$contact_details_data['user_id'] = $user_id;
						//$data = $post_data['data'];
					}
					if($post_data['section_name'] == 'website_url'){
						
						$res = true;
						$url = trim($post_data['data']);
						if (substr($url, 0, 7) == "http://"){
							$res = false;
							}
						if (substr($url, 0, 8) == "https://"){
							$res = false;
						}
						if($res){
							$new_url = "http://".$url;
						}else{
							$new_url = $url;
						}
						$contact_details_data['website_url'] = $new_url;
						$contact_details_data['user_id'] = $user_id;
						
					}
					$data = $post_data['data'];
					if($post_data['section_name'] == 'phone_number'){
						$data = $phone_number_array[1]." ".$phone_number_array[2];
					}
					if($post_data['section_name'] == 'mobile_phone_number'){
						$data = $mobile_phone_number_array[1]." ".$mobile_phone_number_array[2];
					}
					if($post_data['section_name'] == 'additional_phone_number'){
						$data = $additional_phone_number_array[1]." ".$additional_phone_number_array[2];
					}
					if($post_data['section_name'] == 'website_url'){
						$data = $new_url;
					}
					if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
						$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
						
						
					}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
						$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
						
					}
					if(empty($contact_detail)){
						$this->db->insert ('users_contact_details', $contact_details_data
						);
					}else{
					
						$this->db->update('users_contact_details', $contact_details_data, ['user_id'=> $user_id]);
					}
					
					// update user account profile completion mobile/phone number/conatct email parameter start //
					if($post_data['section_name'] == 'phone_number' || $post_data['section_name'] == 'mobile_phone_number' || $post_data['section_name'] == 'contact_email'){
						
						$user_profile_completion_data = array();
						/* if(($post_data['section_name'] == 'phone_number' || $post_data['section_name'] == 'mobile_phone_number') && (empty($contact_detail) || (empty($contact_detail['phone_number']) && empty($contact_detail['mobile_phone_number'])))){
							$user_profile_completion_data['has_phone_or_mobile_number_indicated'] = 'Y';
							$user_profile_completion_data['phone_or_mobile_number_strength_value'] = $profile_completion_parameters['phone_or_mobile_number_strength_value'];
						}
						if($post_data['section_name'] == 'contact_email' && (empty($contact_detail) || empty($contact_detail['contact_email']))){
							$user_profile_completion_data['has_contact_email_indicated'] = 'Y';
							$user_profile_completion_data['contact_email_strength_value'] = $profile_completion_parameters['contact_email_strength_value'];
						} */
						
						if($post_data['section_name'] == 'phone_number' || $post_data['section_name'] == 'mobile_phone_number'){
							$user_profile_completion_data['has_phone_or_mobile_number_indicated'] = 'Y';
							$user_profile_completion_data['phone_or_mobile_number_strength_value'] = $profile_completion_parameters['phone_or_mobile_number_strength_value'];
						}
						if($post_data['section_name'] == 'contact_email'){
							$user_profile_completion_data['has_contact_email_indicated'] = 'Y';
							$user_profile_completion_data['contact_email_strength_value'] = $profile_completion_parameters['contact_email_strength_value'];
						}
						
						if(!empty($user_profile_completion_data)){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
						}
						
					}
					// update user account profile completion mobile/phone number parameter end //
					
					echo json_encode(['status' => 'SUCCESS','message'=>'','location'=>'','data'=>$data]);
					die;
					
				}else{
					echo json_encode ($validation_data_array);
					die;
				}
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH;
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	//This function is used to remove contact details of user from database
	public function delete_account_management_contact_details(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				if($user_id != Cryptor::doDecrypt($post_data['uid'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				if($post_data['section_name'] == 'contact_email'){
					$contact_details_data['contact_email'] = '';
				}
				if($post_data['section_name'] == 'skype'){
					$contact_details_data['skype_id'] = '';
				}
				if($post_data['section_name'] == 'website_url'){
					$contact_details_data['website_url'] = '';
				}
				if($post_data['section_name'] == 'phone_number'){
					$contact_details_data['phone_number'] = '';
				}
				if($post_data['section_name'] == 'mobile_phone_number'){
					$contact_details_data['mobile_phone_number'] = '';
				}
				if($post_data['section_name'] == 'additional_phone_number'){
					$contact_details_data['additional_phone_number'] = '';
				}
				$this->db->update('users_contact_details', $contact_details_data, ['user_id'=> $user_id]);
					
				$contact_detail = $this->db // get the user detail
				->select('cd.*')
				->from('users_contact_details cd')
				->where('cd.user_id', $user_id)
				->get()->row_array();
				if(!empty($contact_detail) && empty($contact_detail['skype_id']) && empty($contact_detail['phone_number']) && empty($contact_detail['mobile_number']) && empty($contact_detail['additional_phone_number']) && empty($contact_detail['contact_email']) && empty($contact_detail['website_url'])){
					$this->db->delete('users_contact_details', array("user_id" => $user_id));
					// profile completeion script start //	
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_phone_or_mobile_number_indicated'] = 'N';
					$user_profile_completion_data['phone_or_mobile_number_strength_value'] =0;
					$user_profile_completion_data['has_contact_email_indicated'] = 'N';
					$user_profile_completion_data['contact_email_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
						
					}
					// profile completeion script end //
				}
				
				// update user account profile completion mobile/phone number/contact email parameter start //
				if($post_data['section_name'] == 'phone_number' || $post_data['section_name'] == 'mobile_phone_number' || $post_data['section_name'] == 'contact_email'){
					$user_profile_completion_data =array();
					if(($post_data['section_name'] == 'phone_number' || $post_data['section_name'] == 'mobile_phone_number') && (empty($contact_detail['mobile_number']) && empty($contact_detail['phone_number']))){
						$user_profile_completion_data['has_phone_or_mobile_number_indicated'] = 'N';
						$user_profile_completion_data['phone_or_mobile_number_strength_value'] = 0;
					}
					if($post_data['section_name'] == 'contact_email'){ 
						$user_profile_completion_data['has_contact_email_indicated'] = 'N';
						$user_profile_completion_data['contact_email_strength_value'] = 0;
					}
					if(!empty($user_profile_completion_data)){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
					}
				}
				// update user account profile completion mobile/phone number parameter end //
				
				
				echo json_encode(['status' => 200,'message'=>'','location'=>'']);
				die;
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH;
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	
    /**
     * Description: this method show the public profile page of user
     * Paremete: pofile_name and share 
     */
    public function user_profile($profile_name) {

		
        $user = $this->session->userdata('user');
		$user_detail = $this->User_model->get_user_detail_from_profile_name($profile_name);
		
		$user_additional_settings = $this->db->get_where('users_additional_accesses_settings', ['user_id' => $user_detail['user_id']])->row_array();

        if ($this->session->userdata('user')) {
			check_session_validity();
			if($user[0]->user_id != $user_detail['user_id']) {
				if(!empty($user_additional_settings) && $user_additional_settings['user_profile_page_not_accessible'] == 'Y') {
					show_custom_404_page(); //show custom 404 page
					return;
				}
			}
		} else {
			if(!empty($user_additional_settings) && $user_additional_settings['user_profile_page_not_accessible'] == 'Y') {
				show_custom_404_page(); //show custom 404 page
				return;
			}
		}
        if (empty($user_detail)) {
						$this->session->unset_userdata('last_redirect_url');
           // $data['current_page'] = '404_default';
            $lay = array();
            ########## set the default 404 title meta tag and meta description  start here #########
			set_status_header(404);
            $default_404_page_title_meta_tag = $this->config->item('404_page_title_meta_tag');
            $default_404_page_description_meta_tag = $this->config->item('404_page_description_meta_tag');
            $data['meta_tag'] = '<title>' . $default_404_page_title_meta_tag . '</title><meta name="description" content="' . $default_404_page_description_meta_tag . '"/>';
            ########## set the default 404 title meta tag and meta description  end here #########
            $this->layout->view('404defaultpage/404_default', $lay, $data, 'error_404');
        } else {
			$user_profile_completion_data = array();
			################################# reset address for profile page ###
			$this->User_model->remove_scrambled_user_address_entries($user_detail['user_id']);	
			
            $user_detail = $this->db // get the user detail
                ->select('u.user_id, u.account_type,u.is_authorized_physical_person, u.gender, u.first_name, u.last_name, u.company_name, u.email, u.profile_name, u.account_validation_date, u.latest_login_date, ud.current_membership_plan_id, ud.user_avatar,ud.user_total_avg_rating_as_sp, upcpt.profile_cover_picture_name, upbi.headline, upbi.description, upbi.hourly_rate, upbi.mother_tongue_language_id, uad.id as headquarter_id,uad.street_address, c.name as county_name, l.name as locality_name, pc.postal_code,countries.*,ucd.skype_id,ucd.contact_email,ucd.website_url,ucd.additional_phone_number,ucd.mobile_phone_number,ucd.phone_number')
				->select('u.sync_linkedin, u.user_linkedin_associated_email, u.sync_facebook, u.user_facebook_associated_email')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
                ->from('users u')
                ->join('users_details ud', 'ud.user_id = u.user_id', 'left')
                ->join('users_profile_base_information upbi', 'upbi.user_id = u.user_id', 'left')
                ->join('users_contact_details ucd', 'ucd.user_id = u.user_id', 'left')
                ->join('users_profile_cover_picture_tracking upcpt', 'upcpt.user_id = u.user_id', 'left')
                ->join('users_address_details uad', 'uad.user_id = u.user_id', 'left')
                ->join('counties c', 'c.id = uad.county_id', 'left')
                ->join('localities l', 'l.id = uad.locality_id', 'left')
                ->join('countries', 'countries.id = uad.country_id', 'left')
                ->join('postal_codes pc', 'pc.id = uad.postal_code_id', 'left')
                ->where('u.profile_name LIKE ', $profile_name)
                ->get()->row_array();
								
				if($user_detail['account_type']  == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type']  == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person']  == 'Y')){
					$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
				}elseif($user_detail['account_type']  == USER_COMPANY_ACCOUNT_TYPE){
					$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
				}
				if(empty($user_detail['mobile_phone_number']) && empty($user_detail['phone_number'])){
					$user_profile_completion_data['has_phone_or_mobile_number_indicated'] = 'N';
					$user_profile_completion_data['phone_or_mobile_number_strength_value'] =0;
				}
				if(empty($user_detail['contact_email'])){
					$user_profile_completion_data['has_contact_email_indicated'] = 'N';
					$user_profile_completion_data['contact_email_strength_value'] =0;
				}
	
				################################# reset services provides ###
				$this->User_model->remove_scrambled_user_services_provided_entries($user_detail['user_id']);	
				################################# reset scrambles user skills ###
				$this->User_model->remove_scrambled_user_skills_entries($user_detail['user_id']);

				### reset the user certifications/education/experience
				############# reset certifcations########
				$this->User_model->remove_scrambled_user_certifications_entries($user_detail['user_id']);
				if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
					########## reset the work expereince start###
					$this->User_model->remove_scrambled_user_work_experience_entries($user_detail['user_id']);
					########## reset the education training start###
					$this->User_model->remove_scrambled_user_education_training_entries($user_detail['user_id']);	
					$this->User_model->remove_scrambled_user_spoken_languages_entries($user_detail['user_id']);
				} else {
					$data['company_base_information'] = $this->db->get_where('users_company_accounts_base_information', ['user_id' => $user_detail['user_id']])->row_array();
					// $data['company_opening_hours'] = $this->db->get_where('users_company_accounts_opening_hours', ['user_id' => $user_detail['user_id']])->result_array();
				}

				$contact_detail = $this->db // get the user detail
				->select('cd.*')
				->from('users_contact_details cd')
				->where('cd.user_id', $user_detail['user_id'])
				->get()->row_array();
				if(!empty($contact_detail) && empty($contact_detail['skype_id']) && empty($contact_detail['phone_number']) && empty($contact_detail['mobile_phone_number']) && empty($contact_detail['additional_phone_number']) && empty($contact_detail['contact_email']) && empty($contact_detail['website_url'])){
					$this->db->delete('users_contact_details', array("user_id" => $user_detail['user_id']));
					// profile completeion script start //	
					$user_profile_completion_data['has_phone_or_mobile_number_indicated'] = 'N';
					$user_profile_completion_data['phone_or_mobile_number_strength_value'] =0;
					$user_profile_completion_data['has_contact_email_indicated'] = 'N';
					$user_profile_completion_data['contact_email_strength_value'] =0;
					// profile completeion script end //
				}
				//address details
				$data['profile_name'] = $profile_name;
				$data['profile_views_cnt'] = $this->User_model->save_user_profile_pages_visits_and_get_count($user_detail['user_id']);
				$data['profile_followers_cnt'] = $this->db->from('users_favorite_employer_tracking')->where('favorite_employer_id', $user_detail['user_id'])->count_all_results();
				$data['profile_contacts_cnt'] = $this->db->from('users_contacts_tracking')->where(['contact_initiated_by' => $user_detail['user_id'], 'is_blocked' => 'no'])->group_by('contact_requested_to')->count_all_results();
				$data['address_detail_exists'] = false;
				$address_details = '<div class="comLoc default_user_location userLocation_streetAddress">';
				if($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE){
					$address_details .= '<span><i class="far fa-building" aria-hidden="true"></i></span>';
				} else {
					$address_details .= '<span><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span>';
				}
				if(!empty($user_detail['country_name'])){
					$data['address_detail_exists'] = true;
				}
				if(!empty($user_detail['street_address'])){
					if(!preg_match('/\s/',$user_detail['street_address'])) {
						$address_details .= '<small class="street_address_nospace">'.htmlspecialchars($user_detail['street_address'], ENT_QUOTES).',</small>';
					} else {
						$address_details .= '<small>'.htmlspecialchars($user_detail['street_address'], ENT_QUOTES).',</small>';
					}
				}
				
				if(!empty($user_detail['locality_name']) && !empty($user_detail['postal_code'])){
					$address_details .= '<small>'.$user_detail['locality_name'].' '.$user_detail['postal_code'].',</small>';
				}
				if(empty($user_detail['locality_name']) && !empty($user_detail['postal_code'])){
					$address_details .= '<small> '.$user_detail['postal_code'].',</small>';
				}
				if(!empty($user_detail['locality_name']) && empty($user_detail['postal_code'])){
					$address_details .= '<small>'.$user_detail['locality_name'].',</small>';
				}
				if(!empty($user_detail['county_name'])){
					$address_details .= '<small>'.$user_detail['county_name'].',</small>';
				}
				$country_flag = ASSETS .'images/countries_flags/'.strtolower($user_detail['country_code']).'.png';
				$address_details .= '<small>'.$user_detail['country_name'].'<div class="default_user_location_flag" style="background-image: url('.$country_flag.');"></div></small>';
				$head_open_hr_exist = false;
				if($data['address_detail_exists']) {
					$data['company_location_heading'] = ($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_user_profile_page_user_company_location_company_headquarter_heading'):$this->config->item('ca_user_profile_page_user_company_location_company_headquarter_heading');
					$head_opening_hours = $this->db->get_where('users_company_accounts_opening_hours', ['user_id' => $user_detail['user_id'], 'is_company_headquarter' => 'Y'])->result_array();
					if(!empty($head_opening_hours)) {
						$data['company_location_heading'] = ($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_user_profile_page_user_company_location_company_headquarter_opening_hours_heading'):$this->config->item('ca_user_profile_page_user_company_location_company_headquarter_opening_hours_heading');
						$head_open_hr_exist = true;
						$first = $head_opening_hours[0];
						if($first['company_open_hours_status'] != 'selected_hours' || ($first['company_open_hours_status'] == 'selected_hours' && $first['is_selected_hours_checked'] == 'Y')) { 
							$address_details .= '<span class="receive_notification">';
							$address_details .= "	<a class='rcv_notfy_btn show_more_location' style='display:none' data-id='1'>".$this->config->item('ca_user_profile_page_user_company_location_show_more_opening_hours_text')."</a>";
							$address_details .= "	<a class='rcv_notfy_btn show_less_location'  data-id='0'>".$this->config->item('ca_user_profile_page_user_company_location_hide_extra_opening_hours_text')."</a>";
							$address_details .= '</span>';
						} else if(($first['company_open_hours_status'] == 'selected_hours' && $first['is_selected_hours_checked'] == 'N')) {
							$head_open_hr_exist = false;
						}
						

						if($first['company_open_hours_status'] != 'selected_hours') {
							$open_hours_status = '';
							$ohcls = 'othr';
							if($first['company_open_hours_status'] == 'always_opened') {
								$open_hours_status = $this->config->item('ca_user_profile_page_company_open_hours_status_always_opened_label_txt');
							} else if($first['company_open_hours_status'] == 'permanently_closed') {
								$open_hours_status = $this->config->item('ca_user_profile_page_company_open_hours_status_permanently_closed_label_txt');
							} else {
								$open_hours_status = $this->config->item('ca_user_profile_page_company_open_hours_status_by_telephone_appointment_label_txt');
								$ohcls = 'othrtel';
							}
							$address_details .= '<div id="content" class="" >';
							$address_details .= '	<div class="default_black_bold_medium mt-1">'.$open_hours_status.'</div>';
							$address_details .= '</div>';
						} else {
							$weekdays = $this->config->item('calendar_weekdays_long_name');
							$days = array_column($head_opening_hours, 'day');
							$curr_day = date('N');
							$curr_day_idx = array_search($curr_day, $days);
							$opcls = 'cNow';
							$opstatus = $this->config->item('ca_user_profile_page_company_closed_now_label_txt');
							if(isset($curr_day_idx) && is_numeric($curr_day_idx)) {
								$curr_time = strtotime(date('H:i'));
								$st_time = strtotime($head_opening_hours[$curr_day_idx]['company_opening_time']);
								$end_time = strtotime($head_opening_hours[$curr_day_idx]['company_closing_time']);

								if($curr_time >= $st_time && $curr_time <= $end_time) {
									$opcls = 'oNow';
									$opstatus = $this->config->item('ca_user_profile_page_company_opened_now_label_txt');
								} 
							}
							if($first['is_selected_hours_checked'] == 'Y') {
								$address_details .= '<div id="content" class="" >';
								$address_details .= '	<div class="default_black_bold_medium mt-1 default_bottom_border '.$opcls.'">'.$opstatus.'</div>';

								foreach($weekdays as $key => $day) {
									$open_time = $this->config->item('ca_user_profile_page_company_open_hours_status_closed_label_txt');
									if(in_array($key, $days)) {
										$idx = array_search($key, $days);
										$open_time = $head_opening_hours[$idx]['company_opening_time'].' - '.$head_opening_hours[$idx]['company_closing_time'];
									}
									$address_details .= '<div class="staProHr">';
									$address_details .= '	<label>'.$day.'</label>';
									$address_details .= '	<label>'.$open_time.'</label>';
									$address_details .= '	<div class="clearfix"></div>';
									$address_details .= '</div>';
								}
								$address_details .= '</div>';
							}
							
						}
					
					}
				}
				$address_details .= '</div>';
				$data['address_details'] = $address_details;

				if(($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE) ){
					$extra_locations_arr = $this->db // get the user detail
					->select('bael.id, bael.street_address, bael.locality_id, bael.county_id, bael.postal_code_id, c.name as county_name, l.name as locality_name, pc.postal_code, con.country_code, con.country_name as country')
					->from('users_company_accounts_additional_branches_addresses bael')
					->join('countries con', 'con.id = bael.country_id', 'left')
					->join('counties c', 'c.id = bael.county_id', 'left')
					->join('localities l', 'l.id = bael.locality_id', 'left')
					->join('postal_codes pc', 'pc.id = bael.postal_code_id', 'left')
					->where('bael.user_id', $user_detail['user_id'])
					->get()->result_array();
					
					if(count($extra_locations_arr) > 0) {
						$data['company_location_heading'] = ($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_user_profile_page_user_company_location_company_locations_heading'):$this->config->item('ca_user_profile_page_user_company_location_company_locations_heading');	
						if($head_open_hr_exist) {
							$data['company_location_heading'] = ($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_user_profile_page_user_company_location_company_locations_opening_hours_heading'):$this->config->item('ca_user_profile_page_user_company_location_company_locations_opening_hours_heading');	
						}
					}
					
					$remainingLocation = count($extra_locations_arr)-$this->config->item('user_profile_page_maximum_company_locations_show');
					$m = 0;
					foreach($extra_locations_arr as $extra_location) {
						$m++;
						
						if($m == $this->config->item('user_profile_page_maximum_company_locations_show')+1) { 
							$data['address_details'] .= '<input type="hidden" class="moreLocation" value="1"><input type="hidden" class="rLocation" value="'.$remainingLocation.'"><section id="locationDiv" class="chkSameLine" style="display:none">'; 
						} 
						
						$address_details_extra_locations = '';
						if($extra_location['street_address']) {
							if(!preg_match('/\s/',$extra_location['street_address'])) {
								$address_details_extra_locations .= '<small class="street_address_nospace">'.htmlspecialchars($extra_location['street_address'], ENT_QUOTES).',</small>';
							} else {
								$address_details_extra_locations .= '<small class="">'.htmlspecialchars($extra_location['street_address'], ENT_QUOTES).',</small>';
							}
						} 
						$country_flag = ASSETS .'images/countries_flags/'.strtolower($extra_location['country_code']).'.png';
						if(!empty($extra_location['locality_name']) && !empty($extra_location['postal_code'])) {
							$address_details_extra_locations .= '<small>'.$extra_location['locality_name'].' '.$extra_location['postal_code'].',</small><small>'.$extra_location['county_name'].',</small><small>'.$extra_location['country'].'<div class="default_user_location_flag" style="background-image: url(\''.$country_flag.'\');"></div></small>';
						} else if(!empty($extra_location['locality_name']) && empty($extra_location['postal_code'])) {
							$address_details_extra_locations .= '<small>'.$extra_location['locality_name'].',</small><small>'.$extra_location['county_name'].',</small><small>'.$extra_location['country'].'<div class="default_user_location_flag" style="background-image: url(\''.$country_flag.'\');"></div></small>';
						} else if(empty($extra_location['locality_name']) && !empty($extra_location['postal_code'])) {
							$address_details_extra_locations .= '<small>'.$extra_location['postal_code'].',</small><small>'.$extra_location['county_name'].',</small><small>'.$extra_location['country'].'<div class="default_user_location_flag" style="background-image: url(\''.$country_flag.'\');"></div></small>';
						} else if(empty($extra_location['locality_name']) && empty($extra_location['postal_code']) && !empty($extra_location['county_name'])){
							$address_details_extra_locations .= '<small>'.$extra_location['county_name'].',</small><small>'.$extra_location['country'].'<div class="default_user_location_flag" style="background-image: url(\''.$country_flag.'\');"></div></small>';
						} else {
							$address_details_extra_locations .= '<small>'.$extra_location['country'].'<div class="default_user_location_flag" style="background-image: url(\''.$country_flag.'\');"></div></small>';
						}
						
						
						$data['address_details'] .= '<div class="comLoc default_user_location">
						<span><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span>'.$address_details_extra_locations;
						
						if($m == count($extra_locations_arr)) { 
							$data['address_details'] .= '</section>';
						}
						

						$branch_opening_hours = $this->db->get_where('users_company_accounts_opening_hours', ['user_id' => $user_detail['user_id'], 'company_location_id' => $extra_location['id'], 'is_company_headquarter' => 'N'])->result_array();
						if(!empty($branch_opening_hours)) {
							$data['company_location_heading'] = ($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_user_profile_page_user_company_location_company_locations_opening_hours_heading'):$this->config->item('ca_user_profile_page_user_company_location_company_locations_opening_hours_heading');
							
							$address_details = '';
							$first = $branch_opening_hours[0];
							if($first['company_open_hours_status'] != 'selected_hours' || ($first['company_open_hours_status'] == 'selected_hours' && $first['is_selected_hours_checked'] == 'Y')) {
								$address_details .= '<span class="receive_notification">';
								if(!$head_open_hr_exist) {
									$address_details .= "	<a class='rcv_notfy_btn show_more_location' style='display:none' data-id='1'>".$this->config->item('ca_user_profile_page_user_company_location_show_more_opening_hours_text')."</a>";
									$address_details .= "	<a class='rcv_notfy_btn show_less_location'  data-id='0'>".$this->config->item('ca_user_profile_page_user_company_location_hide_extra_opening_hours_text')."</a>";
								} else {
									$address_details .= "	<a class='rcv_notfy_btn show_more_location' data-id='1'>".$this->config->item('ca_user_profile_page_user_company_location_show_more_opening_hours_text')."</a>";
									$address_details .= "	<a class='rcv_notfy_btn show_less_location' style='display:none' data-id='0'>".$this->config->item('ca_user_profile_page_user_company_location_hide_extra_opening_hours_text')."</a>";
								}
								
								$address_details .= '</span>';
							}
							

							if($first['company_open_hours_status'] != 'selected_hours') {
								$open_hours_status = '';
								$ohcls = 'othr';
								if($first['company_open_hours_status'] == 'always_opened') {
									$open_hours_status = $this->config->item('ca_user_profile_page_company_open_hours_status_always_opened_label_txt');
								} else if($first['company_open_hours_status'] == 'permanently_closed') {
									$open_hours_status = $this->config->item('ca_user_profile_page_company_open_hours_status_permanently_closed_label_txt');
								} else {
									$open_hours_status = $this->config->item('ca_user_profile_page_company_open_hours_status_by_telephone_appointment_label_txt');
									$ohcls = 'othrtel';
								}
								if(!$head_open_hr_exist) {
									$address_details .= '<div id="content" class="">';
								} else {
									$address_details .= '<div id="content" class="" style="display:none" >';
								}
								$address_details .= '	<div class="default_black_bold_medium mt-1">'.$open_hours_status.'</div>';
								$address_details .= '</div>';
							} else {
								$weekdays = $this->config->item('calendar_weekdays_long_name');
								$days = array_column($branch_opening_hours, 'day');
								$curr_day = date('N');
								$curr_day_idx = array_search($curr_day, $days);
								$opcls = 'cNow';
								$opstatus = $this->config->item('ca_user_profile_page_company_closed_now_label_txt');
								if(isset($curr_day_idx) && is_numeric($curr_day_idx)) {
									$curr_time = strtotime(date('H:i'));
									$st_time = strtotime($branch_opening_hours[$curr_day_idx]['company_opening_time']);
									$end_time = strtotime($branch_opening_hours[$curr_day_idx]['company_closing_time']);

									if($curr_time >= $st_time && $curr_time <= $end_time) {
										$opcls = 'oNow';
										$opstatus = $this->config->item('ca_user_profile_page_company_opened_now_label_txt');
									} 
								}
								if($first['is_selected_hours_checked'] == 'Y') { 
									if(!$head_open_hr_exist) {
										$address_details .= '<div id="content" class="">';
									} else {
										$address_details .= '<div id="content" class="" style="display:none" >';
									}
									$address_details .= '	<div class="default_black_bold_medium mt-1 default_bottom_border '.$opcls.'">'.$opstatus.'</div>';

									foreach($weekdays as $key => $day) {
										$open_time = $this->config->item('ca_user_profile_page_company_open_hours_status_closed_label_txt');
										if(in_array($key, $days)) {
											$idx = array_search($key, $days);
											$open_time = $branch_opening_hours[$idx]['company_opening_time'].' - '.$branch_opening_hours[$idx]['company_closing_time'];
										}
										$address_details .= '<div class="staProHr">';
										$address_details .= '	<label>'.$day.'</label>';
										$address_details .= '	<label>'.$open_time.'</label>';
										$address_details .= '	<div class="clearfix"></div>';
										$address_details .= '</div>';
									}
									$address_details .= '</div>';
								}
								
							}
							$data['address_details'] .= $address_details;
							if($first['company_open_hours_status'] != 'selected_hours' || ($first['company_open_hours_status'] == 'selected_hours' && $first['is_selected_hours_checked'] == 'Y')) { 
								if(!$head_open_hr_exist) {
									$head_open_hr_exist = true;
								}
							}
							
						}

						$data['address_details'] .= '</div>';
					}
					if(count($extra_locations_arr) >$this->config->item('user_profile_page_maximum_company_locations_show')) {
						
					$data['address_details'] .=	'<div class="showmore_category"><label class="AllLocations catAll" onclick="toogleMore('.$remainingLocation.',\'company_locations\')"><i class="fas fa-angle-down"></i> '.str_replace('{remaining_company_locations}', $remainingLocation, $this->config->item('user_profile_page_show_more_company_locations_text')).'</label></div>';
						
						
					}
				}
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
				$common_source_path = $users_ftp_dir . $profile_folder;

				//start check profile cover from ftp server
				$user_cover_picture = USER_COVER_PICTURE;
				$source_path_cover = $common_source_path . $user_cover_picture;
				$coverlist = $this->ftp->list_files($source_path_cover);
				$cover_pic = $source_path_cover . $user_detail['profile_cover_picture_name'];

				$expl = explode('.', $user_detail['profile_cover_picture_name']);
			   /*  echo $original_profile_cover_picture_name = $source_path_cover . $expl[0] . '_original.png';
				die; */
				if (count($coverlist) > 0) {
					$ccheck = true;
					if (!in_array($cover_pic, $coverlist) && $ccheck) {
						$this->db->delete('users_profile_cover_picture_tracking', array('user_id' => $user_detail['user_id']));
						$this->ftp->delete_dir($source_path_cover);
						$user_detail['profile_cover_picture_name'] = '';
						$ccheck = false;
						} 
				}if (count($coverlist) == 0 && $user_detail['profile_cover_picture_name'] != '') {
					$this->db->delete('users_profile_cover_picture_tracking', array('user_id' => $user_detail['user_id']));
					$user_detail['profile_cover_picture_name'] = '';
				}
				//start checking cover picture   
				$data['user_cover_picture_exist_status'] = false;
				if (!empty($user_detail['profile_cover_picture_name'])) {
					$user_cover_picture = USER_COVER_PICTURE;
					$cover_pic = $common_source_path . $user_cover_picture . $user_detail['profile_cover_picture_name'];
					$file_size = $this->ftp->get_filesize($cover_pic);
					if ($file_size != '-1') {
						$data['user_cover_picture_exist_status'] = true;
					}
				}

				//avatar picture
				//start check avatar from ftp server
				$user_avatar = USER_AVATAR;
				$source_path_avatar = $common_source_path . $user_avatar;
				$avatarlist = $this->ftp->list_files($source_path_avatar);
				$avatar_pic = $source_path_avatar . $user_detail['user_avatar'];

				$exap = explode('.', $user_detail['user_avatar']);
				$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

				if (count($avatarlist) > 0) {
					$acheck = true;
					if (!in_array($avatar_pic, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $user_detail['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$user_detail['user_avatar'] = '';
						$acheck = false;
						
						// profile completeion script start //	
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						// profile completeion script end //
						
					} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $user_detail['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$user_detail['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //	
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						// profile completeion script end //
					}
				} if (count($avatarlist) == 0 && $user_detail['user_avatar'] != '') {
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $user_detail['user_id']));
					$user_detail['user_avatar'] = '';
					// profile completeion script start //	
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] =0;
					// profile completeion script end //
				}
				//start check avatar from ftp server if exists 
				$data['user_profile_picture_exist_status'] = false;
				if (!empty($user_detail['user_avatar'])) {
					$file_size = $this->ftp->get_filesize($avatar_pic);
					if ($file_size != '-1') {
						$data['user_profile_picture_exist_status'] = true;
					}
				}
				//end check avatar
				$this->ftp->close();
				######## connectivity of remote server end #######
				//check base information tab
				$check_language_id = $this->User_model->get_language_name_from_id($user_detail['mother_tongue_language_id']); // language name fetch by id
				
				if ($user_detail['mother_tongue_language_id'] != 0 && empty($check_language_id['language'])) {
					$this->db->update('users_profile_base_information', array('mother_tongue_language_id' => 0), array("user_id" => $user_detail['user_id']));
					$user_detail['mother_tongue_language_id'] = 0;
					// profile completeion script start //	
					$user_profile_completion_data['has_mother_tongue_indicated'] = 'N';
					$user_profile_completion_data['mother_tongue_strength_value'] =0;
					// profile completeion script end //
				}
				
				
				if (!empty($user_detail) && empty($user_detail['headline']) && empty($user_detail['description']) && $user_detail['hourly_rate'] == 0 &&  $user_detail['mother_tongue_language_id'] == 0 ) {
					$this->db->delete('users_profile_base_information', array("user_id" => $user_detail['user_id']));
					$user_detail['mother_tongue_language_id'] = '';
					
					// profile completeion script start //	
					$user_profile_completion_data['has_mother_tongue_indicated'] = 'N';
					$user_profile_completion_data['mother_tongue_strength_value'] =0;
					$user_profile_completion_data['has_headline_indicated'] = 'N';
					$user_profile_completion_data['headline_strength_value'] =0;
					$user_profile_completion_data['has_description_indicated'] = 'N';
					$user_profile_completion_data['description_strength_value'] =0;
					// profile completeion script end //
					
				} 
				//end check base info tab
			   
				//start fetch inserted areas of expertise
				$professional_categories = $this->db // get the user detail
					->select('id, professional_category_id, professional_parent_category_id')
					->from('professionals_areas_of_expertise_listings_tracking')
					->where('user_id', $user_detail['user_id'])
					->get()->result_array();
				$mainArr = array();
				foreach ($professional_categories as $cat) {
					$result = $this->db->get_where('categories_professionals', ['id' => $cat['professional_category_id'], 'parent_id' => $cat['professional_parent_category_id']])->row_array();
					if(empty($result)) {
						$this->db->delete('professionals_areas_of_expertise_listings_tracking', ['id' => $cat['id']]);
						continue;
					} else {
						if($cat['professional_parent_category_id'] != 0) {
							$result = $this->db->get_where('categories_professionals', ['id' => $cat['professional_parent_category_id']])->row_array();
							if(empty($result)) {
								$this->db->delete('professionals_areas_of_expertise_listings_tracking', ['id' => $cat['id']]);
								continue;
							}
						}
					}
					if ($cat['professional_parent_category_id'] == 0) {
						$mainArr[$cat['professional_category_id']][] = $cat['professional_parent_category_id'];
					} else {
						$mainArr[$cat['professional_parent_category_id']][] = $cat['professional_category_id'];
					}
					
				}
				
				$recordArr = array();
				foreach ($mainArr as $key => $arr) {
					$category = $this->db
						->select('id, name')
						->from('categories_professionals')
						->where('id', $key)
						->get()->row_array();
					$recordArr[$category['name']] = $category['name'];
					if($arr[0]!=0) {
						$recordSArr = array();
						foreach ($arr as $scat) {
							$scategory = $this->db
							->select('id, name')
							->from('categories_professionals')
							->where('id', $scat)
							->get()->row_array();
							$recordSArr[] = $scategory['name'];
						}
						asort($recordSArr);
						//$recordSArr = asort($recordSArr);
						$recordArr[$category['name']] = $recordSArr;
					}
				}
				ksort($recordArr);
				
				
				/* $areas_of_expertise = '';
				foreach ($recordArr as $catKey=>$catArr) {
					$areas_of_expertise .= '<p><label class="defaultTag"><label>
						<span class="tagFirst">'.$catKey.'</span>';
						if(is_array($catArr)){
							foreach ($catArr as $scat) {
								$areas_of_expertise .= '<small class="tagSecond">'.$scat.'</small>';
							}
						}
					$areas_of_expertise .= '</label></label></p>';
				} */
				//$data['areas_of_expertise'] = $areas_of_expertise;
				$data['recordArr'] = $recordArr;
				//end fetch inserted areas of expertise
				################## count category start ###########
				$total_catgory_row = array();
				$professional_categories = $this->db // get the user detail
				->select('elt.id, elt.professional_category_id, elt.professional_parent_category_id')
				->from('professionals_areas_of_expertise_listings_tracking elt')
				->where('user_id', $user_detail['user_id'])
				->get()->result_array();
				foreach ($professional_categories as $category) {
					$result = $this->db->get_where('categories_professionals', ['id' => $category['professional_category_id'], 'parent_id' => $category['professional_parent_category_id']])->row_array();
					if ($category['professional_parent_category_id'] == 0) {
						$total_catgory_row[$category['professional_category_id']][] = $category['professional_parent_category_id'];
					} else {
						$total_catgory_row[$category['professional_parent_category_id']][] = $category['professional_category_id'];
					}
				}
				
				################## count category end ###########
				if(count($total_catgory_row) == 0){
					$user_profile_completion_data['has_areas_of_expertise_indicated'] = 'N';
					$user_profile_completion_data['areas_of_expertise_strength_value'] = 0;
					$user_profile_completion_data['number_of_areas_of_expertise_entries'] = 0;
					
				}else{
					$user_profile_completion_data['has_areas_of_expertise_indicated'] = 'Y';
					$user_profile_completion_data['areas_of_expertise_strength_value'] = $profile_completion_parameters['areas_of_expertise_strength_value'];
					$user_profile_completion_data['number_of_areas_of_expertise_entries'] = count($total_catgory_row);
				}
				// profile completion script end //
				$data['user_detail'] = $user_detail;
				// Fetch language name for user profile  (spoken language)
				$data['language_name'] = $check_language_id;
				//check same user on their page
				$data['own_profile'] = 0;
				if ($user_detail['user_id'] == $user[0]->user_id) {
					$data['own_profile'] = 1;
				}
				############ Statistics as Service Provider  ################
				// number of won projects of sp (fixed/hourly)
				 $get_sp_won_projects_count = $this->Bidding_model->get_sp_won_projects_count($user_detail['user_id']);
				  $data['sp_won_projects_count'] = $get_sp_won_projects_count;
				  
				 // number of in progress projects of sp (fixed/hourly) 
				 $get_sp_in_progress_projects_count = $this->Bidding_model->get_sp_in_progress_projects_count($user_detail['user_id']);
				$data['sp_in_progress_projects_count'] = $get_sp_in_progress_projects_count;
				
				// number of completed projects via portal of sp (fixed/hourly) 
				$data['sp_completed_projects_count_via_portal'] = $this->Bidding_model->get_sp_completed_projects_count(array('winner_id'=>$user_detail['user_id'],'project_completion_method'=>'via_portal'));
				
				// number of completed projects of sp (fixed/hourly  via portal/ out side portal) 
				$data['sp_completed_projects_count'] = $this->Bidding_model->get_sp_completed_projects_count(array('winner_id'=>$user_detail['user_id']));
				
				// number of fulltime projects on which sp working 
				$sp_hires_fulltime_projects_count = $this->Bidding_model->get_sp_hires_fulltime_projects_count($user_detail['user_id']);
				$data['sp_hires_fulltime_projects_count'] = $sp_hires_fulltime_projects_count;
				############### Statistics as Project Owner ###########
				// number of published projects of po (fixed/hourly)
				$po_published_projects = $this->Projects_model->get_po_published_projects_count($user_detail['user_id']);
				$data['po_published_projects'] = $po_published_projects;
				
				// number of published fulltime projects of po (fulltime)
				$po_published_fulltime_projects_count = $this->Projects_model->get_po_published_fulltime_projects_count($user_detail['user_id']);
				$data['po_published_fulltime_projects_count'] = $po_published_fulltime_projects_count;
				
				// sum of published(fixed/hourly project)+published(fulltime project)
				$data['po_total_posted_projects'] = $po_published_projects+$po_published_fulltime_projects_count;
				
				// number of in progress projects of po (fixed/hourly)
				$po_in_progress_projects_count = $this->Projects_model->get_po_in_progress_projects_count($user_detail['user_id']);
				$data['po_in_progress_projects_count'] = $po_in_progress_projects_count;
				
				// number of completed projects of po (fixed/hourly outside portal/via portal)
				$po_completed_projects_count = $this->Projects_model->get_po_completed_projects_count(array('project_owner_id'=>$user_detail['user_id']));
				$data['po_completed_projects_count'] = $po_completed_projects_count;
				
				// number of completed projects of po (via portal)
				$po_completed_projects_count_via_portal = $this->Bidding_model->get_sp_completed_projects_count(array('project_owner_id'=>$user_detail['user_id'],'project_completion_method'=>'via_portal'));
				$data['po_completed_projects_count_via_portal'] = $po_completed_projects_count_via_portal;
				
				// number of hires sp by po for fulltime projects
				$get_po_hires_sp_on_fulltime_projects_count = $this->Projects_model->get_po_hires_sp_on_fulltime_projects_count($user_detail['user_id']);
				$data['get_po_hires_sp_on_fulltime_projects_count'] = $get_po_hires_sp_on_fulltime_projects_count;
				
				$data['current_page'] = 'user_profile';
				$name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];
				
				
				
				
				if($this->session->userdata('user')) {
					$contact_data = $this->db->get_where('users_contacts_tracking', ['contact_initiated_by' => $user[0]->user_id, 'contact_requested_to' => $user_detail['user_id'] ])->row_array();
					if(!empty($contact_data)) {
						$data['is_in_contact'] = true;
					}
				}
				
				if(($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')){
					############ get education for personal account ##########
					$data['user_education_training_data'] = $this->User_model->get_user_profile_page_education_training_listing(array('user_id'=>$user_detail['user_id']));
					$data['user_work_experience_data'] = $this->User_model->get_user_profile_page_work_experience_listing(array('user_id'=>$user_detail['user_id']));
					$data['user_spoken_languages_data'] = $this->User_model->get_user_profile_page_spoken_languages_listing(array('user_id'=>$user_detail['user_id']));
					
				}
				############ get certifications for personal account/company account start ##########
				$this->User_model->remove_user_orphan_certificates_attachments(array('user_id'=>$user_detail['user_id'],'profile_name'=>$user_detail['profile_name'])); // remove orphan entries of portfolio images
				$data['user_certifications_data'] = $this->User_model->get_user_profile_page_certifications_listing(array('user_id'=>$user_detail['user_id']));
				
				############ get user skills##########
				$data['user_skills_data'] = $this->User_model->get_user_profile_page_skills_listing(array('user_id'=>$user_detail['user_id']));
				############ get user services##########
				$data['user_services_data'] = $this->User_model->get_user_profile_page_services_provided_listing(array('user_id'=>$user_detail['user_id']));
				
				
				########## set the profile title meta tag and meta description  start here #########
				$user_profile_title_meta_tag = $this->config->item('user_profile_page_title_meta_tag');
				$user_profile_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $user_profile_title_meta_tag);
				if(empty($user_detail['description'])){ 
					$user_profile_description_meta_tag = $this->config->item('user_profile_page_default_description_meta_tag');
					 $user_profile_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $user_profile_description_meta_tag);
				}else{
					
					$user_profile_description_meta_tag = get_correct_string_based_on_limit(htmlspecialchars($user_detail['description'], ENT_QUOTES),$this->config->item('user_profile_page_description_meta_tag_character_limit'));
				}	

				$_SESSION['share_title_short'] = $user_profile_title_meta_tag;
				$_SESSION['share_description'] = get_correct_string_based_on_limit(htmlspecialchars($user_detail['description'], ENT_QUOTES), $this->config->item('facebook_and_linkedin_share_user_profile_description_character_limit'));
				$_SESSION['share_url'] = base_url($user_detail['profile_name']);

				$_SESSION['share_image'] = URL.$this->config->item('facebook_share_image_path').'?'.time();
				$_SESSION['share_image_height'] = $this->config->item('facebook_share_image_height');
				$_SESSION['share_image_width'] = $this->config->item('facebook_share_image_width');
				$data['meta_tag'] = '<title>' . $user_profile_title_meta_tag . '</title><meta name="description" content="' . $user_profile_description_meta_tag . '"/>';
				
				
				
				
				########## set the profile title tag start end #########
				$lay = array();
				if(!empty($user_profile_completion_data)){
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$user_detail['user_id'],'account_type'=>$user_detail['account_type']));
					
				}

				if($this->session->userdata('user')) {
					$user = $this->session->userdata('user');
					$data['fb_share_url'] = base_url($user_detail['profile_name']).'?rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_profile_share_fb']));
					$data['ln_share_url'] = base_url($user_detail['profile_name']).'?rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_profile_share_ln']));
					$data['twitter_share_url'] = base_url($user_detail['profile_name']).'?rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_profile_share_twitter']));
					$data['email_share_url'] = base_url($user_detail['profile_name']).'?rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_profile_share_email']));
				} else {
					$data['fb_share_url'] = base_url($user_detail['profile_name']);
					$data['ln_share_url'] = base_url($user_detail['profile_name']);
					$data['twitter_share_url'] = base_url($user_detail['profile_name']);
					$data['email_share_url'] = base_url($user_detail['profile_name']);
				}

				if(!empty($_GET["rfrd"])) {
					$cookie= array(
					'name'   => 'referral_code',
					'value'  => $_GET["rfrd"],
					'expire' => '3600',
					'path' => '/',
					'httponly' => false
					);
					$this->input->set_cookie($cookie);
				}
				
				if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
					$this->layout->view('user/personal_account_user_profile_page', $lay, $data, 'normal');
				}else{
					$this->layout->view('user/ca_user_profile_page', $lay, $data, 'normal');
				}
			
        }
    }
   
  /**
	* Description: This used for save/update the user profile cover picture .
	*/
    public function save_user_profile_cover_picture() {

        if ($this->input->is_ajax_request()) {
			
            $msg['location'] = '';
            if (check_session_validity()) {
                $user = $this->session->userdata('user');
				$user_id = Cryptor::doDecrypt($this->input->post ('uid'));
				if($user_id != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				 $user_detail = $this->db // get the user detail
				->select('u.user_id,u.profile_name,ud.current_membership_plan_id')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user[0]->user_id)
				->get()->row_array();
				if ($user_detail['current_membership_plan_id'] != 4) {
                    $this->User_model->delete_user_profile_cover_picture_record($user[0]->user_id);
					 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('user_profile_upload_cover_picture_not_allowed')]);
					die;
				}

                if ($user_detail['current_membership_plan_id'] == 4) {
					$user_cover_picture_data = $this->db->get_where('users_profile_cover_picture_tracking', array('user_id' => $user[0]->user_id))->row_array();
					
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] = FTP_PORT;
					$config['debug'] = TRUE;
					$this->ftp->connect($config);
					$users_ftp_dir = USERS_FTP_DIR;
					$profile_folder = $user_detail['profile_name'];
					$user_cover_picture_dir  = USER_COVER_PICTURE;
				   ##############Delete the old cover picture of user start ####
				
					if(!empty($user_cover_picture_data) && !empty($user_cover_picture_data['profile_cover_picture_name'])){
						if( file_exists(FCPATH . TEMP_DIR . $old_org_file)){
							unlink(FCPATH . TEMP_DIR . $old_org_file);
						}
						$this->db->delete('users_profile_cover_picture_tracking', array("user_id" => $user[0]->user_id));
					}
				
				  ##############Delete the old cover picture of user end ####
				
					if(!empty($_FILES)){
						foreach($_FILES['files']['name'] as $key => $val) {
						$temp 		= 	explode(".", $val);
						$extension 	= 	end($temp);
						$attachment_name = round(microtime(true) * 1000);
						$image_name = $attachment_name.'.'.$extension;
						
						if(move_uploaded_file($_FILES['files']['tmp_name'][$key], TEMP_DIR.$image_name)){
						
							/* $this->ftp->mkdir($users_ftp_dir . $profile_folder, 0777);
							$this->ftp->mkdir($users_ftp_dir.$profile_folder.$user_cover_picture_dir, 0777); */
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir . $profile_folder.DIRECTORY_SEPARATOR);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$user_cover_picture_dir);
							
							$this->ftp->upload(FCPATH . TEMP_DIR . $image_name, $users_ftp_dir.$profile_folder.$user_cover_picture_dir .$image_name, 'auto', 0777);
							unlink(FCPATH . TEMP_DIR . $image_name);
						}
					
					}
					
					$this->db->insert('users_profile_cover_picture_tracking', array('user_id'=>$user[0]->user_id,'profile_cover_picture_name'=>$image_name,'profile_cover_picture_upload_date'=>date('Y-m-d H:i:s')));
					}
					
					$msg['status'] = 200;
					$msg['cover_picture'] = $image_name;
				
                } 
                echo json_encode($msg);
                die;
            } else {
                $msg['status'] = 400;
                $msg['location'] = VPATH.$this->input->post ('profile_name');
                echo json_encode($msg);
                die;
            }
        } else {
            show_custom_404_page(); //show custom 404 page
        }
    }
	
	
	/**
	* Description: This used for cancel the opreation regarding user profile cover picture .
	*/
	public function cancel_user_profile_cover_picture() {
		 if ($this->input->is_ajax_request()) {
			if (check_session_validity()) {
				$user = $this->session->userdata('user');
				$user_id = Cryptor::doDecrypt($this->input->post ('uid'));
				if($user_id != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$user_detail = $this->db // get the user detail
				->select('u.user_id,u.profile_name,ud.current_membership_plan_id')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user[0]->user_id)
				->get()->row_array();
                /* if (empty($user_detail)) {
                    $msg['status'] = 400;
                    $msg['error'] = $this->config->item('refresh_page_validation_message');
                    echo json_encode($msg);
                    die;
                } */
				if ($user_detail['current_membership_plan_id'] != 4) {
				   $this->User_model->delete_user_profile_cover_picture_record($user[0]->user_id);
					 echo json_encode(['status' => 401,'location'=>'']);
					die;
				}
				
				$msg['location'] = '';
				
				$user_cover_picture_data = $this->db->get_where('users_profile_cover_picture_tracking', array('user_id' => $user[0]->user_id))->row_array();
				
				$cover_file = 	'';
				//$original_cover_name = '';
				if(!empty($user_cover_picture_data['profile_cover_picture_name'])){	
					$cover_file = $user_cover_picture_data['profile_cover_picture_name'];
					/* $ex = explode('.', $user_cover_picture_data['profile_cover_picture_name']);
					$original_cover_name = $ex[0] . "_original.png"; */
				}
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] = FTP_PORT;
				$config['debug'] = TRUE;
				$this->ftp->connect($config);

				$users_ftp_dir = USERS_FTP_DIR;
				$profile_folder = $user_detail['profile_name'];
				$user_cover_picture_dir  = USER_COVER_PICTURE;
				
				$source_path = $users_ftp_dir.$profile_folder.$user_cover_picture_dir ;
				
				//echo $users_ftp_dir.$profile_folder.$upload_folder.$data['org_image_name'];exit;
				//$source_path_original = $source_path . $original_cover_name;
				//$source_path_crop = $source_path . $cover_file;
				
				//$file_size = $this->ftp->get_filesize($source_path_original);
				$file_size = $this->ftp->get_filesize($source_path . $cover_file);
				$file_exists = '0';
				if ($file_size == '-1') {
					$this->db->delete('users_profile_cover_picture_tracking', array('user_id'=>$user[0]->user_id));
					if(!empty($this->ftp->check_ftp_directory_exist($source_path))){
						$this->ftp->delete_dir($source_path);
					}
					
				} else {
					$file_exists = '1';
					
					$user_cover_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.$users_ftp_dir.$profile_folder.$user_cover_picture_dir.$cover_file;
				}
				$this->ftp->close();
				$msg['user_cover_picture'] = $user_cover_picture;
				$msg['file_exists'] = $file_exists;
				$msg['status'] = 200;
				//echo URL .TEMP_DIR . $original_image_name;
			} else {
				$msg['status'] = 400;
               $msg['location'] = VPATH.$this->input->post ('profile_name');
			}
			echo json_encode($msg);die;
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	/**
	* Description: This used for delete the user profile cover picture from disk and database.
	*/
	public function delete_user_profile_cover_picture () {
        if ($this->input->is_ajax_request ())
        {
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$user_id = Cryptor::doDecrypt($this->input->post ('uid'));
				if($user_id != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$user_detail = $this->db // get the user detail
				->select('u.user_id,u.profile_name,ud.current_membership_plan_id')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user[0]->user_id)
				->get()->row_array();
                /* if (empty($user_detail)) {
                    $msg['status'] = 400;
                    $msg['error'] = $this->config->item('refresh_page_validation_message');
                    echo json_encode($msg);
                    die;
                } */
				
				$msg['location'] = '';
				$user_cover_picture_data = $this->db->get_where('users_profile_cover_picture_tracking', array('user_id' => $user[0]->user_id))->row_array();
				
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] = FTP_PORT;
				$config['debug'] = TRUE;
				$this->ftp->connect($config);

				$users_ftp_dir = USERS_FTP_DIR;
				$profile_folder = $user_detail['profile_name'];
				$user_cover_picture_dir  = USER_COVER_PICTURE;
				$source_path = $users_ftp_dir.$profile_folder.$user_cover_picture_dir;
				
				
				$this->db->delete('users_profile_cover_picture_tracking', array('user_id'=>$user[0]->user_id));
				if(!empty($this->ftp->check_ftp_directory_exist($source_path))){
					$this->ftp->delete_dir($source_path);
				}
				
				if ($user_detail['current_membership_plan_id'] != 4) {
				    $this->User_model->delete_user_profile_cover_picture_record($user[0]->user_id);
					echo json_encode(['status' => 401,'location'=>'']);
					die;
					
				}
				
				
				$msg['status'] = 200;
				$msg['location'] = '';
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->input->post ('profile_name');
			}
			echo json_encode($msg);die;
        }else{
			show_custom_404_page(); //show custom 404 page
		}
  }
	
	
	//This function is used to update user registartion email
	public function update_account_management_login_email(){
	
		
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				if($user_id != Cryptor::doDecrypt($post_data['uid'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$validation_data_array = $this->User_model->user_update_login_email_validation($post_data);
				if ($validation_data_array['status'] == 'SUCCESS')
				{
					$account_email = '';
					if($post_data['update_email_step'] == '2'){
						$check_user_email_record = $this->db // get the user detail
						->select('u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.email')
						->from('users u')
						->where('u.user_id', $user_id)
						->get()->row_array();
						
						$account_email_array = explode("@",$post_data['new_email']);
						$account_email = replace_characters_asterisks_except_first_last($account_email_array[0])."@".replace_characters_asterisks_except_first_last($account_email_array[1]);
						
						$old_to = $check_user_email_record['email'];
						$to = $post_data['new_email'];
						
						$this->db->update('users', array('email'=>$post_data['new_email']), ['user_id'=> $user_id]);
						$update_email_user_activity_log_displayed_message = $this->config->item('account_management_update_email_user_activity_log_displayed_message');
						user_display_log($update_email_user_activity_log_displayed_message,$user_id); //activity log
						
						$update_email_time = date(DATE_TIME_FORMAT_EXCLUDE_SECOND,time());
						
						$ip = $_SERVER["REMOTE_ADDR"];
						#### email code start here ####
						$name = (($check_user_email_record['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($check_user_email_record['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $check_user_email_record['is_authorized_physical_person'] == 'Y')) ? $check_user_email_record['first_name']." ".$check_user_email_record['last_name'] : $check_user_email_record['company_name'];
						
						if(($check_user_email_record['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($check_user_email_record['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $check_user_email_record['is_authorized_physical_person'] == 'Y'))
						{
							if($check_user_email_record['gender'] == 'M')
							{
							
								if($check_user_email_record['is_authorized_physical_person'] == 'Y'){
									$cc = $this->config->item('account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_cc');
									$bcc = $this->config->item('account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_bcc');
									$from = $this->config->item('account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_from');
									$reply_to = $this->config->item('account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_reply_to');
									$from_name = $this->config->item('account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_from_name');
									$subject = $this->config->item('account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_subject');
									$subject =  str_replace(array('{user_first_name_last_name}'),array($name),$subject);
									$message = $this->config->item('account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_new_email_address_message');
									$message = str_replace(array('{user_first_name_last_name}','{new_email}','{ip}','{update_email_time}'),array($name,$post_data['new_email'],$ip,$update_email_time),$message);
									
									###
									$old_cc = $this->config->item('account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_cc');
									$old_bcc = $this->config->item('account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_bcc');
									$old_from = $this->config->item('account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_from');
									$old_reply_to = $this->config->item('account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_reply_to');
									$old_from_name = $this->config->item('account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_from_name');
									$old_subject = $this->config->item('account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_subject');
									$old_subject =  str_replace(array('{user_first_name_last_name}'),array($name),$old_subject);
									$old_message = $this->config->item('account_management_personal_account_company_app_male_user_update_email_section_email_sent_to_old_email_address_message');
									$old_message = str_replace(array('{user_first_name_last_name}','{update_email_time}','{ip}'),array($name,$update_email_time,$ip),$old_message);

								}else{
									$cc = $this->config->item('account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_cc');
									$bcc = $this->config->item('account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_bcc');
									$from = $this->config->item('account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_from');
									$reply_to = $this->config->item('account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_reply_to');
									$from_name = $this->config->item('account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_from_name');
									$subject = $this->config->item('account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_subject');
									$subject =  str_replace(array('{user_first_name_last_name}'),array($name),$subject);
									$message = $this->config->item('account_management_personal_account_male_user_update_email_section_email_sent_to_new_email_address_message');
									$message = str_replace(array('{user_first_name_last_name}','{new_email}','{ip}','{update_email_time}'),array($name,$post_data['new_email'],$ip,$update_email_time),$message);
									
									###
									$old_cc = $this->config->item('account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_cc');
									$old_bcc = $this->config->item('account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_bcc');
									$old_from = $this->config->item('account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_from');
									$old_reply_to = $this->config->item('account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_reply_to');
									$old_from_name = $this->config->item('account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_from_name');
									$old_subject = $this->config->item('account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_subject');
									$old_subject =  str_replace(array('{user_first_name_last_name}'),array($name),$old_subject);
									$old_message = $this->config->item('account_management_personal_account_male_user_update_email_section_email_sent_to_old_email_address_message');
									$old_message = str_replace(array('{user_first_name_last_name}','{update_email_time}','{ip}'),array($name,$update_email_time,$ip),$old_message);
								}
							
							}else{
							
								if($check_user_email_record['is_authorized_physical_person'] == 'Y'){
								
									$cc = $this->config->item('account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_cc');
									$bcc = $this->config->item('account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_bcc');
									$from = $this->config->item('account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_from');
									$reply_to = $this->config->item('account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_reply_to');
									$from_name = $this->config->item('account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_from_name');
									$subject = $this->config->item('account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_subject');
									$subject =  str_replace(array('{user_first_name_last_name}'),array($name),$subject);
									$message = $this->config->item('account_management_personal_account_company_app_female_user_update_email_section_email_sent_to_new_email_address_message');
									$message = str_replace(array('{user_first_name_last_name}','{new_email}','{ip}','{update_email_time}'),array($name,$post_data['new_email'],$ip,$update_email_time),$message);
									
									###
									$old_cc = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_cc');
									$old_bcc = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_bcc');
									$old_from = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_from');
									$old_reply_to = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_reply_to');
									$old_from_name = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_from_name');
									$old_subject = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_subject');
									$old_subject =  str_replace(array('{user_first_name_last_name}'),array($name),$old_subject);
									$old_message = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_message');
									$old_message = str_replace(array('{user_first_name_last_name}','{update_email_time}','{ip}'),array($name,$update_email_time,$ip),$old_message); 

								
								
							
								}else{
									$cc = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_cc');
									$bcc = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_bcc');
									$from = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_from');
									$reply_to = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_reply_to');
									$from_name = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_from_name');
									$subject = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_subject');
									$subject =  str_replace(array('{user_first_name_last_name}'),array($name),$subject);
									$message = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_new_email_address_message');
									$message = str_replace(array('{user_first_name_last_name}','{new_email}','{ip}','{update_email_time}'),array($name,$post_data['new_email'],$ip,$update_email_time),$message);
									
									###
									$old_cc = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_cc');
									$old_bcc = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_bcc');
									$old_from = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_from');
									$old_reply_to = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_reply_to');
									$old_from_name = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_from_name');
									$old_subject = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_subject');
									$old_subject =  str_replace(array('{user_first_name_last_name}'),array($name),$old_subject);
									$old_message = $this->config->item('account_management_personal_account_female_user_update_email_section_email_sent_to_old_email_address_message');
									$old_message = str_replace(array('{user_first_name_last_name}','{update_email_time}','{ip}'),array($name,$update_email_time,$ip),$old_message); 
								}
							}
							
						}else{
								$cc = $this->config->item('account_management_company_account_update_email_section_email_sent_to_new_email_address_cc');
								$bcc = $this->config->item('account_management_company_account_update_email_section_email_sent_to_new_email_address_bcc');
								$from = $this->config->item('account_management_company_account_update_email_section_email_sent_to_new_email_address_from');
								$reply_to = $this->config->item('account_management_company_account_update_email_section_email_sent_to_new_email_address_reply_to');
								$from_name = $this->config->item('account_management_company_account_update_email_section_email_sent_to_new_email_address_from_name');
								$subject = $this->config->item('account_management_company_account_update_email_section_email_sent_to_new_email_address_subject');
								$subject =  str_replace(array('{company_name}'),array($name),$subject);
								$message = $this->config->item('account_management_company_account_update_email_section_email_sent_to_new_email_address_message');
								$message = str_replace(array('{company_name}','{new_email}','{ip}','{update_email_time}'),array($name,$post_data['new_email'],$ip,$update_email_time),$message);
								
								###
								$old_cc = $this->config->item('account_management_company_account_update_email_section_email_sent_to_old_email_address_cc');
								$old_bcc = $this->config->item('account_management_company_account_update_email_section_email_sent_to_old_email_address_bcc');
								$old_from = $this->config->item('account_management_company_account_update_email_section_email_sent_to_old_email_address_from');
								$old_reply_to = $this->config->item('account_management_company_account_update_email_section_email_sent_to_old_email_address_reply_to');
								$old_from_name = $this->config->item('account_management_company_account_update_email_section_email_sent_to_old_email_address_from_name');
								$old_subject = $this->config->item('account_management_company_account_update_email_section_email_sent_to_old_email_address_subject');
								$old_subject =  str_replace(array('{user_first_name_last_name}'),array($name),$old_subject);
								$old_message = $this->config->item('account_management_company_account_update_email_section_email_sent_to_old_email_address_message');
								$old_message = str_replace(array('{company_name}','{update_email_time}','{ip}'),array($name,$update_email_time,$ip),$old_message);
						}
						$this->load->library ('email');
						###################### SMTP variables start here ##########
						$config['protocol'] = PROTOCOL;
						$config['smtp_host']    = SMTP_HOST;
						$config['smtp_port']    = SMTP_PORT;
						$config['smtp_timeout'] = SMTP_TIMEOUT;
						$config['smtp_user']    = SMTP_USER;
						$config['smtp_pass']    = SMTP_PASS;
						$config['charset'] = CHARSET;
						$config['mailtype'] = MAILTYPE;
						$config['newline'] = NEWLINE;	
						###################### SMTP variables end here ##########
						/* echo $to;
						echo "<br>";
						echo $old_to;
						die;
						 */
						
						$this->email->initialize($config);
						$from_name = '=?utf-8?B?'.base64_encode($from_name).'?=';
						$this->email->from ($from,$from_name);
						$this->email->to ($to);
						if($cc){
							$this->email->cc ($cc);
						}
						if($bcc){
							$this->email->bcc ($bcc);
						}
						$this->email->subject ($subject);
						$this->email->reply_to($reply_to);
						$this->email->set_mailtype ('html');
						$this->email->set_newline("\r\n");
						$this->email->message ($message);
						$this->email->send ();
						
						$old_from_name = '=?utf-8?B?'.base64_encode($old_from_name).'?=';
						$this->email->from ($old_from,$old_from_name);
						$this->email->to ($old_to);
						if($old_cc){
							$this->email->cc ($old_cc);
						}
						if($old_bcc){
						$this->email->bcc ($old_bcc);
						}
						$this->email->subject ($old_subject);
						$this->email->reply_to($old_reply_to);
						$this->email->set_mailtype ('html');
						$this->email->set_newline("\r\n");
						$this->email->message ($old_message);
						$this->email->send (); 
						#### email code end here ####
					}
					echo json_encode(['status' => 'SUCCESS','account_email'=>$account_email,'message'=>'','location'=>'','data'=>$data]);
					die;
					
				}else{
					echo json_encode ($validation_data_array);
					die;
				}
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH;
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	//This function is used to update user password
	public function update_account_management_login_password(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				if($user_id != Cryptor::doDecrypt($post_data['uid'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$validation_data_array = $this->User_model->user_update_login_password_validation($post_data);
				if ($validation_data_array['status'] == 'SUCCESS')
				{	
					//die("dfg");
					if($post_data['update_password_step'] == '2'){
						$this->db->update('users', array('password'=>md5($post_data['user_new_password'])), ['user_id'=> $user_id]);
						$update_password_user_activity_log_displayed_message = $this->config->item('account_management_update_password_user_activity_log_displayed_message');
						user_display_log($update_password_user_activity_log_displayed_message,$user_id); 
						
						$user_record = $this->db // get the user detail
							->select('u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.email')
							->from('users u')
							->where('u.user_id', $user_id)
							->get()->row_array();
						$to = $user_record['email'];
						$update_password_time = date(DATE_TIME_FORMAT_EXCLUDE_SECOND,time());
						$ip = $_SERVER["REMOTE_ADDR"];
						#### email code start here ####
						$name = (($user_record['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_record['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_record['is_authorized_physical_person'] == 'Y')) ? $user_record['first_name']." ".$user_record['last_name'] : $user_record['company_name'];
						if(($user_record['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_record['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_record['is_authorized_physical_person'] == 'Y'))
						{
							if($user_record['gender'] == 'M')
							{
								if($user_record['is_authorized_physical_person'] == 'Y'){
								
									$cc = $this->config->item('account_management_personal_account_company_app_male_user_update_password_section_email_cc');
									$bcc = $this->config->item('account_management_personal_account_company_app_male_user_update_password_section_email_bcc');
									$from = $this->config->item('account_management_personal_account_company_app_male_user_update_password_section_email_from');
									$reply_to = $this->config->item('account_management_personal_account_company_app_male_user_update_password_section_email_reply_to');
									$from_name = $this->config->item('account_management_personal_account_company_app_male_user_update_password_section_email_from_name');
									$subject = $this->config->item('account_management_personal_account_company_app_male_user_update_password_section_email_subject');
									$subject =  str_replace(array('{user_first_name_last_name}'),array($name),$subject);
									$message = $this->config->item('account_management_personal_account_company_app_male_user_update_password_section_email_message');
									$message = str_replace(array('{user_first_name_last_name}','{ip}','{update_password_time}'),array($name,$ip,$update_password_time),$message);
								
								}else{
							
									$cc = $this->config->item('account_management_personal_account_male_user_update_password_section_email_cc');
									$bcc = $this->config->item('account_management_personal_account_male_user_update_password_section_email_bcc');
									$from = $this->config->item('account_management_personal_account_male_user_update_password_section_email_from');
									$reply_to = $this->config->item('account_management_personal_account_male_user_update_password_section_email_reply_to');
									$from_name = $this->config->item('account_management_personal_account_male_user_update_password_section_email_from_name');
									$subject = $this->config->item('account_management_personal_account_male_user_update_password_section_email_subject');
									$subject =  str_replace(array('{user_first_name_last_name}'),array($name),$subject);
									$message = $this->config->item('account_management_personal_account_male_user_update_password_section_email_message');
									$message = str_replace(array('{user_first_name_last_name}','{ip}','{update_password_time}'),array($name,$ip,$update_password_time),$message);
								}
							
							}else{
								if($user_record['is_authorized_physical_person'] == 'Y'){
									$cc = $this->config->item('account_management_personal_account_company_app_female_user_update_password_section_email_cc');
									$bcc = $this->config->item('account_management_personal_account_company_app_female_user_update_password_section_email_bcc');
									$from = $this->config->item('account_management_personal_account_company_app_female_user_update_password_section_email_from');
									$reply_to = $this->config->item('account_management_personal_account_company_app_female_user_update_password_section_email_reply_to');
									$from_name = $this->config->item('account_management_personal_account_company_app_female_user_update_password_section_email_from_name');
									$subject = $this->config->item('account_management_personal_account_company_app_female_user_update_password_section_email_subject');
									$subject =  str_replace(array('{user_first_name_last_name}'),array($name),$subject);
									$message = $this->config->item('account_management_personal_account_company_app_female_user_update_password_section_email_message');
									$message = str_replace(array('{user_first_name_last_name}','{ip}','{update_password_time}'),array($name,$ip,$update_password_time),$message);	
								}else{	
									$cc = $this->config->item('account_management_personal_account_female_user_update_password_section_email_cc');
									$bcc = $this->config->item('account_management_personal_account_female_user_update_password_section_email_bcc');
									$from = $this->config->item('account_management_personal_account_female_user_update_password_section_email_from');
									$reply_to = $this->config->item('account_management_personal_account_female_user_update_password_section_email_reply_to');
									$from_name = $this->config->item('account_management_personal_account_female_user_update_password_section_email_from_name');
									$subject = $this->config->item('account_management_personal_account_female_user_update_password_section_email_subject');
									$subject =  str_replace(array('{user_first_name_last_name}'),array($name),$subject);
									$message = $this->config->item('account_management_personal_account_female_user_update_password_section_email_message');
									$message = str_replace(array('{user_first_name_last_name}','{ip}','{update_password_time}'),array($name,$ip,$update_password_time),$message);
								}
							}
						}else{
							$cc = $this->config->item('account_management_company_account_update_password_section_email_cc');
							$bcc = $this->config->item('account_management_company_account_update_password_section_email_bcc');
							$from = $this->config->item('account_management_company_account_update_password_section_email_from');
							$reply_to = $this->config->item('account_management_company_account_update_password_section_email_reply_to');
							$from_name = $this->config->item('account_management_company_account_update_password_section_email_from_name');
							$subject = $this->config->item('account_management_company_account_update_password_section_email_subject');
							$subject =  str_replace(array('{company_name}'),array($name),$subject);
							$message = $this->config->item('account_management_company_account_update_password_section_email_message');
							$message = str_replace(array('{company_name}','{ip}','{update_password_time}'),array($name,$ip,$update_password_time),$message);
						}
						$this->load->library ('email');
						###################### SMTP variables start here ##########
						$config['protocol'] = PROTOCOL;
						$config['smtp_host']    = SMTP_HOST;
						$config['smtp_port']    = SMTP_PORT;
						$config['smtp_timeout'] = SMTP_TIMEOUT;
						$config['smtp_user']    = SMTP_USER;
						$config['smtp_pass']    = SMTP_PASS;
						$config['charset'] = CHARSET;
						$config['mailtype'] = MAILTYPE;
						$config['newline'] = NEWLINE;	
						###################### SMTP variables end here ##########
						$this->email->initialize($config);
						$from_name = '=?utf-8?B?'.base64_encode($from_name).'?=';
						$this->email->from ($from,$from_name);
						$this->email->to ($to);
						if($cc){
							$this->email->cc ($cc);
						}
						if($bcc){
							$this->email->bcc ($bcc);
						}
						
						$this->email->subject ($subject);
						$this->email->reply_to($reply_to);
						$this->email->set_mailtype ('html');
						$this->email->set_newline("\r\n");
						$this->email->message ($message);
						$this->email->send ();
					}
					
					echo json_encode(['status' => 'SUCCESS','message'=>'','location'=>'','data'=>$data]);
					die;
					
				}else{
					echo json_encode ($validation_data_array);
					die;
				}
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH;
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
    //checking users_profile_base_information table field value blank
    public function profile_management_base_info_check() {
		
        if ($this->input->is_ajax_request()) {
            $msg['location'] = '';
            if (check_session_validity()) {
				$row = $this->input->post();
                $user = $this->session->userdata('user');
                $user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$user_info = $this->db // get the user detail
				->select('user_id,is_authorized_physical_person')
				->from('users')
				->where('user_id', $user_id)
				->get()->row_array();
				
				
				$user_detail = $this->db // get the user detail
				->select('*')
				->from('users_company_accounts_base_information')
				->where('user_id', $user_id)
				->get()->row_array();
			
				$msg['status'] = 200;
					$user_profile_completion_data = array();
					if ((!empty($user_detail) && $user_detail['company_vision'] == '' && $user_detail['company_core_values'] == '' && $user_detail['company_mission'] == '' && $user_detail['company_strategy_goals'] == '' && $user_detail['company_year_founded'] == '' && $user_detail['company_size'] == '') || empty($user_detail)) {
						$this->db->delete('users_company_accounts_base_information', array("user_id" => $user_id));
						$msg['company_vision'] = '';
						$msg['core_values'] = '';
						$msg['mission'] = '';
						$msg['strategy_goals'] = '';
						$msg['company_year_founded'] = '';
						$msg['company_size'] = '';
						
						$user_profile_completion_data['has_company_vision_indicated'] = 'N';
						$user_profile_completion_data['company_values_strength_value'] = 0;
						$user_profile_completion_data['has_company_core_values_indicated'] = 'N';
						$user_profile_completion_data['company_core_values_strength_value'] = 0;
						$user_profile_completion_data['has_company_mission_indicated'] = 'N';
						$user_profile_completion_data['company_mission_strength_value'] = 0;
						$user_profile_completion_data['has_company_strategy_goals_indicated'] = 'N';
						$user_profile_completion_data['company_strategy_goals_strength_value'] = 0;
						$user_profile_completion_data['has_company_founded_year_indicated'] = 'N';
						$user_profile_completion_data['company_founded_year_strength_value'] = 0;
						$user_profile_completion_data['has_company_size_indicated'] = 'N';
						$user_profile_completion_data['company_size_strength_value'] = 0;
						
						$msg['status'] = 200;
					} else {
						$msg['company_vision'] = $user_detail['company_vision'];
						$msg['core_values'] = trim($user_detail['company_core_values']);
						$msg['mission'] = trim($user_detail['company_mission']);
						$msg['strategy_goals'] = trim($user_detail['company_strategy_goals']);
						$msg['company_year_founded'] = trim($user_detail['company_year_founded']);
						$msg['company_size'] = trim($user_detail['company_size']);						
					}
					if($row['field'] == 'founded_year') {
						$st_yr = $this->config->item('ca_profile_management_base_information_year_start_from');
						$en_yr = $this->config->item('ca_profile_management_base_information_year_end_to');
						if(!empty($user_detail['company_year_founded']) && !($user_detail['company_year_founded'] >= $st_yr &&  $user_detail['company_year_founded'] <= $en_yr )) {
							$this->db->update('users_company_accounts_base_information', ['company_year_founded' => ''], ['user_id' => $user_id]);
							$user_detail['company_year_founded'] = '';
							
							$user_profile_completion_data['has_company_founded_year_indicated'] = 'N';
							$user_profile_completion_data['company_founded_year_strength_value'] = 0;
							
							
						}
						$msg['data'] = $this->load->view('ca_profile_management_base_information_company_founded_in', ['user_info'=>$user_info,'base_info' => $user_detail, 'post_data' => $row ], true);
					}
					if($row['field'] == 'company_size') {
						if($user[0]->is_authorized_physical_person == 'Y'){
							$company_size_dropdown = $this->config->item('ca_app_profile_management_base_information_company_size_dropdown_option');
						}else{
					
							$company_size_dropdown = $this->config->item('ca_profile_management_base_information_company_size_dropdown_option');
						}
						if(!empty($user_detail['company_size']) && !in_array($user_detail['company_size'], $company_size_dropdown)) {
							$this->db->update('users_company_accounts_base_information', ['company_size' => ''], ['user_id' => $user_id]);
							$user_detail['company_size'] = '';
							$user_profile_completion_data['has_company_size_indicated'] = 'N';
							$user_profile_completion_data['company_size_strength_value'] = 0;
							
						}				
						
						
						
						$msg['data'] = $this->load->view('ca_profile_management_base_information_company_size', ['user_info'=>$user_info,'base_info' => $user_detail, 'post_data' => $row ], true);
					}
					if($row['field'] == 'company_vision') {
						$msg['data'] = $this->load->view('ca_profile_management_company_values_and_principles_vision', ['user_info'=>$user_info,'base_info' => $user_detail, 'post_data' => $row ], true);
					}
					if($row['field'] == 'company_mission') {
						$msg['data'] = $this->load->view('ca_profile_management_company_values_and_principles_mission', ['user_info'=>$user_info,'base_info' => $user_detail, 'post_data' => $row ], true);
					}
					if($row['field'] == 'company_core_values') {
						$msg['data'] = $this->load->view('ca_profile_management_company_values_and_principles_core_values', ['user_info'=>$user_info,'base_info' => $user_detail, 'post_data' => $row ], true);
					}
					if($row['field'] == 'company_strategy_goals') {
						$msg['data'] = $this->load->view('ca_profile_management_company_values_and_principles_strategy_goals', ['user_info'=>$user_info,'base_info' => $user_detail, 'post_data' => $row ], true);
					}
					if(!empty($user_profile_completion_data)){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
					}
            } else {
                $msg['status'] = 400;
                $msg['location'] = VPATH;
            }
            echo json_encode($msg);
        } else {
			show_custom_404_page(); //show custom 404 page
			return;
        }
		}
		
		// save user company profile base information 
		public function save_company_profile_base_information() {
			if ($this->input->is_ajax_request()) {
				if (check_session_validity()) {
					$user = $this->session->userdata('user');
					$user_id = $user[0]->user_id;
					if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					$msg['status'] = 200;
					$post_data = $this->input->post();
					
					$validation_data_array = $this->User_model->company_account_base_information_validation($post_data);
					if ($validation_data_array['status'] == 'SUCCESS') { 
					
						$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
						
						$msg['status'] = 'SUCCESS';
						$user_detail = $this->db // get the user detail
						->select('*')
						->from('users_company_accounts_base_information')
						->where('user_id', $user_id)
						->get()->row_array();
						if ((!empty($user_detail) && $user_detail['company_vision'] == '' && $user_detail['company_core_values'] == '' && $user_detail['company_mission'] == '' && $user_detail['company_strategy_goals'] == '' && $user_detail['company_year_founded'] == '' && $user_detail['company_size'] == '') || empty($user_detail)) {
							$this->db->delete('users_company_accounts_base_information', array("user_id" => $user_id));
							$user_detail = [];
						}
						$user_profile_completion_data = array();
						if(!empty($post_data['founded_in'])) {
							$data = [
								'company_year_founded' => $post_data['founded_in'],
								'user_id' => $user_id
							];
							$st_yr = $this->config->item('ca_profile_management_base_information_year_start_from');
							$en_yr = $this->config->item('ca_profile_management_base_information_year_end_to');
							if(!empty($post_data['founded_in']) && !($post_data['founded_in'] >= $st_yr &&  $post_data['founded_in'] <= $en_yr )) {
								$data['company_year_founded'] = '';
							}
							if(empty($user_detail)) {
								$this->db->insert('users_company_accounts_base_information', $data);
							} else {
								$this->db->update('users_company_accounts_base_information', $data, ['user_id' => $user_id]);
							}
							$user_detail = $this->db->get_where('users_company_accounts_base_information', ['user_id' => $user_id])->row_array();
							$msg['data'] = $this->load->view('ca_profile_management_base_information_company_founded_in', ['base_info' => $user_detail, 'post_data' => $post_data ], true);
							
							$user_profile_completion_data['has_company_founded_year_indicated'] = 'Y';
							$user_profile_completion_data['company_founded_year_strength_value'] = $profile_completion_parameters['company_founded_year_strength_value'];
							
							
						}
						if(!empty($post_data['company_size'])) {
						
							if($user[0]->is_authorized_physical_person == 'Y'){
								$company_size_dropdown = $this->config->item('ca_app_profile_management_base_information_company_size_dropdown_option');
							}else{
						
								$company_size_dropdown = $this->config->item('ca_profile_management_base_information_company_size_dropdown_option');
							}
							$data = [
								'company_size' => trim($post_data['company_size']),
								'user_id' => $user_id
							];

							if(!empty($post_data['company_size']) && !in_array($post_data['company_size'], $company_size_dropdown)) {
								$data['company_size'] = '';
							}
							
							if(empty($user_detail)) {
								$this->db->insert('users_company_accounts_base_information', $data);
							} else {
								$this->db->update('users_company_accounts_base_information', $data, ['user_id' => $user_id]);
							}
							$user_detail = $this->db->get_where('users_company_accounts_base_information', ['user_id' => $user_id])->row_array();
						
							
							$msg['data'] = $this->load->view('ca_profile_management_base_information_company_size', ['base_info' => $user_detail, 'post_data' => $post_data ], true);
							
							$user_profile_completion_data['has_company_size_indicated'] = 'Y';
							$user_profile_completion_data['company_size_strength_value'] = $profile_completion_parameters['company_size_strength_value'];
						}
						if(!empty($post_data['company_vision'])) {
							$data = [
								'company_vision' => trim($post_data['company_vision']),
								'user_id' => $user_id
							];
							if(empty($user_detail)) {
								$this->db->insert('users_company_accounts_base_information', $data);

								$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['company_vision']));
							} else {
								$this->db->update('users_company_accounts_base_information', $data, ['user_id' => $user_id]);

								if(empty($user_detail['company_vision'])) {
									$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['company_vision']));
								} else {
									$this->User_model->save_find_professionals_user_information($user_id, 'update', ' '.trim($post_data['company_vision']), ' '.$user_detail['company_vision']);
								}
							}
							$user_detail = $this->db->get_where('users_company_accounts_base_information', ['user_id' => $user_id])->row_array();
							$msg['data'] = $this->load->view('ca_profile_management_company_values_and_principles_vision', ['base_info' => $user_detail, 'post_data' => $post_data ], true);
							
							$user_profile_completion_data['has_company_vision_indicated'] = 'Y';
							$user_profile_completion_data['company_values_strength_value'] = $profile_completion_parameters['company_values_strength_value'];
							
						}
						if(!empty($post_data['company_mission'])) {
							$data = [
								'company_mission' => trim($post_data['company_mission']),
								'user_id' => $user_id
							];
							if(empty($user_detail)) {
								$this->db->insert('users_company_accounts_base_information', $data);

								$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['company_mission']));
							} else {
								$this->db->update('users_company_accounts_base_information', $data, ['user_id' => $user_id]);

								if(empty($user_detail['company_mission'])) {
									$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['company_mission']));
								} else {
									$this->User_model->save_find_professionals_user_information($user_id, 'update', ' '.trim($post_data['company_mission']), ' '.$user_detail['company_mission']);
								}
							}
							$user_detail = $this->db->get_where('users_company_accounts_base_information', ['user_id' => $user_id])->row_array();
							$msg['data'] = $this->load->view('ca_profile_management_company_values_and_principles_mission', ['base_info' => $user_detail, 'post_data' => $post_data ], true);
							
							$user_profile_completion_data['has_company_mission_indicated'] = 'Y';
							$user_profile_completion_data['company_mission_strength_value'] = $profile_completion_parameters['company_mission_strength_value'];
						}
						if(!empty($post_data['company_core_values'])) {
							$data = [
								'company_core_values' => trim($post_data['company_core_values']),
								'user_id' => $user_id
							];
							if(empty($user_detail)) {
								$this->db->insert('users_company_accounts_base_information', $data);

								$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['company_core_values']));
							} else {
								$this->db->update('users_company_accounts_base_information', $data, ['user_id' => $user_id]);
								if(empty($user_detail['company_core_values'])) {
									$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['company_core_values']));
								} else {
									$this->User_model->save_find_professionals_user_information($user_id, 'update', ' '.trim($post_data['company_core_values']), ' '.$user_detail['company_core_values']);
								}
							}
							$user_detail = $this->db->get_where('users_company_accounts_base_information', ['user_id' => $user_id])->row_array();
							$msg['data'] = $this->load->view('ca_profile_management_company_values_and_principles_core_values', ['base_info' => $user_detail, 'post_data' => $post_data ], true);
							
							$user_profile_completion_data['has_company_core_values_indicated'] = 'Y';
							$user_profile_completion_data['company_core_values_strength_value'] = $profile_completion_parameters['company_core_values_strength_value'];
						}
						if(!empty($post_data['company_strategy_goals'])) {
							$data = [
								'company_strategy_goals' => trim($post_data['company_strategy_goals']),
								'user_id' => $user_id
							];
							if(empty($user_detail)) {
								$this->db->insert('users_company_accounts_base_information', $data);

								$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['company_strategy_goals']));
							} else {
								$this->db->update('users_company_accounts_base_information', $data, ['user_id' => $user_id]);

								if(empty($user_detail['company_strategy_goals'])) {
									$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['company_strategy_goals']));
								} else {
									$this->User_model->save_find_professionals_user_information($user_id, 'update', ' '.trim($post_data['company_strategy_goals']), ' '.$user_detail['company_strategy_goals']);
								}
							}
							$user_detail = $this->db->get_where('users_company_accounts_base_information', ['user_id' => $user_id])->row_array();
							
							
							$msg['data'] = $this->load->view('ca_profile_management_company_values_and_principles_strategy_goals', ['base_info' => $user_detail, 'post_data' => $post_data ], true);
							$user_profile_completion_data['has_company_strategy_goals_indicated'] = 'Y';
							$user_profile_completion_data['company_strategy_goals_strength_value'] = $profile_completion_parameters['company_strategy_goals_strength_value'];
						}
						if(!empty($user_profile_completion_data)){
							if(($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'N')) {
								$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
							}
						}
						echo json_encode ($msg);
						return;
					} else {
						echo json_encode ($validation_data_array);
						return;
					}
					echo json_encode($msg);
				} else {
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
				}
			} else {
				show_custom_404_page(); //show custom 404 page
				return;
			}
		}
		// This method is used to remove indecated information from comapny account base information table
		public function remove_company_profile_base_information() {
			if ($this->input->is_ajax_request()) {
				if (check_session_validity()) {
					$user = $this->session->userdata('user');
					$user_id = $user[0]->user_id;
					if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
				}
				$user_detail = $this->db // get the user detail
												->select('*')
												->from('users_company_accounts_base_information')
												->where('user_id', $user_id)
												->get()->row_array();
				$post_data = $this->input->post();
				if(!empty($post_data['founded_in'])) {
					$this->db->update('users_company_accounts_base_information', ['company_year_founded' => ''], ['user_id' => $user_id]);
					$user_profile_completion_data['has_company_founded_year_indicated'] = 'N';
					$user_profile_completion_data['company_founded_year_strength_value'] = 0;
				}
				if(!empty($post_data['company_size'])) {
					$this->db->update('users_company_accounts_base_information', ['company_size' => ''], ['user_id' => $user_id]);
					$user_profile_completion_data['has_company_size_indicated'] = 'N';
					$user_profile_completion_data['company_size_strength_value'] = 0;
				}
				if(!empty($post_data['company_vision'])) {
					$this->db->update('users_company_accounts_base_information', ['company_vision' => ''], ['user_id' => $user_id]);

					$this->User_model->save_find_professionals_user_information($user_id, 'delete', ' ', $user_detail['company_vision']);

					$user_profile_completion_data['has_company_vision_indicated'] = 'N';
					$user_profile_completion_data['company_values_strength_value'] = 0;
				}
				if(!empty($post_data['company_mission'])) {
					$this->db->update('users_company_accounts_base_information', ['company_mission' => ''], ['user_id' => $user_id]);

					$this->User_model->save_find_professionals_user_information($user_id, 'delete', ' ', $user_detail['company_mission']);

					$user_profile_completion_data['has_company_mission_indicated'] = 'N';
					$user_profile_completion_data['company_mission_strength_value'] = 0;
				}
				if(!empty($post_data['company_core_values'])) {
					$this->db->update('users_company_accounts_base_information', ['company_core_values' => ''], ['user_id' => $user_id]);

					$this->User_model->save_find_professionals_user_information($user_id, 'delete', ' ', $user_detail['company_core_values']);

					$user_profile_completion_data['has_company_core_values_indicated'] = 'N';
					$user_profile_completion_data['company_core_values_strength_value'] = 0;
				}
				if(!empty($post_data['company_strategy_goals'])) {
					$this->db->update('users_company_accounts_base_information', ['company_strategy_goals' => ''], ['user_id' => $user_id]);

						$this->User_model->save_find_professionals_user_information($user_id, 'delete', ' ', $user_detail['company_strategy_goals']);

					$user_profile_completion_data['has_company_strategy_goals_indicated'] = 'N';
					$user_profile_completion_data['company_strategy_goals_strength_value'] = 0;
				}
				if(!empty($user_profile_completion_data)){
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
				}

				$user_detail = $this->db // get the user detail
															->select('*')
															->from('users_company_accounts_base_information')
															->where('user_id', $user_id)
															->get()->row_array();
				if ((!empty($user_detail) && $user_detail['company_vision'] == '' && $user_detail['company_core_values'] == '' && $user_detail['company_mission'] == '' && $user_detail['company_strategy_goals'] == '' && $user_detail['company_year_founded'] == '' && $user_detail['company_size'] == '') || empty($user_detail)) {
					$this->db->delete('users_company_accounts_base_information', array("user_id" => $user_id));
				}
				$msg['status'] = 200;
				if(!empty($post_data['founded_in'])) { 
					$user_detail = $this->db->get_where('users_company_accounts_base_information', ['user_id' => $user_id])->row_array();
					$msg['data'] = $this->load->view('ca_profile_management_base_information_company_founded_in', ['base_info' => $user_detail, 'post_data' => $post_data ], true);
				}
				if(!empty($post_data['company_size'])) {
					$user_detail = $this->db->get_where('users_company_accounts_base_information', ['user_id' => $user_id])->row_array();
					$msg['data'] = $this->load->view('ca_profile_management_base_information_company_size', ['base_info' => $user_detail, 'post_data' => $post_data ], true);
				}
				if(!empty($post_data['company_vision'])) {
					$user_detail = $this->db->get_where('users_company_accounts_base_information', ['user_id' => $user_id])->row_array();
					$msg['data'] = $this->load->view('ca_profile_management_company_values_and_principles_vision', ['base_info' => $user_detail, 'post_data' => $post_data ], true);
				}
				if(!empty($post_data['company_mission'])) {
					$user_detail = $this->db->get_where('users_company_accounts_base_information', ['user_id' => $user_id])->row_array();
					$msg['data'] = $this->load->view('ca_profile_management_company_values_and_principles_mission', ['base_info' => $user_detail, 'post_data' => $post_data ], true);
				}
				if(!empty($post_data['company_core_values'])) {
					$user_detail = $this->db->get_where('users_company_accounts_base_information', ['user_id' => $user_id])->row_array();
					$msg['data'] = $this->load->view('ca_profile_management_company_values_and_principles_core_values', ['base_info' => $user_detail, 'post_data' => $post_data ], true);
				}
				if(!empty($post_data['company_strategy_goals'])) {
					$user_detail = $this->db->get_where('users_company_accounts_base_information', ['user_id' => $user_id])->row_array();
					$msg['data'] = $this->load->view('ca_profile_management_company_values_and_principles_strategy_goals', ['base_info' => $user_detail, 'post_data' => $post_data ], true);
				}
				
				echo json_encode($msg);
				return;
			} else {
				show_custom_404_page(); //show custom 404 page
				return;
			}
		}
		// This method is used to check company opening hours information
		public function company_profile_management_check_opening_hours() {
			if ($this->input->is_ajax_request()) {
				if (check_session_validity()) {
					$user = $this->session->userdata('user');
					$user_id = $user[0]->user_id;
					if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					$row = $this->input->post();
					$user_info = $this->db // get the user detail
					->select('user_id,is_authorized_physical_person')
					->from('users')
					->where('user_id', $user_id)
					->get()->row_array();
					
					if(empty($row['location_id'])) {
						$user_opening_hours = $this->db->get_where('users_company_accounts_opening_hours', ['user_id' => $user_id])->result_array();
					} else {
						$location_id = 0;
						$table = '';
						$is_headquarter = 'N';
						if(strpos($row['location_id'], 'hd_') === 0) {
							$arr = explode('hd_', $row['location_id']);
							$location_id = end($arr);
							$table = 'users_address_details';
							$is_headquarter = 'Y';
						} else if(strpos($row['location_id'], 'br_') === 0){
							$arr = explode('br_', $row['location_id']);
							$location_id = end($arr);
							$table = 'users_company_accounts_additional_branches_addresses';
						} 
						$address = $this->db->get_where($table, ['id' => $location_id, 'user_id' =>  $user_id])->row_array();

						if(empty($address)) {
							$res['status'] = 204;
							$res['location_cnt'] = $this->db->from('users_address_details')->where('user_id', $user_id)->count_all_results();
							$res['error'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_opening_hours_location_not_valid_error_message'):$this->config->item('ca_profile_management_base_information_company_opening_hours_location_not_valid_error_message');
							echo json_encode($res);
							return;
						}

						$user_opening_hours = $this->db->get_where('users_company_accounts_opening_hours', ['user_id' => $user_id, 'company_location_id' => $location_id, 'is_company_headquarter' => $is_headquarter])->result_array();
					}
					if(!empty($user_opening_hours)) {
						foreach($user_opening_hours as $val) {
							if($val['company_open_hours_status'] == 'selected_hours') {
								if($val['day'] == '' || $val['company_opening_time'] == '' || $val['company_closing_time'] == '') {
									$this->db->delete('users_company_accounts_opening_hours', ['id' => $val['id']]);
								}
							} else {
								// if($val['day'] != '' || $val['company_opening_time'] != '' || $val['company_closing_time'] != '') {
								// 	$this->db->delete('users_company_accounts_opening_hours', ['id' => $val['id']]);
								// }
							}
						}
					}
					if(empty($row['location_id'])) {
						$location = [];
						$head_location = $this->db // get the user detail
									->select('bael.id, bael.street_address, bael.locality_id, bael.county_id, bael.postal_code_id, c.name as county_name, l.name as locality_name, pc.postal_code, con.country_code, con.country_name as country')
									->from('users_address_details bael')
									->join('countries con', 'con.id = bael.country_id', 'left')
									->join('counties c', 'c.id = bael.county_id', 'left')
									->join('localities l', 'l.id = bael.locality_id', 'left')
									->join('postal_codes pc', 'pc.id = bael.postal_code_id', 'left')
									->where('bael.user_id', $user_id)
									->get()->row_array();
						if(!empty($head_location)) {
							$address_details_head_location = '';
							if($head_location['street_address']) {
								$address_details_head_location .= htmlspecialchars($head_location['street_address'], ENT_QUOTES).', ';
							} 
							
							if($head_location['locality_name']) {
								$address_details_head_location .= $head_location['locality_name'].' '.$head_location['postal_code'].', '.$head_location['county_name'].', '.$head_location['country'];
							}else if($head_location['county_name']){
								$address_details_head_location .= $head_location['county_name'].', '.$head_location['country'];
							}else {
								$address_details_head_location .= $head_location['country'];
							}
							$location['hd_'.$head_location['id']] = $address_details_head_location;
						}

						$extra_locations_arr = $this->db // get the user detail
									->select('bael.id, bael.street_address, bael.locality_id, bael.county_id, bael.postal_code_id, c.name as county_name, l.name as locality_name, pc.postal_code, con.country_code, con.country_name as country')
									->from('users_company_accounts_additional_branches_addresses bael')
									->join('countries con', 'con.id = bael.country_id', 'left')
									->join('counties c', 'c.id = bael.county_id', 'left')
									->join('localities l', 'l.id = bael.locality_id', 'left')
									->join('postal_codes pc', 'pc.id = bael.postal_code_id', 'left')
									->where('bael.user_id', $user_id)
									->get()->result_array();
						foreach($extra_locations_arr as $extra_location) {
							$address_details_extra_locations = '';
							if($extra_location['street_address']) {
								$address_details_extra_locations .= htmlspecialchars($extra_location['street_address'], ENT_QUOTES).', ';
							} 
							
							if(!empty($extra_location['locality_name']) && !empty($extra_location['postal_code'])) {
								$address_details_extra_locations .= $extra_location['locality_name'].' '.$extra_location['postal_code'].', '.$extra_location['county_name'].', '.$extra_location['country'];
							} else if(!empty($extra_location['locality_name']) && empty($extra_location['postal_code'])) {
								$address_details_extra_locations .= $extra_location['locality_name'].', '.$extra_location['county_name'].', '.$extra_location['country'];
							} else if(empty($extra_location['locality_name']) && !empty($extra_location['postal_code'])) {
								$address_details_extra_locations .= $extra_location['postal_code'].', '.$extra_location['county_name'].', '.$extra_location['country'];
							} else if(!empty($extra_location['county_name'])){
								$address_details_extra_locations .= $extra_location['county_name'].', '.$extra_location['country'];
							}else {
								$address_details_extra_locations .= $extra_location['country'];
							}
							$location['br_'.$extra_location['id']] = $address_details_extra_locations;
						}
						$user_opening_hours = $this->db->get_where('users_company_accounts_opening_hours', ['user_id' => $user_id])->result_array();
						$res['status'] = 200;
						$res['location_cnt'] = $this->db->from('users_address_details')->where('user_id', $user_id)->count_all_results();
						$res['data'] = $this->load->view('ca_profile_management_base_information_opening_hours', ['opening_hours' => $user_opening_hours, 'locations' => $location,'user_info'=>$user_info], true);
						echo json_encode($res);
					} else {
						$user_opening_hours = $this->db->get_where('users_company_accounts_opening_hours', ['user_id' => $user_id, 'company_location_id' => $location_id, 'is_company_headquarter' => $is_headquarter])->result_array();
						$res['status'] = 200;
						$res['data'] = $this->load->view('ajax_ca_profile_management_base_information_opening_hours', ['opening_hours' => $user_opening_hours,'user_info'=>$user_info], true);
						echo json_encode($res);
					}
					return;
				} else {
					echo json_encode(['status' => 400]);
					return;
				}
			} else {
				show_custom_404_page(); //show custom 404 page
				return;
			}
		}
		// This method is used to save company opening hours information
		public function save_company_profile_opening_hours() {
			if ($this->input->is_ajax_request()) {
				if (check_session_validity()) {
					$user = $this->session->userdata('user');
					$user_id = $user[0]->user_id;
					if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					$row = $this->input->post();
					$user_profile_completion_data = array();
					$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
					if(!empty($row['company_open_hours_status']) && empty($row['action'])) {
						
						$location_id = 0;
						$table = '';
						$is_headquarter = 'N';
						if(strpos($row['location_id'], 'hd_') === 0) {
							$arr = explode('hd_', $row['location_id']);
							$location_id = end($arr);
							$table = 'users_address_details';
							$is_headquarter = 'Y';
						} else if(strpos($row['location_id'], 'br_') === 0){
							$arr = explode('br_', $row['location_id']);
							$location_id = end($arr);
							$table = 'users_company_accounts_additional_branches_addresses';
						} 
						$user_opening_hours = $this->db->get_where('users_company_accounts_opening_hours', ['user_id' => $user_id, 'company_location_id' => $location_id, 'is_company_headquarter' => $is_headquarter])->row_array();
						$address = $this->db->get_where($table, ['id' => $location_id, 'user_id' =>  $user_id])->row_array();
						if(empty($address)) {
							$res['status'] = 204;
							$res['location_cnt'] = $this->db->from('users_address_details')->where('user_id', $user_id)->count_all_results();
							$res['error'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_opening_hours_location_not_valid_error_message'):$this->config->item('ca_profile_management_base_information_company_opening_hours_location_not_valid_error_message');;
							echo json_encode($res);
							return;
						}
						$data = [
							'user_id' => $user_id,
							'company_open_hours_status' => $row['company_open_hours_status'],
							'company_location_id' => $location_id,
							'is_company_headquarter' => $is_headquarter
						];
						if(empty($user_opening_hours)) {
							if($row['company_open_hours_status'] != 'selected_hours') {
								$this->db->insert('users_company_accounts_opening_hours', $data);
							}
						} else {
							if($row['company_open_hours_status'] != 'selected_hours') {
								$this->db->update('users_company_accounts_opening_hours', $data, ['user_id' => $user_id, 'company_location_id' => $location_id, 'is_company_headquarter' => $is_headquarter ]);
							} else {
								if(!empty($user_opening_hours['day'])) {
									if(!empty($row['is_selected_hours_checked'])) {
										$data['is_selected_hours_checked'] = 'N';
									} else {
										$data['is_selected_hours_checked'] = 'Y';
									}
									$this->db->update('users_company_accounts_opening_hours', $data, ['user_id' => $user_id, 'company_location_id' => $location_id, 'is_company_headquarter' => $is_headquarter ]);
								} else {
									$this->db->delete('users_company_accounts_opening_hours', ['user_id' => $user_id, 'company_location_id' => $location_id, 'is_company_headquarter' => $is_headquarter]);
								}
							}
						}
						$user_opening_hours = $this->db->get_where('users_company_accounts_opening_hours', ['user_id' => $user_id, 'company_location_id' => $location_id, 'is_company_headquarter' => $is_headquarter])->result_array();
						$res['status'] = 200;
						$res['data'] = $this->load->view('ajax_ca_profile_management_base_information_opening_hours', ['opening_hours' => $user_opening_hours], true);
						$res['notification'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_opening_hours_saved_confirmation_message'):$this->config->item('ca_profile_management_base_information_company_opening_hours_saved_confirmation_message');
						
						// profile completion code start 
						
						$check_opening_hours_entry_exists = $this->db->where(['user_id'=>$user_id])->from('users_company_accounts_opening_hours')->count_all_results();
						if($check_opening_hours_entry_exists >0 ){
							$user_profile_completion_data['has_company_opening_hours_indicated'] = 'Y';
							$user_profile_completion_data['company_opening_hours_strength_value'] = $profile_completion_parameters['company_opening_hours_strength_value'];
						}else{
							$user_profile_completion_data['has_company_opening_hours_indicated'] = 'N';
							$user_profile_completion_data['company_opening_hours_strength_value'] =0;
						}
						if(!empty($user_profile_completion_data)){
							if(($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'N')) {
								$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
							}
						}
						// profile completion code end 
						
						
						
						
					} else {
						$validation = $this->User_model->company_account_opening_hours_validation($row);
						if($validation['status'] == 'SUCCESS') {

							$location_id = 0;
							$table = '';
							$is_headquarter = 'N';
							if(strpos($row['location_id'], 'hd_') === 0) {
								$arr = explode('hd_', $row['location_id']);
								$location_id = end($arr);
								$table = 'users_address_details';
								$is_headquarter = 'Y';
							} else if(strpos($row['location_id'], 'br_') === 0){
								$arr = explode('br_', $row['location_id']);
								$location_id = end($arr);
								$table = 'users_company_accounts_additional_branches_addresses';
							} 
							$address = $this->db->get_where($table, [ 'user_id' =>  $user_id, 'id' => $location_id])->row_array();
							if(empty($address)) {
								$res['status'] = 204;
								$res['location_cnt'] = $this->db->from('users_address_details')->where('user_id', $user_id)->count_all_results();
								$res['error'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_opening_hours_location_not_valid_error_message'):$this->config->item('ca_profile_management_base_information_company_opening_hours_location_not_valid_error_message');
								echo json_encode($res);
								return;
							}

							$this->db->delete('users_company_accounts_opening_hours', ['user_id' => $user_id, 'company_location_id' => $location_id, 'is_company_headquarter' => $is_headquarter]);
							$opening_hours = [];
							foreach($row['days'] as $k => $val) {
								$tmp = [
									'user_id' => $user_id,
									'company_open_hours_status' => $row['company_open_hours_status'],
									'day' => $val,
									'company_opening_time' => $row['op'][$val],
									'company_closing_time' => $row['cl'][$val],
									'company_location_id' => $location_id,
									'is_company_headquarter' => $is_headquarter,
									'is_selected_hours_checked' => 'Y'
								];
								array_push($opening_hours, $tmp);
							}
							if(!empty($opening_hours)) {
								$this->db->insert_batch('users_company_accounts_opening_hours', $opening_hours);
							}
							$user_opening_hours = $this->db->get_where('users_company_accounts_opening_hours', ['user_id' => $user_id,'company_location_id' => $location_id, 'is_company_headquarter' => $is_headquarter])->result_array();
							$res['status'] = 200;
							$res['data'] = $this->load->view('ajax_ca_profile_management_base_information_opening_hours', ['opening_hours' => $user_opening_hours], true);
							$res['notification'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_opening_hours_saved_confirmation_message'):$this->config->item('ca_profile_management_base_information_company_opening_hours_saved_confirmation_message');;
							
							// profile completion code start 
							$user_profile_completion_data = array();
							
							$check_opening_hours_entry_exists = $this->db->where(['user_id'=>$user_id])->from('users_company_accounts_opening_hours')->count_all_results();
							if($check_opening_hours_entry_exists >0 ){
								$user_profile_completion_data['has_company_opening_hours_indicated'] = 'Y';
								$user_profile_completion_data['company_opening_hours_strength_value'] = $profile_completion_parameters['company_opening_hours_strength_value'];
							}else{
								$user_profile_completion_data['has_company_opening_hours_indicated'] = 'N';
								$user_profile_completion_data['company_opening_hours_strength_value'] =0;
							}
							if(!empty($user_profile_completion_data)){
								if(($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'N')) {
									$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
								}
							}
							// profile completion code end 
							
						} else {
							$res = $validation;
						}
					}
				
					echo json_encode($res);
					return;
				} else {
					echo json_encode(['status' => 400]);
					return;
				}
			} else {
				show_custom_404_page(); //show custom 404 page
				return;
			}
		}
		// This method is used to remove company opening hours from db
		public function remove_company_profile_opening_hours() {
			if ($this->input->is_ajax_request()) {
				if (check_session_validity()) {
					$user = $this->session->userdata('user');
					$user_id = $user[0]->user_id;
					if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						return;
					}
					$row = $this->input->post();
					$location_id = 0;
					$table = '';
					$is_headquarter = 'N';
					if(strpos($row['location_id'], 'hd_') === 0) {
						$arr = explode('hd_', $row['location_id']);
						$location_id = end($arr);
						$is_headquarter = 'Y';
					} else if(strpos($row['location_id'], 'br_') === 0){
						$arr = explode('br_', $row['location_id']);
						$location_id = end($arr);
					} 
					$this->db->delete('users_company_accounts_opening_hours', ['user_id' => $user_id, 'company_location_id' => $location_id, 'is_company_headquarter' => $is_headquarter]);
					// $user_opening_hours = $this->db->get_where('users_company_accounts_opening_hours', ['user_id' => $user_id])->result_array();
					
					
					
					
					// profile completion code start 
					$user_profile_completion_data = array();
					$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
											
					$check_opening_hours_entry_exists = $this->db->where(['user_id'=>$user_id])->from('users_company_accounts_opening_hours')->count_all_results();
					if($check_opening_hours_entry_exists >0 ){
						$user_profile_completion_data['has_company_opening_hours_indicated'] = 'Y';
						$user_profile_completion_data['company_opening_hours_strength_value'] = $profile_completion_parameters['company_opening_hours_strength_value'];
					}else{
						$user_profile_completion_data['has_company_opening_hours_indicated'] = 'N';
						$user_profile_completion_data['company_opening_hours_strength_value'] =0;
					}
					if(!empty($user_profile_completion_data)){
						if(($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'N')) {
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
						}
					}
					// profile completion code end 
					
					
					
					$res['status'] = 200;
					$res['location_cnt'] = $this->db->from('users_address_details')->where('user_id', $user_id)->count_all_results();
					$res['data'] = $this->load->view('ca_profile_management_base_information_opening_hours', ['opening_hours' => $user_opening_hours], true);
					echo json_encode($res);
					return;
				} else {
					echo json_encode(['status' => 400]);
					return;
				}
			} else {
				show_custom_404_page(); //show custom 404 page	
				return;
			}
		}
    public function ajax_get_company_profile_opening_hours_based_on_location() {
			if ($this->input->is_ajax_request()) {
				if (check_session_validity()) {
					$user = $this->session->userdata('user');
					$user_id = $user[0]->user_id;
					if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						return;
					}
					$row = $this->input->post();
					$location_id = 0;
					$table = '';
					$is_headquarter = 'N';
					if(strpos($row['location_id'], 'hd_') === 0) {
						$arr = explode('hd_', $row['location_id']);
						$location_id = end($arr);
						$table = 'users_address_details';
						$is_headquarter = 'Y';
					} else if(strpos($row['location_id'], 'br_') === 0){
						$arr = explode('br_', $row['location_id']);
						$location_id = end($arr);
						$table = 'users_company_accounts_additional_branches_addresses';
					} 
					$address = $this->db->get_where($table, ['id' => $location_id, 'user_id' =>  $user_id])->row_array();
					if(!empty($address)) {
						$user_opening_hours = $this->db->get_where('users_company_accounts_opening_hours', ['user_id' => $user_id, 'company_location_id' => $location_id, 'is_company_headquarter' => $is_headquarter])->result_array();
						$res['status'] = 200;
						$res['data'] = $this->load->view('ajax_ca_profile_management_base_information_opening_hours', ['opening_hours' => $user_opening_hours], true);
						echo json_encode($res);
						return;
					} else {
						$res['status'] = 204;
						$res['location_cnt'] = $this->db->from('users_address_details')->where('user_id', $user_id)->count_all_results();
						$res['error'] = $this->config->item('ca_profile_management_base_information_company_opening_hours_location_not_valid_error_message');
						echo json_encode($res);
						return;
					}
				} else {
					echo json_encode(['status' => 400]);
					return;
				}
			} else {
				show_custom_404_page(); //show custom 404 page	
				return;
			}
		}
    public function work_experience() {
		if(!$this->session->userdata ('user') && !$this->input->is_ajax_request()){
			$last_redirect_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->session->set_userdata ('last_redirect_url', $last_redirect_url);
		}
        if (!$this->session->userdata('user')) {
            redirect(VPATH . $this->config->item('signin_page_url'));
        } else {

            $user = $this->session->userdata('user');
			if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')){
				$data['user_id'] = $user[0]->user_id;
				if ($this->session->flashdata('settings_success')) {
					$data['active_account'] = 1;
				}
				$user_id = $user[0]->user_id;
				$name = (($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_PERSONAL_ACCOUNT_TYPE) || ($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_COMPANY_ACCOUNT_TYPE && $this->auto_model->getFeild('is_authorized_physical_person', 'users', 'user_id', $user_id) == 'Y')) ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
				/* foreach ($this->user_data as $key => $value) {
					$data[$key] = $value;
				} */
				########## reset the work experience start###
				$this->User_model->remove_scrambled_user_work_experience_entries($user_id);
				########## reset the work experience end###
				$data['current_page'] = 'work_experience';
				
				$start = 0;
				$work_experience_listing_data = 
				$this->User_model->get_user_work_experience_listing(array('user_id'=>$user_id),$start, $this->config->item('personal_account_work_experience_section_listing_limit'));
				$data["work_experience_data"] = $work_experience_listing_data['data'];
				$data['work_experience_count'] = $work_experience_listing_data['total'];
				
				$paginations = generate_pagination_links($work_experience_listing_data['total'], $this->config->item('work_experience_page_url'), $this->config->item('personal_account_work_experience_section_listing_limit'), $this->config->item('personal_account_work_experience_section_number_of_pagination_links'));
				
				$data['work_experience_pagination_links'] = $paginations['links'];

				if($user[0]->is_authorized_physical_person == 'Y'){
					$work_experience_title_meta_tag = $this->config->item('personal_account_work_experience_page_title_meta_tag');
					$work_experience_description_meta_tag = $this->config->item('personal_account_work_experience_page_description_meta_tag');
				}else{
					$work_experience_title_meta_tag = $this->config->item('company_account_app_work_experience_page_title_meta_tag');
					$work_experience_description_meta_tag = $this->config->item('company_account_app_work_experience_page_description_meta_tag');
				}
				
				
				$work_experience_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $work_experience_title_meta_tag);
				$work_experience_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $work_experience_description_meta_tag);
				$data['meta_tag'] = '<title>' . $work_experience_title_meta_tag . '</title><meta name="description" content="' . $work_experience_description_meta_tag . '"/>';
				########## set the work experience title tag start end #########	
				$logstr = 'user ' . get_client_ip() . '/' . $this->session->userdata('user_log_id') . ' visited work experience page.';

				$this->layout->view('personal_account_work_experience_management', '', $data, 'include');
				
			}else{
				 show_custom_404_page(); //show custom 404 page
			}	
            
        }
    }
	
	public function load_user_work_experience_popup_body(){
	
            if($this->input->is_ajax_request ()){
                if(check_session_validity()){ // check session exists or not if exist then it will update user session
                    $data['countries'] = $this->Dashboard_model->get_countries();
                    $user = $this->session->userdata ('user');
                    $user_id = $user[0]->user_id;
					if($user_id != Cryptor::doDecrypt($this->input->post ('u_id'))){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					
					$action_type = $this->input->post ('action_type');
					$section_id = $this->input->post ('section_id');
					if($action_type == 'edit'){
					
					$check_work_experience_data_exists = $this->db->where(['id' =>$section_id,'user_id'=>$user_id])->from('users_personal_accounts_work_experience')->count_all_results();
						if($check_work_experience_data_exists == 0){
						
						
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$user[0]->is_authorized_physical_person == 'Y'?$this->config->item('company_account_app_user_edit_work_experience_entry_already_deleted'):$this->config->item('personal_account_user_edit_work_experience_entry_already_deleted')]);
							die;
						}
					
					}
					
					
					
					$res['work_experience_popup_heading'] = $user[0]->is_authorized_physical_person == 'Y'?$this->config->item('company_account_app_work_experience_section_popup_add_headline_title'):$this->config->item('personal_account_work_experience_section_popup_add_headline_title');
					$work_experience_data = array();
					if($action_type == 'edit' && !empty($section_id)){
						$res['work_experience_popup_heading'] = $this->config->item('personal_account_work_experience_section_popup_edit_headline_title');
						$work_experience_data = $this->db // get the user detail
						->select('we.*')
						->from('users_personal_accounts_work_experience we')
						->where('we.user_id', $user_id)
						->where('we.id', $section_id)
						->get()->row_array();
						
					}
					$data['work_experience_data'] = $work_experience_data;
                    $res['work_experience_popup_body'] = $this->load->view('personal_account_work_experience_popup_body', $data, true);
                   
                    $res['status'] = 200;
                    echo json_encode($res);
                    die; 
                }else{
					
                    $res['status'] = 400;
                    $res['location'] = VPATH;
                    echo json_encode($res);
                    die;
                }
            }else{
                show_custom_404_page(); //show custom 404 page
            }
	
	}
	
	public function save_user_work_experience(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session

				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				if($user_id != Cryptor::doDecrypt($post_data['u_id'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$validation_data_array = $this->User_model->user_work_experience_form_validation($post_data);
				if ($validation_data_array['status'] == 'SUCCESS')
				{
					$listing_limit = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_listing_limit'):$this->config->item('personal_account_work_experience_section_listing_limit');
				
					$pagination_links  = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_number_of_pagination_links'):$this->config->item('personal_account_work_experience_section_number_of_pagination_links');
					
					$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
					
					$position_to_month = 0;
					$position_to_year = 0;
					$still_work = 0;
					
					if($post_data['still_work'] && $post_data['still_work'] == 'Y'){
						$still_work = 1;
					}else{
						$position_month = 0;
						$position_to_year = $post_data['year_to'];
						$position_to_month = $post_data['month_to'];
					}
					if(empty($post_data['graduate_inprogress']) &&  !empty($post_data['graduate_in'])){
						$graduate_in = $post_data['graduate_in'];
					}
					
					$users_work_experience = array(
						'user_id'=>$user_id,
						'position_name'=>trim($post_data['position_title']),
						'position_company_name'=>trim($post_data['company_name']),
						'position_company_address'=>trim($post_data['company_address']),
						'position_from_month'=>$post_data['month_from'],
						'position_from_year'=>$post_data['year_from'],
						'position_to_month'=>$position_to_month,
						'position_to_year'=>$position_to_year,
						'position_still_work'=>$still_work,
						'position_country_id'=>$post_data['company_country'],
						'position_description'=>trim($post_data['position_description'])
					);
					
					if(!empty($this->input->post ('section_id'))){
						$section_id = Cryptor::doDecrypt($this->input->post ('section_id'));
						
						$work_experience_detail = $this->db // get the user detail
						->select('we.*,countries.country_name,countries.country_code')
						->from('users_personal_accounts_work_experience we')
						->join('countries', 'countries.id = we.position_country_id', 'left')
						->where('we.id', $section_id)
						->get()->row_array();
						if(!empty($work_experience_detail)){
							
							$this->db->update('users_personal_accounts_work_experience', $users_work_experience, ['id' => $section_id,'user_id'=> $user_id]);
							
							$old_str = $work_experience_detail['position_name'].' '.$work_experience_detail['position_description'];
							$this->User_model->save_find_professionals_user_information($user_id, 'update', ' '.trim($post_data['position_title']).' '.trim($post_data['position_description']), $old_str);
							
							//profile completion script start 
							$count_user_work_experience = $this->db->where(['user_id'=>$user_id])->from('users_personal_accounts_work_experience')->count_all_results();
							$user_profile_completion_data['has_work_experience_indicated'] = 'Y';
							$user_profile_completion_data['work_experience_strength_value'] = $profile_completion_parameters['work_experience_strength_value'];
							$user_profile_completion_data['number_of_work_experiences_entries'] = $count_user_work_experience;
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
							//profile completion script end 
							
							
							
							$work_experience_detail = $this->db // get the user detail
							->select('we.*,countries.country_name,countries.country_code')
							->from('users_personal_accounts_work_experience we')
							->join('countries', 'countries.id = we.position_country_id', 'left')
							->where('we.id', $section_id)
							->get()->row_array();
							$data['work_experience_value'] = $work_experience_detail;
							echo json_encode(['status' => 200,'section_id'=>$section_id,'action'=>'update','status'=>'SUCCESS','message'=>'','location'=>'','data'=>$this->load->view('personal_account_work_experience_listing_entry_detail',$data, true)]);
							die;
						} else {
						
							$this->db->insert ('users_personal_accounts_work_experience', $users_work_experience);

							$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['position_title']).' '.trim($post_data['position_description']));

							$this->User_model->remove_scrambled_user_work_experience_entries($user_id);
							
							
							
							
							$start = 0;
							$work_experience_listing_data = 
							$this->User_model->get_user_work_experience_listing(array('user_id'=>$user_id),$start, $listing_limit);
							$data["work_experience_data"] = $work_experience_listing_data['data'];
							$data['work_experience_count'] = $work_experience_listing_data['total'];
							
							$paginations = generate_pagination_links($work_experience_listing_data['total'], $this->config->item('work_experience_page_url'), $listing_limit, $pagination_links);
							
							$data['work_experience_pagination_links'] = $paginations['links'];
							
							//profile completion script start 
							
							$user_profile_completion_data['has_work_experience_indicated'] = 'Y';
							$user_profile_completion_data['work_experience_strength_value'] = $profile_completion_parameters['work_experience_strength_value'];
							$user_profile_completion_data['number_of_work_experiences_entries'] = $work_experience_listing_data['total'];
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
							//profile completion script end 
							
							
							
							echo json_encode(['status' => 200,'action'=>'insert','status'=>'SUCCESS','message'=>'','location'=>'','data'=>$this->load->view('personal_account_work_experience_listing',$data, true)]);
							die;
						}
					
					
					
					
					} else {
						$this->db->insert ('users_personal_accounts_work_experience', $users_work_experience);

						$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['position_title']).' '.trim($post_data['position_description']));

						$this->User_model->remove_scrambled_user_work_experience_entries($user_id);
						
						
						$start = 0;
						$work_experience_listing_data = 
						$this->User_model->get_user_work_experience_listing(array('user_id'=>$user_id),$start, $listing_limit);
						$data["work_experience_data"] = $work_experience_listing_data['data'];
						$data['work_experience_count'] = $work_experience_listing_data['total'];
						
						$paginations = generate_pagination_links($work_experience_listing_data['total'], $this->config->item('work_experience_page_url'), $listing_limit, $pagination_links);
						
						$data['work_experience_pagination_links'] = $paginations['links'];
						
						//profile completion script start
						$user_profile_completion_data['has_work_experience_indicated'] = 'Y';
						$user_profile_completion_data['work_experience_strength_value'] = $profile_completion_parameters['work_experience_strength_value'];
						$user_profile_completion_data['number_of_work_experiences_entries'] = $work_experience_listing_data['total'];
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
						//profile completion script end 
						
						
						echo json_encode(['status' => 200,'action'=>'insert','status'=>'SUCCESS','message'=>'','location'=>'','data'=>$this->load->view('personal_account_work_experience_listing',$data, true)]);
						die;
					
					}
					$msg['status'] = 'SUCCESS';
					$msg['message'] = '';
					$msg['location'] = VPATH ;
				   echo json_encode ($msg);
				}else{
						echo json_encode ($validation_data_array);
						die;
				}
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH ;
				echo json_encode($msg);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	/**
	This function is used to make the popup of delete work experience confirmation .
	*/
	public function delete_user_work_experience_confirmation_popup_body(){
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('section_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$section_id = $this->input->post ('section_id');
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				
				if($user_id != Cryptor::doDecrypt($this->input->post ('u_id'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				/* $check_work_experience_data_exists = $this->db->where(['id' =>$section_id,'user_id'=>$user_id])->from('users_personal_accounts_work_experience')->count_all_results();
				if($check_work_experience_data_exists == 0){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('personal_account_user_delete_work_experience_entry_already_deleted')]);
					die;
				} */
				//if($check_work_experience_data_exists > 0 ){
					if($user[0]->is_authorized_physical_person == 'Y') {
						$company_account_app_delete_work_experience_confirmation_project_modal_body = $this->config->item('personal_account_delete_work_experience_confirmation_project_modal_body');
					} else {
						$company_account_app_delete_work_experience_confirmation_project_modal_body = $this->config->item('personal_account_delete_work_experience_confirmation_project_modal_body');
					}
					$confirmation_modal_title = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_delete_work_experience_confirmation_project_modal_title'):$this->config->item('personal_account_delete_work_experience_confirmation_project_modal_title');
					$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$company_account_app_delete_work_experience_confirmation_project_modal_body.'</div>';
					$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn default_popup_width_btn btnSave" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" class="btn blue_btn default_btn default_popup_width_btn delete_user_work_experience btnConfirm project_cancel_button width-auto"  data-attr="'.Cryptor::doEncrypt($section_id).'" data-uid="'.$this->input->post ('u_id').'" >'.$this->config->item('delete_btn_txt').'</button>';
					echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
					die;
					
				//}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	
	
	}
	
	
	/**
	This function is used to delete user work experience .
	*/
	public function delete_user_work_experience(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('section_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$section_id = Cryptor::doDecrypt($this->input->post ('section_id'));
			
			if(check_session_validity()){
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('u_id'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$user_work_exp = $this->db->get_where('users_personal_accounts_work_experience', ['id' => $section_id,'user_id'=>$user_id])->row_array();

					$this->db->delete('users_personal_accounts_work_experience', ['id' => $section_id,'user_id'=>$user[0]->user_id]);

					$old_str = $user_work_exp['position_name'].' '.$user_work_exp['position_description'];
					$this->User_model->save_find_professionals_user_information($user_id, 'delete', ' ', $old_str);

					if($this->input->post ('active_page')){
						$page = $this->input->post ('active_page');
					}else{
							$page = 1;
					}	
					
					
					$total_record = $this->db->from('users_personal_accounts_work_experience')->where(['user_id' => $user_id])->count_all_results(); 
					
					
					$listing_limit = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_listing_limit'):$this->config->item('personal_account_work_experience_section_listing_limit');
					
					$pagination_links  = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_number_of_pagination_links'):$this->config->item('personal_account_work_experience_section_number_of_pagination_links');
					
					$paginations = generate_pagination_links($total_record, $this->config->item('work_experience_page_url'), $listing_limit, $pagination_links);
					
					
					$work_experience_listing_data = 
					$this->User_model->get_user_work_experience_listing(array('user_id'=>$user_id),$paginations['offset'], $$listing_limit);
					
					
					$data["work_experience_data"] = $work_experience_listing_data['data'];
					$data['work_experience_count'] = $work_experience_listing_data['total'];
					
					
					$data['work_experience_pagination_links'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					
					$multiplication = $listing_limit * $page;
					$subtraction = ($multiplication - ($listing_limit - count($data['work_experience_data'])));
					$record_per_page = count($data['work_experience_data']) < $listing_limit ? $subtraction : $multiplication;
					$page_no = ($listing_limit * ($page - 1)) + 1;
					
					$check_work_experience_data_exists = $this->db->where(['user_id' =>$user_id])->from('users_personal_accounts_work_experience')->count_all_results();
					$initial_view_status = '0';
					if($check_work_experience_data_exists == 0){
						$initial_view_status = '1';
					}
					
					
					//profile completion script start 
					$user_profile_completion_data = array();
					if($check_work_experience_data_exists ==0){
						$user_profile_completion_data['has_work_experience_indicated'] = 'N';
						$user_profile_completion_data['work_experience_strength_value'] = 0;
						$user_profile_completion_data['number_of_work_experiences_entries'] = 0;
					}else{
					
						$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
						$user_profile_completion_data['has_work_experience_indicated'] = 'Y';
						$user_profile_completion_data['work_experience_strength_value'] = $profile_completion_parameters['work_experience_strength_value'];
						$user_profile_completion_data['number_of_work_experiences_entries'] = $check_work_experience_data_exists;
					}
					if(!empty($user_profile_completion_data)){	
					 $this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
					}
					//profile completion script end 
					
					
					
					
					echo json_encode(['location'=>'','initial_view_status'=>$initial_view_status,'status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$work_experience_listing_data['total'],'data'=>$this->load->view('personal_account_work_experience_listing',$data, true)]);
				//}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	
	//This function is calling by ajax paging regarding user work experience
	public function load_pagination_user_work_experience(){
		
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ 
				$page = $this->uri->segment(3);
				$user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				
				$listing_limit = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_listing_limit'):$this->config->item('personal_account_work_experience_section_listing_limit');
				
				$pagination_links  = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_number_of_pagination_links'):$this->config->item('personal_account_work_experience_section_number_of_pagination_links');
				
				
				 $total_record = $this->db->from('users_personal_accounts_work_experience')->where(['user_id' => $user_id])->count_all_results();
				 
				 $paginations = generate_pagination_links($total_record, $this->config->item('work_experience_page_url'), $listing_limit, $pagination_links);
				
				$work_experience_listing_data = 
				$this->User_model->get_user_work_experience_listing(array('user_id'=>$user_id),$paginations['offset'], $listing_limit);
				$data["work_experience_data"] = $work_experience_listing_data['data'];
				$data['work_experience_count'] = $work_experience_listing_data['total'];
				$data['work_experience_pagination_links'] = $paginations['links'];
				$page = $paginations['current_page_no'];
				
				$multiplication = $listing_limit * $page;
				$subtraction = ($multiplication - ($listing_limit - count($data['work_experience_data'])));
				$record_per_page = count($data['work_experience_data']) < $listing_limit ? $subtraction : $multiplication;
				$page_no = ($listing_limit * ($page - 1)) + 1;
				
				echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$work_experience_listing_data['total'],'data'=>$this->load->view('personal_account_work_experience_listing',$data, true)]);
				die;
				
			}else{
				echo json_encode(['status' => 400,'location'=>VPATH]);
				die;
			
			}
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}	
		
	
	}
	
	

	public function education_training() {
			// This code is used to set the page url into session when the specific url hit into browser 
			if(!$this->session->userdata ('user') && !$this->input->is_ajax_request()){
				$last_redirect_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$this->session->set_userdata ('last_redirect_url', $last_redirect_url);
			}

			if (!$this->session->userdata('user')) {
					redirect(VPATH . $this->config->item('signin_page_url'));
			}else{

			$user = $this->session->userdata('user');
			$listing_limit = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_listing_limit'):$this->config->item('personal_account_education_section_listing_limit');
					
			$pagination_links = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_number_of_pagination_links'):$this->config->item('personal_account_education_section_number_of_pagination_links');
			if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')){
				$data['user_id'] = $user[0]->user_id;
				if ($this->session->flashdata('settings_success')) {
					$data['active_account'] = 1;
				}
				$user_id = $user[0]->user_id;
				$name = (($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_PERSONAL_ACCOUNT_TYPE) || ($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_COMPANY_ACCOUNT_TYPE) && $user[0]->is_authorized_physical_person == 'Y') ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
				/* foreach ($this->user_data as $key => $value) {
					$data[$key] = $value;
				} */

				########## reset the education training start###
				$this->User_model->remove_scrambled_user_education_training_entries($user_id);
				########## reset the education training end###
				
				$data['current_page'] = 'education_training';
				$start = 0;
				$education_training_listing_data = 
				$this->User_model->get_user_education_training_listing(array('user_id'=>$user_id),$start, $listing_limit);
				$data["education_training_data"] = $education_training_listing_data['data'];
				$data['education_training_count'] = $education_training_listing_data['total'];
				
				$paginations = generate_pagination_links($education_training_listing_data['total'], $this->config->item('education_training_page_url'), $listing_limit, $pagination_links);
				
				
				$data['education_training_pagination_links'] = $paginations['links'];
				if($user[0]->is_authorized_physical_person == 'Y'){
					$education_training_title_meta_tag = $this->config->item('personal_account_education_training_page_title_meta_tag');
					$education_training_description_meta_tag = $this->config->item('personal_account_education_training_page_description_meta_tag');
				}else{
					$education_training_title_meta_tag = $this->config->item('company_account_app_education_training_page_title_meta_tag');
					$education_training_description_meta_tag = $this->config->item('company_account_app_education_training_page_description_meta_tag');
				}
				
				$education_training_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $education_training_title_meta_tag);
				
				$education_training_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $education_training_description_meta_tag);
				$data['meta_tag'] = '<title>' . $education_training_title_meta_tag . '</title><meta name="description" content="' . $education_training_description_meta_tag . '"/>';
				########## set the work experience title tag start end #########	
				$logstr = 'user ' . get_client_ip() . '/' . $this->session->userdata('user_log_id') . ' visited work experience page.';

				$this->layout->view('personal_account_education_training_management', '', $data, 'include');
			}else{
				show_custom_404_page(); //show custom 404 page
			}		
		}
	}
	
	// This function is used to load education trainning popup add/edit education form into popup.
	public function load_user_education_training_popup_body(){
	
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$data['countries'] = $this->Dashboard_model->get_countries();
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('u_id'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$action_type = $this->input->post ('action_type');
				$section_id = $this->input->post ('section_id');
				if($action_type == 'edit'){
				
					$check_education_data_exists = $this->db->where(['id' =>$section_id,'user_id'=>$user_id])->from('users_personal_accounts_education_training')->count_all_results();
					if($check_education_data_exists == 0){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$user[0]->is_authorized_physical_person == 'Y'?$this->config->item('company_account_app_user_edit_education_training_entry_already_deleted'):$this->config->item('personal_account_user_edit_education_training_entry_already_deleted')]);
						die;
					}
				
				}
				$education_data = array();
				if($action_type == 'edit' && !empty($section_id)){
					$education_data = $this->db // get the user detail
					->select('et.*')
					->from('users_personal_accounts_education_training et')
					->where('et.user_id', $user_id)
					->where('et.id', $section_id)
					->get()->row_array();
					
				}
				$data['education_data'] = $education_data;
				$res['education_training_popup_body'] = $this->load->view('personal_account_education_training_popup_body', $data, true);
				
				$res['status'] = 200;
				echo json_encode($res);
				die; 
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH;
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
		
	}
	
	//This function is used to save education training of user into database
	public function save_user_education_training(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				if($user_id != Cryptor::doDecrypt($post_data['u_id'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$section_id = Cryptor::doDecrypt($this->input->post ('section_id'));
				
				$listing_limit = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_listing_limit'):$this->config->item('personal_account_education_section_listing_limit');
					
				$pagination_links = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_number_of_pagination_links'):$this->config->item('personal_account_education_section_number_of_pagination_links');
				
				$validation_data_array = $this->User_model->user_education_training_form_validation($post_data);
				if ($validation_data_array['status'] == 'SUCCESS')
				{
					$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
					$school_country = 0;
					$graduate_inprogress = 0;
					$graduate_in = '';
					
					if(!empty($post_data['school_country'])){
						$school_country = $post_data['school_country'];
					}
					if($post_data['graduate_inprogress'] && $post_data['graduate_inprogress'] == 'Y'){
						$graduate_inprogress = 1;
					}
					if(empty($post_data['graduate_inprogress']) &&  !empty($post_data['graduate_in'])){
						$graduate_in = $post_data['graduate_in'];
					}
					
					$users_personal_accounts_education_training = array(
						'user_id'=>$user_id,
						'education_diploma_degree_name'=>trim($post_data['diploma_name']),
						'education_school_name'=>trim($post_data['school_name']),
						'education_school_address'=>trim($post_data['school_address']),
						'education_country_id'=>$school_country,
						'education_graduate_year'=>$graduate_in,
						'education_progress'=>$graduate_inprogress,
						'education_comments'=>trim($post_data['comment']),
					);
					if(!empty($this->input->post ('section_id'))){
						
						
						$education_detail = $this->db // get the user detail
						->select('et.*,countries.country_name,countries.country_code')
						->from('users_personal_accounts_education_training et')
						->join('countries', 'countries.id = et.education_country_id', 'left')
						->where('et.id', $section_id)
						->get()->row_array();
						if(!empty($education_detail)){
							$this->db->update('users_personal_accounts_education_training', $users_personal_accounts_education_training, ['id' => $section_id,'user_id'=> $user_id]);

							$old_str = ' '.$education_detail['education_diploma_degree_name'].' '.$education_detail['education_school_name'].' '.$education_detail['education_comments'];
							$this->User_model->save_find_professionals_user_information($user_id, 'update', ' '.trim($post_data['diploma_name']).' '.trim($post_data['school_name']).' '.trim($post_data['comment']), $old_str);
							
							// profile completion start 
							$count_user_education_training = $this->db->where(['user_id'=>$user_id])->from('users_personal_accounts_education_training')->count_all_results();
							
							$user_profile_completion_data['has_education_training_indicated'] = 'Y';
							$user_profile_completion_data['education_training_strength_value'] = $profile_completion_parameters['education_training_strength_value'];
							$user_profile_completion_data['number_of_education_training_entries'] = $count_user_education_training;
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
							// profile completion end 
							
							
							$education_detail = $this->db // get the user detail
							->select('et.*,countries.country_name,countries.country_code')
							->from('users_personal_accounts_education_training et')
							->join('countries', 'countries.id = et.education_country_id', 'left')
							->where('et.id', $section_id)
							->get()->row_array();
							$data['education_training_value'] = $education_detail;
							echo json_encode(['status' => 200,'section_id'=>$section_id,'action'=>'update','status'=>'SUCCESS','message'=>'','location'=>'','data'=>$this->load->view('personal_account_education_training_listing_entry_detail',$data, true)]);
							die;
						} else {
							$this->db->insert ('users_personal_accounts_education_training', $users_personal_accounts_education_training);

							$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['diploma_name']).' '.trim($post_data['school_name']).' '.trim($post_data['comment']));

							$this->User_model->remove_scrambled_user_education_training_entries($user_id);
							
							
							
							$start = 0;
							$education_training_listing_data = 
							$this->User_model->get_user_education_training_listing(array('user_id'=>$user_id),$start, $listing_limit);
							$data["education_training_data"] = $education_training_listing_data['data'];
							$data['education_training_count'] = $education_training_listing_data['total'];
							
							$paginations = generate_pagination_links($education_training_listing_data['total'], $this->config->item('education_training_page_url'), $listing_limit, $pagination_links);
							$data['education_training_pagination_links'] = $paginations['links'];
							
							
							// profile completion start 
							
							$user_profile_completion_data['has_education_training_indicated'] = 'Y';
							$user_profile_completion_data['education_training_strength_value'] = $profile_completion_parameters['education_training_strength_value'];
							$user_profile_completion_data['number_of_education_training_entries'] = $education_training_listing_data['total'];
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
							// profile completion end 
							
							
							echo json_encode(['status' => 200,'action'=>'insert','status'=>'SUCCESS','message'=>'','location'=>'','data'=>$this->load->view('personal_account_education_training_listing',$data, true)]);
							die;
						}
						
					} else {
						$this->db->insert ('users_personal_accounts_education_training', $users_personal_accounts_education_training);

						$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['diploma_name']).' '.trim($post_data['school_name']).' '.trim($post_data['comment']));

						$this->User_model->remove_scrambled_user_education_training_entries($user_id);
						
						$start = 0;
						$education_training_listing_data = 
						$this->User_model->get_user_education_training_listing(array('user_id'=>$user_id),$start, $listing_limit);
						$data["education_training_data"] = $education_training_listing_data['data'];
						$data['education_training_count'] = $education_training_listing_data['total'];

						$paginations = generate_pagination_links($education_training_listing_data['total'], $this->config->item('education_training_page_url'), $listing_limit, $pagination_links);
						$data['education_training_pagination_links'] = $paginations['links'];
						
						
						// profile completion start 
						$user_profile_completion_data['has_education_training_indicated'] = 'Y';
						$user_profile_completion_data['education_training_strength_value'] = $profile_completion_parameters['education_training_strength_value'];
						$user_profile_completion_data['number_of_education_training_entries'] = $education_training_listing_data['total'];
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
						// profile completion end
						
						echo json_encode(['status' => 200,'action'=>'insert','status'=>'SUCCESS','message'=>'','location'=>'','data'=>$this->load->view('personal_account_education_training_listing',$data, true)]);
						die;
						
						
					}
					
					
				}else{
					echo json_encode ($validation_data_array);
					die;
				}
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH;
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	/**
	* This function is used to make the popup of delete education confirmation .
	*/
	public function delete_user_education_confirmation_popup_body(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('section_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$section_id = $this->input->post ('section_id');
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				
				if($user_id != Cryptor::doDecrypt($this->input->post ('u_id'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				/* $check_education_data_exists = $this->db->where(['id' =>$section_id,'user_id'=>$user_id])->from('users_personal_accounts_education_training')->count_all_results();
				if($check_education_data_exists == 0){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('personal_account_user_delete_education_training_entry_already_deleted')]);
					die;
				} */
				
				
				//if($check_education_data_exists > 0 ){
					if($user[0]->is_authorized_physical_person == 'Y') {
						$company_account_app_delete_education_training_confirmation_project_modal_body = $this->config->item('company_account_app_delete_education_training_confirmation_project_modal_body');
					} else {
						$company_account_app_delete_education_training_confirmation_project_modal_body = $this->config->item('personal_account_delete_education_training_confirmation_project_modal_body');
					}
					
					$confirmation_modal_title = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_delete_education_training_confirmation_project_modal_title'):$this->config->item('personal_account_delete_education_training_confirmation_project_modal_title');
					$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$company_account_app_delete_education_training_confirmation_project_modal_body.'</div>';
					$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn default_popup_width_btn btnSave" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" class="btn blue_btn default_btn default_popup_width_btn delete_user_education btnConfirm project_cancel_button width-auto"  data-attr="'.Cryptor::doEncrypt($section_id).'" data-uid="'.$this->input->post ('u_id').'" >'.$this->config->item('delete_btn_txt').'</button>';
					echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
					die;
					
				//}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	
	
	}
	
	
	/**
	This function is used to delete user education .
	*/
	public function delete_user_education(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('section_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$section_id = Cryptor::doDecrypt($this->input->post ('section_id'));
			
			if(check_session_validity()){
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('u_id'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}

					$education_detail = $this->db->get_where('users_personal_accounts_education_training', ['id' => $section_id,'user_id' => $user_id])->row_array();
					$this->db->delete('users_personal_accounts_education_training', ['id' => $section_id,'user_id'=>$user[0]->user_id]);

					$old_str = ' '.$education_detail['education_diploma_degree_name'].' '.$education_detail['education_school_name'].' '.$education_detail['education_comments'];
					$this->User_model->save_find_professionals_user_information($user_id, 'delete', ' ', $old_str);

					if($this->input->post ('active_page')){
						$page = $this->input->post ('active_page');
					}else{
							$page = 1;
					}	
					
					
					$total_record = $this->db->from('users_personal_accounts_education_training')->where(['user_id' => $user_id])->count_all_results(); 
					
					
					// profile completion start 
					$user_profile_completion_data = array();
					if($total_record == 0){
				
						$user_profile_completion_data['has_education_training_indicated'] = 'N';
						$user_profile_completion_data['education_training_strength_value'] = 0;
						$user_profile_completion_data['number_of_education_training_entries'] = $total_record;
					}else{
						$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
						$user_profile_completion_data['has_education_training_indicated'] = 'Y';
						$user_profile_completion_data['education_training_strength_value'] = $profile_completion_parameters['education_training_strength_value'];
					
						$user_profile_completion_data['number_of_education_training_entries'] = $total_record;
					}
					if(!empty($user_profile_completion_data)){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
					}
					// profile completion end
					
					$listing_limit = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_listing_limit'):$this->config->item('personal_account_education_section_listing_limit');
					
					$pagination_links = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_number_of_pagination_links'):$this->config->item('personal_account_education_section_number_of_pagination_links');
					
					
					$paginations = generate_pagination_links($total_record, $this->config->item('education_training_page_url'), $listing_limit, $pagination_links);
					
					
					$education_training_listing_data = 
					$this->User_model->get_user_education_training_listing(array('user_id'=>$user_id),$paginations['offset'], $listing_limit);
					
					
					$data["education_training_data"] = $education_training_listing_data['data'];
					$data['education_training_count'] = $education_training_listing_data['total'];
					
					
					$data['education_training_pagination_links'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					
					$multiplication = $listing_limit * $page;
					$subtraction = ($multiplication - ($listing_limit - count($data['education_training_data'])));
					$record_per_page = count($data['education_training_data']) < $listing_limit ? $subtraction : $multiplication;
					$page_no = ($listing_limit * ($page - 1)) + 1;
					
					$check_education_data_exists = $this->db->where(['user_id' =>$user_id])->from('users_personal_accounts_education_training')->count_all_results();
					$initial_view_status = '0';
					if($check_education_data_exists == 0){
						$initial_view_status = '1';
					}
					
					echo json_encode(['location'=>'','initial_view_status'=>$initial_view_status,'status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$education_training_listing_data['total'],'data'=>$this->load->view('personal_account_education_training_listing',$data, true)]);
				//}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	
	//This function is calling by ajax paging regarding user education training
	public function load_pagination_user_education_training(){
		
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ 
				$page = $this->uri->segment(3);
				$user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				
				$total_record = $this->db->from('users_personal_accounts_education_training')->where(['user_id' => $user_id])->count_all_results();
				
				$listing_limit = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_listing_limit'):$this->config->item('personal_account_education_section_listing_limit');
					
				$pagination_links = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_number_of_pagination_links'):$this->config->item('personal_account_education_section_number_of_pagination_links');
					
				
				$paginations = generate_pagination_links($total_record, $this->config->item('education_training_page_url'), $listing_limit, $pagination_links);
				
				
				
				$education_training_listing_data = 
				$this->User_model->get_user_education_training_listing(array('user_id'=>$user_id),$paginations['offset'], $listing_limit);
				$data["education_training_data"] = $education_training_listing_data['data'];
				$data['education_training_count'] = $education_training_listing_data['total'];
				
				$page = $paginations['current_page_no'];
				
				$data['education_training_pagination_links'] = $paginations['links'];
				
				$multiplication = $listing_limit * $page;
				$subtraction = ($multiplication - ($listing_limit - count($data['education_training_data'])));
				$record_per_page = count($data['education_training_data']) < $listing_limit ? $subtraction : $multiplication;
				$page_no = ($listing_limit * ($page - 1)) + 1;
				
				echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$education_training_listing_data['total'],'data'=>$this->load->view('personal_account_education_training_listing',$data, true)]);
				die;
				
			}else{
				echo json_encode(['status' => 400,'location'=>VPATH]);
				die;
			
			}
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}	
		
	
	}
	
	//end of education & training
  public function certifications() {
		// This code is used to set the page url into session when the specific url hit into browser 
		if(!$this->session->userdata ('user') && !$this->input->is_ajax_request()){
			$last_redirect_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->session->set_userdata ('last_redirect_url', $last_redirect_url);
		}
	
	
        if (!$this->session->userdata('user')) {
            redirect(VPATH . $this->config->item('signin_page_url'));
        } else {

            $user = $this->session->userdata('user');
            $data['user_id'] = $user[0]->user_id;
            if ($this->session->flashdata('settings_success')) {
                $data['active_account'] = 1;
            }
            $user_id = $user[0]->user_id;
			
			

           
            /* foreach ($this->user_data as $key => $value) {
                $data[$key] = $value;
						} */
			$user_detail = $this->db // get the user detail
			->select('u.user_id,first_name,last_name,company_name,account_type,u.is_authorized_physical_person,ud.current_membership_plan_id,u.profile_name')
			->from('users u')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('u.user_id', $user_id)
			->get()->row_array(); 
			
			
			
			 $name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];
			 
		
			
			$this->User_model->remove_user_orphan_certificates_attachments(array('user_id'=>$user_id,'profile_name'=>$user_detail['profile_name'])); // remove orphan entries of portfolio images

			########## reset the certification start###
			$this->User_model->remove_scrambled_user_certifications_entries($user_id);
			########## reset the certification end###
            $data['current_page'] = 'certifications';
			$start = 0;
			$certifications_listing_data = 
			$this->User_model->get_user_certifications_listing(array('user_id'=>$user_id),$start, $this->config->item('user_certifications_section_listing_limit'));
			$data["certifications_data"] = $certifications_listing_data['data'];
			$data['certifications_count'] = $certifications_listing_data['total'];

			$paginations = generate_pagination_links($certifications_listing_data['total'], $this->config->item('certifications_page_url'), $this->config->item('user_certifications_section_listing_limit'), $this->config->item('user_certifications_section_number_of_pagination_links'));
			
			
			
			//$data['certifications_pagination_links'] = $this->generate_pagination_links_user_certifications($certifications_listing_data['total'], $this->config->item('certifications_page_url'),$this->config->item('user_certifications_section_listing_limit'));
			$data['certifications_pagination_links'] = $paginations['links'];
			if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
				$certifications_title_meta_tag = $this->config->item('pa_certifications_page_title_meta_tag');
				$certifications_title_meta_tag = str_replace('{user_first_name_last_name}', $name, $certifications_title_meta_tag);

				$certifications_description_meta_tag = $this->config->item('pa_certifications_page_description_meta_tag');
				$certifications_description_meta_tag = str_replace('{user_first_name_last_name}', $name, $certifications_description_meta_tag);
			}else if($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y') {
				$certifications_title_meta_tag = $this->config->item('ca_app_certifications_page_title_meta_tag');
				$certifications_title_meta_tag = str_replace('{user_first_name_last_name}', $name, $certifications_title_meta_tag);

				$certifications_description_meta_tag = $this->config->item('ca_app_certifications_page_description_meta_tag');
				$certifications_description_meta_tag = str_replace('{user_first_name_last_name}', $name, $certifications_description_meta_tag);
			} else {
				$certifications_title_meta_tag = $this->config->item('ca_certifications_page_title_meta_tag');
				$certifications_title_meta_tag = str_replace('{user_company_name}', $name, $certifications_title_meta_tag);

				$certifications_description_meta_tag = $this->config->item('ca_certifications_page_description_meta_tag');
				$certifications_description_meta_tag = str_replace('{user_company_name}', $name, $certifications_description_meta_tag);
			}
            
            
            $data['meta_tag'] = '<title>' . $certifications_title_meta_tag . '</title><meta name="description" content="' . $certifications_description_meta_tag . '"/>';
            ########## set the work experience title tag start end #########	
            $logstr = 'user ' . get_client_ip() . '/' . $this->session->userdata('user_log_id') . ' visited work experience page.';

            $this->layout->view('user_certifications_management', '', $data, 'include');
        }
  }
	 
	public function load_user_certifications_popup_body(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$data = array();
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('u_id'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$action_type = $this->input->post ('action_type');
				$section_id = $this->input->post ('section_id');
				$res['certifications_popup_heading'] = $this->config->item('user_certifications_section_popup_add_headline_title');
				if($action_type == 'edit'){
					$res['certifications_popup_heading'] = $this->config->item('user_certifications_section_popup_edit_headline_title');
				
					$check_certifications_data_exists = $this->db->where(['id' =>$section_id,'user_id'=>$user_id])->from('users_certifications')->count_all_results();
					if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) { 
						$user_edit_certifications_entry_already_deleted = $this->config->item('pa_user_edit_certifications_entry_already_deleted');
					} else {
						$user_edit_certifications_entry_already_deleted = $this->config->item('ca_user_edit_certifications_entry_already_deleted');
					}
					if($check_certifications_data_exists == 0){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$user_edit_certifications_entry_already_deleted]);
						die;
					}
				
				}
				
				$certifications_data = array();
				if($action_type == 'edit' && !empty($section_id)){
					$res['education_training_popup_heading'] = $this->config->item('user_certifications_section_popup_edit_headline_title');
					$certifications_data = $this->db // get the user detail
					->select('c.*')
					->from('users_certifications c')
					->where('c.user_id', $user_id)
					->where('c.id', $section_id)
					->get()->row_array();
					$certifications_data['attachments'] = $this->db->get_where('users_certifications_attachments', ['certificate_id' => $section_id])->result_array();
				}
				$data['certifications_data'] = $certifications_data;
				$res['certifications_popup_body'] = $this->load->view('user_certifications_popup_body', $data, true);
				$res['status'] = 200;
				$res['attachment_cnt'] = !empty($certifications_data['attachments']) ?  count($certifications_data['attachments']) : 0;
				$res['max_allow_limit'] = $this->config->item('user_certifications_section_maximum_allowed_number_of_attachments');
				echo json_encode($res);
				die; 
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH . $this->config->item('dashboard_page_url');
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}

	}
	
	public function save_user_certifications(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session

				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				if($user_id != Cryptor::doDecrypt($post_data['u_id'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$user_detail = $this->db // get the user detail
								->select('u.user_id,u.profile_name,ud.current_membership_plan_id')
								->from('users u')
								->join('users_details ud', 'ud.user_id = u.user_id', 'left')
								->where('u.user_id', $user_id)
								->get()->row_array(); 
				$validation_data_array = $this->User_model->user_certifications_form_validation($post_data);
				if ($validation_data_array['status'] == 'SUCCESS')
				{
				
						
						if(!empty($_FILES)){
							######## connectivity of remote server start#########
							$this->load->library('ftp');
							$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
							$config['ftp_username'] = FTP_USERNAME;
							$config['ftp_password'] = FTP_PASSWORD;
							$config['ftp_port'] 	= FTP_PORT;
							$config['debug']    = TRUE;
							$this->ftp->connect($config); 
							$upload_fail_file_arr = [];
							$row = $this->input->post();
							######## connectivity of remote server end #######
							$users_ftp_dir 	= USERS_FTP_DIR; 
							$user_certificates_dir = USER_CERTIFICATES;
							$profile_folder     = $user_detail['profile_name'];
							
							if(empty($this->ftp->check_ftp_directory_exist($users_ftp_dir))){
								echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
							}
							if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR)){
								echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
							}
							if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$user_certificates_dir)){
								echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
							}
							$this->ftp->close();
						}

				
					if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
						$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
					}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
						$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
					}
				
				
				
					$users_certifications = array(
						'user_id'=>$user_id,
						'certification_name'=>trim($post_data['certification_name']),
						'certification_month'=>$post_data['certification_month'],
						'certification_year'=>$post_data['certification_year']
					);
					if(!empty($this->input->post ('section_id'))){
							$section_id = Cryptor::doDecrypt($this->input->post ('section_id'));
							$certifications_detail = $this->db // get the user detail
							->select('c.*')
							->from('users_certifications c')
							->where('c.id', $section_id)
							->get()->row_array();
							if(!empty($certifications_detail)){
								
								$this->db->update('users_certifications', $users_certifications, ['id' => $section_id,'user_id'=> $user_id]);

								$this->User_model->save_find_professionals_user_information($user_id, 'update', ' '.trim($post_data['certification_name']), $certifications_detail['certification_name']);

								$extra_data['profile_name'] = $user_detail['profile_name'];
								$extra_data['user_id'] = $user_detail['user_id'];
								$extra_data['certificate_id'] = $section_id;
								$extra_data['action'] = 'edit';
								
								$this->upload_user_certifications_attachments($_FILES,$extra_data);
								
								// profile completion script start 
								$count_user_certifications = $this->db->where(['user_id'=>$user_id])->from('users_certifications')->count_all_results();
								$user_profile_completion_data['has_certifications_indicated'] = 'Y';
								$user_profile_completion_data['certifications_strength_value'] = $profile_completion_parameters['certifications_strength_value'];
								$user_profile_completion_data['number_of_certifications_entries'] = $count_user_certifications;
								$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
								// profile completion script end 
								
								
								$certifications_detail = $this->db // get the user detail
								->select('c.*')
								->from('users_certifications c')
								->where('c.id', $section_id)
								->get()->row_array();
								$certifications_detail['attachments'] = $this->db->get_where('users_certifications_attachments', ['certificate_id' => $section_id])->result_array();
								$data['certifications_value'] = $certifications_detail;
								echo json_encode(['status' => 200,'section_id'=>$section_id,'action'=>'update','status'=>'SUCCESS','message'=>'','location'=>'','data'=>$this->load->view('user_certifications_listing_entry_detail',$data, true)]);
								die;
							}else{

							

								$this->db->insert ('users_certifications', $users_certifications);
								$last_insert_id = $this->db->insert_id();

								$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['certification_name']));

								$extra_data['profile_name'] = $user_detail['profile_name'];
								$extra_data['user_id'] = $user_detail['user_id'];
								$extra_data['certificate_id'] = $last_insert_id;
								$this->upload_user_certifications_attachments($_FILES,$extra_data);

								$this->User_model->remove_scrambled_user_certifications_entries($user_id);
								$start = 0;
								$certifications_listing_data = 
								$this->User_model->get_user_certifications_listing(array('user_id'=>$user_id),$start, $this->config->item('user_certifications_section_listing_limit'));
								$data["certifications_data"] = $certifications_listing_data['data'];
								$data['certifications_count'] = $certifications_listing_data['total'];
								
								$paginations = generate_pagination_links($certifications_listing_data['total'], $this->config->item('certifications_page_url'), $this->config->item('user_certifications_section_listing_limit'), $this->config->item('user_certifications_section_number_of_pagination_links'));
								
								
								// profile completion script start 
								$user_profile_completion_data['has_certifications_indicated'] = 'Y';
								$user_profile_completion_data['certifications_strength_value'] = $profile_completion_parameters['certifications_strength_value'];
								$user_profile_completion_data['number_of_certifications_entries'] = $certifications_listing_data['total'];
								
								
								
								$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
								// profile completion script end 
								
								
								$data['certifications_pagination_links'] = $paginations['links'];
								echo json_encode(['status' => 200,'action'=>'insert','status'=>'SUCCESS','message'=>'','location'=>'','data'=>$this->load->view('user_certifications_listing',$data, true)]);
								die;
								
							}
					
					} else {
							

							$this->db->insert ('users_certifications', $users_certifications);
							$last_insert_id = $this->db->insert_id();
							
							$extra_data['profile_name'] = $user_detail['profile_name'];
							$extra_data['user_id'] = $user_detail['user_id'];
							$extra_data['certificate_id'] = $last_insert_id;
							
							$this->upload_user_certifications_attachments($_FILES,$extra_data);

							$this->User_model->save_find_professionals_user_information($user_id, 'insert', trim($post_data['certification_name']));

							
							

							$this->User_model->remove_scrambled_user_certifications_entries($user_id);
							$start = 0;
							$certifications_listing_data = 
							$this->User_model->get_user_certifications_listing(array('user_id'=>$user_id),$start, $this->config->item('user_certifications_section_listing_limit'));
							$data["certifications_data"] = $certifications_listing_data['data'];
							$data['certifications_count'] = $certifications_listing_data['total'];
							$paginations = generate_pagination_links($certifications_listing_data['total'], $this->config->item('certifications_page_url'), $this->config->item('user_certifications_section_listing_limit'), $this->config->item('user_certifications_section_number_of_pagination_links'));
							$data['certifications_pagination_links'] = $paginations['links'];
							
							/* echo "<pre>";
							print_r($certifications_listing_data);
							die; */
							// profile completion script start 
							$user_profile_completion_data['has_certifications_indicated'] = 'Y';
							$user_profile_completion_data['certifications_strength_value'] = $profile_completion_parameters['certifications_strength_value'];
							$user_profile_completion_data['number_of_certifications_entries'] = $certifications_listing_data['total'];
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
							// profile completion script end 
							
							
							
							echo json_encode(['status' => 200,'action'=>'insert','status'=>'SUCCESS','message'=>'','location'=>'','data'=>$this->load->view('user_certifications_listing',$data, true)]);
							die;
					
					}
					$msg['status'] = 'SUCCESS';
					$msg['message'] = '';
					$msg['location'] = VPATH ;
				    echo json_encode ($msg);
				
				
				}else{
					echo json_encode ($validation_data_array);
					die;
				}
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH;
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
			return;
		}

	}
	
	/**
	 * This method is used to check attachments uploaded for certificate is exist
	*/
	public function ajax_check_certificate_attachment_exist() {
		if($this->input->is_ajax_request ()){
			if(check_session_validity()) {
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				$session_conflict = true;
				if(!empty($post_data['current_page']) && $post_data['current_page']== 'user_profile') {
					$session_conflict = false;
				}
				if($session_conflict) {
					if($user_id != Cryptor::doDecrypt($post_data['u_id'])){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						return;
					}
				} else {
					$user_id = Cryptor::doDecrypt($post_data['u_id']);
				}
				
				$user_detail = $this->db->get_where('users', ['user_id' => $user_id])->row_array();
			
				$attachment = $this->db->get_where('users_certifications_attachments', ['id' => $post_data['id'], 'user_id' => $user_id])->row_array();
				if(!empty($attachment)) {
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$user_certificates_dir = USER_CERTIFICATES;
					$profile_folder = $user_detail['profile_name'];
					$destination_path = $users_ftp_dir.$profile_folder.$user_certificates_dir.$attachment['certificate_id']. DIRECTORY_SEPARATOR .$attachment['attachment_name'];
					
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->ftp->connect($config); 
					$original_file_size = $this->ftp->get_filesize($destination_path);
					$this->ftp->close();
					if($original_file_size == '-1') {
						$this->db->delete('users_certifications_attachments', ['id' => $attachment['id']]);
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('user_certifications_section_download_attachment_not_exist_error_message')]);
						return;
					} else {
						$res['status'] = 200;
						$res['path'] = site_url () . 'user/download_certificate_attachment/'.$attachment['id'].'/'.$user_id;
						echo json_encode($res);
						return;
					}
				} else {
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('user_certifications_section_download_attachment_not_exist_error_message')]);
					return;
				}
			} else {
				$res['status'] = 400;
				$res['location'] = VPATH;
				echo json_encode($res);
				return;
			}
		} else {
			show_custom_404_page(); //show custom 404 page
			return;
		}
	}
	/**
	 * This method is used to remove uploaded attachement from disk and db for certificate
	*/
	public function ajax_remove_uploaded_certificate_attachment() {
		if($this->input->is_ajax_request ()){
			if(check_session_validity()) {
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				if($user_id != Cryptor::doDecrypt($post_data['u_id'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					return;
				}
				$attachment = $this->db->get_where('users_certifications_attachments', ['id' => $post_data['id'], 'user_id' => $user_id])->row_array();
				if(!empty($attachment)) {
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$user_certificates_dir = USER_CERTIFICATES;
					$profile_folder = $user[0]->profile_name;
					$destination_path = $users_ftp_dir.$profile_folder.$user_certificates_dir.$attachment['certificate_id']. DIRECTORY_SEPARATOR .$attachment['attachment_name'];
					
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->ftp->connect($config); 
					
					$original_file_size = $this->ftp->get_filesize($destination_path);
					
					$this->db->delete('users_certifications_attachments', ['id' => $attachment['id']]);
					if($original_file_size != '-1') {
						$this->ftp->delete_file($destination_path);
					}
					$this->ftp->close();
					
				}
				$res['status'] = 200;
				echo json_encode($res);
				return;
				/* } else {
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('user_certifications_section_attachment_already_deleted_error_message')]);
					return;
				} */
			} else {
				$res['status'] = 400;
				$res['location'] = VPATH;
				echo json_encode($res);
				return;
			}
		} else {
			show_custom_404_page(); //show custom 404 page
			return;
		}
	}
	/**
	 * This method is used to download certificate attachment 
	*/
	public function download_certificate_attachment() {
		if($this->session->userdata ('user')){
			$this->load->helper('download');
			$attachment_id = $this->uri->segment(3);
			$user = $this->session->userdata ('user');
			$user_id = $this->uri->segment(4);
			$user_detail = $this->db->get_where('users', ['user_id' => $user_id])->row_array();
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
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$user_certificates_dir = USER_CERTIFICATES;
			$profile_folder     = $user_detail['profile_name'];
			$attachment = $this->db->get_where('users_certifications_attachments', ['id' => $attachment_id])->row_array();
			
			$source_path = $users_ftp_dir.$profile_folder.$user_certificates_dir.DIRECTORY_SEPARATOR .$attachment['certificate_id']. DIRECTORY_SEPARATOR .$attachment['attachment_name'];
			
			$file_size = $this->ftp->get_filesize($source_path);
			
			if($file_size != '-1') {
				$destination_path =  FCPATH .TEMP_DIR.$attachment['attachment_name'];
				$this->ftp->download($source_path,$destination_path, 'auto', 0777);
				$this->ftp->close();
				$data = file_get_contents (TEMP_DIR.$attachment['attachment_name'] );// read the content of file
				unlink(TEMP_DIR.$attachment['attachment_name'] );
				force_download ($attachment['attachment_name'],$data);
			} else {
				show_custom_404_page(); //show custom 404 page
				return;
			}
		} 
	}
	/**
	 * This method is used to upload certificate attachments on disk
	*/
	public function upload_user_certifications_attachments($file_array,$data) {
		$profile_name = $data['profile_name'];
		$certificate_id = $data['certificate_id'];
		$user_id = $data['user_id'];
		######## connectivity of remote server start#########
		$this->load->library('ftp');
		$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
		$config['ftp_username'] = FTP_USERNAME;
		$config['ftp_password'] = FTP_PASSWORD;
		$config['ftp_port'] 	= FTP_PORT;
		$config['debug']    = TRUE;
		$this->ftp->connect($config); 
		$upload_fail_file_arr = [];
		$row = $this->input->post();
		######## connectivity of remote server end #######
		$users_ftp_dir 	= USERS_FTP_DIR; 
		$user_certificates_dir = USER_CERTIFICATES;
		$profile_folder     = $profile_name;
		if(!empty($file_array)){
			if(empty($this->ftp->check_ftp_directory_exist($users_ftp_dir))){
				echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
			}
			if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR)){
				echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
			}
			if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$user_certificates_dir)){
				echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
			}
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$user_certificates_dir .$certificate_id.DIRECTORY_SEPARATOR);
		}
		
		
		if(!empty($data['action']) && $data['action'] == 'edit') {
			$attchment_cnt = $this->db->from('users_certifications_attachments')->where(['certificate_id' => $certificate_id, 'user_id' => $user_id])->count_all_results();
			$max_limit = $this->config->item('user_certifications_section_maximum_allowed_number_of_attachments');
			if(!empty($file_array) && (count($file_array['files']['name']) + $attchment_cnt) > $max_limit) {
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('user_certifications_section_user_uploaded_maximum_attachments_error_message')]);
				die;
			}
		}

		if(!empty($file_array)){
			
			foreach($file_array['files']['name'] as $key => $val) {
				$temp 		= 	explode(".", $val);
				$extension 	= 	end($temp);
				$attachment_name = round(microtime(true) * 1000);
				$temp_attachment_name = $attachment_name.'.'.$extension;
				
				if(move_uploaded_file($file_array['files']['tmp_name'][$key], TEMP_DIR.$temp_attachment_name)){ 
					$source_path = FCPATH .TEMP_DIR. $temp_attachment_name;		
					$destination_path = $users_ftp_dir.$profile_folder.$user_certificates_dir.DIRECTORY_SEPARATOR .$certificate_id. DIRECTORY_SEPARATOR .$temp_attachment_name;
					if(!$this->ftp->upload($source_path,$destination_path , 'auto', 0777)) { 
						// upload attachment on remote server
						array_push($upload_fail_file_arr, $row['file_name'][$key]);
					} else {
						$this->db->insert ('users_certifications_attachments', array('user_id' => $user_id,'certificate_id' => $certificate_id, 'attachment_name' =>$temp_attachment_name)); 
					}
					unlink(FCPATH .TEMP_DIR. $temp_attachment_name); 
				} else {
					
					array_push($upload_fail_file_arr, $row['file_name'][$key]);
				}
			}
		}
		$this->ftp->close();
	}

	/**
	* This function is used to make the popup of delete certifications confirmation .
	*/
	public function delete_user_certifications_confirmation_popup_body(){
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('section_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$section_id = $this->input->post ('section_id');
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				
				if($user_id != Cryptor::doDecrypt($this->input->post ('u_id'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$check_certifications_data_exists = $this->db->where(['id' =>$section_id,'user_id'=>$user_id])->from('users_certifications')->count_all_results();
				if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) { 
					//$user_delete_certifications_entry_already_deleted = $this->config->item('pa_user_delete_certifications_entry_already_deleted'); 
					$user_delete_certifications_confirmation_project_modal_body = $this->config->item('pa_user_delete_certifications_confirmation_project_modal_body'); 
				} else {
					//$user_delete_certifications_entry_already_deleted = $this->config->item('ca_user_delete_certifications_entry_already_deleted'); 
					$user_delete_certifications_confirmation_project_modal_body = $this->config->item('ca_user_delete_certifications_confirmation_project_modal_body'); 
				}
				/* if($check_certifications_data_exists == 0){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$user_delete_certifications_entry_already_deleted]);
					die;
				} */
				/* if($check_certifications_data_exists > 0 ){ */
					
					$confirmation_modal_title = $this->config->item('user_delete_certifications_confirmation_project_modal_title');
					$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$user_delete_certifications_confirmation_project_modal_body.'</div>';
					$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn default_popup_width_btn btnSave" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" class="btn blue_btn default_btn default_popup_width_btn delete_user_certifications btnConfirm project_cancel_button width-auto"  data-attr="'.Cryptor::doEncrypt($section_id).'" data-uid="'.$this->input->post ('u_id').'" >'.$this->config->item('delete_btn_txt').'</button>';
					echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
					die;
					
				//}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	
	
	}
	
	
	/**
	* This function is used to delete user certifications .
	*/
	public function delete_user_certifications(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('section_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$section_id = Cryptor::doDecrypt($this->input->post ('section_id'));
			
			if(check_session_validity()){
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('u_id'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
					if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
						$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');

					} else if($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
						$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
					}
					
					$user_certificate = $this->db->get_where('users_certifications', ['id' => $section_id,'user_id'=>$user[0]->user_id])->row_array();

					$this->db->delete('users_certifications', ['id' => $section_id,'user_id'=>$user_id]);

					$this->User_model->save_find_professionals_user_information($user_id, 'delete', ' ', $user_certificate['certification_name']);


					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->ftp->connect($config); 
					######## connectivity of remote server end #######
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$user_certificates_dir = USER_CERTIFICATES;
					$profile_folder     = $user[0]->profile_name;
					
					if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$user_certificates_dir.$section_id))){
						$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$user_certificates_dir.$section_id);
					}
					$this->ftp->close();

					if($this->input->post ('active_page')){
						$page = $this->input->post ('active_page');
					}else{
							$page = 1;
					}
					$total_record = $this->db->from('users_certifications')->where(['user_id' => $user_id])->count_all_results(); 
					// profile completion script start 
					$user_profile_completion_data = array();
					if($total_record == 0){
						$user_profile_completion_data['has_certifications_indicated'] = 'N';
						$user_profile_completion_data['certifications_strength_value'] = 0;
						$user_profile_completion_data['number_of_certifications_entries'] = 0;
					}else{
					
					
						$user_profile_completion_data['has_certifications_indicated'] = 'Y';
						$user_profile_completion_data['certifications_strength_value'] = $profile_completion_parameters['certifications_strength_value'];
						$user_profile_completion_data['number_of_certifications_entries'] = $total_record;
					}
					if(!empty($user_profile_completion_data)){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
					}
					// profile completion script end 
					
					$paginations = generate_pagination_links($total_record, $this->config->item('certifications_page_url'),$this->config->item('user_certifications_section_listing_limit'),$this->config->item('user_certifications_section_number_of_pagination_links'));
					
					$certifications_listing_data = 
					$this->User_model->get_user_certifications_listing(array('user_id'=>$user_id),$paginations['offset'], $this->config->item('user_certifications_section_listing_limit'));
					
					
					$data["certifications_data"] = $certifications_listing_data['data'];
					$data['certifications_count'] = $certifications_listing_data['total'];
					$data['certifications_pagination_links'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					
					$multiplication = $this->config->item('user_certifications_section_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('user_certifications_section_listing_limit') - count($data['certifications_data'])));
					$record_per_page = count($data['certifications_data']) < $this->config->item('user_certifications_section_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('user_certifications_section_listing_limit') * ($page - 1)) + 1;
					
					$check_certifications_data_exists = $this->db->where(['user_id' =>$user_id])->from('users_certifications')->count_all_results();
					$initial_view_status = '0';
					if($check_certifications_data_exists == 0){
						$initial_view_status = '1';
					}
					
					echo json_encode(['location'=>'','initial_view_status'=>$initial_view_status,'status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$certifications_listing_data['total'],'data'=>$this->load->view('user_certifications_listing',$data, true)]);
				//}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	//This function is calling by ajax paging regarding user certifications
	public function load_pagination_user_certifications(){
		
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ 
				$page = $this->uri->segment(3);
				$user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				/* if($page != null) {
					
					if($page == 1) {
						$start = 0;
					} else {
						
						$start = ($page - 1) * $this->config->item('user_certifications_section_listing_limit');
					}
				} else {
					$start = 0;
				} */
				
				 $total_record = $this->db->from('users_certifications')->where(['user_id' => $user_id])->count_all_results();
				 
				$paginations = generate_pagination_links($total_record, $this->config->item('certifications_page_url'),$this->config->item('user_certifications_section_listing_limit'),$this->config->item('user_certifications_section_number_of_pagination_links'));
				
				$certifications_listing_data = 
				$this->User_model->get_user_certifications_listing(array('user_id'=>$user_id),$paginations['offset'], $this->config->item('user_certifications_section_listing_limit'));
				$data["certifications_data"] = $certifications_listing_data['data'];
				$data['certifications_count'] = $certifications_listing_data['total'];
				
				
				
				/* $data['certifications_pagination_links'] = $this->generate_pagination_links_user_certifications($certifications_listing_data['total'], $this->config->item('certifications_page_url'),$this->config->item('user_certifications_section_listing_limit')); */
				$data['certifications_pagination_links'] = $paginations['links'];
				$page = $paginations['current_page_no'];
				$multiplication = $this->config->item('user_certifications_section_listing_limit') * $page;
				$subtraction = ($multiplication - ($this->config->item('user_certifications_section_listing_limit') - count($data['certifications_data'])));
				$record_per_page = count($data['certifications_data']) < $this->config->item('user_certifications_section_listing_limit') ? $subtraction : $multiplication;
				$page_no = ($this->config->item('user_certifications_section_listing_limit') * ($page - 1)) + 1;
				
				echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$certifications_listing_data['total'],'data'=>$this->load->view('user_certifications_listing',$data, true)]);
				die;
				
			}else{
				echo json_encode(['status' => 400,'location'=>VPATH]);
				die;
			
			}
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}	
		
	
	}
	
	
	

  public function portfolio() {
		
		// This code is used to set the page url into session when the specific url hit into browser 
		if(!$this->session->userdata ('user') && !$this->input->is_ajax_request()){
			$last_redirect_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->session->set_userdata ('last_redirect_url', $last_redirect_url);
		}
        if (!$this->session->userdata('user')) {
            redirect(VPATH . $this->config->item('signin_page_url'));
        } else {

            $user = $this->session->userdata('user');
            $data['user_id'] = $user[0]->user_id;
            if ($this->session->flashdata('settings_success')) {
                $data['active_account'] = 1;
            }
            $user_id = $user[0]->user_id;
			
			if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
				$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');

			}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
			$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
			}
			
			
			
			
			 $user_detail = $this->db // get the user detail
			->select('u.user_id,first_name,last_name,company_name,account_type,u.is_authorized_physical_person,ud.current_membership_plan_id,u.profile_name')
			->from('users u')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('u.user_id', $user_id)
			->get()->row_array();           
			$data['user_detail'] = $user_detail;
			
			$this->User_model->remove_user_orphan_portfolio_images(array('user_id'=>$user_id,'profile_name'=>$user_detail['profile_name'])); // remove orphan entries of portfolio images
			
        
			
			 $name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];
			
           /*  foreach ($this->user_data as $key => $value) {
                $data[$key] = $value;
            } */
            $data['current_page'] = 'portfolio';
			
			$start = 0;
			$portfolio_listing_data = 
			$this->User_model->get_user_portfolio_listing(array('user_id'=>$user_id),$start, $this->config->item('user_portfolio_section_listing_limit'));
			$data["portfolio_data"] = $portfolio_listing_data['data'];
			$data['portfolio_count'] = $portfolio_listing_data['total'];
			
			$paginations = generate_pagination_links($portfolio_listing_data['total'], $this->config->item('portfolio_page_url'), $this->config->item('user_portfolio_section_listing_limit'), $this->config->item('user_portfolio_section_number_of_pagination_links'));
			$data['portfolio_pagination_links'] = $paginations['links'];
			$user_profile_completion_data = array();
			
			
			
			if($portfolio_listing_data['total'] == 0){
				$user_profile_completion_data['has_portfolio_indicated'] = 'N';
				$user_profile_completion_data['portfolio_strength_value'] = 0;
				$user_profile_completion_data['number_of_portfolios_entries'] = 0;
			}else{
				$user_profile_completion_data['has_portfolio_indicated'] = 'Y';
				$user_profile_completion_data['portfolio_strength_value'] = $profile_completion_parameters['portfolio_strength_value'];
				$user_profile_completion_data['number_of_portfolios_entries'] = $portfolio_listing_data['total'];

			}
			
			if(!empty($user_profile_completion_data)){
				$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
			}
			
			
            $portfolio_title_meta_tag = $this->config->item('portfolio_page_title_meta_tag');
            $portfolio_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $portfolio_title_meta_tag);
            $portfolio_description_meta_tag = $this->config->item('portfolio_page_description_meta_tag');
            $portfolio_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $portfolio_description_meta_tag);
            $data['meta_tag'] = '<title>' . $portfolio_title_meta_tag . '</title><meta name="description" content="' . $portfolio_description_meta_tag . '"/>';
            ########## set the portfolio title tag start end #########	
            $logstr = 'user ' . get_client_ip() . '/' . $this->session->userdata('user_log_id') . ' visited portfolio page.';

            $this->layout->view('user_portfolio_management', '', $data, 'include');
        }
  }
	
	
	public function portfolio_standalone_page() 
	{ 	
		$portfolio_data = array();
		if(empty($this->input->get('id'))){
			//$data['current_page'] = '404_default';
			########## set the default 404 title meta tag and meta description  start here #########
			$default_404_page_title_meta_tag = $this->config->item('404_page_title_meta_tag');
			$default_404_page_description_meta_tag = $this->config->item('404_page_description_meta_tag');
			$data['meta_tag'] = '<title>' . $default_404_page_title_meta_tag . '</title><meta name="description" content="' . $default_404_page_description_meta_tag . '"/>';
			########## set the default 404 title meta tag and meta description  end here #########
			set_status_header(404);
			$this->layout->view ('404defaultpage/404_default', $lay, $data, 'error_404'); 
			
		}else{
			$portfolio_id = $this->input->get('id');
			$portfolio_data = $this->db // get the user detail
			->select('up.*,upcp.standalone_portfolio_page_id,upcp.standalone_portfolio_page_cover_picture_name,upcp.standalone_portfolio_page_cover_picture_upload_date,ud.user_total_avg_rating_as_sp')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = ud.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = ud.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
			->from('users_portfolios up')
			->join('users_standalone_portfolio_page_cover_picture_tracking upcp', 'upcp.standalone_portfolio_page_id = up.portfolio_id', 'left')
			->join('users_details ud', 'ud.user_id = up.user_id', 'left')
			->where('up.portfolio_id', $portfolio_id)
			->get()->row_array();
			
			
			
			if(empty($portfolio_data)){
				set_status_header(404);
				########## set the default 404 title meta tag and meta description  start here #########
				$lay = array();
				$default_404_page_title_meta_tag = $this->config->item('404_page_title_meta_tag');
				$default_404_page_description_meta_tag = $this->config->item('404_page_description_meta_tag');
				$data['meta_tag'] = '<title>' . $default_404_page_title_meta_tag . '</title><meta name="description" content="' . $default_404_page_description_meta_tag . '"/>';
				########## set the default 404 title meta tag and meta description  end here #########
				$this->layout->view ('404defaultpage/404_default', $lay, $data, 'error_404'); 
		
			}else{
				$data['current_page'] = 'portfolio_standalone_page';
				$user_detail = $this->db // get the user detail
				->select('u.user_id,u.profile_name ,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,ud.user_avatar')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $portfolio_data['user_id'])
				->get()->row_array();
				
				if($this->session->userdata('user')) {
					$user = $this->session->userdata('user');
					$contact_data = $this->db->get_where('users_contacts_tracking', ['contact_initiated_by' => $user[0]->user_id, 'contact_requested_to' => $user_detail['user_id'] ])->row_array();
					if(!empty($contact_data)) {
						$data['is_in_contact'] = true;
					}
				}
				
				
				
				$this->User_model->remove_user_orphan_portfolio_images(array('user_id'=>$portfolio_data['user_id'],'profile_name'=>$user_detail['profile_name'])); // remove orphan entries of portfolio images
				
				##############Start remove the orphan data from database and portfolio standalone page image
				
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] = FTP_PORT;
				$config['debug'] = TRUE;
				$this->ftp->connect($config);
				
				$users_ftp_dir = USERS_FTP_DIR;
				$profile_folder = $user_detail['profile_name'];
				$upload_folder = USER_AVATAR;
				$user_portfolio_dir = USER_PORTFOLIO;
				$user_standalone_portfolio_page_cover_picture = USER_STANDALONE_PORTFOLIO_PAGE_COVER_PICTURE;
				$source_path = $users_ftp_dir.$profile_folder.$user_portfolio_dir .$portfolio_data['portfolio_id'].$user_standalone_portfolio_page_cover_picture ;
				
				$old_file = '';
				//$old_org_file = '';
				$user_cover_picture_exist_status = true;
				if(!empty($portfolio_data['standalone_portfolio_page_cover_picture_name'])){
				
					$old_file = $portfolio_data['standalone_portfolio_page_cover_picture_name'];
					$ex = explode('.', $portfolio_data['standalone_portfolio_page_cover_picture_name']);
					//$old_org_file = $ex[0] . '_original.png';
					$check_picture = $this->ftp->get_filesize($source_path . $old_file);
					//$check_picture_org = $this->ftp->get_filesize($source_path . $old_org_file );
				}
				if(empty($portfolio_data['standalone_portfolio_page_cover_picture_name']) || $check_picture == '-1'){
					$this->db->delete('users_standalone_portfolio_page_cover_picture_tracking', array("standalone_portfolio_page_id" => $portfolio_data['portfolio_id'],'user_id'=>$portfolio_data['user_id']));
					if(!empty($this->ftp->check_ftp_directory_exist($source_path))){
						$this->ftp->delete_dir($source_path);
					}
					$user_cover_picture_exist_status = false;
				}
				
				
				
				
				
				//$this->ftp->close();
				############# End ################
				
				
				/* $this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] = FTP_PORT;
				$config['debug'] = TRUE;
				$this->ftp->connect($config); */
				
				$source_path = $users_ftp_dir . $profile_folder . $upload_folder ;
				$check_avatar = $this->ftp->get_filesize($source_path . $user_detail['user_avatar']);
				
				$this->ftp->close();
				$user_avatar_exist_status = false;
				if ($check_avatar != '-1') {
					$user_avatar_exist_status = true;
					
				}
				
				$check_portfolio_image_exists = $this->db->where(['standalone_portfolio_page_id' => $portfolio_data['portfolio_id']])->from('users_standalone_portfolio_page_cover_picture_tracking')->count_all_results();
				if($check_portfolio_image_exists == 0){
					
					$user_cover_picture_exist_status = false;
					unset($portfolio_data['standalone_portfolio_page_cover_picture_name']);
					/* $portfolio_data['standalone_portfolio_page_cover_picture_name'] == '';
					echo "<pre>";
					print_r($portfolio_data);
					die; */
				}
				
				$data['user_detail']=$user_detail;
				$data['portfolio_data']=$portfolio_data;
				$data['user_avatar_exist_status']= $user_avatar_exist_status;
				$data['user_cover_picture_exist_status']= $user_cover_picture_exist_status;
				######################## meta tag and meta description ##################
				$portfolio_title_meta_tag = strip_tags($portfolio_data['title']);
				$portfolio_title_meta_tag = get_correct_string_based_on_limit($portfolio_title_meta_tag, $this->config->item('portfolio_standalone_page_title_meta_tag_character_limit'));
				$portfolio_title_meta_tag = $portfolio_title_meta_tag;
				$portfolio_description_meta_tag = strip_tags($portfolio_data['description']);
				
				$portfolio_description_meta_tag = get_correct_string_based_on_limit($portfolio_description_meta_tag, $this->config->item('portfolio_standalone_page_description_meta_description_character_limit'));

				$_SESSION['share_title_short'] = $portfolio_title_meta_tag;
				$_SESSION['share_description'] = get_correct_string_based_on_limit(htmlspecialchars($portfolio_data['description'], ENT_QUOTES), $this->config->item('facebook_and_linkedin_share_project_description_character_limit'));
				$_SESSION['share_url'] = base_url().$this->config->item('portfolio_standalone_page_url').'?id='.$portfolio_data['portfolio_id'];

				$_SESSION['share_image'] = URL.$this->config->item('facebook_share_image_path').'?'.time();
				$_SESSION['share_image_height'] = $this->config->item('facebook_share_image_height');
				$_SESSION['share_image_width'] = $this->config->item('facebook_share_image_width');

				if(!$this->session->userdata('user') && !empty($_GET["rfrd"])) {
					$cookie= array(
					'name'   => 'referral_code',
					'value'  => $_GET["rfrd"],
					'expire' => '3600',
					'path' => '/',
					'httponly' => false
					);
					$this->input->set_cookie($cookie);
				}

				if($this->session->userdata('user')) {
					$user = $this->session->userdata('user');
					$data['fb_share_url'] = base_url($this->config->item('portfolio_standalone_page_url')).'?id='.$portfolio_data['portfolio_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_portfolio_sap_share_fb']));
					$data['ln_share_url'] = base_url($this->config->item('portfolio_standalone_page_url')).'?id='.$portfolio_data['portfolio_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_portfolio_sap_share_ln']));
					$data['twitter_share_url'] = base_url($this->config->item('portfolio_standalone_page_url')).'?id='.$portfolio_data['portfolio_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_portfolio_sap_share_twitter']));
					$data['email_share_url'] = base_url($this->config->item('portfolio_standalone_page_url')).'?id='.$portfolio_data['portfolio_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_portfolio_sap_share_email']));
				} else {
					$data['fb_share_url'] = base_url($this->config->item('portfolio_standalone_page_url')).'?id='.$portfolio_data['portfolio_id'];
					$data['ln_share_url'] = base_url($this->config->item('portfolio_standalone_page_url')).'?id='.$portfolio_data['portfolio_id'];
					$data['twitter_share_url'] = base_url($this->config->item('portfolio_standalone_page_url')).'?id='.$portfolio_data['portfolio_id'];
					$data['email_share_url'] = base_url($this->config->item('portfolio_standalone_page_url')).'?id='.$portfolio_data['portfolio_id'];
				}

				$data['meta_tag'] = '<title>' . $portfolio_title_meta_tag . '</title><meta name="description" content="' . $portfolio_description_meta_tag . '"/>';
				########## set the portfolio title tag start end #########	
				$this->layout->view('user_portfolio_standalone_page', '', $data, 'include');
			}
			
		}
		

	}
	
	//This function is calling by ajax paging regarding user portfolio
	public function load_pagination_user_portfolio(){
		
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ 
				$page = $this->uri->segment(3);
				$user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				$total_record = $this->db->from('users_portfolios')->where(['user_id' => $user_id])->count_all_results();
				
				$paginations = generate_pagination_links($total_record, $this->config->item('portfolio_page_url'),$this->config->item('user_portfolio_section_listing_limit'),$this->config->item('user_portfolio_section_number_of_pagination_links'));
				
				$portfolio_listing_data = 
				$this->User_model->get_user_portfolio_listing(array('user_id'=>$user_id),$paginations['offset'], $this->config->item('user_portfolio_section_listing_limit'));
				
				$data["portfolio_data"] = $portfolio_listing_data['data'];
				$data['portfolio_count'] = $portfolio_listing_data['total'];
				$data['portfolio_pagination_links'] = $paginations['links'];
				$page = $paginations['current_page_no'];
				
				$multiplication = $this->config->item('user_portfolio_section_listing_limit') * $page;
				$subtraction = ($multiplication - ($this->config->item('user_portfolio_section_listing_limit') - count($data['portfolio_data'])));
				$record_per_page = count($data['portfolio_data']) < $this->config->item('user_portfolio_section_listing_limit') ? $subtraction : $multiplication;
				$page_no = ($this->config->item('user_portfolio_section_listing_limit') * ($page - 1)) + 1;
								
				
				$user_detail = $this->db // get the user detail
				->select('u.user_id,u.profile_name,ud.current_membership_plan_id')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user_id)
				->get()->row_array();  
				$data['user_detail']= $user_detail;
				echo json_encode(['status' => 200,'add_portfolio_button_style'=>$add_portfolio_button_style,'add_portfolio_button_free_member_style'=>$add_portfolio_button_free_member_style,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$portfolio_listing_data['total'],'data'=>$this->load->view('user_portfolio_listing',$data, true)]);
				die;
				
			}else{
				echo json_encode(['status' => 400,'location'=>VPATH]);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	public function load_user_portfolio_popup_body(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$data = array();
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('u_id'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$action_type = $this->input->post ('action_type');
				$section_id = $this->input->post ('section_id');
				if($action_type == 'edit'){
				
				$check_portfolio_data_exists = $this->db->where(['portfolio_id' =>$section_id,'user_id'=>$user_id])->from('users_portfolios')->count_all_results();
					if($check_portfolio_data_exists == 0){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('user_edit_portfolio_entry_already_deleted')]);
						die;
					}
				
				}	
				$res['portfolio_popup_heading'] = $this->config->item('user_portfolio_section_popup_add_headline_title');
				$portfolio_data = array();
				$portfolio_images = array();
				if($action_type == 'edit' && !empty($section_id)){
					$res['portfolio_popup_heading'] = $this->config->item('user_portfolio_section_popup_edit_headline_title');
					$portfolio_data = $this->db // get the user detail
					->select('up.*')
					->from('users_portfolios up')
					->where('up.user_id', $user_id)
					->where('up.portfolio_id', $section_id)
					->get()->row_array();
					
					$portfolio_images = $this->db // get the user detail
					->select('upi.*')
					->from('users_portfolios_images upi')
					->where('upi.portfolio_id', $section_id)
					->get()->result_array();
					
				}
				$data['portfolio_images']=$portfolio_images;
				$user_detail = $this->db // get the user detail
				->select('u.user_id,u.profile_name,ud.current_membership_plan_id')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user_id)
				->get()->row_array();  
				$data['user_detail']=$user_detail;
				$data['portfolio_data'] = $portfolio_data;
                $res['portfolio_popup_body'] = $this->load->view('user_portfolio_popup_body', $data, true);
				$res['status'] = 200;
				/* $res['portfolio_popup_body'] = $this->load->view('user_portfolio_popup_body', $data, true);
				$res['portfolio_popup_heading'] = $this->config->item('user_portfolio_section_popup_add_headline_title');
				$res['status'] = 200; */
				echo json_encode($res);
				die; 
			}else{
				$res['status'] = 400;
				$msg['location'] = VPATH . $this->config->item('dashboard_page_url');
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	public function save_user_portfolio(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				
				
				$user_detail = $this->db // get the user detail
				->select('u.user_id,u.profile_name,ud.current_membership_plan_id')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user_id)
				->get()->row_array();    
				$data["user_detail"] = $user_detail;
				$post_data = $this->input->post ();
				
				$validation_data_array = $this->User_model->user_portfolio_form_validation($post_data);
				if ($validation_data_array['status'] == 'SUCCESS')
				{
				
					if(!empty($_FILES)){
						$this->load->library('ftp');
						$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
						$config['ftp_username'] = FTP_USERNAME;
						$config['ftp_password'] = FTP_PASSWORD;
						$config['ftp_port'] = FTP_PORT;
						$config['debug'] = TRUE;
						$this->ftp->connect($config);
						$users_ftp_dir = USERS_FTP_DIR;
						$user_portfolio_dir = USER_PORTFOLIO;
						if(empty($this->ftp->check_ftp_directory_exist($users_ftp_dir))){
							echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
							
						}
						if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$user_detail['profile_name'].DIRECTORY_SEPARATOR)){
							echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
						}
						if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$user_detail['profile_name'].$user_portfolio_dir)){
							echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
						}
						$this->ftp->close();
					}
					
					
				
					if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
					$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');

					}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
					$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
					}
					
					$user_portfolio = array(
						'user_id'=>$user_id,
						'title'=>trim($post_data['portfolio_title']),
						'description'=>trim($post_data['description'])
					);
					$user_portfolio['reference_url'] = '';
					if(!empty($post_data['reference_url'])){
						$res = true;
						$url = trim($post_data['reference_url']);
						if (substr($url, 0, 7) == "http://"){
							$res = false;
							}
						if (substr($url, 0, 8) == "https://"){
							$res = false;
						}
						if($res){
							$new_url = "http://".$url;
						}else{
							$new_url = $url;
						}
						$user_portfolio['reference_url'] = $new_url;
					}
				
					//$is_record_saved = false;
					if(!empty($this->input->post ('section_id'))){
						$section_id = Cryptor::doDecrypt($this->input->post ('section_id'));
						
						$portfolio_detail = $this->db // get the portfolio detail
						->select('up.*')
						->from('users_portfolios up')
						->where('up.portfolio_id', $section_id)
						->get()->row_array();
						
						if(!empty($portfolio_detail)){
							
							$portfolio_id = $section_id;
							$this->db->update('users_portfolios', $user_portfolio, ['portfolio_id' => $section_id,'user_id'=> $user_id]);

							$old_str = ' '.$portfolio_detail['title'].' '.$portfolio_detail['description'];
							$this->User_model->save_find_professionals_user_information($user_id, 'update', ' '.trim($post_data['portfolio_title']).' '.trim($post_data['description']), $old_str);
							
							
							// profile completion script start
							$count_user_portfolio = $this->db->where(['user_id'=>$user_id])->from('users_portfolios')->count_all_results();
							$user_profile_completion_data['has_portfolio_indicated'] = 'Y';
							$user_profile_completion_data['portfolio_strength_value'] = $profile_completion_parameters['portfolio_strength_value'];
							$user_profile_completion_data['number_of_portfolios_entries'] = $count_user_portfolio;
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
							// profile completion script end
							
							
							
							
							
							$last_insert_id = $section_id;
							
							//$is_record_saved = true;
							$portfolio_detail = $this->db // get the user detail
							->select('up.*')
							->from('users_portfolios up')
							->where('up.portfolio_id', $section_id)
							->get()->row_array();
							$data['portfolio_value'] = $portfolio_detail;
							
							// for portfolio tags
							$portfolio_tags = $this->db->get_where('users_portfolios_tags', ['portfolio_id' => $section_id])->result_array();
							$this->db->delete('users_portfolios_tags', array('portfolio_id' => $section_id));

							if(!empty($portfolio_tags)) {
								foreach($portfolio_tags as $val) {
									$this->User_model->save_find_professionals_user_information($user_id, 'delete', ' ', ' '.$val['portfolio_tag_name']);
								}
							}

							if(!empty($this->input->post('portfolio_tag'))){
								foreach($this->input->post('portfolio_tag') as $portfolio_tag_key){
									if(!empty($portfolio_tag_key['tag_name'])){
										$this->db->insert ('users_portfolios_tags', array('portfolio_id' => $section_id,'portfolio_tag_name' => trim($portfolio_tag_key['tag_name'])));
										// save data in users_portfolios_tags table from post project form
										$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($portfolio_tag_key['tag_name']));
									}
								}	
							}
							$extra_data['profile_name'] = $user_detail['profile_name'];
							$extra_data['portfolio_id'] = $portfolio_id;
							$extra_data['user_id'] = $user_id;
							$this->upload_user_portfolio_images($_FILES,$extra_data);
							//$portfolio_tags = get_portfolio_tags(array('portfolio_id'=>$section_id));
							
							//$data['portfolio_tags']=$portfolio_tags;
							
							$msg = array('status' => 200,'section_id'=>$section_id,'action'=>'update','status'=>'SUCCESS','message'=>'','location'=>'','data'=>$this->load->view('user_portfolio_listing_entry_detail',$data, true));
							
						} else {
							$portfolio_id = generate_unique_portfolio_standalone_page_id();
							$user_portfolio['portfolio_id'] = $portfolio_id;
							$this->db->insert ('users_portfolios', $user_portfolio);

							$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['portfolio_title']).' '.trim($post_data['description']));
							
							$last_insert_id = $this->db->insert_id();
							//$is_record_saved = true;
							// for portfolio tags
							$last_insert_id = $this->db->insert_id();
							$this->db->delete('users_portfolios_tags', array('portfolio_id' => $portfolio_id));
							if(!empty($this->input->post('portfolio_tag'))){
								foreach($this->input->post('portfolio_tag') as $portfolio_tag_key){
									if(!empty($portfolio_tag_key['tag_name'])){
										$this->db->insert ('users_portfolios_tags', array('portfolio_id' => $portfolio_id,'portfolio_tag_name' => trim($portfolio_tag_key['tag_name'])));
										// save data in users_portfolios_tags table from post project form

										$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($portfolio_tag_key['tag_name']));
									}
								}	
							}
							
							$extra_data['profile_name'] = $user_detail['profile_name'];
							$extra_data['portfolio_id'] = $portfolio_id;
							$extra_data['user_id'] = $user_id;
							$this->upload_user_portfolio_images($_FILES,$extra_data);
							$start = 0;
							$portfolio_listing_data = 
							$this->User_model->get_user_portfolio_listing(array('user_id'=>$user_id),$start, $this->config->item('user_portfolio_section_listing_limit'));
							$data["portfolio_data"] = $portfolio_listing_data['data'];
							$data['portfolio_count'] = $portfolio_listing_data['total'];
							
							$paginations = generate_pagination_links($portfolio_listing_data['total'], $this->config->item('portfolio_page_url'), $this->config->item('user_portfolio_section_listing_limit'), $this->config->item('user_portfolio_section_number_of_pagination_links'));
							
							
							$data['portfolio_pagination_links'] = $paginations['links'];
							
							// profile completion script start
							$user_profile_completion_data['has_portfolio_indicated'] = 'Y';
							$user_profile_completion_data['portfolio_strength_value'] = $profile_completion_parameters['portfolio_strength_value'];
							$user_profile_completion_data['number_of_portfolios_entries'] = $portfolio_listing_data['total'];
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
							// profile completion script end
							
							
							
							$msg = array('status' => 200,'action'=>'insert','status'=>'SUCCESS','message'=>'','location'=>'','data'=>$this->load->view('user_portfolio_listing',$data, true));
						}
						
						
					} else {
						$portfolio_id = generate_unique_portfolio_standalone_page_id();
						$user_portfolio['portfolio_id'] = $portfolio_id;
						$this->db->insert ('users_portfolios', $user_portfolio);
						$last_insert_id = $this->db->insert_id();

						$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['portfolio_title']).' '.trim($post_data['description']));

						//$is_record_saved = true;
						// for portfolio tags
						if(!empty($this->input->post('portfolio_tag'))){
							foreach($this->input->post('portfolio_tag') as $portfolio_tag_key){
								if(!empty($portfolio_tag_key['tag_name'])){
									$this->db->insert ('users_portfolios_tags', array('portfolio_id' => $portfolio_id, 'portfolio_tag_name' => trim($portfolio_tag_key['tag_name'])));
									// save data in users_portfolios_tags table from post project form

									$this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($portfolio_tag_key['tag_name']));
								}
							}	
						}
						
						$extra_data['profile_name'] = $user_detail['profile_name'];
						$extra_data['portfolio_id'] = $portfolio_id;
						$extra_data['user_id'] = $user_id;
						$this->upload_user_portfolio_images($_FILES,$extra_data);
						
						
						$start = 0;
						$portfolio_listing_data = 
						$this->User_model->get_user_portfolio_listing(array('user_id'=>$user_id),$start, $this->config->item('user_portfolio_section_listing_limit'));
						
						$data["portfolio_data"] = $portfolio_listing_data['data'];
						$data['portfolio_count'] = $portfolio_listing_data['total'];
						
						$paginations = generate_pagination_links($portfolio_listing_data['total'], $this->config->item('portfolio_page_url'), $this->config->item('user_portfolio_section_listing_limit'), $this->config->item('user_portfolio_section_number_of_pagination_links'));
							
							
						$data['portfolio_pagination_links'] = $paginations['links'];
						
						
						
						// profile completion script start
						$user_profile_completion_data['has_portfolio_indicated'] = 'Y';
						$user_profile_completion_data['portfolio_strength_value'] = $profile_completion_parameters['portfolio_strength_value'];
						$user_profile_completion_data['number_of_portfolios_entries'] = $portfolio_listing_data['total'];
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
						// profile completion script end
						
						
						/* $data['portfolio_pagination_links'] = $this->generate_pagination_links_user_portfolio($portfolio_listing_data['total'], $this->config->item('portfolio_page_url'),$this->config->item('user_portfolio_section_listing_limit'));
						 */
						$msg = array('status' => 200,'action'=>'insert','status'=>'SUCCESS','message'=>'','location'=>'','data'=>$this->load->view('user_portfolio_listing',$data, true));
						
					}
					
				   echo json_encode ($msg);die;
				}else{
					echo json_encode ($validation_data_array);
					die;
				}
			}else{
				$res['status'] = 400;
				$msg['location'] = VPATH . $this->config->item('dashboard_page_url');
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}

	}
		
	public function upload_user_portfolio_images($file_array,$data){
		$profile_name = $data['profile_name'];
		$portfolio_id = $data['portfolio_id'];
		$user_id = $data['user_id'];
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
		$user_portfolio_dir = USER_PORTFOLIO;
		$profile_folder     = $profile_name;
		
		$upload_fail_file_arr = [];
		$uploaded_file_arr = [];
		$row = $this->input->post();
		
		/* $this->ftp->mkdir($users_ftp_dir.$profile_folder.$user_portfolio_dir, 0777);// create projects directory if not exists
		$this->ftp->mkdir($users_ftp_dir.$profile_folder.$user_portfolio_dir.DIRECTORY_SEPARATOR .$portfolio_id, 0777);// create projects directory if not exists */
		
		//$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
		$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
		$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$user_portfolio_dir);
		$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$user_portfolio_dir .$portfolio_id.DIRECTORY_SEPARATOR);
		
		if($file_array){
			 $image_config['image_library'] = 'gd2';
			  $image_config['create_thumb'] = TRUE;
			 $image_config['maintain_ratio'] = TRUE;
			 $image_config['width'] = 128;
			 $image_config['height'] = 128;
			foreach($file_array['files']['name'] as $key => $val) {
				$temp 		= 	explode(".", $val);
				$extension 	= 	end($temp);
				$attachment_name = round(microtime(true) * 1000);
				$temp_attachment_name = $attachment_name.'.'.$extension;
				
				if(move_uploaded_file($file_array['files']['tmp_name'][$key], TEMP_DIR.$temp_attachment_name)){ 
					
					 //image thumb for 128x128
				
					
					 $image_config['source_image'] = './'.TEMP_DIR.$temp_attachment_name;
					 $image_config['new_image'] = './'.TEMP_DIR.$attachment_name.'.jpg';
					
					/*  $this->load->library('image_lib', $image_config);
					 $this->image_lib->resize();  */
					$this->image_lib->initialize($image_config);  
					$this->image_lib->resize();
					$this->image_lib->clear();
					 
					 // handle if there is any problem
					if ( ! $this->image_lib->resize()){
						echo $this->image_lib->display_errors();
					 }
					$source_path = FCPATH .TEMP_DIR. $temp_attachment_name;		
					$destination_path = $users_ftp_dir.$profile_folder.$user_portfolio_dir.DIRECTORY_SEPARATOR .$portfolio_id. DIRECTORY_SEPARATOR .$temp_attachment_name;
					if(!$this->ftp->upload($source_path,$destination_path , 'auto', 0777)) { 
						// upload attachment on remote server
						array_push($upload_fail_file_arr, $row['file_name'][$key]);
					} else {
						$this->db->insert ('users_portfolios_images', array('portfolio_id' => $portfolio_id,
						'portfolio_image_name' =>$temp_attachment_name,'user_id'=>$user_id)); 
						
						$source_path_thumb = FCPATH .TEMP_DIR. $attachment_name.'_thumb.jpg';
						$destination_path_thumb = $users_ftp_dir.$profile_folder.$user_portfolio_dir .$portfolio_id. DIRECTORY_SEPARATOR . $attachment_name.'_thumb.jpg';
						$this->ftp->upload($source_path_thumb,$destination_path_thumb , 'auto', 0777); 
						
						array_push($uploaded_file_arr, $temp_attachment_name);
					}
					unlink(FCPATH .TEMP_DIR. $temp_attachment_name); 
					unlink(FCPATH .TEMP_DIR. $attachment_name.'_thumb.jpg'); 
				} else {
					
					array_push($upload_fail_file_arr, $row['file_name'][$key]);
				}
			}
		}
		$this->ftp->close();
	}
	
	/**
	This function is used to make the popup of delete portfolio confirmation .
	*/
	public function delete_user_portfolio_confirmation_popup_body(){
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('section_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$section_id = $this->input->post ('section_id');
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				
				if($user_id != Cryptor::doDecrypt($this->input->post ('u_id'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
					$confirmation_modal_title = $this->config->item('delete_portfolio_confirmation_project_modal_title');
					$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('delete_portfolio_confirmation_project_modal_body').'</div>';
					$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn default_popup_width_btn btnSave" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" class="btn blue_btn default_btn default_popup_width_btn delete_user_portfolio btnConfirm project_cancel_button width-auto"  data-attr="'.Cryptor::doEncrypt($section_id).'" data-uid="'.$this->input->post ('u_id').'" >'.$this->config->item('delete_btn_txt').'</button>';
					echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
					die;
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	
	
	}
	
	
	/**
	This function is used to delete user portfolio .
	*/
	public function delete_user_portfolio(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('section_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$section_id = Cryptor::doDecrypt($this->input->post ('section_id'));
			
			if(check_session_validity()){
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('u_id'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				//if(!empty($check_portfolio_data_exists)){
				
					if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
						$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');

					}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
						$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
					}
				
					$user_portfolio = $this->db->get_where('users_portfolios', ['portfolio_id' => $section_id,'user_id'=>$user_id])->row_array();
					$portfolio_tags = $this->db->get_where('users_portfolios_tags', ['portfolio_id' => $section_id])->result_array();

					$this->db->delete('users_portfolios', ['portfolio_id' => $section_id,'user_id'=>$user[0]->user_id]);
					$this->db->delete('users_portfolios_tags', array('portfolio_id' => $section_id));
					$this->db->delete('users_portfolios_images', array('portfolio_id' => $section_id));
					$this->db->delete('users_standalone_portfolio_page_cover_picture_tracking', array('standalone_portfolio_page_id' => $section_id));

					$this->User_model->save_find_professionals_user_information($user_id, 'delete', ' ', ' '.$user_portfolio['title'].' '.$user_portfolio['description']);

					foreach($portfolio_tags as $val) {
						$this->User_model->save_find_professionals_user_information($user_id, 'delete', ' ', ' '.$val['portfolio_tag_name']);
					}
					
					
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->ftp->connect($config); 
					######## connectivity of remote server end #######
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$user_portfolio_dir = USER_PORTFOLIO;
					$profile_folder     = $user[0]->profile_name;
					
					if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$user_portfolio_dir.$project_data['project_id'].$section_id))){
						$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$user_portfolio_dir.$project_data['project_id'].$section_id);
						
					}
					$this->ftp->close();
					
					if($this->input->post ('active_page')){
						$page = $this->input->post ('active_page');
					}else{
							$page = 1;
					}
					$total_record = $this->db->from('users_portfolios')->where(['user_id' => $user_id])->count_all_results(); 
					$user_profile_completion_data = array();
					if($total_record == 0){
						$user_profile_completion_data['has_portfolio_indicated'] = 'N';
						$user_profile_completion_data['portfolio_strength_value'] = 0;
						$user_profile_completion_data['number_of_portfolios_entries'] = 0;
					}else{
						$user_profile_completion_data['has_portfolio_indicated'] = 'Y';
						$user_profile_completion_data['portfolio_strength_value'] = $profile_completion_parameters['portfolio_strength_value'];
						$user_profile_completion_data['number_of_portfolios_entries'] = $total_record;
					
					}
					if(!empty($user_profile_completion_data)){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
					}
					
					
					
					$paginations = generate_pagination_links($total_record, $this->config->item('portfolio_page_url'),$this->config->item('user_portfolio_section_listing_limit'),$this->config->item('user_portfolio_section_number_of_pagination_links'));
					
					$portfolio_listing_data = 
					$this->User_model->get_user_portfolio_listing(array('user_id'=>$user_id),$paginations['offset'], $this->config->item('user_portfolio_section_listing_limit'));
					
					
					$data["portfolio_data"] = $portfolio_listing_data['data'];
					$data['portfolio_count'] = $portfolio_listing_data['total'];
					
					
					$data['portfolio_pagination_links'] = $paginations['links'];
					
					$page = $paginations['current_page_no'];
					
					$multiplication = $this->config->item('user_portfolio_section_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('user_portfolio_section_listing_limit') - count($data['portfolio_data'])));
					$record_per_page = count($data['portfolio_data']) < $this->config->item('user_portfolio_section_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('user_portfolio_section_listing_limit') * ($page - 1)) + 1;
					
					$check_portfolio_data_exists = $this->db->where(['user_id' =>$user_id])->from('users_portfolios')->count_all_results();
					$initial_view_status = '0';
					if($check_portfolio_data_exists == 0){
						$initial_view_status = '1';
					}
					
					
					 $user_detail = $this->db // get the user detail
					->select('u.user_id,ud.current_membership_plan_id,profile_name')
					->from('users u')
					->join('users_details ud', 'ud.user_id = u.user_id', 'left')
					->where('u.user_id', $user_id)
					->get()->row_array();       
					$data['user_detail'] = $user_detail;
					
					echo json_encode(['location'=>'','add_portfolio_button_style'=>$add_portfolio_button_style,'add_portfolio_button_free_member_style'=>$add_portfolio_button_free_member_style,'initial_view_status'=>$initial_view_status,'status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$portfolio_listing_data['total'],'data'=>$this->load->view('user_portfolio_listing',$data, true)]);
				//}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	/**
	This function is used to delete the portfolio image of user.
	*/
	public function delete_user_portfolio_image ()
    {
     
        if ($this->input->is_ajax_request ())
        {
			if(!$this->input->post ('portfolio_id') || !$this->input->post ('row_id') || !$this->input->post ('image_name')){
				show_404();
			}
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
				    echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
			
			
				$row_id = $this->input->post ('row_id');
				$portfolio_id = $this->input->post ('portfolio_id');
				$image_name = $this->input->post ('image_name');
				//$portfolio_data = $this->db->get_where('users_portfolios', ['portfolio_id' => $portfolio_id])->row_array();
				
				$user_detail = $this->db // get the user detail
				->select('u.user_id,u.profile_name')
				->from('users u')
				->where('u.user_id', $user_id)
				->get()->row_array();   
				
				/* if(empty($portfolio_data)) {
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('user_delete_portfolio_picture_entry_already_deleted')]);
					die;
				} */
				
				
				$user = $this->session->userdata ('user');
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
				$user_portfolio_dir = USER_PORTFOLIO;
				$profile_folder     = $user[0]->profile_name;
				
				$result = $this->db->get_where('users_portfolios_images', ['id' => $row_id])->row_array();
				
				if(!empty($result)){
					$image_name_array = explode('.',$result['portfolio_image_name']);
					$thumb_image_name = $image_name_array[0].'_thumb.jpg';
					
					$original_image_source_path = $users_ftp_dir.$profile_folder.$user_portfolio_dir.DIRECTORY_SEPARATOR .$portfolio_id. DIRECTORY_SEPARATOR .$result['portfolio_image_name'];
					$thumb_image_source_path = $users_ftp_dir.$profile_folder.$user_portfolio_dir.DIRECTORY_SEPARATOR .$portfolio_id. DIRECTORY_SEPARATOR .$thumb_image_name;
					
					$original_file_size = $this->ftp->get_filesize($original_image_source_path);
					$thumb_file_size = $this->ftp->get_filesize($thumb_image_source_path);
					if($original_file_size != '-1'){
						$this->ftp->delete_file($original_image_source_path);
					}
					if($thumb_file_size != '-1'){
						$this->ftp->delete_file($thumb_image_source_path);
					}
					$this->db->delete('users_portfolios_images', array('id' => $row_id));
				}else{
					$image_name_array = explode('.',$image_name);
					$thumb_image_name = $image_name_array[0].'_thumb.jpg';
					
					$original_image_source_path = $users_ftp_dir.$profile_folder.$user_portfolio_dir.DIRECTORY_SEPARATOR .$portfolio_id. DIRECTORY_SEPARATOR .$image_name;
					$thumb_image_source_path = $users_ftp_dir.$profile_folder.$user_portfolio_dir.DIRECTORY_SEPARATOR .$portfolio_id. DIRECTORY_SEPARATOR .$thumb_image_name;
					
					$original_file_size = $this->ftp->get_filesize($original_image_source_path);
					$thumb_file_size = $this->ftp->get_filesize($thumb_image_source_path);
					if($original_file_size != '-1'){
						$this->ftp->delete_file($original_image_source_path);
					}
					if($thumb_file_size != '-1'){
						$this->ftp->delete_file($thumb_image_source_path);
					}
				}
				$data['get_portfolio_images'] = $this->User_model->get_portfolio_images(array('portfolio_id'=>$portfolio_id));
				$data['portfolio_id'] = $portfolio_id;
				$data['user_detail'] = $user_detail;
				$msg['status'] = 200;
				$msg['location'] = '';
				$msg['data'] = $this->load->view('user/user_portfolio_listing_entry_detail_images',$data, true);
				echo json_encode ($msg);
			}else{
			
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			
			}
			
        }else{
			show_custom_404_page(); //show custom 404 page
		}
		
    }	
	
	
	/**
	This function is used to delete the portfolio tag from users_portfolioa_tags table.
	*/
	public function delete_portfolio_tag ()
    {
		if($this->input->is_ajax_request ()){
			/* if(empty($this->input->post ('portfolio_tag_id'))){
				show_custom_404_page(); //show custom 404 page
			} */
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				die;
				}
				
				 $user_detail = $this->db // get the user detail
				->select('u.user_id,ud.current_membership_plan_id')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user_id)
				->get()->row_array();       
				
				if($this->input->post ('type') == 'single'){
					
					$portfolio_tag_id = $this->input->post ('portfolio_tag_id');
					$portfolio_tag_array = explode("_",$portfolio_tag_id);

					$portfolio_tag = $this->db->get_where('users_portfolios_tags', ['id' => $portfolio_tag_array[2]])->row_array();

					$this->db->delete('users_portfolios_tags', array('id' => $portfolio_tag_array[2]));
					
					$this->User_model->save_find_professionals_user_information($user_id, 'delete', ' ', ''.trim($portfolio_tag['portfolio_tag_name']));
				}
				if($this->input->post ('type') == 'all'){
				
					$count_portfolio = $this->db // count the number of record in projects_draft table
					->select ('id')
					->from ('users_portfolios')
					->where('portfolio_id',$this->input->post ('section_id'))
					->get ()->num_rows ();
				
					$portfolio_tags = $this->db->get_where('users_portfolios_tags', ['portfolio_id' => $this->input->post ('section_id')])->row_array();
					
					$this->db->delete('users_portfolios_tags', array('portfolio_id' => $this->input->post ('section_id')));

					if(!empty($portfolio_tags)) {
						foreach($portfolio_tags as $val) {
							$this->User_model->save_find_professionals_user_information($user_id, 'delete', ' ', ''.trim($val['portfolio_tag_name']));
						}
					}
					
					$count_project_tags = $this->db->where(['portfolio_id' => $this->input->post ('section_id')])->from('users_portfolios_tags')->count_all_results();
					
					if($user_detail['current_membership_plan_id'] == '1'){ // for free
		
						$number_tag_allowed = $this->config->item('user_portfolio_page_free_membership_subscriber_number_tags_allowed_per_portfolio_slot');	
						
					}else{	
						$number_tag_allowed = $this->config->item('user_portfolio_page_gold_membership_subscriber_number_tags_allowed_per_portfolio_slot');	
					}
					if($count_project_tags >= $number_tag_allowed){
						$msg['show_tag_input_status'] = '0';
					}else{
						$msg['show_tag_input_status'] = '1';
					}
				}
				$msg['status'] = 'SUCCESS';
				$msg['message'] = '';
				echo json_encode ($msg);die;
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	/**
    This function is used to save/update the portfolio standalone cover picture into database and disk.
	*/
    public function save_user_standalone_portfolio_page_cover_picture() {
        if ($this->input->is_ajax_request()) {
			$portfolio_id = $this->input->post ('portfolio_id');
            if (check_session_validity()) {
                $user = $this->session->userdata('user');
				$user_id = Cryptor::doDecrypt($this->input->post ('uid'));
				if($user[0]->user_id != $user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$check_portfolio_data_exists = $this->db->where(['portfolio_id' =>$portfolio_id,'user_id'=>$user_id])->from('users_portfolios')->count_all_results();
				if($check_portfolio_data_exists == 0){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('user_update_cover_picture_on_portfolio_standalone_page_entry_already_deleted')]);
					die;
				}
				$portfolio_cover_picture_data = $this->db->get_where('users_standalone_portfolio_page_cover_picture_tracking', array('standalone_portfolio_page_id' => $portfolio_id))->row_array();
				
				$user_detail = $this->db // get the user detail
				->select('u.user_id,u.profile_name')
				->from('users u')
				->where('u.user_id',$user_id )
				->get()->row_array();
				$msg['location'] = '';
				
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] = FTP_PORT;
				$config['debug'] = TRUE;
				$this->ftp->connect($config);
				$users_ftp_dir = USERS_FTP_DIR;
				$profile_folder = $user_detail['profile_name'];
				
				//$upload_folder = USER_AVATAR;
				$user_portfolio_dir = USER_PORTFOLIO;
				$user_standalone_portfolio_page_cover_picture = USER_STANDALONE_PORTFOLIO_PAGE_COVER_PICTURE;
				
				
				
				##############Delete the old cover picture of portfolio start ####
				
				if(!empty($portfolio_cover_picture_data) && !empty($portfolio_cover_picture_data['standalone_portfolio_page_cover_picture_name'])){
					if( file_exists(FCPATH . TEMP_DIR . $old_org_file)){
						unlink(FCPATH . TEMP_DIR . $old_org_file);
					}
					$this->db->delete('users_standalone_portfolio_page_cover_picture_tracking', array("standalone_portfolio_page_id" => $portfolio_id,'user_id'=>$user_id));
				}
				
				
				
				if(!empty($_FILES)){
					foreach($_FILES['files']['name'] as $key => $val) {
						$temp 		= 	explode(".", $val);
						$extension 	= 	end($temp);
						$attachment_name = round(microtime(true) * 1000);
						$image_name = $attachment_name.'.'.$extension;
						
						if(move_uploaded_file($_FILES['files']['tmp_name'][$key], TEMP_DIR.$image_name)){
						
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$user_portfolio_dir);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$user_portfolio_dir.$portfolio_id.DIRECTORY_SEPARATOR);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$user_portfolio_dir .$portfolio_id.$user_standalone_portfolio_page_cover_picture);
						
						
						/* 
							$this->ftp->mkdir($users_ftp_dir . $profile_folder, 0777);
							$this->ftp->mkdir($users_ftp_dir.$profile_folder.$user_portfolio_dir, 0777);
							$this->ftp->mkdir($users_ftp_dir.$profile_folder.$user_portfolio_dir.$portfolio_id,0777);
							$this->ftp->mkdir($users_ftp_dir.$profile_folder.$user_portfolio_dir .$portfolio_id.$user_standalone_portfolio_page_cover_picture, 0777); */
							
							$this->ftp->upload(FCPATH . TEMP_DIR . $image_name, $users_ftp_dir.$profile_folder.$user_portfolio_dir .$portfolio_id.$user_standalone_portfolio_page_cover_picture .$image_name, 'auto', 0777);
							unlink(FCPATH . TEMP_DIR . $image_name);
							$this->db->insert('users_standalone_portfolio_page_cover_picture_tracking', array('user_id'=>$user_id,'standalone_portfolio_page_id'=>$portfolio_id,'standalone_portfolio_page_cover_picture_name'=>$image_name,'standalone_portfolio_page_cover_picture_upload_date'=>date('Y-m-d H:i:s')));
						}
					}
				}
                $msg['status'] = 200;
                $msg['avatar_picture'] = $image_name;
            } else {
                $msg['status'] = 400;
                $msg['location'] = VPATH.$this->config->item('portfolio_standalone_page_url')."?id=".$portfolio_id;
            }
			echo json_encode($msg);die;
        } else {
            show_custom_404_page(); //show custom 404 page
        }
    }
	
	
	/**
    This function is used to fetch the portfolio standalone cover picture.
	*/
    /* public function fetch_user_standalone_portfolio_page_cover_picture() {
		if ($this->input->is_ajax_request()) {
			$portfolio_id = $this->input->post ('portfolio_id');
			if (check_session_validity()) {
				$user = $this->session->userdata('user');
				$user_id = Cryptor::doDecrypt($this->input->post ('uid'));
				if($user_id != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$check_portfolio_data_exists = $this->db->where(['portfolio_id' =>$portfolio_id,'user_id'=>$user_id])->from('users_portfolios')->count_all_results();
				if($check_portfolio_data_exists == 0){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('user_update_cover_picture_on_portfolio_standalone_page_entry_already_deleted')]);
					die;
				}
				$msg['location'] = '';
				
				
				$portfolio_data = $this->db // get the user detail
				->select('up.*,upcp.standalone_portfolio_page_id,upcp.standalone_portfolio_page_cover_picture_name,upcp.standalone_portfolio_page_cover_picture_upload_date')
				->from('users_portfolios up')
				->join('users_standalone_portfolio_page_cover_picture_tracking upcp', 'upcp.standalone_portfolio_page_id = up.portfolio_id', 'left')
				->where('up.portfolio_id', $portfolio_id)
				->get()->row_array();
				
				$user_detail = $this->db // get the user detail
				->select('u.user_id,u.profile_name')
				->from('users u')
				->where('u.user_id', $user_id)
				->get()->row_array();
				
				$cover_file = 	$original_cover_name = '';
				if(!empty($portfolio_data['standalone_portfolio_page_cover_picture_name'])){	
					$cover_file = $portfolio_data['standalone_portfolio_page_cover_picture_name'];
					$ex = explode('.', $portfolio_data['standalone_portfolio_page_cover_picture_name']);
					$original_cover_name = $ex[0] . "_original.png";
				}
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] = FTP_PORT;
				$config['debug'] = TRUE;
				$this->ftp->connect($config);

				$users_ftp_dir = USERS_FTP_DIR;
				$profile_folder = $user_detail['profile_name'];
				//$upload_folder = USER_AVATAR;
				$user_portfolio_dir = USER_PORTFOLIO;
				$user_standalone_portfolio_page_cover_picture = USER_STANDALONE_PORTFOLIO_PAGE_COVER_PICTURE;
				
				$source_path = $users_ftp_dir.$profile_folder.$user_portfolio_dir .$portfolio_data['portfolio_id'].$user_standalone_portfolio_page_cover_picture ;
				
				//echo $users_ftp_dir.$profile_folder.$upload_folder.$data['org_image_name'];exit;
				$source_path_original = $source_path . $original_cover_name;
				$source_path_crop = $source_path . $cover_file;
				
				$file_size_original = $this->ftp->get_filesize($source_path_original);
				$file_size_crop = $this->ftp->get_filesize($source_path_crop);
				$file_exists = '0';
				if ($file_size_original == '-1' || $file_size_crop == '-1') {
					$this->db->delete('users_standalone_portfolio_page_cover_picture_tracking', array("standalone_portfolio_page_id" => $portfolio_id,'user_id'=>$user_id));
					if(!empty($this->ftp->check_ftp_directory_exist($source_path))){
						$this->ftp->delete_dir($source_path);
					}
					
				} else {
					$file_exists = '1';
					$this->ftp->download($source_path_original, FCPATH . TEMP_DIR . $original_cover_name, 'auto', 0777);
					$portfolio_cover_picture = URL . TEMP_DIR . $original_cover_name;
					
				}
				$this->ftp->close();
				$msg['portfolio_cover_picture'] = $portfolio_cover_picture;
				$msg['file_exists'] = $file_exists;
				$msg['status'] = 200;
			} else {
				$msg['status'] = 400;
                $msg['location'] = VPATH.$this->config->item('portfolio_standalone_page_url')."?id=".$portfolio_id;
			}
			echo json_encode($msg);die;
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	 */
	
	/**
    This function is used to cancel the opreation regarding portfolio standalone page.
	*/
	public function cancel_user_standalone_portfolio_page_cover_picture() {
		 if ($this->input->is_ajax_request()) {
		 $portfolio_id = $this->input->post ('portfolio_id');
			if (check_session_validity()) {
				$user = $this->session->userdata('user');
				$user_id = Cryptor::doDecrypt($this->input->post ('uid'));
				if($user_id != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$check_portfolio_data_exists = $this->db->where(['portfolio_id' =>$portfolio_id,'user_id'=>$user_id])->from('users_portfolios')->count_all_results();
				if($check_portfolio_data_exists == 0){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('user_update_cover_picture_on_portfolio_standalone_page_entry_already_deleted')]);
					die;
				}
				$msg['location'] = '';
				
				$portfolio_data = $this->db // get the user detail
				->select('up.*,upcp.standalone_portfolio_page_id,upcp.standalone_portfolio_page_cover_picture_name,upcp.standalone_portfolio_page_cover_picture_upload_date')
				->from('users_portfolios up')
				->join('users_standalone_portfolio_page_cover_picture_tracking upcp', 'upcp.standalone_portfolio_page_id = up.portfolio_id', 'left')
				->where('up.portfolio_id', $portfolio_id)
				->get()->row_array();
				
				$user_detail = $this->db // get the user detail
				->select('u.user_id,u.profile_name')
				->from('users u')
				->where('u.user_id', $user_id)
				->get()->row_array();
				$cover_file = 	'';
				//$original_cover_name = '';
				if(!empty($portfolio_data['standalone_portfolio_page_cover_picture_name'])){	
					$cover_file = $portfolio_data['standalone_portfolio_page_cover_picture_name'];
					/* $ex = explode('.', $portfolio_data['standalone_portfolio_page_cover_picture_name']);
					$original_cover_name = $ex[0] . "_original.png"; */
				}
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] = FTP_PORT;
				$config['debug'] = TRUE;
				$this->ftp->connect($config);

				$users_ftp_dir = USERS_FTP_DIR;
				$profile_folder = $user_detail['profile_name'];
				//$upload_folder = USER_AVATAR;
				$user_portfolio_dir = USER_PORTFOLIO;
				$user_standalone_portfolio_page_cover_picture = USER_STANDALONE_PORTFOLIO_PAGE_COVER_PICTURE;
				
				$source_path = $users_ftp_dir.$profile_folder.$user_portfolio_dir .$portfolio_id.$user_standalone_portfolio_page_cover_picture ;
				
				//echo $users_ftp_dir.$profile_folder.$upload_folder.$data['org_image_name'];exit;
				//$source_path_original = $source_path . $original_cover_name;
				//$source_path_crop = $source_path . $cover_file;
				
				//$file_size_original = $this->ftp->get_filesize($source_path_original);
				$file_size = $this->ftp->get_filesize($source_path . $cover_file);
				$file_exists = '0';
				if ($file_size == '-1') {
					$this->db->delete('users_standalone_portfolio_page_cover_picture_tracking', array("standalone_portfolio_page_id" => $portfolio_data['portfolio_id'],'user_id'=>$user_id));
					if(!empty($this->ftp->check_ftp_directory_exist($source_path))){
						$this->ftp->delete_dir($source_path);
					}
					
				} else {
					$file_exists = '1';
					/* $this->ftp->download($source_path_original, FCPATH . TEMP_DIR . $original_cover_name, 'auto', 0777);
					$portfolio_cover_picture = URL . TEMP_DIR . $original_cover_name; */
					$portfolio_cover_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.$users_ftp_dir.$profile_folder.$user_portfolio_dir .$portfolio_data['portfolio_id'].$user_standalone_portfolio_page_cover_picture.$cover_file;
				}
				$this->ftp->close();
				$msg['portfolio_cover_picture'] = $portfolio_cover_picture;
				$msg['file_exists'] = $file_exists;
				$msg['status'] = 200;
				//echo URL .TEMP_DIR . $original_image_name;
			} else {
				$msg['status'] = 400;
               $msg['location'] =  VPATH.$this->config->item('portfolio_standalone_page_url')."?id=".$portfolio_id;
			}
			echo json_encode($msg);die;
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	
	/**
	This function is used to delete the user portfolio cover picture from disk and database.
	*/
	public function delete_user_standalone_portfolio_page_cover_picture ()
    {
        if ($this->input->is_ajax_request ())
        {
			$portfolio_id = $this->input->post ('portfolio_id');
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$user_id = Cryptor::doDecrypt($this->input->post ('uid'));
				if($user_id != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				
				$check_portfolio_data_exists = $this->db->where(['portfolio_id' =>$portfolio_id,'user_id'=>$user_id])->from('users_portfolios')->count_all_results();
				if($check_portfolio_data_exists == 0){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('user_update_cover_picture_on_portfolio_standalone_page_entry_already_deleted')]);
					die;
				}
				$msg['location'] = '';
				$portfolio_data = $this->db // get the user detail
				->select('up.*,upcp.standalone_portfolio_page_id,upcp.standalone_portfolio_page_cover_picture_name,upcp.standalone_portfolio_page_cover_picture_upload_date')
				->from('users_portfolios up')
				->join('users_standalone_portfolio_page_cover_picture_tracking upcp', 'upcp.standalone_portfolio_page_id = up.portfolio_id', 'left')
				->where('up.portfolio_id', $portfolio_id)
				->get()->row_array();
				
				$user_detail = $this->db // get the user detail
				->select('u.user_id,u.profile_name')
				->from('users u')
				->where('u.user_id', $user_id)
				->get()->row_array();
				
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] = FTP_PORT;
				$config['debug'] = TRUE;
				$this->ftp->connect($config);

				$users_ftp_dir = USERS_FTP_DIR;
				$profile_folder = $user_detail['profile_name'];
				//$upload_folder = USER_AVATAR;
				$user_portfolio_dir = USER_PORTFOLIO;
				$user_standalone_portfolio_page_cover_picture = USER_STANDALONE_PORTFOLIO_PAGE_COVER_PICTURE;
				
				$source_path = $users_ftp_dir.$profile_folder.$user_portfolio_dir .$portfolio_id.$user_standalone_portfolio_page_cover_picture ;
				
				
				$this->db->delete('users_standalone_portfolio_page_cover_picture_tracking', array("standalone_portfolio_page_id" => $portfolio_id,'user_id'=>$user_id));
				if(!empty($this->ftp->check_ftp_directory_exist($source_path))){
					$this->ftp->delete_dir($source_path);
				}
				$msg['status'] = 200;
				$msg['location'] = '';
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('portfolio_standalone_page_url')."?id=".$portfolio_id;
			}
			echo json_encode($msg);die;
        }else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	
	/*
	This function update the user profile page tabs
	*/
	public function update_user_profile_tabs(){
		if($this->input->is_ajax_request ()){
			if ($this->input->post () )
			{
				$tab_type = $this->input->post ('tab_type');
				$profile_name = $this->input->post ('profile_name');
				$user_detail = $this->db // get the user detail
                ->select('u.user_id,u.profile_name ,u.gender,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
                ->from('users u')
                ->where('u.profile_name LIKE ', $profile_name)
                ->get()->row_array();
				
				
				
				if(empty($user_detail)){
					$res = [
						'status' => 400,
						'location'=>VPATH.$this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				
				}
				$data = array();
				$data['user_detail'] = $user_detail;
				if($tab_type == 'information'){
					 $user_detail = $this->db // get the user detail
					->select('u.user_id, u.account_type,u.is_authorized_physical_person, u.gender, u.first_name, u.last_name, u.company_name, u.email, u.profile_name, u.account_validation_date, u.latest_login_date, upbi.description, upbi.hourly_rate, upbi.mother_tongue_language_id, uad.street_address, c.name as county_name, l.name as locality_name, pc.postal_code,countries.*')
					->from('users u')
					->join('users_profile_base_information upbi', 'upbi.user_id = u.user_id', 'left')
					->join('users_address_details uad', 'uad.user_id = u.user_id', 'left')
					->join('counties c', 'c.id = uad.county_id', 'left')
					->join('localities l', 'l.id = uad.locality_id', 'left')
					->join('postal_codes pc', 'pc.id = uad.postal_code_id', 'left')
					->join('countries', 'countries.id = uad.country_id', 'left')
					->where('u.profile_name LIKE ', $profile_name)
					->get()->row_array();
					$data['address_detail_exists'] = false;
					$data['profile_views_cnt'] = $this->User_model->save_user_profile_pages_visits_and_get_count($user_detail['user_id'], 'tab');
					$data['profile_followers_cnt'] = $this->db->from('users_favorite_employer_tracking')->where('favorite_employer_id', $user_detail['user_id'])->count_all_results();
					$data['profile_contacts_cnt'] = $this->db->from('users_contacts_tracking')->where(['contact_initiated_by' => $user_detail['user_id'], 'is_blocked' => 'no'])->group_by('contact_requested_to')->count_all_results();

					$address_details = '<div class="comLoc default_user_location userLocation_streetAddress">';
					if($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE){
						$address_details .= '<span><i class="far fa-building" aria-hidden="true"></i></span>';
					} else {
						$address_details .= '<span><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span>';
					}
					if(!empty($user_detail['country_name'])){
						$data['address_detail_exists'] = true;
					}
					if(!empty($user_detail['street_address'])){
						if(!preg_match('/\s/',$user_detail['street_address'])) {
							$address_details .= '<small class="street_address_nospace">'.htmlspecialchars($user_detail['street_address'], ENT_QUOTES).',</small>';
						} else {
							$address_details .= '<small>'.htmlspecialchars($user_detail['street_address'], ENT_QUOTES).',</small>';
						}
					}
					
					if(!empty($user_detail['locality_name']) && !empty($user_detail['postal_code'])){
						$address_details .= '<small>'.$user_detail['locality_name'].' '.$user_detail['postal_code'].',</small>';
					}
					if(empty($user_detail['locality_name']) && !empty($user_detail['postal_code'])){
						$address_details .= '<small> '.$user_detail['postal_code'].',</small>';
					}
					if(!empty($user_detail['locality_name']) && empty($user_detail['postal_code'])){
						$address_details .= '<small>'.$user_detail['locality_name'].',</small>';
					}
					if(!empty($user_detail['county_name'])){
						$address_details .= '<small>'.$user_detail['county_name'].',</small>';
					}
					$country_flag = ASSETS .'images/countries_flags/'.strtolower($user_detail['country_code']).'.png';
					$address_details .= '<small>'.$user_detail['country_name'].'<div class="default_user_location_flag" style="background-image: url('.$country_flag.');"></div></small>';
					
					$head_open_hr_exist = false;

					if($data['address_detail_exists']) {
						$data['company_location_heading'] = $this->config->item('ca_user_profile_page_user_company_location_company_headquarter_heading');
						$head_opening_hours = $this->db->get_where('users_company_accounts_opening_hours', ['user_id' => $user_detail['user_id'], 'is_company_headquarter' => 'Y'])->result_array();
						if(!empty($head_opening_hours)) {
							$data['company_location_heading'] = $this->config->item('ca_user_profile_page_user_company_location_company_headquarter_opening_hours_heading');
							$head_open_hr_exist = true;
							$first = $head_opening_hours[0];
							if($first['company_open_hours_status'] != 'selected_hours' || ($first['company_open_hours_status'] == 'selected_hours' && $first['is_selected_hours_checked'] == 'Y')) {
								$address_details .= '<span class="receive_notification">';
								$address_details .= "	<a class='rcv_notfy_btn show_more_location' style='display:none' data-id='1'>".$this->config->item('ca_user_profile_page_user_company_location_show_more_opening_hours_text')."</a>";
								$address_details .= "	<a class='rcv_notfy_btn show_less_location'  data-id='0'>".$this->config->item('ca_user_profile_page_user_company_location_hide_extra_opening_hours_text')."</a>";
								$address_details .= '</span>';
							} else if($first['company_open_hours_status'] == 'selected_hours' && $first['is_selected_hours_checked'] == 'N') {
								$head_open_hr_exist = false;
							}
							

							if($first['company_open_hours_status'] != 'selected_hours') {
								$open_hours_status = '';
								$ohcls = 'othr';
								if($first['company_open_hours_status'] == 'always_opened') {
									$open_hours_status = $this->config->item('ca_user_profile_page_company_open_hours_status_always_opened_label_txt');
								} else if($first['company_open_hours_status'] == 'permanently_closed') {
									$open_hours_status = $this->config->item('ca_user_profile_page_company_open_hours_status_permanently_closed_label_txt');
								} else {
									$open_hours_status = $this->config->item('ca_user_profile_page_company_open_hours_status_by_telephone_appointment_label_txt');
									$ohcls = 'othrtel';
								}
								$address_details .= '<div id="content" class="" >';
								$address_details .= '	<div class="default_black_bold_medium mt-1">'.$open_hours_status.'</div>';
								$address_details .= '</div>';
							} else {
								$weekdays = $this->config->item('calendar_weekdays_long_name');
								$days = array_column($head_opening_hours, 'day');
								$curr_day = date('N');
								$curr_day_idx = array_search($curr_day, $days);
								$opcls = 'cNow';
								$opstatus = $this->config->item('ca_user_profile_page_company_closed_now_label_txt');
								if(isset($curr_day_idx) && is_numeric($curr_day_idx)) {
									$curr_time = strtotime(date('H:i'));
									$st_time = strtotime($head_opening_hours[$curr_day_idx]['company_opening_time']);
									$end_time = strtotime($head_opening_hours[$curr_day_idx]['company_closing_time']);

									if($curr_time >= $st_time && $curr_time <= $end_time) {
										$opcls = 'oNow';
										$opstatus = $this->config->item('ca_user_profile_page_company_opened_now_label_txt');
									} 
								}
								if($first['is_selected_hours_checked'] == 'Y') {
									$address_details .= '<div id="content" class="" >';
									$address_details .= '	<div class="default_black_bold_medium mt-1 default_bottom_border '.$opcls.'">'.$opstatus.'</div>';

									foreach($weekdays as $key => $day) {
										$open_time = $this->config->item('ca_user_profile_page_company_open_hours_status_closed_label_txt');
										if(in_array($key, $days)) {
											$idx = array_search($key, $days);
											$open_time = $head_opening_hours[$idx]['company_opening_time'].' - '.$head_opening_hours[$idx]['company_closing_time'];
										}
										$address_details .= '<div class="staProHr">';
										$address_details .= '	<label>'.$day.'</label>';
										$address_details .= '	<label>'.$open_time.'</label>';
										$address_details .= '	<div class="clearfix"></div>';
										$address_details .= '</div>';
									}
									$address_details .= '</div>';
								}
								
							}
						
						}
					}
					$address_details .= '</div>';
					$data['address_details'] = $address_details;
					
					if($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE){
						$extra_locations_arr = $this->db // get the user detail
						->select('bael.id, bael.street_address, bael.locality_id, bael.county_id, bael.postal_code_id, c.name as county_name, l.name as locality_name, pc.postal_code, con.country_code, con.country_name as country')
						->from('users_company_accounts_additional_branches_addresses bael')
						->join('countries con', 'con.id = bael.country_id', 'left')
						->join('counties c', 'c.id = bael.county_id', 'left')
						->join('localities l', 'l.id = bael.locality_id', 'left')
						->join('postal_codes pc', 'pc.id = bael.postal_code_id', 'left')
						->where('bael.user_id', $user_detail['user_id'])
						->get()->result_array();
						if(count($extra_locations_arr) > 0) {
							$data['company_location_heading'] = $this->config->item('ca_user_profile_page_user_company_location_company_locations_heading');	
							if($head_open_hr_exist) {
								$data['company_location_heading'] = $this->config->item('ca_user_profile_page_user_company_location_company_locations_opening_hours_heading');	
							}
						}
						$remainingLocation = count($extra_locations_arr)-$this->config->item('user_profile_page_maximum_company_locations_show');
						$m = 0;
						foreach($extra_locations_arr as $extra_location) {
							$m++;
							if($m == $this->config->item('user_profile_page_maximum_company_locations_show')+1) { 
								$data['address_details'] .= '<input type="hidden" class="moreLocation" value="1"><input type="hidden" class="rLocation" value="'.$remainingLocation.'"><section id="locationDiv" class="chkSameLine" style="display:none">'; 
							} 
								$address_details_extra_locations = '';
								if($extra_location['street_address']) {
									if(!preg_match('/\s/',$extra_location['street_address'])) {
										$address_details_extra_locations .= '<small class="street_address_nospace">'.htmlspecialchars($extra_location['street_address'], ENT_QUOTES).',</small>';
									} else {
										$address_details_extra_locations .= '<small class="">'.htmlspecialchars($extra_location['street_address'], ENT_QUOTES).',</small>';
									}
								} 
								$country_flag = ASSETS .'images/countries_flags/'.strtolower($extra_location['country_code']).'.png';
								if(!empty($extra_location['locality_name']) && !empty($extra_location['postal_code'])) {
									$address_details_extra_locations .= '<small>'.$extra_location['locality_name'].' '.$extra_location['postal_code'].',</small><small>'.$extra_location['county_name'].',</small><small>'.$extra_location['country'].'<div class="default_user_location_flag" style="background-image: url(\''.$country_flag.'\');"></div></small>';
								} else if(!empty($extra_location['locality_name']) && empty($extra_location['postal_code'])) {
									$address_details_extra_locations .= '<small>'.$extra_location['locality_name'].',</small><small>'.$extra_location['county_name'].',</small><small>'.$extra_location['country'].'<div class="default_user_location_flag" style="background-image: url(\''.$country_flag.'\');"></div></small>';
								} else if(empty($extra_location['locality_name']) && !empty($extra_location['postal_code'])) {
									$address_details_extra_locations .= '<small>'.$extra_location['postal_code'].',</small><small>'.$extra_location['county_name'].',</small><small>'.$extra_location['country'].'<div class="default_user_location_flag" style="background-image: url(\''.$country_flag.'\');"></div></small>';
								}else if(!empty($extra_location['county_name'])) {
									$address_details_extra_locations .= '<small>'.$extra_location['county_name'].',</small><small>'.$extra_location['country'].'<div class="default_user_location_flag" style="background-image: url(\''.$country_flag.'\');"></div></small>';
								} else {
									$address_details_extra_locations .= '<small>'.$extra_location['country'].'<div class="default_user_location_flag" style="background-image: url(\''.$country_flag.'\');"></div></small>';
								}
								$data['address_details'] .= '<div class="comLoc default_user_location">
								<span><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span>'.$address_details_extra_locations;

								
								if($m == count($extra_locations_arr)) { 
									$data['address_details'] .= '</section>';
								}
								$branch_opening_hours = $this->db->get_where('users_company_accounts_opening_hours', ['user_id' => $user_detail['user_id'], 'company_location_id' => $extra_location['id'], 'is_company_headquarter' => 'N'])->result_array();
								if(!empty($branch_opening_hours)) {
									$data['company_location_heading'] = $this->config->item('ca_user_profile_page_user_company_location_company_locations_opening_hours_heading');
									
									$address_details = '';
									$first = $branch_opening_hours[0];

									if($first['company_open_hours_status'] != 'selected_hours' || ($first['company_open_hours_status'] == 'selected_hours' && $first['is_selected_hours_checked'] == 'Y')) {
										$address_details .= '<span class="receive_notification">';
										if(!$head_open_hr_exist) {
											$address_details .= "	<a class='rcv_notfy_btn show_more_location' style='display:none' data-id='1'>".$this->config->item('ca_user_profile_page_user_company_location_show_more_opening_hours_text')."</a>";
											$address_details .= "	<a class='rcv_notfy_btn show_less_location'  data-id='0'>".$this->config->item('ca_user_profile_page_user_company_location_hide_extra_opening_hours_text')."</a>";
										} else {
											$address_details .= "	<a class='rcv_notfy_btn show_more_location' data-id='1'>".$this->config->item('ca_user_profile_page_user_company_location_show_more_opening_hours_text')."</a>";
											$address_details .= "	<a class='rcv_notfy_btn show_less_location' style='display:none' data-id='0'>".$this->config->item('ca_user_profile_page_user_company_location_hide_extra_opening_hours_text')."</a>";
										}
										
										$address_details .= '</span>';
									} 

									if($first['company_open_hours_status'] != 'selected_hours') {
										$open_hours_status = '';
										$ohcls = 'othr';
										if($first['company_open_hours_status'] == 'always_opened') {
											$open_hours_status = $this->config->item('ca_user_profile_page_company_open_hours_status_always_opened_label_txt');
										} else if($first['company_open_hours_status'] == 'permanently_closed') {
											$open_hours_status = $this->config->item('ca_user_profile_page_company_open_hours_status_permanently_closed_label_txt');
										} else {
											$open_hours_status = $this->config->item('ca_user_profile_page_company_open_hours_status_by_telephone_appointment_label_txt');
											$ohcls = 'othrtel';
										}
										if(!$head_open_hr_exist) {
											$address_details .= '<div id="content" class="">';
										} else {
											$address_details .= '<div id="content" class="" style="display:none" >';
										}
										$address_details .= '	<div class="default_black_bold_medium mt-1">'.$open_hours_status.'</div>';
										$address_details .= '</div>';
									} else {
										$weekdays = $this->config->item('calendar_weekdays_long_name');
										$days = array_column($branch_opening_hours, 'day');
										$curr_day = date('N');
										$curr_day_idx = array_search($curr_day, $days);
										$opcls = 'cNow';
										$opstatus = $this->config->item('ca_user_profile_page_company_closed_now_label_txt');
										if(isset($curr_day_idx) && is_numeric($curr_day_idx)) {
											$curr_time = strtotime(date('H:i'));
											$st_time = strtotime($branch_opening_hours[$curr_day_idx]['company_opening_time']);
											$end_time = strtotime($branch_opening_hours[$curr_day_idx]['company_closing_time']);

											if($curr_time >= $st_time && $curr_time <= $end_time) {
												$opcls = 'oNow';
												$opstatus = $this->config->item('ca_user_profile_page_company_opened_now_label_txt');
											} 
										}

										if($first['is_selected_hours_checked'] == 'Y') {
											if(!$head_open_hr_exist) {
												$address_details .= '<div id="content" class="">';
											} else {
												$address_details .= '<div id="content" class="" style="display:none" >';
											}
											$address_details .= '	<div class="default_black_bold_medium mt-1 default_bottom_border '.$opcls.'">'.$opstatus.'</div>';

											foreach($weekdays as $key => $day) {
												$open_time = $this->config->item('ca_user_profile_page_company_open_hours_status_closed_label_txt');
												if(in_array($key, $days)) {
													$idx = array_search($key, $days);
													$open_time = $branch_opening_hours[$idx]['company_opening_time'].' - '.$branch_opening_hours[$idx]['company_closing_time'];
												}
												$address_details .= '<div class="staProHr">';
												$address_details .= '	<label>'.$day.'</label>';
												$address_details .= '	<label>'.$open_time.'</label>';
												$address_details .= '	<div class="clearfix"></div>';
												$address_details .= '</div>';
											}
											$address_details .= '</div>';
										}

										
									}
									$data['address_details'] .= $address_details;
									if($first['company_open_hours_status'] != 'selected_hours' || ($first['company_open_hours_status'] == 'selected_hours' && $first['is_selected_hours_checked'] == 'Y')) { 
										if(!$head_open_hr_exist) {
											$head_open_hr_exist = true;
										}
									}
									
								}
							$data['address_details'] .= '</div>';
						}
						if(count($extra_locations_arr) >$this->config->item('user_profile_page_maximum_company_locations_show')) {
							$data['address_details'] .=	'<div class="showmore_category"><label class="AllLocations catAll" onclick="toogleMore('.$remainingLocation.',\'company_locations\')"><i class="fas fa-angle-down"></i> '.str_replace('{remaining_company_locations}', $remainingLocation, $this->config->item('user_profile_page_show_more_company_locations_text')).'</label></div>';
							
						}
						$data['company_base_information'] = $this->db->get_where('users_company_accounts_base_information', ['user_id' => $user_detail['user_id']])->row_array();
					}
				
				
					//check base information tab
					$check_language_id = $this->User_model->get_language_name_from_id($user_detail['mother_tongue_language_id']); // language name fetch by id

					if (!empty($user_detail) && $user_detail['headline'] == '' && $user_detail['description'] == '' && ($user_detail['hourly_rate'] == '' || $user_detail['hourly_rate'] == '0') && ($user_detail['mother_tongue_language_id'] == '' || $user_detail['mother_tongue_language_id'] == '0' || $check_language_id['language'] === NULL)) {
						$this->db->delete('users_profile_base_information', array("user_id" => $user_detail['user_id']));
						$user_detail['mother_tongue_language_id'] = '';
					} else {
						if ($user_detail['mother_tongue_language_id'] != '' && $check_language_id['language'] === NULL) {
							$this->db->update('users_profile_base_information', array('mother_tongue_language_id' => ''), array("user_id" => $user_detail['user_id']));
							$user_detail['mother_tongue_language_id'] = '';
						}
					}
					//end check base info tab
					//start fetch inserted areas of expertise
					$professional_categories = $this->db // get the user detail
						->select('id, professional_category_id, professional_parent_category_id')
						->from('professionals_areas_of_expertise_listings_tracking')
						->where('user_id', $user_detail['user_id'])
						->get()->result_array();
						
					
					$mainArr = array();
					foreach ($professional_categories as $cat) {
						$result = $this->db->get_where('categories_professionals', ['id' => $cat['professional_category_id'], 'parent_id' => $cat['professional_parent_category_id']])->row_array();
						if(empty($result)) {
							$this->db->delete('professionals_areas_of_expertise_listings_tracking', ['id' => $cat['id']]);
							continue;
						} else {
							if($cat['professional_parent_category_id'] != 0) {
								$result = $this->db->get_where('categories_professionals', ['id' => $cat['professional_parent_category_id']])->row_array();
								if(empty($result)) {
									$this->db->delete('professionals_areas_of_expertise_listings_tracking', ['id' => $cat['id']]);
									continue;
								}
							}
						}
						if ($cat['professional_parent_category_id'] == 0) {
							$mainArr[$cat['professional_category_id']][] = $cat['professional_parent_category_id'];
						} else {
							$mainArr[$cat['professional_parent_category_id']][] = $cat['professional_category_id'];
						}
						
					}
					
					
					// die;
					//echo '<pre>'; print_r($mainArr); echo '</pre>'; die();
					
					$recordArr = array();
					foreach ($mainArr as $key => $arr) {
						$category = $this->db
							->select('id, name')
							->from('categories_professionals')
							->where('id', $key)
							->get()->row_array();
						$recordArr[$category['name']] = $category['name'];
						if($arr[0]!=0) {
							$recordSArr = array();
							foreach ($arr as $scat) {
								$scategory = $this->db
								->select('id, name')
								->from('categories_professionals')
								->where('id', $scat)
								->get()->row_array();
								$recordSArr[] = $scategory['name'];
							}
							asort($recordSArr);
							//$recordSArr = asort($recordSArr);
							$recordArr[$category['name']] = $recordSArr;
						}
					}
					ksort($recordArr);
					//echo '<pre>'; print_r($recordArr); echo '</pre>'; die();

					/* $areas_of_expertise = '';
					foreach ($recordArr as $catKey=>$catArr) {
						$areas_of_expertise .= '<p><label class="defaultTag"><label>
							<span class="tagFirst">'.$catKey.'</span>';
							if(is_array($catArr)){
								foreach ($catArr as $scat) {
									$areas_of_expertise .= '<small class="tagSecond">'.$scat.'</small>';
								}
							}
						$areas_of_expertise .= '</label></label></p>';
					}
					
					//echo $areas_of_expertise;	die;
					$data['areas_of_expertise'] = $areas_of_expertise; */
					$data['recordArr'] = $recordArr; 
					//end fetch inserted areas of expertise
					 $data['user_detail'] = $user_detail;

					// Fetch language name for user profile  (spoken language)
					$data['language_name'] = $check_language_id;
					//check same user on their page
					$data['own_profile'] = 0;
					if ($user_detail['user_id'] == $user[0]->user_id) {
						$data['own_profile'] = 1;
					}
					
					############ Statistics as Service Provider  ################
					// number of won projects of sp (fixed/hourly)
					 $get_sp_won_projects_count = $this->Bidding_model->get_sp_won_projects_count($user_detail['user_id']);
					  $data['sp_won_projects_count'] = $get_sp_won_projects_count;
					  
					 // number of in progress projects of sp (fixed/hourly) 
					 $get_sp_in_progress_projects_count = $this->Bidding_model->get_sp_in_progress_projects_count($user_detail['user_id']);
					$data['sp_in_progress_projects_count'] = $get_sp_in_progress_projects_count;
					
					// number of completed projects via portal of sp (fixed/hourly) 
					$data['sp_completed_projects_count_via_portal'] = $this->Bidding_model->get_sp_completed_projects_count(array('winner_id'=>$user_detail['user_id'],'project_completion_method'=>'via_portal'));
					
					// number of completed projects of sp (fixed/hourly  via portal/ out side portal) 
					$data['sp_completed_projects_count'] = $this->Bidding_model->get_sp_completed_projects_count(array('winner_id'=>$user_detail['user_id']));
					
					// number of fulltime projects on which sp working 
					$sp_hires_fulltime_projects_count = $this->Bidding_model->get_sp_hires_fulltime_projects_count($user_detail['user_id']);
					$data['sp_hires_fulltime_projects_count'] = $sp_hires_fulltime_projects_count;
					############### Statistics as Project Owner ###########
					// number of published projects of po (fixed/hourly)
					$po_published_projects = $this->Projects_model->get_po_published_projects_count($user_detail['user_id']);
					$data['po_published_projects'] = $po_published_projects;
					
					// number of published fulltime projects of po (fulltime)
					$po_published_fulltime_projects_count = $this->Projects_model->get_po_published_fulltime_projects_count($user_detail['user_id']);
					$data['po_published_fulltime_projects_count'] = $po_published_fulltime_projects_count;
					
					// sum of published(fixed/hourly project)+published(fulltime project)
					$data['po_total_posted_projects'] = $po_published_projects+$po_published_fulltime_projects_count;
					
					// number of in progress projects of po (fixed/hourly)
					$po_in_progress_projects_count = $this->Projects_model->get_po_in_progress_projects_count($user_detail['user_id']);
					$data['po_in_progress_projects_count'] = $po_in_progress_projects_count;
					
					// number of completed projects of po (fixed/hourly outside portal/via portal)
					$po_completed_projects_count = $this->Projects_model->get_po_completed_projects_count(array('project_owner_id'=>$user_detail['user_id']));
					$data['po_completed_projects_count'] = $po_completed_projects_count;
					
					// number of completed projects of po (via portal)
					$po_completed_projects_count_via_portal = $this->Bidding_model->get_sp_completed_projects_count(array('project_owner_id'=>$user_detail['user_id'],'project_completion_method'=>'via_portal'));
					$data['po_completed_projects_count_via_portal'] = $po_completed_projects_count_via_portal;
					
					// number of hires sp by po for fulltime projects
					$get_po_hires_sp_on_fulltime_projects_count = $this->Projects_model->get_po_hires_sp_on_fulltime_projects_count($user_detail['user_id']);
					$data['get_po_hires_sp_on_fulltime_projects_count'] = $get_po_hires_sp_on_fulltime_projects_count;
				
					
					
					if(($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y' )){
						$this->User_model->remove_scrambled_user_spoken_languages_entries($user_detail['user_id']);
						$this->User_model->remove_scrambled_user_work_experience_entries($user_detail['user_id']);
						$this->User_model->remove_scrambled_user_education_training_entries($user_detail['user_id']);
						
						############ get education for personal account ##########
						$data['user_education_training_data'] = $this->User_model->get_user_profile_page_education_training_listing(array('user_id'=>$user_detail['user_id']));
						$data['user_work_experience_data'] = $this->User_model->get_user_profile_page_work_experience_listing(array('user_id'=>$user_detail['user_id']));
						$data['user_spoken_languages_data'] = $this->User_model->get_user_profile_page_spoken_languages_listing(array('user_id'=>$user_detail['user_id']));
					}
					$this->User_model->remove_scrambled_user_services_provided_entries($user_id);	
					################################# reset scrambles user skills ###
					$this->User_model->remove_scrambled_user_skills_entries($user_id);
					$this->User_model->remove_scrambled_user_certifications_entries($user_id);
					############ get user skills##########
					$data['user_skills_data'] = $this->User_model->get_user_profile_page_skills_listing(array('user_id'=>$user_detail['user_id']));
					############ get user services##########
					$data['user_services_data'] = $this->User_model->get_user_profile_page_services_provided_listing(array('user_id'=>$user_detail['user_id']));
					############ get certifications for personal account/company account start ##########
					$data['user_certifications_data'] = $this->User_model->get_user_profile_page_certifications_listing(array('user_id'=>$user_detail['user_id']));
					if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
						echo json_encode(['status' => 200,'data'=>$this->load->view('user/personal_account_information_tab_user_profile_page',$data, true)]);
						die;
					}
					if($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE){
						echo json_encode(['status' => 200,'data'=>$this->load->view('user/ca_information_tab_user_profile_page',$data, true)]);
						die;
					}
				}
				if($tab_type == 'portfolio'){
				
					$this->User_model->remove_user_orphan_portfolio_images(array('user_id'=>$user_detail['user_id'],'profile_name'=>$user_detail['profile_name'])); // remove orphan entries of portfolio images
				
					$no_of_records_per_page = $this->config->item('user_profile_page_portfolio_tab_limit');
					$portfolio_listing_data = $this->User_model->get_user_profile_page_portfolio_listing(array('user_id'=>$user_detail['user_id']),0,$no_of_records_per_page);
					$data["user_portfolio_data"] = $portfolio_listing_data['data'];
					$data['user_portfolio_count'] = $portfolio_listing_data['total'];
					echo json_encode(['status' => 200,'data'=>$this->load->view('user/user_profile_page_portfolio_listings_tab',$data, true)]);
					die;
				}
				if($tab_type == 'posted_projects'){
					############ fetch the listing of project created by project owner
					$no_of_records_per_page = $this->config->item('user_profile_page_posted_projects_tab_limit');
					$po_posted_project_listing = $this->Projects_model->get_po_posted_project_listing_profile_page($user_detail['user_id'],'0',$no_of_records_per_page);

					// pre($po_posted_project_listing['data']);
					
					$data['po_posted_project_listing'] = $po_posted_project_listing['data'];
					$data['po_posted_project_count'] = $po_posted_project_listing['total'];
					echo json_encode(['status' => 200,'data'=>$this->load->view('projects/user_profile_page_po_posted_projects_listings_tab',$data, true)]);
					die;
				}
				if($tab_type == 'won_projects'){
					############ fetch the won project of service provider
					$no_of_records_per_page = $this->config->item('user_profile_page_won_projects_tab_limit');
					$sp_won_project_listing = $this->Bidding_model->get_sp_won_project_listing_profile_page($user_detail['user_id'],'0',$no_of_records_per_page);
					$var_sp_won_project_listing = $sp_won_project_listing['data'];
					// $data['sp_won_project_listing'] = $sp_won_project_listing['data'];
					$data['sp_won_project_count'] = $sp_won_project_listing['total'];
					foreach($var_sp_won_project_listing as &$value) {
						if($value['project_type'] == 'fulltime') {
							$value['total_paid_amount'] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($value['project_type'], ['fulltime_project_id' => $value['project_id'], 'employer_id' => $value['project_owner_id'], 'employee_id' => $user_detail['user_id']]);
						} else {
							$value['total_paid_amount'] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($value['project_type'], ['project_id' => $value['project_id'], 'project_owner_id' => $value['project_owner_id'], 'winner_id' => $user_detail['user_id']]);
						}
					}
					$data['sp_won_project_listing'] = $var_sp_won_project_listing;
					$data['user_id'] = $user_detail['user_id'];
					echo json_encode(['status' => 200,'data'=>$this->load->view('bidding/user_profile_page_sp_won_projects_listings_tab',$data, true)]);
					die;
				}
				if($tab_type == 'reviews'){
					echo json_encode(['status' => 200,'data'=>$this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab',$data, true)]);
					die;
				}
				
				
			}else{
				show_custom_404_page(); //show custom 404 page
			}
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
      
	// This function is using for loadmore paging on portfolio tab for user profile page  
	public function user_profile_page_portfolio_tab_loadmore_limit(){
		if($this->input->is_ajax_request ()){
			if ($this->input->post () )
			{
				$post_data = $this->input->post ();
				$user_id = Cryptor::doDecrypt($post_data['uid']);
				$user_detail = $this->db // get the user detail
                ->select('u.user_id,u.profile_name ,u.account_type,u.first_name,u.last_name,u.company_name')
                ->from('users u')
                ->where('u.user_id', $user_id)
                ->get()->row_array();
				
				$this->User_model->remove_user_orphan_portfolio_images(array('user_id'=>$user_detail['user_id'],'profile_name'=>$user_detail['profile_name'])); // remove orphan entries of portfolio images
				$data["user_detail"] = $user_detail;
				
				
				$post_data['pageno'];
				$pageno = $post_data['pageno'];
				$no_of_records_per_page = $this->config->item('user_profile_page_portfolio_tab_limit');
				$offset = ($pageno-1) * $no_of_records_per_page;
				$portfolio_listing_data = $this->User_model->get_user_profile_page_portfolio_listing(array('user_id'=>$user_id),$offset,$no_of_records_per_page);
				$data["user_portfolio_data"] = $portfolio_listing_data['data'];
				$data['user_portfolio_count'] = $portfolio_listing_data['total'];
				$count_records = count($portfolio_listing_data['data']);
				echo json_encode(['status' => 200,'count_records'=>$count_records,'total_record'=>$portfolio_listing_data['total'],'data'=>$this->load->view('user/user_profile_page_portfolio_listings_tab',$data, true)]);
				die;
			}else{
				show_custom_404_page(); //show custom 404 page
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	
	} 
	
	// This function is using for loadmore paging on posted projects tab for user profile page  
	public function user_profile_page_posted_projects_tab_loadmore_limit(){
		if($this->input->is_ajax_request ()){
			if ($this->input->post () )
			{
				$post_data = $this->input->post ();
				$user_id = Cryptor::doDecrypt($post_data['uid']);
				//$user_id = 146;
				
				$post_data['pageno'];
				$pageno = $post_data['pageno'];
				$no_of_records_per_page = $this->config->item('user_profile_page_posted_projects_tab_limit');
				$offset = ($pageno-1) * $no_of_records_per_page;
				$po_posted_project_listing = $this->Projects_model->get_po_posted_project_listing_profile_page($user_id,$offset,$no_of_records_per_page);
				
				$data['po_posted_project_listing'] = $po_posted_project_listing['data'];
				$data['po_posted_project_count'] = $po_posted_project_listing['total'];
				$count_records = count($po_posted_project_listing['data']);
				echo json_encode(['status' => 200,'count_records'=>$count_records,'total_record'=>$po_posted_project_listing['total'],'data'=>$this->load->view('projects/user_profile_page_po_posted_projects_listings_tab',$data, true)]);
				die;
			}else{
				show_custom_404_page(); //show custom 404 page
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	// This function is using for loadmore paging on won projects tab for user profile page  
	public function user_profile_page_won_projects_tab_loadmore_limit(){
		if($this->input->is_ajax_request ()){
			if ($this->input->post () )
			{
				$post_data = $this->input->post ();
				$user_id = Cryptor::doDecrypt($post_data['uid']);
				//$user_id = 146;
				
				$post_data['pageno'];
				$pageno = $post_data['pageno'];
				$no_of_records_per_page = $this->config->item('user_profile_page_won_projects_tab_limit');
				$offset = ($pageno-1) * $no_of_records_per_page;
				$sp_won_project_listing = $this->Bidding_model->get_sp_won_project_listing_profile_page($user_id,$offset,$no_of_records_per_page);
				$var_sp_won_project_listing = $sp_won_project_listing['data'];
				// $data['sp_won_project_listing'] = $sp_won_project_listing['data'];
				$data['sp_won_project_count'] = $sp_won_project_listing['total'];
				$count_records = count($sp_won_project_listing['data']);
				foreach($var_sp_won_project_listing as &$value) {
					if($value['project_type'] == 'fulltime') {
						$value['total_paid_amount'] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($value['project_type'], ['fulltime_project_id' => $value['project_id'], 'employer_id' => $value['project_owner_id'], 'employee_id' => $user_id]);
					} else {
						$value['total_paid_amount'] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($value['project_type'], ['project_id' => $value['project_id'], 'project_owner_id' => $value['project_owner_id'], 'winner_id' => $user_id]);
					}
				}
				$data["sp_won_project_listing"] = $var_sp_won_project_listing;
				$data['user_id'] = $user_id;
				echo json_encode(['status' => 200,'count_records'=>$count_records,'total_record'=>$sp_won_project_listing['total'],'data'=>$this->load->view('bidding/user_profile_page_sp_won_projects_listings_tab',$data, true)]);
				die;
			}else{
				show_custom_404_page(); //show custom 404 page
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
		
	
	}
	
    public function account_management_account_details() {
        if(!$this->session->userdata('user')) {
            redirect(base_url());
        }
        if(check_session_validity()) {
            $user = $this->session->userdata('user');
            $user_id = $user[0]->user_id;
            $data['current_page'] = 'account-management-account-details';
            $name = $this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_PERSONAL_ACCOUNT_TYPE ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);

            $account_management_title_meta_tag = $this->config->item('account_management_page_title_meta_tag');
            $account_management_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_title_meta_tag);
            $account_management_description_meta_tag = $this->config->item('account_management_page_description_meta_tag');
            $account_management_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_description_meta_tag);
            $data['meta_tag'] = '<title>' . $account_management_title_meta_tag . '</title><meta name="description" content="' . $account_management_description_meta_tag . '"/>';

          ########## set the profile title tag start end #########
          $this->layout->view ('user_account_management_account_details', '', $data, 'include');
        }
    }
   
	public function account_management_account_login_details() {
		
        if(!$this->session->userdata('user')) {
            redirect(base_url());
        }
        if(check_session_validity()) {
            $user = $this->session->userdata('user');
            $user_id = $user[0]->user_id;
			
			$user_detail = $this->db // get the user detail
			->select('u.user_id,u.email,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
			->from('users u')
			->where('u.user_id', $user_id)
			->get()->row_array();
			
			$account_email_array = explode("@",$user_detail['email']);
			$account_email = replace_characters_asterisks_except_first_last($account_email_array[0])."@".replace_characters_asterisks_except_first_last($account_email_array[1]);
			
			$data['account_email'] = $account_email;
            $data['current_page'] = 'account-management-account-login-details';
            //$name = $user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];
			
			$name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];

            $account_management_account_login_details_page_title_meta_tag = $this->config->item('account_management_account_login_details_page_title_meta_tag');
            $account_management_account_login_details_page_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_account_login_details_page_title_meta_tag);
            $account_management_account_login_details_page_description_meta_tag = $this->config->item('account_management_account_login_details_page_description_meta_tag');
            $account_management_account_login_details_page_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_account_login_details_page_description_meta_tag);
            $data['meta_tag'] = '<title>' . $account_management_account_login_details_page_title_meta_tag . '</title><meta name="description" content="' . $account_management_account_login_details_page_description_meta_tag . '"/>';
			
			
			

          ########## set the profile title tag start end #########
          $this->layout->view ('user_account_management_account_login_details', '', $data, 'include');
        }
    }
	
	public function account_management_close_account() {
	
        if(!$this->session->userdata('user')) {
            redirect(base_url());
        }
        if(check_session_validity()) {
            $user = $this->session->userdata('user');
            $user_id = $user[0]->user_id;
            $data['current_page'] = 'account-management-close-account';
            $name = (($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_PERSONAL_ACCOUNT_TYPE) || ($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_COMPANY_ACCOUNT_TYPE && $this->auto_model->getFeild('is_authorized_physical_person', 'users', 'user_id', $user_id) == 'Y')) ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
			
			$close_account_request_detail = $this->db // get the user detail
			->select('*')
			->from('users_close_account_requests_tracking ')
			->where('user_id', $user_id)
			->get()->row_array();
			
			
			$data['close_account_request_detail'] = $close_account_request_detail;
            $account_management_account_close_account_page_title_meta_tag = $this->config->item('account_management_account_close_account_page_title_meta_tag');
            $account_management_account_close_account_page_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_account_close_account_page_title_meta_tag);
            $account_management_account_close_account_page_description_meta_tag = $this->config->item('account_management_account_close_account_page_description_meta_tag');
            $account_management_account_close_account_page_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $account_management_account_close_account_page_description_meta_tag);
            $data['meta_tag'] = '<title>' . $account_management_account_close_account_page_title_meta_tag . '</title><meta name="description" content="' . $account_management_account_close_account_page_description_meta_tag . '"/>';

          ########## set the profile title tag start end #########
          $this->layout->view ('user_account_management_close_account', '', $data, 'include');
        }
    }
	
	// This function is used to check the validation for close account page and make a confirmation popoup for close account
	public function account_management_close_account_confirmation_popup_body(){
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('dashboard_page_url')]);
			die;
		}
		
		if($this->input->method(TRUE) === 'POST'){
			$user = $this->session->userdata('user');
			$user_id = $user[0]->user_id;
			$post_data = $this->input->post ();
			if(Cryptor::doDecrypt($this->input->post ('uid')) != $user_id){
					
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				die;
			}
			
			//$validation_data_array = $this->Projects_disputes_model->project_dispute_form_validation($post_data,$project_data);
			$validation_data_array = $this->User_model->account_management_close_account_form_validation($post_data);
			if($validation_data_array['status'] == 'SUCCESS'){
			
				$confirmation_modal_title = $this->config->item('close_account_confirmation_modal_title');
				
				$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('cancel_btn_txt').'</button><button type="button" disabled style="opacity:0.65" class="btn blue_btn default_btn close_account_send_request_button width-auto">'.$this->config->item('close_account_confirmation_modal_close_account_send_request_btn_txt').' <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>';
				
				$disclaimer_message = $this->config->item('user_confirmation_check_box_txt');
				
				$confirmation_modal_message =  $this->config->item('close_account_confirmation_modal_body');
				$confirmation_modal_body .= '<div class="popup_body_semibold_title">'.$confirmation_modal_message.'</div>';
				$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="radio_modal_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="close_account_checkbox"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$disclaimer_message.'</span></label></div></div></div>';
				echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
				die;
			
			
			}else{
				//echo json_encode($msg);
				echo json_encode ($validation_data_array);
				die;
			}
			
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	// This function is used to send the close account request to travai admin and staff
	public function account_management_send_close_account_request(){
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('dashboard_page_url')]);
			die;
		}
		
		if($this->input->method(TRUE) === 'POST'){
			$user = $this->session->userdata('user');
			$user_id = $user[0]->user_id;
			if(Cryptor::doDecrypt($this->input->post ('uid')) != $user_id){
					
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				die;
			}
			$post_data = $this->input->post ();
			
			$check_already_send_close_account = $this->db->where(['user_id' => $user_id])->from('users_close_account_requests_tracking')->count_all_results();
			if($check_already_send_close_account == 0 ){
			    $close_account_request_sent_time = date('Y-m-d H:i:s');
				$close_account_data['user_id']= $user_id;
				$close_account_data['close_account_reason']= $post_data['close_account_reason'];
				$close_account_data['close_account_reason_feedback']= trim($post_data['close_account_reason_feedback']);
				$close_account_data['close_account_request_sent_time']= $close_account_request_sent_time;
				if($this->db->insert ('users_close_account_requests_tracking', $close_account_data)){
				
					 $name = (($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')) ? $user[0]->first_name . ' ' . $user[0]->last_name : $user[0]->company_name;
					 
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
					
					
					$subject = $this->config->item('user_close_account_request_email_subject');
					$msg = $this->config->item('user_close_account_request_email_message');
					$profile_url = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://").$_SERVER['HTTP_HOST'].'/'.$user[0]->profile_name;
					
					$msg = str_replace(['{user_profile_page_url}','{user_first_name_last_name_or_company_name}','{close_account_reason}','{close_account_reason_feedback}','{close_account_request_sent_time}'], [$profile_url,$name,$post_data['close_account_reason'],trim($post_data['close_account_reason_feedback']),date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($close_account_request_sent_time))], $msg);

					$this->email->initialize($config);
					
					$from_name =$this->config->item('user_close_account_request_email_from_name');
					$from_name = '=?utf-8?B?'.base64_encode($from_name).'?=';
					
					$this->email->from($this->config->item('user_close_account_request_email_from'),$from_name );
					if($this->config->item('user_close_account_request_email_reply_to')) {
						$this->email->reply_to($this->config->item('user_close_account_request_email_reply_to'));
					}
					if($this->config->item('user_close_account_request_email_cc')) {
						$this->email->cc($this->config->item('user_close_account_request_email_cc'));
					}
					if($this->config->item('user_close_account_request_email_bcc')) {
						$this->email->bcc($this->config->item('user_close_account_request_email_bcc'));
					}
					
					$this->email->subject($subject);
					$this->email->to($user[0]->email);
					$this->email->message($msg);
					$this->email->send();
				}
			}
			$close_account_request_detail = $this->db // get the user detail
			->select('*')
			->from('users_close_account_requests_tracking ')
			->where('user_id', $user_id)
			->get()->row_array();
			
			echo json_encode(['status' => 200,'location'=>'','msg'=>$this->config->item('user_close_account_request_sent_confirmation_message')]);
			die;
			
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
    public function profile_management_page_profile_definitions() {
		
        if(!$this->session->userdata('user')) {
            redirect(base_url());
        }
		
        if(check_session_validity()) {
            $user = $this->session->userdata('user');
            $user_id = $user[0]->user_id;
            $data['current_page'] = 'profile-management-profile-definitions';
			
			
			
            $name = (($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_PERSONAL_ACCOUNT_TYPE) || ($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_COMPANY_ACCOUNT_TYPE && $this->auto_model->getFeild('is_authorized_physical_person', 'users', 'user_id', $user_id) == 'Y')) ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);

            $profile_management_title_meta_tag = $this->config->item('user_profile_management_profile_definitions_page_title_meta_tag');
            $profile_management_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $profile_management_title_meta_tag);
            $profile_management_description_meta_tag = $this->config->item('user_profile_management_profile_definitions_page_description_meta_tag');
            $profile_management_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $profile_management_description_meta_tag);
            $data['meta_tag'] = '<title>' . $profile_management_title_meta_tag . '</title><meta name="description" content="' . $profile_management_description_meta_tag . '"/>';

          ########## set the profile title tag start end #########
          $this->layout->view ('user_profile_management_profile_definitions', '', $data, 'include');
        }
    }
	
	// This function is using to update the tabs (headline/description/hourly rate tabs) of profile management
	public function update_profile_management_profile_definitions_tabs(){
		
		if($this->input->is_ajax_request ()){
			if ($this->input->post () )
			{
				if(check_session_validity()){ 
					$post_data = $this->input->post ();
					$user = $this->session->userdata ('user');
					$tab_type = $post_data['tab_type'];
					
					$user_id = $user[0]->user_id;
					
					if($user_id != Cryptor::doDecrypt($post_data['uid'])){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					
					 $profile_base_information = $this->db // get the user detail
					->select('*')
					->from('users_profile_base_information')
					->where('user_id', $user_id)
					->get()->row_array();
					$headline = '';
					if($tab_type == 'headline_tab'){
						if(!empty($profile_base_information) && !empty(trim($profile_base_information['headline']))){
							//$headline = htmlspecialchars(trim($profile_base_information['headline']), ENT_QUOTES);
							$headline = trim($profile_base_information['headline']);
						}
						$data['headline'] = $headline;
						echo json_encode(['status' => 200,'data'=>$this->load->view('user/user_profile_management_profile_definitions_user_headline',$data, true)]);die; 
						
						
						
					}
					if($tab_type == 'description_tab'){
					
						if(!empty($profile_base_information) && !empty(trim($profile_base_information['description']))){
							$user_description = trim($profile_base_information['description']);
						}
						$data['user_description'] = $user_description;
					
						echo json_encode(['status' => 200,'data'=>$this->load->view('user/user_profile_management_profile_definitions_user_description',$data, true)]);die;
						 
					}
					if($tab_type == 'hourly_rate_tab'){
						if(!empty($profile_base_information) && !empty(trim($profile_base_information['hourly_rate']))){
							$user_hourly_rate = trim($profile_base_information['hourly_rate']);
						}
						$data['user_hourly_rate'] = $user_hourly_rate;
						echo json_encode(['status' => 200,'data'=>$this->load->view('user/user_profile_management_profile_definitions_user_hourly_rate',$data, true)]);die;
						 
					}	
				
				}else{
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
				}
			}else{
				show_custom_404_page(); //show custom 404 page
			}	
		}else{
			show_custom_404_page();
		}
	
	}	
	
	
	//This function is used to fetch the base information/hourly rate/headline for  profile definations page regarding profile managament 
	public function get_user_profile_management_profile_definitions(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				if($user_id != Cryptor::doDecrypt($post_data['uid'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$profile_base_information = $this->db // get the user detail
				->select('*')
				->from('users_profile_base_information')
				->where('user_id', $user_id)
				->get()->row_array();
				if(!empty($profile_base_information['mother_tongue_language_id']) && $profile_base_information['mother_tongue_language_id'] !=0){
					$check_language_id = $this->User_model->get_language_name_from_id($profile_base_information['mother_tongue_language_id']); // language name fetch by id
					
					if(empty($check_language_id)){
						$this->db->update('users_profile_base_information', array('mother_tongue_language_id' => 0), array("user_id" => $user_id));
						$profile_base_information['mother_tongue_language_id'] = 0;
					}
				}
				
				
				
				if(!empty($profile_base_information) && empty($profile_base_information['headline']) && empty($profile_base_information['description']) && $profile_base_information['hourly_rate'] == 0 && $profile_base_information['mother_tongue_language_id'] == 0){
					
					$this->db->delete('users_profile_base_information', array("user_id" => $user_id));
					
					// profile completeion script start //	
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_mother_tongue_indicated'] = 'N';
					$user_profile_completion_data['mother_tongue_strength_value'] =0;
					$user_profile_completion_data['has_headline_indicated'] = 'N';
					$user_profile_completion_data['headline_strength_value'] =0;
					$user_profile_completion_data['has_description_indicated'] = 'N';
					$user_profile_completion_data['description_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);

					}
					// profile completeion script end //
				} 
				
				
				$profile_base_information = $this->db // get the user detail
				->select('*')
				->from('users_profile_base_information')
				->where('user_id', $user_id)
				->get()->row_array();
				$headline  = '';
				$user_description  = '';
				$user_hourly_rate  = '';
				$mother_tongue_language_id  = '';
				$character_length_count_message = '';
				$mother_tongue_language_view = '';
				
				if($post_data['section_name'] == 'headline'){
					
					if(!empty($profile_base_information) && !empty(trim($profile_base_information['headline']))){
						$headline = trim($profile_base_information['headline']);
						$character_length_count_message = ($this->config->item('profile_management_user_headline_maximum_length_character_limit') - mb_strlen($headline)) . " " . $this->config->item('characters_remaining_message');
					}else{
						$character_length_count_message = $this->config->item('profile_management_user_headline_maximum_length_character_limit') . " " . $this->config->item('characters_remaining_message');
					}
					$data = $headline;
				}
				if($post_data['section_name'] == 'user_description'){
					
					if(!empty($profile_base_information) && !empty(trim($profile_base_information['description']))){
						$user_description = trim($profile_base_information['description']);
						$character_length_count_message = ($this->config->item('profile_management_user_description_maximum_length_character_limit') - mb_strlen($user_description)) . " " . $this->config->item('characters_remaining_message');
					}else{
						$character_length_count_message = $this->config->item('profile_management_user_description_maximum_length_character_limit') . " " . $this->config->item('characters_remaining_message');
					}
					$data = $user_description;
				}
				if($post_data['section_name'] == 'user_hourly_rate'){
					
					if(!empty($profile_base_information) && trim($profile_base_information['hourly_rate']) != 0){
						
						$user_hourly_rate = trim($profile_base_information['hourly_rate']);
						$data = str_replace(".00","",number_format($user_hourly_rate,  2, '.', ' '));
						
					}
					$data = $user_hourly_rate;
					
				}
				if($post_data['section_name'] == 'user_mother_tongue'){
					
					if(!empty($profile_base_information) && trim($profile_base_information['mother_tongue_language_id']) != 0){
						
						$mother_tongue_language_id = trim($profile_base_information['mother_tongue_language_id']);
						
						$mother_tongue_language_fetch = $this->db // get the user detail
						->select('language')
						->from('languages')
						->where('id', $mother_tongue_language_id)
						->get()->row_array();
						
						$mother_tongue_language_view = $mother_tongue_language_fetch['language'];
					}
					//$data = $mother_tongue_language_view;
					
					if($post_data['action_type'] == 'cancel'){
						$data = $mother_tongue_language_view;
					}else{
						$data = $mother_tongue_language_id;
					}
					
					
					
				}
				echo json_encode(['status' => 200,'message'=>'','location'=>'','data'=>$data,'character_length_count_message'=>$character_length_count_message]);
				die;
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH;
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	//This function is used to save profile definations of user into database
	public function save_user_profile_management_profile_definitions(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				if($user_id != Cryptor::doDecrypt($post_data['uid'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
			
				
				if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
					$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
					
				}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
					$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
				}
				$post_data['account_type'] = $user[0]->account_type;
				$validation_data_array = $this->User_model->user_profile_definations_validation($post_data);
				if ($validation_data_array['status'] == 'SUCCESS')
				{
					$data = '';
					$profile_base_information = $this->db // get the user detail
					->select('*')
					->from('users_profile_base_information')
					->where('user_id', $user_id)
					->get()->row_array();
					
				
					
					if($post_data['section_name'] == 'headline'){
						
						$profile_base_information_data['headline'] = trim($post_data['data']);
						$profile_base_information_data['user_id'] = $user_id;
						$data = $post_data['data'];
					}
					if($post_data['section_name'] == 'user_description'){
						$profile_base_information_data['description'] = trim($post_data['data']);
						$profile_base_information_data['user_id'] = $user_id;
						$data = $post_data['data'];
					}
					if($post_data['section_name'] == 'user_hourly_rate'){
						
						$profile_base_information_data['hourly_rate'] = trim(str_replace(" ","",$post_data['data']));
						$profile_base_information_data['user_id'] = $user_id;
						$data = $post_data['data'];
					}
					if($post_data['section_name'] == 'user_mother_tongue'){
						
						$profile_base_information_data['mother_tongue_language_id'] = $post_data['data'];
						$mother_tongue_language_fetch = $this->db // get the user detail
						->select('language')
						->from('languages')
						->where('id', $post_data['data'])
						->get()->row_array();
						
						$mother_tongue_language_view = $mother_tongue_language_fetch['language'];
					
						$profile_base_information_data['user_id'] = $user_id;
						//$data = $post_data['data'];
						$data = trim($mother_tongue_language_view);
					}
					/*if($post_data['section_name'] == 'headline'){
						$data = htmlspecialchars(trim($post_data['data']), ENT_QUOTES);
					} else {*/
						
					//}
					if(empty($profile_base_information)){
						$this->db->insert ('users_profile_base_information', $profile_base_information_data);

						if($post_data['section_name'] == 'headline' || $post_data['section_name'] == 'user_description'){ 
							$this->User_model->save_find_professionals_user_information($user_id, 'insert', trim($post_data['data']));
						}
					} else {
						$this->db->update('users_profile_base_information', $profile_base_information_data, ['user_id'=> $user_id]);

						if($post_data['section_name'] == 'headline' || $post_data['section_name'] == 'user_description'){ 
							$old_str = '';
							if($post_data['section_name'] == 'headline') {
								$old_str = $profile_base_information['headline'];
							} else {
								$old_str = $profile_base_information['description'];
							}
							$this->User_model->save_find_professionals_user_information($user_id, 'update', trim($post_data['data']), $old_str);
						}
					}
					// profile completion script start //
					$user_profile_completion_data = array();
					if($post_data['section_name'] == 'headline' ||  $post_data['section_name'] == 'user_description' || $post_data['section_name'] == 'user_mother_tongue' ){
						if($post_data['section_name'] == 'headline'){
							$user_profile_completion_data['has_headline_indicated'] = 'Y';
							$user_profile_completion_data['headline_strength_value'] = $profile_completion_parameters['headline_strength_value'];
						}
						
						if($post_data['section_name'] == 'user_description'){
							
							$user_profile_completion_data['has_description_indicated'] = 'Y';
							$user_profile_completion_data['description_strength_value'] = $profile_completion_parameters['description_strength_value'];
						}
						
						if($post_data['section_name'] == 'user_mother_tongue'){
							
							$user_profile_completion_data['has_mother_tongue_indicated'] = 'Y';
							$user_profile_completion_data['mother_tongue_strength_value'] = $profile_completion_parameters['mother_tongue_strength_value'];
							
						} 
						if(!empty($user_profile_completion_data)){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
						}
					}
					// profile completion script end //
					echo json_encode(['status' => 'SUCCESS','message'=>'','location'=>'','data'=>$data]);
					die;
					
				}else{
					echo json_encode ($validation_data_array);
					die;
				}
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH;
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	//This function is used to profile definations of user from database
	public function delete_user_profile_management_profile_definitions(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				if($user_id != Cryptor::doDecrypt($post_data['uid'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$character_length_count_message = '';
				if($post_data['section_name'] == 'headline'){
					$profile_base_information_data['headline'] = '';
					$character_length_count_message = $this->config->item('profile_management_user_headline_maximum_length_character_limit') . " " . $this->config->item('characters_remaining_message');
				}
				if($post_data['section_name'] == 'user_description'){
					$profile_base_information_data['description'] = '';
					$character_length_count_message = $this->config->item('profile_management_user_description_maximum_length_character_limit') . " " . $this->config->item('characters_remaining_message');
				}
				if($post_data['section_name'] == 'user_hourly_rate'){
					$profile_base_information_data['hourly_rate'] = 0;
				}
				if($post_data['section_name'] == 'user_mother_tongue'){
					$profile_base_information_data['mother_tongue_language_id'] = 0;
				}
				
				$this->db->update('users_profile_base_information', $profile_base_information_data, ['user_id'=> $user_id]);
				
				$profile_base_information = $this->db // get the user detail
				->select('*')
				->from('users_profile_base_information')
				->where('user_id', $user_id)
				->get()->row_array();
				
				if(!empty($profile_base_information) && empty($profile_base_information['headline']) && empty($profile_base_information['description']) && $profile_base_information['hourly_rate'] == 0 && $profile_base_information['mother_tongue_language_id'] == 0){
					$this->db->delete('users_profile_base_information', array("user_id" => $user_id));
					// profile completeion script start //	
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_mother_tongue_indicated'] = 'N';
					$user_profile_completion_data['mother_tongue_strength_value'] =0;
					$user_profile_completion_data['has_headline_indicated'] = 'N';
					$user_profile_completion_data['headline_strength_value'] =0;
					$user_profile_completion_data['has_description_indicated'] = 'N';
					$user_profile_completion_data['description_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);

					}
					// profile completeion script end //
				} 
				
				// profile completion script start here //
				if($post_data['section_name'] == 'headline' || $post_data['section_name'] == 'user_mother_tongue' || $post_data['section_name'] == 'user_description'){
					$user_profile_completion_data = array();
					
					if($post_data['section_name'] == 'headline'){ 
						$user_profile_completion_data['has_headline_indicated'] = 'N';
						$user_profile_completion_data['headline_strength_value'] = 0;
					}
					if($post_data['section_name'] == 'user_description'){ 
						$user_profile_completion_data['has_description_indicated'] = 'N';
						$user_profile_completion_data['description_strength_value'] = 0;
					}
					if($post_data['section_name'] == 'user_mother_tongue'){ 
						$user_profile_completion_data['has_mother_tongue_indicated'] = 'N';
						$user_profile_completion_data['mother_tongue_strength_value'] = 0;
					}
					if(!empty($user_profile_completion_data)){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
					}
					
				}
				// profile completion script end here //
				
				// Delete from find_professionals_user_information
				if($post_data['section_name'] == 'headline' || $post_data['section_name'] == 'user_description') {
					$this->db->delete('find_professionals_users_information', ['user_id' => $user_id]);
				}
				
				echo json_encode(['status' => 200,'message'=>'','location'=>'','character_length_count_message'=>$character_length_count_message]);
				die;
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH;
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	public function profile_management_page_company_base_information() {
			if(!$this->session->userdata('user')) {
					redirect(base_url());
			}
			if(check_session_validity()) {
					$user = $this->session->userdata('user');
					$user_id = $user[0]->user_id;
					if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
						show_custom_404_page();
						return;
					}
					$data['current_page'] = 'profile-management-company-base-information';
					$name = (($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_PERSONAL_ACCOUNT_TYPE) || ($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_COMPANY_ACCOUNT_TYPE && $this->auto_model->getFeild('is_authorized_physical_person', 'users', 'user_id', $user_id) == 'Y'))? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
					
					
					$user_detail = $this->db // get the user detail
					->select('u.user_id,u.is_authorized_physical_person')
					->from('users u')
					->where('u.user_id', $user_id)
					->get()->row_array(); 
					$data['user_detail']= $user_detail;
					
					
					if($user_detail['is_authorized_physical_person'] == 'Y'){
						$profile_management_title_meta_tag = $this->config->item('ca_app_profile_management_base_information_page_title_meta_tag');
					}else{
						$profile_management_title_meta_tag = $this->config->item('ca_profile_management_base_information_page_title_meta_tag');
					}
					$profile_management_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $profile_management_title_meta_tag);
					if($user_detail['is_authorized_physical_person'] == 'Y'){
						$profile_management_description_meta_tag = $this->config->item('ca_app_profile_management_base_information_page_description_meta_tag');
					}else{
						$profile_management_description_meta_tag = $this->config->item('ca_profile_management_base_information_page_description_meta_tag');
					}
					$profile_management_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $profile_management_description_meta_tag);
					$data['meta_tag'] = '<title>' . $profile_management_title_meta_tag . '</title><meta name="description" content="' . $profile_management_description_meta_tag . '"/>';

				########## set the profile title tag start end #########
				$this->layout->view ('ca_profile_management_company_base_information', '', $data, 'include');
			}
	}

	public function profile_management_page_competencies() {
		if(!$this->session->userdata('user')) {
			redirect(base_url());
		}
		if(check_session_validity()) {
			$user = $this->session->userdata('user');
			$user_id = $user[0]->user_id;
			$data['current_page'] = 'profile-management-competencies';
			$name = (($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_PERSONAL_ACCOUNT_TYPE) || (($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_COMPANY_ACCOUNT_TYPE && $this->auto_model->getFeild('is_authorized_physical_person', 'users', 'user_id', $user_id) == 'Y' ))) ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
			$user_detail = $this->db // get the user detail
			->select('u.profile_name,ud.current_membership_plan_id')
			->from('users u')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('u.user_id', $user_id)
			->get()->row_array(); 
			$data['user_detail'] = $user_detail;
			if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
			{
				$profile_management_title_meta_tag = $this->config->item('pa_user_profile_management_competencies_page_title_meta_tag');
				$profile_management_description_meta_tag = $this->config->item('pa_user_profile_management_competencies_page_description_meta_tag');
			}else if($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y'){
				$profile_management_title_meta_tag = $this->config->item('ca_app_user_profile_management_competencies_page_title_meta_tag');
				$profile_management_description_meta_tag = $this->config->item('ca_app_user_profile_management_competencies_page_description_meta_tag');
			}else{
				$profile_management_title_meta_tag = $this->config->item('ca_user_profile_management_competencies_page_title_meta_tag');
				$profile_management_description_meta_tag = $this->config->item('ca_user_profile_management_competencies_page_description_meta_tag');
			}

			$profile_management_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $profile_management_title_meta_tag);

			$profile_management_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $profile_management_description_meta_tag);
			$data['meta_tag'] = '<title>' . $profile_management_title_meta_tag . '</title><meta name="description" content="' . $profile_management_description_meta_tag . '"/>';


			########## set the profile title tag start end #########
			$this->layout->view ('user_profile_management_competencies', '', $data, 'include');
		}
	}
	
	
	// This function is using to update the tabs (area of expertise/skills/service provided tabs) of profile management
	public function update_profile_management_competencies_tabs(){
		if($this->input->is_ajax_request ()){
			if ($this->input->post () )
			{
				if(check_session_validity()){ 
					$post_data = $this->input->post ();
					$user = $this->session->userdata ('user');
					$tab_type = $post_data['tab_type'];
					
					$user_id = $user[0]->user_id;
					
					if($user_id != Cryptor::doDecrypt($post_data['uid'])){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					$data = array();
					 $user_detail = $this->db // get the user detail
					->select('u.profile_name,ud.current_membership_plan_id')
					->from('users u')
					->join('users_details ud', 'ud.user_id = u.user_id', 'left')
					->where('u.user_id', $user_id)
					->get()->row_array(); 
					$data['user_detail'] = $user_detail;
					
					if($tab_type == 'areas_of_expertise_tab'){
						
							//data fetch for area of expertise dropdown from categories_professionals table for category
							$count_user_expertise = $this->db->where(['user_id' => $user_id])->from('professionals_areas_of_expertise_listings_tracking')->count_all_results();
							
							$data['count_user_expertise'] = $count_user_expertise;
							
							$catArr['type'] = 1;
							$areas_of_expertise_category = $this->User_model->get_professional_categories($catArr);
							$data['areas_of_expertise_category'] = $areas_of_expertise_category;


							if ($user_detail['current_membership_plan_id'] == 1) {
								$areas_of_expertise_category_limit = $this->config->item('user_profile_management_competencies_page_free_membership_subscriber_number_category_slots_allowed');
								$areas_of_expertise_subcategory_limit = $this->config->item('user_profile_management_competencies_page_free_membership_subscriber_number_subcategory_slots_allowed');
							}if ($user_detail['current_membership_plan_id'] == 4) {
								$areas_of_expertise_category_limit = $this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_category_slots_allowed');
								$areas_of_expertise_subcategory_limit = $this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_subcategory_slots_allowed');
							}
							$data['areas_of_expertise_category_limit'] = $areas_of_expertise_category_limit;
							$data['areas_of_expertise_subcategory_limit'] = $areas_of_expertise_subcategory_limit;
							//fetch inserted areas of expertise
							$professional_categories = $this->db // get the user detail
							->select('elt.id, elt.professional_category_id, elt.professional_parent_category_id')
							->from('professionals_areas_of_expertise_listings_tracking elt')
							->where('user_id', $user_id)
							->get()->result_array();
							$fetchCatArr = array();
							$mainArr = array();
							$preselected_ids = array();
				
							
							foreach ($professional_categories as $cat) {
								$result = $this->db->get_where('categories_professionals', ['id' => $cat['professional_category_id'], 'parent_id' => $cat['professional_parent_category_id']])->row_array();
								if(empty($result)) {
									$this->db->delete('professionals_areas_of_expertise_listings_tracking', ['id' => $cat['id']]);
									continue;
								} else {
									if($cat['professional_parent_category_id'] != 0) {
										$result = $this->db->get_where('categories_professionals', ['id' => $cat['professional_parent_category_id']])->row_array();
										if(empty($result)) {
											$this->db->delete('professionals_areas_of_expertise_listings_tracking', ['id' => $cat['id']]);
											continue;
										}
									}
								}
								if ($cat['professional_parent_category_id'] == 0) {
									$fetchCatArr[] = $cat['professional_category_id'];
									$mainArr[$cat['professional_category_id']][] = $cat['professional_parent_category_id'];
								} else {
									$fetchCatArr[] = $cat['professional_parent_category_id'];
									$mainArr[$cat['professional_parent_category_id']][] = $cat['professional_category_id'];
								}
								$preselected_ids[] = $cat['id'];
							}
							
							$fetchUArr = array_unique($fetchCatArr);
							$data['used_category_arr'] = $fetchUArr;
							//echo '<pre>'; print_r(array_unique($fetchCatArr)); echo '</pre>';die();
							$data['areas_of_expertise_category_added'] = count($mainArr);
							$var = '';

							$recordArr = array();
							$tmparr = [];
							foreach ($mainArr as $key => $arr) {
								$category = $this->db
									->select('id, name')
									->from('categories_professionals')
									->where('id', $key)
									->get()->row_array();
								$recordArr[$category['name']] = $category['name'];
								$tmparr[$category['name'].'###########'.$category['id']] = $category['id'];
								if($arr[0]!=0) {
									$recordSArr = array();
									foreach ($arr as $scat) {
										$scategory = $this->db
										->select('id, name')
										->from('categories_professionals')
										->where('id', $scat)
										->get()->row_array();
										$recordSArr[$scat] = $scategory['name'];
									}
									asort($recordSArr);
									$recordArr[$category['name']] = $recordSArr;
									$tmparr[$category['name'].'###########'.$category['id']] = array_keys($recordSArr);
								} else {
									$tmparr[$category['name'].'###########'.$category['id']] = [0];
								}
							}
							ksort($tmparr);
							$mainArr = [];
							foreach($tmparr as $k => $val) {
								$arr = explode('###########', $k);
								$mainArr[end($arr)] = $val;
							} 
							
							foreach ($mainArr as $key => $arr) {
							
							if (($keyA = array_search($key, $fetchUArr)) !== false) {
								unset($fetchUArr[$keyA]);
							}

							//echo '<pre>'; print_r(array_unique($fetchUArr)); echo '</pre>';die();
							$usedCategory .= $key . ',';
							$professional_parent_category = $this->db // get the user detail
											->select('id, name')
											->from('categories_professionals')
											->where('id', $key)
											->get()->row_array();

							if($id=='') {
								
							$var .= '<div class="pmcsa" id="areasExpertise' . $key . '">';
							}
							$var .= '							
								<div class="row" id="editCategory' . $key . '" style="display:none;">
									<div class="col-md-11 areaExpert_section editAoE">
										<div class="categoryPart">
												<div class="form-group default_dropdown_select">
													<input type="hidden" id="inputCat' . $key . '" value="' . $key . '"><select name="category' . $key . '" id="category' . $key . '" onchange="chooseCategory(this.value, ' . $key . ', ' . $areas_of_expertise_subcategory_limit . ')">';
												foreach ($areas_of_expertise_category as $category) {
													$catselected = '';
													if ($key == $category['id']) {
														$catselected = 'selected="selected"';
													}
													if (!in_array($category['id'], $fetchUArr)) {
														$var .= '<option value="' . $category['id'] . '" ' . $catselected . '>' . $category['name'] . '</option>';
													}
												}
												$var .= '</select>
												</div>
										</div>';
										
										 $subcatArr['type'] = 2;
											$subcatArr['parent_id'] = $key;
										
											$areas_of_expertise_subcategory = $this->User_model->get_professional_categories($subcatArr);
											
											//$k=0;
											for ($s = 1; $s <= $areas_of_expertise_subcategory_limit; $s++) {
					
												$disabled = '';
												$show_subcatgory_drop_down_style = "display:inline-flex";
												if(count($areas_of_expertise_subcategory) ==0 || ($s > count($arr) &&  count($areas_of_expertise_subcategory) <= count($arr)) ||   empty($areas_of_expertise_subcategory[$s-1]) || $arr[0] ==0 && $s != 1){
												 $disabled = 'disabled="disabled"';
												 $show_subcatgory_drop_down_style = "display:none";
												}
												if(($s-$k)>1){
													$show_subcatgory_drop_down_style = "display:none";
												}
												
												$var .= '<div style="'.$show_subcatgory_drop_down_style.'" class="subCatPart subcategory' . $key . '_' . $s . '"><div class="form-group default_dropdown_select">   <input type="hidden" id="inputSubCat' . $key . '_' . $s . '" value="' . $arr[$s - 1] . '"><input  type="hidden" id="sectionid' . $key . '_' . $s . '" value="' . $preselected_ids[$s - 1] . '">';
												$var .= '<select name="subcategory' . $key . '_' . $s . '" id="subcategory' . $key . '_' . $s . '" onchange="chooseSubcategory(this.value, ' . $key . ', ' . $s . ', ' . $areas_of_expertise_subcategory_limit . ')" ' . $disabled . '>';

												$is_more_than_constant_value = true;
												if($s > count($areas_of_expertise_subcategory) ||  $s > count($areas_of_expertise_subcategory)){
													$is_more_than_constant_value = false;
												}
												//if($is_more_than_constant_value && $s > count($arr) || $arr[0] == 0 && count($areas_of_expertise_subcategory) >0 && $s<=count($areas_of_expertise_subcategory)){
													if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
														$initial_option = $this->config->item('pa_profile_management_areas_of_expertise_section_select_areas_of_expertise_subcategory_initial_selection');
													} else {
														$initial_option =  $this->config->item('ca_profile_management_areas_of_expertise_section_select_areas_of_expertise_subcategory_initial_selection');
													}
													$var .= '<option value="">'.$initial_option.'</option>';
												//}
												foreach ($areas_of_expertise_subcategory as  $scategory) {
												
													$scatselected = '';
													if ($arr[$s-1] == $scategory['id']) {
														$scatselected = 'selected="selected"';
														//$k = $k+2;
														$k=$s;
													}
													if($is_more_than_constant_value){
													
														if(in_array($scategory['id'] , $arr )){
															$var .= '<option value="' . $scategory['id'] . '" ' . $scatselected . ' style="display:none;" >' . $scategory['name'] . '</option>';
														} else{
															$var .= '<option value="' . $scategory['id'] . '" ' . $scatselected . '>' . $scategory['name'] . '</option>';
														}
													}                       
												}
												$var .= '</select><input type="hidden"  value="'.$arr[$s-1].'"class="subcategory'.$key.'_'.$s.'"/></div></div>';
											}
																
									$var .= '</div>
									<div class="col-md-1 pmAeMob areaExpert_Btnsection">
										<div class="pmAeSelect listSlAction listAction">
											<div class="pmAction saveMode deskTopView" id="saveMode' . $key . '" style="display:none">
												<button class="btn pmCheck default_icon_red_btn"  onclick="cancelAreaOfExpertise(' . $key . ', ' . $areas_of_expertise_subcategory_limit . ')"><i class="fas fa-times"></i></button><button class="btn pmSave default_icon_blue_btn" onclick="saveAreaOfExpertise(' . $key . ', ' . $key . ', ' . $areas_of_expertise_subcategory_limit . ',\'edit\')"><i class="fas fa-save"></i></button>
											</div>
											<div class="pmAction saveMode mobView" id="saveModeM' . $key . '" style="display:none">
												<button class="btn pmCheck default_btn red_btn"  onclick="cancelAreaOfExpertise(' . $key . ', ' . $areas_of_expertise_subcategory_limit . ')">'.$this->config->item('cancel_btn_txt').'</button><button class="btn pmSave default_btn blue_btn" onclick="saveAreaOfExpertise(' . $key . ', ' . $key . ', ' . $areas_of_expertise_subcategory_limit . ',\'edit\')">'.$this->config->item('save_btn_txt').'</button>
											</div>
										</div>
									</div>
								</div>';

							
							$var.= '<div class="row" id="saveCategory' . $key . '">
								<div class="col-md-11 areaExpert_section">
									<div class="categoryPart">
										<div class="pmAExpt default_black_bold_medium">'.$professional_parent_category['name'].'</div>
									</div>';
										$sub_category = '';
										if ($arr[0] != '0') {
											foreach ($arr as $scat) {
												$professional_sub_category = $this->db
																->select('id, name')
																->from('categories_professionals')
																->where('id', $scat)
																->get()->row_array();
												//$sub_category .= $professional_sub_category['name'] . ' / ';
												
											$var .= '<div class="subCatPart"><div class="pmAExpt default_black_regular_medium"><i class="fas fa-bars"></i>'.$professional_sub_category['name'].'</div></div>';
												
												
											}
											//$sub_category = trim($sub_category, ' / ');
											//$var .= '<div class="subCatPart"><div class="pmAExpt default_black_regular_medium">' . $sub_category . '</div></div>';
											
										}
										
										
										
										//$var .= '<div class="clearfix"></div>
									
								$var .= '</div>
								<div class="col-md-1 pmAeMob areaExpert_Btnsection">
									<div class="pmAeSelect listSlAction listAction">
										<div class="pmAction editMode deskTopView" id="editMode' . $key . '">
											<button class="btn pmTrash default_icon_red_btn" onclick="removeAreaOfExpertise(' . $key . ')"><i class="fas fa-trash-alt"></i></button><button class="btn pmEdit default_icon_green_btn" onclick="editAreaOfExpertise(' . $key . ','.$areas_of_expertise_subcategory_limit.')"><i class="fas fa-edit"></i></button>
										</div>
										<div class="pmAction editMode mobView" id="editModeM' . $key . '">
											<button class="btn pmTrash default_btn red_btn" onclick="removeAreaOfExpertise(' . $key . ')">'.$this->config->item('delete_btn_txt').'</button><button class="btn pmEdit default_btn green_btn" onclick="editAreaOfExpertise(' . $key . ','.$areas_of_expertise_subcategory_limit.')">'.$this->config->item('edit_btn_txt').'</button>
										</div>
									</div>
								</div>
							</div>';
								
								
							 if($id=='') {
									$var .= '</div>';
								}
							$a = array($key);
							$fetchUArr = array_merge($a, $fetchUArr);	
								
							
						}
						//echo '<pre>'; print_r($mainArr); echo '</pre>';die();
						$usedCategory = rtrim($usedCategory, ',');
						$data['used_category'] = $usedCategory;
						
						/* echo $var;
						die; */
						
						$data['record'] = $var;
						
						$cond = [
							'user_id' => $user[0]->user_id,
							'sent_notification_type' => 'newly_posted_projects_areas_of_expertise'
						];
						$newly_posted_notification = $this->db->get_where('users_consent_receive_notifications_tracking', $cond)->row_array();
						$data['newly_posted_notification'] = $newly_posted_notification;
						
						
						// profile completion script start ///
						
						if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
							$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
							
						}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
							$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
						}
						
						// profile completion script start ///
						################## count category start ###########
						
						$total_catgory_row = array();
						$professional_categories = $this->db // get the user detail
						->select('elt.id, elt.professional_category_id, elt.professional_parent_category_id')
						->from('professionals_areas_of_expertise_listings_tracking elt')
						->where('user_id', $user_id)
						->get()->result_array();
						foreach ($professional_categories as $category) {
							$result = $this->db->get_where('categories_professionals', ['id' => $category['professional_category_id'], 'parent_id' => $category['professional_parent_category_id']])->row_array();
							
							if ($category['professional_parent_category_id'] == 0) {
								
								$total_catgory_row[$category['professional_category_id']][] = $category['professional_parent_category_id'];
							} else {
								
								$total_catgory_row[$category['professional_parent_category_id']][] = $category['professional_category_id'];
							}
						}
						
						################## count category end ###########
						
						if(count($total_catgory_row) > 0){
						
							$user_profile_completion_data['has_areas_of_expertise_indicated'] = 'Y';
							$user_profile_completion_data['areas_of_expertise_strength_value'] = $profile_completion_parameters['areas_of_expertise_strength_value'];
							$user_profile_completion_data['number_of_areas_of_expertise_entries'] = count($total_catgory_row);
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
						}else{
							$user_profile_completion_data['has_areas_of_expertise_indicated'] = 'N';
							$user_profile_completion_data['areas_of_expertise_strength_value'] = 0;
							$user_profile_completion_data['number_of_areas_of_expertise_entries'] = 0;
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
						
						}
						
					
						// profile completion script end ///
						
						
						echo json_encode(['status' => 200,'data'=>$this->load->view('user/user_profile_management_competencies_user_areas_of_expertise',$data, true)]);die; 
					}
					if($tab_type == 'skills_tab'){
						################################# reset scrambles user skills ###
						$this->User_model->remove_scrambled_user_skills_entries($user_id);
						$users_skills = $this->db
						->select('*')
						->from('users_skills_tracking')
						->where("user_id", $user_id)
						->order_by('user_skill','ASC')
						->get()->result_array();
						$data['users_skills'] = $users_skills;
						
						echo json_encode(['status' => 200,'data'=>$this->load->view('user/user_profile_management_competencies_user_skills',$data, true)]);die;
						 
					}
					if($tab_type == 'services_provided_tab'){
						
						$this->User_model->remove_scrambled_user_services_provided_entries($user_id);	
							//fetch serv_users_services_provided
						$user_service_provided = $this->db
							->select('*')
							->from('users_services_provided_tracking')
							->where("user_id", $user_id)
							->order_by('service_provided asc')
							->get()->result_array();
						$data['user_service_provided'] = $user_service_provided;
						echo json_encode(['status' => 200,'data'=>$this->load->view('user/user_profile_management_competencies_user_services_provided',$data, true)]);die;
						 
					}	
				
				}else{
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
				}
			}else{
				show_custom_404_page(); //show custom 404 page
			}	
		}else{
			show_custom_404_page(); //show custom 404 page
		}	
	
	}	

	//data fetch for area of expertise dropdown from categories_professionals table for category
    public function profile_management_page_get_user_professional_categories() {
        if ($this->input->is_ajax_request()) {
            $msg['location'] = '';
            if (check_session_validity()) {
                $msg['status'] = 200;
                $catArr['type'] = 1;
                $areas_of_expertise_category = $this->User_model->get_professional_categories($catArr);
                $msg['category'] = $areas_of_expertise_category;
            } else {
                $msg['status'] = 400;
                $msg['location'] = VPATH;
            }
            echo json_encode($msg);
        } else {
            show_custom_404_page(); //show custom 404 page
        }
    }
	
	 //data fetch for area of expertise dropdown from categories_professionals table for subcategory
    public function profile_management_page_get_user_professional_subcategories() {
        if ($this->input->is_ajax_request()) {
            $msg['location'] = '';
            if (check_session_validity()) {
                $msg['status'] = 200;
                $subcatArr['type'] = 2;
                $subcatArr['parent_id'] = $this->input->post('parent_id');
                $areas_of_expertise_subcategory = $this->User_model->get_professional_categories($subcatArr);
                $msg['subcategory'] = $areas_of_expertise_subcategory;
            } else {
                $msg['status'] = 400;
                $msg['location'] = VPATH;
            }
            echo json_encode($msg);
        } else {
            show_custom_404_page(); //show custom 404 page
        }
    }
	
	
	// This function is used to check whether the selected category or sub categories is valid or not
    public function profile_management_page_check_users_selected_areas_of_expertise_validity() {
        if ($this->input->is_ajax_request()) {
            $msg['location'] = '';
            
            if (check_session_validity()) {
                $arrData = $this->input->post('data');
                $user = $this->session->userdata('user');
                $user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
                //check selected area of expertise valid or not
               foreach($arrData as $expertise) {
                    $row = $this->db->get_where('professionals_areas_of_expertise_listings_tracking', ['professional_category_id' => $expertise[1], 'professional_parent_category_id' => $expertise[0], 'user_id' => $user_id])->row_array();
                    if(empty($row)) {
						
                        $msg['popup_heading'] = $this->config->item('popup_alert_heading');
                        $msg['status'] = 301;
                        $msg['error'] = $this->config->item('profile_management_areas_of_expertise_valid_category_not_existent_popup_message');
                        echo json_encode($msg);
                        return;
                    }
                }
                $work_expertise_ids = [];
                if(count($arrData) > 1) {
                    $work_expertise_ids = array_column($arrData, 1);
                    array_push($work_expertise_ids, $arrData[0][0]);
                } else {
                    $work_expertise_ids = array_column($arrData, 1);
                }
				
                $checkArr = [];
                $cond = '';
                $this->db->select('*');
                $this->db->from('categories_professionals');
                foreach($work_expertise_ids as $key => $val) {
                    if($key == 0) {
                        $this->db->group_start();
                    } else {
                        $this->db->or_group_start();
                    }
                    $this->db->where('id', $val);
                    $this->db->where('status', 'Y');
                    $this->db->group_end();
                }
                $result = $this->db->get()->result_array();
               /* if(count($result) != count($work_expertise_ids)) {
                    $msg['status'] = 301;
					$msg['location'] = '';
					$msg['popup_heading'] = $this->config->item('popup_alert_heading');
                    $msg['error'] = $this->config->item('profile_management_areas_of_expertise_valid_category_not_existent_popup_message');
                    echo json_encode($msg);
                    return;
                } */
                $msg['status'] = 200;
                echo json_encode($msg);
                return;
            } else {
                $msg['status'] = 400;
                $msg['location'] = VPATH;
				 echo json_encode($msg);
                return;
            }
        } else {
            show_custom_404_page(); //show custom 404 page
        }
    }
	
	/**
     * This method is defined to get users areas of expertise detail based on parent category id to maintain proper user selection when he click on cancel button from user areas of expertise section 
    */
	
    public function profile_management_page_get_user_areas_of_expertise_based_on_parent_id() {
        if ($this->input->is_ajax_request()) {
            if (check_session_validity()) {
			
                $user = $this->session->userdata('user');
                $user_id = $user[0]->user_id;
                $parent_id = $this->input->post('id');
				$arrData = $this->input->post('data');
				
				foreach($arrData as $expertise) {
                    $row = $this->db->get_where('professionals_areas_of_expertise_listings_tracking', ['professional_category_id' => $expertise[1], 'professional_parent_category_id' => $expertise[0], 'user_id' => $user_id])->row_array();
                    if(empty($row)) {
                        $msg['popup_heading'] = $this->config->item('popup_alert_heading');
                        $msg['status'] = 400;
                        $msg['location'] = '';
                        $msg['error'] = $this->config->item('profile_management_areas_of_expertise_valid_category_not_existent_popup_message');
                        echo json_encode($msg);
                        return;
                    }
                }
                $result = $this->db->get_where('professionals_areas_of_expertise_listings_tracking', ['user_id' => $user_id, 'professional_parent_category_id' =>  $parent_id])->result_array();
				
				
				###################
				$professional_categories = $this->db // get the user detail
				->select('id, professional_category_id, professional_parent_category_id')
				->from('professionals_areas_of_expertise_listings_tracking')
				->where('user_id', $user_id)
				->get()->result_array();
							
					foreach ($professional_categories as $cat) {
					if ($cat['professional_parent_category_id'] == 0) {
						
						$mainArr[$cat['professional_category_id']][] = $cat['professional_parent_category_id'];
					} else {
						
						$mainArr[$cat['professional_parent_category_id']][] = $cat['professional_category_id'];
					}
				}			
				$areas_of_expertise_category_added  = count($mainArr);
				$user_detail = $this->db // get the user detail
				->select('u.profile_name,ud.current_membership_plan_id')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user_id)
				->get()->row_array();

				if($user_detail['current_membership_plan_id'] == '1'){ // for free
					
					if ($this->config->item('user_profile_management_competencies_page_free_membership_subscriber_number_category_slots_allowed') > $areas_of_expertise_category_added){
						$add_category_button_style = 1;
						$add_category_section_style = 1;
						$add_category_button_free_member_style = 0;
					
					}else{
						
						$add_category_button_style = 0;
						$add_category_section_style = 0;
						$add_category_button_free_member_style = 1;
					}
					
					if($areas_of_expertise_category_added >= $this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_category_slots_allowed')){
						$add_category_section_style = 0;
						$add_category_button_style = 0;
						$add_category_button_free_member_style = 0;
					}
					
				}else{	
					$add_category_section_style = 0;
					$add_category_button_style = 0;
					$add_category_button_free_member_style = 0;
					
					if ($this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_category_slots_allowed') > $areas_of_expertise_category_added){
						$add_category_section_style = 1;
						$add_category_button_style = 1;
					}
				}

				$msg['add_category_section_style'] = $add_category_section_style;
				$msg['add_category_button_style'] = $add_category_button_style;
				$msg['add_category_button_free_member_style'] = $add_category_button_free_member_style;
				#############
				
				
				
                $msg['data'] = $result;
                $msg['status'] = 200;
                echo json_encode($msg);
                return;
            } else {
                $msg['status'] = 400;
                $msg['location'] = VPATH;
            }
        } else {
            show_custom_404_page(); //show custom 404 page
        }
    }
	
	// save the area of expertise of user
    public function profile_management_page_save_user_areas_of_expertise() {
        if ($this->input->is_ajax_request()) {
            $msg['location'] = '';
            if (check_session_validity()) {
                $user = $this->session->userdata('user');
                $user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$id = $this->input->post('id');
                $arrData = $this->input->post('data');
                $action = $this->input->post('action');
				
				if($id ==0 && !empty($this->input->post('action')) && $this->input->post('action') =='add'){
					$user_detail = $this->db // get the user detail
					->select('u.user_id,ud.current_membership_plan_id')
					->from('users u')
					->join('users_details ud', 'ud.user_id = u.user_id', 'left')
					->where('u.user_id', $user_id)
					->get()->row_array(); 
					
					if($user_detail['current_membership_plan_id'] == 1){
						$areas_of_expertise_category_limit = $this->config->item('user_profile_management_competencies_page_free_membership_subscriber_number_category_slots_allowed');
						$error_msg = $this->config->item('user_profile_management_free_membership_subscriber_category_maximum_slots_reached_error_message');
					}else{
						 $areas_of_expertise_category_limit = $this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_category_slots_allowed');
						$error_msg = $this->config->item('user_profile_management_gold_membership_subscriber_category_maximum_slots_reached_error_message');
					}
					
					$users_area_expertise = $this->db
					->select('*')
					->from('professionals_areas_of_expertise_listings_tracking')
					->where("user_id", $user_id)
					->group_by("professional_parent_category_id")
					->get()->result_array();
					
					if (count($users_area_expertise) >= $areas_of_expertise_category_limit) {
						echo json_encode(['status' => 403,'location'=>'','error'=>$error_msg]);
						die;
					}
				
				}
				
				
				
				
                $msg['status'] = 200;

                $areas_of_expertise_subcategory_limit = $this->input->post('areas_of_expertise_subcategory_limit');
               
                
                $work_expertise_ids = [];
                if(count($arrData) > 1) {
					
                    $work_expertise_ids = array_column($arrData, 1);
                    array_push($work_expertise_ids, $arrData[0][0]);
                } else {
					
                    $work_expertise_ids = array_column($arrData, 1);
                }
                $checkArr = [];
                $cond = '';
                $this->db->select('*');
                $this->db->from('categories_professionals');
                foreach($work_expertise_ids as $key => $val) {
                    if($key == 0) {
                        $this->db->group_start();
                    } else {
                        $this->db->or_group_start();
                    }
                    $this->db->where('id', $val);
                    $this->db->where('status', 'Y');
                    $this->db->group_end();
                }
                $result = $this->db->get()->result_array();
				$work_expertise_ids = array_values(array_filter($work_expertise_ids));
				
                if(count($result) != count($work_expertise_ids)) {
                    $msg['status'] = 301;
					$msg['popup_heading'] = $this->config->item('popup_alert_heading');
					$msg['location'] = '';
                    $msg['error'] = $this->config->item('profile_management_areas_of_expertise_valid_category_not_existent_popup_message');
                    echo json_encode($msg);
                    return;
                }
                
                
				$area_of_expertise_insert_status = false;
                $fetchCatArr = array();
                $mainArr = array();
                $preselected_ids = array();
                if (count($arrData) > 0) {

                    if($id) {
                        $this->db->where("user_id=". $user_id." AND (professional_category_id = ".$id." OR professional_parent_category_id = ".$id.")");
                        $this->db->delete('professionals_areas_of_expertise_listings_tracking');
                    }
                    foreach ($arrData as $list) {
						if(!empty($list[1])){
							$professional_category_id = $list[1];
							$professional_parent_category_id = $list[0];
							
							$insertData = array(
								'user_id' => $user_id,
								'professional_category_id' => $professional_category_id,
								'professional_parent_category_id' => $professional_parent_category_id
							);
							
							if($action == 'add'){
							$this->db->insert('professionals_areas_of_expertise_listings_tracking', $insertData);
							$area_of_expertise_insert_status = true;
						   }else{
							$result = $this->db->get_where('professionals_areas_of_expertise_listings_tracking', ['id' => $list[2],'user_id'=>$user_id])->row_array();
							if(empty($result)){
								$this->db->insert('professionals_areas_of_expertise_listings_tracking', $insertData);
								$area_of_expertise_insert_status = true;
							}else{
								$this->db->update('professionals_areas_of_expertise_listings_tracking', $insertData, array("user_id" => $user_id,"id"=>$list[2]));
							}
						   
						   }
							
							
							$msg['mode'] = 2;
							if ($id) {
								$msg['mode'] = 1;
							}
							if ($professional_parent_category_id == 0) {
								$fetchCatArr[] = $professional_category_id;
								$mainArr[$professional_category_id][] = $professional_parent_category_id;
							} else {
								$fetchCatArr[] = $professional_parent_category_id;
								$mainArr[$professional_parent_category_id][] = $professional_category_id;
							}
							$preselected_ids[] = $cat['id'];
						}	
                    }
                }
				
				// profile completion script start ///
				if($area_of_expertise_insert_status){
					if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
						$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
						
					}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
						$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
					}
					
					################## count category start ###########
						
					$total_catgory_row = array();
					$professional_categories = $this->db // get the user detail
					->select('elt.id, elt.professional_category_id, elt.professional_parent_category_id')
					->from('professionals_areas_of_expertise_listings_tracking elt')
					->where('user_id', $user_id)
					->get()->result_array();
					foreach ($professional_categories as $category) {
						$result = $this->db->get_where('categories_professionals', ['id' => $category['professional_category_id'], 'parent_id' => $category['professional_parent_category_id']])->row_array();
						
						if ($category['professional_parent_category_id'] == 0) {
							
							$total_catgory_row[$category['professional_category_id']][] = $category['professional_parent_category_id'];
						} else {
							
							$total_catgory_row[$category['professional_parent_category_id']][] = $category['professional_category_id'];
						}
					}
					
					################## count category end ###########
					
					$user_profile_completion_data['has_areas_of_expertise_indicated'] = 'Y';
					$user_profile_completion_data['areas_of_expertise_strength_value'] = $profile_completion_parameters['areas_of_expertise_strength_value'];
					$user_profile_completion_data['number_of_areas_of_expertise_entries'] = count($total_catgory_row);
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
					
				}
				// profile completion script end ///
				
				
                $fetchUArr = array_unique($fetchCatArr);
                $catArr['type'] = 1;
                $areas_of_expertise_category = $this->User_model->get_professional_categories($catArr);
                $var ='';
                foreach ($mainArr as $key => $arr) {
                    if (($keyA = array_search($key, $fetchUArr)) !== false) {
                        unset($fetchUArr[$keyA]);
                    }
                    $msg['id'] = $key;
                    $professional_parent_category = $this->db
                        ->select('id, name')
                        ->from('categories_professionals')
                        ->where('id', $key)
                        ->get()->row_array();
                    if($msg['mode']==2) {
                        $var .= '<div class="pmcsa" id="areasExpertise' . $key . '">';
                    }
					
					$var .= '<div  id="editCategory' . $key . '" style="display:none">									
						<div class="row">
							<div class="col-md-11 areaExpert_section editAoE">
								<div class="categoryPart">
										<div class="form-group default_dropdown_select">
										<input type="hidden" id="inputCat' . $key . '" value="' . $key . '">
											<select name="category' . $key . '" id="category' . $key . '" onchange="chooseCategory(this.value, ' . $key . ', ' . $areas_of_expertise_subcategory_limit . ')">';
											foreach ($areas_of_expertise_category as $category) {
												$catselected = '';
												if ($key == $category['id']) {
													$catselected = 'selected="selected"';
												}
												if (!in_array($category['id'], $fetchUArr)) {
													$var .= '<option value="' . $category['id'] . '" ' . $catselected . '>' . $category['name'] . '</option>';
												}
											}
									$var .= '</select>
										</div>
								</div>';
								 $subcatArr['type'] = 2;
									$subcatArr['parent_id'] = $key;
									$areas_of_expertise_subcategory = $this->User_model->get_professional_categories($subcatArr);
								
								for ($s = 1; $s <= $areas_of_expertise_subcategory_limit; $s++) {
					
								$disabled = '';
								if(count($areas_of_expertise_subcategory) ==0 || ($s > count($arr) &&  count($areas_of_expertise_subcategory) <= count($arr)) ||   empty($areas_of_expertise_subcategory[$s-1])){
								 $disabled = 'disabled="disabled"';
								}
								$var .= '<div class="subCatPart subcategory' . $key . '_' . $s . '"><div class="form-group default_dropdown_select">   <input type="hidden" id="inputSubCat' . $key . '_' . $s . '" value="' . $arr[$s - 1] . '"><input  type="hidden" id="sectionid' . $key . '_' . $s . '" value="' . $preselected_ids[$s - 1] . '">';
								
								$var .= '<select name="subcategory' . $key . '_' . $s . '" id="subcategory' . $key . '_' . $s . '" onchange="chooseSubcategory(this.value, ' . $key . ', ' . $s . ', ' . $areas_of_expertise_subcategory_limit . ')" ' . $disabled . '>';
								$is_more_than_constant_value = true;
								if($s > count($areas_of_expertise_subcategory)){
									$is_more_than_constant_value = false;
								}
								/* if($is_more_than_constant_value && $s > count($arr) || $arr[0] == 0  && count($areas_of_expertise_subcategory) >0  && $s<=count($areas_of_expertise_subcategory)){ */
								
									if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
										$initial_option = $this->config->item('pa_profile_management_areas_of_expertise_section_select_areas_of_expertise_subcategory_initial_selection');
									} else {
										$initial_option =  $this->config->item('ca_profile_management_areas_of_expertise_section_select_areas_of_expertise_subcategory_initial_selection');
									}
								
									$var .= '<option value="">'.$initial_option.'</option>';
								/* } */
								foreach ($areas_of_expertise_subcategory as  $scategory) {
								
									$scatselected = '';
									if ($arr[$s-1] == $scategory['id']) {
										$scatselected = 'selected="selected"';							
									}
									if($is_more_than_constant_value){
									
										if(in_array($scategory['id'] , $arr )){
											$var .= '<option value="' . $scategory['id'] . '" ' . $scatselected . ' style="display:none;">' . $scategory['name'] . '</option>';
										} else{
											$var .= '<option value="' . $scategory['id'] . '" ' . $scatselected . '>' . $scategory['name'] . '</option>';
										}
									}                       
								}
								$var .= '</select><input type="hidden"  value="'.$arr[$s-1].'"class="subcategory'.$key.'_'.$s.'"/></div></div>';
								}
													
							$var.=	'</div>
							<div class="col-md-1 pmAeMob areaExpert_Btnsection">
								<div class="pmAeSelect listSlAction listAction">
									<div class="pmAction saveMode deskTopView" id="saveMode' . $key . '" style="display:none">
										<button class="btn pmCheck default_icon_red_btn" onclick="cancelAreaOfExpertise(' . $key . ', ' . $areas_of_expertise_subcategory_limit . ')"><i class="fas fa-times"></i></button><button class="btn pmSave default_icon_blue_btn" onclick="saveAreaOfExpertise(' . $key . ', ' . $key . ', ' . $areas_of_expertise_subcategory_limit . ',\'edit\')"><i class="fas fa-save"></i></button>
									</div>
									
									<div class="pmAction saveMode mobView" id="saveModeM' . $key . '" style="display:none">
										<button class="btn pmCheck default_btn red_btn" onclick="cancelAreaOfExpertise(' . $key . ', ' . $areas_of_expertise_subcategory_limit . ')">'.$this->config->item('cancel_btn_txt').'</button><button class="btn pmSave  default_btn blue_btn" onclick="saveAreaOfExpertise(' . $key . ', ' . $key . ', ' . $areas_of_expertise_subcategory_limit . ',\'edit\')">'.$this->config->item('save_btn_txt').'</button>
									</div>
									
								</div>
							</div>
						</div>
					</div>';
					
					$var .= '<div  id="saveCategory' . $key . '">
			<div class="row">
				<div class="col-md-11 areaExpert_section">
					<div class="categoryPart">
							<div class="pmAExpt default_black_bold_medium">'.$professional_parent_category['name'].'</div>
					</div>';
					
					$sub_category = '';
                    if ($arr[0] != '0') {
                        foreach ($arr as $scat) {
                            $professional_sub_category = $this->db
                                            ->select('id, name')
                                            ->from('categories_professionals')
                                            ->where('id', $scat)
                                            ->get()->row_array();
                            //$sub_category .= $professional_sub_category['name'] . ' / ';
							$var.= '<div class="subCatPart">
							<div class="pmAExpt default_black_regular_medium"><i class="fas fa-bars"></i>'.$professional_sub_category['name'].'</div>
							</div>';
                        }
						
                        //$sub_category = trim($sub_category, ' / ');
                        
													
                    }
					
					
						
						
				$var .=		'</div>
				<div class="col-md-1 pmAeMob areaExpert_Btnsection">
					<div class="pmAeSelect listSlAction listAction">
						<div class="pmAction editMode deskTopView" id="editMode' . $key . '">
							<button class="btn pmTrash default_icon_red_btn" onclick="removeAreaOfExpertise(' . $key . ')"><i class="fas fa-trash-alt"></i></button><button class="btn pmEdit default_icon_green_btn" onclick="editAreaOfExpertise(' . $key . ','.$areas_of_expertise_subcategory_limit.')"><i class="fas fa-edit"></i></button>
						</div>
						<div class="pmAction editMode mobView" id="editModeM' . $key . '">
							<button class="btn pmTrash  default_btn red_btn" onclick="removeAreaOfExpertise(' . $key . ')">'.$this->config->item('delete_btn_txt').'</button><button class="btn pmEdit  default_btn green_btn" onclick="editAreaOfExpertise(' . $key . ','.$areas_of_expertise_subcategory_limit.')">'.$this->config->item('edit_btn_txt').'</button>
						</div>
					</div>
				</div>
			</div>
		</div>';
					
					
					
					
					
                    if($msg['mode']==2) {
                        $var .= '</div>';
                    }
                    $a = array($key);
                        $fetchUArr = array_merge($a, $fetchUArr);
                 }
               #################################################
			   $professional_categories = $this->db // get the user detail
                            ->select('id, professional_category_id, professional_parent_category_id')
                            ->from('professionals_areas_of_expertise_listings_tracking')
                            ->where('user_id', $user_id)
                            ->get()->result_array();
							
					foreach ($professional_categories as $cat) {
					if ($cat['professional_parent_category_id'] == 0) {
						
						$mainArr[$cat['professional_category_id']][] = $cat['professional_parent_category_id'];
					} else {
						
						$mainArr[$cat['professional_parent_category_id']][] = $cat['professional_category_id'];
					}
				}			
				$areas_of_expertise_category_added  = count($mainArr);
				$user_detail = $this->db // get the user detail
				->select('u.profile_name,ud.current_membership_plan_id')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user_id)
				->get()->row_array();
				
				if($user_detail['current_membership_plan_id'] == '1'){ // for free
				
					if ($this->config->item('user_profile_management_competencies_page_free_membership_subscriber_number_category_slots_allowed') > $areas_of_expertise_category_added){
						$add_category_button_style = 1;
						$add_category_button_free_member_style = 0;
						$add_category_section_style = 1;
					
					}else{
						
						$add_category_button_style = 0;
						$add_category_section_style = 0;
						$add_category_button_free_member_style = 1;
					}
					if($areas_of_expertise_category_added >= $this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_category_slots_allowed')){
						$add_category_section_style = 0;
						$add_category_button_style = 0;
						$add_category_button_free_member_style = 0;
					}
					
				}else{	
					$add_category_section_style = 0;
					$add_category_button_style = 0;
					$add_category_button_free_member_style = 0;
					
					if ($this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_category_slots_allowed') > $areas_of_expertise_category_added){
						$add_category_section_style = 1;
						$add_category_button_style = 1;
					}
				}
				
				$msg['add_category_section_style'] = $add_category_section_style;
				$msg['add_category_button_style'] = $add_category_button_style;
				$msg['add_category_button_free_member_style'] = $add_category_button_free_member_style;
                $msg['record'] = $var;
            } else {
                $msg['status'] = 400;
                $msg['location'] = VPATH;
            }
            echo json_encode($msg);
        } else {
            show_custom_404_page(); //show custom 404 page
        }
    }
	
	 //delete user expertise
    public function profile_management_page_delete_user_areas_of_expertise() {
	
        if ($this->input->is_ajax_request()) {
            $msg['location'] = '';
            if (check_session_validity()) {
                $user = $this->session->userdata('user');
                $user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
                $msg['status'] = 200;

                $id = $this->input->post('id');
                //remove data
                $this->db->where('user_id', $user_id);
                $this->db->where('professional_category_id', $id);
                $this->db->or_where('professional_parent_category_id', $id);
                $this->db->delete('professionals_areas_of_expertise_listings_tracking');
				
				// profile completion script start //
				
				if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
					$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
				
				}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
					$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
				}
				
				
				################## count category start ###########
						
				$total_catgory_row = array();
				$professional_categories = $this->db // get the user detail
				->select('elt.id, elt.professional_category_id, elt.professional_parent_category_id')
				->from('professionals_areas_of_expertise_listings_tracking elt')
				->where('user_id', $user_id)
				->get()->result_array();
				foreach ($professional_categories as $category) {
					$result = $this->db->get_where('categories_professionals', ['id' => $category['professional_category_id'], 'parent_id' => $category['professional_parent_category_id']])->row_array();
					
					if ($category['professional_parent_category_id'] == 0) {
						
						$total_catgory_row[$category['professional_category_id']][] = $category['professional_parent_category_id'];
					} else {
						
						$total_catgory_row[$category['professional_parent_category_id']][] = $category['professional_category_id'];
					}
				}
				
				################## count category end ###########
				if(count($total_catgory_row) == 0){
					$user_profile_completion_data['has_areas_of_expertise_indicated'] = 'N';
					$user_profile_completion_data['areas_of_expertise_strength_value'] = 0;
					$user_profile_completion_data['number_of_areas_of_expertise_entries'] = 0;
					
				}else{
					$user_profile_completion_data['has_areas_of_expertise_indicated'] = 'Y';
					$user_profile_completion_data['areas_of_expertise_strength_value'] = $profile_completion_parameters['areas_of_expertise_strength_value'];
					$user_profile_completion_data['number_of_areas_of_expertise_entries'] = count($total_catgory_row);
				}
				if(!empty($user_profile_completion_data)){
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
				}
				// profile completion script end //
				
				
                
               //echo $this->db->last_query();die();
                $professional_categories = $this->db // get the user detail
                                ->select('id')
                                ->from('professionals_areas_of_expertise_listings_tracking')
                                ->where('user_id', $user_id)
                                ->get()->num_rows();
								
								
								
								
				$professional_categories = $this->db // get the user detail
				->select('id, professional_category_id, professional_parent_category_id')
				->from('professionals_areas_of_expertise_listings_tracking')
				->where('user_id', $user_id)
				->get()->result_array();
							
					foreach ($professional_categories as $cat) {
					if ($cat['professional_parent_category_id'] == 0) {
						
						$mainArr[$cat['professional_category_id']][] = $cat['professional_parent_category_id'];
					} else {
						
						$mainArr[$cat['professional_parent_category_id']][] = $cat['professional_category_id'];
					}
				}			
				$areas_of_expertise_category_added  = count($mainArr);
				$user_detail = $this->db // get the user detail
				->select('u.profile_name,ud.current_membership_plan_id')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user_id)
				->get()->row_array();

				if($user_detail['current_membership_plan_id'] == '1'){ // for free
					
					if ($this->config->item('user_profile_management_competencies_page_free_membership_subscriber_number_category_slots_allowed') > $areas_of_expertise_category_added){
						$add_category_button_style = 1;
						$add_category_section_style = 1;
						$add_category_button_free_member_style = 0;
					
					}else{
						
						$add_category_button_style = 0;
						$add_category_section_style = 0;
						$add_category_button_free_member_style = 1;
					}
					
					if($areas_of_expertise_category_added >= $this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_category_slots_allowed')){
						$add_category_section_style = 0;
						$add_category_button_style = 0;
						$add_category_button_free_member_style = 0;
					}
					
				}else{	
					$add_category_section_style = 0;
					$add_category_button_style = 0;
					$add_category_button_free_member_style = 0;
					
					if ($this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_category_slots_allowed') > $areas_of_expertise_category_added){
						$add_category_section_style = 1;
						$add_category_button_style = 1;
					}
				}

				$msg['add_category_section_style'] = $add_category_section_style;
				$msg['add_category_button_style'] = $add_category_button_style;
				$msg['add_category_button_free_member_style'] = $add_category_button_free_member_style;
                $msg['total'] = $professional_categories;
            } else {
                $msg['status'] = 400;
                $msg['location'] = VPATH;
            }
            echo json_encode($msg);
        } else {
            show_custom_404_page(); //show custom 404 page
        }
    }
	
	
	 // save user skill
	public function profile_management_page_save_user_skill() {
        if ($this->input->is_ajax_request()) {
            //$msg['location'] = '';
            if (check_session_validity()) {
				$post_data = $this->input->post ();
                $user = $this->session->userdata('user');
                $user_id = $user[0]->user_id;
				
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$validation_data_array = $this->User_model->user_skill_validation($post_data);
				if ($validation_data_array['status'] == 'SUCCESS')
				{
					 $this->db->insert('users_skills_tracking', array('user_skill' => trim($post_data['user_skill']), "user_id" => $user_id));
					 $insert_id = $this->db->insert_id();

					 $this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['user_skill']));
					// profile completion start 
					if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
					$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');

					}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
					$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
					}
					$count_user_skills = $this->db->where(['user_id'=>$user_id])->from('users_skills_tracking')->count_all_results();
					
					$user_profile_completion_data['has_skills_indicated'] = 'Y';
					$user_profile_completion_data['skills_strength_value'] = $profile_completion_parameters['skills_strength_value'];
					$user_profile_completion_data['number_of_skills_entries'] = $count_user_skills;
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
					 
					 // profile completion end 
					
					 $msg['location'] = '';
					 $msg['id'] = $insert_id;
					 $msg['data'] = htmlspecialchars(trim($post_data['user_skill']), ENT_QUOTES);
					 $msg['status'] = 200;
					 echo json_encode($msg);die;
				}else{
					echo json_encode ($validation_data_array);
					die;
				}
            } else {
                $msg['status'] = 400;
                $msg['location'] = VPATH;
				echo json_encode($msg);die;
            }
            
        } else {
            show_custom_404_page(); //show custom 404 page
        }
  }

	//delete user skills
	public function profile_management_page_delete_user_skill() {
			if ($this->input->is_ajax_request()) {
					$msg['location'] = '';
					if (check_session_validity()) {
							$user = $this->session->userdata('user');
							$user_id = $user[0]->user_id;
			if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				die;
			}
							//start check headline
							$msg['status'] = 200;
							
							$user_skill_id = $this->input->post ('user_skill_id');
							$user_skill_array = explode("_",$user_skill_id);
							
							$user_skill_data = $this->db->get_where('users_skills_tracking', array('id' => $user_skill_array[2]))->row_array();
							$this->db->delete('users_skills_tracking', array('id' => $user_skill_array[2]));
							$this->User_model->save_find_professionals_user_information($user_id, 'delete', '', $user_skill_data['user_skill']);
			
			
			// profile completion script start 
			if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
			$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');

			}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
			$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
			}
			$user_profile_completion_data = array();
			$count_user_skills = $this->db->where(['user_id'=>$user_id])->from('users_skills_tracking')->count_all_results();
			if($count_user_skills ==0){
				$user_profile_completion_data['has_skills_indicated'] = 'N';
				$user_profile_completion_data['skills_strength_value'] = 0;
				$user_profile_completion_data['number_of_skills_entries'] = 0;
			}else{
				$user_profile_completion_data['has_skills_indicated'] = 'Y';
				$user_profile_completion_data['skills_strength_value'] = $profile_completion_parameters['skills_strength_value'];
				$user_profile_completion_data['number_of_skills_entries'] = $count_user_skills;
			}
			if(!empty($user_profile_completion_data)){
				$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
			}
				// profile completion end 
			
			
					} else {
							$msg['status'] = 400;
							$msg['location'] = VPATH;
					}
					echo json_encode($msg);
			} else {
					show_custom_404_page(); //show custom 404 page
			}
	}
	
	// save user service provided
	public function profile_management_page_save_user_services_provided() {
        if ($this->input->is_ajax_request()) {
            //$msg['location'] = '';
            if (check_session_validity()) {
				$post_data = $this->input->post ();
                $user = $this->session->userdata('user');
                $user_id = $user[0]->user_id;
				
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$validation_data_array = $this->User_model->user_services_provided_validation($post_data);
				if ($validation_data_array['status'] == 'SUCCESS')
				{
					
					 $this->db->insert('users_services_provided_tracking', array('service_provided' => trim($post_data['user_service_provided']), "user_id" => $user_id));
					 $insert_id = $this->db->insert_id();

					 $this->User_model->save_find_professionals_user_information($user_id, 'insert', ' '.trim($post_data['user_service_provided']));
					 
					 // profile completion script start 
					 $count_user_services_provided = $this->db->where(['user_id'=>$user_id])->from('users_services_provided_tracking')->count_all_results();
					 if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
						$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
						
					}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
						$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
					}
					 $user_profile_completion_data['has_services_provided_indicated'] = 'Y';
					 $user_profile_completion_data['number_of_services_provided_entries'] = $count_user_services_provided;
					 $user_profile_completion_data['services_provided_strength_value'] = $profile_completion_parameters['services_provided_strength_value'];
					 $this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
					 // profile completion end
					 
					 
					 
					 
					 
					 $msg['location'] = '';
					 $msg['id'] = $insert_id;
					 $msg['data'] = htmlspecialchars(trim($post_data['user_service_provided']), ENT_QUOTES);
					 $msg['status'] = 200;
					 echo json_encode($msg);die;
				}else{
					echo json_encode ($validation_data_array);
					die;
				}
            } else {
                $msg['status'] = 400;
                $msg['location'] = VPATH;
				echo json_encode($msg);die;
            }
            
        } else {
            show_custom_404_page(); //show custom 404 page
        }
    }

	//start delete service_provided
	public function profile_management_page_delete_user_services_provided() {
        if ($this->input->is_ajax_request()) {
            $msg['location'] = '';
            if (check_session_validity()) {
                $user = $this->session->userdata('user');
                $user_id = $user[0]->user_id;
								if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
									echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
									die;
								}
                //start check headline
                $msg['status'] = 200;
                
                $service_provided_id = $this->input->post ('service_provided_id');
								$service_provided_array = explode("_",$service_provided_id);
								
								$service_provided = $this->db->get_where('users_services_provided_tracking', ['id' => $service_provided_array[2]])->row_array();
								$this->db->delete('users_services_provided_tracking', array('id' => $service_provided_array[2]));
								
								$this->User_model->save_find_professionals_user_information($user_id, 'delete', '', $service_provided['service_provided']);
				
				 // profile completion script start 
				 $user_profile_completion_data = array();
				 if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){
					$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
					}elseif($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
					$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
				}
				 $count_user_services_provided = $this->db->where(['user_id'=>$user_id])->from('users_services_provided_tracking')->count_all_results();
				 if($count_user_services_provided == 0){
					$user_profile_completion_data['has_services_provided_indicated'] = 'N';
					$user_profile_completion_data['number_of_services_provided_entries'] = 0;
					$user_profile_completion_data['services_provided_strength_value'] = 0;
				 }else{
					$user_profile_completion_data['has_services_provided_indicated'] = 'Y';
					$user_profile_completion_data['services_provided_strength_value'] = $profile_completion_parameters['services_provided_strength_value'];
					$user_profile_completion_data['number_of_services_provided_entries'] = $count_user_services_provided;
				 }
				 if(!empty($user_profile_completion_data)){
				  $this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
				 }
				 // profile completion end
					 
				
				
				
            } else {
                $msg['status'] = 400;
                $msg['location'] = VPATH;
            }
            echo json_encode($msg);
        } else {
            show_custom_404_page(); //show custom 404 page
        }
	}
		
	public function profile_management_page_mother_tongue() {
		if(!$this->session->userdata('user')) {
			redirect(base_url());
		}
		if(check_session_validity()) {
			$user = $this->session->userdata('user');
			if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || (USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')){
				$user_id = $user[0]->user_id;
				$data['current_page'] = 'profile-management-mother-tongue';
				$name = (($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_PERSONAL_ACCOUNT_TYPE) || ($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_COMPANY_ACCOUNT_TYPE && $this->auto_model->getFeild('is_authorized_physical_person', 'users', 'user_id', $user_id) == 'Y')) ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
				if($user[0]->is_authorized_physical_person == 'Y'){
					$mother_tongue_title_meta_tag = $this->config->item('ca_app_user_profile_management_mother_tongue_language_page_title_meta_tag');
					$mother_tongue_description_meta_tag = $this->config->item('ca_app_user_profile_management_mother_tongue_language_page_description_meta_tag');
				}else{
					$mother_tongue_title_meta_tag = $this->config->item('pa_user_profile_management_mother_tongue_language_page_title_meta_tag');
					$mother_tongue_description_meta_tag = $this->config->item('pa_user_profile_management_mother_tongue_language_page_description_meta_tag');
				}
				
				$mother_tongue_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $mother_tongue_title_meta_tag);
				
				$mother_tongue_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $mother_tongue_description_meta_tag);
				$data['meta_tag'] = '<title>' . $mother_tongue_title_meta_tag . '</title><meta name="description" content="' . $mother_tongue_description_meta_tag . '"/>';
				
				
				
				$profile_base_information = $this->db // get the user detail
				->select('*')
				->from('users_profile_base_information')
				->where('user_id', $user_id)
				->get()->row_array();
				if(!empty($profile_base_information['mother_tongue_language_id']) && $profile_base_information['mother_tongue_language_id'] !=0){
					$check_language_id = $this->User_model->get_language_name_from_id($profile_base_information['mother_tongue_language_id']); // language name fetch by id
					
					if(empty($check_language_id)){
						$this->db->update('users_profile_base_information', array('mother_tongue_language_id' => 0), array("user_id" => $user_id));
					}
				}
				
				$profile_base_information = $this->db // get the user detail
				->select('*')
				->from('users_profile_base_information')
				->where('user_id', $user_id)
				->get()->row_array();
				
				if(!empty($profile_base_information) && empty($profile_base_information['headline']) && empty($profile_base_information['description']) && $profile_base_information['hourly_rate'] == 0 && $profile_base_information['mother_tongue_language_id'] == 0){
					$this->db->delete('users_profile_base_information', array("user_id" => $user_id));
				} 
				
				$profile_base_information = $this->db // get the user detail
				->select('mother_tongue_language_id')
				->from('users_profile_base_information')
				->where('user_id', $user_id)
				->get()->row_array();
				
				$mother_tongue_language_id = '';
				$mother_tongue_language_view = '';
				if(!empty($profile_base_information) && $profile_base_information['mother_tongue_language_id'] != 0){
					
					$mother_tongue_language_id = $profile_base_information['mother_tongue_language_id'];
					
					$mother_tongue_language_fetch = $this->db // get the user detail
					->select('language')
					->from('languages')
					->where('id', $mother_tongue_language_id)
					->get()->row_array();
					
					$mother_tongue_language_view = $mother_tongue_language_fetch['language'];
				}
				
				
				
				
				$spoken_languages_id_array = array();
				$user_spoken_languages = $this->db // get the user detail
				->select('spl.language_id')
				->from('users_personal_accounts_spoken_languages_tracking spl')
				->where('spl.user_id', $user_id)
				->get()->result_array();
				if(!empty($user_spoken_languages)){
					foreach($user_spoken_languages  as $key=>$value){
						$spoken_languages_id_array[] = $value['language_id'];
					}
				}
				
				
				########### for dropdowns start ########
				if($this->config->item('mother_tongue_language_drop_down_top_displayed_option_language_db_id') != 0){
					$this->db->select ('id,language');
					$this->db->order_by('language', 'ASC');
					if(!empty($spoken_languages_id_array)){
						$this->db->where_not_in('id', $spoken_languages_id_array);
					}
					
					$this->db->where('id !=',$this->config->item('mother_tongue_language_drop_down_top_displayed_option_language_db_id'));
					$result = $this->db->get('languages');
					$mother_tongue_languages =  $result->result_array();
					
					$this->db->select ('id,language');
					$this->db->order_by('language', 'ASC');
					if(!empty($spoken_languages_id_array)){
						$this->db->where_not_in('id', $spoken_languages_id_array);
					}
					$this->db->where('id',$this->config->item('mother_tongue_language_drop_down_top_displayed_option_language_db_id'));
					$top_language_option = $this->db->get('languages');
					$top_mother_tongue_lang =  $top_language_option->row_array();
					
					$languages_result_array =array_combine(range(1, count($mother_tongue_languages)), $mother_tongue_languages);
					array_unshift($languages_result_array,$top_mother_tongue_lang);
				}else{
				
					$this->db->select ('id,language');
					$this->db->order_by('language', 'ASC');
					if(!empty($spoken_languages_id_array)){
						$this->db->where_not_in('id', $spoken_languages_id_array);
					}
					$result = $this->db->get('languages');
					$languages_result_array =  $result->result_array();
				
				}
				
				########### for dropdowns start ########
				$data['mother_tongue'] = $languages_result_array;
				$data['mother_tongue_language_id'] = $mother_tongue_language_id;
				$data['mother_tongue_language_view'] = $mother_tongue_language_view;
				########## set the profile title tag start end #########
				$this->layout->view ('user_profile_management_mother_tongue', '', $data, 'include');
			}else{
				show_custom_404_page(); //show custom 404 page	
			}		
		}
	}
	
	public function profile_management_page_spoken_foreign_languages() {
	
		if(!$this->session->userdata('user')) {
				redirect(base_url());
		}
		if(check_session_validity()) {
			$user = $this->session->userdata('user');
			
			if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')){
				$user_id = $user[0]->user_id;
				$data['current_page'] = 'profile-management-spoken-foreign-languages';
				
				$user_detail = $this->db // get the user detail
				->select('u.user_id,u.is_authorized_physical_person,ud.current_membership_plan_id,u.profile_name')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user_id)
				->get()->row_array();           
				$data['user_detail'] = $user_detail;
				
				$name = (($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_PERSONAL_ACCOUNT_TYPE) || ($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_COMPANY_ACCOUNT_TYPE && $this->auto_model->getFeild('is_authorized_physical_person', 'users', 'user_id', $user_id) == 'Y')) ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
			
				// remove srambles entry of user spoken language
				$this->User_model->remove_scrambled_user_spoken_languages_entries($user_id);
				$data['user_detail'] = $user_detail;
				$spoken_languages_listing_data = 
					$this->User_model->get_user_spoken_languages_listing($user_id);
				$data["spoken_languages_data"] = $spoken_languages_listing_data['data'];
				$data['spoken_languages_count'] = $spoken_languages_listing_data['total'];
				
				$languages = $this->db // get the user detail
				->select('id,language')
				->from('languages')
				->get()->result_array();
				$data['languages'] = $languages;
				
				if($user[0]->is_authorized_physical_person == 'Y'){
					$spoken_languages_page_title_meta_tag = $this->config->item('ca_app_user_profile_management_spoken_languages_page_title_meta_tag');
					$spoken_languages_page_description_meta_tag = $this->config->item('ca_app_user_profile_management_spoken_languages_page_description_meta_tag');
				}else{
					$spoken_languages_page_title_meta_tag = $this->config->item('pa_user_profile_management_spoken_languages_page_title_meta_tag');
					$spoken_languages_page_description_meta_tag = $this->config->item('pa_user_profile_management_spoken_languages_page_description_meta_tag');
				}
				

				
				$spoken_languages_page_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $spoken_languages_page_title_meta_tag);
				
				$spoken_languages_page_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $spoken_languages_page_description_meta_tag);
				$data['meta_tag'] = '<title>' . $spoken_languages_page_title_meta_tag . '</title><meta name="description" content="' . $spoken_languages_page_description_meta_tag . '"/>';

				########## set the profile title tag start end #########
				$this->layout->view ('user_profile_management_spoken_foreign_languages', '', $data, 'include');
			}else{
				show_custom_404_page(); //show custom 404 page		
			}		
		}
	}
	
	
	
	/**
	This function is used to fetch the other languages for profile management and send response in json.
	*/
    public function profile_management_page_get_user_spoken_language()
    {
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){
				$user = $this->session->userdata('user');
				if(Cryptor::doDecrypt($this->input->post ('uid')) != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				die;
				}

				$languages_count = $this->db
				->select ('id')
				->from ('languages')
				->get ()->num_rows ();
				if($user[0]->is_authorized_physical_person == 'Y'){
					$options = "<option value=''>".$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_select_language_initial_selection')."</option>";
				}else{
					$options = "<option value=''>".$this->config->item('pa_profile_management_spoken_foreign_languages_section_select_language_initial_selection')."</option>";
				}

				if($languages_count > 0 )
				{

					#### get the mother tounge language#####
					$user_base_information = array();
					if($this->session->userdata('user')){
					$user = $this->session->userdata('user');
					$user_base_information = $this->db // get the user detail
					->select('users_profile_base_information.mother_tongue_language_id')
					->from('users_profile_base_information')
					->where('user_id',$user[0]->user_id)
					->get()->row_array();
					}

					$this->db->select ('id,language');
					if(!empty($user_base_information) && !empty($user_base_information['mother_tongue_language_id']) &&  !empty($user_base_information['mother_tongue_language_id'])){
					$this->db->where('id !=', $user_base_information['mother_tongue_language_id']);
					}
					$this->db->order_by('language', 'ASC');
					$res = $this->db->get ('languages'); 
					foreach ($res->result () as $row)
					{
					$options .= "<option value='".$row->id ."'>".$row->language."</option>";
					}
				}
				$msg['location'] = '';
				$msg['status'] = 200;
				$msg['languages'] = $options;
				echo json_encode ($msg);die;

			}else{
				echo json_encode(['status' => 400,'location'=>VPATH]);
			die;
			}


		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	/**
	This function is used to save spoken languages.
	*/
	
    public function profile_management_page_save_user_spoken_foreign_language()
    {
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$post_data = $this->input->post ();
				$section_id = $post_data['section_id'];
				$action = $post_data['action'];
				$check_foreign_spoken_language_already_selected_as_mother_tongue_language = $this->db->where(['user_id' => $user_id,'mother_tongue_language_id'=>$post_data['sl_name']])->from('users_profile_base_information')->count_all_results();
				
				if($check_foreign_spoken_language_already_selected_as_mother_tongue_language > 0){
					if($user[0]->is_authorized_physical_person == 'Y'){
						echo json_encode(['status' => 403,'location'=>'','error'=>$this->config->item('ca_app_profile_management_user_foreign_spoken_language_already_selected_as_mother_tongue_language_field_error_message')]);
					}else{
						echo json_encode(['status' => 403,'location'=>'','error'=>$this->config->item('pa_profile_management_user_foreign_spoken_language_already_selected_as_mother_tongue_language_field_error_message')]);
					}
					die;
				}
				
				
				if($action == 'add'){
					$users_spoken_languages = $this->db
					->select('*')
					->from('users_personal_accounts_spoken_languages_tracking')
					->where("user_id", $user_id)
					->get()->result_array();
					
					
					$user_detail = $this->db // get the user detail
					->select('u.user_id,ud.current_membership_plan_id')
					->from('users u')
					->join('users_details ud', 'ud.user_id = u.user_id', 'left')
					->where('u.user_id', $user_id)
					->get()->row_array(); 
					if($user_detail['current_membership_plan_id'] == 1){
						if($user[0]->is_authorized_physical_person == 'Y'){
							$user_spoken_language_allowed = $this->config->item('ca_app_user_profile_management_spoken_languages_page_free_membership_subscriber_number_spoken_languages_slots_allowed');
							$error_msg = $this->config->item('ca_app_profile_management_free_membership_subscriber_user_foreign_spoken_languages_maximum_slots_reached_error_message');
						}else{	
							$user_spoken_language_allowed = $this->config->item('pa_user_profile_management_spoken_languages_page_free_membership_subscriber_number_spoken_languages_slots_allowed');
							$error_msg = $this->config->item('pa_profile_management_free_membership_subscriber_user_foreign_spoken_languages_maximum_slots_reached_error_message');
						}
					}else{
						if($user[0]->is_authorized_physical_person == 'Y'){
							$user_spoken_language_allowed = $this->config->item('ca_app_user_profile_management_spoken_languages_page_gold_membership_subscriber_number_spoken_languages_slots_allowed');
							$error_msg = $this->config->item('ca_app_profile_management_gold_membership_subscriber_user_foreign_spoken_languages_maximum_slots_reached_error_message');
						}else{	
							$user_spoken_language_allowed = $this->config->item('pa_user_profile_management_spoken_languages_page_gold_membership_subscriber_number_spoken_languages_slots_allowed');
							$error_msg = $this->config->item('pa_profile_management_gold_membership_subscriber_user_foreign_spoken_languages_maximum_slots_reached_error_message');
						}
					}
					if (count($users_spoken_languages) >= $user_spoken_language_allowed) {
						echo json_encode(['status' => 403,'location'=>'','error'=>$error_msg]);
						die;
					}
				
				}
				
				$user_spoken_language = array(
					'user_id'=>$user_id,
					'language_id'=>$post_data['sl_name'],
					'understanding'=>$post_data['sl_understanding'],
					'speaking'=>$post_data['sl_speaking'],
					'writing'=>$post_data['sl_writing']
				);
				$language_data_insert_status = false;
				if($action == 'add'){
					$this->db->insert('users_personal_accounts_spoken_languages_tracking', $user_spoken_language);
					$last_insert_id = $this->db->insert_id();
					$language_data_insert_status = true;
				
				}
				if($action == 'edit'){
				
					$check_user_spoken_language_data_exists = $this->db->where(['id' =>$section_id,'user_id'=>$user_id])->from('users_personal_accounts_spoken_languages_tracking')->count_all_results();
					if($check_user_spoken_language_data_exists == 0){
						$this->db->insert('users_personal_accounts_spoken_languages_tracking', $user_spoken_language);
						$last_insert_id = $this->db->insert_id();
						$language_data_insert_status = true;
					}else{
						$this->db->update('users_personal_accounts_spoken_languages_tracking', $user_spoken_language, ['id' => $section_id,'user_id'=> $user_id]);
						$last_insert_id = $section_id;
					}
				
				}
				// profile completion script start 
				if($language_data_insert_status){
					$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
					$count_user_spoken_languages = $this->db->where(['user_id'=>$user_id])->from('users_personal_accounts_spoken_languages_tracking')->count_all_results();
					$user_profile_completion_data['has_spoken_foreign_languages_indicated'] = 'Y';
					$user_profile_completion_data['number_of_spoken_foreign_languages_entries'] = $count_user_spoken_languages;
					$user_profile_completion_data['spoken_foreign_languages_strength_value'] = $profile_completion_parameters['spoken_foreign_languages_strength_value'];
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
				}
				// profile completion script end 
				
				$spoken_language_value = $this->db // get the user detail
				->select('sp.*,languages.language')
				->from('users_personal_accounts_spoken_languages_tracking sp')
				->join('languages', 'languages.id = sp.language_id', 'left')
				->where('sp.user_id',$user_id)
				->where('sp.id',$last_insert_id)
				->get()->row_array();
				$data['spoken_language_value'] = $spoken_language_value;
				
				$user_base_information = $this->db // get the user detail
				->select('users_profile_base_information.mother_tongue_language_id')
				->from('users_profile_base_information')
				->where('user_id',$user[0]->user_id)
				->get()->row_array();
				
				
				$this->db->select('id,language');
				$this->db->from('languages');
				
				if(!empty($user_base_information) && !empty($user_base_information['mother_tongue_language_id']) &&  !empty($user_base_information['mother_tongue_language_id'])){
					$this->db->where('id !=', $user_base_information['mother_tongue_language_id']);
				};
				$res = $this->db->get();
				$languages = $res->result_array();
				$data['languages'] = $languages;
				
				#### for membership functionality
				
				$user_detail = $this->db // get the user detail
				->select('u.user_id,ud.current_membership_plan_id,u.profile_name')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user_id)
				->get()->row_array(); 
				
				$spoken_languages_count = $this->db->where(['user_id'=>$user_id])->from('users_personal_accounts_spoken_languages_tracking')->count_all_results();
				
				
				if($user_detail['current_membership_plan_id'] == '1'){ // for free
		
					$spoken_language_slots_allowed = $this->config->item('pa_user_profile_management_spoken_languages_page_free_membership_subscriber_number_spoken_languages_slots_allowed');	
					
					if ($spoken_language_slots_allowed > $spoken_languages_count){
						$add_spoken_language_button_style = "1";
						$add_spoken_language_button_free_member_style = "0";
					
					}else{
						
						$add_spoken_language_button_style = "0";
						$add_spoken_language_button_free_member_style = "1";
					}
					if($spoken_languages_count >= $this->config->item('pa_user_profile_management_spoken_languages_page_gold_membership_subscriber_number_spoken_languages_slots_allowed')){
						
						$add_spoken_language_button_style = "0";
						$add_spoken_language_button_free_member_style = "0";
					}
					
				}else{	
					$spoken_language_slots_allowed = $this->config->item('pa_user_profile_management_spoken_languages_page_gold_membership_subscriber_number_spoken_languages_slots_allowed');	
					$add_spoken_language_button_style = "0";
					$add_spoken_language_button_free_member_style = "0";
					
					if ($spoken_language_slots_allowed > $spoken_languages_count){
						
						$add_spoken_language_button_style = "1";
					}
				}
				
				echo json_encode(['status' => 200,'action'=>$action,'last_row_id'=>$last_insert_id,'add_spoken_language_button_style'=>$add_spoken_language_button_style,'add_spoken_language_button_free_member_style'=>$add_spoken_language_button_free_member_style,'message'=>'','location'=>'','data'=>$this->load->view('personal_account_user_profile_management_spoken_foreign_languages_listing_entry_detail',$data, true)]);
				die;
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH;
				echo json_encode($res);
				die;
			}	
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	// delete user spoken language
	public function profile_management_page_delete_user_spoken_foreign_language()
    {
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				if($user_id != Cryptor::doDecrypt($this->input->post ('uid'))){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$user_detail = $this->db // get the user detail
				->select('u.user_id,ud.current_membership_plan_id,u.profile_name')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user_id)
				->get()->row_array(); 
				
				$section_id = $this->input->post ('section_id');
				$check_user_spoken_language_data_exists = $this->db->where(['id' =>$section_id,'user_id'=>$user_id])->from('users_personal_accounts_spoken_languages_tracking')->count_all_results();
				if($check_user_spoken_language_data_exists == 0){
					if($user[0]->is_authorized_physical_person == 'Y'){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('ca_app_user_spoken_language_entry_already_deleted')]);
					}else{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('pa_user_spoken_language_entry_already_deleted')]);
					}
					die;
				}
				$this->db->delete('users_personal_accounts_spoken_languages_tracking', ['id' => $section_id]);
				$check_spoken_languages_data_exists = $this->db->where(['user_id' =>$user_id])->from('users_personal_accounts_spoken_languages_tracking')->count_all_results();
				
				// profile completion script start//
				$user_profile_completion_data = array();
				if($check_spoken_languages_data_exists == 0){
				
					$user_profile_completion_data['has_spoken_foreign_languages_indicated'] = 'N';
					$user_profile_completion_data['number_of_spoken_foreign_languages_entries'] = 0;
					$user_profile_completion_data['spoken_foreign_languages_strength_value'] = 0;
					
				}else{
					$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
					
					$user_profile_completion_data['spoken_foreign_languages_strength_value'] = $profile_completion_parameters['spoken_foreign_languages_strength_value'];
					$user_profile_completion_data['has_spoken_foreign_languages_indicated'] = 'Y';
					$user_profile_completion_data['number_of_spoken_foreign_languages_entries'] = $check_spoken_languages_data_exists;
				}
				if(!empty($user_profile_completion_data)){
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
				}
				// profile completion script end//
				
				
				
				$initial_view_status = '0';
				if($check_spoken_languages_data_exists == 0){
					$initial_view_status = '1';
				}
				
				#### for membership functionality
				if($user_detail['current_membership_plan_id'] == '1'){ // for free
		
					$spoken_language_slots_allowed = $this->config->item('pa_user_profile_management_spoken_languages_page_free_membership_subscriber_number_spoken_languages_slots_allowed');	
					
					if ($spoken_language_slots_allowed > $check_user_spoken_language_data_exists){
						$add_spoken_language_button_style = "1";
						$add_spoken_language_button_free_member_style = "0";
					
					}else{
						
						$add_spoken_language_button_style = "0";
						$add_spoken_language_button_free_member_style = "1";
					}
					if($check_user_spoken_language_data_exists >= $this->config->item('pa_user_profile_management_spoken_languages_page_gold_membership_subscriber_number_spoken_languages_slots_allowed')){
						
						$add_spoken_language_button_style = "0";
						$add_spoken_language_button_free_member_style = "0";
					}
					
				}else{	
					$spoken_language_slots_allowed = $this->config->item('pa_user_profile_management_spoken_languages_page_gold_membership_subscriber_number_spoken_languages_slots_allowed');	
					$add_spoken_language_button_style = "0";
					$add_spoken_language_button_free_member_style = "0";
					
					if ($spoken_language_slots_allowed > $check_user_spoken_language_data_exists){
						
						$add_spoken_language_button_style = "1";
					}
				}
				
				echo json_encode(['location'=>'','initial_view_status'=>$initial_view_status,'add_spoken_language_button_style'=>$add_spoken_language_button_style,'add_spoken_language_button_free_member_style'=>$add_spoken_language_button_free_member_style,'status' => 200]);
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH;
				echo json_encode($res);
				die;
			}	
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
    public function profile_management_page_company_values_and_principles() {
        if(!$this->session->userdata('user')) {
            redirect(base_url());
        }
        if(check_session_validity()) {
            $user = $this->session->userdata('user');
			$user_id = $user[0]->user_id;
			if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
				show_custom_404_page();
				return;
			}
            $data['current_page'] = 'profile-management-company-values-and-principles';
            $name = ($this->auto_model->getFeild('is_authorized_physical_person', 'users', 'user_id', $user_id) == 'Y') ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
			if($user[0]->is_authorized_physical_person == 'Y')	{
				$profile_management_title_meta_tag = $this->config->item('ca_app_profile_management_company_values_and_principles_page_title_meta_tag');
				$profile_management_description_meta_tag = $this->config->item('ca_app_profile_management_company_values_and_principles_page_description_meta_tag');	
			}else{
				$profile_management_title_meta_tag = $this->config->item('ca_profile_management_company_values_and_principles_page_title_meta_tag');
				$profile_management_description_meta_tag = $this->config->item('ca_profile_management_company_values_and_principles_page_description_meta_tag');	
			}
            $profile_management_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $profile_management_title_meta_tag);
            
            $profile_management_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $profile_management_description_meta_tag);
            $data['meta_tag'] = '<title>' . $profile_management_title_meta_tag . '</title><meta name="description" content="' . $profile_management_description_meta_tag . '"/>';

          ########## set the profile title tag start end #########
          $this->layout->view ('ca_profile_management_company_values_and_principles', '', $data, 'include');
        }
    }
    
	// This function is using the session check if session are different then will show the session conflict popup/ if session is not exist then redirect to index page (only for ajax request)
	public function check_user_session(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){
				
				$user = $this->session->userdata('user');
				
				if(Cryptor::doDecrypt($this->input->post ('session_uid')) != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				echo json_encode(['status' => 200,'location'=>'']);
				die;	
				
			}else{
				echo json_encode(['status' => 400,'location'=>VPATH]);
				die;
			}
			show_custom_404_page(); //show custom 404 page			
		}	
	}

	// this is the default action which call the referral_program page	
    public function referral_program() {
        $data['current_page'] = 'referral_program';
		$referral_program_title_meta_tag = $this->config->item('referral_program_page_title_meta_tag');
		$referral_program_description_meta_tag = $this->config->item('referral_program_page_description_meta_tag');
		$data['meta_tag'] = '<title>' . $referral_program_title_meta_tag . '</title><meta name="description" content="' . $referral_program_description_meta_tag . '"/>';
		$this->layout->view('referral-program', '', $data, 'normal');
    }
	// this is the default action which call the secure_payments_process page	
    public function secure_payments_process() {
        $data['current_page'] = 'secure_payments_process';
		$secure_payments_process_title_meta_tag = $this->config->item('secure_payments_process_page_title_meta_tag');
		$secure_payments_process_description_meta_tag = $this->config->item('secure_payments_process_page_description_meta_tag');
		$data['meta_tag'] = '<title>' . $secure_payments_process_title_meta_tag . '</title><meta name="description" content="' . $secure_payments_process_description_meta_tag . '"/>';
		$this->layout->view('secure-payments-process', '', $data, 'normal');
    }
	// this is the default action which call the contact page	
    public function contact_us() {
		
        $data['current_page'] = 'contact_us';
		$contact_us_title_meta_tag = $this->config->item('contact_us_page_title_meta_tag');
		$contact_us_description_meta_tag = $this->config->item('contact_us_page_description_meta_tag');
		$data['meta_tag'] = '<title>' . $contact_us_title_meta_tag . '</title><meta name="description" content="' . $contact_us_description_meta_tag . '"/>';
		if($this->session->userdata('contact_us_confirmation')){
			$this->layout->view('contact-us-page-user-sent-message-confirmation', '', $data, 'normal');
			$this->session->unset_userdata('contact_us_confirmation');
		}else{
		
			$this->layout->view('contact-us', '', $data, 'normal');
		}
    }
	
	public function send_contact_us_request(){
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		
		if($this->input->method(TRUE) === 'POST'){
			$user = $this->session->userdata('user');
			$user_id = $user[0]->user_id;
			$post_data = $this->input->post ();
			$validation_data_array = $this->User_model->contact_us_form_validation($post_data);
			if($validation_data_array['status'] == 'SUCCESS'){
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
				
				
				$subject = $this->config->item('contact_us_page_email_subject');
				$msg = $this->config->item('contact_us_page_email_message');
			
				$phone_number_code_array = explode("##",$post_data['phone_number_code']);
				
				$msg = str_replace(['{contact_email}','{description}','{phone_number}','{contact_reason}'], [$post_data['contact_email'],trim($post_data['formated_description']),$phone_number_code_array['1']." ".$post_data['phone_number'],$post_data['contact_reason']], $msg);
				if(!empty($post_data['first_name']) && !empty($post_data['company_name'])){
					$msg = str_replace(array('{first_last_name}','{company_name}','{number_of_employees}'),array($this->config->item('contact_us_page_email_message_first_last_name'),$this->config->item('contact_us_page_email_message_company_name'),$this->config->item('contact_us_page_number_of_employees')),$msg);
					
					$msg = str_replace(array('{first_last_name}','{company_name}','{number_of_employees}'),array($post_data['first_name'],$post_data['company_name'],$post_data['number_of_company_employees']),$msg);
					
					
				}
				if(empty($post_data['first_name']) && !empty($post_data['company_name'])){
					$msg = str_replace(array('{first_last_name}','{company_name}','{number_of_employees}'),array("",$this->config->item('contact_us_page_email_message_company_name'),$this->config->item('contact_us_page_number_of_employees')),$msg);
					
					$msg = str_replace(array('{company_name}','{number_of_employees}'),array($post_data['company_name'],$post_data['number_of_company_employees']),$msg);
				}
				if(!empty($post_data['first_name']) && empty($post_data['company_name'])){
					$msg = str_replace(array('{first_last_name}','{company_name}','{number_of_employees}'),array($this->config->item('contact_us_page_email_message_first_last_name'),"",""),$msg);
					
					$msg = str_replace(array('{first_last_name}'),array($post_data['first_name']),$msg);
				}

				$this->email->initialize($config);
				
				$from_name =$this->config->item('contact_us_page_email_from_name');
				$from_name = '=?utf-8?B?'.base64_encode($from_name).'?=';
				
				$this->email->from($this->config->item('contact_us_page_email_from'),$from_name );
				if($this->config->item('contact_us_page_email_reply_to')) {
					$this->email->reply_to($this->config->item('contact_us_page_email_reply_to'));
				}
				if($this->config->item('contact_us_page_email_cc')) {
					$this->email->cc($this->config->item('contact_us_page_email_cc'));
				}
				if($this->config->item('contact_us_page_email_bcc')) {
					$this->email->bcc($this->config->item('contact_us_page_email_bcc'));
				}
				$this->email->subject($subject);
				$this->email->to($post_data['contact_email']);
				$this->email->message($msg);
				$this->email->send();
				$this->session->set_userdata('contact_us_confirmation', '1'); 
				$response['location'] = VPATH . $this->config->item('contact_us_page_url');
				$response['status'] = 200;
				echo json_encode($response);die;
				
			
			}else{
				//echo json_encode($msg);
				echo json_encode ($validation_data_array);
				die;
			}
			
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	// this is the default action which call the we_vs_them page	
    public function we_vs_them() {
        $data['current_page'] = 'we_vs_them';
		$we_vs_them_title_meta_tag = $this->config->item('we_vs_them_page_title_meta_tag');
		$we_vs_them_description_meta_tag = $this->config->item('we_vs_them_page_description_meta_tag');
		$data['meta_tag'] = '<title>' . $we_vs_them_title_meta_tag . '</title><meta name="description" content="' . $we_vs_them_description_meta_tag . '"/>';
		$this->layout->view('we-vs-them', '', $data, 'normal');
    }
	// this is the default action which call the privacy_policy page	
    public function privacy_policy() {
        $data['current_page'] = 'privacy_policy';
		$privacy_policy_title_meta_tag = $this->config->item('privacy_policy_page_title_meta_tag');
		$privacy_policy_description_meta_tag = $this->config->item('privacy_policy_page_description_meta_tag');
		$data['meta_tag'] = '<title>' . $privacy_policy_title_meta_tag . '</title><meta name="description" content="' . $privacy_policy_description_meta_tag . '"/>';
		$this->layout->view('privacy-policy', '', $data, 'normal');
    }
	// this is the default action which call the terms_and_conditions page	
    public function terms_and_conditions() {
        $data['current_page'] = 'terms_and_conditions';
		$terms_and_conditions_title_meta_tag = $this->config->item('terms_and_conditions_page_title_meta_tag');
		$terms_and_conditions_description_meta_tag = $this->config->item('terms_and_conditions_page_description_meta_tag');
		$data['meta_tag'] = '<title>' . $terms_and_conditions_title_meta_tag . '</title><meta name="description" content="' . $terms_and_conditions_description_meta_tag . '"/>';
		$this->layout->view('terms-and-conditions', '', $data, 'normal');
    }
	// this is the default action which call the code_of_conduct page	
    public function code_of_conduct() {
        $data['current_page'] = 'code_of_conduct';
		$code_of_conduct_title_meta_tag = $this->config->item('code_of_conduct_page_title_meta_tag');
		$code_of_conduct_description_meta_tag = $this->config->item('code_of_conduct_page_description_meta_tag');
		$data['meta_tag'] = '<title>' . $code_of_conduct_title_meta_tag . '</title><meta name="description" content="' . $code_of_conduct_description_meta_tag . '"/>';
		$this->layout->view('code-of-conduct', '', $data, 'normal');
    }
	// this is the default action which call the trust_and_safety page	
    public function trust_and_safety() {
        $data['current_page'] = 'trust_and_safety';
		$trust_and_safety_title_meta_tag = $this->config->item('trust_and_safety_page_title_meta_tag');
		$trust_and_safety_description_meta_tag = $this->config->item('trust_and_safety_page_description_meta_tag');
		$data['meta_tag'] = '<title>' . $trust_and_safety_title_meta_tag . '</title><meta name="description" content="' . $trust_and_safety_description_meta_tag . '"/>';
		$this->layout->view('trust-and-safety', '', $data, 'normal');
    }
	// this is the default action which call the trust_and_safety page	
    public function faq() {
        $data['current_page'] = 'faq';
		$faq_title_meta_tag = $this->config->item('faq_page_title_meta_tag');
		$faq_description_meta_tag = $this->config->item('faq_page_description_meta_tag');
		$data['meta_tag'] = '<title>' . $faq_title_meta_tag . '</title><meta name="description" content="' . $faq_description_meta_tag . '"/>';
		$this->layout->view('faq', '', $data, 'normal');
    }
	// this is the default action which call the about_us page	
    public function about_us() {
        $data['current_page'] = 'about_us';
		$about_us_title_meta_tag = $this->config->item('about_us_page_title_meta_tag');
		$about_us_description_meta_tag = $this->config->item('about_us_page_description_meta_tag');
		$data['meta_tag'] = '<title>' . $about_us_title_meta_tag . '</title><meta name="description" content="' . $about_us_description_meta_tag . '"/>';
		$this->layout->view('about-us', '', $data, 'normal');
    }
	// this is the default action which call the fees_and_charges page	
    public function fees_and_charges() {
        $data['current_page'] = 'fees_and_charges';
		$fees_and_charges_title_meta_tag = $this->config->item('fees_and_charges_page_title_meta_tag');
		$fees_and_charges_description_meta_tag = $this->config->item('fees_and_charges_page_description_meta_tag');
		$data['meta_tag'] = '<title>' . $fees_and_charges_title_meta_tag . '</title><meta name="description" content="' . $fees_and_charges_description_meta_tag . '"/>';
		$this->layout->view('fees-and-charges', '', $data, 'normal');
    }
	
	//This function is used to check the fees and charges pdf file exists or not if exists then redirect to action "download_pdf_file_from_fees_and_charges_page"
	public function check_pdf_file_exist_on_fees_and_charges_page () {
		if($this->input->is_ajax_request ()){
			if(file_exists(FCPATH .'assets'. DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'presentation_pages'. DIRECTORY_SEPARATOR .'fees_and_charges'. DIRECTORY_SEPARATOR .'blank.pdf')){
				$msg['status'] = 'SUCCESS';
				$msg['message'] = '';
				$msg['location'] = VPATH . 'user/download_pdf_file_from_fees_and_charges_page';
			}else{
				$msg['status'] = 404;
			}			
			echo json_encode ($msg);die;
		}else{
			show_custom_404_page(); //show custom 404 page
		} 
	}
	
	//This function is used to download the fees and charges pdf file
	public function download_pdf_file_from_fees_and_charges_page () {
	//DIRECTORY_SEPARATOR
		$this->load->helper('download');
		if(file_exists(FCPATH .'assets'. DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'presentation_pages'. DIRECTORY_SEPARATOR .'fees_and_charges'. DIRECTORY_SEPARATOR .'blank.pdf')){
			$data = file_get_contents (FCPATH .'assets'. DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'presentation_pages'. DIRECTORY_SEPARATOR .'fees_and_charges'. DIRECTORY_SEPARATOR .'blank.pdf');// read the content of file
			force_download ('blank.pdf',$data);
		}else{
			die("not exists");
		}
	}	
}
?>