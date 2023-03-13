<?php
// this function is used to store activities sentences into user's log file. sentences are taken from each activity/module controler (information contained in custom config file related to each module)
if(! function_exists('user_display_log')) {
	function user_display_log ($msg = '', $user_id = '') {
		$CI = & get_instance ();
		$user = new stdClass();
		if (($CI->session->userdata ('user') != null && $CI->session->userdata('is_authorized')==1) || !empty($user_id)) {
			$user = $CI->session->userdata ('user');
			$user_id = !empty($user_id) ? $user_id : $user[0]->user_id;
			$CI->load->model ('user_activity_log/User_activity_log_model');
			$data = [
				'user_id' => $user_id,
				'activity_description' => $msg
			];
			$CI->User_activity_log_model->add_data_to_users_activity_log($data);
		}
	}     
}
/**
 * this method id used to store user display activity log for project refresh
 */
if(! function_exists('project_refresh_sequence_save_user_log')) {
	function project_refresh_sequence_save_user_log($CI, $user_id, $msg) {
		$data = [
			'user_id' => $user_id,
			'activity_description' => $msg
		];
		$CI->db->insert('users_activity_log_tracking', $data);
	}
}

if(! function_exists ('get_client_ip')) {
	function get_client_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
			}
        return $ip;
    }
}
?>