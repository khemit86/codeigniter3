<?php
	
$user = $this->session->userdata ('user');

//$name = $awarded_bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $awarded_bidder_data['first_name'] . ' ' . $awarded_bidder_data['last_name'] : $awarded_bidder_data['company_name'];

$name = (($awarded_bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($awarded_bidder_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $awarded_bidder_data['is_authorized_physical_person'] == 'Y'))? $awarded_bidder_data['first_name'] . ' ' . $awarded_bidder_data['last_name'] : $awarded_bidder_data['company_name'];


$awarded_amount = '';
$awarded_amount_txt = '';
$sp_completed_projects = 0;
if($project_data['project_type'] == 'fixed'){
	$award_date = $awarded_bidder_data['project_awarded_date'];
	$bid_award_expiration_date = $awarded_bidder_data['project_award_expiration_date'];
	$awarded_description = $awarded_bidder_data['bid_description'];	
	$sp_rating = $awarded_bidder_data['project_user_total_avg_rating_as_sp'];
	$sp_total_reviews = $awarded_bidder_data['project_user_total_reviews'];
	if(isset($awarded_bidder_data['sp_total_completed_fixed_budget_projects']) && isset($awarded_bidder_data['sp_total_completed_hourly_based_projects'])){
		$sp_completed_projects = $awarded_bidder_data['sp_total_completed_fixed_budget_projects']+$awarded_bidder_data['sp_total_completed_hourly_based_projects'];
	}
	
	
	if($this->session->userdata ('user') && $user[0]->user_id == $project_data['project_owner_id']){ 
		$awarded_amount_txt= $this->config->item('fixed_budget_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view');
	} else if($this->session->userdata ('user') && $user[0]->user_id == $awarded_bidder_data['winner_id']) {
		$awarded_amount_txt= $this->config->item('fixed_budget_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view');
	} else {
		$awarded_amount_txt= $this->config->item('fixed_budget_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view');
	}
	
} else if($project_data['project_type'] == 'hourly'){
	$awarded_description = $awarded_bidder_data['bid_description'];
	$award_date = $awarded_bidder_data['project_awarded_date'];
	$bid_award_expiration_date = $awarded_bidder_data['project_award_expiration_date'];
	$awarded_amount_txt = $this->config->item('project_details_page_bidder_listing_bidded_hourly_rate_txt');
	
	$sp_rating = $awarded_bidder_data['project_user_total_avg_rating_as_sp'];
	$sp_total_reviews = $awarded_bidder_data['project_user_total_reviews'];
	if(isset($awarded_bidder_data['sp_total_completed_fixed_budget_projects']) && isset($awarded_bidder_data['sp_total_completed_hourly_based_projects'])){
		$sp_completed_projects = $awarded_bidder_data['sp_total_completed_fixed_budget_projects']+$awarded_bidder_data['sp_total_completed_hourly_based_projects'];
	}
	
} else if($project_data['project_type'] == 'fulltime'){
	$awarded_description = $awarded_bidder_data['application_description'];
	$award_date = $awarded_bidder_data['application_awarded_date'];
	$bid_award_expiration_date = $awarded_bidder_data['application_award_expiration_date'];
	$awarded_amount_txt = $this->config->item('project_details_page_bidder_listing_expected_salary_txt');
	
	$sp_rating = $awarded_bidder_data['fulltime_project_user_total_avg_rating_as_employee'];
	$sp_total_reviews = $awarded_bidder_data['fulltime_project_user_total_reviews'];
	if(isset($awarded_bidder_data['employee_total_completed_fulltime_projects'])){
	 $sp_completed_projects = $awarded_bidder_data['employee_total_completed_fulltime_projects'];
	}
	
}
if($sp_total_reviews == 0){
	
	$trGiven = number_format($sp_total_reviews,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
	}else if($sp_total_reviews == 1) {
	$trGiven = number_format($sp_total_reviews,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
} else if($sp_total_reviews > 1) {
	$trGiven = number_format($sp_total_reviews,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
}

$descLeng	=	strlen($awarded_description);
/*----------- description show for desktop screen start----*/
$desktop_cnt            =	0;
if($descLeng <= $this->config->item('project_details_page_bidder_listing_bid_description_character_limit_desktop')) {
    $desktop_description	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($awarded_description, ENT_QUOTES)));
} else {
    $desktop_description	= character_limiter($awarded_description,$this->config->item('project_details_page_bidder_listing_bid_description_character_limit_desktop'));
    $desktop_restdescription	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($awarded_description, ENT_QUOTES)));
    $desktop_cnt = 1;
}
/*----------- description show for desktop screen end----*/

/*----------- description show for ipad screen start----*/
$tablet_cnt            =	0;
if($descLeng <= $this->config->item('project_details_page_bidder_listing_bid_description_character_limit_tablet')) {
    $tablet_description	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($awarded_description, ENT_QUOTES)));
} else {
        $tablet_description	= character_limiter($awarded_description,$this->config->item('project_details_page_bidder_listing_bid_description_character_limit_tablet'));
    $tablet_restdescription	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($awarded_description, ENT_QUOTES)));
    $tablet_cnt = 1;
}
/*----------- description show for ipad screen end----*/

/*----------- description show for mobile screen start----*/
$mobile_cnt            =	0;
if($descLeng <= $this->config->item('project_details_page_bidder_listing_bid_description_character_limit_mobile')) {
        $mobile_description	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($awarded_description, ENT_QUOTES)));
} else {
    $mobile_description	= character_limiter($awarded_description,$this->config->item('project_details_page_bidder_listing_bid_description_character_limit_mobile'));
    $mobile_restdescription	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($awarded_description, ENT_QUOTES)));
    $mobile_cnt = 1;
}
/*----------- description show for mobile screen end----*/

if($awarded_bidder_data['bidding_dropdown_option'] == 'NA'){
	if($project_data['project_type'] == 'fixed'){
	
		//$bidder_listing_details_fixed_txt = floatval($awarded_bidder_data['project_delivery_period'])> 1 ? $this->config->item('project_details_page_bidder_listing_details_day_plural') : $this->config->item('project_details_page_bidder_listing_details_day_singular');
		
		if(floatval($awarded_bidder_data['project_delivery_period']) == 1){
			$bidder_listing_details_fixed_txt = $this->config->item('1_day');
		}else if(floatval($awarded_bidder_data['project_delivery_period']) >=2 && floatval($awarded_bidder_data['project_delivery_period']) <= 4){
			$bidder_listing_details_fixed_txt = $this->config->item('2_4_days');
		}else if(floatval($awarded_bidder_data['project_delivery_period']) >4){
			$bidder_listing_details_fixed_txt = $this->config->item('more_than_or_equal_5_days');
		}
		
		
		if($this->session->userdata ('user')) {
			$awarded_amount = '<small><i class="far fa-credit-card"></i>'.$awarded_amount_txt.'<span class="touch_line_break">'.number_format($awarded_bidder_data['awarded_amount'], 0, '', ' ')." ".CURRENCY.'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').'<span class="touch_line_break">'. str_replace(".00","",number_format($awarded_bidder_data['project_delivery_period'],  2, '.', ' ')) .' '.$bidder_listing_details_fixed_txt.'</span></small>';
		}
		
	} else if($project_data['project_type'] == 'hourly'){
		$total_bid_value = floatval($awarded_bidder_data['awarded_hourly_rate']) * floatval($awarded_bidder_data['awarded_hours']);
		
		/* $bidder_listing_details_hour_txt = floatval($awarded_bidder_data['awarded_hours'])> 1 ? $this->config->item('project_details_page_bidder_listing_details_hour_plural') : 
		$this->config->item('project_details_page_bidder_listing_details_hour_singular'); */
		
		if(floatval($awarded_bidder_data['awarded_hours']) == 1){
			$bidder_listing_details_hour_txt = $this->config->item('1_hour');
		}else if(floatval($awarded_bidder_data['awarded_hours']) >=2 && floatval($awarded_bidder_data['awarded_hours']) <= 4){
			$bidder_listing_details_hour_txt = $this->config->item('2_4_hours');
		}else if(floatval($awarded_bidder_data['awarded_hours']) >4){
			$bidder_listing_details_hour_txt = $this->config->item('more_than_or_equal_5_hours');
		}
		
		
		
		if(($this->session->userdata ('user') && $user[0]->user_id == $project_data['project_owner_id'])){ 
			$awarded_amount = '<small><i class="fas fa-stopwatch"></i>'.$awarded_amount_txt.'<span class="touch_line_break">'.number_format($awarded_bidder_data['awarded_hourly_rate'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_hour').'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').'<span class="touch_line_break">'.str_replace(".00","",number_format($awarded_bidder_data['awarded_hours'],  2, '.', ' ')).' '.$bidder_listing_details_hour_txt.'</span></small><small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view'). '<span class="touch_line_break">'.number_format($total_bid_value, 0, '', ' ')." ".CURRENCY.'</span></small>';
		} else if($this->session->userdata ('user') && $user[0]->user_id == $awarded_bidder_data['winner_id']) {
			$awarded_amount = '<small><i class="fas fa-stopwatch"></i>'.$awarded_amount_txt.'<span class="touch_line_break">'.number_format($awarded_bidder_data['awarded_hourly_rate'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_hour').'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').'<span class="touch_line_break">'.str_replace(".00","",number_format($awarded_bidder_data['awarded_hours'],  2, '.', ' ')).' '.$bidder_listing_details_hour_txt.'</span></small><small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view'). '<span class="touch_line_break">'.number_format($total_bid_value, 0, '', ' ')." ".CURRENCY.'</span></small>';
		} else if($this->session->userdata ('user')) {
			$awarded_amount = '<small><i class="fas fa-stopwatch"></i>'.$awarded_amount_txt.'<span class="touch_line_break">'.number_format($awarded_bidder_data['awarded_hourly_rate'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_hour').'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').'<span class="touch_line_break">'.str_replace(".00","",number_format($awarded_bidder_data['awarded_hours'],  2, '.', ' ')).' '.$bidder_listing_details_hour_txt.'</span></small><small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view'). '<span class="touch_line_break">'.number_format($total_bid_value, 0, '', ' ')." ".CURRENCY.'</span></small>';
		}
		
	} else if($project_data['project_type'] == 'fulltime'){
		if($this->session->userdata ('user')) {
			$awarded_amount = '<small><i class="far fa-credit-card"></i>'.$awarded_amount_txt.'<span class="touch_line_break">'.number_format($awarded_bidder_data['awarded_salary'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_month').'</span></small>';
		}
	}
} else if ($awarded_bidder_data['bidding_dropdown_option'] == 'to_be_agreed'){

	if($project_data['project_type'] == 'hourly'){
			if(($this->session->userdata ('user') && $user[0]->user_id == $project_data['project_owner_id'])){ 
				$awarded_amount = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
			} else if($this->session->userdata ('user') && $user[0]->user_id == $awarded_bidder_data['winner_id']) {
				$awarded_amount = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
			} else if($this->session->userdata ('user')) {
				$awarded_amount = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
			}
	} else {
		if($this->session->userdata ('user')) {
			$awarded_amount = '<small><i class="far fa-credit-card"></i>'.$awarded_amount_txt.'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
		}
	}
} else if ($awarded_bidder_data['bidding_dropdown_option'] == 'confidential'){
	if($project_data['project_type'] == 'hourly'){
		if(($this->session->userdata ('user') && $user[0]->user_id == $project_data['project_owner_id'])){ 
			$awarded_amount = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';
		} else if($this->session->userdata ('user') && $user[0]->user_id == $awarded_bidder_data['winner_id']) {
			$awarded_amount = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';
		} else if($this->session->userdata ('user')) {
			$awarded_amount = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';
		}
	
	} else {
		if($this->session->userdata ('user')) {
			$awarded_amount = '<small><i class="far fa-credit-card"></i>'.$awarded_amount_txt.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</small>';
		}
	}
}
?>

<div class="freeBid freeBidAct awarded_bidding_list" id="<?php echo $project_data['project_type'] == 'fulltime' ? "awarded_bidder_list_".$awarded_bidder_data['employee_id'] : "awarded_bidder_list_".$awarded_bidder_data['winner_id']; ?>">
	<div class="fLancerbidding">
		<div class="default_user_avatar_left_adjust user_avatar_project_owner">		
			<div class="imgTxtR">						
				<div class="imgSize">						
					<div class="avtOnly">
						<?php
						if(!empty($awarded_bidder_data['user_avatar']) ) {
						$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$awarded_bidder_data['profile_name'].USER_AVATAR.$awarded_bidder_data['user_avatar'];
						} else {
							
							if(($awarded_bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($awarded_bidder_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $awarded_bidder_data['is_authorized_physical_person'] == 'Y')){
								
								if($awarded_bidder_data['gender'] == 'M'){
									$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
								}if($awarded_bidder_data['gender'] == 'F'){
								   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
								}
							} else {
								$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
							}
						}
						?>
						<div id="profile-picture" class="default_avatar_image avatar_image_size_project_owner" style="background-image: url('<?php echo $user_profile_picture;?>')">
						</div>
					</div>
                    <div class="sRate poSRate">
						<span><?php echo show_dynamic_rating_stars($sp_rating,'small'); ?></span>
						<small class="default_avatar_review avatar_review_project_owner"><?php echo $sp_rating;?></small>
                    </div>						
                    <div class="rvw">
						<span class="default_avatar_total_review"><?php echo $trGiven;?></span>
						<?php if($sp_completed_projects > 0){ ?>
						<small class="default_avatar_complete_project"><?php 
								if($project_data['project_type'] == 'fulltime'){
									if(($awarded_bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($awarded_bidder_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $awarded_bidder_data['is_authorized_physical_person'] == 'Y' )){
										if($awarded_bidder_data['gender'] == 'M'){
											echo $this->config->item('project_details_page_male_user_hires_on_fulltime_projects_as_employee')." ".number_format($sp_completed_projects,0, '.', ' ');
										}else{
											echo $this->config->item('project_details_page_female_user_hires_on_fulltime_projects_as_employee')." ".number_format($sp_completed_projects,0, '.', ' ');
										}
									
									}else{
										echo $this->config->item('project_details_page_company_user_hires_on_fulltime_projects_as_employee')." ".number_format($sp_completed_projects,0, '.', ' ');
									}
								}else{
									echo $this->config->item('project_details_page_user_completed_projects_as_sp')." ".number_format($sp_completed_projects,0, '.', ' ');
								}?></small><?php } ?>	
                    </div>
				</div>
			</div>
		</div>
		<div class="default_user_details_right_adjust user_details_right_adjust_project_owner">
			<div class="opLBttm opBg">
				<div>
					<div class="default_user_name"><a class="default_user_name_link" href="<?php echo site_url ($awarded_bidder_data['profile_name']); ?>"><?php echo $name; ?></a></div>
					<!--<label>bid amount: 300kc</label>-->
					<div class="clearfix"></div>
				</div>
				<div class="bottom_border default_short_details_field"><?php
					$bid_awarded_date = $project_data['project_type'] == 'fulltime' ? date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($awarded_bidder_data['application_awarded_date'])) : date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($awarded_bidder_data['project_awarded_date']));
					?><small><i class="far fa-calendar-alt"></i><?php echo $this->config->item('bid_awarded_on').'<span class="touch_line_break">'.$bid_awarded_date.'</span>'; ?></small><?php 
						if(!empty($awarded_amount)) {
					?><?php echo $awarded_amount; ?><?php
						}
					?><?php if($this->session->userdata ('user') && ($user[0]->user_id == $project_data['project_owner_id']) || $user[0]->user_id == $awarded_bidder_data['winner_id']){ ?><small><i class="fas fa-stopwatch red_icon"></i><span class="award-title-left"><?php echo $this->config->item('project_details_page_awarded_bidder_listing_details_awaiting_acceptance_expires_txt'); ?></span><span class="award-title-right touch_line_break"><?php echo date(DATE_TIME_FORMAT,strtotime($bid_award_expiration_date)); ?></span></small><?php } ?></div>
				
				<div class="default_user_description desktop-secreen">
					<p id="desktop_lessD<?php echo $awarded_bidder_data['winner_id']; ?>">
						<?php echo $desktop_description;?><?php if($desktop_cnt==1) {?><span id="desktop_dotsD<?php echo $awarded_bidder_data['winner_id']; ?>"></span><button onclick="showMoreDescription('desktop', <?php echo $awarded_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button><?php } ?></p><p id="desktop_moreD<?php echo $awarded_bidder_data['winner_id']; ?>" class="moreD">
						<?php echo $desktop_restdescription;?><button onclick="showMoreDescription('desktop', <?php echo $awarded_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button></p></div>
				<div class="default_user_description ipad-screen">
					<p id="tablet_lessD<?php echo $awarded_bidder_data['winner_id']; ?>">
						<?php echo $tablet_description;?><?php if($tablet_cnt==1) {?><span id="tablet_dotsD<?php echo $awarded_bidder_data['winner_id']; ?>"></span><button onclick="showMoreDescription('tablet', <?php echo $awarded_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button><?php } ?></p><p id="tablet_moreD<?php echo $awarded_bidder_data['winner_id']; ?>" class="moreD">
						<?php echo $tablet_restdescription;?><button onclick="showMoreDescription('tablet', <?php echo $awarded_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button></p></div>
				<div class="default_user_description mobile-screen">
					<p id="mobile_lessD<?php echo $awarded_bidder_data['winner_id']; ?>">
						<?php echo $mobile_description;?><?php if($mobile_cnt==1) {?><span id="mobile_dotsD<?php echo $awarded_bidder_data['winner_id']; ?>"></span><button onclick="showMoreDescription('mobile', <?php echo $awarded_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button><?php } ?></p><p id="mobile_moreD<?php echo $awarded_bidder_data['winner_id']; ?>" class="moreD">
						<?php echo $mobile_restdescription;?><button onclick="showMoreDescription('mobile', <?php echo $awarded_bidder_data['winner_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button></p></div>
				
				<?php
					if($this->session->userdata ('user') && ($user[0]->user_id == $project_data['project_owner_id']) || $user[0]->user_id == $awarded_bidder_data['winner_id']){
				?>
				<div class="acCo">
					<div class="row rightAlign">
                                            <div class="padding_left0 attachmentOnly">
					<?php
						if($this->session->userdata ('user') && isset($awarded_bidder_data['bid_attachments']) && !empty($awarded_bidder_data['bid_attachments']))
						{
					?>
					<!--<div class="col-md-5 col-sm-5 col-12 downPosition"><div class="downldBid">-->
                                        
					<?php
							foreach($awarded_bidder_data['bid_attachments'] as $bid_attachment_value)
							{
								if(($user[0]->user_id == $bid_attachment_value['user_id']) || ($user[0]->user_id == $project_data['project_owner_id']))
								{
					?>
					
						<label class="attachFile attachBottom"><span><a class="download_attachment download_bidder_list_bid_attachment" style="cursor:pointer;" data-attr = "<?php echo Cryptor::doEncrypt($bid_attachment_value['id']); ?>" data-sp-id = "<?php echo Cryptor::doEncrypt($bid_attachment_value['user_id']); ?>" data-po-id = "<?php echo Cryptor::doEncrypt($project_data['project_owner_id']); ?>"><i class="fas fa-paperclip"></i><?php echo $bid_attachment_value['bid_attachment_name']; ?></a></span></label>
					<?php
								}
							}
					?>
					<!--</div></div>-->
					<?php
						}
						if($this->session->userdata ('user') && $user[0]->user_id == $project_data['project_owner_id']){
					?>
						<!-- <small class="award-title-left"><?php //echo $this->config->item('project_details_page_awarded_bidder_listing_details_awaiting_acceptance_expires_txt'); ?></small><small class="award-title-right"><?php //echo date(DATE_TIME_FORMAT,strtotime($bid_award_expiration_date)); ?></small> -->
                                        <div class="rightOnly rightAwardBid">
						<!--<div class="col-md-<?php echo !empty($awarded_bidder_data['bid_attachments']) ? 7 : 12; ?> col-sm-<?php echo !empty($awarded_bidder_data['bid_attachments']) ? 7 : 12; ?> col-12 contactBIdBtn">-->
							<button type="button" 
											class="btn default_btn blue_btn contact-bidder default_btn_padding"
											data-name="<?php echo $name?>" 
											data-id="<?php echo $awarded_bidder_data['winner_id']; ?>" 
											data-project-title="<?php echo $project_data['project_title'] ?>" 
											data-project-id="<?php echo $project_data['project_id']?>"
											data-profile="<?php echo $user_profile_picture; ?>"
											data-project-owner="<?php echo $project_data['project_owner_id']?>"
							><?php echo $this->config->item('contactme_button'); ?></button>
						</div>
					
					<?php
						}
					?>
					
					<?php
					if($this->session->userdata ('user') && $user[0]->user_id == $awarded_bidder_data['winner_id']){
					?>
					
						
							<!-- <div class="col-sm-6">
								<small class="award-title-left"><?php //echo $this->config->item('project_details_page_awarded_bidder_listing_details_awaiting_acceptance_expires_txt'); ?></small><small class="award-title-right"><?php //echo date(DATE_TIME_FORMAT,strtotime($bid_award_expiration_date)); ?></small>
							</div> -->
							<!--<div class="col-md-<?php echo !empty($awarded_bidder_data['bid_attachments']) ? 7 : 12; ?> col-sm-<?php echo !empty($awarded_bidder_data['bid_attachments']) ? 7 : 12; ?> col-12 contactBIdBtn">-->
                                                            <div class="rightOnly rightAwardBid">
								<button type="button" class="btn default_btn green_btn accept_bid_confirmation"  data-attr="<?php echo Cryptor::doEncrypt($awarded_bidder_data['winner_id']); ?>"><?php echo $this->config->item('accept_btn_txt'); ?></button>
								<button type="button" class="btn default_btn red_btn decline_bid_confirmation" data-attr="<?php echo Cryptor::doEncrypt($awarded_bidder_data['winner_id']); ?>"><?php echo $this->config->item('decline_btn_txt'); ?></button>
								<!--<button type="button" class="btn btn-secondary">Contact Manish Contact Manish Contact Manish</button>-->
							</div>	
						
					
					
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
			<div class="clearfix"></div>									
		</div>
		<div class="clearfix"></div>
                        
	</div>
</div>