<?php	
#####
/* This file is using for dedicated page of project owner my projects section*/
/* Filename: application\modules\projects\controllers\Projects.php */
/* Action: my_projects_listing */
/* This file include on "application\modules\projects\views\my_projects.php" */
if(!empty($draft_project_data)){
	$total_draft_projects = count($draft_project_data);
	$draft_counter = 1;
	foreach($draft_project_data as $draft_project_key=>$draft_project_value){
		$featured_class = '';
		$location = '';
		$last_record_class_remove_border_bottom = '';
		$last_record_class = '';
		if($draft_project_value['featured'] == 'Y') {
			$featured_class = 'opBg';
		}
		
		if($draft_counter == $total_draft_projects){
			$last_record_class_remove_border_bottom = 'bbNo';
			
			if($page_type == 'dashboard' && $draft_project_count <= $this->config->item('user_dashboard_po_view_draft_projects_listing_limit') ) {
				$last_record_class = 'padding_bottom0';
			}
			
			
			
		}
		if(!empty($draft_project_value['county_name'])){
			if(!empty($draft_project_value['locality_name'])){
				$location .= $draft_project_value['locality_name'];
			}
			if(!empty($draft_project_value['postal_code'])){
				$location .= '&nbsp;'.$draft_project_value['postal_code'] .',&nbsp;';
			}else if(!empty($draft_project_value['locality_name']) && empty($draft_project_value['postal_code'])){
				$location .= ',&nbsp';
			}
			$location .= $draft_project_value['county_name'];
		}
?>
	<div class="tabContent draft_project" id="<?php echo 'draft_project_'.$draft_project_value['project_id']; ?>">
		<!--<div class="default_project_row <?php echo $featured_class." ".$last_record_class_remove_border_bottom." ". $last_record_class; ?>">-->
		
		<div class="default_project_row <?php echo $featured_class; ?>">
			<div class="default_project_title">
                            <a href="" class="default_project_title_link link_disable"><?php echo htmlspecialchars($draft_project_value['project_title'], ENT_QUOTES); ?></a>
                        </div>
			<!-- <span class="opOpen">Open</span> -->
			<label class="default_short_details_field">
				<small><i class="far fa-clock"></i><?php echo $draft_project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_save_as_draft_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($draft_project_value['project_save_date'])).'</span>' : $this->config->item('project_save_as_draft_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($draft_project_value['project_save_date']));?></span></small><small><i class="fa fa-file-text-o"></i><?php if($draft_project_value['project_type'] == 'fixed')
					{
					echo $this->config->item('project_listing_window_snippet_fixed_budget_project');
					}else if($draft_project_value['project_type'] == 'hourly'){
						echo $this->config->item('project_listing_window_snippet_hourly_based_budget_project');
					}else if($draft_project_value['project_type'] == 'fulltime'){
						echo $this->config->item('project_listing_window_snippet_fulltime_project');
					}		
						
					if($draft_project_value['confidential_dropdown_option_selected'] == 'Y'){
						if($draft_project_value['project_type'] == 'fixed'){
							echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected').'</span>';
							}else if($draft_project_value['project_type'] == 'hourly'){
							echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_confidential_option_selected').'</span>';
						}else if($draft_project_value['project_type'] == 'fulltime'){
							echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_confidential_option_selected').'</span>';
						}
					}else if($draft_project_value['not_sure_dropdown_option_selected'] == 'Y'){
						if($draft_project_value['project_type'] == 'fixed'){
						echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected').'</span>';
						}else if($draft_project_value['project_type'] == 'hourly'){
						echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_not_sure_option_selected').'</span>';
						}else if($draft_project_value['project_type'] == 'fulltime'){
							echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_not_sure_option_selected').'</span>';
						}
					}else{
						$budget_range = '';
						if($draft_project_value['max_budget'] != 'All'){
							if(!empty($draft_project_value['min_budget']) && !empty($draft_project_value['max_budget'])){
								if($draft_project_value['project_type'] == 'hourly'){
								
									$budget_range = '';
									if($this->config->item('post_project_budget_range_between')){
										$budget_range .= $this->config->item('post_project_budget_range_between');
									}
									$budget_range .= '<span class="touch_line_break">'.number_format($draft_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($draft_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour').'</span>';
								}else if($draft_project_value['project_type'] == 'fulltime'){
									$budget_range = '';
									if($this->config->item('post_project_budget_range_between')){
										$budget_range .= $this->config->item('post_project_budget_range_between');
									}
									$budget_range .= '<span class="touch_line_break">'.number_format($draft_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($draft_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_month').'</span>';
								}else{
									$budget_range = '';
									if($this->config->item('post_project_budget_range_between')){
										$budget_range .= $this->config->item('post_project_budget_range_between');
									}
									$budget_range .= '<span class="touch_line_break">'.number_format($draft_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($draft_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
								}
							}	
						}else{
							if(!empty($draft_project_value['min_budget'])){
								if($draft_project_value['project_type'] == 'hourly'){
									$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($draft_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span>';
								}else if($draft_project_value['project_type'] == 'fulltime'){
									$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($draft_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span>';
								}else{
									$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($draft_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
								}
							}	
						}
						echo $budget_range;
					}
					
					if($draft_project_value['escrow_payment_method'] == 'Y') {
						echo '<span class="touch_line_break">'.$this->config->item('my_projects_project_payment_method_escrow_system').'</span>';
					}
					
					if($draft_project_value['offline_payment_method'] == 'Y') {
						echo '<span class="touch_line_break">'.$this->config->item('my_projects_project_payment_method_offline_system').'</span>';
					}
					?></small><?php
				if(!empty($location)){
				?><small><i class="fas fa-map-marker-alt"></i><?php echo $location;?></small><?php } ?></label>
				<?php
				//$description = htmlspecialchars($draft_project_value['project_description'], ENT_QUOTES);
				?>
				<div class="default_project_description desktop-secreen">
					<p><?php 
                     echo character_limiter($draft_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_desktop'));
					?></p>
				</div>
				<div class="default_project_description ipad-screen">
					<p><?php 
                          echo character_limiter($draft_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_tablet'));
					?></p>
				</div>
				<div class="default_project_description mobile-screen">
					<p><?php 
                      echo character_limiter($draft_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_mobile'));
					?></p>
				</div>
			<div class="clearfix"></div>
			
				<div class="badgeAction">
					<?php
					$maintain_badge_space = "col-md-12 col-sm-12 col-xs-12"; 
					if($draft_project_value['featured'] == 'Y' || $draft_project_value['urgent'] == 'Y' || $draft_project_value['sealed'] == 'Y' || $draft_project_value['hidden'] == 'Y'){
						$maintain_badge_space = "col-md-2 col-sm-2 col-xs-12"; 
					}	
					if($draft_project_value['featured'] == 'Y' || $draft_project_value['urgent'] == 'Y' || $draft_project_value['sealed'] == 'Y' || $draft_project_value['hidden'] == 'Y'){
						
					?>
					<!-- <div class="col-md-10 col-sm-10 col-xs-12"> -->								
					<label class="badgeOnly">								
						<div class="default_project_badge">
								<?php
								if($draft_project_value['featured'] == 'Y') {
								?>
								<button type="button" class="btn badge_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></button>
								<?php
								}
								?>
								<?php
								if($draft_project_value['urgent'] == 'Y') {
								?>
								<button type="button" class="btn badge_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></button>
								<?php
								}
								?>
								<?php
								if($draft_project_value['sealed'] == 'Y') {
								?>
								<button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
								<?php
								}
								?>
								<?php
								if($draft_project_value['hidden'] == 'Y') {
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
					<!-- <div class="<?php //echo $maintain_badge_space; ?> actOnly"> -->
					<label class="actionBtn">
						<div class="actOnly">
							<div class="myAction">
								<div class="dropdown">
									<button class="btn dropdown-toggle default_btn dark_blue_btn noPaddingtb" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<?php echo $this->config->item('action'); ?>
									</button>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
										<a class="dropdown-item edit_draft" data-po-id="<?php echo Cryptor::doEncrypt($draft_project_value['project_owner_id']); ?>" data-project-type="<?php echo $draft_project_value['project_type'] ?>" data-attr= "<?php echo $draft_project_value['project_id']  ?>" style="cursor:pointer"><?php echo $this->config->item('myprojects_section_draft_tab_option_edit_draft_po_view'); ?></a>
										<a data-po-id="<?php echo Cryptor::doEncrypt($draft_project_value['project_owner_id']); ?>" data-project-type="<?php echo $draft_project_value['project_type'] ?>" class="dropdown-item remove_draft_my_project" style="cursor:pointer" data-attr= "<?php echo $draft_project_value['project_id']  ?>"><?php echo $this->config->item('myprojects_section_draft_tab_option_remove_draft_po_view'); ?></a>
										<a data-po-id="<?php echo Cryptor::doEncrypt($draft_project_value['project_owner_id']); ?>" data-project-type="<?php echo $draft_project_value['project_type'] ?>" class="dropdown-item publish_project" style="cursor:pointer" data-attr= "<?php echo $draft_project_value['project_id']  ?>"><?php echo $this->config->item('myprojects_section_draft_tab_option_publish_project_po_view'); ?></a>
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
		$draft_counter++;
	}
}else{?>
<div class="default_blank_message"><?php echo $this->config->item('no_draft_project_message'); ?></div>
<?php
}	
if($page_type == 'dashboard' && $draft_project_count > $this->config->item('user_dashboard_po_view_draft_projects_listing_limit') ) {
?>
	<div class="viewMore">
	<a class="default_btn blue_btn btn_style_5_35 btnBold" href="<?php echo base_url($this->config->item('myprojects_page_url')); ?>"><?php echo $this->config->item('view_all_projects'); ?></a>
	</div>
<?php
}
?>

<?php
if($page_type == 'my_projects' && $draft_project_count > 0){	
?>	
<!-- Pagination Start -->
<div class="pagnOnly">
	<div class="row">
		<?php
		$manage_paging_width_class = "col-md-12 col-sm-12"; 
		if(!empty($draft_pagination_links)){
		$manage_paging_width_class = "col-md-7 col-sm-7"; 
		}
		?>
		<div class="<?php echo $manage_paging_width_class; ?> col-12">
			<?php
				$rec_per_page = ($draft_project_count > $this->config->item('my_projects_po_view_draft_projects_listing_limit')) ? $this->config->item('my_projects_po_view_draft_projects_listing_limit') : $draft_project_count;
			?>
			<div class="pageOf">
				<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $draft_project_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
			</div>
		</div>
		<?php 
		if(!empty($draft_pagination_links)){
		?>
		<div class="col-md-5 col-sm-5 col-12">
			<div class="modePage">
				<?php echo $draft_pagination_links; ?>
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