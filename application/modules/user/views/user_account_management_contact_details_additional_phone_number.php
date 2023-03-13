<div class="row">
	<div class="col-md-12 col-sm-12 col-12 cdTAb">
		<!-- <label for="pwd"><i class="fas fa-fax icon_modify" aria-hidden="true"></i><b class="default_black_bold"><?php //echo $this->config->item('account_management_contact_details_tab_alt_phone_number'); ?></b></label> -->
		<div id="addeditAdditionalPhoneNo" style="display:<?php echo empty($additional_phone_number)? "block":"none"; ?>">
			<div>
				<?php
				$input_hidden_value = '';
				 if(empty($additional_phone_number)){
					$input_hidden_value = 'cz##(+420)';
				 }
				?>
				<input id="additional_phone_number_input_hidden" type="hidden" name="additional_phone_number_hidden_input" value="<?php echo $input_hidden_value; ?>">
				<input id="additional_phone_number_input" maxlength ="<?php echo $this->config->item('account_management_contact_details_additional_phone_number_characters_maximum_length_characters_limit') ?>" class="form-control avoid_space default_input_field" name="phone" type="tel" placeholder="<?php echo $this->config->item('account_management_contact_details_additional_phone_number_input_placeholder') ?>">
			</div>
			<div class="error_div_sectn clearfix"><span class="error_msg" id="additional_phone_number_error"></span>
				<div class="amBtn">
					<button type="button" class="btn red_btn default_btn cancel_contact_details" data-action-type ="cancel" data-section-name="additional_phone_number" data-view-section="viewAdditionalPhoneNo" data-add-edit-section="addeditAdditionalPhoneNo"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" class="btn blue_btn default_btn save_contact_details" id="save_additional_phone_number_button" data-section-name="additional_phone_number" data-view-section="viewAdditionalPhoneNo" data-add-edit-section="addeditAdditionalPhoneNo"><?php echo $this->config->item('save_btn_txt'); ?></button>
				</div>
			</div>
		</div>
		<div id="viewAdditionalPhoneNo" style="display:<?php echo !empty($additional_phone_number)? "block":"none"; ?>">
			<?php 
			$additional_phone_number_input_value = '';
			if(isset($additional_phone_number) && !empty($additional_phone_number)){ 
				$additional_phone_number_array = explode("##",$additional_phone_number);
				$additional_phone_number_input_value = $additional_phone_number_array[1]." ".$additional_phone_number_array[2];
			}
			?>
			<div>
				<input id="additional_phone_number_input_view" type="text" class="form-control default_input_field" disabled="disabled"  value="<?php echo $additional_phone_number_input_value; ?>">
			</div>
			<div class="amBtn amEditBtn">
				<button type="button" class="btn red_btn default_btn delete_contact_details" data-section-name="additional_phone_number" data-view-section="viewAdditionalPhoneNo" data-add-edit-section="addeditAdditionalPhoneNo"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn edit_contact_details" data-action-type ="edit" data-section-name="additional_phone_number" data-view-section="viewAdditionalPhoneNo" data-add-edit-section="addeditAdditionalPhoneNo"><?php echo $this->config->item('edit_btn_txt'); ?></button>
			</div>
		</div>
	</div>
</div>