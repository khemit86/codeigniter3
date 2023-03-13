<?php
$user = $this->session->userdata('user');
?>	
<div class="dashTop">		
    <!-- Menu Icon on Responsive View Start -->		
    <?php echo $this->load->view('user_left_menu_mobile.php'); ?>
    <!-- Menu Icon on Responsive View End -->		
    <!-- Middle Section Start -->
    <div class="wrapper wrapper1">
        <!-- Left Menu Start -->
        <?php echo $this->load->view('user_left_nav.php'); ?>
        <!-- Left Menu End -->
        <!-- Right Section Start -->
        <div id="content" class="project_dispute_page body_distance_adjust">
			<div class="etSecond_step" id="work_experience_listing_data" style="display:block;">
				<!-- Profile Disputes Management Text Start -->
				<div class="default_page_heading">
					<h4><?php echo $this->config->item('projects_disputes_page_headline_title'); ?></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Disputes Management Text End -->				
				<!-- Profile Disputes Management Checkbox Start -->
				<div class="default_checkbox_button dispute_management_checkbox two_checkbox">
					<span class="singleLine_chkBtn">
						<input type="checkbox" id="project_owner" name="contacts_management_checkbox" value="project_owner" class="chk-btn project_detail_tab po_sp_tab" data-target="project_owner_container">
						<label class="singleLine_radioBtn" for="project_owner">
							<span><?php echo $this->config->item('projects_disputes_page_project_owner'); ?></span>
						</label>
					</span>
					<span class="singleLine_chkBtn">
						<input type="checkbox"  id="service_provider" name="contacts_management_checkbox" value="service_provider" class="chk-btn project_detail_tab po_sp_tab" data-target="service_provider_container">
						<label class="singleLine_radioBtn" for="service_provider">
							<span><?php echo $this->config->item('projects_disputes_page_checkbox_service_provider'); ?></span>
						</label>
					</span>
				</div>
				<!-- Profile Disputes Management Checkbox End -->
				<!-- Profile Disputes Management Checkbox Content End -->
				<div class="cmFieldText">
					<!-- Project Owner Checkbox Start -->
					<div class="cmField d-none" id="project_owner_container">					
						<div class="default_radio_button radio_bttmBdr">
							<section>
								<div>
									<input type="radio" class="payments_tab dispute_tab"  id="new_dispute" name="projects_disputes_management" value="new_dispute"  data-tab-type="new_dispute">
									<label class="doubleLine_radioBtn" for="new_dispute">
										<span><?php echo $this->config->item('projects_disputes_page_project_owner_new_dispute'); ?></span>
									</label>
								</div>
								<div>
									<input type="radio" class="payments_tab dispute_tab" id="active_dispute" name="projects_disputes_management" value="active_dispute"  data-tab-type="active_dispute">
									<label class="doubleLine_radioBtn" for="active_dispute">
										<span><?php echo $this->config->item('projects_disputes_page_project_owner_active_dispute'); ?></span>
									</label>
								</div>
								<div>
									<input type="radio" class="payments_tab dispute_tab" id="close_dispute"  name="projects_disputes_management" value="close_dispute"  data-tab-type="closed_dispute">
									<label class="doubleLine_radioBtn" for="close_dispute">
										<span><?php echo $this->config->item('projects_disputes_page_project_owner_close_dispute'); ?></span>
									</label>
								</div>
							</section>
						</div>
					</div>
					<!-- Project Owner Checkbox End -->
					<!-- Service provider Checkbox Start -->
					<div class="cmField d-none" id="service_provider_container">					
						<div class="default_radio_button radio_bttmBdr">
							<section>
								<div>
									<input type="radio" class="payments_tab dispute_tab" id="new_dispute1" name="projects_disputes_management1" value="new_dispute1"  data-tab-type="new_dispute">
									<label class="doubleLine_radioBtn" for="new_dispute1">
										<span><?php echo $this->config->item('projects_disputes_page_checkbox_service_provider_new_dispute'); ?></span>
									</label>
								</div>
								<div>
									<input type="radio" class="payments_tab dispute_tab" id="active_dispute1" name="projects_disputes_management1" value="active_dispute1"  data-tab-type="active_dispute">
									<label class="doubleLine_radioBtn" for="active_dispute1">
										<span><?php echo $this->config->item('projects_disputes_page_checkbox_service_provider_active_dispute'); ?></span>
									</label>
								</div>
								<div>
									<input type="radio" class="payments_tab dispute_tab" id="close_dispute1"  name="projects_disputes_management1" value="close_dispute1"  data-tab-type="closed_dispute">
									<label class="doubleLine_radioBtn" for="close_dispute1">
										<span><?php echo $this->config->item('projects_disputes_page_checkbox_service_provider_close_dispute'); ?></span>
									</label>
								</div>
							</section>
						</div>
					</div>
					<!-- Service provider Checkbox End -->
					
					<!-- Project Owner Checkbox Content Start -->
					<div id="project_owner_tab">
						<div id="new_dispute_div" class="open_container new_dispute_container" style="display: none;"></div>
						<div id="active_dispute_div" class="open_container active_dispute_container" style="display: none;"></div>
						<div id="close_dispute_div" class="open_container closed_dispute_container" style="display: none;"></div>
					</div>
					<!-- Project Owner Checkbox Content End -->
					
					<!-- Service provider Checkbox Content Start -->
					<div id="service_provider_tab">
						<div id="new_dispute_div1" class="open_container new_dispute_container" style="display: none;"></div>
						<div id="active_dispute_div1" class="open_container active_dispute_container" style="display: none;"></div>
						<div id="close_dispute_div1" class="open_container closed_dispute_container" style="display: none;"></div>
					</div>
					<!-- Service provider Checkbox Content End -->
					
				</div>
				<!-- Profile Disputes Management Checkbox Content End -->
			</div>
			<!-- Right Section End -->
		</div>
    <!-- Middle Section End -->
	
	</div>
</div>
<script>
var session_uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
</script>
<script src="<?= ASSETS ?>js/modules/projects_disputes.js"></script>