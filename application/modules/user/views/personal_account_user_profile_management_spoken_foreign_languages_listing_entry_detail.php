<?php
$user = $this->session->userdata('user');
?>
<div class="mobileEditTxt">
	<!-- Edit Section End -->
	<div class="row" id="editSpokenLanguage<?php echo $spoken_language_value['id']; ?>" style="display:none">
		<div class="col-md-11 alCenter">
			<div class="row alCenterRow">
				<div class="col-md-3 col-sm-3 col-3 categoryPart">
					<div class="default_black_bold_medium mobTitleText"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title'); ?></div>
				<!-- <div class="categoryPart"> -->
					<div class="pmAeSelect">
						<div class="form-group default_dropdown_select">
							<select name="oLName<?php echo $spoken_language_value['id']; ?>" id="oLName<?php echo $spoken_language_value['id']; ?>" onchange="chooseSpokenLanguage(this.value, '<?php echo $spoken_language_value['id']; ?>')">
								<option value=""><?php echo $this->config->item('pa_profile_management_spoken_foreign_languages_section_select_language_initial_selection'); ?></option>
								<?php
								if(!empty($languages)){
									foreach($languages as $language_key=>$language_value){
								?>
									<option <?php if($spoken_language_value['language_id'] ==$language_value['id'] ){ echo "selected"; } ?> value="<?php echo $language_value['id']; ?>"><?php echo $language_value['language']; ?></option>
								<?php
									}
								}
								?>
							</select>
							
						</div>
					</div>
				</div>
				<!-- <div class="subcategoryPart mobeditTxt">
					<div class="row"> -->
				<div class="col-md-3 col-sm-3 col-3 sflSection">
					<div class="default_black_bold_medium mobTitleText"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title_understanding'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title_understanding'); ?></div>
					<div class="subCatPart">
						<div class="form-group default_dropdown_select">
							<select name="oLUnderstanding<?php echo $spoken_language_value['id']; ?>" id="oLUnderstanding<?php echo $spoken_language_value['id']; ?>" onchange="chooseSlUnderstanding(this.value, '<?php echo $spoken_language_value['id']; ?>')">
								<option value=""><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_select_level_initial_selection'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_select_level_initial_selection'); ?></option>
								<?php
								$spoken_lang_understanding_options = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_spoken_languages_understanding_drop_down_options'):$this->config->item('pa_profile_management_spoken_languages_understanding_drop_down_options');
								foreach($spoken_lang_understanding_options as $value){ ?>
								<option <?php if($spoken_language_value['understanding'] ==$value ){ echo "selected"; } ?> value="<?php echo $value ?>"><?php echo $value ?></option>
								<?php } ?>
							</select>
							
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-3 sflSection">
					<div class="default_black_bold_medium mobTitleText"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title_speaking'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title_speaking'); ?></div>
					<div class="subCatPart">
						<div class="form-group default_dropdown_select">
							<select name="oLSpeaking<?php echo $spoken_language_value['id']; ?>" id="oLSpeaking<?php echo $spoken_language_value['id']; ?>"  onchange="chooseSlSpeaking(this.value, '<?php echo $spoken_language_value['id']; ?>')">
								<option value=""><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_select_level_initial_selection'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_select_level_initial_selection'); ?></option>
								<?php
								$spoken_lang_speaking_options = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_spoken_languages_speaking_drop_down_options'):$this->config->item('pa_profile_management_spoken_languages_speaking_drop_down_options');
								foreach($spoken_lang_speaking_options as $value){ ?>
								<option <?php if($spoken_language_value['speaking'] ==$value ){ echo "selected"; } ?> value="<?php echo $value ?>"><?php echo $value ?></option>	
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-3 sflSection">
					<div class="default_black_bold_medium mobTitleText"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title_writing'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title_writing'); ?></div>
					<div class="subCatPart">
						<div class="form-group default_dropdown_select">
							<select name="oLWriting<?php echo $spoken_language_value['id']; ?>" id="oLWriting<?php echo $spoken_language_value['id']; ?>" onchange="chooseSlWriting(this.value, '<?php echo $spoken_language_value['id']; ?>')">
								<option value=""><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_select_level_initial_selection'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_select_level_initial_selection'); ?></option>
								<?php
								$spoken_lang_writing_options = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_spoken_languages_writing_drop_down_options'):$this->config->item('pa_profile_management_spoken_languages_writing_drop_down_options');
								foreach($spoken_lang_writing_options as $value){ ?>
									<option <?php if($spoken_language_value['writing'] ==$value ){ echo "selected"; } ?> value="<?php echo $value ?>"><?php echo $value ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
			</div>
				<!-- </div>
			</div> -->
		</div>
		<div class="col-md-1 pmAeMob pmAeDesk_editText">
			<div class="pmAeSelect">
				<div class="pmAction listSlAction" id="saveSlMode<?php echo $spoken_language_value['id']; ?>">
					<button class="btn pmCheck default_icon_red_btn" id="addSlCancelBtn<?php echo $spoken_language_value['id']; ?>" onclick="cancelSpokenLanguage('<?php echo $spoken_language_value['id']; ?>')"><span><?php echo $this->config->item('cancel_btn_txt'); ?></span><i class="fas fa-times"></i></button><button class="btn pmSave default_icon_blue_btn saveSpokenLanguageBtn" data-action-type="edit" id="addSlSaveBtn<?php echo $spoken_language_value['id']; ?>" data-section-id = "<?php echo $spoken_language_value['id'] ?>" ><span><?php echo $this->config->item('save_btn_txt'); ?></span><i class="fas fa-save"></i></button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Edit Section End -->
<!-- Save Section Start -->
<div class="row saveSectionRow" id="saveSpokenLanguage<?php echo $spoken_language_value['id']; ?>">
	<div class="col-md-11 alCenter">
		<div class="desktopSaveTxt">
			<div class="categoryPart">
				<div class="pmAeSelect">
					<div class="pmAExpt default_black_bold_medium"><?php echo $spoken_language_value['language']; ?></div>
					<div class="language_name" style="display:none;" data-attr="<?php echo $spoken_language_value['language_id']; ?>"></div>
				</div>
			</div>
			<div class="subcategoryPart saveTxt aoeText">
				<div class="row">
					<div class="col-md-4 col-sm-4 col-4 sflSection">
						<div class="subCatPart subCatLan">
							<div class="pmAExpt default_black_regular_medium"><?php echo $spoken_language_value['understanding']; ?></div>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-4 sflSection">
						<div class="subCatPart subCatLan">
							<div class="pmAExpt default_black_regular_medium"><?php echo $spoken_language_value['speaking']; ?></div>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-4 sflSection">
						<div class="subCatPart subCatLan">
							<div class="pmAExpt default_black_regular_medium"><?php echo $spoken_language_value['writing']; ?></div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		
		<div class="mobileSaveTxt">
			<div class="row">
				<div class="col-md-3 col-sm-3 col-3 sflSection">			
					<div class="categoryPart">
						<div class="pmAeSelect">
							<div class="pmAExpt default_black_bold_medium"><?php echo $spoken_language_value['language']; ?></div>
							<div class="language_name" style="display:none;" data-attr="<?php echo $spoken_language_value['language_id']; ?>"></div>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-3 sflSection">
					<div class="subCatPart subCatLan">
						<small class="default_black_bold_medium"><i class="far fa-thumbs-up" aria-hidden="true"></i><?php echo $this->config->item('pa_profile_management_spoken_foreign_languages_section_title_understanding'); ?></small><div class="pmAExpt default_black_regular_medium"><?php echo $spoken_language_value['understanding']; ?></div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-3 sflSection">
					<div class="subCatPart subCatLan">
						<small class="default_black_bold_medium"><i class="fa fa-bullhorn" aria-hidden="true"></i><?php echo $this->config->item('pa_profile_management_spoken_foreign_languages_section_title_speaking'); ?></small><div class="pmAExpt default_black_regular_medium"><?php echo $spoken_language_value['speaking']; ?></div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-3 sflSection">
					<div class="subCatPart subCatLan">
						<small class="default_black_bold_medium"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><?php echo $this->config->item('pa_profile_management_spoken_foreign_languages_section_title_writing'); ?></small><div class="pmAExpt default_black_regular_medium"><?php echo $spoken_language_value['writing']; ?></div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>		
	</div>
	<div class="col-md-1 pmAeMob">
		<div class="pmAeSelect">
			<div class="pmAction editSlMode listSlAction" id="editSlMode<?php echo $spoken_language_value['id']; ?>">
				<button class="btn pmTrash default_icon_red_btn"onclick="removeSpokenLanguage('<?php echo $spoken_language_value['id']; ?>')"><i class="fas fa-trash-alt"></i></button><button class="btn pmEdit default_icon_green_btn" onclick="editSpokenLanguage('<?php echo $spoken_language_value['id']; ?>')"><i class="fas fa-edit"></i></button>
			</div>
		</div>
	</div>
</div>
<!-- Save Section End -->


