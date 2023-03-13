<?php
$user = $this->session->userdata('user');
if(!empty($user)) {
	$user = $user[0];
}
if(!empty($open_bidding_project_data)) {
    foreach($open_bidding_project_data as $open_bidding_project_key => $open_bidding_project_value){
		if($open_bidding_project_value['project_type'] == 'fulltime'){
			$apply_now_button_text = $this->config->item('fulltime_project_apply_now_button_txt');
		}else{
			$apply_now_button_text = $this->config->item('project_apply_now_button_txt');
		}
        $featured_max = 0;
        $urgent_max = 0;
        $expiration_featured_upgrade_date_array = array();
        $expiration_urgent_upgrade_date_array = array();
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
        
        $featured_class = '';
        if($open_bidding_project_value['featured'] == 'Y' && $featured_max > time()){
            $featured_class = 'opBg';
        }
        $location = '';
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
<!--<div class="pDtls">-->
<div class="default_project_row <?php echo $featured_class?>">
    <div class="default_project_title">
        <a class="default_project_title_link" target="_blank" href="<?php echo base_url().$this->config->item('project_detail_page_url')."?id=".$open_bidding_project_value['project_id']; ?>"><?php echo htmlspecialchars($open_bidding_project_value['project_title'], ENT_QUOTES);?></a>
    </div>
    <label class="default_short_details_field">
        <small><i class="far fa-clock"></i><?php echo $open_bidding_project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($open_bidding_project_value['project_posting_date'])).'</span>' : $this->config->item('project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($open_bidding_project_value['project_posting_date'])).'</span>';?></small><small><i class="fa fa-file-text-o"></i><?php 
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
			echo '<span class="touch_line_break">'.$this->config->item('find_projects_project_payment_method_escrow_system').'</span>';
		}
		if($open_bidding_project_value['offline_payment_method'] == 'Y') {
			echo '<span class="touch_line_break">'.$this->config->item('find_projects_project_payment_method_offline_system').'</span>';
		}
		?></small><?php
        if(!empty($location)){
        ?><small><i class="fas fa-map-marker-alt"></i><?php echo $location;?></small><?php
        }
        ?><?php
		if(!empty($user) && $user->user_id == $open_bidding_project_value['project_owner_id'] || $open_bidding_project_value['sealed'] == 'N' ){
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
		}echo $bid_history_total_bids;?></small><?php
		}
		?></label>
	
	<?php
	//$description = htmlspecialchars($open_bidding_project_value['project_description'], ENT_QUOTES);
	?>
	<div class="default_project_description desktop-secreen">
		<p><?php 
		echo character_limiter($open_bidding_project_value['project_description'],$this->config->item('find_project_description_character_limit_desktop'));
		?></p>
	</div>
	<div class="default_project_description ipad-screen">
		<p><?php 
        echo character_limiter($open_bidding_project_value['project_description'],$this->config->item('find_project_description_character_limit_tablet'));?></p>
	</div>
	<div class="default_project_description mobile-screen">
		<p><?php 
        echo character_limiter($open_bidding_project_value['project_description'],$this->config->item('find_project_description_character_limit_mobile'));?></p>
	</div>
<!--</div>
<div class="clearfix"></div>-->

<?php
    $badgeCount = 0;
    if($open_bidding_project_value['featured'] == 'Y') {
        $badgeCount += 1;
    }if($open_bidding_project_value['urgent'] == 'Y') {
        $badgeCount += 1;
    }if($open_bidding_project_value['sealed'] == 'Y') {
        $badgeCount += 1;
    }
    if(!empty($open_bidding_project_value['categories'])) {
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-12">
        <div class="default_project_category text-center">
				<input type="checkbox" class="read-more-state" id="post-<?php echo $open_bidding_project_value['project_id']; ?>"/>
				<div class="read-more-wrap text-left">                     
				<?php	
				foreach($open_bidding_project_value['categories'] as $category_key=>$category_value){
					if($category_key < $this->config->item('find_projects_categories_show_more_less')){
						if(!empty($category_value['parent_category_name']) && !empty($category_value['category_name'])) {
						?>
						<div class="clearfix catSub12">
							<small class="pSmnu"><?php echo $category_value['parent_category_name']; ?></small>
							<a href="#">
								<span><?php echo $category_value['category_name']; ?></span>
							</a>
						</div>
						<?php
						} else if (!empty($category_value['category_name'])) {
							echo '<small>'.$category_value['category_name'].'</small>'; 
						} else if(!empty($category_value['parent_category_name'])) {
							echo '<small>'.$category_value['parent_category_name'].'</small>'; 
						}
					} else {
						//if($category_key == 2){
						?>
															
							<!--<div  class="collapse clearfix details-<?php echo $open_bidding_project_value['project_id']; ?>" >-->
					<?php //} ?>
							<?php
							if(!empty($category_value['parent_category_name']) && !empty($category_value['category_name'])){
							?>
							<div class="clearfix catSub12 read-more-target">
								<small class="pSmnu"><?php echo $category_value['parent_category_name']; ?></small>
								<a href="#">
								<span><?php echo $category_value['category_name']; ?></span>
								</a>
							</div>
							<?php
							} else if(!empty($category_value['category_name'])) {
							?>
								<small class="read-more-target"><?php echo $category_value['category_name']; ?></small>
							<?php
							} else if(!empty($category_value['parent_category_name'])) {
							?>
								<small class="read-more-target"><?php echo $category_value['parent_category_name']; ?></small>
							<?php	
							}
							?> 
					
					<?php
					
					}
				}
			?>
				</div>
				<?php
					if(count($open_bidding_project_value['categories']) > $this->config->item('find_projects_categories_show_more_less')) {
				?>
				<label for="post-<?php echo $open_bidding_project_value['project_id']; ?>" class="read-more-trigger"></label>
				<?php
					}
				?>
				<div class="clearfix"></div>
        </div>
        
    </div>
    
    <!--<div class="col-md-6 col-sm-6 col-12 default_applyNow_adjust">
        <?php if($badgeCount<2 && count($open_bidding_project_value['categories'])>$this->config->item('find_projects_categories_show_more_less')) {  ?>
        <div class="row default_applyNow_btn">
            <div class="col-md-6 col-sm-6 col-12">
                    <?php if($open_bidding_project_value['featured'] == 'Y'){ ?>
                        <button type="button" class="btn badge_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></button>
                    <?php } if($open_bidding_project_value['urgent'] == 'Y' ){ ?>
                        <button type="button" class="btn badge_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></button>
                    <?php } if($open_bidding_project_value['sealed'] == 'Y'){ ?>
                        <button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
                    <?php } ?>
            </div>
            <div class="col-md-6 col-sm-6 col-12 applyNowRight">
            <?php
                if(!empty($user) && $user->user_id != $open_bidding_project_value['project_owner_id'] && check_sp_active_bid_exists_project($open_bidding_project_value['project_id'],$user->user_id,$open_bidding_project_value['project_type']) == 0 ) {
            ?>
                <button type="button" class="btn default_btn blue_btn apply_now_button apply_now_logged_in" data-attr="<?php echo $open_bidding_project_value['project_id']; ?>" data-page-type-attr = "listing" ><?php echo $apply_now_button_text ?></button>
            <?php } if($this->session->userdata('user') == null) { ?>
                <button type="button" class="btn default_btn blue_btn apply_now_button" data-page-type-attr = "listing" data-attr="<?php echo $open_bidding_project_value['project_id']; ?>"><?php echo $apply_now_button_text ?></button>
            <?php } ?>  
            </div>
        </div>
        <?php } ?>
    </div>-->
</div>
<?php
}
?>
<?php //if(($badgeCount>=0 && count($open_bidding_project_value['categories'])<=$this->config->item('find_projects_categories_show_more_less')) || ($badgeCount>1 && count($open_bidding_project_value['categories'])>$this->config->item('find_projects_categories_show_more_less'))) {  ?>
    <div class="row social_badge_btn">
		<?php
		if(($open_bidding_project_value['featured'] == 'Y' && $featured_max != 0 && $featured_max > time()) || ($open_bidding_project_value['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time()) || $open_bidding_project_value['sealed'] == 'Y'){
		?>
		<!-- Only Mobile Version Uses Start -->
		<div class="col-md-12 col-sm-12 col-12 <?php echo $badgeCount > 0 ? 'badgeWidthMob' : 'fontSize0' ?>">
			<div class="default_project_badge">
				<?php if($open_bidding_project_value['featured'] == 'Y' && $featured_max != 0 && $featured_max > time()){ ?>
					<button type="button" class="btn badge_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></button>
				<?php } if($open_bidding_project_value['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time()){ ?>
					<button type="button" class="btn badge_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></button>
				<?php } if($open_bidding_project_value['sealed'] == 'Y'){ ?>
					<button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
				<?php } ?>
			</div>
		</div>
		<!-- Only Mobile Version Uses End -->
		<?php
		}
		?>
        <div class="col-md-2 col-sm-2 col-12 dbSocial">
			<div class="default_project_socialicon">
				<a href="" class="fb_share_project" data-link="<?php echo $open_bidding_project_value['fb_share_url']; ?>"><i class="fa fa-facebook"></i></a>
				<a href="" class="twitter_share_project" data-link="<?php echo $open_bidding_project_value['twitter_share_url']; ?>" data-title="<?php echo htmlspecialchars($open_bidding_project_value['project_title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit(htmlspecialchars($open_bidding_project_value['project_description'], ENT_QUOTES), $this->config->item('twitter_share_project_description_character_limit'));?>"><i class="fa fa-twitter"></i></a>
				<a href="" class="ln_share_project" data-link="<?php echo $open_bidding_project_value['ln_share_url']; ?>" data-title="<?php echo htmlspecialchars($open_bidding_project_value['project_title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit(htmlspecialchars($open_bidding_project_value['project_description'], ENT_QUOTES), $this->config->item('linkedin_share_project_description_character_limit'));?>"><i class="fa fa-linkedin"></i></a>
				<a href="" class="email_share_project" data-link="<?php echo $open_bidding_project_value['email_share_url']; ?>" data-title="<?php echo htmlspecialchars($open_bidding_project_value['project_title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(nl2br($open_bidding_project_value['project_description']), ENT_QUOTES)), $this->config->item('find_project_email_share_project_description_character_limit'));?>"><i class="fas fa-envelope"></i></a>
			</div>
        </div>
        <div class="col-md-7 col-sm-7 col-12 badgeWidth">
			<?php
			if(($open_bidding_project_value['featured'] == 'Y' && $featured_max != 0 && $featured_max > time()) || ($open_bidding_project_value['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time()) || $open_bidding_project_value['sealed'] == 'Y'){
			?>
			<div class="default_project_badge">
				<?php if($open_bidding_project_value['featured'] == 'Y' && $featured_max != 0 && $featured_max > time()){ ?>
					<button type="button" class="btn badge_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></button>
				<?php } if($open_bidding_project_value['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time()  ){ ?>
					<button type="button" class="btn badge_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></button>
				<?php } if($open_bidding_project_value['sealed'] == 'Y'){ ?>
					<button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
				<?php } ?>
			</div>
			<?php
			}
			?>
        </div>
		<?php
			if(!empty($user) && $user->user_id != $open_bidding_project_value['project_owner_id'] && check_sp_active_bid_exists_project($open_bidding_project_value['project_id'],$user->user_id,$open_bidding_project_value['project_type']) == 0 ) {
		?>
        <div class="col-md-3 col-sm-3 col-12 dbApplyNow">
            									
            <div class="default_applyNow_btn">
                <button type="button" class="btn default_btn blue_btn apply_now_button apply_now_logged_in" data-page-type-attr = "listing" data-attr="<?php echo $open_bidding_project_value['project_id']; ?>"><?php echo trim($apply_now_button_text); ?></button>
            </div>
				</div>
				<?php
						}
				?>
				<?php
						if($this->session->userdata('user') == null) {
				?>
					<div class="col-md-3 col-sm-3 col-12 dbApplyNow">
						<div class="default_applyNow_btn">
                <button type="button" class="btn default_btn blue_btn apply_now_button" data-page-type-attr = "listing" data-attr="<?php echo $open_bidding_project_value['project_id']; ?>"><?php echo trim($apply_now_button_text) ?></button>
            </div>
					</div>
				<?php        
						}
				?>
        </div>
    </div>
<?php //} ?>
</div>
<?php
    }
}
?>
<?php
    if(empty($open_bidding_project_data)) {
?>

	<div class=" initialViewNorecord">
			<?php echo $this->config->item('find_project_search_no_results_returned_message'); ?>
	</div>

<?php
    } 
?>