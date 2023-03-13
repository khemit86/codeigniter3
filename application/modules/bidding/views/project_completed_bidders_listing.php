<?php
$user = $this->session->userdata ('user');
//$name = $completed_bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $completed_bidder_data['first_name'] . ' ' . $completed_bidder_data['last_name'] : $completed_bidder_data['company_name'];


$name = (($completed_bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($completed_bidder_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $completed_bidder_data['is_authorized_physical_person'] == 'Y'))? $completed_bidder_data['first_name'] . ' ' . $completed_bidder_data['last_name'] : $completed_bidder_data['company_name'];


$bid_value = '';
$completed_amount_txt = '';
$project_value = '';
$sp_completed_projects = 0;
$sp_rating = $completed_bidder_data['project_user_total_avg_rating_as_sp'];
$sp_total_reviews = $completed_bidder_data['project_user_total_reviews'];
if(isset($completed_bidder_data['sp_total_completed_fixed_budget_projects']) && isset($completed_bidder_data['sp_total_completed_hourly_based_projects'])){
	$sp_completed_projects = $completed_bidder_data['sp_total_completed_fixed_budget_projects']+$completed_bidder_data['sp_total_completed_hourly_based_projects'];
}
if($sp_total_reviews == 0){
	$trGiven = number_format($sp_total_reviews,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
}else if($sp_total_reviews == 1) {
	$trGiven = number_format($sp_total_reviews,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
} else if($sp_total_reviews > 1) {
	$trGiven = number_format($sp_total_reviews,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
}

if($project_data['project_type'] == 'fixed'){
	
	$initial_bid_description = $completed_bidder_data['initial_bid_description'];	
	if($this->session->userdata ('user') && $completed_bidder_data['winner_id'] == $user[0]->user_id){
		$completed_amount_txt= $this->config->item('fixed_budget_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view');
	}else{
		$completed_amount_txt= $this->config->item('fixed_budget_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view');
	}
	if(floatval($completed_bidder_data['total_project_amount']) != 0){
		$project_value= $this->config->item('fixed_or_hourly_project_value').'<span class="touch_line_break">'.number_format($completed_bidder_data['total_project_amount'], 0, '', ' ')." ".CURRENCY.'</span>';
	}
	
}
else if($project_data['project_type'] == 'hourly'){
	$initial_bid_description = $completed_bidder_data['initial_bid_description'];
	$completed_amount_txt = $this->config->item('project_details_page_bidder_listing_bidded_hourly_rate_txt');
}
/* else if($project_data['project_type'] == 'fulltime'){
	$initial_bid_description = $completed_bidder_data['initial_application_description'];
	
	$completed_amount_txt = $this->config->item('project_details_page_bidder_listing_expected_salary_txt');
	
} */

if($completed_bidder_data['bidding_dropdown_option'] == 'NA'){
	if($project_data['project_type'] == 'fixed'){
		
		$bid_value = '<small><i class="far fa-credit-card"></i>'.$completed_amount_txt.'<span class="touch_line_break">'.number_format($completed_bidder_data['initial_bid_value'], 0, '', ' ')." ".CURRENCY.'</span></small>';
		
	}else if($project_data['project_type'] == 'hourly'){
		$total_bid_value = floatval($completed_bidder_data['initial_project_agreed_value']);
		
		
		
		if(floatval($completed_bidder_data['initial_project_agreed_number_of_hours']) == 1){
			$bidder_listing_details_hour_txt = $this->config->item('1_hour');
		}else if(floatval($completed_bidder_data['initial_project_agreed_number_of_hours']) >=2 && floatval( $completed_bidder_data['initial_project_agreed_number_of_hours']) <= 4){
			$bidder_listing_details_hour_txt = $this->config->item('2_4_hours');
		}else if(floatval($completed_bidder_data['initial_project_agreed_number_of_hours']) >4){
			$bidder_listing_details_hour_txt = $this->config->item('more_than_or_equal_5_hours');
		}
		
		
		
		$bid_value = '<small><i class="fas fa-stopwatch"></i>'.$completed_amount_txt.'<span class="touch_line_break">'.number_format($completed_bidder_data['initial_project_agreed_hourly_rate'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_hour').'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').'<span class="touch_line_break">'.$completed_bidder_data['initial_project_agreed_number_of_hours'].' '.$bidder_listing_details_hour_txt.'</span></small>';


		if($total_paid_amount[$completed_bidder_data['winner_id']] > $total_bid_value) {
			$total_paid = $total_paid_amount[$completed_bidder_data['winner_id']];
		} else {
			$total_paid = $total_bid_value;
		}

		$bid_value .= '<small><i class="fas fa-coins"></i>'.$this->config->item('fixed_or_hourly_project_value'). '<span class="touch_line_break total_paid_amount'.$completed_bidder_data['winner_id'].'">'.number_format($total_paid, 0, '', ' ').' '.CURRENCY.'</span></small>';
		
	}/* else if($project_data['project_type'] == 'fulltime'){
		$bid_value = '<small><i class="far fa-credit-card"></i>'.$completed_amount_txt.number_format($completed_bidder_data['initial_fulltime_project_agreed_salary'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_month').'</small>' ;
	} */
} else if ($completed_bidder_data['bidding_dropdown_option'] == 'to_be_agreed'){

	if($project_data['project_type'] == 'hourly'){
		if($this->session->userdata ('user') && $user[0]->user_id == $completed_bidder_data['project_owner_id']) { 
			$bid_value = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
		} else {
			$bid_value = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
		}

		$total_paid = $total_paid_amount[$completed_bidder_data['winner_id']];
		$total_paid_display = 'inline-block';
		if($total_paid == 0) {
			$total_paid_display = 'none';
		}

		$bid_value .= '<small style="display:'.$total_paid_display.'"><i class="fas fa-coins"></i>'.$this->config->item('fixed_or_hourly_project_value'). '<span class="touch_line_break total_paid_amount'.$completed_bidder_data['winner_id'].'">'.number_format($total_paid, 0, '', ' ').' '.CURRENCY.'</span></small>';
	} else {
		$bid_value = '<small><i class="far fa-credit-card"></i>'.$completed_amount_txt.'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
	}
} else if ($completed_bidder_data['bidding_dropdown_option'] == 'confidential'){
	if($project_data['project_type'] == 'hourly'){
		if($this->session->userdata ('user') && $user[0]->user_id == $completed_bidder_data['project_owner_id']) { 
			$bid_value = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';
		} else {
			$bid_value = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';
		}
	
		$total_paid = $total_paid_amount[$completed_bidder_data['winner_id']];
		$total_paid_display = 'inline-block';
		if($total_paid == 0) {
			$total_paid_display = 'none';
		}

		$bid_value .= '<small style="display:'.$total_paid_display.'"><i class="fas fa-coins"></i>'.$this->config->item('fixed_or_hourly_project_value'). '<span class="touch_line_break total_paid_amount'.$completed_bidder_data['winner_id'].'">'.number_format($total_paid, 0, '', ' ').' '.CURRENCY.'</span></small>';
	} else {
		$bid_value = '<small><i class="far fa-credit-card"></i>'.$completed_amount_txt.'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';
	}
}
?>



<div class="freeBid completed_section" id="<?php echo $project_data['project_type'] == 'fulltime' ? "completed_section_".$completed_bidder_data['employee_id'] : "completed_section_".$completed_bidder_data['winner_id']; ?>">
	<div class="fLancerbidding">
		<?php
		if($this->session->userdata ('user') && ($user[0]->user_id == $completed_bidder_data['winner_id'] || $user[0]->user_id == $completed_bidder_data['project_owner_id'])){
		?>
		<div class="user_details_in_progress_bids">
			<div class="opLBttm opBg">
				<div class="sRate completedBids">
					<span class="default_user_name inProgress_title"><a class="default_user_name_link" href="<?php echo site_url ($completed_bidder_data['profile_name']); ?>"><?php echo $name; ?></a></span><span class="inProgress_ratting"><span class="inProgress_rating_details"><?php echo show_dynamic_rating_stars($sp_rating,'small'); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $sp_rating;?></small></span><small class="default_avatar_total_review"><?php echo $trGiven; ?></small></span>
				</div>
				<!-- <div class="default_user_name">
				  <a class="default_user_name_link" href="<?php //echo site_url ($completed_bidder_data['profile_name']); ?>"><?php //echo $name; ?></a>
				</div> -->
				<p class="default_short_details_field inBids_bdr">
					<?php
						/* if($project_data['project_type'] == 'fulltime') {
							$start_date_label = $this->config->item('fulltime_project_hired_sp_employment_start_date').date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($completed_bidder_data['project_winner_work_start_date']));
						} */
						if($project_data['project_type'] == 'fixed') {
							$start_date_label = $this->config->item('completed_bidding_listing_project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($completed_bidder_data['project_winner_work_start_date'])).'</span>';
						} else if($project_data['project_type'] == 'hourly') {
							$start_date_label = $this->config->item('completed_bidding_listing_hourly_rate_based_project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($completed_bidder_data['project_winner_work_start_date'])).'</span>';
						}
					?><small><i class="far fa-clock"></i><?php echo $start_date_label; ?></small><?php
						/* if($project_data['project_type'] == 'fulltime'){
							$end_date_label = $this->config->item('completed_bidding_listing_fulltime_project_employment_completion_date').date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($completed_bidder_data['project_winner_work_completion_date']));
						} else { */
							$end_date_label = $this->config->item('completed_bidding_listing_project_completion_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($completed_bidder_data['project_winner_work_completion_date'])).'</span>';
						//}
						if($completed_bidder_data['project_completion_method'] == 'outside_portal'  && $completed_bidder_data['winner_id'] != $user[0]->user_id){
							$end_date_label .= " (".$this->config->item('project_details_page_project_marked_as_complete_snippet_txt_sp_view').")";
						}
					?><small><i class="fa fa-clock-o project_expired_or_cancel_or_completed_date_icon_size"></i><?php echo $end_date_label; ?></small><?php
						if($this->session->userdata ('user') && ($completed_bidder_data['winner_id'] == $user[0]->user_id || $completed_bidder_data['project_owner_id'] == $user[0]->user_id )){
						?><?php echo $bid_value; ?><?php
						}
						if(!empty($project_value) && $this->session->userdata ('user') && ($completed_bidder_data['winner_id'] == $user[0]->user_id || $completed_bidder_data['project_owner_id'] == $user[0]->user_id )){
						?><small><i class="fas fa-coins"></i><span class="project_value"><?php echo $project_value; ?></span></small><?php
						}
						if($completed_bidder_data['total_sp_active_dispute_count'] > 0){ ?><small><i class="fas fa-exclamation-triangle" style="color:red;"></i></small><?php }else{
							$get_latest_closed_dispute_record = array();
							if($project_data['project_type'] == 'fixed' || $project_data['project_type'] == 'hourly' ){
								$get_latest_closed_dispute_record = get_latest_project_closed_dispute($project_data['project_type'],array('disputed_project_id'=>$completed_bidder_data['project_id'],'sp_winner_id_of_disputed_project'=>$completed_bidder_data['winner_id'])); 
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
						$default_Threecheckbox_button = 'default_Threecheckbox_button';
					if($completed_bidder_data['project_completion_method'] == 'via_portal'){
						$default_Threecheckbox_button = '';
					}
					?>
					<div class="default_checkbox_button radio_bttmBdr <?php echo $default_Threecheckbox_button; ?>" id="<?php echo "tab_container_".$completed_bidder_data['winner_id']; ?>">
							<span class="singleLine_chkBtn small_radio_btn">
								<input data-id ="<?php echo $completed_bidder_data['winner_id']; ?>" data-target="description<?php echo $completed_bidder_data['winner_id']; ?>" type="checkbox" id="description_radio<?php echo $completed_bidder_data['winner_id'] ?>" name="completed_mainRadio" value="description" class="project_detail_tab location_option chk-btn" data-sp-id="<?php echo Cryptor::doEncrypt($completed_bidder_data['winner_id']);?>" data-po-id="<?php echo Cryptor::doEncrypt($completed_bidder_data['project_owner_id']); ?>" data-section-name="completed" data-section-id="<?php echo $completed_bidder_data['winner_id'];?>">
								<label class="singleLine_radioBtn" for="description_radio<?php echo $completed_bidder_data['winner_id'] ?>">
									<span><?php echo $this->config->item('project_details_page_description_section_tab');?></span>
								</label>
							</span>
							<span  class="singleLine_chkBtn small_radio_btn">
								<input data-target="milestone<?php echo $completed_bidder_data['winner_id']; ?>" data-project-id="<?php echo $completed_bidder_data['project_id']; ?>" data-id = "<?php echo $completed_bidder_data['winner_id']; ?>"  type="checkbox" id="payments_radio<?php echo $completed_bidder_data['winner_id'] ?>" name="completed_mainRadio" value="payments" class="project_detail_tab location_option chk-btn" data-sp-id="<?php echo Cryptor::doEncrypt($completed_bidder_data['winner_id']);?>" data-po-id="<?php echo Cryptor::doEncrypt($completed_bidder_data['project_owner_id']); ?>" data-section-name="completed" data-section-id="<?php echo $completed_bidder_data['winner_id'];?>">
								<label class="singleLine_radioBtn" for="payments_radio<?php echo $completed_bidder_data['winner_id'] ?>">
									<span><?php echo $this->config->item('project_details_page_payment_management_section_tab');?></span>
								</label>
							</span>
							<span  class="singleLine_chkBtn small_radio_btn" >
							<?php
								if($this->session->userdata ('user') && $user[0]->user_id == $completed_bidder_data['project_owner_id']) {
									$receiver_id = $completed_bidder_data['winner_id'];
								} else {
									$receiver_id = $completed_bidder_data['project_owner_id'];
								}
							?>
								<input data-target="messages<?php echo $completed_bidder_data['winner_id']; ?>" data-project-title="<?php echo $project_data['project_title'] ?>" data-project-owner="<?php echo $completed_bidder_data['project_owner_id']; ?>" data-receiver-id="<?php echo $receiver_id; ?>" data-project-id="<?php echo $completed_bidder_data['project_id']; ?>" data-id="<?php echo $receiver_id; ?>" type="checkbox" id="messages_radio<?php echo $completed_bidder_data['winner_id']; ?>" name="completed_mainRadio" value="messages" class="project_detail_tab location_option chk-btn messagesTab" data-sp-id="<?php echo Cryptor::doEncrypt($completed_bidder_data['winner_id']);?>" data-po-id="<?php echo Cryptor::doEncrypt($completed_bidder_data['project_owner_id']); ?>" data-section-name="completed" data-section-id="<?php echo $completed_bidder_data['winner_id'];?>">
								<label class="singleLine_radioBtn" for="messages_radio<?php echo $completed_bidder_data['winner_id']; ?>">
									<span class="mr-0"><?php echo $this->config->item('project_details_page_messages_section_tab');?></span>
									<span class="default_counter_notification_red badge custom_badge ml-1 <?php echo $completed_project_chat_unread_messages_count[$completed_bidder_data['winner_id']] == 0 ? 'd-none' : ''; ?>"><?php echo $completed_project_chat_unread_messages_count[$completed_bidder_data['winner_id']] < 99 ? $completed_project_chat_unread_messages_count[$completed_bidder_data['winner_id']] : '99+'; ?></span>
								</label>
								</span>
							<?php
							if($completed_bidder_data['project_completion_method'] == 'via_portal'){
							?>
							<span  class="singleLine_chkBtn small_radio_btn" >
								<input data-target="feedback<?php echo $completed_bidder_data['winner_id']; ?>" data-id="<?php echo $completed_bidder_data['winner_id']; ?>" type="checkbox" id="feedback_radio<?php echo $completed_bidder_data['winner_id'] ?>" name="completed_mainRadio" value="feedback" class="project_detail_tab location_option chk-btn feedback_tab" data-sp-id="<?php echo Cryptor::doEncrypt($completed_bidder_data['winner_id']);?>" data-po-id="<?php echo Cryptor::doEncrypt($completed_bidder_data['project_owner_id']); ?>" data-section-name="completed" data-section-id="<?php echo $completed_bidder_data['winner_id'];?>">
								<label class="singleLine_radioBtn" for="feedback_radio<?php echo $completed_bidder_data['winner_id'] ?>">
									<span><?php echo $this->config->item('project_details_page_feedback_section_tab');?></span>
								</label>
							</span>
							<?php
							}
							?>
					</div>
					
					<!-- here start the tab data html structure start -->
					<div class="inprogressTab <?php echo "project_detail_tab_container".$completed_bidder_data['winner_id'] ?>" id="description<?php echo $completed_bidder_data['winner_id'] ?>" style="display: none;">
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
							<p id="desktop_lessD<?php echo $completed_bidder_data['winner_id']; ?>">
								<?php echo $desktop_description;?><?php if($desktop_cnt==1) {?><span id="desktop_dotsD<?php echo $completed_bidder_data['winner_id']; ?>"></span><button onclick="showMoreDescription('desktop', <?php echo $completed_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
								<?php } ?></p><p id="desktop_moreD<?php echo $completed_bidder_data['winner_id']; ?>" class="moreD"><?php echo $desktop_restdescription;?><button onclick="showMoreDescription('desktop', <?php echo $completed_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button></p></div>
						<div class="default_user_description project_list_tab_desc ipad-screen">
							<p id="tablet_lessD<?php echo $completed_bidder_data['winner_id']; ?>">
								<?php echo $tablet_description;?>
								<?php if($tablet_cnt==1) {?><span id="tablet_dotsD<?php echo $completed_bidder_data['winner_id']; ?>"></span><button onclick="showMoreDescription('tablet', <?php echo $completed_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button><?php } ?></p><p id="tablet_moreD<?php echo $completed_bidder_data['winner_id']; ?>" class="moreD"><?php echo $tablet_restdescription;?><button onclick="showMoreDescription('tablet', <?php echo $completed_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button></p></div>
						<div class="default_user_description project_list_tab_desc mobile-screen">
							<p id="mobile_lessD<?php echo $completed_bidder_data['winner_id']; ?>">
								<?php echo $mobile_description;?><?php if($mobile_cnt==1) {?><span id="mobile_dotsD<?php echo $completed_bidder_data['winner_id']; ?>"></span><button onclick="showMoreDescription('mobile', <?php echo $completed_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button><?php } ?></p><p id="mobile_moreD<?php echo $completed_bidder_data['winner_id']; ?>" class="moreD"><?php echo $mobile_restdescription;?><button onclick="showMoreDescription('mobile', <?php echo $completed_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button></p></div>
						<?php
						if(isset($completed_bidder_data['bid_attachments']) && !empty($completed_bidder_data['bid_attachments'])){
						?>
							
							<div class="acCo projectAttachment">
								<div class="row">
								<div class="col-md-12 col-sm-12 col-12">
								<?php
								foreach($completed_bidder_data['bid_attachments'] as $bid_attachment_value){
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
					<div class="inprogressTab <?php echo "project_detail_tab_container".$completed_bidder_data['winner_id'] ?>" id="milestone<?php echo $completed_bidder_data['winner_id'] ?>" style="display: none;">
					
						<!--<div class="default_radio_button">
						<div data-toggle="tooltip" data-placement="top" title="<?php echo $requested_payment_tab_tooltip_msg; ?>">
						<a data-toggle="tab" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $bid_id; ?>" data-tab-type = "requested_milestone" data-section-name="<?php echo $section_name; ?>" data-target="<?php echo "#".$section_name."RqstPym".$bid_id ?>"><label for="incoming_payment_requests">
								<span><?php echo $requested_payment_tab; ?></span>
							</label></a>
						</div>
						</div>-->
						<?php
						// this condition will execute when bidder is login then his milestone section will show
						
						if($this->session->userdata ('user') && $user[0]->user_id == $completed_bidder_data['winner_id']){
							echo $this->load->view('escrow/sp_escrows_section_project_detail',array('winner_id'=>$completed_bidder_data['winner_id'],'project_owner_id'=>$completed_bidder_data['project_owner_id'],'project_id'=>$completed_bidder_data['project_id'],'project_type'=>$project_data['project_type'],'sp_id'=> $completed_bidder_data['winner_id'],'po_id'=> $completed_bidder_data['project_owner_id'],'project_owner_name'=>$project_owner_name,'bid_id'=>$completed_bidder_data['id'], 'completed_bidder_data' => $completed_bidder_data, 'section_name'=>'completed'), true);
						}
						// this condition will execute when project owner is login then his milestone section will show
						if($this->session->userdata ('user') && $user[0]->user_id == $completed_bidder_data['project_owner_id']){
							echo $this->load->view('escrow/po_escrows_section_project_detail',array('winner_id'=>$completed_bidder_data['winner_id'],'project_owner_id'=>$completed_bidder_data['project_owner_id'],'project_id'=>$completed_bidder_data['project_id'],'project_type'=>$project_data['project_type'],'sp_id'=> $completed_bidder_data['winner_id'],'po_id'=> $completed_bidder_data['project_owner_id'],'project_owner_name'=>$project_owner_name,'bid_id'=>$completed_bidder_data['id'],'service_provider_name'=>$name,'completed_bidder_data' => $completed_bidder_data,'section_name'=>'completed'), true);
						}
						?>
					
					
					</div>
					<?php
						if($this->session->userdata ('user') && $user[0]->user_id == $completed_bidder_data['project_owner_id']) {
							$receiver_id = $completed_bidder_data['winner_id'];
						} else {
							$receiver_id = $completed_bidder_data['project_owner_id'];
						}
					?>
					<div class="inprogressTab <?php echo "project_detail_tab_container".$completed_bidder_data['winner_id'] ?>" id="messages<?php echo $completed_bidder_data['winner_id'] ?>" style="display: none;">
						<?php echo $this->load->view('bidding/message_tab_information', ['project_status' => 'completed', 'comp_inprogress_bidder_data' => $completed_bidder_data, 'comp_profile_pic' => $completed_profile_pic, 'user' => $user ], true); ?>
					</div>
					<?php
						$view_type = '';
						if($this->session->userdata ('user') && $user[0]->user_id == $completed_bidder_data['project_owner_id']) {
							$view_type = "po";
						} else {
							$view_type = 'sp';
							$receiver_id = $completed_bidder_data['project_owner_id'];
						}
					?>
					<div class="inprogressTab <?php echo "project_detail_tab_container".$completed_bidder_data['winner_id'] ?>" id="feedback<?php echo $completed_bidder_data['winner_id'] ?>" style="display: none;">
						<div class="feedbackTab">
							<ul class="nav nav-tabs">
								<li class="nav-item">
									<a style="cursor:pointer" id="feedback_recieved_completed_<?php echo $completed_bidder_data['winner_id'] ?>" class="nav-link review_rating_feedback_tab active feedback_recieved" data-toggle="tab"  data-target="#fReceived_completed<?php echo $completed_bidder_data['winner_id'] ?>" data-view-type = "<?php echo $view_type;?>" data-project-type = "<?php echo $project_data['project_type']; ?>" data-section-name="completed"  data-section-id="<?php echo $completed_bidder_data['winner_id']; ?>"  data-project-id="<?php echo $completed_bidder_data['project_id']; ?>" data-sp-id = "<?php echo Cryptor::doEncrypt($completed_bidder_data['winner_id']); ?>" data-po-id = "<?php echo Cryptor::doEncrypt($completed_bidder_data['project_owner_id']); ?>" data-tab-type="feedback_received"><?php echo $this->config->item('projects_users_ratings_feedbacks_received_tab_project_detail'); ?></a>
								</li>
								<li class="nav-item">
									<a style="cursor:pointer" id="feedback_given_completed_<?php echo $completed_bidder_data['winner_id'] ?>" class="nav-link review_rating_feedback_tab feedback_given" data-toggle="tab" data-target="#fGiven_completed<?php echo $completed_bidder_data['winner_id'] ?>" data-view-type = "<?php echo $view_type;?>" data-project-type = "<?php echo $project_data['project_type']; ?>" data-section-name="completed"  data-section-id="<?php echo $completed_bidder_data['winner_id']; ?>"  data-project-id="<?php echo $completed_bidder_data['project_id']; ?>" data-sp-id = "<?php echo Cryptor::doEncrypt($completed_bidder_data['winner_id']); ?>" data-po-id = "<?php echo Cryptor::doEncrypt($completed_bidder_data['project_owner_id']); ?>" data-tab-type="feedback_given"><?php echo $this->config->item('projects_users_ratings_feedbacks_given_tab_project_detail'); ?></a>
								</li>
							</ul>

							<!-- Tab panes -->
							<div class="tab-content">
								<div class="tab-pane active" id="fReceived_completed<?php echo $completed_bidder_data['winner_id'] ?>">
								<?php 
									$feedback_data = get_received_feedback_tab_data_project_detail(array('login_user_id'=>$user[0]->user_id,'view_type'=>$view_type,'project_type'=>$project_data['project_type'],'po_id'=>$completed_bidder_data['project_owner_id'],'sp_id'=>$completed_bidder_data['winner_id'],'project_id'=>$completed_bidder_data['project_id']));
									
									
									echo $this->load->view('users_ratings_feedbacks/users_ratings_feedbacks_received_section_project_detail_page',['project_type'=>$project_data['project_type'],'view_type'=>$view_type,'sp_id'=>$completed_bidder_data['winner_id'],'po_id'=> $completed_bidder_data['project_owner_id'],'check_receiver_received_rating'=>$feedback_data['check_receiver_received_rating'],'check_receiver_view_his_rating'=>$feedback_data['check_receiver_view_his_rating'],'feedback_given_msg'=>$feedback_data['feedback_given_msg'],'feedback_data'=>$feedback_data['feedback_data'],'po_name'=>$feedback_data['po_name'],'sp_name'=>$feedback_data['sp_name'],'other_party_given_feedback_msg'=>$feedback_data['other_party_given_feedback_msg'],'other_party_not_given_feedback_msg'=>$feedback_data['other_party_not_given_feedback_msg'],'sp_is_authorized_physical_person'=>$feedback_data['sp_is_authorized_physical_person'],'sp_gender'=>$feedback_data['sp_gender'],'sp_account_type'=>$feedback_data['sp_account_type'],'po_is_authorized_physical_person'=>$feedback_data['po_is_authorized_physical_person'],'po_gender'=>$feedback_data['po_gender'],'po_account_type'=>$feedback_data['po_account_type'],'section_id'=>$completed_bidder_data['winner_id']], true); ?>
								</div>
								<div class="tab-pane fade" id="fGiven_completed<?php echo $completed_bidder_data['winner_id'] ?>">
									
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
						if(!empty($completed_bidder_data['user_avatar']) ) {
						$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$completed_bidder_data['profile_name'].USER_AVATAR.$completed_bidder_data['user_avatar'];
						} else {
							
							if(($completed_bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($completed_bidder_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $completed_bidder_data['is_authorized_physical_person'] == 'Y')){	
								
								if($completed_bidder_data['gender'] == 'M'){
									$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
								}if($completed_bidder_data['gender'] == 'F'){
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
						<small class="default_avatar_complete_project"><?php echo $this->config->item('project_details_page_user_completed_projects_as_sp')." ".number_format($sp_completed_projects,0, '.', ' '); ?></small><?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="user_details_right_adjust_project_owner">
			<div class="opLBttm opBg">
				<div class="default_user_name">
				  <a class="default_user_name_link" href="<?php echo site_url ($completed_bidder_data['profile_name']); ?>"><?php echo $name; ?></a>
				</div>
				<p class="bottom_border default_short_details_field">
					<small><i class="far fa-clock"></i><?php
					/* if($project_data['project_type'] == 'fulltime'){
						echo $this->config->item('fulltime_project_hired_sp_employment_start_date').date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($completed_bidder_data['project_winner_work_start_date']));
					}else{ */
						echo $this->config->item('completed_bidding_listing_project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($completed_bidder_data['project_winner_work_start_date'])).'</span>';
					//}?></small><small><i class="fa fa-clock-o project_expired_or_cancel_or_completed_date_icon_size"></i><?php
					/* if($project_data['project_type'] == 'fulltime'){
						echo $this->config->item('completed_bidding_listing_fulltime_project_employment_completion_date').date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($completed_bidder_data['project_winner_work_completion_date']));
					}else{ */
						echo $this->config->item('completed_bidding_listing_project_completion_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($completed_bidder_data['project_winner_work_completion_date'])).'</span>';
					//}?></small><?php
					if($completed_bidder_data['total_sp_active_dispute_count'] > 0){ ?><small><i class="fas fa-exclamation-triangle" style="color:red;"></i></small><?php }else{
						$get_latest_closed_dispute_record = array();
						if($project_data['project_type'] == 'fixed' || $project_data['project_type'] == 'hourly' ){
							$get_latest_closed_dispute_record = get_latest_project_closed_dispute($project_data['project_type'],array('disputed_project_id'=>$completed_bidder_data['project_id'],'sp_winner_id_of_disputed_project'=>$completed_bidder_data['winner_id'])); 
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
						<p id="desktop_lessD<?php echo $completed_bidder_data['winner_id']; ?>">
							<?php echo $desktop_description;?>
							<?php if($desktop_cnt==1) {?>
							<span id="desktop_dotsD<?php echo $completed_bidder_data['winner_id']; ?>"></span>
							<button onclick="showMoreDescription('desktop', <?php echo $completed_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
							<?php } ?></p>
						<p id="desktop_moreD<?php echo $completed_bidder_data['winner_id']; ?>" class="moreD">
							<?php echo $desktop_restdescription;?>
							<button onclick="showMoreDescription('desktop', <?php echo $completed_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
						</p></div>
					<div class="default_user_description ipad-screen">
						<p id="tablet_lessD<?php echo $completed_bidder_data['winner_id']; ?>">
							<?php echo $tablet_description;?>
							<?php if($tablet_cnt==1) {?>
							<span id="tablet_dotsD<?php echo $completed_bidder_data['winner_id']; ?>"></span>
							<button onclick="showMoreDescription('tablet', <?php echo $completed_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
							<?php } ?></p>
						<p id="tablet_moreD<?php echo $completed_bidder_data['winner_id']; ?>" class="moreD">
							<?php echo $tablet_restdescription;?>
							<button onclick="showMoreDescription('tablet', <?php echo $completed_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
						</p></div>
					<div class="default_user_description mobile-screen">
						<p id="mobile_lessD<?php echo $completed_bidder_data['winner_id']; ?>">
							<?php echo $mobile_description;?>
							<?php if($mobile_cnt==1) {?>
							<span id="mobile_dotsD<?php echo $completed_bidder_data['winner_id']; ?>"></span>
							<button onclick="showMoreDescription('mobile', <?php echo $completed_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
							<?php } ?></p>
						<p id="mobile_moreD<?php echo $completed_bidder_data['winner_id']; ?>" class="moreD">
							<?php echo $mobile_restdescription;?>
							<button onclick="showMoreDescription('mobile', <?php echo $completed_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
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
		<?php
		}
		?>
		<div class="clearfix"></div>
	</div>	
</div>

