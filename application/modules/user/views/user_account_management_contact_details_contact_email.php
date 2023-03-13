<div class="row">
	<div class="col-md-12 col-sm-12 col-12 cdTAb">
		<!-- <label for="pwd"><i class="fas fa-envelope icon_modify" aria-hidden="true"></i><b class="default_black_bold"><?php //echo $this->config->item('account_management_contact_details_tab_contact_email'); ?></b></label> -->
		<div id="addeditConEmail" style="display:<?php echo empty($contact_email)? "block":"none"; ?>">
			<div>
				<input type="text" id="contact_email_input" class="form-control default_input_field avoid_space_input" placeholder="<?php echo $this->config->item('account_management_contact_details_contact_email_input_placeholder'); ?>" value="">
			</div>
			<div class="error_div_sectn clearfix"><span class="error_msg" id="contact_email_error"></span>
				<div class="amBtn">
					<button type="button" data-section-name="contact_email"  data-view-section="viewAcConEmail" data-add-edit-section="addeditConEmail" class="btn red_btn default_btn cancel_contact_details" data-action-type ="cancel"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" class="btn blue_btn default_btn save_contact_details" data-section-name="contact_email" id="save_contact_email_button"  data-view-section="viewAcConEmail" data-add-edit-section="addeditConEmail"><?php echo $this->config->item('save_btn_txt'); ?></button>
				</div>
			</div>
		</div>
		<div id="viewAcConEmail" style="display:<?php echo !empty($contact_email)? "block":"none"; ?>">
			<div>
				<input type="text" id="contact_email_input_view" class="form-control default_input_field" disabled="disabled" value="<?php echo $contact_email; ?>">
			</div>
			<div class="amBtn amEditBtn">
				<button type="button" class="btn red_btn default_btn delete_contact_details" data-section-name="contact_email" data-view-section="viewAcConEmail" data-add-edit-section="addeditConEmail"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn edit_contact_details" data-action-type ="edit" data-section-name="contact_email" data-view-section="viewAcConEmail" data-add-edit-section="addeditConEmail"><?php echo $this->config->item('edit_btn_txt'); ?></button>
			</div>
		</div>
	</div>
</div>