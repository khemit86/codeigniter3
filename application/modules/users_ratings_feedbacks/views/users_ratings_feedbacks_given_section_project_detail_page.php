<?php
$user = $this->session->userdata ('user');
$user_id = $user[0]->user_id;
$rating = '0.0';
$feedback_description = '';




if($project_type != 'fulltime'){
	if($user_id == $po_id && $view_type == 'po'){
		$rating = $feedback_data['project_avg_rating_as_sp'];
		$feedback_description = $feedback_data['feedback_left_by_po'];
	}else if($user_id == $sp_id && $view_type == 'sp'){
		$rating = $feedback_data['project_avg_rating_as_po'];
		$feedback_description = $feedback_data['feedback_left_by_sp'];
	}
}else{

	if($user_id == $po_id && $view_type == 'po'){
		$rating = $feedback_data['fulltime_project_avg_rating_as_employee'];
		$feedback_description = $feedback_data['feedback_left_by_employer'];
	}else if($user_id == $sp_id && $view_type == 'sp'){
		$rating = $feedback_data['fulltime_project_avg_rating_as_employer'];
		$feedback_description = $feedback_data['feedback_left_by_employee'];
	}
}
?>	


<div class="feedbackBtn">
	
	<?php
	if($check_receiver_view_his_rating == 0){	
	?>
	<!--<div class="default_blank_message">Please give your feedback</div>-->
	<button type="button" data-view-type = "<?php echo $view_type;?>" data-project-type = "<?php echo $project_type; ?>" data-section-name="<?php echo $section_name; ?>"  data-section-id="<?php echo $section_id; ?>"  data-project-id="<?php echo $project_id; ?>" data-sp-id = "<?php echo Cryptor::doEncrypt($sp_id); ?>" data-po-id = "<?php echo Cryptor::doEncrypt($po_id); ?>" class="btn blue_btn default_btn ratings_feedbacks" data-toggle="modal"><?php echo $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_give_feedback_button_txt'); ?></button>
	<?php
	}
	?>
</div>
<?php
if(!empty($feedback_data)){
?>
<div>
	<!--<div class="fcd">-->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12">
				<div class="comPleted">
					<?php echo $feedback_given_msg; ?><label><span class="sRate"><?php echo show_dynamic_rating_stars($rating); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $rating; ?></small></span></label>
				</div>
			</div>
		</div>
	<!--</div>-->
	
	<div class="fbackOwner collapseSection">
		<?php
		if($project_type != 'fulltime'){
			if($user_id == $po_id && $view_type == 'po'){
			?>
                            
            
			<!--<div><span class="default_black_bold"><?php echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_project_delivered_within_agreed_budget_po_view')); ?></span><small class="default_black_regular"><?php echo $feedback_data['project_delivered_within_agreed_budget'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			<div><span class="default_black_bold"><?php echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_work_delivered_within_agreed_time_slot_po_view')); ?></span><small class="default_black_regular"><?php echo $feedback_data['work_delivered_within_agreed_time_slot'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			<div><span class="default_black_bold"><?php echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_again_po_view')); ?></span><small class="default_black_regular"><?php echo $feedback_data['would_you_hire_sp_again'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			<div><span class="default_black_bold"><?php echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_po_view')); ?></span><small class="default_black_regular"><?php echo $feedback_data['would_you_recommend_sp'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			<div class="fP">
				<div class="row">
				<div class="col-md-4 col-sm-4 col-12">
					<h5 class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_quality_po_view'); ?></h5>
					<h5 class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_communication_po_view'); ?></h5>
					<h5 class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_professionalism_po_view'); ?></h5>
					<h5 class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_expertise_po_view'); ?></h5>
					<h5 class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_value_for_money_po_view'); ?></h5>
				</div>
				<div class="col-md-8 col-sm-8 col-12">
					<div class="ownerFeedback">
						<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['quality_of_work']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['quality_of_work']; ?></small></span>
					</div>
					<div class="ownerFeedback">
						<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['communication']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['communication']; ?></small></span>
					</div>
					<div class="ownerFeedback">
						<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['professionalism']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['professionalism']; ?></small></span>
					</div>
					<div class="ownerFeedback">
						<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['expertise']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['expertise']; ?></small></span>
					</div>
					<div class="ownerFeedback">
						<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['value_for_money']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['value_for_money']; ?></small></span>
					</div>
				</div>
				</div>
			</div>-->
                        
                        
                        <div class="row">
                            <!-- Left Section Start -->
                            <div class="col-md-5 col-sm-5 col-12 ratingLeft">
                                <div class="ratingDiv">
                                    <div class="lRatingText">
                                        <span class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_quality_po_view'); ?></span>
                                    </div>
                                    <div class="rRating">
                                        <div class="sRate">
                                            <small><?php echo show_dynamic_rating_stars($feedback_data['quality_of_work']); ?></small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['quality_of_work']; ?></span>
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
                                            <small><?php echo show_dynamic_rating_stars($feedback_data['communication']); ?></small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['communication']; ?></span>
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
                                            <small><?php echo show_dynamic_rating_stars($feedback_data['professionalism']); ?></small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['professionalism']; ?></span>
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
                                            <small><?php echo show_dynamic_rating_stars($feedback_data['expertise']); ?></small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['expertise']; ?></span>
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
                                            <small><?php echo show_dynamic_rating_stars($feedback_data['value_for_money']); ?></small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['value_for_money']; ?></span>
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
                                        <span class="default_black_bold"><?php echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_project_delivered_within_agreed_budget_po_view')); ?></span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['project_delivered_within_agreed_budget'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="responseDiv">
                                    <div class="workText">
                                        <span class="default_black_bold"><?php echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_work_delivered_within_agreed_time_slot_po_view')); ?></span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['work_delivered_within_agreed_time_slot'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="responseDiv">
                                    <div class="workText">
                                        <span class="default_black_bold">
										<?php
										if(($sp_account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_account_type == USER_COMPANY_ACCOUNT_TYPE && $sp_is_authorized_physical_person == 'Y')){
											if($sp_is_authorized_physical_person == 'Y'){
												if($sp_gender =='M' ){
													echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_company_app_male_again_po_view'));
												}else{
													echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_company_app_female_again_po_view'));
												}
											}else{
												if($sp_gender =='M' ){
													echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_male_again_po_view'));
												}else{
													echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_female_again_po_view'));
												}
											}
										}else{
											echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_company_again_po_view')); 
										}
										?>	
										</span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['would_you_hire_sp_again'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="responseDiv">
                                    <div class="workText">
                                        <span class="default_black_bold">
										<?php
										if(($sp_account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_account_type == USER_COMPANY_ACCOUNT_TYPE && $sp_is_authorized_physical_person == 'Y')){
											if($sp_is_authorized_physical_person == 'Y'){
												if($sp_gender =='M' ){
													echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_company_app_male_po_view'));
												}else{
													echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_company_app_female_po_view'));
												}
											}else{
												if($sp_gender =='M' ){
													echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_male_po_view'));
												}else{
													echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_female_po_view'));
												}
											}
										}else{
											echo str_replace(array('{user_first_name_or_company_name}'),array($sp_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_company_po_view')); 
										}
										?>		
										</span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['would_you_recommend_sp'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?>
										</span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <!-- Right Section Start -->
                        </div>
                        
                        
		  <?php
			}else if($user_id == $sp_id && $view_type == 'sp'){
		   ?>
			<!--<div><span class="default_black_bold"><?php echo str_replace(array('{user_first_name_or_company_name}'),array($po_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_sp_view')); ?></span><small class="default_black_regular"><?php echo $feedback_data['would_you_work_again_with_po'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			<div><span class="default_black_bold"><?php echo str_replace(array('{user_first_name_or_company_name}'),array($po_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_sp_view')); ?></span><small class="default_black_regular"><?php echo $feedback_data['would_you_recommend_po'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			<div class="fP">
				<div class="row">
				<div class="col-md-4 col-sm-4 col-12">
					<h5 class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_clarity_in_requirements_sp_view'); ?></h5>
					<h5 class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_communication_sp_view'); ?></h5>
					<h5 class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_payment_promptness_sp_view'); ?></h5>
				</div>
				<div class="col-md-8 col-sm-8 col-12">
					<div class="ownerFeedback">
						<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['clarity_in_requirements']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['clarity_in_requirements']; ?></small></span>
					</div>
					<div class="ownerFeedback">
						<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['communication']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['communication']; ?></small></span>
					</div>
					<div class="ownerFeedback">
						<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['payment_promptness']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['payment_promptness']; ?></small></span>
					</div>
				</div>
				</div>
			</div>-->
			
                        <div class="row">
                            <!-- Left Section Start -->
                            <div class="col-md-5 col-sm-5 col-12 ratingLeft">
                                <div class="ratingDiv">
                                    <div class="lRatingText">
                                        <span class="default_black_bold"><?php echo $this->config->item('projects_users_ratings_feedbacks_clarity_in_requirements_sp_view'); ?></span>
                                    </div>
                                    <div class="rRating">
                                        <div class="sRate">
                                            <small><?php echo show_dynamic_rating_stars($feedback_data['clarity_in_requirements']); ?></small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['clarity_in_requirements']; ?></span>
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
                                            <small><?php echo show_dynamic_rating_stars($feedback_data['communication']); ?></small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['communication']; ?></span>
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
                                            <small><?php echo show_dynamic_rating_stars($feedback_data['payment_promptness']); ?></small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['payment_promptness']; ?></span>
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
                                        <span class="default_black_bold">
										<?php
										if(($po_account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($po_account_type == USER_COMPANY_ACCOUNT_TYPE && $po_is_authorized_physical_person == 'Y')){
											if($po_is_authorized_physical_person == 'Y'){
												if($po_gender =='M' ){
													echo str_replace(array('{user_first_name_or_company_name}'),array($po_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_company_app_male_sp_view'));
												}else{
													echo str_replace(array('{user_first_name_or_company_name}'),array($po_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_company_app_female_sp_view'));
												}
											}else{
												if($po_gender =='M' ){
													echo str_replace(array('{user_first_name_or_company_name}'),array($po_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_male_sp_view'));
												}else{
													echo str_replace(array('{user_first_name_or_company_name}'),array($po_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_female_sp_view'));
												}
											}
										}else{
											echo str_replace(array('{user_first_name_or_company_name}'),array($po_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_company_sp_view')); 
										}
										?>	
										</span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['would_you_work_again_with_po'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="responseDiv">
                                    <div class="workText">
                                        <span class="default_black_bold">
										<?php
										if(($po_account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($po_account_type == USER_COMPANY_ACCOUNT_TYPE && $po_is_authorized_physical_person == 'Y')){
											if($po_is_authorized_physical_person == 'Y'){
												if($po_gender =='M' ){
													echo str_replace(array('{user_first_name_or_company_name}'),array($po_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_company_app_male_sp_view'));
												}else{
													echo str_replace(array('{user_first_name_or_company_name}'),array($po_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_company_app_female_sp_view'));
												}
											}else{
												if($po_gender =='M' ){
													echo str_replace(array('{user_first_name_or_company_name}'),array($po_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_male_sp_view'));
												}else{
													echo str_replace(array('{user_first_name_or_company_name}'),array($po_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_female_sp_view'));
												}
											}
										}else{
											echo str_replace(array('{user_first_name_or_company_name}'),array($po_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_company_sp_view')); 
										}
										?>
										
										
										
										</span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['would_you_recommend_po'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <!-- Right Section Start -->
                        </div>
		<?php
				
			}
		}else{
			if($user_id == $po_id && $view_type == 'po'){
			?>
			<!--<div><span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_shows_interest_enthusiasm_for_work_employer_view'); ?></span><small class="default_black_regular"><?php echo $feedback_data['employee_shows_interest_enthusiasm_for_work'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			<div><span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_demonstrates_competency_in_knowledge_skills_employer_view'); ?></span><small class="default_black_regular"><?php echo $feedback_data['employee_demonstrates_competency_in_knowledge_skills'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			<div><span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_demonstrates_levels_of_skill_knowledge_employer_view'); ?></span><small class="default_black_regular"><?php echo $feedback_data['employee_demonstrates_levels_of_skill_knowledge'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			<div><span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_dependable_and_reliable_employer_view'); ?></span><small class="default_black_regular"><?php echo $feedback_data['employee_dependable_and_relied'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			<div><span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_properly_organizes_prioritizes_employer_view'); ?></span><small class="default_black_regular"><?php echo $feedback_data['employee_properly_organizes_prioritizes'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			<div class="fP">
				<div class="row">
					<div class="col-md-9 col-sm-9 col-12">
						<h5 class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_demonstrates_effective_oral_verbal_communication_skills_employer_view'); ?></h5>
						<h5 class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_work_quality_employer_view'); ?></h5>
						<h5 class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_self_motivated_employer_view'); ?></h5>
						<h5 class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_working_relations_employer_view'); ?></h5>
						<h5 class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_demonstrates_flexibility_adaptability_employer_view'); ?></h5>
						<h5 class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_solves_problems_employer_view'); ?></h5>
						
						<h5 class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_work_ethic_employer_view'); ?></h5>
						
						
				</div>
					<div class="col-md-3 col-sm-3 col-12">
						<div class="ownerFeedback">
							<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['demonstrates_effective_oral_verbal_communication_skills']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['demonstrates_effective_oral_verbal_communication_skills']; ?></small></span>
						</div>
						<div class="ownerFeedback">
							<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['work_quality']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['work_quality']; ?></small></span>
						</div>
						<div class="ownerFeedback">
							<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['self_motivated']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['self_motivated']; ?></small></span>
						</div>
						<div class="ownerFeedback">
							<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['working_relations']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['working_relations']; ?></small></span>
						</div>
						<div class="ownerFeedback">
							<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['demonstrates_flexibility_adaptability']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['demonstrates_flexibility_adaptability']; ?></small></span>
						</div>
						<div class="ownerFeedback">
							<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['solves_problems']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['solves_problems']; ?></small></span>
						</div>
						<div class="ownerFeedback">
							<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['work_ethic']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['work_ethic']; ?></small></span>
						</div>
					</div>
				</div>
			</div>-->
                        
                        
                        <div class="row">
                            <!-- Left Section Start -->
                            <div class="col-md-5 col-sm-5 col-12 ratingLeft">
                                <div class="ratingDiv">
                                    <div class="lRatingText">
                                        <span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_demonstrates_effective_oral_verbal_communication_skills_employer_view'); ?></span>
                                    </div>
                                    <div class="rRating">
                                        <div class="sRate">
                                            <small><?php echo show_dynamic_rating_stars($feedback_data['demonstrates_effective_oral_verbal_communication_skills']); ?></small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['demonstrates_effective_oral_verbal_communication_skills']; ?></span>
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
                                            <small><?php echo show_dynamic_rating_stars($feedback_data['work_quality']); ?></small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['work_quality']; ?></span>
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
                                            <small><?php echo show_dynamic_rating_stars($feedback_data['self_motivated']); ?></small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['self_motivated']; ?></span>
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
                                            <small><?php echo show_dynamic_rating_stars($feedback_data['working_relations']); ?></small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['working_relations']; ?></span>
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
                                            <small><?php echo show_dynamic_rating_stars($feedback_data['demonstrates_flexibility_adaptability']); ?></small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['demonstrates_flexibility_adaptability']; ?></span>
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
                                            <small><?php echo show_dynamic_rating_stars($feedback_data['solves_problems']); ?></small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['solves_problems']; ?></span>
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
                                            <small><?php echo show_dynamic_rating_stars($feedback_data['work_ethic']); ?></small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['work_ethic']; ?></span>
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
                                        <span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_shows_interest_enthusiasm_for_work_employer_view'); ?></span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['employee_shows_interest_enthusiasm_for_work'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="responseDiv">
                                    <div class="workText">
                                        <span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_demonstrates_competency_in_knowledge_skills_employer_view'); ?></span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['employee_demonstrates_competency_in_knowledge_skills'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="responseDiv">
                                    <div class="workText">
                                        <span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_demonstrates_levels_of_skill_knowledge_employer_view'); ?></span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['employee_demonstrates_levels_of_skill_knowledge'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="responseDiv">
                                    <div class="workText">
                                        <span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_dependable_and_reliable_employer_view'); ?></span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['employee_dependable_and_relied'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="responseDiv">
                                    <div class="workText">
                                        <span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_properly_organizes_prioritizes_employer_view'); ?></span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['employee_properly_organizes_prioritizes'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <!-- Right Section Start -->
                        </div>
                        
			<?php
			}else if($user_id == $sp_id && $view_type == 'sp'){
			?>
			<!--<div><span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_appreciated_right_level_employee_view'); ?></span><small class="default_black_regular"><?php echo $feedback_data['appreciated_right_level'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			<div><span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_empowered_take_extra_responsibilities_employee_view'); ?></span><small class="default_black_regular"><?php echo $feedback_data['empowered_take_extra_responsibilities'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			
			<div><span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_recognition_work_achievements_employee_view'); ?></span><small class="default_black_regular"><?php echo $feedback_data['recognition_work_achievements'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			<div><span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_receive_regular_consistent_feedback_employee_view'); ?></span><small class="default_black_regular"><?php echo $feedback_data['receive_regular_consistent_feedback'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			<div><span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_recommend_this_company_employee_view'); ?></span><small class="default_black_regular"><?php echo $feedback_data['recommend_this_company'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></small></div>
			
			
			<div class="fP">
				<div class="row">
					<div class="col-md-9 col-sm-9 col-12">
						<h5 class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_work_life_balance_feedback_employee_view'); ?></h5>
						<h5 class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_career_opportunities_employee_view'); ?></h5>
						<h5 class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_compensation_benefits_employee_view'); ?></h5>
						<h5 class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_proper_training_support_mentorship_leadership_employee_view'); ?></h5>
						<h5 class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_explained_job_responsibilities_expectation_employee_view'); ?></h5>
						<h5 class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_environment_encourages_expressing_sharing_ideas_innovation_employee_view'); ?></h5>
						<h5 class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_safe_healthy_environment_employee_view'); ?></h5>
						
				</div>
					<div class="col-md-3 col-sm-3 col-12">
						<div class="ownerFeedback">
							<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['work_life_balance']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['work_life_balance']; ?></small></span>
						</div>
						<div class="ownerFeedback">
							<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['career_opportunities']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['career_opportunities']; ?></small></span>
						</div>
						<div class="ownerFeedback">
							<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['compensation_benefits']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['compensation_benefits']; ?></small></span>
						</div>
						<div class="ownerFeedback">
							<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['proper_training_support_mentorship_leadership']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['proper_training_support_mentorship_leadership']; ?></small></span>
						</div>
						<div class="ownerFeedback">
							<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['explained_job_responsibilities_expectation']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['explained_job_responsibilities_expectation']; ?></small></span>
						</div>
						<div class="ownerFeedback">
							<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['environment_encourages_expressing_sharing_ideas_innovation']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['environment_encourages_expressing_sharing_ideas_innovation']; ?></small></span>
						</div>
						<div class="ownerFeedback">
							<span class="sRate"><?php echo show_dynamic_rating_stars($feedback_data['safe_healthy_environment']); ?><small class="default_avatar_review avatar_review_project_owner"><?php echo $feedback_data['safe_healthy_environment']; ?></small></span>
						</div>
					</div>
				</div>
			</div>-->
                        
                        
                        <div class="row">
                            <!-- Left Section Start -->
                            <div class="col-md-5 col-sm-5 col-12 ratingLeft">
                                <div class="ratingDiv">
                                    <div class="lRatingText">
                                        <span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_work_life_balance_feedback_employee_view'); ?></span>
                                    </div>
                                    <div class="rRating">
                                        <div class="sRate">
                                            <small>
                                                <?php echo show_dynamic_rating_stars($feedback_data['work_life_balance']); ?>
                                            </small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['work_life_balance']; ?></span>
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
                                                <?php echo show_dynamic_rating_stars($feedback_data['career_opportunities']); ?>
                                            </small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['career_opportunities']; ?></span>
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
                                                <?php echo show_dynamic_rating_stars($feedback_data['compensation_benefits']); ?>
                                            </small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['compensation_benefits']; ?></span>
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
                                                <?php echo show_dynamic_rating_stars($feedback_data['proper_training_support_mentorship_leadership']); ?>
                                            </small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['proper_training_support_mentorship_leadership']; ?></span>
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
                                                <?php echo show_dynamic_rating_stars($feedback_data['explained_job_responsibilities_expectation']); ?>
                                            </small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['explained_job_responsibilities_expectation']; ?></span>
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
                                                <?php echo show_dynamic_rating_stars($feedback_data['environment_encourages_expressing_sharing_ideas_innovation']); ?>
                                            </small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['environment_encourages_expressing_sharing_ideas_innovation']; ?></span>
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
                                                <?php echo show_dynamic_rating_stars($feedback_data['safe_healthy_environment']); ?>
                                            </small>
                                            <span class="default_avatar_review"><?php echo $feedback_data['safe_healthy_environment']; ?></span>
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
                                        <span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_appreciated_right_level_employee_view'); ?></span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['appreciated_right_level'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="responseDiv">
                                    <div class="workText">
                                        <span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_empowered_take_extra_responsibilities_employee_view'); ?></span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['empowered_take_extra_responsibilities'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="responseDiv">
                                    <div class="workText">
                                        <span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_recognition_work_achievements_employee_view'); ?></span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['recognition_work_achievements'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="responseDiv">
                                    <div class="workText">
                                        <span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_receive_regular_consistent_feedback_employee_view'); ?></span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['receive_regular_consistent_feedback'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="responseDiv">
                                    <div class="workText">
                                        <span class="default_black_bold"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_recommend_this_company_employee_view'); ?></span>
                                    </div>
                                    <div class="responseText">
                                        <span class="default_black_regular"><?php echo $feedback_data['recommend_this_company'] == 'Y' ? $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes') : $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <!-- Right Section Start -->
                        </div>
                        
			<?php
			
			}
		
		}
		
		?>
	
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12">
				<div class="default_user_description feedbackDesc">
					<?php
						//$initial_bid_description = limitStringShowMoreLess($initial_bid_description);
						$descLeng	=	strlen($feedback_description);
						/*----------- description show for desktop screen start----*/
						$desktop_cnt            =	0;
						if($descLeng <= $this->config->item('projects_users_ratings_feedbacks_project_details_page_feedback_description_character_limit_desktop')) {
							$desktop_description	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($feedback_description, ENT_QUOTES)));
						} else {
							$desktop_description	= character_limiter($feedback_description,$this->config->item('projects_users_ratings_feedbacks_project_details_page_feedback_description_character_limit_desktop'));
							$desktop_restdescription	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($feedback_description, ENT_QUOTES)));
							$desktop_cnt = 1;
						}
						/*----------- description show for desktop screen end----*/

						/*----------- description show for ipad screen start----*/
						$tablet_cnt            =	0;
						if($descLeng <= $this->config->item('projects_users_ratings_feedbacks_project_details_page_feedback_description_character_limit_tablet')) {
							$tablet_description	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($feedback_description, ENT_QUOTES)));
						} else {
							$tablet_description	= character_limiter($feedback_description,$this->config->item('projects_users_ratings_feedbacks_project_details_page_feedback_description_character_limit_tablet'));
							$tablet_restdescription	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($feedback_description, ENT_QUOTES)));
							$tablet_cnt = 1;
						}
						/*----------- description show for ipad screen end----*/

						/*----------- description show for mobile screen start----*/
						$mobile_cnt            =	0;
						if($descLeng <= $this->config->item('projects_users_ratings_feedbacks_project_details_page_feedback_description_character_limit_mobile')) {
							$mobile_description	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($feedback_description, ENT_QUOTES)));
						} else {
							$mobile_description	= character_limiter($feedback_description,$this->config->item('projects_users_ratings_feedbacks_project_details_page_feedback_description_character_limit_mobile'));
							$mobile_restdescription	= nl2br(str_replace("  "," &nbsp;",htmlspecialchars($feedback_description, ENT_QUOTES)));
							$mobile_cnt = 1;
						}
						/*----------- description show for mobile screen end----*/
						?>
					<div class="default_user_description desktop-secreen">
						<p id="desktop_lessD<?php echo $section_name.$section_id; ?>">
							<?php echo $desktop_description;?>
							<?php if($desktop_cnt==1) {?>
							<span id="desktop_dotsD<?php echo $section_name.$section_id; ?>"></span>
							<button onclick="showMoreFeedbackDescription('desktop', '<?php echo $section_name.$section_id; ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
							<?php } ?>
						</p>
						<p id="desktop_moreD<?php echo $section_name.$section_id; ?>" class="moreD">
							<?php echo $desktop_restdescription;?>
							<button onclick="showMoreFeedbackDescription('desktop', '<?php echo $section_name.$section_id; ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
						</p>
					</div>
					<div class="default_user_description ipad-screen">
						<p id="tablet_lessD<?php echo $section_name.$section_id; ?>">
							<?php echo $tablet_description;?>
							<?php if($tablet_cnt==1) {?>
							<span id="tablet_dotsD<?php echo $section_name.$section_id; ?>"></span>
							<button onclick="showMoreFeedbackDescription('tablet', '<?php echo $section_name.$section_id; ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
							<?php } ?>
						</p>
						<p id="tablet_moreD<?php echo $section_name.$section_id; ?>" class="moreD">
							<?php echo $tablet_restdescription;?>
							<button onclick="showMoreFeedbackDescription('tablet', '<?php echo $section_name.$section_id; ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
						</p>
					</div>
					<div class="default_user_description mobile-screen">
						<p id="mobile_lessD<?php echo $section_name.$section_id; ?>">
							<?php echo $mobile_description;?>
							<?php if($mobile_cnt==1) {?>
							<span id="mobile_dotsD<?php echo $section_name.$section_id; ?>"></span>
							<button onclick="showMoreFeedbackDescription('mobile', '<?php echo $section_name.$section_id; ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
							<?php } ?>
						</p>
						<p id="mobile_moreD<?php echo $section_name.$section_id; ?>" class="moreD">
							<?php echo $mobile_restdescription;?>
							<button onclick="showMoreFeedbackDescription('mobile', '<?php echo $section_name.$section_id; ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
}	
?>	