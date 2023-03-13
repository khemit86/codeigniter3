<!-- Step 1st Start -->
<div class="pmFirstStep" style="display:<?php echo empty($base_info['company_vision']) ? 'block' : 'none'; ?>">
  <div id="company_vision_initial" class="default_hover_section_iconText mrgBtm0 closeHourlyrate" >
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12 fontSize0 default_bottom_border">
        <i class="far fa-copy"></i>
        <h6><?php echo ($user_info['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_company_values_and_principles_vision_section_initial_view_title'):$this->config->item('ca_profile_management_company_values_and_principles_vision_section_initial_view_title'); ?></h6>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
        <p><?php echo ($user_info['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_company_values_and_principles_vision_section_initial_view_content'):$this->config->item('ca_profile_management_company_values_and_principles_vision_section_initial_view_content'); ?></p>
      </div>
    </div>
  </div>
</div>
<!-- Step 1st End -->
<?php 
  $edit_display = 'none';
  if(!empty($base_info['company_vision'])) {
    if($post_data['event'] == 'update') {
      $edit_display = 'block';
    }
  }
?>
<!-- Step 2nd Start -->
<div class="pmAllStep" style="display:<?php echo $edit_display; ?>">
  <div id="company_vision_edit_view" class="closeDescription">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-12 tlp0">
        <div class="form-group seperate">
          <textarea class="avoid_space_textarea default_textarea_field" name="company_vision_description" id="company_vision_description"  maxlength="<?php echo $this->config->item('ca_profile_management_company_vision_maximum_length_character_limit'); ?>" ><?php echo trim($base_info['company_vision']); ?></textarea>							
          <div class="error_div_sectn clearfix default_error_div_sectn">
            <span class="content-count user_description_length_count_message"><?php 
              if($this->config->item('ca_profile_management_company_vision_maximum_length_character_limit') - mb_strlen(trim($base_info['company_vision'])) >= 0){
                $user_description_remaining_characters = ($this->config->item('ca_profile_management_company_vision_maximum_length_character_limit') - mb_strlen(trim($base_info['company_vision'])));
              } else {
                $user_description_remaining_characters = 0;
              }
              echo $user_description_remaining_characters.' '.$this->config->item('characters_remaining_message'); ?>
            </span>
            <span id="company_vision_error" class="error_msg_description"></span>
          </div>
        </div>
        <div class="amBtn">
          <button type="button" id="company_vision_cancel" class="btn default_btn red_btn"><?php echo $this->config->item('cancel_btn_txt'); ?></button>
		  <button type="button" id="company_vision_save" class="btn default_btn blue_btn"><?php echo $this->config->item('save_btn_txt'); ?></button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Step 2nd End -->
<?php 
  $view_display = 'none';
  if(!empty($base_info['company_vision'])) {
    $view_display = 'block';
    if($post_data['event'] == 'update') {
      $view_display = 'none';
    }
  }
?>
  <!-- Step 3rd Start -->
<div class="pmAllStep" style="display:<?php echo $view_display; ?>">
  <div id="editDescription2" class="closeDescription">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-12 tlp0">
        <div class="form-group">
          <textarea class="mesgDesc default_textarea_field" disabled="disabled" ><?php echo $base_info['company_vision']; ?></textarea>
        </div>
        <div class="amBtn">
          <button type="button" class="btn default_btn red_btn" id="company_vision_remove"><?php echo $this->config->item('delete_btn_txt'); ?></button>
          <button type="button" class="btn green_btn default_btn" id="company_vision_edit"><?php echo $this->config->item('edit_btn_txt'); ?></button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Step 3rd End -->