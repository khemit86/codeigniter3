<?php
$user = $this->session->userdata('user');	
?>	
<form id="education_training_form">
<input type="hidden" name="section_id" value="<?php if(isset($education_data['id'])){ echo Cryptor::doEncrypt($education_data['id']); } ?>"/>
<input type="hidden" name="u_id" value="<?php echo Cryptor::doEncrypt($user[0]->user_id);  ?>"/>
<div class="row">
	<div class="col-md-12" id="block0">
		<div class="form-group">
			<label class="default_black_bold_medium margin_bottom0"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_diploma_name'):$this->config->item('personal_account_education_section_diploma_name'); ?></label>
			<input type="text" id="diploma_name" name="diploma_name" class="form-control avoid_space default_input_field" placeholder="<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_diploma_name_placeholder'):$this->config->item('personal_account_education_section_diploma_name_placeholder'); ?>" value="<?php if(isset($education_data['education_diploma_degree_name'])){ echo htmlentities($education_data['education_diploma_degree_name'],ENT_QUOTES); } ?>" maxlength="<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_diploma_name_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_diploma_name_characters_maximum_length_characters_limit'); ?>">
			<div class="error_div_sectn clearfix default_error_div_sectn">
				<?php 
				$diploma_name_remaining_characters = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_diploma_name_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_diploma_name_characters_maximum_length_characters_limit');
				
				if(isset($education_data['education_diploma_degree_name'])){ 
					if($user[0]->is_authorized_physical_person == 'Y'?$this->config->item('company_account_app_education_section_diploma_name_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_diploma_name_characters_maximum_length_characters_limit') - mb_strlen($education_data['education_diploma_degree_name']) >= 0)
					{
						$diploma_name_remaining_characters = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_diploma_name_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_diploma_name_characters_maximum_length_characters_limit') - mb_strlen($education_data['education_diploma_degree_name']);
					}else{
					$diploma_name_remaining_characters = 0;
					}
				}
				?>
				<span class="content-count diploma_name_length_count_message"><?php echo $diploma_name_remaining_characters.' '.$this->config->item('characters_remaining_message'); ?></span>
				<span class="error_msg" id="diploma_name_error"></span>
			</div>
		</div>
	</div>
	<div class="col-md-12 block" id="block1">
		<div class="form-group">
			<label class="default_black_bold_medium margin_bottom0"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_name'):$this->config->item('personal_account_education_section_school_name'); ?></label>
			
			<input type="text" id="school_name" name="school_name" class="form-control avoid_space default_input_field" placeholder="<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_name_placeholder'):$this->config->item('personal_account_education_section_school_name_placeholder'); ?>" value="<?php if(isset($education_data['education_school_name'])){ echo htmlentities($education_data['education_school_name'],ENT_QUOTES); } ?>" maxlength="<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_name_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_school_name_characters_maximum_length_characters_limit'); ?>">
			<div class="error_div_sectn clearfix default_error_div_sectn">
				<?php 
				$school_name_remaining_characters = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_name_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_school_name_characters_maximum_length_characters_limit');
				if(isset($education_data['education_school_name'])){ 
					if($user[0]->is_authorized_physical_person == 'Y'?$this->config->item('company_account_app_education_section_school_name_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_school_name_characters_maximum_length_characters_limit') - mb_strlen($education_data['education_school_name']) >= 0)
					{
						$school_name_remaining_characters = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_name_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_school_name_characters_maximum_length_characters_limit') - mb_strlen($education_data['education_school_name']);
					}else{
						
					$school_name_remaining_characters = 0;
					} 
				}
				?>
				<span class="content-count school_name_length_count_message"><?php echo$school_name_remaining_characters.' '.$this->config->item('characters_remaining_message'); ?></span>
				<span class="error_msg" id="school_name_error"></span>
			</div>
		</div>
	</div>
	<div class="col-md-12 block" id="block2">
		<div class="form-group">
		
			<label class="default_black_bold_medium margin_bottom0"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_address'):$this->config->item('personal_account_education_section_school_address'); ?></label>
			<input type="text" id="school_address" name="school_address" class="form-control avoid_space default_input_field" placeholder="<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_address_placeholder'):$this->config->item('personal_account_education_section_school_address_placeholder'); ?>" value="<?php if(isset($education_data['education_school_address'])){ echo htmlentities($education_data['education_school_address'],ENT_QUOTES); } ?>" maxlength="<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_address_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_school_address_characters_maximum_length_characters_limit'); ?>">
			<div class="error_div_sectn clearfix default_error_div_sectn">
				<?php 
				$school_address_remaining_characters = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_address_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_school_address_characters_maximum_length_characters_limit');
				
				if(isset($education_data['education_school_address'])){ 
					if($user[0]->is_authorized_physical_person == 'Y'?$this->config->item('company_account_app_education_section_school_address_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_school_address_characters_maximum_length_characters_limit') - mb_strlen($education_data['education_school_address']) >= 0)
					{
						$school_address_remaining_characters = $user[0]->is_authorized_physical_person == 'Y'?$this->config->item('company_account_app_education_section_school_address_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_school_address_characters_maximum_length_characters_limit') - mb_strlen($education_data['education_school_address']);
					}else{
						
					$school_address_remaining_characters = 0;
					}  
				}
				?>
				<span class="content-count school_address_length_count_message"><?php echo $school_address_remaining_characters.' '.$this->config->item('characters_remaining_message'); ?></span>
				<span class="error_msg" id="school_address_error"></span> 
			</div>
			
			<div class="row">
				<div class="col-md-12 col-sm-12 col-12 default_country">
					<label class="default_black_bold_medium countryText"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_address_country'):$this->config->item('personal_account_education_section_school_address_country'); ?></label>
					<label class="countryDetails">
						<div class="default_dropdown_select">
							<select id="school_country" name="school_country" >
							<option value="" ><?php echo $this->config->item('select_country'); ?></option>
							
							<?php foreach ($countries as $country): ?>
							
							<option value="<?php echo $country['id']; ?>" <?php if(isset($education_data['education_country_id']) && $education_data['education_country_id'] == $country['id']){ echo "selected"; } ?>><?php echo $country['country_name'] ?></option>
							<?php endforeach; ?>
							</select>
							<div class="error_div_sectn clearfix"><span class="error_msg" id="school_country_error"></span>
							</div>
						</div>
					</label>
				</div>
				<!-- <div class="col-md-4 col-sm-4 col-12"></div>
				<div class="col-md-4 col-sm-4 col-12"></div> -->
			</div>
		</div>
	</div>
	
	
	<div class="col-md-12 edu_bdrTop block graduateSection default_country" id="block3">
		<label class="default_black_bold_medium graduateLabel"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_graduated_in'):$this->config->item('personal_account_education_section_graduated_in'); ?></label>
		<label class="selectYear pRight0">
			<div class="default_dropdown_select">
				<?php
				$grauate_year_attr = '';
				if(isset($education_data['education_progress']) && $education_data['education_progress'] == '1'){ 
					$grauate_year_attr = 'disabled';
				}
				?>
				<select id="graduate_in" name="graduate_in" <?php echo $grauate_year_attr; ?>>
					<option value=""><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_graduated_in_select_year'):$this->config->item('personal_account_education_section_graduated_in_select_year'); ?></option>
			<?php
				$start_from = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_graduated_in_year_start_from'):$this->config->item('personal_account_education_section_graduated_in_year_start_from');
				
				$end_to = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_graduated_in_year_end_to'):$this->config->item('personal_account_education_section_graduated_in_year_end_to');
				
				for($y=$start_from; $y<=$end_to; $y++)
				{
					
				?>
					<option value="<?php echo $y; ?>" <?php if(isset($education_data['education_graduate_year']) && $education_data['education_graduate_year'] == $y){ echo "selected"; } ?>><?php echo $y; ?></option>
				<?php	
				}
				?>
				</select>
				<div class="error_div_sectn clearfix"><span class="error_msg" id="graduate_in_error"></span>
				</div>
			</div>
		</label>
		<label class="stillWork">
			<div class="drpChk">
				<label for="graduate_inprogress" class="default_checkbox">
				<small class="default_black_bold_medium"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_graduated_in_progress'):$this->config->item('personal_account_education_section_graduated_in_progress'); ?></small>
					<input type="checkbox" id="graduate_inprogress" name="graduate_inprogress" value="Y" class="chk-btn" <?php if(isset($education_data['education_progress']) && $education_data['education_progress'] == '1'){ echo "checked"; } ?>>
					<span class="checkmark"></span>
				</label>
			</div>
		</label>
	</div>
	<div class="col-md-12 edu_bdrTop block" id="block4">
		<div class="form-group lastGroup">
			<label class="default_black_bold_medium default_label_bottom_gap" for="comment"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_comments'):$this->config->item('personal_account_education_section_comments'); ?></label>
			<textarea class="avoid_space_textarea default_textarea_field" rows="5" name="comment" id="comment" maxlength="<?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_comments_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_comments_characters_maximum_length_characters_limit'); ?>"><?php if(isset($education_data['education_comments'])){ echo $education_data['education_comments']; } ?></textarea>
			<div class="error_div_sectn clearfix default_error_div_sectn">
				<?php 
				$comment_remaining_characters = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_comments_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_comments_characters_maximum_length_characters_limit');
				if(isset($education_data['education_comments'])){ 
					if($user[0]->is_authorized_physical_person == 'Y'?$this->config->item('company_account_app_education_section_comments_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_comments_characters_maximum_length_characters_limit') - mb_strlen($education_data['education_comments']) >= 0)
					{
						$comment_remaining_characters = $user[0]->is_authorized_physical_person == 'Y'?$this->config->item('company_account_app_education_section_comments_characters_maximum_length_characters_limit'):$this->config->item('personal_account_education_section_comments_characters_maximum_length_characters_limit') - mb_strlen($education_data['education_comments']);
					}else{
					$comment_remaining_characters = 0;
					}
				}
				?>
				<span class="content-count comment_length_count_message"><?php echo $comment_remaining_characters.' '.$this->config->item('characters_remaining_message'); ?></span>
				<span class="error_msg" id="comment_error"></span>
			</div>
		</div>
	</div>
</div>
<div class="default_popup_close text-right">
	<!--<input type="hidden" id="blockStep" value="0">-->
	<div class="row">
		<div class="col-md-12 col-sm-12 col-12 rightButton"><button type="button" class="btn default_btn red_btn default_popup_width_btn btnCancel" data-dismiss="modal"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" class="btn default_btn blue_btn default_popup_width_btn btnSave" id="save_education_training"><?php echo $this->config->item('save_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button></div>
	</div>
</div>
</form>

