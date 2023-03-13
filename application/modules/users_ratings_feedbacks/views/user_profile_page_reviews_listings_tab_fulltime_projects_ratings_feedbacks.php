<?php
$user = $this->session->userdata('user');	
if(!empty($ratings_feedbacks_data) && $view_type == 'employee'){
?>
<div class="company_listingTab">
<?php
	
	foreach($ratings_feedbacks_data as $key=>$value){
	$counter =$value['id'];
	$employer_name = (($value['employer_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['employer_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['employer_is_authorized_physical_person'] == 'Y')) ? $value['employer_first_name'] . ' ' . $value['employer_last_name'] : $value['employer_company_name'];
	
	$employee_first_name = (($value['employee_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['employee_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['employee_is_authorized_physical_person'] == 'Y')) ? $value['employee_first_name']  : $value['employee_company_name'];
	
	$employee_name = (($value['employee_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['employee_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['employee_is_authorized_physical_person'] == 'Y')) ? $value['employee_first_name']." ".$value['employee_last_name']  : $value['employee_company_name'];
	

?>
	<div class="serPrv">
		<div class="sRate">
			<label>
				<a href="<?php echo base_url().$this->config->item('project_detail_page_url')."?id=".$value['feedback_provided_on_fulltime_project_id']; ?>" target="_blank" class="default_project_title"><?php echo $value['project_title'];  ?></a>
			</label>
			<label>
				<small>
					<?php echo show_dynamic_rating_stars($value['fulltime_project_avg_rating_as_employee']);?>
				</small>
				<span class="default_avatar_review"><?php echo $value['fulltime_project_avg_rating_as_employee']; ?></span>
				<small class="receive_notification expand_notification_area<?php echo $counter?>"><a class="rcv_notfy_btn" onclick="showMoreReview('<?php echo $counter?>')">(<sup>+</sup>)</a>
				<input type="hidden" id="moreReview<?php echo $counter;?>" value="1"></small>
			</label>
		</div>
		<p>	
			<span><?php echo str_replace(array('{feedback_provided_on_date}','{user_first_name_last_name_or_company_name}','{user_profile_page_url}'),array(date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($value['feedback_provided_on_date'])),$employer_name,site_url ($value['employer_profile_name'])),$this->config->item('user_profile_page_feedbacks_tab_review_posted_on_date_txt')) ?></span>
		</p>
		<!-- Collapse Section Start -->
		<div id="rcv_notfy<?php echo $counter;?>" class="collapseSection" style="display:none">
			<div class="row">
				<!-- Left Section Start -->
				<div class="col-md-5 col-sm-5 col-12 ratingLeft projectTwo">
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_demonstrates_effective_oral_verbal_communication_skills_employer_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['demonstrates_effective_oral_verbal_communication_skills']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['demonstrates_effective_oral_verbal_communication_skills']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_work_quality_employer_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['work_quality']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['work_quality']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_self_motivated_employer_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['self_motivated']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['self_motivated']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_working_relations_employer_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['working_relations']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['working_relations'];?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_demonstrates_flexibility_adaptability_employer_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['demonstrates_flexibility_adaptability']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['demonstrates_flexibility_adaptability']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_solves_problems_employer_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['solves_problems']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['solves_problems']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_work_ethic_employer_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['work_ethic']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['work_ethic']; ?></span>
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
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_shows_interest_enthusiasm_for_work_employer_view') ?></span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['employee_shows_interest_enthusiasm_for_work'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>											
					<div class="responseDiv">
						<div class="workText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_demonstrates_competency_in_knowledge_skills_employer_view') ?></span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['employee_demonstrates_competency_in_knowledge_skills'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>											
					<div class="responseDiv">
						<div class="workText">
							
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_demonstrates_levels_of_skill_knowledge_employer_view') ?></span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['employee_demonstrates_levels_of_skill_knowledge'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>											
					<div class="responseDiv">
						<div class="workText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_dependable_and_reliable_employer_view'); ?></span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['employee_dependable_and_relied'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="responseDiv">
						<div class="workText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_properly_organizes_prioritizes_employer_view'); ?></span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['employee_properly_organizes_prioritizes'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
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
				$desktop_descLeng	=	strlen($value['feedback_left_by_employer']);  
				if($desktop_descLeng <= $this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_desktop')) {
				 $desktop_description	= nl2br(htmlspecialchars($value['feedback_left_by_employer'], ENT_QUOTES));
				} else {
					$desktop_description	= character_limiter(nl2br($value['feedback_left_by_employer']),$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_desktop'));
					$desktop_restdescription = nl2br(htmlspecialchars($value['feedback_left_by_employer'], ENT_QUOTES));
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
			$tablet_descLeng	=	strlen($value['feedback_left_by_employer']);
			if($tablet_descLeng <= $this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_tablet')) {
			$tablet_description	= nl2br(htmlspecialchars($value['feedback_left_by_employer'], ENT_QUOTES));
			} else {
				//$tablet_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_tablet'));
				  $tablet_description	= character_limiter(nl2br($value['feedback_left_by_employer']),$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_tablet'));
				$tablet_restdescription = nl2br(htmlspecialchars($value['feedback_left_by_employee'], ENT_QUOTES));
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
				$mobile_descLeng	=	strlen($value['feedback_left_by_employer']);
				if($mobile_descLeng <= $this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_mobile')) {
					$mobile_description	= nl2br(htmlspecialchars($value['feedback_left_by_employer'], ENT_QUOTES));
				} else {
					//$mobile_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_mobile'));
					$mobile_description	= character_limiter(nl2br($value['feedback_left_by_employer']),$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_mobile'));
					$mobile_restdescription = nl2br(htmlspecialchars($value['feedback_left_by_employer'], ENT_QUOTES));
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
			<div id="<?php echo "feedback_reply_conatiner_".$value['id'] ?>" class="reviewReply" style="display:<?php echo empty($value['feedback_reply_by_employee']) ? 'none' : 'block' ?>">
				<?php echo $this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_ratings_feedbacks_reply_detail', array('section_id'=>$value['id'],'feedback_reply'=>$value['feedback_reply_by_employee'],'feedback_reply_on_date'=>$value['feedback_reply_on_date'],'reply_by_user'=>$employee_name), true); ?>
			</div>
			<?php
			if($this->session->userdata('user')){
				if($user[0]->user_id == $value['feedback_recived_by_employee_id'] && empty($value['feedback_reply_by_employee'])){
					 echo $this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_ratings_feedbacks_reply_form', array('section_id'=>$value['id'],'view_type'=>'employee','feedback_recived_by'=>$value['feedback_recived_by_employee_id'],'feedback_given_by'=>$value['feedback_given_by_employer_id'],'project_id'=>$value['feedback_provided_on_fulltime_project_id']), true); 
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
			<input type="hidden" id="pageno_employee_reviews" value="1">
			<button type="button" id="loadmore_employee_reviews" class="btn default_btn blue_btn" style="height:35px;"><?php echo $this->config->item('load_more_results'); ?> <i id="employee_spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
		</div>
	</div>
<?php
	}
}
?>
<?php
if(!empty($ratings_feedbacks_data) && $view_type == 'employer'){
?>
<div class="company_listingTab">
<?php
	
	foreach($ratings_feedbacks_data as $key=>$value){
	$counter =$value['id'];
	
	$employer_name = (($value['employer_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['employer_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['employer_is_authorized_physical_person'] == 'Y')) ? $value['employer_first_name'] . ' ' . $value['employer_last_name'] : $value['employer_company_name'];
	
	
	
	$employer_first_name = (($value['employer_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['employer_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['employer_is_authorized_physical_person'] == 'Y')) ? $value['employer_first_name']  : $value['employer_company_name'];
	
	$employee_name = (($value['employee_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['employee_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['employee_is_authorized_physical_person'] == 'Y')) ? $value['employee_first_name']." ".$value['employee_last_name']  : $value['employee_company_name'];

?>
	<div class="serPrv">
		<div class="sRate">
			<label>
				<a href="<?php echo base_url().$this->config->item('project_detail_page_url')."?id=".$value['feedback_provided_on_fulltime_project_id']; ?>" class="default_project_title"><?php echo $value['project_title'];  ?></a>
			</label>				
			<label>				
				<small>
					<?php echo show_dynamic_rating_stars($value['fulltime_project_avg_rating_as_employer']);?>
				</small>
				<span class="default_avatar_review"><?php echo $value['fulltime_project_avg_rating_as_employer']; ?></span>
				<small class="receive_notification expand_notification_area<?php echo $counter?>"><a class="rcv_notfy_btn" onclick="showMoreReview('<?php echo $counter?>')">(<sup>+</sup>)</a>
				<input type="hidden" id="moreReview<?php echo $counter;?>" value="1"></small>
			</label>
		</div>
		<p>	
			<span><?php echo str_replace(array('{feedback_provided_on_date}','{user_first_name_last_name_or_company_name}','{user_profile_page_url}'),array(date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($value['feedback_provided_on_date'])),$employee_name,site_url ($value['employee_profile_name'])),$this->config->item('user_profile_page_feedbacks_tab_review_posted_on_date_txt')) ?></span>
		</p>
		<!-- Collapse Section Start -->
		<div id="rcv_notfy<?php echo $counter;?>" class="collapseSection" style="display:none">
			<div class="row">
				<!-- Left Section Start -->
				<div class="col-md-5 col-sm-5 col-12 ratingLeft projectOne">
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_work_life_balance_feedback_employee_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['work_life_balance']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['work_life_balance']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_career_opportunities_employee_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['career_opportunities']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['career_opportunities']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_compensation_benefits_employee_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['compensation_benefits']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['compensation_benefits']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_proper_training_support_mentorship_leadership_employee_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['proper_training_support_mentorship_leadership']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['proper_training_support_mentorship_leadership'];?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_explained_job_responsibilities_expectation_employee_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['explained_job_responsibilities_expectation']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['explained_job_responsibilities_expectation']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_environment_encourages_expressing_sharing_ideas_innovation_employee_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['environment_encourages_expressing_sharing_ideas_innovation']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['environment_encourages_expressing_sharing_ideas_innovation']; ?></span>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="ratingDiv">
						<div class="lRatingText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_safe_healthy_environment_employee_view'); ?></span>
						</div>
						<div class="rRating">
							<div class="sRate">
								<small>
									<?php echo show_dynamic_rating_stars($value['safe_healthy_environment']);?>
								</small>
								<span class="default_avatar_review"><?php echo $value['safe_healthy_environment']; ?></span>
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
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_appreciated_right_level_employee_view') ?></span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['appreciated_right_level'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>											
					<div class="responseDiv">
						<div class="workText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_empowered_take_extra_responsibilities_employee_view') ?></span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['empowered_take_extra_responsibilities'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>									
					<div class="responseDiv">
						<div class="workText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_recognition_work_achievements_employee_view'); ?></span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['recognition_work_achievements'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="responseDiv">
						<div class="workText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_receive_regular_consistent_feedback_employee_view'); ?></span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['receive_regular_consistent_feedback'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="responseDiv">
						<div class="workText">
							<span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_recommend_this_company_employee_view'); ?></span>
						</div>
						<div class="responseText">
							<span class="default_black_regular"><?php echo $value['recommend_this_company'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>
					
				</div>
				<!-- Right Section Start -->
			</div>
		</div>
		<!-- Collapse Section End -->
		<?php
		/* <div class="default_user_description">
			<p><?php echo $value['feedback_left_by_employee'];  ?></p>
		</div> */
		?>
		<div class="descTxt default_user_description desktop-secreen">
			<?php
				$desktop_cnt            =	0;
				$desktop_descLeng	=	strlen($value['feedback_left_by_employee']);  
				if($desktop_descLeng <= $this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_desktop')) {
				 $desktop_description	= nl2br(htmlspecialchars($value['feedback_left_by_employee'], ENT_QUOTES));
				} else {
					$desktop_description	= character_limiter(nl2br($value['feedback_left_by_employee']),$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_desktop'));
					$desktop_restdescription = nl2br(htmlspecialchars($value['feedback_left_by_employee'], ENT_QUOTES));
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
			$tablet_descLeng	=	strlen($value['feedback_left_by_employee']);
			if($tablet_descLeng <= $this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_tablet')) {
			$tablet_description	= nl2br(htmlspecialchars($value['feedback_left_by_employee'], ENT_QUOTES));
			} else {
				//$tablet_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_tablet'));
				  $tablet_description	= character_limiter(nl2br($value['feedback_left_by_employee']),$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_tablet'));
				$tablet_restdescription = nl2br(htmlspecialchars($value['feedback_left_by_employee'], ENT_QUOTES));
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
				$mobile_descLeng	=	strlen($value['feedback_left_by_employee']);
				if($mobile_descLeng <= $this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_mobile')) {
					$mobile_description	= nl2br(htmlspecialchars($value['feedback_left_by_employee'], ENT_QUOTES));
				} else {
					//$mobile_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_mobile'));
					$mobile_description	= character_limiter(nl2br($value['feedback_left_by_employee']),$this->config->item('user_profile_page_feedbacks_section_description_display_minimum_length_character_limit_mobile'));
					$mobile_restdescription = nl2br(htmlspecialchars($value['feedback_left_by_employee'], ENT_QUOTES));
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
			<div id="<?php echo "feedback_reply_conatiner_".$value['id'] ?>" class="reviewReply" style="display:<?php echo empty($value['feedback_reply_by_employer']) ? 'none' : 'block' ?>">
				<?php echo $this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_ratings_feedbacks_reply_detail', array('section_id'=>$value['id'],'feedback_reply'=>$value['feedback_reply_by_employer'],'feedback_reply_on_date'=>$value['feedback_reply_on_date'],'reply_by_user'=>$employer_name), true); ?>
			</div>
			<?php
			if($this->session->userdata('user')){
				if($user[0]->user_id == $value['feedback_recived_by_employer_id'] && empty($value['feedback_reply_by_employer'])){
					 echo $this->load->view('users_ratings_feedbacks/user_profile_page_reviews_listings_tab_ratings_feedbacks_reply_form', array('section_id'=>$value['id'],'view_type'=>'employer','feedback_recived_by'=>$value['feedback_recived_by_employer_id'],'feedback_given_by'=>$value['feedback_given_by_employee_id'],'project_id'=>$value['feedback_provided_on_fulltime_project_id']), true); 
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
			<input type="hidden" id="pageno_employer_reviews" value="1">
			<button type="button" id="loadmore_employer_reviews" class="btn default_btn blue_btn" style="height:35px;"><?php echo $this->config->item('load_more_results'); ?> <i id="employer_spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
		</div>
	</div>
<?php
	}
}
// When no feedback for employee/employer view then below code will execute
if(empty($ratings_feedbacks_data) && $view_type == 'employee'){
	if($this->session->userdata('user') && $user[0]->user_id == $user_detail['user_id']){
         $no_data_msg =  $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_employee_view');
	}else{
		if(($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')){	
			
			if($user_detail['gender'] == 'M'){
			
				if($user_detail['is_authorized_physical_person'] == 'Y'){
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_company_app_male_visitor_view');
				}else{
			
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_male_visitor_view');
				}
			}else if($user_detail['gender'] == 'F'){
				if($user_detail['is_authorized_physical_person'] == 'Y'){
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_company_app_female_visitor_view');
				}else{
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_female_visitor_view');
				}
			}
			$no_data_msg = str_replace(array('{user_first_name_last_name}'),array($user_detail['first_name']." ".$user_detail['last_name']),$no_data_msg);
		}else{
			$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab_no_data_company_visitor_view');
			$no_data_msg = str_replace(array('{user_company_name}'),array($user_detail['company_name']),$no_data_msg);
		}

	}
	echo $noRecDiv = '<div class="default_blank_message">'.$no_data_msg.'</div>';
}
if(empty($ratings_feedbacks_data) && $view_type == 'employer'){
	if($this->session->userdata('user') && $user[0]->user_id == $user_detail['user_id']){
         $no_data_msg =  $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_employer_view');
	}else{
		if(($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')){
			
			if($user_detail['gender'] == 'M'){
			
				if($user_detail['is_authorized_physical_person'] == 'Y'){
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_company_app_male_visitor_view');
				}else{
			
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_male_visitor_view');
				}
			}else if($user_detail['gender'] == 'F'){
				if($user_detail['is_authorized_physical_person'] == 'Y'){
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_company_app_female_visitor_view');
				}else{
					$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_female_visitor_view');
				}
			}
			$no_data_msg = str_replace(array('{user_first_name_last_name}'),array($user_detail['first_name']." ".$user_detail['last_name']),$no_data_msg);
		}else{
			$no_data_msg = $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_no_data_company_visitor_view');
			$no_data_msg = str_replace(array('{user_company_name}'),array($user_detail['company_name']),$no_data_msg);
		}

	}
	echo $noRecDiv = '<div class="default_blank_message">'.$no_data_msg.'</div>';
}
?>