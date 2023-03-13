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
        <div id="content" class="deposit_funds_page body_distance_adjust">
			<div class="etSecond_step" id="work_experience_listing_data" style="display:block;">
				<!-- Profile Management Text Start -->
				<div class="default_page_heading">
					<h4><?php echo $this->config->item('finance_headline_title_deposit_funds'); ?></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
				<!-- contact requests Start -->
				
				<!-- Checkbox Start -->
				<div class="default_checkbox_button depositCheckbox three_checkbox">
					<span class="singleLine_chkBtn">
						<input type="checkbox" id="contact_requests" name="contacts_management_checkbox" value="contact_requests" class="chk-btn trigger" data-trigger="hidden_fields_one">
						<label class="singleLine_radioBtn" for="contact_requests">
							<span><?php echo $this->config->item('deposit_funds_page_checkbox_deposit_via_paypal'); ?></span>
						</label></span><span class="singleLine_chkBtn">
						<input type="checkbox" id="blocked_contacts" name="contacts_management_checkbox" value="blocked_contacts" class="chk-btn trigger" data-trigger="hidden_fields_three">
						<label class="singleLine_radioBtn" for="blocked_contacts">
							<span><?php echo $this->config->item('deposit_funds_page_checkbox_deposit_via_payment_cards'); ?></span>
						</label></span><span class="singleLine_chkBtn">
						<input type="checkbox" id="rejected_contact_requests" name="contacts_management_checkbox" value="rejected_contact_requests" class="chk-btn trigger" data-trigger="hidden_fields_two">
						<label class="singleLine_radioBtn" for="rejected_contact_requests">
							<span><?php echo $this->config->item('deposit_funds_page_checkbox_deposit_via_bank_transfer'); ?></span></label></span></div>
				<!-- Checkbox Start -->
				<!-- Checkbox Content Start -->
				<div class="cmFieldText">
					<?php
						if($this->session->userdata('deposit_funds_success')) {
					?>
					<div class="alert alert-success alert-dismissible fade show" id="deposit_success" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<?php echo $this->session->userdata('deposit_funds_success'); ?>
					</div>
					<?php 
							$this->session->unset_userdata('deposit_funds_success');
						}
					?>
					<?php
						if($this->session->userdata('succ')) {
					?>
					<p class="default_success_green_message payment_processor_msg"><?php echo $this->session->userdata('succ'); ?></p>
					<?php
						}
						$this->session->unset_userdata('succ');
					?>
					<?php
						if($this->session->userdata('error')) {
					?>
					<p class="default_error_red_message payment_processor_msg"><?php echo $this->session->userdata('error'); ?></p>
					<?php
						}
						$this->session->unset_userdata('error');
					?>
					<div class="cmField hiddenCMcheckbox" id="hidden_fields_one">
						<div class="fundDeposit">
							<form id="fm_deposit_funds" action="finances/deposit_funds" method="post">
								<div class="depositAmt">
									<div class="amtTextRight amountText">
										<h6 class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_paypal_deposit_amount_label_txt'); ?></h6>
									</div>
									<div class="currencyType">
										<div class="input-group">
											<input type="text" id="deposite_fund" name="deposit_amt" maxlength="<?php echo $this->config->item('deposit_funds_via_paypal_amount_length_character_limit'); ?>" class="form-control avoid_space login_register_input_field">
											<b class="default_black_bold_medium"><?php echo CURRENCY; ?></b>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="processingFee">
									<div class="amtTextRight amountText">
										<h6 class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_paypal_processing_fee_label_txt'); ?></h6><i class="fa fa-question-circle default_icon_help tooltipAuto" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="<?php echo $this->config->item('deposit_funds_via_paypal_processing_fees_info'); ?>"></i>
									</div>
									<div class="currencyType">
										<div class="input-group">
											<input type="text" id="deposit_fund_processing_fee" class="form-control avoid_space login_register_input_field" readonly>
											<b class="default_black_bold_medium"><?php echo CURRENCY; ?></b>
										</div>													
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="totalPay">
									<div class="amtTextRight amountText">
										<h6 class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_paypal_total_label_txt'); ?></h6><i class="fa fa-question-circle default_icon_help tooltipAuto" data-toggle="tooltip" data-placement="top" title="<?php echo $this->config->item('deposit_funds_via_paypal_total_amount_tooltip_info'); ?>"></i>
									</div>
									<div class="currencyType">
										<!-- <div class="amtRight">
											<b id="deposite_fund_total" class="default_black_bold_medium"></b>
										</div> -->
										
										<div class="input-group">
											<input type="text" class="form-control avoid_space default_input_field p-0" id="deposite_fund_total" readonly>
											<b class="default_black_bold_medium"><?php echo CURRENCY; ?></b>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>                    
								<div class="confirmPay">
									<label>
										<div class="default_error_red_message d-none"></div>
										<button id="deposit_fund_submit" class="btn blue_btn default_btn" disabled type="submit"><?php echo $this->config->item('deposit_funds_via_paypal_confirm_payment_btn_txt'); ?> <span id="deposite_fund_total_btn"></span></button>
									</label>
								</div>
							</form>
						</div>
						<!-- Contacts Management End -->
						<?php
							if(!empty($paypal_transactions)) {
						?>
						<div class="projTitle">							
							<div class="receive_notification" id="tagSL">
								<a class="rcv_notfy_btn show_more_transaction" 
										data-text="<?php echo $this->config->item('deposit_funds_via_paypal_transaction_history_label_txt');  ?>" 
										data-reference="moreTags_paypal"
										id="project_tag_heading_section"><?php echo $this->config->item('deposit_funds_via_paypal_transaction_history_label_txt'); ?><small>( + )</small></a>
								<input type="hidden" id="moreTags_paypal" value="1">
							</div>
							<div id="moreTags_paypal_lst" class="collapse row">
								<?php
									foreach($paypal_transactions as $val) {
								?>
								<div class="col-md-12 col-sm-12 col-12 dfThistory"><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_paypal_deposited_amount_label_txt'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['deposit_amount']).' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_paypal_transaction_date_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])) ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_paypal_paypal_account_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['paypal_account']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_paypal_transaction_charge_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo format_money_amount_display($val['total_transaction_charged_fee']).' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_paypal_transaction_id_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['transaction_id']; ?></span></div></label></div><?php
									}
								?>
							</div>
						</div>
						<?php
							}
						?>
					</div>
					<div class="cmField hiddenCMcheckbox" id="hidden_fields_three">
						<!-- Payment Details Text Start -->
						<div class="payDetails default_user_description">
							<p><?php echo $this->config->item('deposit_funds_via_payment_processor_heading_txt'); ?></p>
						</div>
						<!-- Payment Details Text End -->
						<div class="fundDepositPaymentCard">
							<form id="fm_deposit_funds_vai_payment_processor" action="finances/deposit_funds_via_payment_processor"  method="post">
								<?php
									$deposit_funds_via_payment_processor_processing_fee_info = $this->config->item('deposit_funds_via_payment_processor_processing_fee_info');
									$deposit_funds_via_payment_processor_processing_fee_info = str_replace('{fee_amount}', !empty($payment_card_charges) ? $payment_card_charges['charged_fee_value'] : 0 ,$deposit_funds_via_payment_processor_processing_fee_info);
								?>
								<div class="depositAmt depositedAmtPmtCard currency_depositedAmt">
									<div class="amtTextRight amountText">
										<h6 class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_deposit_amount_label_txt'); ?></h6>
									</div>
									<div class="currencyType currencyType_deposited_payment">
										<div class="input-group">
											<input type="text" id="deposited_payment" name="deposit_amt" maxlength="<?php echo $this->config->item('deposit_funds_via_payment_processor_amount_length_character_limit'); ?>" class="form-control avoid_space login_register_input_field">
											<input type="hidden" name="user_id" value="<?php echo $user_data['user_id'] ?>" class="form-control avoid_space login_register_input_field">
											<b class="default_black_bold_medium"><?php echo CURRENCY; ?></b>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="clearfix"></div>
								<div class="processingFee currency_depositedAmt">
									<div class="amtTextRight amountText">
										<h6 class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_processing_fee_label_txt'); ?></h6><i class="fa fa-question-circle default_icon_help tooltipAuto" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="<?php echo $deposit_funds_via_payment_processor_processing_fee_info; ?>"></i>
									</div>
									<div class="currencyType">
										<div class="input-group">
											<input type="text" id="deposited_payment_processing_fee" class="form-control avoid_space login_register_input_field" readonly>
											<b class="default_black_bold_medium"><?php echo CURRENCY; ?></b>
										</div>													
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="clearfix"></div>
								<div class="totalPayCard pull-left" >
									<div class="amtTextRight amountText">
										<h6 class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_total_label_txt'); ?></h6><i class="fa fa-question-circle default_icon_help tooltipAuto" data-toggle="tooltip" data-placement="top" title="<?php echo $this->config->item('deposit_funds_via_payment_card_total_amount_tooltip_info'); ?>"></i>
									</div>
									<div class="currencyType">
										<!-- <div class="amtRight">
											<b id="deposited_payment_total" class="default_black_bold_medium"></b>
										</div> -->
										
										<div class="input-group">
											<input type="text" class="form-control avoid_space default_input_field p-0" id="deposited_payment_total" readonly>
											<b class="default_black_bold_medium"><?php echo CURRENCY; ?></b>
										</div>													
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="clearfix"></div>
								<div class="row text-center paymentCardImg">
									<div class="pamentImage">
										<div class="form-check">
											<label class="default_checkbox">
												<img src="<?php echo ASSETS.'images/deposit_funds_via_payment_processor/muzo.png'?>" />
												<span class="paymentText"><?php echo $this->config->item('deposit_funds_via_payment_processor_credit_card_option_name'); ?></span>
												<input type="checkbox" class="date_filter" name="payment_method" value="platba_kartou"><span class="checkmark"></span>
											</label>
										</div>
									</div><div class="pamentImage">
										<div class="form-check">
											<label class="default_checkbox">
												<img src="<?php echo ASSETS.'images/deposit_funds_via_payment_processor/platba24.png'?>" />
												<span class="paymentText"><?php echo $this->config->item('deposit_funds_via_payment_processor_payment_24_option_name'); ?></span>
												<input type="checkbox" class="date_filter" name="payment_method" value="Platba24"><span class="checkmark"></span>
											</label>
										</div>
									</div><div class="pamentImage">
										<div class="form-check">
											<label class="default_checkbox">
												<img src="<?php echo ASSETS.'images/deposit_funds_via_payment_processor/moje-platba.png'?>" />
												<span class="paymentText"><?php echo $this->config->item('deposit_funds_via_payment_processor_mojeplatba_option_name'); ?></span>
												<input type="checkbox" class="date_filter" name="payment_method" value="mojeplatba"><span class="checkmark"></span>
											</label>
										</div>
									</div><div class="pamentImage">
										<div class="form-check">
											<label class="default_checkbox">
												<img src="<?php echo ASSETS.'images/deposit_funds_via_payment_processor/ekonto.png'?>" />
												<span class="paymentText"><?php echo $this->config->item('deposit_funds_via_payment_processor_ekonto_option_name'); ?></span>
												<input type="checkbox" class="date_filter" name="payment_method" value="ekonto"><span class="checkmark"></span>
											</label>
										</div>
									</div><div class="pamentImage">
										<div class="form-check">
											<label class="default_checkbox">
												<img src="<?php echo ASSETS.'images/deposit_funds_via_payment_processor/mpenize.png'?>" />
												<span class="paymentText"><?php echo $this->config->item('deposit_funds_via_payment_processor_mpenize_option_name'); ?></span>
												<input type="checkbox" class="date_filter" name="payment_method" value="mpenize"><span class="checkmark"></span>
											</label>
										</div>
									</div><div class="pamentImage">
										<div class="form-check">
											<label class="default_checkbox">
												<img src="<?php echo ASSETS.'images/deposit_funds_via_payment_processor/moneta.png'?>" />
												<span class="paymentText"><?php echo $this->config->item('deposit_funds_via_payment_processor_moneta_option_name'); ?></span>
												<input type="checkbox" class="date_filter" name="payment_method" value="moneta"><span class="checkmark"></span>
											</label>
										</div>
									</div><div class="pamentImage">
										<div class="form-check">
											<label class="default_checkbox">
												<img src="<?php echo ASSETS.'images/deposit_funds_via_payment_processor/csob.png'?>" />
												<span class="paymentText"><?php echo $this->config->item('deposit_funds_via_payment_processor_csob_option_name'); ?></span>
												<input type="checkbox" class="date_filter" name="payment_method" value="csbo"><span class="checkmark"></span>
											</label>
										</div>
									</div><div class="pamentImage">
										<div class="form-check">
											<label class="default_checkbox">
												<img src="<?php echo ASSETS.'images/deposit_funds_via_payment_processor/fio.png'?>" />
												<span class="paymentText"><?php echo $this->config->item('deposit_funds_via_payment_processor_fio_bank_option_name'); ?></span>
												<input type="checkbox" class="date_filter" name="payment_method" value="fio_bank"><span class="checkmark"></span>
											</label>
										</div>
									</div><div class="pamentImage">
										<div class="form-check">
											<label class="default_checkbox">
												<img src="<?php echo ASSETS.'images/deposit_funds_via_payment_processor/equa.png'?>" />
												<span class="paymentText"><?php echo $this->config->item('deposit_funds_via_payment_processor_equa_bank_option_name'); ?></span>
												<input type="checkbox" class="date_filter" name="payment_method" value="equa_bank"><span class="checkmark"></span>
											</label>
										</div>
									</div>
								</div>
								<div class="confirmPay">
									<label>
										<div class="default_error_red_message d-none"></div>
											<button id="deposited_payment_submit" class="btn blue_btn btn-block default_btn" disabled type="submit"><?php echo $this->config->item('deposit_funds_via_payment_processor_confirm_payment_btn_txt'); ?> <span id="deposited_payment_total_btn"></span></button>
									</label>
								</div>
							</form>
						</div>
						<!-- Contacts Management End -->
						<?php
							if(!empty($payment_processor_transactions)) {
						?>
						<div class="projTitle">							
							<div class="receive_notification" id="tagSL">
								<a class="rcv_notfy_btn show_more_transaction" 
										data-text="<?php echo $this->config->item('deposit_funds_via_payment_processor_transaction_history_label_txt');  ?>" 
										data-reference="moreTags_payment_card"
										id="project_tag_heading_section"><?php echo $this->config->item('deposit_funds_via_payment_processor_transaction_history_label_txt'); ?><small>( + )</small></a>
								<input type="hidden" id="moreTags_payment_card" value="1">
							</div>
							<div id="moreTags_payment_card_lst" class="collapse row">
								<?php
									
									foreach($payment_processor_transactions as $val) {
								?>
								<div class="col-md-12 col-sm-12 col-12 dfThistory"><label><?php
											$transaction_amt_lbl = '';
											if(in_array($val['status_code'], [2,6])) {
												$transaction_amt_lbl = $this->config->item('deposit_funds_via_payment_processor_deposited_amount_lbl');
											} else {
												$transaction_amt_lbl = $this->config->item('deposit_funds_via_payment_processor_transaction_amount_lbl');
											}
										?><div><b class="default_black_bold_medium"><?php echo $transaction_amt_lbl; ?></b><span class="default_black_regular_medium display-inline-block"><?php echo format_money_amount_display($val['amount']).' '.CURRENCY; ?></span></div></label><?php
										if($val['business_transaction_charged_fee']) {
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_transaction_charge_lbl'); ?></b><span class="default_black_regular_medium"><?php echo format_money_amount_display($val['business_transaction_charged_fee']).' '.CURRENCY ?></span></div></label><?php
										}
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_transaction_date_lbl'); ?></b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])) ?></span></div></label><label class="<?php echo !in_array($val['status_code'], [2,6]) ? 'd-none' : ''; ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_transaction_id_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['payment_id']; ?></span></div></label><?php
										if($val['deposit_transfer_type'] == 'payment_card_transaction') {
									?><label class="<?php echo empty($val['card_number']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_card_number_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['card_number']; ?></span></div>
									</label><label class="<?php echo empty($val['card_brand']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_card_brand_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['card_brand']; ?></span></div>
									</label><label class="<?php echo empty($val['country_code']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_country_code_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['country_code']; ?></span></div></label><label class="<?php echo empty($val['card_bank_name']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_card_bank_name_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['card_bank_name']; ?></span></div></label><label class="<?php echo empty($val['card_type']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_card_type_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['card_type']; ?></span></div></label><?php
										} else if($val['deposit_transfer_type'] == 'bank_transfer_transaction') {
									?><label class="<?php echo empty($val['bank_name']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_bank_name_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['bank_name'].'/'.$this->config->item('deposit_funds_via_payment_processor_method_id_associated_method_name')[$val['method_id']]; ?></span></div></label><label class="<?php echo empty($val['bank_account_number']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_bank_account_number_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['bank_account_number']; ?></span></div></label><label class="<?php echo empty($val['bank_account_owner_name']) ? 'd-none' : '';  ?>"><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_bank_owner_name_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $val['bank_account_owner_name']; ?></span></div></label><?php
										}
									?><label><div><?php
												$status = '';
												if(in_array($val['status_code'], [2,6])) {
													$status = $this->config->item('deposit_funds_via_payment_processor_transaction_success_status_txt');
												} else if($val['status_code'] == 3) {
													$status = $this->config->item('deposit_funds_via_payment_processor_transaction_cancelled_status_txt');
												} else if($val['status_code'] == 4) {
													$status = $this->config->item('deposit_funds_via_payment_processor_transaction_failed_status_txt');
												} else if($val['status_code'] == 7) {
													 $status = $this->config->item('deposit_funds_via_payment_processor_transaction_waiting_for_confirmation_status_txt');
												}
											?><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_via_payment_processor_transaction_status_lbl'); ?></b><span class="default_black_regular_medium"><?php echo $status; ?></span></div>
									</label>
								</div>					
								<?php
										}
								?>
							</div>
						</div>
						<?php
							}
						?>
					</div>
					<div class="cmField hiddenCMcheckbox" id="hidden_fields_two">
						<!-- Payment Details Text Start -->
						<div class="payDetails default_user_description">
							<p><?php echo $this->config->item('deposit_funds_bank_heading_txt'); ?></p>
						</div>
						<!-- Payment Details Text End -->
						
						<!-- Bank Details Start -->
						<div class="bankDetails">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_information_lbl'); ?></h6>
							<p class="default_black_regular_medium"><span class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_account_owner_lbl'); ?>:</span><?php echo $this->config->item('deposit_funds_bank_account_owner_value'); ?></p>
							<p class="default_black_regular_medium"><span class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_account_number_lbl'); ?>:</span><?php echo $this->config->item('deposit_funds_bank_account_number_value'); ?></p>
							<p class="default_black_regular_medium"><span class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_name_lbl'); ?>:</span><?php echo $this->config->item('deposit_funds_bank_name_value'); ?></p>
							<p class="default_black_regular_medium"><span class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_code_lbl'); ?>:</span><?php echo $this->config->item('deposit_funds_bank_code_value'); ?></p>
							<?php 
								if(!empty($user_data['user_bank_deposit_variable_symbol'])) {
							?>
							<p class="default_black_regular_medium"><span class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_user_bank_variable_symbol_lbl'); ?>:</span><?php echo $user_data['user_bank_deposit_variable_symbol']; ?></p>
							<?php
								}
							?>
							<p class="default_black_regular_medium"><span class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_iban_lbl'); ?>:</span><?php echo $this->config->item('deposit_funds_iban_value'); ?></p>
							<p class="default_black_regular_medium"><span class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_bic_or_swift_code_lbl'); ?>:</span><?php echo $this->config->item('deposit_funds_bank_bic_or_swift_code_value'); ?></p>
							<p class="default_black_regular_medium"><span class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_country_lbl'); ?>:</span><?php echo $this->config->item('deposit_funds_bank_country_value'); ?></p>
						</div>
						<!-- Bank Details End -->
						
						<!-- Reference Start -->
						<div class="referenceDetails">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_reference_heading_txt'); ?></h6>
							<p class="default_black_regular_medium"><span><?php echo $this->config->item('deposit_funds_bank_reference_txt'); ?> <?php echo $this->config->item('deposit_funds_bank_reference_username_lbl'); ?>:</span><span class="profile_name"><?php echo $user_data['profile_name']; ?></span></p>
						</div>
						<!-- Reference End -->
						
						<!-- Payment Details Text Start -->
						<div class="payDetails default_user_description">
							<p><?php echo $this->config->item('deposit_funds_bank_body_txt'); ?></p>
						</div>
						<!-- Payment Details Text End -->
						
						<!-- Bank Details Start -->						
						<div class="transactionDetails">						
							<form id="fm_bank_transfer">	
							<div class="row">
								<div class="col-md-6 col-sm-6 col-12 dfOne">
									<div class="form-group">
										<label class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_deposited_amount_lbl'); ?></label>
										<div class="input-group">
											<input type="text" class="form-control default_input_field text-right" maxlength="<?php echo $this->config->item('deposit_funds_direct_bank_transfer_amount_length_character_limit'); ?>" id="deposited_amount" name="deposited_amount">
											<div class="input-group-append">
												<span class="input-group-text default_black_bold_medium"><?php echo CURRENCY; ?></span>
											</div>
										</div>
									</div>
									<div class="error_div_sectn clearfix">
										<span class="error_msg deposit_amount_err"></span>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-12 dfTwo">
									<div class="form-group">
										<label class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_account_owner_lbl'); ?></label>
										<input type="text" class="form-control avoid_space default_input_field" id="account_owner" name="account_owner">
									</div>
									<div class="error_div_sectn clearfix">
										<span class="error_msg account_owner_err"></span>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-12 dfThree">
									<div class="form-group">
										<label class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_account_number_lbl'); ?></label>
										<input type="text" class="form-control avoid_space default_input_field" name="account_number">
									</div>
									<div class="error_div_sectn clearfix">
										<span class="error_msg account_number_err"></span>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-12 dfFour">
									<div class="form-group">
										<label class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_name_lbl'); ?></label>
										<input type="text" class="form-control avoid_space default_input_field" name="bank_name">
									</div>
									<div class="error_div_sectn clearfix">
										<span class="error_msg bank_name_err"></span>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-12 dfFive">
									<div class="form-group">
										<label class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_code_lbl'); ?></label>
										<input type="text" class="form-control avoid_space default_input_field" name="bank_code">
									</div>
									<div class="error_div_sectn clearfix">
										<span class="error_msg bank_code_err"></span>
									</div>
								</div>
								<?php 
									if(!empty($user_data['user_bank_deposit_variable_symbol'])) {
								?>
								<div class="col-md-6 col-sm-6 col-12 dfSix">
									<div class="form-group">
										<label class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_user_bank_variable_symbol_lbl'); ?></label>
										<input type="text" id="variable_symbol" class="form-control default_input_field" disabled  value="<?php echo $user_data['user_bank_deposit_variable_symbol']; ?>"/>
									</div>
									<div class="error_div_sectn clearfix">
										<span class="error_msg"></span>
									</div>
								</div>
								<?php
									}
								?>
								<div class="col-md-12 col-sm-12 col-12 default_country fontSize0 dfSeven">
									<label class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_country_lbl'); ?></label>
									<label class="form-group default_dropdown_select withdrawCountry">
										<select id="company_country" name="country">
											<option value="" class="d-none"><?php echo $this->config->item('deposit_funds_bank_country'); ?></option>
											<?php foreach ($countries as $country): ?>
											<option value="<?php echo $country['id']; ?>" ><?php echo $country['country_name'] ?></option>
											<?php endforeach; ?>
										</select>
									</label>
									<div class="error_div_sectn clearfix">
										<span class="error_msg country_err"></span>
									</div>
								</div>
								
								<div class="col-md-6 col-sm-6 col-12 cz_country d-none dfEight">
									<div class="form-group">
										<label class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_iban_lbl'); ?></label>
										<input type="text" class="form-control avoid_space default_input_field" name="iban">
									</div>
									<div class="error_div_sectn clearfix">
										<span class="error_msg iban_err"></span>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-12 cz_country d-none dfNine">
									<div class="form-group">
										<label class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_bic_or_swift_code_lbl'); ?></label>
										<input type="text" class="form-control avoid_space default_input_field" name="swift_code">
									</div>
									<div class="error_div_sectn clearfix">
										<span class="error_msg bic_swift_code_err"></span>
									</div>
								</div>
								
								<div class="col-md-6 col-sm-6 col-12 dfTen">
									<div class="form-group">
										<label class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_transaction_date_lbl'); ?></label>
										<input type="text" class="form-control avoid_space default_input_field" name="transaction_date">
									</div>
									<div class="error_div_sectn clearfix">
										<span class="error_msg transaction_date_err"></span>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-12 dfEleven">
									<div class="form-group">
										<label class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_transaction_id_lbl'); ?></label>
										<input type="text" class="form-control avoid_space default_input_field" name="transaction_id">
									</div>
									<div class="error_div_sectn clearfix">
										<span class="error_msg transaction_id_err"></span>
									</div>
								</div>
								<div class="col-md-12 col-sm-12 col-12 text-center dfTwelve"><button class="btn default_btn blue_btn register_transaction"><?php echo $this->config->item('deposit_funds_register_transaction_btn'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button></div>
							</div>
							</form>
						</div>
						<!-- Bank Details End -->
						
						<!-- Note Details Start -->
						<div class="noteDetails"><span class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_note_lbl'); ?>:</span><?php echo $this->config->item('deposit_funds_bank_note_txt'); ?></div>
						<!-- Note Details End -->
						
						<!-- When Data End -->
						
						<!-- Contacts Management End -->
						<div class="projTitle direct_bank_transfer_heading" style="display: <?php echo !empty($bank_transactions) ? 'block' : 'none'; ?>">							
							<div class="receive_notification" id="tagSL">
								<a class="rcv_notfy_btn show_more_transaction" 
										data-text="<?php echo $this->config->item('deposit_funds_via_paypal_transaction_history_label_txt');  ?>"
										data-reference="moreTags_bank_deposit"
										id="project_tag_heading_section"><?php echo $this->config->item('deposit_funds_via_paypal_transaction_history_label_txt'); ?><small>( + )</small></span></a>
								<input type="hidden" id="moreTags_bank_deposit" value="1">
							</div>
							<div id="moreTags_bank_deposit_lst" class="collapse row direct_bank_transfer_data">
								<?php
									foreach($bank_transactions as $val) {
								?>
								<div class="col-md-12 col-sm-12 col-12 dfThistory">
									<label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_deposited_amount_lbl'); ?>:</b><span class="default_black_regular_medium display-inline-block"><?php echo $val['deposited_amount'].' '.CURRENCY; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_transaction_id_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_transaction_id']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_transaction_date_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_transaction_date']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_account_owner_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_account_owner_name']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_account_number_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_account_number']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_name_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_name']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_code_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_code']; ?></span></div></label><?php 
										if(!empty($user_data['user_bank_deposit_variable_symbol'])){
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_user_bank_variable_symbol_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $user_data['user_bank_deposit_variable_symbol']; ?></span></div></label><?php
										}
									?><?php
										if($val['bank_account_iban_code'] != '') {
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_iban_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_account_iban_code']; ?></span></div></label><?php
										}
									?><?php 
										if($val['bank_account_bic_swift_code'] != '') {
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_bic_or_swift_code_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['bank_account_bic_swift_code']; ?></span></div></label><?php
										}
									?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('deposit_funds_bank_country_lbl'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['country_name']; ?></span></div></label><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('transaction_status_label_txt'); ?>:</b><span class="default_black_regular_medium"><?php echo $val['status']; ?></span></div></label></div><?php
									}
								?>
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
	var deposit_funds_deposited_amount_lbl = "<?php echo $this->config->item('deposit_funds_deposited_amount_lbl') ?>";
	var deposit_funds_bank_account_owner_lbl = "<?php echo $this->config->item('deposit_funds_bank_account_owner_lbl') ?>";
	var deposit_funds_bank_account_number_lbl = "<?php echo $this->config->item('deposit_funds_bank_account_number_lbl') ?>";
	var deposit_funds_bank_name_lbl = "<?php echo $this->config->item('deposit_funds_bank_name_lbl') ?>";
	var deposit_funds_bank_code_lbl = "<?php echo $this->config->item('deposit_funds_bank_code_lbl') ?>";
	var deposit_funds_iban_lbl = "<?php echo $this->config->item('deposit_funds_iban_lbl') ?>";
	var deposit_funds_bank_bic_or_swift_code_lbl = "<?php echo $this->config->item('deposit_funds_bank_bic_or_swift_code_lbl') ?>";
	var deposit_funds_bank_country_lbl = "<?php echo $this->config->item('deposit_funds_bank_country_lbl') ?>";
	var transaction_date_label_txt = "<?php echo $this->config->item('transaction_date_label_txt') ?>";
	var transaction_id_label_txt = "<?php echo $this->config->item('transaction_id_label_txt') ?>";
	var transaction_status_label_txt = "<?php echo $this->config->item('transaction_status_label_txt') ?>";

	var deposit_funds_bank_transaction_id_lbl = "<?php echo $this->config->item('deposit_funds_bank_transaction_id_lbl') ?>";
	var deposit_funds_bank_transaction_date_lbl = "<?php echo $this->config->item('deposit_funds_bank_transaction_date_lbl') ?>";

	var deposit_funds_via_payment_processor_min_deposit_amount = "<?php echo $this->config->item('deposit_funds_via_payment_processor_min_deposit_amount') ?>";
	var deposit_funds_via_payment_processor_min_amount_error_msg = "<?php echo $this->config->item('deposit_funds_via_payment_processor_min_amount_error_msg') ?>";
	var deposit_funds_via_payment_processor_processing_fee_info = "<?php echo $deposit_funds_via_payment_processor_processing_fee_info; ?>";

	var currency = "<?php echo CURRENCY; ?>";

	var deposit_funds_amount_length_character_limit = '<?php echo $this->config->item('deposit_funds_via_paypal_amount_length_character_limit'); ?>';
	var deposit_funds_direct_bank_transfer_amount_length_character_limit = '<?php echo $this->config->item('deposit_funds_direct_bank_transfer_amount_length_character_limit'); ?>';
	var deposit_funds_via_payment_processor_amount_length_character_limit = '<?php echo $this->config->item('deposit_funds_via_payment_processor_amount_length_character_limit'); ?>';
	var deposit_funds_via_payment_processor_response_message_timeout = '<?php echo $this->config->item('deposit_funds_via_payment_processor_response_message_timeout'); ?>';
	
	var deposit_funds_via_payment_processor_max_deposit_amount = '<?php echo $this->config->item('deposit_funds_via_payment_processor_max_deposit_amount'); ?>';
	var deposit_funds_via_payment_processor_max_deposit_amount_error_msg = '<?php echo $this->config->item('deposit_funds_via_payment_processor_max_deposit_amount_error_msg'); ?>';

	var deposit_funds_via_paypal_processing_fees_percentage_charge_first_amounts_range = '<?php echo $this->config->item('deposit_funds_via_paypal_processing_fees_percentage_charge_first_amounts_range'); ?>';
	var deposit_funds_via_paypal_first_amounts_range_min_value = '<?php echo $this->config->item('deposit_funds_via_paypal_first_amounts_range_min_value'); ?>';
	var deposit_funds_via_paypal_first_amounts_range_max_value = '<?php echo $this->config->item('deposit_funds_via_paypal_first_amounts_range_max_value'); ?>';
	
	var deposit_funds_via_paypal_processing_fees_percentage_charge_second_amounts_range = '<?php echo $this->config->item('deposit_funds_via_paypal_processing_fees_percentage_charge_second_amounts_range'); ?>';
	var deposit_funds_via_paypal_second_amounts_range_min_value = '<?php echo $this->config->item('deposit_funds_via_paypal_second_amounts_range_min_value'); ?>';
	var deposit_funds_via_paypal_second_amounts_range_max_value = '<?php echo $this->config->item('deposit_funds_via_paypal_second_amounts_range_max_value'); ?>';
	
	var deposit_funds_min_amt_error_msg = "<?php echo $this->config->item('deposit_funds_via_paypal_min_amount_error_msg'); ?>";
	var deposit_funds_max_amt_error_msg = "<?php echo $this->config->item('deposit_funds_via_paypal_max_amount_error_msg'); ?>";
	
	var deposit_funds_user_bank_variable_symbol_lbl = '<?php echo $this->config->item('deposit_funds_user_bank_variable_symbol_lbl'); ?>';
	var reference_country_id = '<?php echo $this->config->item('reference_country_id'); ?>';
	var variable_symbol = '<?php echo $user_data['user_bank_deposit_variable_symbol']; ?>';


</script>
<script src="<?= ASSETS ?>js/modules/deposit_funds.js"></script>