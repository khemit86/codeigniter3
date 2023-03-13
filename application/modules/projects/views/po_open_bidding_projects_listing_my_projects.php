<?php
#####
/* This file is using for dedicated page of project owner my projects section*/
/* Filename: application\modules\projects\controllers\Projects.php */
/* Action: my_projects_listing */
/* This file include on "application\modules\projects\views\my_projects.php" */


	// featured project availability
	$featured_time_arr = explode(":", $this->config->item('project_upgrade_availability_featured'));
	$featured_check_valid_arr = array_map('getInt', $featured_time_arr); 
	$featured_valid_time_arr = array_filter($featured_check_valid_arr);
	// urgent project availability
	$urgent_time_arr = explode(":", $this->config->item('project_upgrade_availability_urgent'));
	$urgent_check_valid_arr = array_map('getInt', $urgent_time_arr); 
	$urgent_valid_time_arr = array_filter($urgent_check_valid_arr);
if(!empty($open_bidding_project_data)){
	$total_open_for_bidding_projects = count($open_bidding_project_data);
	$open_for_bidding_counter = 1;
	
	foreach($open_bidding_project_data as $open_bidding_project_key => $open_bidding_project_value){
	
		
		$featured_max = 0;
		$urgent_max = 0;
		$last_record_class_remove_border_bottom = '';
		$last_record_class = '';
		$featured_class = '';
		$location = '';
		$expiration_featured_upgrade_date_array = array();
		$expiration_urgent_upgrade_date_array = array();
		if($open_for_bidding_counter == $total_open_for_bidding_projects){
			$last_record_class_remove_border_bottom = 'bbNo';
			
			if($page_type == 'dashboard' && $open_bidding_project_count <= $this->config->item('user_dashboard_po_view_open_bidding_projects_listing_limit') ) {
				$last_record_class = 'padding_bottom0';
			}
			
		}
		
		if(!empty($open_bidding_project_value['featured_upgrade_end_date'])){
			$expiration_featured_upgrade_date_array[] = $open_bidding_project_value['featured_upgrade_end_date'];
		}
		if(!empty($open_bidding_project_value['bonus_featured_upgrade_end_date'])){
			$expiration_featured_upgrade_date_array[] = $open_bidding_project_value['bonus_featured_upgrade_end_date'];
		}
		if(!empty($open_bidding_project_value['membership_include_featured_upgrade_end_date'])){
			$expiration_featured_upgrade_date_array[] = $open_bidding_project_value['membership_include_featured_upgrade_end_date'];
		}
		if(!empty($expiration_featured_upgrade_date_array)){
			$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
		}
		
		##########
		
		if(!empty($open_bidding_project_value['urgent_upgrade_end_date'])){
			$expiration_urgent_upgrade_date_array[] = $open_bidding_project_value['urgent_upgrade_end_date'];
		}
		if(!empty($open_bidding_project_value['bonus_urgent_upgrade_end_date'])){
			$expiration_urgent_upgrade_date_array[] = $open_bidding_project_value['bonus_urgent_upgrade_end_date'];
		}
		if(!empty($open_bidding_project_value['membership_include_urgent_upgrade_end_date'])){
			$expiration_urgent_upgrade_date_array[] = $open_bidding_project_value['membership_include_urgent_upgrade_end_date'];
		}
		if(!empty($expiration_urgent_upgrade_date_array)){
			$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
		}
		
		if($open_bidding_project_value['featured'] == 'Y' && $featured_max >= time()){
			$featured_class = 'opBg';
		}
		
		
		
		if($open_bidding_project_value['escrow_payment_method'] == 'Y'){
			$payment_method = 'via Escrow system';
			}
		if($open_bidding_project_value['offline_payment_method'] == 'Y'){
			$payment_method = 'via Offline system';
		}
		
		if(!empty($open_bidding_project_value['county_name'])){
		if(!empty($open_bidding_project_value['locality_name'])){
			$location .= $open_bidding_project_value['locality_name'];
		}
		if(!empty($open_bidding_project_value['postal_code'])){
			$location .= '&nbsp;'.$open_bidding_project_value['postal_code'] .',&nbsp;';
		}else if(!empty($open_bidding_project_value['locality_name']) && empty($open_bidding_project_value['postal_code'])){
			$location .= ',&nbsp';
		}
		$location .= $open_bidding_project_value['county_name'];
		}
		
?>
	<div class="tabContent">
		<!--<div class="default_project_row <?php echo $featured_class." ".$last_record_class_remove_border_bottom." ". $last_record_class;?>" id="<?php echo "open_for_bidding_project_".$open_bidding_project_value['project_id'] ?>">-->
		
		<div class="default_project_row <?php echo $featured_class; ?>" id="<?php echo "open_for_bidding_project_".$open_bidding_project_value['project_id'] ?>">
			<div class="default_project_title">
			<a class="default_project_title_link" target="_blank" href="<?php echo base_url().$this->config->item('project_detail_page_url')."?id=".$open_bidding_project_value['project_id']; ?>"><?php echo htmlspecialchars($open_bidding_project_value['project_title'], ENT_QUOTES);?></a>
			</div>
			<label class="default_short_details_field">
				<small><i class="fa fa-file-text-o"></i><?php 
				if($open_bidding_project_value['project_type'] == 'fixed'){
					echo $this->config->item('project_listing_window_snippet_fixed_budget_project');
				}else if($open_bidding_project_value['project_type'] == 'hourly'){
					echo $this->config->item('project_listing_window_snippet_hourly_based_budget_project');
				}else if($open_bidding_project_value['project_type'] == 'fulltime'){
					echo $this->config->item('project_listing_window_snippet_fulltime_project');
				}
				if($open_bidding_project_value['confidential_dropdown_option_selected'] == 'Y'){
					if($open_bidding_project_value['project_type'] == 'fixed'){
						echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected').'</span>';
						}else if($open_bidding_project_value['project_type'] == 'hourly'){
						echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_confidential_option_selected').'</span>';
					}else if($open_bidding_project_value['project_type'] == 'fulltime'){
						echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_confidential_option_selected').'</span>';
					}
				}else if($open_bidding_project_value['not_sure_dropdown_option_selected'] == 'Y'){
					if($open_bidding_project_value['project_type'] == 'fixed'){
					echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected').'</span>';
					}else if($open_bidding_project_value['project_type'] == 'hourly'){
					echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_not_sure_option_selected').'</span>';
					}else if($open_bidding_project_value['project_type'] == 'fulltime'){
						echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_not_sure_option_selected').'</span>';
					}
				}else{
					$budget_range = '';
					if($open_bidding_project_value['max_budget'] != 'All'){
						if($open_bidding_project_value['project_type'] == 'hourly'){
							$budget_range = '';
							if($this->config->item('post_project_budget_range_between')){
								$budget_range .= $this->config->item('post_project_budget_range_between');
							}
							$budget_range .= '<span class="touch_line_break">'.number_format($open_bidding_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($open_bidding_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour').'</span>';
						}else if($open_bidding_project_value['project_type'] == 'fulltime'){
							$budget_range = '';
							if($this->config->item('post_project_budget_range_between')){
								$budget_range .= $this->config->item('post_project_budget_range_between');
							}
							$budget_range .= '<span class="touch_line_break">'.number_format($open_bidding_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($open_bidding_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_month').'</span>';
						}else{
							$budget_range = '';
							if($this->config->item('post_project_budget_range_between')){
								$budget_range .= $this->config->item('post_project_budget_range_between');
							}
							$budget_range .= '<span class="touch_line_break">'.number_format($open_bidding_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($open_bidding_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
						}
					}else{
						if($open_bidding_project_value['project_type'] == 'hourly'){
							$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($open_bidding_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span>';
						}else if($open_bidding_project_value['project_type'] == 'fulltime'){
							$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($open_bidding_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span>';
						}else{
							$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($open_bidding_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
						}
					}
					echo $budget_range;
				}
				
				if($open_bidding_project_value['escrow_payment_method'] == 'Y') {
						echo '<span class="touch_line_break">'.$this->config->item('my_projects_project_payment_method_escrow_system').'</span>';
				}
				
				if($open_bidding_project_value['offline_payment_method'] == 'Y') {
					echo '<span class="touch_line_break">'.$this->config->item('my_projects_project_payment_method_offline_system').'</span>';
				}
				?></small><small><i class="far fa-clock"></i><?php echo $open_bidding_project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($open_bidding_project_value['project_posting_date'])).'</span>' : $this->config->item('project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($open_bidding_project_value['project_posting_date'])).'</span>';?></small><?php
				if(!empty($location)){
				?><small><i class="fas fa-map-marker-alt"></i><?php echo $location;?></small><?php
				}
				?><small><i class="fas fa-bullhorn"></i><?php
				$project_bid_count = get_project_bid_count($open_bidding_project_value['project_id'],$open_bidding_project_value['project_type']);
				$bid_history_total_bids = $project_bid_count."&nbsp;";
				if ($open_bidding_project_value['project_type'] == 'fulltime') {
					 if($project_bid_count == 0){
						$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_0_applications_received');
					}else if($project_bid_count == 1){
						$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_1_application_received');
					}else if($project_bid_count >= 2 && $project_bid_count <= 4){
						$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_2_to_4_applications_received');
					}else {
						$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_5_or_more_applications_received');
					}
				} else{
				    if($project_bid_count == 0){
						$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_0_bids_received');
					}else if($project_bid_count == 1){
						$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_1_bid_received');
					}else if($project_bid_count >=2 && $project_bid_count <=4){
						$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_2_to_4_bids_received');
					}else {
						$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_5_or_more_bids_received');
					}
				} echo $bid_history_total_bids;?></small><?php
					// count the number of number of hires of project
					$project_hires_count = get_project_hires_count($open_bidding_project_value['project_id'],$open_bidding_project_value['project_type']);
					$project_total_hires = $project_hires_count."&nbsp;";
					/* if($project_hires_count > 1 || $project_hires_count == 0){
						$project_total_hires = $project_total_hires.$this->config->item('project_details_page_in_progress_project_hire_plural_po_view');
					} else {
						$project_total_hires = $project_total_hires.$this->config->item('project_details_page_in_progress_project_hire_singular_po_view');
					} */
					if($open_bidding_project_value['project_type'] == 'fulltime')
					{
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
					
					
				?><?php
					if($project_hires_count != 0) {
				?><small><i class="fas fa-tasks"></i><?php echo $project_total_hires; ?></small><?php		
					}
				?><?php
				$project_value = get_total_project_value_po($open_bidding_project_value['project_id'],$open_bidding_project_value['project_type']);
				if($open_bidding_project_value['project_type'] == 'fulltime' && floatval($project_value) != 0){ 
					$project_total_amt_txt = $this->config->item('fulltime_projects_employer_view_total_project_value').'<span class="touch_line_break">'.number_format($project_value, 0, '', ' ')." ".CURRENCY.'</span>';
				} 
				if(floatval($project_value) != 0) {
				?><small><i class="fas fa-coins"></i><?php echo $project_total_amt_txt; ?></small><?php
					}
				if($open_bidding_project_value['total_active_disputes'] > 0){
				?><small><i class="fas fa-exclamation-triangle" style="color:red;"></i></small><?php
				}else{
					$get_latest_closed_dispute_record = array();

					if($open_bidding_project_value['project_type'] == 'fixed' || $open_bidding_project_value['project_type'] == 'hourly' ){
						$dispute_close_conditions = array('disputed_project_id'=>$open_bidding_project_value['project_id'],'project_owner_id_of_disputed_project'=>$open_bidding_project_value['project_owner_id']);
					}else if($open_bidding_project_value['project_type'] == 'fulltime'){
						$dispute_close_conditions = array('disputed_fulltime_project_id'=>$open_bidding_project_value['project_id'],'employer_id_of_disputed_fulltime_project'=>$open_bidding_project_value['project_owner_id']);
					}
					$get_latest_closed_dispute_record = get_latest_project_closed_dispute($open_bidding_project_value['project_type'],$dispute_close_conditions); 
					
					if(!empty($get_latest_closed_dispute_record) && ($get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_sp' || $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_po'|| $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_employee' || $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_employer'||$get_latest_closed_dispute_record['dispute_status'] == 'automatic_decision'|| $get_latest_closed_dispute_record['dispute_status'] == 'parties_agreement')){
					?><small><i class="fas fa-balance-scale"></i></small><?php	
					}if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && (($open_bidding_project_value['project_type'] != 'fulltime' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['sp_winner_id_of_disputed_project']) || ($open_bidding_project_value['project_type'] == 'fulltime'&& $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['employee_winner_id_of_disputed_fulltime_project']))){
					?><small><i class="fas fa-balance-scale-left"></i></small><?php	
					}
					if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && (($open_bidding_project_value['project_type'] != 'fulltime' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['project_owner_id_of_disputed_project']) || ($open_bidding_project_value['project_type'] != 'fulltime' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['employer_id_of_disputed_fulltime_project']))){  ?><small><i class="fas fa-balance-scale-right"></i></small><?php	
					}	
				}
				?></label>
				<div class="default_project_description desktop-secreen">
					<p><?php 
                     echo character_limiter($open_bidding_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_desktop'));
					?></p>
				</div>
				<div class="default_project_description ipad-screen">
					<p><?php 
                     echo character_limiter($open_bidding_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_tablet'));
					?></p>
				</div>
				<div class="default_project_description mobile-screen">
					<p><?php
                     echo character_limiter($open_bidding_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_mobile'));
				
					?></p>
				</div>
			<div class="clearfix"></div>
			<?php
				$show_prolong_structure = "display:none;";
			if(($open_bidding_project_value['featured'] == 'Y' && $featured_max != 0 && $featured_max > time() && $open_bidding_project_value['hidden'] == 'N') || ($open_bidding_project_value['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time() && $open_bidding_project_value['hidden'] == 'N')){
				$show_prolong_structure = "display:block;";
			}
			?>	
			
			<div class="pDSheduled project_upgrade_prolong" id="<?php echo "project_upgrade_prolong_".$open_bidding_project_value['project_id']?>" style="<?php echo $show_prolong_structure; ?>">
			<ul id="<?php echo "open_for_bidding_project_upgrade_prolong_availability_".$open_bidding_project_value['project_id'] ?>">
				<?php
				
				if($open_bidding_project_value['featured'] == 'Y' && $featured_max != 0 && $featured_max > time() && $open_bidding_project_value['hidden'] == 'N'){
				
				?>
				<li>
					<label>
						<span class="default_black_bold">
						<?php echo $this->config->item('project_featured_upgrade_txt_po_dashboard_myprojects_section_view') ;?>
						</span><small class="default_black_regular"><?php echo $this->config->item('project_featured_upgrade_expires_on_txt_po_dashboard_myprojects_section_view')."&nbsp;".date(DATE_TIME_FORMAT,$featured_max); ?></small>
					</label>
					<?php
						if(!empty($featured_valid_time_arr)) {
					?>
					<button data-po-id="<?php echo Cryptor::doEncrypt($open_bidding_project_value['project_owner_id']); ?>" type="button" data-project-type="<?php echo $open_bidding_project_value['project_type'] ?>" data-attr= "<?php echo $open_bidding_project_value['project_id']  ?>" class="btn upgrade_project default_btn blue_btn btnBold btn_style_2_16" data-action-type="prolong_availability_featured"><?php echo $this->config->item('project_featured_upgrade_prolong_availability_txt_po_dashboard_myprojects_section_view') ;?></button>
					<?php
						}
					?>
				</li>
				<?php
				}
				
				if($open_bidding_project_value['urgent'] == 'Y'&& $urgent_max != 0 && $urgent_max > time() && $open_bidding_project_value['hidden'] == 'N'){
				?>
				<li>
					<label>
						<span class="default_black_bold">
						<?php echo $this->config->item('project_urgent_upgrade_txt_po_dashboard_myprojects_section_view') ;?>
						</span><small class="default_black_regular">
						<?php echo $this->config->item('project_urgent_upgrade_expires_on_txt_po_dashboard_myprojects_section_view')."&nbsp;".date(DATE_TIME_FORMAT,$urgent_max); ?></small>
					</label>
					<?php
						if(!empty($urgent_valid_time_arr)) {
					?>
					<button type="button" class="btn upgrade_project default_btn blue_btn btnBold btn_style_2_16" data-po-id="<?php echo Cryptor::doEncrypt($open_bidding_project_value['project_owner_id']); ?>" data-project-type="<?php echo $open_bidding_project_value['project_type'] ?>" data-attr= "<?php echo $open_bidding_project_value['project_id']  ?>" data-action-type="prolong_availability_urgent"><?php echo $this->config->item('project_urgent_upgrade_prolong_availability_txt_po_dashboard_myprojects_section_view') ;?></button>
					<?php
						}
					?>
				</li>
				<?php
				}
				?>
			</ul>
			</div>
				<div class="badgeAction">
				<?php
				$show_upgrade_badge_structure = "display:none;";
				if(($open_bidding_project_value['featured'] == 'Y'&& $featured_max != 0 && $featured_max > time()) || ($open_bidding_project_value['urgent'] == 'Y'&& $urgent_max != 0 && $urgent_max > time()) || $open_bidding_project_value['hidden'] == 'Y'){
					$show_upgrade_badge_structure = "display:block;";
				}
				?>
					<label style="<?php echo $show_upgrade_badge_structure; ?>" class="badgeOnly" id="<?php echo "open_for_bidding_project_upgrade_badges_label".$open_bidding_project_value['project_id'] ?>">								
						<div class="default_project_badge" id="<?php echo "open_for_bidding_project_upgrade_badges_".$open_bidding_project_value['project_id'] ?>">
							<?php
							if($open_bidding_project_value['hidden'] == 'N'){
								if($open_bidding_project_value['featured'] == 'Y'&& $featured_max != 0 && $featured_max > time()){
							?>
								<button type="button" class="btn badge_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></button>
							<?php
								}
							?>
							<?php
								if($open_bidding_project_value['urgent'] == 'Y'&& $urgent_max != 0 && $urgent_max > time()){
							?>
								<button type="button" class="btn badge_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></button>
							<?php
								}
							}	
							?>
							<?php
							if($open_bidding_project_value['sealed'] == 'Y'){
							?>
							<button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
							<?php
							}
							?>
							<?php
							if($open_bidding_project_value['hidden'] == 'Y'){
							?>
							<button type="button" class="btn badge_hidden"><?php echo $this->config->item('post_project_page_upgrade_type_hidden'); ?></button>
							<?php
							}
							?>
						</div>
					</label>
					
					<label class="actionBtn">
						<div class="actOnly">
							<div class="myAction">
								<div class="dropdown">
									<button class="btn dropdown-toggle default_btn dark_blue_btn noPaddingtb" type="button"  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<?php echo $this->config->item('action'); ?>
									</button>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"  id="<?php echo "open_for_bidding_project_drop_down_action_".$open_bidding_project_value['project_id'] ?>">
										<a class="dropdown-item edit_project" data-po-id="<?php echo Cryptor::doEncrypt($open_bidding_project_value['project_owner_id']); ?>" data-project-type="<?php echo $open_bidding_project_value['project_type'] ?>" data-attr= "<?php echo $open_bidding_project_value['project_id']  ?>" style="cursor:pointer"><?php echo $this->config->item('myprojects_section_open_for_bidding_tab_option_edit_project_po_view'); ?>
										</a>
										<?php
										$cancel_open_for_bidding_project_modal_cancel_btn_txt = $this->config->item('cancel_btn_txt');?>
										<a class="dropdown-item cancel_project" data-po-id="<?php echo Cryptor::doEncrypt($open_bidding_project_value['project_owner_id']); ?>" data-project-type="<?php echo $open_bidding_project_value['project_type'] ?>"  data-project_title = "<?php echo $open_bidding_project_value['project_title']; ?>"  data-project-type="<?php echo $open_bidding_project_value['project_type']  ?>" data-modal-cancel-button-txt = "<?php echo $cancel_open_for_bidding_project_modal_cancel_btn_txt; ?>"  data-project-status="open" data-attr= "<?php echo $open_bidding_project_value['project_id']  ?>" style="cursor:pointer"><?php
										echo $this->config->item('myprojects_section_open_for_bidding_tab_option_cancel_project_po_view');
										?></a>
										<?php
										if($open_bidding_project_value['hidden'] == 'N'){	
											if($open_bidding_project_value['featured'] == 'N' && $open_bidding_project_value['urgent'] == 'N' && (($featured_max == 0 && $urgent_max==0) || ($featured_max < time() && $urgent_max <= time())) && (!empty($featured_valid_time_arr) || !empty($urgent_valid_time_arr))){
										?>
											<a data-po-id="<?php echo Cryptor::doEncrypt($open_bidding_project_value['project_owner_id']); ?>"  data-project-type="<?php echo $open_bidding_project_value['project_type'] ?>"  id="<?php echo "upgrade_project_".$open_bidding_project_value['project_id'] ?>" class="dropdown-item upgrade_project" style="cursor:pointer" data-attr= "<?php echo $open_bidding_project_value['project_id']  ?>" data-action-type="upgrade_project"><?php echo $this->config->item('myprojects_section_open_for_bidding_tab_option_upgrade_project_po_view'); ?></a>
										<?php
											}
											elseif($open_bidding_project_value['featured'] == 'N' && $open_bidding_project_value['urgent'] == 'Y' && ($featured_max == 0 || $featured_max < time()) && !empty($featured_valid_time_arr) ){
										?>
										
											<a data-po-id="<?php echo Cryptor::doEncrypt($open_bidding_project_value['project_owner_id']); ?>" data-project-type="<?php echo $open_bidding_project_value['project_type'] ?>"  id="<?php echo "upgrade_project_".$open_bidding_project_value['project_id'] ?>" class="dropdown-item upgrade_project" style="cursor:pointer" data-attr= "<?php echo $open_bidding_project_value['project_id']  ?>" data-action-type="upgrade_as_featured_project"><?php echo $this->config->item('myprojects_section_open_for_bidding_tab_option_upgrade_as_featured_project_po_view'); ?></a>
										<?php
										
											}elseif($open_bidding_project_value['featured'] == 'Y' && $open_bidding_project_value['urgent'] == 'N' && ($urgent_max == 0 || $urgent_max < time()) && !empty($urgent_valid_time_arr)){
										?>
											<a data-po-id="<?php echo Cryptor::doEncrypt($open_bidding_project_value['project_owner_id']); ?>" data-project-type="<?php echo $open_bidding_project_value['project_type'] ?>"  id="<?php echo "upgrade_project_".$open_bidding_project_value['project_id'] ?>" class="dropdown-item upgrade_project" style="cursor:pointer" data-attr= "<?php echo $open_bidding_project_value['project_id']  ?>" data-action-type="upgrade_as_urgent_project"><?php echo $this->config->item('myprojects_section_open_for_bidding_tab_option_upgrade_as_urgent_project_po_view'); ?></a>
										<?php
											}
										}	
										?>
										<a  data-po-id="<?php echo Cryptor::doEncrypt($open_bidding_project_value['project_owner_id']); ?>" data-project-type="<?php echo $open_bidding_project_value['project_type'] ?>"  class="dropdown-item repost_project" data-project-status ="open"  data-attr= "<?php echo $open_bidding_project_value['project_id']  ?>" style="cursor:pointer">
										<?php
										echo $this->config->item('myprojects_section_open_for_bidding_tab_option_copy_into_new_project_po_view');
										?>
										</a>
									</div>
								</div>
							</div>
						</div>
					</label>
					<div class="clearfix"></div>
				</div>
			
		</div>
	</div>										
	
<?php
		$open_for_bidding_counter ++;
	}
}else{
?>
<div class="default_blank_message">
<?php echo $this->config->item('no_open_bidding_project_message')?>
</div>
<?php
}
if($page_type == 'dashboard' && $open_bidding_project_count > $this->config->item('user_dashboard_po_view_open_bidding_projects_listing_limit') ) {
?>	
<div class="viewMore">
	<a class="default_btn blue_btn btn_style_5_35 btnBold" href="<?php echo base_url($this->config->item('myprojects_page_url')); ?>"><?php echo $this->config->item('view_all_projects'); ?></a>
</div>
<?php
}
if($page_type == 'my_projects' && $open_bidding_project_count > 0){	
?>	
<!-- Pagination Start -->
<div class="pagnOnly">
	<div class="row">
		<?php
		$manage_paging_width_class = "col-md-12 col-sm-12"; 
		if(!empty($open_bidding_pagination_links)){
		$manage_paging_width_class = "col-md-7 col-sm-7"; 
		}
		?>
		<div class="<?php echo $manage_paging_width_class; ?> col-12">
			<?php
				$rec_per_page = ($open_bidding_project_count > $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit')) ? $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') : $open_bidding_project_count;
			?>
			<div class="pageOf">
				<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $open_bidding_project_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
			</div>
		</div>
		<?php 
		if(!empty($open_bidding_pagination_links)){
		?>
		<div class="col-md-5 col-sm-5 col-12">
			<div class="modePage">
				<?php echo $open_bidding_pagination_links; ?>
			</div>
		</div>
		<?php
		}
		?>
	</div>
</div>
<!-- Pagination End -->	
<?php
}	
?>	