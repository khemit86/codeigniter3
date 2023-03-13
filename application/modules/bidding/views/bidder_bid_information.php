<?php
$user = $this->session->userdata ('user');	
$name = (($bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bidder_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bidder_data['is_authorized_physical_person'] == 'Y'))? $bidder_data['first_name'] . ' ' . $bidder_data['last_name'] : $bidder_data['company_name'];

$bidder_bid_class = '';

$bid_amount = '';
$bid_deliver_period = '';
$bid_description = '';
$bidded_amount_txt = '';
$sp_completed_projects = 0;
if($project_data['project_type'] == 'fixed'){
	if($this->session->userdata ('user') && $user[0]->user_id == $bidder_data['project_owner_id']) {
        $bidded_amount_txt= $this->config->item('fixed_budget_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view');
    } else if($this->session->userdata ('user') && $user[0]->user_id == $bidder_data['bidder_id']){
        $bidded_amount_txt= $this->config->item('fixed_budget_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view');
    } else {
      $bidded_amount_txt= $this->config->item('fixed_budget_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view');
    }
	$bid_description = $bidder_data['bid_description'];
	$sp_rating = $bidder_data['project_user_total_avg_rating_as_sp'];
	$sp_total_reviews = $bidder_data['project_user_total_reviews'];
	if(isset($bidder_data['sp_total_completed_fixed_budget_projects']) && isset($bidder_data['sp_total_completed_hourly_based_projects'])){
		$sp_completed_projects = $bidder_data['sp_total_completed_fixed_budget_projects']+$bidder_data['sp_total_completed_hourly_based_projects'];
	}
}
else if($project_data['project_type'] == 'hourly'){
	
	$bidded_amount_txt = $this->config->item('project_details_page_bidder_listing_bidded_hourly_rate_txt');
	$bid_description = $bidder_data['bid_description'];
	$sp_rating = $bidder_data['project_user_total_avg_rating_as_sp'];
	$sp_total_reviews = $bidder_data['project_user_total_reviews'];
	if(isset($bidder_data['sp_total_completed_fixed_budget_projects']) && isset($bidder_data['sp_total_completed_hourly_based_projects'])){
		$sp_completed_projects = $bidder_data['sp_total_completed_fixed_budget_projects']+$bidder_data['sp_total_completed_hourly_based_projects'];
	}
}
else if($project_data['project_type'] == 'fulltime'){
	$bid_description = $bidder_data['application_description'];
	$bidded_amount_txt = $this->config->item('project_details_page_bidder_listing_expected_salary_txt');
	$sp_rating = $bidder_data['fulltime_project_user_total_avg_rating_as_employee'];
	$sp_total_reviews = $bidder_data['fulltime_project_user_total_reviews'];
	if(isset($bidder_data['employee_total_completed_fulltime_projects'])){
	 $sp_completed_projects = $bidder_data['employee_total_completed_fulltime_projects'];
	}
}
if($sp_total_reviews == 0){
	$trGiven = number_format($sp_total_reviews,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
}else if($sp_total_reviews == 1) {
	$trGiven = number_format($sp_total_reviews,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
} else if($sp_total_reviews > 1) {
	$trGiven = number_format($sp_total_reviews,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
}
if($bidder_data['bidding_dropdown_option'] == 'NA'){
	if($project_data['project_type'] == 'fixed'){
	
		if(floatval($bidder_data['project_delivery_period']) == 1){
			$bidder_listing_details_fixed_txt = $this->config->item('1_day');
		}else if(floatval($bidder_data['project_delivery_period']) >=2 && floatval($bidder_data['project_delivery_period']) <= 4){
			$bidder_listing_details_fixed_txt = $this->config->item('2_4_days');
		}else if(floatval($bidder_data['project_delivery_period']) >4){
			$bidder_listing_details_fixed_txt = $this->config->item('more_than_or_equal_5_days');
		}
		
		
        if($this->session->userdata ('user')) {
            $bid_amount = '<small><i class="far fa-credit-card"></i>'.$bidded_amount_txt.'<span class="touch_line_break">'.number_format($bidder_data['initial_bidded_amount'], 0, '', ' ')." ".CURRENCY.'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').'<span class="touch_line_break">'. str_replace(".00","",number_format($bidder_data['project_delivery_period'],  2, '.', ' ')) .' '.$bidder_listing_details_fixed_txt.'</span></small>';
        }
		
	} else if($project_data['project_type'] == 'hourly'){
		$total_bid_value = floatval($bidder_data['initial_bidded_hourly_rate']) * floatval($bidder_data['project_delivery_hours']);	
		
		
		
		if(floatval($bidder_data['project_delivery_hours']) == 1){
			$bidder_listing_details_hour_txt = $this->config->item('1_hour');
		}else if(floatval($bidder_data['project_delivery_hours']) >=2 && floatval($bidder_data['project_delivery_hours']) <= 4){
			$bidder_listing_details_hour_txt = $this->config->item('2_4_hours');
		}else if(floatval($bidder_data['project_delivery_hours']) >4){
			$bidder_listing_details_hour_txt = $this->config->item('more_than_or_equal_5_hours');
		}
		
        
        if(($this->session->userdata ('user') && $user[0]->user_id == $bidder_data['project_owner_id'])) { 
            $bid_amount = '<small><i class="fas fa-stopwatch"></i>'.$bidded_amount_txt.'<span class="touch_line_break">'.number_format($bidder_data['initial_bidded_hourly_rate'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_hour').'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').'<span class="touch_line_break">'.str_replace(".00","",number_format($bidder_data['project_delivery_hours'],  2, '.', ' ')).' '.$bidder_listing_details_hour_txt.'</span></small><small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.number_format($total_bid_value, 0, '', ' ')." ".CURRENCY.'</span></small>';
        } else if($this->session->userdata ('user') && $user[0]->user_id == $bidder_data['bidder_id']) {
            $bid_amount = '<small><i class="fas fa-stopwatch"></i>'.$bidded_amount_txt.'<span class="touch_line_break">'.str_replace(".00","",number_format($bidder_data['initial_bidded_hourly_rate'], 0, '', ' '))." ".CURRENCY.$this->config->item('post_project_budget_per_hour').'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').'<span class="touch_line_break">'.str_replace(".00","",number_format($bidder_data['project_delivery_hours'],  2, '.', ' ')).' '.$bidder_listing_details_hour_txt.'</span></small><small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view'). ' '.number_format($total_bid_value, 0, '', ' ')." ".CURRENCY.'</small>';
        } else if($this->session->userdata ('user')) {
          $bid_amount = '<small><i class="fas fa-stopwatch"></i>'.$bidded_amount_txt.'<span class="touch_line_break">'.number_format($bidder_data['initial_bidded_hourly_rate'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_hour').'</span></small><small><i class="far fa-clock"></i>'.$this->config->item('project_details_page_bidder_listing_details_delivery_in').'<span class="touch_line_break">'.str_replace(".00","",number_format($bidder_data['project_delivery_hours'],  2, '.', ' ')).' '.$bidder_listing_details_hour_txt.'</span></small><small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.str_replace(".00","",number_format($total_bid_value, 0, '', ' '))." ".CURRENCY.'</span></small>';
        }

	} else if($project_data['project_type'] == 'fulltime'){
    if($this->session->userdata ('user')) {
      $bid_amount = '<small><i class="far fa-credit-card"></i>'.$bidded_amount_txt.'<span class="touch_line_break">'.number_format($bidder_data['initial_requested_salary'], 0, '', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_month').'</span></small>';
    }
	}
} else if ($bidder_data['bidding_dropdown_option'] == 'to_be_agreed'){

	if($project_data['project_type'] == 'hourly'){
        if(($this->session->userdata ('user') && $user[0]->user_id == $bidder_data['project_owner_id'])) { 
            $bid_amount = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
        } else if($this->session->userdata ('user') && $user[0]->user_id == $bidder_data['bidder_id']) {
            $bid_amount = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
        } else if($this->session->userdata ('user')) {
          $bid_amount = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
        }
	} else {
        if($this->session->userdata ('user')) {
            $bid_amount = '<small><i class="far fa-credit-card"></i>'.$bidded_amount_txt.'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_to_be_agreed_option_selected').'</span></small>';
        }
	}
} else if ($bidder_data['bidding_dropdown_option'] == 'confidential'){
	if($project_data['project_type'] == 'hourly'){
        if(($this->session->userdata ('user') && $user[0]->user_id == $bidder_data['project_owner_id']) ) { 
            $bid_amount = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';
        } else if($this->session->userdata ('user') && $user[0]->user_id == $bidder_data['bidder_id']){
            $bid_amount = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_your_initial_bid_value_txt_sp_view').' '.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</small>';
        } else if($this->session->userdata ('user')) {
          $bid_amount = '<small><i class="far fa-credit-card"></i>'.$this->config->item('hourly_rate_based_project_project_details_page_bidder_listing_initial_bid_value_txt_po_view').'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';
        }
	} else {
        if($this->session->userdata ('user')) {
            $bid_amount = '<small><i class="far fa-credit-card"></i>'.$bidded_amount_txt.'<span class="touch_line_break">'.$this->config->item('displayed_text_project_details_page_bidder_listing_details_confidential_option_selected').'</span></small>';
        }
	}
}
//$bid_description = limitStringShowMoreLess($bid_description);
$descLeng	=	strlen($bid_description);
/*----------- description show for desktop screen start----*/
$desktop_cnt            =	0;
if($descLeng <= $this->config->item('project_details_page_bidder_listing_bid_description_character_limit_desktop')) {
    $desktop_description	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($bid_description, ENT_QUOTES)));
} else {
    $desktop_description	= character_limiter($bid_description,$this->config->item('project_details_page_bidder_listing_bid_description_character_limit_desktop'));
    $desktop_restdescription	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($bid_description, ENT_QUOTES)));
    $desktop_cnt = 1;
}
/*----------- description show for desktop screen end----*/

/*----------- description show for ipad screen start----*/
$tablet_cnt            =	0;
if($descLeng <= $this->config->item('project_details_page_bidder_listing_bid_description_character_limit_tablet')) {
    $tablet_description	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($bid_description, ENT_QUOTES)));
} else {
        $tablet_description	= character_limiter($bid_description,$this->config->item('project_details_page_bidder_listing_bid_description_character_limit_tablet'));
    $tablet_restdescription	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($bid_description, ENT_QUOTES)));
    $tablet_cnt = 1;
}
/*----------- description show for ipad screen end----*/

/*----------- description show for mobile screen start----*/
$mobile_cnt            =	0;
if($descLeng <= $this->config->item('project_details_page_bidder_listing_bid_description_character_limit_mobile')) {
        $mobile_description	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($bid_description, ENT_QUOTES)));
} else {
    $mobile_description	= character_limiter($bid_description,$this->config->item('project_details_page_bidder_listing_bid_description_character_limit_mobile'));
    $mobile_restdescription	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($bid_description, ENT_QUOTES)));
    $mobile_cnt = 1;
}
/*----------- description show for mobile screen end----*/
?>
<div class="default_user_avatar_left_adjust user_avatar_project_owner">		
    <div class="imgTxtR">						
            <div class="imgSize">					
                    <div class="avtOnly">
                            <?php
                            if(!empty($bidder_data['user_avatar']) ) {
                            $user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$bidder_data['profile_name'].USER_AVATAR.$bidder_data['user_avatar'];
                            } else {
								
                                    if(($bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bidder_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bidder_data['is_authorized_physical_person'] == 'Y')){
                                            if($bidder_data['gender'] == 'M'){
                                                    $user_profile_picture = URL . 'assets/images/avatar_default_male.png';
                                            }if($bidder_data['gender'] == 'F'){
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
									if(($bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bidder_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bidder_data['is_authorized_physical_person'] == 'Y' )){
										if($bidder_data['gender'] == 'M'){
											echo $this->config->item('project_details_page_male_user_hires_on_fulltime_projects_as_employee')." ".number_format($sp_completed_projects,0, '.', ' ');
										}else{
											echo $this->config->item('project_details_page_female_user_hires_on_fulltime_projects_as_employee')." ".number_format($sp_completed_projects,0, '.', ' ');
										}
									
									}else{
										echo $this->config->item('project_details_page_company_user_hires_on_fulltime_projects_as_employee')." ".number_format($sp_completed_projects,0, '.', ' ');
									}
								
								}else{
									echo $this->config->item('project_details_page_user_completed_projects_as_sp')." ".number_format($sp_completed_projects,0, '.', ' ');
								}?></small> <?php } ?>
                    </div>
            </div>
    </div>
 </div>
<div class="default_user_details_right_adjust user_details_right_adjust_project_owner">
		<div class="opLBttm opBg">
<div class="default_user_name">
    <a class="default_user_name_link" href="<?php echo site_url ($bidder_data['profile_name']); ?>"><?php echo $name; ?></a>
</div>
<div class="bottom_border default_short_details_field"><?php
$bidding_date = $project_data['project_type'] == 'fulltime' ? $this->config->item('application_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($bidder_data['application_date'])).'</span>' : $this->config->item('bid_date').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($bidder_data['bid_date'])).'</span>';
?><small><i class="far fa-calendar-alt"></i><?php echo $bidding_date; ?></small><?php echo $bid_amount; ?></div>

<div class="default_user_description desktop-secreen">
    <p id="desktop_lessD<?php echo $bidder_data['bidder_id']; ?>">
        <?php echo $desktop_description;?><?php if($desktop_cnt==1) {?><span id="desktop_dotsD<?php echo $bidder_data['bidder_id']; ?>"></span><button onclick="showMoreDescription('desktop', <?php echo $bidder_data['bidder_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
        <?php } ?></p><p id="desktop_moreD<?php echo $bidder_data['bidder_id']; ?>" class="moreD">
        <?php echo $desktop_restdescription;?><button onclick="showMoreDescription('desktop', <?php echo $bidder_data['bidder_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button></p>
</div>
<div class="default_user_description ipad-screen">
    <p id="tablet_lessD<?php echo $bidder_data['bidder_id']; ?>">
        <?php echo $tablet_description;?><?php if($tablet_cnt==1) {?><span id="tablet_dotsD<?php echo $bidder_data['bidder_id']; ?>"></span><button onclick="showMoreDescription('tablet', <?php echo $bidder_data['bidder_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
        <?php } ?></p><p id="tablet_moreD<?php echo $bidder_data['bidder_id']; ?>" class="moreD">
        <?php echo $tablet_restdescription;?><button onclick="showMoreDescription('tablet', <?php echo $bidder_data['bidder_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button></p>
</div>
<div class="default_user_description mobile-screen">
    <p id="mobile_lessD<?php echo $bidder_data['bidder_id']; ?>">
        <?php echo $mobile_description;?><?php if($mobile_cnt==1) {?><span id="mobile_dotsD<?php echo $bidder_data['bidder_id']; ?>"></span><button onclick="showMoreDescription('mobile', <?php echo $bidder_data['bidder_id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
        <?php } ?></p><p id="mobile_moreD<?php echo $bidder_data['bidder_id']; ?>" class="moreD">
        <?php echo $mobile_restdescription;?><button onclick="showMoreDescription('mobile', <?php echo $bidder_data['bidder_id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button></p>
</div>
<?php 
$rightAlign = '';
if($this->session->userdata ('user') && ($user[0]->user_id == $bidder_data['bidder_id'] || $user[0]->user_id == $bidder_data['project_owner_id']))
{ 
   $rightAlign = 'rightAlign';
 } 
if($this->session->userdata ('user') && ((isset($bidder_data['bid_attachments']) && !empty($bidder_data['bid_attachments'])) || ($user[0]->user_id == $bidder_data['project_owner_id']) || ($user[0]->user_id == $bidder_data['bidder_id'] && $project_status != 'cancelled'))){	
?>	
<div class="acCo">
    <div class="row <?php echo $rightAlign;?>">
		<!-- <div class="col-md-4 col-sm-4 col-12 padding_left0 attachmentOnly"> -->
		<div class="padding_left0 attachmentOnly">
			<?php if($this->session->userdata ('user') && isset($bidder_data['bid_attachments']) && !empty($bidder_data['bid_attachments'])){ ?>
			
				<?php 
					foreach($bidder_data['bid_attachments'] as $bid_attachment_value){ 
						if(($user[0]->user_id == $bid_attachment_value['user_id']) || ($user[0]->user_id == $project_data['project_owner_id'])){
				?>
				<label class="attachFile attachBottom"><span><a style="cursor:pointer" data-sp-id = "<?php echo Cryptor::doEncrypt($bid_attachment_value['user_id']); ?>" data-po-id = "<?php echo Cryptor::doEncrypt($project_data['project_owner_id']); ?>" class="download_attachment download_bidder_list_bid_attachment" data-attr = "<?php echo Cryptor::doEncrypt($bid_attachment_value['id']); ?>"><i class="fas fa-paperclip"></i><?php echo $bid_attachment_value['bid_attachment_name']; ?></a></span></label>
				<?php
						}
			}
				?>
		
		<?php } if($user[0]->user_id == $bidder_data['bidder_id'] && $project_status != 'cancelled'){ ?>
			<div class="er_bid_btn rightAwardBid <?php echo $project_data['project_type']== 'fulltime' ?'bidderListFulltimeBtn' : 'bidderListBtn'; ?>">				
				<button type="button"  data-attr="<?php echo Cryptor::doEncrypt($bidder_data['bidder_id']); ?>"  class="btn retract_bid_confirmation default_btn red_btn"><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_project_active_bidder_listing_retract_bid_btn_txt') : $this->config->item('project_details_page_project_active_bidder_listing_retract_bid_btn_txt');?></button><button type="button"  data-id="<?php echo $bidder_data['id']; ?>"  class="btn edit_bid default_btn green_btn"><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_project_active_bidder_listing_edit_bid_btn_txt') : $this->config->item('project_details_page_project_active_bidder_listing_edit_bid_btn_txt');?></button>
			</div>
		<?php } if($user[0]->user_id == $bidder_data['project_owner_id'] && $project_status != 'cancelled'){ ?>
			<!-- <div class="col-md-8 col-sm-8 col-12 rightOnly"> -->
			<div class="rightOnly rightAwardBid <?php echo $project_data['project_type']== 'fulltime' ?'bidderListPOFulltimeBtn' : 'bidderListPOBtn'; ?>">
				<button type="button" data-po-id ="<?php echo Cryptor::doEncrypt($project_data['project_owner_id']); ?>" class="btn default_btn green_btn award_bid_confirmation default_btn_padding" 
				data-attr="<?php echo Cryptor::doEncrypt($bidder_data['bidder_id']); ?>"
				
				><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_project_active_bidder_listing_award_bid_bid_btn_txt') : $this->config->item('award_btn_txt');?></button><button type="button" class="btn default_btn blue_btn contact-bidder default_btn_padding"
				data-name="<?php echo $name?>" 
				data-id="<?php echo $bidder_data['bidder_id']; ?>" 
				data-project-title="<?php echo $project_data['project_title'] ?>" 
				data-project-id="<?php echo $project_data['project_id']?>" 
				data-profile="<?php echo $user_profile_picture; ?>"
				data-project-owner="<?php echo $project_data['project_owner_id']?>"
				><?php echo $this->config->item('contactme_button'); ?></button>
			</div>
		<?php
		
			}
		?>
		</div>
		<?php	
		if($this->session->userdata ('user') ){
			if($project_status == 'cancelled' && ($user[0]->user_id == $bidder_data['project_owner_id'])){
			?>
			<div class="rightOnly rightAwardBid">
				<button type="button" class="btn default_btn blue_btn contact-bidder default_btn_padding"  
				data-name="<?php echo $name?>" 
				data-id="<?php echo $bidder_data['bidder_id']; ?>" 
				data-project-title="<?php echo $project_data['project_title'] ?>" 
				data-project-id="<?php echo $project_data['project_id']?>"
				data-profile="<?php echo $user_profile_picture; ?>"
				data-project-owner="<?php echo $project_data['project_owner_id']?>"
				><?php echo $this->config->item('contactme_button'); ?></button>
			</div>
		<?php
		} /*else {
			if($user[0]->user_id == $bidder_data['bidder_id'] && $project_status != 'cancelled'){*/
		?>
		<?php
		/* 	<!--<div class="col-md-8 col-sm-8 col-12 rightOnly">
				<div class="er_bid_btn">
					<button type="button"  data-id="<?php echo $bidder_data['id']; ?>"  class="btn edit_bid default_btn green_btn"><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_project_active_bidder_listing_edit_bid_btn_txt') : $this->config->item('project_details_page_project_active_bidder_listing_edit_bid_btn_txt');?></button>
                    <button type="button"  data-attr="<?php echo Cryptor::doEncrypt($bidder_data['bidder_id']); ?>"  class="btn retract_bid_confirmation default_btn red_btn"><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_project_active_bidder_listing_retract_bid_btn_txt') : $this->config->item('project_details_page_project_active_bidder_listing_retract_bid_btn_txt');?></button>
				</div>
			</div>-->
	<?php
			//}else if($user[0]->user_id == $bidder_data['project_owner_id']){
	?>
			<!-- <div class="col-md-8 col-sm-8 col-12 rightOnly"> -->
			<!--<div class="rightOnly">
				<button type="button" data-po-id ="<?php echo Cryptor::doEncrypt($project_data['project_owner_id']); ?>" class="btn default_btn green_btn award_bid_confirmation" 
				data-attr="<?php echo Cryptor::doEncrypt($bidder_data['bidder_id']); ?>"
				
				><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_project_active_bidder_listing_award_bid_bid_btn_txt') : $this->config->item('award_btn_txt');?></button><button type="button" class="btn default_btn blue_btn contact-bidder"
				data-name="<?php echo $name?>" 
				data-id="<?php echo $bidder_data['bidder_id']; ?>" 
				data-project-title="<?php echo $project_data['project_title'] ?>" 
				data-project-id="<?php echo $project_data['project_id']?>" 
				data-profile="<?php echo $user_profile_picture; ?>"
				data-project-owner="<?php echo $project_data['project_owner_id']?>"
				><?php echo $this->config->item('contactme_button'); ?></button>
			</div>-->  */?>
	<?php
		
			//}
		//}
	}
	?>
	</div>
</div>
<?php
}	
?>	

<!-- Contact Chat End -->
</div>
	</div>
<!-- Contact Chat End -->

<!-- Contact Chat Script Start -->
<script>
    function myChatDialog() {
		$('.chat_container').find('.chatBtnDetails:nth-child(1)').show();
		$('.chat_container').find('.chatBtnDetails:nth-child(2)').find('.myChat').css({'right': '316px'}).show();
		$('.chat_container').find('.chatBtnDetails:nth-child(2)').show();
    }
    function myChatDialogMinMax() {
        if($("#chatMinMax").val()=='0') {
            $("#chatMinMax").val(1);
            $(".btnChat, .mesgSend").hide();
        } else {
            $("#chatMinMax").val(0);
            $(".btnChat, .mesgSend").show();
        }
    }
</script>
<script>
	$(document).ready(function(){
	  $('[data-toggle="tooltip"]').tooltip();
	  
	  
	  var leftSec = $("#bidder_list_<?php echo $bidder_data['bidder_id']; ?> .default_user_avatar_left_adjust").height();
	  var rightSec = $("#bidder_list_<?php echo $bidder_data['bidder_id']; ?> .default_user_details_right_adjust").height()-25;
	  if(leftSec > rightSec && $(window).outerWidth() >767 ) {
		  var adHeight = leftSec-rightSec;
		  $("#bidder_list_<?php echo $bidder_data['bidder_id']; ?> .default_user_description p").css({"min-height" : adHeight});
	  }
	  //console.log(leftSec+"=="+rightSec+"===="+adHeight);
	});
	$(window).resize(function() {
		if($(window).outerWidth() <=767 ) {
			$("#bidder_list_<?php echo $bidder_data['bidder_id']; ?> .default_user_description p").removeAttr('style');
		}
	  var leftSec = $("#bidder_list_<?php echo $bidder_data['bidder_id']; ?> .default_user_avatar_left_adjust").outerHeight();
	  var rightSec = $("#bidder_list_<?php echo $bidder_data['bidder_id']; ?> .default_user_details_right_adjust").outerHeight()-25;
	  if(leftSec > rightSec && $(window).outerWidth() >767) {
		  var adHeight = leftSec-rightSec;
		  $("#bidder_list_<?php echo $bidder_data['bidder_id']; ?> .default_user_description p").css({"min-height" : adHeight});
	  }
	  //console.log(leftSec+"=="+rightSec+"===="+adHeight);
	});
</script>
<!-- Contact Chat Script Start -->