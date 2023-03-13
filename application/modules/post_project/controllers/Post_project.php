<?php
if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Post_project extends MX_Controller
{

    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password
     */
    public function __construct ()
    {
		/* $this->load->helper ('recaptcha'); */
		$this->load->model ('Post_project_model');
		$this->load->model ('dashboard/Dashboard_model');
		$this->load->model ('projects/Projects_model');
		$this->load->model('user/User_model');
		$this->load->library ('form_validation');
		$this->load->helper ('url'); //You should autoload this one ;)
		$this->load->helper('ci_helper');
		parent::__construct ();
    }

	
	/**
	* This function is used to fetch the child project categories of selected parent and send response in json.
	*/
    public function get_project_child_categories_selected_parent()
    {
		if($this->input->is_ajax_request () && !empty($this->input->post ('category_parent_id'))){
			$category_parent_id = $this->input->post ('category_parent_id');
			$get_parent_category_query = $this->db->get_where ('categories_projects', array('id' => $category_parent_id,'status'=> 'Y'));
			$result_array =  $get_parent_category_query->result_array ();
			$result_array = array_shift($result_array);
			
			$is_options_exist = '0';
			if(!empty($result_array))
			{
				
				$count_project_child_categories = $this->db
				->select ('id')
				->from ('categories_projects')
				->where ('parent_id', $category_parent_id)
				->where ('status', 'Y')
				->get ()->num_rows ();
				if($count_project_child_categories > 0){
				$options = "<option value=''>".$this->config->item('post_project_page_sub_category_drop_down_option_select_sub_category')."</option>";
				}
				
				if($count_project_child_categories > 0 )
				{
					$this->db->select ('id,name');
					$this->db->order_by('name', 'ASC');
					$this->db->where ('parent_id', $category_parent_id);
					$this->db->where ('status', 'Y');
					$res = $this->db->get ('categories_projects'); 
					foreach ($res->result () as $row)
					{
						$options .= "<option value='".$row->id ."'>".$row->name."</option>";
					}
					$is_options_exist = '1';
				}
			}
			$msg['is_options_exist'] = $is_options_exist;
			$msg['options'] = $options;
			echo json_encode ($msg);die;
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	
	/**
	This function is used to fetch the project localities of selected county and send response in json.
	*/
    public function get_project_localities_selected_county()
    {
		if($this->input->is_ajax_request () && !empty($this->input->post ('project_county_id'))){
			$project_county_id = $this->input->post ('project_county_id');
			$count_project_locality = $this->db
				->select ('id')
				->from ('counties')
				->get ()->num_rows ();
			$options = "<option value=''>".$this->config->item('post_project_page_locality_drop_down_option_select_locality')."</option>";
			$is_options_exist = '0';
			if(!empty($count_project_locality))
			{
				
				$count_project_localties = $this->db
				->select ('id')
				->from ('localities')
				->where ('county_id', $project_county_id)
				->get ()->num_rows ();
				
				if($count_project_localties > 0 )
				{
					$this->db->select ('id,name');
					$this->db->order_by('name', 'ASC');
					$this->db->where ('county_id', $project_county_id);
					$res = $this->db->get ('localities'); 
					foreach ($res->result () as $row)
					{
						$options .= "<option value='".$row->id ."'>".$row->name."</option>";
					}
					$is_options_exist = '1';
				}
			}
			$msg['is_options_exist'] = $is_options_exist;
			$msg['project_localties_options'] = $options;
			$msg['project_localties_postal_code_options'] = '0';
			echo json_encode ($msg);die;
			}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	
	/**
	This function is used to fetch the project parent categories.
	*/
    public function get_project_parent_categories()
    {
		if($this->input->is_ajax_request ()){
			$check_project_categories_available_or_not = $this->db->where(['status'=>'Y','parent_id'=>0])->from('categories_projects')->count_all_results();
			if($check_project_categories_available_or_not == 0){
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('projects_categories_disabled_or_deleted_project_posting_disabled_message')]);
				die;
			}
			
			
			/* $selected_category = json_decode($this->input->post('selectednumbers'));
			
			
			$count_project_parent_category = $this->db
			->select ('id')
			->from ('categories_projects')
			->where ('parent_id', 0)
			->where ('status', 'Y')
			->where_not_in ('id', $selected_category)
			->get ()->num_rows ();
			$category_rows_count = $this->input->post('category_rows_count'); */
			
			/* if($count_project_parent_category ==0){
				echo json_encode(['status' => 200,'popup_heading'=>'','location'=>'','error'=>'']);
				die;
			
			} */
			$msg['is_options_exist'] = 0;
			$project_parent_categories_options .= "<option value=''>".$this->config->item('post_project_page_category_drop_down_option_select_category')."</option>";
			$project_parent_categories = $this->Post_project_model->get_project_parent_categories();
			if(!empty($project_parent_categories)){
				
				foreach ($project_parent_categories as $row)
				{
					$project_parent_categories_options .= "<option value='".$row['id'] ."'>".$row['name']."</option>";
				}
				$msg['is_options_exist'] = 1;
			}
			$msg['project_parent_categories_options'] = $project_parent_categories_options; 
			$msg['availables_categories_count'] = $check_project_categories_available_or_not; 
			$msg['status'] = 200;
			echo json_encode ($msg);die;
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	
	
	
	
	/**
	This function is used to fetch the project postal codes of selected locality and send response in json.
	*/
    public function get_project_postal_code_selected_locality()
    {
		if($this->input->is_ajax_request () && !empty($this->input->post ('project_locality_id'))){
			$project_locality_id = $this->input->post ('project_locality_id');
			$count_project_locality = $this->db
				->select ('id')
				->from ('localities')
				->get ()->num_rows ();
			$options = "<option value=''>".$this->config->item('post_project_page_postal_code_drop_down_option_select_postal_code')."</option>";
			$is_options_exist = '0';
			$number_postal_codes = '0';
			if(!empty($count_project_locality))
			{
				
				$count_project_postal_codes = $this->db
				->select ('id')
				->from ('postal_codes')
				->where ('locality_id', $project_locality_id)
				->get ()->num_rows ();
				
				if($count_project_postal_codes > 0 )
				{
					$this->db->select ('id,postal_code');
					$this->db->order_by('id', 'ASC');
					$this->db->where ('locality_id', $project_locality_id);
					$res = $this->db->get ('postal_codes'); 
					foreach ($res->result () as $row)
					{
						if(!empty($row->id) && !empty($row->postal_code)){
							$options .= "<option value='".$row->id ."'>".$row->postal_code."</option>";
							$is_options_exist = '1';
						}
					}
					
					$number_postal_codes = $count_project_postal_codes;
				}
			}
			$msg['number_postal_codes'] = $number_postal_codes ;
			$msg['is_options_exist'] = $is_options_exist;
			$msg['project_postal_codes_options'] = $options;
			echo json_encode ($msg);die;
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	/* This function is used to create project id and after that redirect to post project page */
	public function create_project_id(){
	
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		/* if(!check_session_validity()) {
			echo json_encode(['status' => 400,'location'=>VPATH]);
			die;
		} */
		
		if($this->session->userdata('user')){
			$user = $this->session->userdata('user');
			$this->db->select('current_membership_plan_id');
			$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
			
			// check that all parent category is enabled or not
			$check_project_categories_available_or_not = $this->db->where(['status'=>'Y','parent_id'=>0])->from('categories_projects')->count_all_results();
			if($check_project_categories_available_or_not == 0 ){
				if($user_detail['current_membership_plan_id'] == 1){
					$error_message = $this->config->item('free_membership_subscriber_top_navigation_post_project_project_posting_disabled_message');
				}if($user_detail['current_membership_plan_id'] == 4){
					$error_message = $this->config->item('gold_membership_subscriber_top_navigation_post_project_project_posting_disabled_message');
				}
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
				die;
			}
			
			
			if($user_detail['current_membership_plan_id'] == 1){
				$po_max_draft_projects_number = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
				$po_max_open_projects_number =  $this->config->item('free_membership_subscriber_max_number_of_open_projects');

				$po_max_draft_fulltime_projects_number = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
				$po_max_open_fulltime_projects_number =  $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
			}
			if($user_detail['current_membership_plan_id'] == 4){
				$po_max_draft_projects_number =$this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
				$po_max_open_projects_number = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');

				$po_max_draft_fulltime_projects_number =$this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
				$po_max_open_fulltime_projects_number = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
			}
			$user_total_open_projects_count = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
			$user_total_open_fulltime_projects_count = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
			
			$total_user_draft_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id])->where_in('project_type', ['fixed', 'hourly'])->from('projects_draft')->count_all_results();
			$total_user_draft_fulltime_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_type' => 'fulltime'])->from('projects_draft')->count_all_results();
			// standard project availability
			$standard_time_arr = explode(":", $this->config->item('standard_project_availability'));
			$standard_check_valid_arr = array_map('getInt', $standard_time_arr); 
			$standard_valid_time_arr = array_filter($standard_check_valid_arr);
			
			
			if(($po_max_draft_projects_number == '0' && ($po_max_open_projects_number == '0' || empty($standard_valid_time_arr))) && 
				($po_max_draft_fulltime_projects_number == '0' && ($po_max_open_fulltime_projects_number == '0' || empty($standard_valid_time_arr) )) ){
				if($user_detail['current_membership_plan_id'] == 1){
					$error_message = $this->config->item('free_membership_subscriber_top_navigation_post_project_project_posting_disabled_message');
				}if($user_detail['current_membership_plan_id'] == 4){
					$error_message = $this->config->item('gold_membership_subscriber_top_navigation_post_project_project_posting_disabled_message');
				}
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
				die;
			} else if($user_total_open_projects_count >= $po_max_open_projects_number && 
				$total_user_draft_project_count >= $po_max_draft_projects_number && 
				$user_total_open_fulltime_projects_count >= $po_max_open_fulltime_projects_number && 
				$total_user_draft_fulltime_project_count >= $po_max_draft_fulltime_projects_number) {
				if($user_detail['current_membership_plan_id'] == 1){
					$error_message = $this->config->item('free_membership_subscriber_top_navigation_post_project_and_save_draft_open_slots_not_available_message');
				}if($user_detail['current_membership_plan_id'] == 4){
					$error_message = $this->config->item('gold_membership_subscriber_top_navigation_post_project_and_save_draft_open_slots_not_available_message');
				}
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
				die;
			} 
		}
		$temp_project_id = generate_unique_project_id();
		$this->session->set_userdata ('check_redirection', 1);
		
		$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
		$temp_project_data = array(
			'temp_project_id' => $temp_project_id,
			'temp_project_owner_id' =>($this->session->userdata('user'))?$user[0]->user_id:0,
			'project_submission_date'=>date('Y-m-d H:i:s'),
			'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds')),
			'project_owner_last_activity_date' => date('Y-m-d H:i:s')
			
		);
		
		if ($this->db->insert ('temp_projects', $temp_project_data)){
			$url = VPATH . $this->config->item('post_project_page_url').'?id='.$temp_project_id;
			echo json_encode(['status' => 200,'location'=>$url]);
			die;
			
			
		}
		
	
	}
	/**
	* This function is used to post the project.
	*/
	public function index() {
		//if(!check_session_validity()){
		if(!$this->input->get('id')){
				$this->Post_project_model->delete_temp_project($this->input->get('id'));// Delete the temporary project with complete files,data etc
				redirect(VPATH);
			}
			
		//}
		
		$user = $this->session->userdata('user');
		$temp_project_id = $this->input->get('id');
		if($this->session->userdata('user')){
			########## fetch draft project information of logged in user ##########################
			$data['draft_cnt'] = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
			$data['fulltime_draft_cnt'] = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);
			########## fetch open bidding project information of logged in user ##########################
			$data['open_bidding_cnt'] = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
			$data['fulltime_open_bidding_cnt'] = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
		}
		$count_project_parent_category = $this->db
		->select ('id')
		->from ('categories_projects')
		->where ('parent_id', 0)
		->where ('status', 'Y')
		->get ()->num_rows ();
		$data['count_available_project_parent_category_count'] = 	$count_project_parent_category;
		if($this->session->userdata('user')){
		################## get the user_details #################
			$this->db->select('current_membership_plan_id');
			$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
			$data['user_detail'] = $user_detail;
			$user_membership_plan_details = $this->db->get_where('membership_plans', array('id' => $user_detail['current_membership_plan_id']))->row();
			$data['user_membership_plan_details'] = $user_membership_plan_details;
		}
		
		$data['project_parent_categories'] = $this->Post_project_model->get_project_parent_categories();
		$this->db->where('temp_project_id', $temp_project_id);
		$temp_project_data = $this->db->get('temp_projects')->row_array();
		if(empty($temp_project_data)) { // if project not exists it will redirect to dashboard page
			$url = VPATH . $this->config->item('dashboard_page_url');
			redirect ($url);
		}else{
		
			
			$project_expiration_timestamp = $temp_project_data['project_expiration_date']!= NULL ? strtotime ($temp_project_data['project_expiration_date']) : 0;
			if(empty($project_expiration_timestamp) || $project_expiration_timestamp < time()){
				$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dashboard
				if($this->session->userdata('user')){
					$url = VPATH . $this->config->item('dashboard_page_url');
				}else{
					$url = VPATH;
					
				}	
				redirect ($url);
			}else{
			
				// update expiration time on user action
				$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
				$upate_data = [
					'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
					'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
				];
				$this->db->where('temp_project_id', $temp_project_id);
				$this->db->update('temp_projects', $upate_data);
			}
		}
		
		if(! $this->session->userdata('check_redirection')){
		$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data etc
		
			if($this->session->userdata('user')){
				$url = VPATH . $this->config->item('dashboard_page_url');
				redirect ($url);
			}else{
				redirect (VPATH);
				
			}	
		} 
		$data['current_page'] = 'post_project';
		$data['temp_project_id'] = $temp_project_id;

		######## fetch project detail from temp_projects table ########
		//$user = $this->session->userdata ('user');
		$data['fixed_budget_projects_budget_range'] = $this->Post_project_model->get_fixed_budget_projects_budget_range();// drop down options for fixed budget project budget range
		$data['hourly_rate_based_budget_projects_budget_range'] = $this->Post_project_model->get_hourly_rate_based_projects_budget_range();// drop down options for hourly rate based project budget range
		
		$data['fulltime_project_salary_range'] = $this->Post_project_model->get_fulltime_projects_salaries_range();// drop down options for fulltime project salary range
	
		$data['counties'] = $this->Dashboard_model->get_counties(); // drop down options of counties
		########## fetch project information from temp_projects table with refrences table end #########
		if($this->session->userdata ('user')){
			$data['count_user_featured_membership_included_upgrades_monthly'] = $this->Post_project_model->count_user_featured_membership_included_upgrades_monthly($user[0]->user_id);
			$data['count_user_urgent_membership_included_upgrades_monthly'] = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user[0]->user_id);
			$data['count_user_sealed_membership_included_upgrades_monthly'] = $this->Post_project_model->count_user_sealed_membership_included_upgrades_monthly($user[0]->user_id);
			$data['count_user_hidden_membership_included_upgrades_monthly'] = $this->Post_project_model->count_user_hidden_membership_included_upgrades_monthly($user[0]->user_id);
		}
		
		########## meta information of post project pag ##############
		$post_project_page_title_meta_tag = $this->config->item('post_project_page_title_meta_tag');
		$post_project_page_description_meta_tag = $this->config->item('post_project_page_description_meta_tag');
		$data['meta_tag'] = '<title>' . $post_project_page_title_meta_tag . '</title><meta name="description" content="' . $post_project_page_description_meta_tag . '"/>';
		$this->session->unset_userdata('check_redirection'); 
		$this->session->set_userdata ('check_redirection_preview', 1); // set reditection status for preview page
		if(! $this->session->userdata('repost')){ // when po post project then section is execute
		
			if($this->session->userdata('user')){
				$this->layout->view('post_project', '', $data, 'normal');
			}else{
				$this->layout->view('post_project_logged_off', '', $data, 'normal');
				
			}	
		} else if($this->session->userdata('repost')){ // when po click on repost/copy into new that time the below file and code is execute
			//$this->Projects_model->check_update_invalid_combination_project_location('temp_projects','temp_project_id',$temp_project_id); // check valid combination of locality_id,county_id,postal_code_id If the combination is not valid it will update locality_id,county_id,postal_code_id  to 0.
			$data['project_parent_categories'] = $this->Post_project_model->get_project_parent_categories();
			
			$data['temp_project_id'] = $temp_project_id;
				
			$data['temp_project_data'] = $temp_project_data;
			########## fetch the temp categories and make the dynamic array start ###
			$this->db->select('*');
			$this->db->from('temp_projects_categories_listing_tracking');
			$this->db->where('temp_project_id',$temp_project_id);
			$this->db->order_by('id',"asc");
			$category_result = $this->db->get();
			$temp_category_data = $category_result->result_array();
			$data['temp_category_data'] = $temp_category_data;
			########## fetch the temp categories and make the dynamic array end ###
			$profile_name = '';
			if($this->session->userdata('user')){
			$profile_name = $user[0]->profile_name;
			}
			########## fetch the temp project attachments ###
			$data['project_attachment_array'] = $this->get_tempoary_project_attachments($temp_project_id,$profile_name);
			
			########## fetch the temp project tags ###
			$this->db->select('*');
			$this->db->from('temp_projects_tags');
			$this->db->where('temp_project_id',$temp_project_id);
			$this->db->order_by('id',"asc");
			$temp_project_tag_result = $this->db->get();
			$temp_project_tag_data = $temp_project_tag_result->result_array();
			
			$data['temp_project_tag_data'] = $temp_project_tag_data;
			
			########## fetch the temp project tags end ###
			$data['localities'] = $this->Dashboard_model->get_localities_selected_county($temp_project_data['county_id']);// drop down options of localities
			
			$data['postal_codes'] = $this->Post_project_model->get_project_post_codes($temp_project_data['locality_id']);// drop down options of localities
			
			$count_project_categories = $this->db // count the number of categories of temporary project
				->select ('id')
				->from ('temp_projects_categories_listing_tracking')
				->where('temp_project_id',$temp_project_id)
				->get ()->num_rows ();
				
			$count_project_attachments = $this->db // count the number of attachment of temporary project
				->select ('id')
				->from ('temp_projects_attachments')
				->where('temp_project_id',$temp_project_id)
				->get ()->num_rows ();
				
			$count_project_tags = $this->db // count the number of tag of temporary project
				->select ('id')
				->from ('temp_projects_tags')
				->where('temp_project_id',$temp_project_id)
				->get ()->num_rows ();	
				
			$count_project_postal_codes = $this->db
			->select ('id')
			->from ('postal_codes')
			->where ('locality_id', $temp_project_data['locality_id'])
			->get ()->num_rows ();

			$data['count_project_categories'] = $count_project_categories;
			$data['count_project_attachments'] = $count_project_attachments;
			$data['count_project_tags'] = $count_project_tags;
			$data['count_project_postal_codes'] = $count_project_postal_codes;
			
			$this->layout->view('edit_temporary_project_preview', '', $data, 'normal');
			$this->session->unset_userdata('repost');  
		}
		
	}
	/**
	* This function is used to post the project logged off.
	*/
	public function post_project_logged_off() {
		
            $data['current_page'] = 'post_project';

            ########## meta information of post project pag ##############
            $post_project_page_title_meta_tag = $this->config->item('post_project_page_title_meta_tag');
            $post_project_page_description_meta_tag = $this->config->item('post_project_page_description_meta_tag');
            $data['meta_tag'] = '<title>' . $post_project_page_title_meta_tag . '</title><meta name="description" content="' . $post_project_page_description_meta_tag . '"/>';


            $this->layout->view('post_project_logged_off', '', $data, 'normal');
		
		
	}
	/**
	* This function is used to delete the entire temp project data from database.
	*/
	public function cancel_post_temp_project ()
    {
        if ($this->input->is_ajax_request())
        {
			
			if(!$this->input->post ('temp_project_id')){
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$temp_project_id = $this->input->post ('temp_project_id');
			
			$this->Post_project_model->delete_temp_project($temp_project_id);// // Delete the temporary project with complete files,data etc
			check_session_validity();
			$msg['status'] = 'SUCCESS';
			$msg['message'] = '';
			$msg['location'] = VPATH . $this->config->item('dashboard_page_url');
			echo json_encode ($msg);die;
			
			
        }else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	/**
	This function is used to delete the project category from temp_projects_categories_listing_tracking table.
	*/
	public function delete_project_category_temp ()
    {
		
        if ($this->input->is_ajax_request ())
        {
			if(empty($this->input->post ('temp_category_project_id'))){
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$temp_category_project_id = $this->input->post ('temp_category_project_id');
			$temp_category_array  = explode('_',$temp_category_project_id);
			$temp_project_id = $temp_category_array[1];
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				
				$remove_category_id = $this->input->post ('remove_category_id');
				
				
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				
				if(empty($temp_project_data)) { // if project not exists it will redirect to dashboard page
					$res = [
						'status' => 400,
						'location'=>VPATH.$this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}
				$project_expiration_timestamp = $temp_project_data['project_expiration_date']!= NULL ? strtotime ($temp_project_data['project_expiration_date']) : 0;
				if(empty($project_expiration_timestamp) || $project_expiration_timestamp < time()){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dashboard page
					$res = [
						'status' => 400,
						'location'=>VPATH . $this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}else{
				
					// update expiration time on user action
					$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
					$upate_data = [
						'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
						'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
					];
					$this->db->where('temp_project_id', $temp_project_id);
					$this->db->update('temp_projects', $upate_data);
				}
				
				$this->db->delete('temp_projects_categories_listing_tracking', array('id' => $temp_category_array[0]));
				$count_project_categories = $this->db // count the number of record in temp_projects table
				->select ('id')
				->from ('temp_projects_categories_listing_tracking')
				->where('temp_project_id',$temp_project_id)
				->get ()->num_rows ();
				
				
				$max_category = $this->config->item('number_project_category_post_project');
				$msg['add_category_button_show_status']  = '1';
				if( $count_project_categories >= $max_category ){
					$msg['add_category_button_show_status']  = '0';
				}
				
				
				$msg['remove_category_id'] = $remove_category_id;
				$msg['status'] = 'SUCCESS';
				$msg['message'] = '';
				echo json_encode ($msg);
			}else{
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				if(!empty($temp_project_data)){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
				}	
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
	This function is used to delete the project tag from temp_projects_tags table.
	*/
	public function delete_project_tag_temp ()
    {
        if ($this->input->is_ajax_request () && $this->input->post ('project_tag_id'))
        {
			if(!$this->input->post ('project_tag_id')){ // if project temp id not coming it will show 404 page
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$project_tag_id = $this->input->post ('project_tag_id');
			$project_tag_array = explode("_",$project_tag_id);
			
			$temp_project_id = $project_tag_array[3];
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
					
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				
				if(empty($temp_project_data)) { // if project not exists it will redirect to dasboard page
					$res = [
						'status' => 400,
						'location'=>VPATH.$this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}	
				$project_expiration_timestamp = $temp_project_data['project_expiration_date']!= NULL ? strtotime ($temp_project_data['project_expiration_date']) : 0;
				if(empty($project_expiration_timestamp) || $project_expiration_timestamp < time()){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dashboard page
					$res = [
						'status' => 400,
						'location'=>VPATH . $this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}else{
				
					// update expiration time on user action
					$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
					$upate_data = [
						'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
						'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
					];
					$this->db->where('temp_project_id', $temp_project_id);
					$this->db->update('temp_projects', $upate_data);
				}	
					
					
				$this->db->delete('temp_projects_tags', array('id' => $project_tag_array[2]));
				$msg['status'] = 'SUCCESS';
				$msg['message'] = '';
				echo json_encode ($msg);
			}else{
			
			}
        }else{
			show_custom_404_page(); //show custom 404 page
		}
		
    }
	
	
	/**
	This function is used to delete the all project tags(temp tag/draft tag/project tag).
	*/
	public function delete_all_project_tags ()
    {
        if ($this->input->is_ajax_request ())
        {
			if(!$this->input->post ('project_id')){ // if project temp id not coming it will show 404 page
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$project_id = $this->input->post ('project_id');
			$project_status = $this->input->post ('project_status');
			//if(check_session_validity()){ // check session exists or not if exist then it will update user session
				if($project_status == 'temp'){
					$this->db->delete('temp_projects_tags', array('temp_project_id' => $project_id));
					$count_project_tags = $this->db->where(['temp_project_id' => $project_id])->from('temp_projects_tags')->count_all_results();
					
				}
				if($project_status == 'draft'){
					$this->db->delete('draft_projects_tags', array('project_id' => $project_id));
					$count_project_tags = $this->db->where(['project_id' => $project_id])->from('draft_projects_tags')->count_all_results();
					
				}
				if($project_status == 'open'){
					$this->db->delete('projects_tags', array('project_id' => $project_id));
					$count_project_tags = $this->db->where(['project_id' => $project_id])->from('projects_tags')->count_all_results();
					
				}
				
				if($count_project_tags >= $this->config->item('number_tag_allowed_post_project')){
					$msg['show_tag_input_status'] = '0';
				}else{
					$msg['show_tag_input_status'] = '1';
				}
				$msg['status'] = 200;
				$msg['message'] = '';
				echo json_encode ($msg);
			/* }else{
			
			} */
        }else{
			show_custom_404_page(); //show custom 404 page
		}
		
    }
	
	
	/**
	This function is used to upload project attachment in temp_projects_attachments table.
	*/
	public function upload_project_attachment_temp ()
    {
		if($this->input->is_ajax_request ()){
			if(!$this->uri->segment('3')){ // if project temp id not coming it will show 404 page
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$temp_project_id = $this->uri->segment('3');
			
			//if(check_session_validity()){ // check session exists or not if exist then it will update user session
				
				$user = $this->session->userdata ('user');
				$no_project_attachment_uploaded_user_temp = $this->db
				->select ('id')
				->from ('temp_projects_attachments')
				->where ('temp_project_id', $temp_project_id)
				->get ()->num_rows ();// check the number of attachment of user into database
				
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				
				if(empty($temp_project_data)) { // if project not exists it will redirect to dasboard page
					$res = [
						'status' => 400,
						'location'=>VPATH.$this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}
				
				$project_expiration_timestamp = $temp_project_data['project_expiration_date']!= NULL ? strtotime ($temp_project_data['project_expiration_date']) : 0;
				if(empty($project_expiration_timestamp) || $project_expiration_timestamp < time()){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dashboard page
					$res = [
						'status' => 400,
						'location'=>VPATH . $this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}else{
				
					// update expiration time on user action
					$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
					$upate_data = [
						'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
						'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
					];
					$this->db->where('temp_project_id', $temp_project_id);
					$this->db->update('temp_projects', $upate_data);
				}
				
				
				$project_attachment_maximum_size_limit	 = $this->config->item('project_attachment_maximum_size_limit');
				
				$project_attachment_maximum_size_limit = ($project_attachment_maximum_size_limit * 1048576);
				if(!empty($_FILES['file']['tmp_name'])){
					$file_array = $_FILES['file'];
					if($file_array['size'] > $project_attachment_maximum_size_limit){
						$msg['status'] = 'FAILED';
						$msg['message'] = $this->config->item('project_attachment_maximum_size_validation_post_project_message');
						echo json_encode ($msg);die;
					}elseif($no_project_attachment_uploaded_user_temp >= $this->config->item('maximum_allowed_number_of_attachments_on_projects')){
						$msg['status'] = 'FAILED';
						$msg['message'] = $this->config->item('post_project_page_maximum_allowed_number_of_project_attachments_violation_message');
						echo json_encode ($msg);die;
					
					}else{
					
						######## connectivity of remote server start#########
						$this->load->library('ftp');
						$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
						$config['ftp_username'] = FTP_USERNAME;
						$config['ftp_password'] = FTP_PASSWORD;
						$config['ftp_port'] 	= FTP_PORT;
						$config['debug']    = TRUE;
						$this->ftp->connect($config); 
						######## connectivity of remote server end #######
						$temp_dir = TEMP_DIR;
						$users_ftp_dir 	= USERS_FTP_DIR; 
						$projects_ftp_dir = PROJECTS_FTP_DIR;
						$projects_temp_dir = PROJECT_TEMPORARY_DIR;
						
						$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
						
						
							
						
						if(!empty($temp_project_id )){
							if($this->session->userdata ('user')){
								$profile_folder     = $user[0]->profile_name;
								
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR);
								
								/* $this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
								$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir, 0777);// create temporary directory in projects folder
								$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR , 0777); // create the directory by using temporary project id */
								
							}else{
								$this->ftp->mkdir( DIRECTORY_SEPARATOR .$temp_dir, 0777);// create projects directory if not exists
								//$this->ftp->mkdir(DIRECTORY_SEPARATOR .$temp_dir .substr($projects_ftp_dir,1), 0777);// create projects directory if not exists
								$this->ftp->mkdir(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir, 0777);// create projects directory if not exists
								$this->ftp->mkdir(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR , 0777); // create the directory by using temporary project id
							}
							
							$temp 		= 	explode(".", $file_array["name"]);
							$extension 	= 	end($temp);
							$temp_attachment_name 	= 	$this->config->item('attachment_prefix_text').rand(0,1000).$temp_project_id.'.'.$extension;// name of attachment
							
							if(move_uploaded_file($file_array['tmp_name'],TEMP_DIR.$temp_attachment_name)){
								
								$source_path = FCPATH .TEMP_DIR. $temp_attachment_name;
								if($this->session->userdata ('user')){
									$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR .$temp_attachment_name;
								}else{
									$destination_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$temp_attachment_name;
									
								}
								
								$this->ftp->upload($source_path,$destination_path , 'auto', 0777); // upload the attachment into temporary folder of projects
								unlink(FCPATH .TEMP_DIR. $temp_attachment_name);
								
								$temp_projects_attachments_data = array('temp_project_id'=>$temp_project_id,'temp_project_attachment_name'=>$temp_attachment_name);
								$this->db->insert ('temp_projects_attachments', $temp_projects_attachments_data);
								$last_insert_id = $this->db->insert_id();
								
								$no_project_attachment_uploaded_user_temp = $this->db
								->select ('id')
								->from ('temp_projects_attachments')
								->where ('temp_project_id', $temp_project_id)
								->get ()->num_rows (); // check the number of attachment of user into database
								
								$msg['status'] = 'OK';
								$msg['message'] = 'uploded';
								$msg['filename'] = $temp_attachment_name;
								$msg['size'] = number_format($file_array['size']/1024). 'KB';
								$msg['id'] = $last_insert_id;
								$msg['temp_id'] = Cryptor::doEncrypt($last_insert_id);
								
								$upload_button_status = '0';
								if($no_project_attachment_uploaded_user_temp < $this->config->item('maximum_allowed_number_of_attachments_on_projects')){
									$upload_button_status = '1';
								}
								$msg['upload_button_status'] = $upload_button_status;
								echo json_encode ($msg);die;
							}
						}
						$this->ftp->close();
					}	
					
				}else{
					$msg['status'] = 'FAILED';
					$msg['message'] = 'file is empty';
					echo json_encode($msg);
					die;
				}
			/* }else{
			
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				if(!empty($temp_project_data)){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dashboard page
				}	
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			} */
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }

	
	/**
	This function is used to check that temporary attachment exists or not in either in temp folder or into database
	*/
	public function check_project_attachment_temp_exists ()
    {
		if( $this->input->is_ajax_request ()){
			if(empty($this->input->post ('temp_attachment_id'))){
			
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$page_type = $this->input->post ('page_type');
			$project_type = $this->input->post ('project_type');
			$encrypt_temp_attachment_id = $this->input->post ('temp_attachment_id');
			$decrypt_temp_attachment_id = Cryptor::doDecrypt($this->input->post ('temp_attachment_id'));
			$temp_project_attachment_detail = $this->db->get_where('temp_projects_attachments', array('id' => $decrypt_temp_attachment_id))->result_array();
			$temp_project_id = $temp_project_attachment_detail[0]['temp_project_id'];
			/* if(check_session_validity()){ // check session exists or not if exist then it will update user session */
			
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				
				if(empty($temp_project_data)) { // if project not exists it will redirect to dasboard page
					
					$msg['status'] = 'FAILED';
					if($page_type == 'post_project' ){
						if($project_type == 'post_fulltime_position'){
							$msg['message'] = $this->config->item('fulltime_project_attachment_not_exist_validation_post_project_page_message');
						}else{
							$msg['message'] = $this->config->item('project_attachment_not_exist_validation_post_project_page_message');
						}	
					
					}else{
						if($project_type == 'fulltime'){
							$msg['message'] = $this->config->item('fulltime_project_attachment_not_exist_validation_preview_project_page_message');
						}else{
							$msg['message'] = $this->config->item('project_attachment_not_exist_validation_preview_project_page_message');
						}	
					
					}
					$msg['location'] = '';
					echo json_encode($msg);
					die;
				}
				$project_expiration_timestamp = $temp_project_data['project_expiration_date']!= NULL ? strtotime ($temp_project_data['project_expiration_date']) : 0;
				if(empty($project_expiration_timestamp) || $project_expiration_timestamp < time()){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
					$res = [
						'status' => 400,
						'location'=>VPATH . $this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}else{
				
					// update expiration time on user action
					$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
					$upate_data = [
						'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
						'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
					];
					$this->db->where('temp_project_id', $temp_project_id);
					$this->db->update('temp_projects', $upate_data);
				}
			
			
				$user = $this->session->userdata ('user');
				if(!empty($temp_project_attachment_detail)){
					
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->ftp->connect($config); 
					$temp_dir = TEMP_DIR;
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$projects_temp_dir = PROJECT_TEMPORARY_DIR;
					$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
					
					
					
					
					
					if($this->session->userdata ('user')){
						$profile_folder     = $user[0]->profile_name;
						$temp_project_attachment_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_attachment_detail[0]['temp_project_id'] .DIRECTORY_SEPARATOR .$temp_project_attachment_detail[0]['temp_project_attachment_name'];
						$file_size = $this->ftp->get_filesize($temp_project_attachment_path);
						
						if($file_size == '-1'){
						
							$temp_project_attachment_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_attachment_detail[0]['temp_project_id'].DIRECTORY_SEPARATOR .$temp_project_attachment_detail[0]['temp_project_attachment_name'];
							$file_size = $this->ftp->get_filesize($temp_project_attachment_path);
						}
					}else{
					
						$temp_project_attachment_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_attachment_detail[0]['temp_project_id'].DIRECTORY_SEPARATOR .$temp_project_attachment_detail[0]['temp_project_attachment_name'];
						$file_size = $this->ftp->get_filesize($temp_project_attachment_path);
					}
					//die("dsdsdda");
					
					if($file_size != '-1')
					{
						
						$msg['status'] = 'SUCCESS';
						$msg['message'] = '';
						$msg['location'] = VPATH . 'post_project/download_project_attachment_temp/'.$encrypt_temp_attachment_id;
						
					}else{
						$msg['status'] = 'FAILED';
						if($page_type == 'post_project' ){
							if($project_type == 'post_fulltime_position'){
								$msg['message'] = $this->config->item('fulltime_project_attachment_not_exist_validation_post_project_page_message');
							}else{
								$msg['message'] = $this->config->item('project_attachment_not_exist_validation_post_project_page_message');
							}	
						
						}else{
							if($project_type == 'fulltime'){
								$msg['message'] = $this->config->item('fulltime_project_attachment_not_exist_validation_preview_project_page_message');
							}else{
								$msg['message'] = $this->config->item('project_attachment_not_exist_validation_preview_project_page_message');
							}	
						
						}
						$msg['location'] = '';
					}
					$this->ftp->close();
				}else{
					$msg['status'] = 'FAILED';
					if($page_type == 'post_project' ){
							if($project_type == 'post_fulltime_position'){
								$msg['message'] = $this->config->item('fulltime_project_attachment_not_exist_validation_post_project_page_message');
							}else{
								$msg['message'] = $this->config->item('project_attachment_not_exist_validation_post_project_page_message');
							}	
						
						}else{
							if($project_type == 'fulltime'){
								$msg['message'] = $this->config->item('fulltime_project_attachment_not_exist_validation_preview_project_page_message');
							}else{
								$msg['message'] = $this->config->item('project_attachment_not_exist_validation_preview_project_page_message');
							}	
						
					}
					$msg['location'] = '';
					
				}
				echo json_encode ($msg);die;
			/* }else{
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				if(!empty($temp_project_data)){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
				}	
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			
			} */
		}else{
			show_custom_404_page(); //show custom 404 page
		}
		
    }
	
	
	/**
	This function is used to download project attachment from temp_projects_attachments table.
	*/
	public function download_project_attachment_temp ()
    {
		/* if($this->session->userdata ('user')){ */
			$this->load->helper('download');
			$temp_attachment_id = Cryptor::doDecrypt($this->uri->segment(3));
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
			$temp_dir = TEMP_DIR;
			$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$projects_temp_dir = PROJECT_TEMPORARY_DIR;
			$profile_folder     = $user[0]->profile_name;
			$temp_project_attachment_detail = $this->db->get_where('temp_projects_attachments', array('id' => $temp_attachment_id))->row();
			$temp_project_data = $this->db->get_where('temp_projects', ['temp_project_id' => $temp_project_attachment_detail->temp_project_id])->row_array();
			
			if(empty($temp_project_data)) {
				redirect($this->config->item('dashboard_page_url'));
			} else {
				$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
				$update_data = [
					'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
					'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
				];
				$this->db->where('temp_project_id', $temp_project_data['temp_project_id']);
				$this->db->update('temp_projects', $update_data);
			}
			if($this->session->userdata ('user')){
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_attachment_detail->temp_project_id .DIRECTORY_SEPARATOR .$temp_project_attachment_detail->temp_project_attachment_name;
				$file_size = $this->ftp->get_filesize($source_path);
				if($file_size == '-1'){
				
					$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_attachment_detail->temp_project_id.DIRECTORY_SEPARATOR .$temp_project_attachment_detail->temp_project_attachment_name;
					$file_size = $this->ftp->get_filesize($source_path);
				}
				
				
			}else{
			
				$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_attachment_detail->temp_project_id.DIRECTORY_SEPARATOR .$temp_project_attachment_detail->temp_project_attachment_name;
				$file_size = $this->ftp->get_filesize($source_path);
			}
			
			
			
			if($file_size != '-1')
			{
				$destination_path =  FCPATH .TEMP_DIR.$temp_project_attachment_detail->temp_project_attachment_name;
				$this->ftp->download($source_path,$destination_path, 'auto', 0777);
				$this->ftp->close();
				$data = file_get_contents (TEMP_DIR.$temp_project_attachment_detail->temp_project_attachment_name );// read the content of file
				unlink(TEMP_DIR.$temp_project_attachment_detail->temp_project_attachment_name );
				force_download ($temp_project_attachment_detail->temp_project_attachment_name,$data);
			}else{
			
				show_custom_404_page();
				return;
			}
		/* }else{
			show_404();
		} */
    }
	
	/**
	This function is used to remove the temporary attachment of projects.
	*/
	public function delete_project_attachment_temp ()
    {
       // if ($this->input->is_ajax_request () && $this->input->post ('temp_project_attachment_id') && $this->session->userdata ('user'))
        if ($this->input->is_ajax_request ())
        {
			if(!$this->input->post ('temp_project_id') || !$this->input->post ('temp_project_attachment_id')){
				show_custom_404_page();
				return;
			}
			/* if(check_session_validity()){  */// check session exists or not if exist then it will update user session
			
				$temp_project_id = $this->input->post ('temp_project_id');
				$temp_project_data = $this->db->get_where('temp_projects', ['temp_project_id' => $temp_project_id])->row_array();
				if(empty($temp_project_data)) {
					$res = [
						'status' => 400,
						'location'=>VPATH.$this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}
				$project_expiration_timestamp = $temp_project_data['project_expiration_date']!= NULL ? strtotime ($temp_project_data['project_expiration_date']) : 0;
				if(empty($project_expiration_timestamp) || $project_expiration_timestamp < time()){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to pdasboard
					$res = [
						'status' => 400,
						'location'=>VPATH . $this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}else{
				
					// update expiration time on user action
					$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
					$upate_data = [
						'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
						'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
					];
					$this->db->where('temp_project_id', $temp_project_id);
					$this->db->update('temp_projects', $upate_data);
				}
				
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
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$projects_temp_dir = PROJECT_TEMPORARY_DIR;
				$temp_dir = TEMP_DIR;
				$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
				$profile_folder     = $user[0]->profile_name;
				
				$temp_project_attachment_name = $this->input->post ('temp_project_attachment_name');
				$temp_project_attachment_id = $this->input->post ('temp_project_attachment_id');
				
				$this->db->select('*');
				$this->db->from('temp_projects_attachments');
				$this->db->where('id',$temp_project_attachment_id);
				$temp_project_attachment_result = $this->db->get();
				$temp_project_attachment_data = $temp_project_attachment_result->result_array();
				if(!empty($temp_project_attachment_data[0])){
					
					
					if($this->session->userdata ('user')){
						$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id .DIRECTORY_SEPARATOR .$temp_project_attachment_data[0]['temp_project_attachment_name'];
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
							$this->ftp->delete_file($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR.$temp_project_attachment_data[0]['temp_project_attachment_name']);
							$this->db->delete('temp_projects_attachments', array('id' => $temp_project_attachment_id));
						}
						
						$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id .DIRECTORY_SEPARATOR .$temp_project_attachment_name;
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
						
							$this->ftp->delete_file($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR.$temp_project_attachment_name);
							$this->db->delete('temp_projects_attachments', array('id' => $temp_project_attachment_id));
						}
						
						
						$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$temp_project_attachment_data[0]['temp_project_attachment_name'];
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
							$this->ftp->delete_file(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$temp_project_attachment_data[0]['temp_project_attachment_name']);
							$this->db->delete('temp_projects_attachments', array('id' => $temp_project_attachment_id));
						}
						
						
						$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$temp_project_attachment_name;
						
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
						
							$this->ftp->delete_file(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$temp_project_attachment_name);
							$this->db->delete('temp_projects_attachments', array('id' => $temp_project_attachment_id));
						}
						
						
						
						
					}else{
					
						
						$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$temp_project_attachment_data[0]['temp_project_attachment_name'];
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
							$this->ftp->delete_file(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$temp_project_attachment_data[0]['temp_project_attachment_name']);
							$this->db->delete('temp_projects_attachments', array('id' => $temp_project_attachment_id));
						}
						
						
						$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$temp_project_attachment_name;
						
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
						
							$this->ftp->delete_file(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$temp_project_attachment_name);
							$this->db->delete('temp_projects_attachments', array('id' => $temp_project_attachment_id));
						}
					
					}
					
					
					
					$no_project_attachment_uploaded_user_temp = $this->db
					->select ('id')
					->from ('temp_projects_attachments')
					->where ('temp_project_id', $temp_project_id)
					->get ()->num_rows ();// check the number of attachment of user into database
					$msg['status'] = 'SUCCESS';
					$msg['message'] = '';
					$upload_button_status = '0';
					if($no_project_attachment_uploaded_user_temp < $this->config->item('maximum_allowed_number_of_attachments_on_projects')){
						$upload_button_status = '1';
					}
					$msg['upload_button_status'] = $upload_button_status;
					
				
				
				}else{
				
					if($this->session->userdata ('user')){
						$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id .DIRECTORY_SEPARATOR .$temp_project_attachment_name;
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
						
							$this->ftp->delete_file($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR.$temp_project_attachment_name);
						}
					}else{
					
						$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$temp_project_attachment_name;
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
						
							$this->ftp->delete_file(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$temp_project_attachment_name);
						}
					}
					$no_project_attachment_uploaded_user_temp = $this->db
					->select ('id')
					->from ('temp_projects_attachments')
					->where ('temp_project_id', $temp_project_id)
					->get ()->num_rows ();// check the number of attachment of user into database
					$msg['status'] = 'SUCCESS';
					$msg['message'] = '';
					$upload_button_status = '0';
					if($no_project_attachment_uploaded_user_temp < $this->config->item('maximum_allowed_number_of_attachments_on_projects')){
						$upload_button_status = '1';
					}
					$msg['upload_button_status'] = $upload_button_status;
				}
				// update temp projects expiration time
				$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
				$update_data = [
					'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
					'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
				];
				$this->db->where('temp_project_id', $temp_project_id);
				$this->db->update('temp_projects', $update_data);
				$this->ftp->close();
				echo json_encode ($msg);
			/* }else{
			
				$this->db->where('temp_project_id', $this->input->post ('temp_project_id'));
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				
				if(!empty($temp_project_data)){
					$this->Post_project_model->delete_temp_project($this->input->post ('temp_project_id'));// Delete the temporary project with complete files,data and redirect to dasboard
				}	
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			
			} */
			
        }else{
			show_custom_404_page(); //show custom 404 page
		}
		
    }
	
	/**
	This function is used to remove the temporary attachment of projects.
	*/
	public function delete_all_project_attachment_temp ()
    {	
		if($this->input->is_ajax_request ()){
			if(!$this->input->post ('temp_project_id')){
			
				show_custom_404_page();
				return;
			
			}
			$temp_project_id = $this->input->post ('temp_project_id');
			/* if (check_session_validity())
			{  */
				$user = $this->session->userdata ('user');
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				
				if(empty($temp_project_data)) { // if project not exists it will redirect to dasboard page
					$msg = [
						'status' => 400,
						'location'=>VPATH.$this->config->item('dashboard_page_url')
					];
					echo json_encode($msg);
					die;
				}
				$project_expiration_timestamp = $temp_project_data['project_expiration_date']!= NULL ? strtotime ($temp_project_data['project_expiration_date']) : 0;
				if(empty($project_expiration_timestamp) || $project_expiration_timestamp < time()){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
					$msg = [
						'status' => 400,
						'location'=>VPATH . $this->config->item('dashboard_page_url')
					];
					echo json_encode($msg);
					die;
				}else{
				
					// update expiration time on user action
					$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
					$upate_data = [
						'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
						'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
					];
					$this->db->where('temp_project_id', $temp_project_id);
					$this->db->update('temp_projects', $upate_data);
				}
				
				
				
				$this->db->select('*');
				$this->db->from('temp_projects_attachments');
				$this->db->where('temp_project_id',$temp_project_id);
				$temp_project_attachment_result = $this->db->get();
				$temp_project_attachment_data = $temp_project_attachment_result->result_array();
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$temp_dir 	= TEMP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$projects_temp_dir = PROJECT_TEMPORARY_DIR;
				
				$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
				
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] 	= FTP_PORT;
				$config['debug']    = TRUE;
				$this->ftp->connect($config); 
				/* if(!empty($temp_project_attachment_data)){
					
					######## connectivity of remote server start#########
					
					######## connectivity of remote server end #######
					foreach($temp_project_attachment_data as $key=>$value){
					
						if ($this->session->userdata ('user'))
						{ 
							$profile_folder     = $user[0]->profile_name;
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$value['temp_project_id'].DIRECTORY_SEPARATOR.$value['temp_project_attachment_name'];
							$file_size = $this->ftp->get_filesize($source_path);
							
							if($file_size != '-1')
							{
								$this->ftp->delete_file($source_path);
							}
						}
						
						$source_path = DIRECTORY_SEPARATOR .$temp_dir.substr($projects_ftp_dir,1).$logged_off_users_temporary_projects_attachments_dir.$value['temp_project_id'].DIRECTORY_SEPARATOR .$value['temp_project_attachment_name'];
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
							$this->ftp->delete_file($source_path);
						}
						
						
						$this->db->delete('temp_projects_attachments', array('id' => $value['id'])); 
					}
					$this->ftp->close();
					
				
				}else{
					if($this->session->userdata ('user'))
					{ 
						$profile_folder     = $user[0]->profile_name;
						$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id;
					
						if(!empty($this->ftp->check_ftp_directory_exist($source_path))){
							$this->ftp->delete_dir($source_path);// delete project directory 
						}
					}
					
					
					
					if(!empty($this->ftp->check_ftp_directory_exist(DIRECTORY_SEPARATOR .$temp_dir.substr($projects_ftp_dir,1).$logged_off_users_temporary_projects_attachments_dir.$temp_project_id))) {
						$this->ftp->delete_dir(DIRECTORY_SEPARATOR .$temp_dir.substr($projects_ftp_dir,1).$logged_off_users_temporary_projects_attachments_dir.$temp_project_id);
					}
					
					
					$this->ftp->close();
				} */
				$this->db->delete('temp_projects_attachments', array('temp_project_id' => $temp_project_id));
				if($this->session->userdata ('user'))
				{ 
					$profile_folder     = $user[0]->profile_name;
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id;
				
					if(!empty($this->ftp->check_ftp_directory_exist($source_path))){
						$this->ftp->delete_dir($source_path);// delete project directory 
					}
				}	
				if(!empty($this->ftp->check_ftp_directory_exist(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id))) {
					$this->ftp->delete_dir(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id);
					
				}
				//$this->db->delete('temp_projects_attachments', array('id' => $value['id']));
				$this->ftp->close();
				if($this->session->userdata ('user'))
				{
					$msg['status'] = 'SUCCESS';
					$msg['message'] = '';
				}else{
					$msg['status'] = 201;
					$msg['message'] = '';
				}
				
			/* }else{
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				
				if(!empty($temp_project_data)){
					
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
				}	
				$msg['status'] = 201;
				$msg['location'] = '';
			}  */
			echo json_encode ($msg);
		}else{
		
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	/**
	This function is user the reset the the project entiries from database
	*/
	public function reset_project_data ()
    {
        if ( $this->input->is_ajax_request ())
        {
			if(!$this->input->post ('temp_project_id')){
			
				show_custom_404_page(); //show custom 404 page
			}
            $temp_project_id = $this->input->post ('temp_project_id');
			/* if(check_session_validity()){ */ // check session exists or not if exist then it will update user session
			
			
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				
				if(empty($temp_project_data)) { // if project not exists it will redirect to dasboard page
					$res = [
						'status' => 400,
						'location'=>VPATH.$this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}
				
				$project_expiration_timestamp = $temp_project_data['project_expiration_date']!= NULL ? strtotime ($temp_project_data['project_expiration_date']) : 0;
				if(empty($project_expiration_timestamp) || $project_expiration_timestamp < time()){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
					$res = [
						'status' => 400,
						'location'=>VPATH . $this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}else{
				
					// update expiration time on user action
					$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
					$upate_data = [
						'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
						'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
					];
					$this->db->where('temp_project_id', $temp_project_id);
					$this->db->update('temp_projects', $upate_data);
				}
			
			
				/* $this->db->select('*');
				$this->db->from('temp_projects_attachments');
				$this->db->where('temp_project_id',$temp_project_id);
				$temp_project_attachment_result = $this->db->get();
				$temp_project_attachment_data = $temp_project_attachment_result->result_array(); */
				
				//if(!empty($temp_project_attachment_data)){
					
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
					$temp_dir 	= TEMP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$projects_temp_dir = PROJECT_TEMPORARY_DIR;
					$temp_dir = TEMP_DIR;
					$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
					/* $user = $this->session->userdata ('user');
					$profile_folder     = $user[0]->profile_name;
					foreach($temp_project_attachment_data as $key=>$value){
						
						$this->ftp->delete_file($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$value['temp_project_id'].DIRECTORY_SEPARATOR.$value['temp_project_attachment_name']);
						//$this->ftp->delete_file($temp_dir.$value['temp_project_attachment_name']);
						$this->db->delete('temp_projects_attachments', array('id' => $value['id'])); 
					} */
					
					
					if($this->session->userdata('user')){
						$user = $this->session->userdata ('user');
						$profile_folder     = $user[0]->profile_name;
						$users_ftp_dir 	= USERS_FTP_DIR; 
						$profile_folder     = $user_detail['profile_name'];
						if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id)))
						{
							$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id);// delete project directory 
						}
					}
					if(!empty($this->ftp->check_ftp_directory_exist(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id)))
					{
						$this->ftp->delete_dir(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id);// delete project directory 
					}
					
					
					$this->ftp->close();
				//}
				$this->db->delete('temp_projects_attachments', array('temp_project_id' => $temp_project_id));
				$this->db->delete('temp_projects_tags', array('temp_project_id' => $temp_project_id));
				
				$temp_project_id = $this->input->post('temp_project_id');
				$temp_project_data = array (
					'project_title'=>'',
					'project_description'=>'',
					'locality_id'=>0,
					'county_id'=>0,
					'postal_code_id'=>0,
					'escrow_payment_method'=>'N',
					'offline_payment_method'=>'N',
					'confidential_dropdown_option_selected'=>'N',
					'not_sure_dropdown_option_selected'=>'N',
					'project_type'=>'fixed',
					'min_budget'=>'',
					'max_budget'=>'',
					'featured'=>'N',
					'urgent'=>'N',
					'sealed'=>'N',
					'hidden'=>'N',
				);
			   $this->db->where ('temp_project_id', $temp_project_id);
			   $this->db->update ('temp_projects', $temp_project_data);
			/* }else{
			
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				if(!empty($temp_project_data)){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
				}	
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}  */  
			if($this->session->userdata ('user'))
			{
				$msg['status'] = 'SUCCESS';
				$msg['message'] = '';
			}else{
				$msg['status'] = 201;
				$msg['message'] = '';
			}
			
        }else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	
	/**
	This function is used to post project the project and save the project information in project temporary table.
	*/
	public function post_temp_project () {
        if ( $this->input->is_ajax_request () && $this->input->post ())
        {
            $post_data = $this->input->post ();
            $this->Post_project_model->post_temp_project ($post_data);
			
        }else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	/**
	This function is used to for edit temporary project.
	*/
	public function edit_temp_project () {
        if ( $this->input->is_ajax_request ())
        {
			if(!$this->input->post ('temp_project_id')){
			
				show_404();
			}
            $temp_project_id = $this->input->post ('temp_project_id');
			//if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$check_project_categories_available_or_not = $this->db->where(['status'=>'Y','parent_id'=>0])->from('categories_projects')->count_all_results();
				if($check_project_categories_available_or_not == 0){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('projects_categories_disabled_or_deleted_project_posting_disabled_message')]);
					die;
				}
				
				$msg['status'] = 200;
				echo json_encode($msg);
				die;
				
			/* }else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			
			} */
			
        }else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	
	/**
	This function is used to show the preview of project and fetch the information from temporary tables of project .
	*/
	public function temporary_project_preview(){
		
		
		if(empty($this->input->get('id'))){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		
		$temp_project_id = $this->input->get('id');
		/* if(check_session_validity()){  */
			$this->session->set_userdata ('check_redirection_edit_preview', 1);// set the redirection for edit preview page
			
			$user = $this->session->userdata('user');
			/* $count_temp_project = $this->db // count the number of record in temp_projects table
					->select ('id')
					->from ('temp_projects')
					->where('temp_project_id',$temp_project_id)
					//->where('temp_project_owner_id',$user[0]->user_id)
					->get ()->num_rows (); */
			/* if($this->session->userdata('user')){		
				$check_temp_project = $this->db->where(['temp_project_id' => $temp_project_id,'temp_project_owner_id'=>$user[0]->user_id])->from('temp_projects')->count_all_results();
			}else{ */
			$check_temp_project = $this->db->where(['temp_project_id' => $temp_project_id])->from('temp_projects')->count_all_results();
			
			/* }	 */
			/* echo $check_temp_project;
			die; */
			if($check_temp_project > 0){
			
				if(! $this->session->userdata('check_redirection_preview')){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data etc
					$url = VPATH . $this->config->item('dashboard_page_url');
					redirect ($url);
				}
			
				if(!isset($_SERVER['HTTP_REFERER'])){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data etc
					$url = VPATH . $this->config->item('dashboard_page_url');
					redirect ($url);
				}
				// Update temp project expiration time
				
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				/* if(!empty($temp_project_data) && $temp_project_data['temp_project_owner_id'] != $user[0]->user_id){
					$this->Post_project_model->delete_temp_project($temp_project_id);
					redirect (VPATH . $this->config->item('dashboard_page_url'));
				} */
				if($this->session->userdata('user')){	
					if($temp_project_data['temp_project_owner_id'] == 0){
					
						$this->db->update('temp_projects', ['temp_project_owner_id'=>$user[0]->user_id], ['temp_project_id' => $temp_project_id]);
					
					}
				}else{
					if($temp_project_data['temp_project_owner_id'] != 0){
						redirect (VPATH);
					}
				
				}
				
				$project_expiration_timestamp = $temp_project_data['project_expiration_date']!= NULL ? strtotime ($temp_project_data['project_expiration_date']) : 0;
				if(empty($project_expiration_timestamp) || $project_expiration_timestamp < time()){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete redirect 
					redirect (VPATH . $this->config->item('dashboard_page_url'));
				}else{
				
					// update expiration time on user action
					$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
					$upate_data = [
						'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
						'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
					];
					$this->db->where('temp_project_id', $temp_project_id);
					$this->db->update('temp_projects', $upate_data);
				}
				
				########## fetch the project data from temporary tables ###
				$this->db->select('temp_projects.*,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
				$this->db->from('temp_projects');
				$this->db->join('counties', 'counties.id = temp_projects.county_id', 'left');
				$this->db->join('localities', 'localities.id = temp_projects.locality_id', 'left');
				$this->db->join('postal_codes', 'postal_codes.id = temp_projects.postal_code_id', 'left');
				$this->db->where('temp_projects.temp_project_id',$temp_project_id);
				//$this->db->where('temp_projects.temp_project_owner_id',$user[0]->user_id);
				$project_result = $this->db->get();
				$project_data = $project_result->row_array();
				$data['project_data'] = $project_data;
				if($this->session->userdata('user')){
				
					if($project_data['temp_project_owner_id'] != 0 && $project_data['temp_project_owner_id'] !=$user[0]->user_id ){
					
						redirect (VPATH . $this->config->item('dashboard_page_url'));
					}
				
					// move the attachment from logged off version to loggedin version start'
					
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->ftp->connect($config); 
					######## connectivity of remote server end #######
					$temp_dir = TEMP_DIR;
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$projects_temp_dir = PROJECT_TEMPORARY_DIR;
					$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
					$this->db->select('temp_project_attachment_name');
					$this->db->from('temp_projects_attachments');
					$this->db->where('temp_project_id',$temp_project_id);
					$this->db->order_by('id',"asc");
					$temp_project_attachment_result = $this->db->get();
					$temp_project_attachment_data = $temp_project_attachment_result->result_array();
					if(!empty($temp_project_attachment_data)){
					
						$profile_folder = $user[0]->profile_name;
						$temp_dir = TEMP_DIR;
						$users_ftp_dir 	= USERS_FTP_DIR; 
						$projects_ftp_dir = PROJECTS_FTP_DIR;
						$projects_temp_dir = PROJECT_TEMPORARY_DIR;
						$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
						
						$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
						$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir, 0777);// create temporary directory in projects folder
						$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR , 0777); // create the directory by using temporary project id
						
						foreach($temp_project_attachment_data as $attachment_key => $attachment_value){
									
							if(!empty($attachment_value['temp_project_attachment_name'])){
								
								$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
								$file_size = $this->ftp->get_filesize($source_path);
								if($file_size != '-1'){
									$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
									
									$this->ftp->move($source_path, $destination_path);
									
								}
							}
						}
						
						// remov entry from open bidding table
						if(!empty($this->ftp->check_ftp_directory_exist(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id))) {
							$this->ftp->delete_dir(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id);
						}
					}
				
				}
				// move the attachment from logged off version to loggedin version end
				
				
				
				########## fetch the login user data ###
				$this->db->select('account_type,is_authorized_physical_person,first_name,last_name,company_name,account_validation_date');
				$this->db->from('users');
				$this->db->where('user_id',$project_data['temp_project_owner_id']);
				$user_result = $this->db->get();
				$user_data = $user_result->result_array();
				//$data['user_data'] = $user_data;
				########## fetch the temp project tags ###
				$this->db->select('temp_projects_tags.temp_project_tag_name');
				$this->db->from('temp_projects_tags');
				$this->db->where('temp_project_id',$temp_project_id);
				$this->db->order_by('id',"asc");
				$project_tag_result = $this->db->get();
				$project_tag_data = $project_tag_result->result_array();
				$data['project_tag_data'] = $project_tag_data;
				
				########## fetch the temp project attachments ###
				$profile_name = '';
				if($this->session->userdata ('user')){
					$profile_name = $user[0]->profile_name;
				}
				$data['project_attachment_data'] = $this->get_tempoary_project_attachments($temp_project_id,$profile_name);
				
				########## fetch the temp categories and make the dynamic array end ###
				
				########## fetch the temp categories and make the dynamic array start ###
				$this->db->select('category_project.name as category_name,parent_category_project.name as parent_category_name');
				$this->db->from('temp_projects_categories_listing_tracking as category_tracking');
				$this->db->join('categories_projects as category_project', 'category_project.id = category_tracking.temp_project_category_id', 'left');
				$this->db->join('categories_projects as parent_category_project', 'parent_category_project.id = category_tracking.temp_project_parent_category_id', 'left');
				$this->db->where('category_tracking.temp_project_id',$temp_project_id);
				$this->db->order_by('category_project.name',"asc");
				$category_result = $this->db->get();
				$category_data = $category_result->result_array();
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
				
				$data['category_data'] = $category_data;
				########## fetch draft project information of logged in user ##########################
				$data['draft_cnt'] = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
				$data['fulltime_draft_cnt'] = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);
				########## fetch open bidding project information of logged in user ##########################
				$data['open_bidding_cnt'] = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
				$data['fulltime_open_bidding_cnt'] = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
				$this->db->select('current_membership_plan_id');
				$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
				$data['user_detail'] = $user_detail;
				########## fetch the temp categories and make the dynamic array end ###
				$data['temp_project_id'] = $temp_project_id;
				$data['current_page'] = 'temporary_project_preview';
				
				$temporary_project_preview_title_meta_tag = strip_tags($project_data['project_title']);
				$temporary_project_preview_title_meta_tag = get_correct_string_based_on_limit($temporary_project_preview_title_meta_tag, $this->config->item('project_title_meta_tag_character_limit'));
				$temporary_project_preview_description_meta_tag = strip_tags($project_data['project_description']);
				$temporary_project_preview_description_meta_tag = get_correct_string_based_on_limit($temporary_project_preview_description_meta_tag, $this->config->item('project_description_meta_description_character_limit'));
				
				$data['meta_tag'] = '<title>' . $temporary_project_preview_title_meta_tag . '</title><meta name="description" content="' . $temporary_project_preview_description_meta_tag . '"/>';
				$this->session->unset_userdata ('check_redirection_preview');
				$lay = array();
				$this->layout->view ('post_project/temporary_project_preview', $lay, $data, 'normal');
			}else{
				redirect (VPATH . $this->config->item('dashboard_page_url'));
			}
		/* }else{
			$this->db->where('temp_project_id', $temp_project_id);
			$temp_project_data = $this->db->get('temp_projects')->row_array();
			if(!empty($temp_project_data)){
				$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
			}
			redirect (VPATH . $this->config->item('dashboard_page_url'));
		} */
	}
	
	/**
	This function is used to edit the temporary project preview.
	*/
    public function edit_temporary_project_preview()
    {
		
		if(empty($this->input->get('id'))){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		$temp_project_id = $this->input->get('id');
	/* 	if(check_session_validity()){  */
			$user = $this->session->userdata('user');
			$this->session->set_userdata ('check_redirection_preview', 1);// set the redirection for preview page
		/* 	$check_project_exists = $this->db // count the number of record in temp_projects table
				->select ('id')
				->from ('temp_projects')
				->where('temp_project_id',$this->input->get('id'))
				//->where('temp_project_owner_id', $user[0]->user_id)
				->get ()->num_rows (); */
				
			/* if($this->session->userdata('user')){		
				$check_temp_project = $this->db->where(['temp_project_id' => $temp_project_id,'temp_project_owner_id'=>$user[0]->user_id])->from('temp_projects')->count_all_results();
			}else{ */
				/* $check_temp_project = $this->db->where(['temp_project_id' => $temp_project_id])->from('temp_projects')->count_all_results(); */
			
			/* }	 */
				
				
				
			/* if($check_temp_project == 0){	
				redirect (VPATH . $this->config->item('dashboard_page_url'));
			} */
			if(!empty($temp_project_id)){
			
				$this->db->select('*');
				$this->db->from('temp_projects');
				$this->db->where('temp_project_id',$temp_project_id);
				//$this->db->where('temp_project_owner_id',$user[0]->user_id);
				$temp_project_result = $this->db->get();
				$temp_project_data = $temp_project_result->row_array();
				
				if(empty($temp_project_data)){
					
					redirect(VPATH.$this->config->item('dashboard_page_url'));
				}
				
				if($this->session->userdata('user')){	
					if($temp_project_data['temp_project_owner_id'] == 0){
					
						$this->db->update('temp_projects', ['temp_project_owner_id'=>$user[0]->user_id], ['temp_project_id' => $temp_project_id]);
					
					}
				}else{
					if($temp_project_data['temp_project_owner_id'] != 0){
						redirect (VPATH);
					
					}
				
				}
				
				/* if(!empty($temp_project_data) && $temp_project_data['temp_project_owner_id'] != $user[0]->user_id){
					$this->Post_project_model->delete_temp_project($temp_project_id);
					redirect (VPATH . $this->config->item('dashboard_page_url'));
				} */
				
				if(!isset($_SERVER['HTTP_REFERER'])){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data etc
					$url = VPATH . $this->config->item('dashboard_page_url');
					redirect ($url);
				}
				
				if(! $this->session->userdata('check_redirection_edit_preview')){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data etc
					$url = VPATH . $this->config->item('dashboard_page_url');
					redirect ($url);
				}
				//$this->Projects_model->check_update_invalid_combination_project_location('temp_projects','temp_project_id',$temp_project_id); // check valid combination of locality_id,county_id,postal_code_id If the combination is not valid it will update locality_id,county_id,postal_code_id  to 0.
			
				######## fetch project detail from temp_projects table ########
				
				$project_expiration_timestamp = $temp_project_data['project_expiration_date']!= NULL ? strtotime ($temp_project_data['project_expiration_date']) : 0;
				if(empty($project_expiration_timestamp) || $project_expiration_timestamp < time()){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete redirect 
					redirect (VPATH . $this->config->item('dashboard_page_url'));
					
				}else{
				
					// update expiration time on user action
					$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
					$upate_data = [
						'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
						'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
					];
					$this->db->where('temp_project_id', $temp_project_id);
					$this->db->update('temp_projects', $upate_data);
				}
				
				$data['current_page'] = 'edit_temporary_project_preview';
			
				$profile_folder = '';
				if($this->session->userdata('user')){
				
						
				$check_temp_project = $this->db->where(['temp_project_id' => $temp_project_id,'temp_project_owner_id'=>$user[0]->user_id])->from('temp_projects')->count_all_results();
				if($check_temp_project ==0){
				
					redirect (VPATH . $this->config->item('dashboard_page_url'));
				}
				
				// move the project attachments of logged off version to dedicated user folder start 
						
					//$user = $this->session->userdata('user');
					$profile_folder     = $user[0]->profile_name;
				
					
					######## connectivity of remote server start#########
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->ftp->connect($config); 
					######## connectivity of remote server end #######
					$temp_dir = TEMP_DIR;
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$projects_temp_dir = PROJECT_TEMPORARY_DIR;
					$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
					
					$this->ftp->mkdir($users_ftp_dir, 0777);
					$this->ftp->mkdir($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR, 0777);
					$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
					$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir, 0777);// create temporary directory in projects folder
					$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR , 0777); // create the directory by using temporary project id
					
					$this->db->select('temp_project_attachment_name');
					$this->db->from('temp_projects_attachments');
					$this->db->where('temp_project_id',$temp_project_id);
					$this->db->order_by('id',"asc");
					$temp_project_attachment_result = $this->db->get();
					$temp_project_attachment_data = $temp_project_attachment_result->result_array();
					
					if(!empty($temp_project_attachment_data)){
						
						foreach($temp_project_attachment_data as $attachment_key => $attachment_value){
								
							if(!empty($attachment_value['temp_project_attachment_name'])){
								
								$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
								$file_size = $this->ftp->get_filesize($source_path);
								if($file_size != '-1'){
									$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
									$this->ftp->move($source_path, $destination_path);
									
								}
							}
						}
						
					}
					// remov entry from open bidding table
					if(!empty($this->ftp->check_ftp_directory_exist(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id))) {
						$this->ftp->delete_dir(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id);
					}
					
					// move the project attachments of logged off version to dedicated user folder end

				}
				
				
				
				
				
				$count_project_parent_category = $this->db
				->select ('id')
				->from ('categories_projects')
				->where ('parent_id', 0)
				->where ('status', 'Y')
				->get ()->num_rows ();
				
				$data['count_available_project_parent_category_count'] = 	$count_project_parent_category;
						
				$data['project_parent_categories'] = $this->Post_project_model->get_project_parent_categories();
				$data['temp_project_id'] = $temp_project_id;
				
				$data['temp_project_data'] = $temp_project_data;
				########## fetch the temp categories and make the dynamic array start ###
				$this->db->select('*');
				$this->db->from('temp_projects_categories_listing_tracking');
				$this->db->where('temp_project_id',$temp_project_id);
				$this->db->order_by('id',"asc");
				$category_result = $this->db->get();
				$temp_category_data = $category_result->result_array();
				$data['temp_category_data'] = $temp_category_data;
				########## fetch the temp categories and make the dynamic array end ###
				/* $profile_name = '';
				if($this->session->userdata('user')){
					$profile_name = $user[0]->profile_name;
				} */
				########## fetch the temp project attachments ###
				$data['project_attachment_array'] = $this->get_tempoary_project_attachments($temp_project_id,$profile_folder);
				
				########## fetch the temp project tags ###
				$this->db->select('*');
				$this->db->from('temp_projects_tags');
				$this->db->where('temp_project_id',$temp_project_id);
				$this->db->order_by('id',"asc");
				$temp_project_tag_result = $this->db->get();
				$temp_project_tag_data = $temp_project_tag_result->result_array();
				
				$data['temp_project_tag_data'] = $temp_project_tag_data;
				
				########## fetch the temp project tags end ###
				$data['fixed_budget_projects_budget_range'] = $this->Post_project_model->get_fixed_budget_projects_budget_range();// drop down options for fixed budget project budget range
				
				$data['hourly_rate_based_budget_projects_budget_range'] = $this->Post_project_model->get_hourly_rate_based_projects_budget_range();// drop down options for hourly rate based project budget range
				
				$data['fulltime_project_salary_range'] = $this->Post_project_model->get_fulltime_projects_salaries_range();// drop down options for fulltime project salary range
				
				$data['counties'] = $this->Dashboard_model->get_counties(); // drop down options of counties
				
				
				$data['localities'] = $this->Dashboard_model->get_localities_selected_county($temp_project_data['county_id']);// drop down options of localities
				
				/* echo "<pre>";
				print_r($data['localities']);
				die; */
				
				
				$data['postal_codes'] = $this->Post_project_model->get_project_post_codes($temp_project_data['locality_id']);// drop down options of localities
				if($this->session->userdata('user')){
					$data['draft_cnt'] = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
					$data['fulltime_draft_cnt'] = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);

					########## fetch open bidding project information of logged in user ##########################
					$data['open_bidding_cnt'] = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
					$data['fulltime_open_bidding_cnt'] = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
					
					################## get the user_details #################
					$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
					
					
					$data['user_detail'] = $user_detail;
					$user_membership_plan_details = $this->db->get_where('membership_plans', array('id' => $user_detail['current_membership_plan_id']))->row();
					
					
					$data['user_membership_plan_details'] = $user_membership_plan_details;
					$data['count_user_featured_membership_included_upgrades_monthly'] = $this->Post_project_model->count_user_featured_membership_included_upgrades_monthly($user[0]->user_id);
					$data['count_user_urgent_membership_included_upgrades_monthly'] = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user[0]->user_id);
					$data['count_user_sealed_membership_included_upgrades_monthly'] = $this->Post_project_model->count_user_sealed_membership_included_upgrades_monthly($user[0]->user_id);
					$data['count_user_hidden_membership_included_upgrades_monthly'] = $this->Post_project_model->count_user_hidden_membership_included_upgrades_monthly($user[0]->user_id);
					###############################################
					
				}
				
				
				
				
				$count_project_categories = $this->db // count the number of categories of temporary project
					->select ('id')
					->from ('temp_projects_categories_listing_tracking')
					->where('temp_project_id',$temp_project_id)
					->get ()->num_rows ();
					
				$count_project_attachments = $this->db // count the number of attachment of temporary project
					->select ('id')
					->from ('temp_projects_attachments')
					->where('temp_project_id',$temp_project_id)
					->get ()->num_rows ();
					
				$count_project_tags = $this->db // count the number of tag of temporary project
					->select ('id')
					->from ('temp_projects_tags')
					->where('temp_project_id',$temp_project_id)
					->get ()->num_rows ();	
					
				$count_project_postal_codes = $this->db
				->select ('id')
				->from ('postal_codes')
				->where ('locality_id', $temp_project_data['locality_id'])
				->get ()->num_rows ();

				$data['count_project_categories'] = $count_project_categories;
				$data['count_project_attachments'] = $count_project_attachments;
				$data['count_project_tags'] = $count_project_tags;
				$data['count_project_postal_codes'] = $count_project_postal_codes;
				########## meta information of post project page ##########
				$post_project_page_title_meta_tag = $this->config->item('post_project_page_title_meta_tag');
				$post_project_page_description_meta_tag = $this->config->item('post_project_page_description_meta_tag');
				$this->session->unset_userdata ('check_redirection_edit_preview');
				$data['meta_tag'] = '<title>' . $post_project_page_title_meta_tag . '</title><meta name="description" content="' . $post_project_page_description_meta_tag . '"/>';
				
				$this->layout->view('edit_temporary_project_preview', '', $data, 'normal');
			}else{
				redirect (VPATH . $this->config->item('dashboard_page_url'));
			}
		/* }else{
			$this->db->where('temp_project_id', $temp_project_id);
			$temp_project_data = $this->db->get('temp_projects')->row_array();
			if(!empty($temp_project_data)){
				$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dashboard
			}
			redirect (VPATH);
		
		} */
    }
	
	/**
	* This function is used to move the project data from temporary table to projects_draft and other tables.
	*/
	public function post_draft_project ()
    {
        if ($this->input->is_ajax_request ())
        {
			if(!$this->input->post ()){
				show_custom_404_page(); //show custom 404 page
			}
			
			$temp_project_id = $this->input->post ('temp_project_id');
			$page_type = $this->input->post ('page_type');
			
			// if session is not exist then login popup will show start//
			if(!$this->session->userdata('user')){
			
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				if($temp_project_data['temp_project_owner_id'] != 0){
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
				
				}
			
			
				if($page_type == 'form'){
					$post_data = $this->input->post ();
					$response = $this->Post_project_model->post_project_validation($post_data);
					if($response['status'] == 'SUCCESS'){
						$p_type = 'post_draft_project';
						$page_id = $temp_project_id;
						if(!$this->session->userdata('user')){
							echo json_encode(['status' => 201,'login_status'=>'0','page_type'=>$p_type,'page_id'=>$page_id,'location'=>'','data'=>$this->load->view('signin/signin_popup',array('page_id'=>$page_id,'page_type'=>$p_type), true)]);
							die;
						}
					
					}else{
						echo json_encode ($response);
						die;
					}
				}else if($page_type == 'preview'){
					$p_type = 'post_draft_project';
					$page_id = $temp_project_id;
					if(!$this->session->userdata('user')){
						echo json_encode(['status' => 201,'login_status'=>'0','page_type'=>$p_type,'page_id'=>$page_id,'location'=>'','data'=>$this->load->view('signin/signin_popup',array('page_id'=>$page_id,'page_type'=>$p_type), true)]);
						die;
					}
				
				}
				
			}
			// if session is not exist then login popup will show end//
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata('user');
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				
				if(empty($temp_project_data)) { // if project not exists it will redirect to dashboard page
					$res = [
						'status' => 400,
						'location'=>VPATH.$this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}
				if(!empty($temp_project_data)){
				
					if($temp_project_data['temp_project_owner_id'] == 0){
					
						$this->db->update('temp_projects', ['temp_project_owner_id'=>$user[0]->user_id], ['temp_project_id' => $temp_project_id]);
						$temp_project_data['temp_project_owner_id'] = $user[0]->user_id;
					
					}
				}
				
				if(!empty($temp_project_data) && $temp_project_data['temp_project_owner_id'] != $user[0]->user_id){
					$this->Post_project_model->delete_temp_project($temp_project_id);
					$res = [
						'status' => 400,
						'location'=>VPATH.$this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}
				$project_expiration_timestamp = $temp_project_data['project_expiration_date']!= NULL ? strtotime ($temp_project_data['project_expiration_date']) : 0;
				if(empty($project_expiration_timestamp) || $project_expiration_timestamp < time()){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
					$res = [
						'status' => 400,
						'location'=>VPATH . $this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}else{
				
					// update expiration time on user action
					$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
					$upate_data = [
						'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
						'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
					];
					$this->db->where('temp_project_id', $temp_project_id);
					$this->db->update('temp_projects', $upate_data);
				}
				
				
				$user = $this->session->userdata('user');
				$page_type_array = array('preview','form');
				if (in_array($page_type, $page_type_array))
				{
					######### apply validation for draft ######
					
					$total_user_draft_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id])->where_in('project_type', ['fixed', 'hourly'])->from('projects_draft')->count_all_results();
					$total_user_draft_fulltime_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_type' => 'fulltime'])->from('projects_draft')->count_all_results();
					
					
					$this->db->select('current_membership_plan_id');
					$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
					
					
					if($user_detail['current_membership_plan_id'] == 1){
						$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
						$user_memebership_max_number_of_open_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');

						$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
						$user_memebership_max_number_of_open_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					if($user_detail['current_membership_plan_id'] == 4){
						$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
						$user_memebership_max_number_of_open_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');

						$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
						$user_memebership_max_number_of_open_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					// standard project availability
					$standard_time_arr = explode(":", $this->config->item('standard_project_availability'));
					$standard_check_valid_arr = array_map('getInt', $standard_time_arr); 
					$standard_valid_time_arr = array_filter($standard_check_valid_arr);
					
					
					
					$count_draft_project = $this->db // count the number of record in temp_projects table
					->select ('id')
					->from ('projects_draft')
					->where('project_id',$temp_project_id)
					->where('project_owner_id',$user[0]->user_id)
					->get ()->num_rows ();
					if($count_draft_project == 0){
						
						if($page_type == 'preview'){ // this block will execute when user click "save as draft" button on 	temporary project preview page
						
							if($temp_project_data['project_type'] != 'fulltime'){ 
								if($user_memebership_max_number_of_draft_projects == '0' && ($user_memebership_max_number_of_open_projects == '0' || empty($standard_valid_time_arr))){
									if($user_detail['current_membership_plan_id'] == 1){
										$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_draft_project_project_posting_disabled_message');
									}
									if($user_detail['current_membership_plan_id'] == 4){
										$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_draft_project_project_posting_disabled_message');
									}
									echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
									die;
								} else if ($user_memebership_max_number_of_draft_projects == '0'){
									if($user_detail['current_membership_plan_id'] == 1){
										$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_project_as_draft_disabled_message');
									}
									if($user_detail['current_membership_plan_id'] == 4){
										$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_project_as_draft_disabled_message');
									}
									echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
									die;
								
								} elseif ($total_user_draft_project_count >= $user_memebership_max_number_of_draft_projects){
									if($user_detail['current_membership_plan_id'] == 1){
										$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_project_as_draft_slots_not_available_message');
									}
									if($user_detail['current_membership_plan_id'] == 4){
										$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_project_as_draft_slots_not_available_message');
									}
									echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
									die;
								}
							} else {
								if ($user_memebership_max_number_of_draft_fulltime_projects == '0'){
									if($user_detail['current_membership_plan_id'] == 1){
										$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_fulltime_project_as_draft_disabled_message');
									}
									if($user_detail['current_membership_plan_id'] == 4){
										$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_fulltime_project_as_draft_disabled_message');
									}
									echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
									die;
								
								} elseif ($total_user_draft_fulltime_project_count >= $user_memebership_max_number_of_draft_fulltime_projects){
									if($user_detail['current_membership_plan_id'] == 1){
										$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_fulltime_project_as_draft_slots_not_available_message');
									}
									if($user_detail['current_membership_plan_id'] == 4){
										$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_fulltime_project_as_draft_slots_not_available_message');
									}
									echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
									die;
								}
							}
							
							// move data from temp_projects table to projects_draft table
							$this->db->select('*');
							$this->db->from('temp_projects');
							$this->db->where('temp_projects.temp_project_id',$temp_project_id);
							$this->db->where('temp_projects.temp_project_owner_id',$user[0]->user_id);
							$temp_project_result = $this->db->get();
							$temp_project_data = $temp_project_result->result_array();
							
							$draft_project_data = array (
								'project_id'=>$temp_project_id,
								'project_owner_id'=>$user[0]->user_id,
								'project_save_date'=>date('Y-m-d H:i:s'),
								'project_title'=>trim($temp_project_data[0]['project_title']),
								'project_description'=>trim($temp_project_data[0]['project_description']),
								'locality_id'=>$temp_project_data[0]['locality_id'],
								'county_id'=>$temp_project_data[0]['county_id'],
								'postal_code_id'=>$temp_project_data[0]['postal_code_id'],
								'project_type'=>$temp_project_data[0]['project_type'],
								'min_budget'=>$temp_project_data[0]['min_budget'],
								'max_budget'=>$temp_project_data[0]['max_budget'],
								'confidential_dropdown_option_selected'=>$temp_project_data[0]['confidential_dropdown_option_selected'],
								'not_sure_dropdown_option_selected'=>$temp_project_data[0]['not_sure_dropdown_option_selected'],
								'escrow_payment_method'=>$temp_project_data[0]['escrow_payment_method'],
								'offline_payment_method'=>$temp_project_data[0]['offline_payment_method'],
								'featured'=>$temp_project_data[0]['featured'],
								'urgent'=>$temp_project_data[0]['urgent'],
								'sealed'=>$temp_project_data[0]['sealed'],
								'hidden'=>$temp_project_data[0]['hidden']
							   
							);
							$this->db->insert ('projects_draft', $draft_project_data);
							######### save use activity log message
							$project_title = trim($draft_project_data['project_title']);
							if($draft_project_data['project_type'] == 'fulltime'){
								$publish_project_log_message = $this->config->item('fulltime_post_project_save_as_draft_user_activity_log_displayed_message_sent_to_po');
							}else{
								$publish_project_log_message = $this->config->item('post_project_save_as_draft_user_activity_log_displayed_message_sent_to_po');
							}
							$publish_project_log_message = str_replace(array('{project_title}'),array(htmlspecialchars($project_title)),$publish_project_log_message);
							user_display_log($publish_project_log_message);
							// move data from temp_projects_tags table to draft_projects_tags table
							
							$this->db->select('temp_project_tag_name');
							$this->db->from('temp_projects_tags');
							$this->db->where('temp_project_id',$temp_project_id);
							$this->db->order_by('id',"asc");
							$temp_project_tag_result = $this->db->get();
							$temp_project_tag_data = $temp_project_tag_result->result_array();
							if(!empty($temp_project_tag_data)){
								foreach($temp_project_tag_data as $tag_key => $tag_value){
									$this->db->insert ('draft_projects_tags', array('project_id'=>$temp_project_id,'draft_project_tag_name'=> trim($tag_value['temp_project_tag_name'])));
								}
							
							}
							// move data from temp_projects_categories_listing_tracking table to draft_projects_categories_listing_tracking table
							$this->db->select('temp_project_category_id,temp_project_parent_category_id');
							$this->db->from('temp_projects_categories_listing_tracking');
							$this->db->where('temp_project_id',$temp_project_id);
							$this->db->order_by('id',"asc");
							$temp_project_category_result = $this->db->get();
							$temp_project_category_data = $temp_project_category_result->result_array();
							if(!empty($temp_project_category_data)){
								foreach($temp_project_category_data as $category_key => $category_value){
									$project_category_id = 0;
									$project_parent_category_id = 0;
									
									if(!empty($category_value['temp_project_parent_category_id'])){
									
										$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($category_value['temp_project_parent_category_id']);
				
										$check_project_child_category_exist = $this->Post_project_model->check_project_child_category_exist($category_value['temp_project_parent_category_id'],$category_value['temp_project_category_id']);
										if($check_project_parent_category_exist){
											if($check_project_child_category_exist){
												
												$project_category_id = $category_value['temp_project_category_id'];
												$project_parent_category_id = $category_value['temp_project_parent_category_id'];
											
											}else{
											
												$project_category_id =  $category_value['temp_project_parent_category_id'];
												$project_parent_category_id = 0;
												
											}
										}
									}else{
										$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($category_value['temp_project_category_id']);
										if($check_project_parent_category_exist){
										
											$project_category_id =  $category_value['temp_project_category_id'];
											$project_parent_category_id = 0;
										
										}
									
									}
									if(!empty($project_category_id) || !empty($project_parent_category_id)){
										$this->db->insert ('draft_projects_categories_listing_tracking', array('project_id'=>$temp_project_id,'draft_project_category_id'=> $category_value['temp_project_category_id'],' draft_project_parent_category_id'=> $category_value['temp_project_parent_category_id']));
										//categories from table temp_projects_categories_listing_tracking to draft_projects_categories_listing_tracking and apply check that category is valid or not
									}
								}
							
							}
							
							// move data from temp_projects_attachments table to draft_projects_attachments table
							$this->db->select('temp_project_attachment_name');
							$this->db->from('temp_projects_attachments');
							$this->db->where('temp_project_id',$temp_project_id);
							$this->db->order_by('id',"asc");
							$temp_project_attachment_result = $this->db->get();
							$temp_project_attachment_data = $temp_project_attachment_result->result_array();
							// connect ftp to move attachments
							$this->load->library('ftp');
							$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
							$config['ftp_username'] = FTP_USERNAME;
							$config['ftp_password'] = FTP_PASSWORD;
							$config['ftp_port'] 	= FTP_PORT;
							$config['debug']    = TRUE;
							$this->ftp->connect($config); 
							$temp_dir = TEMP_DIR;
							$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
							$users_ftp_dir 	= USERS_FTP_DIR; 
							$projects_ftp_dir = PROJECTS_FTP_DIR;
							$projects_temp_dir = PROJECT_TEMPORARY_DIR;
							$project_draft_dir = PROJECT_DRAFT_DIR;
							$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
							$profile_folder     = $user[0]->profile_name;
							if(!empty($temp_project_attachment_data)){
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$temp_project_id.DIRECTORY_SEPARATOR);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$temp_project_id.$project_owner_attachments_dir);
								
								
								//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
								
								//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir, 0777);// create temporary directory in projects folder
								//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$temp_project_id.DIRECTORY_SEPARATOR , 0777); // create the directory by using temporary project id
								//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$temp_project_id.$project_owner_attachments_dir , 0777); // create the directory by using temporary project id
							
								foreach($temp_project_attachment_data as $attachment_key => $attachment_value){
								
									if(!empty($attachment_value['temp_project_attachment_name'])){
										$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
										$file_size = $this->ftp->get_filesize($source_path);
										if($file_size == '-1'){
										
											$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
											$file_size = $this->ftp->get_filesize($source_path);
										}
										if($file_size != '-1'){
											$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$temp_project_id.$project_owner_attachments_dir.$attachment_value['temp_project_attachment_name'];
											$this->ftp->move($source_path, $destination_path);
											$this->db->insert ('draft_projects_attachments', array('project_id'=>$temp_project_id,'draft_project_attachment_name'=> $attachment_value['temp_project_attachment_name']));
										}
									}
								}
								$this->db->delete('temp_projects_attachments', array('temp_project_id' => $temp_project_id));
								
							}
							if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id))){
								$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id);// delete project directory 
							}
							if(!empty($this->ftp->check_ftp_directory_exist(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id))) {
								$this->ftp->delete_dir(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id);
							}
							$this->ftp->close();
							########## Delete the racord from tempoary table 
							$this->db->delete('temp_projects_tags', array('temp_project_id' => $temp_project_id));
							$this->db->delete('temp_projects_categories_listing_tracking', array('temp_project_id' => $temp_project_id));
							$this->db->delete('temp_projects', array('temp_project_id' => $temp_project_id));
							$msg['status'] = 'SUCCESS';
							$msg['message'] = '';
							echo json_encode ($msg);
							
							
						} else if($page_type == 'form'){ // this block will execute when user click "save as draft" button on post project page or edit preview page
							$post_data = $this->input->post ();
							$response = $this->Post_project_model->post_project_validation($post_data);
							if($response['status'] == 'SUCCESS'){
								if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){ 
									if($user_memebership_max_number_of_draft_projects == '0' && ($user_memebership_max_number_of_open_projects == '0' || empty($standard_valid_time_arr))){
										if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
											if($user_detail['current_membership_plan_id'] == 1){
												$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_draft_project_project_posting_disabled_message');
											}
											if($user_detail['current_membership_plan_id'] == 4){
												$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_draft_project_project_posting_disabled_message');
											}
										
										}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
											if($user_detail['current_membership_plan_id'] == 1){
												$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_draft_fulltime_project_project_posting_disabled_message');
											}
											if($user_detail['current_membership_plan_id'] == 4){
												$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_draft_fulltime_project_project_posting_disabled_message');
											}
										}
										
										echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
										die;
										
									} else if ($user_memebership_max_number_of_draft_projects == '0'){
										
										if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
											if($user_detail['current_membership_plan_id'] == 1){
												$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_project_as_draft_disabled_message');
											}
											if($user_detail['current_membership_plan_id'] == 4){
												$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_project_as_draft_disabled_message');
											}
										
										}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
											if($user_detail['current_membership_plan_id'] == 1){
												$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_fulltime_project_as_draft_disabled_message');
											}
											if($user_detail['current_membership_plan_id'] == 4){
												$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_fulltime_project_as_draft_disabled_message');
											}
										}
										
										echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
										die;
									
									} elseif ($total_user_draft_project_count >= $user_memebership_max_number_of_draft_projects){
									
										if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
											if($user_detail['current_membership_plan_id'] == 1){
												$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_project_as_draft_slots_not_available_message');
											}
											if($user_detail['current_membership_plan_id'] == 4){
												$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_project_as_draft_slots_not_available_message');
											}
										
										}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
											if($user_detail['current_membership_plan_id'] == 1){
												$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_fulltime_project_as_draft_slots_not_available_message');
											}
											if($user_detail['current_membership_plan_id'] == 4){
												$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_fulltime_project_as_draft_slots_not_available_message');
											}
										}
										
										echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
										die;
									}
								} else {
									if ($user_memebership_max_number_of_draft_fulltime_projects == '0'){
										if($user_detail['current_membership_plan_id'] == 1){
											$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_fulltime_project_as_draft_disabled_message');
										}
										if($user_detail['current_membership_plan_id'] == 4){
											$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_fulltime_project_as_draft_disabled_message');
										}
										echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
										die;
									
									} elseif ($total_user_draft_fulltime_project_count >= $user_memebership_max_number_of_draft_fulltime_projects){
										if($user_detail['current_membership_plan_id'] == 1){
											$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_fulltime_project_as_draft_slots_not_available_message');
										}
										if($user_detail['current_membership_plan_id'] == 4){
											$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_fulltime_project_as_draft_slots_not_available_message');
										}
										echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
										die;
									}
								}
								
								$project_locality_id = 0;$project_county_id = 0;$escrow_payment_method = 'N';
								$offline_payment_method = 'N';$upgrade_type_featured = 'N';$upgrade_type_urgent = 'N';
								$upgrade_type_sealed = 'N';$upgrade_type_hidden = 'N';$min_budget = 0;$max_budget = 0;
								$postal_code_id = 0;$project_type = "fixed";
								$not_sure_dropdown_option_selected = 'N';
								$confidential_dropdown_option_selected = 'N';
								
								
								if($this->input->post('escrow_payment_method') == 'Y'){
									$escrow_payment_method = $this->input->post('escrow_payment_method');
								}
								
								if($this->input->post ('offline_payment_method') == 'Y'){
									$offline_payment_method = $this->input->post ('offline_payment_method');
								}
								if(!empty($this->input->post ('project_budget'))){
									$project_budget = $this->input->post ('project_budget');
									if($project_budget == 'confidential_dropdown_option_selected'){
										$confidential_dropdown_option_selected = 'Y';
									}else if($project_budget == 'not_sure_dropdown_option_selected'){
										$not_sure_dropdown_option_selected = 'Y';
									}else{
										$project_budget_array = explode("_",$this->input->post ('project_budget'));
										$min_budget = $project_budget_array[0];
										$max_budget = $project_budget_array[1]; 
									}
								}
								//if(!empty($this->input->post ('location_option'))){
									if(!empty($this->input->post ('project_locality_id'))){
									$project_locality_id = $this->input->post ('project_locality_id');
									}if(!empty($this->input->post ('project_county_id'))){
										$project_county_id = $this->input->post ('project_county_id');
									}
								
								//}
								if(!empty($this->input->post ('upgrade_type_featured'))){
									$upgrade_type_featured = 'Y';
								}
								if(!empty($this->input->post ('upgrade_type_hidden'))){
									$upgrade_type_hidden = 'Y';
								}
								if(!empty($this->input->post ('upgrade_type_sealed'))){
									$upgrade_type_sealed = 'Y';
								}
								if(!empty($this->input->post ('upgrade_type_urgent'))){
									$upgrade_type_urgent = 'Y';
								}
								if(!empty($this->input->post('project_county_id')) && !empty($this->input->post ('project_locality_id'))){
								
									$postal_code_id = $this->input->post('project_postal_code_id');
									
								}
								if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
									$project_type = $this->input->post('project_type');
									
								
								}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
									$project_type = 'fulltime';
								}
								$draft_project_data = array (
									'project_id'=>$temp_project_id,
									'project_owner_id'=>$user[0]->user_id,
									'project_save_date'=>date('Y-m-d H:i:s'),
									'project_title'=>trim($this->input->post('project_title')),
									'project_description'=>trim($this->input->post('project_description')),
									'locality_id'=>$project_locality_id,
									'county_id'=>$project_county_id,
									'postal_code_id'=>$postal_code_id,
									'project_type'=>$project_type,
									'min_budget'=>$min_budget,
									'max_budget'=>$max_budget,
									'not_sure_dropdown_option_selected'=>$not_sure_dropdown_option_selected,
									'confidential_dropdown_option_selected'=>$confidential_dropdown_option_selected,
									'escrow_payment_method'=>$escrow_payment_method,
									'offline_payment_method'=>$offline_payment_method,
									
									'featured'=>$upgrade_type_featured,
									'urgent'=>$upgrade_type_urgent,
									'sealed'=>$upgrade_type_sealed,
									'hidden'=>$upgrade_type_hidden
									   
								);
							
								$this->db->insert ('projects_draft', $draft_project_data); // save data in projects_draft table from post project form
								######### save use activity log message
								$project_title = trim($draft_project_data['project_title']);
								if($draft_project_data['project_type'] == 'fulltime'){
									$publish_project_log_message = $this->config->item('fulltime_post_project_save_as_draft_user_activity_log_displayed_message_sent_to_po');
								}else{
									$publish_project_log_message = $this->config->item('post_project_save_as_draft_user_activity_log_displayed_message_sent_to_po');
								}
								$publish_project_log_message = str_replace(array('{project_title}'),array(htmlspecialchars($project_title)),$publish_project_log_message);
								user_display_log($publish_project_log_message);
								foreach($this->input->post('project_category') as $project_category_key=>$project_category_value){
									$project_category_id = 0;
									$project_parent_category_id = 0;
								
									if(!empty($project_category_value['project_parent_category'])){
										if(isset($project_category_value['project_child_category']) && !empty($project_category_value['project_child_category']))
										{
											$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);

											$check_project_child_category_exist = $this->Post_project_model->check_project_child_category_exist($project_category_value['project_parent_category'],$project_category_value['project_child_category']);	
											if($check_project_parent_category_exist){
												if($check_project_child_category_exist){
													
													$project_category_id = $project_category_value['project_child_category'];
													$project_parent_category_id = $project_category_value['project_parent_category'];
													
												}else{
												
													$project_category_id =  $project_category_value['project_parent_category'];
													$project_parent_category_id = 0;
													
												}
											}
										}else{
											
											$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);
											if($check_project_parent_category_exist){
											
												$project_category_id =  $project_category_value['project_parent_category'];
												$project_parent_category_id = 0;
											
											}
										}
									}
									if(!empty($project_category_id) || !empty($project_parent_category_id)){
										$this->db->insert ('draft_projects_categories_listing_tracking', array(
											'project_id' => $temp_project_id,
											'draft_project_category_id' => $project_category_id,
											'draft_project_parent_category_id' => $project_parent_category_id 
										));
									}
									// save data in draft_projects_categories_listing_tracking table from post project form
									
								}
							  
								if(!empty($this->input->post('project_tag'))){
									foreach($this->input->post('project_tag') as $project_tag_key){
										if(!empty($project_tag_key['tag_name'])){
											$this->db->insert ('draft_projects_tags', array('project_id' => $temp_project_id,
											'draft_project_tag_name' => trim($project_tag_key['tag_name'])));
											// save data in draft_projects_tags table from post project form
										}
									}	
								}
								// move data from temp_projects_attachments table to draft_projects_attachments table
								$this->db->select('temp_project_attachment_name');
								$this->db->from('temp_projects_attachments');
								$this->db->where('temp_project_id',$temp_project_id);
								$this->db->order_by('id',"asc");
								$temp_project_attachment_result = $this->db->get();
								$temp_project_attachment_data = $temp_project_attachment_result->result_array();
								// connect ftp to move attachments
								$this->load->library('ftp');
								$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
								$config['ftp_username'] = FTP_USERNAME;
								$config['ftp_password'] = FTP_PASSWORD;
								$config['ftp_port'] 	= FTP_PORT;
								$config['debug']    = TRUE;
								$this->ftp->connect($config); 
								$temp_dir = TEMP_DIR;
								$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
								
								$users_ftp_dir 	= USERS_FTP_DIR; 
								$projects_ftp_dir = PROJECTS_FTP_DIR;
								$projects_temp_dir = PROJECT_TEMPORARY_DIR;
								$project_draft_dir = PROJECT_DRAFT_DIR;
								$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
								$profile_folder     = $user[0]->profile_name;
								if(!empty($temp_project_attachment_data)){
									
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$temp_project_id.DIRECTORY_SEPARATOR);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$temp_project_id.$project_owner_attachments_dir);
									
									//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
									//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
									
									//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir, 0777);// create temporary directory in projects folder
									//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$temp_project_id.DIRECTORY_SEPARATOR , 0777); // create the directory by using temporary project id
									//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$temp_project_id.$project_owner_attachments_dir , 0777); // create the directory by using temporary project id
								
									foreach($temp_project_attachment_data as $attachment_key => $attachment_value){
									
										if(!empty($attachment_value['temp_project_attachment_name'])){
											$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
											$file_size = $this->ftp->get_filesize($source_path);
											if($file_size == '-1'){
											
												$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
												$file_size = $this->ftp->get_filesize($source_path);
											
											}
											
											if($file_size != '-1'){
												$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$temp_project_id.$project_owner_attachments_dir.$attachment_value['temp_project_attachment_name'];
												
												$this->ftp->move($source_path, $destination_path);
												$this->db->insert ('draft_projects_attachments', array('project_id'=>$temp_project_id,'draft_project_attachment_name'=> $attachment_value['temp_project_attachment_name']));
											}
										}
									}
									$this->db->delete('temp_projects_attachments', array('temp_project_id' => $temp_project_id));
									
								}
								if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id))){
									$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id);// delete project directory 
								}
								// remov entry from open bidding table
								if(!empty($this->ftp->check_ftp_directory_exist(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id))) {
									$this->ftp->delete_dir(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id);
								}
								$this->ftp->close();
								########## Delete the racord from temporary table 
								$this->db->delete('temp_projects_tags', array('temp_project_id' => $temp_project_id));
								$this->db->delete('temp_projects_categories_listing_tracking', array('temp_project_id' => $temp_project_id));
								$this->db->delete('temp_projects', array('temp_project_id' => $temp_project_id));
								$msg['status'] = 'SUCCESS';
								$msg['message'] = '';
								echo json_encode ($msg);
							
							}else{
							
								echo json_encode ($response);
							}
						
						}else{
							$msg['status'] = 'FAILED';
							$msg['message'] = '';
							echo json_encode ($msg);
						}
					}else{
						$msg['status'] = 'FAILED';
						$msg['message'] = '';
						echo json_encode ($msg);
					}
				}else{
					$msg['status'] = 'FAILED';
					$msg['message'] = '';
					echo json_encode ($msg);
				}
			}else{
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				if(!empty($temp_project_data)){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
				}	
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
	* This function is used to move the project data from temporary table to projects_awaiting_moderation and other tables.
	*/
	public function post_publish_project () {
		if ($this->input->is_ajax_request () && $this->input->post ()) {
			$temp_project_id = $this->input->post ('temp_project_id');
			$page_type = $this->input->post ('page_type');
			
			
			// if session is not exist then login popup will show start//
			if(!$this->session->userdata('user')){
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				if($temp_project_data['temp_project_owner_id'] != 0){
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
				
				}
				if($page_type == 'form'){
					$post_data = $this->input->post ();
					$response = $this->Post_project_model->post_project_validation($post_data);
					if($response['status'] == 'SUCCESS'){
						$p_type = 'post_publish_project';
						$page_id = $temp_project_id;
						if(!$this->session->userdata('user')){
							echo json_encode(['status' => 201,'login_status'=>'0','page_type'=>$p_type,'page_id'=>$page_id,'location'=>'','data'=>$this->load->view('signin/signin_popup',array('page_id'=>$page_id,'page_type'=>$p_type), true)]);
							die;
						}
					
					}else{
						echo json_encode ($response);
						die;
					}
				}else if($page_type == 'preview'){
					$p_type = 'post_publish_project';
					$page_id = $temp_project_id;
					if(!$this->session->userdata('user')){
						echo json_encode(['status' => 201,'login_status'=>'0','page_type'=>$p_type,'page_id'=>$page_id,'location'=>'','data'=>$this->load->view('signin/signin_popup',array('page_id'=>$page_id,'page_type'=>$p_type), true)]);
						die;
					}
				
				}
				
			}
			// if session is not exist then login popup will show end//
			
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
			
			
				
			
				// check that all parent category is avalable
				$check_project_categories_available_or_not = $this->db->where(['status'=>'Y','parent_id'=>0])->from('categories_projects')->count_all_results();
				if($check_project_categories_available_or_not == 0){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('projects_categories_disabled_or_deleted_project_posting_disabled_message')]);
					die;
				}
				
			
			
				$user = $this->session->userdata('user');
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
		
				if(empty($temp_project_data)) { // if project not exists it will redirect to dashboard page
					$res = [
						'status' => 400,
						'location'=>VPATH.$this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}
				if(!empty($temp_project_data)){
				
					if($temp_project_data['temp_project_owner_id'] == 0){
					
						$this->db->update('temp_projects', ['temp_project_owner_id'=>$user[0]->user_id], ['temp_project_id' => $temp_project_id]);
						$temp_project_data['temp_project_owner_id'] = $user[0]->user_id;
					
					}
				}
				if(!empty($temp_project_data) && $temp_project_data['temp_project_owner_id'] != $user[0]->user_id){
					$this->Post_project_model->delete_temp_project($temp_project_id);
					$res = [
						'status' => 400,
						'location'=>VPATH.$this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}
				$project_expiration_timestamp = $temp_project_data['project_expiration_date']!= NULL ? strtotime ($temp_project_data['project_expiration_date']) : 0;
				if(empty($project_expiration_timestamp) || $project_expiration_timestamp < time()){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
					$res = [
						'status' => 400,
						'location'=>VPATH . $this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}
				
				$user = $this->session->userdata('user');
				$page_type_array = array('preview','form');
				if (in_array($page_type, $page_type_array)) {
					$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
					if($user_detail['current_membership_plan_id'] == 1){
						$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
						$user_memebership_max_number_of_open_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');

						$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
						$user_memebership_max_number_of_open_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					if($user_detail['current_membership_plan_id'] == 4){
						$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
						$user_memebership_max_number_of_open_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');

						$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
						$user_memebership_max_number_of_open_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					// standard project availability
					$standard_time_arr = explode(":", $this->config->item('standard_project_availability'));
					$standard_check_valid_arr = array_map('getInt', $standard_time_arr); 
					$standard_valid_time_arr = array_filter($standard_check_valid_arr);
				
					######### apply validation for open projects ######
					
					$user_total_open_projects_count = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);

					$user_total_open_fulltime_projects_count = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);

					$user_membership_plan_detail = $this->db->get_where('membership_plans', ['id' => $user_detail['current_membership_plan_id']])->row_array();
				
					$count_awaiting_moderation_project = $this->db // count the number of record in temp_projects table
					->select ('id')
					->from ('projects_awaiting_moderation')
					->where('project_id',$temp_project_id)
					->where('project_owner_id',$user[0]->user_id)
					->get ()->num_rows ();
					if($count_awaiting_moderation_project == 0){
					
						if($page_type == 'preview'){ // this block will execute when user click "save as draft" button on 	temporary project preview page
							
							if($temp_project_data['project_type'] != 'fulltime') { 
								if(($user_memebership_max_number_of_draft_projects == '0' && ($user_memebership_max_number_of_open_projects == '0' || empty($standard_valid_time_arr)))){ 
									if($user_detail['current_membership_plan_id'] == 1){
										$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_project_project_posting_disabled_message');
									}
									if($user_detail['current_membership_plan_id'] == 4){
										$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_project_project_posting_disabled_message');
									}
									echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
									die;
								} else if($user_total_open_projects_count >= $user_memebership_max_number_of_open_projects ){
									if($user_detail['current_membership_plan_id'] == 1){
										$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_project_open_slots_not_available_message');
									}
									if($user_detail['current_membership_plan_id'] == 4){
										$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_project_open_slots_not_available_message');
									}
									echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
									die;
								}
							} else {
								if(($user_memebership_max_number_of_draft_fulltime_projects == '0' && ($user_memebership_max_number_of_open_fulltime_projects == '0' || empty($standard_valid_time_arr)))){ 
									if($user_detail['current_membership_plan_id'] == 1){
										$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_fulltime_project_project_posting_disabled_message');
									}
									if($user_detail['current_membership_plan_id'] == 4){
										$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_fulltime_project_project_posting_disabled_message');
									}
									echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
									die;
									
								} else if($user_total_open_fulltime_projects_count >= $user_memebership_max_number_of_open_fulltime_projects ){
									if($user_detail['current_membership_plan_id'] == 1){
										$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_fulltime_project_open_slots_not_available_message');
									}
									if($user_detail['current_membership_plan_id'] == 4){
										$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_fulltime_project_open_slots_not_available_message');
									}
									echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
									die;
								}
							}

							// move data from temp_projects table to projects_awaiting_moderation table
							$this->db->select('*');
							$this->db->from('temp_projects');
							$this->db->where('temp_projects.temp_project_id',$temp_project_id);
							$this->db->where('temp_projects.temp_project_owner_id',$user[0]->user_id);
							$temp_project_result = $this->db->get();
							$temp_project_data = $temp_project_result->result_array();
							
							
							$this->db->select('*');
							$this->db->from('temp_projects_categories_listing_tracking');
							$this->db->where('temp_project_id',$temp_project_id);
							$this->db->order_by('id',"asc");
							$temp_project_category_result = $this->db->get();
							$temp_project_category_data = $temp_project_category_result->result_array();
							
							
							###################### check that user selected category is valid or not(admin deactive/delete the category)
							$check_project_parent_category_status = false;
							if(!empty($temp_project_category_data)){
								foreach($temp_project_category_data as $category_key => $category_value){
									$project_parent_category_id = 0;
									if(!empty($category_value['temp_project_parent_category_id'])){
										$project_parent_category_id = $category_value['temp_project_parent_category_id'];
									
									}else{
										$project_parent_category_id = $category_value['temp_project_category_id'];
									
									}
									$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_parent_category_id);
									if($check_project_parent_category_exist){
										$check_project_parent_category_status = true;
										break;
									}
									
								}
							}
							if(!$check_project_parent_category_status){
								$res = [
									'status' => 400,
									'error' => $this->config->item('post_project_valid_category_not_existent_popup_message'),
									'location'=>''
								];
								echo json_encode($res);
								die;
							}
							
							$awaiting_moderation_project_data = array (
								'project_id'=>$temp_project_id,
								'project_owner_id'=>$user[0]->user_id,
								'project_submission_to_moderation_date'=>date('Y-m-d H:i:s'),
								'project_title'=>trim($temp_project_data[0]['project_title']),
								'project_description'=>$temp_project_data[0]['project_description'],
								'locality_id'=>$temp_project_data[0]['locality_id'],
								'county_id'=>$temp_project_data[0]['county_id'],
								'postal_code_id'=>$temp_project_data[0]['postal_code_id'],
								'project_type'=>$temp_project_data[0]['project_type'],
								'min_budget'=>$temp_project_data[0]['min_budget'],
								'max_budget'=>$temp_project_data[0]['max_budget'],
								'confidential_dropdown_option_selected'=>$temp_project_data[0]['confidential_dropdown_option_selected'],
								'not_sure_dropdown_option_selected'=>$temp_project_data[0]['not_sure_dropdown_option_selected'],
								'escrow_payment_method'=>$temp_project_data[0]['escrow_payment_method'],
								'offline_payment_method'=>$temp_project_data[0]['offline_payment_method'],
								'featured'=>$temp_project_data[0]['featured'],
								'urgent'=>$temp_project_data[0]['urgent'],
								'sealed'=>$temp_project_data[0]['sealed'],
								'hidden'=>$temp_project_data[0]['hidden']
								
							);
							
							// @sid code to set auto approval date into projects_awaiting_moderation
							$auto_approval_flag = false; // This flag is used to identify where the entry of temporary project table move either [awaiting moderation / open for bidding]
							
							
							
							
							// @sid this code block is used to update user account balance based on project upgrade 
							
							// check the user account balance,bonus balance,account balance is sufficient for purchase upgrade
							$count_user_featured_membership_included_upgrades_monthly = $this->Post_project_model->count_user_featured_membership_included_upgrades_monthly($user[0]->user_id); // count user membership featured  upgrade
			
							$count_user_urgent_membership_included_upgrades_monthly = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user[0]->user_id);// count user membership urgent upgrade
							
							$count_user_sealed_membership_included_upgrades_monthly = $this->Post_project_model->count_user_sealed_membership_included_upgrades_monthly($user[0]->user_id);// count user membership sealed upgrade
							
							$count_user_hidden_membership_included_upgrades_monthly = $this->Post_project_model->count_user_hidden_membership_included_upgrades_monthly($user[0]->user_id);// count user membership hidden upgrade
							
							
							$upgraded_project_price = 0;
							if($temp_project_data[0]['featured'] == 'Y'){
								if($user_membership_plan_detail['included_number_featured_upgrades'] !='-1' && $count_user_featured_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_featured_upgrades']){
									$upgraded_project_price += $this->config->item('project_upgrade_price_featured');
								}
							}
							if($temp_project_data[0]['urgent'] == 'Y'){
								if($user_membership_plan_detail['included_number_urgent_upgrades']!= '-1' && $count_user_urgent_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_urgent_upgrades']){
									$upgraded_project_price += $this->config->item('project_upgrade_price_urgent');
								}
							}
							if($temp_project_data[0]['sealed'] == 'Y'){
								//$upgraded_project_price += $this->config->item('project_upgrade_price_sealed');
								
								if($user_membership_plan_detail['included_number_sealed_upgrades']!= '-1' && $count_user_sealed_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_sealed_upgrades']){
								$upgraded_project_price += $this->config->item('project_upgrade_price_sealed');
								}
								
								
							}
							if($temp_project_data[0]['hidden'] == 'Y'){
								//$upgraded_project_price += $this->config->item('project_upgrade_price_hidden');
								
								if($user_membership_plan_detail['included_number_hidden_upgrades']!= '-1' && $count_user_hidden_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_hidden_upgrades']){
								$upgraded_project_price += $this->config->item('project_upgrade_price_hidden');
								}
								
							}
							if(floatval($upgraded_project_price) > 0){
								$total_user_balance = $user_detail['bonus_balance'] + $user_detail['signup_bonus_balance'] + $user_detail['user_account_balance'];
								if(floatval($upgraded_project_price) > floatval($total_user_balance) ){
									
									if(($temp_project_data[0]['featured'] == 'Y' && $temp_project_data[0]['urgent'] == 'Y') || ($temp_project_data[0]['featured'] == 'Y' && $temp_project_data[0]['urgent'] == 'Y' && $temp_project_data[0]['sealed'] == 'Y') || ($temp_project_data[0]['featured'] == 'Y' && $temp_project_data[0]['sealed'] == 'Y') || ($temp_project_data[0]['urgent'] == 'Y' && $temp_project_data[0]['sealed'] == 'Y')|| ($temp_project_data[0]['sealed'] == 'Y' && $temp_project_data[0]['hidden'] == 'Y')){
										$error_msg = $this->config->item('user_post_upgraded_project_insufficient_funds_error_message_plural');
									}else{
										$error_msg = $this->config->item('user_post_upgraded_project_insufficient_funds_error_message_singular');
									}
								
									$res = array(
											'status' => 400,
											'location' => '',
											'error' => $error_msg, // define in post_project_custom config
											'popup_heading'=>$this->config->item('popup_alert_heading')
										);
									echo json_encode($res);
									die;
								}
							}

							if($temp_project_data[0]['featured'] == 'Y') {
								$project_upgrade_array['featured'] = 'Y';
							}
							if($temp_project_data[0]['urgent'] == 'Y') {
								$project_upgrade_array['urgent'] = 'Y';
							}
							if($temp_project_data[0]['sealed'] == 'Y') {
								$project_upgrade_array['sealed'] = 'Y';
							}
							if($temp_project_data[0]['hidden'] == 'Y') {
								$project_upgrade_array['hidden'] = 'Y';
							}
							
							$auto_approve_min = get_project_auto_approve_min_time($project_upgrade_array,$user_detail['current_membership_plan_id']);
							if($auto_approve_min != 0) {
								$awaiting_moderation_project_data['auto_approval_date'] = date('Y-m-d H:i:s',$auto_approve_min);
							} else {
								$auto_approval_flag = true;
							}
							
							if($auto_approval_flag) {
								$project_expire_date = $this->Post_project_model->get_project_correct_expiration_date($temp_project_data[0]);
								$open_for_bidding_project_data = [
									'project_id'=>$temp_project_id,
									'project_owner_id'=>$user[0]->user_id,
									'project_posting_date' => date('Y-m-d H:i:s'),
									'project_expiration_date' => $project_expire_date,
									'project_title'=>trim($temp_project_data[0]['project_title']),
									'project_description'=>trim($temp_project_data[0]['project_description']),
									'locality_id'=>$temp_project_data[0]['locality_id'],
									'county_id'=>$temp_project_data[0]['county_id'],
									'postal_code_id' => $temp_project_data[0]['postal_code_id'],
									'project_type' => $temp_project_data[0]['project_type'],
									'min_budget'=>$temp_project_data[0]['min_budget'],
									'max_budget'=>$temp_project_data[0]['max_budget'],
									'confidential_dropdown_option_selected'=>$temp_project_data[0]['confidential_dropdown_option_selected'],
									'not_sure_dropdown_option_selected'=>$temp_project_data[0]['not_sure_dropdown_option_selected'],
									'escrow_payment_method'=>$temp_project_data[0]['escrow_payment_method'],
									'offline_payment_method'=>$temp_project_data[0]['offline_payment_method'],
									'featured'=>$temp_project_data[0]['featured'],
									'urgent'=>$temp_project_data[0]['urgent'],
									'sealed'=>$temp_project_data[0]['sealed'],
									'hidden'=>$temp_project_data[0]['hidden'],
									'views' => 0,
									'revisions'=> 0
								];
								
								$this->db->insert ('projects_open_bidding', $open_for_bidding_project_data); // save data in fixed_budget_projects_open_bidding table from post project form

								$publish_project = [
									'project_id' => $temp_project_id,
									'project_owner_id' => $user[0]->user_id,
									'project_posting_date' => date('Y-m-d H:i:s'),
									'project_title' => trim($temp_project_data[0]['project_title']),
									'project_type' => $temp_project_data[0]['project_type']
								];
								$this->db->insert('users_alltime_published_projects_tracking', $publish_project);

								if(!empty($open_for_bidding_project_data['hidden']) && $open_for_bidding_project_data['hidden'] == 'N') {
									$time_arr = explode(':', $this->config->item('standard_project_refresh_sequence'));
									$check_valid_arr = array_map('getInt', $time_arr); 
									$valid_time_arr = array_filter($check_valid_arr);
									$next_refresh_date = null;
									if(!empty($valid_time_arr)) {
										$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
										$refresh_sequence_data = [
											'project_id' => $temp_project_id,
											'project_last_refresh_time' => null,
											'project_next_refresh_time' => $next_refresh_date,
											'project_upgrade_refresh_sequence_standard' => $this->config->item('standard_project_refresh_sequence')
										];
										$this->db->insert('standard_projects_refresh_sequence_tracking', $refresh_sequence_data);
									}
								}

								######### save use activity log message
								$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$open_for_bidding_project_data['project_id'];
								$project_title = trim($open_for_bidding_project_data['project_title']);
								if($open_for_bidding_project_data['project_type'] == 'fulltime'){
									$publish_project_log_message = $this->config->item('post_fulltime_project_directly_move_open_bidding_user_activity_log_displayed_message_sent_to_po');
								}else{
									$publish_project_log_message = $this->config->item('post_project_directly_move_open_bidding_user_activity_log_displayed_message_sent_to_po');
								}
								$publish_project_log_message = str_replace(array('{project_url_link}','{project_title}'),array($project_url_link,htmlspecialchars(trim($project_title))),$publish_project_log_message);
								user_display_log($publish_project_log_message);
								// insert the data regrading purchase tracking and refresh sequence and manage the user bonus balance and account balance
								$this->Post_project_model->user_project_upgrade_purchase_refresh_sequence_tracking_save($open_for_bidding_project_data,$user[0]->user_id); 
								
								// move data from temp_projects_tags table to awaiting_moderation_projects_tags table
								$this->db->select('temp_project_tag_name');
								$this->db->from('temp_projects_tags');
								$this->db->where('temp_project_id',$temp_project_id);
								$this->db->order_by('id',"asc");
								$temp_project_tag_result = $this->db->get();
								$temp_project_tag_data = $temp_project_tag_result->result_array();
								if(!empty($temp_project_tag_data)){
									foreach($temp_project_tag_data as $tag_key => $tag_value){
										$this->db->insert ('projects_tags', array('project_id'=>$temp_project_id,'project_tag_name'=> trim($tag_value['temp_project_tag_name'])));
									}
								}
								$parent_categories_id = [];
								if(!empty($temp_project_category_data)){
									foreach($temp_project_category_data as $category_key => $category_value){
										$project_category_id = 0;
										$project_parent_category_id = 0;
										
										if(!empty($category_value['temp_project_parent_category_id'])){
										
											$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($category_value['temp_project_parent_category_id']);
					
											$check_project_child_category_exist = $this->Post_project_model->check_project_child_category_exist($category_value['temp_project_parent_category_id'],$category_value['temp_project_category_id']);
											if($check_project_parent_category_exist){
												if($check_project_child_category_exist){
													
													$project_category_id = $category_value['temp_project_category_id'];
													$project_parent_category_id = $category_value['temp_project_parent_category_id'];
												
												}else{
												
													$project_category_id =  $category_value['temp_project_parent_category_id'];
													$project_parent_category_id = 0;
													
												}
											}
										}else{
											$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($category_value['temp_project_category_id']);
											if($check_project_parent_category_exist){
											
												$project_category_id =  $category_value['temp_project_category_id'];
												$project_parent_category_id = 0;
											
											}
										
										}
										if(!empty($project_category_id) || !empty($project_parent_category_id)){
											$this->db->insert ('projects_categories_listing_tracking', array('project_id'=>$temp_project_id,'project_category_id'=> $project_category_id,'project_parent_category_id'=>$project_parent_category_id)); // move the categories from table temp_projects_categories_listing_tracking to projects_categories_listing_tracking and apply check that category is valid or not
											if($project_parent_category_id == 0) {
												array_push($parent_categories_id, $project_category_id);
											} else {
												array_push($parent_categories_id, $project_parent_category_id);
											}
										}
									}
								
								}
								// move data from temp_projects_attachments table to projects_attachments table
								$this->db->select('temp_project_attachment_name');
								$this->db->from('temp_projects_attachments');
								$this->db->where('temp_project_id',$temp_project_id);
								$this->db->order_by('id',"asc");
								$temp_project_attachment_result = $this->db->get();
								$temp_project_attachment_data = $temp_project_attachment_result->result_array();
								// connect ftp to move attachment
								$this->load->library('ftp');
								$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
								$config['ftp_username'] = FTP_USERNAME;
								$config['ftp_password'] = FTP_PASSWORD;
								$config['ftp_port'] 	= FTP_PORT;
								$config['debug']    = TRUE;
								$this->ftp->connect($config); 
								
								$temp_dir = TEMP_DIR;
								$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
								
								$users_ftp_dir 	= USERS_FTP_DIR; 
								$projects_ftp_dir = PROJECTS_FTP_DIR;
								
								$projects_temp_dir = PROJECT_TEMPORARY_DIR;
								$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
								$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
								$profile_folder     = $user[0]->profile_name;
								
								
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$temp_project_id.DIRECTORY_SEPARATOR);
								
								
								//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
								//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir, 0777);// create open for bidding directory in projects folder
								//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$temp_project_id, 0777); // create the directory by using temporary project id
								if(!empty($temp_project_attachment_data)){
									//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$temp_project_id.$project_owner_attachments_dir , 0777); // create the owner attachment directory by using temporary project id
									
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$temp_project_id.$project_owner_attachments_dir);
									
									foreach($temp_project_attachment_data as $attachment_key => $attachment_value){
									
										if(!empty($attachment_value['temp_project_attachment_name'])){
											$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
											$file_size = $this->ftp->get_filesize($source_path);
											
											
											if($file_size == '-1'){
											
												$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
												$file_size = $this->ftp->get_filesize($source_path);
											
											}
											
											
											if($file_size != '-1'){
												$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$temp_project_id.$project_owner_attachments_dir.$attachment_value['temp_project_attachment_name'];
												$this->ftp->move($source_path, $destination_path);
												$this->db->insert ('projects_attachments', array('project_id'=>$temp_project_id,'project_attachment_name'=> $attachment_value['temp_project_attachment_name']));
											}
										}
									}
									$this->db->delete('temp_projects_attachments', array('temp_project_id' => $temp_project_id));
									
									// $list_temp_attachments = $this->ftp->list_files($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id);
								}
								if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id))){
									$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR);// delete project directory 
								}
								$this->ftp->close();

								if(!empty($open_for_bidding_project_data['hidden']) && $open_for_bidding_project_data['hidden'] == 'N') {

									$time_arr = explode(':', $this->config->item('standard_project_refresh_sequence'));
									$check_valid_arr = array_map('getInt', $time_arr); 
									$valid_time_arr = array_filter($check_valid_arr);
									$next_refresh_date = null;
									if(!empty($valid_time_arr)) {
										$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
										$refresh_sequence_data = [
											'project_id' => $temp_project_id,
											'project_last_refresh_time' => null,
											'project_next_refresh_time' => $next_refresh_date,
											'project_upgrade_refresh_sequence_standard'=>$this->config->item('standard_project_refresh_sequence')
										];
										$standard_refresh_sequence_cnt = $this->db->from('standard_projects_refresh_sequence_tracking')->where(['project_id' => $temp_project_id])->count_all_results();
										if($standard_refresh_sequence_cnt == 0) {
											$this->db->insert('standard_projects_refresh_sequence_tracking', $refresh_sequence_data);
										}
									}

									// insert data in projects latest project refresh sequence tracking
									$latest_project_refresh_tracking = [
										'project_id' => $temp_project_id,
										'project_last_refresh_time' => date('Y-m-d H:i:s'),
										'project_next_refresh_time' => $next_refresh_date,
										'refresh_sequence_table_source' => 'standard'
									];
									$this->db->insert('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking);						
									// save data into users newly posted projects sent notification tracking table
									$category_mapping_data = $this->Post_project_model->get_projects_professionals_categories_mapping_data_based_on_categories_id($parent_categories_id);
									if(!empty($category_mapping_data)) {
										$this->Post_project_model->add_data_in_to_users_new_posted_projects_sent_notification_table($category_mapping_data, $parent_categories_id, $open_for_bidding_project_data);
									}
									// save data into users newly posted projects sent notification tracking table when project posted by favorite employer
									$users = $this->Post_project_model->get_users_id_from_favorite_employer_id($open_for_bidding_project_data['project_owner_id']);
									if(!empty($users)) {
										$this->Post_project_model->add_data_in_to_users_new_posted_projects_sent_notification_table_favorite_employer($users, $parent_categories_id, $open_for_bidding_project_data);
									}		
								}

								// trigger socket event to update latest project section on user dashboard
								$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateLatestProjectOnUserDashboard/".$user[0]->user_id.'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
								$options = array(
									CURLOPT_RETURNTRANSFER => true,
									CURLOPT_SSL_VERIFYPEER => false
								);
								try {
									$ch = curl_init( $url );
									curl_setopt_array( $ch, $options );
									curl_exec( $ch );
									curl_close( $ch );
								} catch(Exception $e) {
								}		
									
							} else {
								$this->db->insert ('projects_awaiting_moderation', $awaiting_moderation_project_data);
								######### save use activity log message
								$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$awaiting_moderation_project_data['project_id'];
								$project_title = trim($awaiting_moderation_project_data['project_title']);
								if($awaiting_moderation_project_data['project_type'] == 'fulltime'){
									$publish_project_log_message = $this->config->item('fulltime_project_submited_by_po_for_awaiting_moderation_user_activity_log_displayed_message_sent_to_po');
								}else{
									$publish_project_log_message = $this->config->item('project_submited_by_po_for_awaiting_moderation_user_activity_log_displayed_message_sent_to_po');
								}
								$publish_project_log_message = str_replace(array('{project_url_link}','{project_title}'),array($project_url_link,htmlspecialchars($project_title)),$publish_project_log_message);
								user_display_log($publish_project_log_message);
								
								// insert the data regrading purchase tracking and manage the user bonus balance and account balance
								$this->Post_project_model->user_project_upgrade_purchase_tracking_save($awaiting_moderation_project_data,$user[0]->user_id); // user upgrade purchase tracking 
								
								
								
								// move data from temp_projects_tags table to awaiting_moderation_projects_tags table
								$this->db->select('temp_project_tag_name');
								$this->db->from('temp_projects_tags');
								$this->db->where('temp_project_id',$temp_project_id);
								$this->db->order_by('id',"asc");
								$temp_project_tag_result = $this->db->get();
								$temp_project_tag_data = $temp_project_tag_result->result_array();
								if(!empty($temp_project_tag_data)){
									foreach($temp_project_tag_data as $tag_key => $tag_value){
										$this->db->insert ('awaiting_moderation_projects_tags', array('project_id'=>$temp_project_id,'awaiting_moderation_project_tag_name'=> $tag_value['temp_project_tag_name']));
									}
								}
								// move data from temp_projects_categories_listing_tracking table to awaiting_moderation_projects_categories_listing_tracking table and check that category is valid or not.
								
								if(!empty($temp_project_category_data)){
									foreach($temp_project_category_data as $category_key => $category_value){
										$awaiting_moderation_project_category_id = 0;
										$awaiting_moderation_project_parent_category_id = 0;
										if(!empty($category_value['temp_project_parent_category_id'])){
	
											$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($category_value['temp_project_parent_category_id']);

											$check_project_child_category_exist = $this->Post_project_model->check_project_child_category_exist($category_value['temp_project_parent_category_id'],$category_value['temp_project_category_id']);
											if($check_project_parent_category_exist){
												if($check_project_child_category_exist){
													
													$awaiting_moderation_project_category_id = $category_value['temp_project_category_id'];
													$awaiting_moderation_project_parent_category_id = $category_value['temp_project_parent_category_id'];
												
												}else{
												
													$awaiting_moderation_project_category_id =  $category_value['temp_project_parent_category_id'];
													$awaiting_moderation_project_parent_category_id = 0;
													
												}
											}
										}else{
											$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($category_value['temp_project_category_id']);
											if($check_project_parent_category_exist){

												$awaiting_moderation_project_category_id =  $category_value['temp_project_category_id'];
												$awaiting_moderation_project_parent_category_id = 0;

											}

										}
										if(!empty($awaiting_moderation_project_category_id) || !empty($awaiting_moderation_project_parent_category_id)){
										$this->db->insert ('awaiting_moderation_projects_categories_listing_tracking', array('project_id'=>$temp_project_id,'awaiting_moderation_project_category_id'=> $awaiting_moderation_project_category_id,'awaiting_moderation_project_parent_category_id'=> $awaiting_moderation_project_parent_category_id));
										}
									}
								
								}
								
								// move data from temp_projects_attachments table to awaiting_moderation_projects_attachments table
								$this->db->select('temp_project_attachment_name');
								$this->db->from('temp_projects_attachments');
								$this->db->where('temp_project_id',$temp_project_id);
								$this->db->order_by('id',"asc");
								$temp_project_attachment_result = $this->db->get();
								$temp_project_attachment_data = $temp_project_attachment_result->result_array();
								// connect ftp to move attachment
								$this->load->library('ftp');
								$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
								$config['ftp_username'] = FTP_USERNAME;
								$config['ftp_password'] = FTP_PASSWORD;
								$config['ftp_port'] 	= FTP_PORT;
								$config['debug']    = TRUE;
								$this->ftp->connect($config); 
								$temp_dir = TEMP_DIR;
								$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
								$users_ftp_dir 	= USERS_FTP_DIR; 
								$projects_ftp_dir = PROJECTS_FTP_DIR;
								$projects_temp_dir = PROJECT_TEMPORARY_DIR;
								$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
								$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
								$profile_folder     = $user[0]->profile_name;
								//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
								//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir, 0777);// create temporary directory in projects folder
								//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$temp_project_id.DIRECTORY_SEPARATOR , 0777); // create the directory by using temporary project id
								//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$temp_project_id.$project_owner_attachments_dir , 0777); // create the directory by using temporary project id
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$temp_project_id.DIRECTORY_SEPARATOR);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$temp_project_id.$project_owner_attachments_dir);
								
								
								if(!empty($temp_project_attachment_data)){
									foreach($temp_project_attachment_data as $attachment_key => $attachment_value){
									
										if(!empty($attachment_value['temp_project_attachment_name'])){
											$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
											$file_size = $this->ftp->get_filesize($source_path);
											
											if($file_size == '-1'){
												$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
												$file_size = $this->ftp->get_filesize($source_path);
											
											}
											
											
											
											
											if($file_size != '-1'){
												$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$temp_project_id.$project_owner_attachments_dir .$attachment_value['temp_project_attachment_name'];
												$this->ftp->move($source_path, $destination_path);
												$this->db->insert ('awaiting_moderation_projects_attachments', array('project_id'=>$temp_project_id,'awaiting_moderation_project_attachment_name'=> $attachment_value['temp_project_attachment_name']));
											}
										}
									}
									$this->db->delete('temp_projects_attachments', array('temp_project_id' => $temp_project_id));
								}
								if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id))){
									$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR);// delete project directory 
								}
								$this->ftp->close();

								// // trigger socket event to update latest project section on user dashboard
								// $url = PROJECT_MANAGEMENT_SOCKET_URL."/updateProjectListingOnDashboardByStatus/".$user[0]->user_id.'/awaiting_moderation/""?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
								// $options = array(
								// 	CURLOPT_RETURNTRANSFER => true,
								// 	CURLOPT_SSL_VERIFYPEER => false
								// );
								// try {
								// 	$ch = curl_init( $url );
								// 	curl_setopt_array( $ch, $options );
								// 	curl_exec( $ch );
								// 	curl_close( $ch );
								// } catch(Exception $e) {
								// }		
							}
							
							########## Delete the racord from tempoary table 
							$this->db->delete('temp_projects_tags', array('temp_project_id' => $temp_project_id));
							$this->db->delete('temp_projects_categories_listing_tracking', array('temp_project_id' => $temp_project_id));
							$this->db->delete('temp_projects', array('temp_project_id' => $temp_project_id));
							$msg['status'] = 'SUCCESS';
							$msg['message'] = '';
							echo json_encode ($msg);die;
							
							
							
						} else if($page_type == 'form'){ // this block will execute when user click "publish project" button on post project page or edit preview page
							
							$post_data = $this->input->post ();
							$response = $this->Post_project_model->post_project_validation($post_data);
							
							if($response['status'] == 'SUCCESS'){
								if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
									if(($user_memebership_max_number_of_draft_projects == '0' && ($user_memebership_max_number_of_open_projects == '0' || empty($standard_valid_time_arr)))){ 
										if($user_detail['current_membership_plan_id'] == 1){
											$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_project_project_posting_disabled_message');
										}
										if($user_detail['current_membership_plan_id'] == 4){
											$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_project_project_posting_disabled_message');
										}
										echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
										die;
									} else if($user_total_open_projects_count >= $user_memebership_max_number_of_open_projects ){
										if($user_detail['current_membership_plan_id'] == 1){
											$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_project_open_slots_not_available_message');
										}
										if($user_detail['current_membership_plan_id'] == 4){
											$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_project_open_slots_not_available_message');
										}
										echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
										die;
									}
								} else {
									if(($user_memebership_max_number_of_draft_fulltime_projects == '0' && ($user_memebership_max_number_of_open_fulltime_projects == '0' || empty($standard_valid_time_arr)))){ 
										if($user_detail['current_membership_plan_id'] == 1){
											$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_fulltime_project_project_posting_disabled_message');
										}
										if($user_detail['current_membership_plan_id'] == 4){
											$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_fulltime_project_project_posting_disabled_message');
										}
										echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
										die;
										
									} else if($user_total_open_fulltime_projects_count >= $user_memebership_max_number_of_open_fulltime_projects ){
										if($user_detail['current_membership_plan_id'] == 1){
											$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_fulltime_project_open_slots_not_available_message');
										}
										if($user_detail['current_membership_plan_id'] == 4){
											$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_fulltime_project_open_slots_not_available_message');
										}
										echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
										die;									
									}
								}
								
								###################### check that user selected category is valid or not(admin deactive/delete the category)
								$check_project_parent_category_status = false;
								if($this->input->post('project_category')){
									foreach($this->input->post('project_category') as $project_category_key=>$project_category_value){
										if(isset($project_category_value['project_parent_category']) && !empty($project_category_value['project_parent_category'])){
											$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);
											if($check_project_parent_category_exist){
												$check_project_parent_category_status = true;
												break;
											}
										}
									}
								
								}
								if(!$check_project_parent_category_status){
									$res = [
										'status' => 400,
										'error' => $this->config->item('post_project_valid_category_not_existent_popup_message'),
										'popup_heading'=>$this->config->item('popup_alert_heading'),
										'location'=>''
									];
									echo json_encode($res);
									die;
								}
							
							
								$project_locality_id = 0;$project_county_id = 0;$escrow_payment_method = 'N';
								$offline_payment_method = 'N';$upgrade_type_featured = 'N';$upgrade_type_urgent = 'N';
								$upgrade_type_sealed = 'N';$upgrade_type_hidden = 'N';$min_budget = 0;$max_budget = 0;$not_sure_dropdown_option_selected = 'N';
								$confidential_dropdown_option_selected = 'N';$postal_code_id = 0;
								$project_type = "fixed";
								
								if($this->input->post('escrow_payment_method') == 'Y'){
									$escrow_payment_method = $this->input->post('escrow_payment_method');
								}
								
								if($this->input->post ('offline_payment_method') == 'Y'){
									$offline_payment_method = $this->input->post ('offline_payment_method');
								}
								if(!empty($this->input->post ('project_budget'))){
									$project_budget = $this->input->post ('project_budget');
									if($project_budget == 'confidential_dropdown_option_selected'){
										$confidential_dropdown_option_selected = 'Y';
									}else if($project_budget == 'not_sure_dropdown_option_selected'){
										$not_sure_dropdown_option_selected = 'Y';
									}else{
										$project_budget_array = explode("_",$this->input->post ('project_budget'));
										$min_budget = $project_budget_array[0];
										$max_budget = $project_budget_array[1]; 
									}
								}
								//if(!empty($this->input->post ('location_option'))){
									if(!empty($this->input->post ('project_locality_id'))){
									$project_locality_id = $this->input->post ('project_locality_id');
									}if(!empty($this->input->post ('project_county_id'))){
										$project_county_id = $this->input->post ('project_county_id');
									}
								
								//}
								if(!empty($this->input->post ('upgrade_type_featured'))){
									$upgrade_type_featured = 'Y';
								}
								if(!empty($this->input->post ('upgrade_type_hidden'))){
									$upgrade_type_hidden = 'Y';
								}
								if(!empty($this->input->post ('upgrade_type_sealed'))){
									$upgrade_type_sealed = 'Y';
								}
								if(!empty($this->input->post ('upgrade_type_urgent'))){
									$upgrade_type_urgent = 'Y';
								}
								if(!empty($this->input->post('project_county_id')) && !empty($this->input->post ('project_locality_id'))){
								
									$postal_code_id = $this->input->post('project_postal_code_id');
									
								}
								if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
									$project_type = $this->input->post('project_type');
								}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
									$project_type = 'fulltime';
								}
								$awaiting_moderation_project_data = array (
									'project_id'=>$temp_project_id,
									'project_owner_id'=>$user[0]->user_id,
									'project_submission_to_moderation_date'=>date('Y-m-d H:i:s'),
									'project_title'=>trim($this->input->post('project_title')),
									'project_description'=>trim($this->input->post('project_description')),
									'locality_id'=>$project_locality_id,
									'county_id'=>$project_county_id,
									'postal_code_id'=>$postal_code_id,
									'project_type'=>$project_type,
									'min_budget'=>$min_budget,
									'max_budget'=>$max_budget,
									'not_sure_dropdown_option_selected'=>$not_sure_dropdown_option_selected,
									'confidential_dropdown_option_selected'=>$confidential_dropdown_option_selected,
									'escrow_payment_method'=>$escrow_payment_method,
									'offline_payment_method'=>$offline_payment_method,
									'featured'=>$upgrade_type_featured,
									'urgent'=>$upgrade_type_urgent,
									'sealed'=>$upgrade_type_sealed,
									'hidden'=>$upgrade_type_hidden
										
								);
								
								// @sid code to set auto approval date into projects_awaiting_moderation
								$auto_approval_flag = false; // This flag is used to identify where the entry of temporary project table move either [awaiting moderation / open for bidding]
								
								
								// @sid this code block is used to update user account balance based on project upgrade 
								
								// check the user account balance,bonus balance,account balance is sufficient for purchase upgrade
								$count_user_featured_membership_included_upgrades_monthly = $this->Post_project_model->count_user_featured_membership_included_upgrades_monthly($user[0]->user_id); // count user membership featured  upgrade
				
								$count_user_urgent_membership_included_upgrades_monthly = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user[0]->user_id);// count user membership urgent upgrade
								
								$count_user_sealed_membership_included_upgrades_monthly = $this->Post_project_model->count_user_sealed_membership_included_upgrades_monthly($user[0]->user_id);// count user membership sealed upgrade
								
								$count_user_hidden_membership_included_upgrades_monthly = $this->Post_project_model->count_user_hidden_membership_included_upgrades_monthly($user[0]->user_id);// count user membership hidden upgrade
								
								$upgraded_project_price = 0;
								
								
								if($upgrade_type_featured == 'Y'){
									if($user_membership_plan_detail['included_number_featured_upgrades'] != '-1' && $count_user_featured_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_featured_upgrades']){
										
										$upgraded_project_price += $this->config->item('project_upgrade_price_featured');
									}
								}
								if($upgrade_type_urgent == 'Y'){
									if($user_membership_plan_detail['included_number_urgent_upgrades'] != '-1'&& $count_user_urgent_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_urgent_upgrades']){
										$upgraded_project_price += $this->config->item('project_upgrade_price_urgent');
									}
								}
								if($upgrade_type_sealed == 'Y'){
									
									if($user_membership_plan_detail['included_number_sealed_upgrades'] != '-1'&& $count_user_sealed_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_sealed_upgrades']){
										$upgraded_project_price += $this->config->item('project_upgrade_price_sealed');
									}
									//$upgraded_project_price += $this->config->item('project_upgrade_price_sealed');
								}
								if($upgrade_type_hidden == 'Y'){
								
									if($user_membership_plan_detail['included_number_hidden_upgrades'] != '-1'&& $count_user_sealed_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_hidden_upgrades']){
										$upgraded_project_price += $this->config->item('project_upgrade_price_hidden');
									}
								
									/* $upgraded_project_price += $this->config->item('project_upgrade_price_hidden'); */
								}
								
								if(floatval($upgraded_project_price) > 0){
									$total_user_balance = $user_detail['bonus_balance'] + $user_detail['signup_bonus_balance'] + $user_detail['user_account_balance'];
									if(floatval($upgraded_project_price) > floatval($total_user_balance) ){
									
										if(($upgrade_type_featured == 'Y' && $upgrade_type_urgent == 'Y') || ($upgrade_type_featured == 'Y' && $upgrade_type_urgent == 'Y' && $upgrade_type_sealed == 'Y') || ($upgrade_type_featured == 'Y' && $upgrade_type_sealed == 'Y') || ($upgrade_type_urgent == 'Y' && $upgrade_type_sealed == 'Y')|| ($upgrade_type_sealed == 'Y' && $upgrade_type_hidden == 'Y')){
										$error_msg = $this->config->item('user_post_upgraded_project_insufficient_funds_error_message_plural');
										}else{
											$error_msg = $this->config->item('user_post_upgraded_project_insufficient_funds_error_message_singular');
										}
								
										$res = array(
												'status' => 400,
												'location' => '',
												'error' => $error_msg, // define in post_project_custom config
												'popup_heading'=>$this->config->item('popup_alert_heading')
											);
										echo json_encode($res);
										die;
									}
								}
								if($upgrade_type_featured == 'Y') {
									$project_upgrade_array['featured'] = 'Y';
									}
								if($upgrade_type_urgent == 'Y') {
									$project_upgrade_array['urgent'] = 'Y';
								}
								if($upgrade_type_sealed == 'Y') {
									$project_upgrade_array['sealed'] = 'Y';
								}
								if($upgrade_type_hidden == 'Y') {
									$project_upgrade_array['hidden'] = 'Y';
								}

								$auto_approve_min = get_project_auto_approve_min_time($project_upgrade_array,$user_detail['current_membership_plan_id']);
								if($auto_approve_min != 0) {
									$awaiting_moderation_project_data['auto_approval_date'] = date('Y-m-d H:i:s',$auto_approve_min);
								} else {
									$auto_approval_flag = true;
								}
								
								if($auto_approval_flag) {
									$upgrades = [
										'featured' => $upgrade_type_featured,
										'urgent' => $upgrade_type_urgent,
										'sealed' => $upgrade_type_sealed,
										'hidden' => $upgrade_type_hidden
									];
									$project_expire_date = $this->Post_project_model->get_project_correct_expiration_date($upgrades);
									$open_for_bidding_project_data = [
										'project_id'=>$temp_project_id,
										'project_owner_id'=>$user[0]->user_id,
										'project_posting_date' => date('Y-m-d H:i:s'),
										'project_expiration_date' => $project_expire_date,
										'project_title'=>trim($this->input->post('project_title')),
										'project_description'=>trim($this->input->post('project_description')),
										'locality_id'=>$project_locality_id,
										'county_id'=>$project_county_id,
										'postal_code_id' => $postal_code_id,
										'project_type'=>$project_type,
										'min_budget'=>$min_budget,
										'max_budget'=>$max_budget,
										'not_sure_dropdown_option_selected'=>$not_sure_dropdown_option_selected,
										'confidential_dropdown_option_selected'=>$confidential_dropdown_option_selected,
										'escrow_payment_method'=>$escrow_payment_method,
										'offline_payment_method'=>$offline_payment_method,
										'featured'=>$upgrade_type_featured,
										'urgent'=>$upgrade_type_urgent,
										'sealed'=>$upgrade_type_sealed,
										'hidden'=>$upgrade_type_hidden,
										'views' => 0,
										'revisions'=> 0
									];
									
									$this->db->insert ('projects_open_bidding', $open_for_bidding_project_data); // save data in fixed_budget_projects_open_bidding table from post project form

									$publish_project = [
										'project_id' => $temp_project_id,
										'project_owner_id' => $user[0]->user_id,
										'project_posting_date' => date('Y-m-d H:i:s'),
										'project_title' => trim($this->input->post('project_title')),
										'project_type' => $project_type
									];
									$this->db->insert('users_alltime_published_projects_tracking', $publish_project);

									######### save use activity log message
									$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$open_for_bidding_project_data['project_id'];
									$project_title = trim($open_for_bidding_project_data['project_title']);
									if($open_for_bidding_project_data['project_type'] == 'fulltime'){
										$publish_project_log_message = $this->config->item('post_fulltime_project_directly_move_open_bidding_user_activity_log_displayed_message_sent_to_po');
									}else{
										$publish_project_log_message = $this->config->item('post_project_directly_move_open_bidding_user_activity_log_displayed_message_sent_to_po');
									}
									$publish_project_log_message = str_replace(array('{project_url_link}','{project_title}'),array($project_url_link,htmlspecialchars($project_title)),$publish_project_log_message);
									user_display_log($publish_project_log_message);
									// insert the data regrading purchase traking and refresh sequence and manage the user bonus balance and account balance
									$this->Post_project_model->user_project_upgrade_purchase_refresh_sequence_tracking_save($open_for_bidding_project_data,$user[0]->user_id); 
									
									$parent_categories_id = [];

									foreach($this->input->post('project_category') as $project_category_key=>$project_category_value){
										$project_category_id = 0;
										$project_parent_category_id = 0;
									
										if(!empty($project_category_value['project_parent_category'])){
											if(isset($project_category_value['project_child_category']) && !empty($project_category_value['project_child_category']))
											{
												$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);

												$check_project_child_category_exist = $this->Post_project_model->check_project_child_category_exist($project_category_value['project_parent_category'],$project_category_value['project_child_category']);	
												if($check_project_parent_category_exist){
													if($check_project_child_category_exist){
														
														$project_category_id = $project_category_value['project_child_category'];
														$project_parent_category_id = $project_category_value['project_parent_category'];
													
													}else{
													
														$project_category_id =  $project_category_value['project_parent_category'];
														$project_parent_category_id = 0;
														
													}
												}
											}else{
												
												$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);
												if($check_project_parent_category_exist){
												
													$project_category_id =  $project_category_value['project_parent_category'];
													$project_parent_category_id = 0;
												
												}
											}
										}
										if(!empty($project_category_id) || !empty($project_parent_category_id)){
											$this->db->insert ('projects_categories_listing_tracking', array(
												'project_id' => $temp_project_id,
												'project_category_id' => $project_category_id,
												'project_parent_category_id' => $project_parent_category_id 
											));
											if($project_parent_category_id != 0) {
												array_push($parent_categories_id, $project_parent_category_id);
											} else {
												array_push($parent_categories_id, $project_category_id);
											}
										}
										// save data in projects_categories_listing_tracking table from post project form
										
									}
									if(!empty($this->input->post('project_tag'))){
										foreach($this->input->post('project_tag') as $project_tag_key){
											if(!empty($project_tag_key['tag_name'])){
												$this->db->insert ('projects_tags', array('project_id' => $temp_project_id,
												'project_tag_name' => trim($project_tag_key['tag_name'])));
												// save data in projects_tags table from post project form
											}
										}	
									}
									
									// move data from temp_projects_attachments table to projects_attachments table
									$this->db->select('temp_project_attachment_name');
									$this->db->from('temp_projects_attachments');
									$this->db->where('temp_project_id',$temp_project_id);
									$this->db->order_by('id',"asc");
									$temp_project_attachment_result = $this->db->get();
									$temp_project_attachment_data = $temp_project_attachment_result->result_array();
									// connect ftp to move attachment
									$this->load->library('ftp');
									$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
									$config['ftp_username'] = FTP_USERNAME;
									$config['ftp_password'] = FTP_PASSWORD;
									$config['ftp_port'] 	= FTP_PORT;
									$config['debug']    = TRUE;
									$this->ftp->connect($config); 
									$temp_dir = TEMP_DIR;
									$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
									$users_ftp_dir 	= USERS_FTP_DIR; 
									$projects_ftp_dir = PROJECTS_FTP_DIR;
									$projects_temp_dir = PROJECT_TEMPORARY_DIR;
									$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
									$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
									$profile_folder     = $user[0]->profile_name;
									
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$temp_project_id.DIRECTORY_SEPARATOR);
									
									
									//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
									//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir, 0777);// create open for bidding directory in projects folder
									//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$temp_project_id , 0777); // create the directory by using temporary project id
									if(!empty($temp_project_attachment_data)){
										//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$temp_project_id.$project_owner_attachments_dir , 0777); // create the owner attachment directory by using temporary project id
									
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$temp_project_id.$project_owner_attachments_dir);
									
										foreach($temp_project_attachment_data as $attachment_key => $attachment_value){
										
											if(!empty($attachment_value['temp_project_attachment_name'])){
												$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
												$file_size = $this->ftp->get_filesize($source_path);
												
												if($file_size == '-1'){
											
													$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
													$file_size = $this->ftp->get_filesize($source_path);
													
												}
												
												
												if($file_size != '-1'){
													$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$temp_project_id.$project_owner_attachments_dir.$attachment_value['temp_project_attachment_name'];
													
													$this->ftp->move($source_path, $destination_path);
													$this->db->insert ('projects_attachments', array('project_id'=>$temp_project_id,'project_attachment_name'=> $attachment_value['temp_project_attachment_name']));
												}
											}
										}
										$this->db->delete('temp_projects_attachments', array('temp_project_id' => $temp_project_id));		
										
									}
									if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id))){
										$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR);// delete project directory 
									}
									$this->ftp->close();

									if(!empty($open_for_bidding_project_data['hidden']) && $open_for_bidding_project_data['hidden'] == 'N') {
										// insert into projects_refresh_sequence_tracking table
										$time_arr = explode(':', $this->config->item('standard_project_refresh_sequence'));
										$check_valid_arr = array_map('getInt', $time_arr); 
										$valid_time_arr = array_filter($check_valid_arr);
										$next_refresh_date = null;
										if(!empty($valid_time_arr)) {
											$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
											$refresh_sequence_data = [
												'project_id' => $temp_project_id,
												'project_last_refresh_time' => null,
												'project_next_refresh_time' => $next_refresh_date,
												'project_upgrade_refresh_sequence_standard'=>$this->config->item('standard_project_refresh_sequence')
											];

											$standard_refresh_sequence_cnt = $this->db->from('standard_projects_refresh_sequence_tracking')->where(['project_id' => $temp_project_id])->count_all_results();
											if($standard_refresh_sequence_cnt == 0) {
												$this->db->insert('standard_projects_refresh_sequence_tracking', $refresh_sequence_data);
											}
										}

										// insert data in projects latest project refresh sequence tracking
										$latest_project_refresh_tracking = [
											'project_id' => $temp_project_id,
											'project_last_refresh_time' => date('Y-m-d H:i:s'),
											'project_next_refresh_time' => $next_refresh_date
										];
										$this->db->insert('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking);
										
										// save data into users newly posted projects sent notification tracking table
										$category_mapping_data = $this->Post_project_model->get_projects_professionals_categories_mapping_data_based_on_categories_id($parent_categories_id);
										
										if(!empty($category_mapping_data)) {
											$this->Post_project_model->add_data_in_to_users_new_posted_projects_sent_notification_table($category_mapping_data, $parent_categories_id, $open_for_bidding_project_data);
										}
										
										// save data into users newly posted projects sent notification tracking table when project posted by favorite employer
										$users = $this->Post_project_model->get_users_id_from_favorite_employer_id($open_for_bidding_project_data['project_owner_id']);
										
										if(!empty($users)) {
											$this->Post_project_model->add_data_in_to_users_new_posted_projects_sent_notification_table_favorite_employer($users, $parent_categories_id, $open_for_bidding_project_data);
										}
										//pre('here');
									}

									// trigger socket event to update latest project section on user dashboard
									$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateLatestProjectOnUserDashboard/".$user[0]->user_id.'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
									$options = array(
										CURLOPT_RETURNTRANSFER => true,
										CURLOPT_SSL_VERIFYPEER => false
									);
									try {
										$ch = curl_init( $url );
										curl_setopt_array( $ch, $options );
										curl_exec( $ch );
										curl_close( $ch );
									} catch(Exception $e) {
									}		
										
								} else {
									
									$this->db->insert ('projects_awaiting_moderation', $awaiting_moderation_project_data); 
									// save data in projects_awaiting_moderation table from post project form
									
									######### save use activity log message
									$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$awaiting_moderation_project_data['project_id'];
									$project_title = trim($awaiting_moderation_project_data['project_title']);
									if($awaiting_moderation_project_data['project_type'] == 'fulltime'){
										$publish_project_log_message = $this->config->item('fulltime_project_submited_by_po_for_awaiting_moderation_user_activity_log_displayed_message_sent_to_po');
									}else{
										$publish_project_log_message = $this->config->item('project_submited_by_po_for_awaiting_moderation_user_activity_log_displayed_message_sent_to_po');
									}
									$publish_project_log_message = str_replace(array('{project_url_link}','{project_title}'),array($project_url_link,htmlspecialchars($project_title)),$publish_project_log_message);
									user_display_log($publish_project_log_message);
									
									
									
									// insert the data regrading purchase tracking and manage the user bonus balance and account balance
									$this->Post_project_model->user_project_upgrade_purchase_tracking_save($awaiting_moderation_project_data,$user[0]->user_id);
									
									foreach($this->input->post('project_category') as $project_category_key=>$project_category_value){
										$project_category_id = 0;
										$project_parent_category_id = 0;
										if(!empty($project_category_value['project_parent_category'])){
											if(isset($project_category_value['project_child_category']) && !empty($project_category_value['project_child_category']))
											{
											
												$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);

												$check_project_child_category_exist = $this->Post_project_model->check_project_child_category_exist($project_category_value['project_parent_category'],$project_category_value['project_child_category']);
												if($check_project_parent_category_exist){
													if($check_project_child_category_exist){
														$project_category_id = $project_category_value['project_child_category'];
														$project_parent_category_id = $project_category_value['project_parent_category'];
													
													}else{
													
														$project_category_id =  $project_category_value['project_parent_category'];
														$project_parent_category_id = 0;
														
													}
												}
											}else{
											
												$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);
												if($check_project_parent_category_exist){
													
													$project_category_id =  $project_category_value['project_parent_category'];
													$project_parent_category_id = 0;
												
												}
											}
											
											if(!empty($project_category_id) || !empty($project_parent_category_id)){
												$this->db->insert ('awaiting_moderation_projects_categories_listing_tracking', array(
													'project_id' => $temp_project_id,
													'awaiting_moderation_project_category_id' => $project_category_id,
													'awaiting_moderation_project_parent_category_id' => $project_parent_category_id
												));
											}
											// save data in awaiting_moderation_projects_categories_listing_tracking table from post project form
										}	
									}
									
									if(!empty($this->input->post('project_tag'))){
										foreach($this->input->post('project_tag') as $project_tag_key){
											if(!empty($project_tag_key['tag_name'])){
												$this->db->insert ('awaiting_moderation_projects_tags', array('project_id' => $temp_project_id,
												'awaiting_moderation_project_tag_name' => $project_tag_key['tag_name']));
												// save data in awaiting_moderation_projects_tags table from post project form
											}
										}	
									}
									// move data from temp_projects_attachments table to awaiting_moderation_projects_attachments table
									$this->db->select('temp_project_attachment_name');
									$this->db->from('temp_projects_attachments');
									$this->db->where('temp_project_id',$temp_project_id);
									$this->db->order_by('id',"asc");
									$temp_project_attachment_result = $this->db->get();
									$temp_project_attachment_data = $temp_project_attachment_result->result_array();
									// connect ftp to move attachment
									$this->load->library('ftp');
									$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
									$config['ftp_username'] = FTP_USERNAME;
									$config['ftp_password'] = FTP_PASSWORD;
									$config['ftp_port'] 	= FTP_PORT;
									$config['debug']    = TRUE;
									$this->ftp->connect($config); 
									$temp_dir = TEMP_DIR;
									$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
									$users_ftp_dir 	= USERS_FTP_DIR; 
									$projects_ftp_dir = PROJECTS_FTP_DIR;
									$projects_temp_dir = PROJECT_TEMPORARY_DIR;
									$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
									$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
									$profile_folder     = $user[0]->profile_name;
									
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$temp_project_id.DIRECTORY_SEPARATOR);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$temp_project_id.$project_owner_attachments_dir);
									
									
									//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
									//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir, 0777);// create temporary directory in projects folder
									//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$temp_project_id.DIRECTORY_SEPARATOR , 0777); // create the directory by using temporary project id
									//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$temp_project_id.$project_owner_attachments_dir , 0777); // create the directory by using temporary project id
									if(!empty($temp_project_attachment_data)){
										foreach($temp_project_attachment_data as $attachment_key => $attachment_value){
										
											if(!empty($attachment_value['temp_project_attachment_name'])){
												$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
												$file_size = $this->ftp->get_filesize($source_path);
												
												if($file_size == '-1'){
											
													$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
													$file_size = $this->ftp->get_filesize($source_path);
											
												}
												
												
												
												
												
												if($file_size != '-1'){
													$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$temp_project_id.$project_owner_attachments_dir .$attachment_value['temp_project_attachment_name'];
													
													$this->ftp->move($source_path, $destination_path);
													$this->db->insert ('awaiting_moderation_projects_attachments', array('project_id'=>$temp_project_id,'awaiting_moderation_project_attachment_name'=> $attachment_value['temp_project_attachment_name']));
												}
											}
										}
										$this->db->delete('temp_projects_attachments', array('temp_project_id' => $temp_project_id));
										
									}
									if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id))){
										$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR);// delete project directory 
									}
									$this->ftp->close();
									// trigger socket event to update latest project section on user dashboard
									// $url = PROJECT_MANAGEMENT_SOCKET_URL."/updateProjectListingOnDashboardByStatus/".$user[0]->user_id.'/awaiting_moderation/""?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
									
									// $options = array(
									// 	CURLOPT_RETURNTRANSFER => true,
									// 	CURLOPT_SSL_VERIFYPEER => false
									// );
									// try {
									// 	$ch = curl_init( $url );
									// 	curl_setopt_array( $ch, $options );
									// 	curl_exec( $ch );
									// 	curl_close( $ch );
									// } catch(Exception $e) {
									// 	echo $e->getMessage();
									// }		
								}
								
								########## Delete the racord from tempoary table 
								$this->db->delete('temp_projects_tags', array('temp_project_id' => $temp_project_id));
								$this->db->delete('temp_projects_categories_listing_tracking', array('temp_project_id' => $temp_project_id));
								$this->db->delete('temp_projects', array('temp_project_id' => $temp_project_id));

								

								$msg['status'] = 'SUCCESS';
								$msg['message'] = '';
								echo json_encode ($msg);die;
								
							
							} else {
								echo json_encode ($response);
							}
						} else {
							$msg['status'] = 'FAILED';
							$msg['message'] = '';
							echo json_encode ($msg);
						}
					} else {
						$msg['status'] = 'FAILED';
						$msg['message'] = '';
						echo json_encode ($msg);
					}
				}else{
					$msg['status'] = 'FAILED';
					$msg['message'] = '';
					echo json_encode ($msg);
				}
			} else {
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				if(!empty($temp_project_data)){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
				}	
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
	This function is used to return the list of valid project temporary attachments if any attachment is not exist in disk then this will remove the entry from table also.
	*/
	public function get_tempoary_project_attachments($temp_project_id,$profile_folder){
		/* if(!empty($temp_project_id) && !empty($user_profile_name)){ */
			########## fetch the temp project attachments ###
			$this->db->select('*');
			$this->db->from('temp_projects_attachments');
			$this->db->where('temp_project_id',$temp_project_id);
			$this->db->order_by('id',"asc");
			$temp_project_attachment_result = $this->db->get();
			$temp_project_attachment_data = $temp_project_attachment_result->result_array();
			
			$project_attachment_array = array();
			if(!empty($temp_project_attachment_data)){
			
			
				$temp_dir = TEMP_DIR;
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$projects_temp_dir = PROJECT_TEMPORARY_DIR;
				$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
				
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] 	= FTP_PORT;
				$config['debug']    = TRUE;
				$this->ftp->connect($config);
				
				foreach($temp_project_attachment_data as $attachment_key){
					if($this->session->userdata ('user')){
						$source_path =  $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_key['temp_project_attachment_name'];
						$file_size = $this->ftp->get_filesize($source_path);
					}else{
					
						//if($file_size == '-1'){
							$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_key['temp_project_attachment_name'];
							$file_size = $this->ftp->get_filesize($source_path);
						
						//}
					}
					
					
					if($file_size != '-1'){
						$project_attachment['id'] = $attachment_key['id'];
						$project_attachment['temp_project_id'] = $attachment_key['temp_project_id'];
						$project_attachment['temp_project_attachment_name'] = $attachment_key['temp_project_attachment_name'];
						$project_attachment['size']  = number_format($file_size/1024). 'KB';
						$project_attachment_array[] = $project_attachment;
					}/* else{
						$this->db->delete('temp_projects_attachments', array('id' => $attachment_key['id'])); 
					} */
				}
				$this->ftp->close();
			}
			return $project_attachment_array;
		/* }else{
			show_404();
		} */
	}
	
	public function show_project_upgrade_message_subscription(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$upgrade_message = '';
				if(!empty($this->input->post())){
				
					$project_type_option  = $this->input->get('project_type_option');
					$user = $this->session->userdata('user');
					$user_id = $user[0]->user_id;
					
					$count_user_featured_membership_included_upgrades_monthly = $this->Post_project_model->count_user_featured_membership_included_upgrades_monthly($user_id); // count user membership featured  upgrade
					
					$count_user_urgent_membership_included_upgrades_monthly = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user_id);// count user membership urgent upgrade
					
					$count_user_sealed_membership_included_upgrades_monthly = $this->Post_project_model->count_user_sealed_membership_included_upgrades_monthly($user_id);// count user membership sealed upgrade
					
					$count_user_hidden_membership_included_upgrades_monthly = $this->Post_project_model->count_user_hidden_membership_included_upgrades_monthly($user_id);// count user membership hidden upgrade
					
					$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
					$user_membership_plan_detail = $this->db->get_where('membership_plans', ['id' => $user_detail['current_membership_plan_id']])->row_array();
					$total_user_upgrade_balance = false;
					if(!empty($this->input->post ('upgrade_type_hidden'))){
						//$total_user_upgrade_balance = true;
						if($user_membership_plan_detail['included_number_hidden_upgrades'] != '-1' && $count_user_hidden_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_hidden_upgrades']){
							$total_user_upgrade_balance = true;
						}
					}
					if(!empty($this->input->post ('upgrade_type_sealed'))){
						//$total_user_upgrade_balance = true;
						if($user_membership_plan_detail['included_number_sealed_upgrades'] != '-1' && $count_user_sealed_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_sealed_upgrades']){
							$total_user_upgrade_balance = true;
						}
					}
					if(!empty($this->input->post ('upgrade_type_featured'))){
						if($user_membership_plan_detail['included_number_featured_upgrades'] != '-1' && $count_user_featured_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_featured_upgrades']){
							$total_user_upgrade_balance = true;
						}
					}
					if(!empty($this->input->post ('upgrade_type_urgent'))){
						if($user_membership_plan_detail['included_number_urgent_upgrades'] != '-1' &&$count_user_urgent_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_urgent_upgrades']){
							$total_user_upgrade_balance = true;
						}
					}
					if($total_user_upgrade_balance){
						$total_bonus_balance = $user_detail['bonus_balance'] + $user_detail['signup_bonus_balance'];
						
						$upgrade_message .= '<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>';
						if($project_type_option == 'post_project'){
							$upgrade_message .= $this->config->item('post_project_disclaimer_payments_for_upgrades_are_final');
						}else{
							$upgrade_message .= $this->config->item('post_fulltime_project_disclaimer_payments_for_upgrades_are_final');
						}
						$upgrade_message .= '</div>';
						
						if(floatval($total_bonus_balance) > 0 ){
							if($project_type_option == 'post_project'){
								$disclaimer_user_agreement_payment_bonus_project_upgrades = $this->config->item('post_project_disclaimer_user_agreement_for_payment_from_bonus_for_project_upgrades');
							}else{
							
								$disclaimer_user_agreement_payment_bonus_project_upgrades = $this->config->item('post_fulltime_project_disclaimer_user_agreement_for_payment_from_bonus_for_project_upgrades');
							}
							$disclaimer_user_agreement_payment_bonus_project_upgrades = str_replace('{bonus_balance}',str_replace(".00","",number_format($total_bonus_balance,  2, '.', ' '))."&nbsp;".CURRENCY,$disclaimer_user_agreement_payment_bonus_project_upgrades);
							$upgrade_message .= '<div class="form-group col-md-12 bonus_balance default_terms_text">
							<div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$disclaimer_user_agreement_payment_bonus_project_upgrades.'</div>';
						}
					}
					
					if(!empty($this->input->post ('upgrade_type_featured'))){
						
						if($user_membership_plan_detail['included_number_featured_upgrades'] != '-1' && $count_user_featured_membership_included_upgrades_monthly <$user_membership_plan_detail['included_number_featured_upgrades']){
							
							$remaining_featured_upgrades = $user_membership_plan_detail['included_number_featured_upgrades'] -$count_user_featured_membership_included_upgrades_monthly;
							if($remaining_featured_upgrades > 0){
							
								if(($remaining_featured_upgrades-1) > 1){
								
									if($project_type_option == 'post_project'){$featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_featured_upgrades_included_membership_available_disclaimer');
									}else{
										$featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_featured_upgrades_included_membership_available_disclaimer');
									}
								
									$featured_upgrades_included_membership_available_disclaimer = str_replace('{remaining_featured_upgrades}',($remaining_featured_upgrades-1),$featured_upgrades_included_membership_available_disclaimer);
								
								}elseif(($remaining_featured_upgrades-1) == 1){
									
									if($project_type_option == 'post_project'){
										$featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_featured_upgrade_included_membership_available_disclaimer');
									}else{
									
										$featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_featured_upgrade_included_membership_available_disclaimer');
									
									}
								
									$featured_upgrades_included_membership_available_disclaimer = str_replace('{remaining_featured_upgrade}',($remaining_featured_upgrades-1),$featured_upgrades_included_membership_available_disclaimer); 
								
								}else if(($remaining_featured_upgrades-1) == 0){
									if($project_type_option == 'post_project'){
										$featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_disclaimer_last_free_featured_upgrade_included_membership_available');
									}else{
										$featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_disclaimer_last_free_featured_upgrade_included_membership_available');
									
									}
								}
								$upgrade_message .='<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$featured_upgrades_included_membership_available_disclaimer.'</div>';
							}
							
						}
						if($user_membership_plan_detail['included_number_featured_upgrades'] == '-1'){
						
							if($project_type_option == 'post_project'){
								$unlimited_featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_unlimited_featured_upgrades_included_membership_available_disclaimer');
							}else{
								$unlimited_featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_unlimited_featured_upgrades_included_membership_available_disclaimer');
							
							}
							$upgrade_message .='<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$unlimited_featured_upgrades_included_membership_available_disclaimer.'</div>';
						}
					}
					if(!empty($this->input->post ('upgrade_type_urgent'))){
						if($user_membership_plan_detail['included_number_urgent_upgrades'] != '-1' && $count_user_urgent_membership_included_upgrades_monthly < $user_membership_plan_detail['included_number_urgent_upgrades']){
						
							$remaining_urgent_upgrades = $user_membership_plan_detail['included_number_urgent_upgrades'] -$count_user_urgent_membership_included_upgrades_monthly;
							
							if($remaining_urgent_upgrades > 0){
								if(($remaining_urgent_upgrades-1) > 1){
									if($project_type_option == 'post_project'){
										
										$urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_urgent_upgrades_included_membership_available_disclaimer');
									
									}else{
										$urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_urgent_upgrades_included_membership_available_disclaimer');
									
									}
								
								$urgent_upgrades_included_membership_available_disclaimer = str_replace('{remaining_urgent_upgrades}',($remaining_urgent_upgrades-1),$urgent_upgrades_included_membership_available_disclaimer);
								}
								else if(($remaining_urgent_upgrades-1) == 1){
								
									if($project_type_option == 'post_project'){
										$urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_urgent_upgrade_included_membership_available_disclaimer');
									}else{
									
										$urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_urgent_upgrade_included_membership_available_disclaimer');
									}
								
									$urgent_upgrades_included_membership_available_disclaimer = str_replace('{remaining_urgent_upgrade}',($remaining_urgent_upgrades-1),$urgent_upgrades_included_membership_available_disclaimer);
								}else if(($remaining_urgent_upgrades-1) == 0){
									if($project_type_option == 'post_project'){
										$urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_disclaimer_last_free_urgent_upgrade_included_membership_available');
									}else{
										$urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_disclaimer_last_free_urgent_upgrade_included_membership_available');
									}
								}
								$upgrade_message .='<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$urgent_upgrades_included_membership_available_disclaimer.'</div>';
								
							}
							
						}
						if($user_membership_plan_detail['included_number_urgent_upgrades'] == '-1'){
						
							if($project_type_option == 'post_project'){
								$unlimited_urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_unlimited_urgent_upgrades_included_membership_available_disclaimer');
							}else{
								$unlimited_urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_unlimited_urgent_upgrades_included_membership_available_disclaimer');
							
							}
							$upgrade_message .='<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$unlimited_urgent_upgrades_included_membership_available_disclaimer.'</div>';
						}
					}
					if(!empty($this->input->post ('upgrade_type_sealed'))){
						if($user_membership_plan_detail['included_number_sealed_upgrades'] != '-1' && $count_user_sealed_membership_included_upgrades_monthly < $user_membership_plan_detail['included_number_sealed_upgrades']){
						
							$remaining_sealed_upgrades = $user_membership_plan_detail['included_number_sealed_upgrades'] -$count_user_sealed_membership_included_upgrades_monthly;
							
							if($remaining_sealed_upgrades > 0){
								if(($remaining_sealed_upgrades-1) > 1){
									if($project_type_option == 'post_project'){
										
										$sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_sealed_upgrades_included_membership_available_disclaimer');
									
									}else{
										$sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_sealed_upgrades_included_membership_available_disclaimer');
									
									}
								
								$sealed_upgrades_included_membership_available_disclaimer = str_replace('{remaining_sealed_upgrades}',($remaining_sealed_upgrades-1),$sealed_upgrades_included_membership_available_disclaimer);
								}
								else if(($remaining_sealed_upgrades-1) == 1){
								
									if($project_type_option == 'post_project'){
										$sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_sealed_upgrade_included_membership_available_disclaimer');
									}else{
									
										$sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_sealed_upgrade_included_membership_available_disclaimer');
									}
								
									$sealed_upgrades_included_membership_available_disclaimer = str_replace('{remaining_sealed_upgrade}',($remaining_sealed_upgrades-1),$sealed_upgrades_included_membership_available_disclaimer);
								}else if(($remaining_sealed_upgrades-1) == 0){
									if($project_type_option == 'post_project'){
										$sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_disclaimer_last_free_sealed_upgrade_included_membership_available');
									}else{
										$sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_disclaimer_last_free_sealed_upgrade_included_membership_available');
									}
								}
								$upgrade_message .='<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$sealed_upgrades_included_membership_available_disclaimer.'</div>';
								
							}
							
						}
						if($user_membership_plan_detail['included_number_sealed_upgrades'] == '-1'){
						
							if($project_type_option == 'post_project'){
								$unlimited_sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_unlimited_sealed_upgrades_included_membership_available_disclaimer');
							}else{
								$unlimited_sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_unlimited_sealed_upgrades_included_membership_available_disclaimer');
							
							}
							$upgrade_message .='<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$unlimited_sealed_upgrades_included_membership_available_disclaimer.'</div>';
						}
					}
					if(!empty($this->input->post ('upgrade_type_hidden'))){
						
						if($user_membership_plan_detail['included_number_hidden_upgrades'] != '-1' && $count_user_hidden_membership_included_upgrades_monthly < $user_membership_plan_detail['included_number_hidden_upgrades']){
						
							$remaining_hidden_upgrades = $user_membership_plan_detail['included_number_hidden_upgrades'] -$count_user_hidden_membership_included_upgrades_monthly;
							
							if($remaining_hidden_upgrades > 0){
								if(($remaining_hidden_upgrades-1) > 1){
									
									if($project_type_option == 'post_project'){
										
										$hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_hidden_upgrades_included_membership_available_disclaimer');
									
									}else{
										$hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_hidden_upgrades_included_membership_available_disclaimer');
									
									}
								
								$hidden_upgrades_included_membership_available_disclaimer = str_replace('{remaining_hidden_upgrades}',($remaining_hidden_upgrades-1),$hidden_upgrades_included_membership_available_disclaimer);
								}
								else if(($remaining_hidden_upgrades-1) == 1){
								
									if($project_type_option == 'post_project'){
										$hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_hidden_upgrade_included_membership_available_disclaimer');
									}else{
									
										$hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_hidden_upgrade_included_membership_available_disclaimer');
									}
								
									$hidden_upgrades_included_membership_available_disclaimer = str_replace('{remaining_hidden_upgrade}',($remaining_hidden_upgrades-1),$hidden_upgrades_included_membership_available_disclaimer);
								}else if(($remaining_hidden_upgrades-1) == 0){
									
									if($project_type_option == 'post_project'){
										$hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_disclaimer_last_free_hidden_upgrade_included_membership_available');
									}else{
										$hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_disclaimer_last_free_hidden_upgrade_included_membership_available');
									}
								}
								$upgrade_message .='<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$hidden_upgrades_included_membership_available_disclaimer.'</div>';
								
							}
							
						}
						if($user_membership_plan_detail['included_number_hidden_upgrades'] == '-1'){
						
							if($project_type_option == 'post_project'){
								$unlimited_hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_unlimited_hidden_upgrades_included_membership_available_disclaimer');
							}else{
								$unlimited_hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_unlimited_hidden_upgrades_included_membership_available_disclaimer');
							
							}
							$upgrade_message .='<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$unlimited_hidden_upgrades_included_membership_available_disclaimer.'</div>';
						}
					}
					$msg['status'] = 'SUCCESS';
					$msg['upgrade_message'] = $upgrade_message;
					echo json_encode ($msg);die;
					
				}else{
					$msg['status'] = 'SUCCESS';
					$msg['upgrade_message'] = $upgrade_message;
					echo json_encode ($msg);die;
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
	}
	
	// This function is used two reset the project upgrade amount
	public function reset_temporary_project_upgrade_badge_amount_container(){
		if($this->input->is_ajax_request ()){
		
			if(empty($this->input->post('temp_project_id'))){
				show_custom_404_page(); //show custom 404 page
			}
			$temp_project_id = $this->input->post('temp_project_id');
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
			
				$this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				
				if(empty($temp_project_data)) { // if project not exists it will redirect to dasboard page
					$res = [
						'status' => 400,
						'location'=>VPATH.$this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}
				
				$project_expiration_timestamp = $temp_project_data['project_expiration_date']!= NULL ? strtotime ($temp_project_data['project_expiration_date']) : 0;
				if(empty($project_expiration_timestamp) || $project_expiration_timestamp < time()){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
					$res = [
						'status' => 400,
						'location'=>VPATH . $this->config->item('dashboard_page_url')
					];
					echo json_encode($res);
					die;
				}else{
				
					// update expiration time on user action
					$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
					$upate_data = [
						'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
						'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
					];
					$this->db->where('temp_project_id', $temp_project_id);
					$this->db->update('temp_projects', $upate_data);
				}
			
				$user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				
				$user_detail = $this->db->get_where('users_details', ['user_id' => $user_id])->row_array();
				$user_membership_plan_detail = $this->db->get_where('membership_plans', ['id' => $user_detail['current_membership_plan_id']])->row_array();
				
				
				$upgrade_type_featured_amount_html = '';
				$upgrade_type_urgent_amount_html = '';
				
				$count_user_featured_membership_included_upgrades_monthly = $this->Post_project_model->count_user_featured_membership_included_upgrades_monthly($user_id); // count user membership featured  upgrade
					
				$count_user_urgent_membership_included_upgrades_monthly = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user_id);// count user membership urgent upgrade
				
				$count_user_sealed_membership_included_upgrades_monthly = $this->Post_project_model->count_user_sealed_membership_included_upgrades_monthly($user_id);// count user membership sealed upgrade
				
				$count_user_hidden_membership_included_upgrades_monthly = $this->Post_project_model->count_user_hidden_membership_included_upgrades_monthly($user_id);// count user membership hidden upgrade
				
				if($count_user_featured_membership_included_upgrades_monthly <$user_membership_plan_detail['included_number_featured_upgrades'] || $user_membership_plan_detail['included_number_featured_upgrades'] == '-1'){
				
					$upgrade_type_featured_amount_html = '<span><span id="upgrade_type_featured_amount" data-attr="0">'.$this->config->item('post_project_page_upgrade_free_txt').'</span></span>';
				
				}else{
				
					
				
					$upgrade_type_featured_amount_html = '<span><span id="upgrade_type_featured_amount" data-attr="'.str_replace(" ","",$this->config->item('project_upgrade_price_featured')).'">'.number_format($this->config->item('project_upgrade_price_featured'), 0, '', ' ').'</span> '.CURRENCY.'</span>';
				
				}
				if($count_user_urgent_membership_included_upgrades_monthly <$user_membership_plan_detail['included_number_urgent_upgrades'] || $user_membership_plan_detail['included_number_urgent_upgrades'] == '-1'){
				
					$upgrade_type_urgent_amount_html = '<span><span id="upgrade_type_urgent_amount" data-attr="0">'.$this->config->item('post_project_page_upgrade_free_txt').'</span></span>';
				
				}else{
				
					$upgrade_type_urgent_amount_html = '<span><span id="upgrade_type_urgent_amount" data-attr="'.str_replace(" ","",$this->config->item('project_upgrade_price_urgent')).'">'.number_format($this->config->item('project_upgrade_price_urgent'), 0, '', ' ').'</span> '.CURRENCY.'</span>';
				
				}
				if($count_user_sealed_membership_included_upgrades_monthly <$user_membership_plan_detail['included_number_sealed_upgrades'] || $user_membership_plan_detail['included_number_sealed_upgrades'] == '-1'){
				
					$upgrade_type_sealed_amount_html = '<span><span id="upgrade_type_sealed_amount" data-attr="0">'.$this->config->item('post_project_page_upgrade_free_txt').'</span></span>';
				
				}else{
				
					$upgrade_type_sealed_amount_html = '<span><span id="upgrade_type_sealed_amount" data-attr="'.str_replace(" ","",$this->config->item('project_upgrade_price_sealed')).'">'.number_format($this->config->item('project_upgrade_price_sealed'), 0, '', ' ').'</span> '.CURRENCY.'</span>';
				
				}
				if($count_user_hidden_membership_included_upgrades_monthly <$user_membership_plan_detail['included_number_hidden_upgrades'] || $user_membership_plan_detail['included_number_hidden_upgrades'] == '-1'){
				
					$upgrade_type_hidden_amount_html = '<span><span id="upgrade_type_hidden_amount" data-attr="0">'.$this->config->item('post_project_page_upgrade_free_txt').'</span></span>';
				
				}else{
				
					$upgrade_type_hidden_amount_html = '<span><span id="upgrade_type_hidden_amount" data-attr="'.str_replace(" ","",$this->config->item('project_upgrade_price_hidden')).'">'.number_format($this->config->item('project_upgrade_price_hidden'), 0, '', ' ').'</span> '.CURRENCY.'</span>';
				
				}
					
				$msg['upgrade_type_featured_amount_html'] = $upgrade_type_featured_amount_html;
				$msg['upgrade_type_urgent_amount_html'] = $upgrade_type_urgent_amount_html;
				$msg['upgrade_type_sealed_amount_html'] = $upgrade_type_sealed_amount_html;
				$msg['upgrade_type_hidden_amount_html'] = $upgrade_type_hidden_amount_html;
				$msg['status'] = 'SUCCESS';
				$msg['upgrade_message'] = '';
				echo json_encode ($msg);
			}else{
				/* $this->db->where('temp_project_id', $temp_project_id);
				$temp_project_data = $this->db->get('temp_projects')->row_array();
				if(!empty($temp_project_data)){
					$this->Post_project_model->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
				}	 */
				$msg['status'] = 201;
				$msg['location'] = '';
				echo json_encode($msg);
				die;
			}
			
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	public function test(){
		echo date('Y-m-d H:i:s');die;
	}
	
}
?>