<?php

if (!function_exists('get_user')) {

    function get_user($id = '') {
        $CI = & get_instance();
        $user = new stdClass();
        $user->id = 0;
        if ($CI->session->userdata('user'))
            $user = $CI->session->userdata('user');
        return $user;
    }

}

function pre($data, $exit = false)
{
    echo "<pre>";
    print_r($data);
    if(!$exit)
        die();
}
// @sid remove directory and it's sub-directory recursively [used in member model deleteMember method ]
if(!function_exists('delTree')) {
	function delTree($dir) {
		include_once '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
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
			$dir = $users_ftp_dir.$dir.'/';
			$flag = $CI->ftp->delete_dir($dir);
			$CI->ftp->close();
		} catch(Exception $e) {
			echo $e->getMessage();
		}
		return $flag; 
	}
}

function getInt($val) {
	return (int)$val;
}

if(! function_exists('user_display_log')) {
	function user_display_log ($msg = '', $user_id = '') {
		$CI = & get_instance();
		$data = [
			'user_id' => $user_id,
			'activity_description' => $msg
		];
		$CI->db->insert('users_activity_log_tracking', $data);
	}     
}

if(! function_exists('getInt')) {
	function getInt($val) {
		return (int)$val;
	}
}

/*
* @sid 
* Calculate next refreh time based on next_refresh_time value from db and custom configuration settings -> here we check if next_refresh_time is less then current datetime then we increase it by custom configuration setting untill it greater than current datetime
* Used in projects controller ajax_update_latest_project_dashboard_view method
*/
if(! function_exists('get_next_refresh_time')) {
	function get_next_refresh_time($next_refresh_time, $custom_config_time) {
		$refresh_time = date('Y-m-d H:i:s', strtotime('+'.(int)$custom_config_time[0].' hour +'.(int)$custom_config_time[1].' minutes +'.(int)$custom_config_time[2].' seconds', strtotime($next_refresh_time)));
		if(strtotime($refresh_time) < strtotime(date('Y-m-d H:i:s'))) {
			$refresh_time = get_next_refresh_time($refresh_time, $custom_config_time);
		} 
		return $refresh_time;
	}
}

if ( ! function_exists ('get_hires_count_fulltime_project'))
{
	function get_hires_count_fulltime_project ($project_id)
	{
		
		$CI = & get_instance ();
		$CI->load->model ('project/project_model');
		return $CI->project_model->get_hires_count_fulltime_project($project_id);
	}

}
/**
 * This function is used to format money amount to display
*/
if(!function_exists('format_money_amount_display')) {
	function format_money_amount_display($num) {
		return str_replace(".00","",number_format($num, 2, '.', ' '));
	}
}

/**
 * This method is used to return string of specified limit
 * If string doesen't contains at least 1 word then it will return whole input string as it is
 * If string limit is less then specified limit then also whole input string will return
 * If string is greater then specified limit then it will return complete word of string with 3 dots at the end
*/
if(! function_exists('get_correct_string_based_on_limit')) {
	function get_correct_string_based_on_limit($string, $limit = 1) {
		$string = preg_replace('!\s+!', ' ', $string);
		if(strpos($string, ' ') !== false){
			if(strlen($string) < $limit) {
				return $string;
			}
			$exploaded_arr = explode(' ', $string);
			$strarr = [];
			foreach($exploaded_arr as $val) {
				$tmp_str = implode(' ', $strarr);
				if(strlen($tmp_str) < $limit) {
					array_push($strarr, $val);
				} 
			}
			if(!empty($strarr)) {
				return trim(implode(' ', $strarr)).'...';
			} else {
				return trim($string);
			}
		} else {
			return trim($string);
		}
	}
}

/**
 * Generate pagination links for any page listing  based on parameter passed by calling routin
 */
if(! function_exists('generate_pagination_links')) {
	function generate_pagination_links($total, $url, $limit, $no_of_pagination_links, $page = '', $param = [],$page_query_string = '') {
	
		
		$CI = & get_instance ();
		$CI->load->library ('pagination');
		$config = array();
		if(!empty($page)) {
			$config["cur_page"] = $page;
		}
		if($page_query_string)
		{
			$config['page_query_string'] = TRUE;

		}
		$config["base_url"] = base_url($url);
		$config["total_rows"] = $total;
		$config["per_page"] = $limit;
		$config["uri_segment"] = 3;
		$config["use_page_numbers"] = TRUE;
		$config["full_tag_open"] = '<ul class="pagination">';
		$config["full_tag_close"] = '</ul>';
		$config['first_link'] = '<i class="fa fa-angle-double-left" aria-hidden="true"></i>';
		$config["first_tag_open"] = '<li class="page-item">';
		$config["first_tag_close"] = '</li>';
		$config['last_link'] = '<i class="fa fa-angle-double-right" aria-hidden="true"></i>';
		$config["last_tag_open"] = '<li class="page-item">';
		$config["last_tag_close"] = '</li>';
		$config['next_link'] = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
		$config["next_tag_open"] = '<li class="page-item">';
		$config["next_tag_close"] = '</li>';
		$config["prev_link"] = '<i class="fa fa-angle-left" aria-hidden="true"></i>';
		$config["prev_tag_open"] = "<li class='page-item'>";
		$config["prev_tag_close"] = "</li>";
		$extra_attr = '';
		if(!empty($param)) {
			implode(' ', array_map(
				function ($v, $k) { return sprintf("%s='%s'", $k, $v); },
				$param,
				array_keys($param)
			));
		}
		$config["cur_tag_open"] = "<li class='active'><a class='page-link' ".$extra_attr." href='".base_url($url)."'>";
		$config["cur_tag_close"] = "</a></li>";
		$config["num_tag_open"] = "<li class='page-item'>";
		$config["num_tag_close"] = "</li>";
		$attributes = array('class' => 'page-link');
		if(!empty($param)) {
			$attributes = array_merge($attributes, $param);
		}
		$config['attributes'] = $attributes;
		$config['anchor_class'] = 'class="page-link"';
		$config["num_links"] = $no_of_pagination_links;
		$CI->pagination->initialize($config);
		$current_page = $CI->uri->segment(3);
		
		if($current_page == null) {
			$current_page = 1;
		}
		$c = ceil(($total / $config['per_page']));
		$page = $current_page <= $c ? $current_page : $c;
		if($page == 1 || $total == 0) {
			$offset = 0;
		} else {
			$offset = (($page - 1) * $config['per_page']);
		}
		return ['links' => $CI->pagination->create_links(), 'offset' =>  $offset, 'current_page_no' => $page];
	}
}
