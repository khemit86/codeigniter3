<?php $user = $this->session->userdata('user'); ?>
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
        <div id="content" class="account_management_avatar_page body_distance_adjust">
			<div class="etSecond_step" id="work_experience_listing_data" style="display:block;">
				<!-- Profile Management Text Start -->
				<!-- <div class="default_page_heading mainPage_pageHeading_avatar"> -->
				<div class="default_page_heading">
					<h4><div><?php echo $this->config->item('account_management_avatar_page_headline_title'); ?></div></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
				<!-- Content Start -->	
				<?php
					if($user_avatar_exist_status) {
						
						$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
					} else {
						
						if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
							if($user_detail['gender'] == 'M'){
								$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
							}if($user_detail['gender'] == 'F'){
							   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
							}
						} else if($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y'){
							if($user_detail['gender'] == 'M'){
								$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
							}if($user_detail['gender'] == 'F'){
							   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
							}
						}else {
							$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
						}
					}
				?>
				<div class="cmFieldText">
					<div id="profile_photo_container" class="pmdonotSection cmField pmNoborder">
						<div class="pmAllStep">
						
							<div class="amAvatarImage"  >
								<div class="default_avatar_image"  id="avatar_image_container" style="background-image: url('<?php echo $user_profile_picture ?>'); ">
								</div>
								<div class="amBtn">
									<input type="file" accept="<?php echo $this->config->item('pictures_allowed_extensions_input_file_type'); ?>" style="display:none;"  class="avatar_input">
									<button type="button" class="btn default_btn blue_btn upload_avatar" id="upload_avatar" style="<?php echo  $user_avatar_exist_status ? 'display:none' : ''; ?>"><?php echo $this->config->item('user_profile_upload_avatar_btn_txt') ?></button><button type="button" id="reset_avatar" class="btn default_btn red_btn" style="<?php echo  $user_avatar_exist_status ? '' : 'display:none' ?>"><?php echo $this->config->item('reset_btn_txt') ?></button><button type="button" class="btn default_btn green_btn" id="edit_avatar"  style="<?php echo  $user_avatar_exist_status ? '' : 'display:none'; ?>"><?php echo $this->config->item('edit_btn_txt') ?></button><button type="button" class="btn default_btn red_btn" id= "cancel_avatar" style="display:none"><?php echo $this->config->item('cancel_btn_txt') ?></button><button id="save_avatar" type="button" class="btn default_btn green_btn" style="display:none;"><?php echo $this->config->item('save_btn_txt') ?></button>
								</div>
								<div class="avtarAllow"><?php echo $this->config->item('user_profile_avatar_allowed_formats_error'); ?></div>
								<div id="avatar_error" class="error_msg required"></div>
								
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
<script>
var user_profile_avatar_upload_max_size_allocation = "<?php echo $this->config->item('user_profile_avatar_upload_max_size_allocation'); ?>";
var user_profile_avatar_allowed_file_extensions = '<?php echo $this->config->item('pictures_allowed_extensions_js'); ?>';

var user_profile_avatar_upload_max_size_error = '<?php echo $this->config->item('user_profile_avatar_upload_max_size_validation_message'); ?>';
var user_profile_avatar_allowed_file_extension_error = '<?php echo $this->config->item('user_profile_avatar_allowed_file_extension_validation_message'); ?>';

var uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
var notification_messages_timeout_interval = "<?php echo $this->config->item('notification_messages_timeout_interval'); ?>";

var user_profile_upload_avatar_btn_txt = '<?php echo $this->config->item('user_profile_upload_avatar_btn_txt') ?>';
var user_profile_upload_new_avatar_btn_txt = '<?php echo $this->config->item('user_profile_upload_new_avatar_btn_txt') ?>';
</script>	
<script src="<?= ASSETS ?>js/modules/user_account_management_avatar.js"></script>