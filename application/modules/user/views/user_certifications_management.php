<?php
$user = $this->session->userdata('user');	
if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) { 
	$user_certifications_section_certification_name_characters_minimum_length_characters_limit = $this->config->item('pa_user_certifications_section_certification_name_characters_minimum_length_characters_limit'); 
	$user_certifications_section_certification_name_characters_maximum_length_characters_limit = $this->config->item('pa_user_certifications_section_certification_name_characters_maximum_length_characters_limit'); 
	$user_certifications_section_certification_name_characters_minimum_length_validation_message = $this->config->item('pa_user_certifications_section_certification_name_characters_minimum_length_validation_message'); 
	$user_certifications_section_certification_name_required = $this->config->item('pa_user_certifications_section_certification_name_required'); 
	$user_certifications_section_date_acquired_select_year = $this->config->item('pa_user_certifications_section_date_acquired_select_year'); 
	$user_certifications_section_date_acquired_start_from = $this->config->item('pa_user_certifications_section_date_acquired_start_from'); 
	$user_certifications_section_date_acquired_end_to = $this->config->item('pa_user_certifications_section_date_acquired_end_to'); 
} else {
	$user_certifications_section_certification_name_characters_minimum_length_characters_limit = $this->config->item('ca_user_certifications_section_certification_name_characters_minimum_length_characters_limit'); 
	$user_certifications_section_certification_name_characters_maximum_length_characters_limit = $this->config->item('ca_user_certifications_section_certification_name_characters_maximum_length_characters_limit'); 
	$user_certifications_section_certification_name_characters_minimum_length_validation_message = $this->config->item('ca_user_certifications_section_certification_name_characters_minimum_length_validation_message'); 
	$user_certifications_section_certification_name_required = $this->config->item('ca_user_certifications_section_certification_name_required'); 
	$user_certifications_section_date_acquired_select_year = $this->config->item('ca_user_certifications_section_date_acquired_select_year'); 
	$user_certifications_section_date_acquired_start_from = $this->config->item('ca_user_certifications_section_date_acquired_start_from'); 
	$user_certifications_section_date_acquired_end_to = $this->config->item('ca_user_certifications_section_date_acquired_end_to');
}
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
		<div id="content" class="body_distance_adjust">								
			<!-- 1st Step Start -->
			<?php
			$show_initial_add_certifications_style = 'display:inline-flex;'; 
			if($certifications_count > 0){
				$show_initial_add_certifications_style = 'display:none;'; 
			}
			?>
			
			<div class="weNodata" id="add_certifications_container"  style="<?php echo $show_initial_add_certifications_style; ?>">
				<div class="default_hover_section_iconText weND widthAdjust add_certifications" data-uid="<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>" data-attr = "add">
					<div class="row">
						<div class="col-md-12 default_bottom_border">
							<i class="fa fa-certificate"></i><h6>
							<?php
								if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) { 
									echo $this->config->item('pa_user_certifications_section_initial_view_title'); 
								}else if($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y') { 
									echo $this->config->item('ca_app_user_certifications_section_initial_view_title'); 
								} else {
									echo $this->config->item('ca_user_certifications_section_initial_view_title'); 
								}
							?></h6>
						</div>
						<div class="col-md-12">
							<p>
							<?php 
								if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) { 
									echo $this->config->item('pa_user_certifications_section_initial_view_content'); 
								}else if($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y') { 
									echo $this->config->item('ca_app_user_certifications_section_initial_view_content'); 
								}  else {
									echo $this->config->item('ca_user_certifications_section_initial_view_content'); 
								}
							?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<!-- 1st Step End -->
			
			<!-- 2nd Step Start -->
			<?php
			$show_certifications_listing_style = 'display:none;'; 
			if($certifications_count > 0){
				$show_certifications_listing_style = 'display:block;'; 
			}
			?>
			<div class="etSecond_step" id="certifications_listing_data" style="<?php echo $show_certifications_listing_style; ?>">
				<?php
				echo $this->load->view('user_certifications_listing', array('certifications_data'=>$certifications_data,'certifications_count'=>$certifications_count,'certifications_pagination_links'=>$certifications_pagination_links), true); 
				?>
			</div>
			<!-- 2nd Step Start -->
		</div>
		<!-- Right Section End -->
	</div>
	<!-- Middle Section End -->
</div>
<!-- Modal Start -->
<div class="modal fade" id="certificationsModalCenter" tabindex="-1" role="dialog" aria-labelledby="certificationsModalCenter" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content etModal">
			<div class="modal-header popup_header popup_header_without_text">
				<h5 class="modal-title popup_header_title" id="certifications_popup_heading"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
				<div class="modal-body" id="certifications_popup_body">
					
				</div>
		</div>
	</div>
</div>
<!-- Modal End -->
<!-- Modal Start for confirmation delete certifications-->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content etModal">
			<div class="modal-header popup_header popup_header_without_text">
				<h4 class="modal-title popup_header_title" id="confirmation_modal_title"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body most-project" id="confirmation_modal_body">
			</div>
			<div class="modal-footer mg-bottom-10">
				<div class="row">
					<div class="col-sm-12 col-lg-12 col-xs-12" id="confirmation_modal_footer">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal End -->
<!-- Script Start -->	
<script>
    //certification name
    var user_certifications_section_certification_name_characters_minimum_length_characters_limit = '<?php echo $user_certifications_section_certification_name_characters_minimum_length_characters_limit; ?>';
    var user_certifications_section_certification_name_characters_maximum_length_characters_limit = '<?php echo $user_certifications_section_certification_name_characters_maximum_length_characters_limit; ?>';
    var user_certifications_section_certification_name_required = '<?php echo $user_certifications_section_certification_name_required; ?>';
    var user_certifications_section_certification_name_characters_minimum_length_validation_message = '<?php echo $user_certifications_section_certification_name_characters_minimum_length_validation_message; ?>';
    var user_certifications_section_date_acquired_select_year = '<?php echo $user_certifications_section_date_acquired_select_year; ?>';
    var user_certifications_section_date_acquired_start_from = '<?php echo $user_certifications_section_date_acquired_start_from; ?>';
    var user_certifications_section_date_acquired_end_to = '<?php echo $user_certifications_section_date_acquired_end_to; ?>';
    var user_certifications_section_attachment_allowed_file_types_js = '<?php echo $this->config->item('user_certifications_section_attachment_allowed_file_types_js'); ?>';
    var user_certifications_section_attachment_invalid_file_extension_validation_message = '<?php echo $this->config->item('user_certifications_section_attachment_invalid_file_extension_validation_message'); ?>';
    var user_certifications_section_attachment_maximum_size_limit = '<?php echo $this->config->item('user_certifications_section_attachment_maximum_size_limit'); ?>';
    var user_certifications_section_attachment_maximum_size_validation_message = '<?php echo $this->config->item('user_certifications_section_attachment_maximum_size_validation_message'); ?>';
    var user_certifications_section_maximum_allowed_number_of_attachments = '<?php echo $this->config->item('user_certifications_section_maximum_allowed_number_of_attachments'); ?>';
    var user_certifications_section_allowed_number_of_files_validation_message = '<?php echo $this->config->item('user_certifications_section_allowed_number_of_files_validation_message'); ?>';
    var user_certifications_section_attachment_name_character_length_limit = '<?php echo $this->config->item('user_certifications_section_attachment_name_character_length_limit'); ?>';
		var user_certifications_section_user_upload_blank_attachment_alert_message = '<?php echo $this->config->item('user_certifications_section_user_upload_blank_attachment_alert_message'); ?>';
		var popup_alert_heading = "<?php echo $this->config->item('popup_alert_heading'); ?>";
    //common
    var characters_remaining_message = '<?php echo $this->config->item('characters_remaining_message'); ?>';
</script>
<script src="<?= ASSETS ?>js/modules/user_certifications.js"></script>
<!-- Script End -->