<?php
$user = $this->session->userdata ('user');
if($project_type == 'fulltime'){
	$cancel_option = $this->config->item('fulltime_project_requested_escrow_section_option_cancel_employee_view');
	$reject_option =  $this->config->item('fulltime_project_requested_escrow_section_option_reject_employer_view');
	$pay_requested_escrow = $this->config->item('fulltime_project_requested_escrow_section_option_pay_employer_view');
	$dispute_ref_id = get_sp_project_disputed_reference_id($project_type,array('disputed_fulltime_project_id'=>$project_id,'employer_id_of_disputed_fulltime_project'=>$po_id,'employee_winner_id_of_disputed_fulltime_project'=>$sp_id));
}else{
	$cancel_option = $this->config->item('project_requested_escrow_section_option_cancel_sp_view');
	$reject_option =  $this->config->item('project_requested_escrow_section_option_reject_po_view');
	$pay_requested_escrow = $this->config->item('project_requested_escrow_section_option_pay_po_view');
	$dispute_ref_id = get_sp_project_disputed_reference_id($project_type,array('disputed_project_id'=>$project_id,'project_owner_id_of_disputed_project'=>$po_id,'sp_winner_id_of_disputed_project'=>$sp_id));
	
	
}



if($this->session->userdata ('user') && $user[0]->user_id == $po_id){
	if($project_type == 'fixed'){
		$total_requested_amount_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_incoming_payment_requests_tab_total_requested_amount_txt_po_view');
		$requested_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_incoming_payment_requests_tab_requested_on_txt_po_view');
		$description_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_incoming_payment_requests_tab_description_txt_po_view');
	}
	if($project_type == 'fulltime'){
		$total_requested_amount_txt = $this->config->item('fulltime_project_details_page_payment_management_section_incoming_payment_requests_tab_total_requested_amount_txt_employer_view');
		$requested_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_incoming_payment_requests_tab_requested_on_txt_employer_view');
		$description_txt = $this->config->item('fulltime_project_details_page_payment_management_section_incoming_payment_requests_tab_description_txt_employer_view');
	}
	if($project_type == 'hourly'){
		$total_requested_amount_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_incoming_payment_requests_tab_total_requested_amount_txt_po_view');
		$requested_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_incoming_payment_requests_tab_requested_on_txt_po_view');
		$description_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_incoming_payment_requests_tab_description_txt_po_view');
		
		$number_hours_text = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_incoming_payment_requests_tab_requested_number_of_hours_txt_po_view');
		$hourly_rate_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_incoming_payment_requests_tab_requested_hourly_rate_txt_po_view');
		
	}	
	
}

if($this->session->userdata ('user') && $user[0]->user_id == $requested_escrow_value['winner_id']){	
	if($project_type == 'fixed'){
		$total_requested_amount_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_sent_payment_requests_tab_total_requested_amount_txt_sp_view');
		$requested_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_sent_payment_requests_tab_requested_on_txt_sp_view');
		$description_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_sent_payment_requests_tab_description_txt_sp_view');
	}
	if($project_type == 'fulltime'){
		$total_requested_amount_txt = $this->config->item('fulltime_project_details_page_payment_management_section_sent_payment_requests_tab_total_requested_amount_txt_employee_view');
		$requested_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_sent_payment_requests_tab_requested_on_txt_employee_view');
		$description_txt = $this->config->item('fulltime_project_details_page_payment_management_section_sent_payment_requests_tab_description_txt_employee_view');
		
		
	}
	if($project_type == 'hourly'){
		$total_requested_amount_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_sent_payment_requests_tab_total_requested_amount_txt_sp_view');
		$requested_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_sent_payment_requests_tab_requested_on_txt_sp_view');
		$description_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_sent_payment_requests_tab_description_txt_sp_view');
		$number_hours_text = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_sent_payment_requests_tab_requested_number_of_hours_txt_sp_view');
		$hourly_rate_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_sent_payment_requests_tab_requested_hourly_rate_txt_sp_view');
	}
}

?>

<div class="projTitle">
	<div class="row">
		<div class="col-md-8 col-sm-8 col-12 mDetails">
			<div class="divRightAuto">
			<?php 
				if($project_type == 'hourly') {
			?>
			<?php 
				if($requested_escrow_value['sp_requested_escrow_creation_number_of_hours'] != 0) {
			?>
			<label>
				<div>
					<b class="default_black_bold"><?php echo $number_hours_text; ?></b><span class="default_black_regular touch_line_break"><?php echo str_replace(".00","",number_format($requested_escrow_value['sp_requested_escrow_creation_number_of_hours'],  2, '.', ' ')); ?></span>
				</div>
			</label>
			<?php
				}
			?>
			<?php 
				if($requested_escrow_value['sp_requested_escrow_creation_hourly_rate'] != 0) {
			?>
			<label>
				<div>
					<b class="default_black_bold"><?php echo $hourly_rate_txt; ?></b><span class="touch_line_break default_black_regular"><?php echo str_replace(".00","",number_format($requested_escrow_value['sp_requested_escrow_creation_hourly_rate'],  2, '.', ' '))." ".CURRENCY.$this->config->item('project_details_page_hourly_rate_based_project_per_hour'); ?></span>
				</div>
			</label>
			<?php
				}
			?>
			<?php		
				}
			?>
			<label>
				<div>
					<b class="default_black_bold"><?php echo $total_requested_amount_txt; ?></b><span class="touch_line_break default_black_regular"><?php echo str_replace(".00","",number_format($requested_escrow_value['requested_escrow_amount'],  2, '.', ' '))." ".CURRENCY; ?></span>
				</div>
			</label>
			<label>
				<div>
					<b class="default_black_bold"><?php echo $requested_on_txt; ?></b><span class="touch_line_break default_black_regular"><?php echo date(DATE_TIME_FORMAT,strtotime($requested_escrow_value['escrow_requested_by_sp_date'])); ?></span>
				</div>
			</label>
			</div>
			<?php if(!empty($requested_escrow_value['requested_escrow_description'])){ ?>
			<p class="default_black_regular aDispDesc"><b class="default_black_bold"><?php echo $description_txt;?></b><?php echo htmlspecialchars($requested_escrow_value['requested_escrow_description'], ENT_QUOTES); ?></p>
			<?php } ?>
		</div>
		
		<div class="col-md-4 col-sm-4 col-12 downPosition">	
			<?php
			if(empty($dispute_ref_id)){
			?>
			<div class="myAction actionBtn_adjust">
				<div class="dropdown">
					<button class="btn dropdown-toggle default_btn dark_blue_btn noPaddingtb" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="<?php echo "dropdownMenuButtonInProgressRequestedEscrow".$requested_escrow_value['id']  ?>">
					<?php echo $this->config->item('action'); ?>
					</button>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
						<?php
						if($this->session->userdata ('user') && $user[0]->user_id == $requested_escrow_value['winner_id']){
							echo "<a style='cursor:pointer' class='dropdown-item cancel_requested_escrow_confirmation'   data-id='".$requested_escrow_value['id']."' data-project-id='".$project_id."' data-po-id='".Cryptor::doEncrypt($requested_escrow_value['project_owner_id'])."' data-sp-id='".Cryptor::doEncrypt($requested_escrow_value['winner_id'])."' data-tab-type='requested_escrow' data-section-name='".$section_name."'   data-section-id='".$requested_escrow_value['winner_id']."'>".$cancel_option."</a>";
							}
						if($this->session->userdata ('user') && $user[0]->user_id == $po_id){
							echo "<a style='cursor:pointer' class='dropdown-item reject_requested_escrow_confirmation'  data-id='".$requested_escrow_value['id']."' data-project-id='".$project_id."' data-po-id='".Cryptor::doEncrypt($requested_escrow_value['project_owner_id'])."'  data-sp-id='".Cryptor::doEncrypt($requested_escrow_value['winner_id'])."' data-tab-type='requested_escrow'  data-section-name='".$section_name."' data-section-id='".$requested_escrow_value['winner_id']."' >".$reject_option."</a>";
							
							echo "<a style='cursor:pointer' class='dropdown-item create_requested_escrow_confirmation_po' data-id='".$requested_escrow_value['id']."' data-project-id='".$project_id."' data-po-id='".Cryptor::doEncrypt($requested_escrow_value['project_owner_id'])."'  data-sp-id='".Cryptor::doEncrypt($requested_escrow_value['winner_id'])."' data-tab-type='requested_escrow' data-section-name='".$section_name."' data-section-id='".$requested_escrow_value['winner_id']."' >".$pay_requested_escrow."</a>";
						}
						?>
					</div>
				</div>
			</div>
			<?php
			}
			?>
		</div>
	</div>
</div>

<?php
/* <div class="desReq">
	<label>
		<b>Description :</b>
		<span><?php echo $requested_escrow_value['requested_milestone_description']; ?></span>
	</label>
	<label>
		<b>Requested On :</b>
		<span><?php echo date(DATE_TIME_FORMAT,strtotime($requested_escrow_value['milestone_requested_by_sp_date'])); ?></span>
		<b class="amount-titl">Amount :</b>
		<span><?php echo str_replace(".00","",number_format($requested_escrow_value['requested_milestone_amount_value'],  2, '.', ' '))." ".CURRENCY; ?></span>
		<small>
			<div class="myAction">
				<div class="dropdown">
				
					<button class="btn dropdown-toggle" type="button" id="<?php echo "dropdownMenuButtonInProgressRequestedMilestone".$requested_escrow_value['id']  ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Action
					</button>
					<div class="dropdown-menu" aria-labelledby="<?php echo "dropdownMenuButtonInProgressRequestedMilestone".$requested_escrow_value['id']  ?>" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 476px, 0px); top: 0px; left: 0px; will-change: transform;" x-out-of-boundaries="">
						<?php
						if($this->session->userdata ('user') && $user[0]->user_id == $requested_escrow_value['winner_id']){
							echo "<a style='cursor:pointer' class='dropdown-item cancel_requested_milestone_confirmation'   data-id='".$requested_escrow_value['id']."' data-project-id='".$project_id."' data-po-id='".Cryptor::doEncrypt($requested_escrow_value['project_owner_id'])."' data-sp-id='".Cryptor::doEncrypt($requested_escrow_value['winner_id'])."' data-tab-type='requested_milestone' data-section-name='".$section_name."'   data-section-id='".$bid_id."'>".$cancel_option."</a>";
						}
						if($this->session->userdata ('user') && $user[0]->user_id == $po_id){
							echo "<a style='cursor:pointer' class='dropdown-item reject_requested_milestone_confirmation'  data-id='".$requested_escrow_value['id']."' data-project-id='".$project_id."' data-po-id='".Cryptor::doEncrypt($requested_escrow_value['project_owner_id'])."'  data-sp-id='".Cryptor::doEncrypt($requested_escrow_value['winner_id'])."' data-tab-type='requested_milestone' data-tab-type='requested_milestone' data-section-name='".$section_name."' data-section-id='".$bid_id."' >".$reject_option."</a>";
							
							echo "<a style='cursor:pointer' class='dropdown-item create_requested_milestone_confirmation_po' data-id='".$requested_escrow_value['id']."' data-project-id='".$project_id."' data-po-id='".Cryptor::doEncrypt($requested_escrow_value['project_owner_id'])."'  data-sp-id='".Cryptor::doEncrypt($requested_escrow_value['winner_id'])."' data-tab-type='requested_milestone' data-tab-type='requested_milestone' data-section-name='".$section_name."' data-section-id='".$bid_id."' >".$pay_requested_milestone."</a>";
						}
						?>
					</div>
				</div>
			</div>
		</small>
	</label>
	<div class="clearfix"></div>
</div> */
?>