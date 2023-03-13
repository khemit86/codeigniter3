<?php
$user = $this->session->userdata ('user');
if($project_type == 'fulltime'){
	$release_option = $this->config->item('fulltime_project_active_escrow_section_option_release_employer_view');
	$partial_release_option = $this->config->item('fulltime_project_active_escrow_section_option_partial_release_employer_view');
	$cancel_option = $this->config->item('fulltime_project_active_escrow_section_option_cancel_employee_view');
	$request_release_option = $this->config->item('fulltime_project_active_escrow_section_option_request_release_employee_view');
}else{
	$release_option = $this->config->item('project_active_escrow_section_option_release_po_view');
	$partial_release_option = $this->config->item('project_active_escrow_section_option_partial_release_po_view');
	$cancel_option = $this->config->item('project_active_escrow_section_option_cancel_sp_view');
	$request_release_option = $this->config->item('project_active_escrow_section_option_request_release_sp_view');
	
}
if($this->session->userdata ('user') && $user[0]->user_id == $active_escrow_value['project_owner_id']){
	if($project_type == 'fixed'){
		
		$requested_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_requested_on_txt_po_view');
		
		$escrow_amount_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_escrow_amount_txt_po_view');
		
		$business_service_fee_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_business_service_fee_txt_po_view');
		
		$total_escrow_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_total_escrow_txt_po_view');
		$created_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_created_on_txt_po_view');
		$description_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_description_txt_po_view');
	}
	if($project_type == 'fulltime'){
		
		$requested_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_requested_on_txt_employer_view');
		
		$escrow_amount_txt = $this->config->item('fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_escrow_amount_txt_employer_view');
		
		$business_service_fee_txt = $this->config->item('fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_business_service_fee_txt_employer_view');
		
		$total_escrow_txt = $this->config->item('fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_total_escrow_txt_employer_view');
		$created_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_created_on_txt_employer_view');
		$description_txt = $this->config->item('fulltime_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_description_txt_employer_view');
	}
	if($project_type == 'hourly'){
		
		$requested_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_requested_on_txt_po_view');
		
		$escrow_amount_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_escrow_amount_txt_po_view');
		
		$business_service_fee_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_business_service_fee_txt_po_view');
		
		$total_escrow_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_total_escrow_txt_po_view');
		$created_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_created_on_txt_po_view');
		$description_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_description_txt_po_view');
		
		$hourly_rate_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_hourly_rate_txt_po_view');
		$number_hours_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_outgoing_escrowed_payments_tab_number_of_hours_txt_po_view');
	}	
}	
if($this->session->userdata ('user') && $user[0]->user_id == $active_escrow_value['winner_id']){
	if($project_type == 'fixed'){
		
		$requested_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_incoming_escrowed_payments_tab_requested_on_txt_sp_view');
		
		$escrow_amount_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_incoming_escrowed_payments_tab_escrow_amount_txt_sp_view');
		$created_on_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_incoming_escrowed_payments_tab_created_on_txt_sp_view');
		$description_txt = $this->config->item('fixed_budget_project_details_page_payment_management_section_incoming_escrowed_payments_tab_description_txt_sp_view');
	}
	if($project_type == 'fulltime'){
		
		$requested_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_incoming_escrowed_payments_tab_requested_on_txt_employee_view');
		
		$escrow_amount_txt = $this->config->item('fulltime_project_details_page_payment_management_section_incoming_escrowed_payments_tab_escrow_amount_txt_employee_view');
		$created_on_txt = $this->config->item('fulltime_project_details_page_payment_management_section_incoming_escrowed_payments_tab_created_on_txt_employee_view');
		$description_txt = $this->config->item('fulltime_project_details_page_payment_management_section_incoming_escrowed_payments_tab_description_txt_employee_view');
	}
	if($project_type == 'hourly'){
		
		$requested_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_incoming_escrowed_payments_tab_requested_on_txt_sp_view');
		
		$escrow_amount_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_incoming_escrowed_payments_tab_escrow_amount_txt_sp_view');
		$created_on_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_incoming_escrowed_payments_tab_created_on_txt_sp_view');
		$description_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_incoming_escrowed_payments_tab_description_txt_sp_view');
		
		$hourly_rate_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_incoming_escrowed_payments_tab_hourly_rate_txt_sp_view');
		$number_hours_txt = $this->config->item('hourly_rate_based_project_details_page_payment_management_section_incoming_escrowed_payments_tab_number_of_hours_txt_sp_view');
		
	}
}	
?>

<div class="projTitle">
	<div class="row">
		<div class="col-md-8 col-sm-8 col-12 mDetails">
			<div class="divRightAuto">
			<?php
			if($active_escrow_value['escrow_creation_requested_by_sp'] == 'Y'){
			?>
			<label>
				<div>
					<b class="default_black_bold"><?php echo $requested_on_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo date(DATE_TIME_FORMAT,strtotime($active_escrow_value['escrow_creation_requested_by_sp_date'])); ?></span>
				</div>
			</label>
				<?php
			}
			?>
			<?php
			if($project_type == 'hourly') {
			?>
			<label>
				<div>
					<b class="default_black_bold"><?php echo $hourly_rate_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo str_replace(".00","",number_format($active_escrow_value['escrow_considered_hourly_rate'],  2, '.', ' '))." ".CURRENCY.$this->config->item('project_details_page_hourly_rate_based_project_per_hour'); ?></span>
				</div>
			</label>
			<label>
				<div>
					<b class="default_black_bold"><?php echo $number_hours_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo str_replace(".00","",number_format($active_escrow_value['escrow_considered_number_of_hours'],  2, '.', ' ')); ?></span>
				</div>
			</label>
			<?php
				}
			?>
			<label>
				<div>
					<b class="default_black_bold"><?php echo $escrow_amount_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo str_replace(".00","",number_format($active_escrow_value['created_escrow_amount'],  2, '.', ' '))." ".CURRENCY; ?></span>
				</div>
			</label>
			<?php
			if($this->session->userdata ('user') && $user[0]->user_id == $active_escrow_value['project_owner_id']){
			?>
			<label>
				<div>
					<b class="default_black_bold"><?php echo $business_service_fee_txt ?></b><span class="default_black_regular touch_line_break"><?php echo str_replace(".00","",number_format(($project_type == 'fixed') ? $active_escrow_value['service_fee_charges'] : $active_escrow_value['service_fee_charges'],  2, '.', ' '))." ".CURRENCY; ?></span>
				</div>
			</label>
			<label>
				<div>
					<b class="default_black_bold"><?php echo $total_escrow_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo str_replace(".00","",number_format($active_escrow_value['total_escrow_payment_value'],  2, '.', ' '))." ".CURRENCY; ?></span>
				</div>
			</label>
			<?php
			}
			?>
			</div>
			<div class="divRightAuto">
				<label>
					<div>
						<b class="default_black_bold"><?php echo $created_on_txt; ?></b><span class="default_black_regular touch_line_break"><?php echo date(DATE_TIME_FORMAT,strtotime($active_escrow_value['escrow_creation_date'])); ?></span>
					</div>
				</label>
			</div>
			<?php if(!empty(trim($active_escrow_value['escrow_description']))){ ?>
			<p class="default_black_regular aDispDesc"><b class="default_black_bold"><?php echo $description_txt; ?></b><?php echo htmlspecialchars($active_escrow_value['escrow_description'], ENT_QUOTES); ?></p>
			<?php } ?>
			<?php
			if($active_escrow_value['is_sp_requested_release'] == 'Y'){
			?>
			<label class="escrow_release_message <?php echo "escrow_requested_release_message_".$active_escrow_value['id']; ?>">
			<?php
			if($this->session->userdata ('user') && $user[0]->user_id == $active_escrow_value['project_owner_id']){
				if($project_type == 'fulltime') { 
					$escrow_request_release_message = $this->config->item('project_details_page_employer_view_fulltime_project_requested_release_escrow_message');
				} else {
					$escrow_request_release_message = $this->config->item('project_details_page_po_view_project_requested_release_escrow_message');
				}
				$escrow_request_release_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($sp_name),$escrow_request_release_message);
			}
			if($this->session->userdata ('user') && $user[0]->user_id == $active_escrow_value['winner_id']){
				if($project_type == 'fulltime') { 
				
					$escrow_request_release_message = $this->config->item('project_details_page_employee_view_fulltime_project_requested_release_escrow_message');
				} else {
					$escrow_request_release_message = $this->config->item('project_details_page_sp_view_project_requested_release_escrow_message');
				}
				$escrow_request_release_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($po_name),$escrow_request_release_message);
			}
			
			if($project_type == 'fulltime') { 
				echo $escrow_request_release_message = str_replace(array('{fulltime_request_release_escrow_value}','{employee_requested_release_date}'),array(str_replace(".00","",number_format($active_escrow_value['created_escrow_amount'],  2, '.', ' '))." ".CURRENCY,date(DATE_TIME_FORMAT,strtotime($active_escrow_value['sp_requested_release_date']))),$escrow_request_release_message);
			} else {
				echo $escrow_request_release_message = str_replace(array('{requested_release_escrow_amount}','{sp_requested_release_date}'),array(str_replace(".00","",number_format($active_escrow_value['created_escrow_amount'],  2, '.', ' '))." ".CURRENCY,date(DATE_TIME_FORMAT,strtotime($active_escrow_value['sp_requested_release_date']))),$escrow_request_release_message);
			}
			?>
			</label>
			<?php
			}
			?>
		</div>
		<div class="col-md-4 col-sm-4 col-12 downPosition">
			
			<div class="myAction actionBtn_adjust">
				<div class="dropdown">
					<button class="btn dropdown-toggle default_btn dark_blue_btn noPaddingtb" type="button" id="<?php echo "dropdownMenuButtonInProgressRequestedEscrow".$requested_escrow_value['id']  ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<?php echo $this->config->item('action'); ?>
					</button>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="<?php echo "dropdownMenuButtonInProgressRequestedEscrow".$requested_escrow_value['id']  ?>">
						<?php
							if($this->session->userdata ('user') && $user[0]->user_id == $active_escrow_value['project_owner_id']){
							
								echo "<a style='cursor:pointer' class='dropdown-item release_escrow_confirmation_po'  data-id='".$active_escrow_value['id']."' data-project-id='".$project_id."' data-po-id='".Cryptor::doEncrypt($active_escrow_value['project_owner_id'])."'  data-sp-id='".Cryptor::doEncrypt($active_escrow_value['winner_id'])."' data-tab-type='active_escrow' data-section-name='".$section_name."' data-section-id='".$active_escrow_value['winner_id']."' >".$release_option."</a>";
								echo "<a style='cursor:pointer' class='dropdown-item partial_release_escrow_confirmation_po'  data-id='".$active_escrow_value['id']."' data-project-id='".$project_id."' data-po-id='".Cryptor::doEncrypt($active_escrow_value['project_owner_id'])."'  data-sp-id='".Cryptor::doEncrypt($active_escrow_value['winner_id'])."' data-tab-type='active_escrow' data-section-name='".$section_name."' data-section-id='".$active_escrow_value['winner_id']."' data-ea-id='".$active_escrow_value['created_escrow_amount']."'  data-sf-id='".$active_escrow_value['service_fee_charges']."' >".$partial_release_option."</a>";
							}
							if($this->session->userdata ('user') && $user[0]->user_id == $active_escrow_value['winner_id']){
								echo "<a style='cursor:pointer' class='dropdown-item cancelled_escrow_confirmation_sp' data-id='".$active_escrow_value['id']."' data-project-id='".$project_id."' data-po-id='".Cryptor::doEncrypt($active_escrow_value['project_owner_id'])."'  data-sp-id='".Cryptor::doEncrypt($active_escrow_value['winner_id'])."' data-tab-type='active_escrow' data-section-name='".$section_name."' data-section-id='".$active_escrow_value['winner_id']."' >".$cancel_option."</a>";
								$escrow_requested_release_option_class = 'escrow_requested_release_option_'.$active_escrow_value['id'];
								if($active_escrow_value['is_sp_requested_release'] == 'N'){
								echo "<a style='cursor:pointer' class='dropdown-item request_release_escrow_confirmation_sp ".$escrow_requested_release_option_class."'  data-id='".$active_escrow_value['id']."' data-project-id='".$project_id."' data-po-id='".Cryptor::doEncrypt($active_escrow_value['project_owner_id'])."'  data-sp-id='".Cryptor::doEncrypt($active_escrow_value['winner_id'])."' data-tab-type='active_escrow' data-section-name='".$section_name."' data-section-id='".$active_escrow_value['winner_id']."' >".$request_release_option."</a>";
								}
							
							}
						?>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>