<?php
$user = $this->session->userdata('user');	
?>	
<div class="dashTop">	
	<!-- Menu Icon on Responsive View Start -->	
	<?php echo $this->load->view('user_left_menu_mobile.php'); ?>
<!-- Menu Icon on Responsive View End -->
	<div class="wrapper wrapper1">
		<!-- Left Menu Start -->
		<?php echo $this->load->view('user_left_nav.php'); ?>
		<!-- Left Menu End -->
		<!-- Right Section Start -->
		<div id="content">
			<div class="rightSec">
				<!-- Middle Section Start -->
				<!-- <div class="accMgm">
					<h4><?php //echo $this->config->item('myprojects_headline_title'); ?></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div> -->
				<div class="myProject_rightAlign pojDet">
					<div class="bidFreeNo">
					   <!------myproject section start---->
							<div class="default_block_header_transparent nBorder">
								<div class="transparent abc"><?php echo $this->config->item('my_projects_section_heading'); ?></div>
								<!-- div class="mP">My Projects :<sup>______</sup></div -->
								<div class="myproTabs transparent_body">
									<div class="default_radio_button myproRadio">
										<section>
                                            <div>
												<input type="radio" id="inlineRadio1" name="inlineRadioOptions" value="1" data-tab-type="po" class="user_type" checked>
												<label for="inlineRadio1" style="pointer-events:none" >
													<span><?php echo $this->config->item('my_projects_section_as_employer'); ?></span>
												</label>
											</div><div>
												<input type="radio" data-tab-type="sp" id="inlineRadio2" name="inlineRadioOptions" value="2" class="user_type">
												<label for="inlineRadio2">
													<span><?php echo $this->config->item('my_projects_section_as_service_provider'); ?></span>
												</label>
											</div>
											
										</section>
									</div>
									
									<div class="myProDsk" id="employer_view_div" style="display: block;">
										<nav class="navbar navbar-expand-lg navbar-dark">
											<span class="navbar-brand"></span>
											<button id="myProjecttab1" class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#homeNav5" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
											<!-- <span class="navbar-toggler-icon"></span> -->
											<i class="fas fa-bars"></i>
											</button>
											<div class="collapse navbar-collapse" id="homeNav5">
												<?php
												if($user_detail['current_membership_plan_id'] == 1){			
													$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
													$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');
												
													$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
													$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
												}
												if($user_detail['current_membership_plan_id'] == 4){
													$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
													$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');
												
													$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
													$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
												}
												?>
												<ul class="nav nav-tabs" id="myTab" role="tablist">
													<li class="nav-item">
														
														<a  class="nav-link active my_projects_tab" data-tab-type="draft" id="draft-tabMyProjects" data-toggle="tab" data-target="#draftMyProjects" role="tab" aria-controls="draftMyProjects" aria-selected="true"><?php echo $this->config->item('my_projects_po_view_draft_tab_heading'); ?> (<?php echo $draft_cnt."/".($user_memebership_max_number_of_draft_projects - $draft_cnt) ; ?><?php echo ' + '.$fulltime_draft_cnt.'/'.($user_memebership_max_number_of_draft_fulltime_projects - $fulltime_draft_cnt); ?>)</a>
													</li>
													
													<li class="nav-item">
														<a data-tab-type="awaitingModeration" class="nav-link my_projects_tab" id="awaitingModeration-tabMyProjects" data-toggle="tab" data-target="#awaitingModerationMyProjects" role="tab" aria-controls="awaitingModerationMyProjects" aria-selected="true"><?php echo $this->config->item('my_projects_po_view_awaiting_moderation_tab_heading'); ?> (<?php echo ($awaiting_moderation_project_count - $fulltime_awaiting_moderation_project_count); ?> + <?php echo $fulltime_awaiting_moderation_project_count; ?>)</a>
													</li>
													<li class="nav-item">
														<a  class="nav-link my_projects_tab" id="openBid-tabMyProjects" data-tab-type="openBid" data-toggle="tab" data-target="#openBidMyProjects" role="tab" aria-controls="openBidMyProjects" aria-selected="false"><?php echo $this->config->item('my_projects_po_view_open_for_bidding_tab_heading'); ?> (<?php echo ($open_bidding_project_count - $fulltime_open_project_count )."/".($user_memebership_max_number_of_open_for_bidding_projects-get_user_open_projects_count($user[0]->user_id)); ?><?php echo ' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_bidding_cnt); ?>)</a>
													</li>
													<li class="nav-item">
														<a class="nav-link my_projects_tab" id="awarded-tabMyProjects" data-tab-type="awarded" data-toggle="tab" data-target="#awardedMyProjects" role="tab" aria-controls="awardedMyProjects" aria-selected="false"><?php echo $this->config->item('my_projects_po_view_awarded_tab_heading'); ?> (<?php echo $awarded_project_count; ?>)</a>
													</li>
													<li class="nav-item">
														<a  class="nav-link my_projects_tab" id="workPro-tabMyProjects" data-tab-type="workPro"  data-toggle="tab" data-target="#workProMyProjects" role="tab" aria-controls="workProMyProjects" aria-selected="false"><?php echo $this->config->item('my_projects_po_view_work_in_progress_tab_heading'); ?> (<?php echo $in_progress_project_count; ?>)</a>
													</li>
													<li class="nav-item">
														<a class="nav-link my_projects_tab" id="completed-tabMyProjects" data-tab-type="completed" data-toggle="tab" data-target="#completedMyProjects" role="tab" aria-controls="completedMyProjects" aria-selected="false"><?php echo $this->config->item('my_projects_po_view_completed_tab_heading'); ?> (<?php echo $completed_project_count; ?>)</a>
													</li>
														<li class="nav-item">
														<a class="nav-link my_projects_tab" id="incomplete-tabMyProjects" data-tab-type="incomplete" data-toggle="tab" data-target="#incompleteMyProjects" role="tab" aria-controls="incompleteMyProjects" aria-selected="false"><?php echo $this->config->item('my_projects_po_view_work_incomplete_tab_heading'); ?> (<?php echo $in_complete_project_count; ?>)</a>
													</li>
													
													<li class="nav-item">
														<a class="nav-link my_projects_tab" id="expired-tabMyProjects" data-tab-type="expired" data-toggle="tab" data-target="#expiredMyProjects" role="tab" aria-controls="expiredMyProjects" aria-selected="false"><?php echo $this->config->item('my_projects_po_view_expired_tab_heading'); ?> (<?php echo ($expired_project_count - $fulltime_expired_project_count).' + '.$fulltime_expired_project_count; ?>)</a>
													</li>
													<li class="nav-item">
														<a class="nav-link my_projects_tab" id="cancelled-tabMyProjects" data-tab-type="cancelled" data-toggle="tab" data-target="#cancelledMyProjects" role="tab" aria-controls="closed" aria-selected="false"><?php echo $this->config->item('my_projects_po_view_cancelled_tab_heading'); ?> (<?php echo ($cancelled_project_count - $fulltime_cancelled_project_count).' + '.$fulltime_cancelled_project_count; ?>)</a>
													</li>
												</ul>
											</div>
										</nav>
									</div>
									
									<div class="myProDsk" id="freelancer_view_div" style="display: none;">
										<nav class="navbar navbar-expand-lg navbar-dark">
											<span class="navbar-brand"></span>
											<button id="myProjecttab2" class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#homeNav6" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
											<!-- <span class="navbar-toggler-icon"></span> -->
											<i class="fas fa-bars"></i>
											</button>
											<div class="collapse navbar-collapse" id="homeNav6">
												<ul class="nav nav-tabs" id="myTab1" role="tablist">
													<li class="nav-item">
														<a  class="nav-link active my_projects_tab" id="active-bids-tabMyProjects" data-toggle="tab" data-target="#active_bidsMyProjects" role="tab" aria-controls="active_bidsMyProjects" aria-selected="true" data-tab-type="active_bids"><?php echo $this->config->item('my_projects_sp_view_active_bids_tab_heading'); ?> (<?php echo ($active_bids_project_count - $fulltime_active_bids_project_count).' + '.$fulltime_active_bids_project_count; ?>)</a>
													</li>
													<li class="nav-item">
														<a class="nav-link my_projects_tab" id="awarded-bids-tabMyProjects" data-toggle="tab" data-target="#awarded_bidsMyProjects" role="tab" aria-controls="awarded_bidsMyProjects" aria-selected="false" data-tab-type="awarded_bids" ><?php echo $this->config->item('my_projects_sp_view_awarded_bids_tab_heading'); ?> (<?php echo ($awarded_bids_project_count - $fulltime_awarded_bids_project_count).' + '.$fulltime_awarded_bids_project_count; ?>)</a>
													</li>
													<li class="nav-item">
														<a  class="nav-link my_projects_tab" id="in-progress-bids-work-tabMyProjects" data-toggle="tab" data-target="#in_progress_bidsMyProjects" role="tab" aria-controls="in_progress_bidsMyProjects" aria-selected="false" data-tab-type="in_progress_work"><?php echo $this->config->item('my_projects_sp_view_projects_in_progress_tab_heading'); ?> (<?php echo $in_progress_bids_project_count; ?>)</a>
													</li>
													<li class="nav-item">
														<a  class="nav-link my_projects_tab" id="completed_work-tabMyProjects" data-toggle="tab" data-target="#completed_workMyProjects" role="tab" aria-controls="completed_workMyProjects" aria-selected="false" data-tab-type="completed_work"><?php echo $this->config->item('my_projects_sp_view_completed_tab_heading'); ?> (<?php echo $completed_bids_project_count; ?>)</a>
													</li>
													
													<li class="nav-item">
														<a class="nav-link my_projects_tab" id="hired-tabMyProjects" data-toggle="tab" data-target="#hiredMyProjects" role="tab" aria-controls="hiredMyProjects" aria-selected="false" data-tab-type="hired"><?php echo $this->config->item('my_projects_employee_view_fulltime_projects_hired_tab_heading'); ?> (<?php echo $hired_project_count; ?>)</a>
													</li>
													<li class="nav-item">
														<a  class="nav-link my_projects_tab" id="in-complete-bids-work-tabMyProjects" data-toggle="tab" data-target="#in_complete_bidsMyProjects" role="tab" aria-controls="in_complete_bidsMyProjects" aria-selected="false" data-tab-type="in_complete_work"><?php echo $this->config->item('my_projects_sp_view_projects_incomplete_tab_heading'); ?> (<?php echo $in_complete_bids_project_count; ?>)</a>
													</li>
												</ul>
											</div>
										</nav>
									</div>
									<div class="tab-content" id="employer_tab_div" style="display:block">
										<div class="tab-pane fade workOnly show active" id="draftMyProjects" role="tabpanel" aria-labelledby="draft-tabMyProjects">
										<!-- Loader Section Start -->
										<!--<div class="fix-loader">
												<div id="loading">
													<div id="nlpt"></div>
													<div class="msg">nahrávání...</div>
												</div>
											</div>-->
											<!-- Loader Section End -->
											<?php
												echo $this->load->view('projects/po_draft_projects_listing_my_projects', array('draft_project_data'=>$draft_project_data,'draft_project_count'=>$draft_project_count,'draft_pagination_links'=>$draft_pagination_links,'page_type'=>'my_projects','standard_valid_time_arr'=>$standard_valid_time_arr,'draft_cnt'=>$draft_cnt,'open_bidding_cnt'=>$open_bidding_cnt,'po_max_draft_projects_number'=>$user_memebership_max_number_of_draft_projects,'po_max_open_projects_number'=>$user_memebership_max_number_of_open_for_bidding_projects), true); 
											?>
										</div>
										<div class="tab-pane fade workOnly" id="awaitingModerationMyProjects" role="tabpanel" aria-labelledby="awaitingModeration-tabMyProjects">
											<?php
											//echo $this->load->view('projects/po_awaiting_moderation_projects_listing_my_projects', array('awaiting_moderation_project_data'=>$awaiting_moderation_project_data,'awaiting_moderation_project_count'=>$awaiting_moderation_project_count,'awaiting_moderation_pagination_links'=>$awaiting_moderation_pagination_links), true); 
											?>
										</div>
										
										<div class="tab-pane fade workOnly" id="openBidMyProjects" role="tabpanel" aria-labelledby="openBid-tabMyProjects">	
											<?php
											//echo $this->load->view('projects/po_open_bidding_projects_listing_my_projects', array('open_bidding_project_data'=>$open_bidding_project_data,'open_bidding_project_count'=>$open_bidding_project_count,'open_bidding_pagination_links'=>$open_bidding_pagination_links), true); 
											?>
										</div>
											<div class="clearfix"></div>
										
										<div class="tab-pane fade workOnly" id="awardedMyProjects" role="tabpanel" aria-labelledby="awarded-tabMyProjects">												
											<?php
											//echo $this->load->view('projects/po_awarded_projects_listing_my_projects', array('awarded_project_data'=>$awarded_project_data,'awarded_project_count'=>$awarded_project_count,'awarded_pagination_links'=>$awarded_pagination_links), true); 
											?>									
										</div>
										
										<div class="tab-pane fade workOnly" id="workProMyProjects" role="tabpanel" aria-labelledby="workPro-tabMyProjects">												
											<?php
											//echo $this->load->view('projects/po_in_progress_projects_listing_my_projects', array('in_progress_project_data'=>$in_progress_project_data,'in_progress_project_count'=>$in_progress_project_count,'in_progress_pagination_links'=>$in_progress_pagination_links), true); 
											?>		
											
										</div>

										<div class="tab-pane fade workOnly" id="completedMyProjects" role="tabpanel" aria-labelledby="completed-tabMyProjects">		<?php
											//echo $this->load->view('projects/po_completed_projects_listing_my_projects', array('completed_project_data'=>$completed_project_data,'completed_project_count'=>$completed_project_count,'completed_pagination_links'=>$completed_pagination_links), true); 
											?>		
										</div>
										<div class="tab-pane fade workOnly" id="incompleteMyProjects" role="tabpanel" aria-labelledby="incomplete-tabMyProjects">		<?php
											//echo $this->load->view('projects/po_completed_projects_listing_my_projects', array('completed_project_data'=>$completed_project_data,'completed_project_count'=>$completed_project_count,'completed_pagination_links'=>$completed_pagination_links), true); 
											?>		
										</div>
										
										<div class="tab-pane fade workOnly" id="expiredMyProjects" role="tabpanel" aria-labelledby="expired-tabMyProjects">
											<?php
												//echo $this->load->view('projects/po_expired_projects_listing_my_projects', array('expired_project_data'=>$expired_project_data,'expired_project_count'=>$expired_project_count,'expired_pagination_links'=>$expired_pagination_links), true); 
											?>
										</div>
										
										<div class="tab-pane fade workOnly" id="cancelledMyProjects" role="tabpanel" aria-labelledby="closed-tab"><?php
												//echo $this->load->view('projects/po_cancelled_projects_listing_my_projects', array('cancelled_project_data'=>$cancelled_project_data,'cancelled_project_count'=>$cancelled_project_count,'cancelled_pagination_links'=>$cancelled_pagination_links), true); 
											?>
										</div>													
									</div>
									
									<div class="tab-content" id="freelancer_tab_div" style="display: none;">
										<div class="tab-pane fade show active workOnly" id="active_bidsMyProjects" role="tabpanel" aria-labelledby="active-bids-tabMyProjects">
											<?php
												/* echo $this->load->view('projects/po_cancelled_projects_listing_my_projects', array('cancelled_project_data'=>$cancelled_project_data,'cancelled_project_count'=>$cancelled_project_count,'cancelled_pagination_links'=>$cancelled_pagination_links), true);  */
												
											//echo $this->load->view('bidding/sp_active_bids_listing_my_projects', array('active_bids_project_data'=>$active_bids_project_data,'active_bids_project_count'=>$active_bids_project_count,'active_bids_pagination_links'=>$active_bids_pagination_links,'page_type'=>'my_projects'), true); 
												
												
											?>	
										</div>
										
										<div class="tab-pane fade workOnly" id="awarded_bidsMyProjects" role="tabpanel" aria-labelledby="awarded-bids-tabMyProjects"><?php
										//echo $this->load->view('bidding/sp_awarded_bids_listing_my_projects', array('awarded_bids_project_data'=>$awarded_bids_project_data,'awarded_bids_project_count'=>$awarded_bids_project_count,'page_type'=>'my_projects'), true); 
										?>	
										
										</div>
										
										<div class="tab-pane fade workOnly" id="in_progress_bidsMyProjects" role="tabpanel" aria-labelledby="in-progress-bids-tabMyProjects">
											
										<?php
										//echo $this->load->view('bidding/sp_in_progress_bids_listing_my_projects', array('in_progress_bids_project_data'=>$in_progress_bids_project_data,'in_progress_bids_project_count'=>$in_progress_bids_project_count,'page_type'=>'my_projects'), true); 
										?>		
										</div>
										<div class="tab-pane fade workOnly" id="completed_workMyProjects" role="tabpanel" aria-labelledby="completed_work-tabMyProjects">
										<?php
										//echo $this->load->view('bidding/sp_completed_bids_listing_my_projects', array('completed_bids_project_data'=>$completed_bids_project_data,'completed_bids_project_count'=>$completed_bids_project_count,'page_type'=>'my_projects'), true); 
										?>
										</div>
										
										
										
										<div class="tab-pane fade workOnly" id="hiredMyProjects" role="tabpanel" aria-labelledby="hired-tab"></div>
										<div class="tab-pane fade workOnly" id="in_complete_bidsMyProjects" role="tabpanel" aria-labelledby="in-complete-bids-tabMyProjects">
										</div>
										
									</div>
								</div>
							</div>
						
						<!------myproject section end------>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal Start for edit upgrade-->
<div class="modal fade" id="editUpgradeModalCenter" tabindex="-1" role="dialog" aria-labelledby="editUpgradeModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content etModal">
			<div class="modal-header popup_header popup_header_without_text">
				<h4 class="modal-title popup_header_title" id="upgrade_prolong_popup_heading"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body most-project" id="edit_upgrade_popup_body">
				
			</div>
		</div>
	</div>
</div>
<!-- Modal End -->
<!-- Modal Start for edit upgrade-->
<div class="modal fade" id="cancelProjectModal" tabindex="-1" role="dialog" aria-labelledby="cancelProjectModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content etModal">
			<div class="modal-header popup_header popup_header_without_text">
				<h4 class="modal-title popup_header_title" id="cancel_project_modal_title"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="popup_body_semibold_title" id="cancel_project_modal_body">
				</div>
				<div class="disclaimer default_disclaimer_text disclaimer_separator">
					<div>
						<label class="default_checkbox">
							<input type="checkbox" id="cancel_project_checkbox_po">
							<span class="checkmark"></span>
						<span id="cancel_project_disclaimer" class="popup_body_regular_checkbox_text"></span></label>
					</div>
				</div>
			</div>
			<div class="modal-footer margin_bottom15">
				<button type="button" class="btn blue_btn default_btn" data-dismiss="modal"><?php echo $this->config->item('close_btn_txt'); ?></button>					
				<button type="button" disabled style="opacity:0.65" class="btn default_btn red_btn project_cancel_button width-auto" id="cancel_project_modal_cancel_button_txt"></button>
			</div>
			<!-- <div class="modal-body most-project" id="cancel_project_modal_body">
			</div>
			<div class="modal-footer mg-bottom-10">
				<div class="row">
					<div class="col-sm-12 col-lg-12 col-xs-12">
						<button type="button" class="btn btnCancel project_cancel_button width-auto" id="cancel_project_modal_cancel_button_txt"></button>
						<button type="button" class="btn btnSave" data-dismiss="modal" id="cancel_project_modal_close_button_txt"></button>
					</div>
				</div>
			</div> -->
		</div>
	</div>
</div>
<!-- Modal End -->

<!-- Script Start -->
<!-- Bottom Div Closed Script Start -->
<script src="<?php echo JS; ?>modules/my_projects.js"></script>	
<!-- Below file hold the node functions related with my projects section (dashboard/myproject page) -->
<script src="<?php echo JS; ?>modules/my_projects_management_node.js"></script>		
<!-- Mobile View Chat Section End -->
<!-- Script Start -->
<script>
	//var post_project_page_url = '<?php echo $this->config->item('post_project_page_url') ?>';
	var edit_project_page_url = '<?php echo $this->config->item('edit_project_page_url') ?>';
	var set_redirection_edit_draft_project = '<?php echo "set_redirection_edit_draft_project" ?>';
	var my_project_paging_url = '<?php echo $this->config->item('my_projects_paging_url') ?>';
	var popup_alert_heading = "<?php echo $this->config->item('popup_alert_heading'); ?>";
	var cancel_open_for_bidding_project_modal_body = '<?php echo $this->config->item('cancel_open_for_bidding_project_modal_body') ?>';
	var cancel_open_for_bidding_fulltime_project_modal_body = '<?php echo $this->config->item('cancel_open_for_bidding_fulltime_project_modal_body') ?>';
	var cancel_expired_project_modal_body = '<?php echo $this->config->item('cancel_expired_project_modal_body') ?>';
	var cancel_expired_fulltime_project_modal_body = '<?php echo $this->config->item('cancel_expired_fulltime_project_modal_body') ?>';
	
	var cancel_disclaimer_modal_body = '<?php echo $this->config->item('user_confirmation_check_box_txt') ?>';
</script>