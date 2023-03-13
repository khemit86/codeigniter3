<?php 
$user = $this->session->userdata ('user');
?>
<div class="activeDispute">
	<div>
		<?php
		if($active_disputes_listing_project_data_count >0){
			$total_active_disputes = count($active_disputes_listing_project_data);
			$record_counter = 1;
			foreach($active_disputes_listing_project_data as $dispute_listing_data_key=>$dispute_listing_data_value){
				if($record_counter == $total_active_disputes){
					$last_record_class_border_full_width = 'border_full_width';
				}
			$dispute_initiator_name = $dispute_listing_data_value['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $dispute_listing_data_value['first_name'] . ' ' . $dispute_listing_data_value['last_name'] : $dispute_listing_data_value['company_name'];
			
			if($dispute_listing_data_value['project_type'] == 'fixed'){
				$admin_arbitration_value = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
			}	
			if($dispute_listing_data_value['project_type'] == 'hourly'){
				$admin_arbitration_value = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
			}
			if($dispute_listing_data_value['project_type'] == 'fulltime'){
				$admin_arbitration_value = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
			}
		?>
			<div class="projTitle <?php echo $last_record_class_border_full_width; ?>">
				<h6><b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_disputes_management_page_dispute_id_txt'); ?></b><a class="default_project_title_link" target="_blank" href="<?php echo base_url() . $this->config->item('project_dispute_details_page_url') . "?id=" . $dispute_listing_data_value['dispute_reference_id']; ?>"><?php echo $dispute_listing_data_value['dispute_reference_id']; ?></a></h6>
				
				<h6><b class="default_black_bold_medium"><?php echo ($dispute_listing_data_value['project_type'] == 'fulltime')?$this->config->item('user_projects_disputes_management_page_fulltime_project_title_txt'):$this->config->item('user_projects_disputes_management_page_project_title_txt'); ?></b><a class="default_project_title_link" target="_blank" href="<?php echo base_url() . $this->config->item('project_detail_page_url') . "?id=" . $dispute_listing_data_value['project_id']; ?>"><?php echo htmlspecialchars(trim($dispute_listing_data_value['project_title']), ENT_QUOTES); ?></a></h6>
				<?php 
				/* <p class="default_black_regular_medium"><b class="default_black_bold_medium">Description:</b><?php echo $dispute_message_initiation_phase['description']; ?></p> */ ?>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-12">
						<label>
							<div>
								<b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_disputes_management_page_disputed_amount_txt'); ?></b><span class="default_black_regular_medium touch_line_break"><?php echo str_replace(".00","",number_format($dispute_listing_data_value['disputed_amount'],  2, '.', ' '))." ".CURRENCY; ?></span>
							</div></label><?php
						if($user[0]->user_id == $dispute_listing_data_value['project_owner_id'] ){
						?><label><div><b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_disputes_management_page_total_related_service_fees_txt'); ?></b><span class="default_black_regular_medium touch_line_break"><?php echo str_replace(".00","",number_format($dispute_listing_data_value['disputed_service_fees'],  2, '.', ' '))." ".CURRENCY; ?></span>
							</div></label>
						<?php
						}
						?><br>
						<?php
						if($user[0]->user_id != $dispute_listing_data_value['dispute_initiated_by_user_id'] ){
						?><label>
							<div>
								<b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_disputes_management_page_dispute_initiated_by_txt'); ?></b><span class="default_black_regular_medium word_break_only"><?php echo $dispute_initiator_name; ?></span>
							</div></label>
						<?php
						}
						?><label>
							<div>
								<b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_disputes_management_page_dispute_start_date'); ?></b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT,strtotime($dispute_listing_data_value['dispute_start_date'])); ?></span>
							</div></label><?php
						if($dispute_listing_data_value['dispute_status'] == 'active' && strtotime($dispute_listing_data_value['dispute_negotiation_end_date']) > time()){
						?><label>
							<div>
								<b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_disputes_management_page_user_negotiation_phase_txt'); ?></b>
							</div></label>
						<?php
						}
						?><label>
							<div>
								<b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_disputes_management_page_negotiation_stage_end_date_txt'); ?></b><span class="default_black_regular_medium"><?php echo date(DATE_TIME_FORMAT,strtotime($dispute_listing_data_value['dispute_negotiation_end_date'])); ?></span>
							</div></label><?php 
						if(($dispute_listing_data_value['dispute_status'] == 'under_admin_review') || (strtotime($dispute_listing_data_value['dispute_negotiation_end_date']) < time() && $dispute_listing_data_value['disputed_amount'] >= $admin_arbitration_value) ){ ?><label>
							<div>
								<b class="default_black_bold_medium"><?php echo $this->config->item('user_projects_disputes_management_page_admin_moderation_phase_txt'); ?></b>
							</div></label>
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
		
		<div class="paging_section">
		<?php
			echo $this->load->view('projects_disputes/user_projects_disputes_management_page_paging',array('disputes_listing_project_data_count'=>$active_disputes_listing_project_data_count,'generate_pagination_links_user_projects_disputes_management'=>$generate_pagination_links_user_projects_disputes_management,'disputes_listing_project_data_paging_limit'=>$this->config->item('user_projects_disputes_management_page_active_disputes_listing_limit')), true);
		?>
		</div>
		
		<?php	
			
		}else{
		?>
		<div class="default_blank_message"><?php echo $this->config->item('user_projects_disputes_management_page_no_active_disputes_message'); ?></div>
		<?php
		}		
		?>
		<!--
		<div class="projTitle">
			<h6><b class="default_black_bold_medium">Project title:</b><a class="default_project_title_link" href="#">The standard chunk of Lorem Ipsum used</a></h6>
			<p class="default_black_regular_medium"><b class="default_black_bold_medium">Description:</b>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making.Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making.</p>
			<h6 class="cProject"><b>Pending your Answer(6.12.2018 14:32:28)</b></h6>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-12">
					<label>
						<div>
							<b class="default_black_bold_medium">Dispute Initiate by:</b><span class="default_black_regular_medium">catalin</span>
						</div>
					</label>
					<label>
						<div>
							<b class="default_black_bold_medium">Dispute Start:</b><span class="default_black_regular_medium">06.12.2018 14:32:28</span>
						</div>
					</label>
					<label>
						<div>
							<b class="default_black_bold_medium">Dispute End:</b><span class="default_black_regular_medium">06.12.2018 14:32:28</span>
						</div>
					</label>
					<label>
						<div>
							<b class="default_black_bold_medium">Dispute Amount:</b><span class="default_black_regular_medium">500.00 Kč</span>
						</div>
					</label>
				</div>
			</div>
		</div>
		<div class="projTitle">
			<h6><b class="default_black_bold_medium">Project title:</b><a class="default_project_title_link" href="#">The standard chunk of Lorem Ipsum used</a></h6>
			<p class="default_black_regular_medium"><b class="default_black_bold_medium">Description:</b>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making</p>
			<h6 class="cProject"><b>Pending your answer to Catalin(6.12.2018 14:32:28)</b></h6>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-12">
					<label>
						<div>
							<b class="default_black_bold_medium">Dispute Initiate by:</b><span class="default_black_regular_medium">You</span>
						</div>
					</label>
					<label>
						<div>
							<b class="default_black_bold_medium">Dispute Start:</b><span class="default_black_regular_medium">06.12.2018 14:32:28</span>
						</div>
					</label>
					<label>
						<div>
							<b class="default_black_bold_medium">Dispute End:</b><span class="default_black_regular_medium">06.12.2018 14:32:28</span>
						</div>
					</label>
					<label>
						<div>
							<b class="default_black_bold_medium">Dispute Amount:</b><span class="default_black_regular_medium">500.00 Kč</span>
						</div>
					</label>
				</div>
			</div>
		</div>
		<div class="projTitle">
			<h6><b class="default_black_bold_medium">Project title:</b><a class="default_project_title_link" href="#">The standard chunk of Lorem Ipsum used</a></h6>
			<p class="default_black_regular_medium"><b class="default_black_bold_medium">Description:</b>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making</p>
			<p class="default_black_regular_medium"><b class="default_black_bold_medium">Reason:</b>Parties agreement not reached.Expected admin decision time 21.11.2018</p>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-12">
					<label>
						<div>
							<b class="default_black_bold_medium adMed">Admin Mediation</b>
						</div>
					</label>
					<label>
						<div>
							<b class="default_black_bold_medium">Dispute Start:</b><span class="default_black_regular_medium">06.12.2018 14:32:28</span>
						</div>
					</label>
					<label>
						<div>
							<b class="default_black_bold_medium">Dispute End:</b><span class="default_black_regular_medium">06.12.2018 14:32:28</span>
						</div>
					</label>
					<label>
						<div>
							<b class="default_black_bold_medium">Dispute Amount:</b><span class="default_black_regular_medium">500.00 Kč</span>
						</div>
					</label>
				</div>
			</div>
		</div> -->
	</div>
	
	<?php
	/* <div class="paging_section">
	<?php
		echo $this->load->view('projects_disputes/user_projects_disputes_management_page_paging',array('disputes_listing_project_data_count'=>$active_disputes_listing_project_data_count,'generate_pagination_links_user_projects_disputes_management'=>$generate_pagination_links_user_projects_disputes_management,'disputes_listing_project_data_paging_limit'=>$this->config->item('user_projects_disputes_management_page_active_disputes_listing_limit')), true);
	?>
	</div> */
	?>
	<!--
	<div class="pagnOnly">
		<div class="row">
			<div class="col-md-7 col-sm-7 col-12">
				<div class="pageOf">
					<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page">10</span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec">15</span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
				</div>
			</div>
			<div class="col-md-5 col-sm-5 col-12">
				<div class="modePage"><ul class="pagination"><li class="page-item"><a href="#" class="page-link" rel="prev"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li><li class="page-item"><a href="#" class="page-link" rel="start">1</a></li><li class="active"><a class="page-link" href="#">2</a></li><li class="page-item"><a href="#" class="page-link">3</a></li><li class="page-item"><a href="#" class="page-link" rel="next"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li></ul></div>
			</div>
		</div>
	</div>-->
</div>