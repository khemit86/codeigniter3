<?php
#####
/* This file is using for dedicated page of project owner my projects section*/
/* Filename: application\modules\projects\controllers\Projects.php */
/* Action: my_projects_listing */
/* This file include on "application\modules\projects\views\my_projects.php" */
if(!empty($awaiting_moderation_project_data)){
	$total_awaiting_moderation_projects = count($awaiting_moderation_project_data);
	$awaiting_moderation_counter = 1;
	foreach($awaiting_moderation_project_data as $awaiting_moderation_project_key=>$awaiting_moderation_project_value){
		$featured_class = '';
		$location = '';
		$last_record_class_remove_border_bottom = '';
		$last_record_class = '';
		if($awaiting_moderation_project_value['featured'] == 'Y') {
			$featured_class = 'opBg';
		}
		if($awaiting_moderation_counter == $total_awaiting_moderation_projects){
			$last_record_class_remove_border_bottom = 'bbNo';
			if($page_type == 'dashboard' && $awaiting_moderation_project_count <= $this->config->item('user_dashboard_po_view_awaiting_moderation_projects_listing_limit') ) {
				$last_record_class = 'padding_bottom0';
			}
			
			
		}
		if(!empty($awaiting_moderation_project_value['county_name'])){
		if(!empty($awaiting_moderation_project_value['locality_name'])){
			$location .= $awaiting_moderation_project_value['locality_name'];
		}
		if(!empty($awaiting_moderation_project_value['postal_code'])){
			$location .= '&nbsp;'.$awaiting_moderation_project_value['postal_code'] .',&nbsp;';
		}else if(!empty($awaiting_moderation_project_value['locality_name']) && empty($awaiting_moderation_project_value['postal_code'])){
			$location .= ',&nbsp';
		}
		$location .= $awaiting_moderation_project_value['county_name'];
		}
		
?>	
	<div class="tabContent">
		<!--<div class="default_project_row <?php echo $featured_class." ".$last_record_class_remove_border_bottom." ". $last_record_class; ?>">-->
		
		<div class="default_project_row <?php echo $featured_class; ?>">
			<div class="default_project_title">
			<a class="default_project_title_link" target="_blank" href="<?php echo base_url().$this->config->item('project_detail_page_url')."?id=".$awaiting_moderation_project_value['project_id']; ?>">
				<?php echo htmlspecialchars($awaiting_moderation_project_value['project_title'], ENT_QUOTES); ?></a>
			</div>
		
			<label class="default_short_details_field">
				<small><i class="far fa-clock"></i><?php echo $awaiting_moderation_project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_submission_date_for_moderation').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($awaiting_moderation_project_value['project_submission_to_moderation_date'])).'</span>' : $this->config->item('project_submission_date_for_moderation').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($awaiting_moderation_project_value['project_submission_to_moderation_date'])).'</span>';?></small><small><i class="fa fa-file-text-o"></i><?php 
					if($awaiting_moderation_project_value['project_type'] == 'fixed'){
						echo $this->config->item('project_listing_window_snippet_fixed_budget_project');
					}else if($awaiting_moderation_project_value['project_type'] == 'hourly'){
						echo $this->config->item('project_listing_window_snippet_hourly_based_budget_project');
					}else if($awaiting_moderation_project_value['project_type'] == 'fulltime'){
						echo $this->config->item('project_listing_window_snippet_fulltime_project');
					}		
						
					if($awaiting_moderation_project_value['confidential_dropdown_option_selected'] == 'Y'){
						if($awaiting_moderation_project_value['project_type'] == 'fixed'){
							echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected').'</span>';
							}else if($awaiting_moderation_project_value['project_type'] == 'hourly'){
							echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_confidential_option_selected').'</span>';
						}else if($awaiting_moderation_project_value['project_type'] == 'fulltime'){
							echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_confidential_option_selected').'</span>';
						}
					}else if($awaiting_moderation_project_value['not_sure_dropdown_option_selected'] == 'Y'){
						if($awaiting_moderation_project_value['project_type'] == 'fixed'){
						echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected').'</span>';
						}else if($awaiting_moderation_project_value['project_type'] == 'hourly'){
						echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_not_sure_option_selected').'</span>';
						}else if($awaiting_moderation_project_value['project_type'] == 'fulltime'){
							echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_not_sure_option_selected').'</span>';
						}
					}else{
						$budget_range = '';
						if($awaiting_moderation_project_value['max_budget'] != 'All'){
							if($awaiting_moderation_project_value['project_type'] == 'hourly'){
								$budget_range = '';
								if($this->config->item('post_project_budget_range_between')){
									$budget_range .= $this->config->item('post_project_budget_range_between');
								}
								$budget_range .= '<span class="touch_line_break">'.number_format($awaiting_moderation_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($awaiting_moderation_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour').'</span>';
							}else if($awaiting_moderation_project_value['project_type'] == 'fulltime'){
								$budget_range = '';
								if($this->config->item('post_project_budget_range_between')){
									$budget_range .= $this->config->item('post_project_budget_range_between');
								}
								$budget_range .= '<span class="touch_line_break">'.number_format($awaiting_moderation_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($awaiting_moderation_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_month').'</span>';
							}else{
								$budget_range = '';
								if($this->config->item('post_project_budget_range_between')){
									$budget_range .= $this->config->item('post_project_budget_range_between');
								}
								$budget_range .= '<span class="touch_line_break">'.number_format($awaiting_moderation_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($awaiting_moderation_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
							}
						}else{
							if($awaiting_moderation_project_value['project_type'] == 'hourly'){
								$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($awaiting_moderation_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span>';
							}else if($awaiting_moderation_project_value['project_type'] == 'fulltime'){
								$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($awaiting_moderation_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span>';
							}else{
								$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($awaiting_moderation_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
							}
						}
						echo $budget_range;
					}
					
					if($awaiting_moderation_project_value['escrow_payment_method'] == 'Y') {
						echo '<span class="touch_line_break">'.$this->config->item('my_projects_project_payment_method_escrow_system').'</span>';
					}
					if($awaiting_moderation_project_value['offline_payment_method'] == 'Y') {
						echo '<span class="touch_line_break">'.'<span class="touch_line_break">'.$this->config->item('my_projects_project_payment_method_offline_system').'</span>';
					}
					?></small><?php
				if(!empty($location)){
				?><small><i class="fas fa-map-marker-alt"></i><?php echo $location;?></small><?php
				}
				?></label>
				<div class="default_project_description desktop-secreen">
					<p><?php 
                     echo character_limiter($awaiting_moderation_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_desktop'));
					?></p>
				</div>
				<div class="default_project_description ipad-screen">
					<p><?php 
                     echo character_limiter($awaiting_moderation_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_tablet'));
					?></p>
				</div>
				<div class="default_project_description mobile-screen">
					<p><?php 
                     echo character_limiter($awaiting_moderation_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_mobile'));
					?></p>
				</div>
			<div class="clearfix"></div>
			<?php
			if($awaiting_moderation_project_value['featured'] == 'Y' || $awaiting_moderation_project_value['urgent'] == 'Y' || $awaiting_moderation_project_value['sealed'] == 'Y' || $awaiting_moderation_project_value['hidden'] == 'Y'){
			?>
			
				<div class="badgeAction">
					<div class="badgeOnly">									
						<div class="default_project_badge">
							<?php
							if($awaiting_moderation_project_value['featured'] == 'Y') {
							?>
							<button type="button" class="btn badge_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></button>
							<?php
							}
							?>
							<?php
							if($awaiting_moderation_project_value['urgent'] == 'Y') {
							?>
							<button type="button" class="btn badge_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></button>
							<?php
							} 
							?>
							<?php
							if($awaiting_moderation_project_value['sealed'] == 'Y') {
							?>
							<button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
							<?php
							}
							?>
							<?php
							if($awaiting_moderation_project_value['hidden'] == 'Y') {
							?>
							<button type="button" class="btn badge_hidden"><?php echo $this->config->item('post_project_page_upgrade_type_hidden'); ?></button>
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
	</div>
<?php
		$awaiting_moderation_counter++;
	}
}else{
?>
<div class="default_blank_message">
	<?php echo $this->config->item('no_awaiting_moderation_project_message')?>
</div>
<?php
}if($page_type == 'dashboard' && $awaiting_moderation_project_count > $this->config->item('user_dashboard_po_view_awaiting_moderation_projects_listing_limit') ) {	
?>
<div class="viewMore">
	<a class="default_btn blue_btn btn_style_5_35 btnBold" href="<?PHP echo base_url($this->config->item('myprojects_page_url')); ?>"><?php echo $this->config->item('view_all_projects'); ?></a>
</div>
<?php
}	
if($page_type == 'my_projects' && $awaiting_moderation_project_count > 0){	
?>	
<!-- Pagination Start -->
<div class="pagnOnly">
	<div class="row">
		<?php
		$manage_paging_width_class = "col-md-12 col-sm-12"; 
		if(!empty($awaiting_moderation_pagination_links)){
		$manage_paging_width_class = "col-md-7 col-sm-7"; 
		}
		?>
		<div class="<?php echo $manage_paging_width_class; ?> col-12">
			<?php
				$rec_per_page = ($awaiting_moderation_project_count > $this->config->item('my_projects_po_view_awaiting_moderation_projects_listing_limit')) ? $this->config->item('my_projects_po_view_awaiting_moderation_projects_listing_limit') : $awaiting_moderation_project_count;
			?>
			<div class="pageOf">
				<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $awaiting_moderation_project_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
			</div>
		</div>
		<?php 
		if(!empty($awaiting_moderation_pagination_links)){
		?>
		<div class="col-md-5 col-sm-5 col-12">
			<div class="modePage">
				<?php echo $awaiting_moderation_pagination_links; ?>
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