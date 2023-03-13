<?php
$user = $this->session->userdata ('user');
$show_no_active_escrow_message_css = "display:block;";
if($project_type == 'fulltime'){
	$conditions = array('disputed_fulltime_project_id'=>$project_id,'employer_id_of_disputed_fulltime_project'=>$po_id,'employee_winner_id_of_disputed_fulltime_project'=>$sp_id);
}else{
	$conditions = array('disputed_project_id'=>$project_id,'project_owner_id_of_disputed_project'=>$po_id,'sp_winner_id_of_disputed_project'=>$sp_id);
}
$dispute_ref_id = get_sp_project_disputed_reference_id($project_type,$conditions);

if($po_id == $user[0]->user_id) {
	if($project_type == 'fixed'){
		$total_text = $this->config->item('fixed_budget_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_total_txt_po_view');
	}
	if($project_type == 'hourly'){
		$total_text = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_total_txt_po_view');
	}
	if($project_type == 'fulltime'){
		$total_text = $this->config->item('fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_total_txt_employer_view');
	}
} else {
	if($project_type == 'fixed'){
		$total_text = $this->config->item('fixed_budget_project_details_page_payment_management_section_incoming_escrowed_payments_tab_total_txt_sp_view');
	}
	if($project_type == 'hourly'){
		$total_text = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_incoming_escrowed_payments_tab_total_txt_sp_view');
	}
	if($project_type == 'fulltime'){
		$total_text = $this->config->item('fulltime_project_details_page_payment_management_section_incoming_escrowed_payments_tab_total_txt_employee_view');
	}
}


if(!empty($active_escrow_data)){
?>
<?php
	$show_no_active_escrow_message_css = "display:none;";	
	foreach($active_escrow_data as $active_escrow_key => $active_escrow_value){
		echo $this->load->view('escrow/active_escrows_row_detail_project_detail',array('active_escrow_value'=>$active_escrow_value,'project_type'=>$project_type,'section_id'=>$section_id,'section_name'=>$section_name,'po_name'=>$po_name,'sp_name'=>$sp_name), true);
	}
	
?>
	
<?php
}
$show_total_amount_css = 'display:none';
if(floatval($sum_escrow_amount) != 0){
$show_total_amount_css = 'display:block';
}
?>
<div  class="<?php echo "sum_escrow_amount_container_".$section_id." payAmount" ?> projTitle text-right escrowTotal" style="<?php echo $show_total_amount_css; ?>">
	<div class="currencyDetails">
		<b class="default_black_bold totalCurrency"><span class="total_rightside_gap"><?php echo $total_text; ?></span><span class="touch_line_break <?php echo "sum_escrow_amount_".$section_id ?>"><?php echo str_replace(".00","",number_format($sum_escrow_amount,  2, '.', ' ')); ?> <?php echo CURRENCY; ?><small class="receive_notification expand_notification_area<?php echo $section_id?>"><a class="rcv_notfy_btn" onclick="showOpeDispute('<?php echo $section_id?>')"><span id="disputeShow">(<sup>+</sup>)</span></a></small></span></b>
		<input type="hidden" id="moreDispute<?php echo $section_id;?>" value="1">
		<div id="OpenDispute<?php echo $section_id;?>" class="open_dispute_btn" style="display:none"><button type="button" class="btn red_btn default_btn open_dispute" data-initiator-id = "<?php echo $user[0]->user_id ?>" data-po-id="<?php echo $po_id ?>" data-sp-id="<?php echo $sp_id; ?>"><?php echo $this->config->item("project_detail_page_payment_tab_open_dispute_txt"); ?></button></div>
	</div>
</div>

<?php
echo $this->load->view('escrow/escrow_section_paging_project_detail',array('escrow_count'=>$active_escrow_count,'generate_pagination_links_escrow'=>$generate_pagination_links_escrow,'escrow_paging_limit'=>$this->config->item('project_detail_page_active_escrow_listing_limit')), true);
?>	

	
<div class="no_records_found" style="<?php echo $show_no_active_escrow_message_css; ?>">
	<div class="<?php echo "no_active_escrow_".$section_id." default_blank_message no_requested_escrow" ?>">
		<?php 
			if(empty($dispute_ref_id)){
				if($po_id == $user_data['user_id']) {
					echo $this->config->item('no_outgoing_escrow_payment_message_po_view');
				} else {
					echo $this->config->item('no_incoming_escrow_payment_message_sp_view');
				}
			}else{
			?>
			<button type="button" class="btn red_btn default_btn noRequestedEscrowBtn" style="margin-top: 2px;" data-dispute-ref-id="<?php echo $dispute_ref_id; ?>" id="dispute_detail_page"><?php echo $this->config->item('project_dispute_details_page_go_to_dispute_detail_page_btn_txt') ?></button>
			<?php
			}
		?></div>
</div>
<?php
/* <div  class="<?php echo "no_active_escrow_".$bid_id." default_blank_message" ?>" style="<?php echo $show_no_active_escrow_message_css; ?>"><?php echo $this->config->item('no_active_payment_milestone_message'); ?></div>

<?php
$show_total_amount_css = 'display:none';
if(floatval($sum_milestones_amount) != 0){
$show_total_amount_css = 'display:block';
}	
?>	
<div  class="<?php echo "sum_milestones_amount_container_".$bid_id." payAmount" ?>" style="<?php echo $show_total_amount_css; ?>">Total <span class="<?php echo "sum_milestones_amount_".$bid_id ?>"><?php echo str_replace(".00","",number_format($sum_milestones_amount,  2, '.', ' ')); ?></span> <?php echo CURRENCY; ?></div>
<div class="clearfix"></div>
<div class="paging_section">
<?php
echo $this->load->view('milestones/milestones_section_paging_project_detail',array('milestones_count'=>$active_milestones_count,'generate_pagination_links_milestones'=>$generate_pagination_links_milestones,'milestone_paging_limit'=>$this->config->item('project_detail_page_active_escrow_listing_limit')), true);
?>
</div> */
?>