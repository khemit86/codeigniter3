 <!DOCTYPE html>
<html>
    <head>
	<!--<link rel="stylesheet" href="<?php echo CSS ?>bootstrap.min.css?v=<?= time() ?>" media="screen" type="text/css"/>
	<link rel="stylesheet" href="<?php echo CSS ?>main.css?v=<?= time() ?>" media="screen" type="text/css"/>
	<link href="<?php echo CSS; ?>modules/invoices.css" rel="stylesheet">-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	</head>
	<?php
		 $vat_per = $this->config->item('vat_percentage') + 100;
	?>
	<body>
	<div>
		<div style="margin: 0 15px;">
			<div>
				<div class="logoLeft">
					<img class="invoice" src="<?php echo ASSETS; ?>images/logo.png">
				</div>
				<div class="logoRight">
					<div class="logoRightText invoice" style="">
						<span class="invoice_no "><?php echo $this->config->item('invoice_format_invoice_number_lbl'); ?></span><span class="default_black_regular"> <?php echo $invoice_tracking_data['invoice_reference_id']; ?></span>
					</div>
				</div>
				<div class="clearfix"></div>
				<div style="width:100%; margin-top:10px; border-top:1px solid #ccc"></div>
			</div>
			<div class="clearfix"></div>
			<div style="float:left;width:50%">
					<div class="invoice_info"><h4><?php echo $this->config->item('invoice_format_form_lbl') ?></h4></div>
					<div class="default_black_regular"><?php echo $this->config->item('invoice_format_company_name'); ?></div>
					<div class="default_black_regular"><?php echo $this->config->item('invoice_format_company_address_line_1'); ?></div>
					<div class="default_black_regular"><?php echo $this->config->item('invoice_format_company_address_line_2'); ?></div>
					<div class="default_black_regular"><?php echo $this->config->item('invoice_format_company_country_name'); ?></div>
					<div><span class="default_black_bold"><?php echo $this->config->item('invoice_format_company_identification_number_lbl') . '</span> <span class="default_black_regular">' . $this->config->item('invoice_format_company_identification_number_value'); ?></span></div>
					<div><span class="default_black_bold"><?php echo $this->config->item('invoice_format_company_vat_registration_number_lbl') . '</span> <span class="default_black_regular">' . $this->config->item('invoice_format_company_vat_registration_number_value'); ?></span></div>

					<div style="margin-top:10px"><span class="default_black_bold"><?php echo $this->config->item('invoice_format_company_telephone_lbl'); ?></span> <span class="default_black_regular"><?php echo $this->config->item('invoice_format_company_telephone_value'); ?></span></div>
					<div><span class="default_black_bold"><?php echo $this->config->item('invoice_format_company_email_lbl'); ?></span> <span class="default_black_regular"><?php echo $this->config->item('invoice_format_company_email_value'); ?></span></div>

					<div style="margin-top:10px"><span class="default_black_bold"><?php echo $this->config->item('invoice_format_invoice_date_lbl'); ?></span><span class="default_black_regular" > <?php echo date(DATE_FORMAT, strtotime($invoice_tracking_data['invoice_generation_date'])) ?></span></div>
			</div>
			<div style="float:left;width:5%"></div>
			<div style="float:left;width:45%">
					<div class="invoice_info"><h4><?php echo $this->config->item('invoice_format_to_lbl') ?></h4></div>
					<?php 
						if(($user_details['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || empty($invoicing_details)) {
					?>
					<div class="userDetails"><?php echo ($user_details['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user_details['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_details['is_authorized_physical_person'] == 'Y') )? $user_details['first_name'] . ' ' . $user_details['last_name'] : $user_details['company_name']; ?></div>
					<div class="default_black_regular"><?php echo $user_details['street_address']; ?></div>
					<div class="default_black_regular"><?php echo $user_details['locality'] . ' ' . $user_details['postal_code']; ?></div>
					<div class="default_black_regular"><?php echo $user_details['country_name']; ?></div>
					<?php 
						} else if(!empty($invoicing_details)) {
					?>
						<div class="userDetails"><?php echo $invoicing_details['company_name']; ?></div>
						<div class="default_black_regular"><?php echo $invoicing_details['company_address_line_1']; ?></div>
						<div class="default_black_regular"><?php echo $invoicing_details['company_address_line_2']; ?></div>
						<div class="default_black_regular"><?php echo $invoicing_details['country_name']; ?></div>
						<div ><span class="default_black_bold"><?php echo $this->config->item('invoice_format_company_identification_number_lbl') ?></span> <span class="default_black_regular"><?php echo $invoicing_details['company_registration_number']; ?></span></div>
						<?php
							if($invoicing_details['company_not_vat_registered'] == 'N') {
						?>
						<div ><span class="default_black_bold"><?php echo $this->config->item('invoice_format_company_vat_registration_number_lbl'); ?></span> <span class="default_black_regular"><?php echo $invoicing_details['company_vat_number']; ?></span></div>
						<?php 
							}
						?>
					<?php
						}
					?>
					
			</div>
			<div class="clearfix"></div>

			<div class="row">
				<div class="col-sm-12 col-md-12 col-xs-12">
					<div class="table-responsive invoice">
						<table class="table table-bordered" >
							<thead>
								<tr >
									<th class="text-center cell_padding"><?php echo $this->config->item('invoice_format_table_heading_pos_txt'); ?></th>
									<th class="text-center cell_padding"><?php echo $this->config->item('invoice_format_table_heading_description_txt'); ?></th>
									<th class="text-center cell_padding cost1_head"><?php echo $this->config->item('invoice_format_table_heading_amount_excluding_vat_txt'); ?></th>
									<th class="text-center cell_padding cost2_head"><?php echo $this->config->item('invoice_format_table_heading_unit_amount_txt'); ?></th>

								</tr>
							</thead>
							<tbody>
								<?php
								$cnt = 1;
								$amount = 0;
								foreach ($service_fees as $val) {
									$amount += $val['charged_service_fee_value'];
									?>
									<tr>
										<td class="text-center cell_padding"><?php echo $cnt; ?></td>
										<td class="cell_padding">
											<?php
											if ($val['project_type'] != 'fulltime') {
												$username = '';
												if ($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) {
													$username = $val['first_name'] . ' ' . $val['last_name'];
												} else {
													$username = $val['company_name'];
												}
												$project_url = $this->config->item('project_detail_page_url') . '?id=' . $val['project_id'];
												$project_title = $val['project_title'] . ' (' . $this->config->item('transactions_history_project_id_txt') . " " . $val['project_id'] . ')';
												$service_fees_txt = $this->config->item('invoice_format_service_fees_related_project_payment_txt');
												$service_fees_txt = str_replace(['{user_first_name_last_name_or_company_name}', '{project_url}', '{project_title}'], [$username, $project_url, $project_title], $service_fees_txt);
												?>
												<div class="col-md-12 col-sm-12 col-12 dfThistory">
													<label>
														<div>
															<span class="default_black_regular"><?php echo $service_fees_txt; ?></span>
														</div>
													</label>
													<label>
														<div>
															<b class="default_black_bold"><?php echo $this->config->item('transactions_history_payment_date_lbl'); ?></b> <span class="default_black_regular"><?php echo date(DATE_TIME_FORMAT, strtotime($val['escrow_payment_release_date'])); ?></span>
														</div>
													</label>
													<label>
														<div>
															<b class="default_black_bold"><?php echo $this->config->item('transactions_history_payment_reference_id_lbl'); ?></b> <span class="default_black_regular"><?php echo $val['released_escrow_payment_reference_id']; ?></span>
														</div>
													</label>
												</div>
												<?php
											} else {
												$username = '';
												if ($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) {
													$username = $val['first_name'] . ' ' . $val['last_name'];
												} else {
													$username = $val['company_name'];
												}
												$project_url = $this->config->item('project_detail_page_url') . '?id=' . $val['project_id'];
												$project_title = $val['project_title'] . ' (' . $this->config->item('transactions_history_project_id_txt') . " " . $val['project_id'] . ')';
												$service_fees_txt = $this->config->item('invoice_format_service_fees_related_salary_payment_txt');
												$service_fees_txt = str_replace(['{user_first_name_last_name_or_company_name}', '{project_url}', '{project_title}'], [$username, $project_url, $project_title], $service_fees_txt);
												?>
												<div class="col-md-12 col-sm-12 col-12 dfThistory">
													<label>
														<div>
															<span class="default_black_regular"><?php echo $service_fees_txt; ?></span>
														</div>
													</label>
													<label>
														<div>
															<b class="default_black_bold"><?php echo $this->config->item('transactions_history_payment_date_lbl'); ?></b> <span class="default_black_regular"><?php echo date(DATE_TIME_FORMAT, strtotime($val['escrow_payment_release_date'])); ?></span>
														</div>
													</label>
													<label>
														<div>
															<b class="default_black_bold"><?php echo $this->config->item('transactions_history_payment_reference_id_lbl'); ?></b> <span class="default_black_regular"><?php echo $val['released_escrow_payment_reference_id']; ?></span>
														</div>
													</label>
												</div>
												<?php
											}
											?>
										</td>
										<td class="text-center cell_padding cost1_body"><?php echo format_money_amount_display($val['charged_service_fee_value_excl_vat']); ?><!--1222222222222222222--></td>
										<td class="text-center cell_padding cost1_body"><?php echo format_money_amount_display($val['charged_service_fee_value']); ?><!--1222222222222222222222--></td>

									</tr>
									<?php
									$cnt++;
								}

								foreach ($admin_dispute_service_fees as $val) {
									$amount += $val['admin_dispute_arbitration_amount_fee'];
									?>
									<tr>
										<td class="text-center cell_padding"><?php echo $cnt; ?></td>
										<td class="cell_padding">
											<?php
											if ($val['project_type'] != 'fulltime') {
												$username = '';
												if ($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) {
													$username = $val['first_name'] . ' ' . $val['last_name'];
												} else {
													$username = $val['company_name'];
												}
												$project_url = $this->config->item('project_detail_page_url') . '?id=' . $val['disputed_project_id'];
												$project_title = $val['project_title'] . ' (' . $this->config->item('transactions_history_project_id_txt') . " " . $val['disputed_project_id'] . ')';
												$service_fees_txt = $this->config->item('invoice_format_admin_dispute_moderation_fee_on_project_txt');
												$service_fees_txt = str_replace(['{dispute_id}', '{project_url}', '{project_title}'], [$val['dispute_reference_id'], $project_url, $project_title], $service_fees_txt);
												?>
												<div class="col-md-12 col-sm-12 col-12 dfThistory">
													<label>
														<div>
															<span class="default_black_regular"><?php echo $service_fees_txt; ?></span>
														</div>
													</label>
													<label>
														<div>
															<b class="default_black_bold"><?php echo $this->config->item('transactions_history_payment_date_lbl'); ?></b> <span class="default_black_regular"><?php echo date(DATE_TIME_FORMAT, strtotime($val['dispute_negotiation_end_date'])); ?></span>
														</div>
													</label>
												</div>
												<?php
											} else {
												$username = '';
												if ($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) {
													$username = $val['first_name'] . ' ' . $val['last_name'];
												} else {
													$username = $val['company_name'];
												}
												$project_url = $this->config->item('project_detail_page_url') . '?id=' . $val['disputed_project_id'];
												$project_title = $val['project_title'] . ' (' . $this->config->item('transactions_history_project_id_txt') . " " . $val['disputed_project_id'] . ')';
												$service_fees_txt = $this->config->item('invoice_format_admin_dispute_moderation_fee_on_fulltime_project_txt');
												$service_fees_txt = str_replace(['{dispute_id}', '{project_url}', '{project_title}'], [$val['dispute_reference_id'], $project_url, $project_title], $service_fees_txt);
												?>
												<div class="col-md-12 col-sm-12 col-12 dfThistory">
													<label>
														<div>
															<span class="default_black_regular"><?php echo $service_fees_txt; ?></span>
														</div>
													</label>
													<label>
														<div>
															<b class="default_black_bold"><?php echo $this->config->item('transactions_history_payment_date_lbl'); ?></b> <span class="default_black_regular"><?php echo date(DATE_TIME_FORMAT, strtotime($val['dispute_negotiation_end_date'])); ?></span>
														</div>
													</label>
												</div>
												<?php
											}
											?>
										</td>
										<td class="text-center cell_padding cost1_body"><?php echo format_money_amount_display($val['admin_dispute_arbitration_amount_fee'] - $val['admin_dispute_arbitration_amount_vat_value']); ?><!--1222222222222222222--></td>
										<td class="text-center cell_padding cost1_body"><?php echo format_money_amount_display($val['admin_dispute_arbitration_amount_fee']); ?><!--1222222222222222222222--></td>

									</tr>
									<?php
									$cnt++;
								}

								foreach ($purchased_upgrades as $val) {
									if ($val['project_upgrade_purchase_source'] == 'real_money') {
										$amount += $val['project_upgrade_purchase_value'];
									}
									$project_url = $this->config->item('project_detail_page_url') . '?id=' . $val['project_id'];
									$project_title = $val['project_title'] . ' (' . $this->config->item('transactions_history_project_id_txt') . " " . $val['project_id'] . ')';
									$service_fees_txt = $this->config->item('invoice_format_upgrade_purchase_on_project_txt');
									if ($val['project_type'] == 'fulltime') {
										$service_fees_txt = $this->config->item('invoice_format_upgrade_purchase_on_fulltime_job_txt');
									}
									$service_fees_txt = str_replace(['{project_upgrade_type}', '{project_url}', '{project_title}'], [$this->config->item('transaction_history_project_upgrades_upgrade_types')[$val['project_upgrade_type']], $project_url, $project_title], $service_fees_txt);
									if ($val['project_upgrade_purchase_source'] == 'real_money') {
										?>
										<tr>
											<td class="text-center cell_padding"><?php echo $cnt; ?></td>
											<td class="cell_padding">
												<div class="col-md-12 col-sm-12 col-12 dfThistory">
													<label>
														<div>
															<span class="default_black_regular"><?php echo $service_fees_txt; ?></span>
														</div>
													</label>
													<label>
														<div>
															<b class="default_black_bold"><?php echo $this->config->item('transactions_history_project_upgrade_purchase_date_lbl'); ?></b> <span class="default_black_regular"><?php echo date(DATE_TIME_FORMAT, strtotime($val['project_upgrade_purchase_date'])); ?></span>
														</div>
													</label>
													<label>
														<div>
															<?php
															$source = '';
															if ($val['project_upgrade_purchase_source'] == 'membership_included') {
																$source = $this->config->item('transactions_history_project_upgrade_purchase_included_membership_txt');
															} else if ($val['project_upgrade_purchase_source'] == 'bonus_based') {
																$source = $this->config->item('transactions_history_project_upgrade_purchase_bonus_balance_txt');
															} else {
																$source = $this->config->item('transactions_history_project_upgrade_purchase_account_balance_txt');
															}
															?>
															<b class="default_black_bold"><?php echo $this->config->item('transactions_history_project_upgrade_payment_source_lbl'); ?></b> <span class="default_black_regular"><?php echo $source; ?></span>
														</div>
													</label>
													<label>
														<div>
															<b class="default_black_bold"><?php echo $this->config->item('transactions_history_payment_reference_id_lbl'); ?></b> <span class="default_black_regular"><?php echo $val['project_upgrade_purchase_reference_id']; ?></span>
														</div>
													</label>
												</div>
											</td>
											<td class="text-center cell_padding cost1_body"><?php echo format_money_amount_display($val['project_upgrade_purchase_value_excl_vat']); ?></td>
											<td class="text-center cell_padding"><?php echo format_money_amount_display($val['project_upgrade_purchase_value']); ?></td>

										</tr>
										<?php
									} else {
										?>
										<tr>
											<td class="text-center cell_padding"><?php echo $cnt; ?></td>
											<td class="cell_padding">
												<div class="col-md-12 col-sm-12 col-12 dfThistory">
													<label>
														<div>
															<span class="default_black_regular"><?php echo $service_fees_txt; ?></span>
														</div>
													</label>
													<label>
														<div>
															<b class="default_black_bold"><?php echo $this->config->item('transactions_history_project_upgrade_purchase_date_lbl'); ?></b> <span class="default_black_regular"><?php echo date(DATE_TIME_FORMAT, strtotime($val['project_upgrade_purchase_date'])); ?></span>
														</div>
													</label>
													<label>
														<div>
															<?php
															$source = '';
															if ($val['project_upgrade_purchase_source'] == 'membership_included') {
																$source = $this->config->item('transactions_history_project_upgrade_purchase_included_membership_txt');
															} else if ($val['project_upgrade_purchase_source'] == 'bonus_based') {
																$source = $this->config->item('transactions_history_project_upgrade_purchase_bonus_balance_txt');
															} else {
																$source = $this->config->item('transactions_history_project_upgrade_purchase_account_balance_txt');
															}
															?>
															<b class="default_black_bold"><?php echo $this->config->item('transactions_history_project_upgrade_payment_source_lbl'); ?></b> <span class="default_black_regular"><?php echo $source; ?></span>
														</div>
													</label>
													<label>
														<div>
															<b class="default_black_bold"><?php echo $this->config->item('transactions_history_payment_reference_id_lbl'); ?></b> <span class="default_black_regular"><?php echo $val['project_upgrade_purchase_reference_id']; ?></span>
														</div>
													</label>
												</div>
											</td>
											<td class="text-center cell_padding cost1_body"><?php echo format_money_amount_display($val['project_upgrade_purchase_value_excl_vat']); ?></td>
											<td class="text-center cell_padding"><?php echo format_money_amount_display($val['project_upgrade_purchase_value']); ?></td>

										</tr>
										<tr>
											<td class="text-center cell_padding"></td>
											<td class="cell_padding">
												<div class="col-md-12 col-sm-12 col-12 dfThistory">
													<label>
														<div>
															<span class="default_black_regular"><?php echo $service_fees_txt; ?></span>
														</div>
													</label>
													<label>
														<div>
															<b class="default_black_bold"><?php echo $this->config->item('transactions_history_project_upgrade_purchase_date_lbl'); ?></b> <span class="default_black_regular"><?php echo date(DATE_TIME_FORMAT, strtotime($val['project_upgrade_purchase_date'])); ?></span>
														</div>
													</label>
													<label>
														<div>
															<?php
															$source = '';
															if ($val['project_upgrade_purchase_source'] == 'membership_included') {
																$source = $this->config->item('transactions_history_project_upgrade_purchase_included_membership_txt');
															} else if ($val['project_upgrade_purchase_source'] == 'bonus_based') {
																$source = $this->config->item('transactions_history_project_upgrade_purchase_bonus_balance_txt');
															} else {
																$source = $this->config->item('transactions_history_project_upgrade_purchase_account_balance_txt');
															}
															?>
															<b class="default_black_bold"><?php echo $this->config->item('transactions_history_project_upgrade_payment_source_lbl'); ?></b> <span class="default_black_regular"><?php echo $source; ?></span>
														</div>
													</label>
													<label>
														<div>
															<b class="default_black_bold"><?php echo $this->config->item('transactions_history_payment_reference_id_lbl'); ?></b> <span class="default_black_regular"><?php echo $val['project_upgrade_purchase_reference_id']; ?></span>
														</div>
													</label>
												</div>
											</td>
											<td class="text-center text-danger cell_padding cost1_body"><?php echo '-' . format_money_amount_display($val['project_upgrade_purchase_value_excl_vat']); ?></td>
											<td class="text-center text-danger cell_padding"><?php echo '-' . format_money_amount_display($val['project_upgrade_purchase_value']); ?></td>

										</tr>
										<?php
									}
									$cnt++;
								}

									foreach($deposit_funds_paypal_charges as $val) {
										$amount += $val['total_transaction_charged_fee'];
								?>
										<tr>
											<td class="text-center cell_padding"><?php echo $cnt; ?></td>
											<td class="cell_padding">
												<?php
													$invoice_format_deposit_funds_via_paypal_transaction_fee_txt = $this->config->item('invoice_format_deposit_funds_via_paypal_transaction_fee_txt');
													$invoice_format_deposit_funds_via_paypal_transaction_fee_txt = str_replace(['{transaction_id}','{transaction_date}'], [$val['transaction_id'], date(DATE_TIME_FORMAT, strtotime($val['transaction_date']))], $invoice_format_deposit_funds_via_paypal_transaction_fee_txt);
												?>
												<div class="col-md-12 col-sm-12 col-12 dfThistory">
														<label>
															<div>
																<span class="default_black_regular"><?php echo $invoice_format_deposit_funds_via_paypal_transaction_fee_txt; ?></span>
															</div>
														</label>
												</div>
											</td>
											<td class="text-center cell_padding cost1_body"><?php echo format_money_amount_display(($val['total_transaction_charged_fee'] * 100) / $vat_per); ?><!--1222222222222222222--></td>
											<td class="text-center cell_padding cost1_body"><?php echo format_money_amount_display($val['total_transaction_charged_fee']); ?><!--1222222222222222222222--></td>
										</tr>
								<?php
										$cnt++;
									}

									foreach($deposit_funds_payment_procesor_charges as $val) {
										$amount += $val['business_transaction_charged_fee'];
								?>
										<tr>
											<td class="text-center cell_padding"><?php echo $cnt; ?></td>
											<td class="cell_padding">
												<?php
													if($val['deposit_transfer_type'] == 'bank_transfer_transaction') {
														$payment_method = $this->config->item('deposit_funds_via_payment_processor_method_id_associated_method_name')[$val['method_id']];
														$invoice_format_deposit_funds_via_payment_processor_transaction_fee_txt = $this->config->item('invoice_format_deposit_funds_via_payment_processor_transaction_fee_using_bank_transfer_txt');
														$invoice_format_deposit_funds_via_payment_processor_transaction_fee_txt = str_replace(['{transaction_id}','{transaction_date}', '{bank_name}', '{payment_method}'], [$val['payment_id'], date(DATE_TIME_FORMAT, strtotime($val['transaction_completion_date'])), $val['bank_name'], $payment_method], $invoice_format_deposit_funds_via_payment_processor_transaction_fee_txt);
													} else {
														
														$invoice_format_deposit_funds_via_payment_processor_transaction_fee_txt = $this->config->item('invoice_format_deposit_funds_via_payment_processor_transaction_fee_using_payment_card_transfer_txt');
														$invoice_format_deposit_funds_via_payment_processor_transaction_fee_txt = str_replace(['{transaction_id}','{transaction_date}', '{card_brand}'], [$val['payment_id'], date(DATE_TIME_FORMAT, strtotime($val['transaction_completion_date'])), $val['card_brand']], $invoice_format_deposit_funds_via_payment_processor_transaction_fee_txt);
													}
													
												?>
												<div class="col-md-12 col-sm-12 col-12 dfThistory">
														<label>
															<div>
																<span class="default_black_regular"><?php echo $invoice_format_deposit_funds_via_payment_processor_transaction_fee_txt; ?></span>
															</div>
														</label>
												</div>
											</td>
											<td class="text-center cell_padding cost1_body"><?php echo format_money_amount_display(($val['business_transaction_charged_fee'] * 100) / $vat_per); ?><!--1222222222222222222--></td>
											<td class="text-center cell_padding cost1_body"><?php echo format_money_amount_display($val['business_transaction_charged_fee']); ?><!--1222222222222222222222--></td>
										</tr>
								<?php
										$cnt++;
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
                        <div class="row">
            <div class="col-sm-6 col-md-6 col-xs-6"></div>
            <div class="col-sm-6 col-md-6 col-xs-6 pull-right">
                <div class="table-responsive">
                    <?php
                    $vat_percemtage = $this->config->item('invoice_format_table_heading_vat_percentage_txt');
                    $vat_percemtage = str_replace('{vat_percentage}', $this->config->item('vat_percentage'), $vat_percemtage);

                   
                    $vat_exclude_amount = ($amount * 100) / $vat_per;
                    $vat_amount = $amount - $vat_exclude_amount;
                    ?>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="default_black_bold text-right cell_padding"><?php echo $this->config->item('invoice_format_table_heading_total_excluded_vat_txt'); ?></td>
                                <td class="default_black_regular text-right cell_padding"><?php echo format_money_amount_display($vat_exclude_amount) . ' ' . CURRENCY; ?></td>
                            </tr>
                            <tr>
                                <td class="default_black_bold text-right cell_padding"><?php echo $vat_percemtage; ?></td>
                                <td class="default_black_regular text-right cell_padding"><?php echo format_money_amount_display($vat_amount) . ' ' . CURRENCY; ?></td>
                            </tr>
                            <tr>
                                <td class="default_black_bold text-right cell_padding"><?php echo $this->config->item('invoice_format_table_heading_total_amount_txt'); ?></td>
                                <td class="default_black_regular text-right cell_padding"><?php echo format_money_amount_display($amount) . ' ' . CURRENCY; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
			
                        <div class="clearfix"></div>
			<div style="width:100%; border-top:1px solid #ccc">
                                    <div style="text-align:center; margin-top:10px;"><?php echo $this->config->item('invoice_format_footer_txt'); ?></div>
			</div>
			
		</div>
	</div>
</body>
</html>