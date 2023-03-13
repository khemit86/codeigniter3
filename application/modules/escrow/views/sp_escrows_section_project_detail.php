<?php
if($project_type == 'fulltime'){ 
	$requested_escrow_count_project = get_requested_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id));// count the requested escrow

	$active_escrow_count_project = get_active_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id)); // count the active escrow

	$released_escrow_count_project = get_released_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id)); // count the active escrow
	
	$cancelled_escrow_count_project = get_cancelled_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id)); // count the cancelled escrow
	
	

	$rejected_requested_escrow_count_project = get_rejected_requested_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id));// count the rejected requested escrow

	$sum_requested_escrow_amount_project = get_sum_requested_escrows_amount_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id));
	
	//get the active dispute reference id
	//$dispute_ref_id = '';

} else {
	$requested_escrow_count_project = get_requested_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));// count the requested escrow

	$active_escrow_count_project = get_active_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id)); // count the active escrow
	
	$cancelled_escrow_count_project = get_cancelled_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id)); // count the cancelled escrow
	
	

	$released_escrow_count_project = get_released_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id)); // count the active escrow

	$rejected_requested_escrow_count_project = get_rejected_requested_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));// count the rejected requested escrow

	$sum_requested_escrow_amount_project = get_sum_requested_escrows_amount_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
	
	//get the active dispute reference id
	//$dispute_ref_id = get_sp_project_disputed_reference_id($project_type,array('disputed_project_id'=>$project_id,'project_owner_id_of_disputed_project'=>$po_id,'sp_winner_id_of_disputed_project'=>$sp_id));
}

$request_payment_button = $this->config->item('project_details_page_project_create_escrow_request_form_request_payment_button_txt_sp_view');
$create_request_payment_button = $this->config->item('project_details_page_project_create_escrow_request_form_create_escrow_request_button_txt_sp_view');
$cancel_request_payment_button = $this->config->item('cancel_btn_txt');
$request_form_heading = $this->config->item('project_details_page_project_create_escrow_request_form_heading_sp_view');
//$no_incoming_milestone_msg = $this->config->item('project_details_page_project_no_incoming_milestone_msg_sp_view');
$description_input_label = $this->config->item('project_details_page_project_description_create_escrow_request_form_sp_view');
$amount_input_label = $this->config->item('project_details_page_project_amount_create_escrow_request_form_sp_view');

if($project_type == 'fixed'){
	$description_tooltip_msg = $this->config->item('project_details_page_fixed_budget_project_create_escrow_request_form_tooltip_message_description_sp_view');	
	$amount_tooltip_msg = $this->config->item('project_details_page_fixed_budget_project_create_escrow_request_form_tooltip_message_amount_sp_view');
} else if($project_type == 'hourly'){
	$description_tooltip_msg = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_request_form_tooltip_message_description_sp_view');
	$amount_tooltip_msg = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_payment_request_form_tooltip_message_amount_sp_view');	
	$hourly_rate_input_label = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_request_form_hourly_rate_sp_view');
	$hourly_rate_tooltip_msg = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_request_form_tooltip_message_hourly_rate_sp_view');
	$number_of_hours_input_label = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_request_form_number_of_hours_sp_view');
	$number_of_hours_tooltip_msg = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_request_form_tooltip_message_number_of_hours_sp_view');
} else if($project_type == 'fulltime'){
	$description_tooltip_msg = $this->config->item('project_details_page_fulltime_project_create_escrow_request_form_tooltip_message_description_employee_view');	
	$amount_tooltip_msg = $this->config->item('project_details_page_fulltime_project_create_escrow_request_form_tooltip_message_amount_employee_view');
	$request_payment_button = $this->config->item('project_details_page_fulltime_project_create_escrow_request_form_request_payment_button_txt_employee_view');
	$cancel_request_payment_button = $this->config->item('project_details_page_fulltime_project_create_escrow_request_form_cancel_button_txt_employee_view');
	$request_form_heading = $this->config->item('project_details_page_fulltime_project_create_escrow_request_form_heading_employee_view');
	$create_request_payment_button = $this->config->item('project_details_page_fulltime_project_create_escrow_request_form_create_escrow_request_button_txt_employee_view');
	$description_input_label = $this->config->item('project_details_page_fulltime_project_description_create_escrow_request_form_employee_view');
	$amount_input_label = $this->config->item('project_details_page_fulltime_project_amount_create_escrow_request_form_employee_view');
}

// config for payment tab
$requested_payment_tab = $this->config->item('project_details_page_payment_management_section_sent_payment_requests_tab_sp_view');
$requested_payment_tab_tooltip_msg = $this->config->item('project_details_page_payment_management_section_sent_payment_requests_tab_tooltip_message_sp_view');
$requested_payment_tab_tooltip_msg = str_replace(array('{user_first_name_last_name_or_company_name}'),array($project_owner_name),$requested_payment_tab_tooltip_msg);


$incoming_escrowed_payments_tab = $this->config->item('project_details_page_payment_management_section_incoming_escrowed_payments_tab_sp_view');
$incoming_escrowed_payments_tab_tooltip_msg = $this->config->item('project_details_page_payment_management_section_incoming_escrowed_payments_tab_tooltip_message_sp_view');
$incoming_escrowed_payments_tab_tooltip_msg = str_replace(array('{user_first_name_last_name_or_company_name}'),array($project_owner_name),$incoming_escrowed_payments_tab_tooltip_msg);

$cancelled_escrowed_payments_tab = $this->config->item('project_details_page_payment_management_section_cancelled_escrowed_payments_tab');
$cancelled_escrowed_payments_tab_tooltip_msg = $this->config->item('project_details_page_payment_management_section_cancelled_escrowed_payments_tab_tooltip_message_po_view');

$released_payments_tab = $this->config->item('project_details_page_payment_management_section_received_payments_tab_sp_view');
$released_payments_tab_tooltip_msg = $this->config->item('project_details_page_payment_management_section_received_payments_tab_tooltip_message_sp_view');
$released_payments_tab_tooltip_msg = str_replace(array('{user_first_name_last_name_or_company_name}'),array($project_owner_name),$released_payments_tab_tooltip_msg);


$rejected_payment_requests_tab = $this->config->item('project_details_page_payment_management_section_rejected_payment_requests_tab_sp_view');
$rejected_payment_requests_tab_tooltip_msg = $this->config->item('project_details_page_payment_management_section_rejected_payment_requests_tab_tooltip_message_sp_view');
$rejected_payment_requests_tab_tooltip_msg = str_replace(array('{user_first_name_last_name_or_company_name}'),array($project_owner_name),$rejected_payment_requests_tab_tooltip_msg);

$initial_view_create_escrow_request_button_css = 'display:none;';
$create_escrow_request_button_css = 'display:block;';

if($requested_escrow_count_project == 0 && $active_escrow_count_project == 0 && $released_escrow_count_project == 0 && $rejected_requested_escrow_count_project == 0 && $cancelled_escrow_count_project==0 && $section_name != 'active_dispute'){
	$initial_view_create_escrow_request_button_css = 'display:block;';
	$create_escrow_request_button_css = 'display:none;';
}	

?>
<div style="<?php echo $initial_view_create_escrow_request_button_css; ?>" id="initial_view_create_escrow_request">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-12">
			<?php
			/* <div style="text-align:center;border-bottom:none;" class="default_black_bold_bigger milestone_escrow"><?php echo str_replace(array('{user_first_name_last_name_or_company_name}'),array($project_owner_name),$no_incoming_milestone_msg); ?></div> */
			?>
			<div class="clearfix"></div>
			<div class="crEscrow_btn" style="text-align:center;border-top:none;">
				<div>
					<button type="button" class="btn blue_btn default_btn create_escrow_button" id="intial_view_create_escrow_request_button" data-section-id="<?php echo $winner_id; ?>" data-section-name="<?php echo $section_name; ?>" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>" ><?php echo $create_request_payment_button; ?></button>
				</div>
			</div>
		</div>
	</div>
</div>



<div style="display:none;" id="create_escrow_request_form">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-12">
			<form id="create_escrow_request" name="create_escrow_request">
			<input type="hidden" value="<?php echo $project_id; ?>" name="project_id">	
			<input type="hidden" value="<?php echo $section_name; ?>" name="section_name">	
			<input type="hidden" value="<?php echo Cryptor::doEncrypt($po_id); ?>" name="po_id">	
			<input type="hidden" value="<?php echo Cryptor::doEncrypt($sp_id); ?>" name="sp_id">	
			<?php 
				if($project_type == 'hourly') {
					if($section_name == 'inprogress') {
						$agreed_hourly_rate = $inprogress_bidder_data['initial_project_agreed_hourly_rate'];
					} else {
						$agreed_hourly_rate = $completed_bidder_data['initial_project_agreed_hourly_rate'];
					}
			?>
			<input type="hidden" value="<?php echo ($section_name != 'inprogress') ? 0 : $agreed_hourly_rate; ?>" name="sp_hourly_rate">	
			<?php
				}
			?>
			<div class="default_black_bold_bigger escrow_owner"><?php echo str_replace(array('{user_first_name_last_name_or_company_name}'),array($project_owner_name),$request_form_heading); ?></div> 
			<div class="bidDays">
				<div class="form-group formGroupOnly">
					<div class="Bid_Amount inBid_Description">
						<b class="default_black_bold"><?php echo $description_input_label;?></b><i class="fa fa-question-circle default_icon_help tooltipAuto" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $description_tooltip_msg; ?>"></i>
						<input type="text" class="form-control avoid_space login_register_input_field" style="text-align:left; width: 100%;" id="escrow_description" name="escrow_description" maxlength="<?php echo $this->config->item('escrow_description_maximum_length_character_limit_escrow_form')?>">
						<div class="error_div_sectn clearfix default_error_div_sectn">
							<span class="content-count escrow_description_length_count_message"><?php echo $this->config->item('escrow_description_maximum_length_character_limit_escrow_form')."&nbsp;".$this->config->item('characters_remaining_message'); ?></span>
							<span class="error_msg" id="escrow_description_error"></span>
						</div>
					</div>		
					<?php
						if($project_type == 'hourly') {
					?>	
					<div class="Bid_Amount inBid_autoHeight fontSize0 inBidSp_autoWidth_one">
						<b class="default_black_bold"><?php echo $hourly_rate_input_label;?></b><i class="fa fa-question-circle default_icon_help tooltipAuto" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $hourly_rate_tooltip_msg; ?>"></i>
						<input type="hidden" id="sp_escrow_request_hourly_rate" value="<?php echo ($section_name == 'inprogress' && $agreed_hourly_rate != 0) ? str_replace(".00","",number_format($agreed_hourly_rate,  2, '.', ' ')) : ''; ?>"/>
						<span class="touch_line_break"><input type="text" class="form-control avoid_space login_register_input_field escrow_request_hourly_rate" id="escrow_request_hourly_rate" name="escrow_request_hourly_rate" style="text-align:right;" maxlength="<?php echo $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_request_form_hourly_rate_length_digits_limit');?>" <?php echo ($section_name == 'inprogress' && $agreed_hourly_rate != 0) ? 'readonly' : '' ?> value="<?php echo ($section_name == 'inprogress' && $agreed_hourly_rate != 0) ? str_replace(".00","",number_format($agreed_hourly_rate,  2, '.', ' ')) : ''; ?>"><b class="default_black_bold"><?php echo CURRENCY; ?></b></span>
						<div class="error_div_sectn clearfix">
							<span id="escrow_request_hourly_rate_error" class="error_msg"></span>
						</div>
					</div>
					<div class="Bid_Amount inBid_autoHeight fontSize0 inBidSp_autoWidth_two">
						<b class="default_black_bold"><?php echo $number_of_hours_input_label;?></b><i class="fa fa-question-circle default_icon_help tooltipAuto" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $number_of_hours_tooltip_msg; ?>"></i>
						<span class="touch_line_break"><input type="text" class="form-control avoid_space login_register_input_field escrow_request_no_of_hours" id="escrow_request_no_of_hours" name="escrow_request_no_of_hours" style="text-align:right;" maxlength="<?php echo $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_request_form_number_of_hours_length_digits_limit');?>"></span>
						<div class="error_div_sectn clearfix">
							<span id="escrow_request_no_of_hours_error" class="error_msg"></span>
						</div>
					</div>		
					<?php 
						}
					?>
					<div class="Bid_Amount inBid_autoHeight fontSize0 inBidSp_autoWidth_three">
						<b class="default_black_bold"><?php echo $amount_input_label;?></b><i class="fa fa-question-circle default_icon_help tooltipAuto" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $amount_tooltip_msg; ?>"></i>
						<span class="touch_line_break"><input type="text" class="form-control avoid_space login_register_input_field escrow_request_amount" id="escrow_request_amount" name="escrow_request_amount" style="text-align:right;"  <?php echo ($project_type == 'hourly') ? 'readonly' : ''; ?> maxlength="<?php echo $this->config->item('escrow_amount_length_character_limit_escrow_form');?>"><b class="default_black_bold"><?php echo CURRENCY; ?></b></span>
						<div class="error_div_sectn clearfix">
							<span id="escrow_request_amount_error" class="error_msg"></span>
						</div>
					</div>
					
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="crEscrow_btn POcrEscrow_btn">				
				<button type="button" class="btn red_btn default_btn cancelEscrowBtn" data-sp-id = "<?php echo Cryptor::doEncrypt($sp_id); ?>" data-po-id = "<?php echo Cryptor::doEncrypt($po_id); ?>" data-section-name = "<?php echo $section_name ?>"  data-project-id = "<?php echo $project_id ?>" data-section-id = "<?php echo $winner_id ?>" id="cancel_escrow_request_button"><?php echo $cancel_request_payment_button; ?></button>
				<button type="button" class="btn green_btn default_btn" data-sp-id = "<?php echo Cryptor::doEncrypt($sp_id); ?>" data-po-id = "<?php echo Cryptor::doEncrypt($po_id); ?>" data-section-name = "<?php echo $section_name ?>"  data-project-id = "<?php echo $project_id ?>" data-section-id = "<?php echo $winner_id ?>"id="save_escrow_request_button"><?php echo $request_payment_button; ?></button>
			</div>
			</form>
		</div>
	</div>
</div>
<?php
$show_escrow_table_data_css_sp = "display:block";
if($requested_escrow_count_project == 0 && $active_escrow_count_project == 0 && $released_escrow_count_project == 0 && $rejected_requested_escrow_count_project == 0 && $cancelled_escrow_count_project==0 && $section_name != 'active_dispute'){
	$show_escrow_table_data_css_sp = "display:none";	
}	
?>
<div class="escrowPymnt" id="<?php echo 'escrowPymnt'.$winner_id ?>" style="<?php echo $show_escrow_table_data_css_sp; ?>">
	<div class="inprogressTab escrow_data_section_sp" style="<?php echo $show_escrow_table_data_css_sp; ?>">	
		<div class="default_radio_button radio_bttmBdr radio_left_side payDTls SPmanyRadioBtn">
			<section>
				<div class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $requested_payment_tab_tooltip_msg; ?>">
					<input class="escrow_tab"  type="radio" id="<?php echo $section_name."RqstPym".$winner_id."input"; ?>" name="inProgress_payments" value="incoming_payment_requests" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $winner_id; ?>" data-tab-type = "requested_escrow" data-section-name="<?php echo $section_name; ?>" data-target="<?php echo "#".$section_name."RqstPym".$winner_id ?>">
					<label class="doubleLine_radioBtn" for="<?php echo $section_name."RqstPym".$winner_id."input"; ?>">
						<span><?php echo $requested_payment_tab; ?></span>
					</label>
				</div>
				<div class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $incoming_escrowed_payments_tab_tooltip_msg; ?>" >
					<input type="radio" class="escrow_tab" id="<?php echo $section_name."EscPym".$winner_id."input"; ?>" name="inProgress_payments" value="outgoing_escrowed_payments" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $winner_id; ?>" data-tab-type = "active_escrow" data-section-name="<?php echo $section_name; ?>"  data-target="<?php echo "#".$section_name."EscPym".$winner_id ?>">
					<label class="doubleLine_radioBtn" for="<?php echo $section_name."EscPym".$winner_id."input"; ?>">
						<span><?php echo $incoming_escrowed_payments_tab; ?></span>
					</label>
				</div>
				<div class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $cancelled_escrowed_payments_tab_tooltip_msg; ?>">
					<input type="radio" class="escrow_tab" id="<?php echo $section_name."CancelEscPym".$winner_id."input"; ?>" name="inProgress_payments" value="cancelled_escrow_payments" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $winner_id; ?>" data-tab-type = "cancelled_escrow" data-section-name="<?php echo $section_name; ?>"  data-target="<?php echo "#".$section_name."CancelEscPym".$winner_id ?>">
					<label class="doubleLine_radioBtn" for="<?php echo $section_name."CancelEscPym".$winner_id."input"; ?>">
						<span><?php echo $cancelled_escrowed_payments_tab; ?></span>
					</label>
				</div>
				<div class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $released_payments_tab_tooltip_msg; ?>">
					<input type="radio" class="escrow_tab" id="<?php echo $section_name."PaidPym".$winner_id."input"; ?>" name="inProgress_payments" value="released_payments" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $winner_id; ?>" data-tab-type = "released_escrow" data-section-name="<?php echo $section_name; ?>"  data-target="<?php echo "#".$section_name."PaidPym".$winner_id ?>">
					<label class="doubleLine_radioBtn" for="<?php echo $section_name."PaidPym".$winner_id."input"; ?>">
						<span><?php echo $released_payments_tab; ?></span>
					</label>
				</div>
				<div class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $rejected_payment_requests_tab_tooltip_msg; ?>" >
					<input type="radio" class="escrow_tab" id="<?php echo $section_name."RejRqstPym".$winner_id."input"; ?>" name="inProgress_payments" value="rejected_payment_requests" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $winner_id; ?>" data-tab-type = "rejected_requested_escrow" data-section-name="<?php echo $section_name; ?>"  data-target="<?php echo "#".$section_name."RejRqstPym".$winner_id ?>">
					<label class="doubleLine_radioBtn" for="<?php echo $section_name."RejRqstPym".$winner_id."input";?>">
						<span><?php echo $rejected_payment_requests_tab; ?></span>
					</label>
				</div>
			</section>
		</div>
		<?php
			/* if($project_type == 'fulltime'){ 
				$requested_escrows_listing_project_data = get_all_requested_escrows_listing_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id),0,$this->config->item('project_detail_page_requested_escrow_listing_limit')); // fetch the requested escrow created by PO
			} else {
				$requested_escrows_listing_project_data = get_all_requested_escrows_listing_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id),0,$this->config->item('project_detail_page_requested_escrow_listing_limit')); // fetch the requested escrow created by PO
			}
			
			$requested_escrows_data = $requested_escrows_listing_project_data['data'];
			$requested_escrows_count = $requested_escrows_listing_project_data['total'];
			$generate_pagination_links_escrow = generate_pagination_links_escrow($requested_escrow_count, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),array('project_id'=>$project_id,'po_id'=>$po_id,'sp_id'=>$sp_id,'project_type'=>$project_type,'winner_id'=>$winner_id,'tab_type'=>'requested_escrow','section_name'=>$section_name)); */
		?>
		<div class="radio_right_side">
			<div class="inpaymentsTab <?php echo $section_name.$winner_id ?>" id="<?php echo $section_name."RqstPym".$winner_id ?>" style="display: none;">
				<?php
				//echo $this->load->view('escrow/requested_escrows_section_project_detail',array('requested_escrows_data'=>$requested_escrows_data,'requested_escrows_count'=>$requested_escrows_count,'generate_pagination_links_escrow'=>$generate_pagination_links_escrow,'create_request_payment_button'=>$create_request_payment_button,'project_type'=>$project_type,'winner_id'=>$winner_id,'sum_escrow_amount'=>$sum_requested_escrow_amount_project), true);
				?>
			</div>
			<div class="inpaymentsTab <?php echo $section_name.$winner_id ?>" id="<?php echo $section_name."EscPym".$winner_id ?>" style="display: none;">
			</div>
			<div class="inpaymentsTab <?php echo $section_name.$winner_id ?>" id="<?php echo $section_name."CancelEscPym".$winner_id ?>" style="display: none;">
			</div>
			<div class="inpaymentsTab <?php echo $section_name.$winner_id ?>" id="<?php echo $section_name."PaidPym".$winner_id ?>" style="display: none;">
			</div>
			<div class="inpaymentsTab <?php echo $section_name.$winner_id ?>" id="<?php echo $section_name."RejRqstPym".$winner_id ?>" style="display: none;">
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>	
<?php
$user = $this->session->userdata ('user');
if($this->session->userdata ('user') && $user[0]->user_id == $sp_id && $section_name != 'active_dispute'){
?>
<div class="create_escrow_payments_btn" style="<?php echo $create_escrow_request_button_css; ?>">
	<button type="button" class="btn blue_btn default_btn create_escrow_button" id="create_escrow_request_button" style="<?php echo $create_escrow_request_button_css; ?>"><?php echo $create_request_payment_button; ?></button>
</div>
<?php
}	
?>	

