<!-- Step 1st Start -->
<div class="pmFirstStep" style="display:<?php echo $base_info['company_year_founded'] != null ? 'none' : 'block'  ?>">
  <div id="editHourlyrate" class="default_hover_section_iconText mrgBtm0 closeHourlyrate">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-12 fontSize0 default_bottom_border">
        <i class="far fa-copy"></i>
        <h6><?php echo ($user_info['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_founded_in_section_initial_view_title'):$this->config->item('ca_profile_management_base_information_company_founded_in_section_initial_view_title'); ?></h6>
      </div>
      <div class="col-md-12 col-sm-12 col-12">
        <p><?php echo ($user_info['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_founded_in_section_initial_view_content'):$this->config->item('ca_profile_management_base_information_company_founded_in_section_initial_view_content'); ?></p>
      </div>
    </div>
  </div>
</div>
<!-- Step 1st End -->
<?php 
  $edit_display = 'none';
  if($base_info['company_year_founded'] != null) {
    if($post_data['event'] == 'update') {
      $edit_display = 'block';
    }
  }
?>

<!-- Step 2nd Start -->
<div class="pmdonotSection pmFirstStep saveFounded" style="display:<?php echo $edit_display; ?>;">
  <div id="save_founded_in" class="closeDescription">
    <div class="row">
		<div class="col-md-12 col-sm-12 col-12">
			<div class="baseInfo_hrInput">
				<div class="hrInput">
					<div class="form-group default_dropdown_select soloOwner">
						<select class="form-control" name="founded_in" id="founded_in">
							<option value="" class="d-none"><?php echo $this->config->item('ca_profile_management_base_information_year_select_year'); ?></option>
							<?php 
								for($i = $this->config->item('ca_profile_management_base_information_year_end_to'); $i>=$this->config->item('ca_profile_management_base_information_year_start_from'); $i--) {
								  $selected = '';
								  if($base_info['company_year_founded'] != null && $base_info['company_year_founded'] == $i ) {
									$selected = 'selected';
								  }
								  echo '<option value="'.$i.'" '.$selected.' >'.$i.'</option>';
								}
							  ?>
						</select>
						<div class="error_div_sectn clearfix" >
						  <span id="error_msg_founded_in" class="error_msg_founded_in error_msg"></span>
						</div>
					</div>
				</div>
				<div class="amBtn" >
				  <button type="button" class="btn default_btn red_btn" id="founded_in_cancel"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" id="founded_in_save" class="btn blue_btn default_btn"><?php echo $this->config->item('save_btn_txt'); ?></button>
				</div>
			</div>
		</div>
    </div>
  </div>
</div>
<!-- Step 2nd End -->
<?php 
  $view_display = 'none';
  if($base_info['company_year_founded'] != null) {
    $view_display = 'block';
    if($post_data['event'] == 'update') {
      $view_display = 'none';
    }
  }
?>
<!-- Step 3rd Start -->
<div class="pmdonotSection pmFirstStep saveFounded" style="display:<?php echo $view_display;  ?>;">
  <div id="edit_founded_in" class="closeDescription">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-12">
		<div class="baseInfo_hrInput">
			<div class="hrInput">
				<div class="form-group">
					<input type="text" class="form-control default_input_field" id="founded_in_val" value="<?php echo $base_info['company_year_founded']; ?>" disabled="disabled">
				</div>
			</div>
			<div class="amBtn hrRate">
				<button type="button" class="btn default_btn red_btn" id="remove_founded_in"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn" id="update_founded_in"><?php echo $this->config->item('edit_btn_txt'); ?></button>
			</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Step 3rd End -->