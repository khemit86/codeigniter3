<div class="pmdonotSection pmFirstStep companyInvoicing">

  <div class="streetAddress default_bottom_border default_black_regular_medium">
    <?php
		$non_edit_view = '';
      if(empty($invoicing_details)) {
        echo $user_data['is_authorized_physical_person'] == 'Y' ?  $this->config->item('company_app_invoicing_details_edit_view_top_heading_txt') : $this->config->item('company_invoicing_details_edit_view_top_heading_txt');
      } else {
        echo $user_data['is_authorized_physical_person'] == 'Y' ? $this->config->item('company_app_invoicing_details_non_edit_view_top_heading_txt') : $this->config->item('company_invoicing_details_non_edit_view_top_heading_txt');
		$non_edit_view = ' non_edit_view';
      }
    ?></div>
  <form id="fm_invoicing_details">
  <!-- Company Name Start -->
  <div class="streetAddress">
    <label class="default_black_bold_medium pointer_events_auto streetTitle"><?php echo $user_data['is_authorized_physical_person'] == 'Y' ? $this->config->item('company_app_invoicing_details_company_name_lbl') : $this->config->item('company_invoicing_details_company_name_lbl'); ?></label>
    <div class="streetInput">
    <div class="inputDiv" style="display:<?php echo !empty($invoicing_details['company_name']) ? 'block' : 'none' ?>"><?php echo !empty($invoicing_details['company_name']) ? $invoicing_details['company_name'] : ''; ?></div>
      <input type="text" id="company_name" name="company_name" style="display:<?php echo !empty($invoicing_details['company_name']) ? 'none' : 'block' ?>" value="<?php echo !empty($invoicing_details['company_name']) ? $invoicing_details['company_name'] : ''; ?>" class="form-control avoid_space_textarea avoid_space_street_address avoid_space default_input_field" maxlength="<?php echo $this->config->item('company_invoicing_details_company_name_maximum_length_character_limit'); ?>">    
    </div>
    <div class="error_div_sectn clearfix default_error_div_sectn<?php echo $non_edit_view; ?>">
      <span class="content-count company_name_count_message" style="display:<?php echo !empty($invoicing_details['company_name']) ? 'none' : 'block' ?>"><?php echo $this->config->item('company_invoicing_details_company_name_maximum_length_character_limit'); ?> <?php echo $this->config->item('characters_remaining_message'); ?></span>
      <span id="company_name_error" class="error_msg"></span>
    </div>
  </div>
  <!-- Company Name End -->
  
  <!-- Company Address Start -->
  <div class="streetAddress">
    <label class="default_black_bold_medium pointer_events_auto streetTitle"><?php echo $user_data['is_authorized_physical_person'] == 'Y' ? $this->config->item('company_app_invoicing_details_company_address_lbl') : $this->config->item('company_invoicing_details_company_address_lbl'); ?></label>
    <div class="streetInput">
		<div class="inputDiv" style="display:<?php echo !empty($invoicing_details['company_address_line_1']) ? 'block' : 'none' ?>"><?php echo !empty($invoicing_details['company_address_line_1']) ? $invoicing_details['company_address_line_1'] : ''; ?></div>
      <input type="text" id="company_address_1" name="company_address_1" style="display:<?php echo !empty($invoicing_details['company_address_line_1']) ? 'none' : 'block' ?>" value="<?php echo !empty($invoicing_details['company_address_line_1']) ? $invoicing_details['company_address_line_1'] : '' ?>" class="form-control avoid_space_textarea avoid_space_street_address avoid_space default_input_field" maxlength="<?php echo $this->config->item('company_invoicing_details_company_address_line_1_maximum_length_character_limit'); ?>">
    </div>
    <div class="error_div_sectn clearfix default_error_div_sectn<?php echo $non_edit_view; ?>">
      <span class="content-count company_address_1_count_message" style="display:<?php echo !empty($invoicing_details['company_address_line_1']) ? 'none' : 'block' ?>"><?php echo $this->config->item('company_invoicing_details_company_address_line_1_maximum_length_character_limit'); ?> <?php echo $this->config->item('characters_remaining_message'); ?></span>
      <span id="company_address_1_error" class="error_msg"></span>
    </div>
    <div class="streetInput second_address">
		<div class="inputDiv" style="display:<?php echo !empty($invoicing_details['company_address_line_2']) ? 'block' : 'none' ?>"><?php echo !empty($invoicing_details['company_address_line_2']) ? $invoicing_details['company_address_line_2'] : ''; ?></div>
      <input type="text" id="company_address_2" name="company_address_2" style="display:<?php echo !empty($invoicing_details['company_address_line_2']) ? 'none' : 'block' ?>" value="<?php echo !empty($invoicing_details['company_address_line_2']) ? $invoicing_details['company_address_line_2'] : '' ?>" class="form-control avoid_space_textarea avoid_space_street_address avoid_space default_input_field" maxlength="<?php echo $this->config->item('company_invoicing_details_company_address_line_2_maximum_length_character_limit'); ?>">
    </div>
    <div class="error_div_sectn clearfix default_error_div_sectn<?php echo $non_edit_view; ?>">
      <span class="content-count company_address_2_count_message" style="display:<?php echo !empty($invoicing_details['company_address_line_2']) ? 'none' : 'block' ?>"><?php echo $this->config->item('company_invoicing_details_company_address_line_2_maximum_length_character_limit'); ?> <?php echo $this->config->item('characters_remaining_message'); ?></span>
      <span id="company_address_2_error" class="error_msg"></span>
    </div>
  </div>
  <!-- Company Address End -->
  
  <!-- Select Country Start -->
  <?php 
    $country_label = $user_data['is_authorized_physical_person'] == 'Y' ? $this->config->item('company_app_invoicing_details_select_country_lbl') : $this->config->item('company_invoicing_details_select_country_lbl');
  ?>
  <div class="default_country default_bottom_border countrySectionPart">
    <label class="default_black_bold_medium <?php echo !empty($invoicing_details['company_country']) ? 'streetTitle' : 'countryText' ?>"><div><?php echo $country_label; ?></div></label>
    <div class="streetInput" style="display:<?php echo !empty($invoicing_details['company_country']) ? 'block' : 'none' ?>">
      <?php 
        foreach($countries as $val) {
          $selected = '';
          if(!empty($invoicing_details['company_country']) && $invoicing_details['company_country'] == $val['id']) {
           $country_name = $val['country_name'];
          }
        }
      ?>
      <input type="text" class="default_input_field" disabled value="<?php echo !empty($invoicing_details['company_country']) ? $country_name : '' ?>" >
    </div>
    <label class="sltCountry" style="display:<?php echo !empty($invoicing_details['company_country']) ? 'none' : 'block' ?>">
      <div class="default_dropdown_select">
        <select name="company_country" id="company_country" >
          <option value="" style="display:none;"><?php echo $this->config->item('select_country'); ?></option>
          <?php 
            foreach($countries as $val) {
              $selected = '';
              if(!empty($invoicing_details['company_country']) && $invoicing_details['company_country'] == $val['id']) {
                $selected = 'selected';
              }
          ?>
          <option value="<?php echo $val['id']; ?>" <?php echo $selected; ?>><?php echo $val['country_name']; ?></option>
          <?php
            }
          ?>
          
        </select>
        <div class="error_div_sectn clearfix country_error_div">
          <span id="company_country_error" class="error_msg"></span>
        </div>
      </div>
    </label>
    <div class="clearfix"></div>
  </div>
  <!-- Select Country End -->
  
  <!-- Registration and VAT Number Start -->
  <div class="">
    <div class="row">
      <div class="col-md-6 col-sm-6 col-12 invoceReg">
        <!-- Registration Number Start -->
        <div class="streetAddress">
          <label class="default_black_bold_medium pointer_events_auto streetTitle"><?php echo $user_data['is_authorized_physical_person'] == 'Y' ? $this->config->item('company_app_invoicing_details_company_registration_number_lbl') : $this->config->item('company_invoicing_details_company_registration_number_lbl'); ?></label>
          <div class="streetInput">
			<div class="inputDiv" style="display:<?php echo !empty($invoicing_details['company_registration_number']) ? 'block' : 'none' ?>"><?php echo !empty($invoicing_details['company_registration_number']) ? $invoicing_details['company_registration_number'] : ''; ?></div>
			
            <input type="text" id="company_registration_number" style="display:<?php echo !empty($invoicing_details['company_registration_number']) ? 'none' : 'block' ?>" value="<?php echo !empty($invoicing_details['company_registration_number']) ? $invoicing_details['company_registration_number'] : '' ?>" name="company_registration_number" class="form-control avoid_space_textarea avoid_space avoid_space_street_address default_input_field" maxlength="<?php echo $this->config->item('company_invoicing_details_company_registration_number_maximum_length_character_limit'); ?>">
          </div>
          <div class="error_div_sectn clearfix default_error_div_sectn" style="display:<?php echo !empty($invoicing_details) ? 'none' : 'block' ?>" >
            <span class="content-count company_registration_number_count_message" style="display:<?php echo !empty($invoicing_details['company_registration_number']) ? 'none' : 'block' ?>"><?php echo $this->config->item('company_invoicing_details_company_registration_number_maximum_length_character_limit'); ?> <?php echo $this->config->item('characters_remaining_message'); ?></span>
            <span id="company_registration_number_error" style="display:<?php echo !empty($invoicing_details) ? 'none' : 'inline' ?>" class="error_msg"></span>
          </div>
        </div>
        <!-- Registration Number End -->
      </div>
      <div class="col-md-6 col-sm-6 col-12 invoceVat">
        <!-- VAT Number Start -->
        <div class="streetAddress">
          <label class="default_black_bold_medium pointer_events_auto streetTitle" style="display:<?php echo (!empty($invoicing_details['company_not_vat_registered']) && $invoicing_details['company_not_vat_registered'] == 'Y') ? 'none' : '' ?>"><?php echo $user_data['is_authorized_physical_person'] == 'Y' ? $this->config->item('company_app_invoicing_details_company_vat_number_lbl') : $this->config->item('company_invoicing_details_company_vat_number_lbl'); ?></label>
          <div class="streetInput" style="display:<?php echo (!empty($invoicing_details['company_not_vat_registered']) && $invoicing_details['company_not_vat_registered'] == 'Y') ? 'none' : '' ?>">
			<div class="inputDiv" style="display:<?php echo !empty($invoicing_details['company_vat_number']) ? 'block' : 'none' ?>"><?php echo !empty($invoicing_details['company_vat_number']) ? $invoicing_details['company_vat_number'] : ''; ?></div>
			
            <input type="text" id="company_vat_number" name="company_vat_number" style="display:<?php echo !empty($invoicing_details) ? 'none' : 'block' ?>" value="<?php echo !empty($invoicing_details['company_vat_number']) ? $invoicing_details['company_vat_number'] : '' ?>" class="form-control avoid_space avoid_space_textarea avoid_space_street_address default_input_field" maxlength="<?php echo $this->config->item('company_invoicing_details_company_vat_number_maximum_length_character_limit'); ?>">
          </div>
          <div class="error_div_sectn clearfix default_error_div_sectn" style="display:<?php echo !empty($invoicing_details) ? 'none' : 'block' ?>">
            <span class="content-count company_vat_number_count_message" style="display:<?php echo !empty($invoicing_details) ? 'none' : 'block' ?>"><?php echo $this->config->item('company_invoicing_details_company_vat_number_maximum_length_character_limit'); ?> <?php echo $this->config->item('characters_remaining_message'); ?></span>
            <span id="company_vat_number_error" class="error_msg"></span>
          </div>
          <div class="srchName <?php echo (!empty($invoicing_details['company_not_vat_registered']) && $invoicing_details['company_not_vat_registered'] == 'Y') ? 'marginTop21' : '' ?>"  style="margin-top:0;display:<?php echo (!empty($invoicing_details['company_not_vat_registered']) && $invoicing_details['company_not_vat_registered'] == 'N') ? 'none' : '' ?>">
            <div class="d-inline-block tHistory_label">
              <div class="form-check">
                <label class="default_checkbox">
                  <input type="checkbox" id="no_vat" name="no_vat" value="Y" <?php echo (!empty($invoicing_details['company_not_vat_registered']) && $invoicing_details['company_not_vat_registered'] == 'Y') ? 'checked' : '' ?> <?php echo !empty($invoicing_details['company_not_vat_registered']) ? 'disabled' : '' ?>><span class="checkmark"></span>
                <span class="textGap default_black_bold_medium"><?php echo $user_data['is_authorized_physical_person'] == 'Y' ? $this->config->item('company_app_invoicing_details_company_no_vat_registered_lbl') : $this->config->item('company_invoicing_details_company_no_vat_registered_lbl'); ?></span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <!-- VAT Number End -->
      </div>
    </div>
  </div>
  <!-- Registration and VAT Number End -->
  </form>
  
  <!-- No Record and Record List Start -->
  <div class="display_wrapper" style="display:<?php echo empty($invoicing_details) ? 'none' : 'block' ?>">
    <div class="default_bottom_border default_black_regular_medium">
        <?php
          if(empty($invoicing_details)) {
            echo $user_data['is_authorized_physical_person'] == 'Y' ? $this->config->item('company_app_invoicing_details_edit_view_bottom_heading_txt') : $this->config->item('company_invoicing_details_edit_view_bottom_heading_txt');
          } else {
            echo $user_data['is_authorized_physical_person'] == 'Y' ? $this->config->item('company_app_invoicing_details_non_edit_view_bottom_heading_txt') : $this->config->item('company_invoicing_details_non_edit_view_bottom_heading_txt');
          }
        ?></div>
    <div class="idRecord">
      <p class="default_black_regular_medium compColor" id="company_name_display"><?php echo $invoicing_details['company_name']; ?></p>
      <p class="default_black_regular_medium" id="company_address_1_display"><?php echo $invoicing_details['company_address_line_1']; ?></p>
      <p class="default_black_regular_medium" id="company_address_2_display"><?php echo $invoicing_details['company_address_line_2']; ?></p>
      <p class="default_black_regular_medium" id="company_country_display"><?php echo $invoicing_details['country_name']; ?></p>
      <p class="default_black_regular_medium" id="company_registration_number_display"><?php echo $invoicing_details['company_registration_number']; ?></p>
      <p class="default_black_regular_medium" id="company_vat_number_display"><?php echo $invoicing_details['company_vat_number']; ?></p>
    </div>
  </div>
  <!-- No Record and Record List End -->
  
  <?php 
    if(empty($invoicing_details)) {
  ?>
  <!-- Save and Delete Button Start -->						
  <div class="amBtn amEdDe" >
    <button type="button" id="invoicing_details_cancel" class="btn default_btn red_btn"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" id="invoicing_details_save_popup" class="btn default_btn blue_btn"><?php echo $this->config->item('save_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
  </div>
  <!-- Save and Delete Button End -->
  <?php
    }
  ?>
</div>