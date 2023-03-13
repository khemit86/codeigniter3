<?php
	$user = $this->session->userdata('user');	?>	
<form id="work_experience_form">
	<input type="hidden" name="section_id" value="<?php if(isset($work_experience_data['id'])){ echo Cryptor::doEncrypt($work_experience_data['id']); } ?>"/>
	<input type="hidden" name="u_id" value="<?php echo Cryptor::doEncrypt($user[0]->user_id);  ?>"/>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="default_black_bold_medium"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_position_title'):$this->config->item('personal_account_work_experience_section_position_title'); ?></label>
                <input type="text" id="position_title" name="position_title" class="form-control avoid_space default_input_field" placeholder="<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_position_title_placeholder'):$this->config->item('personal_account_work_experience_section_position_title_placeholder'); ?>" maxlength="<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_position_title_characters_maximum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_position_title_characters_maximum_length_characters_limit'); ?>" value="<?php if(isset($work_experience_data['position_name'])){ echo htmlentities($work_experience_data['position_name'],ENT_QUOTES); } ?>">
                <div class="error_div_sectn clearfix default_error_div_sectn">
					<?php 
					$position_title_remaining_characters = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_position_title_characters_maximum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_position_title_characters_maximum_length_characters_limit');
					if(isset($work_experience_data['position_name'])){ 
						
						if(($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_position_title_characters_maximum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_position_title_characters_maximum_length_characters_limit') - mb_strlen(trim(preg_replace('/\s+/', ' ',$work_experience_data['position_name']))) >= 0)
						{
							$position_title_remaining_characters = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_position_title_characters_maximum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_position_title_characters_maximum_length_characters_limit') - mb_strlen(trim(preg_replace('/\s+/', ' ',$work_experience_data['position_name'])));
						}else{
						
							$position_title_remaining_characters = 0;
						}
					} 
					?>
                    <span class="content-count position_title_length_count_message"><?php echo $position_title_remaining_characters.' '.$this->config->item('characters_remaining_message'); ?></span>
                    <span class="error_msg" id="position_title_error"></span>
                </div>
            </div>
        </div>
        <div class="col-md-12 block">
            <div class="form-group">
                <label class="default_black_bold_medium"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_name'):$this->config->item('personal_account_work_experience_section_company_name'); ?></label>
                <input type="text" id="company_name" name="company_name" class="form-control avoid_space default_input_field" placeholder="<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_name_placeholder'):$this->config->item('personal_account_work_experience_section_company_name_placeholder'); ?>" maxlength="<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_name_characters_maximum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_company_name_characters_maximum_length_characters_limit'); ?>" value="<?php if(isset($work_experience_data['position_company_name'])){ echo htmlentities($work_experience_data['position_company_name'],ENT_QUOTES); } ?>">
				
                <div class="error_div_sectn clearfix default_error_div_sectn">					
					<?php 
					$company_name_remaining_characters = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_name_characters_maximum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_company_name_characters_maximum_length_characters_limit');
					if(isset($work_experience_data['position_company_name'])){ 
						if(($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_name_characters_maximum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_company_name_characters_maximum_length_characters_limit') - mb_strlen($work_experience_data['position_company_name']) >= 0)
						{
							$company_name_remaining_characters = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_name_characters_maximum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_company_name_characters_maximum_length_characters_limit') - mb_strlen($work_experience_data['position_company_name']);
						}else{
						
							$company_name_remaining_characters = 0;
						}
					} 
					?>
                    <span class="content-count company_name_length_count_message"><?php echo $company_name_remaining_characters.' '.$this->config->item('characters_remaining_message'); ?></span>
                    <span class="error_msg" id="company_name_error"></span>
                </div>
            </div>
        </div>
        <div class="col-md-12 block">
            <div class="form-group">
                <label class="default_black_bold_medium"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_address'):$this->config->item('personal_account_work_experience_section_company_address'); ?></label>
                <input type="text" id="company_address" name="company_address" class="form-control avoid_space default_input_field" placeholder="<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_address_placeholder'):$this->config->item('personal_account_work_experience_section_company_address_placeholder'); ?>" maxlength="<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_address_characters_maximum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_company_address_characters_maximum_length_characters_limit'); ?>" value="<?php if(isset($work_experience_data['position_company_address'])){ echo htmlentities($work_experience_data['position_company_address'],ENT_QUOTES); } ?>">
                <div class="error_div_sectn clearfix default_error_div_sectn">
					<?php 
					$company_address_remaining_characters = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_address_characters_maximum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_company_address_characters_maximum_length_characters_limit');
					if(isset($work_experience_data['position_company_address'])){ 
						if(($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_address_characters_maximum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_company_address_characters_maximum_length_characters_limit') - mb_strlen($work_experience_data['position_company_address']) >= 0)
						{
							$company_address_remaining_characters = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_address_characters_maximum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_company_address_characters_maximum_length_characters_limit') - mb_strlen($work_experience_data['position_company_address']);
						}else{
						
							$company_address_remaining_characters = 0;
						}
					} 
					?>
                    <span class="content-count company_address_length_count_message"><?php echo $company_address_remaining_characters.' '.$this->config->item('characters_remaining_message'); ?></span>
                    <span class="error_msg" id="company_address_error"></span> 
                </div>
            </div>
        </div>
		<!-- Select Country Start -->
        <div class="col-md-12 default_country">
			<label class="default_black_bold_medium countryText"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_address_country'):$this->config->item('personal_account_work_experience_section_company_address_country'); ?></label>
			<label class="countryDetails">
				<div class="default_dropdown_select">
					<select id="company_country" name="company_country">
						<option value="" ><?php echo $this->config->item('select_country'); ?></option>
						
						<?php foreach ($countries as $country): ?>
						
						<option value="<?php echo $country['id']; ?>" <?php if(isset($work_experience_data['position_country_id']) && $work_experience_data['position_country_id'] == $country['id']){ echo "selected"; } ?>><?php echo $country['country_name'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="error_div_sectn clearfix">
					<span class="error_msg" id="country_name_error"></span>
				</div>
			</label>
        </div>
		<!-- Select Country End -->
        <div class="col-md-12 block graduateSection default_country">
            <div class="row">
                <div class="col-md-12 fromSection"><label class="default_black_bold_medium graduateLabel"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_from'):$this->config->item('personal_account_work_experience_section_from'); ?></label>
					<label class="adjustLevel">
						<label class="selectMonth popupSecWidth">
							<!-- <label class="default_black_bold_medium graduateLabel"><?php //echo $this->config->item('personal_account_work_experience_section_from'); ?></label> -->
							<div class="default_dropdown_select">
								<select id="month_from" name="month_from">
									<option value=""><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_month_select_month'):$this->config->item('personal_account_work_experience_section_month_select_month'); ?></option>
									<?php
									foreach ($this->config->item('calendar_months') as $monthId => $monthName) { ?>
									<option <?php if(isset($work_experience_data['position_from_month']) && $work_experience_data['position_from_month'] == $monthId){ echo "selected"; } ?> value="<?php echo $monthId; ?>"><?php echo $monthName ?></option>
									<?php	
									}
									?>
								</select>
							</div>
							<div class="error_div_sectn clearfix">
								<span class="error_msg" id="month_from_error"></span>
							</div>
						</label>
					
						<label class="selectYear popupSecWidth">
							<!-- <label class="default_black_bold_medium graduateLabel"></label> -->
							<div class="default_dropdown_select">
								<select id="year_from" name="year_from">
									<option value=""><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_year_select_year'):$this->config->item('personal_account_work_experience_section_year_select_year'); ?></option>
									<?php
										$year_start_from = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_year_start_from'):$this->config->item('personal_account_work_experience_section_year_start_from');
										
										$year_end_to = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_year_end_to'):$this->config->item('personal_account_work_experience_section_year_end_to');
										
										
										for($y=$year_start_from; $y<=$year_end_to; $y++){ ?>
											<option <?php if(isset($work_experience_data['position_from_year']) && $work_experience_data['position_from_year'] == $y){ echo "selected"; } ?> value="<?php echo $y; ?>"><?php echo $y; ?></option>
									<?php		
										}
									?>
								</select>
							</div>
							<div class="error_div_sectn clearfix">
								<span class="error_msg" id="year_from_error"></span>
							</div>
						</label>
						<div class="clearfix"></div>
					</label>
				</div>
                <div class="col-md-12 toSection default_country">
					<div>
						<label class="default_black_bold_medium graduateLabel"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_to'):$this->config->item('personal_account_work_experience_section_to'); ?></label><label class="adjustLevel"><label class="popupSecWidth selectMonth pright0">
								<!-- <label class="default_black_bold_medium graduateLabel"><?php //echo $this->config->item('personal_account_work_experience_section_to'); ?></label> -->
								<?php
								$work_exp_disables_attr = '';
								if(isset($work_experience_data['position_still_work']) && $work_experience_data['position_still_work'] == '1'){ 
									$work_exp_disables_attr = 'disabled';
								}
								?><div class="default_dropdown_select">
									<select id="month_to" name="month_to" <?php echo $work_exp_disables_attr; ?>>
										<option value=""><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_month_select_month'):$this->config->item('personal_account_work_experience_section_month_select_month'); ?></option>
										<?php
										foreach ($this->config->item('calendar_months') as $monthId => $monthName) { ?>
											<option <?php if(isset($work_experience_data['position_to_month']) && $work_experience_data['position_to_month'] == $monthId){ echo "selected"; } ?> value="<?php echo  $monthId; ?>"><?php echo $monthName; ?></option>
										<?php	
										}
										?>
									</select>
								</div><div class="error_div_sectn clearfix" style="width: 255px;"><span class="error_msg" id="month_to_error"></span><span class="error_msg" id="from_to_error"></span></div></label><label class="selectYear popupSecWidth">
								<!-- <label class="default_black_bold_medium graduateLabel"></label> -->
								<div class="default_dropdown_select">
									<select id="year_to" name="year_to" <?php echo $work_exp_disables_attr; ?>>
										<option value=""><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_year_select_year'):$this->config->item('personal_account_work_experience_section_year_select_year'); ?></option>
										<?php
										
											$year_start_from = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_year_start_from'):$this->config->item('personal_account_work_experience_section_year_start_from');
										
											$year_end_to = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_year_end_to'):$this->config->item('personal_account_work_experience_section_year_end_to');
										
										
										
											for($y=$year_start_from; $y<=$year_end_to; $y++){ ?>
											
											<option <?php if(isset($work_experience_data['position_to_year']) && $work_experience_data['position_to_year'] == $y){ echo "selected"; } ?>  value="<?php echo $y; ?>"><?php echo $y; ?></option>
										<?php		
											}
										?>
									</select>
								</div><div class="error_div_sectn clearfix">
									<span class="error_msg" id="year_to_error"></span>
								</div></label></label><label class="stillWork"><div class="drpChk">
								<label for="still_work" class="default_checkbox">
									<small class="default_black_bold_medium"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_still_work'):$this->config->item('personal_account_work_experience_section_still_work'); ?></small>
									<input type="checkbox" id="still_work" name="still_work" value="Y" class="chk-btn"  <?php if(isset($work_experience_data['position_still_work']) && $work_experience_data['position_still_work'] == '1'){ echo "checked"; } ?>>
									<span class="checkmark"></span>
								</label>
							</div></label>
						<div class="clearfix"></div>
					</div>
				</div>
            </div>
        </div>
        
        <!-- <div class="col-md-12 block">
            <div class="drpChk">
                <small class="default_black_bold_medium"><?php //echo $this->config->item('personal_account_work_experience_section_still_work'); ?></small><label for="still_work" class="default_checkbox">
                    <input type="checkbox" id="still_work" name="still_work" value="Y" class="chk-btn">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div> -->
        
        <div class="col-md-12 block">
            <div class="form-group lastGroup">
                <label class="default_black_bold_medium default_label_bottom_gap" for="comment"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_position_description'):$this->config->item('personal_account_work_experience_section_position_description'); ?></label>
                <textarea class="avoid_space_textarea default_textarea_field" name="position_description" rows="5" id="position_description"><?php if(isset($work_experience_data['position_description'])){ echo $work_experience_data['position_description']; } ?></textarea>
                <div class="error_div_sectn clearfix default_error_div_sectn">
					<?php 
					$position_description_remaining_characters = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_position_description_characters_maximum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_position_description_characters_maximum_length_characters_limit');
					if(isset($work_experience_data['position_description'])){ 
						if($user[0]->is_authorized_physical_person == 'Y'?$this->config->item('company_account_app_work_experience_section_position_description_characters_maximum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_position_description_characters_maximum_length_characters_limit') - mb_strlen($work_experience_data['position_description']) >= 0)
						{
							$position_description_remaining_characters = $user[0]->is_authorized_physical_person == 'Y'?$this->config->item('company_account_app_work_experience_section_position_description_characters_maximum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_position_description_characters_maximum_length_characters_limit') - mb_strlen($work_experience_data['position_description']);
						}else{
							$position_description_remaining_characters = 0;
						}
					}
					?>
                    <span class="content-count job_description_length_count_message"><?php echo $position_description_remaining_characters.' '.$this->config->item('characters_remaining_message'); ?></span>
                    <span class="error_msg" id="position_description_error"></span> 
                </div>
            </div>
        </div>
    </div>
    <div class="default_popup_close text-right">
            <!--<input type="hidden" id="blockStep" value="0">-->
        <div class="row">
            <div class="col-md-12 rightButton"><button type="button" class="btn default_btn red_btn default_popup_width_btn btnCancel" data-dismiss="modal"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" class="btn default_btn blue_btn default_popup_width_btn btnSave" id="save_work_experience"><?php echo $this->config->item('save_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button></div>
        </div>
    </div>
</form>