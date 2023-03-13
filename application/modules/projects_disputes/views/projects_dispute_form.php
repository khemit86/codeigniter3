<?php
$show_disputor_form_status = 'display:none';
$user = $this->session->userdata('user');

if($disputed_initiated_status == 0){
	$show_disputor_form_status = 'display:block';
}
?>
<div id="dispute_form_section" class="row" style="<?php echo $show_disputor_form_status; ?>">
	<div class="col-md-8 col-sm-8 col-12 offset-md-2 offset-sm-2">		
		<form id="project_dispute_form">
		<div class="mv">
			<div class="mvT">There are many variations of passages of Lorem Ipsum available, but the</div>
			
			<div class="form-group">
				<input type="hidden" name = "project_id" value="<?php echo $project_id; ?>" />
				<input type="hidden" name = "dispute_ref_id" value="<?php echo $dispute_ref_id; ?>" />
				<input type="hidden" name = "dispute_initiated_by" value="<?php echo Cryptor::doEncrypt($dispute_initiated_by); ?>" />
				<input type="hidden" name = "disputed_against_user_id" value="<?php echo Cryptor::doEncrypt($disputed_against_user_id); ?>" />
				<input type="hidden" name = "project_type" value="<?php echo $project_type; ?>" />
				<textarea class="form-control avoid_space_textarea" rows="5" name="dispute_description" id="dispute_description" maxlength="<?php echo $this->config->item('project_dispute_description_maximum_length_characters_limit'); ?>"></textarea>
				<div class="error_div_sectn clearfix">
					<span id="dispute_description_error" class="error_msg errMesgOnly"></span>
					<span class="content-count project_dispute_description_length_count_message"><?php echo $this->config->item('project_dispute_description_maximum_length_characters_limit')."&nbsp;".$this->config->item('characters_remaining_message'); ?></span> 
				</div>
			</div>
		</div>
		<div class="file-upload">
			<label for="upload1" class="file_upload_btn"><i class="fas fa-cloud-upload-alt"></i> Upload a file</label>
			<!--<input id="upload1" class="file_upload_input" type="file" name="file-upload">-->
			<input type="file" accept="<?php echo $this->config->item('plugin_project_dispute_details_page_attachment_allowed_file_extensions'); ?>" style="display:none;" class="cover_picture_input project_dispute_imgupload" />
			
		</div>
		<div id="attachment_container" style="display:none;"></div>
		<div class="row">
			<!--
			<div class="col-md-6 col-sm-6 col-12">
				<div class="amDisp">Total Amount Dispute 9000kc</div>
			</div>-->
			<div class="col-md-6 col-sm-6 col-12">
				<div class="twoBtn">
				<button class="btn btnCancel" data-type="<?php echo ($disputed_initiated_status == '0') ? "temp" : "active"; ?>" id="reset_dispute_data" data-dispute-ref-id = "<?php echo $dispute_ref_id; ?>" ><?php echo $this->config->item('cancel_btn_txt'); ?></button>
				<?php
				if($disputed_initiated_status == 0){
					$dispute_form_button_text = $this->config->item('project_dispute_details_page_initiate_dispute_btn_txt');
					$dispute_form_button_id = 'initiate_project_dispute_confirmation';
				}else if($disputed_initiated_status == 1 && $project_dispute_status == 1){
				
					if($latest_projects_dispute_message_data['message_sent_by_user_id'] == $user[0]->user_id ){
						$dispute_form_button_text = $this->config->item('project_dispute_details_page_post_message_btn_txt');
					}else{
						$dispute_form_button_text = $this->config->item('reply_btn_txt');
					}
					$dispute_form_button_id = 'post_dispute_message';
				}
				?>
				<button class="btn btnInitDisp" id="<?php echo $dispute_form_button_id; ?>">
				<?php echo $dispute_form_button_text; ?>	
				</button>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>
