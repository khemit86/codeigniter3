<div class="dashTop">	
	<!-- Menu Icon on Responsive View Start -->	
	<?php echo $this->load->view('user_left_menu_mobile.php'); ?>
	<!-- Menu Icon on Responsive View End -->
	<div class="wrapper wrapper1">
		<!-- Left Menu Start -->
		<?php echo $this->load->view('user_left_nav.php'); ?>
		<!-- Left Menu End -->
		<!-- Right Section Start -->
		<div id="content" class="invite_friends_page">
			<div class="rightSec">
				<!-- Middle Section Start -->
				<div class="default_page_heading">
					<h4><?php echo $this->config->item('invite_your_friends_txt'); ?></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<div class="row inviteMainSection">					
					<div class="col-md-12 col-sm-12 col-12 inviteMainPart">
						<!-- Invite Friends Start -->
						<div class="default_block_header_transparent invFrnd nBorder">
							<div class="transparent"><?php echo $this->config->item('invite_friends_txt'); ?></div>
							<div class="ifECC transparent_body">
								<div class="row">
									<div class="col-md-6 col-sm-6 col-12 ifEmail">
										<div class="emlCont">
											<h6><?php echo $this->config->item('dashboard_invite_friends_for_email_contacts'); ?><i class="fa fa-question-circle tooltipAuto" data-toggle="tooltip" data-placement="top" title="<?php echo $this->config->item('dashboard_invite_friends_email_contacts_tooltip'); ?>"></i></h6>
											<p><b><?php echo $this->config->item('dashboard_invite_friends_for_add_email_address'); ?></b><?php echo $this->config->item('dashboard_invite_friends_for_separate_with_spaces_commas'); ?></p>
											<div class="clearfix"></div>
											<div id="emailtag">
												<input type="text" class="default_input_field input_fieldHidden" value="" data-role="tagsinput" placeholder="" />
											</div>
										</div>
										<div class="sendInvitations">
											<span class="sendInvtButton"><button type="button" class="btn default_btn blue_btn send_invite_friend_request" disabled ><?php echo $this->config->item('invite_friends_send_invitations_btn_txt'); ?></button></span>
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
												<a href="" class="fab fa-facebook-f fb_referral_share" data-referral-url="<?php echo $fb_share_link; ?>"></a>
												<a href="" class="fab fa-linkedin-in ln_referral_share" data-referral-url="<?php echo $linkedin_share_link; ?>" ></a>
												<a href="" class="fab fa-twitter twitter_referral_share" data-referral-url="<?php echo $twitter_share_link; ?>" data-share-message="<?php echo $twitter_share_message; ?>"></a>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
									<!-- Referral Sources Statistics Start -->
									<div class="default_social_value socialValue_adjust">
										<div class="rsource"><h6><?php echo $this->config->item('invite_friends_referral_sources_statistics_lbl'); ?></h6></div>
										<div class="socialTag">
											<label>
												<div class="fb">
													<i class="fab fa-facebook-f"></i><b><?php echo $this->config->item('invite_friends_referral_sources_statistics_facebook_lbl'); ?><span><?php echo $invited_friends_registered_via_fb_count;?></span></b>
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
						</div>
						<!-- Invite Friends End -->
						
						<!-- Today's Registrations Start -->
						<div class="default_block_header_transparent nBorder padding_top10 invFrnd">
							<div class="transparent"><?php echo $this->config->item('referral_statistics_heading'); ?></div>
							<div class="ifECC transparent_body">
								<div class="refStat_section">
									<div class="row refStat_section_row">
										<div class="col-md-6 col-sm-6 col-12 referalStatistics_left">
											<!-- Referral Statistics Radio Button Start -->
											<div class="default_radio_button refStat">
												<section>
													<div>
														<input type="radio" id="inlineRadio5" class="referral_statistics_lvl" name="Level1Level2" value="1" checked>
														<label for="inlineRadio5" style="pointer-events:none;">
															<span><?php echo $this->config->item('lvl1_referral_statistics_lbl'); ?></span>
														</label>
													</div>
													<div>
														<input type="radio" id="inlineRadio6" class="referral_statistics_lvl" name="Level1Level2" value="2">
														<label for="inlineRadio6">
															<span><?php echo $this->config->item('lvl2_referral_statistics_lbl'); ?></span>
														</label>
													</div>
												</section>
											</div>
											<!-- Referral Statistics Radio Button End -->
											<?php
												$regi_disclaimer = $earn_disclaimer = ''; 
												if($user_details['current_membership_plan_id'] == 1 && (empty($user_downgrade_plan_details))) {
													$regi_disclaimer = $this->config->item('free_membership_subscriber_disclaimer_message_without_downgrade_plan_history_lvl2_referral_statistics_registrations_tab');
													$regi_disclaimer = str_replace('{membership_page_url}', $this->config->item('membership_page_url'), $regi_disclaimer);

													$earn_disclaimer = $this->config->item('free_membership_subscriber_disclaimer_message_without_downgrade_plan_history_lvl2_referral_statistics_earnings_tab');
													$earn_disclaimer = str_replace('{membership_page_url}', $this->config->item('membership_page_url'), $earn_disclaimer);

												} else if ($user_details['current_membership_plan_id'] == 1 && (!empty($user_downgrade_plan_details))){
													$regi_disclaimer = $this->config->item('free_membership_subscriber_disclaimer_message_when_downgrade_plan_history_lvl2_referral_statistics_registrations_tab');
													$regi_disclaimer = str_replace('{membership_downgrade_date}', date(DATE_TIME_FORMAT_EXCLUDE_SECOND, strtotime($user_downgrade_plan_details['membership_plan_effective_downgrade_start_date'])), $regi_disclaimer);

													$earn_disclaimer = $this->config->item('free_membership_subscriber_disclaimer_message_when_downgrade_plan_history_lvl2_referral_statistics_earnings_tab');
													$earn_disclaimer = str_replace('{membership_downgrade_date}', date(DATE_TIME_FORMAT_EXCLUDE_SECOND, strtotime($user_downgrade_plan_details['membership_plan_effective_downgrade_start_date'])), $earn_disclaimer);
												}
											?>
											<?php 
												if(!empty($regi_disclaimer)) {
											?>
											<div class="invite_friends_free_subscriber_disclaimer" style="display : none;">
												<span class="invite_friend_dislaimer_position regi_tab_disclimer" style="display:none;"><?php echo $regi_disclaimer; ?></span>
												<span class="invite_friend_dislaimer_position earn_tab_disclimer" style="display:none;"><?php echo $earn_disclaimer; ?></span>
											</div>
											<?php
												}
											?>
											<!-- Registrations and Earnings Radio Button Start -->	
											<div class="default_radio_button radio_top_border">
												<section>
													<div>
														<input type="radio" id="inlineRadio7" class="reg_earn" name="regEarn" value="1" checked>
														<label for="inlineRadio7" style="pointer-events:none;">
															<span><?php echo $this->config->item('invite_friends_registrations_referrals_statistics_lbl'); ?></span>
														</label>
													</div>
													<div>
														<input type="radio" id="inlineRadio8" class="reg_earn" name="regEarn" value="2">
														<label for="inlineRadio8">
															<span><?php echo $this->config->item('invite_friends_earnings_referrals_statistics_lbl'); ?></span>
														</label>
													</div>
												</section>
											</div>
											<!-- Registrations and Earnings Radio Button End -->
											
											<!-- Total Count Start -->
											<div class="totalCount">
												<div class="row">
													<div class="col-md-3 col-sm-3 col-12 zisky_section zisky_section_div">
														<div class="dateCount">
															<small><?php echo $this->config->item('referral_statistics_all_time_lbl'); ?><span class="lvl1_regi" style="display:block;"><?php echo format_money_amount_display($lvl1_all_time_registration_cnt); ?></span>
															<span class="lvl2_regi" style="display:none;"><?php echo format_money_amount_display($lvl2_all_time_registration_cnt); ?></span>
															<span class="lvl1_earn" style="display:none;"><?php echo format_money_amount_display($lvl1_all_time_earnings_value).' '.CURRENCY; ?></span>
															<span class="lvl2_earn" style="display:none;"><?php echo format_money_amount_display($lvl2_all_time_earnings_value).' '.CURRENCY; ?></span></small>
														</div>
													</div>
													<div class="col-md-3 col-sm-3 col-12 plNone zisky_section zisky_section_div">
														<div class="todayCount">
															<small><?php echo $this->config->item('referral_statistics_today_lbl'); ?><span class="lvl1_regi" style="display:block;"><?php echo format_money_amount_display($lvl1_today_registration_cnt); ?></span>
															<span class="lvl2_regi" style="display:none;"><?php echo format_money_amount_display($lvl2_today_registration_cnt); ?></span>
															<span class="lvl1_earn" style="display:none;"><?php echo format_money_amount_display($lvl1_today_earnings_value).' '.CURRENCY; ?></span>
															<span class="lvl2_earn" style="display:none;"><?php echo format_money_amount_display($lvl2_today_earnings_value).' '.CURRENCY; ?></span></small>
														</div>
													</div>
													<div class="col-md-3 col-sm-3 col-12 plNone zisky_section zisky_section_div">
														<div class="weekCount">
															<small><?php echo $this->config->item('referral_statistics_this_week_lbl'); ?><span class="lvl1_regi" style="display:block;"><?php echo format_money_amount_display($lvl1_this_week_registration_cnt); ?></span>
															<span class="lvl2_regi" style="display:none;"><?php echo format_money_amount_display($lvl2_this_week_registration_cnt); ?></span>
															<span class="lvl1_earn" style="display:none;"><?php echo format_money_amount_display($lvl1_this_week_earnings_value).' '.CURRENCY; ?></span>
															<span class="lvl2_earn" style="display:none;"><?php echo format_money_amount_display($lvl2_this_week_earnings_value).' '.CURRENCY; ?></span></small>
														</div>
													</div>
													<div class="col-md-3 col-sm-3 col-12 plNone zisky_section_div">
														<div class="monthCount">
															<small><?php echo $this->config->item('referral_statistics_this_month_lbl'); ?><span class="lvl1_regi" style="display:block;"><?php echo format_money_amount_display($lvl1_this_month_registration_cnt); ?></span>
															<span class="lvl2_regi" style="display:none;"><?php echo format_money_amount_display($lvl2_this_month_registration_cnt); ?></span>
															<span class="lvl1_earn" style="display:none;"><?php echo format_money_amount_display($lvl1_this_month_earnings_value).' '.CURRENCY; ?></span>
															<span class="lvl2_earn" style="display:none;"><?php echo format_money_amount_display($lvl2_this_month_earnings_value).' '.CURRENCY; ?></span></small>
														</div>
													</div>
												</div>
											</div>
											<!-- Total Count End -->
										</div>
										<div class="col-md-6 col-sm-6 col-12 referalStatistics_right">
											<!-- Radio Button Start -->
											<div class="default_radio_button">
												<section>
													<div>
														<input type="radio" id="by_day" name="chatView" value="by_day" checked onclick="showview('byDay_view')">
														<label for="by_day" style="pointer-events:none;">
															<span><?php echo $this->config->item('invite_friends_by_day_referrals_statistics_lbl'); ?></span>
														</label>
													</div>
													<div>
														<input type="radio" id="by_week" name="chatView" value="by_week" onclick="showview('byWeek_view')">
														<label for="by_week">
															<span><?php echo $this->config->item('invite_friends_by_week_referrals_statistics_lbl'); ?></span>
														</label>
													</div>
													<div>
														<input type="radio" id="by_month" name="chatView" value="by_month" onclick="showview('byMonth_view')">
														<label for="by_month">
															<span><?php echo $this->config->item('invite_friends_by_month_referrals_statistics_lbl'); ?></span>
														</label>
													</div>
												</section>
											</div>
											<!-- Radio Button End -->
											
											<!-- Radio Body Start -->
											<div class="allTime">
												<div id="by_day_div"  class="chart-container" style="display: block;">
													<canvas id="barchartDay" width="400" height="120"></canvas>
												</div>
												<div id="by_week_div" class="chart-container" style="display: none;">
													<canvas id="barchartWeek" width="400" height="120"></canvas>
												</div>
												<div id="by_month_div" class="chart-container" style="display: none;">
													<canvas id="barchartMonth" width="400" height="120"></canvas>
												</div>
											</div>
											<!-- Radio Body End -->	
										</div>
										<div class="clearfix"></div>										
									</div>
								</div>
                                                            
								<div class="amountSec">
									<label>
										<small class="default_black_bold_medium"><span data-toggle="tooltip" id="user_account_balance_title" data-placement="top" data-original-title="<?php echo $this->config->item('user_referral_earnings_account_balance_lbl'); ?>: <?php echo format_money_amount_display($total_earnings_lvl1_lvl2).' '.CURRENCY; ?>"><?php echo $this->config->item('user_referral_earnings_account_balance_lbl'); ?>:</span><span id="user_account_balance"><?php echo format_money_amount_display($total_earnings_lvl1_lvl2).' '.CURRENCY; ?></span></small>
									</label><label><button class="btn blue_btn default_btn confirm_withdraw_funds"><?php echo $this->config->item('withdraw_referral_earnings_funds_btn'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button></label>
									<div class="minimum_withdraw_amount_error" style="display:none;"><span class="error_msg"></span></div>
								</div>
								
								<div>
									<div class="receive_notification withdraw_funds_heading" id="locationSL" style="display: <?php echo !empty($withdraw_funds) ? 'block' : 'none'; ?>">					
										<a class="rcv_notfy_btn location_option chk-btn" onclick="showMorePow()" id="location_heading"><?php echo $this->config->item('referral_earnings_withdraw_transaction_history_label_txt'); ?><small>( + )</small></a>
										<input type="hidden" id="morePow" value="1" name="location_option">
									</div>
									<div id="more_pow" class="collapse location_section withdraw_funds_data">
										<?php
											foreach($withdraw_funds as $val) { 
										?>
										<div class="dfThistory">
											<label><div><b class="default_black_bold_medium"><?php echo $this->config->item('invite_friends_withdrawal_request_amount_label_txt'); ?>:</b><span class="default_black_regular_medium dfThistory_currency"><?php echo $val['referral_earnings_withdrawal_requested_amount'].' '.CURRENCY; ?></span></div></label><?php
												if($val['referral_earnings_withdrawal_request_status'] == 'admin review') {
											?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('invite_friends_withdrawal_request_submit_date_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['referral_earnings_withdrawal_request_submit_date'])); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('invite_friends_withdrawal_request_status_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo ucfirst($this->config->item('invite_friends_withdrawal_request_status_review_by_admin_txt')); ?></span></div></label><?php
												}
											?><?php
												if($val['referral_earnings_withdrawal_request_status'] == 'rejected') {
											?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('invite_friends_withdrawal_request_submit_date_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['referral_earnings_withdrawal_request_submit_date'])); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('invite_friends_withdrawal_request_status_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $this->config->item('invite_friends_withdrawal_request_status_rejected_by_admin_txt'); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('invite_friends_withdrawal_request_admin_processing_date_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['referral_earnings_withdrawal_request_admin_processing_date'])); ?></span></div></label><?php
												}
											?><?php 
												if($val['referral_earnings_withdrawal_request_status'] == 'approved') {
											?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('invite_friends_withdrawal_request_status_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $this->config->item('invite_friends_withdrawal_request_status_approved_by_admin_txt'); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('invite_friends_withdrawal_request_approval_date_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['referral_earnings_withdrawal_request_admin_processing_date'])); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('invite_friends_transaction_id_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['referral_earnings_withdrawal_transaction_id']; ?></span></div></label><?php
												}
											?>
										</div>
										<?php 
											}
										?>
									</div>
								</div>
							</div>
						</div>
						<!-- Today's Registrations End -->
						
						<!-- Today's Registrations (No Referral Statistics) Start -->
						
						
						<!-- Manage Email Invitations Start -->
						<div class="default_block_header_transparent invFrnd manage_email_invitaitons">
							<div class="transparent"><?php echo $this->config->item('manage_email_invitations_heading'); ?></div>
							<div class="ifECC manageEmail transparent_body">
								<div class="row">
									<div class="col-md-12 col-sm-12 col-12">
										<!-- Checkbox Start -->
										<div class="default_checkbox_button manage_email_invitations_chkbox three_checkbox"><span class="singleLine_chkBtn"><input type="checkbox" id="pending_invitations" name="contacts_management_checkbox" value="pending_invitations" class="chk-btn account_overview_tab manage_invitations_tab" data-target="pending_invitations_container"><label class="singleLine_radioBtn" for="pending_invitations"><span><?php echo $this->config->item('tab_pending_invitation'); ?></span></label></span><span class="singleLine_chkBtn"><input type="checkbox" id="accepted_invitations" name="contacts_management_checkbox" value="accepted_invitations" class="chk-btn account_overview_tab manage_invitations_tab" data-target="accepted_invitations_container"><label class="singleLine_radioBtn" for="accepted_invitations"><span><?php echo $this->config->item('tab_accepted_invitation'); ?></span></label></span><span class="singleLine_chkBtn"><input type="checkbox" id="revoked_invitations" name="contacts_management_checkbox" value="revoked_invitations" class="chk-btn account_overview_tab manage_invitations_tab" data-target="revoked_invitations_container"><label class="singleLine_radioBtn" for="revoked_invitations"><span><?php echo $this->config->item('tab_revoked_invitation'); ?></span></label></span></div>
										<!-- Checkbox Start -->
												
										<!-- Tab Body Start -->
										<div class="cmFieldText">
											<div id="pending_invitations_container" class="cmField d-none">
												<!-- When No Content Start -->
												<div class="default_blank_message pending no_pending_invitaions" style="display: none;"><?php echo $this->config->item('no_pending_invitation'); ?></div>
												<!-- When No Content End -->
												<div class="manage_wrapper">
												
												</div>
											</div>
											<div id="accepted_invitations_container" class="cmField d-none">
												<!-- When No Content Start -->
												<div class="default_blank_message pending no_accepted_invitations" style="display:none;"><?php echo $this->config->item('no_accepted_invitation'); ?></div>
												<!-- When No Content End -->
												<!-- When Content Start -->
												<div class="manage_wrapper">
													
													
												</div>
												<!-- When Content End -->
											</div>
											<div id="revoked_invitations_container" class="cmField d-none">
												<!-- When No Content Start -->
												<div class="default_blank_message pending no_revoked_invitations" style="display: none;"><?php echo $this->config->item('no_revoked_invitation'); ?></div>
												<!-- When No Content End -->
												<!-- When Content Start -->
												<div class="manage_wrapper">
													
													
												</div>
												<!-- When Content End -->
											</div>
										</div>
										<!-- Tab Body End -->
									</div>
									<div class="clearfix"></div>			
								</div>
							</div>
						</div>
						<!-- Manage Email Invitations End -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
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
			<div class="modal-footer mg-bottom-10 invt_modal_footer">
				<div class="row">
					<div class="col-sm-12 col-lg-12 col-12" id="confirmation_modal_footer">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal End -->
<script>
	var deposit_funds_transaction_history_label_txt = "<?php echo $this->config->item('referral_earnings_withdraw_transaction_history_label_txt'); ?>";

	var withdrawal_request_amount_label_txt = "<?php echo $this->config->item('invite_friends_withdrawal_request_amount_label_txt'); ?>";
	var withdrawal_request_submit_date_label_txt = "<?php echo $this->config->item('invite_friends_withdrawal_request_submit_date_label_txt'); ?>";
	var withdrawal_request_status_label_txt = "<?php echo $this->config->item('invite_friends_withdrawal_request_status_label_txt'); ?>";
	var withdrawal_request_admin_processing_date_label_txt = "<?php echo $this->config->item('invite_friends_withdrawal_request_admin_processing_date_label_txt'); ?>";
	var withdrawal_request_approval_date_label_txt = "<?php echo $this->config->item('invite_friends_withdrawal_request_approval_date_label_txt'); ?>";
	var transaction_id_label_txt = "<?php echo $this->config->item('invite_friends_transaction_id_label_txt'); ?>";
	var withdrawal_request_status_rejected_by_admin_txt = "<?php echo $this->config->item('invite_friends_withdrawal_request_status_rejected_by_admin_txt'); ?>";
	var withdrawal_request_status_approved_by_admin_txt = "<?php echo $this->config->item('invite_friends_withdrawal_request_status_approved_by_admin_txt'); ?>";
	var withdrawal_request_status_review_by_admin_txt = "<?php echo $this->config->item('invite_friends_withdrawal_request_status_review_by_admin_txt'); ?>";
	var currency = "<?php echo CURRENCY; ?>";

	var send_invite_friends_request_popup_label_txt = "<?php echo $this->config->item('send_invite_friends_request_popup_label_txt'); ?>";
	var send_invite_friends_request_limit = "<?php echo $this->config->item('send_invite_friends_request_limit'); ?>";
	var send_invite_friends_request_limit_exceed_error_msg = "<?php echo $this->config->item('send_invite_friends_request_limit_exceed_error_msg'); ?>";
	
	var lvl1_earnings_month_chart_y_axis_label = "<?php echo $this->config->item('lvl1_earnings_month_chart_y_axis_label'); ?>";
	var lvl1_earnings_month_chart_data_label = "<?php echo $this->config->item('lvl1_earnings_month_chart_data_label'); ?>";
	
	var lvl1_registration_month_chart_y_axis_label = "<?php echo $this->config->item('lvl1_registrations_month_chart_y_axis_label'); ?>";
	var lvl1_registration_month_chart_data_label = "<?php echo $this->config->item('lvl1_registrations_month_chart_data_label'); ?>";
	
	var lvl1_registration_day_chart_y_axis_label = "<?php echo $this->config->item('lvl1_registrations_day_chart_y_axis_label'); ?>";
	var lvl1_registration_day_chart_data_label = "<?php echo $this->config->item('lvl1_registrations_day_chart_data_label'); ?>";
	
	var lvl1_earnings_day_chart_y_axis_label = "<?php echo $this->config->item('lvl1_earnings_day_chart_y_axis_label'); ?>";
	var lvl1_earnings_day_chart_data_label = "<?php echo $this->config->item('lvl1_earnings_day_chart_data_label'); ?>";
	
	var lvl1_registrations_week_chart_y_axis_label = "<?php echo $this->config->item('lvl1_registrations_week_chart_y_axis_label'); ?>";
	var lvl1_registrations_week_chart_data_label = "<?php echo $this->config->item('lvl1_registrations_week_chart_data_label'); ?>";
	
	var lvl1_earnings_week_chart_y_axis_label = "<?php echo $this->config->item('lvl1_earnings_week_chart_y_axis_label'); ?>";
	var lvl1_earnings_week_chart_data_label = "<?php echo $this->config->item('lvl1_earnings_week_chart_data_label'); ?>";

	var lvl2_registration_month_chart_y_axis_label = "<?php echo $this->config->item('lvl2_registrations_month_chart_y_axis_label'); ?>";
	var lvl2_registration_month_chart_data_label = "<?php echo $this->config->item('lvl2_registrations_month_chart_data_label'); ?>";
	
	var lvl2_earnings_month_chart_y_axis_label = "<?php echo $this->config->item('lvl2_earnings_month_chart_y_axis_label'); ?>";
	var lvl2_earnings_month_chart_data_label = "<?php echo $this->config->item('lvl2_earnings_month_chart_data_label'); ?>";
	
	var lvl2_registration_day_chart_y_axis_label = "<?php echo $this->config->item('lvl2_registrations_day_chart_y_axis_label'); ?>";
	var lvl2_registration_day_chart_data_label = "<?php echo $this->config->item('lvl2_registrations_day_chart_data_label'); ?>";
	
	var lvl2_earnings_day_chart_y_axis_label = "<?php echo $this->config->item('lvl2_earnings_day_chart_y_axis_label'); ?>";
	var lvl2_earnings_day_chart_data_label = "<?php echo $this->config->item('lvl2_earnings_day_chart_data_label'); ?>";
	
	var lvl2_registrations_week_chart_y_axis_label = "<?php echo $this->config->item('lvl2_registrations_week_chart_y_axis_label'); ?>";
	var lvl2_registrations_week_chart_data_label = "<?php echo $this->config->item('lvl2_registrations_week_chart_data_label'); ?>";
	
	var lvl2_earnings_week_chart_y_axis_label = "<?php echo $this->config->item('lvl2_earnings_week_chart_y_axis_label'); ?>";
	var lvl2_earnings_week_chart_data_label = "<?php echo $this->config->item('lvl2_earnings_week_chart_data_label'); ?>";

	var month_chart_label_values = JSON.parse('<?php echo json_encode($month_chart_label_values); ?>');
	var day_chart_label_values = JSON.parse('<?php echo json_encode($day_chart_label_values); ?>');
	var week_chart_label_values = JSON.parse('<?php echo json_encode($week_chart_label_values); ?>');

	var lvl1_registraiton_month_chart_data_values = JSON.parse('<?php echo $lvl1_registraiton_month_chart_data_values; ?>');
	var lvl2_registraiton_month_chart_data_values = JSON.parse('<?php echo $lvl2_registraiton_month_chart_data_values; ?>');

	var lvl1_earnings_month_chart_data_values = JSON.parse('<?php echo $lvl1_earnings_month_chart_data_values; ?>');
	var lvl2_earnings_month_chart_data_values = JSON.parse('<?php echo $lvl2_earnings_month_chart_data_values; ?>');
	
	var lvl1_registration_day_chart_data_values = JSON.parse('<?php echo $lvl1_registration_day_chart_data_values; ?>');
	var lvl2_registration_day_chart_data_values = JSON.parse('<?php echo $lvl2_registration_day_chart_data_values; ?>');
	
	var lvl1_earnings_day_chart_data_values = JSON.parse('<?php echo $lvl1_earnings_day_chart_data_values; ?>');
	var lvl2_earnings_day_chart_data_values = JSON.parse('<?php echo $lvl2_earnings_day_chart_data_values; ?>');

	var lvl1_registration_week_chart_data_values = JSON.parse('<?php echo $lvl1_registration_week_chart_data_values; ?>');
	var lvl2_registration_week_chart_data_values = JSON.parse('<?php echo $lvl2_registration_week_chart_data_values; ?>');
	
	var lvl1_earnings_week_chart_data_values = JSON.parse('<?php echo $lvl1_earnings_week_chart_data_values; ?>');
	var lvl2_earnings_week_chart_data_values = JSON.parse('<?php echo $lvl2_earnings_week_chart_data_values; ?>');
	
</script>
<script src="<?php echo ASSETS; ?>js/charts/Chart.min.js"></script>	
<script src="<?php echo ASSETS; ?>js/charts/utils.js"></script>
<script src="<?php echo ASSETS; ?>js/modules/invite_friends.js"></script>