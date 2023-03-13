<?php
if(!empty($hired_project_data)){
		$total_hired_projects = count($hired_project_data);
		$hired_counter = 1;
    foreach($hired_project_data as $hired_project_key => $hired_project_value){
		
		$location = '';
		$last_record_class_remove_border_bottom = '';
		$last_record_class = '';
		if($hired_counter == $total_hired_projects){
			$last_record_class_remove_border_bottom = 'bbNo';
			
			if($page_type == 'dashboard' && $hired_counter <= $this->config->item('user_dashboard_fulltime_projects_employee_view_hired_listing_limit') ) {
				//$last_record_class = 'padding_bottom0';
			}
			
		}
		if(!empty($hired_project_value['county_name'])){
		if(!empty($hired_project_value['locality_name'])){
			$location .= $hired_project_value['locality_name'];
		}
		if(!empty($hired_project_value['postal_code'])){
			$location .= '&nbsp;'.$hired_project_value['postal_code'] .',&nbsp;';
		}else if(!empty($hired_project_value['locality_name']) && empty($hired_project_value['postal_code'])){
			$location .= ',&nbsp';
		}
		$location .= $hired_project_value['county_name'];
		}

		$featured_max = 0;
		$urgent_max = 0;
		$expiration_featured_upgrade_date_array = array();
		$expiration_urgent_upgrade_date_array = array();
		if(!empty($hired_project_value['featured_upgrade_end_date'])){
			$expiration_featured_upgrade_date_array[] = $hired_project_value['featured_upgrade_end_date'];
		}
		if(!empty($hired_project_value['bonus_featured_upgrade_end_date'])){
			$expiration_featured_upgrade_date_array[] = $hired_project_value['bonus_featured_upgrade_end_date'];
		}
		if(!empty($hired_project_value['membership_include_featured_upgrade_end_date'])){
			$expiration_featured_upgrade_date_array[] = $hired_project_value['membership_include_featured_upgrade_end_date'];
		}
		if(!empty($expiration_featured_upgrade_date_array)){
			$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
		}
		
		##########
		
		if(!empty($hired_project_value['urgent_upgrade_end_date'])){
			$expiration_urgent_upgrade_date_array[] = $hired_project_value['urgent_upgrade_end_date'];
		}
		if(!empty($hired_project_value['bonus_urgent_upgrade_end_date'])){
			$expiration_urgent_upgrade_date_array[] = $hired_project_value['bonus_urgent_upgrade_end_date'];
		}
		if(!empty($hired_project_value['membership_include_urgent_upgrade_end_date'])){
			$expiration_urgent_upgrade_date_array[] = $hired_project_value['membership_include_urgent_upgrade_end_date'];
		}
		if(!empty($expiration_urgent_upgrade_date_array)){
			$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
		}
		$featured_class = '';
		if ($hired_project_value['featured'] == 'Y' && $featured_max > time()) {
			$featured_class = 'opBg';
		}
?>	
<div class="tabContent">
	 <div class="default_project_row <?php echo $featured_class; ?> <?php echo $last_record_class; ?>" id="<?php echo "awarded_bid_".$hired_project_value['project_id'] ?>">
		<div class="default_project_title">
			 <a class="default_project_title_link" target="_blank" href="<?php echo base_url().$this->config->item('project_detail_page_url')."?id=".$hired_project_value['project_id']; ?>"><?php echo htmlspecialchars($hired_project_value['project_title'], ENT_QUOTES);?></a>
		</div>
		<label class="default_short_details_field">
			<small><i class="fa fa-file-text-o"></i><?php 
			if($hired_project_value['project_type'] == 'fixed'){
				echo $this->config->item('project_listing_window_snippet_fixed_budget_project');
			} else if($hired_project_value['project_type'] == 'hourly'){
				echo $this->config->item('project_listing_window_snippet_hourly_based_budget_project');
			} else if($hired_project_value['project_type'] == 'fulltime'){
				echo $this->config->item('project_listing_window_snippet_fulltime_project');
			}
			if($hired_project_value['confidential_dropdown_option_selected'] == 'Y'){
				if($hired_project_value['project_type'] == 'fixed'){
					echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected').'</span>';
					}else if($hired_project_value['project_type'] == 'hourly'){
					echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_confidential_option_selected').'</span>';
				}else if($hired_project_value['project_type'] == 'fulltime'){
					echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_confidential_option_selected').'</span>';
				}
			} else if($hired_project_value['not_sure_dropdown_option_selected'] == 'Y'){
				if($hired_project_value['project_type'] == 'fixed'){
				echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected').'</span>';
				}else if($hired_project_value['project_type'] == 'hourly'){
				echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_not_sure_option_selected').'</span>';
				}else if($hired_project_value['project_type'] == 'fulltime'){
					echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_not_sure_option_selected').'</span>';
				}
			} else {
				$budget_range = '';
				if($hired_project_value['max_budget'] != 'All'){
          $budget_range = '';
          if($this->config->item('post_project_budget_range_between')){
            $budget_range .= $this->config->item('post_project_budget_range_between');
          }
          $budget_range .= '<span class="touch_line_break">'.number_format($hired_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($hired_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_month').'</span>';
				} else {
          $budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($hired_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span>';
				}
				echo $budget_range;
			}
			?></small><small><i class="far fa-clock"></i><?php echo $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($hired_project_value['project_start_date'])).'</span>';?></small><?php
				if(!empty($location)):
			?><small><i class="fas fa-map-marker-alt"></i><?php echo $location;?></small><?php
				endif;
			?><?php
			$bid_value = '';
			$hired_amount_txt = '';
			$project_value = '';
      $initial_bid_description = $hired_project_value['initial_application_description'];			
      $hired_amount_txt = $this->config->item('project_details_page_bidder_listing_expected_salary_txt');
      
			if(floatval($hired_project_value['total_paid_amount']) != 0) {
				$project_value= $this->config->item('fulltime_projects_employee_view_project_value').'<span class="touch_line_break">'.number_format($hired_project_value['total_paid_amount'], 0, '', ' ')." ".CURRENCY.'</span>';
			}

			if($hired_project_value['bidding_dropdown_option'] == 'NA'){
        $bid_value = '<small><i class="far fa-credit-card"></i>'.$hired_amount_txt.'<span class="touch_line_break">'.number_format($hired_project_value['initial_bid_value'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_month').'</span></small>' ;
			} else if ($hired_project_value['bidding_dropdown_option'] == 'to_be_agreed'){
        $bid_value = '<small><i class="far fa-credit-card"></i>'.$hired_amount_txt.'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
			} else if ($hired_project_value['bidding_dropdown_option'] == 'confidential'){
        $bid_value = '<small><i class="far fa-credit-card"></i>'.$hired_amount_txt.'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';
			}
			?><?php echo $bid_value; ?><?php
			if(!empty($project_value)){
			?><small><i class="fas fa-coins"></i><?php echo $project_value; ?></small><?php
			}
			?><?php if($hired_project_value['total_active_disputes'] > 0){
			?><small><i class="fas fa-exclamation-triangle" style="color:red;"></i></small><?php
			}else{
				$get_latest_closed_dispute_record = array();

				if($hired_project_value['project_type'] == 'fixed' || $hired_project_value['project_type'] == 'hourly' ){
					$dispute_close_conditions = array('disputed_project_id'=>$hired_project_value['project_id'],'project_owner_id_of_disputed_project'=>$hired_project_value['project_owner_id']);
				}else if($hired_project_value['project_type'] == 'fulltime'){
					$dispute_close_conditions = array('disputed_fulltime_project_id'=>$hired_project_value['project_id'],'employer_id_of_disputed_fulltime_project'=>$hired_project_value['project_owner_id']);
				}
				$get_latest_closed_dispute_record = get_latest_project_closed_dispute($hired_project_value['project_type'],$dispute_close_conditions); 
				
				if(!empty($get_latest_closed_dispute_record) && ($get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_sp' || $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_po'|| $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_employee' || $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_employer'||$get_latest_closed_dispute_record['dispute_status'] == 'automatic_decision'|| $get_latest_closed_dispute_record['dispute_status'] == 'parties_agreement')){
				?><small><i class="fas fa-balance-scale"></i></small><?php	
				}if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && (($hired_project_value['project_type'] != 'fulltime' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['sp_winner_id_of_disputed_project']) || ($hired_project_value['project_type'] == 'fulltime'&& $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['employee_winner_id_of_disputed_fulltime_project']))){
				?><small><i class="fas fa-balance-scale-left"></i></small><?php	
				}
				if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && (($hired_project_value['project_type'] != 'fulltime' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['project_owner_id_of_disputed_project']) || ($hired_project_value['project_type'] == 'fulltime' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['employer_id_of_disputed_fulltime_project']))){  ?><small><i class="fas fa-balance-scale-right"></i></small><?php	
				}	
			}
			?></label>
			<?php
			$description = htmlspecialchars($hired_project_value['project_description'], ENT_QUOTES);
			?>
			<div class="default_project_description desktop-secreen">
				<p><?php echo character_limiter($hired_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_desktop'));?></p>
			</div>
			<div class="default_project_description ipad-screen">
				<p><?php echo character_limiter($hired_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_tablet'));?></p>
			</div>
			<div class="default_project_description mobile-screen">
				<p><?php echo character_limiter($hired_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_mobile'));?></p>
			</div>
		<div class="clearfix"></div>
		<?php
		if($hired_project_value['featured'] == 'Y' || $hired_project_value['urgent'] == 'Y' || $hired_project_value['sealed'] == 'Y' || $hired_project_value['hidden'] == 'Y'){
		?>
		<div class="badgeAction">
			<label class="badgeOnly">
				<div class="default_project_badge">
				<?php
				if($hired_project_value['featured'] == 'Y' && $featured_max != 0 && $featured_max > time()){
				?>
				<button type="button" class="btn badge_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></button>
				<?php
					}
				if($hired_project_value['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time()){
				?>
				<button type="button" class="btn badge_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></button>
				<?php
				}
				?>
				<?php
				if($hired_project_value['sealed'] == 'Y'){
				?>
				<button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
				<?php
				}
				if($hired_project_value['hidden'] == 'Y'){
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
		$hired_counter++;
	}	
}else{
?>
<div class="default_blank_message">
    <?php echo $this->config->item('no_hired_application_project_message')?>
</div>
<?php

}
	
if($page_type == 'dashboard' && $hired_counter > $this->config->item('user_dashboard_fulltime_projects_employee_view_hired_listing_limit') ) {
?>
<div class="viewMore">
    <a class="default_btn blue_btn btn_style_5_35 btnBold" href="<?php echo base_url($this->config->item('myprojects_page_url')); ?>"><?php echo $this->config->item('view_all_projects'); ?></a> 
</div>
<?php
}

?>
<?php
if($page_type == 'my_projects' && $hired_counter > 0){	
?>	
<!-- Pagination Start -->
<div class="pagnOnly">
	<div class="row">
		<?php
		$manage_paging_width_class = "col-md-12 col-sm-12"; 
		if(!empty($hired_application_pagination_links)){
		$manage_paging_width_class = "col-md-7 col-sm-7"; 
		}
		?>
		<div class="<?php echo $manage_paging_width_class; ?> col-12">
			<?php
				$rec_per_page = ($hired_project_count > $this->config->item('my_projects_fulltime_projects_employee_view_hired_listing_limit')) ? $this->config->item('my_projects_fulltime_projects_employee_view_hired_listing_limit') : $hired_project_count;
			?>
			<div class="pageOf">
				<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $hired_project_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
			</div>
		</div>
		<?php 
		if(!empty($hired_application_pagination_links)){
		?>
		<div class="col-md-5 col-sm-5 col-12">
			<div class="modePage">
				<?php echo $hired_application_pagination_links; ?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<!-- Pagination End -->	
<?php
}	
?>