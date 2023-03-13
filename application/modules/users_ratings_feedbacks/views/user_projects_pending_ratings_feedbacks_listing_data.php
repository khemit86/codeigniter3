<?php
$user = $this->session->userdata ('user');
$user_id = $user[0]->user_id;
?>
<!-- Projects Feedback Start -->
<div class="whenFeedback">
	<div class="default_page_heading" style="display:<?php echo $projects_pending_ratings_feedbacks_count > 0 ? 'block' : 'none' ?>">
		<h4><?php echo $this->config->item('user_projects_pending_ratings_feedbacks_page_headline_title'); ?></h4>
		<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
	</div>
	<!--
	<div class="invoiceInfo">
		<p class="bookman_font_regular">Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum</p>
	</div>
	-->
	<div class="proFback">
	<!-- Projects Feedback (No Projects Feedback Listing) Start -->
		<div class="">
			<div class="initialViewNorecord" style="display:<?php echo $projects_pending_ratings_feedbacks_count == 0 ? 'block' : 'none' ?>"><h4><?php echo $this->config->item('user_projects_pending_ratings_feedbacks_page_no_project_available_message'); ?></h4></div>
		</div>
	<!-- Projects Feedback (No Projects Feedback Listing) End -->
	<?php
		if($projects_pending_ratings_feedbacks_count > 0){	
	?>
		<div class="adp">		
			<div class="ivOnlyHead">
					<div class="row">
							<div class="col-md-6 col-sm-6 col-12">
								<label class="default_black_bold_medium"><?php echo ($projects_pending_ratings_feedbacks_count==1)?$this->config->item('user_projects_pending_ratings_feedbacks_page_project_name_txt'):$this->config->item('user_projects_pending_ratings_feedbacks_page_projects_names_txt'); ?></label>
							</div>
							<div class="col-md-4 col-sm-4 col-12">
								<label class="default_black_bold_medium"><?php echo ($projects_pending_ratings_feedbacks_count==1)?$this->config->item('user_projects_pending_ratings_feedbacks_page_to_user_txt'):$this->config->item('user_projects_pending_ratings_feedbacks_page_to_users_txt'); ?></label>
							</div>
							<div class="col-md-2 col-sm-2 col-12"></div>
					</div>
				</div>
				<div class="pending_feedback_list_section">
				<?php
				$record_counter = 1;
				$total_records = count($projects_pending_ratings_feedbacks_data);
				foreach($projects_pending_ratings_feedbacks_data as $key=>$value){
					$project_type = $value['project_type'];
					$view_type = '';
					$last_record_class_remove_border_bottom = '';
					if($record_counter == $total_records){
						$last_record_class_remove_border_bottom = 'inOnlylast';
					}
					
					if($user_id == $value['po_user_id']){
						$name = (($value['sp_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['sp_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['sp_is_authorized_physical_person'] == 'Y')) ? $value['sp_first_name'] . ' ' . $value['sp_last_name'] : $value['sp_company_name'];
						$view_type = 'po';
					}if($user_id == $value['sp_user_id']){
						$name = (($value['po_account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['po_account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['po_is_authorized_physical_person'] == 'Y')) ? $value['po_first_name'] . ' ' . $value['po_last_name'] : $value['po_company_name'];
						$view_type = 'sp';
					}
					$po_id = $value['po_id'];
					$sp_id = $value['sp_id'];
					if($value['project_type'] == 'fixed_budget'){
						$project_type = 'fixed';
					}
					if($value['project_type'] == 'hourly_rate'){
						$project_type = 'hourly';
					}
					
				?>
				<div class="ivOnly" id="<?php echo "row_".$value['id']; ?>">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-12 projectNameUser">
							<label class="default_project_title"><a class="default_project_title_link" target="_blank" href="<?php echo base_url() . $this->config->item('project_detail_page_url') . "?id=" . $value['project_id']; ?>"><?php echo htmlspecialchars($value['project_title'], ENT_QUOTES); ?></a></label>
						</div>
						<div class="col-md-4 col-sm-4 col-12 toUser">
							<label class="default_black_regular_medium"><span class="toUserRes default_black_bold_medium"><?php echo $this->config->item('user_projects_pending_ratings_feedbacks_page_to_user_txt'); ?></span><?php //$name = (($value['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['is_authorized_physical_person'] == 'Y')) ? $value['first_name'] . ' ' . $value['last_name'] : $value['company_name']; echo $name;?><?php  echo $name; ?></label>
						</div>
						<div class="col-md-2 col-sm-2 col-12 rateUser">
							<?php
							/* if($value['view_type'] == 'po'){
								$po_id = $value['given_by'];
								$sp_id = $value['user_id'];
							}
							if($value['view_type'] == 'sp'){
								$po_id = $value['user_id'];
								$sp_id = $value['given_by'];
							
							} */
							
							?>
							<!--<button type="button" class="btn default_btn green_btn ratings_feedbacks" data-toggle="modal" data-view-type="<?php echo $value['view_type'] ?>" data-project-type="<?php echo $value['project_type'] ?>" data-section-name="completed" data-section-id="<?php echo $value['id']; ?>" data-project-id="<?php echo $value['project_id']; ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" ><?php echo $this->config->item('user_projects_pending_ratings_feedbacks_page_rate_button_txt');?></button>-->
							<button type="button" class="btn default_btn green_btn ratings_feedbacks" data-toggle="modal" data-view-type="<?php echo $view_type; ?>" data-project-type="<?php echo $project_type ?>" data-section-name="completed" data-section-id="<?php echo $value['id']; ?>" data-project-id="<?php echo $value['project_id']; ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id); ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id); ?>" ><?php echo $this->config->item('user_projects_pending_ratings_feedbacks_page_rate_button_txt');?></button>
						</div>
					</div>
				</div>
				<?php
					$record_counter++;
				}
				?>
				</div>
				<div class="paging_section">
				<?php
				if($projects_pending_ratings_feedbacks_count > 0){	
				?>	
				<!-- Pagination Start -->
				<div class="pagnOnly radio_bttmBdr">
					<div class="row">
					
						<?php
						$manage_paging_width_class = "col-md-12 col-sm-12"; 
						if(!empty($projects_pending_ratings_feedbacks_pagination_links)){
						$manage_paging_width_class = "col-md-7 col-sm-7"; 
						}
						?>
					
					
						<div class="<?php echo $manage_paging_width_class; ?> col-12">
							<?php
								$rec_per_page = ($projects_pending_ratings_feedbacks_count > $this->config->item('user_projects_pending_ratings_feedbacks_listing_limit')) ? $this->config->item('user_projects_pending_ratings_feedbacks_listing_limit') : $projects_pending_ratings_feedbacks_count;
								?>
							<div class="pageOf">
								<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $projects_pending_ratings_feedbacks_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
							</div>
						</div>
						<?php
						if(!empty($projects_pending_ratings_feedbacks_pagination_links)){
						?>
						<div class="col-md-5 col-sm-5 col-12">
							<div class="modePage">
								<?php echo $projects_pending_ratings_feedbacks_pagination_links; ?>
							</div>
						</div>
						<?php
						}
						?>
					</div>
				</div>
				<!-- Pagination End -->	
				<?php
				}	
				?>
				</div>

		</div>
		<?php
		}	
		?>
	</div>
</div>
<!-- Projects Feedback Start -->
	