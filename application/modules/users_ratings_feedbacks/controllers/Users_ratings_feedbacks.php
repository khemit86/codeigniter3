<?php
if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Users_ratings_feedbacks extends MX_Controller
{
    public function __construct ()
    {
        $this->load->library ('pagination');
        $this->load->model ('Users_ratings_feedbacks_model');
		$this->load->model('projects/Projects_model'); //used index, edit_job
		$this->load->model('escrow/Escrow_model'); //used index, edit_job
		$this->load->model('user/User_model');
        parent::__construct ();
        
    }
	
	/**
	* This function is used to make the popup of user ratings and feedbacks .
	*/
	public function load_user_give_rating_feedback_popup_body(){
	
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$data = array();
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$section_id = $this->input->post ('section_id');
				$project_id = $this->input->post ('project_id');
				$view_type = $this->input->post ('view_type');
				$section_name = $this->input->post ('section_name');
				$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
				$sp_id = Cryptor::doDecrypt($this->input->post ('sp_id'));
				$project_type = $this->input->post ('project_type');
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				if(!empty($project_status_table_array['project_status']) && !empty($project_status_table_array['table_name'])){
				
					if(($view_type == 'po' &&  $user_id != $po_id) || ($view_type == 'sp' &&  $user_id != $sp_id)){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					if($user_id == $po_id && $view_type == 'po'){
						if($project_type != 'fulltime'){
							$check_feedback_data_exists = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_sp_id'=>$sp_id,'feedback_given_by_po_id'=>$po_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
						}else{
							$check_feedback_data_exists = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employee_id'=>$sp_id,'feedback_given_by_employer_id'=>$po_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')->count_all_results();
						}
					}
					else if($user_id == $sp_id && $view_type == 'sp'){
						if($project_type != 'fulltime'){
							$check_feedback_data_exists = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_po_id'=>$po_id,'feedback_given_by_sp_id'=>$sp_id])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
						}else{
							$check_feedback_data_exists = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_given_by_employee_id'=>$sp_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')->count_all_results();
						}
					}
					if($check_feedback_data_exists > 0 ){
						if($project_type != 'fulltime'){
							$error_message = $this->config->item('projects_users_ratings_feedbacks_po_sp_already_given_feedback');
						}else{
							$error_message = $this->config->item('fulltime_projects_users_ratings_feedbacks_employer_employee_already_given_feedback');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					
					}
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
						$project_data = $this->db // get the user detail
						->select('pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title')
						->from($project_status_table_array['table_name'].' pd')
						->where('pd.fulltime_project_id', $project_id)
						->get()->row_array();
					}else{
						$project_data = $this->db // get the user detail
						->select('pd.project_id,pd.project_title')
						->from($project_status_table_array['table_name'].' pd')
						->where('pd.project_id', $project_id)
						->get()->row_array();
					}
				
				
					$sp_data = $this->db // get the user detail
					->select('u.user_id,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
					->from('users u')
					->where('u.user_id', $sp_id)
					->get()->row_array();
					
					$po_data = $this->db // get the user detail
					->select('u.user_id,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
					->from('users u')
					->where('u.user_id', $po_id)
					->get()->row_array();
					$data['sp_data'] = $sp_data;
					$data['po_data'] = $po_data;
					$data['project_data'] = $project_data;
					$data['project_type'] = $project_type;
					$data['view_type'] = $view_type;
					$data['section_name'] = $section_name;
					$data['section_id'] = $section_id;
					
					$res['ratings_feedbacks_popup_heading'] = $this->config->item('projects_users_ratings_feedbacks_popup_modal_title');
					$res['ratings_feedbacks_popup_body'] = $this->load->view('user_give_rating_feedback_popup_body', $data, true);
					$res['ratings_feedbacks_popup_footer'] = '<button type="button" class="btn red_btn default_btn rate_popup_button default_popup_width_btn" data-dismiss="modal">'.$this->config->item('cancel_btn_txt').'</button><button type="button" id="rate_button" class="btn green_btn default_btn rate_popup_button default_popup_width_btn">'.$this->config->item('rate_btn_txt').' <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>';
					$res['status'] = 200;
					echo json_encode($res);
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
	* This function is used to saved the ratings and feedbacks into database.
	*/
	public function save_user_give_rating_feedback(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$data = array();
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$section_id = $this->input->post ('section_id');
				$project_id = $this->input->post ('project_id');
				$section_name = $this->input->post ('section_name');
				$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
				$sp_id = Cryptor::doDecrypt($this->input->post ('sp_id'));
				$project_type = $this->input->post ('project_type');
				$view_type = $this->input->post ('view_type');
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				if(!empty($project_status_table_array['project_status']) && !empty($project_status_table_array['table_name'])){
					$remove_id = 0;
					$is_bid_completed = '0';
					$is_project_status_change = '0';
					$total_paid_amount = 0;
					$post_data = $this->input->post ();
					
					if(($view_type == 'po' &&  $user_id != $po_id) || ($view_type == 'sp' &&  $user_id != $sp_id)){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					if($user_id == $po_id && $view_type == 'po'){
					
						if($project_type != 'fulltime'){
							$check_feedback_data_exists = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_sp_id'=>$sp_id,'feedback_given_by_po_id'=>$po_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
						}else{
							$check_feedback_data_exists = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employee_id'=>$sp_id,'feedback_given_by_employer_id'=>$po_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')->count_all_results();
						}
					}
					else if($user_id == $sp_id && $view_type == 'sp'){
						if($project_type != 'fulltime'){
							$check_feedback_data_exists = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_po_id'=>$po_id,'feedback_given_by_sp_id'=>$sp_id])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
						}else{
							$check_feedback_data_exists = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_given_by_employee_id'=>$sp_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')->count_all_results();
						}
					}
					if($check_feedback_data_exists > 0 ){
						if($project_type != 'fulltime'){
							$error_message = $this->config->item('projects_users_ratings_feedbacks_po_sp_already_given_feedback');
						}else{
							$error_message = $this->config->item('fulltime_projects_users_ratings_feedbacks_employer_employee_already_given_feedback');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					
					}
					
					$validation_data_array = $this->Users_ratings_feedbacks_model->user_give_rating_feedback_validation($post_data);
					if ($validation_data_array['status'] == 'SUCCESS')
					{
						
					
						if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
							$project_data = $this->db // get the user detail
							->select('pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title')
							->from($project_status_table_array['table_name'].' pd')
							->where('pd.fulltime_project_id', $project_id)
							->get()->row_array();
						}else{
							$project_data = $this->db // get the user detail
							->select('pd.project_id,pd.project_title')
							->from($project_status_table_array['table_name'].' pd')
							->where('pd.project_id', $project_id)
							->get()->row_array();
						}
						$sp_data = $this->db // get the user detail
						->select('u.user_id,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
						->from('users u')
						->where('u.user_id', $sp_id)
						->get()->row_array();
						
						$po_data = $this->db // get the user detail
						->select('u.user_id,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
						->from('users u')
						->where('u.user_id', $po_id)
						->get()->row_array();
					
						$sp_name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
						
						$po_name = (($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')) ?$po_data['first_name'] . ' ' . $po_data['last_name'] :$po_data['company_name'];
						
						
						$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
						
						$sp_profile_url_link = VPATH.$sp_data['profile_name'];
						$po_profile_url_link = VPATH.$po_data['profile_name'];
					
						if($project_type != 'fulltime'){
								$feedback_data['feedback_provided_on_project_id']= $project_id;
								$feedback_data['feedback_provided_on_date']= date('y-m-d H:i:s');
								
							if($user_id == $po_id && $view_type == 'po'){
								
								$feedback_data['feedback_recived_by_sp_id']= $sp_id;
								$feedback_data['feedback_given_by_po_id']= $po_id;
								$feedback_data['project_delivered_within_agreed_budget']=$post_data['project_delivered_within_agreed_budget'];
								
								$feedback_data['work_delivered_within_agreed_time_slot']=$post_data['work_delivered_within_agreed_time_slot'];
								$feedback_data['would_you_hire_sp_again']=$post_data['would_you_hire_sp_again'];
								$feedback_data['would_you_recommend_sp']=$post_data['would_you_recommend_sp'];
								$feedback_data['quality_of_work']=$post_data['quality_of_work'];
								$feedback_data['communication']=$post_data['communication'];
								$feedback_data['professionalism']=$post_data['expertise'];
								$feedback_data['expertise']=$post_data['professionalism'];
								$feedback_data['value_for_money']=$post_data['value_for_money'];
								$feedback_data['feedback_left_by_po']=trim($post_data['feedback']);
								
								$project_avg_rating_as_sp = ($post_data['quality_of_work']+$post_data['communication']+$post_data['expertise']+$post_data['professionalism']+$post_data['value_for_money'])/5;
								
								$feedback_data['project_avg_rating_as_sp']=number_format($project_avg_rating_as_sp,2);
								
								/* $check_sp_given_feedback_to_po = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_po_id'=>$po_id,'feedback_given_by_sp_id'=>$sp_id])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
								
								if($check_sp_given_feedback_to_po > 0){
									$feedback_data['sp_already_placed_feedback'] = 'Y';
								} */
								
								if($this->db->insert ('projects_users_received_ratings_feedbacks_as_sp', $feedback_data)){
								
								
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" start 
									
									$this->db->select('id');
									$this->db->from('projects_candidates_for_users_ratings_feedbacks_exchange tpa');
									$this->db->where('project_id',$project_id);
									$this->db->where('po_id',$po_id);
									$this->db->where('sp_id',$sp_id);
									$this->db->where('sp_rating_to_po_date IS NULL');
									$check_ratings_feedbacks_exchange = $this->db->get()->row_array();
									if(!empty($check_ratings_feedbacks_exchange)){
										$this->db->update('projects_candidates_for_users_ratings_feedbacks_exchange', ['po_rating_to_sp_date'=>$feedback_data['feedback_provided_on_date']], ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]);
									}else{
										$this->db->delete('projects_candidates_for_users_ratings_feedbacks_exchange',  ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]); 
									}
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" end 
								
									// check feedback is given by sp
									$check_feedback_given_by_sp = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_po_id'=>$po_id,'feedback_given_by_sp_id'=>$sp_id])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
									// check feedback is given by po
									$check_feedback_given_by_po = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_sp_id'=>$sp_id,'feedback_given_by_po_id'=>$po_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
									if($check_feedback_given_by_sp > 0  && $check_feedback_given_by_po >0){
										
										
										$this->db->update('projects_users_received_ratings_feedbacks_as_po', ['po_already_placed_feedback'=>'Y'], ['feedback_recived_by_po_id'=> $po_id,'feedback_given_by_sp_id'=>$sp_id,'feedback_provided_on_project_id'=>$project_id]);
										
										$this->db->update('projects_users_received_ratings_feedbacks_as_sp', ['sp_already_placed_feedback'=>'Y'], ['feedback_recived_by_sp_id'=> $sp_id,'feedback_given_by_po_id'=>$po_id,'feedback_provided_on_project_id'=>$project_id]);
									
										$this->db->select('AVG(project_avg_rating_as_po) as project_avg_rating_as_po');
										$this->db->from('projects_users_received_ratings_feedbacks_as_po');
										$this->db->where(['feedback_recived_by_po_id'=>$po_id,'po_already_placed_feedback'=>'Y']);
										
										$avg_rating_result = $this->db->get();
										$avg_rating_row = $avg_rating_result->row_array();
										
										$user_total_avg_rating_as_po = $avg_rating_row['project_avg_rating_as_po'];
										$this->db->update('users_details', ['project_user_total_avg_rating_as_po'=>number_format($user_total_avg_rating_as_po,2)], ['user_id'=> $po_id]);
									
									//if($check_sp_given_feedback_to_po > 0){
										// update user total rating in users_details table for fixed/hourly project for sp
										$this->db->select('AVG(project_avg_rating_as_sp) as project_avg_rating_as_sp');
										$this->db->from('projects_users_received_ratings_feedbacks_as_sp');
										$this->db->where(['feedback_recived_by_sp_id'=>$sp_id,'sp_already_placed_feedback'=>'Y']);
										
										$avg_rating_result = $this->db->get();
										$avg_rating_row = $avg_rating_result->row_array();
										
										
										$user_total_avg_rating_as_sp = $avg_rating_row['project_avg_rating_as_sp'];
										$this->db->update('users_details', ['project_user_total_avg_rating_as_sp'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
										
										####### update total rating of sp ###
										
										$user_details = $this->db // get the user detail
										->select('project_user_total_avg_rating_as_sp,fulltime_project_user_total_avg_rating_as_employee')
										->from('users_details')
										->where('user_id', $sp_id)
										->get()->row_array();
										
										if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
											$user_total_avg_rating_as_sp = ($user_details['project_user_total_avg_rating_as_sp']+$user_details['fulltime_project_user_total_avg_rating_as_employee'])/2;
										}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) == 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
											$user_total_avg_rating_as_sp = $user_details['fulltime_project_user_total_avg_rating_as_employee'];
										}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) == 0){
											$user_total_avg_rating_as_sp = $user_details['project_user_total_avg_rating_as_sp'];
										}								
										$this->db->update('users_details', ['user_total_avg_rating_as_sp'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
										//}
									}
									
								
									// Log message code
									$po_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_po_when_given_feedback_user_activity_log_displayed_message');
									
									$po_activity_log_message = str_replace(array("{sp_profile_url_link}","{user_first_name_last_name_or_company_name}","{project_title}","{project_url_link}"),array($sp_profile_url_link,$sp_name,$project_data['project_title'],$project_url_link),$po_activity_log_message);
									
									
									if(($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')){
										if($po_data['gender'] == 'M'){
											if($po_data['is_authorized_physical_person'] == 'Y'){
												$sp_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_sp_when_po_company_app_male_given_feedback_user_activity_log_displayed_message');
											}else{
												$sp_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_sp_when_po_male_given_feedback_user_activity_log_displayed_message');
											}
											
										}else{
											if($po_data['is_authorized_physical_person'] == 'Y'){
												$sp_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_sp_when_po_company_app_female_given_feedback_user_activity_log_displayed_message');
											}else{
												$sp_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_sp_when_po_female_given_feedback_user_activity_log_displayed_message');
											}
											
										}
										$sp_activity_log_message = str_replace(array("{po_profile_url_link}","{user_first_name_last_name}","{project_title}","{project_url_link}"),array($po_profile_url_link,$po_name,$project_data['project_title'],$project_url_link),$sp_activity_log_message);
										
										
									}else{
										$sp_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_sp_when_po_company_given_feedback_user_activity_log_displayed_message');
										
										$sp_activity_log_message = str_replace(array("{po_profile_url_link}","{user_company_name}","{project_title}","{project_url_link}"),array($po_profile_url_link,$po_name,$project_data['project_title'],$project_url_link),$sp_activity_log_message);
									}
								
								}
							}else if($user_id == $sp_id && $view_type == 'sp'){
								$feedback_data['feedback_recived_by_po_id']= $po_id;
								$feedback_data['feedback_given_by_sp_id']= $sp_id;
								$feedback_data['would_you_work_again_with_po']=$post_data['would_you_work_again_with_po'];
								
								$feedback_data['would_you_recommend_po']=$post_data['would_you_recommend_po'];
								$feedback_data['clarity_in_requirements']=$post_data['clarity_requirements'];
								$feedback_data['communication']=$post_data['communication'];
								$feedback_data['payment_promptness']=$post_data['payment_promptness'];
								$feedback_data['feedback_left_by_sp']=trim($post_data['feedback']);
								
								$project_avg_rating_as_po = ($post_data['clarity_requirements']+$post_data['communication']+$post_data['payment_promptness'])/3;
								
								$feedback_data['project_avg_rating_as_po']=number_format($project_avg_rating_as_po,2);
								
								$check_po_given_feedback_to_sp = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_given_by_po_id'=>$po_id,'feedback_recived_by_sp_id'=>$sp_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
								/* if($check_po_given_feedback_to_sp > 0){
									$feedback_data['po_already_placed_feedback'] = 'Y';
								} */
								if($this->db->insert ('projects_users_received_ratings_feedbacks_as_po', $feedback_data)){
								
								
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" start 
									
									$this->db->select('id');
									$this->db->from('projects_candidates_for_users_ratings_feedbacks_exchange tpa');
									$this->db->where('project_id',$project_id);
									$this->db->where('po_id',$po_id);
									$this->db->where('sp_id',$sp_id);
									$this->db->where('po_rating_to_sp_date IS NULL');
									$check_ratings_feedbacks_exchange = $this->db->get()->row_array();
									if(!empty($check_ratings_feedbacks_exchange)){
										$this->db->update('projects_candidates_for_users_ratings_feedbacks_exchange', ['sp_rating_to_po_date'=>$feedback_data['feedback_provided_on_date']], ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]);
									}else{
										$this->db->delete('projects_candidates_for_users_ratings_feedbacks_exchange',  ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]); 
									}
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" end 
								
								
									// check feedback is given by sp
									$check_feedback_given_by_sp = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_po_id'=>$po_id,'feedback_given_by_sp_id'=>$sp_id])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
									
									// check feedback is given by po
									$check_feedback_given_by_po = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_sp_id'=>$sp_id,'feedback_given_by_po_id'=>$po_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
									if($check_feedback_given_by_sp > 0 && $check_feedback_given_by_po > 0){	
									
										$this->db->update('projects_users_received_ratings_feedbacks_as_po', ['po_already_placed_feedback'=>'Y'], ['feedback_recived_by_po_id'=> $po_id,'feedback_given_by_sp_id'=>$sp_id,'feedback_provided_on_project_id'=>$project_id]);
										
										$this->db->update('projects_users_received_ratings_feedbacks_as_sp', ['sp_already_placed_feedback'=>'Y'], ['feedback_recived_by_sp_id'=> $sp_id,'feedback_given_by_po_id'=>$po_id,'feedback_provided_on_project_id'=>$project_id]);
										
										// update user total rating in users_details table for fixed/hourly project for sp
											$this->db->select('AVG(project_avg_rating_as_sp) as project_avg_rating_as_sp');
											$this->db->from('projects_users_received_ratings_feedbacks_as_sp');
											$this->db->where(['feedback_recived_by_sp_id'=>$sp_id,'sp_already_placed_feedback'=>'Y']);
											
											$avg_rating_result = $this->db->get();
											$avg_rating_row = $avg_rating_result->row_array();
											
											$user_total_avg_rating_as_sp = $avg_rating_row['project_avg_rating_as_sp'];
											$this->db->update('users_details', ['project_user_total_avg_rating_as_sp'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
											
											####### update total rating of sp ###
											
											$user_details = $this->db // get the user detail
											->select('project_user_total_avg_rating_as_sp,fulltime_project_user_total_avg_rating_as_employee')
											->from('users_details')
											->where('user_id', $sp_id)
											->get()->row_array();
											
											if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
												$user_total_avg_rating_as_sp = ($user_details['project_user_total_avg_rating_as_sp']+$user_details['fulltime_project_user_total_avg_rating_as_employee'])/2;
											}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) == 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
												$user_total_avg_rating_as_sp = $user_details['fulltime_project_user_total_avg_rating_as_employee'];
											}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) == 0){
												$user_total_avg_rating_as_sp = $user_details['project_user_total_avg_rating_as_sp'];
											}								
											$this->db->update('users_details', ['user_total_avg_rating_as_sp'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
										
										//if($check_po_given_feedback_to_sp > 0){
											// update user total rating in users_details table  for fixed/hourly project for po
											$this->db->select('AVG(project_avg_rating_as_po) as project_avg_rating_as_po');
											$this->db->from('projects_users_received_ratings_feedbacks_as_po');
											$this->db->where(['feedback_recived_by_po_id'=>$po_id,'po_already_placed_feedback'=>'Y']);
											$avg_rating_result = $this->db->get();
											$avg_rating_row = $avg_rating_result->row_array();
											$user_total_avg_rating_as_po = $avg_rating_row['project_avg_rating_as_po'];
											$this->db->update('users_details', ['project_user_total_avg_rating_as_po'=>number_format($user_total_avg_rating_as_po,2)], ['user_id'=> $po_id]);
										//}
									}	
									$sp_activity_log_message= $this->config->item('projects_rating_feedbacks_message_sent_to_sp_when_given_feedback_user_activity_log_displayed_message');
									
									$sp_activity_log_message = str_replace(array('{po_profile_url_link}','{user_first_name_last_name_or_company_name}','{project_title}','{project_url_link}'),array($po_profile_url_link,$po_name,$project_data['project_title'],$project_url_link),$sp_activity_log_message);
									
									
									if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
										if($sp_data['gender'] == 'M'){
											if($sp_data['is_authorized_physical_person'] == 'Y'){
												$po_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_po_when_sp_company_app_male_given_feedback_user_activity_log_displayed_message');
											}else{
												$po_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_po_when_sp_male_given_feedback_user_activity_log_displayed_message');
											}
											
										}else{
											if($sp_data['is_authorized_physical_person'] == 'Y'){
												$po_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_po_when_sp_company_app_female_given_feedback_user_activity_log_displayed_message');
											}else{
												$po_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_po_when_sp_female_given_feedback_user_activity_log_displayed_message');
											}
											
										}
										$po_activity_log_message = str_replace(array('{sp_profile_url_link}','{user_first_name_last_name}','{project_title}','{project_url_link}'),array($sp_profile_url_link,$sp_name,$project_data['project_title'],$project_url_link),$po_activity_log_message);
										
										
									}else{
										$po_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_po_when_sp_company_given_feedback_user_activity_log_displayed_message');
										
										$po_activity_log_message = str_replace(array('{sp_profile_url_link}','{user_company_name}','{project_title}','{project_url_link}'),array($sp_profile_url_link,$sp_name,$project_data['project_title'],$project_url_link),$po_activity_log_message);
									}
								}
							
							}
							user_display_log($po_activity_log_message,$po_id); // activity log message for po
							user_display_log($sp_activity_log_message,$sp_id); // activity log message for sp
							
							
							############## code for move in progress bid from in progress status to completed start for hourly project###
							$acknowledgement_notification_msg = '';
							if($project_type == 'hourly'){
							
								// check feedback is given by sp
								$check_feedback_given_by_sp = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_po_id'=>$po_id,'feedback_given_by_sp_id'=>$sp_id])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
								
								// check feedback is given by po
								$check_feedback_given_by_po = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_sp_id'=>$sp_id,'feedback_given_by_po_id'=>$po_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
							
								if($check_feedback_given_by_sp > 0  && $check_feedback_given_by_po > 0){
									$project_completion_date = date('Y-m-d H:i:s');
									$get_completed_bid_data = $this->db->get_where('hourly_rate_based_projects_completed_tracking', ['project_id' => $project_id,'winner_id'=>$sp_id])->row_array();
								
									$in_progress_bid_data = $this->db->get_where('hourly_rate_based_projects_progress_sp_bid_reference', ['project_id' => $project_id,'winner_id'=>$sp_id])->row_array();
									
									if(!empty($in_progress_bid_data)){
										$hourly_project_bid_data	 = $in_progress_bid_data ;
										$hourly_bid_status = 'in_progress';
									}
									$in_complete_bid_data = $this->db->get_where('hourly_rate_based_projects_incomplete_tracking', ['project_id' => $project_id,'winner_id'=>$sp_id])->row_array();
									
									if(!empty($in_complete_bid_data)){
										$hourly_project_bid_data	 = $in_complete_bid_data ;
										$hourly_bid_status = 'in_complete';
									}
									
									if(!empty($hourly_project_bid_data)){
										$total_released_escrow = $total_released_escrow_po = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($project_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
									
										if(!empty($hourly_project_bid_data) && $hourly_project_bid_data['initial_project_agreed_value'] == 0){
										
											$sp_notification_msg = $this->config->item('hourly_rate_based_project_realtime_notification_message_sent_to_sp_when_project_completed');
											$sp_notification_msg = str_replace(array('{project_title}','{project_url_link}'),array($project_data['project_title'],$project_url_link),$sp_notification_msg);
											
											//
											$po_notification_msg = $this->config->item('hourly_rate_based_project_realtime_notification_message_sent_to_po_when_project_completed');
											$po_notification_msg = str_replace(array('{project_title}','{project_url_link}'),array($project_data['project_title'],$project_url_link),$po_notification_msg);
											
											if($user_id == $sp_data['user_id']){
												$acknowledgement_notification_msg = $sp_notification_msg;
											}
											else if($user_id == $po_data['user_id']){
												$acknowledgement_notification_msg = $po_notification_msg;
											}
											
										
											
											$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_project_completed_user_activity_log_displayed_message');
											$sp_activity_log_message = str_replace(array('{project_title}','{project_url_link}'),array($project_data['project_title'],$project_url_link),$sp_activity_log_message);
											
											
											$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_project_completed_user_activity_log_displayed_message');
											$po_activity_log_message = str_replace(array('{project_title}','{project_url_link}'),array($project_data['project_title'],$project_url_link),$po_activity_log_message);
											
											
											
										
										
											$is_bid_completed = '1';
											//$remove_id = $in_progress_bid_data['id'];
											$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
											$config['ftp_username'] = FTP_USERNAME;
											$config['ftp_password'] = FTP_PASSWORD;
											$config['ftp_port'] 	= FTP_PORT;
											$config['debug']    = TRUE;
											$this->load->library('ftp');
											$this->ftp->connect($config); 
										
										
										
											$completed_bid_data['project_id'] = $hourly_project_bid_data['project_id'];
											$completed_bid_data['winner_id'] = $hourly_project_bid_data['winner_id'];
											$completed_bid_data['project_owner_id'] = $hourly_project_bid_data['project_owner_id'];
											$completed_bid_data['initial_bid_description'] = $hourly_project_bid_data['initial_bid_description'];
											$completed_bid_data['bidding_dropdown_option'] = $hourly_project_bid_data['bidding_dropdown_option'];
											$completed_bid_data['initial_project_agreed_value'] = $hourly_project_bid_data['initial_project_agreed_value'];
											$completed_bid_data['initial_project_agreed_number_of_hours'] = $hourly_project_bid_data['initial_project_agreed_number_of_hours'];
											$completed_bid_data['initial_project_agreed_hourly_rate'] = $hourly_project_bid_data['initial_project_agreed_hourly_rate'];
											$completed_bid_data['project_winner_work_start_date'] = $hourly_project_bid_data['project_start_date'];
											$completed_bid_data['project_winner_work_completion_date'] = $project_completion_date;
											$completed_bid_data['total_project_amount'] = $total_released_escrow;
											
											if(empty($get_completed_bid_data)){
											
												
												$this->db->insert ('hourly_rate_based_projects_completed_tracking', $completed_bid_data);// move data to completed internally tracking
												$this->db->delete('hourly_rate_based_projects_progress_sp_bid_reference', ['project_id' => $project_id,'winner_id'=>$sp_id]);
												$this->db->delete('hourly_rate_based_projects_incomplete_tracking', ['project_id' => $project_id,'winner_id'=>$sp_id]); 
												
												$completed_bidder_data = $this->db // get the user detail
												->select('u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,cb.*')
												->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
												->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id ) as sp_total_completed_fixed_budget_projects')
												->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id ) as sp_total_completed_hourly_based_projects')
												->from('hourly_rate_based_projects_completed_tracking cb')
												->join('users u', 'u.user_id = cb.winner_id', 'left')
												->join('users_details ud', 'ud.user_id = u.user_id', 'left')
												->where('project_id', $completed_bid_data['project_id'])
												->where('winner_id', $completed_bid_data['winner_id'])
												->where('cb.project_owner_id', $completed_bid_data['project_owner_id'])
												->get()->row_array();
												$common_source_path = USERS_FTP_DIR . $completed_bidder_data['profile_name'];
												
												//avatar picture
												//start check avatar from ftp server
												$user_avatar = USER_AVATAR;
												$source_path_avatar = $common_source_path . $user_avatar;
												$avatarlist = $this->ftp->list_files($source_path_avatar);
												$avatar_pic = $source_path_avatar . $completed_bidder_data['user_avatar'];

												$exap = explode('.', $completed_bidder_data['user_avatar']);
												$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

												if (count($avatarlist) > 0) {
													$acheck = true;
													if (!in_array($avatar_pic, $avatarlist) && $acheck) {
														$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $completed_bidder_data['user_id']));
														$this->ftp->delete_dir($source_path_avatar);
														$completed_bidder_data['user_avatar'] = '';
														$acheck = false;
													} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
														$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$completed_bidder_data['user_id']));
														$this->ftp->delete_dir($source_path_avatar);
														$completed_bidder_data['user_avatar'] = '';
														$acheck = false;
													}
												} if (count($avatarlist) == 0 && $completed_bidder_data['user_avatar'] != '') {
													$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $completed_bidder_data['user_id']));
													$completed_bidder_data['user_avatar'] = '';
												}
												
												$count_in_progress_bids = $this->db->where(['project_id' => $project_id])->from('hourly_rate_based_projects_progress_sp_bid_reference')->count_all_results();
												$project_data = $project_data = $this->db // get the user detail
												->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type')
												->from($project_status_table_array['table_name'].' pd')
												->join('users u', 'u.user_id = pd.project_owner_id', 'left')
												->where('pd.project_id', $project_id)
												->get()->row_array();
												
												if($count_in_progress_bids == 0){
													$is_project_status_change = '1';
													$project_status = $this->config->item('project_status_completed');
													
													$project_completed_table_data_exists = $this->db->where(['project_id' => $project_id])->from('hourly_rate_based_projects_completed')->count_all_results();
													if($project_completed_table_data_exists == 0){
														$profile_name = $project_data['profile_name'];
														unset($project_data['profile_name']);
														unset($project_data['id']);
														unset($project_data['gender']);
														unset($project_data['first_name']);
														unset($project_data['last_name']);
														unset($project_data['company_name']);
														unset($project_data['account_type']);
														$project_data['project_completion_date'] = $project_completion_date;
														$this->db->insert ('hourly_rate_based_projects_completed', $project_data);// 
														$this->db->delete('hourly_rate_based_projects_progress', ['project_id' => $project_id]); 
														
														$this->db->delete('hourly_rate_based_projects_incomplete', ['project_id' => $project_id]); 
														
														$users_ftp_dir 	= USERS_FTP_DIR; 
														$projects_ftp_dir = PROJECTS_FTP_DIR;
														$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
														$project_in_complete_dir = PROJECT_INCOMPLETE_DIR;
														$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
													
														$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
														$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
														$project_completed_dir = PROJECT_COMPLETED_DIR;
														//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir, 0777);// create projects directory if not exists
														
														
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR);
														
														
														
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
														
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir);
														
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.DIRECTORY_SEPARATOR);
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir);
														
														
														//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir, 0777);// create awaiting_moderation directory in projects folder
														//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id , 0777); // create the directory by using  project id
														//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir , 0777); // create the owner attachment directory by using  project id
														
														if($hourly_bid_status == 'in_progress'){
														
														$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir;
														}
														if($hourly_bid_status == 'in_complete'){
															$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id.$project_owner_attachments_dir;
														}
														
														$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.DIRECTORY_SEPARATOR;
														
														$source_list = $this->ftp->list_files($source_path);
														if(!empty($source_list)) {
															foreach($source_list as $path) {
																$arr = explode('/', $path);
																$file_size = $this->ftp->get_filesize($path);
																if($file_size != '-1') {
																	$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir.end($arr);
																	$this->ftp->move($path, $destination_path);
																}
															}
															}
														
														$bid_attachments = $this->db->where ('project_id', $project_id)->get ('projects_active_bids_users_attachments_tracking')->result_array ();
														
														
														if(!empty($bid_attachments)){
															foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
																$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
																if(empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
																	//die("sdfsdff");
																	
																	$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir);
																	
																	$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
																	
																	//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir , 0777); // create the owner attachment directory by using  project id
																	
																	/* $this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] , 0777); // create the owner attachment directory by using  project id */
																	//die("fsdffdf");
																	
																	if($hourly_bid_status == 'in_progress'){
																	
																		$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
																	}
																	if($hourly_bid_status == 'in_complete'){
																	
																		$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
																	}
																	
																	$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
																	if($file_size != '-1')
																	{
																		
																		$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
																		$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
																		
																	}
																}
															}
														}
														
														if($hourly_bid_status == 'in_progress'){
															// remove in progress folder

															if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id))) {
																$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id);
															}
														}
														if($hourly_bid_status == 'in_complete'){
															// remove in progress folder

															if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id))) {
																$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id);
															}
														}
														
														user_display_log($po_activity_log_message,$po_id); // activity log message for po
														user_display_log($sp_activity_log_message,$sp_id); // activity log message for sp
														
													}
												}
												$this->ftp->close();
												
												$project_completion_date_container_html = '<span class="default_black_bold"><i class="fa fa-clock-o" aria-hidden="true"></i> '.$this->config->item('project_details_page_completed_on').'</span>'.$project_completion_date;
						
												if($project_data['project_type'] == 'fulltime' ) {
													$project_value= $this->config->item('fulltime_projects_employer_view_total_project_value').str_replace(".00","",number_format($total_released_escrow,  2, '.', ' '))." ".CURRENCY;
												} else {
													$project_value= $this->config->item('fixed_or_hourly_project_value').str_replace(".00","",number_format($total_released_escrow,  2, '.', ' '))." ".CURRENCY;
												}
												$project_value= $this->config->item('fixed_or_hourly_project_value').str_replace(".00","",number_format($total_released_escrow,  2, '.', ' '))." ".CURRENCY;
												$total_paid_amount = $total_released_escrow_po;
												if($is_bid_completed == '1' ) {
													$completed_bidder_attachment_list = $this->db // get the user detail
													->select('id,bid_attachment_name,user_id')
													->from('projects_active_bids_users_attachments_tracking')
													->where('project_id', $hourly_project_bid_data['project_id'])
													->where('user_id', $hourly_project_bid_data['winner_id'])
													->order_by('id DESC')
													->get()->result_array();

													$completed_bidder_data['bid_attachments'] = $completed_bidder_attachment_list;

													$total_project_value[$completed_bidder_data['winner_id']] = $completed_bidder_data['total_project_amount'];
													$completed_project_tab_project_details_page = '';
													if($view_type == 'sp'){
														$completed_project_tab_project_details_page = $this->config->item('project_details_page_completed_project_tab_sp_view_txt');
													}
													
													
													
													echo json_encode(['status' => 200,'completed_project_tab_project_details_page'=>$completed_project_tab_project_details_page,'total_paid_amount' => number_format($total_paid_amount, 0, '', ' '), 'section_id' => $sp_id, 'sp_msg'=>$sp_notification_msg,'sp_id'=>$sp_id,'po_msg'=>$po_notification_msg,'acknowledgement_notification_msg'=>$acknowledgement_notification_msg,'po_id'=>$po_id,'project_value'=>$project_value,'is_bid_completed'=>$is_bid_completed,'remove_id'=>$sp_id,'is_project_status_change'=>$is_project_status_change,
													'project_completion_date_container_html'=>$project_completion_date_container_html,'project_status'=>$project_status,'data'=>$this->load->view('bidding/project_completed_bidders_listing',array('project_data'=>$project_data,'completed_bidder_data'=>$completed_bidder_data, 'total_paid_amount' => $total_project_value), true)]);
													die;
												} else {
													/* echo json_encode(['status' => 200, 'total_paid_amount' => number_format($total_paid_amount, 0, '', ' '),'sp_msg'=>$sp_notification_msg,'sp_id'=>$sp_id,'po_msg'=>$po_notification_msg,'po_id'=>$po_id,'project_value'=>$project_value,'is_bid_completed'=>$is_bid_completed,'remove_id'=>$remove_id,'is_project_status_change'=>$is_project_status_change,'project_completion_date_container_html'=>$project_completion_date_container_html,'project_status'=>$project_status]);
													die; */
												}
											}
										}
									
									}
								}	
							}

							############## code for move in progress bid from in progress status to completed end for hourly project###
						}else{
							$feedback_data['feedback_provided_on_fulltime_project_id']= $project_id;
							$feedback_data['feedback_provided_on_date']= date('y-m-d H:i:s');
							if($user_id == $po_id && $view_type == 'po'){
							
								$feedback_data['feedback_recived_by_employee_id']= $sp_id;
								$feedback_data['feedback_given_by_employer_id']= $po_id;
								$feedback_data['employee_shows_interest_enthusiasm_for_work']=$post_data['employee_shows_interest_enthusiasm_for_work'];
								
								$feedback_data['employee_demonstrates_competency_in_knowledge_skills']=$post_data['employee_demonstrates_competency_in_knowledge_skills'];
								$feedback_data['employee_demonstrates_levels_of_skill_knowledge']=$post_data['employee_demonstrates_competency_in_knowledge_skills'];
								$feedback_data['employee_dependable_and_relied']=$post_data['employee_dependable_and_relied'];
								$feedback_data['employee_properly_organizes_prioritizes']=$post_data['employee_properly_organizes_prioritizes'];
								
								
								
								$feedback_data['demonstrates_effective_oral_verbal_communication_skills']=$post_data['verbal_communication_skills'];
								$feedback_data['work_quality']=$post_data['work_quality'];
								$feedback_data['self_motivated']=$post_data['self_motivated'];
								$feedback_data['working_relations']=$post_data['working_relations'];
								$feedback_data['demonstrates_flexibility_adaptability']=$post_data['demonstrates_flexibility_adaptability'];
								$feedback_data['solves_problems']=$post_data['solves_problems'];
								$feedback_data['work_ethic']=$post_data['work_ethic'];
								
								$feedback_data['feedback_left_by_employer']=trim($post_data['feedback']);
								
								$fulltime_project_avg_rating_as_employee = ($post_data['verbal_communication_skills']+$post_data['work_quality']+$post_data['self_motivated']+$post_data['working_relations']+$post_data['demonstrates_flexibility_adaptability']+$post_data['solves_problems']+$post_data['work_ethic'])/7;
								
								
								
								
								$feedback_data['fulltime_project_avg_rating_as_employee']=number_format($fulltime_project_avg_rating_as_employee,2);
								
								
								
								/* if($check_employee_given_feedback_to_employer > 0){
									$feedback_data['employee_already_placed_feedback'] = 'Y';
								} */
								
								
								if($this->db->insert ('fulltime_prj_users_received_ratings_feedbacks_as_employee', $feedback_data)){
								
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" start 
									
									$this->db->select('id');
									$this->db->from('projects_candidates_for_users_ratings_feedbacks_exchange tpa');
									$this->db->where('project_id',$project_id);
									$this->db->where('po_id',$po_id); 
									$this->db->where('sp_id',$sp_id);
									$this->db->where('sp_rating_to_po_date IS NULL');
									$check_ratings_feedbacks_exchange = $this->db->get()->row_array();
									if(!empty($check_ratings_feedbacks_exchange)){
										$this->db->update('projects_candidates_for_users_ratings_feedbacks_exchange', ['po_rating_to_sp_date'=>$feedback_data['feedback_provided_on_date']], ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]);
									}else{
										$this->db->delete('projects_candidates_for_users_ratings_feedbacks_exchange',  ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]); 
									}
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" end 
								
									$check_employee_given_feedback_to_employer = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_given_by_employee_id'=>$sp_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')->count_all_results();
								
									$check_employer_given_feedback_to_employee = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employee_id'=>$sp_id,'feedback_given_by_employer_id'=>$po_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')->count_all_results();
								
								
									if($check_employee_given_feedback_to_employer > 0 && $check_employer_given_feedback_to_employee >0){
										$this->db->update('fulltime_prj_users_received_ratings_feedbacks_as_employer', ['employer_already_placed_feedback'=>'Y'], ['feedback_given_by_employee_id'=> $sp_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_provided_on_fulltime_project_id'=>$project_id]);
										
										$this->db->update('fulltime_prj_users_received_ratings_feedbacks_as_employee', ['employee_already_placed_feedback'=>'Y'], ['feedback_recived_by_employee_id'=> $sp_id,'feedback_given_by_employer_id'=>$po_id,'feedback_provided_on_fulltime_project_id'=>$project_id]);
										
										// update user total rating in users_details table for fulltime project of employeer
										$this->db->select('AVG(fulltime_project_avg_rating_as_employer) as project_avg_rating_as_po');
										$this->db->from('fulltime_prj_users_received_ratings_feedbacks_as_employer');
										$this->db->where(['feedback_recived_by_employer_id'=>$po_id,'employer_already_placed_feedback'=>'Y']);
										$avg_rating_result = $this->db->get();
										$avg_rating_row = $avg_rating_result->row_array();
										$user_total_avg_rating_as_po = $avg_rating_row['project_avg_rating_as_po'];
										
										
										$this->db->update('users_details', ['fulltime_project_user_total_avg_rating_as_employer'=>number_format($user_total_avg_rating_as_po,2)], ['user_id'=> $po_id]);
										
									
										//if($check_employee_given_feedback_to_employer > 0){
											// update user total rating in users_details table for fulltime project of employee
											$this->db->select('AVG(fulltime_project_avg_rating_as_employee) as project_avg_rating_as_sp');
											$this->db->from('fulltime_prj_users_received_ratings_feedbacks_as_employee');
											$this->db->where(['feedback_recived_by_employee_id'=>$sp_id,'employee_already_placed_feedback'=>'Y']);
											$avg_rating_result = $this->db->get();
											$avg_rating_row = $avg_rating_result->row_array();
											$user_total_avg_rating_as_sp = $avg_rating_row['project_avg_rating_as_sp'];
											
											$this->db->update('users_details', ['fulltime_project_user_total_avg_rating_as_employee'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
											
											####### update total rating of sp ###
											
											$user_details = $this->db // get the user detail
											->select('project_user_total_avg_rating_as_sp,fulltime_project_user_total_avg_rating_as_employee')
											->from('users_details')
											->where('user_id', $sp_id)
											->get()->row_array();
											 
											if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
												$user_total_avg_rating_as_sp = ($user_details['project_user_total_avg_rating_as_sp']+$user_details['fulltime_project_user_total_avg_rating_as_employee'])/2;
											}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) == 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
												$user_total_avg_rating_as_sp = $user_details['fulltime_project_user_total_avg_rating_as_employee'];
											}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) == 0){
												$user_total_avg_rating_as_sp = $user_details['project_user_total_avg_rating_as_sp'];
											}
											
											$this->db->update('users_details', ['user_total_avg_rating_as_sp'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
									//}
									}
								
									// Log message code
									$po_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employer_when_given_feedback_user_activity_log_displayed_message');
									
									$po_activity_log_message = str_replace(array("{sp_profile_url_link}","{user_first_name_last_name_or_company_name}","{project_title}","{project_url_link}"),array($sp_profile_url_link,$sp_name,$project_data['project_title'],$project_url_link),$po_activity_log_message);
									
									
									if(($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')){
										if($po_data['gender'] == 'M'){
											if($po_data['is_authorized_physical_person'] == 'Y'){
												$sp_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_company_app_male_given_feedback_user_activity_log_displayed_message');
											}else{
												$sp_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_male_given_feedback_user_activity_log_displayed_message');
											}
											
										}else{
											if($po_data['is_authorized_physical_person'] == 'Y'){
												$sp_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_company_app_female_given_feedback_user_activity_log_displayed_message');
											}else{
												$sp_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_female_given_feedback_user_activity_log_displayed_message');
											}
											
										}
										$sp_activity_log_message = str_replace(array("{po_profile_url_link}","{user_first_name_last_name}","{project_title}","{project_url_link}"),array($po_profile_url_link,$po_name,$project_data['project_title'],$project_url_link),$sp_activity_log_message);
										
										
									}else{
										$sp_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_company_given_feedback_user_activity_log_displayed_message');
										
										$sp_activity_log_message = str_replace(array("{po_profile_url_link}","{user_company_name}","{project_title}","{project_url_link}"),array($po_profile_url_link,$po_name,$project_data['project_title'],$project_url_link),$sp_activity_log_message);
									}
								}
							
							
							}else if($user_id == $sp_id && $view_type == 'sp'){
								$feedback_data['feedback_recived_by_employer_id']= $po_id;
								$feedback_data['feedback_given_by_employee_id']= $sp_id;
								$feedback_data['appreciated_right_level']= $post_data['appreciated_right_level'];
								$feedback_data['empowered_take_extra_responsibilities']= $post_data['empowered_take_extra_responsibilities'];
								
								$feedback_data['recognition_work_achievements']= $post_data['recognition_work_achievements'];
								$feedback_data['receive_regular_consistent_feedback']= $post_data['receive_regular_consistent_feedback'];
								$feedback_data['work_life_balance']= $post_data['work_life_balance'];
								$feedback_data['career_opportunities']= $post_data['career_opportunities'];
								$feedback_data['compensation_benefits']= $post_data['compensation_benefits'];
								$feedback_data['proper_training_support_mentorship_leadership']= $post_data['proper_training_support_mentorship_leadership'];
								$feedback_data['explained_job_responsibilities_expectation']= $post_data['job_responsibilities_expectation'];
								$feedback_data['environment_encourages_expressing_sharing_ideas_innovation']= $post_data['environment_encourages_expressing'];
								$feedback_data['safe_healthy_environment']= $post_data['safe_healthy_environment'];
								$feedback_data['recommend_this_company']= $post_data['recommend_this_company'];
								
								
								$feedback_data['feedback_left_by_employee']=trim($post_data['feedback']);
								
								$fulltime_project_avg_rating_as_employer = ($post_data['work_life_balance']+$post_data['career_opportunities']+$post_data['compensation_benefits']+$post_data['proper_training_support_mentorship_leadership']+$post_data['job_responsibilities_expectation']+$post_data['environment_encourages_expressing']+$post_data['safe_healthy_environment'])/7;
								                  
								$feedback_data['fulltime_project_avg_rating_as_employer']=number_format($fulltime_project_avg_rating_as_employer,2);
								                             
								/* $check_employer_given_feedback_to_employer = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_given_by_employer_id'=>$po_id,'feedback_recived_by_employee_id'=>$sp_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')->count_all_results();
								if($check_employer_given_feedback_to_employer > 0){
									$feedback_data['employer_already_placed_feedback'] = 'Y';
								} */
								 
								
								
								if($this->db->insert ('fulltime_prj_users_received_ratings_feedbacks_as_employer', $feedback_data)){
								
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" start 
									
									$this->db->select('id');
									$this->db->from('projects_candidates_for_users_ratings_feedbacks_exchange tpa');
									$this->db->where('project_id',$project_id);
									$this->db->where('po_id',$po_id);
									$this->db->where('sp_id',$sp_id);
									$this->db->where('po_rating_to_sp_date IS NULL');
									$check_ratings_feedbacks_exchange = $this->db->get()->row_array();
									if(!empty($check_ratings_feedbacks_exchange)){
										$this->db->update('projects_candidates_for_users_ratings_feedbacks_exchange', ['sp_rating_to_po_date'=>$feedback_data['feedback_provided_on_date']], ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]);
									}else{
										$this->db->delete('projects_candidates_for_users_ratings_feedbacks_exchange',  ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]); 
									}
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" end 
								
								
									$check_employee_given_feedback_to_employer = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_given_by_employee_id'=>$sp_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')->count_all_results();
								
									$check_employer_given_feedback_to_employee = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employee_id'=>$sp_id,'feedback_given_by_employer_id'=>$po_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')->count_all_results();
								
								
									if($check_employee_given_feedback_to_employer > 0 && $check_employer_given_feedback_to_employee >0)
									{
										$this->db->update('fulltime_prj_users_received_ratings_feedbacks_as_employer', ['employer_already_placed_feedback'=>'Y'], ['feedback_given_by_employee_id'=> $sp_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_provided_on_fulltime_project_id'=>$project_id]);
										
										$this->db->update('fulltime_prj_users_received_ratings_feedbacks_as_employee', ['employee_already_placed_feedback'=>'Y'], ['feedback_recived_by_employee_id'=> $sp_id,'feedback_given_by_employer_id'=>$po_id,'feedback_provided_on_fulltime_project_id'=>$project_id]);
									// update user total rating in users_details table for fulltime project of employee
										$this->db->select('AVG(fulltime_project_avg_rating_as_employee) as project_avg_rating_as_sp');
										$this->db->from('fulltime_prj_users_received_ratings_feedbacks_as_employee');
										$this->db->where(['feedback_recived_by_employee_id'=>$sp_id,'employee_already_placed_feedback'=>'Y']);
										$avg_rating_result = $this->db->get();
										$avg_rating_row = $avg_rating_result->row_array();
										$user_total_avg_rating_as_sp = $avg_rating_row['project_avg_rating_as_sp'];
										
										$this->db->update('users_details', ['fulltime_project_user_total_avg_rating_as_employee'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
										
										####### update total rating of sp ###
										
										$user_details = $this->db // get the user detail
										->select('project_user_total_avg_rating_as_sp,fulltime_project_user_total_avg_rating_as_employee')
										->from('users_details')
										->where('user_id', $sp_id)
										->get()->row_array();
										
										if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
											$user_total_avg_rating_as_sp = ($user_details['project_user_total_avg_rating_as_sp']+$user_details['fulltime_project_user_total_avg_rating_as_employee'])/2;
										}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) == 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
											$user_total_avg_rating_as_sp = $user_details['fulltime_project_user_total_avg_rating_as_employee'];
										}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) == 0){
											$user_total_avg_rating_as_sp = $user_details['project_user_total_avg_rating_as_sp'];
										}
										$this->db->update('users_details', ['user_total_avg_rating_as_sp'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
									
									//if($check_employer_given_feedback_to_employer > 0){
										// update user total rating in users_details table for fulltime project of employeer
										$this->db->select('AVG(fulltime_project_avg_rating_as_employer) as project_avg_rating_as_po');
										$this->db->from('fulltime_prj_users_received_ratings_feedbacks_as_employer');
										$this->db->where(['feedback_recived_by_employer_id'=>$po_id,'employer_already_placed_feedback'=>'Y']);
										$avg_rating_result = $this->db->get();
										$avg_rating_row = $avg_rating_result->row_array();
										$user_total_avg_rating_as_po = $avg_rating_row['project_avg_rating_as_po'];
										
										
										$this->db->update('users_details', ['fulltime_project_user_total_avg_rating_as_employer'=>number_format($user_total_avg_rating_as_po,2)], ['user_id'=> $po_id]);
									//}
								
									}
									$sp_activity_log_message= $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employee_when_given_feedback_user_activity_log_displayed_message');
									
									$sp_activity_log_message = str_replace(array('{po_profile_url_link}','{user_first_name_last_name_or_company_name}','{project_title}','{project_url_link}'),array($po_profile_url_link,$po_name,$project_data['project_title'],$project_url_link),$sp_activity_log_message);
									
									
									if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE  && $sp_data['is_authorized_physical_person'] == 'Y')){
										if($sp_data['gender'] == 'M'){
											if($sp_data['is_authorized_physical_person'] == 'Y'){
												$po_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_company_app_male_given_feedback_user_activity_log_displayed_message');
											}else{
												$po_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_male_given_feedback_user_activity_log_displayed_message');
											}
											
										}else{
											if($sp_data['is_authorized_physical_person'] == 'Y'){
												$po_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_company_app_female_given_feedback_user_activity_log_displayed_message');
											}else{
												$po_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_female_given_feedback_user_activity_log_displayed_message');
											}
											
										}
										$po_activity_log_message = str_replace(array('{sp_profile_url_link}','{user_first_name_last_name}','{project_title}','{project_url_link}'),array($sp_profile_url_link,$sp_name,$project_data['project_title'],$project_url_link),$po_activity_log_message);
										
										
									}else{
										$po_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_company_given_feedback_user_activity_log_displayed_message');
										
										$po_activity_log_message = str_replace(array('{sp_profile_url_link}','{user_company_name}','{project_title}','{project_url_link}'),array($sp_profile_url_link,$sp_name,$project_data['project_title'],$project_url_link),$po_activity_log_message);
									}
								}
							}
							user_display_log($po_activity_log_message,$po_id); // activity log message for po
							user_display_log($sp_activity_log_message,$sp_id); // activity log message for sp
						
						}
						echo json_encode(['status' => 200,'is_bid_completed'=>'0','location'=>'']);
						die;
					}else{
						echo json_encode ($validation_data_array);
						die;
					}
				
				}
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH ;
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
			return;
		}
	}
	
	/* This function is used to load the feedback tab data.
	*/
	public function update_user_ratings_feedbacks_section_tabs_data_project_detail(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ 
			
				$data = array();
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				
				$section_id = $this->input->post ('section_id');
				$project_id = $this->input->post ('project_id');
				$view_type = $this->input->post ('view_type');
				$section_name = $this->input->post ('section_name');
				$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
				$sp_id = Cryptor::doDecrypt($this->input->post ('sp_id'));
				$project_type = $this->input->post ('project_type');
				$tab_type = $this->input->post ('tab_type');
				
				if(($view_type == 'po' &&  $user_id != $po_id) || ($view_type == 'sp' &&  $user_id != $sp_id)){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(!empty($project_status_table_array['project_status']) && !empty($project_status_table_array['table_name'])){
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
						$project_data = $this->db // get the user detail
						->select('pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title')
						->from($project_status_table_array['table_name'].' pd')
						->where('pd.fulltime_project_id', $project_id)
						->get()->row_array();
					}else{
						$project_data = $this->db // get the user detail
						->select('pd.project_id,pd.project_title')
						->from($project_status_table_array['table_name'].' pd')
						->where('pd.project_id', $project_id)
						->get()->row_array();
					}
					$sp_data = $this->db // get the user detail
					->select('u.user_id,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
					->from('users u')
					->where('u.user_id', $sp_id)
					->get()->row_array();
					
					$po_data = $this->db // get the user detail
					->select('u.user_id,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
					->from('users u')
					->where('u.user_id', $po_id)
					->get()->row_array();
					
					$sp_name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
					
					$po_name = (($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')) ?$po_data['first_name'] . ' ' . $po_data['last_name'] :$po_data['company_name'];
					
					$data['section_id'] = $section_id;
					$data['project_id'] = $project_id;
					$data['view_type'] = $view_type;
					$data['section_name'] = $section_name;
					$data['po_id'] = $po_id;
					$data['sp_id'] = $sp_id;
					$data['sp_name'] = $sp_name;
					$data['po_name'] = $po_name;
					$data['sp_gender'] = $sp_data['gender'];
					$data['po_gender'] = $po_data['gender'];
					$data['sp_account_type'] = $sp_data['account_type'];
					$data['po_account_type'] = $po_data['account_type'];
					$data['sp_is_authorized_physical_person'] = $sp_data['is_authorized_physical_person'];
					$data['po_is_authorized_physical_person'] = $po_data['is_authorized_physical_person'];
					
					
					$data['project_type'] = $project_type;
					//$data['tab_type'] = $tab_type;
					$feedback_given_msg = '';// message when other part given the feedabck for feedback received tab
					$other_party_given_feedback_msg = '';// message when other party given the feedback for feedback received tab
					$other_party_not_given_feedback_msg = ''; // message when other party not given the message for feedback received tab
					$feedback_data = array();
					$check_receiver_view_his_rating = 0;
					$check_receiver_received_rating = 0;
					if($tab_type == 'feedback_received'){
						
						if($project_type != 'fulltime'){
							if($user_id == $po_id && $view_type == 'po'){
							
							
							  $check_receiver_view_his_rating = $this->db->where(['feedback_given_by_po_id' => $po_id,'feedback_recived_by_sp_id'=>$sp_id,'feedback_provided_on_project_id'=>$project_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
							
								$check_receiver_received_rating = $this->db->where(['feedback_given_by_sp_id' => $sp_id,'feedback_recived_by_po_id'=>$po_id,'feedback_provided_on_project_id'=>$project_id])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
								
							
								$feedback_data = $this->db // get the user detail
								->select('*')
								->from('projects_users_received_ratings_feedbacks_as_po')
								->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_po_id'=>$po_id,'feedback_given_by_sp_id'=>$sp_id])
								->get()->row_array();
								if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
								
									if($sp_data['gender'] == 'M')
									{
										
										if($sp_data['is_authorized_physical_person'] == 'Y'){
											$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_male_feedback_provider');
											
											$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_male_party_given_feedback');
											
											
										}else{	
										
											$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_male_feedback_provider');
											
											$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_male_party_given_feedback');
										}
										
										
										
										if(empty($feedback_data)){
											
											if($sp_data['is_authorized_physical_person'] == 'Y'){
												$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_male_party_not_given_feedback');
											}else{
											
												$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_male_party_not_given_feedback');
											}
										}
										
									}else{
										if($sp_data['is_authorized_physical_person'] == 'Y'){
											$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_female_feedback_provider');
											
											$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_female_party_given_feedback');
											
										}else{	
											$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_female_feedback_provider');
											
											$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_female_party_given_feedback');
										}
										
										
										
										if(empty($feedback_data)){
											if($sp_data['is_authorized_physical_person'] == 'Y'){
												$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_female_party_not_given_feedback');
												
											}else{	
												$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_female_party_not_given_feedback');
											}
										}
									}
									$feedback_given_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
									
									$other_party_given_feedback_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
									
									if(empty($feedback_data)){
										$other_party_not_given_feedback_msg = str_replace(array('{user_first_name_last_name}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_not_given_feedback_msg);
									}
								
								}else{
									$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_feedback_provider');
									
									$feedback_given_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
									
									$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_party_given_feedback');
									
									$other_party_given_feedback_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
									
									if(empty($feedback_data)){
										$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_party_not_given_feedback');
									
										$other_party_not_given_feedback_msg = str_replace(array('{user_company_name}'),array($sp_name),$other_party_not_given_feedback_msg);
									}
								}
								
							
							}else if($user_id == $sp_id && $view_type == 'sp'){
								
								$check_receiver_view_his_rating = $this->db->where(['feedback_recived_by_po_id' => $po_id,'feedback_given_by_sp_id'=>$sp_id,'feedback_provided_on_project_id'=>$project_id])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
								
								$check_receiver_received_rating = $this->db->where(['feedback_given_by_po_id' => $po_id,'feedback_recived_by_sp_id'=>$sp_id,'feedback_provided_on_project_id'=>$project_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
							
								$feedback_data = $this->db // get the user detail
								->select('*')
								->from('projects_users_received_ratings_feedbacks_as_sp')
								->where(['feedback_provided_on_project_id' => $project_id,'feedback_given_by_po_id'=>$po_id,'feedback_recived_by_sp_id'=>$sp_id])
								->get()->row_array();
								
								if(($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')){
									
									if($po_data['gender'] == 'M')
									{
										
										if($po_data['is_authorized_physical_person'] == 'Y'){
											$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_male_feedback_provider');
											$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_male_party_given_feedback');
										}else{	
											$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_male_feedback_provider');
											$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_male_party_given_feedback');
										}
										
										if(empty($feedback_data)){
											
											if($po_data['is_authorized_physical_person'] == 'Y'){
												$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_male_party_not_given_feedback');
											}else{
											
												$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_male_party_not_given_feedback');
											}
										}
									}else{
										
										if($po_data['is_authorized_physical_person'] == 'Y'){
											$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_female_feedback_provider');
											$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_female_party_given_feedback');
										}else{
											$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_female_feedback_provider');
											$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_female_party_given_feedback');
										}
										
										if(empty($feedback_data)){
											if($po_data['is_authorized_physical_person'] == 'Y'){
												$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_female_party_not_given_feedback');
											}else{
												$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_female_party_not_given_feedback');
											}
										}
									}
									$feedback_given_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
									$other_party_given_feedback_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
									if(empty($feedback_data)){
										$other_party_not_given_feedback_msg = str_replace(array('{user_first_name_last_name}'),array($po_name),$other_party_not_given_feedback_msg);
									}
								
								}else{
									
									$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_feedback_provider');
									$feedback_given_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
									
									$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_party_given_feedback');
									
									$other_party_given_feedback_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
									if(empty($feedback_data)){
										
										$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_party_not_given_feedback');
										$other_party_not_given_feedback_msg = str_replace(array('{user_company_name}'),array($po_name),$other_party_not_given_feedback_msg);
										
									}
								}
							}
							
						}else{
							
							if($user_id == $po_id && $view_type == 'po'){
							
								$check_receiver_view_his_rating = $this->db->where(['feedback_given_by_employer_id' => $po_id,'feedback_recived_by_employee_id'=>$sp_id,'feedback_provided_on_fulltime_project_id'=>$project_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')->count_all_results();
						
								$check_receiver_received_rating = $this->db->where(['feedback_given_by_employee_id' => $sp_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_provided_on_fulltime_project_id'=>$project_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')->count_all_results();
								$feedback_data = $this->db // get the user detail
								->select('*')
								->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')
								->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_given_by_employee_id'=>$sp_id])
								->get()->row_array();
								
								if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
									if($sp_data['gender'] == 'M')
									{
										if($sp_data['is_authorized_physical_person'] == 'Y'){
											$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_male_feedback_provider');
										
											$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_male_party_given_feedback');
										}else{	
											$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_male_feedback_provider');
										
											$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_male_party_given_feedback');
										}
										
										if(empty($feedback_data)){
											
											if($sp_data['is_authorized_physical_person'] == 'Y'){
												$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_male_party_not_given_feedback');
											}else{
											
												$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_male_party_not_given_feedback');
											}
										}
										
									}else{
										if($sp_data['is_authorized_physical_person'] == 'Y'){
											$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_female_feedback_provider');
											
											$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_female_party_given_feedback');
										}else{
											$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_female_feedback_provider');
											
											$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_female_party_given_feedback');
										}
										
										if(empty($feedback_data)){
											if($sp_data['is_authorized_physical_person'] == 'Y'){
												$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_female_party_not_given_feedback');
											}else{
												$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_female_party_not_given_feedback');
											}
										}
									}
									$feedback_given_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
									
									$other_party_given_feedback_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
									
									if(empty($feedback_data)){
										$other_party_not_given_feedback_msg = str_replace(array('{user_first_name_last_name}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_not_given_feedback_msg);
									}
								
								}else{
									$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_feedback_provider');
									
									$feedback_given_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
									
									$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_party_given_feedback');
									
									$other_party_given_feedback_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
									
									if(empty($feedback_data)){
										$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_party_not_given_feedback');
									
										$other_party_not_given_feedback_msg = str_replace(array('{user_company_name}'),array($sp_name),$other_party_not_given_feedback_msg);
									}
								}
						
							}else if($user_id == $sp_id && $view_type == 'sp'){
							
								$check_receiver_view_his_rating = $this->db->where(['feedback_recived_by_employer_id' => $po_id,'feedback_given_by_employee_id'=>$sp_id,'feedback_provided_on_fulltime_project_id'=>$project_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')->count_all_results();
								
								$check_receiver_received_rating = $this->db->where(['feedback_given_by_employer_id' => $po_id,'feedback_recived_by_employee_id'=>$sp_id,'feedback_provided_on_fulltime_project_id'=>$project_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')->count_all_results();
								
								$feedback_data = $this->db // get the user detail
								->select('*')
								->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')
								->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employee_id'=>$sp_id,'feedback_given_by_employer_id'=>$po_id])
								->get()->row_array();
								if(($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')){
									if($po_data['gender'] == 'M')
									{
										
										if($po_data['is_authorized_physical_person'] == 'Y'){
											$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_male_feedback_provider');
											$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_male_party_given_feedback');	
										}else{	
										
											$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_male_feedback_provider');
											$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_male_party_given_feedback');
										}
										if(empty($feedback_data)){
											if($po_data['is_authorized_physical_person'] == 'Y'){
												$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_male_party_not_given_feedback');
											}else{	
												$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_male_party_not_given_feedback');
											}
										}
									}else{
										if($po_data['is_authorized_physical_person'] == 'Y'){
											$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_female_feedback_provider');
											$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_female_party_given_feedback');
										}else{
											$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_female_feedback_provider');
											$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_female_party_given_feedback');
										}
										if(empty($feedback_data)){
											if($po_data['is_authorized_physical_person'] == 'Y'){
												$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_female_party_not_given_feedback');
											}else{	
												$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_female_party_not_given_feedback');
											}
										}
									}
									$feedback_given_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
									$other_party_given_feedback_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
									if(empty($feedback_data)){
										$other_party_not_given_feedback_msg = str_replace(array('{user_first_name_last_name}'),array($po_name),$other_party_not_given_feedback_msg);
									}
								
								}else{
									$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_feedback_provider');
									$feedback_given_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
									
									$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_party_given_feedback');
									
									$other_party_given_feedback_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
									if(empty($feedback_data)){
										$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_party_not_given_feedback');
										$other_party_not_given_feedback_msg = str_replace(array('{user_company_name}'),array($po_name),$other_party_not_given_feedback_msg);
									}
								}
							
							}
						}
					}else if($tab_type == 'feedback_given'){
						
						if($project_type != 'fulltime'){
							
							
							if($user_id == $po_id && $view_type == 'po'){
							
								$check_receiver_view_his_rating = $this->db->where(['feedback_given_by_po_id' => $po_id,'feedback_recived_by_sp_id'=>$sp_id,'feedback_provided_on_project_id'=>$project_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
							
							
								$feedback_data = $this->db // get the user detail
								->select('*')
								->from('projects_users_received_ratings_feedbacks_as_sp')
								->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_sp_id'=>$sp_id,'feedback_given_by_po_id'=>$po_id])
								->get()->row_array();
								
								$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_given_tabs_project_details_page_feedback_message');
								$feedback_given_msg = str_replace(array('{feedback_provided_on_date}'),array(date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
								
							
							}else if($user_id == $sp_id && $view_type == 'sp'){
							
								$check_receiver_view_his_rating = $this->db->where(['feedback_recived_by_po_id' => $po_id,'feedback_given_by_sp_id'=>$sp_id,'feedback_provided_on_project_id'=>$project_id])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
								
							
								$feedback_data = $this->db // get the user detail
								->select('*')
								->from('projects_users_received_ratings_feedbacks_as_po')
								->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_po_id'=>$po_id,'feedback_given_by_sp_id'=>$sp_id])
								->get()->row_array();
								
								
								$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_given_tabs_project_details_page_feedback_message');
								$feedback_given_msg = str_replace(array('{feedback_provided_on_date}'),array(date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
							}
						}else{
							if($user_id == $po_id && $view_type == 'po'){
							
							
								$check_receiver_view_his_rating = $this->db->where(['feedback_given_by_employer_id' => $po_id,'feedback_recived_by_employee_id'=>$sp_id,'feedback_provided_on_fulltime_project_id'=>$project_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')->count_all_results();
							
							
								$feedback_data = $this->db // get the user detail
								->select('*')
								->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')
								->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employee_id'=>$sp_id,'feedback_given_by_employer_id'=>$po_id])
								->get()->row_array();
								
								$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_given_tabs_project_details_page_feedback_message');
								$feedback_given_msg = str_replace(array('{feedback_provided_on_date}'),array(date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
						
							}else if($user_id == $sp_id && $view_type == 'sp'){
							
								$check_receiver_view_his_rating = $this->db->where(['feedback_recived_by_employer_id' => $po_id,'feedback_given_by_employee_id'=>$sp_id,'feedback_provided_on_fulltime_project_id'=>$project_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')->count_all_results();
								
							
								$feedback_data = $this->db // get the user detail
								->select('*')
								->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')
								->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_given_by_employee_id'=>$sp_id])
								->get()->row_array();
								
								
								$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_given_tabs_project_details_page_feedback_message');
								$feedback_given_msg = str_replace(array('{feedback_provided_on_date}'),array(date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
							}
						}
					}
					
					$data['feedback_data'] = $feedback_data;
					
					
					
					
					$data['check_receiver_view_his_rating'] = $check_receiver_view_his_rating;
					$data['check_receiver_received_rating'] = $check_receiver_received_rating;
					$data['other_party_given_feedback_msg'] = $other_party_given_feedback_msg;
					$data['other_party_not_given_feedback_msg'] = $other_party_not_given_feedback_msg;
					$data['feedback_given_msg'] = $feedback_given_msg;
					
					
					
					if($tab_type == 'feedback_received'){
						echo json_encode(['status' => 200,'data'=>$this->load->view('users_ratings_feedbacks_received_section_project_detail_page',$data, true)]);
						die;
					}
					if($tab_type == 'feedback_given'){
						
						echo json_encode(['status' => 200,'data'=>$this->load->view('users_ratings_feedbacks_given_section_project_detail_page',$data, true)]);
						die;
					}
				}	
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
			return;
		}
	
	
	}
	
	
	/*
	This function is used for update reviews and ratings data under the feedback tab on profile page
	*/
	public function update_user_profile_page_feedbacks_tab_data(){
		if($this->input->is_ajax_request ()){
			if ($this->input->post () )
			{
				$tab_type = $this->input->post ('tab_type');
				$profile_name = $this->input->post ('profile_name');
				$user_detail = $this->db // get the user detail
                ->select('u.user_id,u.profile_name ,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,u.gender,ud.project_user_total_avg_rating_as_po,ud.fulltime_project_user_total_avg_rating_as_employer,ud.project_user_total_avg_rating_as_sp,ud.fulltime_project_user_total_avg_rating_as_employee,ud.user_total_avg_rating_as_sp')
                ->from('users u')
				->join('users_details as ud', 'ud.user_id = u.user_id')
                ->where('u.profile_name LIKE ', $profile_name)
                ->get()->row_array();
				$data['user_detail'] = $user_detail;
				$name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];
				
				if($tab_type == 'sp'){
					
					
					$total_reviews_as_sp = $this->db->where(['feedback_recived_by_sp_id'=>$user_detail['user_id'],'sp_already_placed_feedback'=>'Y'])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
					
					
					$feedback_detail_heading = $this->config->item('user_profile_page_ratings_total_avg_rating_and_reviews_as_sp_txt');
					if($total_reviews_as_sp == 0){
						$reviews_as_sp = number_format($total_reviews_as_sp,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
					}else if($total_reviews_as_sp == 1) {
						$reviews_as_sp = number_format($total_reviews_as_sp,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
					} else if($total_reviews_as_sp > 1) {
						$reviews_as_sp = number_format($total_reviews_as_sp,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
					}
					
					$feedback_detail_heading = str_replace(array('{user_first_name_last_name_or_company_name}','{project_user_total_avg_rating_as_sp}','{total_projects_reviews}'),array($name,$user_detail['project_user_total_avg_rating_as_sp'],$reviews_as_sp),$feedback_detail_heading);
					$no_of_records_per_page = $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_limit');
					$ratings_feedbacks_listing_data = $this->Users_ratings_feedbacks_model->get_user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_listing(array('feedback_recived_by_sp_id'=>$user_detail['user_id'],'sp_already_placed_feedback'=>'Y'),0,$no_of_records_per_page);
				
					
					$data['view_type'] = 'sp';
					$data["ratings_feedbacks_data"] = $ratings_feedbacks_listing_data['data'];
					$data['ratings_feedbacks_count'] = $ratings_feedbacks_listing_data['total'];

					if(!empty($ratings_feedbacks_listing_data['data'])) {
						$data['is_last_page'] = ((1 ) == ceil(($data['ratings_feedbacks_count'] / $no_of_records_per_page))) ? true : false;
					} else {
						$data['is_last_page'] = true;
					} 
					
					echo json_encode(['status' => 200,'ratings_feedbacks_count'=>$ratings_feedbacks_listing_data['total'],'feedback_detail_heading'=>$feedback_detail_heading,'data'=>$this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_projects_ratings_feedbacks',$data, true)]);
					die;
				}
				
				if($tab_type == 'po'){
					$total_reviews_as_po = $this->db->where(['feedback_recived_by_po_id'=>$user_detail['user_id'],'po_already_placed_feedback'=>'Y'])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
					
					$feedback_detail_heading = $this->config->item('user_profile_page_ratings_total_avg_rating_and_reviews_as_po_txt');
					if($total_reviews_as_po == 0){
						$reviews_as_po = number_format($total_reviews_as_po,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
					}else if($total_reviews_as_po == 1) {
						$reviews_as_po = number_format($total_reviews_as_po,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
					} else if($total_reviews_as_po > 1) {
						$reviews_as_po = number_format($total_reviews_as_po,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
					}
					$feedback_detail_heading = str_replace(array('{user_first_name_last_name_or_company_name}','{project_user_total_avg_rating_as_po}','{total_projects_reviews}'),array($name,$user_detail['project_user_total_avg_rating_as_po'],$reviews_as_po),$feedback_detail_heading);
					$no_of_records_per_page = $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_po_tab_limit');
					$ratings_feedbacks_listing_data = $this->Users_ratings_feedbacks_model->get_user_profile_page_ratings_feedbacks_on_projects_as_po_tab_listing(array('feedback_recived_by_po_id'=>$user_detail['user_id'],'po_already_placed_feedback'=>'Y'),0,$no_of_records_per_page);
					$data['view_type'] = 'po';
					$data["ratings_feedbacks_data"] = $ratings_feedbacks_listing_data['data'];
					$data['ratings_feedbacks_count'] = $ratings_feedbacks_listing_data['total'];

					if(!empty($ratings_feedbacks_listing_data['data'])) {
						$data['is_last_page'] = ((1 ) == ceil(($data['ratings_feedbacks_count'] / $no_of_records_per_page))) ? true : false;
					} else {
						$data['is_last_page'] = true;
					} 
					
					echo json_encode(['status' => 200,'ratings_feedbacks_count'=>$ratings_feedbacks_listing_data['total'],'feedback_detail_heading'=>$feedback_detail_heading,'data'=>$this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_projects_ratings_feedbacks',$data, true)]);
					die;
				}
				
				if($tab_type == 'employee'){
					$total_reviews_as_employee = $this->db->where(['feedback_recived_by_employee_id'=>$user_detail['user_id'],'employee_already_placed_feedback'=>'Y'])->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')->count_all_results();
					$feedback_detail_heading = $this->config->item('user_profile_page_ratings_total_avg_rating_and_reviews_as_employee_txt');
					if($total_reviews_as_employee == 0){
						$reviews_as_employee = number_format($total_reviews_as_employee,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
					}else if($total_reviews_as_employee == 1) {
						$reviews_as_employee = number_format($total_reviews_as_employee,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
					} else if($total_reviews_as_employee > 1) {
						$reviews_as_employee = number_format($total_reviews_as_employee,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
					}
					$feedback_detail_heading = str_replace(array('{user_first_name_last_name_or_company_name}','{project_user_total_avg_rating_as_employee}','{total_fulltime_projects_reviews}'),array($name,$user_detail['fulltime_project_user_total_avg_rating_as_employee'],$reviews_as_employee),$feedback_detail_heading);
					$no_of_records_per_page = $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_limit');
					$ratings_feedbacks_listing_data = $this->Users_ratings_feedbacks_model->get_user_profile_page_ratings_feedbacks_on_projects_as_employee_tab_listing(array('feedback_recived_by_employee_id'=>$user_detail['user_id'],'employee_already_placed_feedback'=>'Y'),0,$no_of_records_per_page);
					$data['view_type'] = 'employee';
					$data["ratings_feedbacks_data"] = $ratings_feedbacks_listing_data['data'];
					$data['ratings_feedbacks_count'] = $ratings_feedbacks_listing_data['total'];

					if(!empty($ratings_feedbacks_listing_data['data'])) {
						$data['is_last_page'] = ((1 ) == ceil(($data['ratings_feedbacks_count'] / $no_of_records_per_page))) ? true : false;
					} else {
						$data['is_last_page'] = true;
					} 

					echo json_encode(['status' => 200,'ratings_feedbacks_count'=>$ratings_feedbacks_listing_data['total'],'feedback_detail_heading'=>$feedback_detail_heading,'data'=>$this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_fulltime_projects_ratings_feedbacks',$data, true)]);
					die;
				}
				if($tab_type == 'employer'){
					
					
					$total_reviews_as_employer = $this->db->where(['feedback_recived_by_employer_id'=>$user_detail['user_id'],'employer_already_placed_feedback'=>'Y'])->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')->count_all_results();
					
					$feedback_detail_heading = $this->config->item('user_profile_page_ratings_total_avg_rating_and_reviews_as_employer_txt');
					if($total_reviews_as_employer == 0){
						$reviews_as_employee = number_format($total_reviews_as_employer,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
					}else if($total_reviews_as_employer == 1) {
						$reviews_as_employee = number_format($total_reviews_as_employer,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
					} else if($total_reviews_as_employer > 1) {
						$reviews_as_employee = number_format($total_reviews_as_employer,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
					}
					$feedback_detail_heading = str_replace(array('{user_first_name_last_name_or_company_name}','{project_user_total_avg_rating_as_employer}','{total_fulltime_projects_reviews}'),array($name,$user_detail['fulltime_project_user_total_avg_rating_as_employer'],$reviews_as_employee),$feedback_detail_heading);
					$no_of_records_per_page = $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_limit');
					
					
					$ratings_feedbacks_listing_data = $this->Users_ratings_feedbacks_model->get_user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_listing(array('feedback_recived_by_employer_id'=>$user_detail['user_id'],'employer_already_placed_feedback'=>'Y'),0,$no_of_records_per_page);
					
					
					$data['view_type'] = 'employer';
					$data["ratings_feedbacks_data"] = $ratings_feedbacks_listing_data['data'];
					$data['ratings_feedbacks_count'] = $ratings_feedbacks_listing_data['total'];

					if(!empty($ratings_feedbacks_listing_data['data'])) {
						$data['is_last_page'] = ((1 ) == ceil(($data['ratings_feedbacks_count'] / $no_of_records_per_page))) ? true : false;
					} else {
						$data['is_last_page'] = true;
					} 
					echo json_encode(['status' => 200,'ratings_feedbacks_count'=>$ratings_feedbacks_listing_data['total'],'feedback_detail_heading'=>$feedback_detail_heading,'data'=>$this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_fulltime_projects_ratings_feedbacks',$data, true)]);
					die;
				}
				
			}else{
				show_custom_404_page(); //show custom 404 page
				return;
			}
		}else{
			
			show_custom_404_page(); //show custom 404 page
			return;
		}
	}
	
	
	
	
	// This function is using for loadmore paging on as service provider tab under feedbacks tab for user profile page  
	public function user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_loadmore_limit(){
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
				$post_data['pageno'];
				$pageno = $post_data['pageno'];
				$no_of_records_per_page = $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_limit');
				$offset = ($pageno-1) * $no_of_records_per_page;
				$ratings_feedbacks_listing_data = $this->Users_ratings_feedbacks_model->get_user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_listing(array('feedback_recived_by_sp_id'=>$user_detail['user_id'],'sp_already_placed_feedback'=>'Y'),$offset,$no_of_records_per_page);
				$data['view_type'] = 'sp';
				$data["ratings_feedbacks_data"] = $ratings_feedbacks_listing_data['data'];
				$data['ratings_feedbacks_count'] = $ratings_feedbacks_listing_data['total'];
				$count_records = count($ratings_feedbacks_listing_data['data']);

				if(!empty($ratings_feedbacks_listing_data['data'])) {
          $data['is_last_page'] = (($pageno ) == ceil(($data['ratings_feedbacks_count'] / $no_of_records_per_page))) ? true : false;
        } else {
          $data['is_last_page'] = true;
        } 

				echo json_encode(['status' => 200,'count_records'=>$count_records,'total_record'=>$ratings_feedbacks_listing_data['total'],'data'=>$this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_projects_ratings_feedbacks',$data, true)]);
				die;
			}else{
				show_custom_404_page(); //show custom 404 page
				return;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
			return;
		}
	
	} 
	
	// This function is using for loadmore paging on as project owner tab under feedbacks tab for user profile page  
	public function user_profile_page_ratings_feedbacks_on_projects_as_po_tab_loadmore_limit(){
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
				$post_data['pageno'];
				$pageno = $post_data['pageno'];
				$no_of_records_per_page = $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_po_tab_limit');
				$offset = ($pageno-1) * $no_of_records_per_page;
				$ratings_feedbacks_listing_data = $this->Users_ratings_feedbacks_model->get_user_profile_page_ratings_feedbacks_on_projects_as_po_tab_listing(array('feedback_recived_by_po_id'=>$user_detail['user_id'],'po_already_placed_feedback'=>'Y'),$offset,$no_of_records_per_page);
				$data['view_type'] = 'po';
				$data["ratings_feedbacks_data"] = $ratings_feedbacks_listing_data['data'];
				$data['ratings_feedbacks_count'] = $ratings_feedbacks_listing_data['total'];
				$count_records = count($ratings_feedbacks_listing_data['data']);

				if(!empty($ratings_feedbacks_listing_data['data'])) {
          $data['is_last_page'] = (($pageno ) == ceil(($data['ratings_feedbacks_count'] / $no_of_records_per_page))) ? true : false;
        } else {
          $data['is_last_page'] = true;
        } 

				echo json_encode(['status' => 200,'count_records'=>$count_records,'total_record'=>$ratings_feedbacks_listing_data['total'],'data'=>$this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_projects_ratings_feedbacks',$data, true)]);
				die;
			}else{
				show_custom_404_page(); //show custom 404 page
				return;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
			return;
		}
	
	} 
  
  
  // This function is using for loadmore paging on as employee tab under feedbacks tab for user profile page  
	public function user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_loadmore_limit(){
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
				$post_data['pageno'];
				$pageno = $post_data['pageno'];
				
				$no_of_records_per_page = $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_limit');
				$offset = ($pageno-1) * $no_of_records_per_page;
				$ratings_feedbacks_listing_data = $this->Users_ratings_feedbacks_model->get_user_profile_page_ratings_feedbacks_on_projects_as_employee_tab_listing(array('feedback_recived_by_employee_id'=>$user_detail['user_id'],'employee_already_placed_feedback'=>'Y'),$offset,$no_of_records_per_page);
				$data['view_type'] = 'employee';
				$data["ratings_feedbacks_data"] = $ratings_feedbacks_listing_data['data'];
				$data['ratings_feedbacks_count'] = $ratings_feedbacks_listing_data['total'];
				$count_records = count($ratings_feedbacks_listing_data['data']);

				if(!empty($ratings_feedbacks_listing_data['data'])) {
          $data['is_last_page'] = (($pageno ) == ceil(($data['ratings_feedbacks_count'] / $no_of_records_per_page))) ? true : false;
        } else {
          $data['is_last_page'] = true;
        } 

				echo json_encode(['status' => 200,'count_records'=>$count_records,'total_record'=>$ratings_feedbacks_listing_data['total'],'data'=>$this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_fulltime_projects_ratings_feedbacks',$data, true)]);
				die;
			}else{
				show_custom_404_page(); //show custom 404 page
				return;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
			return;
		}
	
	} 
	
	// This function is using for loadmore paging on as employer tab under feedbacks tab for user profile page  
	public function user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_loadmore_limit(){
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
				$post_data['pageno'];
				$pageno = $post_data['pageno'];
				
				$no_of_records_per_page = $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_limit');
				$offset = ($pageno-1) * $no_of_records_per_page;
				$ratings_feedbacks_listing_data = $this->Users_ratings_feedbacks_model->get_user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_listing(array('feedback_recived_by_employer_id'=>$user_detail['user_id'],'employer_already_placed_feedback'=>'Y'),$offset,$no_of_records_per_page);
				$data['view_type'] = 'employer';
				$data["ratings_feedbacks_data"] = $ratings_feedbacks_listing_data['data'];
				$data['ratings_feedbacks_count'] = $ratings_feedbacks_listing_data['total'];
				$count_records = count($ratings_feedbacks_listing_data['data']);

				if(!empty($ratings_feedbacks_listing_data['data'])) {
          $data['is_last_page'] = (($pageno ) == ceil(($data['ratings_feedbacks_count'] / $no_of_records_per_page))) ? true : false;
        } else {
          $data['is_last_page'] = true;
        } 

				echo json_encode(['status' => 200,'count_records'=>$count_records,'total_record'=>$ratings_feedbacks_listing_data['total'],'data'=>$this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_fulltime_projects_ratings_feedbacks',$data, true)]);
				die;
			}else{
				show_custom_404_page(); //show custom 404 page
				return;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
			return;
		}
	
	} 
  
	// This function is using to save the rating feedback reply into database
    public function save_rating_feeback_reply(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
					
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				$feedback_recived_by = Cryptor::doDecrypt($post_data['feedback_recived_by']);
				$feedback_given_by = Cryptor::doDecrypt($post_data['feedback_given_by']);
				if($user_id != $feedback_recived_by){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$feeback_reply = '';
				if($post_data['view_type'] == 'sp'){
					
					$check_feedback_reply_already_given = $this->db->get_where('projects_users_received_ratings_feedbacks_as_sp', ['feedback_recived_by_sp_id'=> $feedback_recived_by,'feedback_given_by_po_id'=> $feedback_given_by,'feedback_provided_on_project_id'=>$post_data['project_id']])->row_array();
					$feeback_reply = $check_feedback_reply_already_given['feedback_reply_by_sp'];
				}
				if($post_data['view_type'] == 'po'){
				
					$check_feedback_reply_already_given = $this->db->get_where('projects_users_received_ratings_feedbacks_as_po', ['feedback_recived_by_po_id'=> $feedback_recived_by,'feedback_given_by_sp_id'=> $feedback_given_by,'feedback_provided_on_project_id'=>$post_data['project_id']])->row_array();
					$feeback_reply = $check_feedback_reply_already_given['feedback_reply_by_po'];
				}
				if($post_data['view_type'] == 'employee'){
					$check_feedback_reply_already_given = $this->db->get_where('fulltime_prj_users_received_ratings_feedbacks_as_employee', ['feedback_recived_by_employee_id'=> $feedback_recived_by,'feedback_given_by_employer_id'=> $feedback_given_by,'feedback_provided_on_fulltime_project_id'=>$post_data['project_id']])->row_array();
					$feeback_reply = $check_feedback_reply_already_given['feedback_reply_by_employee'];
				}
				if($post_data['view_type'] == 'employer'){
					$check_feedback_reply_already_given = $this->db->get_where('fulltime_prj_users_received_ratings_feedbacks_as_employer', ['feedback_recived_by_employer_id'=> $feedback_recived_by,'feedback_given_by_employee_id'=> $feedback_given_by,'feedback_provided_on_fulltime_project_id'=>$post_data['project_id']])->row_array();
					$feeback_reply = $check_feedback_reply_already_given['feedback_reply_by_employer'];
				}
				
				if(!empty($check_feedback_reply_already_given) && !empty($feeback_reply)){
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('users_ratings_feedbacks_user_already_given_reply_on_feedback')]);
					die;
				}
				
				$validation_data_array = $this->Users_ratings_feedbacks_model->rating_feeback_reply_form_validation($post_data);
				if ($validation_data_array['status'] == 'SUCCESS')
				{
					$feedback_reply_on_date = date('Y-m-d H:i:s');
					
					$reply_by_data = $this->db // get the user detail
					->select('account_type,is_authorized_physical_person,first_name,last_name,company_name')
					->from('users')
					->where('user_id',$feedback_recived_by)
					->get()->row_array();
					$reply_by_user = (($reply_by_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($reply_by_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $reply_by_data['is_authorized_physical_person'] == 'Y')) ? $reply_by_data['first_name']." ".$reply_by_data['last_name']  : $reply_by_data['company_name']; 
					
					if($post_data['view_type'] == 'sp'){
						$this->db->update('projects_users_received_ratings_feedbacks_as_sp', ['feedback_reply_by_sp'=>$post_data['rating_feedback_reply'],'feedback_reply_on_date'=>$feedback_reply_on_date], ['feedback_recived_by_sp_id'=> $feedback_recived_by,'feedback_given_by_po_id'=> $feedback_given_by,'feedback_provided_on_project_id'=>$post_data['project_id']]);
						
						
						
						
					}
					if($post_data['view_type'] == 'po'){
						$this->db->update('projects_users_received_ratings_feedbacks_as_po', ['feedback_reply_by_po'=>$post_data['rating_feedback_reply'],'feedback_reply_on_date'=>$feedback_reply_on_date], ['feedback_recived_by_po_id'=> $feedback_recived_by,'feedback_given_by_sp_id'=> $feedback_given_by,'feedback_provided_on_project_id'=>$post_data['project_id']]);
					}
					if($post_data['view_type'] == 'employee'){
						$this->db->update('fulltime_prj_users_received_ratings_feedbacks_as_employee', ['feedback_reply_by_employee'=>$post_data['rating_feedback_reply'],'feedback_reply_on_date'=>$feedback_reply_on_date], ['feedback_recived_by_employee_id'=> $feedback_recived_by,'feedback_given_by_employer_id'=> $feedback_given_by,'feedback_provided_on_fulltime_project_id'=>$post_data['project_id']]);
					}
					if($post_data['view_type'] == 'employer'){
						$this->db->update('fulltime_prj_users_received_ratings_feedbacks_as_employer', ['feedback_reply_by_employer'=>$post_data['rating_feedback_reply'],'feedback_reply_on_date'=>$feedback_reply_on_date], ['feedback_recived_by_employer_id'=> $feedback_recived_by,'feedback_given_by_employee_id'=> $feedback_given_by,'feedback_provided_on_fulltime_project_id'=>$post_data['project_id']]);
					}
					$data['feedback_reply'] = $post_data['rating_feedback_reply'];
					$data['feedback_reply_on_date'] = $feedback_reply_on_date;
					$data['section_id'] =$post_data['section_id'];
					$data['reply_by_user'] =$reply_by_user;
					$msg['status'] = 'SUCCESS';
					$msg['message'] = '';
					$msg['location'] = '' ;
					$msg['data'] = $this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_ratings_feedbacks_reply_detail',$data, true);
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
 
	// This functions is using for pending feedback ratings page 
	public function user_projects_pending_ratings_feedbacks() { 
		if(!check_session_validity()){  
			$last_redirect_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->session->set_userdata ('last_redirect_url', $last_redirect_url);
			redirect(base_url());
			return;
		}
		$data['current_page'] = 'user_projects_pending_ratings_feedbacks';
		########## set the feedbacks title meta tag and meta description  start here #########
		$user = $this->session->userdata ('user');
		$user_id = $user[0]->user_id;
		$name = (($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_PERSONAL_ACCOUNT_TYPE) || ($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_COMPANY_ACCOUNT_TYPE && $this->auto_model->getFeild('is_authorized_physical_person', 'users', 'user_id', $user_id) == 'Y')) ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
		
		$user_projects_pending_feedbacks_listing_data = $this->Users_ratings_feedbacks_model->get_user_projects_pending_ratings_feedbacks_listing($user_id,0,$this->config->item('user_projects_pending_ratings_feedbacks_listing_limit'));
		$data["projects_pending_ratings_feedbacks_data"] = $user_projects_pending_feedbacks_listing_data['data'];
		$data['projects_pending_ratings_feedbacks_count'] = $user_projects_pending_feedbacks_listing_data['total'];
		$paginations = generate_pagination_links($user_projects_pending_feedbacks_listing_data['total'], $this->config->item('pending_feedbacks_management_page_url'),$this->config->item('user_projects_pending_ratings_feedbacks_listing_limit'),$this->config->item('user_projects_pending_ratings_feedbacks_number_of_pagination_links'));
		$data['projects_pending_ratings_feedbacks_pagination_links'] = $paginations['links'];
		
		$pending_feedback_title_meta_tag = $this->config->item('user_projects_pending_ratings_feedbacks_page_title_meta_tag');
		$pending_feedback_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $pending_feedback_title_meta_tag);
		$pending_feedback_description_meta_tag = $this->config->item('user_projects_pending_ratings_feedbacks_page_description_meta_tag');
		$pending_feedback_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $pending_feedback_description_meta_tag);
		$data['meta_tag'] = '<title>' . $pending_feedback_title_meta_tag . '</title><meta name="description" content="' . $pending_feedback_description_meta_tag . '"/>';
		
		/* echo $total_records = $this->Users_ratings_feedbacks_model->get_user_projects_pending_ratings_feedbacks_count($user_id);
		echo "<br>";
		
		 echo $user_projects_pending_feedbacks_listing_data['total']; */
		 
		/*echo "<br>";
	
		
		echo $total_records = $this->Users_ratings_feedbacks_model->get_user_projects_pending_ratings_feedbacks_count($user_id);
		die; */
		$this->layout->view ('user_projects_pending_ratings_feedbacks', '', $data, 'include');

	}
  
  
	// This functions is using for paging for pending feedback ratings page 
	public function load_pagination_user_projects_pending_ratings_feedbacks(){
		
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ 
				$page = $this->uri->segment(3);
				$user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				
				$total_records = $this->Users_ratings_feedbacks_model->get_user_projects_pending_ratings_feedbacks_count($user_id);
				$paginations = generate_pagination_links($total_records, $this->config->item('pending_feedbacks_management_page_url'),$this->config->item('user_projects_pending_ratings_feedbacks_listing_limit'),$this->config->item('user_projects_pending_ratings_feedbacks_number_of_pagination_links'));
				$data['projects_pending_ratings_feedbacks_pagination_links'] = $paginations['links'];
				
				
				$user_projects_pending_feedbacks_listing_data = $this->Users_ratings_feedbacks_model->get_user_projects_pending_ratings_feedbacks_listing($user_id,$paginations['offset'],$this->config->item('user_projects_pending_ratings_feedbacks_listing_limit'));
		
				$data["projects_pending_ratings_feedbacks_data"] = $user_projects_pending_feedbacks_listing_data['data'];
				$data['projects_pending_ratings_feedbacks_count'] = $user_projects_pending_feedbacks_listing_data['total'];
				$page = $paginations['current_page_no'];
				
				$multiplication = $this->config->item('user_projects_pending_ratings_feedbacks_listing_limit') * $page;
				$subtraction = ($multiplication - ($this->config->item('user_projects_pending_ratings_feedbacks_listing_limit') - count($data["projects_pending_ratings_feedbacks_data"])));
				$record_per_page = count($data["projects_pending_ratings_feedbacks_data"]) < $this->config->item('user_projects_pending_ratings_feedbacks_listing_limit') ? $subtraction : $multiplication;
				$page_no = ($this->config->item('user_projects_pending_ratings_feedbacks_listing_limit') * ($page - 1)) + 1;
				
				echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$user_projects_pending_feedbacks_listing_data['total'],'data'=>$this->load->view('user_projects_pending_ratings_feedbacks_listing_data',$data, true)]);
				die;
				
			}else{
				echo json_encode(['status' => 400,'location'=>VPATH]);
				die;
			
			}
		}else{
			
			show_custom_404_page(); //show custom 404 page
			return;
		}
	}
	
	
	/**
	* This function is used to saved the ratings and feedbacks into database from pending feedbak page.
	*/
	public function save_user_projects_pending_rating_feedbacks(){
		
		
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$data = array();
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				$section_id = $this->input->post ('section_id');
				$project_id = $this->input->post ('project_id');
				$section_name = $this->input->post ('section_name');
				$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
				$sp_id = Cryptor::doDecrypt($this->input->post ('sp_id'));
				$project_type = $this->input->post ('project_type');
				$view_type = $this->input->post ('view_type');
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				$acknowledgement_notification_msg = '';
				
				if(!empty($project_status_table_array['project_status']) && !empty($project_status_table_array['table_name'])){
					$remove_id = 0;
					$is_bid_completed = '0';
					$is_project_status_change = '0';
					$total_paid_amount = 0;
					$post_data = $this->input->post ();
					
					if(($view_type == 'po' &&  $user_id != $po_id) || ($view_type == 'sp' &&  $user_id != $sp_id)){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					if($user_id == $po_id && $view_type == 'po'){
					
						if($project_type != 'fulltime'){
							$check_feedback_data_exists = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_sp_id'=>$sp_id,'feedback_given_by_po_id'=>$po_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
						}else{
							$check_feedback_data_exists = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employee_id'=>$sp_id,'feedback_given_by_employer_id'=>$po_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')->count_all_results();
						}
					}
					else if($user_id == $sp_id && $view_type == 'sp'){
						if($project_type != 'fulltime'){
							$check_feedback_data_exists = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_po_id'=>$po_id,'feedback_given_by_sp_id'=>$sp_id])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
						}else{
							$check_feedback_data_exists = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_given_by_employee_id'=>$sp_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')->count_all_results();
						}
					}
					if($check_feedback_data_exists > 0 ){
						if($project_type != 'fulltime'){
							$error_message = $this->config->item('projects_users_ratings_feedbacks_po_sp_already_given_feedback');
						}else{
							$error_message = $this->config->item('fulltime_projects_users_ratings_feedbacks_employer_employee_already_given_feedback');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					
					}
					
					$validation_data_array = $this->Users_ratings_feedbacks_model->user_give_rating_feedback_validation($post_data);
					if ($validation_data_array['status'] == 'SUCCESS')
					{
						
						if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
							$project_data = $this->db // get the user detail
							->select('pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title')
							->from($project_status_table_array['table_name'].' pd')
							->where('pd.fulltime_project_id', $project_id)
							->get()->row_array();
						}else{
							$project_data = $this->db // get the user detail
							->select('pd.project_id,pd.project_title')
							->from($project_status_table_array['table_name'].' pd')
							->where('pd.project_id', $project_id)
							->get()->row_array();
						}
						$sp_data = $this->db // get the user detail
						->select('u.user_id,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
						->from('users u')
						->where('u.user_id', $sp_id)
						->get()->row_array();
						
						$po_data = $this->db // get the user detail
						->select('u.user_id,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
						->from('users u')
						->where('u.user_id', $po_id)
						->get()->row_array();
					
						$sp_name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
						
						$po_name = (($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')) ?$po_data['first_name'] . ' ' . $po_data['last_name'] :$po_data['company_name'];
						
						
						$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
						
						$sp_profile_url_link = VPATH.$sp_data['profile_name'];
						$po_profile_url_link = VPATH.$po_data['profile_name'];
					
						if($project_type != 'fulltime'){
								$feedback_data['feedback_provided_on_project_id']= $project_id;
								$feedback_data['feedback_provided_on_date']= date('y-m-d H:i:s');
								
							if($user_id == $po_id && $view_type == 'po'){
							
								
							
								
								$feedback_data['feedback_recived_by_sp_id']= $sp_id;
								$feedback_data['feedback_given_by_po_id']= $po_id;
								$feedback_data['project_delivered_within_agreed_budget']=$post_data['project_delivered_within_agreed_budget'];
								
								$feedback_data['work_delivered_within_agreed_time_slot']=$post_data['work_delivered_within_agreed_time_slot'];
								$feedback_data['would_you_hire_sp_again']=$post_data['would_you_hire_sp_again'];
								$feedback_data['would_you_recommend_sp']=$post_data['would_you_recommend_sp'];
								$feedback_data['quality_of_work']=$post_data['quality_of_work'];
								$feedback_data['communication']=$post_data['communication'];
								$feedback_data['professionalism']=$post_data['expertise'];
								$feedback_data['expertise']=$post_data['professionalism'];
								$feedback_data['value_for_money']=$post_data['value_for_money'];
								$feedback_data['feedback_left_by_po']=trim($post_data['feedback']);
								
								$project_avg_rating_as_sp = ($post_data['quality_of_work']+$post_data['communication']+$post_data['expertise']+$post_data['professionalism']+$post_data['value_for_money'])/5;
								
								$feedback_data['project_avg_rating_as_sp']=number_format($project_avg_rating_as_sp,2);
								
								if($this->db->insert ('projects_users_received_ratings_feedbacks_as_sp', $feedback_data)){
								
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" start 
									
									$this->db->select('id');
									$this->db->from('projects_candidates_for_users_ratings_feedbacks_exchange tpa');
									$this->db->where('project_id',$project_id);
									$this->db->where('po_id',$po_id);
									$this->db->where('sp_id',$sp_id);
									$this->db->where('sp_rating_to_po_date IS NULL');
									$check_ratings_feedbacks_exchange = $this->db->get()->row_array();
									if(!empty($check_ratings_feedbacks_exchange)){
										$this->db->update('projects_candidates_for_users_ratings_feedbacks_exchange', ['po_rating_to_sp_date'=>$feedback_data['feedback_provided_on_date']], ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]);
									}else{
										$this->db->delete('projects_candidates_for_users_ratings_feedbacks_exchange',  ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]); 
									}
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" end 
									
									
									
									
								
								
									// check feedback is given by sp
									$check_feedback_given_by_sp = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_po_id'=>$po_id,'feedback_given_by_sp_id'=>$sp_id])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
									// check feedback is given by po
									$check_feedback_given_by_po = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_sp_id'=>$sp_id,'feedback_given_by_po_id'=>$po_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
									if($check_feedback_given_by_sp > 0  && $check_feedback_given_by_po >0){
										
										
										$this->db->update('projects_users_received_ratings_feedbacks_as_po', ['po_already_placed_feedback'=>'Y'], ['feedback_recived_by_po_id'=> $po_id,'feedback_given_by_sp_id'=>$sp_id,'feedback_provided_on_project_id'=>$project_id]);
										
										$this->db->update('projects_users_received_ratings_feedbacks_as_sp', ['sp_already_placed_feedback'=>'Y'], ['feedback_recived_by_sp_id'=> $sp_id,'feedback_given_by_po_id'=>$po_id,'feedback_provided_on_project_id'=>$project_id]);
									
										$this->db->select('AVG(project_avg_rating_as_po) as project_avg_rating_as_po');
										$this->db->from('projects_users_received_ratings_feedbacks_as_po');
										$this->db->where(['feedback_recived_by_po_id'=>$po_id,'po_already_placed_feedback'=>'Y']);
										$avg_rating_result = $this->db->get();
										$avg_rating_row = $avg_rating_result->row_array();
										$user_total_avg_rating_as_po = $avg_rating_row['project_avg_rating_as_po'];
										$this->db->update('users_details', ['project_user_total_avg_rating_as_po'=>number_format($user_total_avg_rating_as_po,2)], ['user_id'=> $po_id]);
									
									//if($check_sp_given_feedback_to_po > 0){
										// update user total rating in users_details table for fixed/hourly project for sp
										$this->db->select('AVG(project_avg_rating_as_sp) as project_avg_rating_as_sp');
										$this->db->from('projects_users_received_ratings_feedbacks_as_sp');
										$this->db->where(['feedback_recived_by_sp_id'=>$sp_id,'sp_already_placed_feedback'=>'Y']);
										$avg_rating_result = $this->db->get();
										$avg_rating_row = $avg_rating_result->row_array();
										$user_total_avg_rating_as_sp = $avg_rating_row['project_avg_rating_as_sp'];
										$this->db->update('users_details', ['project_user_total_avg_rating_as_sp'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
										
										####### update total rating of sp ###
										
										$user_details = $this->db // get the user detail
										->select('project_user_total_avg_rating_as_sp,fulltime_project_user_total_avg_rating_as_employee')
										->from('users_details')
										->where('user_id', $sp_id)
										->get()->row_array();
										
										if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
											$user_total_avg_rating_as_sp = ($user_details['project_user_total_avg_rating_as_sp']+$user_details['fulltime_project_user_total_avg_rating_as_employee'])/2;
										}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) == 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
											$user_total_avg_rating_as_sp = $user_details['fulltime_project_user_total_avg_rating_as_employee'];
										}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) == 0){
											$user_total_avg_rating_as_sp = $user_details['project_user_total_avg_rating_as_sp'];
										}								
										$this->db->update('users_details', ['user_total_avg_rating_as_sp'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
										//}
									}
									
								
									// Log message code
									$po_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_po_when_given_feedback_user_activity_log_displayed_message');
									
									$po_activity_log_message = str_replace(array("{sp_profile_url_link}","{user_first_name_last_name_or_company_name}","{project_title}","{project_url_link}"),array($sp_profile_url_link,$sp_name,$project_data['project_title'],$project_url_link),$po_activity_log_message);
									
									
									if(($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')){
										if($po_data['gender'] == 'M'){
											if($po_data['is_authorized_physical_person'] == 'Y'){
												$sp_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_sp_when_po_company_app_male_given_feedback_user_activity_log_displayed_message');
											}else{
												$sp_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_sp_when_po_male_given_feedback_user_activity_log_displayed_message');
											}
											
										}else{
											if($po_data['is_authorized_physical_person'] == 'Y'){
												$sp_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_sp_when_po_company_app_female_given_feedback_user_activity_log_displayed_message');
											}else{
												$sp_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_sp_when_po_female_given_feedback_user_activity_log_displayed_message');
											}
											
										}
										$sp_activity_log_message = str_replace(array("{po_profile_url_link}","{user_first_name_last_name}","{project_title}","{project_url_link}"),array($po_profile_url_link,$po_name,$project_data['project_title'],$project_url_link),$sp_activity_log_message);
										
										
									}else{
										$sp_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_sp_when_po_company_given_feedback_user_activity_log_displayed_message');
										
										$sp_activity_log_message = str_replace(array("{po_profile_url_link}","{user_company_name}","{project_title}","{project_url_link}"),array($po_profile_url_link,$po_name,$project_data['project_title'],$project_url_link),$sp_activity_log_message);
									}
								
								}
							}else if($user_id == $sp_id && $view_type == 'sp'){
								$feedback_data['feedback_recived_by_po_id']= $po_id;
								$feedback_data['feedback_given_by_sp_id']= $sp_id;
								$feedback_data['would_you_work_again_with_po']=$post_data['would_you_work_again_with_po'];
								
								$feedback_data['would_you_recommend_po']=$post_data['would_you_recommend_po'];
								$feedback_data['clarity_in_requirements']=$post_data['clarity_requirements'];
								$feedback_data['communication']=$post_data['communication'];
								$feedback_data['payment_promptness']=$post_data['payment_promptness'];
								$feedback_data['feedback_left_by_sp']=trim($post_data['feedback']);
								
								$project_avg_rating_as_po = ($post_data['clarity_requirements']+$post_data['communication']+$post_data['payment_promptness'])/3;
								
								$feedback_data['project_avg_rating_as_po']=number_format($project_avg_rating_as_po,2);
								
								$check_po_given_feedback_to_sp = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_given_by_po_id'=>$po_id,'feedback_recived_by_sp_id'=>$sp_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
								/* if($check_po_given_feedback_to_sp > 0){
									$feedback_data['po_already_placed_feedback'] = 'Y';
								} */
								if($this->db->insert ('projects_users_received_ratings_feedbacks_as_po', $feedback_data)){
								
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" start 
									
									$this->db->select('id');
									$this->db->from('projects_candidates_for_users_ratings_feedbacks_exchange tpa');
									$this->db->where('project_id',$project_id);
									$this->db->where('po_id',$po_id);
									$this->db->where('sp_id',$sp_id);
									$this->db->where('po_rating_to_sp_date IS NULL');
									$check_ratings_feedbacks_exchange = $this->db->get()->row_array();
									
									
									
									if(!empty($check_ratings_feedbacks_exchange)){
										$this->db->update('projects_candidates_for_users_ratings_feedbacks_exchange', ['sp_rating_to_po_date'=>$feedback_data['feedback_provided_on_date']], ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]);
									}else{
										$this->db->delete('projects_candidates_for_users_ratings_feedbacks_exchange',  ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]); 
									}
									
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" end 
								
								
									// check feedback is given by sp
									$check_feedback_given_by_sp = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_po_id'=>$po_id,'feedback_given_by_sp_id'=>$sp_id])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
									
									// check feedback is given by po
									$check_feedback_given_by_po = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_sp_id'=>$sp_id,'feedback_given_by_po_id'=>$po_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
									if($check_feedback_given_by_sp > 0 && $check_feedback_given_by_po > 0){	
									
										$this->db->update('projects_users_received_ratings_feedbacks_as_po', ['po_already_placed_feedback'=>'Y'], ['feedback_recived_by_po_id'=> $po_id,'feedback_given_by_sp_id'=>$sp_id,'feedback_provided_on_project_id'=>$project_id]);
										
										$this->db->update('projects_users_received_ratings_feedbacks_as_sp', ['sp_already_placed_feedback'=>'Y'], ['feedback_recived_by_sp_id'=> $sp_id,'feedback_given_by_po_id'=>$po_id,'feedback_provided_on_project_id'=>$project_id]);
										
										// update user total rating in users_details table for fixed/hourly project for sp
											$this->db->select('AVG(project_avg_rating_as_sp) as project_avg_rating_as_sp');
											$this->db->from('projects_users_received_ratings_feedbacks_as_sp');
											$this->db->where(['feedback_recived_by_sp_id'=>$sp_id,'sp_already_placed_feedback'=>'Y']);
											$avg_rating_result = $this->db->get();
											$avg_rating_row = $avg_rating_result->row_array();
											$user_total_avg_rating_as_sp = $avg_rating_row['project_avg_rating_as_sp'];
											$this->db->update('users_details', ['project_user_total_avg_rating_as_sp'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
											
											####### update total rating of sp ###
											
											$user_details = $this->db // get the user detail
											->select('project_user_total_avg_rating_as_sp,fulltime_project_user_total_avg_rating_as_employee')
											->from('users_details')
											->where('user_id', $sp_id)
											->get()->row_array();
											
											if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
												$user_total_avg_rating_as_sp = ($user_details['project_user_total_avg_rating_as_sp']+$user_details['fulltime_project_user_total_avg_rating_as_employee'])/2;
											}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) == 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
												$user_total_avg_rating_as_sp = $user_details['fulltime_project_user_total_avg_rating_as_employee'];
											}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) == 0){
												$user_total_avg_rating_as_sp = $user_details['project_user_total_avg_rating_as_sp'];
											}								
											$this->db->update('users_details', ['user_total_avg_rating_as_sp'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
										
										//if($check_po_given_feedback_to_sp > 0){
											// update user total rating in users_details table  for fixed/hourly project for po
											$this->db->select('AVG(project_avg_rating_as_po) as project_avg_rating_as_po');
											$this->db->from('projects_users_received_ratings_feedbacks_as_po');
											$this->db->where(['feedback_recived_by_po_id'=>$po_id,'po_already_placed_feedback'=>'Y']);
											$avg_rating_result = $this->db->get();
											$avg_rating_row = $avg_rating_result->row_array();
											$user_total_avg_rating_as_po = $avg_rating_row['project_avg_rating_as_po'];
											$this->db->update('users_details', ['project_user_total_avg_rating_as_po'=>number_format($user_total_avg_rating_as_po,2)], ['user_id'=> $po_id]);
										//}
									}	
									$sp_activity_log_message= $this->config->item('projects_rating_feedbacks_message_sent_to_sp_when_given_feedback_user_activity_log_displayed_message');
									
									$sp_activity_log_message = str_replace(array('{po_profile_url_link}','{user_first_name_last_name_or_company_name}','{project_title}','{project_url_link}'),array($po_profile_url_link,$po_name,$project_data['project_title'],$project_url_link),$sp_activity_log_message);
									
									
									if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
										if($po_data['gender'] == 'M'){
											if($sp_data['is_authorized_physical_person'] == 'Y'){
												$po_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_po_when_sp_company_app_male_given_feedback_user_activity_log_displayed_message');
											}else{
												$po_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_po_when_sp_male_given_feedback_user_activity_log_displayed_message');
											}
											
										}else{
											if($sp_data['is_authorized_physical_person'] == 'Y'){
												$po_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_po_when_sp_company_app_female_given_feedback_user_activity_log_displayed_message');
											}else{
												$po_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_po_when_sp_female_given_feedback_user_activity_log_displayed_message');
											}
											
										}
										$po_activity_log_message = str_replace(array('{sp_profile_url_link}','{user_first_name_last_name}','{project_title}','{project_url_link}'),array($sp_profile_url_link,$sp_name,$project_data['project_title'],$project_url_link),$po_activity_log_message);
										
										
									}else{
										$po_activity_log_message = $this->config->item('projects_rating_feedbacks_message_sent_to_po_when_sp_company_given_feedback_user_activity_log_displayed_message');
										
										$po_activity_log_message = str_replace(array('{sp_profile_url_link}','{user_company_name}','{project_title}','{project_url_link}'),array($sp_profile_url_link,$sp_name,$project_data['project_title'],$project_url_link),$po_activity_log_message);
									}
								}
							
							}
							user_display_log($po_activity_log_message,$po_id); // activity log message for po
							user_display_log($sp_activity_log_message,$sp_id); // activity log message for sp
							
							
							############## code for move in progress bid from in progress status to completed start for hourly project###
							
							if($project_type == 'hourly'){
							
								// check feedback is given by sp
								$check_feedback_given_by_sp = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_po_id'=>$po_id,'feedback_given_by_sp_id'=>$sp_id])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
								
								// check feedback is given by po
								$check_feedback_given_by_po = $this->db->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_sp_id'=>$sp_id,'feedback_given_by_po_id'=>$po_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
							
								if($check_feedback_given_by_sp > 0  && $check_feedback_given_by_po > 0){
									$project_completion_date = date('Y-m-d H:i:s');
									$get_completed_bid_data = $this->db->get_where('hourly_rate_based_projects_completed_tracking', ['project_id' => $project_id,'winner_id'=>$sp_id])->row_array();
								
									$in_progress_bid_data = $this->db->get_where('hourly_rate_based_projects_progress_sp_bid_reference', ['project_id' => $project_id,'winner_id'=>$sp_id])->row_array();
									
									$in_complete_bid_data = $this->db->get_where('hourly_rate_based_projects_incomplete_tracking', ['project_id' => $project_id,'winner_id'=>$sp_id])->row_array();
									
									if(!empty($in_progress_bid_data)){
										$hourly_project_bid_data	 = $in_progress_bid_data ;
										$hourly_bid_status = 'in_progress';
									}
								if(!empty($in_complete_bid_data)){
										$hourly_project_bid_data	 = $in_complete_bid_data ;
										$hourly_bid_status = 'in_complete';
									}
									
									if(!empty($hourly_project_bid_data)){
										$total_released_escrow = $total_released_escrow_po = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($project_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
									
										if(!empty($hourly_project_bid_data) && $hourly_project_bid_data['initial_project_agreed_value'] == 0){
										
											
											$sp_notification_msg = $this->config->item('hourly_rate_based_project_realtime_notification_message_sent_to_sp_when_project_completed');
											$sp_notification_msg = str_replace(array('{project_title}','{project_url_link}'),array($project_data['project_title'],$project_url_link),$sp_notification_msg);
											
											//
											
											$po_notification_msg = $this->config->item('hourly_rate_based_project_realtime_notification_message_sent_to_po_when_project_completed');
											$po_notification_msg = str_replace(array('{project_title}','{project_url_link}'),array($project_data['project_title'],$project_url_link),$po_notification_msg);
											
											
											
											
											
											
											$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_project_completed_user_activity_log_displayed_message');
											$sp_activity_log_message = str_replace(array('{project_title}','{project_url_link}'),array($project_data['project_title'],$project_url_link),$sp_activity_log_message);
											
											
											$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_project_completed_user_activity_log_displayed_message');
											$po_activity_log_message = str_replace(array('{project_title}','{project_url_link}'),array($project_data['project_title'],$project_url_link),$po_activity_log_message);
										
											$is_bid_completed = '1';
											
											if($user_id == $sp_data['user_id']){
												$acknowledgement_notification_msg = $sp_notification_msg;
											}
											else if($user_id == $po_data['user_id']){
												$acknowledgement_notification_msg = $po_notification_msg;
											}
											
											$remove_id = $hourly_project_bid_data['id'];
											$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
											$config['ftp_username'] = FTP_USERNAME;
											$config['ftp_password'] = FTP_PASSWORD;
											$config['ftp_port'] 	= FTP_PORT;
											$config['debug']    = TRUE;
											$this->load->library('ftp');
											$this->ftp->connect($config); 
										
										
										
											$completed_bid_data['project_id'] = $hourly_project_bid_data['project_id'];
											$completed_bid_data['winner_id'] = $hourly_project_bid_data['winner_id'];
											$completed_bid_data['project_owner_id'] = $hourly_project_bid_data['project_owner_id'];
											$completed_bid_data['initial_bid_description'] = $hourly_project_bid_data['initial_bid_description'];
											$completed_bid_data['bidding_dropdown_option'] = $hourly_project_bid_data['bidding_dropdown_option'];
											$completed_bid_data['initial_project_agreed_value'] = $hourly_project_bid_data['initial_project_agreed_value'];
											$completed_bid_data['initial_project_agreed_number_of_hours'] = $hourly_project_bid_data['initial_project_agreed_number_of_hours'];
											$completed_bid_data['initial_project_agreed_hourly_rate'] = $hourly_project_bid_data['initial_project_agreed_hourly_rate'];
											$completed_bid_data['project_winner_work_start_date'] = $hourly_project_bid_data['project_start_date'];
											$completed_bid_data['project_winner_work_completion_date'] = $project_completion_date;
											$completed_bid_data['total_project_amount'] = $total_released_escrow;
											
											if(empty($get_completed_bid_data)){
											
												
												$this->db->insert ('hourly_rate_based_projects_completed_tracking', $completed_bid_data);// move data to completed internally tracking
												$this->db->delete('hourly_rate_based_projects_progress_sp_bid_reference', ['project_id' => $project_id,'winner_id'=>$sp_id]); 
												
												$this->db->delete('hourly_rate_based_projects_incomplete_tracking', ['project_id' => $project_id,'winner_id'=>$sp_id]); 
												
												$completed_bidder_data = $this->db // get the user detail
												->select('u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,cb.*')
												->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
												->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id ) as sp_total_completed_fixed_budget_projects')
												->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id ) as sp_total_completed_hourly_based_projects')
												->from('hourly_rate_based_projects_completed_tracking cb')
												->join('users u', 'u.user_id = cb.winner_id', 'left')
												->join('users_details ud', 'ud.user_id = u.user_id', 'left')
												->where('project_id', $completed_bid_data['project_id'])
												->where('winner_id', $completed_bid_data['winner_id'])
												->where('cb.project_owner_id', $completed_bid_data['project_owner_id'])
												->get()->row_array();
												$common_source_path = USERS_FTP_DIR . $completed_bidder_data['profile_name'];
												
												//avatar picture
												//start check avatar from ftp server
												$user_avatar = USER_AVATAR;
												$source_path_avatar = $common_source_path . $user_avatar;
												$avatarlist = $this->ftp->list_files($source_path_avatar);
												$avatar_pic = $source_path_avatar . $completed_bidder_data['user_avatar'];

												$exap = explode('.', $completed_bidder_data['user_avatar']);
												$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

												if (count($avatarlist) > 0) {
													$acheck = true;
													if (!in_array($avatar_pic, $avatarlist) && $acheck) {
														$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $completed_bidder_data['user_id']));
														$this->ftp->delete_dir($source_path_avatar);
														$completed_bidder_data['user_avatar'] = '';
														$acheck = false;
													} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
														$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$completed_bidder_data['user_id']));
														$this->ftp->delete_dir($source_path_avatar);
														$completed_bidder_data['user_avatar'] = '';
														$acheck = false;
													}
												} if (count($avatarlist) == 0 && $completed_bidder_data['user_avatar'] != '') {
													$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $completed_bidder_data['user_id']));
													$completed_bidder_data['user_avatar'] = '';
												}
												
												$count_in_progress_bids = $this->db->where(['project_id' => $project_id])->from('hourly_rate_based_projects_progress_sp_bid_reference')->count_all_results();
												$project_data =  $this->db // get the user detail
												->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type')
												->from($project_status_table_array['table_name'].' pd')
												->join('users u', 'u.user_id = pd.project_owner_id', 'left')
												->where('pd.project_id', $project_id)
												->get()->row_array();
												
												if($count_in_progress_bids == 0){
													//$is_project_status_change = '1';
													//$project_status = $this->config->item('project_status_completed');
													
													$project_completed_table_data_exists = $this->db->where(['project_id' => $project_id])->from('hourly_rate_based_projects_completed')->count_all_results();
													if($project_completed_table_data_exists == 0){
														$profile_name = $project_data['profile_name'];
														unset($project_data['profile_name']);
														unset($project_data['id']);
														unset($project_data['gender']);
														unset($project_data['first_name']);
														unset($project_data['last_name']);
														unset($project_data['company_name']);
														unset($project_data['account_type']);
														$project_data['project_completion_date'] = $project_completion_date;
														$this->db->insert ('hourly_rate_based_projects_completed', $project_data);// 
														$this->db->delete('hourly_rate_based_projects_progress', ['project_id' => $project_id]); 
														$this->db->delete('hourly_rate_based_projects_incomplete', ['project_id' => $project_id]); 
														
														$users_ftp_dir 	= USERS_FTP_DIR; 
														$projects_ftp_dir = PROJECTS_FTP_DIR;
														$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
														$project_in_complete_dir = PROJECT_INCOMPLETE_DIR;
														$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
													
														$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
														$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
														$project_completed_dir = PROJECT_COMPLETED_DIR;
														
														
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR);
														
														
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir);
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.DIRECTORY_SEPARATOR);
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir);
														
														
														//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir, 0777);// create projects directory if not exists
														
														//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir, 0777);// create awaiting_moderation directory in projects folder
														//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id , 0777); // create the directory by using  project id
														//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir , 0777); // create the owner attachment directory by using  project id
														if($hourly_bid_status == 'in_progress'){
															$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir;
														}
														if($hourly_bid_status == 'in_complete'){
															$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id.$project_owner_attachments_dir;
														}
														
														$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.DIRECTORY_SEPARATOR;
														
														$source_list = $this->ftp->list_files($source_path);
														if(!empty($source_list)) {
															foreach($source_list as $path) {
																$arr = explode('/', $path);
																$file_size = $this->ftp->get_filesize($path);
																if($file_size != '-1') {
																	$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir.end($arr);
																	$this->ftp->move($path, $destination_path);
																}
															}
														}
														
														$bid_attachments = $this->db->where ('project_id', $project_id)->get ('projects_active_bids_users_attachments_tracking')->result_array ();
														
														
														if(!empty($bid_attachments)){
															foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
																$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
																if(empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
																	//die("sdfsdff");
																	
																	$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir);
																	$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
																	
																	
																	//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir , 0777); // create the owner attachment directory by using  project id
																	
																	//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] , 0777); // create the owner attachment directory by using  project id
																	//die("fsdffdf");
																	
																	if($hourly_bid_status == 'in_progress'){
																	
																		$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
																	}
																	if($hourly_bid_status == 'in_complete'){
																	
																		$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
																	}
																	
																	
																	
																	$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
																	if($file_size != '-1')
																	{
																		
																		$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
																		$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
																		
																	}
																}
															}
														}
														// remove in progress folder
														
														if($hourly_bid_status == 'in_progress'){
														
															if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id))) {
																$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id);
															}
														}
														if($hourly_bid_status == 'in_complete'){
														
															if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id))) {
																$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id);
															}
														}														
														
														user_display_log($po_activity_log_message,$po_id); // activity log message for po
														user_display_log($sp_activity_log_message,$sp_id); // activity log message for sp
														
													}
												}
												$this->ftp->close();
												
												//$project_completion_date_container_html = '<span class="default_black_bold"><i class="fa fa-clock-o" aria-hidden="true"></i> '.$this->config->item('project_details_page_completed_on').'</span>'.$project_completion_date;
						
												/* if($project_data['project_type'] == 'fulltime' ) {
													$project_value= $this->config->item('fulltime_projects_employer_view_total_project_value').str_replace(".00","",number_format($total_released_escrow,  2, '.', ' '))." ".CURRENCY;
												} else {
													$project_value= $this->config->item('fixed_or_hourly_project_value').str_replace(".00","",number_format($total_released_escrow,  2, '.', ' '))." ".CURRENCY;
												} */
												/* $project_value= $this->config->item('fixed_or_hourly_project_value').str_replace(".00","",number_format($total_released_escrow,  2, '.', ' '))." ".CURRENCY; */
												//$total_paid_amount = $total_released_escrow_po;
												/* if($is_bid_completed == '1' ) {
													$completed_bidder_attachment_list = $this->db // get the user detail
													->select('id,bid_attachment_name,user_id')
													->from('projects_active_bids_users_attachments_tracking')
													->where('project_id', $in_progress_bid_data['project_id'])
													->where('user_id', $in_progress_bid_data['winner_id'])
													->order_by('id DESC')
													->get()->result_array();

													$completed_bidder_data['bid_attachments'] = $completed_bidder_attachment_list;

													$total_project_value[$completed_bidder_data['winner_id']] = $completed_bidder_data['total_project_amount'];
													$completed_project_tab_project_details_page = '';
													if($view_type == 'sp'){
														$completed_project_tab_project_details_page = $this->config->item('completed_project_tab_project_details_page_sp_view_txt');
													}
													
													
													
													echo json_encode(['status' => 200,'completed_project_tab_project_details_page'=>$completed_project_tab_project_details_page,'total_paid_amount' => number_format($total_paid_amount, 0, '', ' '), 'section_id' => $completed_bidder_data['id'], 'sp_msg'=>$sp_notification_msg,'sp_id'=>$sp_id,'po_msg'=>$po_notification_msg,'po_id'=>$po_id,'project_value'=>$project_value,'is_bid_completed'=>$is_bid_completed,'remove_id'=>$remove_id,'is_project_status_change'=>$is_project_status_change,
													'project_completion_date_container_html'=>$project_completion_date_container_html,'project_status'=>$project_status,'data'=>$this->load->view('bidding/project_completed_bidders_listing',array('project_data'=>$project_data,'completed_bidder_data'=>$completed_bidder_data, 'total_paid_amount' => $total_project_value), true)]);
													die;
												} else {
													
												} */
											}
										}
									
									}
								}	
							}

							############## code for move in progress bid from in progress status to completed end for hourly project###
						}else{
							$feedback_data['feedback_provided_on_fulltime_project_id']= $project_id;
							$feedback_data['feedback_provided_on_date']= date('y-m-d H:i:s');
							if($user_id == $po_id && $view_type == 'po'){
							
								$feedback_data['feedback_recived_by_employee_id']= $sp_id;
								$feedback_data['feedback_given_by_employer_id']= $po_id;
								$feedback_data['employee_shows_interest_enthusiasm_for_work']=$post_data['employee_shows_interest_enthusiasm_for_work'];
								
								$feedback_data['employee_demonstrates_competency_in_knowledge_skills']=$post_data['employee_demonstrates_competency_in_knowledge_skills'];
								$feedback_data['employee_demonstrates_levels_of_skill_knowledge']=$post_data['employee_demonstrates_competency_in_knowledge_skills'];
								$feedback_data['employee_dependable_and_relied']=$post_data['employee_dependable_and_relied'];
								$feedback_data['employee_properly_organizes_prioritizes']=$post_data['employee_properly_organizes_prioritizes'];
								
								
								
								$feedback_data['demonstrates_effective_oral_verbal_communication_skills']=$post_data['verbal_communication_skills'];
								$feedback_data['work_quality']=$post_data['work_quality'];
								$feedback_data['self_motivated']=$post_data['self_motivated'];
								$feedback_data['working_relations']=$post_data['working_relations'];
								$feedback_data['demonstrates_flexibility_adaptability']=$post_data['demonstrates_flexibility_adaptability'];
								$feedback_data['solves_problems']=$post_data['solves_problems'];
								$feedback_data['work_ethic']=$post_data['work_ethic'];
								
								$feedback_data['feedback_left_by_employer']=trim($post_data['feedback']);
								
								$fulltime_project_avg_rating_as_employee = ($post_data['verbal_communication_skills']+$post_data['work_quality']+$post_data['self_motivated']+$post_data['working_relations']+$post_data['demonstrates_flexibility_adaptability']+$post_data['solves_problems']+$post_data['work_ethic'])/7;
								
								$feedback_data['fulltime_project_avg_rating_as_employee']=number_format($fulltime_project_avg_rating_as_employee,2);
								
								
								
								/* if($check_employee_given_feedback_to_employer > 0){
									$feedback_data['employee_already_placed_feedback'] = 'Y';
								} */
								
								
								if($this->db->insert ('fulltime_prj_users_received_ratings_feedbacks_as_employee', $feedback_data)){
								
								
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" start 
									
									$this->db->select('id');
									$this->db->from('projects_candidates_for_users_ratings_feedbacks_exchange tpa');
									$this->db->where('project_id',$project_id);
									$this->db->where('po_id',$po_id);
									$this->db->where('sp_id',$sp_id);
									$this->db->where('sp_rating_to_po_date IS NULL');
									$check_ratings_feedbacks_exchange = $this->db->get()->row_array();
									if(!empty($check_ratings_feedbacks_exchange)){
										$this->db->update('projects_candidates_for_users_ratings_feedbacks_exchange', ['po_rating_to_sp_date'=>$feedback_data['feedback_provided_on_date']], ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]);
									}else{
										$this->db->delete('projects_candidates_for_users_ratings_feedbacks_exchange',  ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]); 
									}
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" end 
								
								
									$check_employee_given_feedback_to_employer = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_given_by_employee_id'=>$sp_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')->count_all_results();
								
									$check_employer_given_feedback_to_employee = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employee_id'=>$sp_id,'feedback_given_by_employer_id'=>$po_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')->count_all_results();
								
								
									if($check_employee_given_feedback_to_employer > 0 && $check_employer_given_feedback_to_employee >0){
										$this->db->update('fulltime_prj_users_received_ratings_feedbacks_as_employer', ['employer_already_placed_feedback'=>'Y'], ['feedback_given_by_employee_id'=> $sp_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_provided_on_fulltime_project_id'=>$project_id]);
										
										$this->db->update('fulltime_prj_users_received_ratings_feedbacks_as_employee', ['employee_already_placed_feedback'=>'Y'], ['feedback_recived_by_employee_id'=> $sp_id,'feedback_given_by_employer_id'=>$po_id,'feedback_provided_on_fulltime_project_id'=>$project_id]);
										
										// update user total rating in users_details table for fulltime project of employeer
										$this->db->select('AVG(fulltime_project_avg_rating_as_employer) as project_avg_rating_as_po');
										$this->db->from('fulltime_prj_users_received_ratings_feedbacks_as_employer');
										$this->db->where(['feedback_recived_by_employer_id'=>$po_id,'employer_already_placed_feedback'=>'Y']);
										$avg_rating_result = $this->db->get();
										$avg_rating_row = $avg_rating_result->row_array();
										$user_total_avg_rating_as_po = $avg_rating_row['project_avg_rating_as_po'];
										
										
										$this->db->update('users_details', ['fulltime_project_user_total_avg_rating_as_employer'=>number_format($user_total_avg_rating_as_po,2)], ['user_id'=> $po_id]);
										
									
										//if($check_employee_given_feedback_to_employer > 0){
											// update user total rating in users_details table for fulltime project of employee
											$this->db->select('AVG(fulltime_project_avg_rating_as_employee) as project_avg_rating_as_sp');
											$this->db->from('fulltime_prj_users_received_ratings_feedbacks_as_employee');
											$this->db->where(['feedback_recived_by_employee_id'=>$sp_id,'employee_already_placed_feedback'=>'Y']);
											$avg_rating_result = $this->db->get();
											$avg_rating_row = $avg_rating_result->row_array();
											$user_total_avg_rating_as_sp = $avg_rating_row['project_avg_rating_as_sp'];
											
											$this->db->update('users_details', ['fulltime_project_user_total_avg_rating_as_employee'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
											
											####### update total rating of sp ###
											
											$user_details = $this->db // get the user detail
											->select('project_user_total_avg_rating_as_sp,fulltime_project_user_total_avg_rating_as_employee')
											->from('users_details')
											->where('user_id', $sp_id)
											->get()->row_array();
											
											if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
												$user_total_avg_rating_as_sp = ($user_details['project_user_total_avg_rating_as_sp']+$user_details['fulltime_project_user_total_avg_rating_as_employee'])/2;
											}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) == 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
												$user_total_avg_rating_as_sp = $user_details['fulltime_project_user_total_avg_rating_as_employee'];
											}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) == 0){
												$user_total_avg_rating_as_sp = $user_details['project_user_total_avg_rating_as_sp'];
											}
											
											$this->db->update('users_details', ['user_total_avg_rating_as_sp'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
									//}
									}
								
									// Log message code
									$po_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employer_when_given_feedback_user_activity_log_displayed_message');
									
									$po_activity_log_message = str_replace(array("{sp_profile_url_link}","{user_first_name_last_name_or_company_name}","{project_title}","{project_url_link}"),array($sp_profile_url_link,$sp_name,$project_data['project_title'],$project_url_link),$po_activity_log_message);
									
									
										if(($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')){
										if($po_data['gender'] == 'M'){
											if($po_data['is_authorized_physical_person'] == 'Y'){
												$sp_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_company_app_male_given_feedback_user_activity_log_displayed_message');
											}else{
												$sp_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_male_given_feedback_user_activity_log_displayed_message');
											}
											
										}else{
											if($po_data['is_authorized_physical_person'] == 'Y'){
												$sp_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_company_app_female_given_feedback_user_activity_log_displayed_message');
											}else{
												$sp_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_female_given_feedback_user_activity_log_displayed_message');
											}
											
										}
										$sp_activity_log_message = str_replace(array("{po_profile_url_link}","{user_first_name_last_name}","{project_title}","{project_url_link}"),array($po_profile_url_link,$po_name,$project_data['project_title'],$project_url_link),$sp_activity_log_message);
										
										
									}else{
										$sp_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employee_when_employer_company_given_feedback_user_activity_log_displayed_message');
										
										$sp_activity_log_message = str_replace(array("{po_profile_url_link}","{user_company_name}","{project_title}","{project_url_link}"),array($po_profile_url_link,$po_name,$project_data['project_title'],$project_url_link),$sp_activity_log_message);
									}
								}
							
							
							}else if($user_id == $sp_id && $view_type == 'sp'){
								$feedback_data['feedback_recived_by_employer_id']= $po_id;
								$feedback_data['feedback_given_by_employee_id']= $sp_id;
								$feedback_data['appreciated_right_level']= $post_data['appreciated_right_level'];
								$feedback_data['empowered_take_extra_responsibilities']= $post_data['empowered_take_extra_responsibilities'];
								
								$feedback_data['recognition_work_achievements']= $post_data['recognition_work_achievements'];
								$feedback_data['receive_regular_consistent_feedback']= $post_data['receive_regular_consistent_feedback'];
								$feedback_data['work_life_balance']= $post_data['work_life_balance'];
								$feedback_data['career_opportunities']= $post_data['career_opportunities'];
								$feedback_data['compensation_benefits']= $post_data['compensation_benefits'];
								$feedback_data['proper_training_support_mentorship_leadership']= $post_data['proper_training_support_mentorship_leadership'];
								$feedback_data['explained_job_responsibilities_expectation']= $post_data['job_responsibilities_expectation'];
								$feedback_data['environment_encourages_expressing_sharing_ideas_innovation']= $post_data['environment_encourages_expressing'];
								$feedback_data['safe_healthy_environment']= $post_data['safe_healthy_environment'];
								$feedback_data['recommend_this_company']= $post_data['recommend_this_company'];
								
								
								$feedback_data['feedback_left_by_employee']=trim($post_data['feedback']);
								
								$fulltime_project_avg_rating_as_employer = ($post_data['work_life_balance']+$post_data['career_opportunities']+$post_data['compensation_benefits']+$post_data['proper_training_support_mentorship_leadership']+$post_data['job_responsibilities_expectation']+$post_data['environment_encourages_expressing']+$post_data['safe_healthy_environment'])/7;
								
								$feedback_data['fulltime_project_avg_rating_as_employer']=number_format($fulltime_project_avg_rating_as_employer,2);
								
								/* $check_employer_given_feedback_to_employer = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_given_by_employer_id'=>$po_id,'feedback_recived_by_employee_id'=>$sp_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')->count_all_results();
								if($check_employer_given_feedback_to_employer > 0){
									$feedback_data['employer_already_placed_feedback'] = 'Y';
								} */
								
								
								
								if($this->db->insert ('fulltime_prj_users_received_ratings_feedbacks_as_employer', $feedback_data)){
								
								
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" start 
									
									$this->db->select('id');
									$this->db->from('projects_candidates_for_users_ratings_feedbacks_exchange tpa');
									$this->db->where('project_id',$project_id);
									$this->db->where('po_id',$po_id);
									$this->db->where('sp_id',$sp_id);
									$this->db->where('po_rating_to_sp_date IS NULL');
									$check_ratings_feedbacks_exchange = $this->db->get()->row_array();
									
									
									
									if(!empty($check_ratings_feedbacks_exchange)){
										$this->db->update('projects_candidates_for_users_ratings_feedbacks_exchange', ['sp_rating_to_po_date'=>$feedback_data['feedback_provided_on_date']], ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]);
									}else{
										$this->db->delete('projects_candidates_for_users_ratings_feedbacks_exchange',  ['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id]); 
									}
									
									// tracking data for table "projects_candidates_for_users_ratings_feedbacks_exchange" end 
								
								
								
								
								
									$check_employee_given_feedback_to_employer = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_given_by_employee_id'=>$sp_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')->count_all_results();
								
									$check_employer_given_feedback_to_employee = $this->db->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employee_id'=>$sp_id,'feedback_given_by_employer_id'=>$po_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')->count_all_results();
								
								
									if($check_employee_given_feedback_to_employer > 0 && $check_employer_given_feedback_to_employee >0)
									{
										$this->db->update('fulltime_prj_users_received_ratings_feedbacks_as_employer', ['employer_already_placed_feedback'=>'Y'], ['feedback_given_by_employee_id'=> $sp_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_provided_on_fulltime_project_id'=>$project_id]);
										
										$this->db->update('fulltime_prj_users_received_ratings_feedbacks_as_employee', ['employee_already_placed_feedback'=>'Y'], ['feedback_recived_by_employee_id'=> $sp_id,'feedback_given_by_employer_id'=>$po_id,'feedback_provided_on_fulltime_project_id'=>$project_id]);
									// update user total rating in users_details table for fulltime project of employee
										$this->db->select('AVG(fulltime_project_avg_rating_as_employee) as project_avg_rating_as_sp');
										$this->db->from('fulltime_prj_users_received_ratings_feedbacks_as_employee');
										$this->db->where(['feedback_recived_by_employee_id'=>$sp_id,'employee_already_placed_feedback'=>'Y']);
										$avg_rating_result = $this->db->get();
										$avg_rating_row = $avg_rating_result->row_array();
										$user_total_avg_rating_as_sp = $avg_rating_row['project_avg_rating_as_sp'];
										
										$this->db->update('users_details', ['fulltime_project_user_total_avg_rating_as_employee'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
										
										####### update total rating of sp ###
										
										$user_details = $this->db // get the user detail
										->select('project_user_total_avg_rating_as_sp,fulltime_project_user_total_avg_rating_as_employee')
										->from('users_details')
										->where('user_id', $sp_id)
										->get()->row_array();
										
										if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
											$user_total_avg_rating_as_sp = ($user_details['project_user_total_avg_rating_as_sp']+$user_details['fulltime_project_user_total_avg_rating_as_employee'])/2;
										}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) == 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) != 0){
											$user_total_avg_rating_as_sp = $user_details['fulltime_project_user_total_avg_rating_as_employee'];
										}else if(floatval($user_details['project_user_total_avg_rating_as_sp']) != 0 && floatval($user_details['fulltime_project_user_total_avg_rating_as_employee']) == 0){
											$user_total_avg_rating_as_sp = $user_details['project_user_total_avg_rating_as_sp'];
										}
										$this->db->update('users_details', ['user_total_avg_rating_as_sp'=>number_format($user_total_avg_rating_as_sp,2)], ['user_id'=> $sp_id]);
									
									//if($check_employer_given_feedback_to_employer > 0){
										// update user total rating in users_details table for fulltime project of employeer
										$this->db->select('AVG(fulltime_project_avg_rating_as_employer) as project_avg_rating_as_po');
										$this->db->from('fulltime_prj_users_received_ratings_feedbacks_as_employer');
										$this->db->where(['feedback_recived_by_employer_id'=>$po_id,'employer_already_placed_feedback'=>'Y']);
										$avg_rating_result = $this->db->get();
										$avg_rating_row = $avg_rating_result->row_array();
										$user_total_avg_rating_as_po = $avg_rating_row['project_avg_rating_as_po'];
										
										
										$this->db->update('users_details', ['fulltime_project_user_total_avg_rating_as_employer'=>number_format($user_total_avg_rating_as_po,2)], ['user_id'=> $po_id]);
									//}
								
									}
									$sp_activity_log_message= $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employee_when_given_feedback_user_activity_log_displayed_message');
									
									$sp_activity_log_message = str_replace(array('{po_profile_url_link}','{user_first_name_last_name_or_company_name}','{project_title}','{project_url_link}'),array($po_profile_url_link,$po_name,$project_data['project_title'],$project_url_link),$sp_activity_log_message);
									
									
									if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE  && $sp_data['is_authorized_physical_person'] == 'Y')){
										if($sp_data['gender'] == 'M'){
											if($sp_data['is_authorized_physical_person'] == 'Y'){
												$po_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_company_app_male_given_feedback_user_activity_log_displayed_message');
											}else{
												$po_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_male_given_feedback_user_activity_log_displayed_message');
											}
											
										}else{
											if($sp_data['is_authorized_physical_person'] == 'Y'){
												$po_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_company_app_female_given_feedback_user_activity_log_displayed_message');
											}else{
												$po_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_female_given_feedback_user_activity_log_displayed_message');
											}
											
										}
										$po_activity_log_message = str_replace(array('{sp_profile_url_link}','{user_first_name_last_name}','{project_title}','{project_url_link}'),array($sp_profile_url_link,$sp_name,$project_data['project_title'],$project_url_link),$po_activity_log_message);
										
										
									}else{
										$po_activity_log_message = $this->config->item('fulltime_projects_rating_feedbacks_message_sent_to_employer_when_employee_company_given_feedback_user_activity_log_displayed_message');
										
										$po_activity_log_message = str_replace(array('{sp_profile_url_link}','{user_company_name}','{project_title}','{project_url_link}'),array($sp_profile_url_link,$sp_name,$project_data['project_title'],$project_url_link),$po_activity_log_message);
									}
								}
							}
							user_display_log($po_activity_log_message,$po_id); // activity log message for po
							user_display_log($sp_activity_log_message,$sp_id); // activity log message for sp
						
						}
						
						if($this->input->post ('active_page')){
							$page = $this->input->post ('active_page');
						}else{
							$page = 1;
						}
						
						$total_records = $this->Users_ratings_feedbacks_model->get_user_projects_pending_ratings_feedbacks_count($user_id);
				
						$paginations = generate_pagination_links($total_records, $this->config->item('pending_feedbacks_management_page_url'),$this->config->item('user_projects_pending_ratings_feedbacks_listing_limit'),$this->config->item('user_projects_pending_ratings_feedbacks_number_of_pagination_links'));
						$data['projects_pending_ratings_feedbacks_pagination_links'] = $paginations['links'];
						
						
						$user_projects_pending_feedbacks_listing_data = $this->Users_ratings_feedbacks_model->get_user_projects_pending_ratings_feedbacks_listing($user_id,$paginations['offset'],$this->config->item('user_projects_pending_ratings_feedbacks_listing_limit'));
				
						$data["projects_pending_ratings_feedbacks_data"] = $user_projects_pending_feedbacks_listing_data['data'];
						$data['projects_pending_ratings_feedbacks_count'] = $user_projects_pending_feedbacks_listing_data['total'];
						$page = $paginations['current_page_no'];
						
						$multiplication = $this->config->item('user_projects_pending_ratings_feedbacks_listing_limit') * $page;
						$subtraction = ($multiplication - ($this->config->item('user_projects_pending_ratings_feedbacks_listing_limit') - count($data["projects_pending_ratings_feedbacks_data"])));
						$record_per_page = count($data["projects_pending_ratings_feedbacks_data"]) < $this->config->item('user_projects_pending_ratings_feedbacks_listing_limit') ? $subtraction : $multiplication;
						$page_no = ($this->config->item('user_projects_pending_ratings_feedbacks_listing_limit') * ($page - 1)) + 1;
						echo json_encode(['status' => 200,'acknowledgement_notification_msg'=>$acknowledgement_notification_msg,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$user_projects_pending_feedbacks_listing_data['total'],'data'=>$this->load->view('user_projects_pending_ratings_feedbacks_listing_data',$data, true)]);
						die;
						//echo json_encode(['status' => 200,'location'=>'']);
						//die;
					}else{
						echo json_encode ($validation_data_array);
						die;
					}
				
				}
			}else{
				$res['status'] = 400;
				$res['location'] = VPATH ;
				echo json_encode($res);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
			return;
		}
	}
  
}
?>