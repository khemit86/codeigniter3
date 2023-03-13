<?php
$user = $this->session->userdata('user');
?>
<!-- Step 1st Start -->
<div class="pmFirstStep" id="initialViewHeadline" style="<?php if(empty($headline)){ echo "display:block;";} else { echo "display:none;";} ?>">
	<div class="default_hover_section_iconText emailNew mrgBtm0 closeHeadline">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12 fontSize0 default_bottom_border">
				<i class="far fa-comment-alt"></i>
				<h6><?php
					if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
						echo $this->config->item('pa_profile_management_headline_section_initial_view_title');
					}else if ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y') {
						echo $this->config->item('ca_app_profile_management_headline_section_initial_view_title');
					} else {
						echo $this->config->item('ca_profile_management_headline_section_initial_view_title');
					}
					?></h6>
			</div>
			<div class="col-md-12 col-sm-12 col-12">
				<p><?php
					if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
						echo $this->config->item('pa_profile_management_headline_section_initial_view_content');
					} else if ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y') {
						echo $this->config->item('ca_app_profile_management_headline_section_initial_view_content');
					}else {
						echo $this->config->item('ca_profile_management_headline_section_initial_view_content');
					}
					?></p>
			</div>
		</div>
	</div>
</div>
<!-- Step 1st End -->
<div class="pmAllStep" style="display:none;" id="addeditHeadline">
	<div class="row pmtabtxt">
		<div class="col-md-12 col-sm-12 col-12">
			<div class="form-group seperate">
				<!--<input type="text" name="headline" id="headline_input" class="form-control avoid_space default_input_field" value="<?php echo htmlspecialchars($headline, ENT_QUOTES); ?>"  maxlength="<?php echo $this->config->item('profile_management_user_headline_maximum_length_character_limit'); ?>">	
				<div class="error_div_sectn clearfix default_error_div_sectn">
					<span class="content-count headline_length_count_message"><?php echo ($this->config->item('profile_management_user_headline_maximum_length_character_limit') - mb_strlen($user_detail['headline'])) . " " . $this->config->item('characters_remaining_message'); ?></span> 
					<span id="headline_error" class="error_msg"></span>
				</div>-->

				<textarea class="avoid_space_textarea default_textarea_small_field" name="headline" id="headline_input" maxlength="<?php echo $this->config->item('profile_management_user_headline_maximum_length_character_limit'); ?>" ><?php echo $headline; ?></textarea>
				<div class="error_div_sectn clearfix default_error_div_sectn">
					<span class="content-count headline_length_count_message"><?php 
					if($this->config->item('profile_management_user_headline_maximum_length_character_limit') - mb_strlen(trim(preg_replace('/\s+/',' ',$headline))) >= 0){
						$user_headline_remaining_characters = $this->config->item('profile_management_user_headline_maximum_length_character_limit') - mb_strlen(trim(preg_replace('/\s+/',' ',$headline)));
						}else{
					
						$user_headline_remaining_characters = 0;
					}
					echo $user_headline_remaining_characters. " ".$this->config->item('characters_remaining_message');?></span> 
					<span id="headline_error" class="error_msg"></span>
				</div>
			</div>
			<div class="amBtn">
				<button type="button" class="btn default_btn red_btn cancel_profile_definations" data-action-type ="cancel" data-section-name="headline" data-view-section="viewHeadline" data-add-edit-section="addeditHeadline"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" id ="user_headline_save" class="btn blue_btn default_btn save_profile_definations" id="save_headline_button" data-section-name="headline" data-view-section="viewHeadline" data-add-edit-section="addeditHeadline"><?php echo $this->config->item('save_btn_txt'); ?></button>
			</div>
		</div>
	</div>
</div>
<div class="pmAllStep" style="<?php if(!empty($headline)){ echo "display:block;"; }else{ echo "display:none;"; }?>" id="viewHeadline">
	<div class="row pmtabtxt">
		<div class="col-md-12 col-sm-12 col-12">
			<div class="form-group">
				<!--<div id="headline_input_view"><?php echo $headline; ?></div>-->
				<!--<input type="text" id="headline_input_view" class="form-control default_input_field" disabled="disabled" value="<?php echo $headline; ?>" placeholder="">-->
				<textarea class="default_textarea_small_field" disabled="disabled" id="headline_input_view" placeholder=""><?php echo $headline; ?></textarea>
			</div>
			<div class="amBtn">
				<button type="button" class="btn default_btn red_btn delete_profile_definations" data-section-name="headline" data-view-section="viewHeadline" data-add-edit-section="addeditHeadline"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn edit_profile_definations" data-action-type ="edit" data-section-name="headline" data-view-section="viewHeadline" data-add-edit-section="addeditHeadline"><?php echo $this->config->item('edit_btn_txt'); ?></button>
			</div>
		</div>
	</div>
</div>