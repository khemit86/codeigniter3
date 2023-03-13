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
        <div id="content">
			<div class="etSecond_step" id="work_experience_listing_data" style="display:block;">
				<!-- Profile Management Text Start -->
				<div class="default_page_heading">
					<h4><?php echo $this->config->item('account_management_headline_title_membership'); ?></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
				<!-- Content Start -->
				<div class="cmFieldText">
					<div id="membership_container" class="pmdonotSection cmField">
						<div class="pmFirstStep">
							<div class="memNew">
								<span class="default_black_bold">
									<?php echo $this->config->item('account_management_membership_plan_heading'); ?><small>Gold</small>
								</span><a href="<?php echo $this->config->item('membership_page_url'); ?>" class="btn blue_btn default_btn "><?php echo $this->config->item('account_management_membership_title_manage'); ?></a>
								<div class="clearfix"></div>
							</div>
						</div>								
					</div>						
				</div>
				<!-- Content End -->
			</div>
        <!-- Right Section End -->
		</div>
    <!-- Middle Section End -->
	</div>
<script src="<?= ASSETS ?>js/modules/demo_account_management.js"></script>