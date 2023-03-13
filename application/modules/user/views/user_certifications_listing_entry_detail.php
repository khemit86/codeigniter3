<?php
$calendar_months = $this->config->item('calendar_months');
$user = $this->session->userdata('user');	
if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) { 
	$user_certifications_section_acquired_on = $this->config->item('pa_user_certifications_section_acquired_on'); 
} else {
	$user_certifications_section_acquired_on = $this->config->item('ca_user_certifications_section_acquired_on'); 
}	
?>
<div class="userLeft_section">
	<div class="certiText"><span class="certiSection"><b class="default_black_bold_medium"><?php echo htmlspecialchars($certifications_value['certification_name'], ENT_QUOTES); ?></b></span><span class="acquiredOn"><small class="default_black_regular_medium"><?php echo $user_certifications_section_acquired_on; ?></small><b class="default_black_bold_medium"><?php echo $calendar_months [$certifications_value['certification_month']]; ?> <?php echo $certifications_value['certification_year']; ?></b></span><div class="clearfix"></div></div>
	<div class="downloadPdf_section">
		<?php
			if(!empty($certifications_value['attachments'])) {
				foreach($certifications_value['attachments'] as $file) {
					$filename = $this->config->item('user_certifications_section_uploaded_attachment_txt');
					$filearr = explode('.', $file['attachment_name']);
					$filename .= '.'.end($filearr);
		?><div class="downloadPdf"><button class="download_certificate_attachments" data-uid="<?php echo Cryptor::doEncrypt($certifications_value['user_id']); ?>" data-id="<?php echo $file['id']; ?>"><i class="fas fa-download"></i> <?php echo $filename; ?></button></div><?php } }?></div></div>
<div class="userRight_section">
	<button class="btn default_icon_red_btn delete_certifications_confirmation" data-uid="<?php echo Cryptor::doEncrypt($certifications_value['user_id']); ?>" data-section-id = "<?php echo $certifications_value['id']; ?>"><span><?php echo $this->config->item('delete_btn_txt'); ?></span><i class="fas fa-trash-alt"></i></button><button class="btn default_icon_green_btn edit_certifications" data-attr ="edit" data-uid="<?php echo Cryptor::doEncrypt($certifications_value['user_id']); ?>"  data-section-id = "<?php echo $certifications_value['id']; ?>"><span><?php echo $this->config->item('edit_btn_txt'); ?></span><i class="fas fa-edit"></i></button>
</div>
<!-- <div class="weMobile">
	<div class="pmAction">
		<button type="button" class="btn green_btn default_btn"><?php echo $this->config->item('edit_btn_txt'); ?></button><button type="button" class="btn default_btn red_btn"><?php echo $this->config->item('delete_btn_txt'); ?></button>
	</div>
</div> -->
<div class="clearfix"></div>