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
        <div id="content" class="withdraw_funds_page body_distance_adjust">
			<div class="etSecond_step" id="work_experience_listing_data" style="display:block;">
				<!-- Profile Management Text Start -->
				<div class="default_page_heading">
					<h4><?php echo $this->config->item('finance_headline_title_withdraw_funds'); ?></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
				<!-- contact requests Start -->
				
				<!-- Checkbox Start -->
				<div class="default_checkbox_button withdrawCheckbox two_checkbox"><span class="singleLine_chkBtn"><input type="checkbox" id="contact_requests" name="contacts_management_checkbox" value="contact_requests" class="chk-btn trigger" data-trigger="hidden_fields_one"><label class="singleLine_radioBtn" for="contact_requests"><span><?php echo $this->config->item('finance_page_checkbox_withdraw_via_paypal'); ?></span></label></span><span class="singleLine_chkBtn"><input type="checkbox" id="rejected_contact_requests" name="contacts_management_checkbox" value="rejected_contact_requests" class="chk-btn trigger" data-trigger="hidden_fields_two"><label class="singleLine_radioBtn" for="rejected_contact_requests"><span><?php echo $this->config->item('finance_page_checkbox_withdraw_via_bank_transfer'); ?></span></label></span></div>
				<!-- Checkbox Start -->
				<!-- Checkbox Content Start -->
				<div class="cmFieldText">
					<div class="cmField hiddenCMcheckbox" id="hidden_fields_one">
						<div class="withFunds">
							<div class="payPalText">
								<div class="input-group">
									<label class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_via_paypal_email_account_label_txt'); ?></label>
									<input type="text" class="form-control avoid_space default_input_field"  id="paypal_account">
								</div>
								<span id="paypal_email_error" class="error_msg required withdraw_funds_email_err"></span>
								<!-- <div class="error_div_sectn clearfix">
								</div> -->
							</div>									
							
							<div class="amtChoose">
								<div class="ppAccount">
									<div class="amountText">
										<div class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_amount_receive_in_paypal_label_txt'); ?></div>
									</div>
									<div class="currencyType">
										<div class="input-group">
											<input type="text" class="form-control avoid_space default_input_field" maxlength="<?php echo $this->config->item('withdraw_funds_via_paypal_amount_length_character_limit'); ?>" id="withdraw_funds">
											<div class="input-group-append">
												<span class="input-group-text default_black_bold_medium"><?php echo CURRENCY; ?></span>
											</div>
										</div>
									</div>
									<span class="error_msg required withdraw_funds_email_err"></span>
									<div class="clearfix"></div>
								</div>
								<div class="wAccount">
									<div class="amountText">
										<label class="default_black_bold_medium">
											<?php echo $this->config->item('withdraw_funds_amount_to_withdraw_via_paypal_label_txt'); ?></label><i class="fa fa-question-circle default_icon_help tooltipAuto" style="margin:auto;"  data-toggle="tooltip" data-placement="top" title="<?php echo $this->config->item('withdraw_funds_processing_fees_info'); ?>"></i>
									</div>
									<div class="currencyType">
										<div class="input-group">
											<input type="text" class="form-control avoid_space default_input_field p-0" id="withdraw_funds_with_fees" readonly>
											<div class="input-group-append">
												<span class="input-group-text default_black_bold_medium"><?php echo CURRENCY; ?></span>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								
								</div>
								
							</div>
							<div class="error_div_sectn clearfix">
								<div id="withdraw_amount_error" class="withdrawMesg error_msg required d-none">You have insufficient funds in this currency.</div>
							</div>
							<button id="withdraw_fund_submit" class="btn default_btn blue_btn withdrawFunds_paypalBtn"  disabled><?php echo $this->config->item('withdraw_funds_btn_txt'); ?></button>
						</div>
						<!-- Contacts Management End -->
						<div class="projTitle withdraw_transaction_list" style="display:<?php echo !empty($paypal_transactions) ? 'block' : 'none'; ?>">							
							<div class="receive_notification" id="tagSL">
								<a class="rcv_notfy_btn show_more_transaction" 
										data-text="<?php echo $this->config->item('withdraw_funds_via_paypal_transaction_history_label_txt');  ?>" 
										data-reference="moreTags_paypal"
									 	id="project_tag_heading_section"><?php echo $this->config->item('withdraw_funds_via_paypal_transaction_history_label_txt'); ?><small>( + )</small></span></a>
								<input type="hidden" id="moreTags_paypal" value="1">
							</div>
							<div id="moreTags_paypal_lst" class="collapse row withdraw_transactions">
								<?php
									if(!empty($paypal_transactions)) {
										foreach($paypal_transactions as $val) {
								?>
								<div class="col-md-12 col-sm-12 col-12 dfThistory">
									<?php
										if($val['request_status'] == 'Pending') {
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdrawal_request_amount_label_txt'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo $val['withdrawal_requested_amount'].' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdrawal_request_submit_date_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['withdrawal_request_submit_date'])) ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdrawal_to_paypal_account_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['withdraw_to_paypal_account']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdrawal_request_status_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $this->config->item('withdrawal_request_via_paypal_admin_review_status_txt'); ?></span></div></label><?php
										}
									?>
									<?php 
										if($val['request_status'] == 'Approved') {
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_amount_label_txt'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo $val['withdrawal_requested_amount'].' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdrawal_request_approval_date_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])) ?></span></div></label><?php 
										if($val['transaction_status'] == 'Successful') {
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transaction_date_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])) ?></span></div></label>
									<?php 
										}
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('paypal_account_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['withdraw_to_paypal_account']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transaction_id_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span></div></label><?php
										if($val['transaction_status'] == 'Successful') {
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transaction_status_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $this->config->item('withdrawal_request_via_paypal_transaction_success_status_txt'); ?></span></div></label><?php 
										} else {
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transaction_status_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $this->config->item('withdrawal_request_via_paypal_transaction_failed_status_txt'); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transaction_failure_reason_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo ucfirst($val['failure_reason']); ?></span></div></label><?php 
										}
									?>
									<?php 
										}
									?>
									<?php 
										if($val['request_status'] == 'Rejected') {
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_amount_label_txt'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo $val['withdrawal_requested_amount'].' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdrawal_request_submit_date_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['withdrawal_request_submit_date'])) ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('paypal_account_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['withdraw_to_paypal_account']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdrawal_request_status_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $this->config->item('withdrawal_request_via_paypal_rejected_by_admin_status_txt'); ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdrawal_request_rejection_date_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['request_rejection_date'])) ?></span></div></label><?php		
										}
									?>
								</div>					
								<?php
										}
									}
								?>
							</div>
						</div>
					</div>
						
					<!-- </div> -->
					<div class="cmField hiddenCMcheckbox" id="hidden_fields_two">
						<div class="default_blank_message d-none">You do not currently any active milestone payment.</div>
						<!-- When Data Start -->
						
						<!-- Payment Details Text Start -->
						<div class="payDetails default_user_description">
							<p><?php echo $this->config->item('withdraw_funds_bank_heading'); ?></p>
						</div>
						<!-- Payment Details Text End -->
						
						<!-- Bank Details Start -->						
						<div class="transactionDetails">			
							<form id="fm_bank_transfer">				
								<div class="row">
									<div class="col-md-6 col-sm-6 col-12 wfOne">
										<div class="form-group">
											<label class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_withdrawal_amount_lbl'); ?></label>
											<div class="input-group">
												<input type="text" class="form-control default_input_field text-right" id="withdraw_amount" maxlength="<?php echo $this->config->item('withdraw_funds_direct_bank_transfer_amount_length_character_limit'); ?>" name="withdraw_amount">
												<div class="input-group-append">
													<span class="input-group-text default_black_bold_medium"><?php echo CURRENCY; ?></span>
												</div>
											</div>
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg withdraw_amount_err"></span>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12 wfTwo">
										<div class="form-group">
											<label class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_account_owner_lbl'); ?></label>
											<input type="text" class="form-control avoid_space default_input_field" name="account_owner">
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg account_owner_err"></span>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12 wfThree">
										<div class="form-group">
											<label class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_account_number_lbl'); ?></label>
											<input type="text" class="form-control avoid_space default_input_field" name="account_number">
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg account_number_err"></span>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12 wfFour">
										<div class="form-group">
											<label class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_name_lbl'); ?></label>
											<input type="text" class="form-control avoid_space default_input_field" name="bank_name">
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg bank_name_err"></span>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12 wfFive">
										<div class="form-group">
											<label class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_code_lbl'); ?></label>
											<input type="text" class="form-control avoid_space default_input_field" name="bank_code">
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg bank_code_err"></span>
										</div>
									</div>
									<?php
										if(!empty($user_data['user_bank_withdrawal_variable_symbol'])) {
									?>
									<div class="col-md-6 col-sm-6 col-12 wfSix">
										<div class="form-group">
											<label class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_user_bank_variable_symbol_lbl'); ?></label>
											<input type="text" class="form-control avoid_space default_input_field" id="variable_symbol" disabled value="<?php echo $user_data['user_bank_withdrawal_variable_symbol']; ?>">
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg"></span>
										</div>
									</div>
									<?php 
										}
									?>
									<div class="col-md-12 col-sm-12 col-12 default_country fontSize0 wfSeven">
											<label class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_country_lbl'); ?></label>
											<label class="form-group default_dropdown_select withdrawCountry">
												<select id="company_country" name="country">
													<option value="" class="d-none"><?php echo $this->config->item('withdraw_funds_bank_country'); ?></option>
													<?php foreach ($countries as $country): ?>
													<option value="<?php echo $country['id']; ?>" ><?php echo $country['country_name'] ?></option>
													<?php endforeach; ?>
												</select>
											</label>
										<div class="error_div_sectn clearfix">
											<span class="error_msg country_err"></span>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12 cz_country d-none wfEight">
										<div class="form-group">
											<label class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_iban_lbl'); ?></label>
											<input type="text" class="form-control avoid_space default_input_field" name="iban">
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg iban_err"></span>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12 cz_country d-none wfNine">
										<div class="form-group">
											<label class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_bic_or_swift_code_lbl'); ?></label>
											<input type="text" class="form-control avoid_space default_input_field" name="swift_code">
										</div>
										<div class="error_div_sectn clearfix">
											<span class="error_msg bic_swift_code_err"></span>
										</div>
									</div>									
									<div class="col-md-12 col-sm-12 col-12 text-center wfTen">
										<button class="btn default_btn blue_btn register_transaction"><?php echo $this->config->item('withdraw_funds_register_transaction_btn'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
									</div>
								</div>
							</form>
						</div>
						<!-- Bank Details End -->
						
						<!-- Note Details Start -->
						<div class="noteDetails"><span class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_note_lbl'); ?>:</span><?php echo $this->config->item('withdraw_funds_bank_note_txt'); ?></div>
						<!-- Note Details End -->
						
						<!-- When Data End -->
						<div class="projTitle direct_bank_transfer_heading" style="display:<?php echo !empty($bank_transactions) ? 'block' : 'none'; ?>">							
							<div class="receive_notification" id="tagSL">
								<a class="rcv_notfy_btn show_more_transaction" 
										data-text="<?php echo $this->config->item('withdraw_funds_via_paypal_transaction_history_label_txt');  ?>" 
										data-reference="moreTags_bank_transfer"
									 	id="project_tag_heading_section"><?php echo $this->config->item('withdraw_funds_via_paypal_transaction_history_label_txt'); ?><small>( + )</small></a>
								<input type="hidden" id="moreTags_bank_transfer" value="1">
							</div>
							<div id="moreTags_bank_transfer_lst" class="collapse row direct_bank_transfer_data">
								<?php
									if(!empty($bank_transactions)) {
										foreach($bank_transactions as $val) {
								?>
								<div class="col-md-12 col-sm-12 col-12 dfThistory">
									<label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_withdrawal_amount_lbl'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo $val['withdraw_amount'].' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_withdraw_request_date_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['user_withdraw_request_date']; ?></span></div></label><?php
										if($val['status'] == 'request_pending_admin_confirmation') {
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_withdraw_request_status_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $this->config->item('withdraw_funds_direct_bank_transfer_admin_pending_confirmation_status_txt'); ?></span></div></label><?php
										}
									?><?php 
										if($val['status'] == 'request_confirmed_by_admin') {
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_transaction_id_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_transaction_id']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_transaction_date_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_transaction_date']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_withdraw_request_status_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['admin_manual_entry'] ? $this->config->item('withdraw_funds_direct_bank_transfer_added_by_admin_status_txt') : $this->config->item('withdraw_funds_direct_bank_transfer_confirmed_by_admin_status_txt'); ?></span></div></label><?php 
										}
									?><?php 
										if($val['status'] == 'request_rejected_by_admin') {
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_withdraw_request_status_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $this->config->item('withdraw_funds_direct_bank_transfer_rejected_by_admin_status_txt'); ?></span></div></label><?php		
										}
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_account_owner_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_account_owner_name']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_account_number_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_account_number']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_name_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_name']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_code_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_code']; ?></span></div></label><?php
										if(!empty($user_data['user_bank_withdrawal_variable_symbol'])){
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_user_bank_variable_symbol_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $user_data['user_bank_withdrawal_variable_symbol']; ?></span></div></label><?php
										}
									?><?php 
										if($val['bank_account_iban_code'] != '') {
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_iban_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_account_iban_code']; ?></span></div></label><?php
										}
									?><?php 
										if($val['bank_account_bic_swift_code'] != '') {
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_bic_or_swift_code_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_account_bic_swift_code']; ?></span></div></label><?php
										}
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('withdraw_funds_bank_country_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['country_name'];; ?></span></div></label>
								</div>
								<?php
										}
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
				<!-- Checkbox Content End -->
			
        </div>
        <!-- Right Section End -->
    </div>
    <!-- Middle Section End -->
	
</div>
<script type="text/javascript">
	 var withdraw_funds_withdraw_amount_lbl = "<?php echo $this->config->item('withdraw_funds_withdrawal_amount_lbl'); ?>";
	 var withdraw_funds_bank_account_owner_lbl = "<?php echo $this->config->item('withdraw_funds_bank_account_owner_lbl'); ?>";
	 var withdraw_funds_bank_account_number_lbl = "<?php echo $this->config->item('withdraw_funds_bank_account_number_lbl'); ?>";
	 var withdraw_funds_bank_name_lbl = "<?php echo $this->config->item('withdraw_funds_bank_name_lbl'); ?>";
	 var withdraw_funds_bank_code_lbl = "<?php echo $this->config->item('withdraw_funds_bank_code_lbl'); ?>";
	 var withdraw_funds_iban_lbl = "<?php echo $this->config->item('withdraw_funds_iban_lbl'); ?>";
	 var withdraw_funds_bank_bic_or_swift_code_lbl = "<?php echo $this->config->item('withdraw_funds_bank_bic_or_swift_code_lbl'); ?>";
	 var withdraw_funds_bank_country_lbl = "<?php echo $this->config->item('withdraw_funds_bank_country_lbl'); ?>";
	 var transaction_request_date = "<?php echo $this->config->item('transaction_request_date'); ?>";
	 var withdraw_funds_direct_bank_transfer_pending_confirmation_status_txt = "<?php echo $this->config->item('withdraw_funds_direct_bank_transfer_admin_pending_confirmation_status_txt'); ?>";
	 var withdraw_funds_direct_bank_transfer_confirmed_status_txt = "<?php echo $this->config->item('withdraw_funds_direct_bank_transfer_confirmed_by_admin_status_txt'); ?>";
	 var withdraw_funds_direct_bank_transfer_rejected_by_admin_status_txt = "<?php echo $this->config->item('withdraw_funds_direct_bank_transfer_rejected_by_admin_status_txt'); ?>";
	 var withdraw_funds_direct_bank_transfer_added_by_admin_status_txt = "<?php echo $this->config->item('withdraw_funds_direct_bank_transfer_added_by_admin_status_txt'); ?>";

	 var withdraw_funds_withdraw_request_lbl = "<?php echo $this->config->item('withdraw_funds_withdraw_request_date_lbl'); ?>";
	 var withdraw_funds_withdraw_request_status_lbl = "<?php echo $this->config->item('withdraw_funds_withdraw_request_status_lbl'); ?>";
	 var withdraw_funds_bank_transaction_id_lbl = "<?php echo $this->config->item('withdraw_funds_bank_transaction_id_lbl'); ?>";
	 var withdraw_funds_bank_transaction_date_lbl = "<?php echo $this->config->item('withdraw_funds_bank_transaction_date_lbl'); ?>";
	 
	 var withdraw_funds_amount_length_character_limit = "<?php echo $this->config->item('withdraw_funds_via_paypal_amount_length_character_limit'); ?>";
	 var withdraw_funds_direct_bank_transfer_amount_length_character_limit = "<?php echo $this->config->item('withdraw_funds_direct_bank_transfer_amount_length_character_limit'); ?>";
	 
	 var withdrawal_request_via_paypal_admin_review_status_txt = "<?php echo $this->config->item('withdrawal_request_via_paypal_admin_review_status_txt'); ?>";
	 var withdrawal_request_via_paypal_rejected_by_admin_status_txt = "<?php echo $this->config->item('withdrawal_request_via_paypal_rejected_by_admin_status_txt'); ?>";
	 var withdrawal_request_via_paypal_transaction_success_status_txt = "<?php echo $this->config->item('withdrawal_request_via_paypal_transaction_success_status_txt'); ?>";
	 var withdrawal_request_via_paypal_transaction_failed_status_txt = "<?php echo $this->config->item('withdrawal_request_via_paypal_transaction_failed_status_txt'); ?>";

	var withdraw_funds_user_bank_variable_symbol_lbl = '<?php echo $this->config->item('withdraw_funds_user_bank_variable_symbol_lbl'); ?>';
	var reference_country_id = '<?php echo $this->config->item('reference_country_id'); ?>';
	var variable_symbol = '<?php echo $user_data['user_bank_withdrawal_variable_symbol']; ?>';
	 
</script>
<script src="<?= ASSETS ?>js/modules/withdraw_funds.js"></script>