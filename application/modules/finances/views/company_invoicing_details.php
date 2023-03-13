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
			<div class="displayMiddle" style="display:<?php echo empty($invoicing_details) ? 'inline-flex' : 'none' ?>;">
				<div class="pmFirstStep" id="invoicing_details_initial">
					<div  class="default_hover_section_iconText emailNew mrgBtm0 closeHourlyrate">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 fontSize0 default_bottom_border">
								<i class="fas fa-clipboard-list"></i>
								<h6><?php echo $user_data['is_authorized_physical_person'] == 'Y' ? $this->config->item('company_app_invoicing_details_inital_view_title') : $this->config->item('company_invoicing_details_inital_view_title'); ?></h6>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<p><?php echo $user_data['is_authorized_physical_person'] == 'Y' ? $this->config->item('company_app_invoicing_details_initial_view_content') : $this->config->item('company_invoicing_details_initial_view_content'); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="etSecond_step" id="invoicing_details_data" style="display:<?php echo !empty($invoicing_details) ? 'block' : 'none' ?>;">
				<!-- Profile Management Text Start -->
				<div class="default_page_heading">
					<h4><?php echo $this->config->item('finance_headline_title_invoicing_details'); ?></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
				<!-- Content Start -->
				<div class="cmFieldText">
					<div class="cmField">
						<!-- Step 1st Start -->
						
						<!-- Step 1st End -->
						<!-- Step 2nd Start -->
						<div class="invoicing_details_wrapper">
							<?php echo $this->load->view('ajax_company_invoicing_details', ['invoicing_details' => $invoicing_details, 'countries' => $countries], true); ?>
						</div>
						<!-- Step 2nd End -->						
					</div>						
				</div>
				<!-- Content End -->
			
			</div>
        <!-- Right Section End -->
    </div>
    <!-- Middle Section End -->
	
</div>
<!-- Modal Start for edit upgrade-->
<div class="modal fade" id="invoicing_detail_modal" tabindex="-1" role="dialog"  aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content etModal">
						<div class="modal-header popup_header popup_header_without_text">
								<h4 class="modal-title popup_header_title" id="user_block_modal_title"></h4>
								<button type="button" class="close reload_modal" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
								</button>
						</div>
						<div class="modal-body">
								<div class="popup_body_semibold_title" id="user_block_modal_body">
									<?php echo $user_data['is_authorized_physical_person'] == 'Y' ? $this->config->item('company_app_invoicing_details_confirmation_modal_body_heading') : $this->config->item('company_invoicing_details_confirmation_modal_body_heading'); ?>
								</div>
								<div class="popup_body_regular_title hire_request_text radio_modal_separator text-left">
									<span><?php echo $user_data['is_authorized_physical_person'] == 'Y' ? $this->config->item('company_app_invoicing_details_confirmation_modal_body') : $this->config->item('company_invoicing_details_confirmation_modal_body'); ?></span>
								</div>
								<div class="disclaimer default_disclaimer_text radio_modal_separator">
									<div>
										<label class="default_checkbox">
												<input type="checkbox" id="user_block_checkbox_po">
												<span class="checkmark"></span>
										<span id="user_block_disclaimer" class="popup_body_regular_checkbox_text"><?php echo $this->config->item('user_confirmation_check_box_txt'); ?></span>
										</label>
									</div>
								</div>
						</div>
						<div class="modal-footer">
								<div class="chatFooter">	
									<span id="details_saved_error" class="error_msg pull-left"></span>
									<button type="button" data-dismiss="modal" class="btn default_btn red_btn " id="user_block_modal_close_button_txt"><?php echo $this->config->item('close_btn_txt'); ?></button>
									<button type="button" disabled class="btn default_btn blue_btn " id="invoicing_detail_modal_save"><?php echo $this->config->item('save_btn_txt'); ?> <i id="modal_spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
								</div>
						</div>
				</div>
		</div>
</div>
<!-- Modal End -->
<script>
	var invoicing_details_company_name_maximum_length_character_limit = '<?php echo $this->config->item('company_invoicing_details_company_name_maximum_length_character_limit'); ?>';
	var invoicing_details_company_address_line_1_maximum_length_character_limit = '<?php echo $this->config->item('company_invoicing_details_company_address_line_1_maximum_length_character_limit'); ?>';
	var invoicing_details_company_address_line_2_maximum_length_character_limit = '<?php echo $this->config->item('company_invoicing_details_company_address_line_2_maximum_length_character_limit'); ?>';
	var invoicing_details_company_registration_number_maximum_length_character_limit = '<?php echo $this->config->item('company_invoicing_details_company_registration_number_maximum_length_character_limit'); ?>';
	var invoicing_details_company_vat_number_maximum_length_character_limit = '<?php echo $this->config->item('company_invoicing_details_company_vat_number_maximum_length_character_limit'); ?>';
	var characters_remaining_message = '<?php echo $this->config->item('characters_remaining_message'); ?>';
</script>
<script src="<?= ASSETS ?>js/modules/company_invoicing_details.js"></script>