<?php
$user = $this->session->userdata('user');	
if($disputed_initiated_status == 1 && $project_dispute_status == 1){
	if($latest_projects_dispute_message_data['message_sent_by_user_id'] == $user[0]->user_id ){
		$post_message_button_text = $this->config->item('project_dispute_details_page_post_message_btn_txt');
	}else{
		$post_message_button_text = $this->config->item('project_dispute_details_page_reply_answer_back_btn_txt');
	}
	
}

?>	
<div class="dashTop">
	<div class="wrapper wrapper1">		
		<!-- Active Dispute Start -->
		<div class="disPute">
			<div class="dispHead">
				<div class="row">
					<div class="col-md-8 col-sm-8 col-12 offset-md-2 offset-sm-2">
						<h4><?php echo htmlspecialchars(trim($project_data['project_title']), ENT_QUOTES); ?></h4>
						<div id="project_dispute_details_heading">
							<?php
							if($disputed_initiated_status == 1 && $project_dispute_status == 2){
							 $dispute_end_date = $projects_disputes_data['dispute_end_date'];	
							}else{

							$dispute_end_date = '';
							}			
							echo $this->load->view('projects_disputes/heading_dispute_details', array('project_dispute_status'=>$project_dispute_status,'disputed_against_user_name'=>$disputed_against_user_name,'disputed_amount'=>$disputed_amount,'dispute_started_heading'=>$dispute_started_heading,'dispute_closed_heading'=>$dispute_closed_heading,'disputed_initiated_status'=>$disputed_initiated_status,'dispute_end_date'=>$dispute_end_date), true);
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
			if($project_type == 'fixed'){
				$minimum_required_disputed_project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
				$admin_dispute_arbitration_fee = $this->config->item('fixed_budget_project_admin_dispute_arbitration_percentage_fee');
			}
			if($project_type == 'hourly'){
				$minimum_required_disputed_project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
				$admin_dispute_arbitration_fee = $this->config->item('hourly_rate_based_project_admin_dispute_arbitration_percentage_fee');
			}
			if($project_type == 'fulltime'){
				$minimum_required_disputed_project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
				$admin_dispute_arbitration_fee = $this->config->item('fulltime_project_admin_dispute_arbitration_percentage_fee');
			}			
			
			
			if($disputed_initiated_status == 0 || ($disputed_initiated_status == 1 && $projects_disputes_data['dispute_status'] == 'active') ){
				if($disputed_initiated_status == 0){
					if($project_type == 'fulltime'){
						$phase_rules_heading = $this->config->item('fulltime_project_dispute_details_page_dispute_initiation_phase_rules_heading');
					}else{
						$phase_rules_heading = $this->config->item('project_dispute_details_page_dispute_initiation_phase_rules_heading');
					}
					
					if($disputed_amount < $minimum_required_disputed_project_value_for_admin_arbitration){
						if($project_type == 'fulltime'){
							$phase_rules_txt = $this->config->item('fulltime_project_dispute_details_page_automatic_decided_dispute_initiation_phase_rules_txt');
						}else{	
							$phase_rules_txt = $this->config->item('project_dispute_details_page_automatic_decided_dispute_initiation_phase_rules_txt');
						}
					
					}else{
						if($project_type == 'fulltime'){
							$phase_rules_txt = $this->config->item('fulltime_project_dispute_details_page_admin_arbitration_dispute_initiation_phase_dispute_stage_rules_txt');
						}else{				
							$phase_rules_txt = $this->config->item('project_dispute_details_page_admin_arbitration_dispute_initiation_phase_dispute_stage_rules_txt');
						}
						
					}	
					$phase_rules_txt = str_replace(array('{other_party_first_name_last_name_or_company_name}','{project_disputed_amount}'),array($disputed_against_user_name,str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ".CURRENCY),$phase_rules_txt);
				}
				else if($disputed_initiated_status == 1 && $projects_disputes_data['dispute_status'] == 'active' && strtotime($projects_disputes_data['dispute_negotiation_end_date']) > time()){
					
					
					if($user[0]->user_id == $projects_disputes_data['dispute_initiated_by_user_id']){
						if($disputed_amount < $minimum_required_disputed_project_value_for_admin_arbitration){
							
							if($project_type == 'fulltime'){
								$phase_rules_heading = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_initiator_view_autodecided_dispute');
								$phase_rules_txt = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_initiator_view_autodecided_dispute');
							}else{	
							
								$phase_rules_heading = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_initiator_view_autodecided_dispute');
								$phase_rules_txt = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_initiator_view_autodecided_dispute');
							}
							
							$phase_rules_txt = str_replace(array('{other_party_first_name_last_name_or_company_name}','{dispute_negotiation_end_date}'),array($disputed_against_user_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($projects_disputes_data['dispute_negotiation_end_date']))),$phase_rules_txt);
							
							
						}else{
							
							if($project_type == 'fulltime'){
								
								$phase_rules_heading = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_initiator_view_admin_moderated_dispute');
								$phase_rules_txt = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_initiator_view_admin_moderated_dispute');
								
								$phase_rules_txt = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ".CURRENCY),$phase_rules_txt);
								
								
							}else{
								$phase_rules_heading = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_initiator_view_admin_moderated_dispute');
								$phase_rules_txt = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_initiator_view_admin_moderated_dispute');
								
								$phase_rules_txt = str_replace(array('{project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ".CURRENCY),$phase_rules_txt);
							}
							$phase_rules_txt = str_replace(array('{other_party_first_name_last_name_or_company_name}','{dispute_negotiation_end_date}','{admin_dispute_arbitration_fee}'),array($disputed_against_user_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($projects_disputes_data['dispute_negotiation_end_date'])),$admin_dispute_arbitration_fee),$phase_rules_txt);
									
						}
					}
					if($user[0]->user_id == $projects_disputes_data['disputed_against_user_id']){
						if($disputed_amount < $minimum_required_disputed_project_value_for_admin_arbitration){
							
							if($project_type == 'fulltime'){
								$phase_rules_heading = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_disputee_view_autodecided_dispute');
								$phase_rules_txt = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_disputee_view_autodecided_dispute');
							}else{	
								$phase_rules_heading = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_disputee_view_autodecided_dispute');
								$phase_rules_txt = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_disputee_view_autodecided_dispute');
							}	
							
							$phase_rules_txt = str_replace(array('{other_party_first_name_last_name_or_company_name}','{dispute_negotiation_end_date}'),array($dispute_initiated_by_user_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($projects_disputes_data['dispute_negotiation_end_date']))),$phase_rules_txt);
							
						}else{
							if($project_type == 'fulltime'){
								$phase_rules_heading = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_disputee_view_admin_moderated_dispute');
								$phase_rules_txt = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_disputee_view_admin_moderated_dispute');
								
								$phase_rules_txt = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ".CURRENCY),$phase_rules_txt);

							}else{	
								$phase_rules_heading = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_disputee_view_admin_moderated_dispute');
								$phase_rules_txt = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_disputee_view_admin_moderated_dispute');
								
								$phase_rules_txt = str_replace(array('{project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ".CURRENCY),$phase_rules_txt);
								
								
							}
							$phase_rules_txt = str_replace(array('{other_party_first_name_last_name_or_company_name}','{dispute_negotiation_end_date}','{admin_dispute_arbitration_fee}'),array($dispute_initiated_by_user_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($projects_disputes_data['dispute_negotiation_end_date'])),$admin_dispute_arbitration_fee),$phase_rules_txt);
						}		
						
					}
				}
				?>
				<div class="row" id="project_dispute_rules_section">
					<div class="col-md-8 col-sm-8 col-12 offset-md-2 offset-sm-2">
						<div class="dispRules">
							<div class="dR"><strong id="phase_rules_heading"><?php echo $phase_rules_heading; ?></strong></div>
							<div class="dispText">
								<h6 id="phase_rules_txt"><?php echo $phase_rules_txt;  ?></h6>
							</div>
						</div>
					</div>
				</div>
			<?php
			}
			?>
			
			<div class="row" style="display:<?php echo ($disputed_initiated_status == 1 && $project_dispute_status == 2) ? "block" : "none"; ?>" id="project_dispute_closed_section">
				<?php
				echo $this->load->view('projects_disputes/project_dispute_details_closed_dispute_section', array('projects_disputes_data'=>$projects_disputes_data,'sp_data'=>$sp_data,'po_data'=>$po_data,'project_type'=>$project_type), true);
				?>
			</div>	
			<div class="row">
				<div class="col-md-8 col-sm-8 col-12 offset-md-2 offset-sm-2">
				
					<div class="default_radio_button radio_bttmBdr radio_left_side payDTls">
						<span>Disputed Escrows</span>
						<small class="receive_notification expand_notification_area420"><a class="rcv_notfy_btn" onclick="showMoreReview(420)">(<sup>+</sup>)</a><input type="hidden" id="moreReview420" value="1"></small>
					</div>
					<div class="radio_right_side" id="rcv_notfy420" style="display:none;">
					<div class="inpaymentsTab">
						<?php
						if(!empty($projects_disputed_escrows_history_listing)){
							foreach($projects_disputed_escrows_history_listing as $disputed_escrows_key=>$disputed_escrows_value){
						?>
							<div class="projTitle">
								<div class="row">
									<div class="col-md-12 col-sm-12 col-12 mDetails">
									
										<?php
										if($project_type == 'hourly'){
										?>
										<div>
										<label>
											<div>
												<b class="default_black_bold_medium">Hourly Rate:</b><span class="default_black_regular_medium touch_line_break"><?php echo str_replace(".00","",number_format($disputed_escrows_value['escrow_considered_hourly_rate'],  2, '.', ' '))." ".CURRENCY.$this->config->item('project_details_page_hourly_rate_based_project_per_hour'); ?></span>
											</div>
										</label>
										<label>
											<div>
												<b class="default_black_bold_medium">Number of hours:</b><span class="default_black_regular_medium touch_line_break"><?php echo str_replace(".00","",number_format($disputed_escrows_value['escrow_considered_number_of_hours'],  2, '.', ' ')); ?></span>
											</div>
										</label>
										</div>
										<?php
										}
										?>
										
										<label>
											<div>
											<b class="default_black_bold_medium">Disputed Escrow Amount:</b><span class="default_black_regular_medium touch_line_break"><?php echo str_replace(".00","",number_format($disputed_escrows_value['created_escrow_amount'],  2, '.', ' '))." ".CURRENCY; ?></span>
											</div>
										</label>
										<?php
										if($project_data['project_owner_id'] == $user[0]->user_id){
										?>
										<label>
											<div>
											<b class="default_black_bold_medium">Business Service Fee:</b><span class="default_black_regular_medium touch_line_break"><?php echo str_replace(".00","",number_format($disputed_escrows_value['service_fee_charges'],  2, '.', ' '))." ".CURRENCY; ?></span>
											</div>
										</label>
										<label>
											<div>
											<b class="default_black_bold_medium">Total Escrow:</b><span class="default_black_regular_medium touch_line_break"><?php echo str_replace(".00","",number_format($disputed_escrows_value['total_escrow_payment_value'],  2, '.', ' '))." ".CURRENCY; ?></span>
											</div>
										</label>
										<?php
										}
										?>
										<div>
											<label>
											<div>
											<b class="default_black_bold_medium">Created On:</b><span class="default_black_regular_medium touch_line_break"><?php echo date(DATE_TIME_FORMAT,strtotime($disputed_escrows_value['escrow_creation_date'])); ?></span>
											</div>
											</label>
										</div>
										<?php
										if(!empty($disputed_escrows_value['escrow_description'])){
										?>
										<p class="default_black_regular_medium aDispDesc"><b class="default_black_bold_medium">Description:</b><?php echo $disputed_escrows_value['escrow_description']; ?></p>
										<?php
										}
										?>
										<!--
										<label class="escrow_release_message escrow_requested_release_message_632"></label>-->
									</div>
									<div class="col-md-4 col-sm-4 col-12 downPosition">
									<div class="myAction actionBtn_adjust"></div>
									</div>
								</div>
							</div>
						<?php
							}
						}
						?>
						<?php
						/* <div class="sum_escrow_amount_container_180 payAmount projTitle text-right escrowTotal" style="display:block">
							<div class="currencyDetails">
								<b class="default_black_bold_medium totalCurrency"><span class="total_rightside_gap">Total:</span><span class="touch_line_break sum_escrow_amount_180"><?php echo str_replace(".00","",number_format($sum_disputed_escrows_amount_project,  2, '.', ' ')); ?> <?php echo CURRENCY; ?></span></b>
							</div>
						</div> */
						?>
					</div>
					</div>
				</div>
			</div>
			
			<!-- Left Side Start -->
			<div class="bothSide">	
				<div class="row">
					<div class="col-md-6 col-sm-6 col-6 offset-md-2 offset-sm-2" id="dispute_messages_container" style="display:<?php echo !empty($dispute_messages) ? "block" : "none"; ?>">
						<?php 
						foreach($dispute_messages as $diapute_message_key=>$dispute_message_data){
							
							echo $this->load->view('projects_disputes/dispute_message_row_project_dispute_details', array('dispute_message_data'=>$dispute_message_data,'dispute_initiated_by'=>$dispute_initiated_by,'disputed_against_user_id'=>$disputed_against_user_id), true);
						}
						?>
					</div>
					<div class="col-md-2" id="main_make_counter_offer_section_container">
						
							<?php
							echo $this->load->view('projects_disputes/project_dispute_details_make_projects_dispute_counter_offer_section', array('disputed_amount'=>$disputed_amount,'projects_disputes_data'=>$projects_disputes_data,'counter_offer_listing'=>$counter_offer_listing,'latest_counter_offer_data'=>$latest_counter_offer_data,'disputed_against_user_id'=>$disputed_against_user_id,'disputed_against_user_name'=>$disputed_against_user_name,'dispute_initiated_by'=>$dispute_initiated_by,'dispute_initiated_by_user_name'=>$dispute_initiated_by_user_name), true);
							?>
					</div>
					<?php
					if($disputed_initiated_status == 0 || ($disputed_initiated_status == 1 && isset($projects_disputes_data['dispute_status']) && $projects_disputes_data['dispute_status'] == 'active') ){
					?>
					<div class="col-md-8 col-sm-8 col-12 offset-md-2 offset-sm-2" id="dispute_button_section" style="display:<?php echo ($disputed_initiated_status == '1') ? "block" : "none"; ?>">
						<div class="ansBack">
							<div class="payOffer">
								<div class="row">
									<div class="col-md-12 col-sm-12 col-12 text-center">
										<button class="btn mOBtn green_btn" id="post_message_button"><?php echo $post_message_button_text; ?></button>
										<?php
										if($dispute_initiated_by == $user[0]->user_id){
										?>
										<button class="btn red_btn" id="cancel_project_dispute_confirmation" data-dispute-ref-id = "<?php echo $dispute_ref_id; ?>">Cancel Dispute</button>
										<?php
										}
										?>										
									
										<?php
										/* if(empty($latest_counter_offer_data) && $disputed_initiated_status == 1 && $project_dispute_status == 1 && $user[0]->user_id == $disputed_against_user_id){
										?>
											<button class="btn mOBtn green_btn" id="initial_view_make_counter_offer">Make Counter Offer</button>
										<?php
										} */
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
					}
					?>
					<!-- Left Side End -->
					
					<!-- Travai dispute resolved Start -->
					<!--
					<div class="col-md-8 col-sm-8 col-12 offset-md-2 offset-sm-2">
						<div class="ansBack">
							<div class="tR">
								<div class="row">
									<div class="col-md-6 col-sm-6 col-12">
										<div class="tLeft">Travai</div>
									</div>
									<div class="col-md-6 col-sm-6 col-12">
										<div class="tRight">Dispute Closed Time : 21.11.2018 07:33:07</div>
									</div>	
								</div>	
							</div>
							<p class="dResolved">Dispute has been Resolved.</p>
							<div class="reason">Reason: Both parties have negotiated and agreed to Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. </div>
							<hr/>
							<div class="agreed">
								<div class="row">
									<div class="col-md-6 col-sm-6 col-12">
										<h6>Agreed By Workspaceil</h6>
										<p>You Recieved: 7000kc</p>
										<p>Workspaceil recieved: 2000kc</p>
									</div>
									<div class="col-md-6 col-sm-6 col-12">
										<h5>Total Amount 9000kc</h5>
									</div>
								</div>
							</div>
						</div>
					</div>-->
					<!-- Travai dispute resolved End -->
				</div>
			</div>
			<?php
			if($disputed_initiated_status == 0 || ($disputed_initiated_status == 1 && $project_dispute_status == 1 )){
				echo $this->load->view('projects_disputes/projects_dispute_form', array('project_id'=>$project_id,'dispute_ref_id'=>$dispute_ref_id,'dispute_initiated_by'=>$dispute_initiated_by,'disputed_against_user_id'=>$disputed_against_user_id,'project_type'=>$project_type,'disputed_initiated_status'=>$disputed_initiated_status,'project_dispute_status'=>$project_dispute_status,'projects_disputes_data'=>$projects_disputes_data,'latest_projects_dispute_message_data'=>$latest_projects_dispute_message_data), true);
			}
			?>
		</div>
		<!-- Active Dispute Start -->
	</div>
</div>
<!-- Confirmation Modal Start-->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="cancelProjectModalTitle" aria-hidden="true">
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
					<div class="col-sm-12 col-lg-12 col-12" id="confirmation_modal_footer">
						
					</div>
					</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal End -->
<script>
var post_message_btn_txt = "<?php echo $this->config->item('project_dispute_details_page_post_message_btn_txt'); ?>";	
var popup_alert_heading = "<?php echo $this->config->item('popup_alert_heading'); ?>";	
var project_dispute_attachment_allowed_file_extensions = '<?php echo $this->config->item('custom_project_dispute_details_page_attachment_allowed_file_extensions'); ?>';
var project_dispute_attachment_extension_validation_message = "<?php echo $this->config->item('project_dispute_details_page_attachment_extension_validation_message'); ?>";
var project_dispute_attachment_maximum_size = "<?php echo $this->config->item('project_dispute_details_page_attachment_maximum_size_allocation'); ?>";
var project_dispute_attachment_maximum_size_validation_message = "<?php echo $this->config->item('project_dispute_details_page_attachment_maximum_size_validation_message'); ?>";
var maximum_allowed_number_of_attachments = "<?php echo $this->config->item('project_dispute_details_page_maximum_allowed_number_of_attachments_on_disputed_projects'); ?>";
var dispute_description_maximum_length_characters_limit = "<?php echo $this->config->item('project_dispute_description_maximum_length_characters_limit'); ?>";
var dispute_description_minimum_length_words_limit = "<?php echo $this->config->item('project_dispute_description_minimum_length_words_limit'); ?>";
var dispute_description_minimum_length_character_limit = "<?php echo $this->config->item('project_dispute_description_minimum_length_characters_limit'); ?>";
var project_id = "<?php echo $project_id; ?>";
var project_type = "<?php echo $project_type; ?>";
var dispute_ref_id = "<?php echo $dispute_ref_id; ?>";
var dispute_initiated_by = "<?php echo Cryptor::doEncrypt($dispute_initiated_by); ?>";
var disputed_against_user_id = "<?php echo Cryptor::doEncrypt($disputed_against_user_id); ?>";
var character_remaining_message = "<?php echo $this->config->item('characters_remaining_message'); ?>";
var escrow_amount_length_character_limit_before_decimal = "<?php echo $this->config->item('escrow_amount_length_character_limit_before_decimal_point_escrow_form'); ?>"; 
var escrow_amount_length_character = "<?php echo $this->config->item('escrow_amount_length_character_limit_escrow_form'); ?>"; 
var total_counter_offers = "<?php echo $total_counter_offers; ?>"; 


</script>	
<script src="<?php echo JS; ?>modules/project_dispute_details_page.js"></script>