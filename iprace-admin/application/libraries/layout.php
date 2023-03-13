<?php

class Layout {

    private $CI;
    private $layout_title = NULL;
    private $layout_description = NULL;
    private $data;

    public function __construct() {
        $this->CI = & get_instance();
    }

    public function set_title($title) {
        $this->layout_title = $title;
    }

    public function set_description($description) {
        $this->layout_description = $description;
    }

    public function set_assest($params) {
        $this->data = $params;
    }

    public function view($view_name, $layouts = array(), $params = array(), $default = true) {
        if (is_array($layouts) && count($layouts) >= 1) {
            foreach ($layouts as $layout_key => $layout) {
                $params[$layout_key] = $this->CI->load->view($layout, $params, true);
            }
        }
        if ($default) {
            $this->data['logo'] = "";
            $this->CI->load->view('inc/scriptsrc', $this->data);
            $this->CI->load->view('inc/header', $this->data);
			//$this->CI->load->view('inc/dashboard_graph', $this->data);
            $this->CI->load->view('inc/section_left', $this->data);
            $this->CI->load->view($view_name, $params);
            $this->CI->load->view('inc/footer');
        } else {
            //$this->data['logo'] = $this->CI->auto_model->getFeild("site_logo", "setting", "id='45'");
            //$this->CI->load->view('inc/scriptsrc');
            $this->CI->load->view($view_name, $params);
        }
    }

}

?>