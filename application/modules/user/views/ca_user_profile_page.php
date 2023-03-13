<div class="headTopCookies"></div>
<div class="dashTop">
	<?php
	//$user_cover_picture_show_css = "display:none;";	
	$user_cover_picture = "";
	$user_cover_picture_css = "";
	$user = $this->session->userdata('user');
	$hire_me_user_id = $this->session->userdata('hire_me_user_id');
	$this->session->unset_userdata('hire_me_user_id');

	/* if($user_detail['current_membership_plan_id'] == 4 ){
		
		$user_cover_picture_show_css = "display:block;";	
	} */
	if($user_cover_picture_exist_status){
		//$user_cover_picture_exist_status = true;
		$user_cover_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_COVER_PICTURE.$user_detail['profile_cover_picture_name'];
		
		// Read image path, convert to base64 encoding
		//$imageData = base64_encode(file_get_contents($user_cover_picture));
		// Format the image SRC:  data:{mime};base64,{data};
		//$user_cover_picture = 'data: '.mime_content_type($user_cover_picture).';base64,'.$imageData;
		$user_cover_picture_css = "background-image:url('".$user_cover_picture."');min-width: 100%;"; 
	}
	if($user_profile_picture_exist_status){
            $user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
	} else {
		if(($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')){
			if($user_detail['gender'] == 'M'){
				$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
			}if($user_detail['gender'] == 'F'){
			   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
			}
		} else {
			$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
		}
	}
    //$totalReviews = get_sp_total_reviews_count($user_detail['user_id']);     
		$totalReviews = $user_detail['fulltime_project_user_total_reviews']+$user_detail['project_user_total_reviews'];     
		
		$name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];
	

    ?>
	<!--<div class="uploadImg" style="<?php //echo $user_cover_picture_show_css; ?>">-->
	<div class="uploadImg" style="<?php echo  $user_cover_picture_exist_status ? '' : 'display:none'; ?>">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12 no-padding">
			<div id="topContainer" class="<?php echo $user_cover_picture!='' ? 'bgposition' : '';?>" style="<?php echo $user_cover_picture_css; ?>"></div>
			</div>
		</div>	
	</div>
	<div class="clearfix"></div>
	<!-- Upload Image Cover Section End -->

	<div class="profSecond userProfileAdjust"  style="display:none;">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12">
				<div class="user_profile_avatar_adjust">
					<div class="onPerson userDesktop <?php if((!empty($user_data) && $user_data['user_id'] != $user_detail['user_id']) || empty($user_data)) {echo 'logout_contactBtn';}?>">
						<div class="avtOnly">
							<div id="profile-picture" class="default_avatar_image" style="background-image: url('<?php echo $user_profile_picture;?>');">
								<span class="actON"></span>
							</div>
						</div>
						<div class="sRate avatar_review_star_resize">
							<small>
								<?php echo show_dynamic_rating_stars($user_detail['user_total_avg_rating_as_sp']);?>
							</small>
							<span class="default_avatar_review"><?php echo $user_detail['user_total_avg_rating_as_sp']; ?></span>
						</div>
						<div class="default_avatar_total_review"><?php
							if($totalReviews == 0){
								$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
							}else if($totalReviews == 1) {
								$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
							} else if($totalReviews > 1) {
								$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
							}
							echo $trGiven;?></div>
						<div class="default_project_socialicon">
							<a class="fb_share_profile" data-link="<?php echo $fb_share_url; ?>"><i class="fa fa-facebook"></i></a>
							<a class="twitter_share_profile" data-link="<?php echo $twitter_share_url; ?>" data-title="<?php echo $name; ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(($user_detail['description']), ENT_QUOTES)),$this->config->item('twitter_share_user_profile_description_character_limit')); ?>"><i class="fa fa-twitter"></i></a>
							<a class="ln_share_profile" data-link="<?php echo $ln_share_url; ?>"><i class="fa fa-linkedin"></i></a>
							<a class="share_via_email" data-link="<?php echo $email_share_url; ?>" data-name="<?php echo $name; ?>" data-profile="<?php echo $user_detail['profile_name']; ?>" data-headline="<?php echo (htmlspecialchars($user_detail['headline'], ENT_QUOTES));	 ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(nl2br($user_detail['description']), ENT_QUOTES)),$this->config->item('email_share_user_profile_description_character_limit')); ?>" ><i class="fas fa-envelope"></i></a>
						</div>
					</div>
				</div>
				<div class="user_profile_name_adjust <?php if((!empty($user_data) && $user_data['user_id'] != $user_detail['user_id']) || empty($user_data)) {echo 'logout_contactBtn';}?>">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12 pOnm">
							<?php 
							 if($this->session->userdata('user') && $user_detail['user_id'] == $user[0]->user_id && $user_detail['current_membership_plan_id'] == 4 && ($user_cover_picture_exist_status == true)){
								/*$user_info_section_class = 'cover_picture_below_user_name_multiple';
								$cover_picture_button_class = 'cover_picture_multiple_btn';*/
									$user_info_section_class = 'cover_picture_below_user_name_single';
									$cover_picture_button_class = 'cover_picture_single_btn';
								
							 } else if($this->session->userdata('user') && $user_detail['user_id'] == $user[0]->user_id && $user_detail['current_membership_plan_id'] == 4 && ($user_cover_picture_exist_status == false)){
								$user_info_section_class = 'cover_picture_below_user_name_single';
								$cover_picture_button_class = 'cover_picture_single_btn';
								
							 }elseif($this->session->userdata('user') && $user_detail['user_id'] == $user[0]->user_id && $user_detail['current_membership_plan_id'] != 4){
								$user_info_section_class = 'cover_picture_below_user_name_noBtn';
								$cover_picture_button_class = 'cover_picture_single_btn';
							 }else if(($this->session->userdata('user') && $user_detail['user_id'] != $user[0]->user_id) || !$this->session->userdata('user')){
								$user_info_section_class = 'cover_picture_below_user_name_single';
								$cover_picture_button_class = 'cover_picture_single_btn';
							 
							 }							
							?>
							<div class="textBtn_adjust">
								<!-- When Uload Cover / I'm Online Contact Button Start -->
								<span id="user_info_section" class="onLName default_continue_text default_user_name onAdd <?php echo $user_info_section_class; ?>">
									<span id="cover_picture_button_section" class="projBtn <?php echo $cover_picture_button_class; ?>">
										<?php if($own_profile==1 && $user_detail['current_membership_plan_id'] == 4) { ?>
										<div class="onlCont_btn">
											<input type="file" accept="<?php echo $this->config->item('pictures_allowed_extensions_input_file_type'); ?>" style="display:none;"  class="cover_picture_input user_profile_cover_picture_imgupload">
											<button type="button" style="<?php echo  $user_cover_picture_exist_status ? 'display:none' : ''; ?>" class="btn default_btn green_btn" id="upload_cover_picture"><?php echo $this->config->item('user_profile_page_upload_cover_picture_btn_txt'); ?></button>
											<?php
											/* <button id="edit_cover_picture" style="<?php echo  $user_cover_picture_exist_status ? '' : 'display:none' ?>" type="button" class="btn default_btn blue_btn"><?php echo $this->config->item('edit_btn_txt'); ?></button> */
											?>
											<button id="save_cover_picture" type="button" style="display:none;" class="btn default_btn blue_btn"><?php echo $this->config->item('save_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
											<button id="cancel_cover_picture" style="display:none;" type="button" class="btn default_btn red_btn" ><?php echo $this->config->item('cancel_btn_txt'); ?></button>
											<button type="button" class="btn default_btn red_btn" style="<?php echo  $user_cover_picture_exist_status ? '' : 'display:none' ?>" id="reset_cover_picture"><?php echo $this->config->item('reset_btn_txt'); ?></button>
										</div>
											<div class="clearfix"></div>
										<?php } else { ?>
											<?php
												if(!empty($user_data) && $user_data['user_id'] != $user_detail['user_id']) {
											?>
											<div class="onlCont">
												<button class="btn default_btn green_btn" data-profile-name="<?php echo $user_detail['profile_name']; ?>" data-gender="<?php echo $user_detail['gender']; ?>" data-name="<?php echo $user_detail['account_type'] == 1 || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y') ? $user_detail['first_name'].' '.$user_detail['last_name'] : $user_detail['company_name']; ?>" data-id="<?php echo $user_detail['user_id']; ?>" data-profile-pic="<?php echo $user_profile_picture;?>" data-is-in-contact="<?php echo !empty($is_in_contact) ? $is_in_contact : ''; ?>" id="contactMe"><?php echo $this->config->item('contactme_button'); ?></button>
											</div>
											<?php
												} else if(empty($user_data)){
											?>
											<div class="onlCont">
												<button class="btn default_btn green_btn login_popup" data-id="<?php echo $user_detail['user_id']; ?>" data-page-id-attr = "<?php echo $user_detail['profile_name'] ?>" data-page-type-attr = "<?php echo $current_page; ?>"><?php echo $this->config->item('contactme_button'); ?></button>
											</div>
											<?php		
												}
											?>
										<?php } ?>
									</span>
									<!-- Mobile Version Start -->
									<div class="onPerson userMobile <?php if((!empty($user_data) && $user_data['user_id'] != $user_detail['user_id']) || empty($user_data)) {echo 'logout_contactBtn';}?>">								
										<div class="avtOnly">
											<div id="profile-picture" class="default_avatar_image" style="background-image: url('<?php echo $user_profile_picture;?>');">
												<span class="actON"></span>
											</div>
										</div>
										<div class="sRate">
											<small>
												<?php echo show_dynamic_rating_stars($user_detail['user_total_avg_rating_as_sp']);?>
											</small>
											<span class="default_avatar_review"><?php echo $user_detail['user_total_avg_rating_as_sp']; ?></span>
										</div>
										<div class="default_avatar_total_review"><?php
											if($totalReviews == 0){
												$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
											}else if($totalReviews == 1) {
												$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
											} else if($totalReviews > 1) {
												$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
											}
											echo $trGiven;?></div>

											<div class="default_project_socialicon">
												<a class="fb_share_profile" data-link="<?php echo $fb_share_url; ?>"><i class="fa fa-facebook"></i></a>
												<a class="twitter_share_profile" data-link="<?php echo $twitter_share_url; ?>" data-title="<?php echo $name; ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(($user_detail['description']), ENT_QUOTES)),$this->config->item('twitter_share_user_profile_description_character_limit')); ?>"><i class="fa fa-twitter"></i></a>
												<a class="ln_share_profile" data-link="<?php echo $ln_share_url; ?>"><i class="fa fa-linkedin"></i></a>
												<a class="share_via_email" data-link="<?php echo $email_share_url; ?>" data-name="<?php echo $name; ?>" data-profile="<?php echo $user_detail['profile_name']; ?>" data-headline="<?php echo (htmlspecialchars($user_detail['headline'], ENT_QUOTES));	 ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(nl2br($user_detail['description']), ENT_QUOTES)),$this->config->item('email_share_user_profile_description_character_limit')); ?>" ><i class="fas fa-envelope"></i></a>
											</div>
									</div>
									<!-- Mobile Version End -->
									<a class="default_user_name_link"><?php
									$name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];
									echo $name;?></a>
								</span>
								<!-- When Uload Cover / I'm Online Contact Button End -->
										
							<!-- </div>
							<div class="col-md-5 col-sm-5 col-12 pCBtn upload_cover_picture_options" style="<?php //echo $user_cover_picture_show_css; ?>"> -->
								
								
							</div>
						</div>
						</div>
				</div>
				<input type="hidden" id="headline_condition_hidden" value="0@0">
				<div id="headline_condition" class="pCBtn <?php echo $user_detail['headline']!='' ? 'userWithHeadline' : 'userNoHeadline'; ?>">
					<?php if($user_detail['headline']!='') { ?>
					<div class="onLName onAdd">
					   <p class="headline_title <?php echo (htmlspecialchars($user_detail['headline'], ENT_QUOTES) == trim(htmlspecialchars($user_detail['headline'], ENT_QUOTES)) && strpos(htmlspecialchars($user_detail['headline'], ENT_QUOTES), ' ') !== false) ? '' : 'default_continue_text'; ?>"><?php echo htmlspecialchars($user_detail['headline'], ENT_QUOTES);?></p>
					</div>
					  <?php } ?>
					<div class="user_contacts">
						<div class="proBdr default_headline_gap">
							<!-- <div class="contactGap_adjust fontSize0">
								<?php
								if(!empty($user_detail['phone_number'])){
								$phone_number_array = explode("##",$user_detail['phone_number']);	
								?>
								<span class="userPart">
									<div class="nmAddress">
										<label><i class="fa fa-phone lSd" aria-hidden="true"></i><img src="<?php echo URL . 'assets/images/countries_flags/'.$phone_number_array[0].'.png'; ?>"><?php echo $phone_number_array[1]." ".$phone_number_array[2] ?></label>
									</div>
								</span>
								<?php
								}
								if(!empty($user_detail['mobile_phone_number'])){
								$mobile_phone_number_array = explode("##",$user_detail['mobile_phone_number']);
								?>
								<span class="userPart">
									<div class="nmAddress">
										<label><i class="fa fa-mobile lSd onlyMob" aria-hidden="true"></i><img src="<?php echo URL . 'assets/images/countries_flags/'.$mobile_phone_number_array[0].'.png'; ?>"><?php echo $mobile_phone_number_array[1]." ".$mobile_phone_number_array[2] ?></label>
									</div>
								</span>
								<?php
								}
								if(!empty($user_detail['additional_phone_number'])){
								$additional_phone_number_array = explode("##",$user_detail['additional_phone_number']);
								?>
								<span class="userPart">
									<div class="nmAddress">
										<label><i class="fa fa-fax lSd" aria-hidden="true"></i><img src="<?php echo URL . 'assets/images/countries_flags/'.$additional_phone_number_array[0].'.png'; ?>"><?php echo $additional_phone_number_array[1]." ".$additional_phone_number_array[2] ?></label>
									</div>
								</span>
								<?php
								}
								if(!empty($user_detail['contact_email'])){
								?>
								<span class="userPart">
									<div class="nmAddress">
										<label><i class="fa fa-envelope lSd" aria-hidden="true"></i><?php echo $user_detail['contact_email']; ?></label>
									</div>
								</span>
								<?php
								}
								if(!empty($user_detail['skype_id'])){
								?>
								<span class="userPart">
									<div class="nmAddress">
										<label><i class="fa fa-skype lSd" aria-hidden="true"></i><?php echo htmlspecialchars($user_detail['skype_id'], ENT_QUOTES); ?></label>
									</div>
								</span>
								<?php
								}if(!empty($user_detail['website_url'])){
								?>
								<span class="userPart">
									<div class="nmAddress">
										<label><i class="fa fa-dribbble lSd" aria-hidden="true"></i><a href="<?php echo $user_detail['website_url'] ?>" target= "_blank"  style="color:#232323"><?php echo $user_detail['website_url'] ?></a></label>
									</div>
								</span>
								<?php
								}
								?>
								<span class="userPart">
									<div class="nmAddress">
									
										<label><div class="onLName"><i class="fas fa-envelope active" ></i><i class="fab fa-facebook-square <?php echo (!empty($user_detail['user_facebook_associated_email']) && $user_detail['sync_facebook'] == 'y') ? 'verifiedFbAccountBg' : '' ?>"></i><i class="fa fab fa-linkedin <?php echo (!empty($user_detail['user_linkedin_associated_email']) && $user_detail['sync_linkedin'] == 'y') ? 'verifiedlnAccountBg' : '' ?>"></i></div></label>
									</div>
								</span>
							</div> -->
							
							<!-- Middle Section Start -->
							<div id="midPart" class="userSection">
								<div class="profileTab">
									 <ul class="nav nav-tabs portNav">
										<li class="nav-item">
											<a class="nav-link active user_profile_tab information" data-toggle="tab" data-target="#information" data-tab-type="information"><?php echo $this->config->item('user_profile_page_information_tab');?></a>
										</li>
										<li class="nav-item">
											<a class="nav-link user_profile_tab portfolio" data-toggle="tab" data-tab-type="portfolio" data-target="#portfolio"><?php echo $this->config->item('user_profile_page_portfolio_tab');?></a>
										</li>
										<li class="nav-item">
											<a class="nav-link user_profile_tab reviews" data-toggle="tab" data-tab-type="reviews" data-target="#reviews"><?php echo $this->config->item('user_profile_page_reviews_tab');?></a>
										</li>
										<li class="nav-item">
											<a class="nav-link user_profile_tab posted_projects" data-tab-type="posted_projects" data-toggle="tab" data-target="#posted_projects"><?php echo $this->config->item('user_profile_page_projects_created_tab');?></a>
										</li>
										<li class="nav-item">
											<a class="nav-link user_profile_tab won_projects" data-toggle="tab" data-tab-type="won_projects" data-target="#won_projects"><?php echo $this->config->item('user_profile_page_projects_won_tab');?></a>
										</li>
									</ul>
								</div>
								<div class="tab-content">
									<!-- Information Section Start -->
									<div class="tab-pane active" id="information">
										<?php
										echo $this->load->view('user/ca_information_tab_user_profile_page',array('user_detail'=>$user_detail,'sp_won_projects_count'=>$sp_won_projects_count,'sp_in_progress_projects_count'=>$sp_in_progress_projects_count,'sp_completed_projects_count'=>$sp_completed_projects_count,'sp_completed_projects_count_via_portal'=>$sp_completed_projects_count_via_portal,'po_total_posted_projects'=>$po_total_posted_projects,'po_cancelled_projects_count'=>$po_cancelled_projects_count,'po_in_progress_projects_count'=>$po_in_progress_projects_count,'po_completed_projects_count'=>$po_completed_projects_count,'po_completed_projects_count_via_portal'=>$po_completed_projects_count_via_portal,'address_detail_exists'=>$address_detail_exists,'address_details'=>$address_details,'areas_of_expertise'=>$areas_of_expertise), true);
										?>
									</div>
									<!-- Information Section End -->
								
									<!-- Portfolio Section Start -->
									<div class="tab-pane fade" id="portfolio">
										<div class="userRight_Section" id="portfolio_container">
										</div>
										<div class="clearfix"></div>
									</div>
									<!-- Portfolio Section End -->
									<!-- Reviews Section Start -->
									<div class="tab-pane fade" id="reviews"></div>
									<!-- Reviews Section End -->
									<!-- Projects Created Section End -->
									<div class="tab-pane fade creProject" id="posted_projects">
									<div id="posted_projects_container">
									</div>
									
									</div>
									<!-- Projects Created Section End -->
									<div class="tab-pane fade wonProject" id="won_projects">
									<div id="won_projects_container">
									</div>
									</div>
								</div>
							</div>  
						<!-- Middle Section End -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>
	<!-- When Logout Start -->
	<?php if(!$this->session->userdata ('user')){ ?>
	<div class="regHire" id="clientsWrapper">
		<span class="closeDiv"><i class="fa fa-times" aria-hidden="true"></i></span>
		<div class="popupLRadjust">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-6 freReg">
					<button type="button" class="btn default_btn green_btn signup"><?php echo $this->config->item('project_detail_page_register_freelancer_btn_txt'); ?></button>
					<p><?php echo $this->config->item('project_detail_page_start_working_right_now_txt'); ?></p>
				</div>
				<div class="col-md-6 col-sm-6 col-6 freHir">
					<button type="button" class="btn default_btn blue_btn signin"><?php echo $this->config->item('project_detail_page_hire_freelancer_btn_txt'); ?></button>
					<p><?php echo $this->config->item('project_detail_page_get_best_freelancer_just_minutes_txt'); ?></p>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	<!-- When Logout End -->
<script>


var portfolio_tab_url_txt = "<?php echo $this->config->item('user_profile_page_portfolio_tab_url_txt'); ?>";
var reviews_tab_url_txt = "<?php echo $this->config->item('user_profile_page_reviews_tab_url_txt'); ?>";
var projects_created_tab_url_txt = "<?php echo $this->config->item('user_profile_page_projects_created_tab_url_txt'); ?>";
var projects_won_tab_url_txt = "<?php echo $this->config->item('user_profile_page_projects_won_tab_url_txt'); ?>";
	
	
	
	
var signup_page_url = "<?php echo $this->config->item('signup_page_url'); ?>";
var signin_page_url = "<?php echo $this->config->item('signin_page_url'); ?>";
var profile_name = "<?php echo $profile_name; ?>";
var reply_characters_maximum_length_characters_limit = "<?php echo $this->config->item('users_ratings_feedbacks_reply_characters_maximum_length_characters_limit'); ?>";
var reply_characters_minimum_length_characters_limit = "<?php echo $this->config->item('users_ratings_feedbacks_reply_characters_minimum_length_characters_limit'); ?>";
var characters_remaining_message = "<?php echo $this->config->item('characters_remaining_message'); ?>";
var popup_alert_heading = "<?php echo $this->config->item('popup_alert_heading'); ?>";
var user_profile_cover_picture_allowed_file_extensions  = '<?php echo $this->config->item('pictures_allowed_extensions_js'); ?>';	
var project_attachment_not_exist_temporary_project_preview_validation_post_project_message = "<?php echo $this->config->item('project_attachment_not_exist_temporary_project_preview_validation_post_project_message'); ?>";
var dashboard_page_url = "<?php echo $this->config->item('dashboard_page_url'); ?>";
var user_profile_cover_picture_maximum_size = "<?php echo $this->config->item('user_profile_page_cover_picture_maximum_size_allocation'); ?>";
var user_profile_cover_picture_maximum_size_validation_message = "<?php echo $this->config->item('user_profile_cover_picture_maximum_size_validation_message'); ?>";
var user_profile_cover_picture_extension_validation_message = "<?php echo $this->config->item('user_profile_cover_picture_extension_validation_message'); ?>";
var user_profile_cover_picture_size_validation_message = "<?php echo $this->config->item('user_profile_cover_picture_size_validation_message'); ?>";


var user_profile_page_show_more_area_of_expertise_text = '<?php echo $this->config->item("user_profile_page_show_more_area_of_expertise_text");?>';
var user_profile_page_show_less_area_of_expertise_text = '<?php echo $this->config->item("user_profile_page_show_less_area_of_expertise_text");?>';


var user_profile_page_show_more_services_provided_text = '<?php echo $this->config->item("user_profile_page_show_more_services_provided_text");?>';
var user_profile_page_show_less_services_provided_text = '<?php echo $this->config->item("user_profile_page_show_less_services_provided_text");?>';

var user_profile_page_show_more_skills_text = '<?php echo $this->config->item("user_profile_page_show_more_skills_text");?>';
var user_profile_page_show_less_skills_text = '<?php echo $this->config->item("user_profile_page_show_less_skills_text");?>';


var user_profile_page_show_more_work_experience_text = '<?php echo $this->config->item("user_profile_page_show_more_work_experience_text");?>';
var user_profile_page_show_less_work_experience_text = '<?php echo $this->config->item("user_profile_page_show_less_work_experience_text");?>';

var user_profile_page_show_more_education_training_text = '<?php echo $this->config->item("user_profile_page_show_more_education_training_text");?>';
var user_profile_page_show_less_education_training_text = '<?php echo $this->config->item("user_profile_page_show_less_education_training_text");?>';


var user_profile_page_show_more_spoken_languages_text = '<?php echo $this->config->item("user_profile_page_show_more_spoken_languages_text");?>';
var user_profile_page_show_less_spoken_languages_text = '<?php echo $this->config->item("user_profile_page_show_less_spoken_languages_text");?>';

var user_profile_page_show_more_certifications_text = '<?php echo $this->config->item("user_profile_page_show_more_certifications_text");?>';
var user_profile_page_show_less_certifications_text = '<?php echo $this->config->item("user_profile_page_show_less_certifications_text");?>';



var user_profile_page_show_more_company_locations_text = '<?php echo $this->config->item("user_profile_page_show_more_company_locations_text");?>';
var user_profile_page_show_less_company_locations_text = '<?php echo $this->config->item("user_profile_page_show_less_company_locations_text");?>';


var find_professionals_contact_popup_title_text = '<?php echo $this->config->item("find_professionals_contact_popup_title_text");?>';
var profile_owner_id = '<?php echo Cryptor::doEncrypt($user_detail['user_id']); ?>';
var uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
var upload_cover_picture = '<?php echo $this->config->item('user_profile_page_upload_cover_picture_btn_txt'); ?>';
var upload_new_cover_picture = '<?php echo $this->config->item('user_profile_page_upload_new_cover_picture_btn_txt'); ?>';
</script>
<script src="<?php echo JS; ?>modules/user_profile_page.js"></script>
<script>
	$('.closeDiv').on('click', function(){
		$(this).closest("#clientsWrapper").remove();
	});
$(function() {
	<?php
		if(!empty($hire_me_user_id)) {
	?>
	$("#contactMe[data-id='<?php echo $hire_me_user_id;?>']").trigger('click');
	if($(".login_popup[data-id='<?php echo $hire_me_user_id;?>']").length == 1) {
		$(".login_popup[data-id='<?php echo $hire_me_user_id;?>']").trigger('click');
	}
	<?php
		}
	?>
	<?php 
		if(empty($hire_me_user_id) && $this->session->userdata('login_popup_op')) {
	?>
	$('.header_signin .login_popup').trigger('click');
	<?php
					$this->session->unset_userdata('login_popup_op');
			}
	?>
});
</script>