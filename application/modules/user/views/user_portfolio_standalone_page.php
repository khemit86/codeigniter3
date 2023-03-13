<div class="headTopCookies"></div>
<?php 
	$user = $this->session->userdata('user'); 
	$hire_me_user_id = $this->session->userdata('hire_me_user_id');
	$this->session->unset_userdata('hire_me_user_id');
	 $totalReviews = $portfolio_data['fulltime_project_user_total_reviews']+$portfolio_data['project_user_total_reviews']; 
?>
<div class="dashTop">
	<?php 
	
		$cover_picture = '';
		$cover_picture_class = '';
		if(!empty($portfolio_data['standalone_portfolio_page_cover_picture_name'])){
			
			$cover_picture= CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_PORTFOLIO.$this->input->get(id).USER_STANDALONE_PORTFOLIO_PAGE_COVER_PICTURE.$portfolio_data['standalone_portfolio_page_cover_picture_name'];
			$cover_picture_class = 'bgposition';
		}
	?>	
	<!-- Upload Image Cover Section Start -->
	<div class="uploadImg" style="<?php echo  $user_cover_picture_exist_status ? '' : 'display:none'; ?>">
		
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12 no-padding">
				<div id="topContainer" class="<?php echo $cover_picture_class; ?>" style="background-image:url(<?php echo $cover_picture; ?>);"></div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<!-- Upload Image Cover Section End -->	
	<div class="profFirst">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12">
				<div class="user_profile_avatar_adjust">
					<!-- Desktop Design Start -->
					<div class="onPerson userDesktop <?php if(!empty($user_detail) && $user_detail['user_id'] != $user[0]->user_id) {echo 'logout_contactBtn';}?>">								
						<div class="avtOnly">
							<?php
								if($user_avatar_exist_status) {
									
									$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
								} else {
									
									if(($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] =='Y')){
										if($user_detail['gender'] == 'M'){
											$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
										}if($user_detail['gender'] == 'F'){
										   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
										}
									} else {
										$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
									}
								}
							?>
							<div id="profile-picture" class="default_avatar_image" style="background-image: url('<?php echo $user_profile_picture; ?>');">
								<span class="actON"></span>
							</div>
						</div>
						<div class="sRate avatar_review_star_resize">
							<small>
								<?php echo show_dynamic_rating_stars($portfolio_data['user_total_avg_rating_as_sp']);?>
							</small>
							<span class="default_avatar_review"><?php echo $portfolio_data['user_total_avg_rating_as_sp']; ?></span>
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
							<a><i class="fa fa-facebook fb_share_portfolio" data-link="<?php echo $fb_share_url;?>" aria-hidden="true"></i></a>
							<a><i class="fa fa-twitter twitter_share_portfolio" data-link="<?php echo $twitter_share_url;?>" data-title="<?php echo htmlspecialchars($portfolio_data['title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit(htmlspecialchars($portfolio_data['description'], ENT_QUOTES), $this->config->item('twitter_share_project_description_character_limit'));?>" aria-hidden="true"></i></a>
							<a><i class="fa fa-linkedin ln_share_portfolio" data-link="<?php echo $ln_share_url;?>" data-title="<?php echo htmlspecialchars($portfolio_data['title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit(htmlspecialchars($portfolio_data['description'], ENT_QUOTES), $this->config->item('facebook_and_linkedin_share_project_description_character_limit'));?>" aria-hidden="true"></i></a>
							<a><i class="fas fa-envelope email_share_portfolio" data-link="<?php echo $email_share_url;?>" data-title="<?php echo htmlspecialchars($portfolio_data['title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit(htmlspecialchars(nl2br($portfolio_data['description']), ENT_QUOTES), $this->config->item('email_share_user_portfolio_standalone_page_description_character_limit'));?>" aria-hidden="true"></i></a>
						</div>
					</div>
					<!-- Desktop Design End -->
				</div>
				<!-- <div class="col-md-10 col-sm-10 col-12 user_profile_name_adjust"> -->
				<div class="user_profile_name_adjust <?php if(!empty($user_detail) && $user_detail['user_id'] != $user[0]->user_id) {echo 'logout_contactBtn';}?>">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12 pOnm">
							<?php
							if($this->session->userdata('user') && $user_detail['user_id'] == $user[0]->user_id){
								$user_info_section_class = 'cover_picture_below_user_name_single';
								/*if($user_cover_picture_exist_status){
									$user_info_section_class = 'cover_picture_below_user_name_multiple';
								}else{
									$user_info_section_class = 'cover_picture_below_user_name_single';
								}*/
							}else{
								//$user_info_section_class = 'cover_picture_below_user_name_noBtn';
								$user_info_section_class = 'cover_picture_below_user_name_single';
							}
							?>
							<div class="textBtn_adjust">
								<span id="user_info_section" class="onLName default_user_name onAdd <?php echo $user_info_section_class; ?>">
									<!--<a class="default_user_name_link">Dibyendu Mandal</a>-->
									<?php
									if($this->session->userdata('user') && $user_detail['user_id'] == $user[0]->user_id){
										$cover_picture_button_class = 'cover_picture_single_btn';
										/*if($user_cover_picture_exist_status){
											$cover_picture_button_class = 'cover_picture_multiple_btn';
										}else{
											$cover_picture_button_class = 'cover_picture_single_btn';
										}*/
									?>
									<span class="projBtn <?php echo $cover_picture_button_class; ?>" id="cover_picture_button_section">
										<!-- <div class="onlCont">
											<button class="btn default_btn green_btn">Upload Cover Picture</button>
										</div>
										<div class="clearfix"></div> -->
										<div class="onlCont_btn">
											<!--<label for="upload" class="btn default_btn green_btn">Upload Cover Picture</label>
											<input id="upload" type="file" name="file-upload">-->
											<input type="file" accept="<?php echo $this->config->item('pictures_allowed_extensions_input_file_type'); ?>" style="display:none;"  class="cover_picture_input user_standalone_portfolio_cover_picture_imgupload">
											<button id="cancel_cover_picture" style="display:none;" type="button" class="btn default_btn red_btn" ><?php echo $this->config->item('cancel_btn_txt'); ?></button>
											<button type="button" class="btn default_btn red_btn" style="<?php echo  $user_cover_picture_exist_status ? '' : 'display:none' ?>" id="reset_cover_picture"><?php echo $this->config->item('reset_btn_txt'); ?></button>
											<button type="button" style="<?php echo  $user_cover_picture_exist_status ? 'display:none' : ''; ?>" class="btn default_btn green_btn" id="upload_cover_picture"><?php echo $this->config->item('user_portfolio_standalone_page_upload_cover_picture_btn_txt'); ?></button>
											<button id="save_cover_picture" type="button" style="display:none;" class="btn default_btn blue_btn"><?php echo $this->config->item('save_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
										</div>						
									</span>
									<?php
									}else{
									?>
									<span class="projBtn cover_picture_single_btn">
										<?php
										if($this->session->userdata('user')){
									
										?>
										<div class="onlCont">
											<button class="btn default_btn green_btn contactMe" data-profile-name="<?php echo $user_detail['profile_name']; ?>" data-gender="<?php echo $user_detail['gender']; ?>" data-name="<?php echo $user_detail['account_type'] == 1 || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] =='Y') ? $user_detail['first_name'].' '.$user_detail['last_name'] : $user_detail['company_name']; ?>" data-id="<?php echo $user_detail['user_id']; ?>" data-profile-pic="<?php echo $user_profile_picture;?>" data-is-in-contact="<?php echo !empty($is_in_contact) ? $is_in_contact : ''; ?>" id="contactMe"><?php echo $this->config->item('contactme_button'); ?></button>
										</div>
										<?php
										}else{
										?>
										<div class="onlCont">
											<button class="btn default_btn green_btn login_popup contactMe"  data-id="<?php echo $user_detail['user_id']; ?>" data-page-id-attr = "<?php echo $portfolio_data['portfolio_id'] ?>" data-page-type-attr = "<?php echo $current_page; ?>"><?php echo $this->config->item('contactme_button'); ?></button>
										</div>
										<?php
										}
										?>
									</span>
									<?php
									}
									?>
									<!-- Mobile Design Start -->
									<div class="onPerson userMobile <?php if(!empty($user_detail) && $user_detail['user_id'] != $user[0]->user_id) {echo 'logout_contactBtn';}?>">								
										<div class="avtOnly">
											<?php
												if($user_avatar_exist_status) {
													
													$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
												} else {
													
													if(($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] =='Y')){
														if($user_detail['gender'] == 'M'){
															$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
														}if($user_detail['gender'] == 'F'){
														   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
														}
													} else {
														$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
													}
												}
											?>
											<div id="profile-picture" class="default_avatar_image" style="background-image: url('<?php echo $user_profile_picture; ?>');">
												<span class="actON"></span>
											</div>
										</div>
										<div class="sRate">
											<small>
												<?php echo show_dynamic_rating_stars($portfolio_data['user_total_avg_rating_as_sp']);?>
											</small>
											<span class="default_avatar_review"><?php echo $portfolio_data['user_total_avg_rating_as_sp']; ?></span>
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
											<a><i class="fa fa-facebook fb_share_portfolio" data-link="<?php echo $fb_share_url;?>" aria-hidden="true"></i></a>
											<a><i class="fa fa-twitter twitter_share_portfolio" data-link="<?php echo $twitter_share_url;?>" data-title="<?php echo htmlspecialchars($portfolio_data['title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit(htmlspecialchars($portfolio_data['description'], ENT_QUOTES), $this->config->item('twitter_share_project_description_character_limit'));?>" aria-hidden="true"></i></a>
											<a><i class="fa fa-linkedin ln_share_portfolio" data-link="<?php echo $ln_share_url;?>" data-title="<?php echo htmlspecialchars($portfolio_data['title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit(htmlspecialchars($portfolio_data['description'], ENT_QUOTES), $this->config->item('facebook_and_linkedin_share_project_description_character_limit'));?>" aria-hidden="true"></i></a>
											<a><i class="fas fa-envelope email_share_portfolio" data-link="<?php echo $email_share_url;?>" data-title="<?php echo htmlspecialchars($portfolio_data['title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit(htmlspecialchars(nl2br($portfolio_data['description']), ENT_QUOTES), $this->config->item('email_share_user_portfolio_standalone_page_description_character_limit'));?>" aria-hidden="true"></i></a>
										</div>
									</div>
									<!-- Mobile Design End -->
									<a class="default_user_name_link" href="<?php echo site_url ($user_detail['profile_name']); ?>">
									<?php $name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] =='Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name']; echo $name;?></a>
								</span>
								<div class="clearfix"></div>
							</div> 
						</div>
						<!-- <div class="col-md-12 col-sm-12 col-12 pCBtn"> -->
					</div>
				</div>
				<input type="hidden" id="headline_condition_hidden" value="0@0">
				<div id="headline_condition" class="pCBtn userWithHeadline">
					<div class="onLName onAdd">
						<p class="headline_title"><?php echo $portfolio_data['title']; ?></p>
					</div>
					<div class="proStand default_headline_gap">
						<div class="userRight_Section">
							<div class="default_user_description desktop-secreen">
							<?php
								$desktop_cnt            =	0;
								$desktop_descLeng	=	strlen($portfolio_data['description']);  
								if($desktop_descLeng <= $this->config->item('user_standalone_portfolio_page_description_display_minimum_length_character_limit_desktop')) {
								 $desktop_description	= nl2br(htmlspecialchars($portfolio_data['description'], ENT_QUOTES));
								} else {
									$desktop_description	= character_limiter(nl2br($portfolio_data['description']),$this->config->item('user_standalone_portfolio_page_description_display_minimum_length_character_limit_desktop'));
									$desktop_restdescription = nl2br(htmlspecialchars($portfolio_data['description'], ENT_QUOTES));
									$desktop_cnt = 1;
									}
								?>
								<p id="desktop_lessD">
									<?php echo $desktop_description; ?><?php if($desktop_cnt==1) {?><span id="desktop_dotsD"></span><button onclick="showMoreDescription('desktop')"><?php echo $this->config->item('show_more_txt'); ?></button>
									<?php } ?>
								</p>
								<p id="desktop_moreD" class="moreD">
									<?php echo $desktop_restdescription;?><button onclick="showMoreDescription('desktop')"><?php echo $this->config->item('show_less_txt'); ?></button>
								</p>
							</div>
							<div class="default_user_description ipad-screen">
								<?php
								$tablet_cnt            =	0;
								$tablet_descLeng	=	strlen($portfolio_data['description']);
								if($tablet_descLeng <= $this->config->item('user_standalone_portfolio_page_description_display_minimum_length_character_limit_tablet')) {
								$tablet_description	= nl2br(htmlspecialchars($portfolio_data['description'], ENT_QUOTES));
								} else {
									//$tablet_description	= substr(nl2br(htmlspecialchars($portfolio_data['description'], ENT_QUOTES)),0,$this->config->item('user_standalone_portfolio_page_description_display_minimum_length_character_limit_tablet'));
									  $tablet_description	= character_limiter(nl2br($portfolio_data['description']),$this->config->item('user_standalone_portfolio_page_description_display_minimum_length_character_limit_tablet'));
									$tablet_restdescription = nl2br(htmlspecialchars($portfolio_data['description'], ENT_QUOTES));
									$tablet_cnt = 1;
								}
								?>
								<p id="tablet_lessD">
								<?php echo $tablet_description; ?><?php if($tablet_cnt==1) {?><span id="tablet_dotsD"></span><button onclick="showMoreDescription('tablet')"><?php echo $this->config->item('show_more_txt'); ?></button>
								<?php } ?>
								</p>
								<p id="tablet_moreD" class="moreD">
								<?php echo $tablet_restdescription;?><button onclick="showMoreDescription('tablet')"><?php echo $this->config->item('show_less_txt'); ?></button>
								</p>
							</div>
							<div class="default_user_description mobile-screen">
							<?php
								$mobile_cnt            =	0;
								$mobile_descLeng	=	strlen($portfolio_data['description']);
								if($mobile_descLeng <= $this->config->item('user_standalone_portfolio_page_description_display_minimum_length_character_limit_mobile')) {
									$mobile_description	= nl2br(htmlspecialchars($portfolio_data['description'], ENT_QUOTES));
								} else {
									//$mobile_description	= substr(nl2br(htmlspecialchars($portfolio_data['description'], ENT_QUOTES)),0,$this->config->item('user_standalone_portfolio_page_description_display_minimum_length_character_limit_mobile'));
									$mobile_description	= character_limiter(nl2br($portfolio_data['description']),$this->config->item('user_standalone_portfolio_page_description_display_minimum_length_character_limit_mobile'));
									$mobile_restdescription = nl2br(htmlspecialchars($portfolio_data['description'], ENT_QUOTES));
									$mobile_cnt = 1;
								}
								?>
								<p id="mobile_lessD">
									<?php echo $mobile_description; ?><?php if($mobile_cnt==1) {?><span id="mobile_dotsD"></span><button onclick="showMoreDescription('mobile')"><?php echo $this->config->item('show_more_txt'); ?></button>
									<?php } ?>
								</p>
								<p id="mobile_moreD" class="moreD">
									<?php echo $mobile_restdescription ;?><button onclick="showMoreDescription('mobile')"><?php echo $this->config->item('show_less_txt'); ?></button>
								</p>
							</div>
						</div>
						<div class="urlImageTag">
							<?php
							if(!empty($portfolio_data['reference_url'])){	
							?>
							<div class="reference"><a href="<?php echo $portfolio_data['reference_url']; ?>"><?php echo $portfolio_data['reference_url']; ?></a></div> 
							<?php
							}
							?>
							<div class="clearfix"></div>
							<?php
							$get_portfolio_images = get_portfolio_images(array('portfolio_id'=>$portfolio_data['portfolio_id']));

							if(!empty($get_portfolio_images)){
							?>
							<div class="prSt" id="animated-thumbnials">
							<?php
							foreach($get_portfolio_images as $portfolio_image_key=>$portfolio_image_value){
							
							$original_image= CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_PORTFOLIO.$portfolio_data['portfolio_id'].'/'.$portfolio_image_value['portfolio_image_name'];
							$thumb_name = explode('.',$portfolio_image_value['portfolio_image_name']);
							$thumb_image= CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_PORTFOLIO.$portfolio_data['portfolio_id'].'/'.$thumb_name[0].'_thumb.jpg';
							echo '<a class="default_uploaded_image_border" href="'.$original_image.'">
									<img class="img-responsive" src="'.$thumb_image.'">
								</a>';
							}
							?>
								
							</div>
							<div class="clearfix"></div>
							<?php
							}
							$portfolio_tags = get_portfolio_tags(array('portfolio_id'=>$portfolio_data['portfolio_id']));

							if(!empty($portfolio_tags)){
							?>	
							<div class="portTags">
							<?php
								foreach($portfolio_tags as $portfolio_tag_key=>$portfolio_tag_value){
									echo '<label class="defaultTag"><span class="tagFirst">'.$portfolio_tag_value['portfolio_tag_name'].'</span></label>';
								}
							?>
							</div>
							<?php
							}
							?>
						</div>
						<div class="clearfix"></div>
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

var signup_page_url = "<?php echo $this->config->item('signup_page_url'); ?>";
var signin_page_url = "<?php echo $this->config->item('signin_page_url'); ?>";

var cover_picture_upload_max_size_allocation = "<?php echo $this->config->item('user_portfolio_standalone_page_cover_picture_upload_max_size_allocation'); ?>";

var cover_picture_allowed_file_extensions = '<?php echo $this->config->item('pictures_allowed_extensions_js'); ?>';

var cover_picture_upload_max_size_validation_message = '<?php echo $this->config->item('user_portfolio_standalone_page_cover_picture_upload_max_size_validation_message'); ?>';

var cover_picture_allowed_file_extension_validation_message = '<?php echo $this->config->item('user_portfolio_standalone_page_cover_picture_allowed_file_extension_validation_message'); ?>';
var popup_alert_heading = '<?php echo $this->config->item('popup_alert_heading'); ?>';
var cover_picture_size_validation_message = '<?php echo $this->config->item('user_portfolio_standalone_page_cover_picture_size_validation_message'); ?>';




var portfolio_id = "<?php echo $portfolio_data['portfolio_id']; ?>";	
var portfolio_owner_id = '<?php echo Cryptor::doEncrypt($user_detail['user_id']); ?>';
var uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
var upload_cover_picture = '<?php echo $this->config->item('user_portfolio_standalone_page_upload_cover_picture_btn_txt'); ?>';
var upload_new_cover_picture = '<?php echo $this->config->item('user_portfolio_standalone_page_upload_new_cover_picture_btn_txt'); ?>';
</script>	
<script src="<?= ASSETS ?>js/modules/porfolio_standalone_page.js"></script>       
<script type="text/javascript">
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