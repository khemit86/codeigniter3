<?php
$user = $this->session->userdata ('user');

if($user[0]->user_id  == $po_id){	

	if($project_type == 'fixed') {
		$amount_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_released_payments_tab_amount_txt_po_view');
		$business_service_fee_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_released_payments_tab_business_service_fee_txt_po_view');
		$paid_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_released_payments_tab_paid_on_txt_po_view');
		$description_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_released_payments_tab_description_txt_po_view');
		$dispute_id_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_released_payments_tab_dispute_id_txt_po_view');
		
		$total_paid_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_released_payments_tab_total_paid_txt_po_view');
		$total_business_charge_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_released_payments_tab_total_business_charges_txt_po_view');
	}
	if($project_type == 'fulltime') {
		$amount_txt = $this->config->item('fulltime_project_details_page_payment_management_section_released_payments_tab_amount_txt_employer_view');
		$business_service_fee_txt = $this->config->item('fulltime_project_details_page_payment_management_section_released_payments_tab_business_service_fee_txt_employer_view');
		$paid_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_released_payments_tab_paid_on_txt_employer_view');
		$description_txt = $this->config->item('fulltime_project_details_page_payment_management_section_released_payments_tab_description_txt_employer_view');
		$dispute_id_txt = $this->config->item('fulltime_project_details_page_payment_management_section_released_payments_tab_dispute_id_txt_employer_view');
		$total_paid_txt = $this->config->item('fulltime_project_details_page_payment_management_section_released_payments_tab_total_paid_txt_employer_view');
		$total_business_charge_txt = $this->config->item('fulltime_project_details_page_payment_management_section_released_payments_tab_total_business_charges_txt_employer_view');
	}
	if($project_type == 'hourly') {
		$amount_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_released_payments_tab_amount_txt_po_view');
		$business_service_fee_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_released_payments_tab_business_service_fee_txt_po_view');
		$paid_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_released_payments_tab_paid_on_txt_po_view');
		$description_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_released_payments_tab_description_txt_po_view');
		$dispute_id_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_released_payments_tab_dispute_id_txt_po_view');
		
		$hourly_rate_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_released_payments_tab_hourly_rate_txt_po_view');
		$number_hours_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_released_payments_tab_number_of_hours_txt_po_view');
		$total_paid_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_released_payments_tab_total_paid_txt_po_view');
		$total_business_charge_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_released_payments_tab_total_business_charges_txt_po_view');
	}
}
if($user[0]->user_id  == $sp_id){
	if($project_type == 'fixed') {
		$amount_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_received_payments_tab_amount_txt_sp_view');
		
		$paid_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_received_payments_tab_paid_on_txt_sp_view');
		$description_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_received_payments_tab_description_txt_sp_view');
		$dispute_id_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_received_payments_tab_dispute_id_txt_sp_view');
		$total_paid_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_received_payments_tab_total_received_txt_sp_view');
		
	}
	if($project_type == 'fulltime') {
		$amount_txt = $this->config->item('fulltime_project_details_page_payment_management_section_received_payments_tab_amount_txt_employee_view');
		
		$paid_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_received_payments_tab_paid_on_txt_employee_view');
		$description_txt = $this->config->item('fulltime_project_details_page_payment_management_section_received_payments_tab_description_txt_employee_view');
		$dispute_id_txt = $this->config->item('fulltime_project_details_page_payment_management_section_received_payments_tab_dispute_id_txt_employee_view');
		$total_paid_txt = $this->config->item('fulltime_project_details_page_payment_management_section_received_payments_tab_total_received_txt_employee_view');
		
	}
	if($project_type == 'hourly') {
		$amount_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_received_payments_tab_amount_txt_sp_view');
		
		$paid_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_received_payments_tab_paid_on_txt_sp_view');
		$description_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_received_payments_tab_description_txt_sp_view');
		$dispute_id_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_received_payments_tab_dispute_id_txt_sp_view');
		
		$hourly_rate_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_received_payments_tab_hourly_rate_txt_sp_view');
		$number_hours_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_received_payments_tab_number_of_hours_txt_sp_view');
		$total_paid_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_received_payments_tab_total_received_txt_sp_view');
	}
}	


if(!empty($released_escrow_data)){
?>
<?php
	foreach($released_escrow_data as $released_escrow_key => $released_escrow_value){
?>	
	<div class="projTitle">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12 mDetails">
				<div class="divRightAuto">
				<?php
				if($project_type == 'hourly') {
				?>
					<?php
					if(floatval($released_escrow_value['released_escrow_payment_hourly_rate']) != 0){
					?>
					<label>
						<div>
							<b class="default_black_bold"><?php echo $hourly_rate_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo str_replace(".00","",number_format($released_escrow_value['released_escrow_payment_hourly_rate'],  2, '.', ' '))." ".CURRENCY.$this->config->item('project_details_page_hourly_rate_based_project_per_hour'); ?></span>
						</div>
					</label>
					<?php
					}if(floatval($released_escrow_value['released_escrow_payment_number_of_hours']) != 0){
					?>
					<label>
						<div>
							<b class="default_black_bold"><?php echo $number_hours_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo str_replace(".00","",number_format($released_escrow_value['released_escrow_payment_number_of_hours'],  2, '.', ' ')); ?></span>
						</div>
					</label>
				<?php	
					}
				}
				?>
				<label>
					<div>
						<b class="default_black_bold"><?php echo $amount_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo str_replace(".00","",number_format($released_escrow_value['released_escrow_payment_amount'],  2, '.', ' '))." ".CURRENCY; ?></span>
					</div>
				</label>
				<?php
				if($this->session->userdata ('user') && $user[0]->user_id == $released_escrow_value['project_owner_id']){
				?>
				<label>
					<div>
						<b class="default_black_bold"><?php echo $business_service_fee_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo str_replace(".00","",number_format($released_escrow_value['service_fee_charges'],  2, '.', ' '))." ".CURRENCY; ?></span>
					</div>
				</label>
				
				<?php
				}
				?>
				<label>
					<div>
						<b class="default_black_bold"><?php echo $paid_on_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo date(DATE_TIME_FORMAT,strtotime($released_escrow_value['escrow_payment_release_date'])); ?></span>
					</div>
				</label>
				</div>
				<?php if(!empty($released_escrow_value['released_escrow_payment_description'])){ ?>
				<p class="default_black_regular aDispDesc"><b class="default_black_bold"><?php echo $description_txt; ?></b><?php echo htmlspecialchars($released_escrow_value['released_escrow_payment_description'], ENT_QUOTES); ?></p>
				<?php } ?>
				<?php if(isset($released_escrow_value['dispute_reference_id']) && !empty($released_escrow_value['dispute_reference_id'])){ ?>
				<p class="default_black_regular aDispDesc"><b class="default_black_bold"><?php echo $dispute_id_txt; ?></b><?php echo $released_escrow_value['dispute_reference_id']; ?></p>
				<?php } ?>
			</div>
		</div>
	</div>
	
	<?php
	/* <div class="desReq">
		<label>
			<b>Description :</b>
			<span><?php echo $paid_milestones_value['milestone_description']; ?></span>
		</label>
		<label>
			<b class="space-left">Paid On :</b>
			<span><?php echo date(DATE_TIME_FORMAT,strtotime($paid_milestones_value['release_date'])); ?></span>
			<?php
			if($this->session->userdata ('user') && $user[0]->user_id == $paid_milestones_value['project_owner_id']){
			?>
			<b class="space-left">Business Service Fee :</b>
			<span><?php echo str_replace(".00","",number_format($paid_milestones_value['bussiness_fee_charges'],  2, '.', ' '))." ".CURRENCY; ?></span>
			<?php
			}
			?>
			<b class="space-left">Amount :</b>
			<span><?php echo str_replace(".00","",number_format($paid_milestones_value['milestone_amount_value'],  2, '.', ' '))." ".CURRENCY; ?></span>
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
		<b class="default_black_bold totalCurrency"><span class="total_rightside_gap"><?php echo $total_paid_txt; ?></span><span class="touch_line_break <?php echo "sum_escrow_amount_".$section_id ?>"><?php echo str_replace(".00","",number_format($sum_escrow_amount,  2, '.', ' ')).' '.CURRENCY; ?></span></b><?php
		if($user[0]->user_id  == $po_id){?><b class="default_black_bold totalCurrency"><span class="total_rightside_gap"><?php echo $total_business_charge_txt; ?></span>
		<span class="touch_line_break <?php echo "sum_escrow_amount_bussiness_fee_charges_".$section_id ?>"><?php echo str_replace(".00","",number_format($sum_escrow_bussiness_fee_charges,  2, '.', ' ')).' '.CURRENCY; ?></span>
		</b><?php } ?>
	</div>
</div>

<?php
echo $this->load->view('escrow/escrow_section_paging_project_detail',array('escrow_count'=>$released_escrow_count,'generate_pagination_links_escrow'=>$generate_pagination_links_escrow,'escrow_paging_limit'=>$this->config->item('project_detail_page_paid_escrow_listing_limit')), true);
?>
<?php
}
if(empty($released_escrow_data)){	
?>
<?php
/* <div class="no_paid_milestone"><div class="default_blank_message"><?php echo $this->config->item('no_paid_payment_milestone_message');?></div> */
?>
<div class="no_records_found">
	<div class="no_requested_escrow default_blank_message">
		<?php 
			if($po_id == $user_data['user_id']) {
				echo $this->config->item('no_released_escrow_payment_message_po_view');
			} else {
				echo $this->config->item('no_received_escrow_payment_message_sp_view');
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
<div class="<?php echo "sum_milestones_amount_container_".$bid_id." payAmount" ?>" style="<?php echo $show_total_amount_css; ?>">
<?php
if($user[0]->user_id  == $sp_id){	
	echo "Total Received";
}
if($user[0]->user_id  == $po_id){
	echo "Total paid";	
}	
?>	
<span class="<?php echo "sum_milestones_amount_".$bid_id ?>"><?php echo str_replace(".00","",number_format($sum_milestones_amount,  2, '.', ' ')); ?></span> <?php echo CURRENCY; ?>
<?php
if($user[0]->user_id  == $po_id){
	echo "&nbsp;&nbsp;&nbsp;Total Business Charges

";
?>
<span class="<?php echo "sum_milestones_amount_bussiness_fee_charges_".$bid_id ?>"><?php echo str_replace(".00","",number_format($sum_milestone_bussiness_fee_charges,  2, '.', ' ')); ?></span> <?php echo CURRENCY; ?>
<?php
}
?>
</div>
<div class="clearfix"></div>
<div class="paging_section">
<?php
echo $this->load->view('milestones/milestones_section_paging_project_detail',array('milestones_count'=>$paid_milestones_count,'generate_pagination_links_milestones'=>$generate_pagination_links_milestones,'milestone_paging_limit'=>$this->config->item('project_detail_page_paid_escrow_listing_limit')), true);
?>
</div> */
?>