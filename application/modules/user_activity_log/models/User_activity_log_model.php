<?php
if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}
class User_activity_log_model extends BaseModel
{

    public function __construct ()
    {
        return parent::__construct ();
    }
    /**
     * This method is used to store user activity message to activity log table, called from user_source_connection_tracking_helper
    */
    public function add_data_to_users_activity_log($data) {
        if($this->db->insert('users_activity_log_tracking', $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    /**
     * This method is used to get user display activity log data
    */
    public function get_displayed_user_activity_data($start, $limit, $user_id, $filter_arr = []) {
        $this->db->select('DISTINCT SQL_CALC_FOUND_ROWS *, user_activity_log_strip_html_tags(activity_description) as description', false);
        $this->db->from('users_activity_log_tracking');
        $this->db->where('user_id', $user_id);
        if(!empty($filter_arr) && !empty($filter_arr["search_str"])) {
            $val = trim(htmlspecialchars($filter_arr["search_str"], ENT_QUOTES));
            $val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
            if(!empty($val)) {
                $val = ''.$val.'*';
            }
            $this->db->where("match(activity_description) AGAINST ('".$val."' IN BOOLEAN MODE)");
        }
        $this->db->order_by('activity_log_record_time', 'desc');
        if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
        $activities =  $this->db->get()->result_array();
        $query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $total_rec = $query->row()->Count;
        
        return ['data' => $activities, 'total' => $total_rec];
    }
	
	/* This function is used to count the unread of activity log message of user */
	public function get_user_unread_activity_log_messages_count($user_id)
	{
		$count_unread_message = $this->db->where(['user_id' => $user_id,'is_message_read'=>'N'])->from('users_activity_log_tracking')->count_all_results();
		return $count_unread_message;
	}
	
	
}
?>