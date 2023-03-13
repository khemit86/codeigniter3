<?php
$user = $this->session->userdata ('user');
if(!empty($active_bids_project_data)){
		$total_active_bids_projects = count($active_bids_project_data);
		$active_bid_counter = 1;
        foreach($active_bids_project_data as $active_bids_project_key => $active_bids_project_value){
		
		$location = '';
		$last_record_class_remove_border_bottom = '';
		$last_record_class = '';
		if($active_bid_counter == $total_active_bids_projects){
			$last_record_class_remove_border_bottom = 'bbNo';
			if($page_type == 'dashboard'  &&  $active_bids_project_count <= $this->config->item('user_dashboard_sp_view_active_bids_listing_limit')) {
				//$last_record_class = 'padding_bottom0';
			}	
			
			
		}
		if(!empty($active_bids_project_value['county_name'])){
		if(!empty($active_bids_project_value['locality_name'])){
			$location .= $active_bids_project_value['locality_name'];
		}
		if(!empty($active_bids_project_value['postal_code'])){
			$location .= '&nbsp;'.$active_bids_project_value['postal_code'] .',&nbsp;';
		}else if(!empty($active_bids_project_value['locality_name']) && empty($active_bids_project_value['postal_code'])){
			$location .= ',&nbsp';
		}
		$location .= $active_bids_project_value['county_name'];
		}

		$featured_max = 0;
		$urgent_max = 0;
		$expiration_featured_upgrade_date_array = array();
		$expiration_urgent_upgrade_date_array = array();
		if(!empty($active_bids_project_value['featured_upgrade_end_date'])){
			$expiration_featured_upgrade_date_array[] = $active_bids_project_value['featured_upgrade_end_date'];
		}
		if(!empty($active_bids_project_value['bonus_featured_upgrade_end_date'])){
			$expiration_featured_upgrade_date_array[] = $active_bids_project_value['bonus_featured_upgrade_end_date'];
		}
		if(!empty($active_bids_project_value['membership_include_featured_upgrade_end_date'])){
			$expiration_featured_upgrade_date_array[] = $active_bids_project_value['membership_include_featured_upgrade_end_date'];
		}
		if(!empty($expiration_featured_upgrade_date_array)){
			$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
		}
		
		##########
		
		if(!empty($active_bids_project_value['urgent_upgrade_end_date'])){
			$expiration_urgent_upgrade_date_array[] = $active_bids_project_value['urgent_upgrade_end_date'];
		}
		if(!empty($active_bids_project_value['bonus_urgent_upgrade_end_date'])){
			$expiration_urgent_upgrade_date_array[] = $active_bids_project_value['bonus_urgent_upgrade_end_date'];
		}
		if(!empty($active_bids_project_value['membership_include_urgent_upgrade_end_date'])){
			$expiration_urgent_upgrade_date_array[] = $active_bids_project_value['membership_include_urgent_upgrade_end_date'];
		}
		if(!empty($expiration_urgent_upgrade_date_array)){
			$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
		}
		$featured_class = '';
		if ($active_bids_project_value['featured'] == 'Y' && $featured_max > time()) {
			$featured_class = 'opBg';
		}
?>	
<div class="tabContent">
	 <div class="default_project_row <?php echo $featured_class; ?> <?php echo $last_record_class; ?>" id="<?php echo "active_bid_".$active_bids_project_value['project_id'] ?>">
		<div class="default_project_title">
			 <a class="default_project_title_link" target="_blank" href="<?php echo base_url().$this->config->item('project_detail_page_url')."?id=".$active_bids_project_value['project_id']; ?>"><?php echo htmlspecialchars($active_bids_project_value['project_title'], ENT_QUOTES);?></a>
		</div>
		<label class="default_short_details_field">
			<small><i class="fa fa-file-text-o"></i><?php
			if($active_bids_project_value['project_type'] == 'fixed'){
				echo $this->config->item('project_listing_window_snippet_fixed_budget_project');
			}else if($active_bids_project_value['project_type'] == 'hourly'){
				echo $this->config->item('project_listing_window_snippet_hourly_based_budget_project');
			}else if($active_bids_project_value['project_type'] == 'fulltime'){
				echo $this->config->item('project_listing_window_snippet_fulltime_project');
			}
			if($active_bids_project_value['confidential_dropdown_option_selected'] == 'Y'){
				if($active_bids_project_value['project_type'] == 'fixed'){ echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected').'</span>';
					}else if($active_bids_project_value['project_type'] == 'hourly'){ echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_confidential_option_selected').'</span>';
				}else if($active_bids_project_value['project_type'] == 'fulltime'){ echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_confidential_option_selected').'</span>';
				}
			}else if($active_bids_project_value['not_sure_dropdown_option_selected'] == 'Y'){
				if($active_bids_project_value['project_type'] == 'fixed'){
				echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected').'</span>';
				}else if($active_bids_project_value['project_type'] == 'hourly'){
				echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_not_sure_option_selected').'</span>';
				}else if($active_bids_project_value['project_type'] == 'fulltime'){
					echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_not_sure_option_selected').'</span>';
				}
			}else{
				$budget_range = '';
				if($active_bids_project_value['max_budget'] != 'All'){
					if($active_bids_project_value['project_type'] == 'hourly'){
						$budget_range = '';
						if($this->config->item('post_project_budget_range_between')){
							$budget_range .= $this->config->item('post_project_budget_range_between');
						}
						$budget_range .= '<span class="touch_line_break">'.number_format($active_bids_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($active_bids_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour').'</span>';
					}else if($active_bids_project_value['project_type'] == 'fulltime'){
						$budget_range = '';
						if($this->config->item('post_project_budget_range_between')){
							$budget_range .= $this->config->item('post_project_budget_range_between');
						}
						$budget_range .= '<span class="touch_line_break">'.number_format($active_bids_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($active_bids_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_month').'</span>';
					}else{
						$budget_range = '';
						if($this->config->item('post_project_budget_range_between')){
							$budget_range .= $this->config->item('post_project_budget_range_between');
						}
						$budget_range .= '<span class="touch_line_break">'.number_format($active_bids_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($active_bids_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
					}
				}else{
					if($active_bids_project_value['project_type'] == 'hourly'){
						$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($active_bids_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span>';
					}else if($active_bids_project_value['project_type'] == 'fulltime'){
						$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($active_bids_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span>';
					}else{
						$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($active_bids_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
					}
				}
				echo $budget_range;
			}
			if($active_bids_project_value['escrow_payment_method'] == 'Y') { echo '<span class="touch_line_break">'.$this->config->item('my_projects_project_payment_method_escrow_system').'</span>';
			}if($active_bids_project_value['offline_payment_method'] == 'Y'){
				echo '<span class="touch_line_break">'.$this->config->item('my_projects_project_payment_method_offline_system').'</span>';}?></small><small><i class="far fa-clock"></i><?php echo $active_bids_project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($active_bids_project_value['project_posting_date'])).'</span>' : $this->config->item('project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($active_bids_project_value['project_posting_date'])).'</span>';?></small><?php
				if($active_bids_project_value['project_type']== 'fulltime') {
					if($active_bids_project_value['project_status'] == 'cancelled') {
			?><small><i class="fa fa-clock-o project_expired_or_cancel_or_completed_date_icon_size"></i><?php echo $this->config->item('fulltime_project_cancelled_on_sp_view_myprojects_section').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($active_bids_project_value['project_cancellation_date'])).'</span>' ?></small><?php
					} else if($active_bids_project_value['project_status'] != 'cancelled' && strtotime($active_bids_project_value['project_expiration_date']) <= time()) {
			?><small><i class="fa fa-clock-o project_expired_or_cancel_or_completed_date_icon_size"></i><?php echo $this->config->item('fulltime_project_expired_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($active_bids_project_value['project_expiration_date'])).'</span>' ?></small><?php
					}
				}
			?><?php
			if(!empty($location)):
			?><small><i class="fas fa-map-marker-alt"></i><?php echo $location;?></small><?php
			endif;
			?><small><i class="fas fa-bullhorn"></i><?php $project_bid_count = get_project_bid_count($active_bids_project_value['project_id'],$active_bids_project_value['project_type']);
			$bid_history_total_bids = $project_bid_count."&nbsp;";
			if ($active_bids_project_value['project_type'] == 'fulltime') {
				 if($project_bid_count == 0){
					$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_0_applications_received');
				}else if($project_bid_count == 1){
					$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_1_application_received');
				}else if($project_bid_count >= 2 && $project_bid_count <= 4){
					$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_2_to_4_applications_received');
				}else {
					$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_5_or_more_applications_received');
				}
			} else {
			   if($project_bid_count == 0){
				$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_0_bids_received');
				}else if($project_bid_count == 1){
					$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_1_bid_received');
				}else if($project_bid_count >=2 && $project_bid_count <=4){
					$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_2_to_4_bids_received');
				}else {
					$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_5_or_more_bids_received');
				}
			}echo $bid_history_total_bids;?></small></label>
	
		<label class="default_short_details_field margin_top0"><?php
		$bidded_amount_txt = '';
		if($active_bids_project_value['project_type'] == 'fixed'){
			if($this->session->userdata ('user') && $active_bids_project_value['project_owner_id'] == $user[0]->user_id){ 
				$bidded_amount_txt= $this->config->item('fixed_budget_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view');
			} else {
				$bidded_amount_txt= $this->config->item('fixed_budget_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view');
			}
		}else if($active_bids_project_value['project_type'] == 'hourly'){
			$bidded_amount_txt = $this->config->item('project_details_page_bidder_listing_bidded_hourly_rate_txt');
		}
		else if($active_bids_project_value['project_type'] == 'fulltime'){
			$bidded_amount_txt = $this->config->item('project_details_page_bidder_listing_expected_salary_txt');
		}
		if($active_bids_project_value['project_type'] == 'fulltime'){
			$bidding_date = $this->config->item('application_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($active_bids_project_value['bid_date'])).'</span>';
		}else{
			$bidding_date = $this->config->item('bid_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($active_bids_project_value['bid_date'])).'</span>';
		}
		if($active_bids_project_value['bidding_dropdown_option'] == 'NA'){
			if($active_bids_project_value['project_type'] == 'fixed'){
				if(floatval($active_bids_project_value['project_delivery_period']) == 1){
					$bidder_listing_details_fixed_txt = $this->config->item('1_day');
				}else if(floatval($active_bids_project_value['project_delivery_period']) >=2 && floatval($active_bids_project_value['project_delivery_period']) <= 4){
					$bidder_listing_details_fixed_txt = $this->config->item('2_4_days');
				}else if(floatval($active_bids_project_value['project_delivery_period']) >4){
					$bidder_listing_details_fixed_txt = $this->config->item('more_than_or_equal_5_days');
				}$bid_amount ='<small><i class="far fa-credit-card"></i>'.$bidded_amount_txt.'<span class="touch_line_break">'.number_format($active_bids_project_value['initial_bidded_amount'], 0, '', ' ')." ".CURRENCY.'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in'). str_replace(".00","",number_format($active_bids_project_value['project_delivery_period'],  2, '.', ' ')) .' '.$bidder_listing_details_fixed_txt.'</small>';
			}else if($active_bids_project_value['project_type'] == 'hourly'){
				$total_bid_value = floatval($active_bids_project_value['initial_bidded_amount']) * floatval($active_bids_project_value['project_delivery_period']);
				if(floatval($active_bids_project_value['project_delivery_period']) == 1){
					$bidder_listing_details_hour_txt = $this->config->item('1_hour');
				}else if(floatval($active_bids_project_value['project_delivery_period']) >=2 && floatval($active_bids_project_value['project_delivery_period']) <= 4){
					$bidder_listing_details_hour_txt = $this->config->item('2_4_hours');
				}else if(floatval($active_bids_project_value['project_delivery_period']) >4){
					$bidder_listing_details_hour_txt = $this->config->item('more_than_or_equal_5_hours');
				}
				if($this->session->userdata ('user') && $active_bids_project_value['project_owner_id'] == $user[0]->user_id){ 
					$bid_amount ='<small><i class="fas fa-stopwatch"></i>'.$bidded_amount_txt.'<span class="touch_line_break">'.number_format($active_bids_project_value['initial_bidded_amount'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_hour').'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').'<span class="touch_line_break">'.str_replace(".00","",number_format($active_bids_project_value['project_delivery_period'],  2, '.', ' ')).' '.$bidder_listing_details_hour_txt.'</span></small><small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.number_format($total_bid_value, 0, '', ' ')." ".CURRENCY.'</span></small>';} else {
					$bid_amount = '<small><i class="fas fa-stopwatch"></i>'.$bidded_amount_txt.'<span class="touch_line_break">'.number_format($active_bids_project_value['initial_bidded_amount'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_hour').'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').'<span class="touch_line_break">'.str_replace(".00","",number_format($active_bids_project_value['project_delivery_period'],  2, '.', ' ')).' '.$bidder_listing_details_hour_txt.'</span></small><small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').'<span class="touch_line_break">'.number_format($total_bid_value, 0, '', ' ')." ".CURRENCY.'</span></small>';}}else if($active_bids_project_value['project_type'] == 'fulltime'){$bid_amount = '<small><i class="far fa-credit-card"></i>'.$bidded_amount_txt.'<span class="touch_line_break">'.number_format($active_bids_project_value['initial_bidded_amount'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_month').'</span></small>';}}else if ($active_bids_project_value['bidding_dropdown_option'] == 'to_be_agreed'){if($active_bids_project_value['project_type'] == 'hourly'){if($this->session->userdata ('user') && $active_bids_project_value['project_owner_id'] == $user[0]->user_id){$bid_amount = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';} else {$bid_amount = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';}}else{$bid_amount = '<small><i class="far fa-credit-card"></i>'.$bidded_amount_txt.'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';}}else if ($active_bids_project_value['bidding_dropdown_option'] == 'confidential'){if($active_bids_project_value['project_type'] == 'hourly'){
				if($this->session->userdata ('user') && $active_bids_project_value['project_owner_id'] == $user[0]->user_id){$bid_amount ='<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';} else {$bid_amount ='<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';}}else{$bid_amount = '<small><i class="far fa-credit-card"></i>'.$bidded_amount_txt.'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';}
		}
		?><small><i class="far fa-calendar-alt"></i><?php echo $bidding_date; ?></small><?php echo $bid_amount; ?></label>
			<div class="default_project_description desktop-secreen">
				<p><?php 
                 echo character_limiter($active_bids_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_desktop'));?></p>
			</div>
			<div class="default_project_description ipad-screen">
				<p><?php 
                 echo character_limiter($active_bids_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_tablet'));
				?></p>
			</div>
			<div class="default_project_description mobile-screen">
				<p><?php 
               echo character_limiter($active_bids_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_mobile'));?></p>
			</div>
		<div class="clearfix"></div>
		<?php
		if($active_bids_project_value['featured'] == 'Y' || $active_bids_project_value['urgent'] == 'Y' || $active_bids_project_value['sealed'] == 'Y' || $active_bids_project_value['hidden'] == 'Y'){
		?>
	
			<div class="badgeAction">
				<label class="badgeOnly">
					<div class="default_project_badge">
					<?php
					if($active_bids_project_value['featured'] == 'Y' && $featured_max != 0 && $featured_max > time()){
					?>
					<button type="button" class="btn badge_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></button>
					<?php
						}
					if($active_bids_project_value['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time()){
					?>
					<button type="button" class="btn badge_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></button>
					<?php
					}
					if($active_bids_project_value['sealed'] == 'Y'){
					?>
					<button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
					<?php
					}
					if($active_bids_project_value['hidden'] == 'Y'){
					?>
					<button type="button" class="btn badge_hidden"><?php echo $this->config->item('post_project_page_upgrade_type_hidden'); ?></button>
					<?php
					}
					?>
					</div>
				</label>
			</div>
		
		<?php
		}
		?>
	</div>
</div>
<?php
		$active_bid_counter++;
	}	
}else{
?>
<div class="default_blank_message">
	<?php echo $this->config->item('no_active_bids_project_message')?>
</div>
<?php

}
	
if($page_type == 'dashboard'  &&  $active_bids_project_count > $this->config->item('user_dashboard_sp_view_active_bids_listing_limit')) {
?>
<div class="viewMore">
    <a class="default_btn blue_btn btn_style_5_35 btnBold" href="<?php echo base_url($this->config->item('myprojects_page_url')); ?>"><?php echo $this->config->item('view_all_projects'); ?></a> 
</div>
<?php
}

?>
<?php
if($page_type == 'my_projects' && $active_bids_project_count > 0  ){	
?>	
<!-- Pagination Start -->
<div class="pagnOnly">
	<div class="row">
		<?php
		$manage_paging_width_class = "col-md-12 col-sm-12"; 
		if(!empty($active_bids_pagination_links)){
		$manage_paging_width_class = "col-md-7 col-sm-7"; 
		}
		?>
		<div class="<?php echo $manage_paging_width_class; ?> col-12">
			<?php
				$rec_per_page = ($active_bids_project_count > $this->config->item('my_projects_sp_view_active_bids_listing_limit')) ? $this->config->item('my_projects_sp_view_active_bids_listing_limit') : $active_bids_project_count;
			?>
			<div class="pageOf">
				<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $active_bids_project_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
			</div>
		</div>
		<?php 
		if(!empty($active_bids_pagination_links)){
		?>
		<div class="col-md-5 col-sm-5 col-12">
			<div class="modePage">
				<?php echo $active_bids_pagination_links; ?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<!-- Pagination End -->	
<?php
}	
?>	
