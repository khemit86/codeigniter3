<?php
$escrow_form_heading = $this->config->item('project_details_page_project_create_escrow_form_heading_po_view');
$description_input_label = $this->config->item('project_details_page_project_description_create_escrow_form_po_view');
$amount_input_label = $this->config->item('project_details_page_project_amount_create_escrow_form_po_view');
$bussiness_service_fee_input_label = $this->config->item('project_details_page_project_business_service_fee_create_escrow_form_po_view');
$total_input_label = $this->config->item('project_details_page_project_total_amount_create_escrow_form_po_view');
$escrow_payment_button = $this->config->item('project_details_page_project_create_escrow_form_create_escrow_button_txt_po_view');
$cancel_escrow_payment_button = $this->config->item('cancel_btn_txt');
if($project_type == 'fixed'){
	$description_tooltip_msg = $this->config->item('project_details_page_fixed_budget_project_create_escrow_form_tooltip_message_description_po_view');	
	$amount_tooltip_msg = $this->config->item('project_details_page_fixed_budget_project_create_escrow_form_tooltip_message_amount_po_view');
	$bussiness_service_fee_tooltip_msg = $this->config->item('project_details_page_fixed_budget_project_create_escrow_form_tooltip_message_business_service_fee_po_view');
	
}
else if($project_type == 'hourly'){
	$description_tooltip_msg = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_form_tooltip_message_description_po_view');
	$amount_tooltip_msg = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_form_tooltip_message_amount_po_view');	
	$bussiness_service_fee_tooltip_msg = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_form_tooltip_message_business_service_fee_po_view');
	
	
	
	$hourly_rate_input_label = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_form_hourly_rate_po_view');
	$hourly_rate_tooltip_msg = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_form_tooltip_message_hourly_rate_po_view');
	$number_of_hours_input_label = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_form_number_of_hours_po_view');
	$number_of_hours_tooltip_msg = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_form_tooltip_message_number_of_hours_po_view');
} else if($project_type == 'fulltime') {
	$escrow_form_heading = $this->config->item('project_details_page_fulltime_project_create_milestone_form_heading_employer_view');
	$description_input_label = $this->config->item('project_details_page_fulltime_project_description_create_milestone_form_employer_view');
	$amount_input_label = $this->config->item('project_details_page_fulltime_project_amount_create_milestone_form_employer_view');
	$bussiness_service_fee_input_label = $this->config->item('project_details_page_fulltime_project_business_service_fee_create_milestone_form_employer_view');

	$total_input_label = $this->config->item('project_details_page_fulltime_project_total_amount_create_milestone_milestone_form_employer_view');

	$description_tooltip_msg = $this->config->item('project_details_page_fulltime_project_create_milestone_form_tooltip_message_description_employer_view');	
	$amount_tooltip_msg = $this->config->item('project_details_page_fulltime_project_create_milestone_form_tooltip_message_amount_employer_view');
	$bussiness_service_fee_tooltip_msg = $this->config->item('project_details_page_fulltime_project_create_milestone_form_tooltip_message_business_service_fee_employer_view');
	$escrow_payment_button = $this->config->item('project_details_page_fulltime_project_create_milestone_form_create_milestone_button_txt_employer_view');
	$cancel_escrow_payment_button = $this->config->item('project_details_page_fulltime_project_create_milestone_form_cancel_button_txt_employer_view');
}

?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-12">
		<form id="<?php echo "create_escrow_form_".$section_name."_".$section_id; ?>" name="create_escrow">
		<input type="hidden" value="<?php echo $project_id; ?>" name="project_id">	
		<input type="hidden" value="<?php echo $po_id; ?>" name="po_id">	
		<input type="hidden" value="<?php echo $sp_id; ?>" name="sp_id">	
		<input type="hidden" value="<?php echo $section_id; ?>" name="section_id">	
		<input type="hidden" value="<?php echo $section_name; ?>" name="section_name">	
		<?php
			if($project_type == 'hourly') {
		?>
		<input type="hidden" value="<?php echo ($section_name != 'inprogress') ? 0 : $initial_project_agreed_hourly_rate;?>" name="sp_hourly_rate">
		<?php 
			}
		?>
		<div class="default_black_bold_bigger escrow_owner"><?php echo str_replace(array('{user_first_name_last_name_or_company_name}'),array($service_provider_name),$escrow_form_heading); ?></div> 
		<div class="bidDays">
			<div class="form-group formGroupOnly">
				<div class="Bid_Amount inBid_Description">
					<b class="default_black_bold"><?php echo $description_input_label; ?></b><i class="fa fa-question-circle default_icon_help tooltipAuto" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $description_tooltip_msg; ?>"></i>
					<input type="text" class="escrow_description form-control avoid_space login_register_input_field" style="text-align:left; width: 100%;" name="escrow_description" data-section-name="<?php echo $section_name; ?>" data-section-id="<?php echo $section_id; ?>"  id="<?php echo "escrow_description_".$section_name."_".$section_id; ?>" maxlength="<?php echo $this->config->item('escrow_description_maximum_length_character_limit_escrow_form')?>">
					<div class="error_div_sectn clearfix default_error_div_sectn">
						<span class="content-count escrow_description_length_count_message"><?php echo $this->config->item('escrow_description_maximum_length_character_limit_escrow_form')."&nbsp;".$this->config->item('characters_remaining_message'); ?></span>
						<span class="error_msg" id="<?php echo "escrow_description_".$section_name."_".$section_id."_error"; ?>"></span>
					</div>
				</div>
				<?php
						if($project_type == 'hourly') {
				?>	
					<div class="Bid_Amount inBid_autoHeight fontSize0 inBid_autoWidth_one">
						<b class="default_black_bold"><?php echo $hourly_rate_input_label;?></b><i class="fa fa-question-circle default_icon_help tooltipAuto" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $hourly_rate_tooltip_msg; ?>"></i>
						<span class="touch_line_break"><input type="text" class="form-control avoid_space login_register_input_field escrow_request_hourly_rate" id="escrow_request_hourly_rate" name="escrow_request_hourly_rate" style="text-align:right;" maxlength="<?php echo $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_request_form_hourly_rate_length_digits_limit');?>" <?php echo ($section_name == 'inprogress' && $initial_project_agreed_hourly_rate != 0) ? 'readonly' : '' ?> value="<?php echo ($section_name == 'inprogress' && $initial_project_agreed_hourly_rate != 0) ? str_replace(".00","",number_format($initial_project_agreed_hourly_rate,  2, '.', ' ')) : ''; ?>"><b class="default_black_bold"><?php echo CURRENCY; ?></b></span>
						<div class="error_div_sectn clearfix gapAdjust_error">
							<span id="escrow_request_hourly_rate_error" class="error_msg"></span>
						</div>
					</div>
					<div class="Bid_Amount inBid_autoHeight fontSize0 inBid_autoWidth_two">
						<b class="default_black_bold"><?php echo $number_of_hours_input_label;?></b><i class="fa fa-question-circle default_icon_help tooltipAuto" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $number_of_hours_tooltip_msg; ?>"></i>
						<span class="touch_line_break"><input type="text" class="form-control avoid_space login_register_input_field escrow_request_no_of_hours" id="escrow_request_no_of_hours" name="escrow_request_no_of_hours" style="text-align:right;" maxlength="<?php echo $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_request_form_number_of_hours_length_digits_limit');?>"></span>
						<div class="error_div_sectn clearfix">
							<span id="escrow_request_no_of_hours_error" class="error_msg"></span>
						</div>
					</div>		
					<?php 
						}
					?>
				<div class="Bid_Amount inBid_autoHeight fontSize0 inBid_autoWidth_three">
					<b class="default_black_bold"><?php echo $amount_input_label; ?></b><i class="fa fa-question-circle default_icon_help tooltipAuto" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $amount_tooltip_msg; ?>"></i>
					<span class="touch_line_break inprogress_amountOnly"><input type="text" class="form-control avoid_space login_register_input_field escrow_amount" name="escrow_amount" style="text-align:right;" <?php echo ($project_type == 'hourly') ? 'readonly' : ''; ?> maxlength="<?php echo $this->config->item('escrow_amount_length_character_limit_escrow_form');?>" data-section-name="<?php echo $section_name; ?>" data-section-id="<?php echo $section_id; ?>" data-project-id="<?php echo $project_id; ?>" placeholder="" id="<?php echo "escrow_amount_".$section_name."_".$section_id; ?>"><b class="default_black_bold"><?php echo CURRENCY; ?></b></span>
					<div class="error_div_sectn clearfix gapAdjust_error">
						<span id="<?php echo "escrow_amount_".$section_name."_".$section_id."_error"; ?>" class="error_msg"></span>
					</div>
				</div>
				<div class="Bid_Amount inBid_autoHeight fontSize0 inBid_autoWidth_four">
					<b class="default_black_bold"><?php echo $bussiness_service_fee_input_label; ?></b><i class="fa fa-question-circle default_icon_help tooltipAuto prgt_bussiness_fee_label" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $bussiness_service_fee_tooltip_msg; ?>"></i><span class="touch_line_break"><input name="service_fee" readonly type="text" class="form-control avoid_space login_register_input_field service_fee" style="text-align:right;"><b class="default_black_bold"><?php echo CURRENCY; ?></b></span>
				</div>
				<div class="Bid_Amount inBid_autoHeight fontSize0 inBid_autoWidth_five">
					<b class="default_black_bold"><?php echo $total_input_label; ?></b><span class="touch_line_break inprogress_amountOnly"><input readonly type="text" class="form-control avoid_space login_register_input_field service_fee total_amount" name="total_amount" style="text-align:right;"><b class="default_black_bold"><?php echo CURRENCY; ?></b></span>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="crEscrow_btn POcrEscrow_btn">
			<div>
				<button type="button" class="btn red_btn default_btn cancel_escrow_button default_btn_padding" id="<?php echo "cancel_escrow_button_".$section_id; ?>" data-project-id = "<?php echo $project_id ?>" data-sp-id = "<?php echo $sp_id ?>" data-po-id = "<?php echo $po_id ?>" data-section-name="<?php echo $section_name; ?>" data-section-id="<?php echo $section_id; ?>"><?php echo $cancel_escrow_payment_button; ?></button>
				<button type="button" class="btn green_btn default_btn save_escrow_button default_btn_padding" data-section-name="<?php echo $section_name; ?>" data-section-id="<?php echo $section_id; ?>"><?php echo $escrow_payment_button; ?></button>
			</div>
		</div>
		</form>
	</div>
</div>