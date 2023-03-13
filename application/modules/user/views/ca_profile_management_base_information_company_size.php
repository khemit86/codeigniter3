<?php
$user = $this->session->userdata('user');
?>	
<!-- Step 1st Start -->
<div class="pmFirstStep" style="display:<?php echo $base_info['company_size'] != null ? 'none' : 'block'  ?>">
  <div id="company_size_initial" class="default_hover_section_iconText mrgBtm0 closeHourlyrate" >
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12 fontSize0 default_bottom_border">
        <i class="far fa-copy"></i>
        <h6><?php echo ($user_info['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_size_section_initial_view_title'):$this->config->item('ca_profile_management_base_information_company_size_section_initial_view_title'); ?></h6>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
        <p><?php echo ($user_info['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_size_section_initial_view_content'):$this->config->item('ca_profile_management_base_information_company_size_section_initial_view_content'); ?></p>
      </div>
    </div>
  </div>
</div>
<!-- Step 1st End -->
<?php
  $edit_display = 'none';
  if($base_info['company_size'] != null) {
    if($post_data['event'] == 'update') {
      $edit_display = 'block';
    }
  }
 if($user[0]->is_authorized_physical_person == 'Y'){
	$company_size_dropdown = $this->config->item('ca_app_profile_management_base_information_company_size_dropdown_option');
 }else{
	$company_size_dropdown = $this->config->item('ca_profile_management_base_information_company_size_dropdown_option');
 
 }
?>
<!-- Step 2nd Start -->
<div class="pmdonotSection pmFirstStep saveFounded" id="company_size_edit" style="display:<?php echo $edit_display; ?>">
  <div class="closeDescription">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-12 tlp0">
	<div class="baseInfo_hrInput">
      <div class="hrInput">
        <div class="form-group default_dropdown_select soloOwner">
          <select class="form-control" id="company_size">
            <option value="" style="display:none"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_select_company_size_option'):$this->config->item('ca_profile_management_base_information_select_company_size_option'); ?></option>
            <?php
              foreach($company_size_dropdown as $val) {
                $selected = '';
                if($base_info['company_size'] != null && $base_info['company_size'] == $val) {
                  $selected = 'selected';
                }
				
                echo '<option value="'.$val.'"  '.$selected.'>'.$val.'</option>';
              }
            ?>
          </select>
          <div class="error_div_sectn clearfix" >
            <span class="error_msg company_size_error"></span>
          </div>
        </div>
      </div>
        <div class="amBtn">
          <button type="button" id="company_size_cancel" class="btn default_btn red_btn"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" id="company_size_save" class="btn default_btn blue_btn"><?php echo $this->config->item('save_btn_txt'); ?></button>
        </div>
    </div>
    </div>
  </div>
  </div>
</div>
<!-- Step 2nd End -->
<?php
  $view_display = 'none';
  if($base_info['company_size'] != null) {
    $view_display = 'block';
    if($post_data['event'] == 'update') {
      $view_display = 'none';
    }
  }
?>
<!-- Step 3rd Start -->
<div class="pmdonotSection pmFirstStep saveFounded" style="display:<?php echo $view_display; ?>">
	<div id="company_size_view" class="closeDescription">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12">
				<div class="baseInfo_hrInput">
					<div class="hrInput">
						<div class="form-group">
						  <?php 
							/* $company_size_dropdown = $this->config->item('ca_profile_management_base_information_company_size_dropdown_option'); */ 
							$company_size_val = '';
							if($base_info['company_size'] != null && in_array($base_info['company_size'], $company_size_dropdown )) {
							  $company_size_val = $base_info['company_size'];
							}
						  ?>
							<input type="text" class="form-control default_input_field" value="<?php echo $company_size_val; ?>" disabled="disabled">
						</div>
					</div>
					<div class="amBtn hrRate">
						<button type="button" class="btn default_btn red_btn" id="company_size_remove"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn" id="company_size_update"><?php echo $this->config->item('edit_btn_txt'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Step 3rd End -->