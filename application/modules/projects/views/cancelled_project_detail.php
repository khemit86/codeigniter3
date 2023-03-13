<div class="headTopCookies"></div>

<?php //echo $this->load->view('header.php'); 
$user = $this->session->userdata('user');

$hire_me_user_id = $this->session->userdata('hire_me_user_id');
$this->session->unset_userdata('hire_me_user_id');

$CI = & get_instance ();
$CI->load->library('Cryptor');
$payment_method = '';
$location = '';
if($project_data['escrow_payment_method'] == 'Y'){
	$payment_method = $this->config->item('project_details_page_payment_method_escrow_system');
}
if($project_data['offline_payment_method'] == 'Y'){
	$payment_method = $this->config->item('project_details_page_payment_method_offline_system');
}
if(!empty($project_data['county_name']) && !empty($project_data['locality_name']) && !empty($project_data['postal_code'])){
	if(!empty($project_data['locality_name'])){
		$location .= '<span class="locaName">'.$project_data['locality_name'].'</span>';
	}
	if(!empty($project_data['postal_code'])){
		$location .= '<span class="postCode">'.$project_data['postal_code'] .',</span>';
	}else{
		$location .= ',';
	}
	$location .= '<span class="loca">'.$project_data['county_name'].'</span>';
}else if (!empty($project_data['county_name']) && !empty($project_data['locality_name']) && empty($project_data['postal_code'])){
	$location .= '<span class="locaName" style="margin-right:0px;">'.$project_data['locality_name'].'</span>,';
	$location .= '<span class="loca" style="margin-left:5px;">'.$project_data['county_name'].'</span>';

}else if(!empty($project_data['county_name']) && empty($project_data['locality_name']) && empty($project_data['postal_code']))
{
	$location .= '<span class="loca">'.$project_data['county_name'].'</span>';
}


?>
<div class="dashTop">
	<!-- Middle Section Start -->
	<div class="pdLRadjust">
		<div class="row">
			<div class="col-md-10 col-sm-10 col-12 pojDet">
				<!-- Project Details Start -->				
				<div class="default_block_header_transparent nBorder">
					<div class="transparent">
						<?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_details') : $this->config->item('project_details_page_project_details'); ?><span><?php echo $this->config->item('project_status_cancelled'); ?></span><div class="clearfix"></div>
					</div>
					<div class="pDtls transparent_body">
						<h4><?php echo htmlspecialchars(trim($project_data['project_title']), ENT_QUOTES);?></h4>
						<div class="row">
							<div class="col-md-6 col-sm-12 col-12 pDetailsL">
								<div class="pDSheduled">
									<label class="default_black_regular">
										<span class="default_black_bold 1"><i class="fa fa-clock-o" aria-hidden="true"></i><?php echo $this->config->item('project_details_page_cancelled_on') ?></span><?php echo date(DATE_TIME_FORMAT,strtotime($project_data['project_cancellation_date']));?></label><label class="default_black_regular"><span class="default_black_bold"><i class="fa fa-file-text-o" aria-hidden="true"></i><?php 
											echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_project') : $this->config->item('project_details_page_project_type');
											?></span><?php
										if($project_data['project_type'] != 'fulltime'){
											if($project_data['project_type'] == 'fixed'){
												echo $this->config->item('project_details_page_fixed_budget');
											}else if($project_data['project_type'] == 'hourly'){
												echo $this->config->item('project_details_page_hourly_budget');
											}
										}
										?></label><label class="default_black_regular"><span class="default_black_bold"><i class="fa fa-credit-card" aria-hidden="true"></i><?php  echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_salary') : $this->config->item('project_details_page_project_budget'); ?></span><?php
										if($project_data['confidential_dropdown_option_selected'] == 'Y'){
											if($project_data['project_type'] == 'fixed'){
												echo $this->config->item('displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected');
												}else if($project_data['project_type'] == 'hourly'){
												echo $this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_confidential_option_selected');
											}else if($project_data['project_type'] == 'fulltime'){
												echo $this->config->item('displayed_text_fulltime_project_details_page_salary_confidential_option_selected');
											}
										}else if($project_data['not_sure_dropdown_option_selected'] == 'Y'){
											if($project_data['project_type'] == 'fixed'){
											echo $this->config->item('displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected');
											}else if($project_data['project_type'] == 'hourly'){
												echo $this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_not_sure_option_selected');
											}else if($project_data['project_type'] == 'fulltime'){
												echo $this->config->item('displayed_text_fulltime_project_details_page_salary_not_sure_option_selected');
											}
										}else{
											if($project_data['max_budget'] != 'All'){
												if($project_data['project_type'] == 'hourly'){
												
													$budget_range = '';
													/* if($this->config->item('post_project_budget_range_between')){
														$budget_range .= $this->config->item('post_project_budget_range_between').'&nbsp;';
													} */
													$budget_range .= '<span class="word_set">'.number_format($project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span><span class="word_set">'. $this->config->item('post_project_budget_range_and').'</span><span class="word_set">'.number_format($project_data['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour').'</span>';
												
												
												
												}else if($project_data['project_type'] == 'fulltime'){
												
													$budget_range = '';
													/* if($this->config->item('post_project_budget_range_between')){
														$budget_range .= $this->config->item('post_project_budget_range_between').'&nbsp;';
													} */
													$budget_range .= '<span class="word_set">'.number_format($project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span><span class="word_set">'. $this->config->item('post_project_budget_range_and').'</span><span class="word_set">'.number_format($project_data['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_month').'</span>';
												
												
												}else{
													$budget_range = '';
													/* if($this->config->item('post_project_budget_range_between')){
														$budget_range .= $this->config->item('post_project_budget_range_between').'&nbsp;';
													} */
													$budget_range .= '<span class="word_set">'.number_format($project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'</span><span class="word_set">'. $this->config->item('post_project_budget_range_and').'</span><span class="word_set">'.number_format($project_data['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
												}
											}else{
												if($project_data['project_type'] == 'hourly'){
													$budget_range = $this->config->item('post_project_budget_range_more_then').'&nbsp'.number_format($project_data['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_hour');
												}else if($project_data['project_type'] == 'fulltime'){
													$budget_range = $this->config->item('post_project_budget_range_more_then').'&nbsp'.number_format($project_data['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_month');
												}else{
													$budget_range = $this->config->item('post_project_budget_range_more_then').'&nbsp'.number_format($project_data['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY;
												}
											}
										}
										?><?php echo $budget_range; ?></label><?php
									if(!empty($payment_method)){
									?><label class="default_black_regular">
										<span class="default_black_bold"><i class="fa fa-credit-card" aria-hidden="true"></i><?php echo $this->config->item('project_details_page_payment_method') ?></span><?php echo $payment_method; ?></label>
									<?php
									}
									?>
									<?php
									if(!empty($location)){
									?><label class="default_black_regular"><span class="default_black_bold"><i class="fa fa-map-marker" aria-hidden="true"></i><?php echo $this->config->item('project_details_page_location'); ?></span><?php echo $location; ?></label>
									<?php
									}
									?>
									<?php
									if($project_data['sealed'] == 'N' || ($this->session->userdata ('user') &&  ($user[0]->user_id == $project_data['project_owner_id']))){
									?><label class="default_black_regular"><span class="default_black_bold"><i class="fa fa-bullhorn" aria-hidden="true"></i><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_bid_history') : $this->config->item('project_details_page_project_bid_history'); ?></span><small class="default_black_regular" id="project_bid_count_history"><?php echo $project_bid_count;?></small></label>
									<?php
									}
									?>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="col-md-6 col-sm-12 col-12 pDetailsR <?php if(empty($project_category_data)){ echo 'd-none'; }?>">
								<div class="pProject pBNone default_project_category">
									<?php
									if(!empty($project_category_data)){
										foreach($project_category_data as $category_key=>$category_value){
											if(!empty($category_value['parent_category_name']) && !empty($category_value['category_name'])){
									?>
												<div class="clearfix">
													<small class="pSmnu cat" data-id="<?php echo $category_value['p_id']; ?>"><?php echo $category_value['parent_category_name']; ?></small>
													<a >
														<span class="cat" data-id="<?php echo $category_value['c_id']; ?>"><?php echo $category_value['category_name']; ?></span>
													</a>
												</div>
											
									<?php
											}else{
												echo '<small class="cat" data-id="'.$category_value['c_id'].'">'.$category_value['category_name'].'</small>'; 
											}
										}
									}
									?>
								</div>
							</div>
						</div>
						<div class="pDBttm <?php if($project_data['hidden'] == 'Y'){echo 'noSocialMedia'; }?>">
							<div class="badgeAction">
								<?php
								if($project_data['hidden'] != 'Y'){
								?>
								<label class="projectSocial">
									<div class="default_project_socialicon">
										<a href="" class="fb_share_project" data-link="<?php echo $fb_share_url;?>"><i class="fa fa-facebook"></i></a>
										<a href="" class="twitter_share_project" data-link="<?php echo $twitter_share_url;?>" data-title="<?php echo htmlspecialchars($project_data['project_title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit(htmlspecialchars($project_data['project_description'], ENT_QUOTES), $this->config->item('twitter_share_project_description_character_limit'));?>"><i class="fa fa-twitter"></i></a>
										<a href="" class="ln_share_project" data-link="<?php echo $ln_share_url;?>" data-title="<?php echo htmlspecialchars($project_data['project_title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit(htmlspecialchars($project_data['project_description'], ENT_QUOTES), $this->config->item('facebook_and_linkedin_share_project_description_character_limit'));?>"><i class="fa fa-linkedin"></i></a>
										<a href="" class="email_share_project" data-link="<?php echo $email_share_url;?>" data-title="<?php echo htmlspecialchars($project_data['project_title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(nl2br($project_data['project_description']), ENT_QUOTES)), $this->config->item('project_details_page_email_share_project_description_character_limit'));?>"><i class="fas fa-envelope"></i></a>
									</div>
								</label>
								<?php
								}
								?>
								<label class="badgeOnly">							
									<div class="default_project_badge">
										<?php
										if($project_data['sealed'] == 'Y'){
											echo '<button type="button" class="btn badge_sealed">'.$this->config->item('post_project_page_upgrade_type_sealed').'</button>';
										}
										if($project_data['hidden'] == 'Y'){
											echo '<button type="button" class="btn badge_hidden">'.$this->config->item('post_project_page_upgrade_type_hidden').'</button>';
										}
										?>
									</div>
								</label>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
				<!-- Project Details End -->
				<?php 
					$is_po = false;
					if($this->session->userdata ('user') && $user[0]->user_id == $project_data['project_owner_id']) {
						$is_po = true;
					}
				?>
				<!-- Listing Id,History,Revisions and Report Violation Start -->
				<div class="pDLId">
					<div class="row">
						<div class="<?php echo $is_po ? 'col-md-12 col-sm-12 col-12' : 'col-md-9 col-sm-9 col-12'  ?> lidL">
							<label>
								<b><?php echo $this->config->item('project_details_page_listing_id'); ?></b>
								<span><?php echo $project_data['project_id']; ?></span>
							</label><label>
								<b><?php echo $this->config->item('project_details_page_history'); ?></b>
								<span><?php echo $page_visits; ?> <?php echo $this->config->item('project_details_page_views'); ?></span>
							</label><label>
								<b><?php echo $this->config->item('project_details_page_revisions'); ?></b>
								<span><?php echo $project_data['revisions']; ?></span>
							</label>
						</div>
						<?php 
						if($this->session->userdata ('user') && $user[0]->user_id != $project_data['project_owner_id']){
						?>
						<div class="col-md-3 col-sm-3 col-12 vReport">
							<label class="violation_report_popup"><?php echo $this->config->item('project_details_page_report_violation'); ?></label>
						</div>
						<?php } ?>
					</div>
				</div>
				<!-- Listing Id,History,Revisions and Report Violation End -->
				
				
				<!-- Description Start -->
				<div class="default_block_header_transparent nBorder margin_top20">
					<div class="transparent">
						<?php
						echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_project_description') : $this->config->item('project_details_page_project_description');?><div class="clearfix"></div>
					</div>
					<div class="proDn transparent_body">
						<div class="proPart">
							<p class="line-break"><?php echo convert_url_to_anchor(nl2br(str_replace("  "," &nbsp;",htmlspecialchars($project_data['project_description'], ENT_QUOTES)))); ?></p>
						</div>
								<?php
								if(!empty($project_attachment_data)){
									$attachMultiple='';
									if(!empty($project_tag_data)){
										$attachMultiple=' attachMultiple';
									}
								?>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-12 aNowBorder attachHr">
									<hr/>
									<div class="pDAttach<?php echo $attachMultiple; ?>">
										<?php
										foreach($project_attachment_data as $project_attachment_key=>$project_attachment_value){
											$attachment_id = Cryptor::doEncrypt($project_attachment_value['id']);
											echo '<label class="attachFile" id="af'.($project_attachment_key+1).'"><span><a href="javascript:void(0);" class="download_attachment download_project_attachment" data-attr="'.$attachment_id.'"><i class="fas fa-paperclip"></i>'.$project_attachment_value['project_attachment_name'].'</a></span></label>';
										}
										?>
									</div>
								</div>		
							</div>
							<?php
							}
							?>
							
										<?php if(!empty($project_tag_data)){?>
                                            <div class="row">
								<div class="col-md-12 col-sm-12 col-12 aNowBorder">
									<hr/>
									<div class="portTags">
										<div class="smallTag"><?php
											foreach($project_tag_data as $project_tag_key=>$project_tag_value){
												echo '<label class="defaultTag"><span class="tagFirst">'.$project_tag_value['project_tag_name'].'</span></label>';
											}?></div>
                                                                            </div>
								</div>
							</div>
                                                                                            
                                                                                            <?php } ?>
									
					</div>
				</div>
				<!-- Description End -->
				<?php
				if(!empty($project_additional_information_data)){
				?>
				<!-- Additional Information Start -->
				<div class="default_block_header_transparent nBorder margin_top20">
					<div class="transparent">
						<?php echo $this->config->item('project_details_page_additional_information'); ?><div class="clearfix"></div>
					</div>
					<?php
					foreach($project_additional_information_data as $additional_information_key=>$additional_information_value){
					?>
						<div class="pAI transparent_body">
							<h6>
								<i class="fa fa-clock-o" aria-hidden="true"></i><?php echo $this->config->item('project_details_page_updated_on'); ?><span> <?php echo date(DATE_TIME_FORMAT,strtotime($additional_information_value['additional_information_add_date'])); ?></span>
							</h6>
							<p class="line-break addiInform"><?php echo nl2br(str_replace("  "," &nbsp;",htmlspecialchars($additional_information_value['additional_information'], ENT_QUOTES))); ?></p>
						</div>
					<?php
					}
					?>
				</div>
				<!-- Additional Information End -->
				<?php
				}
				?>
				<?php
				if(!$this->session->userdata ('user') || ($this->session->userdata ('user') && $user[0]->user_id != $project_data['project_owner_id']) ){
				
				$add_margin_sealed_disclaimer_msg_style = '';
				if(($project_data['sealed'] == 'Y' && (!$this->session->userdata ('user'))) || ($project_data['sealed'] == 'Y' && ($this->session->userdata ('user') && $user[0]->user_id != $project_data['project_owner_id']))){
					$add_margin_sealed_disclaimer_msg_style = "margin:0;";
				}
				
				?>
				
				
				<!--Start new design for project owner details-->
				<div class="default_block_header_transparent nBorder margin_top20 projectOwner_only" style="<?php echo $add_margin_sealed_disclaimer_msg_style; ?>">
					<div class="transparent">
						<?php 
						echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_employer_details') : $this->config->item('project_details_page_project_owner_details');?><div class="clearfix"></div>
					</div>
					<div class="newPOwner transparent_body">
						<div class="default_user_avatar_left_adjust user_avatar_project_owner">
							<div class="imgTxtR">		
								<div class="avtOnly">
									<?php
									if(!empty($project_data['user_avatar']) ) {
									$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$project_data['profile_name'].USER_AVATAR.$project_data['user_avatar'];
									} else {
										if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] =='Y')){
											if($project_data['gender'] == 'M'){
												$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
											}if($project_data['gender'] == 'F'){
											   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
											}
										} else {
											$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
										}
									}
									?>
									<div class="default_avatar_image avatar_image_size_project_owner" style="background-image: url('<?php echo $user_profile_picture; ?>');">
									</div>
								</div>
								<!-- <label class="sRate poSRate">
									<small class="default_avatar_review avatar_review_project_owner"><?php echo $po_rating; ?></small>
									<span><?php //echo show_dynamic_rating_stars($po_rating,'small'); ?></span>
								</label> -->
							</div>
						</div>
						<div class="default_user_details_right_adjust user_details_right_adjust_project_owner">
							<div class="row user_details_right_adjust_row">
								<div class="col-md-12 col-sm-12 col-12">
									<div class="pdbottom_border pdInline pdInline_desktop">
										<p class="default_user_name default_continue_text">
											<a class="default_user_name_link" href="<?php echo site_url ($project_data['profile_name']); ?>" target="_blank"><?php
											$name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] =='Y')) ? $project_data['first_name'] . ' ' . $project_data['last_name'] : $project_data['company_name'];
											echo $name;
											?></a>
										</p>
										<p class="default_short_details_field rightSeparator">
											<label class="sRate poSRate">
												<span><?php echo show_dynamic_rating_stars($po_rating,'small'); ?></span>
												<small class="default_avatar_review avatar_review_project_owner"><?php echo $po_rating; ?></small>
												<small class="ratingDetails"><!-- <i class="far fa-star"></i> --><?php
												if($po_reviews == 0){
													$trGiven = number_format($po_reviews,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
												}else if($po_reviews == 1) {
													$trGiven = number_format($po_reviews,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
												} else if($po_reviews > 1) {
													$trGiven = number_format($po_reviews,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
												}
												echo $trGiven;?></small>
											</label>
											<label class="pdOther_details">
												<small><!-- <i class="fas fa-clipboard"></i> --><?php
												if($project_data['project_type'] == 'fulltime'){
													echo $this->config->item('project_details_page_total_fulltime_projects_published')." ".$po_published_projects;
												}else{
													echo $this->config->item('project_details_page_total_projects_published')." ".$po_published_projects;
												}?></small>
												<small><!-- <i class="fas fa-clipboard-check"></i> --><?php
												if($project_data['project_type'] == 'fulltime'){
													echo $this->config->item('project_details_page_total_hires_on_fulltime_projects_via_portal')." ".$get_po_hires_sp_on_fulltime_projects_count;
												}else{
													echo $this->config->item('project_details_page_total_completed_projects_via_portal')." ".$po_completed_projects_count_via_portal;
												}?></small>
											</label>
										</p>
									</div>
									<!-- Mobile Version Start -->
									<div class="pdbottom_border pdInline pdInline_tab">										
										<p class="default_short_details_field rightSeparator">
											<label class="sRate poSRate">
												<span><?php echo show_dynamic_rating_stars($po_rating,'small'); ?></span>
												<small class="default_avatar_review avatar_review_project_owner"><?php echo $po_rating; ?></small>
												<small class="ratingDetails"><!-- <i class="far fa-star"></i> --><?php
												if($po_reviews == 0){
													$trGiven = number_format($po_reviews,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
												}else if($po_reviews == 1) {
													$trGiven = number_format($po_reviews,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
												} else if($po_reviews > 1) {
													$trGiven = number_format($po_reviews,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
												}
												echo $trGiven;?></small>
											</label>
											<label class="pdOther_details">
												<small><!-- <i class="fas fa-clipboard"></i> --><?php
												if($project_data['project_type'] == 'fulltime'){
													echo $this->config->item('project_details_page_total_fulltime_projects_published')." ".$po_published_projects;
												}else{
													echo $this->config->item('project_details_page_total_projects_published')." ".$po_published_projects;
												}?></small>
												<small><!-- <i class="fas fa-clipboard-check"></i> --><?php
												if($project_data['project_type'] == 'fulltime'){
													echo $this->config->item('project_details_page_total_hires_on_fulltime_projects_via_portal')." ".$get_po_hires_sp_on_fulltime_projects_count;
												}else{
													echo $this->config->item('project_details_page_total_completed_projects_via_portal')." ".$po_completed_projects_count_via_portal;
												}?></small>
											</label>
										</p>
										<p class="default_user_name default_continue_text">
											<a class="default_user_name_link" href="<?php echo site_url ($project_data['profile_name']); ?>" target="_blank"><?php
											$name = $project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $project_data['first_name'] . ' ' . $project_data['last_name'] : $project_data['company_name'];
											echo $name;
											?></a>
										</p>
									</div>
									<!-- Mobile Version End -->
								</div>
							</div>	
							<div class="row bellShow">
								<div class="col-md-9 col-sm-9 col-9 padding_right0 pdContent">
									<?php
										if($this->session->userdata ('user')  &&  $user[0]->user_id != $project_data['project_owner_id']){ 
											$subscription_limit = 0;
											if($users_plan['current_membership_plan_id'] == 1) {
													$subscription_limit = $this->config->item('free_subscribers_max_number_of_favorite_employers_subscriptions');
											} else if($users_plan['current_membership_plan_id'] == 4) {
													$subscription_limit = $this->config->item('gold_subscribers_max_number_of_favorite_employers_subscriptions');
											}
											$employers = [];
											$bell_clicked = false;
											$is_bell_visible = false;
											if(!empty($favorite_employer_list)) {
													$employers = array_column($favorite_employer_list, 'favorite_employer_id');
													if(in_array($project_data['project_owner_id'], $employers)) {
															$bell_clicked = true;
													}
													if(count($favorite_employer_list) >= $subscription_limit && $bell_clicked) {
															$is_bell_visible = true;
													}
											}
											if(count($favorite_employer_list) >= $subscription_limit ) {
													$display_bell = true;
										?>
										<?php
									/* 	<div class="empDtls poBellIcon" style="display:<?php echo $is_bell_visible ? 'block' : 'none';?>">
											<a id="bell_clicked">
												<i class="fas fa-bell <?php echo $bell_clicked ? 'bell_clicked' : '' ?>" aria-hidden="true" data-onwer-id="<?php echo $project_data['project_owner_id'];?>" data-placement="top" data-toggle="tooltip" data-original-title="" title="<?php echo $bell_clicked ? $project_details_page_tooltip_unsubscribe_to_po_new_projects_posted_notifications_txt :  $project_details_page_tooltip_subscribe_to_po_new_projects_posted_notifications_txt; ?>"></i>
											</a>	
										</div> */
										?>
										<div class="empDtls poBellIcon" style="display:<?php echo $is_bell_visible ? 'inline-flex' : 'none';?>">
											<a id="bell_clicked" style="cursor:pointer">
												<i class="fas icon_size fa-bell tooltipAuto <?php echo $bell_clicked ? 'bell_clicked' : '' ?>" aria-hidden="true" data-onwer-id="<?php echo $project_data['project_owner_id'];?>" data-placement="top" data-toggle="tooltip" data-original-title="" title="<?php echo $bell_clicked ? $project_details_page_tooltip_unsubscribe_to_po_new_projects_posted_notifications_txt :  $project_details_page_tooltip_subscribe_to_po_new_projects_posted_notifications_txt; ?>"></i>
											</a>	
										</div>
										
										
										<?php
											} else {
										?>
										<?php
									/* 	<div class="empDtls poBellIcon">
											<a id="bell_clicked" style="cursor:pointer">
												<i class="fas fa-bell icon_size <?php echo $bell_clicked ? 'bell_clicked' : '' ?>" aria-hidden="true" data-onwer-id="<?php echo $project_data['project_owner_id'];?>" data-placement="top" data-toggle="tooltip" data-original-title="" title="<?php echo $bell_clicked ? $project_details_page_tooltip_unsubscribe_to_po_new_projects_posted_notifications_txt :  $project_details_page_tooltip_subscribe_to_po_new_projects_posted_notifications_txt; ?>"></i>
											</a>	
										</div> */
										?>
											<div class="empDtls poBellIcon">
												<a id="bell_clicked" style="cursor:pointer">
													<i class="fas fa-bell icon_size tooltipAuto <?php echo $bell_clicked ? 'bell_clicked' : '' ?>" aria-hidden="true" data-onwer-id="<?php echo $project_data['project_owner_id'];?>" data-placement="top" data-toggle="tooltip" data-original-title="" title="<?php echo $bell_clicked ? $project_details_page_tooltip_unsubscribe_to_po_new_projects_posted_notifications_txt :  $project_details_page_tooltip_subscribe_to_po_new_projects_posted_notifications_txt; ?>"></i>
												</a>	
											</div>
										<?php
											}
										}
										?>
								</div>
								<div class="col-md-3 col-sm-3 col-3 text-right pdContactBtn">
									<?php
										if($this->session->userdata('user') && isset($is_general_chat) && !$is_general_chat) {
									?>
									<button  class="btn default_btn blue_btn w-100 contact-bidder"
									data-name="<?php echo $name; ?>"
									data-id="<?php echo $project_data['project_owner_id']; ?>"
									data-project-title="<?php echo htmlspecialchars($project_data['project_title'], ENT_QUOTES); ?>"
									data-project-id="<?php echo $project_data['project_id']; ?>"
									data-profile="<?php echo $po_profile_pic_url; ?>"
									data-project-owner="<?php echo $project_data['project_owner_id']; ?>"
									<?php echo (isset($is_in_project_contact) && $is_in_project_contact) ? 'data-project-contact=""' : '' ;?>
									><?php echo $this->config->item('contactme_button'); ?></button>					
									<?php
										} else {
											if($this->session->userdata('user') && $is_general_chat) {
									?>
									<button  class="btn default_btn blue_btn w-100 contact-bidder"
									data-name="<?php echo $name; ?>"
									data-id="<?php echo $project_data['project_owner_id']; ?>"
									data-project-title=""
									data-project-id=""
									data-profile="<?php echo $po_profile_pic_url; ?>"
									data-project-owner=""
									><?php echo $this->config->item('contactme_button'); ?></button>
									<?php
											} else if($this->session->userdata('user')) {
									?>
									<button class="btn default_btn blue_btn w-100" id="contactMe" <?php echo (isset($user_already_place_bid) && $user_already_place_bid) ? 'data-user-already-place-bid=""' : ''; ?> data-profile-name="<?php echo $project_data['profile_name'] ?>" data-gender="<?php echo $project_data['gender'] ?>" data-id="<?php echo $project_data['project_owner_id']; ?>" data-name="<?php echo $name; ?>"><?php echo $this->config->item('contactme_button'); ?></button>
									<?php
											} else {
									?>
									<button class="btn default_btn blue_btn w-100 login_popup" data-id="<?php echo $project_data['project_owner_id']; ?>" data-page-id-attr="<?php echo $project_data['project_id']; ?>" data-page-type-attr="<?php echo $current_page; ?>"><?php echo $this->config->item('contactme_button'); ?></button>
									<?php
											}
										}
									?>
								</div>
							</div>	
						</div>
						<div class="pdmobile_view">
							<!-- <p class="default_user_name default_continue_text">
								<a class="default_user_name_link" href="<?php //echo site_url ($project_data['profile_name']); ?>" target="_blank"><?php //echo $po_name; ?></a>
							</p> -->
							<div class="pdOther_detailsMob">
								<div class="default_short_details_field">
									<small><!-- <i class="fas fa-clipboard"></i> --><?php
									if($project_data['project_type'] == 'fulltime'){
										echo $this->config->item('project_details_page_total_fulltime_projects_published')." ".$po_published_projects;
									}else{
										echo $this->config->item('project_details_page_total_projects_published')." ".$po_published_projects;
									}?></small>
									<small><!-- <i class="fas fa-clipboard-check"></i> --><?php
									if($project_data['project_type'] == 'fulltime'){
										echo $this->config->item('project_details_page_total_hires_on_fulltime_projects_via_portal')." ".$get_po_hires_sp_on_fulltime_projects_count;
									}else{
										echo $this->config->item('project_details_page_total_completed_projects_via_portal')." ".$po_completed_projects_count_via_portal;
									}?></small>
								</div>
							</div>
							<div class="bellHide">
								<div class="row">
									<div class="col-md-9 col-sm-9 col-9 padding_right0 pdContent">
										<?php
											if($this->session->userdata ('user')  &&  $user[0]->user_id != $project_data['project_owner_id']){ 
												$subscription_limit = 0;
												if($users_plan['current_membership_plan_id'] == 1) {
														$subscription_limit = $this->config->item('free_subscribers_max_number_of_favorite_employers_subscriptions');
												} else if($users_plan['current_membership_plan_id'] == 4) {
														$subscription_limit = $this->config->item('gold_subscribers_max_number_of_favorite_employers_subscriptions');
												}
												$employers = [];
												$bell_clicked = false;
												$is_bell_visible = false;
												if(!empty($favorite_employer_list)) {
														$employers = array_column($favorite_employer_list, 'favorite_employer_id');
														if(in_array($project_data['project_owner_id'], $employers)) {
																$bell_clicked = true;
														}
														if(count($favorite_employer_list) >= $subscription_limit && $bell_clicked) {
																$is_bell_visible = true;
														}
												}
												if(count($favorite_employer_list) >= $subscription_limit ) {
														$display_bell = true;
											?>
											<?php
										/* 	<div class="empDtls poBellIcon" style="display:<?php echo $is_bell_visible ? 'block' : 'none';?>">
												<a id="bell_clicked">
													<i class="fas fa-bell <?php echo $bell_clicked ? 'bell_clicked' : '' ?>" aria-hidden="true" data-onwer-id="<?php echo $project_data['project_owner_id'];?>" data-placement="top" data-toggle="tooltip" data-original-title="" title="<?php echo $bell_clicked ? $project_details_page_tooltip_unsubscribe_to_po_new_projects_posted_notifications_txt :  $project_details_page_tooltip_subscribe_to_po_new_projects_posted_notifications_txt; ?>"></i>
												</a>	
											</div> */
											?>
											<div class="empDtls poBellIcon" style="display:<?php echo $is_bell_visible ? 'inline-flex' : 'none';?>">
												<a id="bell_clicked" style="cursor:pointer">
													<i class="fas fa-bell icon_size tooltipAuto <?php echo $bell_clicked ? 'bell_clicked' : '' ?>" aria-hidden="true" data-onwer-id="<?php echo $project_data['project_owner_id'];?>" data-placement="top" data-toggle="tooltip" data-original-title="" title="<?php echo $bell_clicked ? $project_details_page_tooltip_unsubscribe_to_po_new_projects_posted_notifications_txt :  $project_details_page_tooltip_subscribe_to_po_new_projects_posted_notifications_txt; ?>"></i>
												</a>	
											</div>
											
											
											<?php
												} else {
											?>
											<?php
										/* 	<div class="empDtls poBellIcon">
												<a id="bell_clicked" style="cursor:pointer">
													<i class="fas fa-bell icon_size <?php echo $bell_clicked ? 'bell_clicked' : '' ?>" aria-hidden="true" data-onwer-id="<?php echo $project_data['project_owner_id'];?>" data-placement="top" data-toggle="tooltip" data-original-title="" title="<?php echo $bell_clicked ? $project_details_page_tooltip_unsubscribe_to_po_new_projects_posted_notifications_txt :  $project_details_page_tooltip_subscribe_to_po_new_projects_posted_notifications_txt; ?>"></i>
												</a>	
											</div> */
											?>
												<div class="empDtls poBellIcon">
													<a id="bell_clicked" style="cursor:pointer">
														<i class="fas fa-bell icon_size tooltipAuto <?php echo $bell_clicked ? 'bell_clicked' : '' ?>" aria-hidden="true" data-onwer-id="<?php echo $project_data['project_owner_id'];?>" data-placement="top" data-toggle="tooltip" data-original-title="" title="<?php echo $bell_clicked ? $project_details_page_tooltip_unsubscribe_to_po_new_projects_posted_notifications_txt :  $project_details_page_tooltip_subscribe_to_po_new_projects_posted_notifications_txt; ?>"></i>
													</a>	
												</div>
											<?php
												}
											}
											?>
									</div>
									<div class="col-md-3 col-sm-3 col-3 text-right pdContactBtn">
										<?php
											if($this->session->userdata('user') && isset($is_general_chat) && !$is_general_chat) {
										?>
										<button  class="btn default_btn blue_btn w-100 contact-bidder"
										data-name="<?php echo $name; ?>"
										data-id="<?php echo $project_data['project_owner_id']; ?>"
										data-project-title="<?php echo htmlspecialchars($project_data['project_title'], ENT_QUOTES); ?>"
										data-project-id="<?php echo $project_data['project_id']; ?>"
										data-profile="<?php echo $po_profile_pic_url; ?>"
										data-project-owner="<?php echo $project_data['project_owner_id']; ?>"
										<?php echo (isset($is_in_project_contact) && $is_in_project_contact) ? 'data-project-contact=""' : '' ;?>
										><?php echo $this->config->item('contactme_button'); ?></button>					
										<?php
											} else {
												if($this->session->userdata('user') && $is_general_chat) {
										?>
										<button  class="btn default_btn blue_btn w-100 contact-bidder"
										data-name="<?php echo $name; ?>"
										data-id="<?php echo $project_data['project_owner_id']; ?>"
										data-project-title=""
										data-project-id=""
										data-profile="<?php echo $po_profile_pic_url; ?>"
										data-project-owner=""
										><?php echo $this->config->item('contactme_button'); ?></button>
										<?php
												} else if($this->session->userdata('user')) {
										?>
										<button class="btn default_btn blue_btn w-100" id="contactMe" <?php echo (isset($user_already_place_bid) && $user_already_place_bid) ? 'data-user-already-place-bid=""' : ''; ?> data-profile-name="<?php echo $project_data['profile_name'] ?>" data-gender="<?php echo $project_data['gender'] ?>" data-id="<?php echo $project_data['project_owner_id']; ?>" data-name="<?php echo $name; ?>"><?php echo $this->config->item('contactme_button'); ?></button>
										<?php
												} else {
										?>
										<button class="btn default_btn blue_btn w-100 login_popup" data-id="<?php echo $project_data['project_owner_id']; ?>" data-page-id-attr="<?php echo $project_data['project_id']; ?>" data-page-type-attr="<?php echo $current_page; ?>"><?php echo $this->config->item('contactme_button'); ?></button>
										<?php
												}
											}
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>					
				</div>
				
				<!--End new design for project owner details-->
				<?php
				}
				if(($project_data['sealed'] == 'Y' && (!$this->session->userdata ('user'))) || ($project_data['sealed'] == 'Y' && ($this->session->userdata ('user') && $user[0]->user_id != $project_data['project_owner_id']))){
				?>
				<div class="pDLId">
				<div class="row"><div class="col-md-12 col-sm-12 col-12 lidL"><label><strong class="ftNormal"><?php 
				echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_project_sealed_disclaimer_message') : $this->config->item('project_details_page_project_sealed_disclaimer_message');?></strong></label></div></div>
				</div>
				<?php
				}
				?>

				<!-- start Section for dispute section for specific SP view to see his dsipute tab !-->
				<?php
				$show_sp_active_dispute_bidder_css = "display:none;";
				if($this->session->userdata ('user') &&  $check_sp_disputed_bid_exists > 0 ){
					$show_sp_active_dispute_bidder_css = "display:block;";
				}
				?>
				
				<div class="default_block_header_transparent active_dispute_bids nBorder margin_top20" style="<?php echo $show_sp_active_dispute_bidder_css; ?>" id="sp_active_dispute_bid_container">
					<div class="transparent" id="sp_incomplete_bid_container_heading"><?php 
					echo $project_data['project_type'] == 'fulltime' ? $this->config->item('fulltime_project_details_page_active_dispute_tab_employee_view_txt') : $this->config->item('project_details_page_active_dispute_tab_sp_view_txt');?></div>
					<div class="bidFree active_dispute_bidder_list transparent_body" id="sp_active_dispute_bid">
					<?php
					if($this->session->userdata ('user') &&  $check_sp_disputed_bid_exists >0 ){
					$incomplete_bidder_data['project_owner_name'] = $po_name;
					echo $this->load->view('projects_disputes/project_active_disputes_listing_project_details', $incomplete_bidder_data, true);
					}
					?>
					</div>
				</div>
			    <!-- In php End -->

				<!-- start Section for in progress section for specific SP view to see his in progress tab !-->
				<?php
				$show_sp_inprogress_bidder_css = "display:none;";
				if($this->session->userdata ('user') &&  $check_sp_in_progress_bid_exists > 0 ){
					$show_sp_inprogress_bidder_css = "display:block;";
				}
				?>
					<div class="default_block_header_transparent inprogress_bids nBorder margin_top20" style="<?php echo $show_sp_inprogress_bidder_css; ?>" id="sp_inprogress_bid_container">
						<div class="transparent" id="sp_inprogress_bid_container_heading"><?php 
						echo $project_data['project_type'] == 'fulltime' ? $this->config->item('fulltime_project_details_page_hired_fulltime_project_tab_employee_view_txt') : $this->config->item('project_details_page_inprogress_project_tab_sp_view_txt');?></div>
						<div class="bidFree inprogress_bidder_list transparent_body" id="sp_inprogress_bid">
						<?php
						if($this->session->userdata ('user') &&  $check_sp_in_progress_bid_exists >0 ){
							$inprogress_bidder_data['project_owner_name'] = $po_name;
							echo $this->load->view('bidding/project_inprogress_bidders_listing', $inprogress_bidder_data, true);
						}
						?>
						</div>
					</div>
				<!-- In Progress End -->

				<!-- start Section for awarded section for specific SP view to see his awarded tab !-->
				<?php
				if($this->session->userdata ('user') &&  $check_sp_awarded_bid_exists >0 ){
				?>
				<div class="default_block_header_transparent awarded_bids nBorder margin_top20"  id="sp_awarded_bid_container">
					<div class="transparent">
						<?php 
							echo $project_data['project_type'] == 'fulltime' ? $this->config->item('fulltime_project_details_page_inprogress_awaiting_acceptance_fulltime_project_tab_employee_view_txt') : $this->config->item('project_details_page_awaiting_acceptance_project_tab_sp_view_txt');?><div class="clearfix"></div>
					</div>
					<div class="bidFree transparent_body" id="sp_awarded_bid">
						<?php
					
						echo $this->load->view('bidding/project_awarded_bidders_listing', $awarded_bidder_data, true);
						?>
					</div>
				</div>				
				<?php
				}		
				?>
				<!-- end -->

				<!-- Active dispute Start -->
				<?php
				
				$show_active_dispute_bidder_list_css = "display:none;";
					if((!$this->session->userdata ('user') && $project_data['sealed'] == 'N') || ($this->session->userdata ('user') && $user[0]->user_id == $project_data['project_owner_id']) || ($this->session->userdata ('user') && $user[0]->user_id != $project_data['project_owner_id'] && $project_data['sealed'] == 'N')){
					$show_active_dispute_bidder_list_css = "display:block;";
						if(empty($project_active_disputes_listing)){
							$show_active_dispute_bidder_list_css = "display:none;";
						}
					
					}
					
				?>
				<div class="default_block_header_transparent active_dispute_bids nBorder margin_top20" style="<?php echo $show_active_dispute_bidder_list_css; ?>" id="po_active_dispute_bid_container">
					<div class="transparent"><?php
							if($project_data['project_type']== 'fulltime'){
								if(count($project_active_disputes_listing) == 1){
									$active_disputed_bidders_list_heading = $this->config->item('fulltime_project_details_page_active_disputed_applications_list_singular');
								}
								else if(count($project_active_disputes_listing) > 1){
									$active_disputed_bidders_list_heading = $this->config->item('fulltime_project_details_page_active_disputed_applications_list_plural');
								}	
							}else{
								if(count($project_active_disputes_listing) == 1){
									$active_disputed_bidders_list_heading = $this->config->item('project_details_page_project_active_disputed_bids_list_singular');
								}
								else if(count($project_active_disputes_listing) > 1){
									$active_disputed_bidders_list_heading = $this->config->item('project_details_page_project_active_disputed_bids_list_plural');
								}
							}
							echo $active_disputed_bidders_list_heading;?><div class="clearfix"></div></div>
					<div class="bidFree active_dispute_bidder_list transparent_body" id="active_dispute_bidder_list">
						<?php
						
						if(!empty($project_active_disputes_listing)){
							foreach($project_active_disputes_listing  as $active_dispute_bidder_key=>$active_dispute_bidder_value){
								$active_dispute_bidder_data['active_dispute_bidder_data'] = $active_dispute_bidder_value;
								$active_dispute_bidder_data['project_owner_name'] = $po_name;
								if((!$this->session->userdata ('user') && $project_data['sealed'] == 'N') || ($this->session->userdata ('user') && $user[0]->user_id == $active_dispute_bidder_value['project_owner_id']) || ($this->session->userdata ('user') && $project_data['sealed'] == 'N' && $user[0]->user_id != $active_dispute_bidder_value['project_owner_id'])){
									echo $this->load->view('projects_disputes/project_active_disputes_listing_project_details', $active_dispute_bidder_data, true);
								}
							}
						}
						?>
						
					</div>
				</div>
				<!-- Active dispute section End -->
				<!-- In Progress Start -->
				<?php
				$show_inprogress_bidder_list_css = "display:none;";
				if((!$this->session->userdata ('user') && $project_data['sealed'] == 'N') || ($this->session->userdata ('user') && $user[0]->user_id == $project_data['project_owner_id']) || ($this->session->userdata ('user') && $user[0]->user_id != $project_data['project_owner_id'] && $project_data['sealed'] == 'N')){
					$show_inprogress_bidder_list_css = "display:block;";
					if(empty($project_inprogress_bidder_list)){
						$show_inprogress_bidder_list_css = "display:none;";
					}
				}
				?>
					<div class="default_block_header_transparent nBorder margin_top20 inprogress_bids" style="<?php echo $show_inprogress_bidder_list_css; ?>" id="po_inprogress_bid_container">
						<div class="transparent"><?php
						if($project_data['project_type']== 'fulltime'){
							if(count($project_inprogress_bidder_list) == 1){
								$inprogress_bidders_list_heading = $this->config->item('fulltime_project_details_page_hired_applications_list_singular');
							}
							else if(count($project_inprogress_bidder_list) > 1){
								$inprogress_bidders_list_heading = $this->config->item('fulltime_project_details_page_hired_applications_list_plural');
							}	
						}else{
							if(count($project_inprogress_bidder_list) == 1){
								$inprogress_bidders_list_heading = $this->config->item('project_details_page_project_in_progress_bids_list_singular');
							}
							else if(count($project_inprogress_bidder_list) > 1){
								$inprogress_bidders_list_heading = $this->config->item('project_details_page_project_in_progress_bids_list_plural');
							}
						}
						echo $inprogress_bidders_list_heading;?><div class="clearfix"></div></div>
						<div class="bidFree inprogress_bidder_list transparent_body">
							<?php
							if(!empty($project_inprogress_bidder_list)){
								foreach($project_inprogress_bidder_list  as $inprogress_bidder_key=>$inprogress_bidder_value){
									$inprogress_bidder_data['inprogress_bidder_data'] = $inprogress_bidder_value;
									$inprogress_bidder_data['project_owner_name'] = $po_name;
										if((!$this->session->userdata ('user') && $project_data['sealed'] == 'N') || ($this->session->userdata ('user') && $user[0]->user_id == $inprogress_bidder_value['project_owner_id']) || ($this->session->userdata ('user') && $project_data['sealed'] == 'N' && $user[0]->user_id != $inprogress_bidder_value['project_owner_id'])){
										echo $this->load->view('bidding/project_inprogress_bidders_listing', $inprogress_bidder_data, true);
									}
								}
							}
							?>
							
						</div>
					</div>
				<!-- In Progress End -->

				<!-- Awarded Start -->
				<?php
				$show_awarded_bidder_list_css = "display:none;";
				if((!$this->session->userdata ('user') && $project_data['sealed'] == 'N') || ($this->session->userdata ('user') && $user[0]->user_id == $project_data['project_owner_id']) || ($this->session->userdata ('user') && $user[0]->user_id != $project_data['project_owner_id'] && $project_data['sealed'] == 'N')){
				
					$show_awarded_bidder_list_css = "display:block;";
					if(empty($project_awarded_bidder_list)){
						$show_awarded_bidder_list_css = "display:none;";
					}
					
				}
				?>
				<div class="default_block_header_transparent awarded_bids nBorder margin_top20" style="<?php echo $show_awarded_bidder_list_css; ?>" id="po_awarded_bid_container">
					<div class="transparent"><?php
						if($project_data['project_type']== 'fulltime'){
							if(count($project_awarded_bidder_list) == 1){
								$awarded_bidders_list_heading = $this->config->item('fulltime_project_details_page_awaiting_acceptance_applications_list_singular');
							}
							else if(count($project_awarded_bidder_list) > 1){
								$awarded_bidders_list_heading = $this->config->item('fulltime_project_details_page_awaiting_acceptance_applications_list_plural');
							}	
						}else{
							if(count($project_awarded_bidder_list) == 1){
								$awarded_bidders_list_heading = $this->config->item('project_details_page_project_awaiting_acceptance_bid_list_singular');
							}
							else if(count($project_awarded_bidder_list) > 1){
								$awarded_bidders_list_heading = $this->config->item('project_details_page_project_awaiting_acceptance_bid_list_plural');
							}
						}
						echo $awarded_bidders_list_heading;?><div class="clearfix"></div>
					</div>
					<div class="bidFree transparent_body" id="awarded_bidder_list">
						<?php
						if(!empty($project_awarded_bidder_list)){
							foreach($project_awarded_bidder_list  as $awarded_bidder_key=>$awarded_bidder_value){
								$awarded_bidder_data['awarded_bidder_data'] = $awarded_bidder_value;
								if((!$this->session->userdata ('user') && $project_data['sealed'] == 'N') || ($this->session->userdata ('user') && $user[0]->user_id == $awarded_bidder_value['project_owner_id']) || ($this->session->userdata ('user') && $project_data['sealed'] == 'N' && $user[0]->user_id != $awarded_bidder_value['project_owner_id'])){
								echo $this->load->view('bidding/project_awarded_bidders_listing', $awarded_bidder_data, true);
								}
							}
						}
						?>
					</div>
				</div>
				<!-- Awarded End -->

				<?php
			   $show_bidder_list_css = "display:none;";
				if($project_data['sealed'] == 'N' || ($this->session->userdata ('user') &&  ($user[0]->user_id == $project_data['project_owner_id'])) || ($this->session->userdata ('user') && $check_sp_active_bid_exists > 0)){
					$show_bidder_list_css = "display:block;";
					if(empty($project_bidder_list)){
						$show_bidder_list_css = "display:none;";
					}
				}
				?>
				<div class="default_block_header_transparent nBorder margin_top20" style="<?php echo $show_bidder_list_css; ?>">
					<div class="transparent"><?php
						if($project_data['project_type']== 'fulltime'){
							if(count($project_bidder_list) == 1){
								$bidders_list_heading = $this->config->item('fulltime_project_details_page_applicants_list_singular');
							}
							else if(count($project_bidder_list) > 1){
								$bidders_list_heading = $this->config->item('fulltime_project_details_page_applicants_list_plural');
							}	
						}else{
							if(count($project_bidder_list) == 1){
								$bidders_list_heading = $this->config->item('project_details_page_project_bidders_list_singular');
							}
							else if(count($project_bidder_list) > 1){
								$bidders_list_heading = $this->config->item('project_details_page_project_bidders_list_plural');
							}
						}
						echo $bidders_list_heading;?><div class="clearfix"></div></div>
					<div class="bidFree transparent_body" id="bidder_list">
						<?php
						foreach($project_bidder_list  as $bidder_key=>$bidder_value){
							$bidder_data['bidder_data'] = $bidder_value;
							$bidder_data['project_data'] = $project_data;
							$bidder_data['project_status'] = $project_status;
							if($project_data['sealed'] == 'N' || ($this->session->userdata ('user') &&  ($user[0]->user_id == $bidder_value['project_owner_id'])) || ($this->session->userdata ('user') && $user[0]->user_id == $bidder_value['bidder_id'])){
							echo $this->load->view('bidding/project_bidders_listing', $bidder_data, true);
							}
						}
						?>
					</div>
				</div>
				
			</div>
			<!-- Start Cover Image Button Start -->
			<!--<div class="col-md-2 col-sm-2 col-12 pojBtn">
				<div class="projBtn">
					<button type="button" class="btn btn-primary">Reset Cover Picture</button>
					<button type="button" class="btn btn-secondary">Upload Cover Picture</button>
				</div>
			</div>-->
		</div>
	</div>
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

	<!-- Middle Section End -->
</div>
	<!-- Modal End -->
<!-- Modal Start for edit upgrade-->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="cancelProjectModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content etModal">
			<div class="modal-header popup_header popup_header_without_text">
				<h4 class="modal-title popup_header_title" id="confirmation_modal_title"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body most-project" id="confirmation_modal_body">
			</div>
			<div class="modal-footer mg-bottom-10">
				<div class="row">
					<div class="col-sm-12 col-lg-12 col-12" id="confirmation_modal_footer">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal End -->
<!-- Modal Start in Violation Report for Project Details Page-->
<div class="modal fade vioRepo" id="violationReport" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header popup_header popup_header_without_text">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-12">
						<div class="popup_body_bold_title"><?php echo $this->config->item('project_details_page_violation_report_popup_heading'); ?></div>
						<div class="popup_body_regular_title popup_body_border_bottom"><?php echo $this->config->item('project_details_page_violation_report_popup_sub_heading_txt'); ?></div>
					</div>
					<div class="col-md-12 col-sm-12 col-12">
						<div class="urlViolation">
							<label class="default_black_bold headText"><?php echo $this->config->item('project_details_page_violation_report_popup_url_lbl'); ?></label>
							<input type="text" class="description-text avoid_space default_input_field" disabled value="<?php echo base_url($this->config->item('project_detail_page_url').'?id='.$project_data['project_id']); ?>">
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-12">
						<label class="default_black_bold"><?php echo $this->config->item('project_details_page_violation_report_popup_reason_lbl'); ?></label>
						<div class="form-group default_dropdown_select"> 
							<select name="violation_reason" id="violation_reason">
								<option value="" class="d-none"><?php echo $this->config->item('project_details_page_violation_report_popup_reason_default_option_name'); ?></option>
								<?php
									foreach($this->config->item('project_details_page_violation_report_popup_reasons_option_name') as $key => $val) {
										echo '<option value="'.$key.'">'.$val.'</option>';
									}
								?>
							</select>                    
						</div>
						<div class="error_div_sectn clearfix">
							<span id="reason_error" class="error_msg"></span>
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-12">
						<label class="default_black_bold"><?php echo $this->config->item('project_details_page_violation_report_popup_detail_violation_lbl'); ?></label>
						<textarea name="violation_detail" id="violation_detail" class="avoid_space_textarea default_textarea_field" maxlength="<?php echo $this->config->item('project_details_page_violation_report_popup_detail_violation_max_character_limit'); ?>"></textarea>
						<div class="error_div_sectn clearfix default_error_div_sectn">
							<span class="content-count content_cnt"><span><?php echo $this->config->item('project_details_page_violation_report_popup_detail_violation_max_character_limit'); ?></span>&nbsp;<?php echo $this->config->item('characters_remaining_message'); ?></span>
							<span id="detail_error" class="error_msg"></span>
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-12">
						<div class="default_terms_text tac"><?php echo $this->config->item('project_details_page_violation_report_popup_disclaimer_modal_body'); ?></div>
					</div>
				</div>
			</div>
			<div class="modal-footer mg-bottom-10">
				<div class="row">
					<div class="col-sm-12 col-lg-12 col-12 abc" id="confirmation_modal_footer"><button type="button" class="btn blue_btn default_btn width-auto submit_violation_report"><?php echo $this->config->item('project_details_page_violation_report_popup_submit_report_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>&nbsp;<button type="button" class="btn red_btn default_btn" data-dismiss="modal"><?php echo $this->config->item('cancel_btn_txt'); ?></button></div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Feedback Modal Start -->
<div class="modal feedModal fade" id="feedbackModal">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">      
			<!-- Modal Header -->
			<div class="modal-header popup_header popup_header_without_text">
				<h4 class="modal-title popup_header_title" id="ratings_feedbacks_modal_title">Send Feedback</h4>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>        
			<!-- Modal body -->
			<div class="modal-body"  id="ratings_feedbacks_modal_body"></div>			
			<!-- Modal footer -->
			<div class="modal-footer">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-12" id="ratings_feedbacks_modal_footer"></div> 
				</div> 
			</div>     
		</div>
	</div>
</div>
<!-- Feedback Modal End -->
<script src="<?= ASSETS ?>js/modules/users_ratings_feedbacks.js"></script> 
<script>
var project_details_page_violation_report_popup_detail_violation_max_character_limit = '<?php echo $this->config->item('project_details_page_violation_report_popup_detail_violation_max_character_limit'); ?>';
var users_ratings_feedbacks_popup_feedback_characters_maximum_length_characters_limit = '<?php echo $this->config->item('users_ratings_feedbacks_popup_feedback_characters_maximum_length_characters_limit'); ?>';		
var users_ratings_feedbacks_popup_feedback_characters_minimum_length_characters_limit = '<?php echo $this->config->item('users_ratings_feedbacks_popup_feedback_characters_minimum_length_characters_limit'); ?>';	
var popup_alert_heading = "<?php echo $this->config->item('popup_alert_heading'); ?>";
var dashboard_page_url = "<?php echo $this->config->item('dashboard_page_url'); ?>";
var signup_page_url = "<?php echo $this->config->item('signup_page_url'); ?>";
var signin_page_url = "<?php echo $this->config->item('signin_page_url'); ?>";
var project_id = "<?php echo $project_id; ?>";
var project_title = "<?php echo htmlspecialchars($project_data['project_title'], ENT_QUOTES); ?>";
var users_chat_new_message_text = '<?php echo $this->config->item('users_chat_new_message_text'); ?>';
var unread_msg_counter = '<?php echo $project_chat_unread_messages_count; ?>';
var project_owner = '<?php echo $project_data['project_owner_id'];?>';
var page_type = "project_detail";
var project_details_page_tooltip_subscribe_to_po_new_projects_posted_notifications_txt = "<?php echo $project_details_page_tooltip_subscribe_to_po_new_projects_posted_notifications_txt; ?>";
var project_details_page_tooltip_unsubscribe_to_po_new_projects_posted_notifications_txt = "<?php echo $project_details_page_tooltip_unsubscribe_to_po_new_projects_posted_notifications_txt; ?>";
var project_type = '<?php echo $project_data['project_type']; ?>';
var escrow_maximum_length_character_limit = "<?php echo $this->config->item('escrow_description_maximum_length_character_limit_escrow_form'); ?>";
var escrow_minimum_length_character_limit = "<?php echo $this->config->item('escrow_description_minimum_length_character_limit_escrow_form'); ?>"; 
var escrow_characters_remaining_message = "<?php echo $this->config->item('characters_remaining_message'); ?>"; 
var escrow_amount_length_character = "<?php echo $this->config->item('escrow_amount_length_character_limit_escrow_form'); ?>"; 
var escrow_amount_length_character_limit_before_decimal = "<?php echo $this->config->item('escrow_amount_length_character_limit_before_decimal_point_escrow_form'); ?>"; 
var requested_escrow_listing_limit = "<?php echo $this->config->item('project_detail_page_requested_escrow_listing_limit'); ?>"; 
var active_escrow_listing_limit = "<?php echo $this->config->item('project_detail_page_active_escrow_listing_limit'); ?>"; 
var create_escrow_form_tooltip_message_business_service_fee_po_view = "<?php echo $this->config->item('project_details_page_fixed_project_create_milestone_form_tooltip_message_business_service_fee_po_view'); ?>"; 
var session_uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
</script>
<script src="<?php echo JS; ?>modules/project_detail.js"></script>
<?php
if($this->session->userdata ('user')){	
?>
<script src="<?php echo JS; ?>modules/escrow.js"></script>
<?php
}	
?>	
<?php //echo $this->load->view('footer.php'); ?>

	<!-- Bottom Div Closed Script End -->
	<!-- Read More and Read Less Script End -->
	<script>
            //signin/signup popup Bottom Div Closed Script Start
$('.closeDiv').on('click', function(){
    $(this).closest("#clientsWrapper").remove();
});
//signin/signup popup Bottom Div Closed Script End
		function myFunction() {
		  var dots = document.getElementById("dots");
		  var moreText = document.getElementById("more");
		  var btnText = document.getElementById("myBtn");

		  if (dots.style.display === "none") {
			  dots.style.display = "inline";
			btnText.innerHTML = "Read more"; 
			moreText.style.display = "none";
		  } else {
			dots.style.display = "none";
			btnText.innerHTML = "Read less"; 
			moreText.style.display = "inline";
		  }
		}
	</script>
	<script>
		function myFunction1() {
		  var dots = document.getElementById("dots1");
		  var moreText = document.getElementById("more1");
		  var btnText = document.getElementById("myBtn1");

		  if (dots.style.display === "none") {
			dots.style.display = "inline";
			btnText.innerHTML = "Read more"; 
			moreText.style.display = "none";
		  } else {
			dots.style.display = "none";
			btnText.innerHTML = "Read less"; 
			moreText.style.display = "inline";
		  }
		}
	</script>
	<script>
		function myFunction2() {
		  var dots = document.getElementById("dots2");
		  var moreText = document.getElementById("more2");
		  var btnText = document.getElementById("myBtn2");

		  if (dots.style.display === "none") {
			dots.style.display = "inline";
			btnText.innerHTML = "Read more"; 
			moreText.style.display = "none";
		  } else {
			dots.style.display = "none";
			btnText.innerHTML = "Read less"; 
			moreText.style.display = "inline";
		  }
		}
	</script>
	<!-- Read More and Read Less Script End -->
	<script>
		var hire_me_user_id;
		$(function() {
			<?php
				if(!empty($hire_me_user_id)) {
			?>
			hire_me_user_id = '<?php echo $hire_me_user_id;?>';
			if($(".user_details_right_adjust_project_owner #contactMe[data-id='<?php echo $hire_me_user_id;?>']").length == 1 && $(".user_details_right_adjust_project_owner #contactMe[data-id='<?php echo $hire_me_user_id;?>']").is(":visible") ) {
				$(".user_details_right_adjust_project_owner #contactMe[data-id='<?php echo $hire_me_user_id;?>']").trigger('click');
			}
			if($(".pdmobile_view #contactMe[data-id='<?php echo $hire_me_user_id;?>']").length == 1 && $(".pdmobile_view #contactMe[data-id='<?php echo $hire_me_user_id;?>']").is(":visible")) {
				$(".pdmobile_view #contactMe[data-id='<?php echo $hire_me_user_id;?>']").trigger('click');
			}
			
			if($(".user_details_right_adjust_project_owner .login_popup[data-id='<?php echo $hire_me_user_id;?>']").length == 1 && $(".user_details_right_adjust_project_owner .login_popup[data-id='<?php echo $hire_me_user_id;?>']").is(":visible") ) {
				$(".user_details_right_adjust_project_owner .login_popup[data-id='<?php echo $hire_me_user_id;?>']").trigger('click');
			}
			if($(".pdmobile_view .login_popup[data-id='<?php echo $hire_me_user_id;?>']").length == 1 && $(".pdmobile_view .login_popup[data-id='<?php echo $hire_me_user_id;?>']").is(":visible")) {
				$(".pdmobile_view .login_popup[data-id='<?php echo $hire_me_user_id;?>']").trigger('click');
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
