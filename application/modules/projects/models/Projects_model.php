<?php
if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}
class Projects_model extends BaseModel
{
    public function __construct ()
    {
		parent::__construct ();
		$this->load->model('escrow/Escrow_model');
		$this->load->model('user/User_model');
    }
	
	
	/**
	* This function is used to count the total open projects of user(included open for biddding project, awaiting moderation and expired project) .
	*/
	public function get_user_open_projects_count($user_id){
	
		$user_total_open_projects_count = 0;
		$user_awaiting_moderation_project_count = $this->db->where(['project_owner_id' => $user_id])->where_in('project_type', ['fixed', 'hourly'])->from('projects_awaiting_moderation')->count_all_results();
		$user_open_bidding_project_count = $this->db->where(['project_owner_id' => $user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s')])->where_in('project_type', ['fixed', 'hourly'])->from('projects_open_bidding')->count_all_results();
		$user_open_biddding_but_expired_project_count = $this->db->where(['project_owner_id' => $user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s')])->from('projects_open_bidding')->where_in('project_type', ['fixed', 'hourly'])->count_all_results();
		$user_expired_project_count = $this->db->where(['project_owner_id' => $user_id])->from('fixed_budget_projects_expired')->count_all_results();
		$user_hourly_expired_project_count = $this->db->where(['project_owner_id' => $user_id])->from('hourly_rate_based_projects_expired')->count_all_results();
		$user_fixed_awarded_project_count = $this->db->where(['project_owner_id' => $user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s')])->from('fixed_budget_projects_awarded')->count_all_results();
		$user_fixed_awarded_but_expired_project_count = $this->db->where(['project_owner_id' => $user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s')])->from('fixed_budget_projects_awarded')->count_all_results();
		$user_hourly_awarded_project_count = $this->db->where(['project_owner_id' => $user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s')])->from('hourly_rate_based_projects_awarded')->count_all_results();
		$user_hourly_awarded_but_expired_project_count = $this->db->where(['project_owner_id' => $user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s')])->from('hourly_rate_based_projects_awarded')->count_all_results();

		$user_total_open_projects_count += $user_awaiting_moderation_project_count;
		$user_total_open_projects_count += $user_open_bidding_project_count;
		$user_total_open_projects_count += $user_open_biddding_but_expired_project_count;
		$user_total_open_projects_count += $user_expired_project_count;
		$user_total_open_projects_count += $user_hourly_expired_project_count;
		$user_total_open_projects_count += $user_fixed_awarded_project_count;
		$user_total_open_projects_count += $user_fixed_awarded_but_expired_project_count;
		$user_total_open_projects_count += $user_hourly_awarded_project_count;
		$user_total_open_projects_count += $user_hourly_awarded_but_expired_project_count;

		return $user_total_open_projects_count;
		
	}

		/**
	* This function is used to count the total open fulltime projects of user(included open for biddding project, awaiting moderation) .
	*/
	public function get_user_open_fulltime_projects_count($user_id){
		$user_total_open_projects_count = 0;
		$user_awaiting_moderation_project_count = $this->db->where(['project_owner_id' => $user_id, 'project_type' => 'fulltime'])->from('projects_awaiting_moderation')->count_all_results();
		$user_open_bidding_project_count = $this->db->where(['project_owner_id' => $user_id, 'project_type' => 'fulltime', 'project_expiration_date >='=>date('Y-m-d H:i:s')])->from('projects_open_bidding')->count_all_results();
		$user_open_biddding_but_expired_project_count = $this->db->where(['project_owner_id' => $user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s')])->from('projects_open_bidding')->where_in('project_type', ['fulltime'])->count_all_results();
		$user_expired_project_count = $this->db->where(['employer_id' => $user_id])->from('fulltime_projects_expired')->count_all_results();
		$user_total_open_projects_count += $user_awaiting_moderation_project_count;
		$user_total_open_projects_count += $user_open_bidding_project_count;
		$user_total_open_projects_count += $user_open_biddding_but_expired_project_count;
		$user_total_open_projects_count += $user_expired_project_count;
		return $user_total_open_projects_count;
		
	}
	/**
	 * This method is used to get all latest projects 
	*/
	public function get_all_latest_projects() {
		$user = $this->session->userdata('user');
			
			##################### fetch the open bidding projects data from database to display under latest project section ##########
			
			
			
			$this->db->select('op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.featured,op.urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,op.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code, u.profile_name');
			$this->db->select('plrst.project_next_refresh_time,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date');
			$this->db->from('projects_open_bidding op');
			$this->db->join('counties', 'counties.id = op.county_id', 'left');
			$this->db->join('localities', 'localities.id = op.locality_id', 'left');
			$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
			$this->db->join('users u', 'op.project_owner_id = u.user_id', 'left');
			$this->db->join('projects_latest_refresh_sequence_tracking plrst', 'plrst.project_id = op.project_id', 'left');

			$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = op.project_id', 'left');

			$this->db->where('op.project_expiration_date >= NOW()');
			$this->db->where('op.project_owner_id !=', $user[0]->user_id);
			$this->db->where('op.hidden = "N"');
			$this->db->order_by('plrst.project_last_refresh_time DESC, op.project_title ASC');
			$this->db->limit($this->config->item('dashboard_latest_projects_section_number_of_displayed_listings'));
			$standard_projects = $this->db->get()->result_array();
			$data['open_bidding_latest_projects'] = $standard_projects;

		
			
			
			if(!empty($data['open_bidding_latest_projects'])){
				foreach($data['open_bidding_latest_projects'] as $project_key=>$project_value){
					$data['open_bidding_latest_projects'][$project_key]['categories'] = $this->get_project_categories($project_value['project_id'],'open_for_bidding');

					$data['open_bidding_latest_projects'][$project_key]['fb_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_fb']));
					$data['open_bidding_latest_projects'][$project_key]['ln_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_ln']));
					$data['open_bidding_latest_projects'][$project_key]['twitter_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_twitter']));
					$data['open_bidding_latest_projects'][$project_key]['email_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_email']));
				}
			}
			return $data;
	}
	
	/**
	 * This function is used to count the total draft projects of user(included draft project)
	*/
	public function get_user_draft_projects_count($user_id) {
		$user_total_draft_projects_count = 0;
		$user_draft_project_count = $this->db->where(['project_owner_id' => $user_id])->where_in('project_type', ['fixed','hourly'])->from('projects_draft')->count_all_results();
		$user_total_draft_projects_count += $user_draft_project_count;
		return $user_total_draft_projects_count;
	}
	/**
	 * This function is used to count the total draft fulltime projects of user(included draft project)
	*/
	public function get_user_draft_fulltime_projects_count($user_id) {
		$user_total_draft_projects_count = 0;
		$user_draft_project_count = $this->db->where(['project_owner_id' => $user_id, 'project_type' => 'fulltime'])->from('projects_draft')->count_all_results();
		$user_total_draft_projects_count += $user_draft_project_count;
		return $user_total_draft_projects_count;
	}
	/**
	* This function is used to return the table name and project status .
	*/
	public function get_project_status_table_name($project_id){
		$project_status_table_name_array = array('project_status'=>'','table_name'=>'', 'project_data' => []);
		if(!empty($project_id)){
			$tables_name_array = array(
				'projects_awaiting_moderation'=>'awaiting_moderation',
				'projects_open_bidding'=>'open_for_bidding',
				'fixed_budget_projects_awarded'=>'awarded',
				'hourly_rate_based_projects_awarded'=>'awarded',
				'fixed_budget_projects_progress'=>'in_progress',
				'hourly_rate_based_projects_progress'=>'in_progress',
				'fixed_budget_projects_cancelled'=>'cancelled',
				'fixed_budget_projects_cancelled_by_admin'=>'cancelled',
				'hourly_rate_based_projects_cancelled'=>'cancelled',
				'hourly_rate_based_projects_cancelled_by_admin'=>'cancelled',
				'fulltime_projects_cancelled'=>'cancelled',
				'fulltime_projects_cancelled_by_admin'=>'cancelled',
				'fulltime_projects_expired'=>'expired',
				'hourly_rate_based_projects_expired'=>'expired',
				'fixed_budget_projects_expired'=>'expired',
				'fixed_budget_projects_completed'=>'completed',
				'fixed_budget_projects_incomplete'=>'incomplete',
				'hourly_rate_based_projects_incomplete'=>'incomplete',
				'hourly_rate_based_projects_completed'=>'completed'
			);
			foreach($tables_name_array as $table_name=>$project_status){
				if(substr($table_name, 0, strlen('fulltime')) === 'fulltime') {
					$row = $this->db->where(['fulltime_project_id LIKE' => $project_id])->from($table_name)->get()->row_array();
					$count_project_exists = !empty($row) ? 1 : 0;
				} else {
					$row = $this->db->where(['project_id LIKE' => $project_id])->from($table_name)->get()->row_array();
					$count_project_exists = !empty($row) ? 1 : 0;
				}
				if($count_project_exists > 0){
				
					if($project_status == 'awaiting_moderation'){
						
						$project_status_table_name_array = array('project_status'=>'','table_name'=>'');
						if($this->session->userdata ('user')){
							$user = $this->session->userdata ('user');
							$count_awaiting_moderation_project = $this->db 
							->select ('id')
							->from ('projects_awaiting_moderation')
							->where('project_id',$project_id)
							->where('project_owner_id',$user[0]->user_id)
							->get ()->num_rows ();
							if($count_awaiting_moderation_project > 0 ){
								$project_status_table_name_array = array('project_status'=>$project_status,'table_name'=>$table_name);
							}
						}
					}else{
						$project_status_table_name_array = array('project_status'=>$project_status,'table_name'=>$table_name, 'project_data' => $row);
					}
					break;
				}
			}
		}
		return $project_status_table_name_array;
	}
	
	/**
		* This function is used to return the table name and project status .
	*/
	public function get_project_status_type($project_id){
		$project_status_table_name_array = array('project_status'=>'','table_name'=>'');
		if(!empty($project_id)){
			$tables_name_array['projects_open_bidding'] = array('project_status'=>'open_for_bidding','project_type'=>'fixed_hourly_fulltime');
			
			$tables_name_array['fixed_budget_projects_awarded'] = array('project_status'=>'awarded','project_type'=>'fixed');
			$tables_name_array['hourly_rate_based_projects_awarded'] = array('project_status'=>'awarded','project_type'=>'hourly');
			
			$tables_name_array['fixed_budget_projects_progress'] = array('project_status'=>'in_progress','project_type'=>'fixed');
			$tables_name_array['hourly_rate_based_projects_progress'] = array('project_status'=>'in_progress','project_type'=>'hourly');
			
			
			
			
			$tables_name_array['fixed_budget_projects_cancelled'] = array('project_status'=>'cancelled','project_type'=>'fixed');
			$tables_name_array['fixed_budget_projects_cancelled_by_admin'] = array('project_status'=>'cancelled','project_type'=>'fixed');
			$tables_name_array['hourly_rate_based_projects_cancelled'] = array('project_status'=>'cancelled','project_type'=>'hourly');
			$tables_name_array['hourly_rate_based_projects_cancelled_by_admin'] = array('project_status'=>'cancelled','project_type'=>'hourly');
			$tables_name_array['fulltime_projects_cancelled'] = array('project_status'=>'cancelled','project_type'=>'fulltime');
			$tables_name_array['fulltime_projects_cancelled_by_admin'] = array('project_status'=>'cancelled','project_type'=>'fulltime');
			
			$tables_name_array['fulltime_projects_expired'] = array('project_status'=>'expired','project_type'=>'fulltime');
			$tables_name_array['hourly_rate_based_projects_expired'] = array('project_status'=>'expired','project_type'=>'hourly');
			$tables_name_array['fixed_budget_projects_expired'] = array('project_status'=>'expired','project_type'=>'fixed');
			$tables_name_array['fixed_budget_projects_completed'] = array('project_status'=>'completed','project_type'=>'fixed');
			$tables_name_array['hourly_rate_based_projects_completed'] = array('project_status'=>'completed','project_type'=>'hourly');
			
			$tables_name_array['fixed_budget_projects_incomplete'] = array('project_status'=>'incomplete','project_type'=>'fixed');
			$tables_name_array['hourly_rate_based_projects_incomplete'] = array('project_status'=>'incomplete','project_type'=>'hourly');
			
			foreach($tables_name_array as $table_name=>$project_status){
				if(substr($table_name, 0, strlen('fulltime')) === 'fulltime') {
					$count_project_exists = $this->db->where(['fulltime_project_id LIKE' => $project_id])->from($table_name)->count_all_results();
				} else {
					$count_project_exists = $this->db->where(['project_id LIKE' => $project_id])->from($table_name)->count_all_results();
				}
				
				if($count_project_exists > 0){
					$project_status_table_name_array = array('project_status'=>$project_status['project_status'],'project_type'=>$project_status['project_type'],'table_name'=>$table_name);
					break;
				}
			}
		}
		return $project_status_table_name_array;
	}
	
	/**
	* This function is used to return the list of tag of projects from tables .
	*/
	public function get_project_tags($project_id,$project_status){
		if(!empty($project_id) && !empty($project_status)){	
			########## fetch the draft project attachments ###
			if($project_status == 'awaiting_moderation')
			{
				$this->db->select('awaiting_moderation_project_tag_name');
				$this->db->from('awaiting_moderation_projects_tags');
				$this->db->where('project_id',$project_id);
				$this->db->order_by('id',"asc");
				$project_tag_result = $this->db->get();
				$project_tag_data = $project_tag_result->result_array();
			
			}elseif($project_status == 'open_for_bidding' || $project_status == 'awarded' || $project_status == 'in_progress' || $project_status == 'expired' || $project_status == 'cancelled' || $project_status == 'completed' ){
				$this->db->select('project_tag_name');
				$this->db->from('projects_tags');
				$this->db->where('project_id',$project_id);
				$this->db->order_by('id',"asc");
				$project_tag_result = $this->db->get();
				$project_tag_data = $project_tag_result->result_array();
			}
			return $project_tag_data;
		}else{
			show_404();
		}
	}
	
	/**
	This function is used to return the list of valid project attachments if any attachment is not exist in disk then this will remove the entry from table also.
	*/
	public function get_project_attachments($project_id,$user_profile_name,$project_status){
		if(!empty($project_id) && !empty($user_profile_name) && !empty($project_status)){
			########## fetch the draft project attachments ###
			if($project_status == 'awaiting_moderation')
			{
				$this->db->select('*');
				$this->db->from('awaiting_moderation_projects_attachments');
				$this->db->where('project_id',$project_id);
				$this->db->order_by('id',"asc");
				$project_attachment_result = $this->db->get();
				$project_attachment_data = $project_attachment_result->result_array();
				
			
			}elseif(in_array($project_status, array('open_for_bidding','awarded','in_progress','incomplete','expired','completed'))){
				$this->db->select('*');
				$this->db->from('projects_attachments');
				$this->db->where('project_id',$project_id);
				$this->db->order_by('id',"asc");
				$project_attachment_result = $this->db->get();
				$project_attachment_data = $project_attachment_result->result_array();
			}
			
			
			$project_attachment_array = array();
			if(!empty($project_attachment_data)){
			
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				
				$profile_folder     = $user_profile_name;
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] 	= FTP_PORT;
				$config['debug']    = TRUE;
				$this->ftp->connect($config); 
				foreach($project_attachment_data as $attachment_key){
					
					if($project_status == 'awaiting_moderation'){
						$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
						$source_path =  $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id.$project_owner_attachments_dir .$attachment_key['awaiting_moderation_project_attachment_name'];
					}else if($project_status == 'open_for_bidding'){
					
						$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
						$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
						$source_path =  $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
						$users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
					}
					else if($project_status == 'awarded'){
					
						
						$project_awarded_dir = PROJECT_AWARDED_DIR;
						
						$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
						$source_path =  $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
						$users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
					}
					else if($project_status == 'in_progress'){
					
						
						$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
						$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
						$source_path =  $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
						$users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
					}else if($project_status == 'incomplete'){
						$project_in_complete_dir = PROJECT_INCOMPLETE_DIR;
						$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
						$source_path =  $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_complete_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
						$users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_complete_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
					}
					else if($project_status == 'completed'){
					
						
						$project_completed_dir = PROJECT_COMPLETED_DIR;
						$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
						$source_path =  $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
						$users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
					}
					
					else if($project_status == 'expired'){
					
						
						
						$project_expired_dir = PROJECT_EXPIRED_DIR;
						$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
						$source_path =  $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
					}
					$file_size = $this->ftp->get_filesize($source_path);
					if($file_size != '-1'){
						$project_attachment['id'] = $attachment_key['id'];
						$project_attachment['project_id'] = $attachment_key['project_id'];
						if($project_status == 'awaiting_moderation'){
							$project_attachment['project_attachment_name'] = $attachment_key['awaiting_moderation_project_attachment_name'];
						}else if(in_array($project_status, array('open_for_bidding','awarded','expired','in_progress','incomplete','completed'))){
							$project_attachment['project_attachment_name'] = $attachment_key['project_attachment_name'];
						}
						$project_attachment['size']  = number_format($file_size/1024). 'KB';
						$project_attachment_array[] = $project_attachment;
					}else{
						if($project_status == 'awaiting_moderation'){
							$this->db->delete('awaiting_moderation_projects_attachments', array('id' => $attachment_key['id'])); 
						}else if($project_status == 'open_for_bidding' || $project_status == 'awarded' || $project_status == 'in_progress' || $project_status == 'incomplete' || $project_status == 'expired'|| $project_status == 'completed'){
							$this->db->delete('projects_attachments', array('id' => $attachment_key['id'])); 
						}
					}
				}
				$this->ftp->close();
			}
			return $project_attachment_array;
		}else{
			show_404();
		}
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
				$this->db->order_by('category_project.name',"asc");
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
				$this->db->order_by('category_project.name',"asc");
				$category_result = $this->db->get();
				$category_data = $category_result->result_array();
			
			
			} elseif($project_status == 'open_for_bidding' || $project_status == 'awarded' || $project_status == 'in_progress' || $project_status == 'expired' || $project_status == 'cancelled' || $project_status == 'completed' ){
				$this->db->select('category_project.name as category_name,parent_category_project.name as parent_category_name,category_project.id as c_id,parent_category_project.id as p_id');
				$this->db->from('projects_categories_listing_tracking as category_tracking');
				$this->db->join('categories_projects as category_project', 'category_project.id = category_tracking.project_category_id AND category_project.status = "Y"', 'left');
				$this->db->join('categories_projects as parent_category_project', 'parent_category_project.id = category_tracking.project_parent_category_id AND parent_category_project.status = "Y"', 'left');
				$this->db->where('category_tracking.project_id',$project_id);
				$this->db->order_by('category_project.name',"asc");
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
		}else{
			show_404();
			}
	}
   
	/**
     * Manage standard project refresh called from projects/ajax_update_latest_project_dashboard_view
     */
	public function manage_standard_project_refresh($next_refresh_time) {
        $time_arr = explode(":", $this->config->item('standard_project_refresh_sequence'));
		$check_valid_arr = array_map('getInt', $time_arr); 
        $valid_time_arr = array_filter($check_valid_arr);
		
			$this->db->select('op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.featured,op.urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code, u.profile_name');
			$this->db->select('(select project_next_refresh_time from '.$this->db->dbprefix.'standard_projects_refresh_sequence_tracking where project_id = op.project_id  ORDER BY id desc limit 1) as project_next_refresh_time');
			$this->db->from('projects_open_bidding op');
			$this->db->join('counties', 'counties.id = op.county_id', 'left');
			$this->db->join('localities', 'localities.id = op.locality_id', 'left');
			$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
			$this->db->join('users u', 'op.project_owner_id = u.user_id', 'left');
			$this->db->where('op.hidden = "N"');
			$this->db->where('op.project_expiration_date > NOW()');
			$this->db->order_by('project_next_refresh_time DESC');
			$standard_projects = $this->db->get()->result_array();
			
			if(!empty($standard_projects)) {
				foreach($standard_projects as $standard_project) {
					if(!empty($standard_project['project_next_refresh_time']) && strtotime($standard_project['project_next_refresh_time']) <= strtotime(date('Y-m-d H:i:s'))) {
							$next_refresh_date = get_next_refresh_time($standard_project['project_next_refresh_time'], $time_arr);
							if(strtotime($next_refresh_date) >= strtotime($standard_project['project_expiration_date'])) {
								$next_refresh_date = null;
								}
							if(empty($valid_time_arr)) { // standard refresh sequence only exectue if standard project refresh sequence time not set to disabled like "00:00:00"
								$next_refresh_date = null;
							}
							
							$next_refresh_time[$standard_project['project_id']] = [
								'project_title' => $standard_project['project_title'],
								'next_refresh_time' => $next_refresh_date,
								'type' => 'standard',
								'project_type' => $standard_project['project_type'],
								'profile' => $standard_project['profile_name'],
								'last_refresh_time' => $standard_project['project_next_refresh_time'],
								'project_owner_id' => $standard_project['project_owner_id']
							];
								
							$refresh_tracking = [
								'project_id' => $standard_project['project_id'],
								'project_last_refresh_time' => $standard_project['project_next_refresh_time'],
								'project_next_refresh_time' => $next_refresh_date,
								'project_upgrade_refresh_sequence_standard' => $this->config->item('standard_project_refresh_sequence')
								
							];
							
							$this->db->insert('standard_projects_refresh_sequence_tracking', $refresh_tracking);
							$insert_id = $this->db->insert_id();

							// update data in projects latest project refresh sequence tracking
							$latest_project_refresh_tracking = [
								'project_last_refresh_time' => $standard_project['project_next_refresh_time'],
								'project_next_refresh_time' => $next_refresh_date,
								'refresh_sequence_table_source' => 'standard'
							];
							$this->db->update('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking, ['project_id' => $standard_project['project_id']]);

							$result = $this->get_immediate_next_refresh_time_for_refreshed_project($standard_project['project_id']);
							if(!empty($result)) {
								$next_refresh_time[$standard_project['project_id']]['next_refresh_time'] = $result['project_next_refresh_time'];
							}

					}
				}
			}	
        
        return ['next_refresh_time' => $next_refresh_time];
	}
	/**
	 * get immediate next refresh time for refreshed project
	 */
	public function get_immediate_next_refresh_time_for_refreshed_project($project_id) {
		$refresh_sequence_table = [
			'standard_projects_refresh_sequence_tracking',
			'featured_projects_refresh_sequence_tracking',
			'urgent_projects_refresh_sequence_tracking',
			'sealed_projects_refresh_sequence_tracking'
		];
		$refresh_time_arr = [];
		$result = [];
		foreach($refresh_sequence_table as $val) {
			$this->db->select('*');
			$this->db->from($val);
			$this->db->where('project_id', $project_id);
			// $this->db->where('project_next_refresh_time IS NOT NULL');
			$this->db->order_by('id', 'desc');	
			$this->db->limit(1);
			$row = $this->db->get()->row_array();
			if(!empty($row)) {
				array_push($refresh_time_arr, $row['project_next_refresh_time']);				
			}	
		}
		if(!empty($refresh_time_arr)) {
			$refresh_time_arr = array_filter($refresh_time_arr);
			usort($refresh_time_arr, "date_sort");
			$result['project_next_refresh_time'] = current($refresh_time_arr);
		}
		return $result;
	}
	/**
	 * check and get next purchased upgrade detail based on parameter passed 
	 * this method is used in refresh sequence calculation method
	*/
	public function check_and_get_next_purchased_upgrade_detail($param) {
		$purchased_upgrade = [
			'membership_included' => 'proj_membership_included_upgrades_purchase_tracking',
			'real_money' => 'proj_real_money_upgrades_purchase_tracking',
			'bonus_money' => 'proj_bonus_based_upgrades_purchase_tracking'
		];
		$result = [];
		foreach($purchased_upgrade as $key => $val) {
			$this->db->select('*');
			$this->db->from($val);
			$this->db->where('project_id', $param['project_id']);
			$this->db->where('project_upgrade_type', $param['project_type']);
			$this->db->where('project_upgrade_end_date >', $param['next_refresh_time']);
			$this->db->limit(1);
			$row = $this->db->get()->row_array();
			if(!empty($row)){
				$result = $row;
				$result['refresh_sequence_table_source'] = $key;
				break;
			}
		}
		
		return $result;
	}
	/**
	 * This function is used to check given record is already exist in refresh sequence table or not
	*/
	public function check_unique_entry_for_project_refresh_sequence($refresh_sequence_table, $cond = []) {
		return $this->db->where($cond)->from($refresh_sequence_table)->count_all_results();
	}
    /**
     * manage featured project refresh for real payment called from cron/cron_calculate_and_update_project_refresh_sequence
     */
    public function manage_featured_project_refresh_for_real_money_payment($next_refresh_time) {
        $time_arr = explode(":", $this->config->item('project_upgrade_refresh_sequence_featured'));
		$check_valid_arr = array_map('getInt', $time_arr); 
        $valid_time_arr = array_filter($check_valid_arr);
		$standard_time_arr = explode(":", $this->config->item('standard_project_refresh_sequence'));
		
		
			$this->db->select('op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.featured,op.urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code, u.profile_name');
			$this->db->select('upt.real_money_project_upgrade_purchase_reference_id as upgrade_purchase_id, upt.project_upgrade_end_date');
			$this->db->select('(select project_next_refresh_time from '.$this->db->dbprefix.'featured_projects_refresh_sequence_tracking where project_upgrade_purchase_reference_id = upt.real_money_project_upgrade_purchase_reference_id AND project_upgrade_purchase_tracking_table_source = "real_money"  ORDER BY id desc limit 1) as project_next_refresh_time');
			$this->db->from('proj_real_money_upgrades_purchase_tracking upt');
			$this->db->join('users u', 'upt.project_owner_id = u.user_id', 'left');
			$this->db->join('projects_open_bidding op', 'op.project_id = upt.project_id');
			$this->db->join('counties', 'counties.id = op.county_id', 'left');
			$this->db->join('localities', 'localities.id = op.locality_id', 'left');
			$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
			$this->db->where('op.project_expiration_date >= NOW()');
			$this->db->where('upt.project_upgrade_type', 'featured');
			$this->db->where('upt.project_upgrade_end_date > NOW()');
			$featured_purchase = $this->db->get()->result_array();
			if(!empty($featured_purchase)) {
				foreach($featured_purchase as $featured) {
					if(!empty($featured['project_next_refresh_time']) && strtotime($featured['project_next_refresh_time']) <= strtotime(date('Y-m-d H:i:s'))) {
							$next_refresh_date = get_next_refresh_time($featured['project_next_refresh_time'], $time_arr);
							if(strtotime($next_refresh_date) >= strtotime($featured['project_upgrade_end_date'])) {
								$param = [
									'project_id' => $featured['project_id'],
									'project_type' => 'featured',
									'next_refresh_time' => $next_refresh_date
								];
								$purchased_upgrade = $this->check_and_get_next_purchased_upgrade_detail($param);
								if(empty($purchased_upgrade)) {
									$next_refresh_date = null;
								}
							}
							if(empty($valid_time_arr)) { // featured refresh sequence only exectue if featured project refresh sequence time not set to disabled like "00:00:00"
								$next_refresh_date = null;
							}
							$next_refresh_time[$featured['project_id']] = [
								'project_title' => $featured['project_title'],
								'next_refresh_time' => $next_refresh_date,
								'type' => 'featured',
								'project_type' => $featured['project_type'],
								'profile' => $featured['profile_name'],
								'last_refresh_time' => $featured['project_next_refresh_time'],
								'project_owner_id' => $featured['project_owner_id']
							];
							
							if(!empty($purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['real_money_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['real_money_project_upgrade_purchase_reference_id'];
							}

							$refresh_tracking = [
								'project_id' => $featured['project_id'],
								'project_upgrade_purchase_reference_id' => empty($purchased_upgrade) ? $featured['upgrade_purchase_id'] : $purchased_upgrade['id'],
								'project_upgrade_purchase_tracking_table_source' => empty($purchased_upgrade) ? 'real_money' : $purchased_upgrade['refresh_sequence_table_source'],
								'project_last_refresh_time' => $featured['project_next_refresh_time'],
								'project_next_refresh_time' => $next_refresh_date,
								'project_upgrade_refresh_sequence_featured'=>$this->config->item('project_upgrade_refresh_sequence_featured')
							];
							
							$check_record_exists = $this->db->where(['project_id' => $featured['project_id'],'project_upgrade_purchase_tracking_table_source'=>empty($purchased_upgrade) ? 'real_money' : $purchased_upgrade['refresh_sequence_table_source'],'project_last_refresh_time'=>$featured['project_next_refresh_time'],'project_next_refresh_time'=>$next_refresh_date])->from('featured_projects_refresh_sequence_tracking')->count_all_results();
							
							if($check_record_exists == 0){
								$this->db->insert('featured_projects_refresh_sequence_tracking', $refresh_tracking);
								$insert_id = $this->db->insert_id();
								// update data in projects latest project refresh sequence tracking
								$latest_project_refresh_tracking = [
									'project_last_refresh_time' => $featured['project_next_refresh_time'],
									'project_next_refresh_time' => $next_refresh_date,
									'refresh_sequence_table_source' => 'featured'
								];
								$this->db->update('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking, ['project_id' => $featured['project_id']]);
								$result = $this->get_immediate_next_refresh_time_for_refreshed_project($featured['project_id']);
								if(!empty($result)) {
									$next_refresh_time[$featured['project_id']]['next_refresh_time'] = $result['project_next_refresh_time'];
								}
							}else{
								unset($next_refresh_time[$featured['project_id']]);
							}
					}
				}
			}	
        
        return ['next_refresh_time' => $next_refresh_time ];
    }
    /**
     * manage featured project refresh for membership included payment called from cron/cron_calculate_and_update_project_refresh_sequence
     */
    public function manage_featured_project_refresh_for_membership_included_payment($next_refresh_time) {
        $time_arr = explode(":", $this->config->item('project_upgrade_refresh_sequence_featured'));
		$check_valid_arr = array_map('getInt', $time_arr); 
        $valid_time_arr = array_filter($check_valid_arr);
        $standard_time_arr = explode(":", $this->config->item('standard_project_refresh_sequence'));
		
		
			$this->db->select('op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.featured,op.urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code, u.profile_name');
			$this->db->select('mupt.membership_included_project_upgrade_purchase_reference_id as upgrade_purchase_id, mupt.project_upgrade_end_date');
			$this->db->select('(select project_next_refresh_time from '.$this->db->dbprefix.'featured_projects_refresh_sequence_tracking where project_upgrade_purchase_reference_id = mupt.membership_included_project_upgrade_purchase_reference_id AND project_upgrade_purchase_tracking_table_source = "membership_included"  ORDER BY id desc limit 1) as project_next_refresh_time');
			$this->db->from('proj_membership_included_upgrades_purchase_tracking mupt');
			$this->db->join('users u', 'mupt.project_owner_id = u.user_id', 'left');
			$this->db->join('projects_open_bidding op', 'op.project_id = mupt.project_id');
			$this->db->join('counties', 'counties.id = op.county_id', 'left');
			$this->db->join('localities', 'localities.id = op.locality_id', 'left');
			$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
			$this->db->where('op.project_expiration_date >= NOW()');
			$this->db->where('mupt.project_upgrade_type', 'featured');
			$this->db->where('mupt.project_upgrade_end_date > NOW()');
			
            $featured_purchase = $this->db->get()->result_array();
            
			if(!empty($featured_purchase)) {
				foreach($featured_purchase as $featured) {
					if( !empty($featured['project_next_refresh_time']) && strtotime($featured['project_next_refresh_time']) <= strtotime(date('Y-m-d H:i:s'))) {
							
							$next_refresh_date = get_next_refresh_time($featured['project_next_refresh_time'], $time_arr);
							
							if(strtotime($next_refresh_date) >= strtotime($featured['project_upgrade_end_date'])) {
								$param = [
									'project_id' => $featured['project_id'],
									'project_type' => 'featured',
									'next_refresh_time' => $next_refresh_date
								];
								$purchased_upgrade = $this->check_and_get_next_purchased_upgrade_detail($param);
								if(empty($purchased_upgrade)) {
									$next_refresh_date = null;
								}
							}
							if(empty($valid_time_arr)) { // featured refresh sequence only exectue if featured project refresh sequence time not set to disabled like "00:00:00"
								$next_refresh_date = null;
							}
							$next_refresh_time[$featured['project_id']] = [
								'project_title' => $featured['project_title'],
								'next_refresh_time' => $next_refresh_date,
								'type' => 'featured',
								'project_type' => $featured['project_type'],
								'profile' => $featured['profile_name'],
								'last_refresh_time' => $featured['project_next_refresh_time'],
								'project_owner_id' => $featured['project_owner_id']
							];
							
							if(!empty($purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['real_money_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['real_money_project_upgrade_purchase_reference_id'];
							}

							$refresh_tracking = [
								'project_id' => $featured['project_id'],
								'project_upgrade_purchase_reference_id' => empty($purchased_upgrade) ?  $featured['upgrade_purchase_id'] : $purchased_upgrade['id'],
								'project_upgrade_purchase_tracking_table_source' => empty($purchased_upgrade) ? 'membership_included' : $purchased_upgrade['refresh_sequence_table_source'],
								'project_last_refresh_time' => $featured['project_next_refresh_time'],
								'project_next_refresh_time' => $next_refresh_date,
								'project_upgrade_refresh_sequence_featured'=>$this->config->item('project_upgrade_refresh_sequence_featured')
							];
							
							
							$check_record_exists = $this->db->where(['project_id' => $featured['project_id'],'project_upgrade_purchase_tracking_table_source'=>empty($purchased_upgrade) ? 'membership_included' : $purchased_upgrade['refresh_sequence_table_source'],'project_last_refresh_time'=>$featured['project_next_refresh_time'],'project_next_refresh_time'=>$next_refresh_date])->from('featured_projects_refresh_sequence_tracking')->count_all_results();
							if($check_record_exists == 0){
							
								$this->db->insert('featured_projects_refresh_sequence_tracking', $refresh_tracking);
								$insert_id = $this->db->insert_id();
								// update data in projects latest project refresh sequence tracking
								$latest_project_refresh_tracking = [
									'project_last_refresh_time' => $featured['project_next_refresh_time'],
									'project_next_refresh_time' => $next_refresh_date,
									'refresh_sequence_table_source' => 'featured'
								];
								$this->db->update('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking, ['project_id' => $featured['project_id']]);
								$result = $this->get_immediate_next_refresh_time_for_refreshed_project($featured['project_id']);
								if(!empty($result)) {
									$next_refresh_time[$featured['project_id']]['next_refresh_time'] = $result['project_next_refresh_time'];
								}
							}else{
								unset($next_refresh_time[$featured['project_id']]);
							
							}
							
					}
				}
			}	
        
        return ['next_refresh_time' => $next_refresh_time ];
    }
    /**
     * manage featured project refresh for bonus based payment called from cron/cron_calculate_and_update_project_refresh_sequence
     */
    public function manage_featured_project_refresh_for_bonus_based_payment($next_refresh_time) {
        $time_arr = explode(":", $this->config->item('project_upgrade_refresh_sequence_featured'));
		$check_valid_arr = array_map('getInt', $time_arr); 
        $valid_time_arr = array_filter($check_valid_arr);
        $standard_time_arr = explode(":", $this->config->item('standard_project_refresh_sequence'));
		
		
			$this->db->select('op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.featured,op.urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code, u.profile_name');
			$this->db->select('bput.bonus_based_project_upgrade_purchase_reference_id as upgrade_purchase_id, bput.project_upgrade_end_date');
			$this->db->select('(select project_next_refresh_time from '.$this->db->dbprefix.'featured_projects_refresh_sequence_tracking where project_upgrade_purchase_reference_id = bput.bonus_based_project_upgrade_purchase_reference_id AND project_upgrade_purchase_tracking_table_source = "bonus_money"  ORDER BY id desc limit 1) as project_next_refresh_time');
			$this->db->from('proj_bonus_based_upgrades_purchase_tracking bput');
			$this->db->join('users u', 'bput.project_owner_id = u.user_id', 'left');
			$this->db->join('projects_open_bidding op', 'op.project_id = bput.project_id');
			$this->db->join('counties', 'counties.id = op.county_id', 'left');
			$this->db->join('localities', 'localities.id = op.locality_id', 'left');
			$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
			$this->db->where('op.project_expiration_date >= NOW()');
			$this->db->where('bput.project_upgrade_type', 'featured');
			$this->db->where('bput.project_upgrade_end_date > NOW()');
            $featured_purchase = $this->db->get()->result_array();
			if(!empty($featured_purchase)) {
				foreach($featured_purchase as $featured) {
					if( !empty($featured['project_next_refresh_time']) && strtotime($featured['project_next_refresh_time']) <= strtotime(date('Y-m-d H:i:s'))) {
							$next_refresh_date = get_next_refresh_time($featured['project_next_refresh_time'], $time_arr);
							if(strtotime($next_refresh_date) >= strtotime($featured['project_upgrade_end_date'])) {
								$param = [
									'project_id' => $featured['project_id'],
									'project_type' => 'featured',
									'next_refresh_time' => $next_refresh_date
								];
								$purchased_upgrade = $this->check_and_get_next_purchased_upgrade_detail($param);
								if(empty($purchased_upgrade)) {
									$next_refresh_date = null;
								}
							}
							if(empty($valid_time_arr)) { // featured refresh sequence only exectue if featured project refresh sequence time not set to disabled like "00:00:00"
								$next_refresh_date = null;
							}
							$next_refresh_time[$featured['project_id']] = [
								'project_title' => $featured['project_title'],
								'next_refresh_time' => $next_refresh_date,
								'type' => 'featured',
								'project_type' => $featured['project_type'],
								'profile' => $featured['profile_name'],
								'last_refresh_time' => $featured['project_next_refresh_time'],
								'project_owner_id' => $featured['project_owner_id']
							];
							
							if(!empty($purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['real_money_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['real_money_project_upgrade_purchase_reference_id'];
							}

							$refresh_tracking = [
								'project_id' => $featured['project_id'],
								'project_upgrade_purchase_reference_id' => empty($purchased_upgrade) ? $featured['upgrade_purchase_id'] : $purchased_upgrade['id'],
								'project_upgrade_purchase_tracking_table_source' => empty($purchased_upgrade) ? 'bonus_money' : $purchased_upgrade['refresh_sequence_table_source'],
								'project_last_refresh_time' => $featured['project_next_refresh_time'],
								'project_next_refresh_time' => $next_refresh_date,
								'project_upgrade_refresh_sequence_featured'=>$this->config->item('project_upgrade_refresh_sequence_featured')
							];
							
							$check_record_exists = $this->db->where(['project_id' => $featured['project_id'],'project_upgrade_purchase_tracking_table_source'=>empty($purchased_upgrade) ? 'bonus_money' : $purchased_upgrade['refresh_sequence_table_source'],'project_last_refresh_time'=>$featured['project_next_refresh_time'],'project_next_refresh_time'=>$next_refresh_date])->from('featured_projects_refresh_sequence_tracking')->count_all_results();
							if($check_record_exists == 0){
								$this->db->insert('featured_projects_refresh_sequence_tracking', $refresh_tracking);
								$insert_id = $this->db->insert_id();
								// update data in projects latest project refresh sequence tracking
								$latest_project_refresh_tracking = [
									'project_last_refresh_time' => $featured['project_next_refresh_time'],
									'project_next_refresh_time' => $next_refresh_date,
									'refresh_sequence_table_source' => 'featured'
								];
								$this->db->update('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking, ['project_id' => $featured['project_id']]);
								$result = $this->get_immediate_next_refresh_time_for_refreshed_project($featured['project_id']);
								if(!empty($result)) {
									$next_refresh_time[$featured['project_id']]['next_refresh_time'] = $result['project_next_refresh_time'];
								}
							}else{
							
								unset($next_refresh_time[$featured['project_id']]);
							}
							
					}
				}
			}	
        
        return ['next_refresh_time' => $next_refresh_time ];
    }
    /**
     * manage urgent project refresh called from cron/cron_calculate_and_update_project_refresh_sequence
     */
    public function manage_urgent_project_refresh_for_real_money_payment($next_refresh_time) {
      $time_arr = explode(":", $this->config->item('project_upgrade_refresh_sequence_urgent'));
			$check_valid_arr = array_map('getInt', $time_arr); 
			$valid_time_arr = array_filter($check_valid_arr);
			$standard_time_arr = explode(":", $this->config->item('standard_project_refresh_sequence'));
		
		
			$this->db->select('op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.featured,op.urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code, u.profile_name');
			$this->db->select('upt.real_money_project_upgrade_purchase_reference_id as upgrade_purchase_id, upt.project_upgrade_end_date');
			$this->db->select('(select project_next_refresh_time from '.$this->db->dbprefix.'urgent_projects_refresh_sequence_tracking where project_upgrade_purchase_reference_id = upt.real_money_project_upgrade_purchase_reference_id AND project_upgrade_purchase_tracking_table_source = "real_money"  ORDER BY id desc limit 1) as project_next_refresh_time');
			$this->db->from('proj_real_money_upgrades_purchase_tracking upt');
			$this->db->join('users u', 'upt.project_owner_id = u.user_id', 'left');
			$this->db->join('projects_open_bidding op', 'op.project_id = upt.project_id');
			$this->db->join('counties', 'counties.id = op.county_id', 'left');
			$this->db->join('localities', 'localities.id = op.locality_id', 'left');
			$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
			$this->db->where('op.project_expiration_date >= NOW()');
			$this->db->where('upt.project_upgrade_type', 'urgent');
			$this->db->where('upt.project_upgrade_end_date > NOW()');
			$urgent_purchase = $this->db->get()->result_array();
			
			
			if(!empty($urgent_purchase)) {
				foreach($urgent_purchase as $urgent) {
					
					if(!empty($urgent['project_next_refresh_time']) && strtotime($urgent['project_next_refresh_time']) <= strtotime(date('Y-m-d H:i:s'))) {
						
							$next_refresh_date = get_next_refresh_time($urgent['project_next_refresh_time'],$time_arr);
							
							if(strtotime($next_refresh_date) >= strtotime($urgent['project_upgrade_end_date'])) {
								$param = [
									'project_id' => $urgent['project_id'],
									'project_type' => 'urgent',
									'next_refresh_time' => $next_refresh_date
								];
								$purchased_upgrade = $this->check_and_get_next_purchased_upgrade_detail($param);
								if(empty($purchased_upgrade)) {
									$next_refresh_date = null;
								}
							} 
							if(empty($valid_time_arr)) { // urgent refresh sequence only exectue if urgent project refresh sequence time not set to disabled like "00:00:00"
								$next_refresh_date = null;
							}
							if(array_key_exists($urgent['project_id'], $next_refresh_time) && $next_refresh_time[$urgent['project_id']]['type'] == 'featured') {
								$next_refresh_time[$urgent['project_id']] = [
									'project_title' => $urgent['project_title'],
									'next_refresh_time' => $next_refresh_date,
									'type' => 'featured',
									'project_type' => $urgent['project_type'],
									'profile' => $urgent['profile_name'],
									'last_refresh_time' => $urgent['project_next_refresh_time'],
									'project_owner_id' => $urgent['project_owner_id']
								];
							} else {
								$next_refresh_time[$urgent['project_id']] = [
									'project_title' => $urgent['project_title'],
									'next_refresh_time' => $next_refresh_date,
									'type' => 'urgent',
									'project_type' => $urgent['project_type'],
									'profile' => $urgent['profile_name'],
									'last_refresh_time' => $urgent['project_next_refresh_time'],
									'project_owner_id' => $urgent['project_owner_id']
								];
							}
							
							if(!empty($purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['real_money_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['real_money_project_upgrade_purchase_reference_id'];
							}
							
							$refresh_tracking = [
								'project_id' => $urgent['project_id'],
								'project_upgrade_purchase_reference_id' => empty($purchased_upgrade) ?  $urgent['upgrade_purchase_id'] : $purchased_upgrade['id'],
								'project_upgrade_purchase_tracking_table_source' => empty($purchased_upgrade) ? 'real_money' : $purchased_upgrade['refresh_sequence_table_source'],
								'project_last_refresh_time' => $urgent['project_next_refresh_time'],
								'project_next_refresh_time' => $next_refresh_date,
								'project_upgrade_refresh_sequence_urgent' => $this->config->item('project_upgrade_refresh_sequence_urgent')
							];
							
							$check_record_exists = $this->db->where(['project_id' => $urgent['project_id'],'project_upgrade_purchase_tracking_table_source'=>empty($purchased_upgrade) ? 'real_money' : $purchased_upgrade['refresh_sequence_table_source'],'project_last_refresh_time'=>$urgent['project_next_refresh_time'],'project_next_refresh_time'=>$next_refresh_date])->from('urgent_projects_refresh_sequence_tracking')->count_all_results();
							if($check_record_exists == 0){
							
								$this->db->insert('urgent_projects_refresh_sequence_tracking', $refresh_tracking);
								$insert_id = $this->db->insert_id();
								// update data in projects latest project refresh sequence tracking
								$latest_project_refresh_tracking = [
									'project_last_refresh_time' => $urgent['project_next_refresh_time'],
									'project_next_refresh_time' => $next_refresh_date,
									'refresh_sequence_table_source' => 'urgent'
								];
								$this->db->update('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking, ['project_id' => $urgent['project_id']]);

								$result = $this->get_immediate_next_refresh_time_for_refreshed_project($urgent['project_id']);
								if(!empty($result)) {
									$next_refresh_time[$urgent['project_id']]['next_refresh_time'] = $result['project_next_refresh_time'];
								}
							}else{
							
								unset($next_refresh_time[$urgent['project_id']]);
							}
							
					}
					
				}
			}	
        
        return ['next_refresh_time' => $next_refresh_time ];
    }
    /**
     * manage urgent project refresh membership included payment called from cron/cron_calculate_and_update_project_refresh_sequence
     */
    public function manage_urgent_project_refresh_for_membership_included_payment($next_refresh_time) {
        $time_arr = explode(":", $this->config->item('project_upgrade_refresh_sequence_urgent'));
		$check_valid_arr = array_map('getInt', $time_arr); 
        $valid_time_arr = array_filter($check_valid_arr);
        $standard_time_arr = explode(":", $this->config->item('standard_project_refresh_sequence'));
		
			$this->db->select('op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.featured,op.urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code, u.profile_name');
			$this->db->select('mupt.membership_included_project_upgrade_purchase_reference_id as upgrade_purchase_id, mupt.project_upgrade_end_date');
			$this->db->select('(select project_next_refresh_time from '.$this->db->dbprefix.'urgent_projects_refresh_sequence_tracking where project_upgrade_purchase_reference_id = mupt.membership_included_project_upgrade_purchase_reference_id AND project_upgrade_purchase_tracking_table_source = "membership_included"  ORDER BY id desc limit 1) as project_next_refresh_time');
			$this->db->from('proj_membership_included_upgrades_purchase_tracking mupt');
			$this->db->join('users u', 'mupt.project_owner_id = u.user_id', 'left');
			$this->db->join('projects_open_bidding op', 'op.project_id = mupt.project_id');
			$this->db->join('counties', 'counties.id = op.county_id', 'left');
			$this->db->join('localities', 'localities.id = op.locality_id', 'left');
			$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
			$this->db->where('op.project_expiration_date >= NOW()');
			$this->db->where('mupt.project_upgrade_type', 'urgent');
			$this->db->where('mupt.project_upgrade_end_date > NOW()');
			$urgent_purchase = $this->db->get()->result_array();
			if(!empty($urgent_purchase)) {
				foreach($urgent_purchase as $urgent) {
					
					if(!empty($urgent['project_next_refresh_time']) && strtotime($urgent['project_next_refresh_time']) <= strtotime(date('Y-m-d H:i:s'))) {
							
							$next_refresh_date = get_next_refresh_time($urgent['project_next_refresh_time'],$time_arr);
							
							if(strtotime($next_refresh_date) >= strtotime($urgent['project_upgrade_end_date'])) {
								$param = [
									'project_id' => $urgent['project_id'],
									'project_type' => 'urgent',
									'next_refresh_time' => $next_refresh_date
								];
								$purchased_upgrade = $this->check_and_get_next_purchased_upgrade_detail($param);
								if(empty($purchased_upgrade)) {
									$next_refresh_date = null;
								}
							}
							if(empty($valid_time_arr)) { // urgent refresh sequence only exectue if urgent project refresh sequence time not set to disabled like "00:00:00"
								$next_refresh_date = null;
							} 
							if(array_key_exists($urgent['project_id'], $next_refresh_time) && $next_refresh_time[$urgent['project_id']]['type'] == 'featured') {
								$next_refresh_time[$urgent['project_id']] = [
									'project_title' => $urgent['project_title'],
									'next_refresh_time' => $next_refresh_date,
									'type' => 'featured',
									'project_type' => $urgent['project_type'],
									'profile' => $urgent['profile_name'],
									'last_refresh_time' => $urgent['project_next_refresh_time'],
									'project_owner_id' => $urgent['project_owner_id']
								];
							} else {
								$next_refresh_time[$urgent['project_id']] = [
									'project_title' => $urgent['project_title'],
									'next_refresh_time' => $next_refresh_date,
									'type' => 'urgent',
									'project_type' => $urgent['project_type'],
									'profile' => $urgent['profile_name'],
									'last_refresh_time' => $urgent['project_next_refresh_time'],
									'project_owner_id' => $urgent['project_owner_id']
								];
							}

							if(!empty($purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['real_money_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['real_money_project_upgrade_purchase_reference_id'];
							}

							$refresh_tracking = [
								'project_id' => $urgent['project_id'],
								'project_upgrade_purchase_reference_id' => empty($purchased_upgrade) ? $urgent['upgrade_purchase_id'] : $purchased_upgrade['id'],
								'project_upgrade_purchase_tracking_table_source' => empty($purchased_upgrade) ? 'membership_included' : $purchased_upgrade['refresh_sequence_table_source'],
								'project_last_refresh_time' => $urgent['project_next_refresh_time'],
								'project_next_refresh_time' => $next_refresh_date,
								'project_upgrade_refresh_sequence_urgent' => $this->config->item('project_upgrade_refresh_sequence_urgent')
							];
							
							$check_record_exists = $this->db->where(['project_id' => $urgent['project_id'],'project_upgrade_purchase_tracking_table_source'=>empty($purchased_upgrade) ? 'membership_included' : $purchased_upgrade['refresh_sequence_table_source'],'project_last_refresh_time'=>$urgent['project_next_refresh_time'],'project_next_refresh_time'=>$next_refresh_date])->from('urgent_projects_refresh_sequence_tracking')->count_all_results();
							if($check_record_exists == 0){
							
								$this->db->insert('urgent_projects_refresh_sequence_tracking', $refresh_tracking);
								$insert_id = $this->db->insert_id();
								// update data in projects latest project refresh sequence tracking
								$latest_project_refresh_tracking = [
									'project_last_refresh_time' => $urgent['project_next_refresh_time'],
									'project_next_refresh_time' => $next_refresh_date,
									'refresh_sequence_table_source' => 'urgent'
								];
								$this->db->update('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking, ['project_id' => $urgent['project_id']]);

								$result = $this->get_immediate_next_refresh_time_for_refreshed_project($urgent['project_id']);
								if(!empty($result)) {
									$next_refresh_time[$urgent['project_id']]['next_refresh_time'] = $result['project_next_refresh_time'];
								}
							}else{
								unset($next_refresh_time[$urgent['project_id']]);
							
							}
							
					}
					
				}
			}	
        
        return ['next_refresh_time' => $next_refresh_time ];
    }
    /**
     * manage urgent project refresh bonus based payment called from cron/cron_calculate_and_update_project_refresh_sequence
     */
    public function manage_urgent_project_refresh_for_bonus_based_payment($next_refresh_time) {
        $time_arr = explode(":", $this->config->item('project_upgrade_refresh_sequence_urgent'));
		$check_valid_arr = array_map('getInt', $time_arr); 
        $valid_time_arr = array_filter($check_valid_arr);
        $standard_time_arr = explode(":", $this->config->item('standard_project_refresh_sequence'));
		
			$this->db->select('op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.featured,op.urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code, u.profile_name');
			$this->db->select('bupt.bonus_based_project_upgrade_purchase_reference_id as upgrade_purchase_id, bupt.project_upgrade_end_date');
			$this->db->select('(select project_next_refresh_time from '.$this->db->dbprefix.'urgent_projects_refresh_sequence_tracking where project_upgrade_purchase_reference_id = bupt.bonus_based_project_upgrade_purchase_reference_id AND project_upgrade_purchase_tracking_table_source = "bonus_money"  ORDER BY id desc limit 1) as project_next_refresh_time');
			$this->db->from('proj_bonus_based_upgrades_purchase_tracking bupt');
			$this->db->join('users u', 'bupt.project_owner_id = u.user_id', 'left');
			$this->db->join('projects_open_bidding op', 'op.project_id = bupt.project_id');
			$this->db->join('counties', 'counties.id = op.county_id', 'left');
			$this->db->join('localities', 'localities.id = op.locality_id', 'left');
			$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
			$this->db->where('op.project_expiration_date >= NOW()');
			$this->db->where('bupt.project_upgrade_type', 'urgent');
			$this->db->where('bupt.project_upgrade_end_date > NOW()');
			$urgent_purchase = $this->db->get()->result_array();
			if(!empty($urgent_purchase)) {
				foreach($urgent_purchase as $urgent) {
					
					if(!empty($urgent['project_next_refresh_time']) && strtotime($urgent['project_next_refresh_time']) <= strtotime(date('Y-m-d H:i:s'))) {
							
							$next_refresh_date = get_next_refresh_time($urgent['project_next_refresh_time'],$time_arr);
							
							if(strtotime($next_refresh_date) >= strtotime($urgent['project_upgrade_end_date'])) {
								$param = [
									'project_id' => $urgent['project_id'],
									'project_type' => 'urgent',
									'next_refresh_time' => $next_refresh_date
								];
								$purchased_upgrade = $this->check_and_get_next_purchased_upgrade_detail($param);
								if(empty($purchased_upgrade)) {
									$next_refresh_date = null;
								}
							} 
							if(empty($valid_time_arr)) { // urgent refresh sequence only exectue if urgent project refresh sequence time not set to disabled like "00:00:00"
								$next_refresh_date = null;
							}
							if(array_key_exists($urgent['project_id'], $next_refresh_time) && $next_refresh_time[$urgent['project_id']]['type'] == 'featured') {
								$next_refresh_time[$urgent['project_id']] = [
									'project_title' => $urgent['project_title'],
									'next_refresh_time' => $next_refresh_date,
									'type' => 'featured',
									'project_type' => $urgent['project_type'],
									'profile' => $urgent['profile_name'],
									'last_refresh_time' => $urgent['project_next_refresh_time'],
									'project_owner_id' => $urgent['project_owner_id']
								];
							} else {
								$next_refresh_time[$urgent['project_id']] = [
									'project_title' => $urgent['project_title'],
									'next_refresh_time' => $next_refresh_date,
									'type' => 'urgent',
									'project_type' => $urgent['project_type'],
									'profile' => $urgent['profile_name'],
									'last_refresh_time' => $urgent['project_next_refresh_time'],
									'project_owner_id' => $urgent['project_owner_id']
								];
							}

							if(!empty($purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['real_money_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['real_money_project_upgrade_purchase_reference_id'];
							}

							$refresh_tracking = [
								'project_id' => $urgent['project_id'],
								'project_upgrade_purchase_reference_id' => empty($purchased_upgrade) ? $urgent['upgrade_purchase_id'] : $purchased_upgrade['id'],
								'project_upgrade_purchase_tracking_table_source' => empty($purchased_upgrade) ? 'bonus_money' : $purchased_upgrade['refresh_sequence_table_source'],
								'project_last_refresh_time' => $urgent['project_next_refresh_time'],
								'project_next_refresh_time' => $next_refresh_date,
								'project_upgrade_refresh_sequence_urgent' => $this->config->item('project_upgrade_refresh_sequence_urgent')
							];
							
							$check_record_exists = $this->db->where(['project_id' => $urgent['project_id'],'project_upgrade_purchase_tracking_table_source'=>empty($purchased_upgrade) ? 'bonus_money' : $purchased_upgrade['refresh_sequence_table_source'],'project_last_refresh_time'=>$urgent['project_next_refresh_time'],'project_next_refresh_time'=>$next_refresh_date])->from('urgent_projects_refresh_sequence_tracking')->count_all_results();
							
							if($check_record_exists == 0){
								$this->db->insert('urgent_projects_refresh_sequence_tracking', $refresh_tracking);
								$insert_id = $this->db->insert_id();
								// update data in projects latest project refresh sequence tracking
								$latest_project_refresh_tracking = [
									'project_last_refresh_time' => $urgent['project_next_refresh_time'],
									'project_next_refresh_time' => $next_refresh_date,
									'refresh_sequence_table_source' => 'urgent'
								];
								$this->db->update('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking, ['project_id' => $urgent['project_id']]);
								$result = $this->get_immediate_next_refresh_time_for_refreshed_project($urgent['project_id']);
								if(!empty($result)) {
									$next_refresh_time[$urgent['project_id']]['next_refresh_time'] = $result['project_next_refresh_time'];
								}
							}else{
								unset($next_refresh_time[$urgent['project_id']]);
							}
							
					}
					
				}
			}	
        
        return ['next_refresh_time' => $next_refresh_time ];
    }
    /**
     * manage sealed project refresh called from cron/cron_calculate_and_update_project_refresh_sequence
     */
    public function manage_sealed_project_refresh_for_real_money_payment($next_refresh_time) {
        $time_arr = explode(":", $this->config->item('project_upgrade_refresh_sequence_sealed'));
		$check_valid_arr = array_map('getInt', $time_arr); 
		$valid_time_arr = array_filter($check_valid_arr);
		$standard_time_arr = explode(":", $this->config->item('standard_project_refresh_sequence'));
		
		
			$this->db->select('op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.featured,op.urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code, u.profile_name');
			$this->db->select('upt.real_money_project_upgrade_purchase_reference_id as upgrade_purchase_id, upt.project_upgrade_end_date');
			$this->db->select('(select project_next_refresh_time from '.$this->db->dbprefix.'sealed_projects_refresh_sequence_tracking where project_upgrade_purchase_reference_id = upt.real_money_project_upgrade_purchase_reference_id AND project_upgrade_purchase_tracking_table_source = "real_money"  ORDER BY id desc limit 1) as project_next_refresh_time');
			$this->db->from('proj_real_money_upgrades_purchase_tracking upt');
			$this->db->join('users u', 'upt.project_owner_id = u.user_id', 'left');
			$this->db->join('projects_open_bidding op', 'op.project_id = upt.project_id');
			$this->db->join('counties', 'counties.id = op.county_id', 'left');
			$this->db->join('localities', 'localities.id = op.locality_id', 'left');
			$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
			$this->db->where('op.project_expiration_date >= NOW()');
			$this->db->where('upt.project_upgrade_type', 'sealed');
			$this->db->where('upt.project_upgrade_end_date > NOW()');
			$sealed_purchase = $this->db->get()->result_array();
			if(!empty($sealed_purchase)) {
				foreach($sealed_purchase as $sealed) {
					if(!empty($sealed['project_next_refresh_time']) && strtotime($sealed['project_next_refresh_time']) <= strtotime(date('Y-m-d H:i:s'))) {
							
							$next_refresh_date = get_next_refresh_time($sealed['project_next_refresh_time'], $time_arr);
							
							if(strtotime($next_refresh_date) >= strtotime($sealed['project_upgrade_end_date'])) {
								$param = [
									'project_id' => $sealed['project_id'],
									'project_type' => 'sealed',
									'next_refresh_time' => $next_refresh_date
								];
								$purchased_upgrade = $this->check_and_get_next_purchased_upgrade_detail($param);
								if(empty($purchased_upgrade)) {
									$next_refresh_date = null;
								}
							} 
							if(empty($valid_time_arr)) { // sealed refresh sequence only exectue if sealed project refresh sequence time not set to disabled like "00:00:00"
								$next_refresh_date = null;
							}
							if(!array_key_exists($sealed['project_id'], $next_refresh_time)) {
								$next_refresh_time[$sealed['project_id']] = [
									'project_title' => $sealed['project_title'],
									'next_refresh_time' => $next_refresh_date,
									'type' => 'sealed',
									'project_type' => $sealed['project_type'],
									'profile' => $sealed['profile_name'],
									'last_refresh_time' => $sealed['project_next_refresh_time'],
									'project_owner_id' => $sealed['project_owner_id']
								];
							}

							if(!empty($purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['real_money_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['real_money_project_upgrade_purchase_reference_id'];
							}

							$refresh_tracking = [
								'project_id' => $sealed['project_id'],
								'project_upgrade_purchase_reference_id' => empty($purchased_upgrade) ? $sealed['upgrade_purchase_id'] : $purchased_upgrade['id'],
								'project_upgrade_purchase_tracking_table_source' => empty($purchased_upgrade) ? "real_money" : $purchased_upgrade['refresh_sequence_table_source'],
								'project_last_refresh_time' => $sealed['project_next_refresh_time'],
								'project_next_refresh_time' => $next_refresh_date,
								'project_upgrade_refresh_sequence_sealed' => $this->config->item('project_upgrade_refresh_sequence_sealed')
							];
							
							
							$check_record_exists = $this->db->where(['project_id' => $sealed['project_id'],'project_upgrade_purchase_tracking_table_source'=>empty($purchased_upgrade) ? "real_money" : $purchased_upgrade['refresh_sequence_table_source'],'project_last_refresh_time'=>$sealed['project_next_refresh_time'],'project_next_refresh_time'=>$next_refresh_date])->from('sealed_projects_refresh_sequence_tracking')->count_all_results();
							if($check_record_exists == 0){
							
								$this->db->insert('sealed_projects_refresh_sequence_tracking', $refresh_tracking);
								$insert_id = $this->db->insert_id();

								// update data in projects latest project refresh sequence tracking
								$latest_project_refresh_tracking = [
									'project_last_refresh_time' => $sealed['project_next_refresh_time'],
									'project_next_refresh_time' => $next_refresh_date,
									'refresh_sequence_table_source' => 'sealed'
								];
								$this->db->update('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking, ['project_id' => $sealed['project_id']]);

								$result = $this->get_immediate_next_refresh_time_for_refreshed_project($sealed['project_id']);
								if(!empty($result)) {
									$next_refresh_time[$sealed['project_id']]['next_refresh_time'] = $result['project_next_refresh_time'];
								}
							}else{
								unset($next_refresh_time[$sealed['project_id']]);
							}		
							
					}
				}
			}	
        
        return ['next_refresh_time' => $next_refresh_time];
    }
    
    /**
     * manage sealed project refresh bonus based payment called from cron/cron_calculate_and_update_project_refresh_sequence
     */
    public function manage_sealed_project_refresh_for_bonus_based_payment($next_refresh_time) {
        $time_arr = explode(":", $this->config->item('project_upgrade_refresh_sequence_sealed'));
		$check_valid_arr = array_map('getInt', $time_arr); 
		$valid_time_arr = array_filter($check_valid_arr);
		$standard_time_arr = explode(":", $this->config->item('standard_project_refresh_sequence'));
		
		
			$this->db->select('op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.featured,op.urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code, u.profile_name');
			$this->db->select('bupt.bonus_based_project_upgrade_purchase_reference_id as upgrade_purchase_id, bupt.project_upgrade_end_date');
			$this->db->select('(select project_next_refresh_time from '.$this->db->dbprefix.'sealed_projects_refresh_sequence_tracking where project_upgrade_purchase_reference_id = bupt.bonus_based_project_upgrade_purchase_reference_id AND project_upgrade_purchase_tracking_table_source = "bonus_money"  ORDER BY id desc limit 1) as project_next_refresh_time');
			$this->db->from('proj_bonus_based_upgrades_purchase_tracking bupt');
			$this->db->join('users u', 'bupt.project_owner_id = u.user_id', 'left');
			$this->db->join('projects_open_bidding op', 'op.project_id = bupt.project_id');
			$this->db->join('counties', 'counties.id = op.county_id', 'left');
			$this->db->join('localities', 'localities.id = op.locality_id', 'left');
			$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
			$this->db->where('op.project_expiration_date >= NOW()');
			$this->db->where('bupt.project_upgrade_type', 'sealed');
			$this->db->where('bupt.project_upgrade_end_date > NOW()');
			$sealed_purchase = $this->db->get()->result_array();
			if(!empty($sealed_purchase)) {
				foreach($sealed_purchase as $sealed) {
					if( !empty($sealed['project_next_refresh_time']) && strtotime($sealed['project_next_refresh_time']) <= strtotime(date('Y-m-d H:i:s'))) {
						
							$next_refresh_date = get_next_refresh_time($sealed['project_next_refresh_time'], $time_arr);
							
							if(strtotime($next_refresh_date) >= strtotime($sealed['project_upgrade_end_date'])) {
								$param = [
									'project_id' => $sealed['project_id'],
									'project_type' => 'sealed',
									'next_refresh_time' => $next_refresh_date
								];
								$purchased_upgrade = $this->check_and_get_next_purchased_upgrade_detail($param);
								if(empty($purchased_upgrade)) {
									$next_refresh_date = null;
								}
							} 
							if(empty($valid_time_arr)) { // sealed refresh sequence only exectue if sealed project refresh sequence time not set to disabled like "00:00:00"
								$next_refresh_date = null;
							}
							if(!array_key_exists($sealed['project_id'], $next_refresh_time)) {
								$next_refresh_time[$sealed['project_id']] = [
									'project_title' => $sealed['project_title'],
									'next_refresh_time' => $next_refresh_date,
									'type' => 'sealed',
									'project_type' => $sealed['project_type'],
									'profile' => $sealed['profile_name'],
									'last_refresh_time' => $sealed['project_next_refresh_time'],
									'project_owner_id' => $sealed['project_owner_id']
								];
							}

							if(!empty($purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['real_money_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['real_money_project_upgrade_purchase_reference_id'];
							}

							$refresh_tracking = [
								'project_id' => $sealed['project_id'],
								'project_upgrade_purchase_reference_id' => empty($purchased_upgrade) ?  $sealed['upgrade_purchase_id'] : $purchased_upgrade['id'],
								'project_upgrade_purchase_tracking_table_source' => empty($purchased_upgrade) ?  'bonus_money' : $purchased_upgrade['refresh_sequence_table_source'],
								'project_last_refresh_time' => $sealed['project_next_refresh_time'],
								'project_next_refresh_time' => $next_refresh_date,
								'project_upgrade_refresh_sequence_sealed' => $this->config->item('project_upgrade_refresh_sequence_sealed')
							];
							
							$check_record_exists = $this->db->where(['project_id' => $sealed['project_id'],'project_upgrade_purchase_tracking_table_source'=>empty($purchased_upgrade) ?  'bonus_money' : $purchased_upgrade['refresh_sequence_table_source'],'project_last_refresh_time'=>$sealed['project_next_refresh_time'],'project_next_refresh_time'=>$next_refresh_date])->from('sealed_projects_refresh_sequence_tracking')->count_all_results();
							
							if($check_record_exists == 0){
								$this->db->insert('sealed_projects_refresh_sequence_tracking', $refresh_tracking);
								$insert_id = $this->db->insert_id();
								// update data in projects latest project refresh sequence tracking
								$latest_project_refresh_tracking = [
									'project_last_refresh_time' => $sealed['project_next_refresh_time'],
									'project_next_refresh_time' => $next_refresh_date,
									'refresh_sequence_table_source' => 'sealed'
								];
								$this->db->update('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking, ['project_id' => $sealed['project_id']]);

								$result = $this->get_immediate_next_refresh_time_for_refreshed_project($sealed['project_id']);
								if(!empty($result)) {
									$next_refresh_time[$sealed['project_id']]['next_refresh_time'] = $result['project_next_refresh_time'];
								}
							}else{
								unset($next_refresh_time[$sealed['project_id']]);
							
							}
							
					}
				}
			}	
        
        return ['next_refresh_time' => $next_refresh_time];

	}
	
	
	/**
     * manage sealed project refresh membership included payment called from cron/cron_calculate_and_update_project_refresh_sequence
     */
    public function manage_sealed_project_refresh_for_membership_included_payment($next_refresh_time) {
        $time_arr = explode(":", $this->config->item('project_upgrade_refresh_sequence_sealed'));
		$check_valid_arr = array_map('getInt', $time_arr); 
        $valid_time_arr = array_filter($check_valid_arr);
        $standard_time_arr = explode(":", $this->config->item('standard_project_refresh_sequence'));
			
			$this->db->select('op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.featured,op.urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code, u.profile_name');
			$this->db->select('mupt.membership_included_project_upgrade_purchase_reference_id as upgrade_purchase_id, mupt.project_upgrade_end_date');
			$this->db->select('(select project_next_refresh_time from '.$this->db->dbprefix.'sealed_projects_refresh_sequence_tracking where project_upgrade_purchase_reference_id = mupt.membership_included_project_upgrade_purchase_reference_id AND project_upgrade_purchase_tracking_table_source = "membership_included"  ORDER BY id desc limit 1) as project_next_refresh_time');
			$this->db->from('proj_membership_included_upgrades_purchase_tracking mupt');
			$this->db->join('users u', 'mupt.project_owner_id = u.user_id', 'left');
			$this->db->join('projects_open_bidding op', 'op.project_id = mupt.project_id');
			$this->db->join('counties', 'counties.id = op.county_id', 'left');
			$this->db->join('localities', 'localities.id = op.locality_id', 'left');
			$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
			$this->db->where('op.project_expiration_date >= NOW()');
			$this->db->where('mupt.project_upgrade_type', 'sealed');
			$this->db->where('mupt.project_upgrade_end_date > NOW()');
			$sealed_purchase = $this->db->get()->result_array();
			
			
			
			if(!empty($sealed_purchase)) {
				foreach($sealed_purchase as $sealed) {
					if( !empty($sealed['project_next_refresh_time']) && strtotime($sealed['project_next_refresh_time']) <= strtotime(date('Y-m-d H:i:s'))) {
							
							$next_refresh_date = get_next_refresh_time($sealed['project_next_refresh_time'], $time_arr);
						
							
							
							if(strtotime($next_refresh_date) >= strtotime($sealed['project_upgrade_end_date'])) {
								$param = [
									'project_id' => $sealed['project_id'],
									'project_type' => 'sealed',
									'next_refresh_time' => $next_refresh_date
								];
								$purchased_upgrade = $this->check_and_get_next_purchased_upgrade_detail($param);
								if(empty($purchased_upgrade)) {
									$next_refresh_date = null;
								}
							} 
							if(empty($valid_time_arr)) { // sealed refresh sequence only exectue if sealed project refresh sequence time not set to disabled like "00:00:00"
								$next_refresh_date = null;
							}
							if(!array_key_exists($sealed['project_id'], $next_refresh_time)) {
								$next_refresh_time[$sealed['project_id']] = [
									'project_title' => $sealed['project_title'],
									'next_refresh_time' => $next_refresh_date,
									'type' => 'sealed',
									'project_type' => $sealed['project_type'],
									'profile' => $sealed['profile_name'],
									'last_refresh_time' => $sealed['project_next_refresh_time'],
									'project_owner_id' => $sealed['project_owner_id']
								];
							}

							if(!empty($purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['membership_included_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['bonus_based_project_upgrade_purchase_reference_id'];
							} else if(!empty($purchased_upgrade['real_money_project_upgrade_purchase_reference_id'])) {
								$purchased_upgrade['id'] = $purchased_upgrade['real_money_project_upgrade_purchase_reference_id'];
							}

							$refresh_tracking = [
								'project_id' => $sealed['project_id'],
								'project_upgrade_purchase_reference_id' => empty($purchased_upgrade) ?  $sealed['upgrade_purchase_id'] : $purchased_upgrade['id'],
								'project_upgrade_purchase_tracking_table_source' => empty($purchased_upgrade) ?'membership_included' : $purchased_upgrade['refresh_sequence_table_source'],
								'project_last_refresh_time' => $sealed['project_next_refresh_time'],
								'project_next_refresh_time' => $next_refresh_date,
								'project_upgrade_refresh_sequence_sealed' => $this->config->item('project_upgrade_refresh_sequence_sealed')
							];
							
						
							$check_record_exists = $this->db->where(['project_id' => $sealed['project_id'],'project_upgrade_purchase_tracking_table_source'=>empty($purchased_upgrade) ?  'membership_included' : $purchased_upgrade['refresh_sequence_table_source'],'project_last_refresh_time'=>$sealed['project_next_refresh_time'],'project_next_refresh_time'=>$next_refresh_date])->from('sealed_projects_refresh_sequence_tracking')->count_all_results();
							if($check_record_exists == 0){
								$this->db->insert('sealed_projects_refresh_sequence_tracking', $refresh_tracking);
								$insert_id = $this->db->insert_id();
								// update data in projects latest project refresh sequence tracking
								$latest_project_refresh_tracking = [
									'project_last_refresh_time' => $sealed['project_next_refresh_time'],
									'project_next_refresh_time' => $next_refresh_date,
									'refresh_sequence_table_source' => 'sealed'
								];
								$this->db->update('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking, ['project_id' => $sealed['project_id']]);

								$result = $this->get_immediate_next_refresh_time_for_refreshed_project($sealed['project_id']);
								if(!empty($result)) {
									$next_refresh_time[$sealed['project_id']]['next_refresh_time'] = $result['project_next_refresh_time'];
								}
							}else{
								unset($next_refresh_time[$sealed['project_id']]);
							
							}
							
					}
				}
			}	
        
        return ['next_refresh_time' => $next_refresh_time ];
    }
	
	
	
	/**
	 * this function will run at prolong time only and once PO hits prolong you check whether there is an available refresh sequence for that specific project or not
	 * available refresh sequence means it has a next refresh date available
	 * if there is a valid refresh sequence than at prolong time there is done only one insert into purchase tracking table and the refresh sequence will be continue considering current cycle without doing any refresh of project in projects listings rankings
	 * if there is no valid refresh sequence than at prolong time there is done insert in both refresh tracking and purchase tracking tables, and a new refresh sequence is created for that specific project, together with refreshing project position in rankings 
	 */
	public function get_current_available_project_upgrade_details($param) {
		$upgrade_tables = [
			'membership_included' => 'proj_membership_included_upgrades_purchase_tracking',
			'bonus_money' => 'proj_bonus_based_upgrades_purchase_tracking',
			'real_money' => 'proj_real_money_upgrades_purchase_tracking'
		];
		$refresh_sequence_tables = [
			'featured' => 'featured_projects_refresh_sequence_tracking',
			'urgent' => 'urgent_projects_refresh_sequence_tracking',
			'sealed' => 'sealed_projects_refresh_sequence_tracking'
		];
		$result = [];
		foreach($upgrade_tables as $key => $val) {
			$this->db->select('*');
			$this->db->from($val);
			$this->db->where('project_id', $param['project_id']);
			$this->db->where('project_upgrade_type', $param['project_upgrade_type']);
			$this->db->where('project_upgrade_end_date >= NOW()');
			$this->db->limit(1);
			$row = $this->db->get()->row_array();
			if(!empty($row)) {
				$result = $row;
				$result['refresh_sequence_table'] = $refresh_sequence_tables[$param['project_upgrade_type']];
				$result['refresh_sequence_table_source'] = $key;
				break;
			}
		}
		return $result;
	}
	/**
	 * This method is used to get such refresh sequence records whoes next refresh time set to null
	*/
	public function get_currently_running_upgrade_refresh_sequence_detail($project_upgrade_purchase_tracking_id, $param) {
		$this->db->select('*');
		$this->db->from($param['refresh_sequence_table']);
		$this->db->where('project_upgrade_purchase_reference_id', $project_upgrade_purchase_tracking_id);
		$this->db->where('project_upgrade_purchase_tracking_table_source', $param['refresh_sequence_table_source']);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}
	
	/**
	* This function is used to save user upgrade purchase tracking  and refrence sequences and deduct the bonus balance and account balance. but its not including the membership included upgrades. It always considered the bonus/real money for upgrades.It is using when po upgrade/prolong/edit the project. O
	*/
	
	public function user_project_upgrade_purchase_refresh_sequence_tracking_membership_exclude_save($project_data,$user_id){
	
		$user_selected_upgrades = array();
		$upgrade_counter = 0;
		if(!empty($project_data['featured']) && $project_data['featured'] == 'Y' ){
			//$upgrade_type_featured = 'Y';
			$user_selected_upgrades[$upgrade_counter][] = 'featured';
			$user_selected_upgrades[$upgrade_counter][] = $project_data['featured_upgrade_end_date'];
			$user_selected_upgrades[$upgrade_counter][] = $project_data['project_upgrade_featured_prolong_status'];
			$user_selected_upgrades[$upgrade_counter][] = $project_data['project_upgrade_start_date'];
			$upgrade_counter++;
		}
		if(!empty($project_data['urgent']) && $project_data['urgent'] == 'Y'){
			//$upgrade_type_urgent = 'Y';
			//$user_selected_upgrades[] = 'urgent';
			$user_selected_upgrades[$upgrade_counter][] = 'urgent';
			$user_selected_upgrades[$upgrade_counter][] = $project_data['urgent_upgrade_end_date'];
			$user_selected_upgrades[$upgrade_counter][] = $project_data['project_upgrade_urgent_prolong_status'];
			$user_selected_upgrades[$upgrade_counter][] = $project_data['project_upgrade_start_date'];
			$upgrade_counter++;
			
		}
		if(!empty($project_data['sealed']) && $project_data['sealed'] == 'Y'){
			//$upgrade_type_sealed = 'Y';
			$user_selected_upgrades[$upgrade_counter][] = 'sealed';
			$upgrade_counter++;
		}
		if(!empty($project_data['hidden']) && $project_data['hidden'] == 'Y'){
			//$upgrade_type_hidden = 'Y';
			$user_selected_upgrades[$upgrade_counter][] = 'hidden';
			$upgrade_counter++;
		}
		
		$project_id = $project_data['project_id'];
		$project_title = trim($project_data['project_title']);
		$project_type = $project_data['project_type'];
		
	
		if(!empty($user_selected_upgrades)){
			$refresh_sequence_tables = [
				'featured' => 'featured_projects_refresh_sequence_tracking',
				'urgent' => 'urgent_projects_refresh_sequence_tracking',
				'sealed' => 'sealed_projects_refresh_sequence_tracking'
			];
			
			foreach( $user_selected_upgrades as $value){
			
				$user_detail = $this->db->get_where('users_details', ['user_id' => $user_id])->row_array();
				$user_membership_plan_detail = $this->db->get_where('membership_plans', ['id' => $user_detail['current_membership_plan_id']])->row_array();
				
				
				$project_upgrade_type =  $value[0];
				$project_upgrade_price =  $this->config->item('project_upgrade_price_'.$value[0]);
				$project_upgrade_availability =  $this->config->item('project_upgrade_availability_'.$value[0]);
				$project_upgrade_refresh_sequence =  $this->config->item('project_upgrade_refresh_sequence_'.$value[0]);
				$project_upgrade_end =  $this->config->item('project_upgrade_availability_'.$value[0]);
				if(empty($value[1])){
				
					$time_arr = explode(':', $project_upgrade_end);
					$value[1] = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
				}
				 	
				
				$upgrades_purchase_data = [
					'project_id' => $project_id,
					'project_owner_id' => $user_id,
					'project_owner_membership_plan_id' => $user_detail['current_membership_plan_id'],
					'project_upgrade_purchase_date' => date('Y-m-d H:i:s'),
					'project_upgrade_type' => $project_upgrade_type,
					'project_upgrade_availability_length' => $project_upgrade_availability,
					'project_upgrade_start_date' => $value[3],
					'project_upgrade_end_date' => $value[1]
				];
				
				$upgrade_purchase_tracking_data = [
					'project_id' => $project_id,
					'project_owner_id' => $user_id,
					'project_upgrade_type' => $project_upgrade_type,
					'project_upgrade_purchase_date' => date('Y-m-d H:i:s'),
				];

				if($value[2] == '1') {
					$upgrades_purchase_data['purchase_type'] = 'upgrade_availability_prolong';
					$upgrade_purchase_tracking_data['project_upgrade_purchase_type'] = 'upgrade_availability_prolong';
				}
				
				$time_arr = explode(':',$project_upgrade_refresh_sequence);
				$check_valid_arr = array_map('getInt', $time_arr); 
				$valid_time_arr = array_filter($check_valid_arr);
				// insert into projects_refresh_sequence_tracking table
				$refresh_sequence_data = [
					'project_id' => $project_id,
					'project_last_refresh_time' => null
				];
				
				################ insert message in activity log message for upgrade ########
				$upgrade_price = $this->config->item('project_upgrade_price_'.$value[0])." ". CURRENCY;
				$upgrade_price_for_log_msg = str_replace(".00","",number_format($this->config->item('project_upgrade_price_'.$value[0]),  2, '.', ' '))." ". CURRENCY;
				
				$project_upgrade_availability_array = explode(':',$project_upgrade_availability);
				$total_project_upgrade_availability_seconds = ($project_upgrade_availability_array[0] * 3600)+($project_upgrade_availability_array[1] * 60)+$project_upgrade_availability_array[2];
				
				$upgrade_expiration_time = date(DATE_TIME_FORMAT,strtotime($value[1]));
				$upgrade_expiration_time_words = trim(secondsToWords($total_project_upgrade_availability_seconds));
				$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				$upgrade_activity_log_message = '';
				
				if($project_upgrade_type == 'featured'){
					if($value[2] == '0'){
						if($project_type == 'fulltime'){
							$upgrade_activity_log_message = $this->config->item('fulltime_project_featured_upgrade_user_activity_log_displayed_message_sent_to_po');
						}else{
							$upgrade_activity_log_message = $this->config->item('project_featured_upgrade_user_activity_log_displayed_message_sent_to_po');
						}
						$upgrade_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{project_featured_upgrade_price}','{project_featured_upgrade_expiration_date}'),array($project_url_link,htmlspecialchars_decode($project_title),$upgrade_price_for_log_msg,$upgrade_expiration_time),$upgrade_activity_log_message);
						
						
						
						
					}else{
						if($project_type == 'fulltime'){
							$upgrade_activity_log_message = $this->config->item('fulltime_project_featured_upgrade_prolong_user_activity_log_displayed_message_sent_to_po');
						}else{
							$upgrade_activity_log_message = $this->config->item('project_featured_upgrade_prolong_user_activity_log_displayed_message_sent_to_po');
						}
						//$upgrade_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{project_upgrade_availability_featured}','{project_upgrade_price_featured}','{project_featured_prolong_upgrade_next_expiration_date}'),array($project_url_link,htmlspecialchars_decode($project_title),$upgrade_expiration_time_words,$upgrade_price,$upgrade_expiration_time),$upgrade_activity_log_message);
						
						$upgrade_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{project_featured_upgrade_prolong_next_expiration_date}','{project_featured_upgrade_price}','{project_featured_upgrade_prolong_availability}'),array($project_url_link,htmlspecialchars_decode($project_title),$upgrade_expiration_time,$upgrade_price_for_log_msg,$upgrade_expiration_time_words),$upgrade_activity_log_message);
						
					}
				} else if($project_upgrade_type == 'urgent'){
					if($value[2] == '0'){
						if($project_type == 'fulltime'){
							$upgrade_activity_log_message = $this->config->item('fulltime_project_urgent_upgrade_user_activity_log_displayed_message_sent_to_po');
						}else{
						
							$upgrade_activity_log_message = $this->config->item('project_urgent_upgrade_user_activity_log_displayed_message_sent_to_po');
						}
						$upgrade_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{project_urgent_upgrade_price}','{project_urgent_upgrade_expiration_date}'),array($project_url_link,htmlspecialchars_decode($project_title),$upgrade_price_for_log_msg,$upgrade_expiration_time),$upgrade_activity_log_message);
					}else{
						if($project_type == 'fulltime'){
							$upgrade_activity_log_message = $this->config->item('fulltime_project_urgent_upgrade_prolong_user_activity_log_displayed_message_sent_to_po');
						}else{
							$upgrade_activity_log_message = $this->config->item('project_urgent_upgrade_prolong_user_activity_log_displayed_message_sent_to_po');
						}
						
						$upgrade_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{project_urgent_upgrade_prolong_next_expiration_date}','{project_urgent_upgrade_price}','{project_urgent_upgrade_prolong_availability}'),array($project_url_link,htmlspecialchars_decode($project_title),$upgrade_expiration_time,$upgrade_price_for_log_msg,$upgrade_expiration_time_words),$upgrade_activity_log_message);
					}
				}
				if($project_upgrade_type == 'featured' || $project_upgrade_type == 'urgent'){
					user_display_log($upgrade_activity_log_message);
				}
				################ insert message in activity log message for upgrade ########
				
				$vat_percentage = $this->config->item('vat_percentage');
					
				$remaining_upgrade_amount_after_deduction = 0;
				$deduction_amount = 0;
				$remaining_signup_bonus_balance =0;
				$remaining_bonus_balance =0;
				$remaining_user_account_balance =0;
				
				if( $user_detail['signup_bonus_balance'] >= $project_upgrade_price ){
					$deduction_amount = $project_upgrade_price;
					$remaining_signup_bonus_balance =  $user_detail['signup_bonus_balance'] - $deduction_amount;
				} else{
					$remaining_upgrade_amount_after_deduction = $project_upgrade_price - $user_detail['signup_bonus_balance'];
					$deduction_amount = $user_detail['signup_bonus_balance'];
					$remaining_signup_bonus_balance = 0;
				}
				$upgrades_purchase_data['project_upgrade_purchase_value'] = $deduction_amount;
				$param = [
					'project_id' => $project_id,
					'project_upgrade_type' => $project_upgrade_type
				];
				$current_running_upgrade = $this->get_current_available_project_upgrade_details($param);
				$refresh_ins_flag = false;
				if(!empty($current_running_upgrade)) {

					if(!empty($current_running_upgrade['membership_included_project_upgrade_purchase_reference_id'])) {
						$current_running_upgrade['id'] = $current_running_upgrade['membership_included_project_upgrade_purchase_reference_id'];
					} else if(!empty($current_running_upgrade['real_money_project_upgrade_purchase_reference_id'])) {
						$current_running_upgrade['id'] = $current_running_upgrade['real_money_project_upgrade_purchase_reference_id'];
					} else if(!empty($current_running_upgrade['bonus_based_project_upgrade_purchase_reference_id'])) {
						$current_running_upgrade['id'] = $current_running_upgrade['bonus_based_project_upgrade_purchase_reference_id'];
					}

					$refresh_sequence = $this->get_currently_running_upgrade_refresh_sequence_detail($current_running_upgrade['id'], $current_running_upgrade);
				}
				if(!empty($refresh_sequence)) {
					if($refresh_sequence['project_next_refresh_time'] == null) {
						$refresh_ins_flag = true;
					}
				} else {
					$refresh_ins_flag = true;
				}
				

				if(floatval($user_detail['signup_bonus_balance']) != 0){

					$reference_id = PROJECT_BONUS_BASED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
					$latest_released_escrow_data = $this->db->from('proj_bonus_based_upgrades_purchase_tracking')->where('bonus_based_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
					$digits = $this->config->item('reference_id_digits_limit');
					if(empty($latest_released_escrow_data)) {
						$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
					} else {
						$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['bonus_based_project_upgrade_purchase_reference_id']);
						$exclude_inital_number = ltrim($exclude_inital_number, '0');
						$exclude_inital_number = !empty($exclude_inital_number) ? (int)$exclude_inital_number + 1 : 1;
						$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
					}
					$vat_excluded_amount = ($deduction_amount * 100)/ ($vat_percentage+100);
					$vat_amount = $deduction_amount - $vat_excluded_amount;
		
					$upgrades_purchase_data['bonus_based_project_upgrade_purchase_reference_id'] = $reference_id;
					$upgrades_purchase_data['vat_percentage_value'] = $vat_percentage;
					$upgrades_purchase_data['bonus_based_project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
					$upgrades_purchase_data['bonus_based_project_upgrade_vat_amount_value'] = $vat_amount;

					$this->db->insert('proj_bonus_based_upgrades_purchase_tracking', $upgrades_purchase_data);


					########################## insert data into projects_upgrades_purchases_tracking table ##########################################
					$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $reference_id;
					$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'bonus_based';
					$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $deduction_amount;
					$upgrade_purchase_tracking_data['vat_percentage_value'] = $vat_percentage;
					$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
					$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
					$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $vat_amount;

					$po_lvl1_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl1_user_id($user_id);
					$po_lvl2_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl2_user_id($user_id);

					$po_lv1_percentage_value = 0;
					$po_lv2_percentage_value = 0;

					if(!empty($po_lvl1_referal_data)) {
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_id'] = $po_lvl1_referal_data['user_id'];
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_membership_id'] = $po_lvl1_referal_data['id'];
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_considered_percentage'] = $po_lvl1_referal_data['lvl1_percentage'];
						// $po_lv1_percentage_value = ($project_upgrade_purchase_value_excl_vat * $po_lvl1_referal_data['lvl1_percentage'] ) / 100 ;
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_affiliate_generated_income'] = $po_lv1_percentage_value;
					}

					if(!empty($po_lvl2_referal_data)) {
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_id'] = $po_lvl2_referal_data['user_id'];
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_membership_id'] = $po_lvl2_referal_data['id'];
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_considered_percentage'] = $po_lvl2_referal_data['lvl2_percentage'];
						// $po_lv2_percentage_value = ($project_upgrade_purchase_value_excl_vat * $po_lvl2_referal_data['lvl2_percentage'] ) / 100 ;
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_affiliate_generated_income'] = $po_lv2_percentage_value;
					}

					$upgrade_purchase_tracking_data['project_upgrade_purchase_net_value'] = $project_upgrade_purchase_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value);
					$this->db->insert('projects_upgrades_purchases_tracking', $upgrade_purchase_tracking_data);
					############################################ End ##############################################################################

					$last_insert_id = $this->db->insert_id();
					$refresh_sequence_data['project_upgrade_purchase_reference_id'] =  $reference_id;
					$refresh_sequence_data['project_upgrade_purchase_tracking_table_source'] = 'bonus_money';
					$this->db->update('users_details', ['signup_bonus_balance' => $remaining_signup_bonus_balance], ['id' => $user_detail['id']]);
				}
				if($remaining_upgrade_amount_after_deduction != 0  ){
					if( $user_detail['bonus_balance'] >= $remaining_upgrade_amount_after_deduction ){
						$deduction_amount = $remaining_upgrade_amount_after_deduction;
						$remaining_bonus_balance = $user_detail['bonus_balance'] -$remaining_upgrade_amount_after_deduction;
						$remaining_upgrade_amount_after_deduction = 0;
					} else{
						$deduction_amount =  $user_detail['bonus_balance'];
						$remaining_upgrade_amount_after_deduction = $remaining_upgrade_amount_after_deduction - $user_detail['bonus_balance'];
						$remaining_bonus_balance = 0;
					}
					if(floatval($user_detail['bonus_balance']) != 0){
						
						$upgrades_purchase_data['project_upgrade_purchase_value'] = $deduction_amount;

						$reference_id = PROJECT_BONUS_BASED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
						$latest_released_escrow_data = $this->db->from('proj_bonus_based_upgrades_purchase_tracking')->where('bonus_based_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
						$digits = $this->config->item('reference_id_digits_limit');
						if(empty($latest_released_escrow_data)) {
							$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
						} else {
							$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['bonus_based_project_upgrade_purchase_reference_id']);
							$exclude_inital_number = ltrim($exclude_inital_number, '0');
							$exclude_inital_number = !empty($exclude_inital_number) ? (int)$exclude_inital_number + 1 : 1;
							$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
						}
						$vat_excluded_amount = ($deduction_amount * 100)/ ($vat_percentage+100);
						$vat_amount = $deduction_amount - $vat_excluded_amount;
						
						$upgrades_purchase_data['bonus_based_project_upgrade_purchase_reference_id'] = $reference_id;
						$upgrades_purchase_data['vat_percentage_value'] = $vat_percentage;
						$upgrades_purchase_data['bonus_based_project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
						$upgrades_purchase_data['bonus_based_project_upgrade_vat_amount_value'] = $vat_amount;

						$this->db->insert('proj_bonus_based_upgrades_purchase_tracking', $upgrades_purchase_data);

						########################## insert data into projects_upgrades_purchases_tracking table ##########################################
						$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $reference_id;
						$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'bonus_based';
						$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $deduction_amount;
						$upgrade_purchase_tracking_data['vat_percentage_value'] = $vat_percentage;
						$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
						$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
						$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $vat_amount;

						$po_lvl1_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl1_user_id($user_id);
						$po_lvl2_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl2_user_id($user_id);

						$po_lv1_percentage_value = 0;
						$po_lv2_percentage_value = 0;

						if(!empty($po_lvl1_referal_data)) {
							$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_id'] = $po_lvl1_referal_data['user_id'];
							$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_membership_id'] = $po_lvl1_referal_data['id'];
							$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_considered_percentage'] = $po_lvl1_referal_data['lvl1_percentage'];
							// $po_lv1_percentage_value = ($project_upgrade_purchase_value_excl_vat * $po_lvl1_referal_data['lvl1_percentage'] ) / 100 ;
							$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_affiliate_generated_income'] = $po_lv1_percentage_value;
						}

						if(!empty($po_lvl2_referal_data)) {
							$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_id'] = $po_lvl2_referal_data['user_id'];
							$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_membership_id'] = $po_lvl2_referal_data['id'];
							$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_considered_percentage'] = $po_lvl2_referal_data['lvl2_percentage'];
							// $po_lv2_percentage_value = ($project_upgrade_purchase_value_excl_vat * $po_lvl2_referal_data['lvl2_percentage'] ) / 100 ;
							$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_affiliate_generated_income'] = $po_lv2_percentage_value;
						}

						$upgrade_purchase_tracking_data['project_upgrade_purchase_net_value'] = $project_upgrade_purchase_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value);
						$this->db->insert('projects_upgrades_purchases_tracking', $upgrade_purchase_tracking_data);
						############################################ End ##############################################################################

						$last_insert_id = $this->db->insert_id();
						$refresh_sequence_data['project_upgrade_purchase_reference_id'] =  $reference_id;
						$refresh_sequence_data['project_upgrade_purchase_tracking_table_source'] = 'bonus_money';
						
						$this->db->update('users_details', ['bonus_balance' => $remaining_bonus_balance], ['id' => $user_detail['id']]);
					}	
					if($remaining_upgrade_amount_after_deduction != 0){
						unset($upgrades_purchase_data['bonus_based_project_upgrade_purchase_reference_id']);
						unset($upgrades_purchase_data['bonus_based_project_upgrade_purchase_value_excl_vat']);
						unset($upgrades_purchase_data['bonus_based_project_upgrade_vat_amount_value']);


						if( $user_detail['user_account_balance'] >= $remaining_upgrade_amount_after_deduction ){
							$deduction_amount = $remaining_upgrade_amount_after_deduction;
							$remaining_user_account_balance = $user_detail['user_account_balance'] - $deduction_amount;
							
						} 
						
						$upgrades_purchase_data['project_upgrade_purchase_value'] = $deduction_amount;

						$reference_id = PROJECT_REAL_MONEY_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
						$latest_released_escrow_data = $this->db->from('proj_real_money_upgrades_purchase_tracking')->where('real_money_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
						$digits = $this->config->item('reference_id_digits_limit');
						if(empty($latest_released_escrow_data)) {
							$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
						} else {
							$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['real_money_project_upgrade_purchase_reference_id']);
							$exclude_inital_number = ltrim($exclude_inital_number, '0');
							$exclude_inital_number = !empty($exclude_inital_number) ? (int)$exclude_inital_number + 1 : 1;
							$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
						}
						$vat_excluded_amount = ($deduction_amount * 100)/ ($vat_percentage+100);
						$vat_amount = $deduction_amount - $vat_excluded_amount;
						
						$upgrades_purchase_data['real_money_project_upgrade_purchase_reference_id'] = $reference_id;
						$upgrades_purchase_data['vat_percentage_value'] = $vat_percentage;
						$upgrades_purchase_data['real_money_project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
						$upgrades_purchase_data['real_money_project_upgrade_purchase_vat_amount_value'] = $vat_amount;

						$this->db->insert('proj_real_money_upgrades_purchase_tracking', $upgrades_purchase_data);


						########################## insert data into projects_upgrades_purchases_tracking table ##########################################
						$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $reference_id;
						$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'real_money';
						$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $deduction_amount;
						$upgrade_purchase_tracking_data['vat_percentage_value'] = $vat_percentage;
						$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
						$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
						$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $vat_amount;

						$po_lvl1_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl1_user_id($user_id);
						$po_lvl2_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl2_user_id($user_id);
						
						$po_lv1_percentage_value = 0;
						$po_lv2_percentage_value = 0;

						$ts = strtotime(date('Y-m-d'));
						$start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
						$week_start_date = date('Y-m-d', $start);

						if(!empty($po_lvl1_referal_data)) {
							$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_id'] = $po_lvl1_referal_data['user_id'];
							$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_membership_id'] = $po_lvl1_referal_data['id'];
							$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_considered_percentage'] = $po_lvl1_referal_data['lvl1_percentage'];
							$po_lv1_percentage_value = ($project_upgrade_purchase_value_excl_vat * $po_lvl1_referal_data['lvl1_percentage'] ) / 100 ;
							$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_affiliate_generated_income'] = $po_lv1_percentage_value;

							$referral_earnings = [
								'user_id' => $po_lvl1_referal_data['user_id'],
								'referral_earning_date' => date('Y-m-d H:i:s'),
								'referral_earning_source_reference_id' => $upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'],
								'referral_earnig_value' => $po_lv1_percentage_value,
								'referral_earning_lvl_source' => 'lvl1',
								'referee_user_id' => $user_id
							];
							$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

							$daily_earnings = [
								'user_id' => $po_lvl1_referal_data['user_id'],
								'referral_earnings_date' => date('Y-m-d H:i:s'),
								'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
							];
							$this->Escrow_model->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

							$weekly_earnings = [
								'user_id' => $po_lvl1_referal_data['user_id'],
								'referral_earnings_week_start_date' => $week_start_date,
								'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
							];
							$this->Escrow_model->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);

							$monthly_earnings = [
								'user_id' => $po_lvl1_referal_data['user_id'],
								'referral_earnings_month' => date('Y-m-d'),
								'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
							];
							$this->Escrow_model->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);

							$total_earnings = [
								'user_id' => $po_lvl1_referal_data['user_id'],
								'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
							];
							$this->Escrow_model->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);
						}

						if(!empty($po_lvl2_referal_data)) {
							$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_id'] = $po_lvl2_referal_data['user_id'];
							$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_membership_id'] = $po_lvl2_referal_data['id'];
							$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_considered_percentage'] = $po_lvl2_referal_data['lvl2_percentage'];
							$po_lv2_percentage_value = ($project_upgrade_purchase_value_excl_vat * $po_lvl2_referal_data['lvl2_percentage'] ) / 100 ;
							$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_affiliate_generated_income'] = $po_lv2_percentage_value;

							$referral_earnings = [
								'user_id' => $po_lvl2_referal_data['user_id'],
								'referral_earning_date' => date('Y-m-d H:i:s'),
								'referral_earning_source_reference_id' => $upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'],
								'referral_earnig_value' => $po_lv2_percentage_value,
								'referral_earning_lvl_source' => 'lvl2',
								'referee_user_id' => $user_id
							];
							$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

							$daily_earnings = [
								'user_id' => $po_lvl2_referal_data['user_id'],
								'referral_earnings_date' => date('Y-m-d H:i:s'),
								'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
							];
							$this->Escrow_model->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

							$weekly_earnings = [
								'user_id' => $po_lvl2_referal_data['user_id'],
								'referral_earnings_week_start_date' => $week_start_date,
								'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
							];
							$this->Escrow_model->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);

							$monthly_earnings = [
								'user_id' => $po_lvl2_referal_data['user_id'],
								'referral_earnings_month' => date('Y-m-d'),
								'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
							];
							$this->Escrow_model->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);
							
							$total_earnings = [
								'user_id' => $po_lvl2_referal_data['user_id'],
								'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
							];
							$this->Escrow_model->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);
						}

						$upgrade_purchase_tracking_data['project_upgrade_purchase_net_value'] = $project_upgrade_purchase_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value);
						$this->db->insert('projects_upgrades_purchases_tracking', $upgrade_purchase_tracking_data);
						############################################ End ##############################################################################

						$last_insert_id = $this->db->insert_id();
						$refresh_sequence_data['project_upgrade_purchase_reference_id'] =  $reference_id;
						$refresh_sequence_data['project_upgrade_purchase_tracking_table_source'] = 'real_money';
						
						$amount = $user_detail['signup_bonus_balance'] - $project_upgrade_price;
						$this->db->update('users_details', ['user_account_balance' => $remaining_user_account_balance], ['id' => $user_detail['id']]);
					}
				}
				
				if($refresh_ins_flag) {
					if($value[0] != 'hidden'){
						if(!empty($valid_time_arr)) {
							
							$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
							if($project_upgrade_type == 'featured'){
								$refresh_sequence_data['project_upgrade_refresh_sequence_featured'] = $this->config->item('project_upgrade_refresh_sequence_featured');
								$time_arr = explode(':',$this->config->item('project_upgrade_refresh_sequence_featured'));	
								$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
								$refresh_sequence_data['project_next_refresh_time'] = $next_refresh_date;
							}
							if($project_upgrade_type == 'urgent'){
								$refresh_sequence_data['project_upgrade_refresh_sequence_urgent'] = $this->config->item('project_upgrade_refresh_sequence_urgent');
								$time_arr = explode(':',$this->config->item('project_upgrade_refresh_sequence_urgent'));	
								$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
								$refresh_sequence_data['project_next_refresh_time'] = $next_refresh_date;
							}
							$this->db->insert($refresh_sequence_tables[$project_upgrade_type], $refresh_sequence_data);
							// update data in projects latest project refresh sequence tracking
							$latest_project_refresh_tracking = [
								'project_id' => $project_id,
								'project_last_refresh_time' => date('Y-m-d H:i:s'),
								'project_next_refresh_time' => $next_refresh_date,
								'refresh_sequence_table_source' => $project_upgrade_type
							];
							$this->db->update('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking, ['project_id' => $project_id]);
						}
					}
				}
			}	
		}
	} 

	/*
	 * This method is used to update memebership included purchase tracking, bonus included purchase tracking, upgrade purchase tracking tables project_upgrade_start_date and project_upgrade_end_date column
	 * also make entry into there respected refresh sequence table when cron auto approved the project for open for bidding from awaiting moderation status
	 */
	public function update_upgrade_purchase_tracking_with_related_refresh_sequence_tracking($project_id) {
		$refresh_sequence_tables = [
			'featured' => 'featured_projects_refresh_sequence_tracking',
			'urgent' => 'urgent_projects_refresh_sequence_tracking',
			'sealed' => 'sealed_projects_refresh_sequence_tracking'
		];
		$refresh_sequence_duration = [
			'featured' => 'project_upgrade_refresh_sequence_featured',
			'urgent' => 'project_upgrade_refresh_sequence_urgent',
			'sealed' => 'project_upgrade_refresh_sequence_sealed'
		];
		$refresh_sequence_tracking_arr = [];
		// Get membership included upgrade purchase tracking data
		$vat_percentage = $this->config->item('vat_percentage');
		// $membership_included_upgrade_data = $this->db->get_where('projects_awm_membership_included_upgrades_purchase_tracking', ['project_id' => $project_id ])->result_array();

		$this->db->select('mupt.*, op.hidden');
		$this->db->from('projects_awm_membership_included_upgrades_purchase_tracking mupt');
		$this->db->join('projects_open_bidding op', 'op.project_id = mupt.project_id');
		$this->db->where('mupt.project_id' , $project_id);
		$membership_included_upgrade_data = $this->db->get()->result_array();

		if(!empty($membership_included_upgrade_data)) {
			foreach($membership_included_upgrade_data as $val) {
				$time_arr = explode(':', $val['project_upgrade_availability_length']);
				$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
				$upgrades_purchase_data = $val;
				$upgrades_purchase_data['project_upgrade_start_date'] = date('Y-m-d H:i:s');
				$upgrades_purchase_data['project_upgrade_end_date'] = $upgrade_end_date;
				unset($upgrades_purchase_data['id']);
				unset($upgrades_purchase_data['hidden']);
				$project_upgrade_price = $upgrades_purchase_data['project_upgrade_purchase_value'];

				$reference_id = PROJECT_MEMBERSHIP_INCLUDED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
				$latest_released_escrow_data = $this->db->from('proj_membership_included_upgrades_purchase_tracking')->where('membership_included_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
				$digits = $this->config->item('reference_id_digits_limit');
				if(empty($latest_released_escrow_data)) {
					$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
				} else {
					$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['membership_included_project_upgrade_purchase_reference_id']);
					$exclude_inital_number = ltrim($exclude_inital_number, '0');
					$exclude_inital_number = !empty($exclude_inital_number) ? (int)$exclude_inital_number + 1 : 1;
					$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
				}
				$vat_excluded_amount = ($project_upgrade_price * 100)/ ($vat_percentage+100);
				$vat_amount = $project_upgrade_price - $vat_excluded_amount;

				$upgrades_purchase_data['membership_included_project_upgrade_purchase_reference_id'] = $reference_id;
				$upgrades_purchase_data['vat_percentage_value'] = $vat_percentage;
				$upgrades_purchase_data['membership_included_project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
				$upgrades_purchase_data['membership_included_project_upgrade_purchase_vat_amount_value'] = $vat_amount;
				
				$this->db->insert('proj_membership_included_upgrades_purchase_tracking', $upgrades_purchase_data);
				
				########################## insert data into projects_upgrades_purchases_tracking table ##########################################
				$upgrade_purchase_tracking_data = [
					'project_id' => $upgrades_purchase_data['project_id'],
					'project_owner_id' => $upgrades_purchase_data['project_owner_id'],
					'project_upgrade_type' => $upgrades_purchase_data['project_upgrade_type'],
					'project_upgrade_purchase_date' => $upgrades_purchase_data['project_upgrade_purchase_date']
				];
				
				$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $upgrades_purchase_data['membership_included_project_upgrade_purchase_reference_id'];
				$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'membership_included';
				$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $upgrades_purchase_data['project_upgrade_purchase_value'];
				$upgrade_purchase_tracking_data['vat_percentage_value'] = $upgrades_purchase_data['vat_percentage_value'];
				$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $upgrades_purchase_data['membership_included_project_upgrade_purchase_value_excl_vat'];
				$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
				$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $upgrades_purchase_data['membership_included_project_upgrade_purchase_vat_amount_value'];


				$po_lvl1_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl1_user_id($upgrades_purchase_data['project_owner_id']);
				$po_lvl2_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl2_user_id($upgrades_purchase_data['project_owner_id']);
				$po_lv1_percentage_value = 0;
				$po_lv2_percentage_value = 0;

				if(!empty($po_lvl1_referal_data)) {
					$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_id'] = $po_lvl1_referal_data['user_id'];
					$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_membership_id'] = $po_lvl1_referal_data['id'];
					$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_considered_percentage'] = $po_lvl1_referal_data['lvl1_percentage'];
					// $po_lv1_percentage_value = ($project_upgrade_purchase_value_excl_vat * $po_lvl1_referal_data['lvl1_percentage'] ) / 100 ;
					$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_affiliate_generated_income'] = $po_lv1_percentage_value;
				}

				if(!empty($po_lvl2_referal_data)) {
					$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_id'] = $po_lvl2_referal_data['user_id'];
					$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_membership_id'] = $po_lvl2_referal_data['id'];
					$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_considered_percentage'] = $po_lvl2_referal_data['lvl2_percentage'];
					// $po_lv2_percentage_value = ($project_upgrade_purchase_value_excl_vat * $po_lvl2_referal_data['lvl2_percentage'] ) / 100 ;
					$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_affiliate_generated_income'] = $po_lv2_percentage_value;
				}

				$upgrade_purchase_tracking_data['project_upgrade_purchase_net_value'] = $project_upgrade_purchase_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value);
				$this->db->insert('projects_upgrades_purchases_tracking', $upgrade_purchase_tracking_data);

				$this->db->delete('projects_awm_membership_included_upgrades_purchase_tracking', ['id' => $val['id']]);
				############################################ End ##############################################################################
				// insert into refresh sequence tracking membership included upgrades
				$refresh_sequence_data = [
					'project_id' => $project_id,
					'project_upgrade_purchase_reference_id' => $upgrades_purchase_data['membership_included_project_upgrade_purchase_reference_id'],
					'project_upgrade_purchase_tracking_table_source' => 'membership_included',
					'project_last_refresh_time' => null
				];
				
				if($val['project_upgrade_type'] == 'featured'){
					$refresh_sequence_data['project_upgrade_refresh_sequence_featured'] = $this->config->item('project_upgrade_refresh_sequence_featured');
					$time_arr = explode(':',$this->config->item('project_upgrade_refresh_sequence_featured'));	
					$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
					$refresh_sequence_data['project_next_refresh_time'] = $next_refresh_date;
					
				}
				if($val['project_upgrade_type'] == 'urgent'){
					$refresh_sequence_data['project_upgrade_refresh_sequence_urgent'] = $this->config->item('project_upgrade_refresh_sequence_urgent');
					$time_arr = explode(':',$this->config->item('project_upgrade_refresh_sequence_urgent'));	
					$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
					$refresh_sequence_data['project_next_refresh_time'] = $next_refresh_date;
					
				}
				if($val['project_upgrade_type'] == 'sealed'){
					$refresh_sequence_data['project_upgrade_refresh_sequence_sealed'] = $this->config->item('project_upgrade_refresh_sequence_sealed');
					$time_arr = explode(':',$this->config->item('project_upgrade_refresh_sequence_sealed'));	
					$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
					$refresh_sequence_data['project_next_refresh_time'] = $next_refresh_date;
					
				}
				
				if($val['hidden'] == 'N') {
					$this->db->insert($refresh_sequence_tables[$val['project_upgrade_type']], $refresh_sequence_data);
				}
			}
		}
		// get bonus based upgrade purchased tracking data
		$this->db->select('bupt.*, op.hidden');
		$this->db->from('projects_awm_bonus_based_upgrades_purchase_tracking bupt');
		$this->db->join('projects_open_bidding op', 'op.project_id = bupt.project_id');
		$this->db->where('bupt.project_id' , $project_id);
		$bonus_based_upgrade_data = $this->db->get()->result_array();
		
		if(!empty($bonus_based_upgrade_data)) {
			foreach($bonus_based_upgrade_data as $val) {
				$time_arr = explode(':', $val['project_upgrade_availability_length']);
				$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
				$upgrades_purchase_data = $val;
				unset($upgrades_purchase_data['id']);
				unset($upgrades_purchase_data['hidden']);

				$deduction_amount = $val['project_upgrade_purchase_value'];

				$upgrades_purchase_data['project_upgrade_start_date'] = date('Y-m-d H:i:s');
				$upgrades_purchase_data['project_upgrade_end_date'] = $upgrade_end_date;

				$reference_id = PROJECT_BONUS_BASED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
				$latest_released_escrow_data = $this->db->from('proj_bonus_based_upgrades_purchase_tracking')->where('bonus_based_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
				$digits = $this->config->item('reference_id_digits_limit');
				if(empty($latest_released_escrow_data)) {
					$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
				} else {
					$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['bonus_based_project_upgrade_purchase_reference_id']);
					$exclude_inital_number = ltrim($exclude_inital_number, '0');
					$exclude_inital_number = !empty($exclude_inital_number) ? (int)$exclude_inital_number + 1 : 1;
					$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
				}
				$vat_excluded_amount = ($deduction_amount * 100)/ ($vat_percentage+100);
				$vat_amount = $deduction_amount - $vat_excluded_amount;

				$upgrades_purchase_data['bonus_based_project_upgrade_purchase_reference_id'] = $reference_id;
				$upgrades_purchase_data['vat_percentage_value'] = $vat_percentage;
				$upgrades_purchase_data['bonus_based_project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
				$upgrades_purchase_data['bonus_based_project_upgrade_vat_amount_value'] = $vat_amount;

				$this->db->insert('proj_bonus_based_upgrades_purchase_tracking', $upgrades_purchase_data);
				########################## insert data into projects_upgrades_purchases_tracking table ##########################################
				$upgrade_purchase_tracking_data = [
					'project_id' => $upgrades_purchase_data['project_id'],
					'project_owner_id' => $upgrades_purchase_data['project_owner_id'],
					'project_upgrade_type' => $upgrades_purchase_data['project_upgrade_type'],
					'project_upgrade_purchase_date' => $upgrades_purchase_data['project_upgrade_purchase_date']
				];
				
				$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $upgrades_purchase_data['bonus_based_project_upgrade_purchase_reference_id'];
				$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'bonus_based';
				$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $upgrades_purchase_data['project_upgrade_purchase_value'];
				$upgrade_purchase_tracking_data['vat_percentage_value'] = $upgrades_purchase_data['vat_percentage_value'];
				$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $upgrades_purchase_data['bonus_based_project_upgrade_purchase_value_excl_vat'];
				$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
				$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $upgrades_purchase_data['bonus_based_project_upgrade_vat_amount_value'];


				$po_lvl1_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl1_user_id($upgrades_purchase_data['project_owner_id']);
				$po_lvl2_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl2_user_id($upgrades_purchase_data['project_owner_id']);
				$po_lv1_percentage_value = 0;
				$po_lv2_percentage_value = 0;

				if(!empty($po_lvl1_referal_data)) {
					$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_id'] = $po_lvl1_referal_data['user_id'];
					$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_membership_id'] = $po_lvl1_referal_data['id'];
					$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_considered_percentage'] = $po_lvl1_referal_data['lvl1_percentage'];
					// $po_lv1_percentage_value = ($project_upgrade_purchase_value_excl_vat * $po_lvl1_referal_data['lvl1_percentage'] ) / 100 ;
					$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_affiliate_generated_income'] = $po_lv1_percentage_value;
				}

				if(!empty($po_lvl2_referal_data)) {
					$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_id'] = $po_lvl2_referal_data['user_id'];
					$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_membership_id'] = $po_lvl2_referal_data['id'];
					$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_considered_percentage'] = $po_lvl2_referal_data['lvl2_percentage'];
					// $po_lv2_percentage_value = ($project_upgrade_purchase_value_excl_vat * $po_lvl2_referal_data['lvl2_percentage'] ) / 100 ;
					$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_affiliate_generated_income'] = $po_lv2_percentage_value;
				}

				$upgrade_purchase_tracking_data['project_upgrade_purchase_net_value'] = $project_upgrade_purchase_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value);
				$this->db->insert('projects_upgrades_purchases_tracking', $upgrade_purchase_tracking_data);

				$this->db->delete('projects_awm_bonus_based_upgrades_purchase_tracking', ['id' => $val['id']]);
				############################################ End ##############################################################################

				if($val['hidden'] == 'N') {
					// insert into refresh sequence tracking membership included upgrades
					$refresh_sequence_data = [
						'project_id' => $project_id,
						'project_upgrade_purchase_reference_id' => $upgrades_purchase_data['bonus_based_project_upgrade_purchase_reference_id'],
						'project_upgrade_purchase_tracking_table_source' => 'bonus_money',
						'project_last_refresh_time' => null
					];
					if($val['project_upgrade_type'] == 'featured'){
						$refresh_sequence_data['project_upgrade_refresh_sequence_featured'] = $this->config->item('project_upgrade_refresh_sequence_featured');
						$time_arr = explode(':',$this->config->item('project_upgrade_refresh_sequence_featured'));	
						$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
						$refresh_sequence_data['project_next_refresh_time'] = $next_refresh_date;
					
					}
					if($val['project_upgrade_type'] == 'urgent'){
						$refresh_sequence_data['project_upgrade_refresh_sequence_urgent'] = $this->config->item('project_upgrade_refresh_sequence_urgent');
						$time_arr = explode(':',$this->config->item('project_upgrade_refresh_sequence_urgent'));	
						$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
						$refresh_sequence_data['project_next_refresh_time'] = $next_refresh_date;
						
					}
					if($val['project_upgrade_type'] == 'sealed'){
						$refresh_sequence_data['project_upgrade_refresh_sequence_sealed'] = $this->config->item('project_upgrade_refresh_sequence_sealed');
						$time_arr = explode(':',$this->config->item('project_upgrade_refresh_sequence_sealed'));	
						$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
						$refresh_sequence_data['project_next_refresh_time'] = $next_refresh_date;
						
					}
					if($val['project_upgrade_type'] != 'hidden') { 
						$refresh_sequence_tracking_arr[$val['project_upgrade_type']][$project_id] = $refresh_sequence_data;
					}
				}
				
			}
		}
		// get real payment upgrade data
		$this->db->select('rupt.*, op.hidden');
		$this->db->from('projects_awm_real_money_upgrades_purchase_tracking rupt');
		$this->db->join('projects_open_bidding op', 'op.project_id = rupt.project_id');
		$this->db->where('rupt.project_id' , $project_id);
		$upgrade_purchase_data = $this->db->get()->result_array();
		if(!empty($upgrade_purchase_data)) {
			foreach($upgrade_purchase_data as $val) {
				$time_arr = explode(':', $val['project_upgrade_availability_length']);
				$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
			
				$upgrades_purchase_data = $val;
				unset($upgrades_purchase_data['id']);
				unset($upgrades_purchase_data['hidden']);
				
				$upgrades_purchase_data['project_upgrade_start_date'] = date('Y-m-d H:i:s');
				$upgrades_purchase_data['project_upgrade_end_date'] = $upgrade_end_date;

				$deduction_amount = $val['project_upgrade_purchase_value'];

				$reference_id = PROJECT_REAL_MONEY_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
				$latest_released_escrow_data = $this->db->from('proj_real_money_upgrades_purchase_tracking')->where('real_money_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
				$digits = $this->config->item('reference_id_digits_limit');
				if(empty($latest_released_escrow_data)) {
					$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
				} else {
					$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['real_money_project_upgrade_purchase_reference_id']);
					$exclude_inital_number = ltrim($exclude_inital_number, '0');
					$exclude_inital_number = !empty($exclude_inital_number) ? (int)$exclude_inital_number + 1 : 1;
					$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
				}
				$vat_excluded_amount = ($deduction_amount * 100)/ ($vat_percentage+100);
				$vat_amount = $deduction_amount - $vat_excluded_amount;
				
				$upgrades_purchase_data['real_money_project_upgrade_purchase_reference_id'] = $reference_id;
				$upgrades_purchase_data['vat_percentage_value'] = $vat_percentage;
				$upgrades_purchase_data['real_money_project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
				$upgrades_purchase_data['real_money_project_upgrade_purchase_vat_amount_value'] = $vat_amount;

				$this->db->insert('proj_real_money_upgrades_purchase_tracking', $upgrades_purchase_data);
				########################## insert data into projects_upgrades_purchases_tracking table ##########################################
				$upgrade_purchase_tracking_data = [
					'project_id' => $upgrades_purchase_data['project_id'],
					'project_owner_id' => $upgrades_purchase_data['project_owner_id'],
					'project_upgrade_type' => $upgrades_purchase_data['project_upgrade_type'],
					'project_upgrade_purchase_date' => $upgrades_purchase_data['project_upgrade_purchase_date']
				];
				
				$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $upgrades_purchase_data['real_money_project_upgrade_purchase_reference_id'];
				$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'real_money';
				$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $upgrades_purchase_data['project_upgrade_purchase_value'];
				$upgrade_purchase_tracking_data['vat_percentage_value'] = $upgrades_purchase_data['vat_percentage_value'];
				$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $upgrades_purchase_data['real_money_project_upgrade_purchase_value_excl_vat'];
				$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
				$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $upgrades_purchase_data['real_money_project_upgrade_purchase_vat_amount_value'];


				$po_lvl1_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl1_user_id($upgrades_purchase_data['project_owner_id']);
				$po_lvl2_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl2_user_id($upgrades_purchase_data['project_owner_id']);
				$po_lv1_percentage_value = 0;
				$po_lv2_percentage_value = 0;

				$ts = strtotime(date('Y-m-d'));
				$start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
				$week_start_date = date('Y-m-d', $start);

				if(!empty($po_lvl1_referal_data)) {
					$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_id'] = $po_lvl1_referal_data['user_id'];
					$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_membership_id'] = $po_lvl1_referal_data['id'];
					$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_considered_percentage'] = $po_lvl1_referal_data['lvl1_percentage'];
					$po_lv1_percentage_value = ($project_upgrade_purchase_value_excl_vat * $po_lvl1_referal_data['lvl1_percentage'] ) / 100 ;
					$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_affiliate_generated_income'] = $po_lv1_percentage_value;


					$referral_earnings = [
						'user_id' => $po_lvl1_referal_data['user_id'],
						'referral_earning_date' => date('Y-m-d H:i:s'),
						'referral_earning_source_reference_id' => $upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'],
						'referral_earnig_value' => $po_lv1_percentage_value,
						'referral_earning_lvl_source' => 'lvl1',
						'referee_user_id' => $upgrades_purchase_data['project_owner_id']
					];
					$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

					$daily_earnings = [
						'user_id' => $po_lvl1_referal_data['user_id'],
						'referral_earnings_date' => date('Y-m-d H:i:s'),
						'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
					];
					$this->Escrow_model->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

					$weekly_earnings = [
						'user_id' => $po_lvl1_referal_data['user_id'],
						'referral_earnings_week_start_date' => $week_start_date,
						'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
					];
					$this->Escrow_model->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);

					$monthly_earnings = [
						'user_id' => $po_lvl1_referal_data['user_id'],
						'referral_earnings_month' => date('Y-m-d'),
						'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
					];
					$this->Escrow_model->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);

					$total_earnings = [
						'user_id' => $po_lvl1_referal_data['user_id'],
						'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
					];
					$this->Escrow_model->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);

				}

				if(!empty($po_lvl2_referal_data)) {
					$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_id'] = $po_lvl2_referal_data['user_id'];
					$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_membership_id'] = $po_lvl2_referal_data['id'];
					$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_considered_percentage'] = $po_lvl2_referal_data['lvl2_percentage'];
					$po_lv2_percentage_value = ($project_upgrade_purchase_value_excl_vat * $po_lvl2_referal_data['lvl2_percentage'] ) / 100 ;
					$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_affiliate_generated_income'] = $po_lv2_percentage_value;


					$referral_earnings = [
						'user_id' => $po_lvl2_referal_data['user_id'],
						'referral_earning_date' => date('Y-m-d H:i:s'),
						'referral_earning_source_reference_id' => $upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'],
						'referral_earnig_value' => $po_lv2_percentage_value,
						'referral_earning_lvl_source' => 'lvl2',
						'referee_user_id' => $upgrades_purchase_data['project_owner_id']
					];
					$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

					$daily_earnings = [
						'user_id' => $po_lvl2_referal_data['user_id'],
						'referral_earnings_date' => date('Y-m-d H:i:s'),
						'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
					];
					$this->Escrow_model->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

					$weekly_earnings = [
						'user_id' => $po_lvl2_referal_data['user_id'],
						'referral_earnings_week_start_date' => $week_start_date,
						'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
					];
					$this->Escrow_model->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);

					$monthly_earnings = [
						'user_id' => $po_lvl2_referal_data['user_id'],
						'referral_earnings_month' => date('Y-m-d'),
						'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
					];
					$this->Escrow_model->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);
					
					$total_earnings = [
						'user_id' => $po_lvl2_referal_data['user_id'],
						'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
					];
					$this->Escrow_model->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);

				}

				$upgrade_purchase_tracking_data['project_upgrade_purchase_net_value'] = $project_upgrade_purchase_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value);
				$this->db->insert('projects_upgrades_purchases_tracking', $upgrade_purchase_tracking_data);

				$this->db->delete('projects_awm_real_money_upgrades_purchase_tracking', ['id' => $val['id']]);
				############################################ End ##############################################################################
				
				if($val['hidden'] == 'N') {
					// insert into refresh sequence tracking membership included upgrades
					$refresh_sequence_data = [
						'project_id' => $project_id,
						'project_upgrade_purchase_reference_id' => $upgrades_purchase_data['real_money_project_upgrade_purchase_reference_id'],
						'project_upgrade_purchase_tracking_table_source' => 'real_money',
						'project_last_refresh_time' => null
					];
					
					if($val['project_upgrade_type'] == 'featured'){
						$refresh_sequence_data['project_upgrade_refresh_sequence_featured'] = $this->config->item('project_upgrade_refresh_sequence_featured');
						$time_arr = explode(':',$this->config->item('project_upgrade_refresh_sequence_featured'));	
						$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
						$refresh_sequence_data['project_next_refresh_time'] = $next_refresh_date;
						
					}
					if($val['project_upgrade_type'] == 'urgent'){
						$refresh_sequence_data['project_upgrade_refresh_sequence_urgent'] = $this->config->item('project_upgrade_refresh_sequence_urgent');
						$time_arr = explode(':',$this->config->item('project_upgrade_refresh_sequence_urgent'));	
						$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
						$refresh_sequence_data['project_next_refresh_time'] = $next_refresh_date;
						
					}
					if($val['project_upgrade_type'] == 'sealed'){
						$refresh_sequence_data['project_upgrade_refresh_sequence_sealed'] = $this->config->item('project_upgrade_refresh_sequence_sealed');
						$time_arr = explode(':',$this->config->item('project_upgrade_refresh_sequence_sealed'));	
						$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
						$refresh_sequence_data['project_next_refresh_time'] = $next_refresh_date;
						
					}
					$refresh_sequence_tracking_arr[$val['project_upgrade_type']][$project_id] = $refresh_sequence_data;
				}
				
			}
		}
		foreach($refresh_sequence_tracking_arr as $key => $val) {
			foreach($val as $k => $v) {
				if($this->config->item($refresh_sequence_duration[$key]) != null) {
					$time_arr = explode(':', $this->config->item($refresh_sequence_duration[$key]));
					$check_valid_arr = array_map('getInt', $time_arr); 
					$valid_time_arr = array_filter($check_valid_arr);
					if(!empty($valid_time_arr) && array_key_exists($key, $refresh_sequence_tables) ) {
						$this->db->insert($refresh_sequence_tables[$key], $v);
					}
				}
			}	
		}
	}
	
	/**
	 * This method is used to update upgrade column [featured / urgent] status in open bidding table 
	*/
	public function update_expired_upgrade_status_open_bidding() {
		$cron_start_date = date('d.m.Y H:i:s');
		$cron_project_info = '';
		$project_owner_ids = [];
		$this->db->select('op.project_id,op.project_title,op.project_expiration_date,op.project_owner_id,op.project_title,op.project_type,op.featured,op.urgent,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date, u.profile_name');
		$this->db->from('projects_open_bidding op');
		// $this->db->where('op.project_expiration_date >= NOW()');
		//$this->db->where('op.featured = "Y" OR op.urgent = "Y"');
		$this->db->join('users u','u.user_id = op.project_owner_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = op.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = op.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = op.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = op.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = op.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = op.project_id', 'left');
		$this->db->order_by('op.id','desc');
		$open_bidding_project_result = $this->db->get();
		$open_bidding_project_data = $open_bidding_project_result->result_array();
		
		if(!empty($open_bidding_project_data)) {
			
			foreach($open_bidding_project_data as $open_bidding_project_key => $open_bidding_project_value){
				$featured_max = 0;
				$urgent_max = 0;
				$expiration_featured_upgrade_date_array = array();
				$expiration_urgent_upgrade_date_array = array();
				
				if(!empty($open_bidding_project_value['featured_upgrade_end_date'])){
					$expiration_featured_upgrade_date_array[] = $open_bidding_project_value['featured_upgrade_end_date'];
				}
				if(!empty($open_bidding_project_value['bonus_featured_upgrade_end_date'])){
					$expiration_featured_upgrade_date_array[] = $open_bidding_project_value['bonus_featured_upgrade_end_date'];
				}
				if(!empty($open_bidding_project_value['membership_include_featured_upgrade_end_date'])){
					$expiration_featured_upgrade_date_array[] = $open_bidding_project_value['membership_include_featured_upgrade_end_date'];
				}
				if(!empty($expiration_featured_upgrade_date_array)){
					$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
				}
				
				##########
				if(!empty($open_bidding_project_value['urgent_upgrade_end_date'])){
					$expiration_urgent_upgrade_date_array[] = $open_bidding_project_value['urgent_upgrade_end_date'];
				}
				if(!empty($open_bidding_project_value['bonus_urgent_upgrade_end_date'])){
					$expiration_urgent_upgrade_date_array[] = $open_bidding_project_value['bonus_urgent_upgrade_end_date'];
				}
				if(!empty($open_bidding_project_value['membership_include_urgent_upgrade_end_date'])){
					$expiration_urgent_upgrade_date_array[] = $open_bidding_project_value['membership_include_urgent_upgrade_end_date'];
				}
				if(!empty($expiration_urgent_upgrade_date_array)){
					$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
				}
				//$updated_open_bidding_data = [];
				
				if((($featured_max != 0 && $featured_max < time()) || $featured_max == 0) && $open_bidding_project_value['featured'] == 'N' ){
					$this->delete_featured_project_upgrade_record_cover_picture($open_bidding_project_value['profile_name'],$open_bidding_project_value['project_id']);
				
				}
				
				if(($featured_max != 0 && $featured_max < time()) && $open_bidding_project_value['featured'] == 'Y') {
					$this->delete_featured_project_upgrade_record_cover_picture($open_bidding_project_value['profile_name'],$open_bidding_project_value['project_id']);
					//$updated_open_bidding_data['featured'] = 'N';
					
					$this->db->update('projects_open_bidding', ['featured' => 'N'], ['project_id' => $open_bidding_project_value['project_id']]);
					
					
					$this->db->limit(1);
					$this->db->order_by('id', 'desc');
					$this->db->update('featured_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $open_bidding_project_value['project_id']]);

					$project_url = VPATH.($this->config->item('project_detail_page_url').'?id='.$open_bidding_project_value['project_id']);
					if($featured_max != strtotime($open_bidding_project_value['project_expiration_date'])) {
					
						if($open_bidding_project_value['project_type'] == 'fulltime'){
							$project_upgrade_expired_user_activity_displayed_message = $this->config->item('fulltime_project_featured_upgrade_expired_user_activity_log_displayed_message_sent_to_po');
							}else{
							$project_upgrade_expired_user_activity_displayed_message = $this->config->item('project_featured_upgrade_expired_user_activity_log_displayed_message_sent_to_po');
						}
						$project_url = VPATH.$this->config->item('project_detail_page_url').'?id='.$open_bidding_project_value['project_id'];
						$project_upgrade_expired_user_activity_displayed_message = str_replace([ '{project_title}', '{featured_upgrade_expiration_date}', '{project_url_link}'], [ htmlspecialchars($open_bidding_project_value['project_title'], ENT_QUOTES), date('d.m.Y H:i:s', $featured_max), $project_url], $project_upgrade_expired_user_activity_displayed_message);
						if(!in_array($open_bidding_project_value['project_owner_id'], $project_owner_ids)) {
							array_push($project_owner_ids, $open_bidding_project_value['project_owner_id']);
						}
						user_display_log($project_upgrade_expired_user_activity_displayed_message, $open_bidding_project_value['project_owner_id']);
						
						$cron_project_info .= "\r\n".'project_id : '.$open_bidding_project_value['project_id'].' | project_owner_id : '.$open_bidding_project_value['project_owner_id'].' | expired upgrade : featured';
					}
				}
				if(($urgent_max != 0 && $urgent_max < time()) && $open_bidding_project_value['urgent'] == 'Y') {
					$this->db->update('projects_open_bidding', ['urgent' => 'N'], ['project_id' => $open_bidding_project_value['project_id']]);
					$this->db->limit(1);
					$this->db->order_by('id', 'desc');
					$this->db->update('urgent_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $open_bidding_project_value['project_id']]);

					$project_url = VPATH.$this->config->item('project_detail_page_url').'?id='.$open_bidding_project_value['project_id'];
					if($urgent_max != strtotime($open_bidding_project_value['project_expiration_date'])) {
						if($open_bidding_project_value['project_type'] == 'fulltime'){
							$project_upgrade_expired_user_activity_displayed_message = $this->config->item('fulltime_project_urgent_upgrade_expired_user_activity_log_displayed_message_sent_to_po');
						}else{
							$project_upgrade_expired_user_activity_displayed_message = $this->config->item('project_urgent_upgrade_expired_user_activity_log_displayed_message_sent_to_po');
						}
						
						$project_upgrade_expired_user_activity_displayed_message = str_replace([ '{project_title}', '{urgent_upgrade_expiration_date}', '{project_url_link}'], [ htmlspecialchars($open_bidding_project_value['project_title'], ENT_QUOTES), date('d.m.Y H:i:s', $urgent_max), $project_url], $project_upgrade_expired_user_activity_displayed_message);
						
						
						
						if(!in_array($open_bidding_project_value['project_owner_id'], $project_owner_ids)) {
							array_push($project_owner_ids, $open_bidding_project_value['project_owner_id']);
						}
						user_display_log($project_upgrade_expired_user_activity_displayed_message, $open_bidding_project_value['project_owner_id']);
						$cron_project_info .= "\r\n".'project_id : '.$open_bidding_project_value['project_id'].' | project_owner_id : '.$open_bidding_project_value['project_owner_id'].' | expired upgrade : urgent';
						
					}
				}
				
				/* if(!empty($updated_open_bidding_data)) {
					$this->db->update('projects_open_bidding', $updated_open_bidding_data, ['project_id' => $open_bidding_project_value['project_id']]);
					
				} */
			}
		}
		if(!empty($project_owner_ids)) {
			// trigger socket event to update open bidding or expired project listing on dashboard
			$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateUserDashboardUpgradeExpired/?authorization_key=".NODE_URL_AUTHORIZATION_KEY;
			$options = array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => http_build_query($project_owner_ids)
			);
			try {
				$ch = curl_init( $url );
				curl_setopt_array( $ch, $options );
				curl_exec( $ch );
				curl_close( $ch );
			} catch(Exception $e) {
			}		
		}
		$cron_str = 'cronUpdateProjectExpiredUpgradeStatusOpenBiddingTable executed successfully (running every 15 seconds). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. '.$cron_project_info;
		echo $cron_str;
	}
	
	//this function is used to fetch the featured/urgent upgrade status
	public function get_project_upgrade_status($project_id)
	{
		
		$this->db->select('projects_open_bidding.project_id,projects_open_bidding.featured,projects_open_bidding.urgent,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date');
		$this->db->from('projects_open_bidding');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id='.$project_id.'  group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
	
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id='.$project_id.'  group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
		
		$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id='.$project_id.'  group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
		
		######### for urgent #############
		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent"  and project_id='.$project_id.'  group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
	
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id='.$project_id.'  group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
		
		$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent"  and project_id='.$project_id.'  group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
		$this->db->where('projects_open_bidding.project_id',$project_id);
		
		$project_result = $this->db->get();
		$project_data = $project_result->row_array();
		return $project_data;
		
	}
	
	
	
	public function get_featured_project_upgrade_expiration_status($project_id,$project_info = array())
	{
		
		$upgrade_fields = "";
		if($project_info['project_status'] == 'open_for_bidding' ){
			$upgrade_fields =  ',pd.featured';
		}
		$project_table_name = 'projects_open_bidding';
		if($project_info['project_status'] == 'awarded'){
			if($project_info['project_type'] == 'fixed'){
				$project_table_name = 'fixed_budget_projects_awarded';
			}
			if($project_info['project_type'] == 'hourly'){
				$project_table_name = 'hourly_rate_based_projects_awarded';
			}
		}
		
		
		
		
		$this->db->select('pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,pd.project_expiration_date'.$upgrade_fields.',featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,users.profile_name');
		$this->db->from($project_table_name.' pd');
		$this->db->join('users', 'users.user_id = pd.project_owner_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured"  and project_id = "'.$project_id.'" group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
	
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		
		$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured"  and project_id = "'.$project_id.'" group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->where('pd.project_id',$project_id);
		
		
		$project_result = $this->db->get();
		$project_data = $project_result->result_array();
		
		
		
		
		 $featured_max = 0;
		if(!empty($project_data[0]['featured_upgrade_end_date'])){
			$expiration_featured_upgrade_date_array[] = $project_data[0]['featured_upgrade_end_date'];
		}
		if(!empty($project_data[0]['bonus_featured_upgrade_end_date'])){
			$expiration_featured_upgrade_date_array[] = $project_data[0]['bonus_featured_upgrade_end_date'];
		}
		if(!empty($project_data[0]['membership_include_featured_upgrade_end_date'])){
			$expiration_featured_upgrade_date_array[] = $project_data[0]['membership_include_featured_upgrade_end_date'];
		}
		if(!empty($expiration_featured_upgrade_date_array)){
			$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
		}
		$profile_folder     = $project_data[0]['profile_name'];
		if(($project_info['project_status'] == 'open_for_bidding' && $project_data[0]['featured'] == 'Y' && $featured_max >= time()) || ($project_info['project_status'] == 'awarded' && $featured_max >= time())){
			$project_featured_upgrade_expiration_status = true;
			
		}
		else if((($featured_max != 0 && $featured_max < time()) || $featured_max == 0) && ($project_info['project_status'] == 'open_for_bidding' && $project_data[0]['featured'] == 'N') ){
			
			$this->delete_featured_project_upgrade_record_cover_picture($profile_folder,$project_id);
			$project_featured_upgrade_expiration_status = false;
		}
		else if((($featured_max != 0 && $featured_max < time()) || $featured_max == 0) && ($project_info['project_status'] == 'open_for_bidding' && $project_data[0]['featured'] == 'Y' )){
			
				
			$this->db->update('projects_open_bidding', ['featured' => 'N'], ['project_id' => $project_data[0]['project_id']]);	
			$this->delete_featured_project_upgrade_record_cover_picture($profile_folder,$project_id);
			$project_featured_upgrade_expiration_status = false;
			
			$project_url = VPATH.$this->config->item('project_detail_page_url').'?id='.$project_id;
			
			if($project_data[0]['project_type'] == 'fulltime'){
			$project_upgrade_expired_user_activity_displayed_message = $this->config->item('fulltime_project_featured_upgrade_expired_user_activity_log_displayed_message_sent_to_po');
			}else{
			$project_upgrade_expired_user_activity_displayed_message = $this->config->item('project_featured_upgrade_expired_user_activity_log_displayed_message_sent_to_po');
			}
			$project_upgrade_expired_user_activity_displayed_message = str_replace([ '{project_title}', '{featured_upgrade_expiration_date}', '{project_url_link}'], [htmlspecialchars($project_data[0]['project_title'], ENT_QUOTES), date('d.m.Y H:i:s', $featured_max), $project_url], $project_upgrade_expired_user_activity_displayed_message);
			user_display_log($project_upgrade_expired_user_activity_displayed_message, $project_data[0]['project_owner_id']);
			
		}
		
		################# remove the obsolete entry start ###########
		$count_featured_project_cover_picture = $this->db // count the project featured cover picture record
				->select ('id')
				->from ('featured_projects_users_upload_cover_pictures_tracking')
				->where('project_id',$project_id)
				->get ()->num_rows ();
		if($count_featured_project_cover_picture == 0){
				
			$this->delete_featured_project_upgrade_record_cover_picture($profile_folder,$project_id);
		}else{
			$project_featured_cover_picture_detail = $this->db->get_where('featured_projects_users_upload_cover_pictures_tracking', ['project_id' => $project_id])->row_array();
                        
			if(!empty($project_featured_cover_picture_detail['project_cover_picture_name'])){
			
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] 	= FTP_PORT;
				$config['debug']    = TRUE;
				$this->ftp->connect($config);
			
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
				$project_awarded_dir = PROJECT_AWARDED_DIR;
				$featured_upgrade_cover_picture = PROJECT_FEATURED_UPGRADE_COVER_PICTURE;
				$profile_folder     = $project_data[0]['profile_name'];
				$expl      = explode('.',$project_featured_cover_picture_detail['project_cover_picture_name']);
				
			   // echo '<pre>'; print_r($expl); echo '</pre>'; die();
				
				$original_featured_cover_picture_name	= $expl[0].'_original.png';
				// Fom open for biding folder
                 //for main cover picture
				$source_path_open_bidding = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$featured_upgrade_cover_picture.$project_featured_cover_picture_detail['project_cover_picture_name'];
				$file_size_open_bidding = $this->ftp->get_filesize($source_path_open_bidding);
				 
				 $source_path_awarded = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$featured_upgrade_cover_picture.$project_featured_cover_picture_detail['project_cover_picture_name'];
                 $file_size_awarded = $this->ftp->get_filesize($source_path_awarded);
                                
                 //for original cover picture
				/* $source_path_original = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$featured_upgrade_cover_picture.$original_featured_cover_picture_name;
				$file_size_original = $this->ftp->get_filesize($source_path_original); */
				$check_connection = true;
				if($file_size_open_bidding == '-1' && $project_info['project_status'] == 'open_for_bidding') {
						
                   $this->delete_featured_project_upgrade_record_cover_picture($profile_folder,$project_id);
				   $check_connection = false;
                   
				}if($file_size_awarded == '-1' && $project_info['project_status'] == 'awarded') {
					
                   $this->delete_featured_project_upgrade_record_cover_picture($profile_folder,$project_id);
				   $check_connection = false;
                   
				} 
				if($check_connection){	
				$this->ftp->close();
			   }
               
			}
		}
		################# remove the obsolete entry end ###########
		return $project_featured_upgrade_expiration_status; 
	}
	
	/**
	 * This method is used to delete the featured upgrade project cover picture from folder as well as from database  
	 */
	public function delete_featured_project_upgrade_record_cover_picture($profile_folder,$project_id){
	
		$this->db->delete('featured_projects_users_upload_cover_pictures_tracking', array('project_id' => $project_id));
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
		$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
		$project_awarded_dir = PROJECT_AWARDED_DIR;
		$featured_upgrade_cover_picture = PROJECT_FEATURED_UPGRADE_COVER_PICTURE;
                
		$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$featured_upgrade_cover_picture;
		if(!empty($this->ftp->check_ftp_directory_exist($source_path)))
		{
			$this->ftp->delete_dir($source_path);// delete cover picture directory 
		}
		$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$featured_upgrade_cover_picture;
		if(!empty($this->ftp->check_ftp_directory_exist($source_path)))
		{
			$this->ftp->delete_dir($source_path);// delete cover picture directory 
		}
		$this->ftp->close();
	
	}
	
	/**
	* This method is used to check valid combination of locality_id,county_id,postal_code_id If the combination is not valid it will update locality_id,county_id,postal_code_id  to 0.
	 */
	/* public function check_update_invalid_combination_project_location($table_name,$field_name,$project_id){
		
		$check_valid_location = true;
		$this->db->select('locality_id,county_id,postal_code_id');
		$this->db->from($table_name);
		$this->db->where($field_name,$project_id);
		$project_result = $this->db->get();
		$project_data = $project_result->result_array();
		if(!empty($project_data)){
			$locality_id = $project_data[0]['locality_id']!= NULL ? $project_data[0]['locality_id'] : 0;
			$county_id = $project_data[0]['county_id']!= NULL ? $project_data[0]['county_id'] : 0;
			$postal_code_id = $project_data[0]['postal_code_id']!= NULL ? $project_data[0]['postal_code_id'] : 0;
			$location_addition = $locality_id + $county_id + $postal_code_id;
			if(!empty($location_addition)){
				$county_detail = $this->db->get_where('counties', ['id' => $project_data[0]['county_id']])->row_array();
				if(!empty($county_detail)){
					$locality_detail = $this->db->get_where('localities', ['id'=>$project_data[0]['locality_id'],'county_id'=>$project_data[0]['county_id']])->row_array();
					if(!empty($locality_detail)){
							
						$postal_code_detail = $this->db->get_where('postal_codes', ['id'=>$project_data[0]['postal_code_id'],'locality_id'=>$project_data[0]['locality_id']])->row_array();
						if(empty($postal_code_detail)){
							$check_valid_location = false;
						}
					}else{
						$check_valid_location = false;
					}
				}else{
					$check_valid_location = false;
				}
				if(!$check_valid_location){
				$this->db->update($table_name, ['locality_id' => 0,'county_id' => 0,'postal_code_id' => 0], [$field_name => $project_id]);
				}
			}
		}
	} */
	
	

	
	// This function is used to count the total cancelled projects of user
	/* public function get_user_cancelled_projects_count($user_id){
		$user_cancelled_project_count = 0;
		$user_fixed_budget_projects_cancelled_count = $this->db->where(['project_owner_id' => $user[0]->user_id])->from('fixed_budget_projects_cancelled')->count_all_results();
		$user_cancelled_project_count += $user_fixed_budget_projects_cancelled_count;
		
		
		$user_fixed_budget_projects_cancelled_by_admin_count = $this->db->where(['project_owner_id' => $user[0]->user_id])->from('fixed_budget_projects_cancelled_by_admin')->count_all_results();
		$user_cancelled_project_count += $user_fixed_budget_projects_cancelled_by_admin_count;
		
		$user_fulltime_projects_cancelled_count = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled')->count_all_results();
		$user_cancelled_project_count += $user_fulltime_projects_cancelled_count;
		
		$user_fulltime_projects_cancelled_by_admin_count = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled_by_admin')->count_all_results();
		$user_cancelled_project_count += $user_fulltime_projects_cancelled_by_admin_count;
		
		$user_hourly_rate_based_projects_cancelled_count = $this->db->where(['project_owner_id' => $user[0]->user_id])->from('hourly_rate_based_projects_cancelled')->count_all_results();
		$user_cancelled_project_count += $user_hourly_rate_based_projects_cancelled_count;
		
		$user_hourly_rate_based_projects_cancelled_by_admin_count = $this->db->where(['project_owner_id' => $user[0]->user_id])->from('hourly_rate_based_projects_cancelled_by_admin')->count_all_results();
		
		$user_cancelled_project_count += $user_hourly_rate_based_projects_cancelled_by_admin_count;
		return $user_cancelled_project_count;
	
	} */
	
	// This function is used to count the total expired projects of user
	/* public function get_user_expired_projects_count($user_id){
		$user_expired_project_count = 0;
		
		$user_fixed_budget_projects_expired_count = $this->db->where(['project_owner_id' => $user[0]->user_id])->from('fixed_budget_projects_expired')->count_all_results();
		$user_expired_project_count += $user_fixed_budget_projects_expired_count;
		
		
		$user_fulltime_projects_expired_count = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_expired')->count_all_results();
		$user_expired_project_count += $user_fulltime_projects_expired_count;
		
		$user_hourly_rate_based_projects_expired_count = $this->db->where(['project_owner_id' => $user[0]->user_id])->from('hourly_rate_based_projects_expired')->count_all_results();
		$user_expired_project_count += $user_hourly_rate_based_projects_expired_count;
		return $user_cancelled_project_count;
	
	} */
	
	// This function move the expired project from open for bidding table to  dedicated expired projects table
	public function move_expired_project_entry_from_open_bidding_projects_table($project_id){
		$this->db->select('ob.*, u.profile_name');
		$this->db->from('projects_open_bidding ob');
		$this->db->join('users u', 'u.user_id = ob.project_owner_id');
		$this->db->where('ob.project_id',$project_id);
		$this->db->where('ob.project_expiration_date <=',date('Y-m-d H:i:s'));
		$project_data = $this->db->get()->row_array();
		if(!empty($project_data)){
			$expired_data = [
				'project_id' => $project_data['project_id'],
				'project_owner_id' => $project_data['project_owner_id'],
				'project_posting_date' => $project_data['project_posting_date'],
				'project_expiration_date' => $project_data['project_expiration_date'],
				'project_title' => $project_data['project_title'],
				'project_description' => $project_data['project_description'],
				'locality_id' => $project_data['locality_id'],
				'county_id' => $project_data['county_id'],
				'postal_code_id' => $project_data['postal_code_id'],
				'min_budget' => $project_data['min_budget'],
				'max_budget' => $project_data['max_budget'],
				'confidential_dropdown_option_selected' => $project_data['confidential_dropdown_option_selected'],
				'not_sure_dropdown_option_selected' => $project_data['not_sure_dropdown_option_selected'],
				'escrow_payment_method' => $project_data['escrow_payment_method'],
				'offline_payment_method' => $project_data['offline_payment_method'],
				'sealed' => $project_data['sealed'],
				'hidden' => $project_data['hidden'],
				'views' => $project_data['views'],
				'revisions' => $project_data['revisions']
			];
			if($project_data['project_type'] == 'fixed'){
				$check_expired_project_count = $this->db->where(['project_id' => $project_data['project_id']])->from('fixed_budget_projects_expired')->count_all_results();
				if($check_expired_project_count == 0) {
					$this->db->insert('fixed_budget_projects_expired', $expired_data);
				}
			} else if($project_data['project_type'] == 'hourly'){
				$check_expired_project_count = $this->db->where(['project_id' => $project_data['project_id']])->from('hourly_rate_based_projects_expired')->count_all_results();
				if($check_expired_project_count == 0) {
					$this->db->insert('hourly_rate_based_projects_expired', $expired_data);
				}
			} else if($project_data['project_type'] == 'fulltime'){
				
				$expired_data['fulltime_project_id'] = $project_data['project_id'];
				$expired_data['employer_id'] = $project_data['project_owner_id'];
				$expired_data['fulltime_project_posting_date'] = $project_data['project_posting_date'];
				$expired_data['fulltime_project_expiration_date'] = $project_data['project_expiration_date'];
				$expired_data['fulltime_project_title'] = $project_data['project_title'];
				$expired_data['fulltime_project_description'] = $project_data['project_description'];
				$expired_data['min_salary'] = $project_data['min_budget'];
				$expired_data['max_salary'] = $project_data['max_budget'];

				unset($expired_data['project_id']);
				unset($expired_data['project_owner_id']);
				unset($expired_data['project_posting_date']);
				unset($expired_data['project_expiration_date']);
				unset($expired_data['project_title']);
				unset($expired_data['project_description']);
				unset($expired_data['min_budget']);
				unset($expired_data['max_budget']);

				$check_expired_project_count = $this->db->where(['fulltime_project_id' => $project_data['project_id']])->from('fulltime_projects_expired')->count_all_results();
				if($check_expired_project_count == 0) {
					$this->db->insert('fulltime_projects_expired', $expired_data);
				}
			}
			// Set next refresh time to null for cancelled project
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('standard_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('featured_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('urgent_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('sealed_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);
			// remove entry from project latest refresh sequence tracking table
			$this->db->delete('projects_latest_refresh_sequence_tracking', array('project_id' => $project_data['project_id']));

			$this->db->delete('projects_open_bidding', ['project_id' => $project_data['project_id']]);
			$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
			if($project_data['project_type'] == 'fulltime'){
				$project_expired_activity_log_message = $this->config->item('fulltime_project_expired_user_activity_log_displayed_message_sent_to_po');
			}else{
				$project_expired_activity_log_message = $this->config->item('project_expired_user_activity_log_displayed_message_sent_to_po');
			}
			$project_title = $project_data['project_title'];
			$project_expiration_date = date(DATE_TIME_FORMAT,strtotime($project_data['project_expiration_date']));
			
			
			$project_expired_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{project_expiration_date}'),array($project_url_link,htmlspecialchars_decode($project_title),$project_expiration_date),$project_expired_activity_log_message);
			user_display_log($project_expired_activity_log_message,$project_data['project_owner_id']);
			
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->load->library('ftp');
			$this->ftp->connect($config); 
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
			$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
			$project_expired_dir = PROJECT_EXPIRED_DIR;
			$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
			$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
			
			$profile_folder = $project_data['profile_name'];
			
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
			
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir);
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].DIRECTORY_SEPARATOR);
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$project_owner_attachments_dir);
			
			
			$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].$project_owner_attachments_dir;
			$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$project_owner_attachments_dir;
			
			$source_list = $this->ftp->list_files($source_path);
			if(!empty($source_list)) {
				foreach($source_list as $path) {
					$arr = explode('/', $path);
					$file_size = $this->ftp->get_filesize($path);
					if($file_size != '-1') {
						$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$project_owner_attachments_dir.end($arr);
						$this->ftp->move($path, $destination_path);
					}
				}
			}
			
			
			$bid_attachments = $this->db->where ('project_id', $project_id)->get ('projects_active_bids_users_attachments_tracking')->result_array ();
			if(!empty($bid_attachments)){
			
				$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$users_bid_attachments_dir);	
				foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);	
					
					// create the owner attachment directory by using  project id
				
					//	
					$bid_attachment_directory_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
						
						$source_bid_attachment_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						
						$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
						if($file_size != '-1')
						{
							$destination_bid_attachment_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
							$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
							
						}
						/* $this->db->delete('projects_active_bids_users_attachments_tracking', ['id' => $bid_attachment_value['id']]); */
					}
				}
			
			}
			
			$this->db->delete('featured_projects_users_upload_cover_pictures_tracking', array('project_id' => $project_data['project_id']));
			// remov entry from open bidding table
			if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id']))) {
				$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id']);
			}
		}	
	}
	
	// This function count the total published  projects of project owner (except fulltime)
	public function get_po_published_projects_count($user_id){
	
		$projects_published_tables_array = array('fixed_budget_projects_awarded','fixed_budget_projects_progress','fixed_budget_projects_completed','fixed_budget_projects_expired','fixed_budget_projects_cancelled','fixed_budget_projects_cancelled_by_admin','hourly_rate_based_projects_awarded','hourly_rate_based_projects_progress','hourly_rate_based_projects_completed','hourly_rate_based_projects_expired','hourly_rate_based_projects_cancelled','hourly_rate_based_projects_cancelled_by_admin');
		$po_total_published_projects_count = 0;
		foreach($projects_published_tables_array as $table_name){
			
		 $po_total_published_projects_count += $this->db->where(['project_owner_id' => $user_id])->from($table_name)->count_all_results();
		
		}
		$po_total_published_projects_count += $this->db->where(['project_owner_id' => $user_id,'project_type != '=>'fulltime'])->from('projects_open_bidding')->count_all_results();
		return $po_total_published_projects_count;
		
	}
	
	// This function count the total published  fulltime projects of project owner (except fulltime)
	public function get_po_published_fulltime_projects_count($user_id){
	
		$fulltime_projects_published_tables_array = array('fulltime_projects_cancelled','fulltime_projects_cancelled_by_admin','fulltime_projects_expired');
		$po_total_published_fulltime_projects_count = 0;
		foreach($fulltime_projects_published_tables_array as $table_name){
		 $po_total_published_fulltime_projects_count += $this->db->where(['employer_id' => $user_id])->from($table_name)->count_all_results();
		}
		$po_total_published_fulltime_projects_count += $this->db->where(['project_owner_id' => $user_id,'project_type'=>'fulltime'])->from('projects_open_bidding')->count_all_results();
		return $po_total_published_fulltime_projects_count;
	}
	
	// This function count the number of hires sp by po
	public function get_po_hires_sp_on_fulltime_projects_count($user_id){
	
		$po_hires_sp_on_fulltime_projects_count = $this->db->where(['employer_id' => $user_id])->from('fulltime_projects_hired_employees_tracking')->count_all_results();
		return $po_hires_sp_on_fulltime_projects_count;
	}
	
	// This function count the total in progress  projects of project owner
	public function get_po_in_progress_projects_count($user_id){
	
		$projects_in_progress_tables_array = array('fixed_budget_projects_progress','hourly_rate_based_projects_progress');
		$po_total_in_progress_projects_count = 0;
		foreach($projects_in_progress_tables_array as $table_name){
			$po_total_in_progress_projects_count += $this->db->where(['project_owner_id' => $user_id])->from($table_name)->count_all_results();
		
		}
		return $po_total_in_progress_projects_count;
		
	}
	
	/**
		* This function is used to fetch the all posted projects of project owner .
	*/
	public function get_po_posted_project_listing_profile_page($user_id,$start = '', $limit = ''){
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		########################## fetch the project owner open for bidding projects##########
		$this->db->select('SQL_CALC_FOUND_ROWS pob.project_id,pob.project_title,pob.project_description,pob.project_type,pob.min_budget,pob.max_budget,pob.confidential_dropdown_option_selected,pob.not_sure_dropdown_option_selected,pob.urgent,pob.featured,pob.sealed,pob.hidden,pob.project_posting_date as project_sorted_date,pob.escrow_payment_method,pob.offline_payment_method,"" as project_completion_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"open_for_bidding" as project_status,"" as cancelled_by,pob.project_posting_date as po_view_project_date,pob.project_owner_id,pob.project_expiration_date',false);
		
		//$this->db->select('"0" as total_active_disputes');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_active_disputes where disputed_fulltime_project_id = pob.project_id and employer_id_of_disputed_fulltime_project = "'.$user_id.'") as total_active_disputes');
		
		$this->db->from('projects_open_bidding as pob');
		$this->db->where('pob.hidden','N');
		$this->db->where('pob.project_owner_id',$user_id);
		
		$this->db->join('counties', 'counties.id = pob.county_id', 'left');
		$this->db->join('localities', 'localities.id = pob.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pob.postal_code_id', 'left');
		$open_for_bidding_projects_query = $this->db->get_compiled_select();

		
		
		########################## fetch the project owner awarded projects##########
		$this->db->select(' fbpa.project_id,fbpa.project_title,fbpa.project_description,fbpa.project_type,fbpa.min_budget,fbpa.max_budget,fbpa.confidential_dropdown_option_selected,fbpa.not_sure_dropdown_option_selected,"" as featured,"" as urgent,fbpa.sealed,fbpa.hidden,fbpa.project_posting_date as project_sorted_date,fbpa.escrow_payment_method,fbpa.offline_payment_method,"" as project_completion_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"awarded" as project_status,"" as cancelled_by,fbpa.project_posting_date as po_view_project_date,fbpa.project_owner_id,fbpa.project_expiration_date');
		$this->db->select('"0" as total_active_disputes');
		$this->db->from('fixed_budget_projects_awarded as fbpa');
		$this->db->where('fbpa.hidden','N');
		$this->db->where('fbpa.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = fbpa.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbpa.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbpa.postal_code_id', 'left');
		$fixed_budget_projects_awarded_query = $this->db->get_compiled_select();
		
		
		$this->db->select('hrpa.project_id,hrpa.project_title,hrpa.project_description,hrpa.project_type,hrpa.min_budget,hrpa.max_budget,hrpa.confidential_dropdown_option_selected,hrpa.not_sure_dropdown_option_selected,"" as featured,"" as urgent,hrpa.sealed,hrpa.hidden,hrpa.project_posting_date as project_sorted_date,hrpa.escrow_payment_method,hrpa.offline_payment_method,"" as project_completion_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"awarded" as project_status,"" as cancelled_by,hrpa.project_posting_date as po_view_project_date,hrpa.project_owner_id,hrpa.project_expiration_date');
		$this->db->select('"0" as total_active_disputes');
		$this->db->from('hourly_rate_based_projects_awarded as hrpa');
		$this->db->where('hrpa.hidden','N');
		$this->db->where('hrpa.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = hrpa.county_id', 'left');
		$this->db->join('localities', 'localities.id = hrpa.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = hrpa.postal_code_id', 'left');
		$hourly_rate_based_projects_awarded_query = $this->db->get_compiled_select();

		
		
		########################## fetch the project owner in progress projects##########
		$this->db->select(' fbpp.project_id,fbpp.project_title,fbpp.project_description,fbpp.project_type,fbpp.min_budget,fbpp.max_budget,fbpp.confidential_dropdown_option_selected,fbpp.not_sure_dropdown_option_selected,"" as featured,"" as urgent,fbpp.sealed,fbpp.hidden,fbpp.project_posting_date as project_sorted_date,fbpp.escrow_payment_method,fbpp.offline_payment_method, "" as project_completion_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"in_progress" as project_status,"" as cancelled_by,fbpp.project_start_date as po_view_project_date,fbpp.project_owner_id,fbpp.project_expiration_date');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = fbpp.project_id and project_owner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		$this->db->from('fixed_budget_projects_progress as fbpp');
		$this->db->where('fbpp.hidden','N');
		$this->db->where('fbpp.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = fbpp.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbpp.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbpp.postal_code_id', 'left');
		$fixed_budget_projects_in_progress_query = $this->db->get_compiled_select();
		
		
		$this->db->select('hrpp.project_id,hrpp.project_title,hrpp.project_description,hrpp.project_type,hrpp.min_budget,hrpp.max_budget,hrpp.confidential_dropdown_option_selected,hrpp.not_sure_dropdown_option_selected,"" as featured,"" as urgent,hrpp.sealed,hrpp.hidden,hrpp.project_posting_date as project_sorted_date,hrpp.escrow_payment_method,hrpp.offline_payment_method, "" as project_completion_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"in_progress" as project_status,"" as cancelled_by,hrpp.project_start_date as po_view_project_date,hrpp.project_owner_id,hrpp.project_expiration_date');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_disputes where disputed_project_id = hrpp.project_id and project_owner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('hourly_rate_based_projects_progress as hrpp');
		$this->db->where('hrpp.hidden','N');
		$this->db->where('hrpp.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = hrpp.county_id', 'left');
		$this->db->join('localities', 'localities.id = hrpp.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = hrpp.postal_code_id', 'left');
		$hourly_rate_based_projects_in_progress_query = $this->db->get_compiled_select();
		
		
		########################## fetch the project owner incomplete projects##########
		$this->db->select(' fbip.project_id,fbip.project_title,fbip.project_description,fbip.project_type,fbip.min_budget,fbip.max_budget,fbip.confidential_dropdown_option_selected,fbip.not_sure_dropdown_option_selected,"" as featured,"" as urgent,fbip.sealed,fbip.hidden,fbip.project_posting_date as project_sorted_date,fbip.escrow_payment_method,fbip.offline_payment_method, "" as project_completion_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"incomplete" as project_status,"" as cancelled_by,fbip.project_start_date as po_view_project_date,fbip.project_owner_id,fbip.project_expiration_date');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = fbip.project_id and project_owner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('fixed_budget_projects_incomplete as fbip');
		$this->db->where('fbip.hidden','N');
		$this->db->where('fbip.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = fbip.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbip.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbip.postal_code_id', 'left');
		$fixed_budget_projects_in_complete_query = $this->db->get_compiled_select();


		$this->db->select('hrip.project_id,hrip.project_title,hrip.project_description,hrip.project_type,hrip.min_budget,hrip.max_budget,hrip.confidential_dropdown_option_selected,hrip.not_sure_dropdown_option_selected,"" as featured,"" as urgent,hrip.sealed,hrip.hidden,hrip.project_posting_date as project_sorted_date,hrip.escrow_payment_method,hrip.offline_payment_method, "" as project_completion_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"incomplete" as project_status,"" as cancelled_by,hrip.project_start_date as po_view_project_date,hrip.project_owner_id,hrip.project_expiration_date');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_disputes where disputed_project_id = hrip.project_id and project_owner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('hourly_rate_based_projects_incomplete as hrip');
		$this->db->where('hrip.hidden','N');
		$this->db->where('hrip.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = hrip.county_id', 'left');
		$this->db->join('localities', 'localities.id = hrip.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = hrip.postal_code_id', 'left');
		$hourly_rate_based_projects_in_complete_query = $this->db->get_compiled_select();
		

		
		
		########################## fetch the project owner completed projects##########
		$this->db->select(' fbcp.project_id,fbcp.project_title,fbcp.project_description,fbcp.project_type,fbcp.min_budget,fbcp.max_budget,fbcp.confidential_dropdown_option_selected,fbcp.not_sure_dropdown_option_selected,"" as featured,"" as urgent,fbcp.sealed,fbcp.hidden,fbcp.project_posting_date as project_sorted_date,fbcp.escrow_payment_method,fbcp.offline_payment_method,fbcp.project_completion_date as project_completion_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"completed" as project_status,"" as cancelled_by,fbcp.project_start_date as po_view_project_date,fbcp.project_owner_id,fbcp.project_expiration_date');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = fbcp.project_id and project_owner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('fixed_budget_projects_completed as fbcp');
		$this->db->where('fbcp.hidden','N');
		$this->db->where('fbcp.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = fbcp.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbcp.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbcp.postal_code_id', 'left');
		$fixed_budget_projects_completed_query = $this->db->get_compiled_select();

		$this->db->select(' fbcp.project_id,fbcp.project_title,fbcp.project_description,fbcp.project_type,fbcp.min_budget,fbcp.max_budget,fbcp.confidential_dropdown_option_selected,fbcp.not_sure_dropdown_option_selected,"" as featured,"" as urgent,fbcp.sealed,fbcp.hidden,fbcp.project_posting_date as project_sorted_date,fbcp.escrow_payment_method,fbcp.offline_payment_method,fbcp.project_completion_date as project_completion_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"completed" as project_status,"" as cancelled_by,fbcp.project_start_date as po_view_project_date,fbcp.project_owner_id,fbcp.project_expiration_date');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_disputes where disputed_project_id = fbcp.project_id and project_owner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('hourly_rate_based_projects_completed as fbcp');
		$this->db->where('fbcp.hidden','N');
		$this->db->where('fbcp.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = fbcp.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbcp.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbcp.postal_code_id', 'left');
		$hourly_based_projects_completed_query = $this->db->get_compiled_select();

		

		########################## fetch the project owner expired projects##########
		$this->db->select(' fbpe.project_id,fbpe.project_title,fbpe.project_description,fbpe.project_type,fbpe.min_budget,fbpe.max_budget,fbpe.confidential_dropdown_option_selected,fbpe.not_sure_dropdown_option_selected,"" as featured,"" as urgent,fbpe.sealed,fbpe.hidden,fbpe.project_posting_date as project_sorted_date,fbpe.escrow_payment_method,fbpe.offline_payment_method,"" as project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"expired" as project_status,"" as cancelled_by,fbpe.project_expiration_date as po_view_project_date,fbpe.project_owner_id,fbpe.project_expiration_date');
		$this->db->select('"0" as total_active_disputes');
		$this->db->from('fixed_budget_projects_expired as fbpe');
		$this->db->where('fbpe.hidden','N');
		$this->db->where('fbpe.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = fbpe.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbpe.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbpe.postal_code_id', 'left');
		$fixed_budget_projects_expired_query = $this->db->get_compiled_select();

		
		$this->db->select('fpe.fulltime_project_id as project_id,fpe.fulltime_project_title as project_title,fpe.fulltime_project_description as project_description,fpe.project_type,fpe.min_salary as min_budget,fpe.max_salary as max_budget,fpe.confidential_dropdown_option_selected,fpe.not_sure_dropdown_option_selected,"" as featured,"" as urgent,fpe.sealed,fpe.hidden,fpe.fulltime_project_posting_date as project_sorted_date,fpe.escrow_payment_method,fpe.offline_payment_method,"" as project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"expired" as project_status,"" as cancelled_by,fpe.fulltime_project_expiration_date as po_view_project_date,fpe.employer_id as project_owner_id,fpe.fulltime_project_expiration_date as project_expiration_date');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_active_disputes where disputed_fulltime_project_id = fpe.fulltime_project_id and employer_id_of_disputed_fulltime_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('fulltime_projects_expired as fpe');
		$this->db->where('fpe.hidden','N');
		$this->db->where('fpe.employer_id',$user_id);
		
		$this->db->join('counties', 'counties.id = fpe.county_id', 'left');
		$this->db->join('localities', 'localities.id = fpe.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fpe.postal_code_id', 'left');
		$fulltime_projects_expired_query = $this->db->get_compiled_select();

		$this->db->select('hrpe.project_id,hrpe.project_title,hrpe.project_description,hrpe.project_type,hrpe.min_budget,hrpe.max_budget,hrpe.confidential_dropdown_option_selected,hrpe.not_sure_dropdown_option_selected,"" as featured,"" as urgent,hrpe.sealed,hrpe.hidden,hrpe.project_posting_date as project_sorted_date,hrpe.escrow_payment_method,hrpe.offline_payment_method,"" as project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"expired" as project_status,"" as cancelled_by,hrpe.project_expiration_date as po_view_project_date,hrpe.project_owner_id,hrpe.project_expiration_date');
		$this->db->select('"0" as total_active_disputes');
		$this->db->from('hourly_rate_based_projects_expired as hrpe');
		$this->db->where('hrpe.hidden','N');
		$this->db->where('hrpe.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = hrpe.county_id', 'left');
		$this->db->join('localities', 'localities.id = hrpe.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = hrpe.postal_code_id', 'left');
		$hourly_rate_based_projects_expired_query = $this->db->get_compiled_select();

		
		
		########################## fetch the project owner cancelled projects##########
	
		$this->db->select('fbpc.project_id, fbpc.project_title,fbpc.project_description,fbpc.project_type,fbpc.min_budget,fbpc.max_budget,fbpc.confidential_dropdown_option_selected,fbpc.not_sure_dropdown_option_selected,"" as featured,"" as urgent,fbpc.sealed,fbpc.hidden,fbpc.project_posting_date as project_sorted_date,fbpc.escrow_payment_method,fbpc.offline_payment_method,"" as project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"cancelled" as project_status,"user" as cancelled_by,fbpc.project_cancellation_date as po_view_project_date,fbpc.project_owner_id,fbpc.project_expiration_date');
		$this->db->select('"0" as total_active_disputes');
		$this->db->from('fixed_budget_projects_cancelled as fbpc');
		$this->db->where('fbpc.hidden','N');
		$this->db->where('fbpc.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = fbpc.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbpc.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbpc.postal_code_id', 'left');
		$fixed_budget_projects_cancelled_query = $this->db->get_compiled_select();

		

		$this->db->select('fbpca.project_id,fbpca.project_title,fbpca.project_description,fbpca.project_type,fbpca.min_budget,fbpca.max_budget,fbpca.confidential_dropdown_option_selected,fbpca.not_sure_dropdown_option_selected,"" as featured,"" as urgent,fbpca.sealed,fbpca.hidden,fbpca.project_posting_date as project_sorted_date,fbpca.escrow_payment_method,fbpca.offline_payment_method,"" as project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"cancelled" as project_status,"admin" as cancelled_by,fbpca.project_cancellation_date as po_view_project_date,fbpca.project_owner_id,fbpca.project_expiration_date');
		$this->db->select('"0" as total_active_disputes');
		$this->db->from('fixed_budget_projects_cancelled_by_admin as fbpca');
		$this->db->where('fbpca.hidden','N');
		$this->db->where('fbpca.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = fbpca.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbpca.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbpca.postal_code_id', 'left');
		$fixed_budget_projects_cancelled_by_admin_query = $this->db->get_compiled_select();

		
		
		$this->db->select('fpc.fulltime_project_id as project_id,fpc.fulltime_project_title as project_title,fpc.fulltime_project_description as project_description,fpc.project_type,fpc.min_salary as min_budget,fpc.max_salary as max_budget,fpc.confidential_dropdown_option_selected,fpc.not_sure_dropdown_option_selected,"" as featured,"" as urgent,fpc.sealed,fpc.hidden,fpc.fulltime_project_posting_date as project_sorted_date,fpc.escrow_payment_method,fpc.offline_payment_method,"" as project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"cancelled" as project_status,"user" as cancelled_by,fpc.fulltime_project_cancellation_date as po_view_project_date,fpc.employer_id as project_owner_id,fpc.fulltime_project_expiration_date as project_expiration_date');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_active_disputes where disputed_fulltime_project_id = fpc.fulltime_project_id and employer_id_of_disputed_fulltime_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('fulltime_projects_cancelled as fpc');
		$this->db->where('fpc.hidden','N');
		$this->db->where('fpc.employer_id',$user_id);
		$this->db->join('counties', 'counties.id = fpc.county_id', 'left');
		$this->db->join('localities', 'localities.id = fpc.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fpc.postal_code_id', 'left');
		$fulltime_projects_cancelled_query = $this->db->get_compiled_select();

		

		$this->db->select('fpca.fulltime_project_id as project_id, fpca.fulltime_project_title as project_title,fpca.fulltime_project_description as project_description,fpca.project_type,fpca.min_salary as min_budget,fpca.max_salary as max_budget,fpca.confidential_dropdown_option_selected,fpca.not_sure_dropdown_option_selected,"" as featured,"" as urgent,fpca.sealed,fpca.hidden,fpca.fulltime_project_posting_date as project_sorted_date,fpca.escrow_payment_method,fpca.offline_payment_method,"" as project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"cancelled" as project_status,"admin" as cancelled_by,fpca.fulltime_project_cancellation_date as po_view_project_date,fpca.employer_id as project_owner_id,fpca.fulltime_project_expiration_date as project_expiration_date');
		$this->db->select('"0" as total_active_disputes');
		$this->db->from('fulltime_projects_cancelled_by_admin as fpca');
		$this->db->where('fpca.hidden','N');
		$this->db->where('fpca.employer_id',$user_id);
		$this->db->join('counties', 'counties.id = fpca.county_id', 'left');
		$this->db->join('localities', 'localities.id = fpca.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fpca.postal_code_id', 'left');
		$fulltime_projects_cancelled_by_admin_query = $this->db->get_compiled_select();

		
		
		
		$this->db->select('hrpc.project_id, hrpc.project_title,hrpc.project_description,hrpc.project_type,hrpc.min_budget,hrpc.max_budget,hrpc.confidential_dropdown_option_selected,hrpc.not_sure_dropdown_option_selected,"" as featured,"" as urgent,hrpc.sealed,hrpc.hidden,hrpc.project_posting_date as project_sorted_date,hrpc.escrow_payment_method,hrpc.offline_payment_method,"" as project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"cancelled" as project_status,"user" as cancelled_by,hrpc.project_cancellation_date as po_view_project_date,hrpc.project_owner_id,hrpc.project_expiration_date');
		$this->db->select('"0" as total_active_disputes');
		$this->db->from('hourly_rate_based_projects_cancelled as hrpc');
		$this->db->where('hrpc.hidden','N');
		$this->db->where('hrpc.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = hrpc.county_id', 'left');
		$this->db->join('localities', 'localities.id = hrpc.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = hrpc.postal_code_id', 'left');
		$hourly_rate_based_projects_cancelled_query = $this->db->get_compiled_select();

		
		
		$this->db->select('hrpca.project_id,hrpca.project_title,hrpca.project_description,hrpca.project_type,hrpca.min_budget,hrpca.max_budget,hrpca.confidential_dropdown_option_selected,hrpca.not_sure_dropdown_option_selected,"" as featured,"" as urgent,hrpca.sealed,hrpca.hidden,hrpca.project_posting_date as project_sorted_date,hrpca.escrow_payment_method,hrpca.offline_payment_method,"" as project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"cancelled" as project_status,"admin" as cancelled_by,hrpca.project_cancellation_date as po_view_project_date,hrpca.project_owner_id,hrpca.project_expiration_date');
		$this->db->select('"0" as total_active_disputes');
		$this->db->from('hourly_rate_based_projects_cancelled_by_admin as hrpca');
		$this->db->where('hrpca.hidden','N');
		$this->db->where('hrpca.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = hrpca.county_id', 'left');
		$this->db->join('localities', 'localities.id = hrpca.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = hrpca.postal_code_id', 'left');
		$hourly_rate_based_projects_cancelled_by_admin_query = $this->db->get_compiled_select();

		$tables_name_array = [
			$open_for_bidding_projects_query,
			$fixed_budget_projects_awarded_query,
			$hourly_rate_based_projects_awarded_query,
			$fixed_budget_projects_in_progress_query,
			$hourly_rate_based_projects_in_progress_query,
			$fixed_budget_projects_in_complete_query,
			$hourly_rate_based_projects_in_complete_query,
			$fixed_budget_projects_completed_query,
			$hourly_based_projects_completed_query,
			$fixed_budget_projects_expired_query,
			$fulltime_projects_expired_query,
			$hourly_rate_based_projects_expired_query,
			$fixed_budget_projects_cancelled_query,
			$fixed_budget_projects_cancelled_by_admin_query,
			$fulltime_projects_cancelled_query,
			$fulltime_projects_cancelled_by_admin_query,
			$hourly_rate_based_projects_cancelled_query,
			$hourly_rate_based_projects_cancelled_by_admin_query
			
		];
		 

		

		$project_result_query = $this->db->query(implode(' UNION ', $tables_name_array).' ORDER BY project_sorted_date DESC LIMIT '.$limit_range);
		// $project_result_query = $this->db->query($open_for_bidding_projects_query . ' UNION ' .$fixed_budget_projects_expired_query .' UNION '.$fulltime_projects_expired_query .' UNION '.$hourly_rate_based_projects_expired_query. ' UNION ' .$fixed_budget_projects_awarded_query .'  UNION '.$hourly_rate_based_projects_awarded_query . ' UNION ' .$fixed_budget_projects_in_progress_query .' UNION '.$hourly_rate_based_projects_in_progress_query.' UNION '. $fixed_budget_projects_completed_query . ' UNION '.$fixed_budget_projects_cancelled_query . ' UNION ' . $fixed_budget_projects_cancelled_by_admin_query . ' UNION ' . $fulltime_projects_cancelled_query . ' UNION ' . $fulltime_projects_cancelled_by_admin_query. ' UNION ' . $hourly_rate_based_projects_cancelled_query . ' UNION ' . $hourly_rate_based_projects_cancelled_by_admin_query.' UNION '.$hourly_based_projects_completed_query.' ORDER BY project_sorted_date DESC LIMIT '.$limit_range);
		$result  = $project_result_query->result_array();
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	
		
	}
	
	/**
	* This function is used to fetch the draft projects on dedicated page of my projects .
	*/
	public function get_po_draft_projects_listing_my_projects($user_id,$start = '', $limit = ''){
		
		
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS pd.project_id,pd.project_owner_id,pd.project_title,pd.project_description,pd.project_type,pd.min_budget,pd.max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.featured,pd.urgent,pd.sealed,pd.hidden,pd.project_save_date,pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code', false);
		$this->db->from('projects_draft pd');
		$this->db->where('pd.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$this->db->order_by('pd.project_save_date','desc');
		
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		$draft_project_result = $this->db->get();
		$draft_project_data = $draft_project_result->result_array();
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		 return ['data' => $draft_project_data, 'total' => $total_rec];
		
	}
	
	
	/**
		* This function is used to fetch the awaiting moderation projects on dedicated page of my projects .
	*/
	public function get_po_awaiting_moderation_projects_listing_my_projects($user_id,$start = '', $limit = ''){
		
		
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS am.project_id,am.project_title,am.project_description,am.project_type,am.min_budget,am.max_budget,am.confidential_dropdown_option_selected,am.not_sure_dropdown_option_selected,am.featured,am.urgent,am.sealed,am.hidden,am.project_submission_to_moderation_date,am.escrow_payment_method,am.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code', false);
		$this->db->from('projects_awaiting_moderation am');
		$this->db->where('am.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = am.county_id', 'left');
		$this->db->join('localities', 'localities.id = am.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = am.postal_code_id', 'left');
		$this->db->order_by('am.project_submission_to_moderation_date','desc');
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		$awaiting_moderation_project_result = $this->db->get();
		$awaiting_moderation_project_data = $awaiting_moderation_project_result->result_array();
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		 return ['data' => $awaiting_moderation_project_data, 'total' => $total_rec];
		
	}
	
	/**
	* This function is used to fetch the open for bidding projects of users .
	*/
	public function get_po_open_for_bidding_projects_listing_my_projects($user_id,$start = '', $limit = ''){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}

		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.featured,op.urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date',false);
		$this->db->select(" '' as bid_count, op.offline_payment_method, op.escrow_payment_method");
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_active_disputes where disputed_fulltime_project_id = op.project_id and employer_id_of_disputed_fulltime_project = "'.$user_id.'") as total_active_disputes');

	

		$this->db->from('projects_open_bidding op');
		$this->db->where('op.project_owner_id',$user_id);
		$this->db->where('op.project_expiration_date >= NOW()');
		$this->db->join('counties', 'counties.id = op.county_id', 'left');
		$this->db->join('localities', 'localities.id = op.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured" and  project_owner_id = "'.$user_id.'" group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = op.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and  project_owner_id = "'.$user_id.'" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = op.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_owner_id = "'.$user_id.'" group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = op.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user_id.'" group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = op.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user_id.'" group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = op.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user_id.'" group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = op.project_id', 'left');
		// $this->db->order_by('op.project_posting_date','desc');

		$open_bidding_project_query = $this->db->get_compiled_select();

		$this->db->select('op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,"N" as featured,"N" as urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"0" as featured_upgrade_end_date,"0" as bonus_featured_upgrade_end_date,"0" as urgent_upgrade_end_date,"0" as bonus_urgent_upgrade_end_date,"0" as membership_include_featured_upgrade_end_date,"0" as membership_include_urgent_upgrade_end_date');
		$this->db->select("op.offline_payment_method, op.escrow_payment_method");
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'fixed_budget_projects_awarded_tracking WHERE project_award_expiration_date >= NOW() AND project_id = op.project_id) as bid_count');
		$this->db->select(" '0' as total_active_disputes");
		$this->db->from('fixed_budget_projects_awarded as op');
		$this->db->where('op.project_expiration_date >= NOW()');
		$this->db->where('op.project_owner_id',$user_id);
		$this->db->having('bid_count = 0');
		$this->db->join('counties', 'counties.id = op.county_id', 'left');
		$this->db->join('localities', 'localities.id = op.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
		$fixed_budget_projects_awarded_query = $this->db->get_compiled_select();

		$this->db->select('op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,"N" as featured,"N" as urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,"0" as featured_upgrade_end_date,"0" as bonus_featured_upgrade_end_date,"0" as urgent_upgrade_end_date,"0" as bonus_urgent_upgrade_end_date,"0" as membership_include_featured_upgrade_end_date,"0" as membership_include_urgent_upgrade_end_date');
		$this->db->select("op.offline_payment_method, op.escrow_payment_method");
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'hourly_rate_based_projects_awarded_tracking WHERE project_award_expiration_date >= NOW() AND project_id = op.project_id) as bid_count');
		$this->db->select(" '0' as total_active_disputes");
		$this->db->from('hourly_rate_based_projects_awarded as op');
		$this->db->where('op.project_owner_id',$user_id);
		$this->db->where('op.project_expiration_date >= NOW()');
		$this->db->having('bid_count = 0');
		$this->db->join('counties', 'counties.id = op.county_id', 'left');
		$this->db->join('localities', 'localities.id = op.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
		$hourly_rate_based_projects_awarded_query = $this->db->get_compiled_select();

		$union_table_name = [
			$open_bidding_project_query,
			$fixed_budget_projects_awarded_query,
			$hourly_rate_based_projects_awarded_query
		];
		
		$project_awarded_query = $this->db->query(implode(' UNION ', $union_table_name) .' ORDER BY project_posting_date DESC LIMIT '.$limit_range);
		$open_bidding_project_data  = $project_awarded_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		 return ['data' => $open_bidding_project_data, 'total' => $total_rec];
		
	}
	
	/**
	* This function is used to fetch the awarded projects of users on my projects dedicated page .
	*/
	public function get_po_awarded_projects_listing_my_projects($user_id,$start = '', $limit = ''){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$this->db->select('SQL_CALC_FOUND_ROWS fbpa.project_id,fbpa.project_owner_id,fbpa.project_title,fbpa.project_description,fbpa.project_type,fbpa.min_budget,fbpa.max_budget,fbpa.confidential_dropdown_option_selected,fbpa.not_sure_dropdown_option_selected,fbpa.project_posting_date,fbpa.project_expiration_date,fbpa.escrow_payment_method,fbpa.sealed,fbpa.hidden,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code',false);
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'fixed_budget_projects_awarded_tracking WHERE project_award_expiration_date >= NOW() AND project_id = fbpa.project_id) as bid_count');
		$this->db->from('fixed_budget_projects_awarded as fbpa');
		$this->db->where('fbpa.project_owner_id',$user_id);
		$this->db->having('bid_count > 0');
		$this->db->join('counties', 'counties.id = fbpa.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbpa.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbpa.postal_code_id', 'left');
		$fixed_budget_projects_awarded_query = $this->db->get_compiled_select();


		$this->db->select('hrpa.project_id,hrpa.project_owner_id,hrpa.project_title,hrpa.project_description,hrpa.project_type,hrpa.min_budget,hrpa.max_budget,hrpa.confidential_dropdown_option_selected,hrpa.not_sure_dropdown_option_selected,hrpa.project_posting_date,hrpa.project_expiration_date,hrpa.escrow_payment_method,hrpa.sealed,hrpa.hidden,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'hourly_rate_based_projects_awarded_tracking WHERE project_award_expiration_date >= NOW() AND project_id = hrpa.project_id) as bid_count');
		$this->db->from('hourly_rate_based_projects_awarded as hrpa');
		$this->db->where('hrpa.project_owner_id',$user_id);
		$this->db->having('bid_count > 0');
		$this->db->join('counties', 'counties.id = hrpa.county_id', 'left');
		$this->db->join('localities', 'localities.id = hrpa.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = hrpa.postal_code_id', 'left');
		$hourly_rate_based_projects_awarded_query = $this->db->get_compiled_select();

		$union_table_name = [
			$fixed_budget_projects_awarded_query,
			$hourly_rate_based_projects_awarded_query
		];

		$project_awarded_query = $this->db->query(implode(' UNION ', $union_table_name) .' ORDER BY project_posting_date DESC LIMIT '.$limit_range);
		$result  = $project_awarded_query->result_array();
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
		
		
	}
	
	
	/**
	* This function is used to fetch the in progress projects of users on my projects dedicated page .
	*/
	public function get_po_in_progress_projects_listing_my_projects($user_id,$start = '', $limit = ''){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS fbpp.project_id,fbpp.project_owner_id,fbpp.project_title,fbpp.project_description,fbpp.project_type,fbpp.min_budget,fbpp.max_budget,fbpp.confidential_dropdown_option_selected,fbpp.not_sure_dropdown_option_selected,fbpp.project_posting_date,fbpp.project_expiration_date,fbpp.escrow_payment_method,fbpp.sealed,fbpp.hidden,fbpp.project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code',false);
		
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = fbpp.project_id and project_owner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		/* $this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date');
		 */
		
		
		$this->db->from('fixed_budget_projects_progress as fbpp');
		$this->db->where('fbpp.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = fbpp.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbpp.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbpp.postal_code_id', 'left');
		
		/* $this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  project_owner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = fbpp.project_id', 'left'); */
		
		
		$fixed_budget_projects_in_progress_query = $this->db->get_compiled_select();
		
		######################################################

		$this->db->select('hrpp.project_id,hrpp.project_owner_id,hrpp.project_title,hrpp.project_description,hrpp.project_type,hrpp.min_budget,hrpp.max_budget,hrpp.confidential_dropdown_option_selected,hrpp.not_sure_dropdown_option_selected,hrpp.project_posting_date,hrpp.project_expiration_date,hrpp.escrow_payment_method,hrpp.sealed,hrpp.hidden,hrpp.project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
		
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_disputes where disputed_project_id = hrpp.project_id and project_owner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		/* $this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date');
		 */
		
		$this->db->from('hourly_rate_based_projects_progress as hrpp');
		$this->db->where('hrpp.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = hrpp.county_id', 'left');
		$this->db->join('localities', 'localities.id = hrpp.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = hrpp.postal_code_id', 'left');
		
		/* $this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  project_owner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = hrpp.project_id', 'left'); */
		
		
		$hourly_rate_based_projects_in_progress_query = $this->db->get_compiled_select();
		$project_in_progress_query = $this->db->query($fixed_budget_projects_in_progress_query . ' UNION ' . $hourly_rate_based_projects_in_progress_query .'ORDER BY project_posting_date DESC LIMIT '.$limit_range);
		$result  = $project_in_progress_query->result_array();
		
		/* echo "<pre>";
		print_r($result);
		die;
		 */
		
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
		
		
		
	}
	
	/**
	* This function is used to fetch the in complete projects of users on my projects dedicated page .
	*/
	public function get_po_incomplete_projects_listing_my_projects($user_id,$start = '', $limit = ''){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS fbip.project_id,fbip.project_owner_id,fbip.project_title,fbip.project_description,fbip.project_type,fbip.min_budget,fbip.max_budget,fbip.confidential_dropdown_option_selected,fbip.not_sure_dropdown_option_selected,fbip.project_posting_date,fbip.project_expiration_date,fbip.escrow_payment_method,fbip.sealed,fbip.hidden,fbip.project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code',false);
		
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = fbip.project_id and project_owner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		/* $this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date'); */
		
		
		
		$this->db->from('fixed_budget_projects_incomplete as fbip');
		$this->db->where('fbip.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = fbip.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbip.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbip.postal_code_id', 'left');
		
		/* $this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  project_owner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = fbip.project_id', 'left'); */
		
		$fixed_budget_projects_in_complete_query = $this->db->get_compiled_select();
		
		// for hourly projects
		
		$this->db->select('hrip.project_id,hrip.project_owner_id,hrip.project_title,hrip.project_description,hrip.project_type,hrip.min_budget,hrip.max_budget,hrip.confidential_dropdown_option_selected,hrip.not_sure_dropdown_option_selected,hrip.project_posting_date,hrip.project_expiration_date,hrip.escrow_payment_method,hrip.sealed,hrip.hidden,hrip.project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
		
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_disputes where disputed_project_id = hrip.project_id and project_owner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		/*  $this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date'); */
		
		$this->db->from('hourly_rate_based_projects_incomplete as hrip');
		$this->db->where('hrip.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = hrip.county_id', 'left');
		$this->db->join('localities', 'localities.id = hrip.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = hrip.postal_code_id', 'left');
		
		/* $this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  project_owner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = fbcp.project_id', 'left');  */
		
		$hourly_rate_based_projects_incomplete_query = $this->db->get_compiled_select();
		
		

		/* $this->db->select('hrpp.project_id,hrpp.project_title,hrpp.project_description,hrpp.project_type,hrpp.min_budget,hrpp.max_budget,hrpp.confidential_dropdown_option_selected,hrpp.not_sure_dropdown_option_selected,hrpp.project_posting_date,hrpp.project_expiration_date,hrpp.escrow_payment_method,hrpp.sealed,hrpp.hidden,hrpp.project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
		$this->db->from('hourly_rate_based_projects_progress as hrpp');
		$this->db->where('hrpp.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = hrpp.county_id', 'left');
		$this->db->join('localities', 'localities.id = hrpp.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = hrpp.postal_code_id', 'left');
		$hourly_rate_based_projects_in_progress_query = $this->db->get_compiled_select(); 
		$project_in_progress_query = $this->db->query($fixed_budget_projects_in_progress_query . ' UNION ' . $hourly_rate_based_projects_in_progress_query .'ORDER BY project_posting_date DESC LIMIT '.$limit_range);*/
		
		$project_in_complete_query = $this->db->query($fixed_budget_projects_in_complete_query .' UNION '.$hourly_rate_based_projects_incomplete_query. 'ORDER BY project_posting_date DESC LIMIT '.$limit_range);
		
		$result  = $project_in_complete_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		
		/* echo "<pre>";
		print_r($result);
		die; */
		
		
		return ['data' => $result, 'total' => $total_rec];
		
	}
	
	/**
	* This function is used to fetch the in progress projects of users on my projects dedicated page .
	*/
	public function get_po_completed_projects_listing_my_projects($user_id,$start = '', $limit = ''){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS fbcp.project_id,fbcp.project_owner_id,fbcp.project_title,fbcp.project_description,fbcp.project_type,fbcp.min_budget,fbcp.max_budget,fbcp.confidential_dropdown_option_selected,fbcp.not_sure_dropdown_option_selected,fbcp.project_posting_date,fbcp.project_completion_date,fbcp.escrow_payment_method,fbcp.sealed,fbcp.hidden,fbcp.project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code',false);
		
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = fbcp.project_id and project_owner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		/* $this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date'); */
		
		$this->db->from('fixed_budget_projects_completed as fbcp');
		$this->db->where('fbcp.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = fbcp.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbcp.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbcp.postal_code_id', 'left');
		
		/* $this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  project_owner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = fbcp.project_id', 'left'); */
		
		$fixed_budget_projects_completed_query = $this->db->get_compiled_select();


		##################################

		$this->db->select(' hrcp.project_id,hrcp.project_owner_id,hrcp.project_title,hrcp.project_description,hrcp.project_type,hrcp.min_budget,hrcp.max_budget,hrcp.confidential_dropdown_option_selected,hrcp.not_sure_dropdown_option_selected,hrcp.project_posting_date,hrcp.project_completion_date,hrcp.escrow_payment_method,hrcp.sealed,hrcp.hidden,hrcp.project_start_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
		
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_disputes where disputed_project_id = hrcp.project_id and project_owner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		/*  $this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date'); */
		
		$this->db->from('hourly_rate_based_projects_completed as hrcp');
		$this->db->where('hrcp.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = hrcp.county_id', 'left');
		$this->db->join('localities', 'localities.id = hrcp.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = hrcp.postal_code_id', 'left');
		
		/* $this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  project_owner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = fbcp.project_id', 'left');  */
		
		$hourly_rate_based_projects_completed_query = $this->db->get_compiled_select();

	
		$project_completed_query = $this->db->query($fixed_budget_projects_completed_query .' UNION '.$hourly_rate_based_projects_completed_query.' ORDER BY project_completion_date DESC LIMIT '.$limit_range);
		$result  = $project_completed_query->result_array();
		
		/* echo "<pre>";
		print_r($result);
		die;  */
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
		
	}
	
	
	/**
	* This function is used to fetch the cancelled projects of users on my projects dedicated page.
	*/
	public function get_po_cancelled_project_listing_my_projects($user_id,$start = '', $limit = ''){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$this->db->select('SQL_CALC_FOUND_ROWS fbpc.project_id,"N" as cancelled_by_admin, "user" as cancelled_by, fbpc.project_title,fbpc.project_owner_id,fbpc.project_description,fbpc.project_type,fbpc.min_budget,fbpc.max_budget,fbpc.confidential_dropdown_option_selected,fbpc.not_sure_dropdown_option_selected,fbpc.sealed,fbpc.hidden,fbpc.project_posting_date,fbpc.project_expiration_date,fbpc.project_cancellation_date,fbpc.escrow_payment_method,fbpc.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code',false);
		$this->db->select('"0" as total_active_disputes');
		$this->db->from('fixed_budget_projects_cancelled as fbpc');
		$this->db->where('fbpc.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = fbpc.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbpc.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbpc.postal_code_id', 'left');
		$fixed_budget_projects_cancelled_query = $this->db->get_compiled_select();

		$this->db->select('fbpca.project_id,fbpca.cancelled_by_admin,"admin" as cancelled_by,fbpca.project_title,fbpca.project_owner_id,fbpca.project_description,fbpca.project_type,fbpca.min_budget,fbpca.max_budget,fbpca.confidential_dropdown_option_selected,fbpca.not_sure_dropdown_option_selected,fbpca.sealed,fbpca.hidden,fbpca.project_posting_date,fbpca.project_expiration_date,fbpca.project_cancellation_date,fbpca.escrow_payment_method,fbpca.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
		$this->db->select('"0" as total_active_disputes');
		$this->db->from('fixed_budget_projects_cancelled_by_admin as fbpca');
		$this->db->where('fbpca.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = fbpca.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbpca.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbpca.postal_code_id', 'left');
		$fixed_budget_projects_cancelled_by_admin_query = $this->db->get_compiled_select();
		
		$this->db->select('fpc.fulltime_project_id as project_id,"N" as cancelled_by_admin, "user" as cancelled_by,fpc.fulltime_project_title as project_title,fpc.employer_id as project_owner_id,fpc.fulltime_project_description as project_description,fpc.project_type,fpc.min_salary as min_budget,fpc.max_salary as max_budget,fpc.confidential_dropdown_option_selected,fpc.not_sure_dropdown_option_selected,fpc.sealed,fpc.hidden,fpc.fulltime_project_posting_date as project_posting_date,fpc.fulltime_project_expiration_date as project_expiration_date,fpc.fulltime_project_cancellation_date as project_cancellation_date,fpc.escrow_payment_method,fpc.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_active_disputes where disputed_fulltime_project_id = fpc.fulltime_project_id and employer_id_of_disputed_fulltime_project = "'.$user_id.'") as total_active_disputes');


		$this->db->from('fulltime_projects_cancelled as fpc');
		$this->db->where('fpc.employer_id',$user_id);
		$this->db->join('counties', 'counties.id = fpc.county_id', 'left');
		$this->db->join('localities', 'localities.id = fpc.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fpc.postal_code_id', 'left');
		$fulltime_projects_cancelled_query = $this->db->get_compiled_select();

		$this->db->select('fpca.fulltime_project_id as project_id,fpca.cancelled_by_admin, "admin" as cancelled_by, fpca.fulltime_project_title as project_title,fpca.employer_id as project_owner_id,fpca.fulltime_project_description as project_description,fpca.project_type,fpca.min_salary as min_budget,fpca.max_salary as max_budget,fpca.confidential_dropdown_option_selected,fpca.not_sure_dropdown_option_selected,fpca.sealed,fpca.hidden,fpca.fulltime_project_posting_date as project_posting_date,fpca.fulltime_project_expiration_date as project_expiration_date,fpca.fulltime_project_cancellation_date as project_cancellation_date,fpca.escrow_payment_method,fpca.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_active_disputes where disputed_fulltime_project_id = fpca.fulltime_project_id and employer_id_of_disputed_fulltime_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('fulltime_projects_cancelled_by_admin as fpca');
		$this->db->where('fpca.employer_id',$user_id);
		$this->db->join('counties', 'counties.id = fpca.county_id', 'left');
		$this->db->join('localities', 'localities.id = fpca.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fpca.postal_code_id', 'left');
		$fulltime_projects_cancelled_by_admin_query = $this->db->get_compiled_select();
		
		
		$this->db->select('hrpc.project_id,"N" as cancelled_by_admin, "user" as cancelled_by, hrpc.project_title,hrpc.project_owner_id,hrpc.project_description,hrpc.project_type,hrpc.min_budget,hrpc.max_budget,hrpc.confidential_dropdown_option_selected,hrpc.not_sure_dropdown_option_selected,hrpc.sealed,hrpc.hidden,hrpc.project_posting_date,hrpc.project_expiration_date,hrpc.project_cancellation_date,hrpc.escrow_payment_method,hrpc.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
		$this->db->select('"0" as total_active_disputes');
		$this->db->from('hourly_rate_based_projects_cancelled as hrpc');
		$this->db->where('hrpc.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = hrpc.county_id', 'left');
		$this->db->join('localities', 'localities.id = hrpc.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = hrpc.postal_code_id', 'left');
		$hourly_rate_based_projects_cancelled_query = $this->db->get_compiled_select();
		
		$this->db->select('hrpca.project_id,hrpca.cancelled_by_admin, "admin" as cancelled_by,hrpca.project_title,hrpca.project_owner_id,hrpca.project_description,hrpca.project_type,hrpca.min_budget,hrpca.max_budget,hrpca.confidential_dropdown_option_selected,hrpca.not_sure_dropdown_option_selected,hrpca.sealed,hrpca.hidden,hrpca.project_posting_date,hrpca.project_expiration_date,hrpca.project_cancellation_date,hrpca.escrow_payment_method,hrpca.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
		$this->db->select('"0" as total_active_disputes');
		$this->db->from('hourly_rate_based_projects_cancelled_by_admin as hrpca');
		$this->db->where('hrpca.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = hrpca.county_id', 'left');
		$this->db->join('localities', 'localities.id = hrpca.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = hrpca.postal_code_id', 'left');
		$hourly_rate_based_projects_cancelled_by_admin_query = $this->db->get_compiled_select();
		
		$project_cancelled_query = $this->db->query($fixed_budget_projects_cancelled_query . ' UNION ' . $fixed_budget_projects_cancelled_by_admin_query . ' UNION ' . $fulltime_projects_cancelled_query . ' UNION ' . $fulltime_projects_cancelled_by_admin_query. ' UNION ' . $hourly_rate_based_projects_cancelled_query . ' UNION ' . $hourly_rate_based_projects_cancelled_by_admin_query.' ORDER BY project_cancellation_date DESC LIMIT '.$limit_range);
		$result  = $project_cancelled_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		
		
		return ['data' => $result, 'total' => $total_rec];
		
	}
	
	/**
	* This function is used to fetch the expired projects of users on my projects dedicated page.
	*/
	public function get_po_expired_projects_listing_my_projects($user_id,$start = '', $limit = ''){
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$this->db->select('SQL_CALC_FOUND_ROWS fbpe.project_id,fbpe.project_title,fbpe.project_owner_id,fbpe.project_description,fbpe.project_type,fbpe.min_budget,fbpe.max_budget,fbpe.confidential_dropdown_option_selected,fbpe.not_sure_dropdown_option_selected,fbpe.project_posting_date,fbpe.project_expiration_date,fbpe.escrow_payment_method,fbpe.sealed,fbpe.hidden,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code',false);
		$this->db->select("fbpe.offline_payment_method, fbpe.escrow_payment_method");
		$this->db->select(" '' as bid_count");
		$this->db->select(" '0' as total_active_disputes");
		$this->db->from('fixed_budget_projects_expired as fbpe');
		$this->db->where('fbpe.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = fbpe.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbpe.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbpe.postal_code_id', 'left');
		$fixed_budget_projects_expired_query = $this->db->get_compiled_select();

		$this->db->select('fbpa.project_id,fbpa.project_title,fbpa.project_owner_id,fbpa.project_description,fbpa.project_type,fbpa.min_budget,fbpa.max_budget,fbpa.confidential_dropdown_option_selected,fbpa.not_sure_dropdown_option_selected,fbpa.project_posting_date,fbpa.project_expiration_date,fbpa.escrow_payment_method,fbpa.sealed,fbpa.hidden,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
		$this->db->select("fbpa.offline_payment_method, fbpa.escrow_payment_method");
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'fixed_budget_projects_awarded_tracking WHERE project_award_expiration_date >= NOW() AND project_id = fbpa.project_id) as bid_count');
		$this->db->select(" '0' as total_active_disputes");
		$this->db->from('fixed_budget_projects_awarded as fbpa');
		$this->db->where('fbpa.project_owner_id',$user_id);
		$this->db->where('fbpa.project_expiration_date <=',date('Y-m-d H:i:s'));
		$this->db->having('bid_count <= 0');
		$this->db->join('counties', 'counties.id = fbpa.county_id', 'left');
		$this->db->join('localities', 'localities.id = fbpa.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fbpa.postal_code_id', 'left');
		$fixed_budget_projects_awarded_expired_query = $this->db->get_compiled_select();
		
		$this->db->select('fpe.fulltime_project_id as project_id,fpe.fulltime_project_title as project_title,fpe.employer_id as project_owner_id,fpe.fulltime_project_description as project_description,fpe.project_type,fpe.min_salary as min_budget,fpe.max_salary as max_budget,fpe.confidential_dropdown_option_selected,fpe.not_sure_dropdown_option_selected,fpe.fulltime_project_posting_date as project_posting_date,fpe.fulltime_project_expiration_date as project_expiration_date,fpe.escrow_payment_method,fpe.sealed,fpe.hidden,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
		$this->db->select("fpe.offline_payment_method, fpe.escrow_payment_method");
		$this->db->select(" '' as bid_count");
		$this->db->select("(SELECT count(*)  FROM ".$this->db->dbprefix."fulltime_projects_active_disputes where disputed_fulltime_project_id = fpe.fulltime_project_id and employer_id_of_disputed_fulltime_project = '".$user_id."') as total_active_disputes");
		$this->db->from('fulltime_projects_expired as fpe');
		$this->db->where('fpe.employer_id',$user_id);
		$this->db->join('counties', 'counties.id = fpe.county_id', 'left');
		$this->db->join('localities', 'localities.id = fpe.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = fpe.postal_code_id', 'left');
		$fulltime_projects_expired_query = $this->db->get_compiled_select();

		$this->db->select('hrpe.project_id,hrpe.project_title,hrpe.project_owner_id,hrpe.project_description,hrpe.project_type,hrpe.min_budget,hrpe.max_budget,hrpe.confidential_dropdown_option_selected,hrpe.not_sure_dropdown_option_selected,hrpe.project_posting_date,hrpe.project_expiration_date,hrpe.escrow_payment_method,hrpe.sealed,hrpe.hidden,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
		$this->db->select("hrpe.offline_payment_method, hrpe.escrow_payment_method");
		$this->db->select(" '' as bid_count");
		$this->db->select(" '0' as total_active_disputes");
		$this->db->from('hourly_rate_based_projects_expired as hrpe');
		$this->db->where('hrpe.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = hrpe.county_id', 'left');
		$this->db->join('localities', 'localities.id = hrpe.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = hrpe.postal_code_id', 'left');
		$hourly_rate_based_projects_expired_query = $this->db->get_compiled_select();

		$this->db->select('hrpa.project_id,hrpa.project_title,hrpa.project_owner_id,hrpa.project_description,hrpa.project_type,hrpa.min_budget,hrpa.max_budget,hrpa.confidential_dropdown_option_selected,hrpa.not_sure_dropdown_option_selected,hrpa.project_posting_date,hrpa.project_expiration_date,hrpa.escrow_payment_method,hrpa.sealed,hrpa.hidden,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
		$this->db->select("hrpa.offline_payment_method, hrpa.escrow_payment_method");
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'hourly_rate_based_projects_awarded_tracking WHERE project_award_expiration_date >= NOW() AND project_id = hrpa.project_id) as bid_count');
		$this->db->select(" '0' as total_active_disputes");	
		$this->db->from('hourly_rate_based_projects_awarded as hrpa');
		$this->db->where('hrpa.project_owner_id',$user_id);
		$this->db->where('hrpa.project_expiration_date <=',date('Y-m-d H:i:s'));
		$this->db->having('bid_count <= 0');
		$this->db->join('counties', 'counties.id = hrpa.county_id', 'left');
		$this->db->join('localities', 'localities.id = hrpa.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = hrpa.postal_code_id', 'left');
		$hourly_rate_based_projects_awarded_expired_query = $this->db->get_compiled_select();
		
		// expired project but exists in open for bidding table
		$this->db->select('op.project_id,op.project_title,op.project_owner_id,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,op.sealed,op.hidden,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
		$this->db->select("op.offline_payment_method, op.escrow_payment_method");
		$this->db->select(" '' as bid_count");
		$this->db->select("(SELECT count(*)  FROM ".$this->db->dbprefix."fulltime_projects_active_disputes where disputed_fulltime_project_id = op.project_id and employer_id_of_disputed_fulltime_project = '".$user_id."') as total_active_disputes");
		$this->db->from('projects_open_bidding as op');
		$this->db->where('op.project_expiration_date < NOW()');
		$this->db->where('op.project_owner_id',$user_id);
		$this->db->join('counties', 'counties.id = op.county_id', 'left');
		$this->db->join('localities', 'localities.id = op.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
		$open_projects_expired_query = $this->db->get_compiled_select();

		$union_table_name = [
			$fixed_budget_projects_expired_query,
			$fixed_budget_projects_awarded_expired_query,
			$fulltime_projects_expired_query,
			$hourly_rate_based_projects_expired_query,
			$hourly_rate_based_projects_awarded_expired_query,
			$open_projects_expired_query
		];
		
		$project_expired_query = $this->db->query(implode(' UNION ', $union_table_name) .' ORDER BY project_expiration_date DESC LIMIT '.$limit_range);
		
		
		
		$result  = $project_expired_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
		
	}
	
	
	
	// This function count the total completed  projects of project owner
	public function get_po_completed_projects_count($conditions){
	
		$projects_completed_tables_array = array('fixed_budget_projects_completed', 'hourly_rate_based_projects_completed');
		$po_total_completed_projects_count = 0;
		foreach($projects_completed_tables_array as $table_name){
			$po_total_completed_projects_count += $this->db->where($conditions)->from($table_name)->count_all_results();
		
		}
		return $po_total_completed_projects_count;
		
	}
	
	
	/**
	This function is used count the number of project invitation sent by po included his membership of user per month.
	*/
	function count_po_sent_project_invitations_membership_included_monthly($user_id){
		$membership_included_project_invites_count = $this->db
		->select ('id')
		->from ('projects_invitations_tracking')
		->where ('po_id',$user_id)
		->where ('MONTH(invitation_sent_date)',date('m'))
		->get ()->num_rows ();
		return $membership_included_project_invites_count;
   
	}
	/**
	 * This method is used to save user visits on projects and return unique count
	 */
	function save_projects_details_pages_visits_and_get_count($project_id) {
		$result = $this->db->get_where('projects_details_pages_visits_tracking', ['project_id' => $project_id, 'visitor_source_ip' => get_client_ip()])->row_array();
		if(empty($result)) {
			$data = [
				'project_id' => $project_id, 
				'visitor_source_ip' => get_client_ip(),
				'number_of_visits' => 1
			];
			$this->db->insert('projects_details_pages_visits_tracking', $data);
		} else {
			$this->db->set('number_of_visits', 'number_of_visits + 1', false);
			$this->db->where('id', $result['id']);
			$this->db->update('projects_details_pages_visits_tracking');
		}
		return $this->db->from('projects_details_pages_visits_tracking')->where('project_id', $project_id)->count_all_results();
	}
	
}
?>