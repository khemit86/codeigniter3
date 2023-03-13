<?php $user = $this->session->userdata ('user'); ?>
<!-- Step 1st Start -->
<div class="pmFirstStep" id="initialViewDescription" style="<?php if(empty($user_description)){ echo "display:block;";} else { echo "display:none;";} ?>">
	<div  class="default_hover_section_iconText mrgBtm0 closeHourlyrate">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12 fontSize0 default_bottom_border">
				<i class="far fa-copy"></i>
				<h6><?php
					if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
						echo $this->config->item('pa_profile_management_description_section_initial_view_title');
					}else if ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y') {
						echo $this->config->item('ca_app_profile_management_description_section_initial_view_title');
					} else {
						echo $this->config->item('ca_profile_management_description_section_initial_view_title');
					}
					?></h6>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<p><?php
					if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
						echo $this->config->item('pa_profile_management_description_section_initial_view_content');
					}else if ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y') {
						echo $this->config->item('ca_app_profile_management_description_section_initial_view_content');
					} else {
						echo $this->config->item('ca_profile_management_description_section_initial_view_content');
					}
					?></p>
			</div>
		</div>
	</div>
</div>
<!-- Step 1st End -->
<!-- Step 2nd Start -->
<div class="pmAllStep" style="display:none;" id="addeditDescription">
	<div  class="closeDescription pmtabtxt">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12 tlp0">
				<div class="form-group seperate">
					<textarea class="avoid_space_textarea default_textarea_field" name="user_description" id="user_description_input"   maxlength="<?php echo $this->config->item('profile_management_user_description_maximum_length_character_limit'); ?>" ><?php echo $user_description; ?></textarea>							
					<div class="error_div_sectn clearfix default_error_div_sectn">
						<span class="content-count user_description_length_count_message"><?php 
						if($this->config->item('profile_management_user_description_maximum_length_character_limit') - mb_strlen(trim(preg_replace('/\s+/',' ',$user_description))) >= 0){
							$user_description_remaining_characters = $this->config->item('profile_management_user_description_maximum_length_character_limit') - mb_strlen(trim(preg_replace('/\s+/',' ',$user_description)));
							}else{
						
							$user_description_remaining_characters = 0;
						}
						echo $user_description_remaining_characters. " ".$this->config->item('characters_remaining_message');
						//echo ($this->config->item('profile_management_user_description_maximum_length_character_limit') - strlen(trim($user_detail['description']))) . " " . $this->config->item('characters_remaining_message');
							
						?></span> 
						<span id="user_description_error" class="error_msg_description"></span>
					</div>
				</div>
				<div class="amBtn amBtnDescription">
					<button type="button" id="user_description_cancel" class="btn default_btn red_btn cancel_profile_definations" data-action-type ="cancel" data-section-name="user_description" data-view-section="viewDescription" data-add-edit-section="addeditDescription"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" id="save_description_button" class="btn default_btn blue_btn save_profile_definations" data-section-name="user_description" data-view-section="viewDescription" data-add-edit-section="addeditDescription"><?php echo $this->config->item('save_btn_txt'); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Step 2nd End -->
 <!-- Step 3rd Start -->
<div class="pmAllStep" id="viewDescription" style="<?php if(!empty($user_description)){ echo "display:block;"; }else{ echo "display:none;"; }?>">
	<div  class="closeDescription pmtabtxt">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12 tlp0">
				<div class="form-group">
					<textarea class="mesgDesc default_textarea_field" disabled="disabled" id="user_description_input_view" placeholder=""><?php echo $user_description; ?></textarea>
				</div>
				<div class="amBtn">
					<button type="button" class="btn default_btn red_btn delete_profile_definations" id="user_description_remove" data-section-name="user_description" data-view-section="viewDescription" data-add-edit-section="addeditDescription"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn edit_profile_definations" id="user_description_edit_btn" data-section-name="user_description" data-view-section="viewDescription" data-add-edit-section="addeditDescription" data-action-type ="edit"><?php echo $this->config->item('edit_btn_txt'); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Step 3rd End -->