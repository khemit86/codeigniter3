<?php
$user = $this->session->userdata('user');	
if(!empty($ratings_feedbacks_data) && $view_type == 'sp'){
?>
<div class="company_listingTab">
<?php
	foreach($ratings_feedbacks_data as $key=>$value){
	$counter =$value['id'];
	$po_name = (($value['po_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['po_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['po_is_authorized_physical_person'] == 'Y')) ? $value['po_first_name'] . ' ' . $value['po_last_name'] : $value['po_company_name'];
	
	$sp_first_name = (($value['sp_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['sp_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['sp_is_authorized_physical_person'] == 'Y')) ? $value['sp_first_name']  : $value['sp_company_name'];
	
	$sp_name = (($value['sp_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['sp_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['sp_is_authorized_physical_person'] == 'Y')) ? $value['sp_first_name']." ".$value['sp_last_name']  : $value['sp_company_name'];

?>
	<div class="serPrv">
		<div class="sRate">
			<label>
				<a href="<?php echo base_url().$this->config->item('project_detail_page_url')."?id=".$value['feedback_provided_on_project_id']; ?>" target="_blank" class="default_project_title"><?php echo $value['project_title'];  ?></a>
			</label>
			<label>
				<small>
					<?php echo show_dynamic_rating_stars($value['project_avg_rating_as_sp']);?>
				</small>
				<span class="default_avatar_review"><?php echo $value['project_avg_rating_as_sp']; ?></span>
				<small class="receive_notification expand_notification_area<?php echo $counter?>"><a class="rcv_notfy_btn" onclick="showMoreReview('<?php echo $counter?>')">(<sup>+</sup>)</a>
				<input type="hidden" id="moreReview<?php echo $counter;?>" value="1"></small>
			</label>
		</div>
		<p>	
			<span><?php echo str_replace(array('{feedback_provided_on_date}','{user_first_name_last_name_or_company_name}','{user_profile_page_url}'),array(date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($value['feedback_provided_on_date'])),$po_name,site_url ($value['po_profile_name'])),$this->config->item('user_profile_page_feedbacks_tab_review_posted_on_date_txt')) ?></span>
		</p>
		<!-- Collapse Section Start -->
		<div id="rcv_notfy<?php echo $counter;?>" class="collapseSection" style="display:none">
			<div class="row">
				<!-- Left Section Start -->
				<div class="col-md-5 col-sm-5 col-12 ratingLeft projectFour">
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_quality_po_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['quality_of_work']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['quality_of_work']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_communication_po_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['communication']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['communication']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_professionalism_po_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['professionalism']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['professionalism'];?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_expertise_po_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['expertise']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['expertise']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_value_for_money_po_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['value_for_money']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['value_for_money']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<!-- Left Section End -->
				<!-- Middle Section Start -->
				<div class="col-md-2 col-sm-2 col-12 ratingMiddle">
					<div class="ifOr"></div>
				</div>
				<!-- Middle Section Start -->
				<!-- Right Section Start -->
				<div class="col-md-5 col-sm-5 col-12 ratingRight">											
					<div class="responseDiv">
						<div class="workText">
							<span class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_project_delivered_within_agreed_budget_po_view') ?></span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['project_delivered_within_agreed_budget'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>											
					<div class="responseDiv">
						<div class="workText">
							<span class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_work_delivered_within_agreed_time_slot_po_view') ?></span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['work_delivered_within_agreed_time_slot'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>											
					<div class="responseDiv">
						<div class="workText">
							
							<span class="default_black_bold"><?php
							if(($value['sp_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['sp_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['sp_is_authorized_physical_person'] == 'Y')){
								if($value['sp_is_authorized_physical_person'] == 'Y'){
									if($value['sp_gender'] =='M' ){
										echo str_replace(array('{user_first_name_or_company_name}'),array($sp_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_company_app_male_again_po_view'));
									}else{
										echo str_replace(array('{user_first_name_or_company_name}'),array($sp_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_company_app_female_again_po_view'));
									}
								}else{
									if($value['sp_gender'] =='M' ){
										echo str_replace(array('{user_first_name_or_company_name}'),array($sp_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_male_again_po_view'));
									}else{
										echo str_replace(array('{user_first_name_or_company_name}'),array($sp_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_female_again_po_view'));
									}
								}
							}else{
								echo str_replace(array('{user_first_name_or_company_name}'),array($sp_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_company_again_po_view')); 
							}
							?>
							</span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['would_you_hire_sp_again'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>											
					<div class="responseDiv">
						<div class="workText">
							<span class="default_black_bold"><?php
							if(($value['sp_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['sp_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['sp_is_authorized_physical_person'] == 'Y')){
								if($value['sp_is_authorized_physical_person'] == 'Y'){
									if($value['sp_gender'] =='M' ){
										echo str_replace(array('{user_first_name_or_company_name}'),array($sp_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_company_app_male_po_view'));
									}else{
										echo str_replace(array('{user_first_name_or_company_name}'),array($sp_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_company_app_female_po_view'));
									}
								}else{
									if($value['sp_gender'] =='M' ){
										echo str_replace(array('{user_first_name_or_company_name}'),array($sp_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_male_po_view'));
									}else{
										echo str_replace(array('{user_first_name_or_company_name}'),array($sp_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_female_po_view'));
									}
								}
							}else{
								echo str_replace(array('{user_first_name_or_company_name}'),array($sp_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_company_po_view')); 
							}
							?></span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['would_you_recommend_sp'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<!-- Right Section Start -->
			</div>
		</div>
		<!-- Collapse Section End -->								
		<div class="descTxt default_user_description desktop-secreen">
			<?php
				$desktop_cnt            =	0;
				$desktop_descLeng	=	strlen($value['feedback_left_by_po']);  
				if($desktop_descLeng <= $this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_desktop')) {
				 $desktop_description	= nl2br(htmlspecialchars($value['feedback_left_by_po'], ENT_QUOTES));
				} else {
					$desktop_description	= character_limiter(nl2br($value['feedback_left_by_po']),$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_desktop'));
					$desktop_restdescription = nl2br(htmlspecialchars($value['feedback_left_by_po'], ENT_QUOTES));
					$desktop_cnt = 1;
					}
			?>
			<p id="<?php echo "desktop_we_".$value['id'] ?>_lessD">
				<?php echo $desktop_description; ?><?php if($desktop_cnt==1) {?><span id="<?php echo "desktop_we_".$value['id'] ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "desktop_we_".$value['id'] ?>')"><?php echo $this->config->item('show_more_txt'); ?></button><?php } ?></p>
			<p id="<?php echo "desktop_we_".$value['id'] ?>_moreD" class="moreD">
				<?php echo $desktop_restdescription;?><button onclick="showMoreDescription('<?php echo "desktop_we_".$value['id'] ?>')"><?php echo $this->config->item('show_less_txt'); ?></button></p>
		</div>
		<div class="descTxt default_user_description ipad-screen">
			<?php
			$tablet_cnt            =	0;
			$tablet_descLeng	=	strlen($value['feedback_left_by_po']);
			if($tablet_descLeng <= $this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_tablet')) {
			$tablet_description	= nl2br(htmlspecialchars($value['feedback_left_by_po'], ENT_QUOTES));
			} else {
				//$tablet_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_tablet'));
				  $tablet_description	= character_limiter(nl2br($value['feedback_left_by_po']),$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_tablet'));
				$tablet_restdescription = nl2br(htmlspecialchars($value['feedback_left_by_po'], ENT_QUOTES));
				$tablet_cnt = 1;
			}
			?>
			<p id="<?php echo "tablet_we_".$value['id'] ?>_lessD">
			<?php echo $tablet_description; ?><?php if($tablet_cnt==1) {?><span id="<?php echo "tablet_we_".$value['id'] ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "tablet_we_".$value['id'] ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
			<?php } ?>
			</p>
			<p id="<?php echo "tablet_we_".$value['id'] ?>_moreD" class="moreD">
			<?php echo $tablet_restdescription;?><button onclick="showMoreDescription('<?php echo "tablet_we_".$value['id'] ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
			</p>
		</div>
		<div class="descTxt default_user_description mobile-screen">
			<?php
				$mobile_cnt            =	0;
				$mobile_descLeng	=	strlen($value['feedback_left_by_po']);
				if($mobile_descLeng <= $this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_mobile')) {
					$mobile_description	= nl2br(htmlspecialchars($value['feedback_left_by_po'], ENT_QUOTES));
				} else {
					//$mobile_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_mobile'));
					$mobile_description	= character_limiter(nl2br($value['feedback_left_by_po']),$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_mobile'));
					$mobile_restdescription = nl2br(htmlspecialchars($value['feedback_left_by_po'], ENT_QUOTES));
					$mobile_cnt = 1;
				}
			?>
			<p id="<?php echo "mobile_we_".$value['id'] ?>_lessD">
				<?php echo $mobile_description; ?><?php if($mobile_cnt==1) {?><span id="<?php echo "mobile_we_".$value['id'] ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "mobile_we_".$value['id'] ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
				<?php } ?>
			</p>
			<p id="<?php echo "mobile_we_".$value['id'] ?>_moreD" class="moreD">
				<?php echo $mobile_restdescription ;?><button onclick="showMoreDescription('<?php echo "mobile_we_".$value['id'] ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
			</p>
		</div>
		<div>
			<div id="<?php echo "feedback_reply_conatiner_".$value['id'] ?>" class="reviewReply" style="display:<?php echo empty($value['feedback_reply_by_sp']) ? 'none' : 'block' ?>">
				<?php echo $this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_ratings_feedbacks_reply_detail', array('section_id'=>$value['id'],'feedback_reply'=>$value['feedback_reply_by_sp'],'feedback_reply_on_date'=>$value['feedback_reply_on_date'],'reply_by_user'=>$sp_name), true); ?>
			</div>
			<?php
			if($this->session->userdata('user')){
				if($user[0]->user_id == $value['feedback_recived_by_sp_id'] && empty($value['feedback_reply_by_sp'])){
					 echo $this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_ratings_feedbacks_reply_form', array('section_id'=>$value['id'],'view_type'=>'sp','feedback_recived_by'=>$value['feedback_recived_by_sp_id'],'feedback_given_by'=>$value['feedback_given_by_po_id'],'project_id'=>$value['feedback_provided_on_project_id']), true); 
				}
			}
			?>
		</div>
	</div>
<?php
	$counter ++;
	}
	?>
	</div>
<?php
	if(!$is_last_page){	
	?>
	<div class="row">
		<div class="col-md-12 text-center companyRating_viewMore">
			<input type="hidden" id="pageno_sp_reviews" value="1">
			<button type="button" id="loadmore_sp_reviews" class="btn default_btn blue_btn" style="height:35px;"><?php echo $this->config->item('load_more_results'); ?> <i id="sp_spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
		</div>
	</div>
<?php
	}
}
?>
<?php
if(!empty($ratings_feedbacks_data) && $view_type == 'po'){
?>
<div class="company_listingTab">
<?php
	
	
	foreach($ratings_feedbacks_data as $key=>$value){
	$counter =$value['id'];
	
	$sp_name = (($value['sp_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['sp_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['sp_is_authorized_physical_person'] == 'Y')) ? $value['sp_first_name'] . ' ' . $value['sp_last_name'] : $value['sp_company_name'];
	
	$po_first_name = (($value['po_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['po_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['po_is_authorized_physical_person'] == 'Y')) ? $value['po_first_name']  : $value['po_company_name'];
	
	$po_name = (($value['po_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['po_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['po_is_authorized_physical_person'] == 'Y')) ? $value['po_first_name']." ".$value['po_last_name']  : $value['po_company_name'];

?>
	<div class="serPrv">
		<div class="sRate">
			<label>
				<a href="<?php echo base_url().$this->config->item('project_detail_page_url')."?id=".$value['feedback_provided_on_project_id']; ?>" class="default_project_title"><?php echo $value['project_title'];  ?></a>
			</label>
			<label>
				<small>
					<?php echo show_dynamic_rating_stars($value['project_avg_rating_as_po']);?>
				</small>
				<span class="default_avatar_review"><?php echo $value['project_avg_rating_as_po']; ?></span>
				<small class="receive_notification expand_notification_area<?php echo $counter?>"><a class="rcv_notfy_btn" onclick="showMoreReview('<?php echo $counter?>')">(<sup>+</sup>)</a>
				<input type="hidden" id="moreReview<?php echo $counter;?>" value="1"></small>
			</label>
		</div>
		<p>	
			<span><?php echo str_replace(array('{feedback_provided_on_date}','{user_first_name_last_name_or_company_name}','{user_profile_page_url}'),array(date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($value['feedback_provided_on_date'])),$sp_name,site_url ($value['sp_profile_name'])),$this->config->item('user_profile_page_feedbacks_tab_review_posted_on_date_txt')) ?></span>
		</p>
		<!-- Collapse Section Start -->
		<div id="rcv_notfy<?php echo $counter;?>" class="collapseSection" style="display:none">
			<div class="row">
				<!-- Left Section Start -->
				<div class="col-md-5 col-sm-5 col-12 ratingLeft projectThree">
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_clarity_in_requirements_sp_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['clarity_in_requirements']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['clarity_in_requirements']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_communication_sp_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['communication']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['communication']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_payment_promptness_sp_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['payment_promptness']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['payment_promptness']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<!-- Left Section End -->
				<!-- Middle Section Start -->
				<div class="col-md-2 col-sm-2 col-12 ratingMiddle">
					<div class="ifOr"></div>
				</div>
				<!-- Middle Section Start -->
				<!-- Right Section Start -->
				<div class="col-md-5 col-sm-5 col-12 ratingRight">											
					<div class="responseDiv">
						<div class="workText">
							<span class="default_black_bold"><?php
							if(($value['po_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['po_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['po_is_authorized_physical_person'] == 'Y')){
								if($value['po_is_authorized_physical_person'] == 'Y'){
									if($value['po_gender'] =='M' ){
										echo str_replace(array('{user_first_name_or_company_name}'),array($po_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_company_app_male_sp_view'));
									}else{
										echo str_replace(array('{user_first_name_or_company_name}'),array($po_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_company_app_female_sp_view'));
									}
								}else{
									if($value['po_gender'] =='M' ){
										echo str_replace(array('{user_first_name_or_company_name}'),array($po_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_male_sp_view'));
									}else{
										echo str_replace(array('{user_first_name_or_company_name}'),array($po_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_female_sp_view'));
									}
								}
							}else{
								echo str_replace(array('{user_first_name_or_company_name}'),array($po_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_company_sp_view')); 
							}
							?></span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['would_you_work_again_with_po'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>											
					<div class="responseDiv">
						<div class="workText">
							<span class="default_black_bold"><?php
							if(($value['po_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['po_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['po_is_authorized_physical_person'] == 'Y')){
								if($value['po_is_authorized_physical_person'] == 'Y'){
									if($value['po_gender'] =='M' ){
										echo str_replace(array('{user_first_name_or_company_name}'),array($po_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_company_app_male_sp_view'));
									}else{
										echo str_replace(array('{user_first_name_or_company_name}'),array($po_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_company_app_female_sp_view'));
									}
								}else{
									if($value['po_gender'] =='M' ){
										echo str_replace(array('{user_first_name_or_company_name}'),array($po_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_male_sp_view'));
									}else{
										echo str_replace(array('{user_first_name_or_company_name}'),array($po_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_female_sp_view'));
									}
								}
							}else{
								echo str_replace(array('{user_first_name_or_company_name}'),array($po_first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_company_sp_view')); 
							}
							?></span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['would_you_recommend_po'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<!-- Right Section Start -->
			</div>
		</div>
		<!-- Collapse Section End -->								
		<div class="descTxt default_user_description desktop-secreen">
			<?php
				$desktop_cnt            =	0;
				$desktop_descLeng	=	strlen($value['feedback_left_by_sp']);  
				if($desktop_descLeng <= $this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_desktop')) {
				 $desktop_description	= nl2br(htmlspecialchars($value['feedback_left_by_sp'], ENT_QUOTES));
				} else {
					$desktop_description	= character_limiter(nl2br($value['feedback_left_by_sp']),$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_desktop'));
					$desktop_restdescription = nl2br(htmlspecialchars($value['feedback_left_by_sp'], ENT_QUOTES));
					$desktop_cnt = 1;
					}
			?>
			<p id="<?php echo "desktop_we_".$value['id'] ?>_lessD">
				<?php echo $desktop_description; ?><?php if($desktop_cnt==1) {?><span id="<?php echo "desktop_we_".$value['id'] ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "desktop_we_".$value['id'] ?>')"><?php echo $this->config->item('show_more_txt'); ?></button><?php } ?></p>
			<p id="<?php echo "desktop_we_".$value['id'] ?>_moreD" class="moreD">
				<?php echo $desktop_restdescription;?><button onclick="showMoreDescription('<?php echo "desktop_we_".$value['id'] ?>')"><?php echo $this->config->item('show_less_txt'); ?></button></p>
		</div>
		<div class="descTxt default_user_description ipad-screen">
			<?php
			$tablet_cnt            =	0;
			$tablet_descLeng	=	strlen($value['feedback_left_by_sp']);
			if($tablet_descLeng <= $this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_tablet')) {
			$tablet_description	= nl2br(htmlspecialchars($value['feedback_left_by_sp'], ENT_QUOTES));
			} else {
				//$tablet_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_tablet'));
				  $tablet_description	= character_limiter(nl2br($value['feedback_left_by_sp']),$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_tablet'));
				$tablet_restdescription = nl2br(htmlspecialchars($value['feedback_left_by_sp'], ENT_QUOTES));
				$tablet_cnt = 1;
			}
			?>
			<p id="<?php echo "tablet_we_".$value['id'] ?>_lessD">
			<?php echo $tablet_description; ?><?php if($tablet_cnt==1) {?><span id="<?php echo "tablet_we_".$value['id'] ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "tablet_we_".$value['id'] ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
			<?php } ?>
			</p>
			<p id="<?php echo "tablet_we_".$value['id'] ?>_moreD" class="moreD">
			<?php echo $tablet_restdescription;?><button onclick="showMoreDescription('<?php echo "tablet_we_".$value['id'] ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
			</p>
		</div>
		<div class="descTxt default_user_description mobile-screen">
			<?php
				$mobile_cnt            =	0;
				$mobile_descLeng	=	strlen($value['feedback_left_by_sp']);
				if($mobile_descLeng <= $this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_mobile')) {
					$mobile_description	= nl2br(htmlspecialchars($value['feedback_left_by_sp'], ENT_QUOTES));
				} else {
					//$mobile_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_mobile'));
					$mobile_description	= character_limiter(nl2br($value['feedback_left_by_sp']),$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_mobile'));
					$mobile_restdescription = nl2br(htmlspecialchars($value['feedback_left_by_sp'], ENT_QUOTES));
					$mobile_cnt = 1;
				}
			?>
			<p id="<?php echo "mobile_we_".$value['id'] ?>_lessD">
				<?php echo $mobile_description; ?><?php if($mobile_cnt==1) {?><span id="<?php echo "mobile_we_".$value['id'] ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "mobile_we_".$value['id'] ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
				<?php } ?>
			</p>
			<p id="<?php echo "mobile_we_".$value['id'] ?>_moreD" class="moreD">
				<?php echo $mobile_restdescription ;?><button onclick="showMoreDescription('<?php echo "mobile_we_".$value['id'] ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
			</p>
		</div>
		<div>
			<div id="<?php echo "feedback_reply_conatiner_".$value['id'] ?>" class="reviewReply" style="display:<?php echo empty($value['feedback_reply_by_po']) ? 'none' : 'block' ?>">
				<?php echo $this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_ratings_feedbacks_reply_detail', array('section_id'=>$value['id'],'feedback_reply'=>$value['feedback_reply_by_po'],'feedback_reply_on_date'=>$value['feedback_reply_on_date'],'reply_by_user'=>$po_name), true); ?>
			</div>
			<?php
			if($this->session->userdata('user')){
				if($user[0]->user_id == $value['feedback_recived_by_po_id'] && empty($value['feedback_reply_by_po'])){
					 echo $this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_ratings_feedbacks_reply_form', array('section_id'=>$value['id'],'view_type'=>'po','feedback_recived_by'=>$value['feedback_recived_by_po_id'],'feedback_given_by'=>$value['feedback_given_by_sp_id'],'project_id'=>$value['feedback_provided_on_project_id']), true); 
				}
				
			}
			?>
		</div>
	</div>
<?php
	$counter ++;
	}
	?>
	</div>
<?php
	if(!$is_last_page){	
	?>
	<div class="row">
		<div class="col-md-12 text-center companyRating_viewMore">
			<input type="hidden" id="pageno_po_reviews" value="1">
			<button type="button" id="loadmore_po_reviews" class="btn default_btn blue_btn" style="height:35px;"><?php echo $this->config->item('load_more_results'); ?> <i id="po_spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
		</div>
	</div>
<?php
	}
}
// When no feedback for sp/po view then below code will execute
if(empty($ratings_feedbacks_data) && $view_type == 'sp'){
	if($this->session->userdata('user') && $user[0]->user_id == $user_detail['user_id']){
               $no_data_msg =  $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_sp_view');
	}else{
		if(($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')){	
			
			if($user_detail['gender'] == 'M'){
			
				if($user_detail['is_authorized_physical_person'] == 'Y'){
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_company_app_male_visitor_view');
				}else{
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_male_visitor_view');
				}
			}else if($user_detail['gender'] == 'F'){
				if($user_detail['is_authorized_physical_person'] == 'Y'){
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_company_app_female_visitor_view');
				}else{
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_female_visitor_view');
				}
			}
			$no_data_msg = str_replace(array('{user_first_name_last_name}'),array($user_detail['first_name']." ".$user_detail['last_name']),$no_data_msg);
		}else{
			$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_no_data_company_visitor_view');
			$no_data_msg = str_replace(array('{user_company_name}'),array($user_detail['company_name']),$no_data_msg);
		}

	}
	echo $noRecDiv = '<div class="default_blank_message">'.$no_data_msg.'</div>';
}
if(empty($ratings_feedbacks_data) && $view_type == 'po'){
	if($this->session->userdata('user') && $user[0]->user_id == $user_detail['user_id']){
        $no_data_msg =  $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_po_view');
	}else{
		if(($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')){	
			
			if($user_detail['gender'] == 'M'){
				if($user_detail['is_authorized_physical_person'] == 'Y'){
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_company_app_male_visitor_view');
				}else{	
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_male_visitor_view');
				}
			}else if($user_detail['gender'] == 'F'){
				if($user_detail['is_authorized_physical_person'] == 'Y'){
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_company_app_female_visitor_view');
				}else{
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_female_visitor_view');
				}
			}
			$no_data_msg = str_replace(array('{user_first_name_last_name}'),array($user_detail['first_name']." ".$user_detail['last_name']),$no_data_msg);
		}else{
			$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_po_tab_no_data_company_visitor_view');
			$no_data_msg = str_replace(array('{user_company_name}'),array($user_detail['company_name']),$no_data_msg);
		}

	}
	echo $noRecDiv = '<div class="default_blank_message">'.$no_data_msg.'</div>';
}
?>




