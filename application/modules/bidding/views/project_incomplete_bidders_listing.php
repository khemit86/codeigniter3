<?php
$user = $this->session->userdata ('user');
$name = $incomplete_bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $incomplete_bidder_data['first_name'] . ' ' . $incomplete_bidder_data['last_name'] : $incomplete_bidder_data['company_name'];
$bid_value = '';
$incomplete_amount_txt = '';
$project_value = '';
$sp_completed_projects = 0;
if($project_data['project_type'] == 'fixed'){
	
	$initial_bid_description = $incomplete_bidder_data['initial_bid_description'];	
	$sp_rating = $incomplete_bidder_data['project_user_total_avg_rating_as_sp'];
	$sp_total_reviews = $incomplete_bidder_data['project_user_total_reviews'];
	if(isset($incomplete_bidder_data['sp_total_completed_fixed_budget_projects']) && isset($incomplete_bidder_data['sp_total_completed_hourly_based_projects'])){
		$sp_completed_projects = $incomplete_bidder_data['sp_total_completed_fixed_budget_projects']+$incomplete_bidder_data['sp_total_completed_hourly_based_projects'];
	}
	
	if($this->session->userdata ('user') && $incomplete_bidder_data['winner_id'] == $user[0]->user_id){
		$incomplete_amount_txt= $this->config->item('fixed_budget_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view');
	}else{
		$incomplete_amount_txt= $this->config->item('fixed_budget_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view');
	}
	if(floatval($incomplete_bidder_data['initial_project_agreed_value']) != 0){
		$project_value = $this->config->item('fixed_or_hourly_project_value').'<span class="touch_line_break">'.number_format($incomplete_bidder_data['initial_project_agreed_value'], 0, '', ' ')." ".CURRENCY.'</span>';
	}
	
} else if($project_data['project_type'] == 'hourly'){
	$initial_bid_description = $incomplete_bidder_data['initial_bid_description'];
	$incomplete_amount_txt = $this->config->item('project_details_page_bidder_listing_bidded_hourly_rate_txt');
	$sp_rating = $incomplete_bidder_data['project_user_total_avg_rating_as_sp'];
	$sp_total_reviews = $incomplete_bidder_data['project_user_total_reviews'];
	if(isset($incomplete_bidder_data['sp_total_completed_fixed_budget_projects']) && isset($incomplete_bidder_data['sp_total_completed_hourly_based_projects'])){
		$sp_completed_projects = $incomplete_bidder_data['sp_total_completed_fixed_budget_projects']+$incomplete_bidder_data['sp_total_completed_hourly_based_projects'];
	}
} else if($project_data['project_type'] == 'fulltime'){
	$initial_bid_description = $incomplete_bidder_data['initial_application_description'];
	$incomplete_amount_txt = $this->config->item('project_details_page_bidder_listing_expected_salary_txt');
	
	$sp_rating = $incomplete_bidder_data['fulltime_project_user_total_avg_rating_as_employee'];
	$sp_total_reviews = $incomplete_bidder_data['fulltime_project_user_total_reviews'];
	if(isset($incomplete_bidder_data['employee_total_completed_fulltime_projects'])){
	 $sp_completed_projects = $incomplete_bidder_data['employee_total_completed_fulltime_projects'];
	}
}
if($sp_total_reviews == 0){
	$trGiven = number_format($sp_total_reviews,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
}else if($sp_total_reviews == 1) {
	$trGiven = number_format($sp_total_reviews,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
} else if($sp_total_reviews > 1) {
	$trGiven = number_format($sp_total_reviews,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
}


if($incomplete_bidder_data['bidding_dropdown_option'] == 'NA'){
	if($project_data['project_type'] == 'fixed'){
	
		
		if(floatval($incomplete_bidder_data['initial_project_agreed_delivery_period']) == 1){
			$bidder_listing_details_fixed_txt = $this->config->item('1_day');
		}else if(floatval($incomplete_bidder_data['initial_project_agreed_delivery_period']) >=2 && floatval($incomplete_bidder_data['initial_project_agreed_delivery_period']) <= 4){
			$bidder_listing_details_fixed_txt = $this->config->item('2_4_days');
		}else if(floatval($incomplete_bidder_data['initial_project_agreed_delivery_period']) >4){
			$bidder_listing_details_fixed_txt = $this->config->item('more_than_or_equal_5_days');
		}
		
	
		$exptected_date = '';

		if($this->session->userdata ('user') && ($incomplete_bidder_data['project_owner_id'] == $user[0]->user_id || $incomplete_bidder_data['winner_id'] == $user[0]->user_id)){
		
			$exptected_date_second = strtotime($incomplete_bidder_data['project_start_date'])+($incomplete_bidder_data['initial_project_agreed_delivery_period'])*86400;
		
			$exptected_date = "(".$this->config->item('in_progress_bidding_listing_fixed_expected_completion_date').'<span class="touch_line_break">'.date(DATE_FORMAT,$exptected_date_second)."</span>)";
		}
	
		
		$bid_value = '<small><i class="far fa-credit-card"></i>'.$incomplete_amount_txt.'<span class="touch_line_break">'.number_format($incomplete_bidder_data['initial_bid_value'], 0, '', ' ').' '.CURRENCY.'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').'<span class="touch_line_break">'. str_replace(".00","",number_format($incomplete_bidder_data['initial_project_agreed_delivery_period'],  2, '.', ' '))." ".$bidder_listing_details_fixed_txt.'</span>'.$exptected_date.'</small>';
		
	} else if($project_data['project_type'] == 'hourly'){
		$total_bid_value = floatval($incomplete_bidder_data['initial_project_agreed_value']);
		
		
		
		if(floatval($incomplete_bidder_data['initial_project_agreed_number_of_hours']) == 1){
			$bidder_listing_details_hour_txt = $this->config->item('1_hour');
		}else if(floatval($incomplete_bidder_data['initial_project_agreed_number_of_hours']) >=2 && floatval($incomplete_bidder_data['initial_project_agreed_number_of_hours']) <= 4){
			$bidder_listing_details_hour_txt = $this->config->item('2_4_hours');
		}else if(floatval($incomplete_bidder_data['initial_project_agreed_number_of_hours']) >4){
			$bidder_listing_details_hour_txt = $this->config->item('more_than_or_equal_5_hours');
		}
		
		
		
		$bid_value = '<small><i class="fas fa-stopwatch"></i>'.$incomplete_amount_txt.'<span class="touch_line_break">'.number_format($incomplete_bidder_data['initial_project_agreed_hourly_rate'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_hour').'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').'<span class="touch_line_break">'.str_replace(".00","",number_format($incomplete_bidder_data['initial_project_agreed_number_of_hours'],  2, '.', ' ')).' '.$bidder_listing_details_hour_txt.'</span></small>';

		if($total_paid_amount[$incomplete_bidder_data['winner_id']] > $total_bid_value) {
			$total_paid = $total_paid_amount[$incomplete_bidder_data['winner_id']];
		} else {
			$total_paid = $total_bid_value;
		}

		$bid_value .= '<small><i class="fas fa-coins"></i>'.$this->config->item('fixed_or_hourly_project_value').'<span class="touch_line_break total_paid_amount'.$incomplete_bidder_data['winner_id'].'">'.number_format($total_paid, 0, '', ' ').' '.CURRENCY.'</span></small>';
		
	} 
} else if ($incomplete_bidder_data['bidding_dropdown_option'] == 'to_be_agreed'){

	if($project_data['project_type'] == 'hourly'){
		if($this->session->userdata ('user') && $user[0]->user_id == $incomplete_bidder_data['project_owner_id']) { 
			$bid_value = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
		} else {
			$bid_value = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
		}

		$total_paid = $total_paid_amount[$incomplete_bidder_data['winner_id']];
		$total_paid_display = 'inline-block';
		if($total_paid == 0) {
			$total_paid_display = 'none';
		}
		$bid_value .= '<small style="display:'.$total_paid_display.'"><i class="fas fa-coins"></i>'.$this->config->item('fixed_or_hourly_project_value').'<span class="total_paid_amount'.$incomplete_bidder_data['winner_id'].'">'.number_format($total_paid, 0, '', ' ')."</span>".' '.CURRENCY.'</small>';

	} else if($project_data['project_type'] == 'fixed') {
		$bid_value = '<small><i class="far fa-credit-card"></i>'.$incomplete_amount_txt.'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
	}
} else if ($incomplete_bidder_data['bidding_dropdown_option'] == 'confidential'){
	if($project_data['project_type'] == 'hourly'){
		if($this->session->userdata ('user') && $user[0]->user_id == $incomplete_bidder_data['project_owner_id']) { 
			$bid_value = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';
		} else {
			$bid_value = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';
		}
		$total_paid = $total_paid_amount[$incomplete_bidder_data['winner_id']];
		$total_paid_display = 'inline-block';
		if($total_paid == 0) {
			$total_paid_display = 'none';
		}
		$bid_value .= '<small style="display:'.$total_paid_display.'"><i class="fas fa-coins"></i>'.$this->config->item('fixed_or_hourly_project_value').'<span class="touch_line_break total_paid_amount'.$incomplete_bidder_data['winner_id'].'">'.number_format($total_paid, 0, '', ' ')."</span>".' '.CURRENCY.'</small>';
	} else if($project_data['project_type'] == 'fixed') {
		$bid_value = '<small><i class="far fa-credit-card"></i>'.$incomplete_amount_txt.'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';
	}
}
?>

<div class="freeBid in_complete_section bidderListPage" id="<?php echo $project_data['project_type'] == 'fulltime' ? "in_complete_section_".$incomplete_bidder_data['employee_id'] : "in_complete_section_".$incomplete_bidder_data['winner_id']; ?>">
	<div class="fLancerbidding">
		<?php
			
		if($this->session->userdata ('user') && ($user[0]->user_id == $incomplete_bidder_data['winner_id'] || $user[0]->user_id == $incomplete_bidder_data['project_owner_id'])){
		?>
		<div class="user_details_in_progress_bids">
			<div class="opLBttm opBg">
				<div class="sRate incompletedBids">
					<span class="default_user_name inProgress_title"><a class="default_user_name_link" href="<?php echo site_url ($incomplete_bidder_data['profile_name']); ?>"><?php echo $name; ?></a></span><span class="inProgress_ratting"><span class="inProgress_rating_details"><?php echo show_dynamic_rating_stars($sp_rating,'small'); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $sp_rating;?></small></span><small class="default_avatar_total_review"><?php echo $trGiven;?></small></span>
				</div>
				<p class="default_short_details_field inBids_bdr ipB_top">
					<?php
						if($project_data['project_type'] == 'fulltime'){
							$start_date_label = $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($incomplete_bidder_data['project_start_date'])).'</span>';
						} else if($project_data['project_type'] == 'fixed') {
							$start_date_label = $this->config->item('in_progress_bidding_listing_project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($incomplete_bidder_data['project_start_date'])).'</span>';
						} else if($project_data['project_type'] == 'hourly') {
							$start_date_label = $this->config->item('in_progress_bidding_listing_hourly_rate_based_project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($incomplete_bidder_data['project_start_date'])).'</span>';
						}
					?><small><i class="far fa-clock"></i><?php echo $start_date_label; ?></small><?php
						if(!empty($bid_value)){ 
							echo $bid_value;	
						} 
						if(!empty($project_value) && $this->session->userdata ('user') && ($incomplete_bidder_data['winner_id'] == $user[0]->user_id || $incomplete_bidder_data['project_owner_id'] == $user[0]->user_id )){
						?><small><i class="fas fa-coins"></i><span class="project_value"><?php echo $project_value; ?><span></small><?php	
						} 
						?><?php
					if($incomplete_bidder_data['total_sp_active_dispute_count'] > 0){  ?><small><i class="fas fa-exclamation-triangle" style="color:red;"></i></small><?php }else{
					
						$get_latest_closed_dispute_record = array();
						if($project_data['project_type'] == 'fixed' || $project_data['project_type'] == 'hourly'){
							$get_latest_closed_dispute_record = get_latest_project_closed_dispute($project_data['project_type'],array('disputed_project_id'=>$incomplete_bidder_data['project_id'],'sp_winner_id_of_disputed_project'=>$incomplete_bidder_data['winner_id'])); 
						}
						
						
						if(!empty($get_latest_closed_dispute_record) && ($get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_sp' || $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_po' || $get_latest_closed_dispute_record['dispute_status'] == 'automatic_decision' || $get_latest_closed_dispute_record['dispute_status'] == 'parties_agreement')){
						?><small><i class="fas fa-balance-scale"></i></small><?php	
						}if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['sp_winner_id_of_disputed_project']){
						?><small><i class="fas fa-balance-scale-left"></i></small><?php	
						}
						if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['project_owner_id_of_disputed_project']){ ?><small><i class="fas fa-balance-scale-right"></i></small><?php	
						}
					}		
					?></p>
				<!-- manish start design here -->
				<div class="inProgress_RadioBtn">
					<?php
					
					
					if($project_data['project_type'] == 'fulltime'){
						$initial_project_agreed_value = $incomplete_bidder_data['initial_fulltime_project_agreed_salary'];
						$paid_milestones_count_project = $total_paid_amount[$incomplete_bidder_data['winner_id']];
					} else {
						$initial_project_agreed_value = $incomplete_bidder_data['initial_project_agreed_value'];
						$mark_complete_project_request_listing_data = get_mark_complete_project_request_listing($project_data['project_type'],array('project_id'=>$project_id,'project_owner_id'=>$incomplete_bidder_data['project_owner_id'],'winner_id'=>$incomplete_bidder_data['winner_id'])); // fetch the complete request
						$mark_complete_project_request_data = $mark_complete_project_request_listing_data['data'];
						$mark_complete_project_request_count = $mark_complete_project_request_listing_data['total'];
						$paid_milestones_count_project = get_released_escrows_count_project($project_data['project_type'],array('project_id'=>$project_data['project_id'],'project_owner_id'=>$project_data['project_owner_id'],'winner_id'=>$incomplete_bidder_data['winner_id'], 'is_partial_released' => 'N')); // count the active milestone
					}
								
					
					
					$default_Threecheckbox_button = 'default_Threecheckbox_button';
					
					if($project_data['project_type'] != 'fulltime' && (($user[0]->user_id == $incomplete_bidder_data['project_owner_id']) || (!empty($mark_complete_project_request_data) && $user[0]->user_id == $incomplete_bidder_data['winner_id']))){
						if(!($initial_project_agreed_value ==  0 && $paid_milestones_count_project > 0)) {
							$default_Threecheckbox_button = '';
						}
					}
					if($initial_project_agreed_value ==  0 && $paid_milestones_count_project > 0 && $project_data['project_type'] == 'hourly') { 
						$default_Threecheckbox_button = '';
					}
					
					if($project_data['project_type'] == 'fulltime' && ($paid_milestones_count_project >= $incomplete_bidder_data['initial_fulltime_project_threshold_value'] && (strtotime(date('Y-m-d H:i:s')) > strtotime($incomplete_bidder_data['feedback_exchange_availability_date'])))) {
						$default_Threecheckbox_button = '';
					}
					?>
					<div class="default_checkbox_button radio_bttmBdr <?php echo $default_Threecheckbox_button; ?>" id="<?php echo "tab_container_".$incomplete_bidder_data['winner_id']; ?>">
							<span class="singleLine_chkBtn small_radio_btn">
								<input type="checkbox" id="description_radio<?php echo $incomplete_bidder_data['winner_id'] ?>" name="inProgress_mainRadio" value="description" class="location_option chk-btn project_detail_tab" data-id ="<?php echo $incomplete_bidder_data['winner_id']; ?>" data-target="description<?php echo $incomplete_bidder_data['winner_id']; ?>" data-sp-id="<?php echo Cryptor::doEncrypt($incomplete_bidder_data['winner_id']);?>" data-po-id="<?php echo Cryptor::doEncrypt($incomplete_bidder_data['project_owner_id']); ?>" data-section-name="incomplete" data-section-id="<?php echo $incomplete_bidder_data['winner_id'];?>">
								<label class="singleLine_radioBtn" for="description_radio<?php echo $incomplete_bidder_data['winner_id'] ?>">
									<span><?php echo $this->config->item('project_details_page_description_section_tab');?></span>
								</label>
							</span>
							<span  class="singleLine_chkBtn small_radio_btn">
								<input type="checkbox" id="payments_radio<?php echo $incomplete_bidder_data['winner_id'] ?>" name="inProgress_mainRadio" value="payments" class="location_option chk-btn project_detail_tab" data-id = "<?php echo $incomplete_bidder_data['winner_id']; ?>"  data-target="milestone<?php echo $incomplete_bidder_data['winner_id']; ?>" data-sp-id="<?php echo Cryptor::doEncrypt($incomplete_bidder_data['winner_id']);?>" data-po-id="<?php echo Cryptor::doEncrypt($incomplete_bidder_data['project_owner_id']); ?>" data-section-name="incomplete" data-section-id="<?php echo $incomplete_bidder_data['winner_id'];?>">
								<label class="singleLine_radioBtn" for="payments_radio<?php echo $incomplete_bidder_data['winner_id'] ?>">
									<span><?php echo $this->config->item('project_details_page_payment_management_section_tab');?></span>
								</label>
							</span>
							<span   class="singleLine_chkBtn small_radio_btn">
							<?php
							
								if($this->session->userdata ('user') && $user[0]->user_id == $incomplete_bidder_data['project_owner_id']) {
									$receiver_id = $incomplete_bidder_data['winner_id'];
								} else {
									$receiver_id = $incomplete_bidder_data['project_owner_id'];
								}
							?>
								<input data-id="<?php echo $receiver_id; ?>" data-project-title="<?php echo $project_data['project_title'] ?>" data-project-owner="<?php echo $incomplete_bidder_data['project_owner_id']; ?>" data-receiver-id="<?php echo $receiver_id; ?>" data-project-id="<?php echo $incomplete_bidder_data['project_id']; ?>" data-target="messages<?php echo $incomplete_bidder_data['winner_id']; ?>" type="checkbox" id="messages_radio<?php echo $incomplete_bidder_data['winner_id'] ?>" name="inProgress_mainRadio" value="messages" class="location_option chk-btn project_detail_tab messagesTab" data-sp-id="<?php echo Cryptor::doEncrypt($incomplete_bidder_data['winner_id']);?>" data-po-id="<?php echo Cryptor::doEncrypt($incomplete_bidder_data['project_owner_id']); ?>" data-section-name="incomplete" data-section-id="<?php echo $incomplete_bidder_data['winner_id'];?>">
								<label class="singleLine_radioBtn" for="messages_radio<?php echo $incomplete_bidder_data['winner_id']; ?>">
									<span class="mr-0"><?php echo $this->config->item('project_details_page_messages_section_tab');?></span>
									<span class="default_counter_notification_red badge custom_badge ml-1 <?php echo $project_chat_unread_messages_count[$incomplete_bidder_data['winner_id']] == 0 ? 'd-none' : ''; ?>"><?php echo $project_chat_unread_messages_count[$incomplete_bidder_data['winner_id']] < 99 ? $project_chat_unread_messages_count[$incomplete_bidder_data['winner_id']] : '99+'; ?></span>
								</label>
							</span>
							
							<?php
								
								$mark_complete_display_css = 'none';
								if(!($initial_project_agreed_value ==  0 && $paid_milestones_count_project > 0)) {
									$mark_complete_display_css = 'inline-block';
								}
								if($project_data['project_type'] != 'fulltime' && (($user[0]->user_id == $incomplete_bidder_data['project_owner_id']) || (!empty($mark_complete_project_request_data) && $user[0]->user_id == $incomplete_bidder_data['winner_id']))){
							?>
							
							<span class="singleLine_chkBtn" style="display:<?php echo $mark_complete_display_css; ?>">
								<input type="checkbox" data-id="<?php echo $incomplete_bidder_data['winner_id']; ?>" data-target="markProjectComplete<?php echo $incomplete_bidder_data['winner_id']; ?>"  id="mark_project_complete_radio<?php echo $incomplete_bidder_data['winner_id'] ?>" name="inProgress_mainRadio" value="mark_project_complete" class="location_option chk-btn project_detail_tab" data-sp-id="<?php echo Cryptor::doEncrypt($incomplete_bidder_data['winner_id']);?>" data-po-id="<?php echo Cryptor::doEncrypt($incomplete_bidder_data['project_owner_id']); ?>" data-section-name="incomplete" data-section-id="<?php echo $incomplete_bidder_data['winner_id'];?>">
								<label class="singleLine_radioBtn" for="mark_project_complete_radio<?php echo $incomplete_bidder_data['winner_id'] ?>">
									<span><?php echo $this->config->item('project_details_page_mark_project_complete_section_tab');?></span>
								</label>
							</span>
							<?php
									}
							?>
							<?php
								$feedback_display_css = 'none';
								if($initial_project_agreed_value ==  0 && $paid_milestones_count_project > 0 && $project_data['project_type'] == 'hourly') { 
									$feedback_display_css = 'inline-block';
								}
								
								if($project_data['project_type'] == 'fulltime' && ($paid_milestones_count_project >= $incomplete_bidder_data['initial_fulltime_project_threshold_value'] && (strtotime(date('Y-m-d H:i:s')) > strtotime($incomplete_bidder_data['feedback_exchange_availability_date'])))) {
									$feedback_display_css = 'inline-block';
								}
							?>
							<span  class="singleLine_chkBtn small_radio_btn" style="display:<?php echo $feedback_display_css; ?>">
								<input data-target="feedback<?php echo $incomplete_bidder_data['winner_id']; ?>" data-id="<?php echo $incomplete_bidder_data['winner_id']; ?>" type="checkbox" id="feedback_radio<?php echo $incomplete_bidder_data['winner_id'] ?>" name="completed_mainRadio" value="feedback" class="project_detail_tab location_option chk-btn">
								<label class="singleLine_radioBtn" for="feedback_radio<?php echo $incomplete_bidder_data['winner_id'] ?>">
									<span><?php echo $this->config->item('project_details_page_feedback_section_tab');?></span>
								</label>
							</span>
					</div>
					
					<!-- here start the tab data html structure start -->
					<div class="inprogressTab <?php echo "project_detail_tab_container".$incomplete_bidder_data['winner_id'] ?>" id="description<?php echo $incomplete_bidder_data['winner_id'] ?>" style="display: none;">
						<?php
						
						//$initial_bid_description = limitStringShowMoreLess($initial_bid_description);
						$descLeng	=	strlen($initial_bid_description);
						/*----------- description show for desktop screen start----*/
						$desktop_cnt            =	0;
						if($descLeng <= $this->config->item('project_details_page_bidder_listing_bid_description_character_limit_desktop')) {
							$desktop_description	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($initial_bid_description, ENT_QUOTES)));
						} else {
							$desktop_description	= character_limiter($initial_bid_description,$this->config->item('project_details_page_bidder_listing_bid_description_character_limit_desktop'));
							$desktop_restdescription	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($initial_bid_description, ENT_QUOTES)));
							$desktop_cnt = 1;
						}
						/*----------- description show for desktop screen end----*/
						
						/*----------- description show for ipad screen start----*/
						$tablet_cnt            =	0;
						if($descLeng <= $this->config->item('project_details_page_bidder_listing_bid_description_character_limit_tablet')) {
							$tablet_description	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($initial_bid_description, ENT_QUOTES)));
						} else {
							$tablet_description	= character_limiter($initial_bid_description,$this->config->item('project_details_page_bidder_listing_bid_description_character_limit_tablet'));
							$tablet_restdescription	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($initial_bid_description, ENT_QUOTES)));
							$tablet_cnt = 1;
						}
						/*----------- description show for ipad screen end----*/

						/*----------- description show for mobile screen start----*/
						$mobile_cnt            =	0;
						if($descLeng <= $this->config->item('project_details_page_bidder_listing_bid_description_character_limit_mobile')) {
							$mobile_description	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($initial_bid_description, ENT_QUOTES)));
						} else {
							$mobile_description	= character_limiter($initial_bid_description,$this->config->item('project_details_page_bidder_listing_bid_description_character_limit_mobile'));
							$mobile_restdescription	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($initial_bid_description, ENT_QUOTES)));
							$mobile_cnt = 1;
						}
						/*----------- description show for mobile screen end----*/
						?>
						<div class="default_user_description project_list_tab_desc desktop-secreen">
							<p id="desktop_lessD<?php echo $incomplete_bidder_data['winner_id']; ?>">
								<?php echo $desktop_description;?><?php if($desktop_cnt==1) {?><span id="desktop_dotsD<?php echo $incomplete_bidder_data['winner_id']; ?>"></span><button onclick="showMoreDescription('desktop', <?php echo $incomplete_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button><?php } ?></p><p id="desktop_moreD<?php echo $incomplete_bidder_data['winner_id']; ?>" class="moreD"><?php echo $desktop_restdescription;?><button onclick="showMoreDescription('desktop', <?php echo $incomplete_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button></p></div>
						<div class="default_user_description project_list_tab_desc ipad-screen">
							<p id="tablet_lessD<?php echo $incomplete_bidder_data['winner_id']; ?>"><?php echo $tablet_description;?><?php if($tablet_cnt==1) {?><span id="tablet_dotsD<?php echo $incomplete_bidder_data['winner_id']; ?>"></span><button onclick="showMoreDescription('tablet', <?php echo $incomplete_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button><?php } ?></p><p id="tablet_moreD<?php echo $incomplete_bidder_data['winner_id']; ?>" class="moreD"><?php echo $tablet_restdescription;?><button onclick="showMoreDescription('tablet', <?php echo $incomplete_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button></p></div>
						<div class="default_user_description project_list_tab_desc mobile-screen">
							<p id="mobile_lessD<?php echo $incomplete_bidder_data['winner_id']; ?>"><?php echo $mobile_description;?><?php if($mobile_cnt==1) {?><span id="mobile_dotsD<?php echo $incomplete_bidder_data['winner_id']; ?>"></span><button onclick="showMoreDescription('mobile', <?php echo $incomplete_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button><?php } ?></p><p id="mobile_moreD<?php echo $incomplete_bidder_data['winner_id']; ?>" class="moreD"><?php echo $mobile_restdescription;?><button onclick="showMoreDescription('mobile', <?php echo $incomplete_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button></p></div>
						<?php
						if(isset($incomplete_bidder_data['bid_attachments']) && !empty($incomplete_bidder_data['bid_attachments'])){ ?>
						<div class="acCo projectAttachment">
							<div class="row">
							<div class="col-md-12 col-sm-12 col-12">
							<?php
							foreach($incomplete_bidder_data['bid_attachments'] as $bid_attachment_value){
							?>
								
								<?php
								if(($user[0]->user_id == $bid_attachment_value['user_id']) || ($user[0]->user_id == $project_data['project_owner_id'])){
								?><small><a style="cursor:pointer" class="download_attachment download_bidder_list_bid_attachment" data-attr = "<?php echo Cryptor::doEncrypt($bid_attachment_value['id']); ?>" data-sp-id = "<?php echo Cryptor::doEncrypt($bid_attachment_value['user_id']); ?>" data-po-id = "<?php echo Cryptor::doEncrypt($project_data['project_owner_id']); ?>"><i class="fas fa-paperclip"></i><?php echo $bid_attachment_value['bid_attachment_name']; ?></a></small>
								<?php
								}
								?>
							<?php
							}
							?>
							</div>
							</div>
						</div>
						<?php
						}
						?>
					</div>
				
					<div class="inprogressTab <?php echo "project_detail_tab_container".$incomplete_bidder_data['winner_id'] ?>" id="milestone<?php echo $incomplete_bidder_data['winner_id'] ?>" style="display: none;">
						<!--<div class="default_radio_button">
						<div data-toggle="tooltip" data-placement="top" title="<?php echo $requested_payment_tab_tooltip_msg; ?>">
						<a data-toggle="tab" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $bid_id; ?>" data-tab-type = "requested_milestone" data-section-name="<?php echo $section_name; ?>" data-target="<?php echo "#".$section_name."RqstPym".$bid_id ?>"><label for="incoming_payment_requests">
								<span><?php echo $requested_payment_tab; ?></span>
							</label></a>
						</div>
						</div>-->
						<?php
						// this condition will execute when bidder is login then his milestone section will show
						if($this->session->userdata ('user') && $user[0]->user_id == $incomplete_bidder_data['winner_id']){
							echo $this->load->view('escrow/sp_escrows_section_project_detail',array('winner_id'=>$incomplete_bidder_data['winner_id'],'project_owner_id'=>$incomplete_bidder_data['project_owner_id'],'project_id'=>$incomplete_bidder_data['project_id'],'project_type'=>$project_data['project_type'],'sp_id'=> $incomplete_bidder_data['winner_id'],'po_id'=> $incomplete_bidder_data['project_owner_id'],'project_owner_name'=>$project_owner_name,'bid_id'=>$incomplete_bidder_data['id'], 'incomplete_bidder_data' => $incomplete_bidder_data, 'section_name'=>'incomplete'), true);
						}
						// this condition will execute when project owner is login then his milestone section will show
						
						if($this->session->userdata ('user') && $user[0]->user_id == $incomplete_bidder_data['project_owner_id']){
							echo $this->load->view('escrow/po_escrows_section_project_detail',array('winner_id'=>$incomplete_bidder_data['winner_id'],'project_owner_id'=>$incomplete_bidder_data['project_owner_id'],'project_id'=>$incomplete_bidder_data['project_id'],'project_type'=>$project_data['project_type'],'sp_id'=> $incomplete_bidder_data['winner_id'],'po_id'=> $incomplete_bidder_data['project_owner_id'],'project_owner_name'=>$project_owner_name,'bid_id'=>$incomplete_bidder_data['id'],'service_provider_name'=>$name,'incomplete_bidder_data' => $incomplete_bidder_data,'section_name'=>'incomplete'), true);
						}
						?>
					
					
					</div>
					
					<?php
						if($this->session->userdata ('user') && $user[0]->user_id == $incomplete_bidder_data['project_owner_id']) {
							$receiver_id = $incomplete_bidder_data['winner_id'];
						} else {
							$receiver_id = $incomplete_bidder_data['project_owner_id'];
						}
					?>
					<div class="inprogressTab <?php echo "project_detail_tab_container".$incomplete_bidder_data['winner_id'] ?>" id="messages<?php echo $incomplete_bidder_data['winner_id']; ?>" style="display: none;">
						 <?php echo $this->load->view('bidding/message_tab_information', ['project_status' => 'in-progress', 'incomplete_bidder_data' => $incomplete_bidder_data, 'incomplete_profile_pic' => $incomplete_profile_pic, 'user' => $user ], true); ?>
					</div>
					<?php
						$view_type = '';
						if($this->session->userdata ('user') && $user[0]->user_id == $incomplete_bidder_data['project_owner_id']) {
							$view_type = "po";
						} else {
							$view_type = 'sp';
							$receiver_id = $incomplete_bidder_data['project_owner_id'];
						}
					?>
					<div class="inprogressTab <?php echo "project_detail_tab_container".$incomplete_bidder_data['winner_id'] ?>" id="markProjectComplete<?php echo $incomplete_bidder_data['winner_id']; ?>" style="display: none;">
						<?php
						echo $this->load->view('bidding/mark_project_complete_request_section_project_detail',array('mark_complete_project_request_data'=>$mark_complete_project_request_data,'mark_complete_project_request_count'=>$mark_complete_project_request_count,'winner_id'=>$incomplete_bidder_data['winner_id'],'project_owner_id'=>$incomplete_bidder_data['project_owner_id'],'project_id'=>$incomplete_bidder_data['project_id'],'project_type'=>$project_data['project_type'],'sp_id'=> $incomplete_bidder_data['winner_id'],'po_id'=> $incomplete_bidder_data['project_owner_id'],'project_owner_name'=>$project_owner_name,'bid_id'=>$incomplete_bidder_data['id'],'section_name'=>'incomplete'), true);
						?>
					</div>
					<div class="inprogressTab <?php echo "project_detail_tab_container".$incomplete_bidder_data['winner_id'] ?>" id="feedback<?php echo $incomplete_bidder_data['winner_id'] ?>" style="display: none;">
						<div class="feedbackTab">
							<ul class="nav nav-tabs">
								<li class="nav-item">
								
									<a style="cursor:pointer" id="feedback_recieved_incomplete_<?php echo $incomplete_bidder_data['winner_id'] ?>" class="nav-link review_rating_feedback_tab active feedback_recieved" data-toggle="tab"  data-target="#fReceived_inprogress<?php echo $incomplete_bidder_data['winner_id'] ?>" data-view-type = "<?php echo $view_type;?>" data-project-type = "<?php echo $project_data['project_type']; ?>" data-section-name="inprogress"  data-section-id="<?php echo $incomplete_bidder_data['winner_id']; ?>"  data-project-id="<?php echo $incomplete_bidder_data['project_id']; ?>" data-sp-id = "<?php echo Cryptor::doEncrypt($incomplete_bidder_data['winner_id']); ?>" data-po-id = "<?php echo Cryptor::doEncrypt($incomplete_bidder_data['project_owner_id']); ?>" data-tab-type="feedback_received"><?php echo $this->config->item('projects_users_ratings_feedbacks_received_tab_project_detail'); ?></a>
								</li>
								<li class="nav-item">
									<a style="cursor:pointer" id="feedback_given_incomplete_<?php echo $incomplete_bidder_data['winner_id'] ?>" class="nav-link review_rating_feedback_tab feedback_given" data-toggle="tab" data-target="#fGiven_inprogress<?php echo $incomplete_bidder_data['winner_id'] ?>" data-view-type = "<?php echo $view_type;?>" data-project-type = "<?php echo $project_data['project_type']; ?>" data-section-name="inprogress"  data-section-id="<?php echo $incomplete_bidder_data['winner_id']; ?>"  data-project-id="<?php echo $incomplete_bidder_data['project_id']; ?>" data-sp-id = "<?php echo Cryptor::doEncrypt($incomplete_bidder_data['winner_id']); ?>" data-po-id = "<?php echo Cryptor::doEncrypt($incomplete_bidder_data['project_owner_id']); ?>" data-tab-type="feedback_given"><?php echo $this->config->item('projects_users_ratings_feedbacks_given_tab_project_detail'); ?></a>
								</li>
							</ul>

							<!-- Tab panes -->
							<div class="tab-content">
								<div class="tab-pane active" id="fReceived_inprogress<?php echo $incomplete_bidder_data['winner_id'] ?>">
								<?php 
									$feedback_data = get_received_feedback_tab_data_project_detail(array('login_user_id'=>$user[0]->user_id,'view_type'=>$view_type,'project_type'=>$project_data['project_type'],'po_id'=>$incomplete_bidder_data['project_owner_id'],'sp_id'=>$incomplete_bidder_data['winner_id'],'project_id'=>$incomplete_bidder_data['project_id']));
									
									
									echo $this->load->view('users_ratings_feedbacks/users_ratings_feedbacks_received_section_project_detail_page',['project_type'=>$project_data['project_type'],'view_type'=>$view_type,'sp_id'=>$incomplete_bidder_data['winner_id'],'po_id'=> $incomplete_bidder_data['project_owner_id'],'check_receiver_received_rating'=>$feedback_data['check_receiver_received_rating'],'check_receiver_view_his_rating'=>$feedback_data['check_receiver_view_his_rating'],'feedback_given_msg'=>$feedback_data['feedback_given_msg'],'feedback_data'=>$feedback_data['feedback_data'],'po_name'=>$feedback_data['po_name'],'sp_name'=>$feedback_data['sp_name'],'other_party_given_feedback_msg'=>$feedback_data['other_party_given_feedback_msg'],'other_party_not_given_feedback_msg'=>$feedback_data['other_party_not_given_feedback_msg'],'sp_is_authorized_physical_person'=>$feedback_data['sp_is_authorized_physical_person'],'sp_gender'=>$feedback_data['sp_gender'],'sp_account_type'=>$feedback_data['sp_account_type'],'po_is_authorized_physical_person'=>$feedback_data['po_is_authorized_physical_person'],'po_gender'=>$feedback_data['po_gender'],'po_account_type'=>$feedback_data['po_account_type'],'section_id'=>$incomplete_bidder_data['winner_id']], true); ?>
								</div>
								<div class="tab-pane fade" id="fGiven_inprogress<?php echo $incomplete_bidder_data['winner_id'] ?>">
									
								</div>
							</div>
						</div>
					</div>
					<!-- here start the tab data html structure end -->
				</div>
				
				<!-- manish end work here -->
				
			</div>
			<div class="clearfix"></div>
		</div>
		<?php
		}else{
		?>
			<div class="personImg user_avatar_project_owner">		
				<div class="imgTxtR">						
					<div class="imgSize">						
						<div class="avtOnly">
							<?php
							if(!empty($incomplete_bidder_data['user_avatar']) ) {
							$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$incomplete_bidder_data['profile_name'].USER_AVATAR.$incomplete_bidder_data['user_avatar'];
							} else {
								if($incomplete_bidder_data['account_type'] == 1){
									if($incomplete_bidder_data['gender'] == 'M'){
										$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
									}if($incomplete_bidder_data['gender'] == 'F'){
									   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
									}
								} else {
									$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
								}
							}
							?>
							<div id="profile-picture" class="default_avatar_image avatar_image_size_project_owner" style="background-image: url('<?php echo $user_profile_picture;?>')">
							</div>
						</div>
						<div class="sRate poSRate">
							<span><?php echo show_dynamic_rating_stars($sp_rating,'small'); ?></span>
							<small class="default_avatar_review avatar_review_project_owner"><?php echo $sp_rating;?></small>
						</div>						
						<div class="rvw">
							<span class="default_avatar_total_review"><?php echo $trGiven;?></span>
							<?php if($sp_completed_projects > 0){ ?>
							<small class="default_avatar_complete_project"><?php 
								if($project_data['project_type'] == 'fulltime'){
									if(($incomplete_bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($incomplete_bidder_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $incomplete_bidder_data['is_authorized_physical_person'] == 'Y' )){
										if($incomplete_bidder_data['gender'] == 'M'){
											echo $this->config->item('project_details_page_male_user_hires_on_fulltime_projects_as_employee')." ".number_format($sp_completed_projects,0, '.', ' ');
										}else{
											echo $this->config->item('project_details_page_female_user_hires_on_fulltime_projects_as_employee')." ".number_format($sp_completed_projects,0, '.', ' ');
										}
									}else{
										echo $this->config->item('project_details_page_company_user_hires_on_fulltime_projects_as_employee')." ".number_format($sp_completed_projects,0, '.', ' ');
									}
								}else{
									echo $this->config->item('project_details_page_user_completed_projects_as_sp')." ".number_format($sp_completed_projects,0, '.', ' ');
								}?></small><?php } ?>	
						</div>
					</div>
				</div>
			</div>
			<div class="user_details_right_adjust_project_owner">
				<div class="opLBttm opBg">
					<div class="default_user_name">
					  <a class="default_user_name_link" href="<?php echo site_url ($incomplete_bidder_data['profile_name']); ?>"><?php echo $name; ?></a>
					</div>
					<div class="bottom_border default_short_details_field">
						<small><i class="far fa-clock"></i><?php
						if($project_data['project_type'] == 'fulltime'){
							echo $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($incomplete_bidder_data['project_start_date'])).'</span>';
						}else{
							echo $this->config->item('in_progress_bidding_listing_project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($incomplete_bidder_data['project_start_date'])).'</span>';
						}
						?></small>
						<?php
						if($incomplete_bidder_data['total_sp_active_dispute_count'] > 0){ ?>
						<small><i class="fas fa-exclamation-triangle" style="color:red;"></i></small>
						<?php }else{ 
						
						
						
							$get_latest_closed_dispute_record = array();
							if($project_data['project_type'] == 'fixed' || $project_data['project_type'] == 'hourly'){
								$get_latest_closed_dispute_record = get_latest_project_closed_dispute($project_data['project_type'],array('disputed_project_id'=>$incomplete_bidder_data['project_id'],'sp_winner_id_of_disputed_project'=>$incomplete_bidder_data['winner_id'])); 
							}
							
							
							if(!empty($get_latest_closed_dispute_record) && ($get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_sp' || $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_po' || $get_latest_closed_dispute_record['dispute_status'] == 'automatic_decision' || $get_latest_closed_dispute_record['dispute_status'] == 'parties_agreement')){
							?>
							<small><i class="fas fa-balance-scale"></i></small>
							<?php	
							}if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['sp_winner_id_of_disputed_project']){
							?>
							<small><i class="fas fa-balance-scale-left"></i></small>
							<?php	
							}
							if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['project_owner_id_of_disputed_project']){ ?>
							<small><i class="fas fa-balance-scale-right"></i></small>	
							<?php	
							}
						 }	
						?>
						
					</div>
					<?php
						//$initial_bid_description = limitStringShowMoreLess($initial_bid_description);
						$descLeng	=	strlen($initial_bid_description);
						/*----------- description show for desktop screen start----*/
						$desktop_cnt            =	0;
						if($descLeng <= $this->config->item('project_details_page_bidder_listing_bid_description_character_limit_desktop')) {
							$desktop_description	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($initial_bid_description, ENT_QUOTES)));
						} else {
							$desktop_description	= character_limiter($initial_bid_description,$this->config->item('project_details_page_bidder_listing_bid_description_character_limit_desktop'));
							$desktop_restdescription	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($initial_bid_description, ENT_QUOTES)));
							$desktop_cnt = 1;
						}
						/*----------- description show for desktop screen end----*/

						/*----------- description show for ipad screen start----*/
						$tablet_cnt            =	0;
						if($descLeng <= $this->config->item('project_details_page_bidder_listing_bid_description_character_limit_tablet')) {
							$tablet_description	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($initial_bid_description, ENT_QUOTES)));
						} else {
							$tablet_description	= character_limiter($initial_bid_description,$this->config->item('project_details_page_bidder_listing_bid_description_character_limit_tablet'));
							$tablet_restdescription	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($initial_bid_description, ENT_QUOTES)));
							$tablet_cnt = 1;
						}
						/*----------- description show for ipad screen end----*/

						/*----------- description show for mobile screen start----*/
						$mobile_cnt            =	0;
						if($descLeng <= $this->config->item('project_details_page_bidder_listing_bid_description_character_limit_mobile')) {
							$mobile_description	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($initial_bid_description, ENT_QUOTES)));
						} else {
							$mobile_description	= character_limiter($initial_bid_description,$this->config->item('project_details_page_bidder_listing_bid_description_character_limit_mobile'));
							$mobile_restdescription	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($initial_bid_description, ENT_QUOTES)));
							$mobile_cnt = 1;
						}
						/*----------- description show for mobile screen end----*/
						?>
						<div class="default_user_description desktop-secreen">
							<p id="desktop_lessD<?php echo $incomplete_bidder_data['winner_id']; ?>">
								<?php echo $desktop_description;?>
								<?php if($desktop_cnt==1) {?>
								<span id="desktop_dotsD<?php echo $incomplete_bidder_data['winner_id']; ?>"></span>
								<button onclick="showMoreDescription('desktop', <?php echo $incomplete_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
								<?php } ?></p>
							<p id="desktop_moreD<?php echo $incomplete_bidder_data['winner_id']; ?>" class="moreD">
								<?php echo $desktop_restdescription;?>
								<button onclick="showMoreDescription('desktop', <?php echo $incomplete_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
							</p></div>
						<div class="default_user_description ipad-screen">
							<p id="tablet_lessD<?php echo $incomplete_bidder_data['winner_id']; ?>">
								<?php echo $tablet_description;?>
								<?php if($tablet_cnt==1) {?>
								<span id="tablet_dotsD<?php echo $incomplete_bidder_data['winner_id']; ?>"></span>
								<button onclick="showMoreDescription('tablet', <?php echo $incomplete_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
								<?php } ?></p>
							<p id="tablet_moreD<?php echo $incomplete_bidder_data['winner_id']; ?>" class="moreD">
								<?php echo $tablet_restdescription;?>
								<button onclick="showMoreDescription('tablet', <?php echo $incomplete_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
							</p></div>
						<div class="default_user_description mobile-screen">
							<p id="mobile_lessD<?php echo $incomplete_bidder_data['winner_id']; ?>">
								<?php echo $mobile_description;?>
								<?php if($mobile_cnt==1) {?>
								<span id="mobile_dotsD<?php echo $incomplete_bidder_data['winner_id']; ?>"></span>
								<button onclick="showMoreDescription('mobile', <?php echo $incomplete_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
								<?php } ?></p>
							<p id="mobile_moreD<?php echo $incomplete_bidder_data['winner_id']; ?>" class="moreD">
								<?php echo $mobile_restdescription;?>
								<button onclick="showMoreDescription('mobile', <?php echo $incomplete_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
							</p></div>
					<?php
					/* <?php
					$initial_bid_description = limitStringShowMoreLess($initial_bid_description);
					?>
					<div class="default_user_description">
										<p>
						<?php echo htmlspecialchars($initial_bid_description['first_text'], ENT_QUOTES); ?>
										</p>
					<?php if(isset($initial_bid_description['second_text'])){ ?>
					<div  class="collapse clearfix details-1" >
						<p>
							<?php
							echo nl2br(htmlspecialchars($initial_bid_description['second_text'], ENT_QUOTES));
							?>
						</p>
					</div>
					<div class="text-center">
					<button type="button" class="btn opSL opShLsbtn"data-toggle="collapse" data-target=".details-1" data-text-alt="<?php echo $this->config->item('show_less_txt'); ?> <i class='fas fa-angle-up'></i>"><?php echo $this->config->item('show_more_txt'); ?> <i class="fas fa-angle-down"></i></button>
					</div>	
					<?php
					}
					?>
					</div> */
					?>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		<?php
		}?>
		
	</div>
</div>