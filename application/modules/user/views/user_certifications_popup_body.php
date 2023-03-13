<?php
$user = $this->session->userdata('user');	
if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) { 
	$user_certifications_section_certification_name_characters_maximum_length_characters_limit = $this->config->item('pa_user_certifications_section_certification_name_characters_maximum_length_characters_limit'); 
	$user_certifications_section_certification_name = $this->config->item('pa_user_certifications_section_certification_name'); 
	$user_certifications_section_certification_name_placeholder = $this->config->item('pa_user_certifications_section_certification_name_placeholder'); 
	$user_certifications_section_date_acquired = $this->config->item('pa_user_certifications_section_date_acquired'); 
	$user_certifications_section_date_acquired_select_year = $this->config->item('pa_user_certifications_section_date_acquired_select_year'); 
	$user_certifications_section_date_acquired_select_month = $this->config->item('pa_user_certifications_section_date_acquired_select_month'); 
	$user_certifications_section_date_acquired_start_from = $this->config->item('pa_user_certifications_section_date_acquired_start_from'); 
	$user_certifications_section_date_acquired_end_to = $this->config->item('pa_user_certifications_section_date_acquired_end_to'); 
} else {
	$user_certifications_section_certification_name_characters_maximum_length_characters_limit = $this->config->item('ca_user_certifications_section_certification_name_characters_maximum_length_characters_limit'); 
	$user_certifications_section_certification_name = $this->config->item('ca_user_certifications_section_certification_name'); 
	$user_certifications_section_certification_name_placeholder = $this->config->item('ca_user_certifications_section_certification_name_placeholder'); 
	$user_certifications_section_date_acquired = $this->config->item('ca_user_certifications_section_date_acquired'); 
	$user_certifications_section_date_acquired_select_year = $this->config->item('ca_user_certifications_section_date_acquired_select_year'); 
	$user_certifications_section_date_acquired_select_month = $this->config->item('ca_user_certifications_section_date_acquired_select_month'); 
	$user_certifications_section_date_acquired_start_from = $this->config->item('ca_user_certifications_section_date_acquired_start_from'); 
	$user_certifications_section_date_acquired_end_to = $this->config->item('ca_user_certifications_section_date_acquired_end_to'); 
}
?>	
<form id="certifications_form">
	<input type="hidden" name="section_id" value="<?php if(isset($certifications_data['id'])){ echo Cryptor::doEncrypt($certifications_data['id']); } ?>"/>
	<input type="hidden" name="u_id" value="<?php echo Cryptor::doEncrypt($user[0]->user_id);  ?>"/>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label class="default_black_bold_medium"><?php echo $user_certifications_section_certification_name; ?></label>
				<input type="text" class="form-control avoid_space default_input_field" name="certification_name" id="certification_name" placeholder="<?php echo $user_certifications_section_certification_name_placeholder; ?>" maxlength="<?php echo $user_certifications_section_certification_name_characters_maximum_length_characters_limit; ?>" value="<?php if(isset($certifications_data['certification_name'])){ echo htmlentities($certifications_data['certification_name'],ENT_QUOTES); } ?>">
				<div class="error_div_sectn clearfix default_error_div_sectn">
					<?php 
					$certificate_name_remaining_characters = $this->config->item('personal_account_education_section_diploma_name_characters_maximum_length_characters_limit');
					if(isset($certifications_data['certification_name'])){ 
						if($this->config->item('personal_account_education_section_diploma_name_characters_maximum_length_characters_limit') - mb_strlen($certifications_data['certification_name']) >= 0) {
							$certificate_name_remaining_characters = $this->config->item('personal_account_education_section_diploma_name_characters_maximum_length_characters_limit') - mb_strlen($certifications_data['certification_name']);
						} else {
							$certificate_name_remaining_characters  = 0;
						}
					}
					?>
					<span class="content-count certification_name_length_count_message"><?php echo $certificate_name_remaining_characters.' '.$this->config->item('characters_remaining_message'); ?></span>
					<span class="error_msg" id="certification_name_error"></span> 
				</div>
			</div>
		</div>
		<div class="col-md-12 col-sm-12 col-12 date_acquired default_country">
			<label class="default_black_bold_medium countryText"><?php echo $user_certifications_section_date_acquired; ?></label>
			<label class="adjustLevel">
				<label class="selectMonth">
					<div class="default_dropdown_select">
						<select id="certification_month" name="certification_month">
							<option value=""><?php echo $user_certifications_section_date_acquired_select_month; ?></option>
							<?php
								foreach($this->config->item('calendar_months') as $monthId=>$monthName)
								{
							?>	
									<option value="<?php echo $monthId; ?>" <?php if(isset($certifications_data['certification_month']) && $certifications_data['certification_month'] == $monthId){ echo "selected"; } ?> value="<?php echo $monthId; ?>"><?php echo $monthName; ?></option>
							<?php		
								}
							?>
						</select>
					</div>
					<div class="error_div_sectn clearfix">
						<span class="error_msg" id="certification_month_error"></span>
					</div>
				</label>
				<label class="selectYear">
					<div class="default_dropdown_select">
						<select id="certification_year" name="certification_year">
							<option value=""><?php echo $user_certifications_section_date_acquired_select_year; ?></option>
							<?php
								for($y=$user_certifications_section_date_acquired_end_to; $y>=$user_certifications_section_date_acquired_start_from; $y--) {
							?>
									<option value="<?php echo $y ?>" <?php if(isset($certifications_data['certification_year']) && $certifications_data['certification_year'] == $y){ echo "selected"; } ?>><?php echo $y; ?></option>
							<?php	
								}
							?>
						</select>
					</div>
					<div class="error_div_sectn clearfix">
						<span class="error_msg" id="certification_year_error"></span>
					</div>
				</label>
				<div class="clearfix"></div>
			</label>
		</div>
		<!-- <div class="col-md-12 col-sm-12 col-12 certificate_popup_default_bottom_border"></div> -->
		<div class="col-sm-12 col-md-12 col-12">
			<?php 
				$upload_limit = $this->config->item('user_certifications_section_maximum_allowed_number_of_attachments');
				$upload_btn_display = 'inline-block';
				$drop_zone_class = 'drop_zone';
				if(!empty($certifications_data['attachments']) && count($certifications_data['attachments']) == $upload_limit) {
					$upload_btn_display = 'none';
					$drop_zone_class = '';
				}
			?>
			<div class="default_upload_btn <?php echo $drop_zone_class; ?>">
				<div id="overlay" class="d-none">
					<div class="text"><?php echo $this->config->item('drop_zone_drag_and_drop_files_area_message_txt') ?></div>
				</div>
				<div class="upload_file_wrapper" style="display:<?php echo $upload_btn_display; ?>">
					<input type="file" class="certificates_imgupload" accept="<?php echo $this->config->item('user_certifications_section_attachment_allowed_file_types'); ?>" style="display:none"/>
					<button type="button" class="OpenImgUpload btn default_btn blue_btn"><i class="fa fa-cloud-upload"></i><?php echo $this->config->item('user_certifications_section_upload_files_txt'); ?></button>
				</div><div class="certificate_attachment_wrapper"><?php if(!empty($certifications_data['attachments'])) {
							foreach($certifications_data['attachments'] as $file) {
								$filename = $this->config->item('user_certifications_section_uploaded_attachment_txt');
								$filearr = explode('.', $file['attachment_name']);
								$filename .= '.'.end($filearr);?><div class="default_download_attachment attachment_wrapper">
									<a class="download_certificate_attachments download_text" data-uid="<?php echo Cryptor::doEncrypt($certifications_data['user_id']); ?>" data-id="<?php echo $file['id']; ?>"><label><?php echo $filename; ?></label></a><label class="delete_icon 1"><a class="remove_uploaded_attachment" data-uid="<?php echo Cryptor::doEncrypt($certifications_data['user_id']); ?>" data-id="<?php echo $file['id']; ?>"><i class="fa fa-trash-o delete-btn" aria-hidden="true"></i></a></label>
									<div class="clearfix"></div>
								</div><?php
							}
						}
					?></div>
			</div>
			
			<div class="error_div_sectn clearfix"><span id="certificate_image_error" style="display:flex;" class="error_msg"></span></div>
		</div>
		
	</div>
	<div class="default_popup_close text-right">
		<!--<input type="hidden" id="blockStep" value="0">-->
		<div class="row">
			<div class="col-md-12 col-sm-12 rightButton"><button type="button" class="btn default_btn red_btn default_popup_width_btn btnCancel" data-dismiss="modal"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" class="btn default_btn blue_btn default_popup_width_btn btnSave" id="save_certifications"><?php echo $this->config->item('save_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button></div>
		</div>
	</div>
</form>

