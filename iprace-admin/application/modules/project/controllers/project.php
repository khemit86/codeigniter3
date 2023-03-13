<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Project extends MX_Controller {
	
    //private $auto_model;
	 
	 /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password 
     */
    public function __construct() {
		parent::__construct();
        $this->load->model('project_model');
        $this->load->model('member/member_model');
        $this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->helper('url');    
    }

    public function index() {
	    redirect (base_url());
       
    }

	/* 
	This is used to show the list of projects whoose are awaiting moderations status
	*/
	public function awaiting_approval()
    {	
		 // Row per page
		$rowperpage = PAGING_LIMIT;
		
		include_once '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
		$data['data'] = $this->auto_model->leftPannel();
		$lay['lft'] = "inc/section_left";
		
		$this->db->select ('id'); // count the number of record in projects_draft table			
		$this->db->from ('projects_awaiting_moderation');
			
		$this->db->join('users u', 'u.user_id = projects_awaiting_moderation.project_owner_id', 'left');
		$count_project = $this->db->get()->num_rows();

		//Generate pagination links
		$paginations = generate_pagination_links($count_project, 'project/awaiting_approval', $rowperpage, 3);
		$project_listing = $this->project_model->get_awaiting_moderation_project_list($rowperpage, $paginations['offset']);
		$data["links"]   = $paginations['links'];
		$data['page_no'] = $paginations['current_page_no'];
		$data['total_rows']      = $count_project;
		$data['project_listing'] = $project_listing;
		
   		$this->layout->view('awaiting_moderation_project_listing', $lay, $data);
    }   
	
/**
	 * This is used to show filter open for bidding projects
	 */	
	public function ajax_filter_awaiting_approval($param = '') {
      
		$rowperpage = PAGING_LIMIT;
		$rowno = 0;
		
		$filter_arr = $this->input->post();
  
		$projects = $this->project_model->get_awaiting_moderation_project_list($rowperpage, '0',$filter_arr);
		$project_count = count($projects);
  
		$paginations = generate_pagination_links($project_count, 'project/awaiting_approval', $rowperpage, 3);
  
		$data['total_rows'] = $project_count;
		$page = $paginations['current_page_no'];
  
		$project_listing = $this->project_model->get_awaiting_moderation_project_list($rowperpage, $paginations['offset'],$filter_arr);	
		
		$data['project_listing'] = $project_listing;	
		
		$data["links"] = $paginations['links'];
		$multiplication = $rowperpage * $page;
		$subtraction = ($multiplication - ($rowperpage - count($data['project_listing'])));
		$data['record_per_page'] = count($data['project_listing']) < $rowperpage ? $subtraction : $multiplication;
		$data['page_no'] = ($rowperpage * ($page - 1)) + 1;
  
		$res['status'] = 200;
		$res['data'] = $this->load->view('ajax_awaiting_approval_project_list', $data, true);
		$res['data'] = mb_convert_encoding($res['data'], 'UTF-8', 'UTF-8');
		echo json_encode($res);
		return;
	}
  
		/* 
	* This is used to show the list of open for bidding projects
	*/	
	public function open_for_bidding()
    {		
		
		 // Row per page
		$rowperpage = PAGING_LIMIT;

		// Row position
		$rowno   = 0;
		$page_no = 1;
		if($this->input->get('per_page')){
		  $page_no = $this->input->get('per_page');
		  $rowno   = ($page_no-1) * $rowperpage;
		}

		include_once '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
		$data['data'] = $this->auto_model->leftPannel();
		$lay['lft'] = "inc/section_left";
				
		$this->db->select('projects_open_bidding.id');
		$this->db->from('projects_open_bidding');

		$this->db->join('users u', 'u.user_id = projects_open_bidding.project_owner_id', 'left');
		$this->db->where('projects_open_bidding.project_expiration_date >= NOW()');	
		$count_project = $this->db->get()->num_rows (); 

		//Generate pagination links
		$paginations = generate_pagination_links($count_project, 'project/open_for_bidding', $rowperpage, 3);
		$project_listing = $this->project_model->get_open_for_bidding_project_list($rowperpage, $paginations['offset']);
		$data["links"]   = $paginations['links'];
		$data['page_no'] = $paginations['current_page_no'];
		$data['total_rows']      = $project_listing['total'];
		$data['project_listing'] = $project_listing['data'];		
	  $this->layout->view('open_for_bidding_project_listing', $lay, $data);

	}

	/**
	 * This is used to show filter open for bidding projects
	 */	
	public function ajax_filter_open_for_bidding($param = '') {
      
		$rowperpage = PAGING_LIMIT;
		$rowno = 0;
		
		$filter_arr = $this->input->post();
  
		$projects = $this->project_model->get_open_for_bidding_project_list($rowperpage, '0',$filter_arr);
		$project_count = $projects['total'];
  
		$paginations = generate_pagination_links($project_count, 'project/open_for_bidding', $rowperpage, 3);
  
		$data['total_rows'] = $project_count;
		$page = $paginations['current_page_no'];
  
		$project_listing = $this->project_model->get_open_for_bidding_project_list($rowperpage, $paginations['offset'],$filter_arr);	
		
		$data['project_listing'] = $project_listing['data'];	
		
		$data["links"] = $paginations['links'];
		$multiplication = $rowperpage * $page;
		$subtraction = ($multiplication - ($rowperpage - count($data['project_listing'])));
		$data['record_per_page'] = count($data['project_listing']) < $rowperpage ? $subtraction : $multiplication;
		$data['page_no'] = ($rowperpage * ($page - 1)) + 1;
  
		$res['status'] = 200;
		$res['data'] = $this->load->view('ajax_open_for_bidding_project_list', $data, true);
		$res['data'] = mb_convert_encoding($res['data'], 'UTF-8', 'UTF-8');
		echo json_encode($res);
		return;
	}
  
	/**
	 * This is used to show the list of awarded projects
	*/
	public function awarded() {
		
		// Row per page
	   $rowperpage = PAGING_LIMIT;

	   include_once '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
	   $data['data'] = $this->auto_model->leftPannel();
	   $lay['lft'] = "inc/section_left";
	   $this->db->select ('id');// count the number of record in projects_draft table	
	   $this->db->from ('fixed_budget_projects_awarded');		
	   $this->db->join('users u', 'u.user_id = fixed_budget_projects_awarded.project_owner_id', 'left');

	   $count_project = $this->db->get ()->num_rows ();

	   $this->db->select ('id');// count the number of record in projects_draft table	
	   $this->db->from ('hourly_rate_based_projects_awarded');		
	   $this->db->join('users u', 'u.user_id = hourly_rate_based_projects_awarded.project_owner_id', 'left');

	   $count_project += $this->db->get ()->num_rows ();

	   //Generate pagination links
	   $paginations = generate_pagination_links($count_project, 'project/awarded', $rowperpage, 3);
	   $project_listing = $this->project_model->get_awarded_project_list($rowperpage, $paginations['offset']);
	   $data["links"]   = $paginations['links'];
	   $data['page_no'] = $paginations['current_page_no'];
	   $data['total_rows']      = $count_project;
	   $data['project_listing'] = $project_listing;

	   $data['displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected'] = $config['displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected'];
	   $data['displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected'] = $config['displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected'];
	   $data['post_project_budget_range_more_then'] = $config['post_project_budget_range_more_then'];
	   
	   //$data['post_project_budget_range_between'] = $config['post_project_budget_range_between'];
	   $data['post_project_budget_range_and'] = $config['post_project_budget_range_and'];
	   
	   // $data['project_listing'] = $project_listing;
		  $this->layout->view('awarded_project_listing', $lay, $data);
   }
   
	/**
	* This is used to filter awarder projects
	*/	
   public function ajax_filter_awarded($param = '') {
	 
	   $rowperpage = PAGING_LIMIT;
	   $rowno = 0;
	   if($this->input->post()){
	   $filter_arr = $this->input->post();
	   }else{
		$filter_arr = [];
	   }
	   
	   $projects = $this->project_model->get_awarded_project_list('', '',$filter_arr);
	   $count_project = count($projects);
 
	   $paginations = generate_pagination_links($count_project, 'project/open_for_bidding', $rowperpage, 3);
 
	   $page = $paginations['current_page_no'];
 
	   $project_listing = $this->project_model->get_awarded_project_list($rowperpage, $paginations['offset'],$filter_arr);	
	   
	   $data['project_listing'] = $project_listing;	
	   $data['total_rows'] = $count_project;

	   $data["links"] = $paginations['links'];
	   $multiplication = $rowperpage * $page;
	   $subtraction = ($multiplication - ($rowperpage - count($data['project_listing'])));
	   $data['record_per_page'] = count($data['project_listing']) < $rowperpage ? $subtraction : $multiplication;
	   $data['page_no'] = ($rowperpage * ($page - 1)) + 1;
 
	   $res['status'] = 200;
	   $res['data'] = $this->load->view('ajax_awarded_project_list', $data, true);
	   $res['data'] = mb_convert_encoding($res['data'], 'UTF-8', 'UTF-8');
	   echo json_encode($res);
	   return;
   }
 	
	/**
	 * This is used to show the list of in progress projects
	*/
	public function in_progress() {
		
		// Row per page
	   $rowperpage = PAGING_LIMIT;

	   include_once '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
	   $data['data'] = $this->auto_model->leftPannel();
	   $lay['lft'] = "inc/section_left";		

	   $this->db->select ('id'); // count the number of record in projects_draft table			
	   $this->db->from ('fixed_budget_projects_progress');					
	   $this->db->join('users u', 'u.user_id = fixed_budget_projects_progress.project_owner_id', 'left');
	   $count_project = $this->db->get()->num_rows();
		   
	   $this->db->select ('id'); // count the number of record in projects_draft table			
	   $this->db->from ('hourly_rate_based_projects_progress');			
	   $this->db->join('users u', 'u.user_id = hourly_rate_based_projects_progress.project_owner_id', 'left');
	   $count_project += $this->db->get()->num_rows();

	   //Generate pagination links
	   $paginations = generate_pagination_links($count_project, 'project/in_progress', $rowperpage, 3);
	   $project_listing = $this->project_model->get_in_progress_project_list($rowperpage, $paginations['offset']);
	   $data["links"]   = $paginations['links'];
	   $data['page_no'] = $paginations['current_page_no'];
	   $data['total_rows']      = $count_project;
	   $data['project_listing'] = $project_listing;
			   
	   $data['displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected'] = $config['displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected'];
	   $data['displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected'] = $config['displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected'];
	   $data['post_project_budget_range_more_then'] = $config['post_project_budget_range_more_then'];
	   
	   //$data['post_project_budget_range_between'] = $config['post_project_budget_range_between'];
	   $data['post_project_budget_range_and'] = $config['post_project_budget_range_and'];
	   
		  $this->layout->view('in_progress_project_listing', $lay, $data);
   }
   
   /**
	* This is used to filter in progress projects
	*/	
   public function ajax_filter_in_progress($param = '') {
	 
	   $rowperpage = PAGING_LIMIT;
	   $rowno = 0;
	   if($this->input->post()){
	   $filter_arr = $this->input->post();
	   }else{
		$filter_arr = [];
	   }
	   
	   $projects = $this->project_model->get_in_progress_project_list('', '',$filter_arr);
	   $count_project = count($projects);
 
	   $paginations = generate_pagination_links($count_project, 'project/in_progress', $rowperpage, 3);
 
	   $page = $paginations['current_page_no'];
 
	   $project_listing = $this->project_model->get_in_progress_project_list($rowperpage, $paginations['offset'],$filter_arr);	
	   
	   $data['project_listing'] = $project_listing;	
	   $data['total_rows'] =   $count_project;

	   $data["links"] = $paginations['links'];
	   $multiplication = $rowperpage * $page;
	   $subtraction = ($multiplication - ($rowperpage - count($data['project_listing'])));
	   $data['record_per_page'] = count($data['project_listing']) < $rowperpage ? $subtraction : $multiplication;
	   $data['page_no'] = ($rowperpage * ($page - 1)) + 1;
 
	   $res['status'] = 200;
	   $res['data'] = $this->load->view('ajax_in_progress_project_list', $data, true);
	   $res['data'] = mb_convert_encoding($res['data'], 'UTF-8', 'UTF-8');
	   echo json_encode($res);
	   return;
   }	
	
	/**
	 * This is used to show the list of expired projects
	*/
	public function expire() {
		
		// Row per page
	   $rowperpage = PAGING_LIMIT;

	   $config = array();
	   include_once '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
	   $data['data'] = $this->auto_model->leftPannel();
	   $lay['lft'] = "inc/section_left";
	   
	   $total_project = $this->project_model->get_expired_project_list('','');
	   $count_project = count($total_project);

	   //Generate pagination links
	   $paginations = generate_pagination_links($count_project, 'project/expire', $rowperpage, 3);
	   $project_listing = $this->project_model->get_expired_project_list($rowperpage, $paginations['offset']);
	   $data["links"]   = $paginations['links'];
	   $data['page_no'] = $paginations['current_page_no'];
	   $data['total_rows']      = $count_project;
	   $data['project_listing'] = $project_listing;
	   
	   $data['displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected'] = $config['displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected'];
	   
	   $data['displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected'] = $config['displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected'];
	   
	   
	   $data['post_project_budget_range_more_then'] = $config['post_project_budget_range_more_then'];
	   
	   //$data['post_project_budget_range_between'] = $config['post_project_budget_range_between'];
	   $data['post_project_budget_range_and'] = $config['post_project_budget_range_and'];
	   
		  $this->layout->view('expired_project_listing', $lay, $data);
   }

   /**
	* This is used to filter expired projects
	*/	
   public function ajax_filter_expired($param = '') {
	 
	   $rowperpage = PAGING_LIMIT;
	   $rowno = 0;
	   if($this->input->post()){
		   $filter_arr = $this->input->post();
	   }else{
		   $filter_arr = [];
	   }
	   
	   $projects = $this->project_model->get_expired_project_list('', '',$filter_arr);
	   $count_project = count($projects);
 
	   $paginations = generate_pagination_links($count_project, 'project/expire', $rowperpage, 3);
 
	   $page = $paginations['current_page_no'];
 
	   $project_listing = $this->project_model->get_expired_project_list($rowperpage, $paginations['offset'],$filter_arr);	
	   
	   $data['project_listing'] = $project_listing;	
	   $data['total_rows'] =   $count_project;

	   $data["links"] = $paginations['links'];
	   $multiplication = $rowperpage * $page;
	   $subtraction = ($multiplication - ($rowperpage - count($data['project_listing'])));
	   $data['record_per_page'] = count($data['project_listing']) < $rowperpage ? $subtraction : $multiplication;
	   $data['page_no'] = ($rowperpage * ($page - 1)) + 1;
 
	   $res['status'] = 200;
	   $res['data'] = $this->load->view('ajax_expired_project_list', $data, true);
	   $res['data'] = mb_convert_encoding($res['data'], 'UTF-8', 'UTF-8');
	   echo json_encode($res);
	   return;
   }
	
	/**
	 * This is used to show the list of cancel projects 
	*/
	public function canceled() {
		
		// Row per page
	   $rowperpage = PAGING_LIMIT;
	   
	   $data['data'] = $this->auto_model->leftPannel();
	   $lay['lft'] = "inc/section_left";
	   //$config = array();
	   include_once '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
	   //$count_project = $this->db->query('SELECT FOUND_ROWS() as total')->row_array();
	   
	   $total_project = $this->project_model->get_cancelled_project_list();
	   $count_project  = count($total_project);
	   
	   //Generate pagination links
	   $paginations = generate_pagination_links($count_project, 'project/canceled', $rowperpage, 3);
	   $project_listing = $this->project_model->get_cancelled_project_list($rowperpage, $paginations['offset']);
	   $data["links"]   = $paginations['links'];
	   $data['page_no'] = $paginations['current_page_no'];
	   $data['total_rows']      = $count_project;
	   $data['project_listing'] = $project_listing;
			   
		  $this->layout->view('canceled_project_listing', $lay, $data);
   }
   
   /**
	* This is used to filter canceled projects
	*/	
   public function ajax_filter_canceled($param = '') {
	 
	   $rowperpage = PAGING_LIMIT;
	   $rowno = 0;
	   if($this->input->post()){
		   $filter_arr = $this->input->post();
	   }else{
		   $filter_arr = [];
	   }
	   
	   $projects = $this->project_model->get_cancelled_project_list('', '',$filter_arr);
	   $count_project = count($projects);
 
	   $paginations = generate_pagination_links($count_project, 'project/canceled', $rowperpage, 3);
 
	   $page = $paginations['current_page_no'];
 
	   $project_listing = $this->project_model->get_cancelled_project_list($rowperpage, $paginations['offset'],$filter_arr);	
	   
	   $data['project_listing'] = $project_listing;	
	   $data['total_rows'] =   $count_project;

	   $data["links"] = $paginations['links'];
	   $multiplication = $rowperpage * $page;
	   $subtraction = ($multiplication - ($rowperpage - count($data['project_listing'])));
	   $data['record_per_page'] = count($data['project_listing']) < $rowperpage ? $subtraction : $multiplication;
	   $data['page_no'] = ($rowperpage * ($page - 1)) + 1;
 
	   $res['status'] = 200;
	   $res['data'] = $this->load->view('ajax_expired_project_list', $data, true);
	   $res['data'] = mb_convert_encoding($res['data'], 'UTF-8', 'UTF-8');
	   echo json_encode($res);
	   return;
   }
	
	/**
	 * This is used to show the list of completed projects
	*/
	public function completed() {
		
		// Row per page
	   $rowperpage = PAGING_LIMIT;

	   include_once '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
	   $data['data'] = $this->auto_model->leftPannel();
	   $lay['lft'] = "inc/section_left";
		   
	   $this->db->select ('id'); // count the number of record in projects_draft table			
	   $this->db->from ('fixed_budget_projects_completed');
		   
	   $this->db->join('users u', 'u.user_id = fixed_budget_projects_completed.project_owner_id', 'left');
	   $count_project = $this->db->get()->num_rows();

	   $this->db->select ('id'); // count the number of record in projects_draft table			
	   $this->db->from ('hourly_rate_based_projects_completed');			
	   $this->db->join('users u', 'u.user_id = hourly_rate_based_projects_completed.project_owner_id', 'left');
	   $count_project += $this->db->get()->num_rows();

	   
	   //Generate pagination links
	   $paginations = generate_pagination_links($count_project, 'project/completed', $rowperpage, 3);
	   $project_listing = $this->project_model->get_completed_project_list($rowperpage, $paginations['offset']);
	   $data["links"]   = $paginations['links'];
	   $data['page_no'] = $paginations['current_page_no'];
	   $data['total_rows']      = $count_project;
	   $data['project_listing'] = $project_listing;
	   
	   $data['displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected'] = $config['displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected'];
	   $data['displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected'] = $config['displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected'];
	   $data['post_project_budget_range_more_then'] = $config['post_project_budget_range_more_then'];
	   
	   //$data['post_project_budget_range_between'] = $config['post_project_budget_range_between'];
	   $data['post_project_budget_range_and'] = $config['post_project_budget_range_and'];
	   
	   $data['project_listing'] = $project_listing;
		  $this->layout->view('completed_project_listing', $lay, $data);
   }
   
   /**
	* This is used to filter completed projects
	*/	
   public function ajax_filter_completed($param = '') {
	 
	   $rowperpage = PAGING_LIMIT;
	   $rowno = 0;
	   if($this->input->post()){
	   $filter_arr = $this->input->post();
	   }else{
		$filter_arr = [];
	   }
	   
	   $projects = $this->project_model->get_completed_project_list('', '',$filter_arr);
	   $count_project = count($projects);
 
	   $paginations = generate_pagination_links($count_project, 'project/completed', $rowperpage, 3);
 
	   $page = $paginations['current_page_no'];
 
	   $project_listing = $this->project_model->get_completed_project_list($rowperpage, $paginations['offset'],$filter_arr);	
	   
	   $data['project_listing'] = $project_listing;	
	   $data['total_rows']      = $count_project;

	   $data["links"] = $paginations['links'];
	   $multiplication = $rowperpage * $page;
	   $subtraction = ($multiplication - ($rowperpage - count($data['project_listing'])));
	   $data['record_per_page'] = count($data['project_listing']) < $rowperpage ? $subtraction : $multiplication;
	   $data['page_no'] = ($rowperpage * ($page - 1)) + 1;
 
	   $res['status'] = 200;
	   $res['data'] = $this->load->view('ajax_completed_project_list', $data, true);
	   $res['data'] = mb_convert_encoding($res['data'], 'UTF-8', 'UTF-8');
	   echo json_encode($res);
	   return;
   }
 	
	/* 
	This is used to delete the awaiting moderation project
	*/
	public function delete_awaiting_moderation_project($project_id) {
		if(empty($project_id)){
			redirect(base_url() . 'project/awaiting_approval');  
		}
		$project_owner_id = $this->auto_model->getFeild('project_owner_id','projects_awaiting_moderation','project_id',$project_id);
		$project_title = $this->auto_model->getFeild('project_title','projects_awaiting_moderation','project_id',$project_id);
		$project_type = $this->auto_model->getFeild('project_type','projects_awaiting_moderation','project_id',$project_id);
		$profile_folder  = $this->auto_model->getFeild('profile_name','users','user_id',$project_owner_id);
		if(empty($project_owner_id)){
			redirect(base_url() . 'project/awaiting_approval');  
		}
		include '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_projects_custom_config.php';
		
		$this->db->delete('awaiting_moderation_projects_attachments', array('project_id' => $project_id));
		$this->db->delete('awaiting_moderation_projects_categories_listing_tracking', array('project_id' => $project_id));
		$this->db->delete('awaiting_moderation_projects_tags', array('project_id' => $project_id));
		$this->db->delete('projects_awaiting_moderation', array('project_id' => $project_id));
		$this->db->delete('projects_awm_membership_included_upgrades_purchase_tracking', ['project_id' => $project_id, 'project_owner_id' => $project_owner_id]);

		$bonus_money_upgrade_purchase = $this->db->get_where('projects_awm_bonus_based_upgrades_purchase_tracking', ['project_id' => $project_id, 'project_owner_id' => $project_owner_id])->result_array();
		if(!empty($bonus_money_upgrade_purchase)) {
			$reverted_bonus_money = array_sum(array_column($bonus_money_upgrade_purchase, 'project_upgrade_purchase_value'));
			$this->db->where(['user_id' => $project_owner_id]);
			$this->db->set('bonus_balance', 'bonus_balance + '.$reverted_bonus_money, false);
			$this->db->update('users_details');
			$this->db->delete('projects_awm_bonus_based_upgrades_purchase_tracking', ['project_id' => $project_id, 'project_owner_id' => $project_owner_id]);
		}
		
		$real_money_upgrade_purchase = $this->db->get_where('projects_awm_real_money_upgrades_purchase_tracking', ['project_id' => $project_id, 'project_owner_id' => $project_owner_id])->result_array();
		if(!empty($real_money_upgrade_purchase)) {
			$reverted_real_money = array_sum(array_column($real_money_upgrade_purchase, 'project_upgrade_purchase_value'));
			$this->db->where(['user_id' => $project_owner_id]);
			$this->db->set('user_account_balance', 'user_account_balance + '.$reverted_real_money, false);
			$this->db->update('users_details');
			$this->db->delete('projects_awm_real_money_upgrades_purchase_tracking', ['project_id' => $project_id, 'project_owner_id' => $project_owner_id]);
		}
		
		################ genreate the log activity #################
		if($project_type == 'fulltime'){
			$project_activity_log_displayed_message = $config['fulltime_project_deleted_by_admin_user_activity_log_displayed_message_sent_to_po'];
		}else{
			$project_activity_log_displayed_message = $config['project_deleted_by_admin_user_activity_log_displayed_message_sent_to_po'];
		}
		$project_activity_log_displayed_message = str_replace(array("{project_title}"),array(htmlspecialchars($project_title, ENT_QUOTES)),$project_activity_log_displayed_message);
		user_display_log($project_activity_log_displayed_message,$project_owner_id);
		######################################################

		$this->session->set_flashdata('succ_msg', 'Project removed successfully');

		$curl_url = PROJECT_MANAGEMENT_SOCKET_URL."/deleteProject/".$project_owner_id."/".base64_encode($project_title).'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
		
		$options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false
		);
		$ch = curl_init( $curl_url );
		curl_setopt_array( $ch, $options );
		curl_exec( $ch );
		curl_close( $ch );

		// trigger socket event to update awaiting moderation project section on user dashboard
		$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateProjectListingOnDashboardByStatus/".$project_owner_id.'/awaiting_moderation/'.base64_encode($project_title).'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;

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

		$CI = & get_instance();
		$CI->load->library('ftp');
		$conf['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
		$conf['ftp_username'] = FTP_USERNAME;
		$conf['ftp_password'] = FTP_PASSWORD;
		$conf['ftp_port'] = FTP_PORT;
		$conf['debug'] = TRUE;
		try {
			$CI->ftp->connect($conf);
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
			if(!empty($CI->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id))){
				$CI->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id);// delete awaiting_moderation project directory
			}
			
		} catch(Exception $e) {
			
		}	
		if($this->input->get ('search_element')){
			$search_element = $this->input->get ('search_element');
		}else{
			$search_element = '';
		}
		if($this->input->get ('per_page')){
			$page = $this->input->get ('per_page');
		}else{
			$page = 1;
		}	
		$new_counter = $page;
		if($page != null) {
			if($page == 1) {
				$start = 0;
				} else {
				
				$start = ($page - 1) * PAGING_LIMIT;
			}
		} else {
			$start = 0;
		}
		
		$project_listing = $this->project_model->get_awaiting_moderation_project_list($start,PAGING_LIMIT,$search_element);
		
		if(empty($project_listing)){
			
			$page = $new_counter-1;
			if($page != null) {
			
				if($page == 1) {
					$start = 0;
				} else {
					
				$start = ($page - 1) *PAGING_LIMIT;
				}
			} else {
				$start = 0;
				}
		}
		redirect(base_url()."project/awaiting_approval/?search_element=" . $search_element."&per_page=".$page);   
	}
	/**
	* This is used to cancel the open for bidding project
	*/
	public function cancel_open_for_bidding_project($project_id) {
		if(empty($project_id)){
			redirect(base_url() . 'project/open_for_bidding');  
		}
		include '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_projects_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_bidding_fulltime_projects_custom_config.php';
		$open_bidding_project_data = $this->db->get_where('projects_open_bidding', ['project_id' => $project_id])->row_array();
		
		$project_type = $open_bidding_project_data['project_type'];
		
		
		
		/// for fulltime project code awarded bid move to active bid table
		if($project_type == 'fulltime'){	
			
			$awarded_bidding_list = $this->db // get the user detail
			->select('awarded_bid.*,u.profile_name,u.user_id')
			->from('fulltime_projects_awarded_tracking awarded_bid')
			->where('fulltime_project_id', $project_id)
			->join('users u', 'u.user_id = awarded_bid.employee_id', 'left')
			->order_by('awarded_bid.id ASC')
			->get()->result_array();
			$check_fulltime_award_bid_count = count($awarded_bidding_list);
		}	
		
		
		
			
		if(!empty($open_bidding_project_data)) {
			$project_id = $open_bidding_project_data['project_id'];
			$project_owner_id = $open_bidding_project_data['project_owner_id'];

			$project_title = $open_bidding_project_data['project_title'];
			$profile_folder =  $this->auto_model->getFeild('profile_name','users','user_id',$open_bidding_project_data['project_owner_id']);
			unset($open_bidding_project_data['id']);
			unset($open_bidding_project_data['featured']);
			unset($open_bidding_project_data['urgent']);
			$open_bidding_project_data['project_cancellation_date'] = date('Y-m-d H:i:s');
			if($open_bidding_project_data['project_type'] == 'fixed') { // insert into fixed budget project cancelled table
				
				unset($open_bidding_project_data['project_type']);
				$this->db->insert('fixed_budget_projects_cancelled_by_admin', $open_bidding_project_data);
			} else if($open_bidding_project_data['project_type'] == 'hourly') { // insert into fixed budget project cancelled table
				
				unset($open_bidding_project_data['project_type']);
				$this->db->insert('hourly_rate_based_projects_cancelled_by_admin', $open_bidding_project_data);
			} else if($open_bidding_project_data['project_type'] == 'fulltime') {
				$open_bidding_project_data['fulltime_project_id'] = $open_bidding_project_data['project_id'];
				$open_bidding_project_data['employer_id'] = $open_bidding_project_data['project_owner_id'];
				$open_bidding_project_data['fulltime_project_posting_date'] = $open_bidding_project_data['project_posting_date'];
				$open_bidding_project_data['fulltime_project_expiration_date'] = $open_bidding_project_data['project_expiration_date'];
				$open_bidding_project_data['fulltime_project_title'] = $open_bidding_project_data['project_title'];
				$open_bidding_project_data['fulltime_project_description'] = $open_bidding_project_data['project_description'];
				$open_bidding_project_data['fulltime_project_cancellation_date'] = $open_bidding_project_data['project_cancellation_date'];
				$open_bidding_project_data['min_salary'] = $open_bidding_project_data['min_budget'];
				$open_bidding_project_data['max_salary'] = $open_bidding_project_data['max_budget'];

				unset($open_bidding_project_data['project_id']);
				unset($open_bidding_project_data['project_owner_id']);
				unset($open_bidding_project_data['project_posting_date']);
				unset($open_bidding_project_data['project_expiration_date']);
				unset($open_bidding_project_data['project_title']);
				unset($open_bidding_project_data['project_description']);
				unset($open_bidding_project_data['project_cancellation_date']);
				unset($open_bidding_project_data['min_budget']);
				unset($open_bidding_project_data['max_budget']);

				$this->db->insert('fulltime_projects_cancelled_by_admin', $open_bidding_project_data);
			}

			// Remove project attachement based on project id
			$this->db->delete('projects_attachments', ['project_id' => $project_id]);

			// Remove bidder attachments based on project id
			$this->db->delete('projects_active_bids_users_attachments_tracking', ['project_id' => $project_id]);

			// Set next refresh time to null for cancelled project
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('standard_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('featured_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('urgent_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('sealed_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			
			// remove entry from project latest refresh sequence tracking table
			$this->db->delete('projects_latest_refresh_sequence_tracking', array('project_id' => $project_id));
			// remov entry from open bidding table
			$this->db->delete('projects_open_bidding', ['project_id' => $project_id]);
			################ genreate the log activity #################
			$project_url_link = HTTP_WEBSITE_HOST.$config['project_detail_page_url']."?id=".$project_id;
			if($project_type == 'fulltime'){
				
				if($check_fulltime_award_bid_count > 0){
					$project_activity_log_displayed_message = $config['fulltime_project_cancelled_by_admin_awarded_user_activity_log_displayed_message_sent_to_po'];
					
				}else{	
				
					$project_activity_log_displayed_message = $config['fulltime_project_cancelled_by_admin_open_for_bidding_user_activity_log_displayed_message_sent_to_po'];
				}
			}else{
			
				$project_activity_log_displayed_message = $config['project_cancelled_by_admin_open_for_bidding_user_activity_log_displayed_message_sent_to_po'];
			}
			$project_activity_log_displayed_message = str_replace(array("{project_title}", "{project_url_link}"),array(htmlspecialchars($project_title, ENT_QUOTES),$project_url_link),$project_activity_log_displayed_message);
			
			
			
			user_display_log($project_activity_log_displayed_message,$project_owner_id);
			######################################################

			// trigger socket event to update expired project section on user dashboard
			$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateOpenBiddingOrExpiredProjectListOnProjectCancelledByAdmin/".$project_owner_id.'/open/?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
			
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

			//$CI = & get_instance();
			$this->load->library('ftp');
			$conf['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$conf['ftp_username'] = FTP_USERNAME;
			$conf['ftp_password'] = FTP_PASSWORD;
			$conf['ftp_port'] 	= FTP_PORT;
			$conf['debug']    = TRUE;
			try{
				$this->ftp->connect($conf); 
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
				$project_cancelled_dir = PROJECT_CANCELLED_DIR;
				
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir);
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir.$project_id.DIRECTORY_SEPARATOR);
				if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id))) {
					$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id);
				} 
			} catch(Exception $e) {

			}
			
			if($project_type == 'fulltime'){
				if(!empty($awarded_bidding_list)){
					foreach($awarded_bidding_list as $awarded_bid_key => $awarded_bid_value){
						$check_bid_data_exists = $this->db->where(['fulltime_project_id' => $awarded_bid_value['fulltime_project_id'],'employer_id'=>$awarded_bid_value['employer_id'],'employee_id'=>$awarded_bid_value['employee_id']])->from('fulltime_projects_open_bidding_active_applications')->count_all_results();
						
						if($check_bid_data_exists == 0){
						
							$bid_data['fulltime_project_id'] = $awarded_bid_value['fulltime_project_id'];
							$bid_data['employer_id'] = $awarded_bid_value['employer_id'];
							$bid_data['employee_id'] = $awarded_bid_value['employee_id'];
							$bid_data['application_bidding_dropdown_option'] = $awarded_bid_value['application_bidding_dropdown_option'];
							
						
							$bid_data['application_description'] = $awarded_bid_value['application_description'];
							
							$bid_data['application_date'] = $awarded_bid_value['application_date'];
							
							$bid_data['initial_requested_salary'] = $awarded_bid_value['awarded_salary'];
							
							$awarded_bid_activity_log_displayed_message = $config['fulltime_project_cancelled_by_admin_awarded_user_activity_log_displayed_message_sent_to_awarded_sp'];
							
							$awarded_bid_activity_log_displayed_message = str_replace(array("{project_title}", "{project_url_link}"),array(htmlspecialchars($project_title, ENT_QUOTES), $project_url_link),$awarded_bid_activity_log_displayed_message);
							$awarded_bid_activity_log_displayed_message;
							
							$this->db->insert ('fulltime_projects_open_bidding_active_applications', $bid_data);// insert into active bid table
							
							$this->db->delete('fulltime_projects_awarded_tracking', ['fulltime_project_id' => $awarded_bid_value['fulltime_project_id'],'employee_id'=>$awarded_bid_value['employee_id']]); // remove entry from award tracking table
							
							$this->db->delete('projects_active_bids_users_attachments_tracking', ['project_id' => $awarded_bid_value['fulltime_project_id'],'user_id'=>$awarded_bid_value['employee_id']]); // remove entry 
							
							user_display_log($awarded_bid_activity_log_displayed_message,$awarded_bid_value['user_id']);
						}
					}
				}
			}	
			
			
			$this->session->set_flashdata('succ_msg', 'Project cancelled successfully');
			
		}
		//redirect(base_url() . 'project/open_for_bidding');  
		if($this->input->get ('search_element')){
			$search_element = $this->input->get ('search_element');
		}else{
			$search_element = '';
		}
		if($this->input->get ('per_page')){
			$page = $this->input->get ('per_page');
		}else{
			$page = 1;
		}	
		$new_counter = $page;
		if($page != null) {
			if($page == 1) {
				$start = 0;
				} else {
				
				$start = ($page - 1) * PAGING_LIMIT;
			}
		} else {
			$start = 0;
		}
		
		$project_listing = $this->project_model->get_open_for_bidding_project_list($start,PAGING_LIMIT,$search_element);
		
		if(empty($project_listing)){
			
			$page = $new_counter-1;
			if($page != null) {
			
				if($page == 1) {
					$start = 0;
				} else {
					
				$start = ($page - 1) *PAGING_LIMIT;
				}
			} else {
				$start = 0;
				}
		}
		redirect(base_url()."project/open_for_bidding/?search_element=" . $search_element."&per_page=".$page);   
	}
	/**
	 * This is used to cancel the expired project
	*/
	public function cancel_expired_project($project_id) {
		if(empty($project_id)){
			redirect(base_url() . 'project/expire');  
		}
		include '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_projects_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_bidding_fulltime_projects_custom_config.php';
		$expired_project_data = array();
		
		
		/* $fixed_budget_expired_project_data = $this->db->get_where('fixed_budget_projects_expired', ['project_id' => $project_id])->row_array();
	
		if(!empty($fixed_budget_expired_project_data)){
			$expired_project_data = $fixed_budget_expired_project_data;
		}else{
			$hourly_rate_based_expired_project_data = $this->db->get_where('hourly_rate_based_projects_expired', ['project_id' => $project_id])->row_array();
			if(!empty($hourly_rate_based_expired_project_data)){
				$expired_project_data = $hourly_rate_based_expired_project_data;
			}else{
				$fulltime_expired_project_data = $this->db->get_where('fulltime_projects_expired', ['project_id' => $project_id])->row_array();
				if(!empty($fulltime_expired_project_data)){
					$expired_project_data = $fulltime_expired_project_data;
				}
			}
		} */
		
		$project_status_table_array = $this->project_model->get_project_status_table_name($project_id);
		
		if(!empty($project_status_table_array['table_name'])){
		
			if (substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
				$expired_project_data =  $this->db->get_where($project_status_table_array['table_name'], ['fulltime_project_id' => $project_id])->row_array();
				
				
				
			} else {
				$expired_project_data =  $this->db->get_where($project_status_table_array['table_name'], ['project_id' => $project_id])->row_array();
				/* 
				if($expired_project_data['project_type'] == 'fulltime'){
					$expired_project_data['fulltime_project_id'] = $project_id;
					$expired_project_data['employer_id'] = $expired_project_data['project_owner_id'];
					unset($expired_project_data['project_id']);
					unset($expired_project_data['project_owner_id']);
				} */
				
				
			}
			
			if($expired_project_data['project_type'] == 'fulltime'){
				$awarded_bidding_list = $this->db // get the user detail
				->select('awarded_bid.*,u.profile_name,u.user_id')
				->from('fulltime_projects_awarded_tracking awarded_bid')
				->where('fulltime_project_id', $project_id)
				->join('users u', 'u.user_id = awarded_bid.employee_id', 'left')
				->order_by('awarded_bid.id ASC')
				->get()->result_array();
				$check_fulltime_award_bid_count = count($awarded_bidding_list);
				
			}
			
			unset($expired_project_data['id']);
			unset($expired_project_data['featured']);
			unset($expired_project_data['urgent']);
			if($expired_project_data['project_type'] != 'fulltime') {
				$profile_folder =  $this->auto_model->getFeild('profile_name','users','user_id',$expired_project_data['project_owner_id']);
				$expired_project_data['project_cancellation_date'] = date('Y-m-d H:i:s');
			} else {
				$profile_folder =  $this->auto_model->getFeild('profile_name','users','user_id',$expired_project_data['employer_id']);
				$expired_project_data['fulltime_project_cancellation_date'] = date('Y-m-d H:i:s');
				
				
			}
			
			
			if($expired_project_data['project_type'] == 'fixed'){
				
				$cancelled_by_admin_project_data =  $this->db->get_where('fixed_budget_projects_cancelled_by_admin', ['project_id' => $project_id])->row_array();
				if(empty($cancelled_by_admin_project_data)){
					$this->db->insert('fixed_budget_projects_cancelled_by_admin', $expired_project_data);
				}
				
				$this->db->delete($project_status_table_array['table_name'], ['project_id' => $project_id]);
			}
			if($expired_project_data['project_type'] == 'hourly'){
				$cancelled_by_admin_project_data =  $this->db->get_where('hourly_rate_based_projects_cancelled_by_admin', ['project_id' => $project_id])->row_array();
				if(empty($cancelled_by_admin_project_data)){
					$this->db->insert('hourly_rate_based_projects_cancelled_by_admin', $expired_project_data);
				}
				$this->db->delete($project_status_table_array['table_name'], ['project_id' => $project_id]);
				
			}
			if($expired_project_data['project_type'] == 'fulltime'){
				$cancelled_by_admin_project_data =  $this->db->get_where('fulltime_projects_cancelled_by_admin', ['fulltime_project_id' => $project_id])->row_array();
				if(empty($cancelled_by_admin_project_data)){
					
					if (substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
						$this->db->insert('fulltime_projects_cancelled_by_admin', $expired_project_data);
						$this->db->delete($project_status_table_array['table_name'], ['fulltime_project_id' => $project_id]);
					}else{
						$expired_project_data['project_cancellation_date'] = date('Y-m-d H:i:s');
						$expired_project_data['fulltime_project_id'] = $expired_project_data['project_id'];
						$expired_project_data['employer_id'] = $expired_project_data['project_owner_id'];
						$expired_project_data['fulltime_project_posting_date'] = $expired_project_data['project_posting_date'];
						$expired_project_data['fulltime_project_expiration_date'] = $expired_project_data['project_expiration_date'];
						$expired_project_data['fulltime_project_title'] = $expired_project_data['project_title'];
						$expired_project_data['fulltime_project_description'] = $expired_project_data['project_description'];
						$expired_project_data['fulltime_project_cancellation_date'] = $expired_project_data['project_cancellation_date'];
						$expired_project_data['min_salary'] = $expired_project_data['min_budget'];
						$expired_project_data['max_salary'] = $expired_project_data['max_budget'];

						unset($expired_project_data['project_id']);
						unset($expired_project_data['project_owner_id']);
						unset($expired_project_data['project_posting_date']);
						unset($expired_project_data['project_expiration_date']);
						unset($expired_project_data['project_title']);
						unset($expired_project_data['project_description']);
						unset($expired_project_data['project_cancellation_date']);
						unset($expired_project_data['min_budget']);
						unset($expired_project_data['max_budget']);

						$this->db->insert('fulltime_projects_cancelled_by_admin', $expired_project_data);
						$this->db->delete($project_status_table_array['table_name'], ['project_id' => $project_id]);
					}	
					
					
					
				}
				
			}

			// Remove project attachement based on project id
			$this->db->delete('projects_attachments', ['project_id' => $project_id]);

			// Remove bidder attachments based on project id
			$this->db->delete('projects_active_bids_users_attachments_tracking', ['project_id' => $project_id]);
			
			// Set next refresh time to null for cancelled project
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('standard_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('featured_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('urgent_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('sealed_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			
			################ genreate the log activity #################
			$project_url_link = HTTP_WEBSITE_HOST.$config['project_detail_page_url']."?id=".$project_id;
			if($expired_project_data['project_type'] == 'fulltime'){
				
				if($check_fulltime_award_bid_count > 0){
					$project_activity_log_displayed_message = $config['fulltime_expired_project_cancelled_by_admin_awarded_user_activity_log_displayed_message_sent_to_po'];
				}else{	
				
				
					$project_activity_log_displayed_message = $config['fulltime_expired_project_cancelled_by_admin_user_activity_log_displayed_message_sent_to_po'];
				}
				$project_title = $expired_project_data['fulltime_project_title'];
				$project_owner = $expired_project_data['employer_id'];
			}else{
				$project_activity_log_displayed_message = $config['expired_project_cancelled_by_admin_user_activity_log_displayed_message_sent_to_po'];
				$project_title = $expired_project_data['project_title'];
				$project_owner = $expired_project_data['project_owner_id'];
			}
			$project_activity_log_displayed_message = str_replace(array("{project_title}", "{project_url_link}"),array(htmlspecialchars($project_title, ENT_QUOTES), $project_url_link),$project_activity_log_displayed_message);
			user_display_log($project_activity_log_displayed_message, $project_owner);
			######################################################
			// trigger socket event to update expired project section on user dashboard
			$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateOpenBiddingOrExpiredProjectListOnProjectCancelledByAdmin/".$project_owner.'/expired/?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
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

			//$CI = & get_instance();
			$this->load->library('ftp');
			$conf['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$conf['ftp_username'] = FTP_USERNAME;
			$conf['ftp_password'] = FTP_PASSWORD;
			$conf['ftp_port'] 	= FTP_PORT;
			$conf['debug']    = TRUE;
			$this->ftp->connect($conf); 
			try {
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$project_expired_dir = PROJECT_EXPIRED_DIR;
				$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
				$profile_folder = $profile_folder  = $this->auto_model->getFeild('profile_name','users','user_id',$project_owner);
				$project_cancelled_dir = PROJECT_CANCELLED_DIR;
				
				
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir);
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir.$project_id.DIRECTORY_SEPARATOR);
				
				//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists							
				//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir, 0777);// create cancelled directory in projects folder
				//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir.$project_id , 0777); // create the directory by using  project id
				
				if($project_status_table_array['project_status'] == 'open_for_bidding'){
					$project_dir = $project_open_for_bidding_dir;
				}
				if($project_status_table_array['project_status'] == 'expired'){
					$project_dir = $project_expired_dir;
				}
				
				if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dir.$project_id))) {
					$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dir.$project_id);
				}
			} catch(Exception $e) {

			}
			
			if($expired_project_data['project_type'] == 'fulltime'){
				if(!empty($awarded_bidding_list)){
					foreach($awarded_bidding_list as $awarded_bid_key => $awarded_bid_value){
						$check_bid_data_exists = $this->db->where(['fulltime_project_id' => $awarded_bid_value['fulltime_project_id'],'employer_id'=>$awarded_bid_value['employer_id'],'employee_id'=>$awarded_bid_value['employee_id']])->from('fulltime_projects_open_bidding_active_applications')->count_all_results();
						
						if($check_bid_data_exists == 0){
						
							$bid_data['fulltime_project_id'] = $awarded_bid_value['fulltime_project_id'];
							$bid_data['employer_id'] = $awarded_bid_value['employer_id'];
							$bid_data['employee_id'] = $awarded_bid_value['employee_id'];
							$bid_data['application_bidding_dropdown_option'] = $awarded_bid_value['application_bidding_dropdown_option'];
							
						
							$bid_data['application_description'] = $awarded_bid_value['application_description'];
							
							$bid_data['application_date'] = $awarded_bid_value['application_date'];
							
							$bid_data['initial_requested_salary'] = $awarded_bid_value['awarded_salary'];
							
							$awarded_bid_activity_log_displayed_message = $config['fulltime_expired_project_cancelled_by_admin_awarded_user_activity_log_displayed_message_sent_to_awarded_sp'];
							
							$awarded_bid_activity_log_displayed_message = str_replace(array("{project_title}", "{project_url_link}"),array(htmlspecialchars($project_title, ENT_QUOTES), $project_url_link),$awarded_bid_activity_log_displayed_message);
							$awarded_bid_activity_log_displayed_message;
							
							$this->db->insert ('fulltime_projects_open_bidding_active_applications', $bid_data);// insert into active bid table
							
							$this->db->delete('fulltime_projects_awarded_tracking', ['fulltime_project_id' => $awarded_bid_value['fulltime_project_id'],'employee_id'=>$awarded_bid_value['employee_id']]); // remove entry from award tracking table
							
							$this->db->delete('projects_active_bids_users_attachments_tracking', ['project_id' => $awarded_bid_value['fulltime_project_id'],'user_id'=>$awarded_bid_value['employee_id']]); // remove entry 
							
							user_display_log($awarded_bid_activity_log_displayed_message,$awarded_bid_value['user_id']);
						}
					}
				}
			}
			
			$this->session->set_flashdata('succ_msg', 'Project cancelled successfully');
			
		}
		if($this->input->get ('search_element')){
			$search_element = $this->input->get ('search_element');
		}else{
			$search_element = '';
		}
		if($this->input->get ('per_page')){
			$page = $this->input->get ('per_page');
		}else{
			$page = 1;
		}	
		$new_counter = $page;
		if($page != null) {
			if($page == 1) {
				$start = 0;
				} else {
				
				$start = ($page - 1) * PAGING_LIMIT;
			}
		} else {
			$start = 0;
		}
		
		$project_listing = $this->project_model->get_expired_project_list($start,PAGING_LIMIT,$search_element);
		
		if(empty($project_listing)){
			
			$page = $new_counter-1;
			if($page != null) {
			
				if($page == 1) {
					$start = 0;
				} else {
					
				$start = ($page - 1) *PAGING_LIMIT;
				}
			} else {
				$start = 0;
				}
		}
		redirect(base_url()."project/expire/?search_element=" . $search_element."&per_page=".$page);   
	}
	
	/**
		* This is used to cancel the awarded project
	*/
	public function cancel_awarded_project($project_id) {
		if(empty($project_id)){
			redirect(base_url() . 'project/awarded');  
		}
		include '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_projects_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_bidding_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_bidding_fixed_budget_projects_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_bidding_hourly_rate_projects_custom_config.php';
		$expired_project_data = array();
		$awarded_bid_table_name = '';
		$active_bid_table_name = '';
		
		$fixed_budget_awarded_project_data = $this->db->get_where('fixed_budget_projects_awarded', ['project_id' => $project_id])->row_array();
	
		if(!empty($fixed_budget_awarded_project_data)){
			$awarded_project_data = $fixed_budget_awarded_project_data;
			$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
			$active_bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
		}else{
			$hourly_rate_based_awarded_project_data = $this->db->get_where('hourly_rate_based_projects_awarded', ['project_id' => $project_id])->row_array();
			if(!empty($hourly_rate_based_awarded_project_data)){
				$awarded_project_data = $hourly_rate_based_awarded_project_data;
				$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
				$active_bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
			}else{
				// $fulltime_awarded_project_data = $this->db->get_where('projects_open_bidding', ['project_id' => $project_id])->row_array();
				// if(!empty($fulltime_awarded_project_data)){
				// 	$awarded_project_data = $fulltime_awarded_project_data;
				// 	$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
				// 	$active_bid_table_name = 'fulltime_projects_open_bidding_active_applications';
				// } 
			}
		}
		/* echo "<pre>";
		print_r($awarded_project_data); */
		//die;
		
		if(!empty($awarded_project_data)) {
			unset($awarded_project_data['id']);
			$profile_folder =  $this->auto_model->getFeild('profile_name','users','user_id',$awarded_project_data['project_owner_id']);
			$awarded_project_data['project_cancellation_date'] = date('Y-m-d H:i:s');
			if($awarded_project_data['project_type'] == 'fixed'){
				
				
				$check_fixed_budget_cancelled_project = $this->db->where(['project_id' => $project_id])->from('fixed_budget_projects_cancelled_by_admin')->count_all_results();
				
				if($check_fixed_budget_cancelled_project == 0){
					$this->db->insert('fixed_budget_projects_cancelled_by_admin', $awarded_project_data);
				}
				$this->db->delete('fixed_budget_projects_awarded', ['project_id' => $project_id]);
			}
			if($awarded_project_data['project_type'] == 'hourly'){
				$check_hourly_cancelled_project = $this->db->where(['project_id' => $project_id])->from('hourly_rate_based_projects_cancelled_by_admin')->count_all_results();
				if($check_hourly_cancelled_project == 0){
					$this->db->insert('hourly_rate_based_projects_cancelled_by_admin', $awarded_project_data);
				}
				$this->db->delete('hourly_rate_based_projects_awarded', ['project_id' => $project_id]);
				
			}
			if($awarded_project_data['project_type'] == 'fulltime'){
				$check_fulltime_cancelled_project = $this->db->where(['fulltime_project_id' => $project_id])->from('fulltime_projects_cancelled_by_admin')->count_all_results();
				if($check_fulltime_cancelled_project == 0){
					$this->db->insert('fulltime_projects_cancelled_by_admin', $awarded_project_data);
				}
				
				//$this->db->delete('fulltime_projects_awarded', ['project_id' => $project_id]);
			}

			// Remove project attachement based on project id
			//$this->db->delete('projects_attachments', ['project_id' => $project_id]);
			// Set next refresh time to null for cancelled project
			/* $this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('standard_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('featured_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('urgent_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('sealed_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]); */
			
			################ genreate the log activity #################
			$project_url_link = HTTP_WEBSITE_HOST.$config['project_detail_page_url']."?id=".$project_id;
			if($awarded_project_data['project_type'] == 'fixed'){
				$project_activity_log_displayed_message = $config['awarded_fixed_budget_project_cancelled_by_admin_message_sent_to_po_user_activity_log_displayed_message'];
			}else if ($awarded_project_data['project_type'] == 'hourly') {
				$project_activity_log_displayed_message = $config['awarded_hourly_project_cancelled_by_admin_message_sent_to_po_user_activity_log_displayed_message'];
			}else if ($awarded_project_data['project_type'] == 'fulltime') {
				$project_activity_log_displayed_message = $config['awarded_fulltime_project_cancelled_by_admin_message_sent_to_po_user_activity_log_displayed_message'];
			}
			$project_title = $awarded_project_data['project_title'];
			$project_activity_log_displayed_message = str_replace(array("{project_title}", "{project_url_link}"),array(htmlspecialchars($project_title, ENT_QUOTES), $project_url_link),$project_activity_log_displayed_message);
			user_display_log($project_activity_log_displayed_message,$awarded_project_data['project_owner_id']);
			
			
			$awarded_bidding_list = $this->db // get the user detail
			->select('awarded_bid.*,u.profile_name,u.user_id')
			->from($awarded_bid_table_name.' awarded_bid')
			->where('project_id', $project_id)
			->join('users u', 'u.user_id = awarded_bid.winner_id', 'left')
			->order_by('awarded_bid.id ASC')
			->get()->result_array();
			if(!empty($awarded_bidding_list)){
				foreach($awarded_bidding_list as $awarded_bid_key => $awarded_bid_value){
					$check_bid_data_exists = $this->db->where(['project_id' => $awarded_bid_value['project_id'],'project_owner_id'=>$awarded_bid_value['project_owner_id'],'bidder_id'=>$awarded_bid_value['winner_id']])->from($active_bid_table_name)->count_all_results();
					
					if($check_bid_data_exists == 0){
					
						$bid_data['project_id'] = $awarded_bid_value['project_id'];
						$bid_data['project_owner_id'] = $awarded_bid_value['project_owner_id'];
						$bid_data['bidder_id'] = $awarded_bid_value['winner_id'];
						$bid_data['bidding_dropdown_option'] = $awarded_bid_value['bidding_dropdown_option'];
						if($awarded_project_data['project_type'] == 'fulltime'){
					
							$bid_data['application_description'] = $awarded_bid_value['application_description'];
							$bid_data['application_date'] = $awarded_bid_value['application_date'];
							$bid_data['initial_requested_salary'] = $awarded_bid_value['awarded_salary'];
						}
						if($awarded_project_data['project_type'] == 'fixed'){
							
							$bid_data['bid_description'] = $awarded_bid_value['bid_description'];
							$bid_data['bid_date'] = $awarded_bid_value['bid_date'];
							$bid_data['initial_bidded_amount'] = $awarded_bid_value['awarded_amount'];
							$bid_data['project_delivery_period'] = $awarded_bid_value['project_delivery_period'];
						}if($awarded_project_data['project_type'] == 'hourly'){
							$bid_data['bid_description'] = $awarded_bid_value['bid_description'];
							$bid_data['bid_date'] = $awarded_bid_value['bid_date'];
							$bid_data['project_delivery_hours'] = $awarded_bid_value['awarded_hours'];
							$bid_data['initial_bidded_hourly_rate'] = $awarded_bid_value['awarded_hourly_rate'];
							
						}
						
				
						if($awarded_project_data['project_type'] == 'fixed'){
							$awarded_bid_activity_log_displayed_message = $config['awarded_fixed_budget_project_cancelled_by_admin_message_sent_to_awarded_sp_user_activity_log_displayed_message'];
						}else if($awarded_project_data['project_type'] == 'hourly'){
							$awarded_bid_activity_log_displayed_message = $config['awarded_hourly_project_cancelled_by_admin_message_sent_to_awarded_sp_user_activity_log_displayed_message'];
						}else if($awarded_project_data['project_type'] == 'fulltime'){
							$awarded_bid_activity_log_displayed_message = $config['awarded_fulltime_project_cancelled_by_admin_message_sent_to_awarded_sp_user_activity_log_displayed_message'];
						}
						
						
						$awarded_bid_activity_log_displayed_message = str_replace(array("{project_title}", "{project_url_link}"),array(htmlspecialchars($project_title, ENT_QUOTES), $project_url_link),$awarded_bid_activity_log_displayed_message);
						$awarded_bid_activity_log_displayed_message;
						
						$this->db->insert ($active_bid_table_name, $bid_data);// insert into active bid table
						
						$this->db->delete($awarded_bid_table_name, ['project_id' => $awarded_bid_value['project_id'],'winner_id'=>$awarded_bid_value['winner_id']]); // remove entry from award tracking table
						
						$this->db->delete('projects_active_bids_users_attachments_tracking', ['project_id' => $awarded_bid_value['project_id'],'user_id'=>$awarded_bid_value['winner_id']]); // remove entry 
						
						
						user_display_log($awarded_bid_activity_log_displayed_message,$awarded_bid_value['user_id']);
					}
					
				
				}
			
			
			}
			######################################################
			// trigger socket event to update expired project section on user dashboard
			/* $url = PROJECT_MANAGEMENT_SOCKET_URL."/updateOpenBiddingOrExpiredProjectListOnProjectCancelledByAdmin/".$awarded_project_data['project_owner_id'].'/expired/?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
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
			}		 */

			//$CI = & get_instance();
			$this->load->library('ftp');
			$conf['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$conf['ftp_username'] = FTP_USERNAME;
			$conf['ftp_password'] = FTP_PASSWORD;
			$conf['ftp_port'] 	= FTP_PORT;
			$conf['debug']    = TRUE;
			try{
				$this->ftp->connect($conf); 
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				//$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
				$project_awarded_dir =  PROJECT_AWARDED_DIR;
				
				$project_cancelled_dir = PROJECT_CANCELLED_DIR;
				
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir);
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir.$project_id.DIRECTORY_SEPARATOR);
				
				
				//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists							
				//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir, 0777);// create cancelled directory in projects folder
				//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir.$project_id , 0777); // create the directory by using  project id
				if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id))) {
					$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id);
				} 
			} catch(Exception $e) {

			}
			
			$this->session->set_flashdata('succ_msg', 'Project cancelled successfully');
			
		}
		//redirect(base_url() . 'project/awarded');
		if($this->input->get ('search_element')){
			$search_element = $this->input->get ('search_element');
		}else{
			$search_element = '';
		}
		if($this->input->get ('per_page')){
			$page = $this->input->get ('per_page');
		}else{
			$page = 1;
		}	
		$new_counter = $page;
		if($page != null) {
			if($page == 1) {
				$start = 0;
				} else {
				
				$start = ($page - 1) * PAGING_LIMIT;
			}
		} else {
			$start = 0;
		}
		
		$project_listing = $this->project_model->get_awarded_project_list($start,PAGING_LIMIT,$search_element);
		
		if(empty($project_listing)){
			
			$page = $new_counter-1;
			if($page != null) {
			
				if($page == 1) {
					$start = 0;
				} else {
					
				$start = ($page - 1) *PAGING_LIMIT;
				}
			} else {
				$start = 0;
				}
		}
		redirect(base_url()."project/awarded/?search_element=" . $search_element."&per_page=".$page);   
	}
	
	
	
	
	/**
	 * This is used to delete the expired project
	*/
	public function delete_expired_project($project_id) {
		
		if(empty($project_id)){
			redirect(base_url() . 'project/expire');  
		}
		$project_status_table_array = $this->project_model->get_project_status_table_name($project_id);
		if(!empty($project_status_table_array['table_name'])){
		
			if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
				$project_type = $this->auto_model->getFeild('project_type',$project_status_table_array['table_name'],'fulltime_project_id',$project_id);
				$project_owner_id = $this->auto_model->getFeild('employer_id',$project_status_table_array['table_name'],'fulltime_project_id',$project_id);
				$project_title = $this->auto_model->getFeild('fulltime_project_title',$project_status_table_array['table_name'],'fulltime_project_id',$project_id);
			} else {
				$project_type = $this->auto_model->getFeild('project_type',$project_status_table_array['table_name'],'project_id',$project_id);
				$project_owner_id = $this->auto_model->getFeild('project_owner_id',$project_status_table_array['table_name'],'project_id',$project_id);
				$project_title = $this->auto_model->getFeild('project_title',$project_status_table_array['table_name'],'project_id',$project_id);	
			}	
			if($project_type == 'fulltime'){
				$get_hires_count_fulltime_project = $this->project_model->get_hires_count_fulltime_project($project_id);
				if($get_hires_count_fulltime_project > 0){
					$this->session->set_flashdata('error_msg', 'unable to delete project status has been changed');
					redirect(base_url() . 'project/expire'); 
				}
				
				
			}else{
				if($project_status_table_array['project_status'] != 'expired' && $project_status_table_array['project_status'] != 'open_for_bidding'){
					$this->session->set_flashdata('error_msg', 'unable to delete project status has been changed');
					redirect(base_url() . 'project/expire'); 
				}	
			}	

			
			$profile_folder  = $this->auto_model->getFeild('profile_name','users','user_id',$project_owner_id);
			if(empty($project_owner_id)){
				redirect(base_url() . 'project/expire');  
			}
			include '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
			include '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
			include '../application/config/'.SITE_LANGUAGE.'_projects_custom_config.php';
			
			
			$CI = & get_instance();
			$CI->load->library('ftp');
			$conf['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$conf['ftp_username'] = FTP_USERNAME;
			$conf['ftp_password'] = FTP_PASSWORD;
			$conf['ftp_port'] = FTP_PORT;
			$conf['debug'] = TRUE;
			try {
				$CI->ftp->connect($conf);
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
				$project_expired_dir = PROJECT_EXPIRED_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				//$bidders_attachments_dir = $config['bidders_attachments_dir'];
				
				if($project_status_table_array['project_status'] == 'open_for_bidding'){
					$project_dir = $project_open_for_bidding_dir;
				}
				if($project_status_table_array['project_status'] == 'expired'){
					$project_dir = $project_expired_dir;
				}
			
				if(!empty($CI->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dir.$project_id))){
				
					$CI->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dir.$project_id);// delete awaiting_moderation project directory

				}
				
			} catch(Exception $e) {
				// pre($e);
			}
			$this->db->delete('projects_attachments', array('project_id' => $project_id));
			$this->db->delete('projects_categories_listing_tracking', array('project_id' => $project_id));
			$this->db->delete('projects_tags', array('project_id' => $project_id));
			$this->db->delete('projects_active_bids_users_attachments_tracking', ['project_id' => $project_id]);
			$this->db->delete('fixed_budget_projects_open_bidding_active_bids', ['project_id' => $project_id]);
			$this->db->delete('hourly_rate_based_projects_open_bidding_active_bids', ['project_id' => $project_id]);
			$this->db->delete('fixed_budget_projects_awarded_tracking', ['project_id' => $project_id]);
			$this->db->delete('hourly_rate_based_projects_awarded_tracking', ['project_id' => $project_id]);
			$this->db->delete('fulltime_projects_open_bidding_active_applications', ['fulltime_project_id' => $project_id]);
			$this->db->delete('fulltime_projects_awarded_tracking', ['fulltime_project_id' => $project_id]);
			if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
				$this->db->delete($project_status_table_array['table_name'], array('fulltime_project_id' => $project_id));
			}else{
				$this->db->delete($project_status_table_array['table_name'], array('project_id' => $project_id));
			}
			$this->db->update('users_contacts_tracking', ['project_status' => 'deleted_by_admin'], ['project_id' => $project_id]);
			// Set next refresh time to null for cancelled project
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('standard_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('featured_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('urgent_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('sealed_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			################ genreate the log activity #################
			if($project_type == 'fulltime'){
				$project_activity_log_displayed_message = $config['fulltime_project_deleted_by_admin_user_activity_log_displayed_message_sent_to_po'];
			}else{
				$project_activity_log_displayed_message = $config['project_deleted_by_admin_user_activity_log_displayed_message_sent_to_po'];
			}
			
			$project_activity_log_displayed_message = str_replace(array("{project_title}"),array(htmlspecialchars($project_title, ENT_QUOTES)),$project_activity_log_displayed_message);
			user_display_log($project_activity_log_displayed_message,$project_owner_id);
			######################################################
			
			 $url = PROJECT_MANAGEMENT_SOCKET_URL."/deleteProject/".$project_owner_id."/".base64_encode($project_title).'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
		
		
			$options = array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false
			);
			$ch = curl_init( $url );
			curl_setopt_array( $ch, $options );
			curl_exec( $ch );
			curl_close( $ch );

			
			
			
			$this->session->set_flashdata('succ_msg', 'Project removed successfully');
			// trigger socket event to update expired project section on user dashboard
			//$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateProjectListingOnDashboardByStatus/".$project_owner_id.'/expired/?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
			
			$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateProjectListingOnDashboardByStatus/".$project_owner_id.'/expired/'.base64_encode($project_title).'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
			
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
		}
		//redirect(base_url() . 'project/expire');
		if($this->input->get ('search_element')){
			$search_element = $this->input->get ('search_element');
		}else{
			$search_element = '';
		}
		if($this->input->get ('per_page')){
			$page = $this->input->get ('per_page');
		}else{
			$page = 1;
		}	
		$new_counter = $page;
		if($page != null) {
			if($page == 1) {
				$start = 0;
				} else {
				
				$start = ($page - 1) * PAGING_LIMIT;
			}
		} else {
			$start = 0;
		}
		
		$project_listing = $this->project_model->get_awaiting_moderation_project_list($start,PAGING_LIMIT,$search_element);
		
		if(empty($project_listing)){
			
			$page = $new_counter-1;
			if($page != null) {
			
				if($page == 1) {
					$start = 0;
				} else {
					
				$start = ($page - 1) *PAGING_LIMIT;
				}
			} else {
				$start = 0;
				}
		}
		redirect(base_url()."project/expire/?search_element=" . $search_element."&per_page=".$page);
	}
	
	/* 
	This is used to delete the open for bidding project
	*/
	public function delete_open_for_bidding_project($project_id)
    {
		if(empty($project_id)){
			redirect(base_url() . 'project/open_for_bidding');  
		}
		
		$project_type = $this->auto_model->getFeild('project_type','projects_open_bidding','project_id',$project_id);
		
		if($project_type == 'fulltime'){
			$get_hires_count_fulltime_project = $this->project_model->get_hires_count_fulltime_project($project_id);
			if($get_hires_count_fulltime_project > 0){
				$this->session->set_flashdata('error_msg', 'unable to delete project status has been changed');
				redirect(base_url() . 'project/open_for_bidding'); 
			}	
		}	
		$check_project_exists_open_bidding = $this->db->where(['project_id' => $project_id])->from('projects_open_bidding')->count_all_results();
		if($check_project_exists_open_bidding == 0){
			$this->session->set_flashdata('error_msg', 'unable to delete project status has been changed');
			redirect(base_url() . 'project/open_for_bidding'); 
		}	
		
		
		
		
		$project_owner_id = $this->auto_model->getFeild('project_owner_id','projects_open_bidding','project_id',$project_id);
		$project_title = $this->auto_model->getFeild('project_title','projects_open_bidding','project_id',$project_id);
		
		
		
		$profile_folder  = $this->auto_model->getFeild('profile_name','users','user_id',$project_owner_id);
		if(empty($project_owner_id)){
			redirect(base_url() . 'project/open_for_bidding');  
		}
		include '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_projects_custom_config.php';
		
		
		$CI = & get_instance();
		$CI->load->library('ftp');
		$conf['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
		$conf['ftp_username'] = FTP_USERNAME;
		$conf['ftp_password'] = FTP_PASSWORD;
		$conf['ftp_port'] = FTP_PORT;
		$conf['debug'] = TRUE;
		try {
			$CI->ftp->connect($conf);
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
			$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
			//$bidders_attachments_dir = $config['bidders_attachments_dir'];
			if(!empty($CI->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id))){
			
				$CI->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id);// delete awaiting_moderation project directory

			}
			
		} catch(Exception $e) {
			
		}
		$this->db->delete('projects_attachments', array('project_id' => $project_id));
		$this->db->delete('projects_categories_listing_tracking', array('project_id' => $project_id));
		$this->db->delete('projects_tags', array('project_id' => $project_id));
		$this->db->delete('projects_open_bidding', array('project_id' => $project_id));
		$this->db->delete('projects_active_bids_users_attachments_tracking', ['project_id' => $project_id]);
		$this->db->delete('fixed_budget_projects_open_bidding_active_bids', ['project_id' => $project_id]);
		$this->db->delete('hourly_rate_based_projects_open_bidding_active_bids', ['project_id' => $project_id]);
		$this->db->delete('fixed_budget_projects_awarded_tracking', ['project_id' => $project_id]);
		$this->db->delete('hourly_rate_based_projects_awarded_tracking', ['project_id' => $project_id]);
		$this->db->delete('fulltime_projects_open_bidding_active_applications', ['fulltime_project_id' => $project_id]);
		$this->db->delete('fulltime_projects_awarded_tracking', ['fulltime_project_id' => $project_id]);

		$this->db->update('users_contacts_tracking', ['project_status' => 'deleted_by_admin'], ['project_id' => $project_id]);
		// remove entry from project latest refresh sequence tracking table
		$this->db->delete('projects_latest_refresh_sequence_tracking', array('project_id' => $project_id));
		// Set next refresh time to null for cancelled project
		$this->db->limit(1);
		$this->db->order_by('id', 'desc');
		$this->db->update('standard_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
		$this->db->limit(1);
		$this->db->order_by('id', 'desc');
		$this->db->update('featured_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
		$this->db->limit(1);
		$this->db->order_by('id', 'desc');
		$this->db->update('urgent_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
		$this->db->limit(1);
		$this->db->order_by('id', 'desc');
		$this->db->update('sealed_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
		################ genreate the log activity #################
		$project_url_link = HTTP_WEBSITE_HOST.$config['project_detail_page_url']."?id=".$project_id;
		if($project_type == 'fulltime'){
			$project_activity_log_displayed_message = $config['fulltime_project_deleted_by_admin_user_activity_log_displayed_message_sent_to_po'];
		}else{
			$project_activity_log_displayed_message = $config['project_deleted_by_admin_user_activity_log_displayed_message_sent_to_po'];
		}
		$project_activity_log_displayed_message = str_replace(array("{project_title}"),array(htmlspecialchars($project_title, ENT_QUOTES)),$project_activity_log_displayed_message);
		user_display_log($project_activity_log_displayed_message,$project_owner_id);
		
		
	  $curl_url = PROJECT_MANAGEMENT_SOCKET_URL."/deleteProject/".$project_owner_id."/".base64_encode($project_title).'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
		
		
		$options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false
		);
		$ch = curl_init( $curl_url );
		curl_setopt_array( $ch, $options );
		curl_exec( $ch );
		curl_close( $ch );
		######################################################
		$this->session->set_flashdata('succ_msg', 'Project removed successfully');
		// trigger socket event to update open bidding project section on user dashboard
		$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateProjectListingOnDashboardByStatus/".$project_owner_id.'/open/'.base64_encode($project_title).'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
		
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
		//redirect(base_url() . 'project/open_for_bidding'); 
		if($this->input->get ('search_element')){
			$search_element = $this->input->get ('search_element');
		}else{
			$search_element = '';
		}
		if($this->input->get ('per_page')){
			$page = $this->input->get ('per_page');
		}else{
			$page = 1;
		}	
		$new_counter = $page;
		if($page != null) {
			if($page == 1) {
				$start = 0;
				} else {
				
				$start = ($page - 1) * PAGING_LIMIT;
			}
		} else {
			$start = 0;
		}
		
		$project_listing = $this->project_model->get_open_for_bidding_project_list($start,PAGING_LIMIT,$search_element);
		
		if(empty($project_listing)){
			
			$page = $new_counter-1;
			if($page != null) {
			
				if($page == 1) {
					$start = 0;
				} else {
					
				$start = ($page - 1) *PAGING_LIMIT;
				}
			} else {
				$start = 0;
				}
		}
		redirect(base_url()."project/open_for_bidding/?search_element=" . $search_element."&per_page=".$page);   
	}
	/**
	 * This is used to delete canceled project
	*/
	public function delete_canceled_project($project_id) {

		if(empty($project_id)){
			redirect(base_url() . 'project/canceled');  
		}
		$project_status_table_array = $this->project_model->get_project_status_table_name($project_id);
		if(!empty($project_status_table_array['table_name'])){
			if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
				$project_type = $this->auto_model->getFeild('project_type',$project_status_table_array['table_name'],'fulltime_project_id',$project_id);
				$project_owner_id = $this->auto_model->getFeild('employer_id',$project_status_table_array['table_name'],'fulltime_project_id',$project_id);
				$project_title = $this->auto_model->getFeild('fulltime_project_title',$project_status_table_array['table_name'],'fulltime_project_id',$project_id);
			}else{
				$project_type = $this->auto_model->getFeild('project_type',$project_status_table_array['table_name'],'project_id',$project_id);
				$project_owner_id = $this->auto_model->getFeild('project_owner_id',$project_status_table_array['table_name'],'project_id',$project_id);
				$project_title = $this->auto_model->getFeild('project_title',$project_status_table_array['table_name'],'project_id',$project_id);	
			}	
			
			
			$profile_folder  = $this->auto_model->getFeild('profile_name','users','user_id',$project_owner_id);
			if(empty($project_owner_id)){
				redirect(base_url() . 'project/canceled');  
			}
			include '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
			include '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
			include '../application/config/'.SITE_LANGUAGE.'_projects_custom_config.php';

			$CI = & get_instance();
			$CI->load->library('ftp');
			$conf['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$conf['ftp_username'] = FTP_USERNAME;
			$conf['ftp_password'] = FTP_PASSWORD;
			$conf['ftp_port'] = FTP_PORT;
			$conf['debug'] = TRUE;
			try {
				$CI->ftp->connect($conf);
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$project_cancelled_dir = $config['project_cancelled_dir'];
				if(!empty($CI->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir.$project_id))){
					$CI->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir.$project_id);// delete cancelled project directory
				}
				
			} catch(Exception $e) {
				
			}
			
			$this->db->delete('projects_attachments', array('project_id' => $project_id));
			$this->db->delete('projects_categories_listing_tracking', array('project_id' => $project_id));
			
			$this->db->delete('projects_tags', array('project_id' => $project_id));
			$this->db->delete('fixed_budget_projects_open_bidding_active_bids', ['project_id' => $project_id]);
			$this->db->delete('hourly_rate_based_projects_open_bidding_active_bids', ['project_id' => $project_id]);
			$this->db->delete('fixed_budget_projects_awarded_tracking', ['project_id' => $project_id]);
			$this->db->delete('hourly_rate_based_projects_awarded_tracking', ['project_id' => $project_id]);
			$this->db->delete('fulltime_projects_open_bidding_active_applications', ['fulltime_project_id' => $project_id]);
			$this->db->delete('fulltime_projects_awarded_tracking', ['fulltime_project_id' => $project_id]);
			
			if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
				$this->db->delete($project_status_table_array['table_name'], array('fulltime_project_id' => $project_id));
			}else{
				$this->db->delete($project_status_table_array['table_name'], array('project_id' => $project_id));
			}
			$this->db->update('users_contacts_tracking', ['project_status' => 'deleted_by_admin'], ['project_id' => $project_id]);
			################ genreate the log activity #################
			if($project_type == 'fulltime'){
				$project_activity_log_displayed_message = $config['fulltime_project_deleted_by_admin_user_activity_log_displayed_message_sent_to_po'];
			}else{
				$project_activity_log_displayed_message = $config['project_deleted_by_admin_user_activity_log_displayed_message_sent_to_po'];
			}
			$project_activity_log_displayed_message = str_replace(array("{project_title}"),array(htmlspecialchars($project_title, ENT_QUOTES)),$project_activity_log_displayed_message);
			user_display_log($project_activity_log_displayed_message,$project_owner_id);
			######################################################
			
			/* $url = PROJECT_MANAGEMENT_SOCKET_URL."/deleteProject/".$project_owner_id."/".base64_encode($project_title).'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
		
		
			$options = array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false
			);
			$ch = curl_init( $url );
			curl_setopt_array( $ch, $options );
			curl_exec( $ch );
			curl_close( $ch );
			die; */
			
			
			
			
			
			$this->session->set_flashdata('succ_msg', 'Project removed successfully');
			// trigger socket event to update cancelled project section on user dashboard
			//$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateProjectListingOnDashboardByStatus/".$project_owner_id.'/canceled/?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
			
			
			$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateProjectListingOnDashboardByStatus/".$project_owner_id.'/canceled/'.base64_encode($project_title).'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
			
			
			
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
			//redirect(base_url() . 'project/canceled');  
			if($this->input->get ('search_element')){
			$search_element = $this->input->get ('search_element');
			}else{
				$search_element = '';
			}
			if($this->input->get ('per_page')){
				$page = $this->input->get ('per_page');
			}else{
				$page = 1;
			}	
			$new_counter = $page;
			if($page != null) {
				if($page == 1) {
					$start = 0;
					} else {
					
					$start = ($page - 1) * PAGING_LIMIT;
				}
			} else {
				$start = 0;
			}
			
			$project_listing = $this->project_model->get_cancelled_project_list($start,PAGING_LIMIT,$search_element);
			
			if(empty($project_listing)){
				
				$page = $new_counter-1;
				if($page != null) {
				
					if($page == 1) {
						$start = 0;
					} else {
						
					$start = ($page - 1) *PAGING_LIMIT;
					}
				} else {
					$start = 0;
					}
			}
			redirect(base_url()."project/canceled/?search_element=" . $search_element."&per_page=".$page);  
		}
		
	}
	
	/* 
	This is used to approved the awaiting moderation project.So after approved the project it will available for bidding
	*/
	public function approved_awaiting_moderation_project($project_id)
    {
		
		
		if(empty($project_id)){
			redirect(base_url() . 'project/awaiting_approval');  
		}
		
		$project_owner_id = $this->auto_model->getFeild('project_owner_id','projects_awaiting_moderation','project_id',$project_id);
		if(empty($project_owner_id)){
			redirect(base_url() . 'project/awaiting_approval');  
		}
		include_once '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
		include_once '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
		include_once '../application/config/'.SITE_LANGUAGE.'_projects_custom_config.php';
		include_once '../application/config/'.SITE_LANGUAGE.'_newly_posted_projects_realtime_notifications_custom_config.php';
		
		include_once '../application/config/'.SITE_LANGUAGE.'_server_settings_custom_config.php';
		include_once '../application/config/'.SITE_LANGUAGE.'_post_project_settings_custom_config.php';
		include_once '../application/config/'.SITE_LANGUAGE.'_projects_settings_custom_config.php';
		include_once '../application/config/'.SITE_LANGUAGE.'_newly_posted_projects_realtime_notifications_settings_custom_config.php';
		
		$this->db->select('*');
		$this->db->from('projects_awaiting_moderation');
		$this->db->where('projects_awaiting_moderation.project_id',$project_id);
		$project_result = $this->db->get();
		$project_data = $project_result->result_array();
		
		$count_open_for_bidding_project = $this->db // count the number of record in temp_projects table
				->select ('id')
				->from ('projects_open_bidding')
				->where('project_id',$project_id)
				->get ()->num_rows ();
		if($count_open_for_bidding_project == 0 && !empty($project_data)){
					
			$project_data = array_shift($project_data);
			$project_expire_date = $this->project_model->get_project_correct_expiration_date($project_data, $config);
			// Insert into projects_open_bidding table
			$open_bidding_data = array(
				'project_id' => $project_data['project_id'],
				'project_owner_id' => $project_data['project_owner_id'],
				'project_posting_date' => date('Y-m-d H:i:s'),
				'project_expiration_date' => $project_expire_date,
				'project_title' => $project_data['project_title'],
				'project_description' => $project_data['project_description'],
				'locality_id'=>$project_data['locality_id'],
				'county_id'=>$project_data['county_id'],
				'postal_code_id' => $project_data['postal_code_id'],
				'project_type' => $project_data['project_type'],
				'min_budget' => $project_data['min_budget'],
				'max_budget' => $project_data['max_budget'],
				'confidential_dropdown_option_selected' => $project_data['confidential_dropdown_option_selected'],
				'not_sure_dropdown_option_selected' => $project_data['not_sure_dropdown_option_selected'],
				'escrow_payment_method' => $project_data['escrow_payment_method'],
				'offline_payment_method' => $project_data['offline_payment_method'],
				'featured' => $project_data['featured'],
				'urgent' => $project_data['urgent'],
				'sealed' => $project_data['sealed'],
				'hidden' => $project_data['hidden'],
				'views' => 0,
				'revisions' => 0
			);
			$this->db->insert('projects_open_bidding', $open_bidding_data);


			$publish_project = [
				'project_id' => $project_data['project_id'],
				'project_owner_id' => $project_data['project_owner_id'],
				'project_posting_date' => date('Y-m-d H:i:s'),
				'project_title' => $project_data['project_title'],
				'project_type' => $project_data['project_type']
			];
			$this->db->insert('users_alltime_published_projects_tracking', $publish_project);

			// Insert data into project_tags
			$profile_folder  = $this->auto_model->getFeild('profile_name','users','user_id',$project_owner_id);
			################ genreate the log activity #################
			if($project_data['project_type'] == 'fulltime'){
				$project_activity_log_displayed_message = $config['fulltime_project_approved_by_admin_user_activity_log_displayed_message_sent_to_po'];
			}else{
				$project_activity_log_displayed_message = $config['project_approved_by_admin_user_activity_log_displayed_message_sent_to_po'];
			}
			
			
			
			$project_url_link = HTTP_WEBSITE_HOST.$config['project_detail_page_url']."?id=".$project_data['project_id'];
			$project_activity_log_displayed_message = str_replace(array("{project_url_link}","{project_title}"),array($project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES)),$project_activity_log_displayed_message);
			
			user_display_log($project_activity_log_displayed_message,$project_owner_id);
			
			#######################################################################
			$this->db->select('awaiting_moderation_project_tag_name');
			$this->db->from('awaiting_moderation_projects_tags');
			$this->db->where('project_id',$project_id);
			$this->db->order_by('id',"asc");
			$project_tag_result = $this->db->get();
			$project_tag_data = $project_tag_result->result_array();
			if(!empty($project_tag_data)) {
				foreach($project_tag_data as $tag_value) {
					$this->db->insert ('projects_tags', array('project_id'=>$project_id,'project_tag_name'=> $tag_value['awaiting_moderation_project_tag_name']));
				}
			}
			
			// Insert into project_categories_list_tracking
			$this->db->select('awaiting_moderation_project_category_id,awaiting_moderation_project_parent_category_id,project_id');
			$this->db->from('awaiting_moderation_projects_categories_listing_tracking');
			$this->db->where('project_id',$project_id);
			$this->db->order_by('id',"asc");
			$project_category_result = $this->db->get();
			$project_category_data = $project_category_result->result_array();
			$parent_categories_id = [];
			if(!empty($project_category_data)) {
				foreach($project_category_data as $category_key => $category_value){
					/* $this->db->insert ('projects_categories_listing_tracking', array('project_id'=>$project_id,'project_category_id'=> $category_value['awaiting_moderation_project_category_id'],'project_parent_category_id'=> $category_value['awaiting_moderation_project_parent_category_id'])); */
					
					$project_category_id = 0;
					$project_parent_category_id = 0;
					if(!empty($category_value['awaiting_moderation_project_parent_category_id'])){
					
						$check_project_parent_category_exist = $this->project_model->check_project_parent_category_exist($category_value['awaiting_moderation_project_parent_category_id']);
				
						$check_project_child_category_exist = $this->project_model->check_project_child_category_exist($category_value['awaiting_moderation_project_parent_category_id'],$category_value['awaiting_moderation_project_category_id']);
						
						if($check_project_parent_category_exist){
							if($check_project_child_category_exist){
								
								$project_category_id = $category_value['awaiting_moderation_project_category_id'];
								$project_parent_category_id = $category_value['awaiting_moderation_project_parent_category_id'];
								
							}else{
							
								$project_category_id =  $category_value['awaiting_moderation_project_parent_category_id'];
								$project_parent_category_id = 0;
								
							}
						}
					}else{
						$check_project_parent_category_exist = $this->project_model->check_project_parent_category_exist($category_value['awaiting_moderation_project_category_id']);
						if($check_project_parent_category_exist){
						
							$project_category_id =  $category_value['awaiting_moderation_project_category_id'];
							$project_parent_category_id = 0;
						
						}
					
					}
					
					if(!empty($project_category_id) || !empty($project_parent_category_id)){
						$this->db->insert ('projects_categories_listing_tracking', array('project_id'=>$category_value['project_id'],'project_category_id'=> $project_category_id,'project_parent_category_id'=>$project_parent_category_id)); // move the categories from table awaiting_moderation_projects_categories_listing_tracking to projects_categories_listing_tracking and apply check that category is valid or not
						if($project_parent_category_id == 0) {
							array_push($parent_categories_id, $project_category_id);
						} else {
							array_push($parent_categories_id, $project_parent_category_id);
						}
					}
					
				}
			}
			
			$this->db->delete('awaiting_moderation_projects_tags', array('project_id' => $project_id));
			$this->db->delete('projects_awaiting_moderation', array('project_id' => $project_id));
			$this->db->delete('awaiting_moderation_projects_categories_listing_tracking', array('project_id' => $project_id));
			
			// trigger socket event to update awaiting moderation project list on user dashboard
			$url = PROJECT_MANAGEMENT_SOCKET_URL."/awaitingModerationProjectsApproved/".$project_data['project_owner_id']."/".$project_id.'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
			$options = array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false
			);
			$ch = curl_init( $url );
			curl_setopt_array( $ch, $options );
			curl_exec( $ch );
			curl_close( $ch );
			
			if($project_data['featured'] == 'Y' || $project_data['urgent'] == 'Y' || $project_data['sealed'] == 'Y' || $project_data['hidden'] == 'Y' ) {
				$this->project_model->update_upgrade_purchase_tracking_with_related_refresh_sequence_tracking($project_data['project_id']);
			}
			
			if(!empty($project_data['hidden']) && $project_data['hidden'] == 'N') {
				// insert into projects_refresh_sequence_tracking table
				$time_arr = explode(':', $config['standard_project_refresh_sequence']);
				$check_valid_arr = array_map('getInt', $time_arr); 
				$valid_time_arr = array_filter($check_valid_arr);
				$next_refresh_date = null;
				if(!empty($valid_time_arr)) {
					$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
					$refresh_sequence_data = [
						'project_id' => $project_data['project_id'],
						'project_last_refresh_time' => null,
						'project_next_refresh_time' => $next_refresh_date,
						'project_upgrade_refresh_sequence_standard' => $config['standard_project_refresh_sequence']
					];
					$this->db->insert('standard_projects_refresh_sequence_tracking', $refresh_sequence_data);
				}
				
				$latest_project_refresh_tracking = [
					'project_id' => $project_data['project_id'],
					'project_last_refresh_time' => date('Y-m-d H:i:s'),
					'project_next_refresh_time' => $next_refresh_date
				];
				$this->db->insert('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking);

				// save data into users newly posted projects sent notification tracking table
				if(!empty($parent_categories_id)) {
					$category_mapping_data = $this->project_model->get_projects_professionals_categories_mapping_data_based_on_categories_id($parent_categories_id);
				}
				if(!empty($category_mapping_data)) {
					$this->project_model->add_data_in_to_users_new_posted_projects_sent_notification_table($category_mapping_data, $parent_categories_id, $open_bidding_data, $config);
				}
				// save data into users newly posted projects sent notification tracking table when project posted by favorite employer
				$users = $this->project_model->get_users_id_from_favorite_employer_id($open_bidding_data['project_owner_id']);
				if(!empty($users)) {
					$this->project_model->add_data_in_to_users_new_posted_projects_sent_notification_table_favorite_employer($users, $parent_categories_id, $open_bidding_data, $config);
				}
			}
				
			
			// move data from awaiting_moderation_projects_attachments table to projects_attachments
			$this->db->select('awaiting_moderation_project_attachment_name');
			$this->db->from('awaiting_moderation_projects_attachments');
			$this->db->where('project_id',$project_id);
			$this->db->order_by('id',"asc");
			$project_attachment_result = $this->db->get();
			$project_attachment_data = $project_attachment_result->result_array();
			// connect ftp to move attachments
			//$CI = & get_instance();
			$this->load->library('ftp');
			$conf['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$conf['ftp_username'] = FTP_USERNAME;
			$conf['ftp_password'] = FTP_PASSWORD;
			$conf['ftp_port'] = FTP_PORT;
			$conf['debug'] = TRUE;
			try {
				$this->ftp->connect($conf);
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$projects_temp_dir = PROJECT_TEMPORARY_DIR;
				$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
				$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir);
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.DIRECTORY_SEPARATOR);
				
				
				//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
				//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir, 0777);// create directory for open for bidding project
				//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id, 0777); // create the project directory if it is not exists
				if(!empty($project_attachment_data)) {
					//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir , 0777); // create the project attachment directory
					
					$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir);
					
					
					
					
					foreach($project_attachment_data as $attachment_key => $attachment_value){
					
						if(!empty($attachment_value['awaiting_moderation_project_attachment_name'])){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id.$project_owner_attachments_dir .$attachment_value['awaiting_moderation_project_attachment_name'];
							$file_size = $this->ftp->get_filesize($source_path);
							if($file_size != '-1'){
								$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir.$attachment_value['awaiting_moderation_project_attachment_name'];
								$this->ftp->move($source_path, $destination_path);
								$this->db->insert ('projects_attachments', array('project_id'=>$project_id, 'project_attachment_name'=> $attachment_value['awaiting_moderation_project_attachment_name']));
							}
						}
					}
					$this->db->delete('awaiting_moderation_projects_attachments', array('project_id' => $project_id));
					
				}
				if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id))) {
					$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id.DIRECTORY_SEPARATOR);// delete project directory 
				}
				$this->ftp->close();
			} catch(Exception $e) {

			}
			$this->session->set_flashdata('succ_msg', 'Project approved successfully');
			//redirect(base_url() . 'project/awaiting_approval'); 
			if($this->input->get ('search_element')){
			$search_element = $this->input->get ('search_element');
			}else{
				$search_element = '';
			}
			if($this->input->get ('per_page')){
				$page = $this->input->get ('per_page');
			}else{
				$page = 1;
			}	
			$new_counter = $page;
			if($page != null) {
				if($page == 1) {
					$start = 0;
					} else {
					
					$start = ($page - 1) * PAGING_LIMIT;
				}
			} else {
				$start = 0;
			}
			
			$project_listing = $this->project_model->get_awaiting_moderation_project_list($start,PAGING_LIMIT,$search_element);
			
			if(empty($project_listing)){
				
				$page = $new_counter-1;
				if($page != null) {
				
					if($page == 1) {
						$start = 0;
					} else {
						
					$start = ($page - 1) *PAGING_LIMIT;
					}
				} else {
					$start = 0;
					}
			}
			redirect(base_url()."project/awaiting_approval/?search_element=" . $search_element."&per_page=".$page);
			
			
		}else{
			redirect(base_url() . 'project/awaiting_approval'); 
		}
		
	}
	
	/* 
	This is used to rejected the awaiting moderation project.So after rejected the project it will move to awaiting moderation table to draft table
	*/
	public function rejected_awaiting_moderation_project($project_id)
    {
	
		if(empty($project_id)){
			redirect(base_url() . 'project/awaiting_approval');  
		}
		$project_owner_id = $this->auto_model->getFeild('project_owner_id','projects_awaiting_moderation','project_id',$project_id);
		if(empty($project_owner_id)){
			redirect(base_url() . 'project/awaiting_approval');  
		}
		include '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_projects_custom_config.php';
		
		include '../application/config/'.SITE_LANGUAGE.'_server_settings_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_post_project_settings_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_projects_settings_custom_config.php';
		
		
		$this->db->select('*');
		$this->db->from('projects_awaiting_moderation');
		$this->db->where('projects_awaiting_moderation.project_id',$project_id);
		$project_result = $this->db->get();
		$project_data = $project_result->result_array();
		
		
		$count_draft_project = $this->db // count the number of record in temp_projects table
				->select ('id')
				->from ('projects_draft')
				->where('project_id',$project_id)
				->get ()->num_rows ();
		if($count_draft_project == 0 && !empty($project_data)){
			$project_data = array_shift($project_data);
			// Insert into projects_draft table
			$draft_data = array(
				'project_id' => $project_data['project_id'],
				'project_owner_id' => $project_data['project_owner_id'],
				'project_save_date' => date('Y-m-d H:i:s'),
				'project_title' => $project_data['project_title'],
				'project_description' => $project_data['project_description'],
				'locality_id'=>$project_data['locality_id'],
				'county_id'=>$project_data['county_id'],
				'postal_code_id' => $project_data['postal_code_id'],
				'project_type' => $project_data['project_type'],
				'min_budget' => $project_data['min_budget'],
				'max_budget' => $project_data['max_budget'],
				'confidential_dropdown_option_selected' => $project_data['confidential_dropdown_option_selected'],
				'not_sure_dropdown_option_selected' => $project_data['not_sure_dropdown_option_selected'],
				'escrow_payment_method' => $project_data['escrow_payment_method'],
				'offline_payment_method' => $project_data['offline_payment_method'],
				'featured' => $project_data['featured'],
				'urgent' => $project_data['urgent'],
				'sealed' => $project_data['sealed'],
				'hidden' => $project_data['hidden']
			);
			
			$this->db->insert('projects_draft', $draft_data);
			$profile_folder  = $this->auto_model->getFeild('profile_name','users','user_id',$project_owner_id);
			################ genreate the log activity #################
			$project_url_link = HTTP_WEBSITE_HOST.$config['project_detail_page_url']."?id=".$project_id;
			if($project_data['project_type'] == 'fulltime'){
				$project_activity_log_displayed_message = $config['fulltime_project_rejected_by_admin_user_activity_log_displayed_message_sent_to_po'];
			}else{
			
				$project_activity_log_displayed_message = $config['project_rejected_by_admin_user_activity_log_displayed_message_sent_to_po'];
			}
			$project_activity_log_displayed_message = str_replace(array("{project_title}", "{project_url_link}"),array(htmlspecialchars($project_data['project_title'], ENT_QUOTES), $project_url_link),$project_activity_log_displayed_message);
			user_display_log($project_activity_log_displayed_message,$project_owner_id);
			############################################################
			
			// Insert data into draft_projects_tags
			
			$this->db->select('awaiting_moderation_project_tag_name');
			$this->db->from('awaiting_moderation_projects_tags');
			$this->db->where('project_id',$project_id);
			$this->db->order_by('id',"asc");
			$project_tag_result = $this->db->get();
			$project_tag_data = $project_tag_result->result_array();
			if(!empty($project_tag_data)) {
				foreach($project_tag_data as $tag_value) {
					$this->db->insert ('draft_projects_tags', array('project_id'=>$project_id,'draft_project_tag_name'=> $tag_value['awaiting_moderation_project_tag_name']));
				}
			}
			
			// Insert into draft_projects_categories_listing_tracking
			$this->db->select('awaiting_moderation_project_category_id,awaiting_moderation_project_parent_category_id');
			$this->db->from('awaiting_moderation_projects_categories_listing_tracking');
			$this->db->where('project_id',$project_id);
			$this->db->order_by('id',"asc");
			$project_category_result = $this->db->get();
			$project_category_data = $project_category_result->result_array();
			if(!empty($project_category_data)) {
				foreach($project_category_data as $category_key => $category_value){
					$this->db->insert ('draft_projects_categories_listing_tracking', array('project_id'=>$project_id,'draft_project_category_id'=> $category_value['awaiting_moderation_project_category_id'],'draft_project_parent_category_id'=> $category_value['awaiting_moderation_project_parent_category_id'])); 
				}
			}
			
			$this->db->delete('awaiting_moderation_projects_tags', array('project_id' => $project_id));
			$this->db->delete('awaiting_moderation_projects_categories_listing_tracking', array('project_id' => $project_id));
			$this->db->delete('projects_awaiting_moderation', array('project_id' => $project_id));
			$this->session->set_flashdata('succ_msg', 'Project rejected successfully');

			$this->db->delete('projects_awm_membership_included_upgrades_purchase_tracking', ['project_id' => $project_id, 'project_owner_id' => $project_owner_id]);

			$bonus_money_upgrade_purchase = $this->db->get_where('projects_awm_bonus_based_upgrades_purchase_tracking', ['project_id' => $project_id, 'project_owner_id' => $project_owner_id])->result_array();
			if(!empty($bonus_money_upgrade_purchase)) {
				$reverted_bonus_money = array_sum(array_column($bonus_money_upgrade_purchase, 'project_upgrade_purchase_value'));
				$this->db->where(['user_id' => $project_owner_id]);
				$this->db->set('bonus_balance', 'bonus_balance + '.$reverted_bonus_money, false);
				$this->db->update('users_details');
				$this->db->delete('projects_awm_bonus_based_upgrades_purchase_tracking', ['project_id' => $project_id, 'project_owner_id' => $project_owner_id]);
			}
			
			$real_money_upgrade_purchase = $this->db->get_where('projects_awm_real_money_upgrades_purchase_tracking', ['project_id' => $project_id, 'project_owner_id' => $project_owner_id])->result_array();
			if(!empty($real_money_upgrade_purchase)) {
				$reverted_real_money = array_sum(array_column($real_money_upgrade_purchase, 'project_upgrade_purchase_value'));
				$this->db->where(['user_id' => $project_owner_id]);
				$this->db->set('user_account_balance', 'user_account_balance + '.$reverted_real_money, false);
				$this->db->update('users_details');
				$this->db->delete('projects_awm_real_money_upgrades_purchase_tracking', ['project_id' => $project_id, 'project_owner_id' => $project_owner_id]);
			}

			// trigger socket event to update awaiting moderation project list on user dashboard
			//$url = PROJECT_MANAGEMENT_SOCKET_URL."/awaitingModerationProjectRejectedByAdmin/".$project_data['project_owner_id'].'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
			
			$url = PROJECT_MANAGEMENT_SOCKET_URL."/awaitingModerationProjectRejectedByAdmin/".$project_data['project_owner_id']."/".$project_id.'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
			
			
			$options = array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false
			);
			$ch = curl_init( $url );
			curl_setopt_array( $ch, $options );
			curl_exec( $ch );
			curl_close( $ch );

			// move data from awaiting_moderation_projects_attachments table to projects_attachments
			$this->db->select('awaiting_moderation_project_attachment_name');
			$this->db->from('awaiting_moderation_projects_attachments');
			$this->db->where('project_id',$project_id);
			$this->db->order_by('id',"asc");
			$project_attachment_result = $this->db->get();
			$project_attachment_data = $project_attachment_result->result_array();
			// connect ftp to move attachments
			include_once '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
			//$CI = & get_instance();
			$this->load->library('ftp');
			$conf['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$conf['ftp_username'] = FTP_USERNAME;
			$conf['ftp_password'] = FTP_PASSWORD;
			$conf['ftp_port'] = FTP_PORT;
			$conf['debug'] = TRUE;
			try {
				$this->ftp->connect($conf);
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$projects_temp_dir = PROJECT_TEMPORARY_DIR;
				$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
				$project_draft_dir = PROJECT_DRAFT_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir);
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.DIRECTORY_SEPARATOR);
				$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.$project_owner_attachments_dir);
	
				if(!empty($project_attachment_data)) {
						
					foreach($project_attachment_data as $attachment_key => $attachment_value){
					
						if(!empty($attachment_value['awaiting_moderation_project_attachment_name'])){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id.$project_owner_attachments_dir .$attachment_value['awaiting_moderation_project_attachment_name'];
							$file_size = $this->ftp->get_filesize($source_path);
							if($file_size != '-1'){
								$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.$project_owner_attachments_dir .$attachment_value['awaiting_moderation_project_attachment_name'];
								$this->ftp->move($source_path, $destination_path);
								$this->db->insert ('draft_projects_attachments', array('project_id'=>$project_id,
								'draft_project_attachment_name'=> $attachment_value['awaiting_moderation_project_attachment_name']));
							}
						}
					}
					$this->db->delete('awaiting_moderation_projects_attachments', array('project_id' => $project_id));
				}
				
				if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id)))
				{
					$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id);// delete project directory 
				}
				$this->ftp->close();
			} catch(Exception $e) {

			}
			if($this->input->get ('search_element')){
			$search_element = $this->input->get ('search_element');
			}else{
				$search_element = '';
			}
			if($this->input->get ('per_page')){
				$page = $this->input->get ('per_page');
			}else{
				$page = 1;
			}	
			$new_counter = $page;
			if($page != null) {
				if($page == 1) {
					$start = 0;
					} else {
					
					$start = ($page - 1) * PAGING_LIMIT;
				}
			} else {
				$start = 0;
			}
			
			$project_listing = $this->project_model->get_awaiting_moderation_project_list($start,PAGING_LIMIT,$search_element);
			
			if(empty($project_listing)){
				
				$page = $new_counter-1;
				if($page != null) {
				
					if($page == 1) {
						$start = 0;
					} else {
						
					$start = ($page - 1) *PAGING_LIMIT;
					}
				} else {
					$start = 0;
					}
			}
			redirect(base_url()."project/awaiting_approval/?search_element=" . $search_element."&per_page=".$page);
			//redirect(base_url() . 'project/awaiting_approval');
		}
		else{
			redirect(base_url() . 'project/awaiting_approval');
		}
	}	
	
	/* 
	This is used to show the project detail 
	*/
	public function project_detail($project_id)
    {
		$data['data'] = $this->auto_model->leftPannel();
		$lay['lft'] = "inc/section_left";
		$project_status_table_array = $this->project_model->get_project_status_table_name($project_id);
		if(!empty($project_status_table_array['table_name'])){
			include_once '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
			$this->db->select($project_status_table_array['table_name'].'.*,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,users.account_type,users.first_name,users.last_name,users.profile_name,users.company_name,users.is_authorized_physical_person');
			$this->db->from($project_status_table_array['table_name']);
			$this->db->join('counties', 'counties.id = '.$project_status_table_array['table_name'].'.county_id', 'left');
			$this->db->join('localities', 'localities.id = '.$project_status_table_array['table_name'].'.locality_id', 'left');
			$this->db->join('postal_codes', 'postal_codes.id = '.$project_status_table_array['table_name'].'.postal_code_id', 'left');
			if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
				$this->db->join('users', 'users.user_id = '.$project_status_table_array['table_name'].'.employer_id', 'left');
				$this->db->where($project_status_table_array['table_name'].'.fulltime_project_id',$project_id);
			} else {
				$this->db->join('users', 'users.user_id = '.$project_status_table_array['table_name'].'.project_owner_id', 'left');
				$this->db->where($project_status_table_array['table_name'].'.project_id',$project_id);
			}
			$project_result = $this->db->get();
			$project_data = $project_result->result_array();
			
			if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') { 
				$project_data[0]['project_id'] = $project_data[0]['fulltime_project_id'];
				$project_data[0]['project_owner_id'] = $project_data[0]['employer_id'];
				$project_data[0]['project_posting_date'] = $project_data[0]['fulltime_project_posting_date'];
				$project_data[0]['project_expiration_date'] = $project_data[0]['fulltime_project_expiration_date'];
				$project_data[0]['project_title'] = $project_data[0]['fulltime_project_title'];
				$project_data[0]['project_description'] = $project_data[0]['fulltime_project_description'];
				$project_data[0]['min_budget'] = $project_data[0]['min_salary'];
				$project_data[0]['max_budget'] = $project_data[0]['max_salary'];
				if(isset($project_data[0]['fulltime_project_cancellation_date'])){
					$project_data[0]['project_cancellation_date'] = $project_data[0]['fulltime_project_cancellation_date'];
				}else{
					$project_data[0]['project_cancellation_date'] = '';
					
				}	
			}
			
			
			$data['project_data'] = $project_data;
			$project_tag_data = $this->project_model->get_project_tags($project_id,$project_status_table_array['project_status']);
			$data['project_tag_data'] = $project_tag_data;
			$project_categories_data =$this->project_model->get_project_categories($project_id,$project_status_table_array['project_status']);
			$data['project_category_data'] = $project_categories_data;
			$profile_folder = !empty($project_data[0]['profile_name']) ? $project_data[0]['profile_name'] : '';
			$project_attachment_data = $this->project_model->get_project_attachments($project_id,$profile_folder,$project_status_table_array['project_status']);
			$data['project_attachment_data'] = $project_attachment_data;
			
			// load the view according to project status//
			if($project_status_table_array['table_name'] == 'projects_awaiting_moderation'){
				$this->layout->view('awaiting_moderation_project_detail', $lay, $data);
			} else if($project_status_table_array['table_name'] == 'projects_open_bidding' && strtotime($project_data[0]['project_expiration_date']) >= time()){			
				$this->layout->view('open_for_bidding_project_detail', $lay, $data);			
			} else if(($project_status_table_array['project_status'] == 'expired' || $project_status_table_array['table_name'] == 'projects_open_bidding') && strtotime($project_data[0]['project_expiration_date']) <= time()) {
				$this->layout->view('expired_project_detail', $lay, $data);			
			} else if($project_status_table_array['project_status'] == 'cancelled') {
				$this->layout->view('canceled_project_detail', $lay, $data);			
			} else if($project_status_table_array['project_status'] == 'awarded') {
				$this->layout->view('awarded_project_detail', $lay, $data);			
			}else if($project_status_table_array['project_status'] == 'in_progress') {
				$this->layout->view('in_progress_project_detail', $lay, $data);			
			}else if($project_status_table_array['project_status'] == 'completed') {
				$this->layout->view('completed_project_detail', $lay, $data);			
			}	


			
		}
	}
	
	public function download_project_attachment ($attachment_id,$profile_folder,$project_status)
    {
		$this->project_model->download_project_attachment($attachment_id,$profile_folder,$project_status);
	}
	
	 
        
	public function edit_project()
	{			
		$data['id']=$id = $this->uri->segment(4);
		$data['status']=$status = $this->uri->segment(3);
		$data['data'] = $this->auto_model->leftPannel();
		$lay['lft'] = "inc/section_left";
		$data['parent_cat']=$this->project_model->getParentcat();

		if($id && $status)
		{
			$data['all_data'] = $this->project_model->getOpenProject($status,$id);
		}
		//echo "<pre>";print_r($data['all_data']);die;
		
		if($this->input->post('submit'))
		{
					//print_r($this->input->post());die();
                    $this->form_validation->set_rules('title', 'Title', 'required');    
                    //$this->form_validation->set_rules('category', 'Category', 'required');
					//$this->form_validation->set_rules('skills', 'Skills', 'required');
					$this->form_validation->set_rules('description', 'Description', 'required');
					// $this->form_validation->set_rules('project_type', 'Project Type', 'required');
					//$this->form_validation->set_rules('budgetall', 'Budget', 'required');
				
                    if ($this->form_validation->run() == FALSE)
                    {						
                        $this->layout->view('edit', $lay, $data);
                    }
                    else
                    {					
						$post_data['title'] = $this->input->post('title');
						
						//$post_data['category'] = $this->input->post('category');
						
						$post_data['description'] =$this->input->post('description');
					
						//post_data['project_type'] = $this->input->post('project_type');
						
						//$post_data['skills'] = $this->input->post('skills');
						
						$b=  explode("#",$this->input->post('budgetall'));
						//$post_data['buget_min']=$b[0];
						//$post_data['buget_max']=$b[1];	 
		
						//$post_data['featured']=	$this->input->post('featured');					
						//$post_data['budgetall'] = $this->input->post('budgetall');
						/*
						$ball=$this->input->post('budgetall');
							$bmin=0;
							$bmax=0;
							if($ball!=""){ 
							$b=  explode("-",$this->input->post('budgetall'));
							$bmin=$b[0];
							$bmax=$b[1];
							}
							else{ 
							$bmin=$this->input->post('budget_min');
							$bmax=$this->input->post('budget_max');
							} */
						
						
						if($status=='O'){
							$fnc = 'open';
						}elseif($status=='F'){
							$fnc = 'frozen';
						}
						elseif($status=='P'){
							$fnc = 'process';
						}
						elseif($status=='C'){
							$fnc = 'complete';
						}
						elseif($status=='E'){
							$fnc = 'expire';
						}
							
			            $update = $this->project_model->updateProject($post_data,$id,$status);
						
							
			if ($update)
            {
                            $this->session->set_flashdata('succ_msg', 'Update Successfully');
							$ch = curl_init();
				curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
				curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_URL,"http://88.99.226.7:8080/refreshprojects");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$server_output = curl_exec ($ch);
				curl_close($ch);
			}
           else
           {
                            $this->session->set_flashdata('error_msg', 'unable to Update');
		    }
			redirect(base_url() . 'project/'.$fnc);
                    }
		
		
		}
		
		
			//print_r($data['all_data']);die();
			$this->layout->view('edit', $lay, $data);
		
		
	}	
	
		  
    public function getBudget(){
        $type = $this->input->post('type');
        $result = "<option value=''>--- Please Select ---</option>";
        if($type == 'H'){
            $results = $this->project_model->getHourlyRate();
            foreach($results as $bg){                             
                if($bg['max'] != 0){                              
                    $result .= "<option value=".$bg['min']."#".$bg['max']." >$".$bg['min']."/hr to $".$bg['max']."/hr</option>";
                }else{
                    $result .= "<option value=".$bg['min']."#0>More then $".$bg['min']."/hr</option>";
                }
            } 
            echo $result;          
        }else if($type == 'F'){
            $results = $this->project_model->getFixed();
            foreach($results as $bg){                             
                if($bg['max'] != 0){                              
                    $result .= "<option value=".$bg['min']."#".$bg['max']." >Between $".$bg['min']." and $".$bg['max']."</option>";
                }else{
                    $result .= "<option value=".$bg['min']."#0>More then $".$bg['min']."</option>";
                }
            } 
            echo $result;      
        }else if($type == 'FU'){
            $results = $this->project_model->getFulltime();
            foreach($results as $bg){                             
                if($bg['max'] != 0){                              
                    $result .= "<option value=".$bg['min']."#".$bg['max']." >Between $".$bg['min']." and $".$bg['max']."</option>";
                }else{
                    $result .= "<option value=".$bg['min']."#0>More then $".$bg['min']."</option>";
                }
            } 
            echo $result;      
        }                
    }
    
 

}