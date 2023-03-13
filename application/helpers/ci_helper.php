<?php
if ( ! function_exists ('get_user'))
{
    function get_user ($id = '')
    {
        $CI = & get_instance ();
        $user = new stdClass();
        $user->id = 0;
        if ($CI->session->userdata ('user'))
            $user = $CI->session->userdata ('user');
        return $user;
    }

}

if ( ! function_exists ('pre'))
{
	function pre ($data, $exit = false)
	{
		echo "<pre>";
		print_r ($data);
		if ( ! $exit)
			die ();
	}
}
if ( ! function_exists ('humanTiming'))
{
    function humanTiming ($time)
    {
		
        $time = time () - $time; // to get the time since that moment
        $time = ($time < 1) ? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text)
        {
            if ($time < $unit)
                continue;
            $numberOfUnits = floor ($time / $unit);
            return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
        }
    }

}


if ( ! function_exists ('calculate_user_work_experience'))
{
	function calculate_user_work_experience($from_year,$from_month,$to_year,$to_month,$current_work)
	{
		$CI = & get_instance();
		/* $month = $CI->config->item('month_singular_txt');
		$months = $CI->config->item('month_plural_txt'); */
		/* $year = $CI->config->item('year_singular_txt');
		$years = $CI->config->item('year_plural_txt'); */
		
		$year = $CI->config->item('1_year');
		$years = $CI->config->item('year_plural_txt');
		
		
		
		if($current_work == 1){
			if($from_year == $to_year &&  $from_month == $to_month){
				return "";
			}else{
				$date1 = new DateTime($from_year."-".$from_month."-01");
				$date2 = new DateTime($to_year."-".$to_month."-01");
				$interval = $date1->diff($date2);
				$interval_orignal_month = $interval->m;
				if( $interval->y  >= 1){
					$interval_calc_month = $interval->m;
				}else{
					$interval_calc_month = $interval->m+1;
				}
				if( $interval->y  <= 1 && $interval_calc_month  >= 13){
					$interval->y += 1;
				}
				$month_txt = '';
				if($interval_calc_month == '1'){
					$month_txt = $CI->config->item('1_month');
				
				}
				if($interval_calc_month >= '2' && $interval_calc_month <= '4'){
					$month_txt = $CI->config->item('2_4_months');
				
				}
				if($interval_calc_month >= '5' && $interval_calc_month <= '11'){
					$month_txt = $CI->config->item('more_than_or_equal_5_months');
				}
				
				if($interval->y >= '2' && $interval->y <= '4'){
					$years = $CI->config->item('2_4_years');
				
				}
				if($interval->y >= '5'){
					$years = $CI->config->item('more_than_or_equal_5_years');
				}
				
				
				$year = $interval->y > 1 ? $interval->y . ' '.$years : $interval->y.' '.$year;
				$month = $interval_calc_month > 1 ? $interval_calc_month  . ' '.$month_txt : $interval_calc_month .' '.$month_txt;
				$result = '';
				if($year >0) { 
					$result =  $year; 
					if($month >0){
						$result.= ' '; 
					}
				}
				if($month >0){
					$result.= $month; 
				}
				return $result;
			}
		}else{
			if($from_year == $to_year &&  $from_month == $to_month){
				return "1 ".$CI->config->item('1_month');
			}else{
			
			
				$date1 = new DateTime($from_year."-".$from_month."-01");
				$date2 = new DateTime($to_year."-".$to_month."-28");
				
				$interval = $date1->diff($date2);
				//echo "ffff ".$interval->d;
				$interval_orignal_month = $interval->m;
				if($interval->d >=25){
					$interval->m +=1;
					if( $interval->m == 12){
						$interval->y += 1;
						$interval->m = 0;
					}
				}
				if( $interval->y  >= 1){
					$interval_calc_month = $interval->m;
				}else{
					$interval_calc_month = $interval->m;
				}
				if( $interval->y  <= 1 && $interval_calc_month  >= 13){
					  $interval->y += 1;
					  
				}
				$month_txt = '';
				if($interval_calc_month == '1'){
					$month_txt = $CI->config->item('1_month');
				
				}
				if($interval_calc_month >= '2' && $interval_calc_month <= '4'){
					$month_txt = $CI->config->item('2_4_months');
				
				}
				if($interval_calc_month >= '5' && $interval_calc_month <= '11'){
					$month_txt = $CI->config->item('more_than_or_equal_5_months');
				}
				
				
				if($interval->y >= '2' && $interval->y <= '4'){
					$years = $CI->config->item('2_4_years');
				
				}
				if($interval->y >= '5'){
					$years = $CI->config->item('more_than_or_equal_5_years');
				}
				
				
				$year = $interval->y > 1 ? $interval->y . ' '.$years : $interval->y.' '.$year;
				$month = $interval_calc_month > 1 ? $interval_calc_month  . ' '.$month_txt : $interval_calc_month .' '.$month_txt;
				$result = '';
				if($year >0) { 
					$result =  $year; 
					if($month >0){
						$result.= ' '; 
					}
				}
				if($month >0){
					$result.= $month; 
				}
				return $result;	
			}
		}
	}
}
/* if ( ! function_exists ('calculate_user_work_experience'))
{
	function calculate_user_work_experience($from_year,$from_month,$to_year,$to_month,$current_work)
	{
		$CI = & get_instance();
		$month = $CI->config->item('month_singular_txt');
		$months = $CI->config->item('month_plural_txt');
		$year = $CI->config->item('year_singular_txt');
		$years = $CI->config->item('year_plural_txt');
		
		if($current_work == 1){
			if($from_year == $to_year &&  $from_month == $to_month){
				return "";
			}else{
				$date1 = new DateTime($from_year."-".$from_month."-01");
				$date2 = new DateTime($to_year."-".$to_month."-01");
				$interval = $date1->diff($date2);
				$interval_orignal_month = $interval->m;
				if( $interval->y  >= 1){
					$interval_calc_month = $interval->m;
				}else{
					$interval_calc_month = $interval->m+1;
				}
				if( $interval->y  <= 1 && $interval_calc_month  >= 13){
					  $interval->y += 1;
				}
				$year = $interval->y > 1 ? $interval->y . ' '.$years : $interval->y.' '.$year;
				$month = $interval_calc_month > 1 ? $interval_calc_month  . ' '.$months : $interval_calc_month .' '.$month;
				$result = '';
				if($year >0) { 
					$result =  $year; 
					if($month >0){
						$result.= ' '; 
					}
				}
				if($month >0){
					$result.= $month; 
				}
				return $result;
			}
		}else{
			if($from_year == $to_year &&  $from_month == $to_month){
				return "1 ".$month;
			}else{
				$years_diff = $to_year - $from_year;
				$years_plural = $years_diff > 1 ? $years : $year;
				$months_diff = $to_month - $from_month;
				$months_plural = $months_diff > 1 ? $months : $month;
				if($years_diff == 0 && $months_diff >= 1 ){
					return ($months_diff+1)." ".$months;
				}elseif($months_diff == 0 && $years_diff != 0)
					return $years_diff." ".$years_plural;
				else{
					return $years_diff." ".$years_plural." ".$months_diff." ".$months_plural;
				}
				
			}
		}
	}
} */



if ( ! function_exists ('secondsToWords'))
{
	/**
	*
	* @convert seconds to words
	*
	* @param INT $seconds
	*
	* @return string
	*
	*/
	/* function secondsToWords($seconds)
	{
			$days=(int)($seconds/86400);
	$plural = $days > 1 ? 'Days' : 'Day';
			$hours = (int)(($seconds-($days*86400))/3600);
	$mins = (int)(($seconds-$days*86400-$hours*3600)/60);
	$secs = (int)($seconds - ($days*86400)-($hours*3600)-($mins*60));
			return sprintf("%d $plural %d Hours %d Minutes %d Seconds", $days, $hours, $mins, $secs);
	} */
	
	function secondsToWords($seconds)
	{
		$CI = & get_instance();
		/** number of days **/
		$days=(int)($seconds/86400) == 0 ? '' : (int)($seconds/86400);
		/** if more than one day **/
		//$days_plural = $days > 1 ? 'Days' : ($days < 1 ? '' : 'Day');
		if($days == 1){
			$days_plural = $CI->config->item('1_day');
		}else if($days >= 2 && $days <= 4){
			$days_plural = $CI->config->item('2_4_days');
		}else if($days >4){
			$days_plural = $CI->config->item('more_than_or_equal_5_days');
		}
		
		/** number of hours **/
		$hours = (int)(($seconds-($days*86400))/3600) == 0 ? '' : (int)(($seconds-($days*86400))/3600);
		/** if 0 hour or more then 1 hour **/
		//$hours_plural = $hours < 1 ? '' : ( $hours > 1 ? 'Hours' : 'Hour' );
		
		if($hours == 1){
			$hours_plural = $CI->config->item('1_hour');
		}else if($hours >= 2 && $hours <= 4){
			$hours_plural = $CI->config->item('2_4_hours');
		}else if($hours >4){
			$hours_plural = $CI->config->item('more_than_or_equal_5_hours');
		}
		
		
		/** number of mins **/
		$mins = (int)(($seconds-$days*86400-$hours*3600)/60) == 0 ? '' : (int)(($seconds-$days*86400-$hours*3600)/60);
		/** if 0 minute or more then 1 minutes **/
		//$min_plural = $mins < 1 ? '' : ( $mins > 1 ? ' Minutes' : ' Minute' );
		if($mins == 1){
			$min_plural = $CI->config->item('1_minute');
		}else if($mins >= 2 && $mins <= 4){
			$min_plural = $CI->config->item('2_4_minutes');
		}else if($mins >4){
			$min_plural = $CI->config->item('more_than_or_equal_5_minutes');
		}
		/** number of seconds **/
		$secs = (int)($seconds - ($days*86400)-($hours*3600)-($mins*60)) == 0 ? '' : (int)($seconds - ($days*86400)-($hours*3600)-($mins*60));
		/** if 0 second or more then 1 seconds **/
		//$secs_plural = $secs < 1 ? '' : ( $secs > 1 ? ' Seconds' : ' Second' );
		if($secs == 1){
			$secs_plural = $CI->config->item('1_second');
		}else if($secs >= 2 && $secs <= 4){
			$secs_plural = $CI->config->item('2_4_seconds');
		}else if($secs >4){
			$secs_plural = $CI->config->item('more_than_or_equal_5_seconds');
		}
		
		
		/** return the string **/
		return sprintf("$days $days_plural $hours $hours_plural $mins $min_plural $secs $secs_plural");
    }
	
	
	
}	

if ( ! function_exists ('secondsToWordsResponsive'))
{
	/**
	*
	* @convert seconds to words
	*
	* @param INT $seconds
	*
	* @return string
	*
	*/
	/* function secondsToWordsResponsive($seconds)
	{
			$days=(int)($seconds/86400);
	$plural = $days > 1 ? 'Days' : 'Day';
			$hours = (int)(($seconds-($days*86400))/3600);
	$mins = (int)(($seconds-$days*86400-$hours*3600)/60);
	$secs = (int)($seconds - ($days*86400)-($hours*3600)-($mins*60));
			return sprintf("%d $plural %d Hours %d Minutes %d Seconds", $days, $hours, $mins, $secs);
	} */
//This function is using for responsive view for show the seconds to words string
	function secondsToWordsResponsive($seconds)
	{
		$CI = & get_instance();
		/** number of days **/
		$days=(int)($seconds/86400) == 0 ? '' : (int)($seconds/86400);
		/** if more than one day **/
		//$days_plural = $days > 1 ? 'Days' : ($days < 1 ? '' : 'Day');
		if($days == 1){
			$days_plural = $CI->config->item('1_day');
		}else if($days >= 2 && $days <= 4){
			$days_plural = $CI->config->item('2_4_days');
		}else if($days >4){
			$days_plural = $CI->config->item('more_than_or_equal_5_days');
		}
		
		/** number of hours **/
		$hours = (int)(($seconds-($days*86400))/3600) == 0 ? '' : (int)(($seconds-($days*86400))/3600);
		/** if 0 hour or more then 1 hour **/
		//$hours_plural = $hours < 1 ? '' : ( $hours > 1 ? 'Hours' : 'Hour' );
		
		if($hours == 1){
			$hours_plural = $CI->config->item('1_hour');
		}else if($hours >= 2 && $hours <= 4){
			$hours_plural = $CI->config->item('2_4_hours');
		}else if($hours >4){
			$hours_plural = $CI->config->item('more_than_or_equal_5_hours');
		}
		
		
		
		/** number of mins **/
		$mins = (int)(($seconds-$days*86400-$hours*3600)/60) == 0 ? '' : (int)(($seconds-$days*86400-$hours*3600)/60);
		/** if 0 minute or more then 1 minutes **/
		//$min_plural = $mins < 1 ? '' : ( $mins > 1 ? ' Minutes' : ' Minute' );
		if($mins == 1){
			$min_plural = $CI->config->item('1_minute');
		}else if($mins >= 2 && $mins <= 4){
			$min_plural = $CI->config->item('2_4_minutes');
		}else if($mins >4){
			$min_plural = $CI->config->item('more_than_or_equal_5_minutes');
		}
		
		
		
		/** number of seconds **/
		$secs = (int)($seconds - ($days*86400)-($hours*3600)-($mins*60)) == 0 ? '' : (int)($seconds - ($days*86400)-($hours*3600)-($mins*60));
		/** if 0 second or more then 1 seconds **/
		//$secs_plural = $secs < 1 ? '' : ( $secs > 1 ? ' Seconds' : ' Second' );
		if($secs == 1){
			$secs_plural = $CI->config->item('1_second');
		}else if($secs >= 2 && $secs <= 4){
			$secs_plural = $CI->config->item('2_4_seconds');
		}else if($secs >4){
			$secs_plural = $CI->config->item('more_than_or_equal_5_seconds');
		}
		
		$word_string = "";
		if(!empty($days)){
		 $word_string .= "<span class='word_set'>".$days." ".$days_plural."</span>";
		}
		if(!empty($hours)){
		 $word_string .= "<span class='word_set'>".$hours." ".$hours_plural."</span>";
		}
		if(!empty($mins)){
		 $word_string .= "<span class='word_set'>".$mins." ".$min_plural."</span>";
		}
		if(!empty($secs)){
		 $word_string .= "<span class='word_set'>".$secs." ".$secs_plural."</span>";
		}
		
		/** return the string **/
		return sprintf($word_string);
    }
}	



if ( ! function_exists ('validate_session'))
{
    function validate_session ()
    {
		
        $CI = & get_instance ();
        $var = $CI->auto_model->validate_session ();
        return $var;
    }
}

if ( ! function_exists ('cryptor_encrypt'))
{
    function cryptor_encrypt ($value)
    {
		
        $CI = & get_instance ();
		$CI->load->library('Cryptor');
		 return Cryptor::doEncrypt($value);
    }
}

if ( ! function_exists ('cryptor_decrypt'))
{
    function cryptor_decrypt ($value)
    {
		
        $CI = & get_instance ();
		$CI->load->library('Cryptor');
		 return Cryptor::doDecrypt($value);
    }
}


if ( ! function_exists ('convert_seconds_to_time'))
{
    function convert_seconds_to_time($value)
	{
		$CI = & get_instance ();
		$date1 = new DateTime();
		$date2 = $date1->diff(new DateTime($value ));
		
		$hours =  ($date2->d*24)+$date2->h;
		$minutes = $date2->i;
		$seconds = $date2->s;
		
		 $hourly_txt = '';
		 $minutes_txt = '';
		 $seconds_txt = '';
		if($hours ==1){
			$hourly_txt = $CI->config->item('1_hour');
		}else if($hours >=2 && $hours <= 4){
			$hourly_txt = $CI->config->item('2_4_hours'); 
		}
		else if($hours > 4){
			$hourly_txt = $CI->config->item('more_than_or_equal_5_hours');
		}
		
		// For minutes
		if($minutes ==1){
			$minutes_txt = $CI->config->item('1_minute');
		}else if($minutes >=2 && minutes <= 4){
			$minutes_txt = $CI->config->item('2_4_minutes');
		}
		else if($minutes > 4){
			$minutes_txt = $CI->config->item('more_than_or_equal_5_minutes');
		}
		
		// For seconds
		if($seconds ==1){
			$seconds_txt = $CI->config->item('1_second');
		}else if($seconds >=2 && seconds <= 4){
			$seconds_txt = $CI->config->item('2_4_seconds');
		}
		else if($seconds > 4){
			$seconds_txt = $CI->config->item('more_than_or_equal_5_seconds');
		}
		$date = '';
		if($hours > 0 ){
			if($hours < 10){
				$hours = '0'.$hours;
			}
			if($minutes < 10){
				$minutes = '0'.$minutes;
			}
			if($seconds < 10){
				$seconds = '0'.$seconds;
			}
			$date = $hours.":".$minutes.":".$seconds.' '.$hourly_txt;
		}
		else if($hours == 0 && $minutes > 0)
		{
			if($hours < 10){
				$hours = '0'.$hours;
			}
			if($minutes < 10){
				$minutes = '0'.$minutes;
			}
			if($seconds < 10){
				$seconds = '0'.$seconds;
			}
			$date = $hours.":".$minutes.":".$seconds.' '.$minutes_txt;
		}
		else if($minutes == 0 && $seconds > 0)
		{
			if($hours < 10){
				$hours = '0'.$hours;
			}
			if($minutes < 10){
				$minutes = '0'.$minutes;
			}
			if($seconds < 10){
				$seconds = '0'.$seconds;
			}
			$date = $hours.":".$minutes.":".$seconds.' '.$seconds_txt;
		}
		return $date;
	
		
		/* $diff  = abs(time() - strtotime($value));
		$time = gmdate("H:i:s", $diff);
		if(gmdate("H",$diff) != '00' && gmdate("H",$diff) != '01')
		{
			return  "$time hodiny";
		}
		elseif(gmdate("H",$diff) == '01'){
			return  "$time hodina";
		}
		elseif(gmdate("H", $diff) == '00' && gmdate("i", $diff) != '00' && gmdate("i",$diff) != '01' )
		{
		 return "$time minuty";
		}
		elseif(gmdate("i",$diff) == '01' )
		{
		 return "$time minuta";
		}
		elseif(gmdate("i", $diff) == '00' && gmdate("s", $diff) != '00'  && gmdate("i",$diff) != '01' )
		{
		 return "$time vteÅ™iny";
		}
		elseif( gmdate("i",$diff) == '01' )
		{
		 return "$time vteÅ™ina";
		}
		 */
			
	}
}
// This function is used to get date difference between two dates in hours / minutes / seconds
if(!function_exists('dateDifference')) {
	function dateDifference($date1, $date2) {		
		$date1 = strtotime($date1);
		$date2 = strtotime($date2); 
		$diff = abs($date1 - $date2);
		
		// $day = $diff/(60*60*24); // in day
		// $dayFix = floor($day);
		// $dayPen = $day - $dayFix;
		// if($dayPen > 0)
		// {
		// 	$hour = $dayPen*(24); // in hour (1 day = 24 hour)
		// 	$hourFix = floor($hour);
		// 	$hourPen = $hour - $hourFix;
		// 	if($hourPen > 0)
		// 	{
		// 		$min = $hourPen*(60); // in hour (1 hour = 60 min)
		// 		$minFix = floor($min);
		// 		$minPen = $min - $minFix;
		// 		if($minPen > 0)
		// 		{
		// 			$sec = $minPen*(60); // in sec (1 min = 60 sec)
		// 			$secFix = floor($sec);
		// 		}
		// 	}
		// }
		$days    = floor($diff / 86400);
		$hr = floor(($diff) / 3600);
		$hours   = floor(($diff - ($days * 86400)) / 3600);
		$minutes = floor(($diff - ($days * 86400) - ($hours * 3600))/60);
		$seconds = floor(($diff - ($days * 86400) - ($hours * 3600) - ($minutes*60)));
		
		$str = "";
		if($hr < 10) {
			$str.= '0'.$hr.":";
		} else {
			$str.= $hr.":";
		}
		if($minutes < 10) {
			$str.= '0'.$minutes.":";
		} else {
			$str.= $minutes.":";
		}
		if($seconds < 10) {
			$str.= '0'.$seconds;
		} else {
			$str.= $seconds;
		}
		return $str;
	}
}

/**
 * @sid
 * sort single dimentaional date array in ascending order
*/
if(! function_exists('date_sort')) {
	function date_sort($a, $b) {
		return strtotime($a) - strtotime($b);
	}
}

/*
 * @sid
 * Check that whether user session time has been expired or not based on user_log table last_activity_time
 * used in third_party/MX/Controller.php file to check session for every module
*/
if(! function_exists ('check_session_validity')) {
	function check_session_validity() {
		// Call excluded url from session
		$CI = & get_instance ();
		// Excluded url from session expiration
		$excluded_page = [
			$CI->config->item('invitefriends_page_url')			
		];
		$current_page = $CI->uri->segment(1);
		if($CI->session->userdata('user') != null && in_array($current_page, $excluded_page) && !$CI->input->is_ajax_request()) {
			update_session_expiration_time();
			return true;
		}
		// if($CI->session->userdata('user') != null && in_array($current_page, $excluded_page) && !$CI->input->is_ajax_request() ) {
			// return true;
		// }
		if($CI->session->userdata('user_log_id') != null && $CI->session->userdata('is_authorized')) {
			$cond = [
				'id' => $CI->session->userdata('user_log_id')
			];
			$CI->db->select('*');
			$CI->db->from('user_log');
			$CI->db->where($cond);
			$result = $CI->db->get()->row_array();
			if(!empty($result)) {
				$d1 = new DateTime();
				$d2 = new DateTime($result['session_expiration_time']);
				if($d1 > $d2) {
					$CI->db->where($cond);
					$CI->db->delete('user_log');
					return false;
				} else {
					//if(!$CI->input->is_ajax_request()) {
						update_session_expiration_time();
					//}
					return true;
				} 
			} else {
				return false;
			}
		} 
		else if($CI->session->userdata('is_logged') && !$CI->session->userdata('is_authorized')) {
			return true;
		}
		else {
			return false;
		}
	}
}

/*
 * @sid
 * update session expiration time and last activity time if user sesion is valid
*/
if(! function_exists ('update_session_expiration_time')) {
	function update_session_expiration_time() { 
		$CI = & get_instance ();
		$time_arr = explode(':', USER_SESSION_AVAILABILITY_TIME);
		$updatedata = [
			'last_activity_time' => date('Y-m-d H:i:s'),
			'session_expiration_time' => !empty($time_arr) ? date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds', strtotime(date('Y-m-d H:i:s')))): date('Y-m-d H:i:s')
		];
		$CI->db->where('id', $CI->session->userdata('user_log_id'));
		$CI->db->update('user_log', $updatedata);
	}
}
/*
* @sid
* Generate referral code for invite friend
*/
if(! function_exists('generate_referral_code')) {
	function generate_referral_code() {
		$referral_code = '';
		for($i=0; $i<3; $i++) {
			$referral_code .= generate_individual_block_code(mt_rand(0,1));
		}
		$referral_code = substr($referral_code,0, -1);
		$CI = & get_instance ();
		$CI->db->where('referral_code', $referral_code);
		$result = $CI->db->get('users')->row_array();
		if(empty($result)) {
			return $referral_code;
		} else {
			generate_referral_code();
		}
	}
}
/*
* @sid
* Generate individual block code for invite friend
* @param Integer 0 - Alphabet, 1 - Number 
*/
if(! function_exists('generate_individual_block_code')) {
	function generate_individual_block_code($param) {
		$rand_str = '';
		if($param == 0) {
			for($i = 0; $i<3; $i++) {
				$rand_str .= chr(mt_rand(97,122));
			}
		} else {
			for($i = 0; $i<3; $i++) {
				$rand_str .= chr(mt_rand(48,57));
			}
		}
		return $rand_str.'-';
	}
}
/*
* @sid
* Generate unique project id for user project [6-10 digit]
*/
if(! function_exists('generate_unique_project_id')) {
	function generate_unique_project_id() {
		$random_number = mt_rand(100000, 9999999999);
		$CI = & get_instance ();
		$temp_projects = $CI->db->get_where('temp_projects', ['temp_project_id' => $random_number])->row_array();
		$tables_name_array = array(
			'projects_draft',
			'projects_awaiting_moderation',
			'projects_open_bidding',
			'fixed_budget_projects_awarded',
			'fixed_budget_projects_progress',
			'fixed_budget_projects_completed',
			'fixed_budget_projects_cancelled',
			'fixed_budget_projects_cancelled_by_admin',
			'fixed_budget_projects_incomplete',
			'hourly_rate_based_projects_incomplete',
			'fixed_budget_projects_expired',
			'hourly_rate_based_projects_awarded',
			'hourly_rate_based_projects_progress',
			'hourly_rate_based_projects_completed',
			'hourly_rate_based_projects_cancelled',
			'hourly_rate_based_projects_cancelled_by_admin',
			'hourly_rate_based_projects_expired',
			'fulltime_projects_cancelled',
			'fulltime_projects_cancelled_by_admin',
			'fulltime_projects_expired'
		);

		if(empty($temp_projects)) {
			foreach($tables_name_array as $table) {
				if(substr($table, 0, strlen('fulltime')) === 'fulltime') {
					$result = $CI->db->get_where($table, ['fulltime_project_id' => $random_number])->row_array();
				} else {
					$result = $CI->db->get_where($table, ['project_id' => $random_number])->row_array();
				}
				if(!empty($result)) {
					generate_unique_project_id();
					return;
				}
			}
			return $random_number;
		} else {
			generate_unique_project_id();
		}
	}
}



/*
* @sid 
* Get random time for auto approval project based on min, max value 
* Used in post_project controller
*/
if(! function_exists('generate_random_project_autoapproval_time_between_min_max_values')) {
	function generate_random_project_autoapproval_time_between_min_max_values($min, $max) {
		$min_arr = array_map('getInt', explode(":", $min));
		$max_arr = array_map('getInt', explode(":", $max));
		if(empty(array_filter($min_arr)) && empty(array_filter($max_arr))) {
			return 0;
		} else {
			$timestamp1 = mktime($min_arr[0],$min_arr[1],$min_arr[2]);
			$timestamp2 = mktime($max_arr[0],$max_arr[1],$max_arr[2]);
			$rand_time = mt_rand($timestamp1, $timestamp2);
			if((int)date('H', $rand_time) == 0 && (int)date('i', $rand_time) == 0 && (int)date('s', $rand_time) == 0) {
				return 0;
			}
			$final_date = date('Y-m-d H:i:s', strtotime('+'.(int)date('H', $rand_time).' hour +'.(int)date('i', $rand_time).' minutes +'.(int)date('s', $rand_time).' seconds'));
			return $final_date;
		}
	}
	
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
		$check_valid_arr = array_map('getInt', $custom_config_time); 
		$valid_time_arr = array_filter($check_valid_arr);
		if(!empty($valid_time_arr)) {
			$refresh_time = date('Y-m-d H:i:s', strtotime('+'.(int)$custom_config_time[0].' hour +'.(int)$custom_config_time[1].' minutes +'.(int)$custom_config_time[2].' seconds', strtotime($next_refresh_time)));
			if(strtotime($refresh_time) < strtotime(date('Y-m-d H:i:s'))) {
				$refresh_time = get_next_refresh_time($refresh_time, $custom_config_time);
			} 
		} else {
			$refresh_time = strtotime();
		}
		
		return $refresh_time;
	}
}

/*
*  Limits the string based on the character count.  Preserves complete words	
*/	
if(! function_exists('limitString')) {
	function limitString($string, $limit = 32) {
		// Return early if the string is already shorter than the limit
		$string = preg_replace('!\s+!', ' ', $string);
		if(strpos($string, ' ') !== false){
			if(strlen($string) < $limit) {
				return $string;
			}
			$regex = "/(.{1,$limit})\b/";
			preg_match($regex, $string, $matches);
			return trim($matches[1])."...";
		}else{
			if(strlen($string) < $limit) {return $string;}
			return trim(substr($string,0,$limit))."...";
		}
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

/*
*  Limits the string based on the character count.
*/	
if(! function_exists('limitCharacter')) {
	function limitCharacter($string, $limit = 32) {
		// Return early if the string is already shorter than the limit
		if(strlen($string) < $limit) {return $string;}
		return substr($string,0,$limit)."...";
	}

}

/*
*  This functions is used to sort array by specific key value
*/	
if(! function_exists('sortArrayBySpecificKeyValue')) {
	function sortArrayBySpecificKeyValue($field, &$array, $direction = 'asc')
	{
		usort($array, create_function('$a, $b', '
			$a = $a["' . $field . '"];
			$b = $b["' . $field . '"];
			if ($a == $b)
			{
				return 0;
			}
			return ($a ' . ($direction == 'desc' ? '>' : '<') .' $b) ? -1 : 1;
		'));
		return true;
	}
}

/*
* 
* Check the unique project title
*/
if(! function_exists('check_unique_project_title')) {
	//function check_unique_project_title($project_id,$project_owner_id,$project_title) {
	function check_unique_project_title($project_id,$project_title) {
		$CI = & get_instance ();
		$tables_name_array = array('projects_draft','projects_awaiting_moderation','projects_open_bidding','fixed_budget_projects_progress','fixed_budget_projects_expired','fixed_budget_projects_incomplete','fixed_budget_projects_completed','fixed_budget_projects_cancelled','fixed_budget_projects_cancelled_by_admin','hourly_rate_based_projects_cancelled','hourly_rate_based_projects_cancelled_by_admin','hourly_rate_based_projects_expired','hourly_rate_based_projects_progress','hourly_rate_based_projects_incomplete','hourly_rate_based_projects_completed','fulltime_projects_cancelled','fulltime_projects_cancelled_by_admin','fulltime_projects_expired');
		$record_exists = false;
		/* echo $project_id;
		echo "<br>";
		echo $project_owner_id;
		echo "<br>";
		echo $project_title;
		echo "<br>";*/
		
		$temp_projects_count = $CI->db->where(['temp_project_id !=' =>$project_id,'temp_project_owner_id' =>$project_owner_id,'project_title' =>$project_title])->from('temp_projects')->count_all_results();
		
		if($temp_projects_count == 0){
		
			foreach($tables_name_array as $value){
				if(substr($value, 0, strlen('fulltime')) === 'fulltime') { 
					//$projects_count = $CI->db->where(['fulltime_project_id !=' =>$project_id,'employer_id' =>$project_owner_id,'fulltime_project_title' =>$project_title])->from($value)->count_all_results();
					$projects_count = $CI->db->where(['fulltime_project_id !=' =>$project_id,'fulltime_project_title' =>$project_title])->from($value)->count_all_results();
				} else {
					$projects_count = $CI->db->where(['project_id !=' =>$project_id,'project_owner_id' =>$project_owner_id,'project_title' =>$project_title])->from($value)->count_all_results();
					$projects_count = $CI->db->where(['project_id !=' =>$project_id,'project_title' =>$project_title])->from($value)->count_all_results();
				}
				$record_exists = false;
				if($projects_count != 0){
					$record_exists = true;
					break;
				}
			}
		}else{
			$record_exists = true;
		}
		return $record_exists;
	}
}	
	/* This functionation is used to show 404 page */
	if ( ! function_exists ('show_custom_404_page'))
	{
		function show_custom_404_page ($id = '')
		{
			$CI = & get_instance ();
			//$data['current_page'] = '404_default';
			$lay = array();
			set_status_header(404);
			########## set the default 404 title meta tag and meta description  start here #########
			$default_404_page_title_meta_tag = $CI->config->item('404_page_title_meta_tag');
			$default_404_page_description_meta_tag = $CI->config->item('404_page_description_meta_tag');
			$data['meta_tag'] = '<title>' . $default_404_page_title_meta_tag . '</title><meta name="description" content="' . $default_404_page_description_meta_tag . '"/>';
			########## set the default 404 title meta tag and meta description  end here #########
			$CI->layout->view ('404defaultpage/404_default', $lay, $data, 'error_404'); 
			
			
			
		}

	}
	
	/* This function is used to check that user already apply the bid on project or not*/
	if ( ! function_exists ('check_sp_active_bid_exists_project')) 
	{
		function check_sp_active_bid_exists_project ($project_id,$bidder_id,$project_type)
		{
			
			$CI = & get_instance ();
			$CI->load->model ('bidding/Bidding_model');
			return $CI->Bidding_model->check_sp_active_bid_exists_project($project_id,$bidder_id,$project_type);
		}
	}
	
	if ( ! function_exists ('limitStringShowMoreLess')) 
	{
		function limitStringShowMoreLess ($string,$limit = 100)
		{
			
			$string = preg_replace('!\s+!', ' ', $string);
			if(strpos($string, ' ') !== false){
				if(strlen($string) < $limit) { 
					$data['first_text'] = $string ;
					return $data;
				}

				$regex = "/(.{1,$limit})\b/";
				preg_match($regex, $string, $matches);
				$data['first_text'] = $matches[1]."...";
				//$data['first_text'] = $matches[1];
				$data['second_text'] = substr($string,strlen($matches[1]),strlen($string));
				
				return $data;
				//echo  $matches[1]."...".substr($string,strlen($matches[1]),strlen($string));
			}else{
				if(strlen($string) < $limit) {$data['first_text'] = $string; return $data;}
				$data['first_text'] = substr($string,0,$limit)."...";
				//$data['first_text'] = substr($string,0,$limit);
				$data['second_text'] = substr($string,$limit,strlen($string));
				return $data;
			}
		}
	}
	
	// This function is used to sum the number of active,awarded,in progress bids/applications
	if ( ! function_exists ('get_project_bid_count')) 
	{
		function get_project_bid_count ($project_id,$project_type)
		{
			
			$CI = & get_instance ();
			$CI->load->model ('bidding/Bidding_model');
			//return $CI->Bidding_model->check_sp_active_bid_exists_project($project_id,$bidder_id,$project_type);
			return $CI->Bidding_model->get_project_bid_count($project_id,$project_type); // get bid count of project
		}
	}
	
	// This function is used to count the number of bidder working on the project or hires for the project
	if ( ! function_exists ('get_project_hires_count')) 
	{
		function get_project_hires_count ($project_id,$project_type)
		{
			
			$CI = & get_instance ();
			$CI->load->model ('bidding/Bidding_model');
			//return $CI->Bidding_model->check_sp_active_bid_exists_project($project_id,$bidder_id,$project_type);
			return $CI->Bidding_model->get_project_hires_count($project_id,$project_type); // get bid count of project
		}
	}
	
	
	
	

	/* This function is used to convert H:i:s format to real value in days of project upgrade*/
	if ( ! function_exists ('convert_time_to_project_upgrade_availability_days')) 
	{
		function convert_time_to_project_upgrade_availability_days ($time)
		{
			$CI = & get_instance ();
			$day_singular = $CI->config->item('project_details_page_bidder_listing_details_day_singular');
			$day_plural = $CI->config->item('project_details_page_bidder_listing_details_day_plural');
			
			$time = explode(":",$time);
			$hours = $time[0]*60*60;
			$mintutes = $time[1]*60;
			$sec = $time[2];
			$total_seconds = $hours + $mintutes + $sec;
			
			$days =  ($total_seconds / 86400);
			return $days > 1 ? $days." ".$day_plural:$days." ".$day_singular;
		}
	}
	/**
	 * This function is used to identify given url is valid or not
	 * Bascially this method we will use to check remote location image or file existance while we displaying user profile
	 */
	if(! function_exists('is_url_exist')) {
		function is_url_exist($url){
			$ch = curl_init($url);    
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_exec($ch);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
			if($code == 200){
			   $status = true;
			}else{
			  $status = false;
			}
			curl_close($ch);
		   return $status;
		}
	}
	
	/* This function is fetch the latest project start date of inprogress project*/
	/* if ( ! function_exists ('get_latest_in_progress_project_start_date')) 
	{
		function get_latest_in_progress_project_start_date ($project_id,$project_type)
		{
			
			$CI = & get_instance ();
			$CI->load->model ('bidding/Bidding_model');
			
			return $CI->Bidding_model->get_latest_in_progress_project_start_date($project_id,$project_type); // get project start date
		}
	} */
	
	
	/* This function is fetch the latest project start date of completed project*/
	/* if ( ! function_exists ('get_latest_completed_project_start_date')) 
	{
		function get_latest_completed_project_start_date ($project_id,$project_type)
		{
			
			$CI = & get_instance ();
			$CI->load->model ('bidding/Bidding_model');
			return $CI->Bidding_model->get_latest_completed_project_start_date($project_id,$project_type); // get project start date
		}
	} */
	
	/*
	This functions is used to count the paid milestones for project owner/service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the count of paid milestone.
	
	*/
	if ( ! function_exists ('get_released_escrows_count_project')) 
	{
		function get_released_escrows_count_project ($project_type,$conditions)
		{
			
			$CI = & get_instance ();
			$CI->load->model ('escrow/Escrow_model');
			
			return $CI->Escrow_model->get_released_escrows_count_project($project_type,$conditions);
		}
	}
	
	/*
	This functions is used to count the cancelled milestones for project owner/service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the count of paid milestone.
	
	*/
	if ( ! function_exists ('get_cancelled_escrows_count_project')) 
	{
		function get_cancelled_escrows_count_project ($project_type,$conditions)
		{
			
			$CI = & get_instance ();
			$CI->load->model ('escrow/Escrow_model');
			
			return $CI->Escrow_model->get_cancelled_escrows_count_project($project_type,$conditions);
		}
	}
	
	
	/*
	This functions is used to count the active milestones for project owner/service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the count of active milestone.
	
	*/
	if ( ! function_exists ('get_active_escrows_count_project')) 
	{
		function get_active_escrows_count_project ($project_type,$conditions)
		{
			
			$CI = & get_instance ();
			$CI->load->model ('escrow/Escrow_model');
			
			return $CI->Escrow_model->get_active_escrows_count_project($project_type,$conditions);
		}
	}
	
	/*
	This functions is used to count the requested milestones for project owner/service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the requested milestone.
	*/
	if ( ! function_exists ('get_requested_escrows_count_project')) 
	{
		function get_requested_escrows_count_project ($project_type,$conditions)
		{
			
			$CI = & get_instance ();
			$CI->load->model ('escrow/Escrow_model');
			
			return $CI->Escrow_model->get_requested_escrows_count_project($project_type,$conditions);
		}
	}
	
		/*
	This functions is used to count the rejected requested milestones for project owner/service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the requested milestone.
	*/
	if ( ! function_exists ('get_rejected_requested_escrows_count_project')) 
	{
		function get_rejected_requested_escrows_count_project ($project_type,$conditions)
		{
			
			$CI = & get_instance ();
			$CI->load->model ('escrow/Escrow_model');
			
			return $CI->Escrow_model->get_rejected_requested_escrows_count_project($project_type,$conditions);
		}
	}
	
	/*
	This functions is used to fetch the requested milestone crated by SP
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the requested milestone.
	*/
	if ( ! function_exists ('get_all_requested_escrows_listing_project')) 
	{
		function get_all_requested_escrows_listing_project ($project_type,$conditions,$start = '', $limit = '')
		{
			
			$CI = & get_instance ();
			$CI->load->model ('escrow/Escrow_model');
			return $CI->Escrow_model->get_all_requested_escrows_listing_project($project_type,$conditions,$start, $limit);
		}
	}
	
	/*
	This functions is used to sum the requested milestones for service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the requested milestone.
	*/
	if ( ! function_exists ('get_sum_requested_escrows_amount_project')) 
	{
		function get_sum_requested_escrows_amount_project ($project_type,$conditions)
		{
			
			$CI = & get_instance ();
			$CI->load->model ('escrow/Escrow_model');
			
			return $CI->Escrow_model->get_sum_requested_escrows_amount_project($project_type,$conditions);
		}
	}
	
	
	/*
	This function is used to generate pagination links for project detail page paymnts section by load the function from model 
	*/
	/* if ( ! function_exists ('generate_pagination_links_escrow')) 
	{
		function generate_pagination_links_escrow ($total,$url,$record_per_page,$params)
		{
			$CI = & get_instance ();
			$CI->load->model ('escrow/Escrow_model');
			
			return $CI->Escrow_model->generate_pagination_links_escrow($total,$url,$record_per_page,$params);
		}
	} */
	
	/* This function is used to count the unread of message of user when related with newly posted project by load the model*/
	if ( ! function_exists ('get_user_unread_notification_message_count')) 
	{
		function get_user_unread_notification_message_count ($user_id,$notification_section_type)
		{
			$CI = & get_instance ();
			if($notification_section_type == 'newly_posted_project'){
				$CI->load->model ('newly_posted_projects_realtime_notifications/Newly_posted_projects_realtime_notifications_model');
				return $CI->Newly_posted_projects_realtime_notifications_model->get_user_unread_newly_posted_project_messages_count($user_id);
			}
			if($notification_section_type == 'activity_log'){
				$CI->load->model ('user_activity_log/User_activity_log_model');
				return $CI->User_activity_log_model->get_user_unread_activity_log_messages_count($user_id);
			}
		}
	}
	
	
	/*
	This function is used to fetch the project detail
	*/
	/* if ( ! function_exists ('get_status_based_project_detail')) 
	{
		function get_status_based_project_detail ($project_id,$project_type)
		{
			$CI = & get_instance ();
			$CI->load->model ('projects/Projects_model');
			
			return $CI->Projects_model->get_status_based_project_detail($project_id,$project_type);
		}
	} */
	
	
	/*
	This functions is used to fetch the project completed request created by po
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the project completed request.
	*/
	if ( ! function_exists ('get_mark_complete_project_request_listing')) 
	{
		function get_mark_complete_project_request_listing ($project_type,$conditions)
		{
			
			$CI = & get_instance ();
			$CI->load->model ('bidding/Bidding_model');
			return $CI->Bidding_model->get_mark_complete_project_request_listing($project_type,$conditions);
		}
	}
	
	if ( ! function_exists ('get_latest_project_complete_request_detail')) 
	{
		// This function is used to fetch the complete request detail
		function get_latest_project_complete_request_detail($conditions,$project_type)
		{
			$CI = & get_instance ();
			$CI->load->model ('bidding/Bidding_model');
			return $CI->Bidding_model->get_latest_project_complete_request_detail($conditions,$project_type);
			
		}
	}
	
	if ( ! function_exists ('get_user_open_projects_count')) 
	{
		/**
		* This function is used to count the total open projects of user.This function is used in "open for bidding" tab in my projects section on dashboard/my projects page.Its is using for calculation to inform user for available open slots
		*/ 
		function get_user_open_projects_count($user_id)
		{
			$CI = & get_instance ();
			$CI->load->model ('projects/Projects_model');
			return $CI->Projects_model->get_user_open_projects_count($user_id);
			
		}
	}
	
	if ( ! function_exists ('get_project_auto_approve_min_time')) 
	{
		/**
		* This function is used to get the minimum time for auto approval when po select upgrade from post project
		*/ 
		function get_project_auto_approve_min_time($project_upgrade_array,$user_membership_plan_id)
		{	
			$CI = & get_instance ();
			$auto_approve_date_array = array();
			if($user_membership_plan_id == 1){
					
				if($project_upgrade_array['featured'] == 'Y') {
					
					$auto_approve_date = generate_random_project_autoapproval_time_between_min_max_values($CI->config->item('free_membership_subscriber_featured_project_auto_approval_min'), $CI->config->item('free_membership_subscriber_featured_project_auto_approval_max'));
					$auto_approve_date_array[] = $auto_approve_date == 0 ? 0 : strtotime($auto_approve_date);
				}
				if($project_upgrade_array['urgent'] == 'Y') {
					$auto_approve_date = generate_random_project_autoapproval_time_between_min_max_values($CI->config->item('free_membership_subscriber_urgent_project_auto_approval_min'), $CI->config->item('free_membership_subscriber_urgent_project_auto_approval_max'));
					$auto_approve_date_array[] = $auto_approve_date == 0 ? 0 : strtotime($auto_approve_date);
				}
				if($project_upgrade_array['hidden'] == 'Y') {
					$auto_approve_date =  generate_random_project_autoapproval_time_between_min_max_values($CI->config->item('free_membership_subscriber_hidden_project_auto_approval_min'), $CI->config->item('free_membership_subscriber_hidden_project_auto_approval_max'));
					$auto_approve_date_array[] = $auto_approve_date == 0 ? 0 : strtotime($auto_approve_date);
					
				}
				if($project_upgrade_array['sealed'] == 'Y') {
					$auto_approve_date =  generate_random_project_autoapproval_time_between_min_max_values($CI->config->item('free_membership_subscriber_sealed_project_auto_approval_min'), $CI->config->item('free_membership_subscriber_sealed_project_auto_approval_max'));
					$auto_approve_date_array[] = $auto_approve_date == 0 ? 0 : strtotime($auto_approve_date);
				}
				$auto_approve_date =  generate_random_project_autoapproval_time_between_min_max_values($CI->config->item('free_membership_subscriber_standard_project_auto_approval_min'), $CI->config->item('free_membership_subscriber_standard_project_auto_approval_max'));
				$auto_approve_date_array[] = $auto_approve_date == 0 ? 0 : strtotime($auto_approve_date);
				
			}	
			if($user_membership_plan_id == 4){
					
				if($project_upgrade_array['featured'] == 'Y') {
					
					 $auto_approve_date = generate_random_project_autoapproval_time_between_min_max_values($CI->config->item('gold_membership_subscriber_featured_project_auto_approval_min'), $CI->config->item('gold_membership_subscriber_featured_project_auto_approval_max'));
					 
					 $auto_approve_date_array[] = $auto_approve_date == 0 ? 0 : strtotime($auto_approve_date);
					 
				}
				if($project_upgrade_array['urgent'] == 'Y') {
					$auto_approve_date = generate_random_project_autoapproval_time_between_min_max_values($CI->config->item('gold_membership_subscriber_urgent_project_auto_approval_min'), $CI->config->item('gold_membership_subscriber_urgent_project_auto_approval_max'));
					$auto_approve_date_array[] = $auto_approve_date == 0 ? 0 : strtotime($auto_approve_date);
				}
				if($project_upgrade_array['hidden'] == 'Y') {
					$auto_approve_date = generate_random_project_autoapproval_time_between_min_max_values($CI->config->item('gold_membership_subscriber_hidden_project_auto_approval_min'), $CI->config->item('gold_membership_subscriber_hidden_project_auto_approval_max'));
					$auto_approve_date_array[] = $auto_approve_date == 0 ? 0 : strtotime($auto_approve_date);
					
				}
				if($project_upgrade_array['sealed'] == 'Y') {
					$auto_approve_date = generate_random_project_autoapproval_time_between_min_max_values($CI->config->item('gold_membership_subscriber_sealed_project_auto_approval_min'), $CI->config->item('gold_membership_subscriber_sealed_project_auto_approval_max'));
					 $auto_approve_date_array[] = $auto_approve_date == 0 ? 0 : strtotime($auto_approve_date);
				}
				$auto_approve_date = generate_random_project_autoapproval_time_between_min_max_values($CI->config->item('gold_membership_subscriber_standard_project_auto_approval_min'), $CI->config->item('gold_membership_subscriber_standard_project_auto_approval_max'));
				 $auto_approve_date_array[] = $auto_approve_date == 0 ? 0 : strtotime($auto_approve_date);
				
			}
			$auto_aprove_min = min($auto_approve_date_array);
			return $auto_aprove_min;
			
		}
	}
	
	
	if ( ! function_exists ('get_total_project_value_po')) 
	{
		/**
		* This function is used to sum of the project value for po.
		ex:sp1 project value = 1000 sp2 project value = 0 sp3 project value = 1000 total project value for po->2000
		*/
		function get_total_project_value_po($project_id,$project_type)
		{
			$CI = & get_instance ();
			$CI->load->model ('bidding/Bidding_model');
			return $CI->Bidding_model->get_total_project_value_po($project_id,$project_type);
			
		}
	}
	

/**
 * Generate pagination links for any page listing  based on parameter passed by calling routin
 */
if(! function_exists('generate_pagination_links')) {
	function generate_pagination_links($total, $url, $limit, $no_of_pagination_links, $page = '', $param = []) {
	
		
		$CI = & get_instance ();
		$CI->load->library ('pagination');
		$config = array();
		if(!empty($page)) {
			$config["cur_page"] = $page;
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
		$config["num_links"] = $no_of_pagination_links;
		$CI->pagination->initialize($config);
		$current_page = $CI->uri->segment(3);
		
		if(empty($current_page) || $current_page == null) {
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

/**
 * Replace all characters of string to asterisks except first and last characters in 
 */
if(! function_exists('replace_characters_asterisks_except_first_last')) {
	function replace_characters_asterisks_except_first_last($str) {
		$len = strlen($str);
		return substr($str, 0, 1).str_repeat('*', $len - 2).substr($str, $len - 1, 1);
	}
}	

/* This function get the portfolio tags*/
if ( ! function_exists ('get_portfolio_tags'))
{
	function get_portfolio_tags ($conditions)
	{
		$CI = & get_instance ();
		$CI->load->model ('user/User_model');
		return $CI->User_model->get_portfolio_tags($conditions);
	}

}

/* This function get the portfolio images*/
if ( ! function_exists ('get_portfolio_images'))
{
	function get_portfolio_images ($conditions)
	{
		$CI = & get_instance ();
		$CI->load->model ('user/User_model');
		return $CI->User_model->get_portfolio_images($conditions);
	}

}

/*
* 
* Generate unique portfolio id for user portfolio [6-10 digit]
*/
if(! function_exists('generate_unique_portfolio_standalone_page_id')) {
	function generate_unique_portfolio_standalone_page_id() {
		$CI = & get_instance ();
		$min = $CI->config->item('portfolio_id_min_number');
		$max = $CI->config->item('portfolio_id_max_number');
		$random_number = mt_rand($min, $max);
		
		$user_portfolio = $CI->db->get_where('users_portfolios', ['portfolio_id' => $random_number])->row_array();
		if(!empty($user_portfolio)){
			generate_unique_portfolio_standalone_page_id();
		
		}else{
			return $random_number;
		}
	}
}

/* This function get upgrade status of project*/
if ( ! function_exists ('get_project_upgrade_status'))
{
	function get_project_upgrade_status ($project_id)
	{
		$CI = & get_instance ();
		$CI->load->model ('projects/Projects_model');
		return $CI->Projects_model->get_project_upgrade_status($project_id);
	}

}

/* function used to fetch the feedback data for received feedback tab on project detail page */
if ( ! function_exists ('get_received_feedback_tab_data_project_detail'))
{
	function get_received_feedback_tab_data_project_detail ($data)
	{
		$CI = & get_instance ();
		$CI->load->model ('users_ratings_feedbacks/Users_ratings_feedbacks_model');
		return $CI->Users_ratings_feedbacks_model->get_received_feedback_tab_data_project_detail($data);
	}

}

if ( ! function_exists ('show_dynamic_rating_stars'))
{
	function show_dynamic_rating_stars ($reviews,$start_type ="large")
	{
		
		
		$rVal       = explode('.', $reviews);
		$rValInt    = $rVal[0];
		$rValFlt    = $rVal[1];
		$tStar     = $rValInt;
		if($rValFlt>0) {
			$tStar     = $rValInt+1;
		}
		$rStar      = 5-$tStar;
		$star = '';
		if($start_type == 'large'){
			if($reviews > 0) {
				if($rValInt>0) {
					for($r=1; $r<=$rValInt; $r++) {
						$star .= '<i class="fa fa-star default_avatar_review_star" aria-hidden="true"></i>';
					}
				} if($rValFlt > 0) {
					$star .= '<i class="fa fa-star-half-o default_avatar_review_star" aria-hidden="true"></i>';
				}
			}
			if($rStar>0) {
				for($ro=1; $ro<=$rStar; $ro++) {
					$star .= '<i class="fa fa-star-o default_avatar_review_star" aria-hidden="true"></i>';
				}
			}
		}
		if($start_type == 'small'){
			if($reviews > 0) {
				if($rValInt>0) {
					for($r=1; $r<=$rValInt; $r++) {
						$star .= '<i class="fa fa-star default_avatar_review_star avatar_review_star_project_owner" aria-hidden="true"></i>';
					}
				} if($rValFlt > 0) {
					$star .= '<i class="fa fa-star-half-o default_avatar_review_star avatar_review_star_project_owner" aria-hidden="true"></i>';
				}
			}
			if($rStar>0) {
				for($ro=1; $ro<=$rStar; $ro++) {
					$star .= '<i class="fa fa-star-o default_avatar_review_star avatar_review_star_project_owner" aria-hidden="true"></i>';
				}
			}
		}
		return $star;
	}

}

/*
This functions is used to fetch the active dispute reference id.
*/
if ( ! function_exists ('get_sp_project_disputed_reference_id')) 
{
	function get_sp_project_disputed_reference_id ($project_type,$conditions)
	{
		
		$CI = & get_instance ();
		if($project_type == 'fixed'){
			$active_dispute_table_name  = 'fixed_budget_projects_active_disputes';
		}
		if($project_type == 'hourly'){
			$active_dispute_table_name  = 'hourly_rate_based_projects_active_disputes';
		}
		if($project_type == 'fulltime'){
			$active_dispute_table_name  = 'fulltime_projects_active_disputes';
		}
		$result = $CI->db->select('dispute_reference_id')->get_where($active_dispute_table_name, $conditions)->row_array();
		if(!empty($result['dispute_reference_id'])){
			return $result['dispute_reference_id'];
		}else{
			return '';
		}
	}
}

/*
This functions is used to fetch the latest closed dispute record.
*/
if ( ! function_exists ('get_latest_project_closed_dispute')) 
{
	function get_latest_project_closed_dispute ($project_type,$conditions)
	{
		
		$CI = & get_instance ();
		if($project_type == 'fixed'){
			$closed_dispute_table_name  = 'fixed_budget_projects_closed_disputes';
		}
		if($project_type == 'hourly'){
			$closed_dispute_table_name  = 'hourly_rate_based_projects_closed_disputes';
		}
		if($project_type == 'fulltime'){
			$closed_dispute_table_name  = 'fulltime_projects_closed_disputes';
		}
		$result = $CI->db->from($closed_dispute_table_name)->where($conditions)->order_by('dispute_end_date', 'DESC')->limit(1)->get()->row_array();
		return $result;
	}
}


/*
This functions is used to fetch the rerverted amount of po regarding closed dispute.
*/
if ( ! function_exists ('get_projects_closed_disputes_po_reverted_amounts')) 
{
	function get_projects_closed_disputes_po_reverted_amounts ($project_type,$conditions)
	{
		
		$CI = & get_instance ();
		if($project_type == 'fixed'){
			$projects_closed_disputes_po_reverted_table_name  = 'fixed_budget_projects_closed_disputes_po_reverted_amounts';
		}if($project_type == 'hourly'){
			$projects_closed_disputes_po_reverted_table_name  = 'hourly_rate_projects_closed_disputes_po_reverted_amounts';
		}if($project_type == 'fulltime'){
			$projects_closed_disputes_po_reverted_table_name  = 'fulltime_projects_closed_disputes_employer_reverted_amounts';
		}
		$result = $CI->db->select('*')->get_where($projects_closed_disputes_po_reverted_table_name, $conditions)->row_array();
		return $result;
	}
}

if ( ! function_exists ('get_projects_disputes_admin_arbitration_fees')) 
{
	function get_projects_disputes_admin_arbitration_fees ($conditions)
	{
		$CI = & get_instance ();
		/* if($project_type == 'fixed'){
			$projects_closed_disputes_po_reverted_table_name  = 'fixed_budget_projects_closed_disputes_po_reverted_amounts';
		} */
		$result = $CI->db->select('*')->get_where('projects_disputes_admin_arbitration_fees_tracking', $conditions)->row_array();
		return $result;
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
 * This function is used to parse text and convert urls into hyperlinks
*/
if(!function_exists('linkify')) {
	/* function parse_links($str)
	{
		$url_pattern = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';   
		$str= preg_replace($url_pattern, '<a href="$0" target="_blank" rel="nofollow">$0</a>', $str);
		return $str;
	} */
	function linkify($value, $protocols = array('http', 'mail'), array $attributes = array())
    {
	
        // Link attributes
        $attr = '';
        foreach ($attributes as $key => $val) {
            $attr .= ' ' . $key . '="' . htmlentities($val) . '"';
        }
        
        $links = array();
        
        // Extract existing links and tags
        $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) { return '<' . array_push($links, $match[1]) . '>'; }, $value);
		
		
        // Extract text links for each protocol
        foreach ((array)$protocols as $protocol) {
            switch ($protocol) {
                case 'http':
                case 'https':$value = preg_replace_callback('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { if ($match[1]) $protocol = $match[1]; $link = $match[2] ?: $match[3]; return '<' . array_push($links, "<a $attr href=\"$protocol://$link\" >$link</a>") . '>'; }, $value); break;
               /*  case 'mail':    $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"mailto:{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
                case 'twitter': $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"https://twitter.com/" . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1]  . "\">{$match[0]}</a>") . '>'; }, $value); break; */
                default:        $value = preg_replace_callback('~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { return '<' . array_push($links, "<a $attr href=\"$protocol://{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
            }
        }
        // Insert all link
        return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) { return $links[$match[1] - 1]; }, $value);
    }
}
/* 
if(!function_exists('format_links_of_string')) {
	function format_links_of_string($string,$format = 'html', $returnMatches = false) {
		$formatProtocol = $format == 'html'
			? '<a href="$0" target="_blank" >$0</a>'
			: ($format == 'short' || $returnMatches ? '[link:$0]' : '$0');

		$formatSansProtocol = $format == 'html'
			? '<a href="http://$0" target="_blank">$0</a>'
			: ($format == 'short' || $returnMatches ? '[link://$0]' : '$0');

		$formatMailto = $format == 'html'
			? '<a href="mailto:$1" target="_blank" >$1</a>'
			: ($format == 'short' || $returnMatches ? '[mailto:$1]' : '$1');

		$regProtocol = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,}(\/[^\<\>\s]*)?/';
		$regSansProtocol = '/(?<=\s|\A|\>)([0-9a-zA-Z\-\.]+\.[a-zA-Z0-9\/]{2,})(?=\s|$|\,|\<)/';
		//$regSansProtocol = '^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?';
		$regEmail = '/([^\s\>\<]+\@[^\s\>\<]+\.[^\s\>\<]+)\b/';
		$consecutiveDotsRegex = $format == 'html'
			? '/<a[^\>]+[\.]{2,}[^\>]*?>([^\<]*?)<\/a>/'
			: '/\[link:.*?\/\/([^\]]+[\.]{2,}[^\]]*?)\]/';

		// Protocol links
		$formatString = preg_replace($regProtocol, $formatProtocol, $string);
		// Sans Protocol Links
		$formatString = preg_replace($regSansProtocol, $formatSansProtocol, $formatString); // use formatString from above
		// Email - Mailto - Links
		$formatString = preg_replace($regEmail, $formatMailto, $formatString); // use formatString from above
		// Prevent consecutive periods from getting captured
		$formatString = preg_replace($consecutiveDotsRegex, '$1', $formatString);

		if ($returnMatches) {
			// Find all [x:link] patterns
			preg_match_all('/\[.*?:(.*?)\]/', $formatString, $matches);

			current($matches); // to move pointer onto groups
			return next($matches); // return the groups
		}

		return $formatString;
	}
}
 */

if(!function_exists('convert_url_to_anchor')) {
	function convert_url_to_anchor($string){
		 
		$regx_string_url = preg_replace("/(\b(?:(?:http(s)?|ftp):\/\/|(www\.)))([-a-zÃ¼Ã¶Ã¤ÃŸ0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|])/im", '<a target="_blank" href="http$2://$3$4" rel="nofollow">$1$4</a>', $string);
		return $formatString = preg_replace("/([\w+\.\-]+@[\w+\-]+\.[a-zA-Z]{2,4})/im", strtolower('<a href="mailto:$1"  rel="nofollow">$1</a>'), $regx_string_url);
		
	}
}

/*
// This function is used to check and insert the data into table "projects_candidates_for_users_ratings_feedbacks_exchange" when po/sp can give the feedback for fixed/hourly/fulltime project
*/
if ( ! function_exists ('insert_data_for_projects_candidates_for_users_ratings_feedbacks_exchange')) 
{
	function insert_data_for_projects_candidates_for_users_ratings_feedbacks_exchange ($data = array())
	{
		
		$CI = & get_instance ();
		$CI->load->model ('escrow/Escrow_model');
		return $CI->Escrow_model->insert_data_for_projects_candidates_for_users_ratings_feedbacks_exchange($data);
	}
}

/*
This functions is used to fetch the latest closed dispute record.
*/
/* if ( ! function_exists ('get_latest_dispute_message_initiation_phase')) 
{
	function get_dispute_message_initiation_phase ($conditions)
	{
		$CI = & get_instance ();
		$result = $CI->db->from('projects_disputes_messages_activity_tracking')->where($conditions)->order_by('message_sent_date', 'ASC')->limit(1)->get()->row_array();
		return $result;
	}
} */

/* function used to fetch the feedback data for received feedback tab on project detail page */
/* if ( ! function_exists ('get_sp_total_reviews_count'))
{
	function get_sp_total_reviews_count ($user_id)
	{
		
		$CI = & get_instance ();
		$CI->load->model ('users_ratings_feedbacks/Users_ratings_feedbacks_model');
		return $CI->Users_ratings_feedbacks_model->get_sp_total_reviews_count($user_id);
	}

} */


//This function is using for count the total completed projects of sp(fixed+hourly+fulltime project).
/* if ( ! function_exists ('get_sp_total_completed_projects_count'))
{
	function get_sp_total_completed_projects_count ($user_id)
	{
		
		$CI = & get_instance ();
		$CI->load->model ('users_ratings_feedbacks/Users_ratings_feedbacks_model');
		return $CI->Users_ratings_feedbacks_model->get_sp_total_completed_projects_count($user_id);
	}

} */
?>
