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
		<div id="content" class="body_distance_adjust">				
			<!-- 1st Step Start -->
			<?php
			$show_initial_add_education_style = 'display:inline-flex;'; 
			if($education_training_count > 0){
				$show_initial_add_education_style = 'display:none;'; 
			}
			?>
			<div class="weNodata" id="add_education_training_container"  style="<?php echo $show_initial_add_education_style; ?>">
				<div class="default_hover_section_iconText weND widthAdjust add_education_training" data-uid="<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>" data-attr = "add">
					<div class="row">
						<div class="col-md-12 default_bottom_border">
							<i class="fa fa-graduation-cap"></i><h6><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_training_section_initial_view_title'):$this->config->item('personal_account_education_training_section_initial_view_title'); ?></h6>
						</div>
						<div class="col-md-12">
							<p><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_training_section_initial_view_content'):$this->config->item('personal_account_education_training_section_initial_view_content'); ?></p>
						</div>
					</div>
				</div>
			</div>
			<!-- 1st Step End -->
			
			<!-- 2nd Step Start -->
			<?php
			$show_education_listing_style = 'display:none;'; 
			if($education_training_count > 0){
				$show_education_listing_style = 'display:block;'; 
			}
			?>
			<div class="etSecond_step" style="<?php echo $show_education_listing_style; ?>" id="education_listing_data">
				<?php
				echo $this->load->view('personal_account_education_training_listing', array('education_training_data'=>$education_training_data,'education_training_count'=>$education_training_count,'education_training_pagination_links'=>$education_training_pagination_links), true); 
				?>
			</div>
			<!-- 2nd Step Start -->
		</div>
		<!-- Right Section End -->
	</div>
	<!-- Middle Section End -->
</div>

<!-- Modal Start for add/edit education popup-->
<div class="modal fade" id="educationTrainingModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content etModal">
			<div class="modal-header popup_header popup_header_without_text">
				<h5 class="modal-title popup_header_title" id="education_training_popup_heading"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="education_training_popup_body"></div>
		</div>
	</div>
</div>
<!-- Modal End -->
<!-- Modal Start for confirmation delete education modal-->
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
    //diploma name
    var personal_account_education_section_diploma_name_characters_minimum_length_characters_limit = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_diploma_name_characters_minimum_length_characters_limit'):$this->config->item('personal_account_education_section_diploma_name_characters_minimum_length_characters_limit'); ?>';
	
    var personal_account_education_section_diploma_name_characters_maximum_length_characters_limit = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_diploma_name_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_diploma_name_characters_maximum_length_characters_limit'); ?>';
	
    var personal_account_education_section_diploma_name_characters_minimum_length_validation_message = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_diploma_name_characters_minimum_length_validation_message'):$this->config->item('personal_account_education_section_diploma_name_characters_minimum_length_validation_message'); ?>';
	
    //school name
    var personal_account_education_section_school_name_characters_minimum_length_characters_limit = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_name_characters_minimum_length_characters_limit'):$this->config->item('personal_account_education_section_school_name_characters_minimum_length_characters_limit'); ?>';
	
    var personal_account_education_section_school_name_characters_maximum_length_characters_limit = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_name_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_school_name_characters_maximum_length_characters_limit'); ?>';
	
	 var personal_account_education_section_school_name_characters_minimum_length_validation_message = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_name_characters_minimum_length_validation_message'):$this->config->item('personal_account_education_section_school_name_characters_minimum_length_validation_message'); ?>';
  
    //school address
    var personal_account_education_section_school_address_characters_minimum_length_characters_limit = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_address_characters_minimum_length_characters_limit'):$this->config->item('personal_account_education_section_school_address_characters_minimum_length_characters_limit'); ?>';
	
    var personal_account_education_section_school_address_characters_maximum_length_characters_limit = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_address_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_school_address_characters_maximum_length_characters_limit'); ?>';
	
    var personal_account_education_section_school_address_characters_minimum_length_validation_message = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_address_characters_minimum_length_validation_message'):$this->config->item('personal_account_education_section_school_address_characters_minimum_length_validation_message'); ?>';
	
	var personal_account_education_section_school_address_characters_minimum_length_validation_message = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_address_characters_minimum_length_validation_message'):$this->config->item('personal_account_education_section_school_address_characters_minimum_length_validation_message'); ?>';
	
	//graduate in
	var personal_account_education_section_graduated_in = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_graduated_in'):$this->config->item('personal_account_education_section_graduated_in'); ?>';
	
	var personal_account_education_section_graduated_in_year_start_from = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_graduated_in_year_start_from'):$this->config->item('personal_account_education_section_graduated_in_year_start_from'); ?>';
	
	var personal_account_education_section_graduated_in_year_end_to = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_graduated_in_year_end_to'):$this->config->item('personal_account_education_section_graduated_in_year_end_to'); ?>';
	
	var personal_account_education_section_graduated_in_select_year = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_graduated_in_select_year'):$this->config->item('personal_account_education_section_graduated_in_select_year'); ?>';
	
    //comments
    var personal_account_education_section_comments_characters_minimum_length_characters_limit = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_comments_characters_minimum_length_characters_limit'):$this->config->item('personal_account_education_section_comments_characters_minimum_length_characters_limit'); ?>';
	
    var personal_account_education_section_comments_characters_maximum_length_characters_limit = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_comments_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_comments_characters_maximum_length_characters_limit'); ?>';
	
    var personal_account_education_section_comments_characters_minimum_length_validation_message = '<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_comments_characters_minimum_length_validation_message'):$this->config->item('personal_account_education_section_comments_characters_minimum_length_validation_message'); ?>';
    //common
    var characters_remaining_message = '<?php echo $this->config->item('characters_remaining_message'); ?>';
	
</script>
<script src="<?= ASSETS ?>js/modules/personal_account_education_training_management.js"></script>
