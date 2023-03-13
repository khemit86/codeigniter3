<?php

class Layout
{
    private $CI;
    private $data;
    public function __construct ()
    {
        $this->CI = & get_instance ();
    }
    public function view ($view_name, $layouts = array (), $params = array (), $default = '', $include_search = 'Y')
    {
        if (is_array ($layouts) && count ($layouts) >= 1) {
            foreach ($layouts as $layout_key => $layout) {
                $params[$layout_key] = $this->CI->load->view ($layout, $params, true);
            }
        }
        
        //echo $default;exit;
        //echo $view_name;exit;
        //print_r($params);exit;
        $this->data = $params;
        
		if ($default == 'include' || $default == 'normal' || $default == 'share') {
            $this->CI->load->view ('header', $this->data);
            $this->CI->load->view ($view_name, $this->data);
            $this->CI->load->view ('footer', $this->data);
        } elseif ($default == 'ajax') {
            $this->CI->load->view ($view_name, $params);
        } elseif ($default == 'us') {
            $this->CI->load->model ('user/user_model');
            $this->CI->load->view ($view_name, $params);
        } elseif ($default == 'feed') {
            $this->CI->load->view ($view_name, $params);
        } elseif ($default == 'script') {
            $this->CI->load->view ($view_name, $params);
        }
		elseif ($default == 'error_404') {
			$this->CI->load->view ('header_404_page', $this->data);
            $this->CI->load->view ($view_name, $params);
        }
    }
}

?>
