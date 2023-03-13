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
        <div id="content" class="invoices_page body_distance_adjust <?php echo $invoices_count == 0 ? 'no_data_msg_display_center' : '' ?>">
					<?php 
						$heading = $this->config->item('invoices_tracking_heading');
						$heading = str_replace('{next_invoice_date}', $next_invoice_date, $heading);

						$no_record = $this->config->item('invoices_tracking_no_record');
						$no_record = str_replace('{next_invoice_date}', $next_invoice_date, $no_record);
					?>
					<div class="etSecond_step" >				
						<!-- Heading Text Start -->
						<div class="default_page_heading" style="display:<?php echo $invoices_count != 0 ? 'block' : 'none'; ?>">
							<h4><?php echo $this->config->item('finance_headline_title_invoices'); ?></h4>
							<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
						</div>
						<!-- Heading Text End -->

						<!-- Content Start -->
						<div class="invoiceInfo" style="display:<?php echo $invoices_count != 0 ? 'block' : 'none'; ?>">
							
							<p><?php echo $heading; ?></p>
						</div>
						<?php 
							if($invoices_count != 0) {
						?>
						<div class="tInvoice">
							<div class="row">
								<div class="col-md-3 col-sm-3 col-12 default_country">
									<div class="selectYear" style="display: <?php echo count($years) <= 1 ? 'none' : 'block' ?>">
										<div class="form-group default_dropdown_select">
											<select id="year">
												<option value=""><?php echo $this->config->item('invoices_tracking_all_years_option_name'); ?></option>
												<?php
													foreach($years as $val) {
														echo '<option value="'.$val.'">'.$val.'</option>';
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-4 col-sm-4 col-12 btnSection">
									<button class="btn blue_btn default_btn search_filter" style="display: <?php echo count($years) <= 1 ? 'none' : 'block' ?>" type="button"><?php echo $this->config->item('invoices_tracking_search_btn'); ?></button>
									<!-- <button class="btn red_btn default_btn" type="button">Reset</button> -->
								</div>
								<div class="col-md-5 col-sm-5 col-12 ">
									<div class="totalIvc default_black_bold_medium"><?php echo $this->config->item('invoices_tracking_total_invoices_txt'); ?><span id="total_invoice"><?php echo $invoices_count; ?></span></div>
								</div>
							</div>
						</div>
						<div class="adp" id="invoice_list_page">
							<div class="ivOnlyHead">
								<div class="row">
									<div class="col-md-3 col-sm-3 col-12 stepOneHead">
										<label class="default_black_regular_medium"><b class="default_black_bold_medium"><?php echo $invoices_count == 1 ? $this->config->item('invoices_tracking_invoice_number_txt') : $this->config->item('invoices_tracking_invoices_number_txt'); ?></b></label>
									</div>
									<div class="col-md-3 col-sm-3 col-12 stepTwoHead">
										<label class="default_black_regular_medium"><b class="default_black_bold_medium"><?php echo $this->config->item('invoices_tracking_invoice_generated_month_txt'); ?></b></label>
									</div>
									<div class="col-md-4 col-sm-4 col-12 stepThreeHead">
										<label class="default_black_regular_medium"><b class="default_black_bold_medium"><?php echo $this->config->item('invoices_tracking_invoice_amount_txt'); ?></b></label>
									</div>
									<div class="col-md-2 col-sm-2 col-12 stepFourHead"></div>
								</div>
							</div>
							<div class="invoice_wrapper">
								<?php echo $this->load->view('ajax_user_invoices_listing', ['invoices' => $invoices, 'pagination_links' => $pagination_links, 'invoices_count' => $invoices_count, 'limit' => $limit, 'rec_per_page' => $rec_per_page, 'page_no' => $page_no], true); ?>
							</div>
						</div>
						<?php 
							}
						?>
						<!-- Content End -->				
						<div class="initialViewNorecord" style="display : <?php echo $invoices_count == 0 ? 'block' : 'none'; ?> ">
							<?php echo  $no_record; ?>
						</div>
					</div>
					
        </div>
        <!-- Right Section End -->
    </div>
    <!-- Middle Section End -->
	
</div>
<script type="text/javascript">
</script>
<script src="<?= ASSETS ?>js/modules/invoices.js"></script>