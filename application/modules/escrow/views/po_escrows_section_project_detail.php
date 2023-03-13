<?php

if($project_type == 'fulltime') { 
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

	$released_escrow_count_project = get_released_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id)); // count the released escrow
	
	$cancelled_escrow_count_project = get_cancelled_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id)); // count the cancelled escrow
	
	
	

	$rejected_requested_escrow_count_project = get_rejected_requested_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));// count the rejected requested escrow

	$sum_requested_escrow_amount_project = get_sum_requested_escrows_amount_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
	
	//get the active dispute reference id
	//$dispute_ref_id = get_sp_project_disputed_reference_id($project_type,array('disputed_project_id'=>$project_id,'project_owner_id_of_disputed_project'=>$po_id,'sp_winner_id_of_disputed_project'=>$sp_id));
}



//$no_outgoing_milestone_msg = $this->config->item('project_details_page_project_no_outgoing_milestone_msg_po_view');
$create_escrow_payment_button = $this->config->item('project_details_page_project_create_escrow_form_create_escrow_payment_button_txt_po_view');
if($project_type == 'fulltime'){

	$create_escrow_payment_button = $this->config->item('project_details_page_fulltime_project_create_milestone_form_create_milestone_payment_button_txt_employer_view');
	//$no_outgoing_milestone_msg = $this->config->item('project_details_page_fulltime_project_no_outgoing_milestone_msg_po_view');
}

// config for payment tab
$requested_payment_tab = $this->config->item('project_details_page_payment_management_section_incoming_payment_requests_tab_po_view');
$requested_payment_tab_tooltip_msg = $this->config->item('project_details_page_payment_management_section_incoming_payment_requests_tab_tooltip_message_po_view');
$requested_payment_tab_tooltip_msg = str_replace(array('{user_first_name_last_name_or_company_name}'),array($service_provider_name),$requested_payment_tab_tooltip_msg);


$outgoing_escrowed_payments_tab = $this->config->item('project_details_page_payment_management_section_outgoing_escrowed_payments_tab_po_view');
$outgoing_escrowed_payments_tab_tooltip_msg = $this->config->item('project_details_page_payment_management_section_outgoing_escrowed_payments_tab_tooltip_message_po_view');
$outgoing_escrowed_payments_tab_tooltip_msg = str_replace(array('{user_first_name_last_name_or_company_name}'),array($service_provider_name),$outgoing_escrowed_payments_tab_tooltip_msg);


$cancelled_escrowed_payments_tab = $this->config->item('project_details_page_payment_management_section_cancelled_escrowed_payments_tab');
$cancelled_escrowed_payments_tab_tooltip_msg = $this->config->item('project_details_page_payment_management_section_cancelled_escrowed_payments_tab_tooltip_message_po_view');


$released_payments_tab = $this->config->item('project_details_page_payment_management_section_released_payments_tab_po_view');
$released_payments_tab_tooltip_msg = $this->config->item('project_details_page_payment_management_section_released_payments_tab_tooltip_message_po_view');
$released_payments_tab_tooltip_msg = str_replace(array('{user_first_name_last_name_or_company_name}'),array($service_provider_name),$released_payments_tab_tooltip_msg);


$rejected_payment_requests_tab = $this->config->item('project_details_page_payment_management_section_rejected_payment_requests_tab_po_view');
$rejected_payment_requests_tab_tooltip_msg = $this->config->item('project_details_page_payment_management_section_rejected_payment_requests_tab_tooltip_message_po_view');
$rejected_payment_requests_tab_tooltip_msg = str_replace(array('{user_first_name_last_name_or_company_name}'),array($service_provider_name),$rejected_payment_requests_tab_tooltip_msg);



$initial_view_create_escrow_button_css = "display:none";
$create_escrow_button_css = 'display:block;';
if($requested_escrow_count_project == 0 && $active_escrow_count_project == 0 && $released_escrow_count_project == 0 && $rejected_requested_escrow_count_project == 0 && $cancelled_escrow_count_project== 0 && $section_name != 'active_dispute'){
	$initial_view_create_escrow_button_css = "display:block;";
	$create_escrow_button_css = 'display:none;';
}	
?>
<div style="<?php echo $initial_view_create_escrow_button_css; ?>" class="<?php echo "initial_view_create_escrow_payment_".$winner_id." cmRBtn"; ?>">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-12">
			<?php
			/* <div style="text-align:center;border-bottom:none;" class="default_black_bold_bigger escrow_owner"><?php echo str_replace(array('{user_first_name_last_name_or_company_name}'),array($service_provider_name),$no_outgoing_milestone_msg); ?></div> */ ?>
			<div class="clearfix"></div>
			<div class="crEscrow_btn" style="text-align:center;border-top:none;">
				<div>
					<button type="button" class="btn blue_btn default_btn create_escrow_button create_escrow_button_po" id="<?php echo "create_escrow_button_initial_".$winner_id; ?>" data-section-name="<?php echo $section_name ?>"  data-section-id="<?php echo $winner_id; ?>"  data-project-id="<?php echo $project_id; ?>" data-sp-id = "<?php echo Cryptor::doEncrypt($sp_id); ?>" data-po-id = "<?php echo Cryptor::doEncrypt($po_id); ?>"> <?php echo $create_escrow_payment_button; ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
<div style="display:none;"  class="<?php echo "create_escrow_form_".$winner_id." cmR"; ?>">
</div>	
<?php
/* <div   style="<?php echo $initial_view_create_milestone_button_css; ?>" class="<?php echo "initial_view_create_milestone_payment_".$winner_id." cmRBtn"; ?>">
	<h3><?php echo str_replace(array('{user_first_name_last_name_or_company_name}'),array($service_provider_name),$no_outgoing_milestone_msg); ?></h3>				
	<button type="button" class="btn btn-primary create_milestone_button" id="<?php echo "create_milestone_button_initial_".$winner_id; ?>" data-section-name="<?php echo $section_name ?>"  data-section-id="<?php echo $winner_id; ?>"  data-project-id="<?php echo $project_id; ?>"><?php echo $create_milestone_payment_button; ?></button>
</div>
	
<div  style="display:none;"  class="<?php echo "create_milestone_form_".$winner_id." cmR"; ?>">
	
</div> */
?>
<?php
$show_escrow_table_data_css_po = "display:block";
if($requested_escrow_count_project == 0 && $active_escrow_count_project == 0 && $released_escrow_count_project == 0 && $rejected_requested_escrow_count_project == 0 && $cancelled_escrow_count_project== 0 && $section_name != 'active_dispute'){
	$show_escrow_table_data_css_po = "display:none";	
}	
?>
<div class="escrowPymnt" id="<?php echo 'escrowPymnt'.$winner_id ?>" style="<?php echo $show_escrow_table_data_css_po; ?>">
	<div style="<?php echo $show_escrow_table_data_css_po; ?>" class="<?php echo "default_radio_button radio_bttmBdr radio_left_side payDTls POmanyRadioBtn escrow_data_section_po_".$winner_id; ?>">
		<section>
			<div class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $requested_payment_tab_tooltip_msg; ?>" >
				<input type="radio" class="escrow_tab" id="<?php echo $section_name."RqstPym".$winner_id."input"; ?>" name="inProgress_payments<?php echo $winner_id?>" value="incoming_payment_requests" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $winner_id; ?>" data-tab-type = "requested_escrow" data-section-name="<?php echo $section_name; ?>" data-target="<?php echo "#".$section_name."RqstPym".$winner_id ?>">
				<label class="doubleLine_radioBtn" for="<?php echo $section_name."RqstPym".$winner_id."input"; ?>">
					<span><?php echo $requested_payment_tab; ?></span>
				</label>
			</div>
			<div class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $outgoing_escrowed_payments_tab_tooltip_msg; ?>"   >
				<input type="radio" class="escrow_tab" id="<?php echo $section_name."EscPym".$winner_id."input"; ?>" name="inProgress_payments<?php echo $winner_id?>" value="outgoing_escrowed_payments" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $winner_id; ?>" data-tab-type = "active_escrow" data-section-name="<?php echo $section_name; ?>"  data-target="<?php echo "#".$section_name."EscPym".$winner_id ?>">
				<label class="doubleLine_radioBtn" for="<?php echo $section_name."EscPym".$winner_id."input"; ?>">
					<span><?php echo $outgoing_escrowed_payments_tab; ?></span>
				</label>
			</div>
			<div class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $cancelled_escrowed_payments_tab_tooltip_msg; ?>" >
				<input type="radio" class="escrow_tab" id="<?php echo $section_name."CancelEscPym".$winner_id."input"; ?>" name="inProgress_payments<?php echo $winner_id?>" value="cancelled_escrow_payments"  data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $winner_id; ?>" data-tab-type = "cancelled_escrow" data-section-name="<?php echo $section_name; ?>"  data-target="<?php echo "#".$section_name."CancelEscPym".$winner_id ?>">
				<label class="doubleLine_radioBtn" for="<?php echo $section_name."CancelEscPym".$winner_id."input"; ?>">
					<span><?php echo $cancelled_escrowed_payments_tab; ?></span>
				</label>
			</div>
			<div class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $released_payments_tab_tooltip_msg; ?>" >
				<input type="radio" class="escrow_tab" id="<?php echo $section_name."PaidPym".$winner_id."input"; ?>" name="inProgress_payments<?php echo $winner_id?>" value="released_payments" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $winner_id; ?>" data-tab-type = "released_escrow" data-section-name="<?php echo $section_name; ?>"  data-target="<?php echo "#".$section_name."PaidPym".$winner_id ?>">
				<label class="doubleLine_radioBtn" for="<?php echo $section_name."PaidPym".$winner_id."input"; ?>">
					<span><?php echo $released_payments_tab; ?></span>
				</label>
			</div>
			<div class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $rejected_payment_requests_tab_tooltip_msg; ?>"  >
				<input type="radio" class="escrow_tab" id="<?php echo $section_name."RejRqstPym".$winner_id."input"; ?>" name="inProgress_payments<?php echo $winner_id?>" value="rejected_payment_requests" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $winner_id; ?>" data-tab-type = "rejected_requested_escrow" data-section-name="<?php echo $section_name; ?>"  data-target="<?php echo "#".$section_name."RejRqstPym".$winner_id ?>">
				<label class="doubleLine_radioBtn" for="<?php echo $section_name."RejRqstPym".$winner_id."input"; ?>">
					<span><?php echo $rejected_payment_requests_tab; ?></span>
				</label>
			</div>
		</section>
	</div>
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
<?php
/* <div style="<?php echo $show_milestone_table_data_css_po; ?>" class="<?php echo "payDTls milestones_data_section_po_".$winner_id; ?>">
	<ul class="nav nav-tabs">
		<li class="nav-item" data-toggle="tooltip" data-placement="top" title="<?php echo $requested_payment_tab_tooltip_msg; ?>">
			<a class="nav-link active milestones_tab" data-toggle="tab" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $winner_id; ?>" data-tab-type = "requested_milestone" data-section-name="<?php echo $section_name; ?>" data-target="<?php echo "#".$section_name."RqstPym".$winner_id ?>"><?php echo $requested_payment_tab; ?></a>
		</li>
		<li class="nav-item" data-toggle="tooltip" data-placement="top"  title="<?php echo $outgoing_escrowed_payments_tab_tooltip_msg; ?>">
			<!--<a class="nav-link" data-toggle="tab" data-target="#escPay">Escrow Payments</a>-->
			<a class="nav-link milestones_tab active_milestones_tab" data-toggle="tab" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $winner_id; ?>" data-tab-type = "active_milestone" data-section-name="<?php echo $section_name; ?>"  data-target="<?php echo "#".$section_name."EscPym".$winner_id ?>"><?php echo $outgoing_escrowed_payments_tab; ?></a>
		</li>
		<li class="nav-item" data-toggle="tooltip" data-placement="top"  title="<?php echo $cancelled_escrowed_payments_tab_tooltip_msg; ?>">
			<!--<a class="nav-link" data-toggle="tab" data-target="#escPay">Escrow Payments</a>-->
			<a class="nav-link milestones_tab cancelled_milestones_tab" data-toggle="tab" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $winner_id; ?>" data-tab-type = "cancelled_milestone" data-section-name="<?php echo $section_name; ?>"  data-target="<?php echo "#".$section_name."CancelEscPym".$winner_id ?>"><?php echo $cancelled_escrowed_payments_tab; ?></a>
		</li>
		<li class="nav-item" data-toggle="tooltip" data-placement="top"  title="<?php echo $released_payments_tab_tooltip_msg; ?>">
			<a class="nav-link milestones_tab paid_milestones_tab" data-toggle="tab" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $winner_id; ?>" data-tab-type = "paid_milestone" data-section-name="<?php echo $section_name; ?>"  data-target="<?php echo "#".$section_name."PaidPym".$winner_id ?>"><?php echo $released_payments_tab; ?></a>
		</li>
		<li class="nav-item"  data-toggle="tooltip" data-placement="top"  title="<?php echo $rejected_payment_requests_tab_tooltip_msg; ?>">
			<a class="nav-link milestones_tab" data-toggle="tab" data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>"  data-section-id="<?php echo $winner_id; ?>" data-tab-type = "rejected_requested_milestone" data-section-name="<?php echo $section_name; ?>"  data-target="<?php echo "#".$section_name."RejRqstPym".$winner_id ?>"><?php echo $rejected_payment_requests_tab; ?></a>
		</li>
	</ul>
	<?php
	$requested_milestones_listing_project_data = get_requested_escrow_listing_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id),0,$this->config->item('project_detail_page_requested_escrow_listing_limit')); // fetch the requested milestone created by PO
	$requested_milestones_data = $requested_milestones_listing_project_data['data'];
	$requested_milestones_count = $requested_milestones_listing_project_data['total'];
	$generate_pagination_links_milestones = generate_pagination_links_milestones($requested_milestones_count, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),array('project_id'=>$project_id,'po_id'=>$po_id,'sp_id'=>$sp_id,'project_type'=>$project_type,'winner_id'=>$winner_id,'tab_type'=>'requested_milestone','section_name'=>$section_name));
	?>	
	<div class="tab-content">
		<div class="tab-pane container active" id="<?php echo $section_name."RqstPym".$winner_id ?>">
			<?php
			echo $this->load->view('milestones/requested_milestones_section_project_detail',array('requested_milestones_data'=>$requested_milestones_data,'requested_milestones_count'=>$requested_milestones_count,'generate_pagination_links_milestones'=>$generate_pagination_links_milestones,'create_request_payment_button'=>$create_request_payment_button,'project_type'=>$project_type,'winner_id'=>$winner_id,'sum_milestones_amount'=>$sum_requested_milestones_amount_project), true);
			?>
		</div>
	
		<div class="tab-pane container fade" id="<?php echo $section_name."EscPym".$winner_id ?>"></div>
		<div class="tab-pane container fade" id="<?php echo $section_name."CancelEscPym".$winner_id ?>"></div>
	
		<div class="tab-pane container fade" id="<?php echo $section_name."PaidPym".$winner_id ?>"></div>
	
		<div class="tab-pane container fade" id="<?php echo $section_name."RejRqstPym".$winner_id ?>"></div>
	</div>
</div> */
?>
<?php
$user = $this->session->userdata ('user');
if($this->session->userdata ('user') && $user[0]->user_id == $po_id && $section_name != 'active_dispute'){
?>
<div class="create_escrow_payments_btn" style="<?php echo $create_escrow_button_css; ?>">
	<button style="<?php echo $create_escrow_button_css; ?>"  type="button"  class="<?php echo "create_escrow_button_".$winner_id. " btn blue_btn default_btn create_escrow_button after_creation_escrow_button create_escrow_button_po"; ?>" data-section-name="<?php echo $section_name ?>"  data-section-id="<?php echo $winner_id; ?>"  data-project-id="<?php echo $project_id; ?>" data-sp-id = "<?php echo Cryptor::doEncrypt($sp_id); ?>" data-po-id = "<?php echo Cryptor::doEncrypt($po_id); ?>"><?php echo $create_escrow_payment_button; ?></button>
</div>
<?php
}	

?>	
