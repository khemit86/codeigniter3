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
			<div id="content" class="transactions_history_page body_distance_adjust <?php echo $transactions_histroy_cnt == 0 ? 'no_data_msg_display_center' : ''; ?>">
			<div class="etSecond_step ">
				<!-- Heading Text Start -->
				<div class="default_page_heading" style="display:<?php echo $transactions_histroy_cnt > 0 ? 'block' : 'none' ?>">
					<h4><?php echo $this->config->item('finance_headline_title_transactions_history'); ?></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Heading Text End -->

				<!-- Content Start -->

				<div class="tHistory" style="display:<?php echo $transactions_histroy_cnt > 0 ? 'block' : 'none' ?>">
					<div id="date_show_more_less" class="">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-12">
									<div class="srchName">
										<div class="d-inline-block tHistory_label">
											<?php
												$transaction_history_date_filter_checkboxes_lbl = $this->config->item('transactions_history_date_filter_checkboxes_lbl');
												foreach($transaction_history_date_filter_checkboxes_lbl as $key => $val) {
											?>
													<div class="form-check">
														<label class="default_checkbox">
															<input type="checkbox" class="date_filter" <?php echo $key == 'all' ? 'checked' : ''; ?> value="<?php echo $key; ?>"><span class="checkmark"></span><span class="textGap default_black_bold_medium"><?php echo $val; ?></span></label>
													</div>
											<?php
												}
											?>
										</div>
									</div>
								</div>
							</div>
							<div class="row custom_date" style="display:none;">
								<div class="col-md-4 col-sm-4 col-6 pR0 fromDate">	
									<div class="form-group shEn default_dropdown_select">
										<label for="sEntries"><?php echo $this->config->item('transactions_history_from_lbl'); ?></label><div class="btn-group">
											<input type="text" placeholder="--.--.----" class="form-control" id="startDate"></input>
										</div>
									</div>
								</div>
								<div class="col-md-4 col-sm-4 col-6 pR0 toDate">	
									<div class="form-group shEn default_dropdown_select">
										<label for="sEntries"><?php echo $this->config->item('transactions_history_to_lbl'); ?></label><div class="btn-group">
											<input type="text" placeholder="--.--.----" class="form-control" id="endDate" data-provide="datepicker"></input>
										</div>
									</div>
								</div>
								<div class="col-md-4 col-sm-4 col-12 pR0 pl-0 btnSection">
									<button id="search_btn_filter" class="btn blue_btn default_btn clear_dates" type="button"><?php echo $this->config->item('transactions_history_search_btn_text'); ?></button>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-12 col-sm-12 col-12 pr0 mt-1 date_err date_error_mesg" style="display:none;">
									<span class="error_msg"><?php echo $this->config->item('transactions_history_from_date_prior_than_to_date_error_message'); ?></span>
								</div>
							</div>
					</div>
				
				<!-- </div> -->
				
				<!-- Notification Collapse Start -->
					<div class="receive_notification date_show_more_less" style="display:<?php echo $transactions_histroy_cnt > 0 ? 'block' : 'none' ?>">
						<a class="rcv_notfy_btn" onclick="showMoreDateFilter()">
							<?php echo $this->config->item('transactions_history_show_more_search_options_text'); ?>
						</a>
						<input type="hidden" id="moreDateFilter" value="1">
					</div>
					<div id="rcv_notfy" class="proDtls" style="display: none;">
						<div class="pDtls">
							<div class="fbSelect">
								<div class="fProjectlr">
									<div class="multiselect pSelect">
										<?php
											$deposits = $this->config->item('transactions_history_deposits_option_list');
										?>
										<div class="selectBox">
											<select class="">
												<option><?php echo $this->config->item('transactions_history_deposits_dropdown_option_name'); ?></option>
											</select>
											<div class="overSelect"></div>
										</div>
										<div id="checkboxes" class="visible_option select_flex_width" style="display: none;">
											<?php
												foreach($deposits as $key => $val) {
													$checked = '';
													if(strpos($key, 'all') !== false) {
														$checked = 'checked';
													}
											?>
											<div class="drpChk">
												<label for="<?php echo $key; ?>" class="default_checkbox">
													<input type="checkbox" id="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?>>
													<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $val; ?></small></label>
											</div>
											<?php
												}
											?>
										</div>
									</div>
								</div>
								<div class="fProjectlr">
									<div class="multiselect pSelect">
										<?php
											$withdraws = $this->config->item('transactions_history_withdraws_option_list');
										?>
										<div class="selectBox">
											<select class="">
												<option><?php echo $this->config->item('transactions_history_withdrawals_dropdown_option_name'); ?></option>
											</select>
											<div class="overSelect"></div>
										</div>
										<div id="checkboxes1" class="visible_option select_flex_width" style="display: none;">
											<?php
												foreach($withdraws as $key => $val) {
													$checked = '';
													if(strpos($key, 'all') !== false) {
														$checked = 'checked';
													}
											?>
											<div class="drpChk">
												<label for="<?php echo $key; ?>" class="default_checkbox">
													<input type="checkbox" id="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?>>
													<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $val; ?></small></label>
											</div>
											<?php
												}
											?>
										</div>
									</div>
								</div>
								<div class="fProjectlr">
									<div class="multiselect pSelect ">
										<?php
											$proj_upgrades = $this->config->item('transactions_history_project_upgrades_option_list');
										?>
										<div class="selectBox">
											<select class=" ">
												<option><?php echo $this->config->item('transactions_history_project_upgrades_dropdown_option_name'); ?></option>
											</select>
											<div class="overSelect"></div>
										</div>
										<div id="checkboxes5" class="visible_option select_flex_width">
											<?php 
												foreach($proj_upgrades as $key => $val) {
													$checked = '';
													if(strpos($key, 'all') !== false) {
														$checked = 'checked';
													}
											?>
											<div class="drpChk">
												<label for="<?php echo $key; ?>" class="default_checkbox">
													<input type="checkbox" id="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?>>
													<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $val; ?></small></label>
											</div>
											<?php
												}
											?>
										</div>
									</div>
								</div>
								<div class="fProjectlr">
									<div class="multiselect pSelect ">
										<?php
											$payment_on_proj = $this->config->item('transactions_history_payments_on_projects_option_list');
										?>
										<div class="selectBox">
											<select class="">
												<option><?php echo $this->config->item('transactions_history_payments_on_projects_dropdown_option_name'); ?></option>
											</select>
											<div class="overSelect"></div>
										</div>
										<div id="checkboxes4" class="visible_option select_flex_width">
											<?php
												foreach($payment_on_proj as $key => $val) {
													$checked = '';
													if(strpos($key, 'all') !== false) {
														$checked = 'checked';
													}
											?>
											<div class="drpChk">
												<label for="<?php echo $key; ?>" class="default_checkbox">
													<input type="checkbox" id="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?>>
													<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $val; ?></small></label>
											</div>
											<?php
												}
											?>
										</div>
									</div>
								</div>
								<div class="fProjectlr">
									<div class="multiselect pSelect ">
										<?php 
											$payment_receive_proj = $this->config->item('transactions_history_payments_received_on_projects_option_list');
										?>
										<div class="selectBox">
											<select class=" ">
												<option><?php echo $this->config->item('transactions_history_received_payments_on_projects_dropdown_option_name'); ?></option>
											</select>
											<div class="overSelect"></div>
										</div>
										<div id="checkboxes3" class="visible_option select_flex_width">
											<?php 
												foreach($payment_receive_proj as $key => $val) {
													$checked = '';
													if(strpos($key, 'all') !== false) {
														$checked = 'checked';
													}
											?>
											<div class="drpChk">
												<label for="<?php echo $key; ?>" class="default_checkbox">
													<input type="checkbox" id="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?>>
													<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $val; ?></small></label>
											</div>
											<?php
												}
											?>
										</div>
									</div>
								</div>
								<div class="fProjectlr">
									<div class="multiselect pSelect ">
										<?php
											$payment_on_fulltime = $this->config->item('transactions_history_salary_payments_on_fulltime_jobs_option_list');
										?>
										<div class="selectBox">
											<select class=" ">
												<option><?php echo $this->config->item('transactions_history_payments_on_fulltime_jobs_dropdown_option_name'); ?></option>
											</select>
											<div class="overSelect"></div>
										</div>
										<div id="checkboxes2" class="visible_option select_flex_width">
											<?php
												foreach($payment_on_fulltime as $key => $val) {
													$checked = '';
													if(strpos($key, 'all') !== false) {
														$checked = 'checked';
													}
											?>
											<div class="drpChk">
												<label for="<?php echo $key; ?>" class="default_checkbox">
													<input type="checkbox" id="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?>>
													<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $val; ?></small></label>
											</div>
											<?php
												}
											?>
										</div>
									</div>
								</div>
								<div class="fProjectlr">
									<div class="multiselect pSelect">
										<?php
											$payment_receive_fulltime = $this->config->item('transactions_history_salary_payments_received_on_fulltime_jobs_option_list');
										?>
										<div class="selectBox">
											<select class="">
												<option><?php echo $this->config->item('transactions_history_salary_payments_received_on_fulltime_jobs_dropdown_option_name'); ?></option>
											</select>
											<div class="overSelect"></div>
										</div>
										<div id="checkboxes6" class="visible_option select_flex_width">
											<?php
												foreach($payment_receive_fulltime as $key => $val) {
													$checked = '';
													if(strpos($key, 'all') !== false) {
														$checked = 'checked';
													}
											?>
											<div class="drpChk">
												<label for="<?php echo $key; ?>" class="default_checkbox">
													<input type="checkbox" id="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?>>
													<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $val; ?></small></label>
											</div>
											<?php
												}
											?>
										</div>
									</div> 
								</div><div class="fProjectlr">
									<div class="multiselect pSelect">
										<?php
											$service_fees = $this->config->item('transactions_history_service_fees_payments_option_list');
										?>
										<div class="selectBox">
											<select class="">
												<option><?php echo $this->config->item('transactions_history_service_fees_payments_dropdown_option_name'); ?></option>
											</select>
											<div class="overSelect"></div>
										</div>
										<div id="checkboxes7" class="visible_option select_flex_width">
											<?php
												foreach($service_fees as $key => $val) {
													$checked = '';
													if(strpos($key, 'all') !== false) {
														$checked = 'checked';
													}
											?>
											<div class="drpChk">
												<label for="<?php echo $key; ?>" class="default_checkbox">
													<input type="checkbox" id="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?>>
													<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $val; ?></small></label>
											</div>
											<?php
												}
											?>
										</div>
									</div> 
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="filter_wrapper">
								<label class="defaultTag"><label class="checkboxes"><span class="tagFirst"><?php echo $this->config->item('transactions_history_deposits_dropdown_option_name'); ?></span><small class="tagSecond" style="display:block"><?php echo $this->config->item('transactions_history_all_option_txt'); ?><i class="fa fa-times" style="display:none" aria-hidden="true"></i></small></label>
								</label><label class="defaultTag"><label class="checkboxes1"><span class="tagFirst"><?php echo $this->config->item('transactions_history_withdrawals_dropdown_option_name'); ?></span><small class="tagSecond" style="display:block"><?php echo $this->config->item('transactions_history_all_option_txt'); ?></small></label>
								</label><label class="defaultTag"><label class="checkboxes5"><span class="tagFirst"><?php echo $this->config->item('transactions_history_project_upgrades_dropdown_option_name'); ?></span><small class="tagSecond" style="display:block"><?php echo $this->config->item('transactions_history_all_option_txt'); ?></small></label></label><label class="defaultTag"><label class="checkboxes4"><span class="tagFirst"><?php echo $this->config->item('transactions_history_payments_on_projects_dropdown_option_name'); ?></span><small class="tagSecond" style="display:block"><?php echo $this->config->item('transactions_history_all_option_txt'); ?></small></label></label><label class="defaultTag"><label class="checkboxes3"><span class="tagFirst"><?php echo $this->config->item('transactions_history_received_payments_on_projects_dropdown_option_name'); ?></span><small class="tagSecond" style="display:block"><?php echo $this->config->item('transactions_history_all_option_txt'); ?></small></label></label><label class="defaultTag"><label class="checkboxes2"><span class="tagFirst"><?php echo $this->config->item('transactions_history_payments_on_fulltime_jobs_dropdown_option_name'); ?></span><small class="tagSecond" style="display:block"><?php echo $this->config->item('transactions_history_all_option_txt'); ?></small></label></label><label class="defaultTag"><label class="checkboxes6"><span class="tagFirst"><?php echo $this->config->item('transactions_history_salary_payments_received_on_fulltime_jobs_dropdown_option_name'); ?></span><small class="tagSecond" style="display:block"><?php echo $this->config->item('transactions_history_all_option_txt'); ?></small></label></label><label class="defaultTag"><label class="checkboxes7"><span class="tagFirst"><?php echo $this->config->item('transactions_history_service_fees_payments_dropdown_option_name'); ?></span><small class="tagSecond" style="display:block"><?php echo $this->config->item('transactions_history_all_option_txt'); ?></small></label></label><label class="defaultTag"><button class="btn default_btn blue_btn btnBold clear_all_filters clear_all_filter"><?php echo $this->config->item('transactions_history_clear_filter_btn_text'); ?></button></label>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="notifyCollapse">
					<div class="transactionHistory">
						<div class="fix-loader" style="display:<?php echo ($transactions_histroy_cnt == 0) ? 'flex' : 'none';?>">
								<div id="loading">
										<div id="nlpt"></div>
										<div class="msg"><?php echo $this->config->item('transactions_history_loader_display_text'); ?></div>
								</div>
								<div class="no_filter_record" style="display:<?php echo ($transactions_histroy_cnt == 0) ? 'block' : 'none';?>">
										<div class="initialViewNorecord">
											<h4><?php echo $this->config->item('transactions_history_no_transaction_available_message'); ?></h4>
										</div>
								</div>
						</div>
						<div class="transaction_wrapper" style="display:<?php echo ($transactions_histroy_cnt == 0) ? 'none' : 'block';?>">
							<?php 
								if($transactions_histroy_cnt > 0) {
									echo $this->load->view('ajax_transactions_history_listing', ['transactions_histroy' => $transactions_histroy, 'transactions_histroy_cnt' => $transactions_histroy_cnt, 'limit' => $limit, 'is_last_page' => $is_last_page], true); 
								}
							?>
						</div>
					</div>
				</div>
				<!-- Notification Collapse End -->
				<!-- Content End -->
				
			</div>
        </div>
        <!-- Right Section End -->
    </div>
    <!-- Middle Section End -->
	
</div>
<?php
	$calendar_months = array_values($this->config->item('calendar_months'));
	$calendar_months_short_name = array_values($this->config->item('calendar_months_short_name'));
	$calendar_weekdays_short_name = array_values($this->config->item('calendar_weekdays_short_name'));
?>
<script type="text/javascript">
	var transactions_history_show_more_search_options_text = "<?php echo $this->config->item('transactions_history_show_more_search_options_text'); ?>";
	var transactions_history_hide_extra_search_options_text = "<?php echo $this->config->item('transactions_history_hide_extra_search_options_text'); ?>";
	var transactions_history_loader_progressbar_display_time = "<?php echo $this->config->item('transactions_history_loader_progressbar_display_time'); ?>";

	var calendar_months = JSON.parse('<?php echo json_encode($calendar_months); ?>');
	var calendar_months_short_name = JSON.parse('<?php echo json_encode($calendar_months_short_name); ?>');
	var calendar_weekdays_short_name = JSON.parse('<?php echo json_encode($calendar_weekdays_short_name); ?>');

</script>
<script src="<?= ASSETS ?>js/gijgo.min.js" type="text/javascript"></script>
<link href="<?= ASSETS ?>css/gijgo.min.css" rel="stylesheet" type="text/css" />
<script src="<?= ASSETS ?>js/modules/transactions_history.js"></script>
<script>
	function disputeTab(p)
	{
		$('.payAmount').hide();
		if (p=='ad'){
			$('.payAmount').show();
		}
	}
</script>