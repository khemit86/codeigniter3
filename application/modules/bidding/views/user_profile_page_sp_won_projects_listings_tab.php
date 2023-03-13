<?php
$user = $this->session->userdata('user');
if(!empty($sp_won_project_listing)){
?>
<div class="projects_list">
<?php
	// pre($sp_won_project_listing);
	foreach($sp_won_project_listing as $project_key=>$project_value ){
	$location = '';
	if(!empty($project_value['county_name'])){
		if(!empty($project_value['locality_name'])){
			$location .= $project_value['locality_name'];
		}
		if(!empty($project_value['postal_code'])){
			$location .= '&nbsp;'.$project_value['postal_code'] .',&nbsp;';
		}else if(!empty($project_value['locality_name']) && empty($project_value['postal_code'])){
			$location .= ',&nbsp';
		}
		$location .= $project_value['county_name'];
	}
?>
	<div class="projects_created default_project_row">
		<div class="row">
			<div class="col-md-10 col-sm-10 col-12 userProject_text">
				<div class="stProj default_project_title">
					<a class="default_project_title_link" target="_blank" href="<?php echo base_url().$this->config->item('project_detail_page_url')."?id=".$project_value['project_id']; ?>">
					<?php echo htmlspecialchars($project_value['project_title'], ENT_QUOTES);?></a>
				</div>
			</div>
			<!--- Desktop Design Start --->
			<div class="col-md-2 col-sm-2 col-12 userProject_btn desktop_userProject_btn">
				<div class="opnProj">
					<?php
					$project_status_text = $this->config->item('user_profile_page_fulltime_project_status_hired_sp_projects_won_tab');
					$po_view_project_date = '';
					if($project_value['project_status'] == 'open_bidding') {
						//$project_status_text = $this->config->item('project_status_open_for_bidding');
						if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						} else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						} 
					}
					if($project_value['project_status'] == 'expired') {
						//$project_status_text = $this->config->item('project_status_expired');
						/* if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_expired_on').date(DATE_TIME_FORMAT,strtotime($project_value['project_expiration_date']));
						} else{
							$po_view_project_date =  $this->config->item('project_expired_on').date(DATE_TIME_FORMAT,strtotime($project_value['project_expiration_date']));
						} */ 
						if($project_value['project_type'] == 'fulltime'){
							//$po_view_project_date =  $this->config->item('fulltime_project_start_date').date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date']));
							$po_view_project_date =  $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						} else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						} 
					}

					if($project_value['project_status'] == 'cancelled') {
						if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						}
						/* //$project_status_text = $this->config->item('project_status_cancelled');
						$cancelled_on = $this->config->item('fulltime_project_cancelled_by_po_on');
						if($project_value['cancelled_by_admin'] == 'Y') {
							//$project_status_text = $this->config->item('project_status_cancelled_by_admin');
							$cancelled_on = $this->config->item('fulltime_project_cancelled_by_admin_on');
						} 
						if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date = $cancelled_on.date(DATE_TIME_FORMAT,strtotime($project_value['project_expiration_date']));
						} */
					}

					if($project_value['project_status'] == 'in_progress'){
						$project_status_text = $this->config->item('project_status_in_progress');
						//$project_start_date = get_latest_in_progress_project_start_date($project_value['project_id'],$project_value['project_type']);
						 if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						}else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
							} 
						
					}if($project_value['project_status'] == 'incomplete'){
						$project_status_text = $this->config->item('project_status_incomplete');
						//$project_start_date = get_latest_in_progress_project_start_date($project_value['project_id'],$project_value['project_type']);
						 if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						}else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
							} 
						
					} if($project_value['project_status'] == 'completed'){
						if($project_value['project_completion_method'] == 'outside_portal'){
							$project_status_text = $this->config->item('user_profile_page_project_status_marked_as_complete_sp_projects_won_tab');
						}else{
							$project_status_text = $this->config->item('project_status_completed');
						}
						
						
						 if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						}else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
							}
					}
					?>
					<button><?php echo $project_status_text; ?></button>
				</div>
			</div>
			<!--- Desktop Design End --->
			<div class="col-md-12 col-sm-12 col-12">	
				<div class="opLBottom">
					<label class="default_short_details_field">
						<small><i class="fa fa-file-text-o"></i><?php 
							if($project_value['project_type'] == 'fixed'){
								echo $this->config->item('project_listing_window_snippet_fixed_budget_project');
							}else if($project_value['project_type'] == 'hourly'){
								echo $this->config->item('project_listing_window_snippet_hourly_based_budget_project');
							}else if($project_value['project_type'] == 'fulltime'){
								echo $this->config->item('project_listing_window_snippet_fulltime_project');
							}
							if($project_value['confidential_dropdown_option_selected'] == 'Y'){
								if($project_value['project_type'] == 'fixed'){ echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected').'</span>';
									}else if($project_value['project_type'] == 'hourly'){ echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_confidential_option_selected').'</span>';
								}else if($project_value['project_type'] == 'fulltime'){ echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_confidential_option_selected').'</span>';
								}
							}else if($project_value['not_sure_dropdown_option_selected'] == 'Y'){
								if($project_value['project_type'] == 'fixed'){
								echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected').'</span>';
								}else if($project_value['project_type'] == 'hourly'){
								echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_not_sure_option_selected').'</span>';
								}else if($project_value['project_type'] == 'fulltime'){
									echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_not_sure_option_selected').'</span>';
								}
							}else{
								$budget_range = '';
								if($project_value['max_budget'] != 'All'){
									if($project_value['project_type'] == 'hourly'){
										$budget_range = '';
										if($this->config->item('post_project_budget_range_between')){
											$budget_range .= $this->config->item('post_project_budget_range_between');
										}
										$budget_range .= '<span class="touch_line_break">'.number_format($project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour').'</span>';
									}else if($project_value['project_type'] == 'fulltime'){
										$budget_range = '';
										if($this->config->item('post_project_budget_range_between')){
											$budget_range .= $this->config->item('post_project_budget_range_between');
										}
										$budget_range .= '<span class="touch_line_break">'.number_format($project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_month').'</span>';
									}else{
										$budget_range = '';
										if($this->config->item('post_project_budget_range_between')){
											$budget_range .= $this->config->item('post_project_budget_range_between');
										}
										$budget_range .= '<span class="touch_line_break">'.number_format($project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'&nbsp;'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
									}
								}else{
									if($project_value['project_type'] == 'hourly'){
										$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span>';
									}else if($project_value['project_type'] == 'fulltime'){
										$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span>';
									}else{
										$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
									}
								}
								echo $budget_range;
							}
							?></small><small><i class="<?php echo (in_array($project_value['project_status'], ['expired', 'cancelled'])) ? 'far fa-clock' : 'far fa-clock'; ?>"></i><?php echo $po_view_project_date; ?></small><?php
						if($project_value['project_status'] == 'completed'){
						?><small><i class="fa fa-clock-o project_expired_or_cancel_or_completed_date_icon_size"></i><?php
						if($project_value['project_type'] == 'fulltime'){
							echo $this->config->item('fulltime_project_completion_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_winner_work_completion_date'])).'</span>';
						}else{
							echo  $this->config->item('project_completion_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_winner_work_completion_date'])).'</span>';
							} 
						?></small><?php
						}
						?>
						<?php if(!empty($location)) { ?><small><i class="fas fa-map-marker-alt"></i><?php echo $location; ?></small><?php } ?>
						<?php
							$inprogress_amount_txt = '';
							$proj_value = '';
							if($project_value['project_type'] == 'fixed'){
								$initial_bid_description = $project_value['initial_bid_description'];	
								if($this->session->userdata ('user') && $user[0]->user_id == $project_value['project_owner_id']) { 
									$inprogress_amount_txt= $this->config->item('fixed_budget_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view');
								} else {
									$inprogress_amount_txt= $this->config->item('fixed_budget_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view');
								}
								if($project_value['project_status'] == 'in_progress'){
								
									if(!empty( floatval($project_value['initial_project_agreed_value']))){
										$proj_value = $this->config->item('fixed_or_hourly_project_value').'<span class="touch_line_break">'.number_format($project_value['initial_project_agreed_value'], 0, '', ' ')." ".CURRENCY.'</span>';
									}
								}
								if($project_value['project_status'] == 'incomplete'){
								
									if(!empty( floatval($project_value['initial_project_agreed_value']))){
										$proj_value = $this->config->item('fixed_or_hourly_project_value').'<span class="touch_line_break">'.number_format($project_value['initial_project_agreed_value'], 0, '', ' ')." ".CURRENCY.'</span>';
									}
								}
								if($project_value['project_status'] == 'completed'){
								
									if(!empty(floatval($project_value['total_paid_amount']))){
										$proj_value = $this->config->item('fixed_or_hourly_project_value').'<span class="touch_line_break">'.number_format($project_value['total_paid_amount'], 0, '', ' ')." ".CURRENCY.'</span>';
									}
								}
								
							} else if($project_value['project_type'] == 'hourly'){
								$initial_bid_description = $project_value['initial_bid_description'];
								$inprogress_amount_txt = $this->config->item('project_details_page_bidder_listing_bidded_hourly_rate_txt');
								if(!empty($project_value['initial_bid_value']) && $project_value['initial_bid_value'] >= $project_value['total_paid_amount']){
									$proj_value= $this->config->item('fixed_or_hourly_project_value').'<span class="touch_line_break">'.number_format($project_value['initial_bid_value'], 0, '', ' ')." ".CURRENCY.'</span>';
								} else if(!empty($project_value['initial_bid_value']) && $project_value['total_paid_amount'] > $project_value['initial_bid_value']) {
									$proj_value= $this->config->item('fixed_or_hourly_project_value').'<span class="touch_line_break">'.number_format($project_value['total_paid_amount'], 0, '', ' ')." ".CURRENCY.'</span>';
								} else if(empty($project_value['initial_bid_value']) && $project_value['total_paid_amount'] > $project_value['initial_bid_value']) {
									$proj_value= $this->config->item('fixed_or_hourly_project_value').'<span class="touch_line_break">'.number_format($project_value['total_paid_amount'], 0, '', ' ')." ".CURRENCY.'</span>';
								}
							} else if($project_value['project_type'] == 'fulltime'){
								$initial_bid_description = $project_value['initial_application_description'];
								$inprogress_amount_txt = $this->config->item('project_details_page_bidder_listing_expected_salary_txt');
								if(floatval($project_value['total_paid_amount']) > 0 ){
								 $proj_value= $this->config->item('fulltime_projects_employee_view_project_value').'<span class="touch_line_break">'.number_format($project_value['total_paid_amount'], 0, '', ' ')." ".CURRENCY.'</span>';
								}
							}
							$bid_value = '';
							if($project_value['bidding_dropdown_option'] == 'NA'){
								if($project_value['project_type'] == 'fixed'){
								
									if($project_value['project_status'] == 'in_progress'){
										
										/* $bidder_listing_details_fixed_txt = floatval($project_value['initial_project_agreed_delivery_period'])> 1 ? $this->config->item('project_details_page_bidder_listing_details_day_plural') : $this->config->item('project_details_page_bidder_listing_details_day_singular'); */
										
										if(floatval($project_value['initial_project_agreed_delivery_period']) == 1){
											$bidder_listing_details_fixed_txt = $this->config->item('1_day');
										}else if(floatval($project_value['initial_project_agreed_delivery_period']) >=2 && floatval($project_value['initial_project_agreed_delivery_period']) <= 4){
											$bidder_listing_details_fixed_txt = $this->config->item('2_4_days');
										}else if(floatval($project_value['initial_project_agreed_delivery_period']) >4){
											$bidder_listing_details_fixed_txt = $this->config->item('more_than_or_equal_5_days');
										}
										
								
										$bid_value = '<small><i class="far fa-credit-card"></i>'.$inprogress_amount_txt.'<span class="touch_line_break">'.number_format($project_value['initial_bid_value'], 0, '', ' ')." ".CURRENCY.'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').' '. str_replace(".00","",number_format($project_value['initial_project_agreed_delivery_period'], 0, '', ' ')) .' '.$bidder_listing_details_fixed_txt.'</small>';
									}
									if($project_value['project_status'] == 'incomplete'){
										$bidder_listing_details_fixed_txt = floatval($project_value['initial_project_agreed_delivery_period'])> 1 ? $this->config->item('project_details_page_bidder_listing_details_day_plural') : $this->config->item('project_details_page_bidder_listing_details_day_singular');
								
										$bid_value = '<small><i class="far fa-credit-card"></i>'.$inprogress_amount_txt.'<span class="touch_line_break">'.number_format($project_value['initial_bid_value'], 0, '', ' ')." ".CURRENCY.'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').' '. str_replace(".00","",number_format($project_value['initial_project_agreed_delivery_period'], 0, '', ' ')) .' '.$bidder_listing_details_fixed_txt.'</small>';
									}
									if($project_value['project_status'] == 'completed'){
										
								
										$bid_value = '<small><i class="far fa-credit-card"></i>'.$inprogress_amount_txt.'<span class="touch_line_break">'.number_format($project_value['initial_bid_value'], 0, '', ' ')." ".CURRENCY.'</span></small>';
									}
									
								}else if($project_value['project_type'] == 'hourly'){
									$total_bid_value = floatval($project_value['initial_bid_value']);
									
									if(floatval($project_value['initial_project_agreed_delivery_period']) == 1){
									$bidder_listing_details_hour_txt = $this->config->item('1_hour');
									}else if(floatval($project_value['initial_project_agreed_delivery_period']) >=2 && floatval($project_value['initial_project_agreed_delivery_period']) <= 4){
										$bidder_listing_details_hour_txt = $this->config->item('2_4_hours');
									}else if(floatval($project_value['initial_project_agreed_delivery_period']) >4){
										$bidder_listing_details_hour_txt = $this->config->item('more_than_or_equal_5_hours');
									}
									
									
									
									$bid_value = '<small><i class="fas fa-stopwatch"></i>'.$inprogress_amount_txt.'<span class="touch_line_break">'.number_format($project_value['initial_project_agreed_value'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_hour').'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').' '. str_replace(".00","",number_format($project_value['initial_project_agreed_delivery_period'],  2, '.', ' ')).' '.$bidder_listing_details_hour_txt.'</small><small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').'<span class="touch_line_break">'.number_format($total_bid_value, 0, '', ' ')." ".CURRENCY.'</span></small>';
								} else if($project_value['project_type'] == 'fulltime'){
									$bid_value = '<small><i class="far fa-credit-card"></i>'.$inprogress_amount_txt.'<span class="touch_line_break">'.number_format($project_value['initial_bid_value'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_month').'</span></small>' ;
								}
							} else if ($project_value['bidding_dropdown_option'] == 'confidential'){
								if($project_value['project_type'] == 'hourly'){
									$bid_value = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';
								} else {
									$bid_value = '<small><i class="far fa-credit-card"></i>'.$inprogress_amount_txt.'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';
								}
							} else if ($project_value['bidding_dropdown_option'] == 'to_be_agreed') { 
								if($project_value['project_type'] == 'hourly'){
									$bid_value = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
								} else {
									$bid_value = '<small><i class="far fa-credit-card"></i>'.$inprogress_amount_txt.'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
								}
							}
						?><?php
						if(!empty($bid_value) && $this->session->userdata('user') && $user[0]->user_id == $user_id){
						?><?php echo $bid_value; ?><?php
						}
						?><?php
						if(!empty($proj_value) && $this->session->userdata('user') && $user[0]->user_id == $user_id){
						?><small><i class="fas fa-coins"></i><?php echo $proj_value; ?></small><?php
						}
						?><?php
						if($project_value['total_active_disputes'] > 0){
						?><small><i class="fas fa-exclamation-triangle" style="color:red;"></i></small><?php
						}else{
							$get_latest_closed_dispute_record = array();

							if($project_value['project_type'] == 'fixed' || $project_value['project_type'] == 'hourly' ){
								$dispute_close_conditions = array('disputed_project_id'=>$project_value['project_id'],'project_owner_id_of_disputed_project'=>$project_value['project_owner_id']);
							}else if($project_value['project_type'] == 'fulltime'){
								$dispute_close_conditions = array('disputed_fulltime_project_id'=>$project_value['project_id'],'employer_id_of_disputed_fulltime_project'=>$project_value['project_owner_id']);
							}
							$get_latest_closed_dispute_record = get_latest_project_closed_dispute($project_value['project_type'],$dispute_close_conditions); 
							
							if(!empty($get_latest_closed_dispute_record) && ($get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_sp' || $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_po'|| $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_employee' || $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_employer'||$get_latest_closed_dispute_record['dispute_status'] == 'automatic_decision'|| $get_latest_closed_dispute_record['dispute_status'] == 'parties_agreement')){
						?><small><i class="fas fa-balance-scale"></i></small><?php	
							}if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && (($project_value['project_type'] != 'fulltime' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['sp_winner_id_of_disputed_project']) || ($project_value['project_type'] == 'fulltime'&& $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['employee_winner_id_of_disputed_fulltime_project']))){
							?><small><i class="fas fa-balance-scale-left"></i></small><?php	
							}
							if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && (($project_value['project_type'] != 'fulltime' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['project_owner_id_of_disputed_project']) || ($project_value['project_type'] == 'fulltime' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['employer_id_of_disputed_fulltime_project']))){  ?><small><i class="fas fa-balance-scale-right"></i></small><?php	
							}							
						}	
						?></label>
				</div>
			</div>
			<!--- Desktop Design Start --->
			<div class="col-md-2 col-sm-2 col-12 userProject_btn mobile_userProject_btn">
				<div class="opnProj">
					<?php
					$project_status_text = $this->config->item('user_profile_page_fulltime_project_status_hired_sp_projects_won_tab');
					$po_view_project_date = '';
					if($project_value['project_status'] == 'open_bidding') {
						//$project_status_text = $this->config->item('project_status_open_for_bidding');
						if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						} else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						} 
					}
					if($project_value['project_status'] == 'expired') {
						//$project_status_text = $this->config->item('project_status_expired');
						/* if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_expired_on').date(DATE_TIME_FORMAT,strtotime($project_value['project_expiration_date']));
						} else{
							$po_view_project_date =  $this->config->item('project_expired_on').date(DATE_TIME_FORMAT,strtotime($project_value['project_expiration_date']));
						} */ 
						if($project_value['project_type'] == 'fulltime'){
							//$po_view_project_date =  $this->config->item('fulltime_project_start_date').date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date']));
							$po_view_project_date =  $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						} else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						} 
					}

					if($project_value['project_status'] == 'cancelled') {
						if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						}
						/* //$project_status_text = $this->config->item('project_status_cancelled');
						$cancelled_on = $this->config->item('fulltime_project_cancelled_by_po_on');
						if($project_value['cancelled_by_admin'] == 'Y') {
							//$project_status_text = $this->config->item('project_status_cancelled_by_admin');
							$cancelled_on = $this->config->item('fulltime_project_cancelled_by_admin_on');
						} 
						if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date = $cancelled_on.date(DATE_TIME_FORMAT,strtotime($project_value['project_expiration_date']));
						} */
					}

					if($project_value['project_status'] == 'in_progress'){
						$project_status_text = $this->config->item('project_status_in_progress');
						//$project_start_date = get_latest_in_progress_project_start_date($project_value['project_id'],$project_value['project_type']);
						 if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						}else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
							} 
						
					} if($project_value['project_status'] == 'incomplete'){
						$project_status_text = $this->config->item('project_status_incomplete');
						//$project_start_date = get_latest_in_progress_project_start_date($project_value['project_id'],$project_value['project_type']);
						 if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						}else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
							} 
						
					}if($project_value['project_status'] == 'completed'){
						if($project_value['project_completion_method'] == 'outside_portal'){
							$project_status_text = $this->config->item('user_profile_page_project_status_marked_as_complete_sp_projects_won_tab');
						}else{
							$project_status_text = $this->config->item('project_status_completed');
						}
						
						
						 if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
						}else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_start_date'])).'</span>';
							}
					}
					?>
					<button><?php echo $project_status_text; ?></button>
				</div>
			</div>
			<!--- Mobile Design End --->
			<div class="col-md-12 col-sm-12 col-12">
				<div class="proCrtd">
					<div class="osu1">
						<?php
						//$description = htmlspecialchars($project_value['project_description'], ENT_QUOTES);
						?>
						<div class="default_project_description desktop-secreen">
							<p><?php 
							 echo character_limiter($project_value['project_description'],$this->config->item('user_profile_page_project_owner_posted_project_listing_project_description_character_limit_dekstop'));?></p>
						</div>
						<div class="default_project_description ipad-screen">
							<p><?php 
							  echo character_limiter($project_value['project_description'],$this->config->item('user_profile_page_project_owner_posted_project_listing_project_description_character_limit_tablet'));
							?></p>
						</div>
						<div class="default_project_description mobile-screen">
							<p><?php 
							echo character_limiter($project_value['project_description'],$this->config->item('user_profile_page_project_owner_posted_project_listing_project_description_character_limit_mobile'));?></p>
						</div>
					</div>
				</div>				
				<div class="clearfix"></div>									
			</div>
			<?php if($project_value['featured'] == 'Y' || $project_value['urgent'] == 'Y' || $project_value['sealed'] == 'Y') { ?>
			<div class="col-md-12 col-sm-12 col-12">
				<div class="default_project_badge">
					<?php if($project_value['project_status'] == 'open_for_bidding') { ?>
						<?php if($project_value['featured'] == 'Y') { ?>
						<button type="button" class="btn badge_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></button>
						<?php } ?>
						<?php if($project_value['urgent'] == 'Y') { ?>
						<button type="button" class="btn badge_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></button>
						<?php } ?>
					<?php } ?>
					<?php if($project_value['sealed'] == 'Y') { ?>
					<button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
					<?php } ?>
					
					<div class="clearfix"></div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
<?php
	}
?>
	</div>
<?php
	if($sp_won_project_count > $this->config->item('user_profile_page_won_projects_tab_limit')){
	?>
	<div class="row">
		<div class="col-md-12 text-center projects_viewMore">
			<input type="hidden" id="pageno_won_projects" value="1">
			<button type="button" id="loadmore_won_projects" class="btn default_btn blue_btn"><?php echo $this->config->item('load_more_results'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
        </div>
	</div>
<?php
	}
}else{
	
	if($this->session->userdata('user') && $user[0]->user_id == $user_detail['user_id']){
         $no_data_msg =  $this->config->item('user_profile_page_projects_won_tab_no_data_sp_view');
	}else{
		if(($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')){	
			if($user_detail['gender'] == 'M'){
				if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
					$no_data_msg = $this->config->item('user_profile_page_projects_won_tab_no_data_male_visitor_view');
				}else{
					$no_data_msg = $this->config->item('user_profile_page_projects_won_tab_no_data_company_app_male_visitor_view');
				}
			}else if($user_detail['gender'] == 'F'){
				if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
					$no_data_msg = $this->config->item('user_profile_page_projects_won_tab_no_data_female_visitor_view');
				}else{
					$no_data_msg = $this->config->item('user_profile_page_projects_won_tab_no_data_company_app_female_visitor_view');
				}
			}
			$no_data_msg = str_replace(array('{user_first_name_last_name}'),array($user_detail['first_name']." ".$user_detail['last_name']),$no_data_msg);
		}else{
			$no_data_msg = $this->config->item('user_profile_page_projects_won_tab_no_data_company_visitor_view');
			$no_data_msg = str_replace(array('{user_company_name}'),array($user_detail['company_name']),$no_data_msg);
		}
	}	
	echo '<div class="default_blank_message">'.$no_data_msg.'</div>';
}	
?>
</div>