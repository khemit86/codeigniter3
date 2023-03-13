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
        <div id="content" class="account_management_account_overview_page body_distance_adjust">
			<div class="etSecond_step" id="work_experience_listing_data" style="display:block;">
				<!-- Account Overview Text Start -->
				<!-- <div class="default_page_heading mainPage_pageHeading_account_overview"> -->
				<div class="default_page_heading"><h4><div><?php echo $this->config->item('account_management_account_overview_page_headline_title'); ?></div></h4><span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span></div>
				<!-- Account Overview Text End -->
				
				<!-- Checkbox Start -->
				<div class="default_checkbox_button accManagement_accOverview_chkbox two_checkbox"><span class="singleLine_chkBtn"><input type="checkbox" id="account_details" name="contacts_management_checkbox" value="account_details" class="chk-btn account_overview_tab" data-target="account_details_container"><label class="singleLine_radioBtn" for="account_details"><span><?php echo $this->config->item('account_management_account_overview_page_account_details_tab'); ?></span></label></span><span class="singleLine_chkBtn"><input type="checkbox" id="membership_tab" name="contacts_management_checkbox" value="membership_tab" class="chk-btn account_overview_tab" data-target="membership_container"><label class="singleLine_radioBtn" for="membership_tab"><span><?php echo $this->config->item('account_management_account_overview_page_membership_tab'); ?></span></label></span></div>
				<!-- Checkbox End -->
				
				<!-- Content Start -->	
				<div class="cmFieldText">
					<div id="account_details_container" class="cmField d-none">
						<div class="pmdonotSection pmNoborder">
							<div class="pmAllStep accmTab">
								<?php
								$account_type_icon_class = "";
								if ($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) {
								?>
								<label class="nameLabel">
									<div class="bigNameDot"><i class="fas fa-id-card-alt"></i><span class="default_black_bold_medium"><?php echo $this->config->item('account_management_account_details_name_heading'); ?></span><small class="default_black_regular_medium"><?php echo $user_detail['first_name'] . ' ' . $user_detail['last_name']; ?></small></div></label>
								<label class="genderLabel">
									<div>
										<?php
										$gender_txt = '';
										$gender_icon_class = '';
										
										if ($user_detail['gender'] == 'M') {
											$gender_txt = $this->config->item('account_management_account_details_personal_account_type_gender_male_txt');
											$gender_icon_class = "fa-mars";
											$account_type_icon_class = "fa-male";
										} else {
											$gender_txt = $this->config->item('account_management_account_details_personal_account_type_gender_female_txt');
											$gender_icon_class = "fa-venus";
											$account_type_icon_class = "fa-female";
										}
										?>
										<i class="fas <?php echo $gender_icon_class; ?>"></i><span class="default_black_bold_medium"><?php echo $this->config->item('account_management_account_details_gender_heading'); ?></span><small class="default_black_regular_medium"><?php echo $gender_txt; ?></small>
									</div>
								</label>
								<?php
								}else{
								?>
								
								<label class="nameLabel">
									<div class="bigNameDot">
										<i class="fas fa-building"></i><span class="default_black_bold_medium"><?php echo $this->config->item('account_management_account_details_company_name_heading'); ?></span><small class="default_black_regular_medium"><?php echo $user_detail['company_name']; ?></small>
									</div>
								</label>
								<?php
								}
								?>
								<div class="accTypeLabel"><div><i class="fas <?php echo $account_type_icon_class; ?>"></i><span class="default_black_bold_medium"><?php echo $this->config->item('account_management_account_details_account_type_heading'); ?></span><small class="default_black_regular_medium"><?php
								if ($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
									echo $this->config->item('account_management_account_details_personal_account_type_txt');
								}else if ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y') {
									echo $this->config->item('account_management_account_details_company_app_account_type_txt');
								} else {
									echo $this->config->item('account_management_account_details_company_account_type_txt');
								}
								?></small></div></div>
							</div>
						</div>						
					</div>
					<div id="membership_container" class="cmField d-none">
						<div class="pmdonotSection pmNoborder">
							<div class="pmAllStep">
								<div class="memNew">
									<span class="default_black_bold_medium">
										<?php
											$user_plan_name = '';
											$plans_names = $this->config->item('membership_plans_names');
											if(!empty($plans_names) && array_key_exists($user_detail['current_membership_plan_id'], $plans_names)) {
												$user_plan_name = $plans_names[$user_detail['current_membership_plan_id']];
											}
										?>
										<?php echo $this->config->item('account_management_membership_plan_heading'); ?><small id="user_plan_name"><?php echo $user_plan_name; ?></small>
									</span><small class="memberBtn"><a href="<?php echo $this->config->item('membership_page_url'); ?>" class="btn blue_btn default_btn "><?php echo $this->config->item('account_management_membership_title_manage'); ?></a>
									</small><div class="clearfix"></div>
								</div>
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
<?php
$user = $this->session->userdata('user');	
?>	
<script>
var uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
</script>
<script src="<?= ASSETS ?>js/modules/user_account_management_account_overview.js"></script>