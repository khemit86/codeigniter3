<?php
	$user = $this->session->userdata('user');
	
if(!empty($po_posted_project_listing)){
?>
<div class="projects_list">
<?php
	foreach($po_posted_project_listing as $project_key=>$project_value ){

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
	$featured_class = '';
	if($project_value['project_status'] == 'open_for_bidding' && $project_value['featured'] == 'Y' && strtotime($project_value['project_expiration_date']) >= time()) {
		$featured_class = 'ylbg';
	}

	
	if($project_value['project_status'] == 'open_for_bidding'){
		$project_upgrade_status  = get_project_upgrade_status($project_value['project_id']);

		$featured_max = 0;
		$urgent_max = 0;
		$expiration_featured_upgrade_date_array = [];
		$expiration_urgent_upgrade_date_array = [];

		if(!empty($project_upgrade_status['featured_upgrade_end_date'])){
			$expiration_featured_upgrade_date_array[] = $project_upgrade_status['featured_upgrade_end_date'];
		}
		if(!empty($project_upgrade_status['bonus_featured_upgrade_end_date'])){
			$expiration_featured_upgrade_date_array[] = $project_upgrade_status['bonus_featured_upgrade_end_date'];
		}
		if(!empty($project_upgrade_status['membership_include_featured_upgrade_end_date'])){
			$expiration_featured_upgrade_date_array[] = $project_upgrade_status['membership_include_featured_upgrade_end_date'];
		}
		if(!empty($expiration_featured_upgrade_date_array)){
			$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
			
		}
		if($featured_max < time()){
			$featured_class = '';
		}
		
		if(!empty($project_upgrade_status['urgent_upgrade_end_date'])){
			$expiration_urgent_upgrade_date_array[] = $project_upgrade_status['urgent_upgrade_end_date'];
			}
		if(!empty($project_upgrade_status['bonus_urgent_upgrade_end_date'])){
			$expiration_urgent_upgrade_date_array[] = $project_upgrade_status['bonus_urgent_upgrade_end_date'];
		}
		if(!empty($project_upgrade_status['membership_include_urgent_upgrade_end_date'])){
			$expiration_urgent_upgrade_date_array[] = $project_upgrade_status['membership_include_urgent_upgrade_end_date'];
		}
		if(!empty($expiration_urgent_upgrade_date_array)){
			
			$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
		}
		
		
		
		
	}
	
?>
	<div class="projects_created default_project_row  <?php echo $featured_class; ?>">
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
					
					$project_status_text = '';
					$po_view_project_date = '';
					if($project_value['project_status'] == 'open_for_bidding'){
					
						if(!empty($project_value['project_expiration_date']) && strtotime($project_value['project_expiration_date']) < time()){
							$project_status_text = $this->config->item('project_status_expired');
							$po_view_project_date = $project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_expired_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_expiration_date'])).'</span>' : $this->config->item('project_expired_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_expiration_date'])).'</span>';
						
						}
						else if(!empty($project_value['project_expiration_date']) && strtotime($project_value['project_expiration_date']) >= time()){
							$project_status_text = $this->config->item('project_status_open_for_bidding');
						
							$po_view_project_date = $project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>' : $this->config->item('project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
						
						}
						
						
					}else if($project_value['project_status'] == 'awarded'){
						$project_status_text = $this->config->item('project_status_awarded');
						$po_view_project_date = $project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>' : $this->config->item('project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
						
					}else if($project_value['project_status'] == 'in_progress'){
						$project_status_text = $this->config->item('project_status_in_progress');
						 if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
						}else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
							} 
						
					}else if($project_value['project_status'] == 'incomplete'){
						$project_status_text = $this->config->item('project_status_incomplete');
						 if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
						}else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
							} 
						
					}else if($project_value['project_status'] == 'completed'){
						$project_status_text = $this->config->item('project_status_completed');
						 if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
						}else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
							} 
						
					}else if($project_value['project_status'] == 'expired'){
						$project_status_text = $this->config->item('project_status_expired');
						$po_view_project_date = $project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_expired_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>' : $this->config->item('project_expired_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
					}else if($project_value['project_status'] == 'cancelled'){
						if($project_value['cancelled_by'] == 'user'){
							$project_status_text = $this->config->item('project_status_cancelled');
							
							$po_view_project_date = $project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_cancelled_by_po_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>' : $this->config->item('project_cancelled_by_po_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
							
						}else if($project_value['cancelled_by'] == 'admin'){
							$project_status_text = $this->config->item('project_status_cancelled_by_admin');
							
							$po_view_project_date =  $project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_cancelled_by_admin_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>' : $this->config->item('project_cancelled_by_admin_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
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
						<?php
						if($project_value['project_status'] == 'awarded'){
						?><small><i class="far fa-clock"></i><?php echo $po_view_project_date; ?></small>
						<?php
						}
						?><small><i class="fa fa-file-text-o"></i><?php 
							// if($project_value['project_status'] != 'in_progress'){
								if($project_value['project_type'] == 'fixed'){
									echo $this->config->item('project_listing_window_snippet_fixed_budget_project');
								} else if($project_value['project_type'] == 'hourly'){
									echo $this->config->item('project_listing_window_snippet_hourly_based_budget_project');
								} else if($project_value['project_type'] == 'fulltime'){
									echo $this->config->item('project_listing_window_snippet_fulltime_project');
								}
							// }else{
							// 	if($project_value['project_type'] == 'fixed'){
							// 		echo $this->config->item('inprogress_project_listing_window_snippet_fixed_budget_project_po_view');
							// 	}else if($project_value['project_type'] == 'hourly'){
							// 		echo $this->config->item('inprogress_project_listing_window_snippet_hourly_based_budget_project_po_view');
							// 	}else if($project_value['project_type'] == 'fulltime'){
							// 		echo $this->config->item('inprogress_project_listing_window_snippet_fulltime_project_po_view');
							// 	}
													
							// }
							
							// if($project_value['project_status'] != 'in_progress' )
							// {
								if($project_value['confidential_dropdown_option_selected'] == 'Y'){
									if($project_value['project_type'] == 'fixed'){
										echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected').'</span>';
										}else if($project_value['project_type'] == 'hourly'){
										echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_confidential_option_selected').'</span>';
									}else if($project_value['project_type'] == 'fulltime'){
										echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_confidential_option_selected').'</span>';
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
											$budget_range .= '<span class="touch_line_break">'.number_format($project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
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
								if($project_value['escrow_payment_method'] == 'Y') {
									echo '<span class="touch_line_break">'.$this->config->item('po_profile_page_project_payment_method_escrow_system').'</span>';
								}
								if($project_value['offline_payment_method'] == 'Y'){
									echo '<span class="touch_line_break">'.$this->config->item('po_profile_page_project_payment_method_offline_system').'</span>';
								}
							// }?></small><?php
						if($project_value['project_status'] == 'open_for_bidding' || $project_value['project_status'] == 'in_progress' || $project_value['project_status'] == 'incomplete' || $project_value['project_status'] == 'completed'){
						?><small><i class="far fa-clock"></i><?php echo $po_view_project_date; ?></small>
						<?php
						}
						?>
						<?php
						if($project_value['project_status'] == 'cancelled' || $project_value['project_status'] == 'expired'){
						?><small><i class="fa fa-clock-o project_expired_or_cancel_or_completed_date_icon_size"></i><?php echo $po_view_project_date; ?></small><?php
						}
						?>
						<?php
						if($project_value['project_status'] == 'completed'){
						?><small><i class="fa fa-clock-o project_expired_or_cancel_or_completed_date_icon_size"></i><?php echo $project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_completion_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_completion_date'])).'</span>' : $this->config->item('project_completion_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_completion_date'])).'</span>'; ?></small><?php
						}
						?>
						<?php if(!empty($location)) { ?><small><i class="fas fa-map-marker-alt"></i><?php echo $location; ?></small><?php } ?>
						<?php
						if($project_value['sealed'] == 'N' || ($this->session->userdata ('user') &&  ($user[0]->user_id == $project_value['project_owner_id']))){
						?><small><i class="fas fa-bullhorn"></i><?php
							$project_bid_count = get_project_bid_count($project_value['project_id'],$project_value['project_type']);
							$bid_history_total_bids = $project_bid_count."&nbsp;";
							if ($project_value['project_type'] == 'fulltime') {
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
							} echo $bid_history_total_bids; ?></small><?php
							$project_hires_count = 0;
							if($project_value['project_type'] == 'fulltime') {
								// count the number of number of hires of project
								$project_hires_count = get_project_hires_count($project_value['project_id'],$project_value['project_type']);
								$project_total_hires = $project_hires_count."&nbsp;";
								if ($project_value['project_type'] == 'fulltime') {
									if($project_hires_count == 0){
										$project_total_hires = $project_total_hires . $this->config->item('fulltime_project_description_snippet_hire_history_0_employees_hired');
									}else if($project_hires_count == 1){
										$project_total_hires = $project_total_hires . $this->config->item('fulltime_project_description_snippet_hire_history_1_employee_hire');
									}else if($project_hires_count >= 2 && $project_hires_count <= 4){
										$project_total_hires = $project_total_hires . $this->config->item('fulltime_project_description_snippet_hire_history_2_to_4_employees_hired');
									}else {
										$project_total_hires = $project_total_hires . $this->config->item('fulltime_project_description_snippet_hire_history_5_or_more_employees_hired');
									}
								}else{
									if($project_hires_count == 0){
										$project_total_hires = $project_total_hires . $this->config->item('project_description_snippet_hire_history_0_sps_hired');
									}else if($project_hires_count == 1){
										$project_total_hires = $project_total_hires . $this->config->item('project_description_snippet_hire_history_1_sp_hire');
										
									}else if($project_hires_count >= 2 && $project_hires_count <= 4 ){
										$project_total_hires = $project_total_hires . $this->config->item('project_description_snippet_hire_history_2_to_4_sps_hired');
									}else {
										$project_total_hires = $project_total_hires . $this->config->item('project_description_snippet_hire_history_5_or_more_sps_hired');
									}
								}
								$proj_value = get_total_project_value_po($project_value['project_id'],$project_value['project_type']);
								if(floatval($proj_value) != 0) {
									$project_total_amt_txt = $this->config->item('fulltime_projects_employer_view_total_project_value').'<span class="touch_line_break">'.number_format($proj_value, 0, '', ' ')." ".CURRENCY.'</span>';
								}
							}
							?>
							<?php
							if($project_value['project_type'] == 'fulltime' && $project_hires_count != 0) {
							?><small><i class="fas fa-tasks"></i><?php echo $project_total_hires; ?></small><?php		
							}
							?>
							<?php
							if($project_value['project_type'] == 'fulltime' && (isset($proj_value) && floatval($proj_value) != 0 && $this->session->userdata('user') && $user[0]->user_id == $project_value['project_owner_id'])) {
							?><small><i class="fas fa-coins"></i><?php echo $project_total_amt_txt; ?></small><?php
							}
							?>
							<?php
							if($project_value['project_status'] == 'in_progress' || $project_value['project_status'] == 'incomplete' || $project_value['project_status'] == 'completed' || $project_value['project_status'] == 'open_for_bidding' || $project_value['project_status'] == 'expired' || $project_value['project_status'] == 'cancelled'){
							$project_hires_count = get_project_hires_count($project_value['project_id'],$project_value['project_type']);
								if($project_value['project_type'] != 'fulltime' && $project_hires_count != 0) {
							?><small><i class="fas fa-tasks"></i><?php
								// count the number of number of hires of project
								
								$project_total_hires = $project_hires_count."&nbsp;";
								if ($project_value['project_type'] == 'fulltime') {
									if($project_hires_count == 0){
										$project_total_hires = $project_total_hires . $this->config->item('fulltime_project_description_snippet_hire_history_0_employees_hired');
									}else if($project_hires_count == 1){
										$project_total_hires = $project_total_hires . $this->config->item('fulltime_project_description_snippet_hire_history_1_employee_hire');
									}else if($project_hires_count >= 2 && $project_hires_count <= 4){
										$project_total_hires = $project_total_hires . $this->config->item('fulltime_project_description_snippet_hire_history_2_to_4_employees_hired');
									}else {
										$project_total_hires = $project_total_hires . $this->config->item('fulltime_project_description_snippet_hire_history_5_or_more_employees_hired');
									}
								}else{
									if($project_hires_count == 0){
										$project_total_hires = $project_total_hires . $this->config->item('project_description_snippet_hire_history_0_sps_hired');
									}else if($project_hires_count == 1){
										$project_total_hires = $project_total_hires . $this->config->item('project_description_snippet_hire_history_1_sp_hire');
									}else if($project_hires_count >= 2 && $project_hires_count <= 4 ){
										$project_total_hires = $project_total_hires . $this->config->item('project_description_snippet_hire_history_2_to_4_sps_hired');
									}else {
										$project_total_hires = $project_total_hires . $this->config->item('project_description_snippet_hire_history_5_or_more_sps_hired');
									}
								} echo $project_total_hires; ?></small><?php
							}
							$proj_value = get_total_project_value_po($project_value['project_id'],$project_value['project_type']);
							if($project_value['project_type'] == 'fixed' && floatval($proj_value) != 0){ 
								$project_total_amt_txt = $this->config->item('my_projects_po_view_total_project_value').'<span class="touch_line_break">'.number_format($proj_value, 0, '', ' ')." ".CURRENCY.'</span>';
							} else if($project_value['project_type'] == 'hourly' && floatval($proj_value) != 0) {
								$project_total_amt_txt = $this->config->item('my_projects_po_view_total_project_value').'<span class="touch_line_break">'.number_format($proj_value, 0, '', ' ')." ".CURRENCY.'</span>';
							} 
							if(isset($proj_value) && floatval($proj_value) != 0 && $this->session->userdata('user') && $user[0]->user_id == $project_value['project_owner_id'] && $project_value['project_type'] != 'fulltime') {
							?><small><i class="fas fa-coins"></i><?php echo $project_total_amt_txt; ?></small><?php
								}
							if($project_value['total_active_disputes'] > 0){
							?><small><i class="fas fa-exclamation-triangle" style="color:red;"></i></small>
							<?php	
								}else{
									$get_latest_closed_dispute_record = array();
									if($project_value['project_type'] == 'fixed' || $project_value['project_type'] == 'hourly' ){
										$dispute_close_conditions = array('disputed_project_id'=>$project_value['project_id'],'project_owner_id_of_disputed_project'=>$project_value['project_owner_id']);
									}else if($project_value['project_type'] == 'fulltime'){
										$dispute_close_conditions = array('disputed_fulltime_project_id'=>$project_value['project_id'],'employer_id_of_disputed_fulltime_project'=>$project_value['project_owner_id']);
									}
									
									$get_latest_closed_dispute_record = get_latest_project_closed_dispute($project_value['project_type'],$dispute_close_conditions); 
									
									if(!empty($get_latest_closed_dispute_record) && ($get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_sp' || $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_po'|| $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_employee' || $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_employer'||$get_latest_closed_dispute_record['dispute_status'] == 'automatic_decision'|| $get_latest_closed_dispute_record['dispute_status'] == 'parties_agreement')){
									?>
									<small><i class="fas fa-balance-scale"></i></small>
									<?php
									}if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && (($project_value['project_type'] != 'fulltime' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['sp_winner_id_of_disputed_project']) || ($project_value['project_type'] == 'fulltime'&& $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['employee_winner_id_of_disputed_fulltime_project']))){
									?>
									<small><i class="fas fa-balance-scale-left"></i></small>
									<?php	
									}
									if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && (($project_value['project_type'] != 'fulltime' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['project_owner_id_of_disputed_project']) || ($project_value['project_type'] == 'fulltime' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['employer_id_of_disputed_fulltime_project']))){ ?>
									<small><i class="fas fa-balance-scale-right"></i></small>	
									<?php	
									}
								}									
							}
							?>
						<?php
						}
						?>
					</label>
				</div>
			</div>
			<!-- Mobile Design Start -->
			<div class="col-12 userProject_btn mobile_userProject_btn">
				<div class="opnProj">
					<?php
					
					$project_status_text = '';
					$po_view_project_date = '';
					if($project_value['project_status'] == 'open_for_bidding'){
					
						if(!empty($project_value['project_expiration_date']) && strtotime($project_value['project_expiration_date']) < time()){
							$project_status_text = $this->config->item('project_status_expired');
							$po_view_project_date = $project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_expired_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_expiration_date'])).'</span>' : $this->config->item('project_expired_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['project_expiration_date'])).'</span>';
						
						}
						else if(!empty($project_value['project_expiration_date']) && strtotime($project_value['project_expiration_date']) >= time()){
							$project_status_text = $this->config->item('project_status_open_for_bidding');
						
							$po_view_project_date = $project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>' : $this->config->item('project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
						
						}
						
						
					}else if($project_value['project_status'] == 'awarded'){
						$project_status_text = $this->config->item('project_status_awarded');
						$po_view_project_date = $project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>' : $this->config->item('project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
						
					}else if($project_value['project_status'] == 'in_progress'){
						$project_status_text = $this->config->item('project_status_in_progress');
						 if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
						}else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
							} 
						
					}else if($project_value['project_status'] == 'incomplete'){
						$project_status_text = $this->config->item('project_status_incomplete');
						 if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
						}else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
							} 
						
					}else if($project_value['project_status'] == 'completed'){
						$project_status_text = $this->config->item('project_status_completed');
						 if($project_value['project_type'] == 'fulltime'){
							$po_view_project_date =  $this->config->item('fulltime_project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
						}else{
							$po_view_project_date =  $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
							} 
						
					}else if($project_value['project_status'] == 'expired'){
						$project_status_text = $this->config->item('project_status_expired');
						$po_view_project_date = $project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_expired_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>' : $this->config->item('project_expired_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
					}else if($project_value['project_status'] == 'cancelled'){
						if($project_value['cancelled_by'] == 'user'){
							$project_status_text = $this->config->item('project_status_cancelled');
							
							$po_view_project_date = $project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_cancelled_by_po_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>' : $this->config->item('project_cancelled_by_po_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
							
						}else if($project_value['cancelled_by'] == 'admin'){
							$project_status_text = $this->config->item('project_status_cancelled_by_admin');
							
							$po_view_project_date =  $project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_cancelled_by_admin_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>' : $this->config->item('project_cancelled_by_admin_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($project_value['po_view_project_date'])).'</span>';
						}
					}
					?>
					<button><?php echo $project_status_text; ?></button>
					</div>
			</div>
			<!-- Mobile Design End -->
			<div class="col-md-12 col-sm-12 col-12">
				<div class="proCrtd">
					<div class="osu1">
						<?php
						//$description = htmlspecialchars($project_value['project_description'], ENT_QUOTES);
						?>
						<div class="default_project_description desktop-secreen">
														<p><?php 
														echo character_limiter($project_value['project_description'],$this->config->item('user_profile_page_project_owner_posted_project_listing_project_description_character_limit_dekstop'));
							//echo limitString($description,$this->config->item('user_profile_page_project_owner_posted_project_listing_project_description_character_limit_dekstop'));?></p>
						</div>
						<div class="default_project_description ipad-screen">
							<p><?php echo character_limiter($project_value['project_description'],$this->config->item('user_profile_page_project_owner_posted_project_listing_project_description_character_limit_tablet')); ?></p>
						</div>
						<div class="default_project_description mobile-screen">
							<p><?php echo character_limiter($project_value['project_description'],$this->config->item('user_profile_page_project_owner_posted_project_listing_project_description_character_limit_mobile')); ?></p>
						</div>
					</div>
				</div>				
				<div class="clearfix"></div>									
			</div>
			<?php
			
			$show_badge = "display:none;";
			if(($project_value['project_status'] == 'open_for_bidding' && strtotime($project_value['project_expiration_date']) >= time() && ($project_value['featured'] == 'Y' || $project_value['urgent'] == 'Y' || $project_value['sealed'] == 'Y'))  || ($project_value['project_status'] == 'open_for_bidding' && strtotime($project_value['project_expiration_date']) < time() &&  $project_value['sealed'] == 'Y') ||  ($project_value['project_status'] != 'open_for_bidding' && $project_value['sealed'] == 'Y')){
			$show_badge = "display:block;";
			}
			?>
			<div class="col-md-12 col-sm-12 col-12" style="<?php echo $show_badge; ?>">
                            <div class="default_project_badge">
                                <?php if($project_value['featured'] == 'Y' && $featured_max >= time()) { ?>
                                <button type="button" class="btn badge_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></button>
                                <?php } ?>
                                <?php if($project_value['urgent'] == 'Y' && $urgent_max >= time()) { ?>
                                <button type="button" class="btn badge_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></button>
                                <?php } ?>
                                <?php if($project_value['sealed'] == 'Y') { ?>
                                <button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
                                <?php } ?>

                            <div class="clearfix"></div>
                            </div>
			</div>
		</div>
	</div>
<?php
	}
	?>
	</div>
<?php
if($po_posted_project_count > $this->config->item('user_profile_page_posted_projects_tab_limit')){
	?>
	<div class="row">
		<div class="col-md-12 text-center projects_viewMore">
			<input type="hidden" id="pageno_posted_projects" value="1">
			<button type="button" id="loadmore_posted_projects" class="btn default_btn blue_btn"><?php echo $this->config->item('load_more_results'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
		</div>
	</div>		
<?php
	}	
}else{
	if($this->session->userdata('user') && $user[0]->user_id == $user_detail['user_id']){
         $no_data_msg =  $this->config->item('user_profile_page_projects_created_tab_no_data_po_view');
	}else{
		if(($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')){	
			if($user_detail['gender'] == 'M'){
				if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
					$no_data_msg = $this->config->item('user_profile_page_projects_created_tab_no_data_male_visitor_view');
				}else{
					$no_data_msg = $this->config->item('user_profile_page_projects_created_tab_no_data_company_app_male_visitor_view');
				}
			}else if($user_detail['gender'] == 'F'){
				if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
					$no_data_msg = $this->config->item('user_profile_page_projects_created_tab_no_data_female_visitor_view');
				}else{
					$no_data_msg = $this->config->item('user_profile_page_projects_created_tab_no_data_company_app_female_visitor_view');
				}
			}
			$no_data_msg = str_replace(array('{user_first_name_last_name}'),array($user_detail['first_name']." ".$user_detail['last_name']),$no_data_msg);
		}else{
			$no_data_msg = $this->config->item('user_profile_page_projects_created_tab_no_data_company_visitor_view');
			$no_data_msg = str_replace(array('{user_company_name}'),array($user_detail['company_name']),$no_data_msg);
		}
	}	
	echo '<div class="default_blank_message">'.$no_data_msg.'</div>';
}	
?>