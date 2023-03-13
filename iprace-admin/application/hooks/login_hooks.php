<?php

class Login_check {
 var $CI;

    function __construct(){
        $this->CI =& get_instance();
    }
  function is_login() {
  
  if (!isset($this->CI->session))
    {
        $this->CI->load->library('session');
    }
	
        $this->CI->load->database();

 $userData = $this->CI->session->userdata('user');
//print_r($userData);
  $login=$this->CI->router->class;
 $logout=$this->CI->router->method;
 $cur_page = $login;
	if ($logout != "") {
		$cur_page.='/' . $logout;
	}

	
	
 if($userData!='' && $login!="login" && $logout!="logout" ){

			$this->CI->db->select('admin_id,  username, email,type,last_login,password');
			$query = $this->CI->db->get_where("admin", array("username" => $userData->username, "password" => $userData->password));
			$result = $query->row();
			if (count($result) == 0) {
			//$this->session->unset_userdata('user');
				redirect(VPATH."login");
			}
		}elseif($userData!='' && $login=="login" && $logout!="logout" && $logout=="forgotpass"){
		
			$this->CI->db->select('admin_id,  username, email,type,last_login,password');
			$query = $this->CI->db->get_where("admin", array("username" => $userData->username, "password" => $userData->password));
			$result = $query->row();
			if (count($result) == 0) {
				//$this->session->unset_userdata('user');
				redirect(VPATH."login");
			}else{
				redirect(VPATH);
			}		
		}elseif($userData=='' && $login!="login" && $logout!="logout"){
			redirect(VPATH."login");
		}else{
		
		
		}
 
 }
 }