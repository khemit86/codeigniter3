<div class="row">
	<div class="col-md-12 col-sm-12 col-12 cdTAb">
		<!-- <label for="pwd"><i class="fab fa-skype icon_modify" aria-hidden="true"></i><b class="default_black_bold"><?php //echo $this->config->item('account_management_contact_details_tab_skype'); ?></b></label> -->
		<div id="addeditSkype" style="display:<?php echo empty($skype_id)? "block":"none"; ?>">
			<div>
				<input type="text" class="form-control default_input_field avoid_space_input" id="skype_input" placeholder="<?php echo $this->config->item('account_management_contact_details_skype_id_input_placeholder'); ?>" value="" maxlength="<?php echo $this->config->item('account_management_contact_details_skype_id_characters_maximum_length_characters_limit'); ?>">
			</div>
			<div class="error_div_sectn clearfix"><span class="error_msg" id="skype_error"></span>
				<div class="amBtn">
					<button type="button" data-section-name="skype"  data-view-section="viewAcSkype" data-add-edit-section="addeditSkype" class="btn red_btn default_btn cancel_contact_details" data-action-type ="cancel"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" class="btn blue_btn default_btn save_contact_details" data-section-name="skype" id="save_skype_button"  data-view-section="viewAcSkype" data-add-edit-section="addeditSkype"><?php echo $this->config->item('save_btn_txt'); ?></button>
				</div>
			</div>
		</div>
		<div  id="viewAcSkype" style="display:<?php echo !empty($skype_id)? "block":"none"; ?>">
			<div>
				<input type="text" class="form-control default_input_field" id="skype_input_view"  disabled="disabled" value="<?php echo $skype_id; ?>">
			</div>
			<div class="amBtn amEditBtn">
				<button type="button" class="btn red_btn default_btn delete_contact_details" data-section-name="skype" data-view-section="viewAcSkype" data-add-edit-section="addeditSkype"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn edit_contact_details" data-action-type ="edit" data-section-name="skype" data-view-section="viewAcSkype" data-add-edit-section="addeditSkype"><?php echo $this->config->item('edit_btn_txt'); ?></button>
			</div>
		</div>
	</div>
</div>