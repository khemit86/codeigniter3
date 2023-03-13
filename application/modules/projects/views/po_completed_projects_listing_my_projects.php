<?php
#####
/* This file is using for dedicated page of project owner my projects section*/
/* Filename: application\modules\projects\controllers\Projects.php */
/* Action: my_projects_listing */
/* This file include on "application\modules\projects\views\my_projects.php" */

if(!empty($completed_project_data)){
	$total_completed_projects = count($completed_project_data);
	$completed_counter = 1;
	foreach($completed_project_data as $completed_project_key => $completed_project_value){
		$location = '';
		$last_record_class_remove_border_bottom = '';
		$last_record_class = '';
		
		if($completed_counter == $total_completed_projects){
			$last_record_class_remove_border_bottom = 'bbNo';
			if($page_type == 'dashboard' && $completed_project_count <= $this->config->item('user_dashboard_po_view_completed_projects_listing_limit')) {
				$last_record_class = 'padding_bottom0';
			}
		}
		
		
		if(!empty($completed_project_value['county_name'])){
		if(!empty($completed_project_value['locality_name'])){
			$location .= $completed_project_value['locality_name'];
		}
		if(!empty($completed_project_value['postal_code'])){
			$location .= '&nbsp;'.$completed_project_value['postal_code'] .',&nbsp;';
		}else if(!empty($completed_project_value['locality_name']) && empty($completed_project_value['postal_code'])){
			$location .= ',&nbsp';
		}
		$location .= $completed_project_value['county_name'];
		}
?>
<div class="tabContent">
<!--<div class="default_project_row <?php echo $last_record_class_remove_border_bottom." ". $last_record_class; ?>" id="<?php echo "completed_project_".$completed_project_value['project_id'] ?>" >-->

<div class="default_project_row" id="<?php echo "completed_project_".$completed_project_value['project_id'] ?>" >
	<div class="default_project_title">
	<a class="default_project_title_link" target="_blank" href="<?php echo base_url().$this->config->item('project_detail_page_url')."?id=".$completed_project_value['project_id']; ?>">
	<?php echo htmlspecialchars($completed_project_value['project_title'], ENT_QUOTES);?></a>
	</div>
	<label class="default_short_details_field">
		<small><i class="fa fa-file-text-o"></i><?php 
			// if($completed_project_value['project_type'] == 'fixed'){
			// 	echo $this->config->item('completed_project_listing_window_snippet_fixed_budget_project_po_view');
			// }else if($completed_project_value['project_type'] == 'hourly'){
			// 	echo $this->config->item('completed_project_listing_window_snippet_hourly_based_budget_project_po_view');
			// }else if($completed_project_value['project_type'] == 'fulltime'){
			// 	echo $this->config->item('completed_project_listing_window_snippet_fulltime_project_po_view');
			// }

			if($completed_project_value['project_type'] == 'fixed'){
				echo $this->config->item('project_listing_window_snippet_fixed_budget_project');
			} else if($completed_project_value['project_type'] == 'hourly'){
				echo $this->config->item('project_listing_window_snippet_hourly_based_budget_project');
			} /* else if($completed_project_value['project_type'] == 'fulltime'){
				echo $this->config->item('project_listing_window_snippet_fulltime_project')."&nbsp;";
			} */
			if($completed_project_value['confidential_dropdown_option_selected'] == 'Y'){
				if($completed_project_value['project_type'] == 'fixed'){
					echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected').'</span>';
					}else if($completed_project_value['project_type'] == 'hourly'){
					echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_confidential_option_selected').'</span>';
				}/* else if($completed_project_value['project_type'] == 'fulltime'){
					echo $this->config->item('displayed_text_fulltime_project_details_page_salary_confidential_option_selected');
				} */
			}else if($completed_project_value['not_sure_dropdown_option_selected'] == 'Y'){
				if($completed_project_value['project_type'] == 'fixed'){
				echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected').'</span>';
				}else if($completed_project_value['project_type'] == 'hourly'){
				echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_not_sure_option_selected').'</span>';
				}/* else if($completed_project_value['project_type'] == 'fulltime'){
					echo $this->config->item('displayed_text_fulltime_project_details_page_salary_not_sure_option_selected');
				} */
			}else{
				$budget_range = '';
				if($completed_project_value['max_budget'] != 'All'){
					if($completed_project_value['project_type'] == 'hourly'){
						$budget_range = '';
						if($this->config->item('post_project_budget_range_between')){
							$budget_range .= $this->config->item('post_project_budget_range_between');
						}
						$budget_range .= '<span class="touch_line_break">'.number_format($completed_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($completed_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour').'</span>';
					}/* else if($completed_project_value['project_type'] == 'fulltime'){
						$budget_range = '';
						if($this->config->item('post_project_budget_range_between')){
							$budget_range .= $this->config->item('post_project_budget_range_between').'&nbsp;';
						}
						$budget_range .= number_format($completed_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month').'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($completed_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_month');
					} */else{
						$budget_range = '';
						if($this->config->item('post_project_budget_range_between')){
							$budget_range .= $this->config->item('post_project_budget_range_between');
						}
						$budget_range .= '<span class="touch_line_break">'.number_format($completed_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($completed_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
					}
				}else{
					if($completed_project_value['project_type'] == 'hourly'){
						$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($completed_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span>';
					}/* else if($completed_project_value['project_type'] == 'fulltime'){
						$budget_range = $this->config->item('post_project_budget_range_more_then').'&nbsp'.number_format($completed_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_month');
					} */else{
						$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($completed_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
					}
				}
				echo $budget_range;
			}
			?></small><small><i class="far fa-clock"></i><?php
		//$project_start_date = get_latest_completed_project_start_date($completed_project_value['project_id'],$completed_project_value['project_type']);
		 /* if($completed_project_value['project_type'] == 'fulltime'){
			echo $this->config->item('fulltime_project_posted_on').date(DATE_TIME_FORMAT,strtotime($completed_project_value['project_start_date']));
		}else{ */
			echo $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($completed_project_value['project_start_date'])).'</span>';
		//} ?></small><small><i class="fa fa-clock-o project_expired_or_cancel_or_completed_date_icon_size"></i><?php
		
		/*  if($completed_project_value['project_type'] == 'fulltime'){
			echo $this->config->item('fulltime_project_completion_date').date(DATE_TIME_FORMAT,strtotime($completed_project_value['project_completion_date']));
		}else{ */
			echo $this->config->item('project_completion_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($completed_project_value['project_completion_date'])).'</span>';
		//} ?></small><?php
			if(!empty($location)):
		?><small><i class="fas fa-map-marker-alt"></i><?=$location?></small><?php
			endif;
		?><small><i class="fas fa-bullhorn"></i><?php
		// count the number of bid for bid history
		$project_bid_count = get_project_bid_count($completed_project_value['project_id'],		 		$completed_project_value['project_type']);
		$bid_history_total_bids = $project_bid_count."&nbsp;";
		if($project_bid_count == 0){
			$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_0_bids_received');
		}else if($project_bid_count == 1){
			$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_1_bid_received');
		}else if($project_bid_count >=2 && $project_bid_count <=4){
			$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_2_to_4_bids_received');
		}else {
			$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_5_or_more_bids_received');
		}
		echo $bid_history_total_bids; ?></small><small><i class="fas fa-tasks"></i><?php
		// count the number of number of hires of project
		$project_hires_count = get_project_hires_count($completed_project_value['project_id'],$completed_project_value['project_type']);
		$project_total_hires = $project_hires_count."&nbsp;";
		if ($completed_project_value['project_type'] == 'fulltime') {
						
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
		echo $project_total_hires;?></small><?php
		$project_value = get_total_project_value_po($completed_project_value['project_id'],$completed_project_value['project_type']);
		if($completed_project_value['project_type'] == 'fixed' && floatval($project_value) != 0){ 
			$total_paid_amt_txt = $this->config->item('my_projects_po_view_total_project_value').'<span class="touch_line_break">'.number_format($project_value, 0, '', ' ')." ".CURRENCY.'</span>';
		} else if($completed_project_value['project_type'] == 'hourly' && floatval($project_value) != 0) {
			$total_paid_amt_txt = $this->config->item('my_projects_po_view_total_project_value').'<span class="touch_line_break">'.number_format($project_value, 0, '', ' ')." ".CURRENCY.'</span>';
		}
		if(floatval($project_value) != 0) {
		?><small>
			<i class="fas fa-coins"></i><?php echo $total_paid_amt_txt; ?></small><?php
		}
		?><?php
		if($completed_project_value['total_active_disputes'] > 0){
		?><small><i class="fas fa-exclamation-triangle" style="color:red;"></i></small><?php
		}else{
			$get_latest_closed_dispute_record = array();
			if($completed_project_value['project_type'] == 'fixed' || $completed_project_value['project_type'] == 'hourly'){
				$get_latest_closed_dispute_record = get_latest_project_closed_dispute($completed_project_value['project_type'],array('disputed_project_id'=>$completed_project_value['project_id'],'project_owner_id_of_disputed_project'=>$completed_project_value['project_owner_id'])); 
			}

		
			if(!empty($get_latest_closed_dispute_record) && ($get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_sp' || $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_po' || $get_latest_closed_dispute_record['dispute_status'] == 'automatic_decision' || $get_latest_closed_dispute_record['dispute_status'] == 'parties_agreement')){
		?><small><i class="fas fa-balance-scale"></i></small><?php	
			}
			
			if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['project_owner_id_of_disputed_project']) { ?>
			<small><i class="fas fa-balance-scale-right"></i></small><?php	
			}
			if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['sp_winner_id_of_disputed_project']){
			?><small><i class="fas fa-balance-scale-left"></i></small><?php
			}
		}	
		?></label>
	
		<?php
		//$description = htmlspecialchars($completed_project_value['project_description'], ENT_QUOTES);
		?>
		<div class="default_project_description desktop-secreen">
			<p><?php 
                         echo character_limiter($completed_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_desktop'));
			//echo limitString($description,$this->config->item('dashboard_my_projects_section_project_description_character_limit_desktop'));?></p>
		</div>
		<div class="default_project_description ipad-screen">
			<p><?php 
                         echo character_limiter($completed_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_tablet'));
			//echo limitString($description,$this->config->item('dashboard_my_projects_section_project_description_character_limit_tablet'));?></p>
		</div>
		<div class="default_project_description mobile-screen">
			<p><?php 
                         echo character_limiter($completed_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_mobile'));
			//echo limitString($description,$this->config->item('dashboard_my_projects_section_project_description_character_limit_mobile'));?></p>
		</div>
	
	<div class="clearfix"></div>
	<div class="badgeAction">
		<?php
		if($completed_project_value['sealed'] == 'Y' || $completed_project_value['hidden'] == 'Y') {
		?>
		<label class="badgeOnly">
			<div class="default_project_badge">
				<?php
				if($completed_project_value['sealed'] == 'Y') {
				?>
				<button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
				<?php
				}
				?>
				<?php
				if($completed_project_value['hidden'] == 'Y') {
				?>
				<button type="button" class="btn badge_hidden"><?php echo $this->config->item('post_project_page_upgrade_type_hidden'); ?></button>
				<?php
				}
				?>
			</div>
		</label>
		<?php
		}
		?>
		<label class="actionBtn">
			<div class="actOnly">
				<div class="myAction">
					<div class="dropdown">
						<button class="btn dropdown-toggle default_btn dark_blue_btn noPaddingtb" type="button"  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?php echo $this->config->item('action'); ?>
						</button>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" >
							
							<a  data-po-id="<?php echo Cryptor::doEncrypt($completed_project_value['project_owner_id']); ?>" data-project-type="<?php echo $completed_project_value['project_type'] ?>"  class="dropdown-item repost_project" data-project-status ="completed"  data-attr= "<?php echo $completed_project_value['project_id']  ?>" style="cursor:pointer">
							<?php
							echo $this->config->item('myprojects_section_completed_tab_option_copy_into_new_project_po_view');
							?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</label>
	</div>
</div>
</div>										

<?php
$completed_counter++;
}
}else{
?>
<div class="default_blank_message">
<?php echo $this->config->item('no_completed_project_message')?>
</div>
<?php
}
if($page_type == 'dashboard' && $completed_project_count > $this->config->item('user_dashboard_po_view_completed_projects_listing_limit')) {
?>
<div class="viewMore">
	<a class="default_btn blue_btn btn_style_5_35 btnBold" href="<?PHP echo base_url($this->config->item('myprojects_page_url')); ?>"><?php echo $this->config->item('view_all_projects'); ?></a>
</div>
<?php
}
	
if($page_type == 'my_projects' && $completed_project_count > 0){	
?>	
<!-- Pagination Start -->
<div class="pagnOnly">
	<div class="row">
		<?php
		$manage_paging_width_class = "col-md-12 col-sm-12"; 
		if(!empty($completed_pagination_links)){
		$manage_paging_width_class = "col-md-7 col-sm-7"; 
		}
		?>
		<div class="<?php echo $manage_paging_width_class; ?> col-12">
			<?php
				$rec_per_page = ($completed_project_count > $this->config->item('my_projects_po_view_completed_projects_listing_limit')) ? $this->config->item('my_projects_po_view_completed_projects_listing_limit') : $completed_project_count;
			?>
			<div class="pageOf">
				<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $completed_project_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
			</div>
		</div>
		<?php 
		if(!empty($completed_pagination_links)){
		?>
		<div class="col-md-5 col-sm-5 col-12">
			<div class="modePage">
				<?php echo $completed_pagination_links; ?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<!-- Pagination End -->	
<?php
}	
?>	
