<?php
$user = $this->session->userdata('user');
?>
<!-- Step 1st Start -->
<div class="pmFirstStep" id="initialViewHourlyrate" style="<?php if(empty($user_hourly_rate)){ echo "display:block;";} else { echo "display:none;";} ?>">
	<div class="default_hover_section_iconText mrgBtm0 closeHourlyrate">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12 fontSize0 default_bottom_border">
				<i class="fas fa-user-clock"></i>
				<h6><?php
					if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
						echo $this->config->item('pa_profile_management_hourly_rate_section_initial_view_title');
					}else if ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y') {
						echo $this->config->item('ca_app_profile_management_hourly_rate_section_initial_view_title');
					} else {
						echo $this->config->item('ca_profile_management_hourly_rate_section_initial_view_title');
					}
					?></h6>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<p><?php
					if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
						echo $this->config->item('pa_profile_management_hourly_rate_section_initial_view_content');
					}else if ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y') {
						echo $this->config->item('ca_app_profile_management_hourly_rate_section_initial_view_content');
					} else {
						echo $this->config->item('ca_profile_management_hourly_rate_section_initial_view_content');
					}
					?></p>
			</div>
		</div>
	</div>
</div>
<!-- Step 1st End -->
 <!-- Step 2nd Start -->
<div class="pmdonotSection pmAllStep" id="addeditHourlyrate" style="display:none;" >
	<div  class="closeHourlyrate">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="input-group hrInput">
					<input type="text" id="user_hourly_rate_input" name="user_hourly_rate" class="form-control allow_number avoid_space default_input_field" maxlength="<?php echo $this->config->item('profile_management_user_hourly_rate_max_digit'); ?>">
					<div class="input-group-append">
						<span class="input-group-text default_black_bold_medium"><?php echo CURRENCY . $this->config->item('profile_management_user_hourly_rate_per_hour'); ?></span>
					</div>
					<div class="error_div_sectn clearfix">
						<span id="user_hourly_rate_error" class="error_msg_hourly_rate error_msg"></span>
					</div>
				</div>
				<div class="amBtn hrRate">
					<button type="button" class="btn default_btn red_btn cancel_profile_definations" data-action-type ="cancel" data-section-name="user_hourly_rate" data-view-section="viewHourlyrate" data-add-edit-section="addeditHourlyrate" id="user_hourly_rate_cancel"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" id="save_hourly_rate_button" class="btn blue_btn default_btn save_profile_definations" data-section-name="user_hourly_rate" data-view-section="viewHourlyrate" data-add-edit-section="addeditHourlyrate"><?php echo $this->config->item('save_btn_txt'); ?></button>
				</div>
			</div>
		</div> 
	</div>
</div>
<!-- Step 2nd End -->
<!-- Step 3rd Start --> 
<div class="pmdonotSection pmAllStep" id="viewHourlyrate" style="<?php if(!empty($user_hourly_rate)){ echo "display:block;"; }else{ echo "display:none;"; }?>">
	<div  class="closeHourlyrate">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12">
				<div class="input-group hrInput">
					<input type="text" class="form-control default_input_field" disabled="disabled" id="user_hourly_rate_input_view" value="<?php echo str_replace(".00","",number_format($user_hourly_rate,  2, '.', ' ')); ?>">
					<div class="input-group-append">
						<span class="input-group-text default_black_bold_medium"><?php echo CURRENCY . $this->config->item('profile_management_user_hourly_rate_per_hour'); ?></span>
					</div>
				</div>
				<div class="amBtn hrRate">
					<button type="button" class="btn default_btn red_btn delete_profile_definations" id="user_hourly_rate_remove" data-section-name="user_hourly_rate" data-view-section="viewHourlyrate" data-add-edit-section="addeditHourlyrate"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn edit_profile_definations" id="user_hourly_rate_edit_btn" data-section-name="user_hourly_rate" data-view-section="viewHourlyrate" data-add-edit-section="addeditHourlyrate" data-action-type ="edit"><?php echo $this->config->item('edit_btn_txt'); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Step 3rd End -->	