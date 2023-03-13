<?php
if(!empty($completed_bids_project_data)){
		$total_in_progress_bids_projects = count($completed_bids_project_data);
		$completed_bid_counter = 1;
        foreach($completed_bids_project_data as $completed_bids_project_key => $completed_bids_project_value){
		$location = '';
		$last_record_class_remove_border_bottom = '';
		$last_record_class = '';
		if($completed_bid_counter == $total_in_progress_bids_projects){
			$last_record_class_remove_border_bottom = 'bbNo';
			if($page_type == 'dashboard' && $completed_bids_project_count <= $this->config->item('my_projects_sp_view_completed_bids_listing_limit')  ) {
				//$last_record_class = 'padding_bottom0';
			}	
			
			
		}
		if(!empty($completed_bids_project_value['county_name'])){
		if(!empty($completed_bids_project_value['locality_name'])){
			$location .= $completed_bids_project_value['locality_name'];
		}
		if(!empty($completed_bids_project_value['postal_code'])){
			$location .= '&nbsp;'.$completed_bids_project_value['postal_code'] .',&nbsp;';
		}else if(!empty($completed_bids_project_value['locality_name']) && empty($completed_bids_project_value['postal_code'])){
			$location .= ',&nbsp';
		}
		$location .= $completed_bids_project_value['county_name'];
		}
?>	
<div class="tabContent">
	 <div class="default_project_row <?php echo $last_record_class; ?>" id="<?php echo "completed_bid_".$completed_bids_project_value['project_id'] ?>">
		<div class="default_project_title">
			 <a class="default_project_title_link" target="_blank" href="<?php echo base_url().$this->config->item('project_detail_page_url')."?id=".$completed_bids_project_value['project_id']; ?>"><?php echo htmlspecialchars($completed_bids_project_value['project_title'], ENT_QUOTES);?></a>
		</div>
		<label class="default_short_details_field">
			<small><i class="fa fa-file-text-o"></i><?php 
			// if($completed_bids_project_value['project_type'] == 'fixed'){
			// 	echo $this->config->item('completed_project_listing_window_snippet_fixed_budget_project_sp_view');
			// }else if($completed_bids_project_value['project_type'] == 'hourly'){
			// 	echo $this->config->item('completed_project_listing_window_snippet_hourly_based_budget_project_sp_view');
			// }else if($completed_bids_project_value['project_type'] == 'fulltime'){
			// 	echo $this->config->item('completed_project_listing_window_snippet_fulltime_project_sp_view');
			// }
			if($completed_bids_project_value['project_type'] == 'fixed'){
				echo $this->config->item('project_listing_window_snippet_fixed_budget_project');
			} else if($completed_bids_project_value['project_type'] == 'hourly'){
				echo $this->config->item('project_listing_window_snippet_hourly_based_budget_project');
			} else if($completed_bids_project_value['project_type'] == 'fulltime'){
				echo $this->config->item('project_listing_window_snippet_fulltime_project');
			}
			if($completed_bids_project_value['confidential_dropdown_option_selected'] == 'Y'){
				if($completed_bids_project_value['project_type'] == 'fixed'){
					echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected').'</span>';
					}else if($completed_bids_project_value['project_type'] == 'hourly'){
					echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_confidential_option_selected').'</span>';
				}else if($completed_bids_project_value['project_type'] == 'fulltime'){
					echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_confidential_option_selected').'</span>';
				}
			}else if($completed_bids_project_value['not_sure_dropdown_option_selected'] == 'Y'){
				if($completed_bids_project_value['project_type'] == 'fixed'){
				echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected').'</span>';
				}else if($completed_bids_project_value['project_type'] == 'hourly'){
				echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_not_sure_option_selected').'</span>';
				}else if($completed_bids_project_value['project_type'] == 'fulltime'){
					echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_not_sure_option_selected').'</span>';
				}
			}else{
				$budget_range = '';
				if($completed_bids_project_value['max_budget'] != 'All'){
					if($completed_bids_project_value['project_type'] == 'hourly'){
						$budget_range = '';
						if($this->config->item('post_project_budget_range_between')){
							$budget_range .= $this->config->item('post_project_budget_range_between');
						}
						$budget_range .= '<span class="touch_line_break">'.number_format($completed_bids_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($completed_bids_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour').'</span>';
					}else if($completed_bids_project_value['project_type'] == 'fulltime'){
						$budget_range = '';
						if($this->config->item('post_project_budget_range_between')){
							$budget_range .= $this->config->item('post_project_budget_range_between');
						}
						$budget_range .= '<span class="touch_line_break">'.number_format($completed_bids_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($completed_bids_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_month').'</span>';
					}else{
						$budget_range = '';
						if($this->config->item('post_project_budget_range_between')){
							$budget_range .= $this->config->item('post_project_budget_range_between');
						}
						$budget_range .= '<span class="touch_line_break">'.number_format($completed_bids_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($completed_bids_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
					}
				}else{
					if($completed_bids_project_value['project_type'] == 'hourly'){
						$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($completed_bids_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span>';
					}else if($completed_bids_project_value['project_type'] == 'fulltime'){
						$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($completed_bids_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span>';
					}else{
						$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($completed_bids_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
					}
				}
				echo $budget_range;
			}
			?></small><small><i class="far fa-clock"></i><?php
			if($completed_bids_project_value['project_type'] == 'fulltime'){
				echo $this->config->item('fulltime_project_hired_sp_employment_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($completed_bids_project_value['project_winner_work_start_date'])).'</span>';
			}else{
				echo $this->config->item('project_start_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($completed_bids_project_value['project_winner_work_start_date'])).'</span>';
			}?></small><small><i class="far fa-calendar-alt"></i><?php
			if($completed_bids_project_value['project_type'] == 'fulltime'){
				echo $this->config->item('fulltime_project_completion_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($completed_bids_project_value['project_winner_work_completion_date'])).'</span>';
			}else{
				echo $this->config->item('project_completion_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($completed_bids_project_value['project_winner_work_completion_date'])).'</span>';
			}?></small><?php
				if(!empty($location)):
			?><small><i class="fas fa-map-marker-alt"></i><?php echo $location;?></small><?php
				endif;
			?><?php
			$bid_value = '';
			$completed_amount_txt = '';
			$project_value = '';
			if($completed_bids_project_value['project_type'] == 'fixed'){
				
				$initial_bid_description = $completed_bids_project_value['initial_bid_description'];	
				$completed_amount_txt= $this->config->item('fixed_budget_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view');
				if(floatval($completed_bids_project_value['total_project_amount']) != 0){
					$project_value= $this->config->item('completed_bidding_listing_project_value').'<span class="touch_line_break">'.number_format($completed_bids_project_value['total_project_amount'], 0, '', ' ')." ".CURRENCY.'</span>';
				}
				
			}
			else if($completed_bids_project_value['project_type'] == 'hourly'){
				$initial_bid_description = $completed_bids_project_value['initial_bid_description'];
				$completed_amount_txt = $this->config->item('project_details_page_bidder_listing_bidded_hourly_rate_txt');
				if(floatval($completed_bids_project_value['total_project_amount']) != 0){
					$project_value= $this->config->item('completed_bidding_listing_project_value').'<span class="touch_line_break">'.number_format($completed_bids_project_value['total_project_amount'], 0, '', ' ')." ".CURRENCY.'</span>';
				}
				//$project_value= $this->config->item('completed_bidding_listing_project_value');
			}
			else if($completed_bids_project_value['project_type'] == 'fulltime'){
				$initial_bid_description = $completed_bids_project_value['initial_application_description'];
				
				$completed_amount_txt = $this->config->item('project_details_page_bidder_listing_expected_salary_txt');
				
				
			}

			if($completed_bids_project_value['bidding_dropdown_option'] == 'NA'){
				if($completed_bids_project_value['project_type'] == 'fixed'){
				
					$bid_value = '<small><i class="far fa-credit-card"></i>'.$completed_amount_txt.'<span class="touch_line_break">'.number_format($completed_bids_project_value['initial_bid_value'], 0, '', ' ')." ".CURRENCY.'</span></small>'; 
					
				}else if($completed_bids_project_value['project_type'] == 'hourly'){
					$total_bid_value = floatval($completed_bids_project_value['initial_project_agreed_value']);
					
					if(floatval($completed_bids_project_value['initial_project_agreed_number_of_hours']) == 1){
					$bidder_listing_details_hour_txt = $this->config->item('1_hour');
					}else if(floatval($completed_bids_project_value['initial_project_agreed_number_of_hours']) >=2 && floatval($completed_bids_project_value['initial_project_agreed_number_of_hours']) <= 4){
						$bidder_listing_details_hour_txt = $this->config->item('2_4_hours');
					}else if(floatval($completed_bids_project_value['initial_project_agreed_number_of_hours']) >4){
						$bidder_listing_details_hour_txt = $this->config->item('more_than_or_equal_5_hours');
					}
					$bid_value = '<small><i class="fas fa-stopwatch"></i>'.$completed_amount_txt.'<span class="touch_line_break">'.number_format($completed_bids_project_value['initial_project_agreed_hourly_rate'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_hour').'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').$completed_bids_project_value['initial_project_agreed_number_of_hours'].' '.$bidder_listing_details_hour_txt.'</small>';
					// $bid_value .= ' | '.$this->config->item('completed_bidding_listing_project_value'). ' '.number_format($total_bid_value, 0, '', ' ')." ".CURRENCY;
					
				}else if($completed_bids_project_value['project_type'] == 'fulltime'){
					$bid_value = '<small><i class="fas fa-stopwatch"></i>'.$completed_amount_txt.'<span class="touch_line_break">'.number_format($completed_bids_project_value['initial_fulltime_project_agreed_salary'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_month').'</span></small>';
				}
			}else if ($completed_bids_project_value['bidding_dropdown_option'] == 'to_be_agreed'){

				if($completed_bids_project_value['project_type'] == 'hourly'){
						$bid_value = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').' '.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</small>';
				}else{

					$bid_value = '<small><i class="far fa-credit-card"></i>'.$completed_amount_txt.'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
				}
			}
			else if ($completed_bids_project_value['bidding_dropdown_option'] == 'confidential'){
				if($completed_bids_project_value['project_type'] == 'hourly'){
					$bid_value = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</small>';
				
				}else{
					$bid_value = '<small><i class="far fa-credit-card"></i>'.$completed_amount_txt.'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</small></small>';
				}
			}
			if(!empty($bid_value)){
			?><?php echo $bid_value; ?><?php
			}
			if(!empty($project_value)){
			?><small><i class="fas fa-coins"></i><?php echo $project_value; ?></small><?php
			}
			?><?php
			if($completed_bids_project_value['total_active_disputes'] > 0){
			?><small><i class="fas fa-exclamation-triangle" style="color:red;"></i></small><?php
			}else {
				$get_latest_closed_dispute_record = array();
				if($completed_bids_project_value['project_type'] == 'fixed' || $completed_bids_project_value['project_type'] == 'hourly'){
					
					
					
					$get_latest_closed_dispute_record = get_latest_project_closed_dispute($completed_bids_project_value['project_type'],array('disputed_project_id'=>$completed_bids_project_value['project_id'],'sp_winner_id_of_disputed_project'=>$completed_bids_project_value['winner_id'])); 
				}
				
				if(!empty($get_latest_closed_dispute_record) && ($get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_sp' || $get_latest_closed_dispute_record['dispute_status'] == 'dispute_cancelled_by_initiator_po' || $get_latest_closed_dispute_record['dispute_status'] == 'automatic_decision' || $get_latest_closed_dispute_record['dispute_status'] == 'parties_agreement')){	
			?><small><i class="fas fa-balance-scale"></i></small><?php	
				}if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['sp_winner_id_of_disputed_project']){
				?><small><i class="fas fa-balance-scale-left"></i></small><?php	
				}
				if(!empty($get_latest_closed_dispute_record) && $get_latest_closed_dispute_record['dispute_status'] == 'admin_decision' && $get_latest_closed_dispute_record['disputed_winner_id'] == $get_latest_closed_dispute_record['project_owner_id_of_disputed_project']){ ?><small><i class="fas fa-balance-scale-right"></i></small><?php	
				}		
			}	
			?></label>
			<div class="default_project_description desktop-secreen">
				<p><?php 
				echo character_limiter($completed_bids_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_desktop'));
			   // echo limitString($description,$this->config->item('dashboard_my_projects_section_project_description_character_limit_desktop'));?></p>
			</div>
			<div class="default_project_description ipad-screen">
				<p><?php 
                 echo character_limiter($completed_bids_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_tablet'));?></p>
			</div>
			<div class="default_project_description mobile-screen">
				<p><?php 
                echo character_limiter($completed_bids_project_value['project_description'],$this->config->item('dashboard_my_projects_section_project_description_character_limit_mobile'));?></p>
			</div>
		<div class="clearfix"></div>
		<?php
		if( $completed_bids_project_value['sealed'] == 'Y' || $completed_bids_project_value['hidden'] == 'Y'){
		?>
		<div class="badgeAction">
			<label class="badgeOnly">
				<div class="default_project_badge">
				<?php
				if($completed_bids_project_value['sealed'] == 'Y'){
				?>
				<button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
				<?php
				}
				if($completed_bids_project_value['hidden'] == 'Y'){
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
		$completed_bid_counter++;
	}	
}else{
?>
<div class="default_blank_message">
    <?php echo $this->config->item('no_completed_bids_project_message')?>
</div>
<?php
}
if($page_type == 'dashboard' && $completed_bids_project_count > $this->config->item('user_dashboard_sp_view_completed_bids_listing_limit')  ) {
?>
<div class="viewMore">
    <a class="default_btn blue_btn btn_style_5_35 btnBold" href="<?php echo base_url($this->config->item('myprojects_page_url')); ?>"><?php echo $this->config->item('view_all_projects'); ?></a> 
</div>
<?php
}
?>
<?php
if($page_type == 'my_projects'  && $completed_bids_project_count > 0 ){	
?>	
<!-- Pagination Start -->
<div class="pagnOnly">
	<div class="row">
		<?php
		$manage_paging_width_class = "col-md-12 col-sm-12"; 
		if(!empty($completed_bids_pagination_links)){
		$manage_paging_width_class = "col-md-7 col-sm-7"; 
		}
		?>
		<div class="<?php echo $manage_paging_width_class; ?> col-12">
			<?php
				$rec_per_page = ($completed_bids_project_count > $this->config->item('my_projects_sp_view_completed_bids_listing_limit')) ? $this->config->item('my_projects_sp_view_completed_bids_listing_limit') : $completed_bids_project_count;
			?>
			<div class="pageOf">
				<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $completed_bids_project_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
			</div>
		</div>
		<?php 
		if(!empty($completed_bids_pagination_links)){
		?>
		<div class="col-md-5 col-sm-5 col-12">
			<div class="modePage">
				<?php echo $completed_bids_pagination_links; ?>
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
