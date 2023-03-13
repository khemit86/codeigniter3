<!-- Step 1st Start -->
<?php
$user = $this->session->userdata('user');  	
?>	
<div class="pmFirstStep" style="display: <?php  echo (!empty($opening_hours) || empty($locations) ) ? 'none' : 'block';?>">
  <div id="opening_hours_inital" class="default_hover_section_iconText mrgBtm0 " >
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12 fontSize0 default_bottom_border">
        <i class="far fa-copy"></i>
        <h6><?php echo ($user_info['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_opening_hours_section_initial_view_title'):$this->config->item('ca_profile_management_base_information_company_opening_hours_section_initial_view_title'); ?></h6>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12" id="opening_hours_inital_content">
        <p><?php echo ($user_info['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_opening_hours_section_location_available_initial_view_content'):$this->config->item('ca_profile_management_base_information_company_opening_hours_section_location_available_initial_view_content'); ?></p>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12" style="display:none" id="opening_hours_inital_warning">
        <p><?php echo ($user_info['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_opening_hours_conflict_no_location_available_warning_message'):$this->config->item('ca_profile_management_base_information_company_opening_hours_conflict_no_location_available_warning_message'); ?></p>
      </div>
    </div>
  </div>
</div>
<!-- Step 1st End -->
<div class="pmFirstStep" id="no_location" style="display: <?php  echo empty($locations) ? 'block' : 'none';?>">
  <div class="default_hover_section_iconText mrgBtm0">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12 fontSize0 default_bottom_border">
        <i class="far fa-copy"></i>
        <h6><?php echo ($user_info['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_opening_hours_section_initial_view_title'):$this->config->item('ca_profile_management_base_information_company_opening_hours_section_initial_view_title'); ?></h6>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
        <p id="no_location_content"><?php echo ($user_info['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_opening_hours_section_location_not_available_initial_view_content'):$this->config->item('ca_profile_management_base_information_company_opening_hours_section_location_not_available_initial_view_content'); ?></p>
      </div>
    </div>
  </div>
</div>
<?php 
  $opening_hours_option_display = 'none';
  $opening_hour_option = '';
  if(!empty($opening_hours)) {
    $opening_hours_option_display  =  'block';
    $opening_hour_option = $opening_hours[0]['company_open_hours_status'];
  }
?>
<!-- Step 2nd Start -->
<div class="pmdonotSection pmFirstStep openingHoursOptionContainerWidth" style="display:<?php echo $opening_hours_option_display; ?>">
  <div class="default_country locationDrpMiddle">
    <label class="sltCountry">
      <div class="default_dropdown_select">
        <select name="location" class="location" id="location">
          <option value="" style="display:none;"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_select_company_location_option'):$this->config->item('ca_profile_management_base_information_select_company_location_option'); ?></option>
          <?php 
            if(!empty($locations)) {
              foreach ($locations as $key => $val){ 
          ?>
            <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
          <?php 
              } 
            }
          ?>
        </select>
      </div>
      <span class="error_msg " style="display:none" id="location_error"></span>
    </label>
    <!-- <label class="countryReset">
      <div class="reset_btn"><button class="btn btnRefresh red_btn default_btn reset_country" disabled id="reset_location" type="button"><?php echo $this->config->item('reset_btn_txt'); ?></button></div>
    </label> -->
    <div class="clearfix"></div>
  </div>
  <div class="default_bottom_border" id="location_btm_border" style="display:none;"></div>
  <div id="opening_hours_wrapper">
    
  </div>
</div>
<!-- Step 2nd End -->