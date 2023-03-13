<div class="row">
	<div class="col-md-12 col-sm-12 col-12 cdTAb">
		<!-- <label for="pwd"><i class="fa fa-phone icon_modify" aria-hidden="true"></i><b class="default_black_bold"><?php //echo $this->config->item(<!--'account_management_contact_details_tab_phone_number'); ?></b></label> -->
		<div id="addeditPhoneNo" style="display:<?php echo empty($phone_number)? "block":"none"; ?>">
			<div>
			
				<?php
				$input_phone_hidden_value = '';
				 if(empty($phone_number)){
					$input_phone_hidden_value = 'cz##(+420)';
				 }
				?>
				<input id="phone_number_input_hidden" type="hidden" name="phone_number_hidden_input" value="<?php echo $input_phone_hidden_value; ?>">
			
				<input id="phone_number_input" maxlength ="<?php echo $this->config->item('account_management_contact_details_phone_number_characters_maximum_length_characters_limit') ?>"  class="form-control avoid_space default_input_field" name="phone" type="tel" placeholder="<?php echo $this->config->item('account_management_contact_details_phone_number_input_placeholder') ?>">
			</div>
			<div class="error_div_sectn clearfix"><span class="error_msg" id="phone_number_error"></span>
				<div class="amBtn">
					<button type="button" class="btn red_btn default_btn cancel_contact_details" data-section-name="phone_number" data-view-section="viewPhoneNo" data-add-edit-section="addeditPhoneNo" data-action-type ="cancel"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" class="btn blue_btn default_btn save_contact_details" id="save_phone_number_button" data-section-name="phone_number" data-view-section="viewPhoneNo" data-add-edit-section="addeditPhoneNo"><?php echo $this->config->item('save_btn_txt'); ?></button>
				</div>
			</div>
		</div>
		<div id="viewPhoneNo" style="display:<?php echo !empty($phone_number)? "block":"none"; ?>">
			<div>
				<?php 
				$phone_number_input_value = '';
				if(!empty($phone_number)){ 
					$phone_number_array = explode("##",$phone_number);
					$phone_number_input_value = $phone_number_array[1]." ".$phone_number_array[2];
				}
				?>
				<input type="text" class="form-control default_input_field"  disabled="disabled" id="phone_number_input_view" value="<?php echo $phone_number_input_value; ?>">
			</div>
			<div class="amBtn amEditBtn">
				<button type="button" class="btn red_btn default_btn delete_contact_details"  data-section-name="phone_number" data-view-section="viewPhoneNo" data-add-edit-section="addeditPhoneNo"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn edit_contact_details" data-action-type ="edit" data-section-name="phone_number" data-view-section="viewPhoneNo" data-add-edit-section="addeditPhoneNo"><?php echo $this->config->item('edit_btn_txt'); ?></button>
			</div>
		</div>
	</div>
</div>