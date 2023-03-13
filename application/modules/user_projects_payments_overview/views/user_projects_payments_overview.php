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
        <div id="content" class="user_projects_payments_overview_page body_distance_adjust">
			<div class="etSecond_step" id="work_experience_listing_data" style="display:block;">
				<!-- Profile Management Text Start -->
				<div class="default_page_heading">
					<h4><div><?php echo $this->config->item('user_projects_payments_overview_page_headline_title'); ?></div></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
				<!-- contact requests Start -->
				
				<!-- Checkbox Start -->
				<div class="default_checkbox_button two_checkbox">
					<span class="singleLine_chkBtn">
						<input type="checkbox" id="project_owner" name="contacts_management_checkbox" value="project_owner" class="chk-btn project_detail_tab" data-target="project_owner_container">
						<label class="singleLine_radioBtn" for="project_owner">
							<span><?php echo $this->config->item('user_projects_payments_overview_page_project_owner'); ?></span>
						</label>
					</span>
					<span class="singleLine_chkBtn">
						<input type="checkbox" id="service_provider" name="contacts_management_checkbox" value="service_provider" class="chk-btn project_detail_tab" data-target="service_provider_container">
						<label class="singleLine_radioBtn" for="service_provider">
							<span><?php echo $this->config->item('user_projects_payments_overview_page_checkbox_service_provider'); ?></span>
						</label>
					</span>
				</div>
				<!-- Checkbox Start -->
				<!-- Checkbox Content Start -->
				<div class="cmFieldText">
					<div class="cmField d-none" id="project_owner_container">
						<?php
						//echo $this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_po_view',array(), true);
						?>
					</div>
					<div class="cmField d-none" id="service_provider_container">
						<?php
						//echo $this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_sp_view',array(), true);
						?>
					</div>
				</div>
				<!-- Checkbox Content End -->
			
        </div>
        <!-- Right Section End -->
    </div>
    <!-- Middle Section End -->
	
</div>
</div>
<script>
var uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
</script>
<script src="<?= ASSETS ?>js/modules/user_projects_payments_overview.js"></script>