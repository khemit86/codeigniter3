<div class="dashTop">	
	<!-- Menu Icon on Responsive View Start -->
	<?php echo $this->load->view('user_left_menu_mobile.php'); ?>
	<!-- Menu Icon on Responsive View End -->
	<div class="wrapper wrapper1">
		<!-- Left Menu Start -->
		<?php echo $this->load->view('user_left_nav.php'); ?>
		<!-- Left Menu End -->
		<!-- Right Section Start -->
		<div id="content" class="pending_feedbacks_listing_content body_distance_adjust <?php echo $projects_pending_ratings_feedbacks_count == 0 ? 'no_data_msg_display_center' : '' ?>">
			<div class="rightSec">
				<!-- Middle Section Start -->
				<div class="fePage" id="pending_feedbacks_listing_data">
					<?php
					echo $this->load->view('user_projects_pending_ratings_feedbacks_listing_data', array('projects_pending_ratings_feedbacks_count'=>$projects_pending_ratings_feedbacks_count,'projects_pending_ratings_feedbacks_data'=>$projects_pending_ratings_feedbacks_data,'projects_pending_ratings_feedbacks_pagination_links'=>$projects_pending_ratings_feedbacks_pagination_links), true); 
					?>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Feedback Modal Start -->
<div class="modal feedModal fade" id="feedbackModal">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">      
			<!-- Modal Header -->
			<div class="modal-header popup_header popup_header_without_text">
				<h4 class="modal-title popup_header_title" id="ratings_feedbacks_modal_title">Send Feedback</h4>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>        
			<!-- Modal body -->
			<div class="modal-body"  id="ratings_feedbacks_modal_body"></div>			
			<!-- Modal footer -->
			<div class="modal-footer">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-12" id="ratings_feedbacks_modal_footer"></div> 
				</div> 
			</div>     
		</div>
	</div>
</div>
<!-- Feedback Modal End -->

<!-- Script Start -->
<script>
var users_ratings_feedbacks_popup_feedback_characters_maximum_length_characters_limit = '<?php echo $this->config->item('users_ratings_feedbacks_popup_feedback_characters_maximum_length_characters_limit'); ?>';		
var users_ratings_feedbacks_popup_feedback_characters_minimum_length_characters_limit = '<?php echo $this->config->item('users_ratings_feedbacks_popup_feedback_characters_minimum_length_characters_limit'); ?>';
var characters_remaining_message = "<?php echo $this->config->item('characters_remaining_message'); ?>";
</script>
<script src="<?php echo JS.'modules/user_projects_pending_ratings_feedbacks.js'?>" type="text/javascript"></script>
	

<!-- Script End -->