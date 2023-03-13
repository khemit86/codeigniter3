<?php
if(!empty($rejected_requested_escrow_data)){
$user = $this->session->userdata ('user');
if($user[0]->user_id  == $po_id){	
	if($project_type == 'fixed') {
		$requested_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_on_txt_po_view');
		$amount_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_amount_txt_po_view');
		$rejected_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_rejected_on_txt_po_view');
		$description_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_description_txt_po_view');
		$total_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_total_txt_po_view');
		
	}
	if($project_type == 'fulltime') {
		$requested_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_on_txt_employer_view');
		$amount_txt = $this->config->item('fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_amount_txt_employer_view');
		$rejected_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_rejected_on_txt_employer_view');
		$description_txt = $this->config->item('fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_description_txt_employer_view');
		$total_txt = $this->config->item('fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_total_txt_employer_view');
	}
	if($project_type == 'hourly') {
		$requested_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_on_txt_po_view');
		$amount_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_rejected_payment_requests_tab_amount_txt_po_view');
		$rejected_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_rejected_payment_requests_tab_rejected_on_txt_po_view');
		$description_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_rejected_payment_requests_tab_description_txt_po_view');
		$hourly_rate_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_hourly_rate_txt_po_view');
		$number_hours_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_number_of_hours_txt_po_view');
		$total_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_rejected_payment_requests_tab_total_txt_po_view');
	}
}
if($user[0]->user_id  == $sp_id){	
	if($project_type == 'fixed') {
		
		$requested_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_on_txt_sp_view');
		$amount_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_amount_txt_sp_view');
		$rejected_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_rejected_on_txt_sp_view');
		$description_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_description_txt_sp_view');
		$total_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_rejected_payment_requests_tab_total_txt_sp_view');
		
	}
	if($project_type == 'fulltime') {
		
		$requested_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_on_txt_employee_view');
		$amount_txt = $this->config->item('fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_amount_txt_employee_view');
		$rejected_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_rejected_on_txt_employee_view');
		$description_txt = $this->config->item('fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_description_txt_employee_view');
		$total_txt = $this->config->item('fulltime_project_details_page_payment_management_section_rejected_payment_requests_tab_total_txt_employee_view');
		
	}
	if($project_type == 'hourly') {
		
		$requested_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_on_txt_sp_view');
		$amount_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_rejected_payment_requests_tab_amount_txt_sp_view');
		$rejected_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_rejected_payment_requests_tab_rejected_on_txt_sp_view');
		$description_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_rejected_payment_requests_tab_description_txt_sp_view');
		$hourly_rate_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_hourly_rate_txt_sp_view');
		$number_hours_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_rejected_payment_requests_tab_requested_number_of_hours_txt_sp_view');
		$total_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_rejected_payment_requests_tab_total_txt_sp_view');
		
	}
}
	
?>
<?php
	foreach($rejected_requested_escrow_data as $rejected_requested_escrow_key=>$rejected_requested_escrow_value){
?>	
	<div class="projTitle">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12 mDetails">
				<div class="divRightAuto">
					<?php 
						if($project_type == 'hourly') {
					?>
					<label>
						<div>
							<b class="default_black_bold"><?php echo $hourly_rate_txt; ?></b><span class="touch_line_break default_black_regular"><?php echo str_replace(".00","",number_format($rejected_requested_escrow_value['sp_requested_escrow_creation_hourly_rate'],  2, '.', ' '))." ".CURRENCY.$this->config->item('project_details_page_hourly_rate_based_project_per_hour'); ?></span>
						</div>
					</label>
					<label>
						<div>
							<b class="default_black_bold"><?php echo $number_hours_txt; ?></b><span class="touch_line_break default_black_regular"><?php echo str_replace(".00","",number_format($rejected_requested_escrow_value['sp_requested_escrow_creation_number_of_hours'],  2, '.', ' ')); ?></span>
						</div>
					</label>
					<?php 		
						}
					?>
					<label>
						<div>
							<b class="default_black_bold"><?php echo $amount_txt; ?></b><span class="touch_line_break default_black_regular"><?php echo str_replace(".00","",number_format($rejected_requested_escrow_value['requested_escrow_amount'],  2, '.', ' '))." ".CURRENCY; ?></span>
						</div>
					</label>
				</div>
				<div class="divRightAuto">
					<label>
						<div>
							<b class="default_black_bold"><?php echo $requested_on_txt; ?></b><span class="touch_line_break default_black_regular"><?php echo date(DATE_TIME_FORMAT,strtotime($rejected_requested_escrow_value['escrow_requested_by_sp_date'])); ?></span>
						</div>
					</label>
					<label>
						<div>
							<b class="default_black_bold"><?php echo $rejected_on_txt; ?></b><span class="touch_line_break default_black_regular"><?php echo date(DATE_TIME_FORMAT,strtotime($rejected_requested_escrow_value['requested_escrow_rejection_date'])); ?></span>
						</div>
					</label>
				</div>
				
				<?php if(!empty($rejected_requested_escrow_value['requested_escrow_description'])){ ?>
				<p class="default_black_regular aDispDesc"><b class="default_black_bold"><?php echo $description_txt; ?></b><?php echo htmlspecialchars($rejected_requested_escrow_value['requested_escrow_description'], ENT_QUOTES); ?></p>
				<?php } ?>
			</div>
			
		</div>
	</div>
	<?php
	/* <div class="desReq">
		<label>
			<b>Description :</b>
			<span><?php echo $rejected_requested_milestones_value['requested_milestone_description']; ?></span>
		</label>
		<label>
			<b class="space-left">Requested On :</b>
			<span><?php echo date(DATE_TIME_FORMAT,strtotime($rejected_requested_milestones_value['milestone_requested_by_sp_date'])); ?></span>
			<b>Rejected On :</b>
			<span><?php echo date(DATE_TIME_FORMAT,strtotime($rejected_requested_milestones_value['requested_milestone_rejection_date'])); ?></span>
			<b class="space-left">Amount :</b>
			<span><?php echo str_replace(".00","",number_format($rejected_requested_milestones_value['requested_milestone_amount_value'],  2, '.', ' '))." ".CURRENCY; ?></span>
		</label>
	</div> */
	?>
<?php
	}
?>
	<?php
	$show_total_amount_css = 'display:none';
	if(floatval($sum_escrow_amount) != 0){
	$show_total_amount_css = 'display:block';
	}	
	?>
	<div class="projTitle text-right escrowTotal <?php echo "sum_escrow_amount_container_".$section_id." payAmount" ?>" style="<?php echo $show_total_amount_css; ?>">
		<div class="currencyDetails">
			<b class="default_black_bold totalCurrency"><span class="total_rightside_gap"><?php echo $total_txt; ?></span><span class="touch_line_break <?php echo "sum_escrow_amount_".$section_id ?>"><?php echo str_replace(".00","",number_format($sum_escrow_amount,  2, '.', ' ')).' '.CURRENCY; ?></span></b>
		</div>
	</div>
	<?php
	echo $this->load->view('escrow/escrow_section_paging_project_detail',array('escrow_count'=>$rejected_requested_escrow_count,'generate_pagination_links_escrow'=>$generate_pagination_links_escrow,'escrow_paging_limit'=>$this->config->item('project_detail_page_rejected_requested_escrow_listing_limit')), true);
	?>
<?php
}

if(empty($rejected_requested_escrow_data)){	
?>
<?php
/* <div class="no_requested_milestone"><div class="default_blank_message"><?php echo $this->config->item('no_rejected_requested_payment_milestone_message');?></div> */
?>
	<div class="no_records_found">
		<div class="no_requested_escrow default_blank_message">
			<?php 
				if($po_id == $user_data['user_id']) {
					echo $this->config->item('no_rejected_requested_escrow_creation_message_po_view');
				} else {
					echo $this->config->item('no_rejected_requested_escrow_creation_message_sp_view');
				}
			?></div>
	</div>
<?php
}	
?>
<?php
/* <?php
$show_total_amount_css = 'display:none';
if(floatval($sum_milestones_amount) != 0){
$show_total_amount_css = 'display:block';
}	
?>	
<div  class="<?php echo "sum_milestones_amount_container_".$bid_id." payAmount" ?>" style="<?php echo $show_total_amount_css; ?>">Total <span class="<?php echo "sum_milestones_amount_".$bid_id ?>"><?php echo str_replace(".00","",number_format($sum_milestones_amount,  2, '.', ' ')); ?></span> <?php echo CURRENCY; ?></div>



<div class="clearfix"></div>
<div class="paging_section">
<?php
echo $this->load->view('milestones/milestones_section_paging_project_detail',array('milestones_count'=>$rejected_requested_milestones_count,'generate_pagination_links_milestones'=>$generate_pagination_links_milestones,'milestone_paging_limit'=>$this->config->item('project_detail_page_rejected_requested_escrow_listing_limit')), true);
?>
</div> */
?>