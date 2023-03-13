<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class user_model extends BaseModel {

    public function __construct() {
        return parent::__construct();
    }

    public function login() {
        $username = trim($this->input->post("username"));
        $password = $this->input->post("password");
        $response = array();
        $this->db->select('admin_id,username, email,type,last_login,password');
        $query = $this->db->get_where("admin", array("username" => $username, "password" => md5($password), "status" => 'Y'));

       $result = $query->row();
        if (count($result) == 0) {
            $response['status'] = "error";
        } else {
            $response['status'] = "success";
            $this->session->set_userdata('user', $result);
        }
        return $response;
    }

    public function register() {
        $response = array();
        $data = array(
            'username' => $this->input->post('username'),
            'name' => $this->input->post('name'),
            'password' => md5($this->input->post('password')),
            'email' => $this->input->post('email'),
            'created_date' => date('Y-m-d h:m:s'),
            'status' => 1
        );
        parent::insert("users", $data);
//        if ($this->db->insert('users', $data)) {
        if (is_array($data)) {
            $response['status'] = "success";
            $response['id'] = "";
        } else {
            $response['status'] = "error";
            $response['id'] = "";
        }
        return $response;
    }

	

	public function count_member($status='')
    {
	  $this->db->select('count(user_id) as total');
	  $rs=$this->db->get("users");
	  foreach($rs->result() as $val)
		{
			$count = $val->total;

		}
		return $count;

	}




	
	public function getMember($status='',$date='')
    {
	  $this->db->select('count(user_id) as am');

	$this->db->where('status',$status);
	$this->db->like('reg_date',$date);
	  $rs=$this->db->get("user");
	  foreach($rs->result() as $val)
		{
			$count = $val->am;

		}
		return $count;

	}


	public function getFinance($status='',$date='')
    {
	  $this->db->select('sum(amount) as am');

	$this->db->where('transction_type',$status);
	$this->db->like('transction_date',$date);
	  $rs=$this->db->get("transaction");

	  foreach($rs->result() as $val)
		{
			$count = $val->am;

		}
		return $count;

	}
	public function getProfit($date='')
    {
	  $this->db->select('sum(profit) as am');

	$this->db->like('transction_date',$date);
	  $rs=$this->db->get("transaction");
	  foreach($rs->result() as $val)
		{
			$count = $val->am;

		}
		return $count;

	}

	public function count_amember()
    {
	  $this->db->select('count(user_id) as total');
	   $rs=$this->db->get_where("user",array('status'=>'Y'));


	  foreach($rs->result() as $val)
		{
			$count = $val->total;

		}
		return $count;

	}
	public function count_imember()
    {
	  $this->db->select('count(user_id) as total');
	   $rs=$this->db->get_where("user",array('status'=>'N'));


	  foreach($rs->result() as $val)
		{
			$count = $val->total;

		}
		return $count;

	}
	public function count_event()
    {
	  $this->db->select('count(event_id) as total');
	   $rs=$this->db->get_where("event");


	  foreach($rs->result() as $val)
		{
			$count = $val->total;

		}
		return $count;

	}
	public function count_photo()
    {
	  $this->db->select('count(gal_id) as total');
	   $rs=$this->db->get_where("gallery");


	  foreach($rs->result() as $val)
		{
			$count = $val->total;

		}
		return $count;

	}

	public function count_completed_task()
	{
	$this->db->select('count(id) as total');
	$rs=$this->db->get_where("task_status_date",array('task_type'=>3,'status'=>'Y'));
	foreach ($rs ->result() as $val)
	{
	$count_comp = $val->total;
	}
	return $count_comp;
	}


	public function count_pay_task()
	{
	$this->db->select('count(id) as total');
	$rs=$this->db->get_where("task_status_date",array('task_type'=>4,'status'=>'Y'));
	foreach ($rs ->result() as $val)
	{
	$count_pay = $val->total;
	}
	return $count_pay;
	}

	public function count_cities()
	{
	$this->db->select('count(id) as total');
	$rs=$this->db->get_where("city");
	foreach ($rs ->result() as $val)
	{
	$count_city = $val->total;
	}
	return $count_city;
	}

	public function count_states()
	{
	$this->db->select('count(id) as total');
	$rs=$this->db->get_where("state");
	foreach ($rs ->result() as $val)
	{
	$count_state = $val->total;
	}
	return $count_state;
	}

/* 	public function count_country()
	{
		$this->db->select('count(id) as total');
		$rs=$this->db->get_where("countries");
		foreach ($rs ->result() as $val)
		{
		$count_coun = $val->total;
		}
		return $count_coun;
	} */

	public function count_task()
	{
	$this->db->select('count(id) as total');
    $rs = $this->db->get('task_status_date');

	foreach ($rs->result() as $val) {

        $count_post = $val->total;

	   }
	return $count_post;

	}
	public function count_Listing($status)
    {
	  $this->db->select('count(listing_id) as total');
	   $rs=$this->db->get_where("listing_table",array('status'=>$status));


	  foreach($rs->result() as $val)
		{
			$count = $val->total;

		}
		return $count;

	}



	public function listing_graph()
	{
		$rs= $this->db->query("select count(`listing_id`) AS 'TOTAL',month(`post_date`) as 'curmonth' from yellow_listing_table where status='A' group by curmonth ");
		//echo $this->db->last_query();
		$data=array();

		foreach ($rs->result() as $row) {
			$data[]=array(
				'postdate' => $row -> curmonth,
				'total_list' => $row ->TOTAL
			);
		}
		//echo "<pre>";
		//print_r($data);
		//die();
		return $data;

	}
	public function listing_inactive_graph()
	{
		$rs= $this->db->query("select count(`listing_id`) AS 'TOTAL',month(`post_date`) as 'curmonth' from yellow_listing_table where status='I' group by curmonth ");
		//echo $this->db->last_query();
		$data=array();

		foreach ($rs->result() as $row) {
			$data[]=array(
				'postdate' => $row -> curmonth,
				'total_list' => $row ->TOTAL
			);
		}
		//echo "<pre>";
		//print_r($data);
		//die();
		return $data;

	}

	public function count_Adsense($pages)
    {
	  $this->db->select('count(id) as total');
	   $rs=$this->db->get_where("banner",array('type'=>$pages));


	  foreach($rs->result() as $val)
		{
			$count = $val->total;

		}
		return $count;

	}
	public function count_Adsense_status($status)
    {
	  $this->db->select('count(id) as total');
	   $rs=$this->db->get_where("banner",array('status'=>$status));


	  foreach($rs->result() as $val)
		{
			$count = $val->total;

		}
		return $count;

	}

    function getaldata($attr, $table, $by, $value) {
        $this->db->select($attr);
        $rs   = $this->db->get_where($table, [$by => $value]);
        $data = [];
        foreach ($rs->result() as $row) {
            $data[] = ['body' => $row->template, 'subject' => $row->subject];
            break;
        }

        return $data;
    }
}	
  
  
  
  


