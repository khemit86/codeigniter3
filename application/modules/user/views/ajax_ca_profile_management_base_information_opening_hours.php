<?php 
$user = $this->session->userdata('user');  
  $opening_hours_option_display = 'none';
  $opening_hour_option = '';
  if(!empty($opening_hours)) {
    $opening_hours_option_display  =  'block';
    $opening_hour_option = $opening_hours[0]['company_open_hours_status'];
    $selected_hours_checked = $opening_hours[0]['is_selected_hours_checked'];
  }
?>
<!-- Radio Section Start -->
<div id="opening_hours_option">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-12">
      <div class="radio_button_default fontSize0">
        <div>
			<label>
				<input type="checkbox" class="open_hours_checkbox"  value="selected_hours" <?php echo ($opening_hour_option == 'selected_hours' && $selected_hours_checked == 'Y') ? 'checked' : '' ?>>
				<span class="checkmark"></span>
				<small class="<?php echo ($opening_hour_option == 'selected_hours' && $selected_hours_checked == 'Y') ? 'boldCat' : '' ?>"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_opened_on_selected_hours_label'):$this->config->item('ca_profile_management_base_information_company_opened_on_selected_hours_label'); ?></small>
			</label>
        </div>
        <div>
			<label>
				<input type="checkbox" class="open_hours_checkbox"  value="always_opened" <?php echo $opening_hour_option == 'always_opened' ? 'checked' : '' ?>>
				<span class="checkmark"></span>
				<small class="<?php echo $opening_hour_option == 'always_opened' ? 'boldCat' : '' ?>"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_always_opened_label'):$this->config->item('ca_profile_management_base_information_company_always_opened_label'); ?></small>
			</label>
        </div>
        <div>
			<label>
				<input type="checkbox" class="open_hours_checkbox"  value="permanently_closed" <?php echo $opening_hour_option == 'permanently_closed' ? 'checked' : '' ?>>
				<span class="checkmark"></span>
				<small class="<?php echo $opening_hour_option == 'permanently_closed' ? 'boldCat' : '' ?>"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_permanently_closed_label'):$this->config->item('ca_profile_management_base_information_company_permanently_closed_label'); ?></small>
			</label>
        </div>
        <div>
			<label>
				<input type="checkbox" class="open_hours_checkbox"  value="telephone_appointment" <?php echo $opening_hour_option == 'telephone_appointment' ? 'checked' : '' ?>>
				<span class="checkmark"></span>
				<small class="<?php echo $opening_hour_option == 'telephone_appointment' ? 'boldCat' : '' ?>"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_telephone_appointment_label'):$this->config->item('ca_profile_management_base_information_company_telephone_appointment_label'); ?></small>
			</label>
		</div>
      </div>
    </div>
  </div>
</div>
<!-- Radio Section End -->
<!-- Radio Content Section Start -->
<div>
  <!-- Open on Select Hours Start -->
  <div class="openHours" style="display:<?php echo (!empty($opening_hour_option) && ($opening_hour_option == 'selected_hours' && $selected_hours_checked == 'Y')) ? 'block' : 'none'; ?>">
    <form id="opened_hours">
    <?php
      $weekdays = $this->config->item('calendar_weekdays_long_name');
      $opening_hours_dropdown = $this->config->item('ca_profile_management_base_information_company_opening_hours_dropdown_option');
      if(!empty($opening_hour_option) && $opening_hour_option == 'selected_hours') {
        $days = array_column($opening_hours, 'day');
      }
	 
      foreach($weekdays as $key => $day) {
        $checked = '';
        $open_time = '';
        $closing_time = '';
        if(!empty($days) && in_array($key, $days)) {
          $checked = 'checked';
          $idx = array_search($key, $days);
          $open_time = $opening_hours[$idx]['company_opening_time'];
          $closing_time = $opening_hours[$idx]['company_closing_time'];
        }
    ?>
    <div class="row openSecHours open_hours_wrapper">
      <div class="col-md-4 col-sm-4 col-12 baseOpeningDay">
        <div class="openingDay">
			<label class="default_checkbox">
				<input type="checkbox" class="days" name="days[]" value="<?php echo $key; ?>" <?php echo $checked; ?>>
				<span class="checkmark"></span>
				<small class="default_checkbox_text <?php echo !empty($checked) ? 'boldCat' : ''; ?>""><?php echo $day; ?></small>
			</label>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 col-6 ohFirst">
        <div class="default_dropdown_select">
          <select class="form-control opening_time" name="op[<?php echo $key; ?>]"  <?php echo empty($checked) ? 'disabled' : ''; ?>>
            <option value="" style="display:none"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_select_company_opening_time_option'):$this->config->item('ca_profile_management_base_information_select_company_opening_time_option'); ?></option>
            <?php
              foreach($opening_hours_dropdown as $k => $val) {
                $op_selected = '';
                if($open_time == $k) {
                  $op_selected = 'selected';
                }
                echo '<option value="'.$k.'"  '.$op_selected.'>'.$val.'</option>';
              }
            ?>
          </select>
          <div class="error_div_sectn clearfix" ><span id="op_<?php echo $key; ?>_error" class="error_msg "></span></div>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 col-6 ohLast">
        <div class="default_dropdown_select">
          <select class="form-control closing_time" name="cl[<?php echo $key; ?>]" <?php echo empty($checked) ? 'disabled' : ''; ?>>
            <option value="" style="display:none"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_select_company_closing_time_option'):$this->config->item('ca_profile_management_base_information_select_company_closing_time_option'); ?></option>
            <?php
              foreach($opening_hours_dropdown as $k => $val) {
                $cl_selected = '';
                if($closing_time == $k) {
                  $cl_selected = 'selected';
                }
                echo '<option value="'.$k.'" '.$cl_selected.'>'.$val.'</option>';
              }
            ?>
          </select>
          <div class="error_div_sectn clearfix"><span id="cl_<?php echo $key; ?>_error" class="error_msg "></span></div>
        </div>
      </div>
    </div>
    <?php
      }
    ?>
    </form>
  </div>
  <!-- Open on Select Hours End -->
  <div class="amBtn action_section" style="display:<?php echo $opening_hours_option_display; ?>">
    <div class="error_div_sectn clearfix text-left" style="display:<?php echo ($opening_hour_option == 'selected_hours' && empty($days)) ? 'inline-block' : 'none'; ?>"><span id="day_error" class="error_msg"></span></div>
    <button type="button" class="btn red_btn default_btn" style="display:<?php echo ($opening_hour_option == 'selected_hours' && empty($days)) ? 'inline-block' : 'none'; ?>" id="opening_hours_cancel"><?php echo $this->config->item('cancel_btn_txt'); ?></button>
    <button type="button" id="opening_hours_save" style="display:none" class="btn default_btn blue_btn"><?php echo $this->config->item('save_btn_txt'); ?></button>
    <button type="button" class="btn default_btn red_btn" style="display:<?php echo ($opening_hour_option == 'selected_hours' && !empty($days)) ? 'inline-block' : 'none'; ?>" id="opening_hours_remove"><?php echo $this->config->item('delete_btn_txt'); ?></button>
    <button type="button" class="btn green_btn default_btn" style="display:<?php echo ($opening_hour_option == 'selected_hours' && !empty($days)) ? 'inline-block' : 'none'; ?>" id="opening_hours_edit"><?php echo $this->config->item('edit_btn_txt'); ?></button>
  </div>
  
</div>
<!-- Radio Content Section End -->