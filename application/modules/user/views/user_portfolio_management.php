<?php
$user = $this->session->userdata('user');
if($user_detail['current_membership_plan_id'] == '1'){ // for free
		
	$number_tag_allowed = $this->config->item('user_portfolio_page_free_membership_subscriber_number_tags_allowed_per_portfolio_slot');	
	$number_image_allowed = $this->config->item('user_portfolio_page_free_membership_subscriber_number_images_allowed_per_portfolio_slot');	
	$images_allowed_per_portfolio_slots_exceeded_error_message = $this->config->item('user_portfolio_section_free_membership_subscriber_number_images_allowed_per_portfolio_slots_exceeded_error_message');
	
	
	
}else{	
	$number_tag_allowed = $this->config->item('user_portfolio_page_gold_membership_subscriber_number_tags_allowed_per_portfolio_slot');
	$number_image_allowed = $this->config->item('user_portfolio_page_gold_membership_subscriber_number_images_allowed_per_portfolio_slot');
	$images_allowed_per_portfolio_slots_exceeded_error_message = $this->config->item('user_portfolio_section_gold_membership_subscriber_number_images_allowed_per_portfolio_slots_exceeded_error_message');
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
			$show_initial_add_portfolio_style = 'display:inline-flex;'; 
			if($portfolio_count > 0){
				$show_initial_add_portfolio_style = 'display:none;'; 
			}
			?>
			<div class="weNodata" id="add_portfolio_container"  style="<?php echo $show_initial_add_portfolio_style; ?>">
				<div class="default_hover_section_iconText weND widthAdjust add_portfolio" data-uid="<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>" data-attr = "add">
					<div class="row">
						<div class="col-md-12 default_bottom_border">
							<i class="fa fa-briefcase"></i><h6><?php echo $this->config->item('user_portfolio_section_initial_view_add_headline_title'); ?></h6>
						</div>
						<div class="col-md-12">
							<p><?php echo $this->config->item('user_portfolio_section_initial_view_description'); ?></p>
						</div>
					</div>
				</div>
			</div>
			<!-- 1st Step End -->
			<!-- 2nd Step Start -->
			<?php
			$show_portfolio_listing_style = 'display:none;'; 
			if($portfolio_count > 0){
				$show_portfolio_listing_style = 'display:block;'; 
			}
			?>
			<div class="etSecond_step" id="portfolio_listing_data" style="<?php echo $show_portfolio_listing_style; ?>">
				<?php
				echo $this->load->view('user_portfolio_listing', array('portfolio_data'=>$portfolio_data,'portfolio_count'=>$portfolio_count,'portfolio_pagination_links'=>$portfolio_pagination_links,'user_detail'=>$user_detail), true); 
				?>
			</div>
			<!-- 2nd Step End -->
		 </div>
		<!-- Right Section End -->
	</div>
	<!-- Middle Section End -->
</div>
<!-- Modal Start -->
<div class="modal fade" id="portfolioModalCenter" tabindex="-1" role="dialog" aria-labelledby="portfolioModalCenter" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content etModal">
				<div class="modal-header popup_header popup_header_without_text">
						<h5 class="modal-title popup_header_title" id="portfolio_popup_heading"></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
						</button>
				</div>
				<div class="modal-body" id="portfolio_popup_body"></div>
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
<!-- Script Start -->	
<script>
var portfolio_image_invalid_file_extension_validation_message = "<?php echo $this->config->item('user_portfolio_section_portfolio_image_invalid_file_extension_validation_message'); ?>";

var portfolio_image_maximum_size_limit = "<?php echo $this->config->item('user_portfolio_section_portfolio_image_maximum_size_limit'); ?>";

portfolio_image_maximum_size_limit = portfolio_image_maximum_size_limit * 1048576;



var portfolio_image_maximum_size_validation_message = "<?php echo $this->config->item('user_portfolio_section_portfolio_image_exceeded_maximum_allowed_size_error_message'); ?>";
var portfolio_section_number_images_allowed_per_portfolio_slots_exceeded_error_message = "<?php echo $images_allowed_per_portfolio_slots_exceeded_error_message; ?>";

/* var user_portfolio_section_custom_portfolio_image_allowed_file_extensions = [<?php echo $this->config->item('user_portfolio_section_custom_portfolio_image_allowed_file_extensions'); ?>];
		
var user_portfolio_section_portfolio_image_invalid_file_extension_validation_message = "<?php echo $this->config->item('user_portfolio_section_portfolio_image_invalid_file_extension_validation_message'); ?>";

var portfolio_image_maximum_size_limit = "<?php echo $this->config->item('user_portfolio_section_portfolio_image_maximum_size_limit'); ?>";

var popup_alert_heading = "<?php echo $this->config->item('popup_alert_heading'); ?>";

var upload_blank_attachment_alert_message = "<?php echo $this->config->item('upload_blank_attachment_alert_message'); ?>";	

var portfolio_image_maximum_size_validation_message = "<?php echo $this->config->item('user_portfolio_section_portfolio_image_maximum_size_validation_project_bid_form_message'); ?>";

var portfolio_image_allowed_files_validation_message = "<?php echo $this->config->item('user_portfolio_section_portfolio_image_allowed_files_validation_message'); ?>";

var bid_attachment_invalid_file_extension_validation_message = "<?php echo $this->config->item('project_details_page_bid_attachment_invalid_file_extension_validation_project_bid_form_message'); ?>";

var portfolio_section_plugin_portfolio_image_allowed_file_extensions = '<?php echo $this->config->item('user_portfolio_section_plugin_portfolio_image_allowed_file_extensions'); ?>'; */
	
//var portfolio_detail_page_url = '<?php echo $this->config->item('portfolio_detail_page_url'); ?>';
//portfolio title
var user_portfolio_section_portfolio_title_characters_minimum_length_characters_limit = '<?php echo $this->config->item('user_portfolio_section_portfolio_title_characters_minimum_length_characters_limit'); ?>';
var user_portfolio_section_portfolio_title_characters_maximum_length_characters_limit = '<?php echo $this->config->item('user_portfolio_section_portfolio_title_characters_maximum_length_characters_limit'); ?>';
var user_portfolio_section_portfolio_title_required = '<?php echo $this->config->item('user_portfolio_section_portfolio_title_required'); ?>';
var user_portfolio_section_portfolio_title_characters_minimum_length_validation_message = '<?php echo $this->config->item('user_portfolio_section_portfolio_title_characters_minimum_length_validation_message'); ?>';

//Referrence
var user_portfolio_section_referrence = '<?php echo $this->config->item('user_portfolio_section_referrence'); ?>';
var user_portfolio_section_referrence_required = '<?php echo $this->config->item('user_portfolio_section_referrence_required'); ?>';

//portfolio tags
var user_portfolio_section_tags_characters_minimum_length_characters_limit = '<?php echo $this->config->item('user_portfolio_section_tags_characters_minimum_length_characters_limit'); ?>';
var user_portfolio_section_tags_characters_maximum_length_characters_limit = '<?php echo $this->config->item('user_portfolio_section_tags_characters_maximum_length_characters_limit'); ?>';
var user_portfolio_section_tags_characters_minimum_length_validation_message = '<?php echo $this->config->item('user_portfolio_section_tags_characters_minimum_length_validation_message'); ?>';
var user_portfolio_section_tags_required = '<?php echo $this->config->item('user_portfolio_section_tags_required'); ?>';

//portfolio description
var user_portfolio_section_description_characters_minimum_length_characters_limit = '<?php echo $this->config->item('user_portfolio_section_description_characters_minimum_length_characters_limit'); ?>';

var user_portfolio_section_description_minimum_length_words_limit = '<?php echo $this->config->item('user_portfolio_section_description_minimum_length_words_limit'); ?>';

var user_portfolio_section_description_characters_maximum_length_characters_limit = '<?php echo $this->config->item('user_portfolio_section_description_characters_maximum_length_characters_limit'); ?>';
//var user_portfolio_section_portfolio_title_characters_minimum_length_validation_message = '<?php echo $this->config->item('user_portfolio_section_portfolio_title_characters_minimum_length_validation_message'); ?>';
var user_portfolio_section_description_required = '<?php echo $this->config->item('user_portfolio_section_description_required'); ?>';

//Tags
var user_portfolio_section_tags = '<?php echo $this->config->item('user_portfolio_section_tags'); ?>';



//common
var characters_remaining_message = '<?php echo $this->config->item('characters_remaining_message'); ?>';
var membership_page_url = "<?php echo $this->config->item('membership_page_url'); ?>";

var number_tag_allowed = "<?php echo $number_tag_allowed;?>";
var number_image_allowed = "<?php echo $number_image_allowed;?>";
var uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
var notification_messages_timeout_interval = "<?php echo $this->config->item('notification_messages_timeout_interval'); ?>";
var portfolio_picture_allowed_file_extensions = '<?php echo $this->config->item('pictures_allowed_extensions_js'); ?>';

</script>
<script src="<?= ASSETS ?>js/modules/user_portfolio.js"></script>

<script>
	$('#chooseFile').bind('change', function () {
		var filename = $("#chooseFile").val();
		if (/^\s*$/.test(filename)) {
			$(".file-upload").removeClass('active');
			$("#noFile").text("No file chosen..."); 
		}
	else {
		$(".file-upload").addClass('active');
		$("#noFile").text(filename.replace("C:\\fakepath\\", "")); 
	}
	});
</script>
<!-- Portfolio Accordition Script End  -->
<!-- Script End -->