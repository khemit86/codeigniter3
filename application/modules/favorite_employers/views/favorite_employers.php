<div class="dashTop">
	<!-- Menu Icon on Responsive View Start -->	
	<?php echo $this->load->view('user_left_menu_mobile.php'); ?>
	<!-- Menu Icon on Responsive View End -->
	<div class="wrapper wrapper1">
	<!-- Left Menu Start -->
	<?php echo $this->load->view('user_left_nav.php'); ?>
	<!-- Left Menu End -->
	<!-- Right Section Start -->
		<div id="content" class="favorite_employers_page body_distance_adjust <?php echo $favorite_employers_count == 0 ? 'no_data_msg_display_center' : '' ?>">
			<div class="rightSec">
					<!-- Middle Section Start -->
				<div class="default_page_heading" style="display:<?php echo $favorite_employers_count > 0 ? 'block' : 'none' ?>">
					<?php
						if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
							$headline_title = $this->config->item('pa_favorite_employers_headline_title_my_favourite_employers');
						}else if($user_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_data['is_authorized_physical_person'] == 'Y') {
							$headline_title = $this->config->item('ca_app_favorite_employers_headline_title_my_favourite_employers');
						} else {
							$headline_title = $this->config->item('ca_favorite_employers_headline_title_my_favourite_employers');
						}
					?>
					<h4><?php echo $headline_title; ?></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<div class="fePage">
					<div class="filtered_data" id="favorite_employers">
						<div class="">
							<div class=" initialViewNorecord" style="display:<?php echo empty($favorite_employers) ? 'block' : 'none' ?>">
								<h4>
									<?php 
										if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
											echo $this->config->item('pa_favorite_employers_no_favourite_employer_available');
										} else if($user_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_data['is_authorized_physical_person'] == 'Y') {
											echo $this->config->item('ca_app_favorite_employers_no_favourite_employer_available');
										}else {
											echo $this->config->item('ca_favorite_employers_no_favourite_employer_available');
										}
									?></h4>
							</div>
						</div>
						<?php
							if(!empty($favorite_employers)) {
						?>							
						
						<?php
							foreach($favorite_employers as $key => $value) {
							if(($value['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['is_authorized_physical_person'] == 'Y')) {
								$name = htmlspecialchars($value['first_name'].' '.$value['last_name'], ENT_QUOTES);
							} else {
								$name = htmlspecialchars($value['company_name'], ENT_QUOTES);
							}
						?>
						<div class="freeBid">
							<div class="fe_leftAlign">
								<div class="imgTxtR">		
									<div class="avtOnly">
										<div id="profile-picture" class="default_avatar_image default_avatar_image_size" style="background-image: url('<?php echo $value['user_avatar'] ?>');">
										</div>
									</div>				
									<div class="rvw fervw">
										<?php 
											if(empty($value['is_in_contact'])) {
										?>
										<div class="fjApply default_applyNow_btn">
											<button type="button" id="contactMe" data-profile-name="<?php echo $value['profile_name'] ?>" data-gender="<?php echo $value['gender'] ?>" data-id="<?php echo $value['user_id']; ?>" data-name="<?php echo $name; ?>" class="btn default_btn blue_btn"><?php echo $this->config->item('contactme_button'); ?></button>
										</div>
										<?php
											} else {
										?>
										<div class="fjApply default_applyNow_btn">
											<button type="button" class="btn default_btn blue_btn contact-bidder"
											data-name="<?php echo $name; ?>"
											data-id="<?php echo $value['user_id']; ?>"
											data-project-title=""
											data-project-id=""
											data-profile="<?php echo $value['user_avatar']; ?>"
											data-project-owner=""
											><?php echo $this->config->item('contactme_button'); ?></button>
										</div>
										<?php 		
											}
										?>
									</div>											
								</div>
							</div>
							<div class="fe_RightAlign">
								<div class="opLBttm opBg">
									<div class="fpBdr">
										<div class="fe_userOnly">
											<div class="<?php echo $value['headline']=='' ? 'no_headline' : '' ?>">
												<p class="default_user_name default_continue_text">
													<a class="default_user_name_link" href="<?php echo base_url($value['profile_name'])?>" target="_blank"><?php echo $name; ?></a>
												</p>
											</div>
											<?php if($value['headline']!='') { ?>
											<div class="headline_title"><?php echo htmlspecialchars($value['headline'], ENT_QUOTES); ?></div>
											<?php } ?>
										</div>
										<div class="fe_userButton feBtn_desktop">
											<div class="unfavourite_btn">
												<button type="button" class="btn default_btn red_btn unfavorite" data-user-id="<?php echo $value['user_id'];?>"><?php echo $this->config->item('favourite_employers_un_favourite_btn_txt'); ?></button>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="default_user_description desktop-secreen fpBtop default_headline_gap">
										<?php
											$desktop_cnt            =	0;
											$desktop_descLeng	=	strlen($value['description']);
											if($desktop_descLeng <= $this->config->item('favorite_employers_description_display_minimum_length_character_limit_desktop')) {
												$desktop_description	= htmlspecialchars($value['description'], ENT_QUOTES);
											} else {
												$desktop_description	= character_limiter($value['description'], $this->config->item('favorite_employers_description_display_minimum_length_character_limit_desktop'));
												$desktop_restdescription = nl2br(htmlspecialchars($value['description'], ENT_QUOTES));
												$desktop_cnt = 1;
											} 
										?>
										<p id="desktop_less<?php echo $key;?>">
											<?php echo $desktop_description; ?><?php if($desktop_cnt==1) {?><span id="desktop_dots<?php echo $key;?>"></span><button onclick="showMoreDescription('desktop', <?php echo $key?>)"><?php echo $this->config->item('show_more_txt'); ?></button><?php } ?></p>
										<p id="desktop_more<?php echo $key;?>" class="moreD">
											<?php echo $desktop_restdescription;?>
											<button onclick="showMoreDescription('desktop', <?php echo $key?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
										</p>
									</div>
									<div class="default_user_description ipad-screen fpBtop default_headline_gap">
										<?php
											$tablet_cnt = 0;
												$tablet_descLeng = strlen($value['description']);
												if($tablet_descLeng <= $this->config->item('favorite_employers_description_display_minimum_length_character_limit_tablet')) {
													$tablet_description	= htmlspecialchars($value['description'], ENT_QUOTES);
												} else {
													$tablet_description	= character_limiter($value['description'],$this->config->item('favorite_employers_description_display_minimum_length_character_limit_tablet'));
													$tablet_restdescription = nl2br(htmlspecialchars($value['description'], ENT_QUOTES));
													$tablet_cnt = 1;
												}
										?>
										<p id="tablet_less<?php echo $key;?>"><?php echo $tablet_description; ?><?php if($tablet_cnt==1) {?><span id="tablet_dots<?php echo $key;?>"></span><button onclick="showMoreDescription('tablet',<?php echo $key?>)"><?php echo $this->config->item('show_more_txt'); ?></button><?php } ?></p>
										<p id="tablet_more<?php echo $key;?>" class="moreD">
											<?php echo $tablet_restdescription;?>
											<button onclick="showMoreDescription('tablet', <?php echo $key?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
										</p>
									</div>
									<div class="default_user_description mobile-screen fpBtop default_headline_gap">
										<?php
											$mobile_cnt = 0;
											$mobile_descLeng = strlen($value['description']);
											if($mobile_descLeng <= $this->config->item('favorite_employers_description_display_minimum_length_character_limit_mobile')) {
												$mobile_description = htmlspecialchars($value['description'], ENT_QUOTES);
											} else {
												$mobile_description = character_limiter($value['description'],$this->config->item('user_profile_description_display_minimum_length_character_limit_mobile'));
												$mobile_restdescription = nl2br(htmlspecialchars($value['description'], ENT_QUOTES));
												$mobile_cnt = 1;
											}
											?>
										<p id="mobile_less<?php echo $key;?>"><?php echo $mobile_description; ?><?php if($mobile_cnt==1) {?><span id="mobile_dots<?php echo $key;?>"></span><button onclick="showMoreDescription('mobile', <?php echo $key?>)"><?php echo $this->config->item('show_more_txt'); ?></button><?php } ?></p>
										<p id="mobile_more<?php echo $key;?>" class="moreD">
										<?php echo $mobile_restdescription ;?>
										<button onclick="showMoreDescription('mobile', <?php echo $key?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
										</p>
									</div>
									<?php
										$location = '';
										if(!empty($value['street_address'])){
											if(!preg_match('/\s/',$value['street_address'])) {
												$location .= '<small class="street_address_nospace">'.htmlspecialchars($value['street_address'], ENT_QUOTES).',</small>';
											} else {
												$location .= '<small class="">'.htmlspecialchars($value['street_address'], ENT_QUOTES).',</small>';
											}
										}
										if(!empty($value['locality']) && !empty($value['postal_code'])){
											$location .= '<small class="">'.$value['locality'].' '.$value['postal_code'].',</small>';
										}
										if(empty($value['locality']) && !empty($value['postal_code'])){
											$location .= '<small class=""> '.$value['postal_code'].',</small>';
										}
										if(!empty($value['locality']) && empty($value['postal_code'])){
											$location .= '<small class="">'.$value['locality'].',</small>';
										}
										if(!empty($value['county'])){
											$location .= '<small class="">'.$value['county'].',</small>';
										}
										if(!empty($value['country_name'])) {
											$country_flag = ASSETS .'images/countries_flags/'.strtolower($value['country_code']).'.png';
											$location .= '<small >'.$value['country_name'].'<div class="default_user_location_flag" style="background-image: url('.$country_flag.');"></div></small>';	
										}
										

										
									?>
									
									<div class="default_user_location">
										
										<?php
												if(!empty($location)) :
										?>
										<span><i class="<?php echo $value['account_type'] == 1 ? 'fas fa-map-marker-alt' : 'far fa-building' ?>"></i></span><?php echo $location; ?>
										
										<?php
											endif;
										?>
									</div>
									<?php 
									if($value['project_user_total_reviews'] == 0){
										$reviews_as_po = number_format($value['project_user_total_reviews'],0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
									}else if($value['project_user_total_reviews'] == 1) {
										$reviews_as_po = number_format($value['project_user_total_reviews'],0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
									} else if($value['project_user_total_reviews'] > 1) {
										$reviews_as_po = number_format($value['project_user_total_reviews'],0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
									}
									
									if($value['fulltime_project_user_total_reviews'] == 0){
										$reviews_as_employer = number_format($value['fulltime_project_user_total_reviews'],0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
									}else if($value['fulltime_project_user_total_reviews'] == 1) {
										$reviews_as_employer = number_format($value['fulltime_project_user_total_reviews'],0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
									} else if($value['fulltime_project_user_total_reviews'] > 1) {
										$reviews_as_employer = number_format($value['fulltime_project_user_total_reviews'],0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
									}
										
										
									?>
									<div class="default_favourite_short_details_field">
										<div class="feSection"><span class="pPosted"><i class="fas fa-clipboard"></i><?php echo $this->config->item('favourite_employers_page_total_published_listings'); ?><small><?php echo $value['po_total_posted_projects']; ?></small></span></div><div class="feSection"><span class="pPosted"><i class="fas fa-clipboard"></i><?php echo $this->config->item('favourite_employers_page_total_published_projects'); ?><small><?php echo $value['po_published_projects']."".str_replace(array("{total_projects_reviews}","{project_user_total_avg_rating_as_po}"),array($reviews_as_po,$value['project_user_total_avg_rating_as_po']),$this->config->item('favourite_employers_page_total_avg_rating_and_reviews_as_po_txt')); ?></small></span><span class="pCompleted"><i class="fas fa-clipboard-check"></i><?php echo $this->config->item('favourite_employers_page_projects_completed_via_portal'); ?><small><?php echo $value['po_completed_projects_count_via_portal']; ?></small></span></div><span class="pPosted"><i class="fas fa-clipboard"></i><?php echo $this->config->item('favourite_employers_page_total_published_fulltime_projects'); ?><small><?php echo $value['po_published_fulltime_projects_count']."".str_replace(array("{total_fulltime_projects_reviews}","{fulltime_project_user_total_avg_rating_as_employer}"),array($reviews_as_employer,$value['fulltime_project_user_total_avg_rating_as_employer']),$this->config->item('favourite_employers_page_total_avg_rating_and_reviews_as_employer_txt')); ?></small></span><span class="pCompleted"><i class="fas fa-clipboard-check"></i><?php echo $this->config->item('favourite_employers_page_hires_on_fulltime_projects_via_portal'); ?><small><?php echo $value['get_po_hires_sp_on_fulltime_projects_count']; ?></small></span>
									</div>
									<!-- Mobile Version Only Start -->
									<div class="fe_userButton feBtn_mobile">
										<div class="unfavourite_btn">
											<button type="button" class="btn default_btn red_btn unfavorite" data-user-id="<?php echo $value['user_id'];?>"><?php echo $this->config->item('favourite_employers_un_favourite_btn_txt'); ?></button>
										</div>
									</div>
									<!-- Mobile Version Only End -->
									<div class="acCo">
										
									</div>
								</div>
								<div class="clearfix"></div>		
							</div>
							<div class="clearfix"></div>
						</div>             
							<?php
								}
							?>
						
						<?php
							}
						?>
					</div>
					<!-- Notification Collapse Start -->
					<div class="notifyCollapse" style="display:<?php echo $favorite_employers_count > 0 ? 'block' : 'none' ?>">
						<!--<div class="receive_notification">
							<a class="rcv_notfy_btn" onclick="showMoreSearch()">
								<?php echo $this->config->item('favorite_employers_show_more_notifications_consent_text'); ?>
							</a>
							<input type="hidden" id="moreSearch" value="1">
						</div>-->
						<div id="rcv_notfy" class="favNotify">
							<label class="default_checkbox default_small_checkbox">
								<input type="checkbox" class="receive_notification_favorite_employer"  data-id="favorite_employer" <?php echo !empty($favorite_employer_notification) ? 'checked': '';?>>
								<span class="checkmark"></span>
							<span class="default_terms_text <?php echo !empty($favorite_employer_notification) ? 'boldCat': '';?>"><?php 
							if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
								echo $this->config->item('pa_favorite_employers_newly_posted_projects_user_notifications_consent_txt');
							}else if($user_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_data['is_authorized_physical_person'] == 'Y') {
								echo $this->config->item('ca_app_favorite_employers_newly_posted_projects_user_notifications_consent_txt');
							} else {
								echo $this->config->item('ca_favorite_employers_newly_posted_projects_user_notifications_consent_txt');
							} ?></span></label>
						</div>
					</div>
					<!-- Notification Collapse End -->
						<?php
							if($favorite_employers_count > 0) {
						?>
					<div class="pagnOnly" style="display:<?php echo $favorite_employers_count > 0 ? 'block' : 'none' ?>">
						<div class="row">
							<div class="no_page_links <?php echo !empty($pagination_links) ? 'col-md-7 col-sm-7 col-12' : 'col-md-12 col-12'; ?>">
								<?php
									$rec_per_page = ($favorite_employers_count > $this->config->item('favorite_employers_listing_limit_per_page')) ? $this->config->item('favorite_employers_listing_limit_per_page') : $favorite_employers_count;
								?>
								<div class="pageOf">
									<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $favorite_employers_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
								</div>
							</div>
							<div class="page_links col-md-5 col-sm-5 col-12" style="display:<?php echo !empty($pagination_links) ? 'block' : 'none'; ?>">
								<div class="modePage">
									<?php
										echo $pagination_links;
									?>
								</div>
							</div>
						</div>
					</div>
					<?php
						}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Script Start -->
		<script>
				var favorite_employers_show_more_notifications_consent_text = "<?php echo $this->config->item('favorite_employers_show_more_notifications_consent_text'); ?>";
				var favorite_employers_hide_extra_notifications_consent_text = "<?php echo $this->config->item('favorite_employers_hide_extra_notifications_consent_text'); ?>";
		</script>
    <script src="<?php echo JS.'modules/favorite_employers.js'?>" type="text/javascript"></script>
	<!-- Bottom Div Closed Script Start -->
	<script>
		$('.closeDiv').on('click', function(){
			$(this).closest("#clientsWrapper").remove();
		});
	</script>
	<!-- Bottom Div Closed Script End -->
<!-- Script End -->