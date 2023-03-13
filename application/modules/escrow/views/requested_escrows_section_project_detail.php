<?php
$show_no_requested_escrow_message_css = "display:block;";
$user = $this->session->userdata ('user');

if($po_id == $user[0]->user_id) {
	if($project_type == 'fixed'){
		$total_text = $this->config->item('fixed_budget_project_details_page_payment_management_section_incoming_payment_requests_tab_total_txt_po_view');
	}
	if($project_type == 'hourly'){
		$total_text = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_incoming_payment_requests_tab_total_txt_po_view');
	}
	if($project_type == 'fulltime'){
		$total_text = $this->config->item('fulltime_project_details_page_payment_management_section_incoming_payment_requests_tab_total_txt_employer_view');
	}
} else {
	if($project_type == 'fixed'){
		$total_text = $this->config->item('fixed_budget_project_details_page_payment_management_section_sent_payment_requests_tab_total_txt_sp_view');
		
	}
	if($project_type == 'hourly'){
		$total_text = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_sent_payment_requests_tab_total_txt_sp_view');
	}
	if($project_type == 'fulltime'){
		$total_text = $this->config->item('fulltime_project_details_page_payment_management_section_sent_payment_requests_tab_total_txt_employee_view');
	}
}

if(!empty($requested_escrows_data)){
?>
<?php
	
	$show_no_requested_escrow_message_css = "display:none;";	
	foreach($requested_escrows_data as $requested_escrows_key=>$requested_escrows_value){
		echo $this->load->view('escrow/requested_escrow_row_detail_project_detail',array('requested_escrow_value'=>$requested_escrows_value,'project_type'=>$project_type,'section_id'=>$section_id,'section_name'=>$section_name), true);
	}
?>
<?php
}
$show_total_amount_css = 'display:none';
if(floatval($sum_escrow_amount) != 0){
$show_total_amount_css = 'display:block';
}
?>
<div class="projTitle text-right escrowTotal <?php echo "sum_escrow_amount_container_".$section_id." payAmount" ?>" style="<?php echo $show_total_amount_css; ?>">
	<div class="currencyDetails">
		<b class="default_black_bold totalCurrency"><span class="total_rightside_gap"><?php echo $total_text; ?></span><span class="touch_line_break <?php echo "sum_escrow_amount_".$section_id ?>"><?php echo str_replace(".00","",number_format($sum_escrow_amount,  2, '.', ' ')); ?> <?php echo CURRENCY; ?></span></b>
	</div>
</div>
<?php
echo $this->load->view('escrow/escrow_section_paging_project_detail',array('escrow_count'=>$requested_escrows_count,'generate_pagination_links_escrow'=>$generate_pagination_links_escrow,'escrow_paging_limit'=>$this->config->item('project_detail_page_requested_escrow_listing_limit')), true);
?>
<?php
/* <div  class="no_requested_milestone default_blank_message" style="<?php echo $show_no_requested_milestone_message_css; ?>"><?php echo $this->config->item('no_requested_payment_milestone_message'); ?></div> */
?>
<div class="no_records_found" style="<?php echo $show_no_requested_escrow_message_css; ?>">
	<div class="no_requested_escrow default_blank_message">
		<?php 
			if($po_id == $user_data['user_id']) {
				echo $this->config->item('no_incoming_payment_request_message_po_view');
			} else {
				echo $this->config->item('no_sent_payment_request_message_sp_view');
			}
		?></div>
</div>

<?php
/* <?php
$show_total_amount_css = 'display:none';
if(floatval($sum_milestones_amount) != 0){
$show_total_amount_css = 'display:block';
}	
?>	
<div class="<?php echo "sum_milestones_amount_container_".$bid_id." payAmount" ?>" style="<?php echo $show_total_amount_css; ?>">Total <span class="<?php echo "sum_milestones_amount_".$bid_id ?>"><?php echo str_replace(".00","",number_format($sum_milestones_amount,  2, '.', ' ')); ?></span> <?php echo CURRENCY; ?></div>

<div class="clearfix"></div>
<div class="paging_section">
<?php
echo $this->load->view('milestones/milestones_section_paging_project_detail',array('milestones_count'=>$requested_milestones_count,'generate_pagination_links_milestones'=>$generate_pagination_links_milestones,'milestone_paging_limit'=>$this->config->item('project_detail_page_requested_escrow_listing_limit')), true);
?>
</div> */
?>

