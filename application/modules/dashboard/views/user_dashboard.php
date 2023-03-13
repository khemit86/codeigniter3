<?php
$user = $this->session->userdata('user');	
?>
<div class="dashTop">
		<!-- Menu Icon on Responsive View Start -->
        <?php echo $this->load->view('user_left_menu_mobile.php'); ?>
		<!-- Menu Icon on Responsive View End -->
		
		<!-- Middle Section Start -->
		<div class="wrapper wrapper1">
			<!-- Left Menu Start -->
			
			<?php echo $this->load->view('user_left_nav.php'); ?>
			
			<!-- Left Menu End -->
			<!-- Right Section Start -->
                        <!-- <div id="content">
				<div class="rightSec">
					<div class="row">
						<div class="col-md-9 col-sm-9 col-12 rsP dbMiddle"> -->
						<div class="rsP dbMiddle">
							<div id="middleSection1" class="content1">
								<!-- Online Person, Slogan and Upload Cover Image Start -->
								<!--<div class="osu">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-12 padding0">
											<div class="default_user_name">
												<a class="default_user_name_link" href="<?php echo site_url ($user_detail['profile_name']); ?>">
												<?php $name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name']; echo $name;?></a>
												<?php if($user_detail['headline']!='') { echo '<div class="headline_title">'.htmlspecialchars($user_detail['headline'], ENT_QUOTES).'</div>'; }?>
											</div>
										</div>
									</div>
								</div>-->
								<!-- Online Person, Slogan and Upload Cover Image End -->
								<!-- Free Member, Referral and Create Project Start -->
								<div class="frp">
									<div class="row">
										<div class="col-md-6 col-sm-6 col-12 pl0 headtop_left">
											<div class="fm">
												<div class="fmB">
													<?php 
														$membership_plan = '';
														$plans_names = $this->config->item('membership_plans_names');
														if(!empty($plans_names) && array_key_exists($user_detail['current_membership_plan_id'], $plans_names)) {
															$membership_plan = $plans_names[$user_detail['current_membership_plan_id']];
														}
													?>
													<span class="default_black_bold"><?php echo $membership_plan; ?> <?php echo $this->config->item('dashboard_top_section_member'); ?></span><a class="default_blue_bold_link" href="<?php echo $this->config->item('membership_page_url');?>"><?php echo $check_user_upgrade_gold_membership == 0 ? $this->config->item('dashboard_top_section_manage_membership_initial_view_no_upgrade_yet') : $this->config->item('dashboard_top_section_manage_membership'); ?></a>
												</div>
												
												<div class="fmB default_black_regular"><?php echo str_replace("{user_profile_completion_percentage}",$user_profile_completion > 100 ? 100 : $user_profile_completion,$this->config->item('user_profile_completion_percentage_dashboard_txt')); ?></div>
												
												<div class="fmB">
													<div class="progress">
														<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $user_profile_completion > 100 ? 100 : $user_profile_completion; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $user_profile_completion > 100 ? 100 : $user_profile_completion; ?>%"></div>
													</div>
												</div>
												<div class="fmB default_user_currency">
													<span><i class="fa fa-money" aria-hidden="true"></i></span><?php 
													if($user_detail['hourly_rate']!='' && $user_detail['hourly_rate']!=0){
														echo '<span class="default_black_regular">'.str_replace(".00","",number_format($user_detail['hourly_rate'],  2, '.', ' ')).' '.CURRENCY.$this->config->item('post_project_budget_per_hour').'</span>';
													}else { ?><a class="default_blue_bold_link" href="<?php echo site_url ($this->config->item('profile_management_profile_definitions_page_url')) ?>"><span class="space_right"><?php echo $this->config->item('dashboard_top_section_add_hourly_rate'); ?> <span>(<?php echo CURRENCY.$this->config->item('post_project_budget_per_hour') ?>)</span></span></a>
													<?php } ?>
												</div>
												
												<?php
												if(!empty($address_details)){
													echo $address_details;
												}else{
													if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){ 
													 $address_page_url = base_url($this->config->item('account_management_address_details_page_url'));
													}else{
														$address_page_url = base_url($this->config->item('account_management_address_details_page_url'));
													}
												?>
												
													<div class="fmB">
														<i class="fas fa-map-marker-alt" aria-hidden="true"></i><a class="default_blue_bold_link" href="<?php echo $address_page_url; ?>"><?php echo $this->config->item('dashboard_top_section_add_address'); ?></a>
													</div>
												<?php
												}
												?>
												<div id="demo" class="collapse">
													
													<div class="fmB">
														Credit:<span>0 <?php echo CURRENCY ?></span>
													</div>
												</div>
												
												<div class="smSL dpSN">
													<input type="checkbox" class="read-more-state" id="post-3"/>    
													<div class="read-more-wrap">
														<div class="read-more-target">	
															<div class="fmB">
																<span class="default_black_bold"><?php echo $this->config->item('dashboard_top_section_account_balance'); ?></span><span class="default_black_regular"><?php echo str_replace(".00","",number_format($user_detail['user_account_balance'], 2, '.', ' '))." ".CURRENCY;?></span>
																
															</div>
															<div class="fm">
																<?php
																if(!empty($user_detail['referral_code'])){
																?>
																<div class="fmB">
																	<span class="default_black_bold"><?php echo $this->config->item('dashboard_top_section_referral_code'); ?></span><span class="default_black_regular"><?php echo $user_detail['referral_code']?></span>
																</div>
																<?php
																}
																if(floatval($user_detail['signup_bonus_balance']) != 0) : 
																?>
																<div class="fmB">
																	<span class="default_black_bold"><?php echo $this->config->item('dashboard_top_section_signup_bonus_balance'); ?></span><span class="default_black_regular"><?=number_format($user_detail['signup_bonus_balance'], 0, '.', ' ')." ".CURRENCY; ?></span>
																</div>
																<?php
																	endif;
																?>
																<?php
																	if(floatval($user_detail['bonus_balance']) != 0) :
																?>
																<div class="fmB">
																	<span class="default_black_bold"><?php echo $this->config->item('dashboard_top_section_bonus_balance'); ?></span><span class="default_black_regular"><?=number_format($user_detail['bonus_balance'], 0, '.', ' ')." ".CURRENCY;?></span>
																</div>
																<?php
																	endif;
																?>
															</div>
														</div>
													</div>
													<label for="post-3" class="read-more-trigger"></label>
												</div>											
											</div>
										</div>
										<div class="col-md-6 col-sm-6 col-12 pRight0 headtop_right">
											<div class="fm">
												<div class="fmB">
													<span class="default_black_bold"><?php echo $this->config->item('dashboard_top_section_account_balance'); ?></span><span class="default_black_regular display-inline-block"><?php echo str_replace(".00","",number_format($user_detail['user_account_balance'], 2, '.', ' '))." ".CURRENCY;?></span>
												</div>
												<?php
												if(!empty($user_detail['referral_code'])){
												?>
												<div class="fmB">
													<span class="default_black_bold"><?php echo $this->config->item('dashboard_top_section_referral_code'); ?></span><span class="default_black_regular"><?php echo $user_detail['referral_code']?></span>
												</div>
												<?php
												}
												if(floatval($user_detail['signup_bonus_balance']) != 0) : 
												?>
												<div class="fmB">
													<span class="default_black_bold"><?php echo $this->config->item('dashboard_top_section_signup_bonus_balance'); ?></span><span class="default_black_regular"><?php echo number_format($user_detail['signup_bonus_balance'], 0, '.', ' ')." ".CURRENCY?></span>
												</div>
												<?php
													endif;
												?>
												<?php
													if(floatval($user_detail['bonus_balance']) != 0) :
												?>
												<div class="fmB">
													<span class="default_black_bold"><?php echo $this->config->item('dashboard_top_section_bonus_balance'); ?></span><span class="default_black_regular"><?php echo number_format($user_detail['bonus_balance'], 0, '.', ' ')." ".CURRENCY;?></span>
												</div>
												<?php
													endif;
												?>
											</div>
										</div>
										<div class="col-md-12 col-sm-12 col-12 pLeft0 headtop_btn">
											<div class="covBtn">
												<div class="row">
													<div class="col-md-3 col-sm-3 col-12 btnNoP btnNoP_bluebtn">
														<button type="button" class="btn blue_btn default_btn browse_find_project"><?php echo $this->config->item('browse_projects_txt') ?></button>
													</div>
													<div class="col-md-3 col-sm-3 col-12 btnNoP btnNoP_redbtn">
														<button type="button" class="btn purple_btn default_btn browse_find_professionals"><?php echo $this->config->item('browse_service_providers_txt') ?></button>
													</div>
													<div class="col-md-6 col-sm-6 col-12 btnNoP btnNoP_greenbtn">
														<a href="<?php echo base_url($this->config->item('invite_friends_page_url')) ; ?>" class="btn default_btn green_btn"><?php echo $this->config->item('manage_sent_invitations_and_affiliate_income') ?></a>

														<!-- button type="button" class="btn transparent_btn">Manage Invitations & Affiliate Income</button -->

													</div>
													<!-- <div class="col-md-9 col-sm-9 col-12 btnNoP btnNoP_greenGrayBtn">
														<div class="row">
															<div class="col-md-4 col-sm-4 col-12 btnNoP">
																<button type="button" class="btn purple_btn default_btn browse_find_professionals"><?php echo $this->config->item('browse_service_providers_txt') ?></button>
															</div>
															<div class="col-md-8 col-sm-8 col-12 btnNoP">
																<a href="<?php echo base_url($this->config->item('invite_friends_page_url')) ; ?>" class="btn default_btn green_btn"><?php echo $this->config->item('manage_sent_invitations_and_affiliate_income') ?></a>

															</div>
														</div>
													</div> -->
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- Free Member, Referral and Create Project End -->
							
								<!-- Server and Chat Start -->
								<div class="svrChat">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-12 lSec">
											<!-- Open Project Start -->
											<div id="latest_open_bidding_project">
													<?php echo $this->load->view('projects/ajax_latest_project_user_dashboard', $latest_projects_section_data,true); ?>
											</div>
											<!-- Open Project End -->
											
											<!-- Invite Friends Start -->
											<div class="default_block_header_transparent inviteFrndsFooterCalculate nBorder padding_top10">
												<div class="transparent"><?php echo $this->config->item('dashboard_invite_friends_section_heading'); ?></div>
												<!-- div class="iF">Invite Friends :<sup>______</sup></div -->
												<div class="ifECC transparent_body">
													<div class="row">
														<div class="col-md-6 col-sm-6 col-12 ifEmail">
															<div class="emlCont">									
																<h6><?php echo $this->config->item('dashboard_invite_friends_for_email_contacts'); ?><i class="fa fa-question-circle tooltipAuto" data-toggle="tooltip" data-placement="top" title="<?php echo $this->config->item('dashboard_invite_friends_email_contacts_tooltip'); ?>"></i></h6>
																<!-- <h6><?php //echo $this->config->item('dashboard_invite_friends_for_email_contacts'); ?><i class="fa fa-question-circle" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="<?php //echo $this->config->item('dashboard_invite_friends_email_contacts_tooltip'); ?>"></i></h6> -->
																<p><b><?php echo $this->config->item('dashboard_invite_friends_for_add_email_address'); ?></b><?php echo $this->config->item('dashboard_invite_friends_for_separate_with_spaces_commas'); ?></p>
																<div class="clearfix"></div>
																<div id="emailtag">
																	<input type="text" class="tagsinputText" value="" data-role="tagsinput" placeholder="" />
																</div>
															</div>
															<div class="sendInvitations">
																<span class="sendInvtButton"><button type="button" class="btn default_btn blue_btn send_invite_friend_request" disabled><?php echo $this->config->item('dashboard_invite_friends_for_send_invitations_btn_txt'); ?></button></span>
																<span class="default_error_red_message" style="display:none;"></span>
																<div class="clearfix"></div>
															</div>														
														</div>
														<div class="col-md-1 col-sm-1 col-12 ifEmailOr">
															<div class="ifOr"><span><?php echo $this->config->item('or'); ?></span></div>
														</div>
														<div class="col-md-5 col-sm-5 col-12 codeCopy">
															<div class="inShare">
																<!-- <h6>Share</h6> -->
																<p><?php echo $this->config->item('dashboard_invite_friends_better_visibility_and_more_invitations'); ?><i class="fa fa-question-circle tooltipAuto" data-toggle="tooltip" data-placement="top" title="<?php echo $this->config->item('dashboard_invite_friends_your_url_tooltip'); ?>"></i></p>
																<div class="input-group textCopy">
																	<input id="refferal_link" type="text" class="form-control" name="msg" placeholder="Additional Info" value="<?php echo $share_link; ?>">
																	<div class="input-group-append">
																	   <span class="input-group-text bstooltip" onclick="copyToClipboard('refferal_link')" title="<?php echo $this->config->item("copy_invite_friends_referral_link_tooltip_message_copied"); ?>" ><?php echo $this->config->item('dashboard_invite_friends_for_copy_url'); ?></span>
																	</div>
																</div>
																<div class="socIcon">
																	<a href="" class="fab fa-facebook-f fb_referral_share"  data-referral-url="<?php echo $fb_share_link; ?>"></a>
																	<a href="" class="fab fa-linkedin-in ln_referral_share" data-referral-url="<?php echo $linkedin_share_link; ?>" ></a>
																	<a href="" class="fab fa-twitter twitter_referral_share" data-referral-url="<?php echo $twitter_share_link; ?>" data-share-message="<?php echo $twitter_share_message; ?>"></a>
																</div>															
															</div>
														</div>
													</div>
													<!-- Referral Sources Statistics Start -->
													<div class="default_social_value socialValue_adjust">
														<div class="rsource"><h6><?php echo $this->config->item('invite_friends_referral_sources_statistics_lbl'); ?></h6></div>
														<div class="socialTag">
															<label>
																<div class="fb">
																	<i class="fab fa-facebook-f"></i><b><?php echo $this->config->item('invite_friends_referral_sources_statistics_facebook_lbl'); ?><span><?php echo $invited_friends_registered_via_fb_count; ?></span></b>
																</div>
															</label>
															<label>
																<div class="lin">
																	<i class="fab fa-linkedin-in"></i><b><?php echo $this->config->item('invite_friends_referral_sources_statistics_linkedin_lbl'); ?><span><?php echo $invited_friends_registered_via_ln_count; ?></span></b>
																</div>
															</label>
															<label>
																<div class="twt">
																	<i class="fab fa-twitter"></i><b><?php echo $this->config->item('invite_friends_referral_sources_statistics_twitter_lbl'); ?><span><?php echo $invited_friends_registered_via_twitter_count; ?></span></b>
																</div>
															</label>
															<label>
																<div class="eml">
																	<i class="fas fa-envelope"></i><b><?php echo $this->config->item('invite_friends_referral_sources_statistics_email_lbl'); ?><span><?php echo $invited_friends_registered_via_email_count; ?></span></b>
																</div>
															</label>
															<label>
																<div class="url">
																	<i class="fas fa-link"></i><b><?php echo $this->config->item('invite_friends_referral_sources_statistics_url_lbl'); ?><span><?php echo $invited_friends_registered_via_url_count; ?></span></b>
																</div>
															</label>
															<label>
																<div class="cod">
																	<i class="fas fa-code"></i><b><?php echo $this->config->item('invite_friends_referral_sources_statistics_code_lbl'); ?><span><?php echo $invited_friends_registered_via_code_count; ?></span></b>
																</div>
															</label>
														</div>
													</div>
													<!-- Referral Sources Statistics End -->
												</div>
											</div>
											<!-- Invite Friends End -->
											 <!-- My Projects Start -->
											<div class="default_block_header_transparent nBorder">
												<div class="transparent mP"><?php echo $this->config->item('my_projects_section_heading'); ?></div>
												<div class="myproTabs transparent_body">
													<div class="default_radio_button myproRadio">
														<section>
															<div>
																<input type="radio" id="inlineRadio1" name="inlineRadioOptions" value="1" data-tab-type="po" class="user_type" checked>
																<label style="pointer-events:none" for="inlineRadio1">
																	<span><?php echo $this->config->item('my_projects_section_as_employer'); ?></span>
																</label>
															</div><div>
																<input type="radio" data-tab-type="sp" id="inlineRadio2" name="inlineRadioOptions" value="2" class="user_type">
																<label for="inlineRadio2">
																	<span><?php echo $this->config->item('my_projects_section_as_service_provider'); ?></span>
																</label>
															</div>															
														</section>
													</div>
													
																							   
													<div class="myProDsk" id="employer_view_div" style="display: block;">
													
													
														<nav class="navbar navbar-expand-lg navbar-dark">
															<span class="navbar-brand"></span>
															<button id="myProjecttab1" class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#homeNav5" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
															<!-- <span class="navbar-toggler-icon"></span> -->
															<i class="fas fa-bars"></i>
															</button>
															<div class="collapse navbar-collapse" id="homeNav5">
																<?php
																if($user_detail['current_membership_plan_id'] == 1){
																	$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
																	$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');

																	$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
																	$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
																}
																if($user_detail['current_membership_plan_id'] == 4){
																	$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
																	$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');
																	
																	$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
																	$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
																}
																?>
																<ul class="nav nav-tabs" id="myTab" role="tablist">
																	<li class="nav-item">
																		
																		<a  class="nav-link active my_projects_tab" id="draft-tab" data-tab-type="draft" data-target="#draft" data-toggle="tab" role="tab" aria-controls="draft" aria-selected="true"><?php echo $this->config->item('my_projects_po_view_draft_tab_heading'); ?> (<?php echo $draft_cnt."/".($user_memebership_max_number_of_draft_projects - $draft_cnt); ?><?php echo ' + '.$fulltime_draft_cnt.'/'.($user_memebership_max_number_of_draft_fulltime_projects - $fulltime_draft_cnt); ?>)</a>
																	</li>
																	<li class="nav-item">
																		<a  data-tab-type="awaitingModeration"  class="nav-link my_projects_tab" id="awaitingModeration-tab" data-toggle="tab" data-target="#awaitingModeration" role="tab" aria-controls="awaitingModeration" aria-selected="true"><?php echo $this->config->item('my_projects_po_view_awaiting_moderation_tab_heading'); ?> (<?php echo ($awaiting_moderation_project_count - $fulltime_awaiting_moderation_project_count); ?> + <?php echo $fulltime_awaiting_moderation_project_count; ?>)</a>
																	</li>
																	<li class="nav-item">
																		<a  class="nav-link my_projects_tab" id="openBid-tab" data-toggle="tab" data-tab-type="openBid" data-target="#openBid" role="tab" aria-controls="openBid" aria-selected="false"><?php echo $this->config->item('my_projects_po_view_open_for_bidding_tab_heading'); ?> (<?php echo ($open_bidding_project_count - $fulltime_open_project_count)."/".($user_memebership_max_number_of_open_for_bidding_projects-get_user_open_projects_count($user[0]->user_id)); ?><?php echo ' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_bidding_cnt); ?>)</a>
																	</li>
																	<li class="nav-item">
																		<a  class="nav-link my_projects_tab" id="awarded-tab" data-tab-type="awarded" data-toggle="tab" data-target="#awarded" role="tab" aria-controls="awarded" aria-selected="false"><?php echo $this->config->item('my_projects_po_view_awarded_tab_heading'); ?> (<?php echo $awarded_project_count; ?>)</a>
																	</li>
																	<li class="nav-item">
																		<a  class="nav-link my_projects_tab" id="workPro-tab" data-tab-type="workPro"  data-toggle="tab" data-target="#workPro" role="tab" aria-controls="workPro" aria-selected="false"><?php echo $this->config->item('my_projects_po_view_work_in_progress_tab_heading'); ?> (<?php echo $in_progress_project_count; ?>)</a>
																	</li>
																	<li class="nav-item">
																		<a  class="nav-link my_projects_tab" data-tab-type="completed" id="completed-tab" data-toggle="tab" data-target="#completed" role="tab" aria-controls="completed" aria-selected="false"><?php echo $this->config->item('my_projects_po_view_completed_tab_heading'); ?> (<?php echo $completed_project_count; ?>)</a>
																	</li>
																	<li class="nav-item">
																		<a  class="nav-link my_projects_tab" data-tab-type="incomplete" id="incomplete-tab" data-toggle="tab" data-target="#incomplete" role="tab" aria-controls="incomplete" aria-selected="false"><?php echo $this->config->item('my_projects_po_view_work_incomplete_tab_heading'); ?> (<?php echo $in_complete_project_count; ?>)</a>
																	</li>
																	<li class="nav-item">
																		<a  class="nav-link my_projects_tab" id="expired-tab" data-tab-type="expired" data-toggle="tab" data-target="#expired" role="tab" aria-controls="expired" aria-selected="false"><?php echo $this->config->item('my_projects_po_view_expired_tab_heading'); ?> (<?php echo ($expired_project_count - $fulltime_expired_project_count).' + '.$fulltime_expired_project_count; ?>)</a>
																	</li>
																	<li class="nav-item">
																		<a  class="nav-link my_projects_tab" id="cancelled-tab" data-toggle="tab" data-target="#cancelled" role="tab" aria-controls="cancelled" aria-selected="false" data-tab-type="cancelled"><?php echo $this->config->item('my_projects_po_view_cancelled_tab_heading'); ?> (<?php echo ($cancelled_project_count - $fulltime_cancelled_project_count).' + '.$fulltime_cancelled_project_count; ?>)</a>
																	</li>
																</ul>
															</div>
														</nav>
													</div>
													
													<div class="myProDsk" id="freelancer_view_div" style="display: none;">
														<nav class="navbar navbar-expand-lg navbar-dark">
															<span class="navbar-brand"></span>
															<button id="myProjecttab2" class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#homeNav6" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
															<!-- <span class="navbar-toggler-icon"></span> -->
															<i class="fas fa-bars"></i>
															</button>
															<div class="collapse navbar-collapse" id="homeNav6">
																<ul class="nav nav-tabs" id="myTab1" role="tablist">
																	<li class="nav-item">
																		<a class="nav-link active my_projects_tab" id="active-bids-tab" data-toggle="tab" data-target="#active_bids" role="tab" aria-controls="active_bids" aria-selected="true" data-tab-type="active_bids"><?php echo $this->config->item('my_projects_sp_view_active_bids_tab_heading'); ?> (<?php echo ($active_bids_project_count - $fulltime_active_bids_project_count).' + '.$fulltime_active_bids_project_count; ?>)</a>
																	</li>
																	<li class="nav-item">
																		<a class="nav-link my_projects_tab" id="awarded-bids-tab" data-toggle="tab" data-target="#awarded_bids" role="tab" aria-controls="awarded_bids" aria-selected="false" data-tab-type="awarded_bids"><?php echo $this->config->item('my_projects_sp_view_awarded_bids_tab_heading'); ?> (<?php echo ($awarded_bids_project_count - $fulltime_awarded_bids_project_count).' + '.$fulltime_awarded_bids_project_count; ?>)</a>
																	</li>
																	<li class="nav-item">
																		<a  class="nav-link my_projects_tab" id="in-progress-work-tab" data-toggle="tab" data-target="#in_progress_work" role="tab" aria-controls="in_progress_work" aria-selected="false" data-tab-type="in_progress_work"><?php echo $this->config->item('my_projects_sp_view_projects_in_progress_tab_heading'); ?> (<?php echo $in_progress_bids_project_count; ?>)</a>
																	</li>
																	<li class="nav-item">
																		<a  class="nav-link my_projects_tab" id="completed-work-tab" data-toggle="tab" data-target="#completed_work" role="tab" aria-controls="completed_work" aria-selected="false" data-tab-type="completed_work"><?php echo $this->config->item('my_projects_sp_view_completed_tab_heading'); ?> (<?php echo $completed_bids_project_count; ?>)</a>
																	</li>
																	<li class="nav-item">
																		<a  class="nav-link my_projects_tab" id="hired-tab" data-toggle="tab" data-target="#hired" role="tab" aria-controls="hired" aria-selected="false" data-tab-type="hired"><?php echo $this->config->item('my_projects_employee_view_fulltime_projects_hired_tab_heading'); ?> (<?php echo $hired_project_count; ?>)</a>
																	</li>
																	<li class="nav-item">
																		<a  class="nav-link my_projects_tab" id="in-complete-work-tab" data-toggle="tab" data-target="#in_complete_work" role="tab" aria-controls="in_complete_work" aria-selected="false" data-tab-type="in_complete_work"><?php echo $this->config->item('my_projects_sp_view_projects_incomplete_tab_heading'); ?> (<?php echo $in_complete_bids_project_count; ?>)</a>
																	</li>
																</ul>
															</div>
														</nav>
													</div>
													<div class="tab-content" id="employer_tab_div" style="display:block">
														<div class="tab-pane fade workOnly show active" id="draft" role="tabpanel" aria-labelledby="draft-tab">				
														<?php
														/*echo $this->load->view('projects/po_draft_projects_listing_my_projects', array('draft_project_data'=>$draft_project_data,'draft_project_count'=>$draft_project_count,'page_type'=>'dashboard','standard_valid_time_arr'=>$standard_valid_time_arr,'draft_cnt'=>$draft_cnt,'open_bidding_cnt'=>$open_bidding_cnt,'po_max_draft_projects_number'=>$user_memebership_max_number_of_draft_projects,'po_max_open_projects_number'=>$user_memebership_max_number_of_open_for_bidding_projects), true);*/
														?>
														</div>
														<div class="tab-pane fade workOnly" id="awaitingModeration" role="tabpanel" aria-labelledby="awaitingModeration-tab">
														
														</div>
														<div class="tab-pane fade workOnly" id="openBid" role="tabpanel" aria-labelledby="openBid-tab">	

														</div>
															
														<div class="tab-pane fade workOnly" id="awarded" role="tabpanel" aria-labelledby="awarded-tab">
																			
														</div>
														
														<div class="tab-pane fade workOnly" id="workPro" role="tabpanel" aria-labelledby="workPro-tab">
										
														</div>
					
														<div class="tab-pane fade workOnly" id="completed" role="tabpanel" aria-labelledby="completed-tab">
														</div>
														<div class="tab-pane fade workOnly" id="incomplete" role="tabpanel" aria-labelledby="incomplete-tab">
														</div>
														
														<div class="tab-pane fade workOnly" id="expired" role="tabpanel" aria-labelledby="expired-tab">
													
														</div>
														
														<div class="tab-pane fade workOnly" id="cancelled" role="tabpanel" aria-labelledby="closed-tab">
														</div>	
													</div>
													
													<div class="tab-content" id="freelancer_tab_div" style="display:none">
														<div class="tab-pane fade show active workOnly" id="active_bids" role="tabpanel" aria-labelledby="active-bids-tab"></div>
														<div class="tab-pane fade workOnly" id="awarded_bids" role="tabpanel" aria-labelledby="awarded-bids-tab"></div>
														<div class="tab-pane fade workOnly" id="in_progress_work" role="tabpanel" aria-labelledby="in-progress-work-tab"></div>
														<div class="tab-pane fade workOnly" id="completed_work" role="tabpanel" aria-labelledby="completed-work-tab"></div>
														<div class="tab-pane fade workOnly" id="hired" role="tabpanel" aria-labelledby="hired-tab"></div>
														<div class="tab-pane fade workOnly" id="in_complete_work" role="tabpanel" aria-labelledby="in-complete-work-tab"></div>
													</div>
												</div>
											</div>
											<!-- My Projects End -->
										</div>
										<!-- <div class="col-md-3 col-sm-3 col-12 rSec">
											
										</div> -->
									</div>
									<!-- Server and Chat End -->
								</div>
							</div>
						</div>
						
						<!-- <div class="col-md-3 col-sm-3 col-12 dbChat"> -->
						<div class="dbChat">
                            <div class="rgtChatLoad"></div>
							<div id="chatMinH" class="rgtChat">
								<div id="sidebar1" class="sidebar1">	
									<div class="top-space">
										<!--<h4><?php //echo $this->config->item('dashboard_contact_list_section_heading'); ?></h4>-->
										<!-- With Chat Section Start -->
										<div class="outChat" style="display:<?php echo count($users_chat_list) == 0 ? 'block' : 'none' ?>">
											<div class="default_blank_message_block" id="noChatList">
												<i class="far fa-bell"></i>
												<?php echo $this->config->item('dashboard_contacts_list_no_record'); ?></div>
										</div>
										<!-- With Chat Section Start -->
										
										<!-- With Chat Section Start -->
									
										<!--<div class="chatScroll">-->
										<div id="chatScroll" class="chatScrolls <?php echo count($users_chat_list) > 0 ? '' : 'd-none'; ?>">
											<div class="mobChat">
												<?php
													if(!empty($users_chat_list) && is_array($users_chat_list)) {
														
													foreach($users_chat_list as $arr) {
														$val = current($arr);
														$user_sess = $this->session->userdata('user');
														$is_active = ($user_sess[0]->user_id != $val['latest_message_sender_id'] && $val['unread_msg_count'] != 0) ? 'active' : '';
												?>
												<div class="media chatBgHover <?php echo $is_active?> contact-bidder" 
													data-name="<?php echo $val['user_name']; ?>"
													data-id="<?php echo $val['user_id']; ?>"
													data-project-title="<?php echo (!empty($val['project_detail']['project_title']))?htmlspecialchars($val['project_detail']['project_title'], ENT_QUOTES):""; ?>"
													data-project-id="<?php echo (!empty($val['project_detail']['project_id']))?$val['project_detail']['project_id']:''; ?>"
													data-profile="<?php echo $val['profile_pic_url']; ?>"
													data-project-owner="<?php echo (!empty($val['project_detail']['project_owner_id']))?$val['project_detail']['project_owner_id']:""; ?>"
													data-source="dashboard"
												>											
													<div class="rChat default_avatar_image" style="background-image: url('<?php echo $val['profile_pic_url'] ?>');"></div>	
													<div class="media-body">
														<!--<div class="cLft <?php echo empty($val['project_detail']) ? 'pt-3' : '' ?>">-->
														<div class="cLft">
															<div class="default_user_name"><!-- <span data-toggle="tooltip" data-placement="left" data-container="body" data-original-title="<?php echo $val['user_name']; ?>"><?php echo $val['user_name'] ?></span> --><span data-container="body" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<?php echo $val['user_name']; ?>"><?php echo $val['user_name'] ?></span></div>
														</div>
														<div class="headline_title txt_color_black <?php echo empty($val['project_detail']) ? 'd-none' : '' ?>">
															<span data-container="body" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<?php echo !empty($val['project_detail']) ?  $val['project_detail']['project_title'] : ''; ?>"><?php echo !empty($val['project_detail']) ?  $val['project_detail']['project_title'] : $this->config->item('chat_room_dashboard_general_chat_label_text'); ?></span>
														</div>
														<div class="cRgt <?php echo empty($val['latest_message_sent_time'] || $val['display_latest_message_sent_time']) ? 'd-none' : '' ?> ">												
															<div class="mRDT">
																<?php
																	if(empty($val['latest_message_sent_timestamp'])) {
																?>
																<span  class="<?php echo empty($val['latest_message_sent_time']) ? 'd-none' : '' ?>"><?php echo !empty($val['latest_message_sent_time']) ? date(DATE_TIME_FORMAT_EXCLUDE_SECOND, strtotime($val['latest_message_sent_time'])) : '' ?></span>
																<?php
																	} else {
																?>
																<span  class="<?php echo empty($val['display_latest_message_sent_time']) ? 'd-none' : '' ?>"><?php echo $val['display_latest_message_sent_time']; ?></span>
																<?php
																	}
																?>
																<small class="chat_count default_counter_notification_red <?php echo  ($user_sess[0]->user_id == $val['latest_message_sender_id'] || $val['unread_msg_count'] == 0) ? 'd-none' : '' ?>"><?php echo $val['unread_msg_count']; ?></small>
																<div class="clearfix"></div>
															</div>
														</div>
													</div>
												</div>
												<div class="clearfix"></div>
												<?php	
													}
												}
												?>					
											</div>
										</div>
										
										
										<!-- With Chat Section End -->
										
										</div>
										
								</div>
								
							</div>
							<div class="moreBtn <?php echo count($users_chat_list) == 0 ? 'd-none' : '' ?>" >
								<button type="button" class="btn default_btn blue_btn btnBold see-more-in-chat-room"><?php echo $this->config->item('dashboard_see_more_in_chat_room_label_text'); ?></button>
							</div>
							<div class="clearfix"></div>
						</div>
					<!-- </div>
				</div> -->
				<!-- Right Section End -->
			<!-- </div> -->
			<!-- Right Section End -->			
		</div>
		<!-- Middle Section End -->
	</div>
<input type="hidden" id="myprojectTab" value="draft">
	<!-- Mobile View Chat Section End -->
	<div class="chatMessage" id="chat_message" ><?php echo $this->config->item('dashboard_contact_list_section_heading'); ?><span class="chatMessage_counter chat_unread_cnt" style="display:none">2</span></div>
	<div id="myChat" class="chatDetails ">
		<div class="myChat ">
			<!-- <h4><?php //echo $this->config->item('dashboard_contact_list_section_heading'); ?></h4> -->
			<div class="chatMessage chatMessageTop" id="chat_message" ><?php echo $this->config->item('dashboard_contact_list_section_heading'); ?><span class="chatMessage_counter chat_unread_cnt" style="display:none;">2</span></div>
			
			<div class="mobChat">
        <!-- With Chat Section Start -->
				<div class="outChat" style="display:<?php echo count($users_chat_list) == 0 ? 'block' : 'none' ?>">
					<div class="default_blank_message_block" id="noChatList">
							<i class="far fa-bell"></i>
							<?php echo $this->config->item('dashboard_contacts_list_no_record'); ?>
					</div>
				</div>
				<!-- With Chat Section Start -->
				<?php
					if(!empty($users_chat_list) && is_array($users_chat_list)) {
					foreach($users_chat_list as $arr) {
						$val = current($arr);
						$user_sess = $this->session->userdata('user');
						$is_active = ($user_sess[0]->user_id != $val['latest_message_sender_id'] && $val['unread_msg_count'] != 0) ? 'active' : '';
				?>
				<div class="media chatBgHover <?php echo $is_active?> contact-bidder" 
					data-name="<?php echo $val['user_name']; ?>"
					data-id="<?php echo $val['user_id']; ?>"
					data-project-title="<?php echo !empty($val['project_detail']) ?htmlspecialchars($val['project_detail']['project_title'], ENT_QUOTES):''; ?>"
					data-project-id="<?php echo !empty($val['project_detail']) ?$val['project_detail']['project_id']:''; ?>"
					data-profile="<?php echo $val['profile_pic_url']; ?>"
					data-project-owner="<?php echo !empty($val['project_detail']) ?$val['project_detail']['project_owner_id']:''; ?>"
					data-source="dashboard"
				>											
					<div class="rChat default_avatar_image" style="background-image: url('<?php echo $val['profile_pic_url'] ?>');"></div>	
					<div class="media-body">
						<div class="cLft <?php echo empty($val['project_detail']) ? 'pt-3' : '' ?>">
							<div class="default_user_name"><!-- <span data-toggle="tooltip" data-placement="left" data-container="body" data-original-title="<?php echo $val['user_name']; ?>"><?php echo $val['user_name'] ?></span> --><span data-container="body" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<?php echo $val['user_name']; ?>"><?php echo $val['user_name'] ?></span></div>
						</div>
						<div class="headline_title txt_color_black <?php echo empty($val['project_detail']) ? 'd-none' : '' ?>">
							<span class="headline_title_popover" data-container="body" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<?php echo !empty($val['project_detail']) ?  $val['project_detail']['project_title'] : ''; ?>"><?php echo !empty($val['project_detail']) ?  $val['project_detail']['project_title'] : $this->config->item('chat_room_dashboard_general_chat_label_text'); ?></span>
						</div>
						<div class="cRgt">												
							<div class="mRDT">
								<?php
									if(empty($val['latest_message_sent_timestamp'])) {
								?>
								<span><?php echo !empty($val['latest_message_sent_time']) ? date(DATE_TIME_FORMAT_EXCLUDE_SECOND, strtotime($val['latest_message_sent_time'])) : '' ?></span>
								<?php
									} else {
								?>
								<span><?php echo $val['display_latest_message_sent_time']; ?></span>
								<?php
									}
								?>
								<small class="chat_count default_counter_notification_red <?php echo  ($user_sess[0]->user_id == $val['latest_message_sender_id'] || $val['unread_msg_count'] == 0) ? 'd-none' : '' ?>"><?php echo $val['unread_msg_count']; ?></small>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<?php	
					}
				}
				?>										
			</div>
			<div class="moreBtn <?php echo count($users_chat_list) == 0 ? 'd-none' : '' ?>">
				<button type="button" class="btn default_btn blue_btn btnBold btn_style_5_10 see-more-in-chat-room"><?php echo $this->config->item('dashboard_see_more_in_chat_room_label_text'); ?></button>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<!-- Modal Start for edit upgrade-->
	<div class="modal fade" id="editUpgradeModalCenter" tabindex="-1" role="dialog" aria-labelledby="editUpgradeModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content etModal">
				<div class="modal-header popup_header popup_header_without_text">
					<h4 class="modal-title popup_header_title" id="upgrade_prolong_popup_heading"></h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body most-project" id="edit_upgrade_popup_body">
					
				</div>
			</div>
		</div>
	</div>
<!-- Modal End -->
	<!-- Modal Start for edit upgrade-->
	<div class="modal fade" id="cancelProjectModal" tabindex="-1" role="dialog" aria-labelledby="cancelProjectModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content etModal">
				<div class="modal-header popup_header popup_header_without_text">
					<h4 class="modal-title popup_header_title" id="cancel_project_modal_title"></h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="popup_body_semibold_title" id="cancel_project_modal_body">
					</div>
					<div class="disclaimer default_disclaimer_text disclaimer_separator">
						<div>
							<label class="default_checkbox">
								<input type="checkbox" id="cancel_project_checkbox_po">
								<span class="checkmark"></span><span id="cancel_project_disclaimer" class="popup_body_regular_checkbox_text"></span>
							</label>
						</div>
					</div>
				</div>
				<div class="modal-footer margin_bottom15">		
					<button type="button" class="btn blue_btn default_btn" data-dismiss="modal"><?php echo $this->config->item('close_btn_txt'); ?></button>			
					<button type="button" disabled style="opacity:0.65" class="btn default_btn red_btn project_cancel_button width-auto" id="cancel_project_modal_cancel_button_txt"></button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal End -->

<script>
var send_feedback_popup_description_max_character_limit = "<?php echo $this->config->item('send_feedback_popup_description_max_character_limit'); ?>";
var popup_alert_heading = "<?php echo $this->config->item('popup_alert_heading'); ?>";
var cancel_open_for_bidding_project_modal_body = '<?php echo $this->config->item('cancel_open_for_bidding_project_modal_body') ?>';
var cancel_open_for_bidding_fulltime_project_modal_body = '<?php echo $this->config->item('cancel_open_for_bidding_fulltime_project_modal_body') ?>';
var cancel_expired_project_modal_body = '<?php echo $this->config->item('cancel_expired_project_modal_body') ?>';
var cancel_expired_fulltime_project_modal_body = '<?php echo $this->config->item('cancel_expired_fulltime_project_modal_body') ?>';

var cancel_disclaimer_modal_body = '<?php echo $this->config->item('user_confirmation_check_box_txt') ?>';




//var find_projects_page_url = '<?php echo $this->config->item('find_projects_page_url') ?>';
//var find_professionals_page_url = '<?php echo $this->config->item('find_professionals_page_url') ?>';
//var edit_project_page_url = '<?php echo $this->config->item('edit_project_page_url') ?>';
var dashboard_page_url = '<?php echo $this->config->item('dashboard_page_url'); ?>';

var send_invite_friends_request_popup_label_txt = "<?php echo $this->config->item('send_invite_friends_request_popup_label_txt'); ?>";
var send_invite_friends_request_limit = "<?php echo $this->config->item('send_invite_friends_request_limit'); ?>";
var send_invite_friends_request_limit_exceed_error_msg = "<?php echo $this->config->item('send_invite_friends_request_limit_exceed_error_msg'); ?>";
//var set_redirection_edit_draft_project = '<?php echo "set_redirection_edit_draft_project" ?>';

</script>	
<script src="<?php echo JS; ?>modules/user_dashboard.js"></script>	
<!-- Below file hold the node functions related with my projects section (dashboard/myproject page) -->
<script src="<?php echo JS; ?>modules/my_projects_management_node.js"></script>	
	<!-- Mobile View Chat Section End -->
<!-- Script Start -->
<script>
	
     
	$(window).on("load scroll touchmove", function() {
		var tabtype = 'draft';
		if(typeof tab_type !== 'undefined') {tabtype = tab_type;}
		//alert(tabtype);
		var sh = $(window).height() - (parseInt($("#headerContent").outerHeight()) + parseInt($("#footer").outerHeight()));
		/*var mh = parseInt($("#headerContent").outerHeight()) +
		parseInt($(".frp").outerHeight()) +
		parseInt($("#latest_open_bidding_project").outerHeight()) +
		parseInt($(".inviteFrndsFooterCalculate").outerHeight()) +
		parseInt($(".mP").outerHeight()) +
		parseInt($(".myproRadio").outerHeight()) +
		parseInt($(".myProDsk").outerHeight()) +
		parseInt($("#" + tabtype).outerHeight()) - 40;*/
		var mh = parseInt($(".frp").outerHeight()) +
		parseInt($("#latest_open_bidding_project").outerHeight()) +
		parseInt($(".inviteFrndsFooterCalculate").outerHeight()) +
		parseInt($(".mP").outerHeight()) +
		parseInt($(".myproRadio").outerHeight()) +
		parseInt($(".myProDsk").outerHeight()) +
		parseInt($("#" + tabtype).outerHeight()) + 10;
		
		var sHeight = sh;
		if (mh > sh) {
			sHeight = mh;
		}
		var see_more_button_h = $(".dbChat .moreBtn").height();
		
		var $el = $('#footer'),
		scrollTop = $(this).scrollTop(),
		scrollBot = scrollTop + $(this).height(),
		elTop = $el.offset().top,
		elBottom = elTop + $el.outerHeight(),
		visibleTop = elTop < scrollTop ? scrollTop : elTop,
		visibleBottom = elBottom > scrollBot ? scrollBot : elBottom;
		
		var vFooter = parseInt(visibleBottom - visibleTop);
		
		if((vFooter+10 > 0 && $("#noChatList").parent().is(':hidden'))) {
			setChatHeight(sHeight, see_more_button_h);
		}
    });		
    function setChatHeight(mh, see_more_btn_height) {
			see_more_btn_height = see_more_btn_height || '';
		var wh = $(window).height()-$("#headerContent").outerHeight();
		
        if(mh>0 && mh <= wh) {
            var ch = mh - 10;
        } else {
            var ch = wh;
		}
		if(see_more_btn_height) {
			//ch -= see_more_btn_height;
		}
		$(".dbChat .moreBtn").fadeIn(2500);
		
		var nochatH = ch;
		//alert(see_more_btn_height);
		$('#chatMinH').removeClass('doubleLineBtn').addClass('singleLineBtn');
		if(see_more_btn_height > 36) {
			$('#chatMinH').removeClass('singleLineBtn').addClass('doubleLineBtn');
		}
		var nochatScroll = 78;
		if($(window).outerWidth()<1299) { nochatScroll = 96; }
		if($(window).outerWidth()<1250) { nochatScroll = 111;}
		//alert($("#noChatList").parent().parent().parent().parent().html())
		if($("#noChatList").parent().is(':visible')) {
			$(".dbMiddle").css('min-height', '');
			$("#noChatList").parent().parent().parent().parent().css({'min-height':'100%'}); 
		}
		$("#noChatList").css({ 
			"height": nochatH-2,
			"overflow-y": "hidden"
		});
		
		if(nochatH <= nochatScroll) {
			$(".rgtChat").removeAttr('style').css({'display':'block'});
			$("#noChatList").css({ 
				 "overflow-y": "scroll"
			});
		}
		var contact_bidder = 0;
		$('.contact-bidder').each(function() {
		  contact_bidder += $(this).outerHeight();
		});
		var bCH = parseInt($(".dbMiddle").outerHeight());
		
		if (contact_bidder>ch){
			console.log(1111);
			$("#chatScroll").removeClass('overflow-hidden').addClass('overflow-y-scroll');
			
				$("#chatScroll").css({  
					 "height": ch - see_more_btn_height
				});
        } else {
			$("#chatScroll").removeClass('overflow-y-scroll').addClass('overflow-hidden');
			if($("#chatScroll").hasClass('overflow-hidden')) {
				$("#chatScroll").removeAttr('style');
			}
			$(".chatScrolls").css({ 
				"height": contact_bidder,
			});
		}
    }
</script>
<!-- Show More and Show Less Script Start -->	
<script>

	$(document).ready(function(){
            $('.nav-tabs').scrollingTabs();
            if($(window).outerWidth()==1600){
				//start scrolling tab update
				$('.nav-tabs').scrollingTabs('refresh');
				$('.scrtabs-tabs-fixed-container').removeClass("tab_with_arrow").addClass("tab_without_arrow");
				if($(".scrtabs-tab-scroll-arrow").is(':visible')) {
					$('.scrtabs-tabs-fixed-container').removeClass("tab_without_arrow").addClass("tab_with_arrow");
				}
				//$('.scrtabs-tabs-fixed-container').width($(".myproTabs").width());
				//start scrolling tab update
            }
            
            $("#demo,#demo1").on("hide.bs.collapse", function(){
                    $(".smlBtn").html('<?php echo $this->config->item('show_more_txt'); ?>');
            });
            $("#demo,#demo1").on("show.bs.collapse", function(){
                    $(".smlBtn").html('<?php echo $this->config->item('show_less_txt'); ?>');
            });
	});
</script>
<!-- Show More and Show Less Script End -->	
<script>

    $(document).ready(function(){
		
		if ( typeof tab_type === 'undefined' ) {
			tab_type = 'draft';
		}
		
		
        <?php if($this->config->item('dashboard_left_projects')>0) { ?>
            setTimeout(function() {
        <?php } ?>    
				activeTab(tab_type);
				
				
                $(".rgtChat").fadeIn(1500);
			
        <?php if($this->config->item('dashboard_left_projects')>0) { ?>
            }, 1000);
        <?php } ?>
    });
	// track width, set to window width
	var width = $(window).width();
	
    var resizeTimer;
    $(window).resize(function(){
		//$(".dbMiddle, .dbChat").removeAttr('style');
		$(".dbMiddle, .dbChat").css('min-height', '');		
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
			//start scrolling tab update
			$('.nav-tabs').scrollingTabs('refresh');
			$('.scrtabs-tabs-fixed-container').removeClass("tab_with_arrow").addClass("tab_without_arrow");
			if($(".scrtabs-tab-scroll-arrow").is(':visible')) {
				$('.scrtabs-tabs-fixed-container').removeClass("tab_without_arrow").addClass("tab_with_arrow");
			}
			//$('.scrtabs-tabs-fixed-container').width($(".myproTabs").width());
			//start scrolling tab update
        }, 250);
        $('#sidebar').css({"position": ""});
	
			var sh = $(window).height() - (parseInt($("#headerContent").outerHeight()) + parseInt($("#footer").outerHeight()));
			
			var mh = parseInt($(".frp").outerHeight()) +
			parseInt($("#latest_open_bidding_project").outerHeight()) +
			parseInt($(".inviteFrndsFooterCalculate").outerHeight()) +
			parseInt($(".mP").outerHeight()) +
			parseInt($(".myproRadio").outerHeight()) +
			parseInt($(".myProDsk").outerHeight()) +
			parseInt($("#" + tab_type).outerHeight()) + 10;
			
			var sHeight = sh;
			if (mh > sh) {
				sHeight = mh;
			}
			
			

			$(".rgtChat").fadeIn();
			var see_more_button_h = $(".dbChat .moreBtn").height();
			
			var $el = $('#footer'),
			scrollTop = $(this).scrollTop(),
			scrollBot = scrollTop + $(this).height(),
			elTop = $el.offset().top,
			elBottom = elTop + $el.outerHeight(),
			visibleTop = elTop < scrollTop ? scrollTop : elTop,
			visibleBottom = elBottom > scrollBot ? scrollBot : elBottom;
			
			var vFooter = parseInt(visibleBottom - visibleTop);
			//console.log("Footer Height==="+vFooter+"===Footer=="+parseInt($("#footer").outerHeight()));
			if((vFooter+10) < 0 || vFooter==parseInt($("#footer").outerHeight()) || $(window).width()!=width) {
				setTimeout(function () {
					//alert(1);
					setChatHeight(sHeight, see_more_button_h);
					
				}, 100);
			}
			//alert($(window).width()+"==="+width);
			width = $(window).width();
			delete width;
	
			
			//if($(window).width())
			//setChatHeight(sHeight, see_more_button_h);
			setMenuHeight(mh);
			
			//footer height manage
			var wheight = $(window).height();
			
			var theight = parseInt($(".dbMiddle").outerHeight()) + parseInt($("#headerContent").outerHeight())+parseInt($("#footer").outerHeight())+10;
			var menuHeight = parseInt(sHeight) + parseInt($("#headerContent").outerHeight()) + parseInt($("#footer").outerHeight());
	
			if (theight > wheight || (menuHeight > wheight && $(window).outerWidth() > dDwidth)) {
				$("#footer").removeClass("footerFixed").addClass("footerVisible");
			} else {
				$("#footer").removeClass("footerVisible").addClass("footerFixed");
			}
			
		
			
				if ($(window).outerWidth() >=850) {
					$('#myChat').css({'display':'none'});
				}
    });

</script>
<!-- Mobile View Chat Script Start -->
<script>
    function myFunction() {
        
    }
</script>
<!-- Mobile View Chat Script Start -->

<!-- Project Section Three Line text Script Start -->
<script>
	$("p").each(function() {
	   var textMaxChar = $(this).attr('data-max-characters');

	   length = $(this).text().length;
	   if(length > textMaxChar) {
		   $(this).text($(this).text().substr(0, textMaxChar) + '...');
	   }
	});
</script>
<!-- Project Section Three Line text Script End -->
<!-- Project Section More/Less Script Start -->
<script>
    $(function(){
        $("#div2").hide();
        $("#preview").on("click", function(){
            $("#div2").toggle();
            $("#preview1").css("display","block");
            $("#preview").css("display","none");
        });
        $("#preview1").on("click", function(){
            $("#div2").toggle();
            $("#preview1").css("display","none");
            $("#preview").css("display","block");
        });
    });
</script>
<!-- Project Section More/Less Script End -->
<!-- When Click Copy Button then Only Input Type Text Selected Script Start  -->
<script>
    function copyToClipboard() {
        /* Get the text field */
        var copyText = document.getElementById("refferal_link");
        /* Select the text field */
        copyText.select();
        /* Copy the text inside the text field */
        document.execCommand("copy");
    }
    $('.bstooltip').tooltip({
        trigger: 'manual',
        placement: 'top'
      });
    $(document).on('mousedown', '.bstooltip', function () {
        setTimeout(copy_tooltip_hold, 200);
        setTimeout(function(){
            $(this).tooltip('hide');
        }, 5000);
    });
    $('.bstooltip').mouseleave(function(){
        $(this).tooltip('hide');
    });
    function copy_tooltip_hold() {
        $('.bstooltip').tooltip('show');
    }
</script>
<!-- Copy Text and Onclick Tooltip End -->
<script>
	$(document).ready(function(){
		$('[data-toggle="popover"]').popover({
            boundary: 'window'
	});
	});
</script>