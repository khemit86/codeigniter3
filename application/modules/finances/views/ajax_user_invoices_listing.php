<div class="invoice_list_section">
<?php
	$invoice_amt_limit = $this->config->item('invoices_tracking_invoice_value_more_than_minimum_allowed_amount');
  foreach($invoices as $val) {
	$hide_pdf = false;
	if($val['invoice_total_bonuses_amount'] == 0 && $val['invoice_total_membership_included_amount'] == 0 && $val['invoice_total_amount'] == 0) {
		$hide_pdf = true;
	}
?>
<div class="ivOnly">
	<div class="row">
		<div class="col-md-3 col-sm-3 col-12 stepOne">
			<label class="default_black_regular_medium ivOnly_mobile"><b class="default_black_bold_medium"><?php echo $this->config->item('invoices_tracking_invoice_number_txt'); ?>:</b></label>
			<label class="default_black_regular_medium"><?php echo $val['invoice_reference_id']; ?></label>
		</div>
		<div class="col-md-3 col-sm-3 col-12 stepTwo">
			<label class="default_black_regular_medium ivOnly_mobile"><b class="default_black_bold_medium"><?php echo $this->config->item('invoices_tracking_invoice_generated_month_txt'); ?>:</b></label>
			<label class="default_black_regular_medium"><?php echo date(DATE_FORMAT, strtotime($val['invoice_generation_date'])) ?></label>
		</div>
		<div class="col-md-4 col-sm-4 col-12 stepThree">
			<label class="default_black_regular_medium ivOnly_mobile"><b class="default_black_bold_medium"><?php echo $this->config->item('invoices_tracking_invoice_amount_txt'); ?>:</b></label>
			<label class="default_black_regular_medium"><span class="invAmount"><?php echo format_money_amount_display($val['invoice_total_amount']).' '.CURRENCY; ?></span></label>
		</div>
		<?php
		$stepFourClass = "stepFourBlank";
		if((!$hide_pdf && $val['invoice_total_amount'] < $invoice_amt_limit) || $val['invoice_total_amount'] > $invoice_amt_limit){
			$stepFourClass = "";
		}
		?>
		<div class="col-md-2 col-sm-2 col-12 stepFour <?php echo $stepFourClass; ?>">
			<?php 
				if(!$hide_pdf && $val['invoice_total_amount'] < $invoice_amt_limit) {
			?>
			<div class="pdfBtn">
				<div class="downloadPdf">
					<button class="download_invoice" data-id="<?php echo $val['id']; ?>"><i class="fas fa-download"></i><?php echo $this->config->item('invoices_tracking_download_invoice_as_pdf_extension_txt'); ?></button>
				</div>
			</div>
			<?php
				} else if($val['invoice_total_amount'] > $invoice_amt_limit) {
			?>
				<label class="default_black_regular_medium pdfDetailsText"><?php echo $this->config->item('invoices_tracking_for_invoice_contact_support_txt'); ?></label>
			<?php
				}
			?>
			
		</div>
	</div>
</div>
<?php
  }
?>
</div>
<?php 
  if(!empty($invoices)) {
?>
<div class="pagnOnly">
  <div class="row">
    <div class="<?php echo !empty($pagination_links) ? 'col-md-7 col-sm-7 col-12' : 'col-md-12 col-sm-12 col-12' ?>">
      <div class="pageOf">
        <label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no"><?php echo $page_no; ?></span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $invoices_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
      </div>
    </div>
    <div class="col-md-5 col-sm-5 col-12" style="display:<?php echo !empty($pagination_links) ? 'block' : 'none'; ?>">
      <div class="modePage">
        <?php echo $pagination_links; ?>
      </div>
    </div>
  </div>
</div>
<?php
  }
?>