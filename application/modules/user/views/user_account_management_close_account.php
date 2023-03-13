<?php $user = $this->session->userdata('user'); ?>
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
        <div id="content" class="account_management_close_account_page body_distance_adjust <?php echo !empty($close_account_request_detail) ? 'no_data_msg_display_center' : '' ?>">
			<div class="etSecond_step" >
			
			
				<div style="display:<?php echo !empty($close_account_request_detail) ? 'block' : 'none' ?>">
					<div class="initialViewNorecord">
						<h4>
						<?php echo str_replace('{close_account_request_sent_time}',date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($close_account_request_detail['close_account_request_sent_time'])),$this->config->item('user_close_account_request_already_sent_message')); ?>
						</h4>
					</div>
				</div>
			
				<div  id="close_account_form_container" style="display:<?php echo empty($close_account_request_detail) ? 'block' : 'none' ?>">
					<!-- Profile Management Text Start -->
					<div class="default_page_heading">
						<h4><div><?php echo $this->config->item('account_management_headline_title_close_account'); ?></div></h4>
						<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
					</div>
					<!-- Profile Management Text End -->
					<!-- Content Start -->
					<div class="cmFieldText">
						<div id="close_account_container" class="pmdonotSection cmField">
							<!-- Step 1st Start -->
							<div class="pmFirstStep close_account_field">
								<div id="editService_provider" class="mrgBtm0 closeService_provider">
									
									<div class="amCA">
										<div class="default_user_description"><?php echo $this->config->item('account_management_close_account_page_terms_first_description_text'); ?></div>
										<div class="default_user_description"><?php echo $this->config->item('account_management_close_account_page_terms_second_description_text'); ?></div>
										<div class="close_account_textarea_section">
											<div class="close_account_txtPanel">
												<div class="form-group default_dropdown_select">
													<label class="default_black_bold_medium"><?php echo $this->config->item('account_management_close_account_page_close_account_reason_of_close_txt'); ?></label>
													<div class="form-group default_dropdown_select">
														<select name="close_reason" id="close_reason">
															<option value=""><?php echo $this->config->item('account_management_close_account_page_close_account_reason_default_option_name'); ?></option>
															<?php
															$reasons_dropdown_option = $this->config->item('account_management_close_account_page_close_account_reasons_dropdown');
															foreach($reasons_dropdown_option as $value){
															?>
															<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
															<?php
															}
															?>
														</select>
													</div>
													<div class="error_div_sectn clearfix">
														<span class="error_msg" id="close_reason_error"></span> 
														
													</div>
												</div>
												
												<div class="form-group amTextarea default_bottom_border">
													<label for="message" class="default_black_bold_medium"><?php echo $this->config->item('account_management_close_account_page_reason_description_txt'); ?></label>
													<textarea class="default_textarea_field avoid_space_textarea" rows="10" id="reason_description" name="reason_description"></textarea>
													<div class="error_div_sectn clearfix">
														<span class="error_msg_description" id="reason_description_error"></span>
														<span class="content-count reason_description_length_count_message"><?php echo $this->config->item('account_management_close_account_page_maximum_length_character_limit_reason_description')."&nbsp;".$this->config->item('characters_remaining_message'); ?></span> 
													</div>
												</div>
												<div class="amBtn">
													<button type="button" class="btn default_btn blue_btn" id="close_account_confirmation"><?php echo $this->config->item('account_management_close_account_page_confirmation_btn_text'); ?></button>
												</div>
											</div>
										</div>
										<div class="default_user_description"><?php echo $this->config->item('account_management_close_account_page_terms_third_description_text'); ?></div>
									</div>
								</div>
							</div>
							<!-- Step 1st End -->							
						</div>						
					</div>
					<!-- Content End -->
				</div>
			</div>
			<!-- Right Section End -->
		</div>
    <!-- Middle Section End -->	
	</div>
	
<!-- Modal Start for close account-->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"  aria-hidden="true">
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
			<div class="modal-footer">
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
var uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
</script>	
<script src="<?= ASSETS ?>js/modules/user_account_management_close_account.js"></script>
<script>
var character_remaining_message = "<?php echo $this->config->item('characters_remaining_message'); ?>";
var reason_description_minimum_length_character_limit = "<?php echo $this->config->item('account_management_close_account_page_minimum_length_character_limit_reason_description'); ?>";
var reason_description_maximum_length_character_limit = "<?php echo $this->config->item('account_management_close_account_page_maximum_length_character_limit_reason_description'); ?>";
var reason_description_minimum_length_words_limit = "<?php echo $this->config->item('account_management_close_account_page_minimum_length_words_limit_reason_description'); ?>";
</script>	