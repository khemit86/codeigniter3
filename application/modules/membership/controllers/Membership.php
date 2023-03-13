<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Membership extends MX_Controller {



    /**

     * Description: this used for check the user is exsts or not if exists then it redirect to this site

     * Paremete: username and password 

     */

    public function __construct() {

        $this->load->model('Membership_model');
        $this->load->model('user/User_model');

        parent::__construct();
		

    }

    
	// @sid upgrade plan - user_membership_plan_management -> this function manages upgrading and downgrading user's membership plan
	public function user_membership_plan_upgrade() {
		if(!$this->input->is_ajax_request ()){ 
			show_custom_404_page();
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		update_session_expiration_time();
		$row = $this->input->post();
		$user = $this->session->userdata('user')[0];
		$this->db->where('id', $row['plan_id']);
		$plan = $this->db->get('membership_plans')->row_array();
		//$default_plan = $this->Membership_model->get_default_membership_plan();
		$current_plan = $this->Membership_model->get_current_running_plan_by_user_id($user->user_id);
		$membership_plan_tracking = $this->Membership_model->get_membership_plan_tracking_by_user_id_and_start_date($user->user_id, $current_plan['current_membership_start_date']);
		// insert into membership plan upgrade tracking
		//if($row['plan_id'] > $current_plan['current_membership_plan_id'] && $row['plan_id'] > $default_plan['id']) {
			$price = $plan['monthly_price'];
			/* if($current_plan['user_account_balance'] < $price) {
				echo json_encode(['status' => 400]);
				return;
			} */
			//$check_user_upgrade_gold_membership = $this->db->where(['user_id' => $user[0]->user_id,'upgrade_to_membership_plan'=>4])->from('users_membership_plans_upgrade_tracking')->count_all_results();
			$this->db->where('user_id', $user->user_id);
			$this->db->where('upgraded_plan_id', $row['plan_id']);
			$bonus_balance_tracking = $this->db->get('users_membership_plan_upgrade_bonus_tracking')->row_array();
			
			$user_details = [
				'current_membership_plan_id' => $row['plan_id'] ,
				'current_membership_start_date' => date('Y-m-d H:i:s'),
				//'user_account_balance' => $current_plan['user_account_balance'] - $price
			];
			if(empty($bonus_balance_tracking)) {
				$user_details['bonus_balance'] = $current_plan['bonus_balance'] + $plan['bonus_value'];
				$bonus_tarcking_data = [
					'user_id' => $user->user_id,
					'upgraded_plan_id' => $row['plan_id'],
					'bonus_amount' => $plan['bonus_value'],
					'bonus_granted_date' => date('Y-m-d H:i:s')
				];
				$this->db->insert('users_membership_plan_upgrade_bonus_tracking', $bonus_tarcking_data);
			}
			
			// update membership plan details into user details table
			$this->db->where('user_id', $user->user_id);
			$this->db->update('users_details', $user_details);
			// Add membership plan details into membership tracking
			$track_data = [
				'user_id' => $user->user_id,
				'current_membership_plan_id' => $row['plan_id'],
				'current_membership_plan_start_date' => date('Y-m-d H:i:s'),
				'membership_price' => $price
			];
			$this->db->insert('users_membership_tracking', $track_data);
			$upgrade_data = [
				'user_id' => $user->user_id,
				'previous_membership_plan' => $current_plan['current_membership_plan_id'],
				'upgrade_to_membership_plan' => $row['plan_id'],
				'membership_plan_upgrade_date' => date('Y-m-d H:i:s'),
				'upgrade_to_membership_plan_price' => $price
			];
			$this->db->insert('users_membership_plans_upgrade_tracking', $upgrade_data);
			//$this->upgrade_plan_mail($user->user_id);
		
			
			$this->db->select('mput.*,mp.membership_plan_name');
			$this->db->from('users_membership_plans_upgrade_tracking mput');
			$this->db->join('membership_plans mp', 'mp.id = mput.upgrade_to_membership_plan');
			$this->db->where('mput.user_id', $user->user_id);
			$this->db->order_by('mput.id', 'desc');
			$this->db->limit(1);
			$upgrade_plan_tracking = $this->db->get()->row_array();
			// user display activity 
			if(empty($bonus_balance_tracking)) { 
				$user_activity_display = $this->config->item('user_activity_log_displayed_message_successful_membership_upgrade_plan_confirmation_first_time');
				$this->session->set_userdata('bonus_tracking', 1);
			} else {
				$user_activity_display = $this->config->item('user_activity_log_displayed_message_successful_membership_upgrade_plan_confirmation');
				$this->session->unset_userdata('bonus_tracking');
			}
			$user_activity_display = str_replace('{user_current_membership_plan_name}', $current_plan['membership_plan_name'], $user_activity_display);
			$user_activity_display = str_replace('{user_upgraded_membership_plan_name}', $upgrade_plan_tracking['membership_plan_name'], $user_activity_display);
			$user_activity_display = str_replace('{upgraded_membership_plan_price}', number_format($price, 2), $user_activity_display);
			$user_activity_display = str_replace('{membership_upgrade_type_month_or_year}',  $this->config->item('month'), $user_activity_display);
			user_display_log($user_activity_display);
			// when user select upgrade
			if($row['plan_id'] == '4'){
				
				$topLeftTabBtn = '<button class="btn default_btn membership_red_btn btn-upgrade-downgrade" id="membership_1" data-id="1" plan="Free">'.$this->config->item('downgrade_membership_btn_text').'</button>';
				$topRightTabBtn = '<button class="btn default_btn visible_btn noPointer">Aktuálně zvolené</button>';
			
			}
		//} 
		echo json_encode(['status' => 200,'topLeftTabBtn'=>$topLeftTabBtn,'topRightTabBtn'=>$topRightTabBtn, 'user_id' => $user->user_id]);
		return;
	}
	
	// @sid user membership plan downgrade
	public function user_membership_plan_downgrade() {
		if(!$this->input->is_ajax_request ()){ 
			show_custom_404_page();
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		update_session_expiration_time();
		$row = $this->input->post();
		$user = $this->session->userdata('user')[0];
		$this->db->where('id', $row['plan_id']);
		$plan = $this->db->get('membership_plans')->row_array();
		//$default_plan = $this->Membership_model->get_default_membership_plan();
		$current_plan = $this->Membership_model->get_current_running_plan_by_user_id($user->user_id);
		$price = $plan['monthly_price'];
		/* if($current_plan['user_account_balance'] < $price) {
			echo json_encode(['status' => 400]);
			return;
		} */
		$user_details = [
				'current_membership_plan_id' => $row['plan_id'] ,
				'current_membership_start_date' => date('Y-m-d H:i:s'),
				//'user_account_balance' => $current_plan['user_account_balance'] - $price
			];
			
			
		
		// update membership plan details into user details table
		$this->db->where('user_id', $user->user_id);
		$this->db->update('users_details', $user_details);
		// Add membership plan details into membership tracking
		$track_data = [
			'user_id' => $user->user_id,
			'current_membership_plan_id' => $row['plan_id'],
			'current_membership_plan_start_date' => date('Y-m-d H:i:s'),
			'membership_price' => $price
		];
		$this->db->insert('users_membership_tracking', $track_data);
		$downgrade_data = [
			'user_id' => $user->user_id,
			'current_membership_plan' => $current_plan['current_membership_plan_id'],
			'downgraded_to_membership_plan' => $row['plan_id'],
			'membership_plan_effective_downgrade_start_date' => date('Y-m-d H:i:s'),
			'downgraded_to_membership_plan_price' => $price
		];
		$this->db->insert('users_membership_plans_downgrade_tracking', $downgrade_data);
		############ if user down grade the plan the cover picture will be removed #####################
		$this->User_model->delete_user_profile_cover_picture_record($user->user_id);
		
		//$this->downgrade_plan_mail($user->user_id);
	
		
		$this->db->select('mpdt.*,mp.membership_plan_name');
		$this->db->from('users_membership_plans_downgrade_tracking mpdt');
		$this->db->join('membership_plans mp', 'mp.id = mpdt.downgraded_to_membership_plan');
		$this->db->where('mpdt.user_id', $user->user_id);
		$this->db->order_by('mpdt.id', 'desc');
		$this->db->limit(1);
		$downgrade_plan_tracking = $this->db->get()->row_array();
		// user display activity 
		$user_activity_display = $this->config->item('user_activity_log_displayed_message_successful_membership_downgrade_plan_confirmation');
		$user_activity_display = str_replace('{user_current_membership_plan_name}', $current_plan['membership_plan_name'], $user_activity_display);
		$user_activity_display = str_replace('{user_downgraded_membeship_plan_name}', $downgrade_plan_tracking['membership_plan_name'], $user_activity_display);
		$user_activity_display = str_replace('{membership_downgrade_price}', number_format($price, 2), $user_activity_display);
		$user_activity_display = str_replace('{membership_downgrade_type_month_or_year}',  $this->config->item('month'), $user_activity_display);
		user_display_log($user_activity_display);
		// when user select downgrade
		if($row['plan_id'] == '1'){
			$topRightTabBtn = '<button class="btn default_btn blue_btn btn-upgrade-downgrade"  id="membership_4" data-id="4" plan="Gold">'. $this->config->item('upgrade_membership_btn_text').'</button>';
			$topLeftTabBtn = '<button class="btn default_btn visible_btn noPointer">Aktuálně zvolené</button>';
			
		}
		
		//topRightTabBtn upgrade
		//topLeftTabBtn downgrade
		
		
		echo json_encode(['status' => 200,'topLeftTabBtn'=>$topLeftTabBtn,'topRightTabBtn'=>$topRightTabBtn, 'user_id' => $user->user_id]);
		return ;
	}
	 // @sid update session expiration time based on user action
	

  
	
	
	public function membership_plan() {		
		if(check_session_validity()) { 
			
			$user=$this->session->userdata('user');
			$data['user_id']=$user[0]->user_id;
			$data['user_membership']=$this->auto_model->getFeild('current_membership_plan_id','users_details','user_id',$user[0]->user_id);
			$data['current_plan'] = $this->Membership_model->get_current_running_plan_by_user_id($user[0]->user_id);
			$data['current_page'] = 'membership';
			$data['membership_plan']=$this->Membership_model->getplan();    	
			$membership_plans = $this->Membership_model->get_all_membership_prices();
			$check_user_upgrade_gold_membership = $this->db->where(['user_id' => $user[0]->user_id,'upgrade_to_membership_plan'=>4])->from('users_membership_plans_upgrade_tracking')->count_all_results();
			
			
			
			$data['check_user_upgrade_gold_membership'] = $check_user_upgrade_gold_membership;
			
			 $name = (($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')) ? $user[0]->first_name . ' ' . $user[0]->last_name : $user[0]->company_name;
			 
			$membership_page_title_meta_tag = $this->config->item('membership_page_title_meta_tag');
            $membership_page_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $membership_page_title_meta_tag);
            $membership_page_description_meta_tag = $this->config->item('membership_page_description_meta_tag');
            $membership_page_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $membership_page_description_meta_tag);
			
			
			
			
			$data['meta_tag'] = '<title>' . $membership_page_title_meta_tag . '</title><meta name="description" content="' . $membership_page_description_meta_tag . '"/>';
			//$data = $this->assign_membership_data_to_view($membership_plans, $data);
			/* if ($this->input->is_ajax_request()) {
				echo $this->load->view('membershipajax', $data, true);
				return;
			} */
			
			$this->layout->view('membership','',$data,'normal');
		} else {
			redirect (site_url ());
		}
    }
}
?>