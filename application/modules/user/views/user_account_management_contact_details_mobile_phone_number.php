<div class="row">
	<div class="col-md-12 col-sm-12 col-12 cdTAb">
		<!-- <label for="pwd"><i class="fas fa-mobile-alt icon_modify" aria-hidden="true"></i><b class="default_black_bold"><?php //echo $this->config->item('account_management_contact_details_tab_mobile_number'); ?></b></label> -->
		<div id="addeditMobilePhoneNo" style="display:<?php echo empty($mobile_phone_number)? "block":"none"; ?>">
			<div>
			<?php
				$input_mobile_hidden_value = '';
				 if(empty($mobile_phone_number)){
					$input_mobile_hidden_value = 'cz##(+420)';
				 }
				?>
				<input id="mobile_phone_number_input_hidden" type="hidden" name="mobile_phone_number_hidden_input" value="<?php echo $input_mobile_hidden_value; ?>">
				<input id="mobile_phone_number_input" maxlength ="<?php echo $this->config->item('account_management_contact_details_mobile_phone_number_characters_maximum_length_characters_limit') ?>"  class="form-control avoid_space default_input_field" name="phone" type="tel" placeholder="<?php echo $this->config->item('account_management_contact_details_mobile_phone_number_input_placeholder') ?>">
			</div>
			<div class="error_div_sectn clearfix"><span id="mobile_phone_number_error" class="error_msg"></span>
				<div class="amBtn">
					<button type="button" class="btn red_btn default_btn cancel_contact_details" data-action-type ="cancel" data-section-name="mobile_phone_number" data-view-section="viewMobilePhoneNo" data-add-edit-section="addeditMobilePhoneNo"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" class="btn blue_btn default_btn save_contact_details" id="save_mobile_phone_number_button" data-section-name="mobile_phone_number" data-view-section="viewMobilePhoneNo" data-add-edit-section="addeditMobilePhoneNo"><?php echo $this->config->item('save_btn_txt'); ?></button>
				</div>
			</div>
		</div>
		<div id="viewMobilePhoneNo" style="display:<?php echo !empty($mobile_phone_number)? "block":"none"; ?>">
			<div>
				<?php 
				$mobile_phone_number_input_value = '';
				if(isset($mobile_phone_number) && !empty($mobile_phone_number)){ 
					$mobile_phone_number_array = explode("##",$mobile_phone_number);
					$mobile_phone_number_input_value = $mobile_phone_number_array[1]." ".$mobile_phone_number_array[2];
				}
				?>
				<input type="text" class="form-control default_input_field" id="mobile_phone_number_input_view"  disabled="disabled" value="<?php echo $mobile_phone_number_input_value; ?>">
			</div>
			<div class="amBtn amEditBtn">
				<button type="button" class="btn red_btn default_btn delete_contact_details" data-section-name="mobile_phone_number" data-view-section="viewMobilePhoneNo" data-add-edit-section="addeditMobilePhoneNo"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn edit_contact_details" data-action-type ="edit" data-section-name="mobile_phone_number" data-view-section="viewMobilePhoneNo" data-add-edit-section="addeditMobilePhoneNo"><?php echo $this->config->item('edit_btn_txt'); ?></button>
			</div>
		</div>
	</div>
</div>