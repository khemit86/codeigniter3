<?php
if($rejected_requested_escrows_listing_project_data_count >0){
	
	if($view_type == 'po'){
		$total_txt = $this->config->item('user_projects_payments_overview_page_rejected_payment_requests_tab_total_txt_po_view');	
	}else if($view_type == 'sp'){
		$total_txt = $this->config->item('user_projects_payments_overview_page_rejected_payment_requests_tab_total_txt_sp_view');
	}
	
	$total_rejected_requested_escrows = count($rejected_requested_escrows_listing_project_data);
	$record_counter = 1;
	foreach($rejected_requested_escrows_listing_project_data as $rejected_requested_escrows_listing_project_data_key=>$rejected_requested_escrows_listing_project_data_value){
		if($record_counter == $total_rejected_requested_escrows){
			$last_record_class_border_full_width = 'border_full_width';
		}
	$name = (($rejected_requested_escrows_listing_project_data_value['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($rejected_requested_escrows_listing_project_data_value['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $rejected_requested_escrows_listing_project_data_value['is_authorized_physical_person'] == 'Y')) ? $rejected_requested_escrows_listing_project_data_value['first_name'] . ' ' . $rejected_requested_escrows_listing_project_data_value['last_name'] : $rejected_requested_escrows_listing_project_data_value['company_name'];
?>	

	<div class="projTitle <?php echo $last_record_class_border_full_width; ?>">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12 mDetails">
				<div>
					<label class="pTitle">
						<div class="default_project_title">
							<b class="default_black_bold_medium"><?php echo $rejected_requested_escrows_listing_project_data_value['project_type'] == 'fulltime' ? $this->config->item('user_projects_payments_overview_page_fulltime_project_title_txt') : $this->config->item('user_projects_payments_overview_page_project_title_txt'); ?></b><a target="_blank" href="<?php echo base_url() . $this->config->item('project_detail_page_url') . "?id=" . $rejected_requested_escrows_listing_project_data_value['project_id']; ?>" class="default_project_title_link"><?php echo htmlspecialchars(trim($rejected_requested_escrows_listing_project_data_value['project_title']), ENT_QUOTES); ?></a>
						</div>
					</label>
				</div>
				<?php
				if($rejected_requested_escrows_listing_project_data_value['project_type'] != 'fulltime'){
				?>
				<div>
					<label>
						<div>
							<b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_payments_overview_page_project_type_txt'); ?></b><span class="default_black_regular_medium"><?php echo $rejected_requested_escrows_listing_project_data_value['project_type'] == 'hourly' ? $this->config->item('user_projects_payments_overview_page_hourly_rate_based_project_type_txt') : $this->config->item('user_projects_payments_overview_page_fixed_budget_project_type_txt'); ?></span>
						</div>
					</label>
				</div>
				<?php
				}
				?>
				<div>
					<label>
						<div>
							<?php
							if($view_type == 'po'){
							?><b class="default_black_bold_medium"><?php echo $rejected_requested_escrows_listing_project_data_value['project_type'] == 'fulltime' ? $this->config->item('user_projects_payments_overview_page_employee_name_txt') : $this->config->item('user_projects_payments_overview_page_service_provider_name_txt'); ?></b><?php
							}if($view_type == 'sp'){
							?><b class="default_black_bold_medium"><?php echo $rejected_requested_escrows_listing_project_data_value['project_type'] == 'fulltime' ? $this->config->item('user_projects_payments_overview_page_employer_name_txt') : $this->config->item('user_projects_payments_overview_page_project_owner_name_txt'); ?></b><?php
							}
							?><a target="_blank" href="<?php echo site_url ($rejected_requested_escrows_listing_project_data_value['profile_name']); ?>" class="default_project_title_link"><?php echo $name ?></a>
						</div>
					</label>
				</div>
				<div>
					<label>
						<div>
							<b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_payments_overview_page_amount_txt'); ?></b><span class="default_black_regular_medium"><?php echo str_replace(".00","",number_format($rejected_requested_escrows_listing_project_data_value['requested_escrow_amount'],  2, '.', ' '))." ".CURRENCY; ?></span>
						</div>
					</label><label>
						<div>
							<b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_payments_overview_page_rejected_payment_requests_tab_requested_on_txt'); ?></b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT,strtotime($rejected_requested_escrows_listing_project_data_value['escrow_requested_by_sp_date'])); ?></span>
						</div>
					</label><label>
						<div>
							<b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_payments_overview_page_rejected_on_txt'); ?></b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT,strtotime($rejected_requested_escrows_listing_project_data_value['requested_escrow_rejection_date'])); ?></span>
						</div>
					</label>
				</div>
				<?php
				if(!empty(trim($rejected_requested_escrows_listing_project_data_value['requested_escrow_description']))){
				?>
				<div>						
					<p class="default_black_regular_medium aDispDesc"><b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_payments_overview_page_description_txt'); ?></b><?php echo htmlspecialchars(trim($rejected_requested_escrows_listing_project_data_value['requested_escrow_description']), ENT_QUOTES); ?></p>
				</div>
				<?php
				}
				?>
			</div>
		</div>
	</div>
	<?php
	$record_counter ++;
	}	
	?>	
<div class="projTitle text-right milestoneTotal sum_milestones_amount_container_78 payAmount" style="display:block">
	<b class="default_black_bold_medium"><?php echo $total_txt; ?><small><span class="sum_milestones_amount_78"><?php echo str_replace(".00","",number_format($sum_all_rejected_requested_escrows_amount_all_projects,  2, '.', ' ')); ?></span> <?php echo CURRENCY; ?></small></b>
</div>
<div class="paging_section">
<?php
	echo $this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_paging',array('escrows_listing_project_data_count'=>$rejected_requested_escrows_listing_project_data_count,'generate_pagination_links_user_projects_payments_overview'=>$generate_pagination_links_user_projects_payments_overview,'escrows_listing_project_data_paging_limit'=>$this->config->item('user_projects_payments_overview_page_rejected_requested_escrow_listing_limit')), true);
?>
</div>


<?php
}else{
	if($view_type == 'po'){
		if($project_type == ''){
			$msg = $this->config->item('user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_message_po_view');
		}
		else if($project_type == 'fulltime'){
			$msg = $this->config->item('user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_fulltime_project_message_po_view');
		}else{
			$msg = $this->config->item('user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_project_message_po_view');
		}
	}
	if($view_type == 'sp'){
		if($project_type == ''){
			$msg = $this->config->item('user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_message_sp_view');
		}
		else if($project_type == 'fulltime'){
			$msg = $this->config->item('user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_fulltime_project_message_sp_view');
		}else{
			$msg = $this->config->item('user_projects_payments_overview_page_no_rejected_requested_escrow_payment_creation_project_message_sp_view');
		}
	}
	echo '<div class="default_blank_message">'.$msg.'</div>';
}
?>	