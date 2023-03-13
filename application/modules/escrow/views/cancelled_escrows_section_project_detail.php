<?php
$user = $this->session->userdata('user');
$show_no_cancelled_escrow_message_css = "display:block;";


if (!empty($cancelled_escrow_data)) {
    ?>
    <?php
    $show_no_cancelled_escrow_message_css = "display:none;";
    foreach ($cancelled_escrow_data as $cancelled_escrow_key => $cancelled_escrow_value) {
		

if ($this->session->userdata('user') && $user[0]->user_id == $cancelled_escrow_value['project_owner_id']) {
	
	 if($project_type == 'fixed') {
		$reverted_amount_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_amount_txt_po_view');
		$reverted_business_fee_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_business_service_fee_txt_po_view');
		$total_reverted_amount_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_reverted_amount_txt_po_view');
		$dispute_id_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_id_txt_po_view');
		$dispute_close_date_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_close_date_txt_po_view');
		$description_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_description_txt_po_view');
		
		$cancelled_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_on_txt_po_view');
		$created_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_created_on_txt_po_view');
		$total_text = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_txt_po_view');
		
	 }
	if($project_type == 'fulltime') {
		$reverted_amount_txt = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_amount_txt_employer_view');
		$reverted_business_fee_txt = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_business_service_fee_txt_employer_view');
		$total_reverted_amount_txt = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_reverted_amount_txt_employer_view');
		$dispute_id_txt = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_id_txt_employer_view');
		$dispute_close_date_txt = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_close_date_txt_employer_view');
		$description_txt = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_description_txt_employer_view');
		
		$cancelled_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_on_txt_employer_view');
		$created_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_created_on_txt_employer_view');
		$total_text = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_txt_employer_view');
		
		
	 }	 
	 if($project_type == 'hourly') {
		$reverted_amount_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_amount_txt_po_view');
		$reverted_business_fee_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_business_service_fee_txt_po_view');
		$total_reverted_amount_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_reverted_amount_txt_po_view');
		$dispute_id_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_id_txt_po_view');
		$dispute_close_date_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_close_date_txt_po_view');
		$description_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_description_txt_po_view');
		
		
		
		
		$cancelled_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_on_txt_po_view');
		$created_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_created_on_txt_po_view');
		
		$hourly_rate_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_hourly_rate_txt_po_view');
		$number_hours_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_number_of_hours_txt_po_view');
		$total_text = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_txt_po_view');
	 }
}
if ($this->session->userdata('user') && $user[0]->user_id == $cancelled_escrow_value['winner_id']) {
	
	if($project_type == 'fixed') {
		$dispute_id_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_id_txt_sp_view');
		$dispute_close_date_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_close_date_txt_sp_view');
		
		$reverted_amount_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_amount_topo_txt_sp_view');
		
		$description_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_description_txt_sp_view');
		
		$cancelled_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_on_txt_sp_view');
		$created_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_created_on_txt_sp_view');
		$total_text = $this->config->item('fixed_budget_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_txt_sp_view');
		
	
	}
	if($project_type == 'fulltime') {
		$dispute_id_txt = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_id_txt_employee_view');
		$dispute_close_date_txt = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_close_date_txt_employee_view');
		
		$reverted_amount_txt = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_amount_topo_txt_employee_view');
		
		$description_txt = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_description_txt_employee_view');
		
		$cancelled_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_on_txt_employee_view');
		$created_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_created_on_txt_employee_view');
		$total_text = $this->config->item('fulltime_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_txt_employee_view');
		
	
	}
	if($project_type == 'hourly') {
		$dispute_id_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_id_txt_sp_view');
		$dispute_close_date_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_dispute_close_date_txt_sp_view');
		
		$reverted_amount_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_reverted_amount_topo_txt_sp_view');
		
		$description_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_description_txt_sp_view');
		
		$cancelled_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_on_txt_sp_view');
		$created_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_created_on_txt_sp_view');
		
		$hourly_rate_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_hourly_rate_txt_sp_view');
		$number_hours_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_cancelled_number_of_hours_txt_sp_view');
		$total_text = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_cancelled_escrowed_payments_tab_total_txt_sp_view');
	}
}		
		
		
        ?>
        <div class="projTitle">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12 mDetails">
					<div class="divRightAuto">
                        <?php
                        if($project_type == 'hourly') {
                        ?>
							<?php
							if(floatval($cancelled_escrow_value['reverted_escrow_considered_hourly_rate']) != 0){
							?>
							<label>
								<div>
									<b class="default_black_bold"><?php echo $hourly_rate_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo str_replace(".00","",number_format($cancelled_escrow_value['reverted_escrow_considered_hourly_rate'],  2, '.', ' ')) . " " . CURRENCY.$this->config->item('project_details_page_hourly_rate_based_project_per_hour'); ?></span>
								</div>
							</label>
							<?php
							}
							if(floatval($cancelled_escrow_value['reverted_escrow_considered_number_of_hours']) != 0){
							?>
								<label>
									<div>
										<b class="default_black_bold"><?php echo $number_hours_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo str_replace(".00","",number_format($cancelled_escrow_value['reverted_escrow_considered_number_of_hours'],  2, '.', ' ')); ?></span>
									</div>
								</label>
                        <?php
								}
                            }
                        ?>
						<label>
							<div>
								<?php
								if ($this->session->userdata('user') && $user[0]->user_id == $cancelled_escrow_value['winner_id']) {
									
									
									$reverted_amount_txt = str_replace("{user_first_name_last_name_or_company_name}",$po_name,$reverted_amount_txt );
								}
								?>	
								<b class="default_black_bold"><?php echo $reverted_amount_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo str_replace(".00", "", number_format($cancelled_escrow_value['reverted_escrowed_amount'], 2, '.', ' ')) . " " . CURRENCY; ?></span>
							</div>
						</label>
						<?php
						if ($this->session->userdata('user') && $user[0]->user_id == $cancelled_escrow_value['project_owner_id']) {
							?>
							<label>
								<div>
									<b class="default_black_bold"><?php echo $reverted_business_fee_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo str_replace(".00", "", number_format($cancelled_escrow_value['reverted_service_fee_charges'] , 2, '.', ' ')) . " " . CURRENCY; ?></span>
								</div>
							</label>
							<label>
								<div>
									<b class="default_black_bold"><?php echo $total_reverted_amount_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo str_replace(".00", "", number_format($cancelled_escrow_value['total_reverted_escrow_payment_value'], 2, '.', ' ')) . " " . CURRENCY; ?></span>
								</div>
							</label>
							<?php
						}
						?>
					</div>

					<div class="divRightAuto">
						
						<label>
							<div>
							<?php
							if(empty($cancelled_escrow_value['dispute_reference_id'])){

								/* if ($this->session->userdata('user') && $user[0]->user_id == $cancelled_escrow_value['project_owner_id']) {
									$ecrow_creation_label = 'Outgoing escrow payment created on:';
								}
								if ($this->session->userdata('user') && $user[0]->user_id == $cancelled_escrow_value['winner_id']) {
									$ecrow_creation_label = 'Incoming escrow payment created on:';	
								}	 */	
							?>
								<b class="default_black_bold"><?php echo $created_on_txt; ?></b><span class="touch_line_break default_black_regular"><?php echo date(DATE_TIME_FORMAT, strtotime($cancelled_escrow_value['initial_escrow_creation_date'])); ?></span>
							<?php
							}else{
							?>	
								<b class="default_black_bold"><?php echo $dispute_id_txt; ?></b><span class="touch_line_break default_black_regular"><?php echo $cancelled_escrow_value['dispute_reference_id']; ?></span>
							<?php
							}	
							?>	
							</div>
						</label>
						<?php
						if ($this->session->userdata('user') && $user[0]->user_id == $cancelled_escrow_value['project_owner_id']) {
						?>
						<label>
							<div>
								<b class="default_black_bold"><?php echo (!empty($cancelled_escrow_value['dispute_reference_id'])) ? $dispute_close_date_txt : $cancelled_on_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo date(DATE_TIME_FORMAT, strtotime($cancelled_escrow_value['escrow_cancellation_date'])); ?></span>
							</div>
						</label>
						<?php
						}
						?>
					</div>
					<?php
						if ($this->session->userdata('user') && $user[0]->user_id == $cancelled_escrow_value['winner_id']) {
					?>
					<div class="divRightAuto">
						<label>
							<div>
								<b class="default_black_bold"><?php echo (!empty($cancelled_escrow_value['dispute_reference_id'])) ? $dispute_close_date_txt : $cancelled_on_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo date(DATE_TIME_FORMAT, strtotime($cancelled_escrow_value['escrow_cancellation_date'])); ?></span>
							</div>
						</label>
					</div>
					<?php
						}
					?>
					<div class="divRightAuto">						
						<?php if (!empty($cancelled_escrow_value['cancelled_escrow_description'])) { ?>
							<p class="default_black_regular aDispDesc"><b class="default_black_bold"><?php echo $description_txt; ?></b><?php echo htmlspecialchars($cancelled_escrow_value['cancelled_escrow_description'], ENT_QUOTES); ?></p>
						<?php } ?>
					</div>
                </div>
            </div>
        </div>
        <?php
        /* <label>
          <b>Description :</b>
          <span><?php echo $cancelled_milestones_value['milestone_description']; ?></span>
          </label>
          <label>

          <b>Initial Created On :</b>
          <span><?php echo date(DATE_TIME_FORMAT,strtotime($cancelled_milestones_value['initial_milestone_creation_date'])); ?></span>

          <b>Cancelled On :</b>
          <span><?php echo date(DATE_TIME_FORMAT,strtotime($cancelled_milestones_value['milestone_cancellation_date'])); ?></span>
          <b class="amount-titl">Milestone Amount :</b>
          <span><?php echo str_replace(".00","",number_format($cancelled_milestones_value['reverted_milestone_amount_value'],  2, '.', ' '))." ".CURRENCY; ?></span>
          <?php
          if($this->session->userdata ('user') && $user[0]->user_id == $cancelled_milestones_value['project_owner_id']){
          ?>
          <b class="amount-titl">Business Service Fee :</b>
          <span><?php echo str_replace(".00","",number_format($cancelled_milestones_value['reverted_business_fee_charges'],  2, '.', ' '))." ".CURRENCY; ?></span>
          <b class="amount-titl">Total Milestone :</b>
          <span><?php echo str_replace(".00","",number_format($cancelled_milestones_value['total_reverted_milestone_value'],  2, '.', ' '))." ".CURRENCY; ?></span>
          <?php
          }
          ?>

          </label>
          <div class="clearfix"></div> */
        ?>
        <?php
    }
    $show_total_amount_css = 'display:none';
    if (floatval($sum_escrow_amount) != 0) {
        $show_total_amount_css = 'display:block';
    }
    ?>
    <div class="projTitle text-right escrowTotal <?php echo "sum_escrow_amount_container_" . $bid_id . " payAmount" ?>" style="<?php echo $show_total_amount_css; ?>">
		<div class="currencyDetails">
			<b class="default_black_bold totalCurrency"><span class="total_rightside_gap"><?php echo $total_text; ?></span><span class="<?php echo "sum_escrow_amount_" . $bid_id ?>"><?php echo str_replace(".00", "", number_format($sum_escrow_amount, 2, '.', ' ')); ?></span> <?php echo CURRENCY; ?></b>
		</div>
    </div>
	<?php
	echo $this->load->view('escrow/escrow_section_paging_project_detail', array('escrow_count' => $cancelled_escrow_count, 'generate_pagination_links_escrow' => $generate_pagination_links_escrow, 'escrow_paging_limit' => $this->config->item('project_detail_page_cancelled_escrow_listing_limit')), true);
	?>
    <?php
}
?>
<?php
if (empty($cancelled_escrow_data)) {
    ?>
    <?php
    /* <div  class="<?php echo "no_cancelled_milestone_".$bid_id." default_blank_message" ?>" style="<?php echo $show_no_cancelled_milestone_message_css; ?>"><?php echo $this->config->item('no_cancelled_payment_milestone_message'); ?></div> */
    ?>
    <div class="no_records_found" style="<?php echo $show_no_cancelled_escrow_message_css; ?>">
        <div class="no_requested_escrow default_blank_message <?php echo "no_cancelled_escrow_" . $bid_id . " default_blank_message" ?>">
            <?php 
                if($po_id == $user_data['user_id']) {
                    echo $this->config->item('no_cancelled_escrow_creation_request_message_po_view');
                } else {
                    echo $this->config->item('no_cancelled_escrow_creation_request_message_sp_view');
                }
            ?></div>
    </div>
    <?php
}
?>	
<?php
/* $show_total_amount_css = 'display:none';
  if(floatval($sum_milestones_amount) != 0){
  $show_total_amount_css = 'display:block';
  }
  ?>
  <div  class="<?php echo "sum_milestones_amount_container_".$bid_id." payAmount" ?>" style="<?php echo $show_total_amount_css; ?>">Total <span class="<?php echo "sum_milestones_amount_".$bid_id ?>"><?php echo str_replace(".00","",number_format($sum_milestones_amount,  2, '.', ' ')); ?></span> <?php echo CURRENCY; ?></div>
  <div class="clearfix"></div>
  <div class="paging_section">
  <?php
  echo $this->load->view('milestones/milestones_section_paging_project_detail',array('milestones_count'=>$cancelled_milestones_count,'generate_pagination_links_milestones'=>$generate_pagination_links_milestones,'milestone_paging_limit'=>$this->config->item('project_detail_page_cancelled_escrow_listing_limit')), true);
  ?>
  </div> */
?>