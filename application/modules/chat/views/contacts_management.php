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
        <div id="content" class="contacts_management_page body_distance_adjust">
			<div class="etSecond_step" id="work_experience_listing_data" style="display:block;">
				<!-- Profile Management Text Start -->
				<div class="default_page_heading">
					<h4><?php echo $this->config->item('contacts_management_headline_title_contacts_management'); ?></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
				<!-- contact requests Start -->
				
				<!-- Checkbox Start -->
				<div class="default_checkbox_button three_checkbox">
					<span class="singleLine_chkBtn">
						<input type="checkbox" id="contact_requests" name="contacts_management_checkbox" value="contact_requests" class="chk-btn trigger" data-trigger="hidden_fields_one">
						<label class="singleLine_radioBtn" for="contact_requests">
							<span class="mr-0">
								<?php echo $this->config->item('contacts_management_box_headline_title_contact_requests') ;?><span class="default_counter_notification_red badge badge_leftGap" id="pending_request" style="display:<?php echo ($pending_contacts_count > 0) ? 'inline-block' : 'none'; ?>"><?php echo $pending_contacts_count < 99 ? $pending_contacts_count : '99+'; ?></span>
							</span>
						</label>
					</span>
					<span class="singleLine_chkBtn">
						<input type="checkbox" id="rejected_contact_requests" name="contacts_management_checkbox" value="rejected_contact_requests" class="chk-btn trigger" data-trigger="hidden_fields_two">
						<label class="singleLine_radioBtn" for="rejected_contact_requests">
							<span class="mr-0">
								<?php echo $this->config->item('contacts_management_box_headline_title_rejected_contact_requests') ;?><span class="default_counter_notification_red badge badge_leftGap" id="rejected_request" style="display:<?php echo ($rejected_contacts_count > 0) ? 'inline-block' : 'none' ?>"><?php echo $rejected_contacts_count < 99 ? $rejected_contacts_count : '99+'; ?></span>
							</span>
						</label>
					</span>
					<span class="singleLine_chkBtn">
						<input type="checkbox" id="blocked_contacts" name="contacts_management_checkbox" value="blocked_contacts" class="chk-btn trigger" data-trigger="hidden_fields_three">
						<label class="singleLine_radioBtn" for="blocked_contacts">
							<span class="mr-0">
								<?php echo $this->config->item('contacts_management_box_headline_title_blocked_contacts') ;?><span class="default_counter_notification_red badge badge_leftGap" id="blocked_request" style="display:<?php echo ($blocked_contacts_count > 0) ? 'inline-block' : 'none'; ?>"><?php echo $blocked_contacts_count < 99 ? $blocked_contacts_count : '99+'; ?></span>
							</span>
						</label>
					</span>
				</div>
				<!-- Checkbox Start -->
				<!-- Checkbox Content Start -->
				<div class="cmFieldText">
					<div class="cmField hiddenCMcheckbox pending_request" id="hidden_fields_one">
						<div class="content_wrapper">				
						<!-- Education Training End -->
						<?php 
							foreach($pending_contacts as $val) {
						?>
						<div class="wkExp">
							<div class="cmLeftSection cmLeftDesc">
								<div class="default_avatar_image hireAvtPpicture margin_bottom0" style="background-image: url('<?php echo $val['user_avatar'];?>');"></div>
								<div class="cmButtonOnly">
									<button class="btn default_btn red_btn request_action" data-action="reject" data-profile-name="<?php echo $val['display_name']; ?>" data-profile-url="<?php echo base_url($val['profile_name']); ?>" data-id="<?php echo $val['user_id']; ?>"><?php echo $this->config->item('get_in_contact_request_reject_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
									<button class="btn default_btn green_btn request_action" data-action="accept" data-profile-name="<?php echo $val['display_name']; ?>" data-profile-url="<?php echo base_url($val['profile_name']); ?>" data-id="<?php echo $val['user_id']; ?>"><?php echo $this->config->item('accept_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
								</div>
							</div>
							<div class="cmRightSection">
								<div class="userLeft_section"><div class="default_user_name default_continue_text"><a class="default_user_name_link" target="_blank" href="<?php echo base_url($val['profile_name']); ?>"><?php echo $val['display_name'] ?></a></div></div>
								<div class="clearfix"></div>
								<?php
									if(!empty($val['headline'])) {
								?>
								<div class="headline_title"><?php echo $val['headline']; ?></div>
								<?php 
									}
								?>
								<div class="cmBtn">
									<div class="userRight_section"><label><?php echo date(DATE_TIME_FORMAT, strtotime($val['get_in_contact_request_send_date'])); ?></label></div>
									<div class="default_error_red_message pull-left d-none" id="expired_err_<?php echo $val['user_id']; ?>">
										<span></span>
									</div>
									<div class="cmButton">
										<button class="btn default_btn red_btn request_action" data-action="reject" data-profile-name="<?php echo $val['display_name']; ?>" data-profile-url="<?php echo base_url($val['profile_name']); ?>" data-id="<?php echo $val['user_id']; ?>"><?php echo $this->config->item('get_in_contact_request_reject_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
										<button class="btn default_btn green_btn request_action" data-action="accept" data-profile-name="<?php echo $val['display_name']; ?>" data-profile-url="<?php echo base_url($val['profile_name']); ?>" data-id="<?php echo $val['user_id']; ?>"><?php echo $this->config->item('accept_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
									</div>
								</div>
							</div>
							<!-- <div class="clearfix"></div> -->
						</div>
						<?php 
							}
						?>
						<!-- Contacts Management End -->
						</div>
						<!-- Pagination Start -->
						<div class="pagnOnly" style="display:<?php echo $pending_contacts_count > 0 ? 'block' : 'none' ?>">
							<div class="row">
								<div class="no_page_links <?php echo !empty($pending_contacts_pagination_links) ? 'col-md-7 col-sm-7 col-12' : 'col-md-12 col-12'; ?>">
									<?php
										$rec_per_page = ($pending_contacts_count > $this->config->item('get_in_contact_pending_requests_listing_limit_per_page')) ? $this->config->item('get_in_contact_pending_requests_listing_limit_per_page') : $pending_contacts_count;
									?>
									<div class="pageOf">
										<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $pending_contacts_count; ?></span><?php echo $this->config->item('listings_pagination_txt') ?></label>
									</div>
								</div>
								<div class="page_links col-md-5 col-sm-5 col-12" style="display:<?php echo !empty($pending_contacts_pagination_links) ? 'block' : 'none'; ?>">
										<div class="modePage">
											<?php echo $pending_contacts_pagination_links; ?>
										</div>
								</div>
							</div>
						</div>
						<!-- Pagination End -->
						<div class="default_blank_message no_data" style="display:<?php echo $pending_contacts_count == 0 ? 'block' : 'none'; ?>"><?php echo $this->config->item('get_in_contact_pending_requests_no_request_found'); ?></div>
					</div>
					<div class="cmField hiddenCMcheckbox rejected_request" id="hidden_fields_two">
						<div class="content_wrapper">
						<?php 
							foreach($rejected_contacts as $val) {
						?>
						<div class="wkExp">
							<div class="cmLeftSection cmLeftDesc">
								<div class="default_avatar_image hireAvtPpicture margin_bottom0" style="background-image: url('<?php echo $val['user_avatar'];?>');"></div>
								<div class="cmButtonOnly">
									<button class="btn default_btn green_btn request_action" data-action="accepts_rejected" data-profile-name="<?php echo $val['display_name']; ?>" data-profile-url="<?php echo base_url($val['profile_name']); ?>" data-id="<?php echo $val['user_id']; ?>"><?php echo $this->config->item('accept_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
								</div>
							</div>
							<div class="cmRightSection">
								<div class="userLeft_section"><div class="default_user_name default_continue_text"><a class="default_user_name_link" target="_blank" href="<?php echo base_url($val['profile_name']); ?>"><?php echo $val['display_name'] ?></a></div></div>
								<div class="clearfix"></div>
								<?php
									if(!empty($val['headline'])) {
								?>
								<div class="headline_title"><?php echo $val['headline']; ?></div>
								<?php 
									}
								?>
								<div class="cmBtn">
									<div class="userRight_section"><label><?php echo date(DATE_TIME_FORMAT, strtotime($val['get_in_contact_request_send_date'])); ?></label></div>
									<div class="default_error_red_message pull-left d-none" id="expired_err_<?php echo $val['user_id']; ?>">
										<span></span>
									</div>
									<div class="cmButton">
										<button class="btn default_btn green_btn request_action" data-action="accepts_rejected" data-profile-name="<?php echo $val['display_name']; ?>" data-profile-url="<?php echo base_url($val['profile_name']); ?>" data-id="<?php echo $val['user_id']; ?>"><?php echo $this->config->item('accept_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
									</div>
								</div>
							</div>
							<!-- <div class="clearfix"></div> -->
						</div>
						<?php 
							}
						?>
						</div>
						<!-- Pagination Start -->
						<div class="pagnOnly" style="display:<?php echo $rejected_contacts_count > 0 ? 'block' : 'none' ?>">
							<div class="row">
								<div class="no_page_links <?php echo !empty($rejected_contacts_pagination_links) ? 'col-md-7 col-sm-7 col-12' : 'col-md-12 col-12'; ?>">
									<?php
										$rec_per_page = ($rejected_contacts_count > $this->config->item('get_in_contact_rejected_requests_listing_limit_per_page')) ? $this->config->item('get_in_contact_rejected_requests_listing_limit_per_page') : $rejected_contacts_count;
									?>
									<div class="pageOf">
										<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page;  ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $rejected_contacts_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
									</div>
								</div>
								<div class="page_links col-md-5 col-sm-5 col-12" style="display:<?php echo !empty($rejected_contacts_pagination_links) ? 'block' : 'none'; ?>">
										<div class="modePage">
											<?php echo $rejected_contacts_pagination_links; ?>
										</div>
								</div>
							</div>
						</div>
						<!-- Pagination End -->
						<div class="default_blank_message no_data" style="display:<?php echo $rejected_contacts_count == 0 ? 'block' : 'none'; ?>">
							<?php echo $this->config->item('get_in_contact_rejected_requests_no_request_found'); ?>
						</div>
					</div>
					<div class="cmField hiddenCMcheckbox blocked_request" id="hidden_fields_three">
						<div class="content_wrapper">
						<?php 
							foreach($blocked_contacts as $val) {
						?>
						<div class="wkExp">
							<div class="cmLeftSection cmLeftDesc">
								<div class="default_avatar_image hireAvtPpicture margin_bottom0" style="background-image: url('<?php echo $val['user_avatar'];?>');"></div>
								<div class="cmButtonOnly">
									<button class="btn default_btn blue_btn un_block" data-action="unblock" data-profile-name="<?php echo $val['display_name']; ?>" data-profile-url="<?php echo base_url($val['profile_name']); ?>" data-id="<?php echo $val['user_id']; ?>"><?php echo $this->config->item('get_in_contact_request_unblock_btn_txt'); ?></button>
								</div>
							</div>
							<div class="cmRightSection">
								<div class="userLeft_section"><div class="default_user_name default_continue_text"><a class="default_user_name_link" target="_blank" href="<?php echo base_url($val['profile_name']); ?>"><?php echo $val['display_name'] ?></a></div></div>
								<div class="clearfix"></div>
								<?php
									if(!empty($val['headline'])) {
								?>
								<div class="headline_title"><?php echo $val['headline']; ?></div>
								<?php 
									}
								?>
								<div class="cmBtn">
									<div class="userRight_section"><label><?php echo date(DATE_TIME_FORMAT, strtotime($val['contact_block_date'])); ?></label></div>
									<div class="default_error_red_message pull-left d-none" id="expired_err_<?php echo $val['user_id']; ?>">
										<span></span>
									</div>
									<div class="cmButton">
										<button class="btn default_btn blue_btn un_block" data-action="unblock" data-profile-name="<?php echo $val['display_name']; ?>" data-profile-url="<?php echo base_url($val['profile_name']); ?>" data-id="<?php echo $val['user_id']; ?>" data-toggle="modal" data-target="#userUnBlockModal"><?php echo $this->config->item('get_in_contact_request_unblock_btn_txt'); ?></button>
									</div>
								</div>
							</div>
							<!-- <div class="clearfix"></div> -->
						</div>
						<?php 
							}
						?>
						</div>
						<!-- Pagination Start -->
						<div class="pagnOnly" style="display:<?php echo $blocked_contacts_count > 0 ? 'block' : 'none' ?>">
							<div class="row">
								<div class="no_page_links <?php echo !empty($blocked_contacts_pagination_links) ? 'col-md-7 col-sm-7 col-12' : 'col-md-12 col-12'; ?>">
									<?php
										$rec_per_page = ($blocked_contacts_count > $this->config->item('get_in_contact_blocked_requests_listing_limit_per_page')) ? $this->config->item('get_in_contact_blocked_requests_listing_limit_per_page') : $blocked_contacts_count;
									?>
									<div class="pageOf">
										<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page;  ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $blocked_contacts_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
									</div>
								</div>
								<div class="page_links col-md-5 col-sm-5 col-12" style="display:<?php echo !empty($blocked_contacts_pagination_links) ? 'block' : 'none'; ?>">
										<div class="modePage">
											<?php echo $blocked_contacts_pagination_links; ?>
										</div>
								</div>
							</div>
						</div>
						<!-- Pagination End -->


						<div class="default_blank_message no_data" style="display:<?php echo $blocked_contacts_count == 0 ? 'block' : 'none'; ?>">
							<?php echo $this->config->item('get_in_contact_blocked_requests_no_request_found'); ?>
						</div>
					</div>
				</div>
				<!-- Checkbox Content End -->
			
			
			
			
			
			
           
			
			
			
			
        </div>
        <!-- Right Section End -->
    </div>
    <!-- Middle Section End -->
	
	<!-- Modal Start for edit upgrade-->
	<div class="modal fade" id="userUnBlockModal" tabindex="-1" role="dialog"  aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content etModal">
				<div class="modal-header popup_header popup_header_without_text">
					<h4 class="modal-title popup_header_title" id="user_un_block_modal_title"><?php echo $this->config->item('user_unblock_contact_modal_title') ?></h4>
					<button type="button" class="close reload_modal" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="popup_body_semibold_title" id="user_un_block_modal_body"></div>
					<div class="disclaimer default_disclaimer_text disclaimer_separator">
						<div>
							<label class="default_checkbox">
								<input type="checkbox" id="user_un_block_checkbox_po">
								<span class="checkmark"></span><span id="user_block_disclaimer" class="popup_body_regular_checkbox_text"><?php echo $this->config->item('user_confirmation_check_box_txt'); ?></span>
							</label>
						</div>
					</div>
				</div>
				<div class="modal-footer">					
					<button type="button" disabled style="opacity:0.65" class="btn default_btn red_btn width-auto" id="user_un_block_modal_block_button_txt"><?php echo $this->config->item('user_unblock_contact_modal_unblock_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal End -->
</div>
<script type="text/javascript">
var get_in_contact_request_accept_btn_txt = '<?php echo $this->config->item('accept_btn_txt'); ?>';
var get_in_contact_request_reject_btn_txt = '<?php echo $this->config->item('get_in_contact_request_reject_btn_txt'); ?>';
var user_unblock_contact_modal_body_title = '<?php echo $this->config->item('user_unblock_contact_modal_body_title'); ?>';
</script>
<script src="<?= ASSETS ?>js/modules/contacts_management.js"></script>