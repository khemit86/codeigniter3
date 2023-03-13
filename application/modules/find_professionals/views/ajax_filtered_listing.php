<?php
$user = $this->session->userdata('user');
    if(!empty($professionals)){
      
        foreach($professionals  as $key=>$val)
        {
            /*--------------- for rating and reviews------------- */
              $totalReviews = $val['total_reviews'];
              
            /*--------------- for rating and reviews end------------- */
            
                            $descLeng	=	strlen($val['description']);
            /*----------- description show for desktop screen start----*/
            $desktop_cnt            =	0;
        
            if($descLeng <= $this->config->item('find_professionals_user_description_character_limit_dekstop')) {
                $desktop_description	= htmlspecialchars($val['description'], ENT_QUOTES);
            } else {          
                //$desktop_description	= substr((htmlspecialchars($val['description'], ENT_QUOTES)),0,$this->config->item('find_professionals_user_description_character_limit_dekstop'));
                $desktop_description	= character_limiter($val['description'],$this->config->item('find_professionals_user_description_character_limit_dekstop'));
                $desktop_restdescription	= nl2br(htmlspecialchars($val['description'], ENT_QUOTES));
                $desktop_cnt = 1;
            }
            /*----------- description show for desktop screen end----*/
            
            /*----------- description show for ipad screen start----*/
            $tablet_cnt            =	0;
            if($descLeng <= $this->config->item('find_professionals_user_description_character_limit_tablet')) {
                $tablet_description	= htmlspecialchars($val['description'], ENT_QUOTES);
            } else {          
                //$tablet_description	= substr((htmlspecialchars($val['description'], ENT_QUOTES)),0,$this->config->item('find_professionals_user_description_character_limit_tablet'));
                $tablet_description	= character_limiter($val['description'],$this->config->item('find_professionals_user_description_character_limit_tablet'));
                $tablet_restdescription	= nl2br(htmlspecialchars($val['description'], ENT_QUOTES));
                $tablet_cnt = 1;
            }
            /*----------- description show for ipad screen end----*/
            
            /*----------- description show for mobile screen start----*/
            $mobile_cnt            =	0;
            if($descLeng <= $this->config->item('find_professionals_user_description_character_limit_mobile')) {
                $mobile_description	= htmlspecialchars($val['description'], ENT_QUOTES);
            } else {
                //$mobile_description	= substr((htmlspecialchars($val['description'], ENT_QUOTES)),0,$this->config->item('find_professionals_user_description_character_limit_mobile'));
                $mobile_description	= character_limiter($val['description'],$this->config->item('find_professionals_user_description_character_limit_mobile'));
                $mobile_restdescription	= nl2br(htmlspecialchars($val['description'], ENT_QUOTES));
                $mobile_cnt = 1;
            }
            /*----------- description show for mobile screen end----*/
        ?>
        
        <div class="pDtls fTalent <?php echo (!empty($val['featured_user_profile'])) ? 'featured_profile' : ''; ?>">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-12 default_user_avatar_left_adjust">
                    <div class="fTProfile">							
                        <div class="avtOnly">
                            <!--<div id="profile-picture" class="profile-picture" style="background-image: url('<?php //echo URL ?>assets/images/chat/rChat7.png');">
                                <span class="actON"></span>
                            </div>-->
                            <div id="profile-picture" class="default_avatar_image default_avatar_image_size" style="background-image: url('<?php echo $val['user_profile_picture'] ?>');" data-url="<?php echo URL . $val['profile_name']; ?>">
                                <!-- <span class="actON"></span> -->
                            </div>
                        </div>
                        <div class="sRate">
							<span><?php echo show_dynamic_rating_stars($val['user_total_avg_rating_as_sp']); ?></span>
							<span class="default_avatar_review Rating Rating--labeled" data-star_rating="<?php echo $val['user_total_avg_rating_as_sp']; ?>"><?php echo $val['user_total_avg_rating_as_sp']; ?></span>
                        </div>						
                        <div class="rvw">
                            <span class="default_avatar_total_review">
                                <?php
                                    if($totalReviews == 0){
										$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
									}else if($totalReviews == 1) {
										$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
									} else if($totalReviews > 1) {
										$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
									}
									echo $trGiven;
                                ?>
                            </span>
                            <?php if(!empty($val['hourly_rate'])) {  ?>
                            <small class="default_avatar_complete_project"><?php echo str_replace(".00","",number_format($val['hourly_rate'], 0, '', ' ')).' '.CURRENCY.$this->config->item('find_professionals_user_hourly_rate_per_hour');   ?></small>
                            <?php } ?>
							<?php
							if($val['completed_projects_as_sp'] >0){
							?>
                            <small class="default_avatar_complete_project"><?php
							echo $this->config->item('user_completed_projects')." ".number_format($val['completed_projects_as_sp'],0, '.', ' '); ?></small>
							<?php
							}if($val['completed_projects_as_employee'] >0){
							?>
							<small class="default_avatar_complete_project"><?php
							if(($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y' )){
								if($val['gender'] == 'M'){
									echo $this->config->item('project_details_page_male_user_hires_on_fulltime_projects_as_employee')." ".number_format($val['completed_projects_as_employee'],0, '.', ' ');
								}else{
									echo $this->config->item('project_details_page_female_user_hires_on_fulltime_projects_as_employee')." ".number_format($val['completed_projects_as_employee'],0, '.', ' ');
								}
							
							}else{
								echo $this->config->item('project_details_page_company_user_hires_on_fulltime_projects_as_employee')." ".number_format($val['completed_projects_as_employee'],0, '.', ' ');
							} ?></small> 
                            <?php
							}
                                if($this->session->userdata('user') && $user_data['profile_name'] != $val['profile_name']) {
                            ?>								
                            <div class="fjApply default_applyNow_btn">
                                <button type="button" class="btn default_btn blue_btn" data-page-no="<?php echo $page; ?>" data-profile-name="<?php echo $val['profile_name']; ?>" data-gender="<?php echo $val['gender'] ?>" data-name="<?php echo $val['account_type'] == 1 || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y') ? $val['name'] : $val['company_name']; ?>" data-id="<?php echo $key; ?>" data-profile-pic="<?php echo $val['user_profile_picture'] ?>" data-is-in-contact="<?php echo !empty($val['is_in_contact']) ? $val['is_in_contact'] : ''; ?>" id="contactMe"><?php echo $this->config->item('contactme_button'); ?></button>    
                            </div>      
                            <?php
                                } else if(!$this->session->userdata('user')) {
                            ?>
                            <div class="fjApply default_applyNow_btn">
                                <button type="button" class="btn default_btn blue_btn login_popup" data-page-no="<?php echo $page; ?>" data-id="<?php echo $key; ?>" data-page-type-attr="<?php echo $current_page; ?>" ><?php echo $this->config->item('contactme_button'); ?></button>
                            </div>
                            <?php
                                }
														?>
														<div class="default_project_socialicon">
															<a class="fb_share_profile" data-link="<?php echo $val['fb_share_url']; ?>"><i class="fa fa-facebook"></i></a>
															<a class="twitter_share_profile" data-link="<?php echo $val['twitter_share_url']; ?>" data-title="<?php echo $val['account_type'] == 1 || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y') ? $val['name'] : $val['company_name']; ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(($val['description']), ENT_QUOTES)),$this->config->item('twitter_share_user_profile_description_character_limit')); ?>"><i class="fa fa-twitter"></i></a>
															<a class="ln_share_profile" data-link="<?php echo $val['ln_share_url']; ?>"><i class="fa fa-linkedin"></i></a>
															<a class="share_via_email" data-link="<?php echo $val['email_share_url']; ?>" data-name="<?php echo $val['account_type'] == 1 || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y') ? $val['name'] : $val['company_name']; ?>" data-profile="<?php echo $val['profile_name']; ?>" data-headline="<?php echo (htmlspecialchars($val['headline'], ENT_QUOTES));	 ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(nl2br($val['description']), ENT_QUOTES)),$this->config->item('find_professionals_email_share_user_descripition_character_limit')); ?>" ><i class="fas fa-envelope"></i></a>
														</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-10 col-sm-10 col-12 default_user_details_right_adjust">
                    <div class="opLBttm opBg">
                        <div class="fpBdr">
                            <div class="row">
								<!-- Mobile Version Start -->
								<!-- Avatar Only Start -->
								<div class="col-sm-2 col-12 avatarMobile">
									<div id="profile-picture" class="default_avatar_image default_avatar_image_size" style="background-image: url('<?php echo $val['user_profile_picture'] ?>');" data-url="<?php echo URL . $val['profile_name']; ?>"></div>
								</div>
								<!-- Avatar Only End -->
								<!-- Review Only Start -->
								<div class="col-sm-3 col-12 reviewMobile">
									<div class="sRate">
										<span><?php echo show_dynamic_rating_stars($val['user_total_avg_rating_as_sp']); ?></span>
										<span class="default_avatar_review Rating Rating--labeled" data-star_rating="<?php echo $val['user_total_avg_rating_as_sp']; ?>"><?php echo $val['user_total_avg_rating_as_sp']; ?></span>
									</div>
									<div class="rvw">
										<span class="default_avatar_total_review">
											<?php
												if($totalReviews == 0){
													$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
												}else if($totalReviews == 1) {
													$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
												} else if($totalReviews > 1) {
													$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
												}
												echo $trGiven;
										?></span>
										<?php
										if($val['completed_projects_as_sp'] > 0){
										?>
										<small class="default_avatar_complete_project"><?php
											echo $this->config->item('user_completed_projects')." ".number_format($val['completed_projects_as_sp'],0, '.', ' '); ?></small>
										<?php
										}if($val['completed_projects_as_employee'] > 0 ){
										?>
											<small class="default_avatar_complete_project"><?php
											if(($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y' )){
												if($val['gender'] == 'M'){
													echo $this->config->item('project_details_page_male_user_hires_on_fulltime_projects_as_employee')." ".number_format($val['completed_projects_as_employee'],0, '.', ' ');
												}else{
													echo $this->config->item('project_details_page_female_user_hires_on_fulltime_projects_as_employee')." ".number_format($val['completed_projects_as_employee'],0, '.', ' ');
												}
											
											}else{
												echo $this->config->item('project_details_page_company_user_hires_on_fulltime_projects_as_employee')." ".number_format($val['completed_projects_as_employee'],0, '.', ' ');
											} ?></small>
										<?php
										}
										?>
									</div>
									<div class="default_project_socialicon socialTab">
										<a class="fb_share_profile" data-link="<?php echo $val['fb_share_url']; ?>"><i class="fa fa-facebook"></i></a>
										<a class="twitter_share_profile" data-link="<?php echo $val['twitter_share_url']; ?>" data-title="<?php echo $val['account_type'] == 1 || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y') ? $val['name'] : $val['company_name']; ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(($val['description']), ENT_QUOTES)),$this->config->item('twitter_share_user_profile_description_character_limit')); ?>"><i class="fa fa-twitter"></i></a>
										<a class="ln_share_profile" data-link="<?php echo $val['ln_share_url']; ?>"><i class="fa fa-linkedin"></i></a>
										<a class="share_via_email" data-link="<?php echo $val['email_share_url']; ?>" data-name="<?php echo $val['account_type'] == 1 || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y') ? $val['name'] : $val['company_name']; ?>" data-profile="<?php echo $val['profile_name']; ?>" data-headline="<?php echo (htmlspecialchars($val['headline'], ENT_QUOTES));	 ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(nl2br($val['description']), ENT_QUOTES)),$this->config->item('find_professionals_email_share_user_descripition_character_limit')); ?>" ><i class="fas fa-envelope"></i></a>
									</div>
								</div>
								<!-- Review Only End -->
								<!-- Contact Only Start -->
								<div class="col-sm-7 col-12 contactTab">
									<?php
										if($this->session->userdata('user') && $user_data['profile_name'] != $val['profile_name']) {
									?>								
									<div class="fjApply default_applyNow_btn">
											<button type="button" class="btn default_btn blue_btn" data-page-no="<?php echo $page; ?>" data-profile-name="<?php echo $val['profile_name']; ?>" data-gender="<?php echo $val['gender'] ?>" data-name="<?php echo $val['account_type'] == 1 || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y') ? $val['name'] : $val['company_name']; ?>" data-id="<?php echo $key; ?>" data-profile-pic="<?php echo $val['user_profile_picture'] ?>" data-is-in-contact="<?php echo !empty($val['is_in_contact']) ? $val['is_in_contact'] : ''; ?>" id="contactMe"><?php echo $this->config->item('contactme_button'); ?></button>    
									</div>      
									<?php
										} else if(!$this->session->userdata('user')) {
									?>
									<div class="fjApply default_applyNow_btn">
											<button type="button" class="btn default_btn blue_btn login_popup" data-page-no="<?php echo $page; ?>" data-id="<?php echo $key; ?>" data-page-type-attr="<?php echo $current_page; ?>" ><?php echo $this->config->item('contactme_button'); ?></button>
									</div>
									<?php
										}
									?>
								</div>
								<!-- Contact Only End -->
								<!-- Mobile Version End -->
                                <div class="col-md-12 col-sm-12 col-12 userMobile">
                                    <p class="default_user_name">
                                        <a class="default_user_name_link" target="_blank" href="<?php echo URL.$val['profile_name']; ?>"><?php echo (($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) ?  $val['name'] : $val['company_name'];?></a>
                                    </p>
                                    <?php if($val['headline']!='') { ?>
                                    <div class="headline_title"><?php echo htmlspecialchars($val['headline'], ENT_QUOTES);?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="default_user_description desktop-secreen fpBtop default_headline_gap">
                            <p id="desktop_lessD<?php echo $key; ?>">
                                <?php echo $desktop_description;?><?php if($desktop_cnt==1) {?><span id="desktop_dotsD<?php echo $key; ?>"></span><button onclick="showMoreDescription('desktop', <?php echo $key; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
                                <?php } ?></p><p id="desktop_moreD<?php echo $key; ?>" class="moreD">
                                <?php echo $desktop_restdescription;?>
                                <button onclick="showMoreDescription('desktop', <?php echo $key; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
                            </p>
                        </div>
                        <div class="default_user_description ipad-screen fpBtop default_headline_gap">
                            <p id="tablet_lessD<?php echo $key; ?>">
                                <?php echo $tablet_description;?><?php if($tablet_cnt==1) {?><span id="tablet_dotsD<?php echo $key; ?>"></span><button onclick="showMoreDescription('tablet', <?php echo $key; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
                                <?php } ?></p><p id="tablet_moreD<?php echo $key; ?>" class="moreD">
                                <?php echo $tablet_restdescription;?>
                                <button onclick="showMoreDescription('tablet', <?php echo $key; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
                            </p>
                        </div>
                        <div class="default_user_description mobile-screen fpBtop default_headline_gap">
                            <p id="mobile_lessD<?php echo $key; ?>">
                                <?php echo $mobile_description;?><?php if($mobile_cnt==1) {?><span id="mobile_dotsD<?php echo $key; ?>"></span><button onclick="showMoreDescription('mobile', <?php echo $key; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
                                <?php } ?></p><p id="mobile_moreD<?php echo $key; ?>" class="moreD">
                                <?php echo $mobile_restdescription;?>
                                <button onclick="showMoreDescription('mobile', <?php echo $key; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
                            </p>
                        </div>
						<!-- Hourly Rate Only Mobile Version Start -->
						<div class="hrateMobile">
							<?php if (!empty($val['hourly_rate'])) {?>
							<i class="fa fa-money" aria-hidden="true"></i><small class="default_avatar_complete_project"><?php echo str_replace(".00","",number_format($val['hourly_rate'], 0, '', ' ')) . ' ' . CURRENCY . $this->config->item('find_professionals_user_hourly_rate_per_hour'); ?></small>
							<?php }?>
						</div>
						<!-- Hourly Rate Only Mobile Version End -->
                    </div>
                    <div class="clearfix"></div>
                    
                    <?php 
                        if(!empty($val['country_name'])) {
						$country_flag = ASSETS .'images/countries_flags/'.strtolower($val['country_code']).'.png';
                    ?>
					<div class="default_user_location">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-12">
								<span><i class="<?php echo $val['account_type'] == 1 || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y') ? 'fas fa-map-marker-alt' : 'far fa-building'; ?>" aria-hidden="true"></i></span><?php
								$address_details = '';
								if(!empty($val['street_address'])){
									if(!preg_match('/\s/',$val['street_address'])) {
										$address_details .= '<small class="street_address_nospace default_black_bold_bigger">'.htmlspecialchars($val['street_address'], ENT_QUOTES).',</small>';
									} else {
										$address_details .= '<small class="default_black_bold_bigger">'.htmlspecialchars($val['street_address'], ENT_QUOTES).',</small>';
									}
								}
								if(!empty($val['locality']) && !empty($val['postal_code'])){
									$address_details .= '<small class="default_black_bold_bigger">'.$val['locality'].' '.$val['postal_code'].',</small>';
								}
								if(empty($val['locality']) && !empty($val['postal_code'])){
									$address_details .= '<small class="default_black_bold_bigger"> '.$val['postal_code'].',</small>';
								}
								if(!empty($val['locality']) && empty($val['postal_code'])){
									$address_details .= '<small class="default_black_bold_bigger">'.$val['locality'].',</small>';
								}
								if(!empty($val['county'])){
									$address_details .= '<small class="default_black_bold_bigger">'.$val['county'].',</small>';
								}
								$address_details  .= '<small class="default_black_bold_bigger">'.$val['country_name'].'<div class="default_user_location_flag" style="background-image: url('.$country_flag.')"></div></small>';
								echo $address_details;
								?>
								<?php
								/* <small><?php echo $val['locality']; ?></small><small><?php echo $val['postal_code']; ?>,</small><small><?php echo $val['county'] ?></small><small class="default_user_location_flag" style="background-image: url('<?php echo URL ?>assets/images/countries_flags/cz.png');"></small> */
								?>
								
							</div>
						</div>
					</div>
					<?php
                  /*   <div class="default_user_location">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12">
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span><small><?php echo $val['locality']; ?></small><small><?php echo $val['postal_code']; ?>,</small><small><?php echo $val['county'] ?></small><small class="default_user_location_flag" style="background-image: url('<?php echo URL ?>assets/images/countries_flags/cz.png');"></small>
                            </div>
                        </div>
                    </div> */
					?>
                    <?php
                        }
                    ?>
                    <?php if(count($val['professional_category']) > 0) { ?>
                    <div class="row">
						<div class="col-md-12 col-sm-12 col-12">
							<div class="default_project_category" id="cat<?php echo $key; ?>">
								<?php
									$catRowVal = 0;
									$c = 0;
									foreach($val['professional_category'] as $cat=>$scatArr)
									{
										$c++;
										$class = '';
										if(count($val['professional_category']) > $this->config->item('find_professionals_areas_of_expertise_show_more_less') && $c > $this->config->item('find_professionals_areas_of_expertise_show_more_less')) {
											$class = ' moreCat';
										}
										if(is_array($scatArr)) {
											$sclass = '';
											if(count($scatArr)<2) {
												$sclass = ' catSub12';
											}
											if(count($scatArr)==2) {
												$sclass = ' catSub3';
											}
										} else {
											$sclass = ' catSub12';
										}
										
								?>
								<div class="clearfix<?php echo $class.$sclass; ?>">
									<small class="pSmnu"><?php echo $cat  ;?></small>
									<?php 
										if(is_array($scatArr)) {
										foreach($scatArr as $subcat) { 
									?>
										<a href="#">
												<span><?php echo is_array($subcat) ? $subcat[1] : $subcat; ?></span>
										</a>
									<?php 
											}  
										} 
									?>
								</div>
								<?php 
									}
									if(count($val['professional_category']) > $this->config->item('find_professionals_areas_of_expertise_show_more_less') && $c > $this->config->item('find_professionals_areas_of_expertise_show_more_less')) 
									{
										$catRowVal = 1;
								?>
									
									<div class="show_more_less"><button onclick="showMoreCat(<?php echo $key; ?>)" id="cat_myBtnD<?php echo $key; ?>"><?php echo $this->config->item('show_more_txt'); ?></button></div>
									<?php
										}
									?>
									<input type="hidden" id="catRow<?php echo $key; ?>" value="<?php echo $catRowVal;?>">
								<div class="clearfix"></div>
							</div>
						</div>
                    </div>
                    <?php } ?>
					<!-- Contact Only Mobile Version Start -->
					<div class="row">
						<div class="col-12 socialMobile">
							<div class="default_project_socialicon">
								<a class="fb_share_profile" data-link="<?php echo $val['fb_share_url']; ?>"><i class="fa fa-facebook"></i></a>
								<a class="twitter_share_profile" data-link="<?php echo $val['twitter_share_url']; ?>" data-title="<?php echo $val['account_type'] == 1 ? $val['name'] : $val['company_name']; ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(($val['description']), ENT_QUOTES)),$this->config->item('twitter_share_user_profile_description_character_limit')); ?>"><i class="fa fa-twitter"></i></a>
								<a class="ln_share_profile" data-link="<?php echo $val['ln_share_url']; ?>"><i class="fa fa-linkedin"></i></a>
								<a class="share_via_email" data-link="<?php echo $val['email_share_url']; ?>" data-name="<?php echo $val['account_type'] == 1 ? $val['name'] : $val['company_name']; ?>" data-profile="<?php echo $val['profile_name']; ?>" data-headline="<?php echo (htmlspecialchars($val['headline'], ENT_QUOTES));	 ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(nl2br($val['description']), ENT_QUOTES)),$this->config->item('find_professionals_email_share_user_descripition_character_limit')); ?>" ><i class="fas fa-envelope"></i></a>
							</div>
						</div>
						<div class="col-12 contactMobile">
							<div class="fjApply default_applyNow_btn">
								<button type="button" class="btn default_btn blue_btn login_popup" data-page-no="<?php echo $page; ?>" data-id="<?php echo $key; ?>" data-page-type-attr="<?php echo $current_page; ?>" ><?php echo $this->config->item('contactme_button'); ?></button>
							</div>
						</div>
					</div>
					<!-- Contact Only Mobile Version End -->
				</div>
			</div>
		</div>
        <!-- </div> -->
    <?php 
        }
    
        
    }
    else{
    ?>
    <div class="initialViewNorecord">
        <?php echo $this->config->item('find_professionals_search_no_results_returned_message'); ?>
    </div>
    <?php } ?>
<script>
var uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
</script>