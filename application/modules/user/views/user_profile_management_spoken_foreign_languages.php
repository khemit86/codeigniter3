<?php 
$user = $this->session->userdata('user');
if($user_detail['current_membership_plan_id'] == '1'){ // for free
		
	$spoken_language_slots_allowed = ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_user_profile_management_spoken_languages_page_free_membership_subscriber_number_spoken_languages_slots_allowed'):$this->config->item('pa_user_profile_management_spoken_languages_page_free_membership_subscriber_number_spoken_languages_slots_allowed');	
	
	if ($spoken_language_slots_allowed > $spoken_languages_count){
		$add_spoken_language_button_style = "";
		$add_spoken_language_button_free_member_style = "display:none";
	
	}else{
		
		$add_spoken_language_button_style = "display:none";
		$add_spoken_language_button_free_member_style = "";
	}
	$max_spoken_language = 	$user_detail['is_authorized_physical_person'] == 'Y'?$this->config->item('ca_app_user_profile_management_spoken_languages_page_gold_membership_subscriber_number_spoken_languages_slots_allowed'):$this->config->item('pa_user_profile_management_spoken_languages_page_gold_membership_subscriber_number_spoken_languages_slots_allowed');
	if($spoken_languages_count >= $max_spoken_language){
		
		$add_spoken_language_button_style = "display:none";
		$add_spoken_language_button_free_member_style = "display:none";
	}
	
}else{	
	$spoken_language_slots_allowed = $user_detail['is_authorized_physical_person'] == 'Y'?$this->config->item('ca_app_user_profile_management_spoken_languages_page_gold_membership_subscriber_number_spoken_languages_slots_allowed'):$this->config->item('pa_user_profile_management_spoken_languages_page_gold_membership_subscriber_number_spoken_languages_slots_allowed');	
	$add_spoken_language_button_style = "display:none";
	$add_spoken_language_button_free_member_style = "display:none";
	
	if ($spoken_language_slots_allowed > $spoken_languages_count){
		
		$add_spoken_language_button_style = "";
	}
	
}
if($spoken_languages_count ==0){
	$add_spoken_language_button_style = "display:none";
	$add_spoken_language_button_free_member_style = "display:none";
}	
?>
<div class="dashTop">		
    <!-- Menu Icon on Responsive View Start -->		
    <?php echo $this->load->view('user_left_menu_mobile.php'); ?>
    <!-- Menu Icon on Responsive View End -->		
      <!-- Middle Section Start -->
    <div class="wrapper wrapper1">
        <!-- Left Menu Start -->
        <?php echo $this->load->view('user_left_nav.php'); ?>
        <!-- Left Menu End -->
        <!-- Right Section Start -->
        <div id="content" class="profile_foreign_language_page body_distance_adjust">
			<!-- Step 1st Start -->
			
			<div class="displayMiddle" id="initialViewSpokenLanguages" style="<?php if($spoken_languages_count ==0){ echo "display:inline-flex;";} else { echo "display:none;";} ?>">
				<div class="pmFirstStep">
					<div  class="default_hover_section_iconText emailNew  mrgBtm0 closeSpokenLanguages initialViewSpokenLanguages" id="initial_view_spoken_languages">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 fontSize0 default_bottom_border">
								<i class="fas fa-language"></i>
								<h6><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_initial_view_title'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_initial_view_title'); ?></h6>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<p><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_initial_view_content'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_initial_view_content'); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Step 1st End -->
			<div class="etSecond_step" id="work_experience_listing_data">
				<!-- Profile Management Text Start -->
				<div class="default_page_heading" id="spoken_language_heading" style="<?php if($spoken_languages_count ==0){ echo "display:none;";} else { echo "display:block;";} ?>">
					<h4><div><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_page_headline_title'):$this->config->item('pa_profile_management_spoken_foreign_languages_page_headline_title'); ?></div></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
				<!-- Content Start -->
				<div class="cmFieldText">
					<div id="foreign_language_container" class="cmField foreignLanguage_section">
						<!-- Step 2nd Start -->
						<div id="editSpokenLanguages1" class="pmdonotSection pmarEp" style="<?php if($spoken_languages_count >0){ echo "display:block;";} else { echo "display:none;";} ?>">							
							<!--- Desktop View Start --->
							<div class="aoe_desktop">	
								<div class="pmAeH">
									<div class="row">
										<div class="col-md-11 alCenter">
												<div class="categoryPart">
													<div class="default_black_bold_medium"><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title'); ?></div>
												</div>
												<div class="subcategoryPart">
													<div class="row">
														<div class="col-md-4 col-sm-4 col-4 sflSection">
															<div class="subCatPart">
																<div class="default_black_bold_medium"><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title_understanding'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title_understanding'); ?></div>
															</div>
														</div>
														<div class="col-md-4 col-sm-4 col-4 sflSection">
															<div class="subCatPart">
																<div class="default_black_bold_medium"><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title_speaking'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title_speaking'); ?></div>
															</div>
														</div>
														<div class="col-md-4 col-sm-4 col-4 sflSection">
															<div class="subCatPart">
																<div class="default_black_bold_medium"><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title_writing'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title_writing'); ?></div>
															</div>
														</div>
													</div>
												</div>
										</div>
										<div class="col-md-1 pmAeMob"></div>
									</div>
								</div>
								<!--- Desktop View End --->
								<!-- Edit Section Start -->
								
								<div id="mainSlEditViewContainer" >
								<?php
								if(!empty($spoken_languages_data)){
								foreach($spoken_languages_data as $spoken_language_key=>$spoken_language_value){
								?>
								<div class="pmcsa spoken_language_edit_view_row_container" id="spokenLanguages<?php echo $spoken_language_value['id']; ?>">	
								<?php
									echo $this->load->view('personal_account_user_profile_management_spoken_foreign_languages_listing_entry_detail',array('spoken_language_value'=>$spoken_language_value,'languages'=>$languages));
								?>
								<div class="error_div_sectn clearfix" id="error_div<?php echo $spoken_language_value['id']; ?>" style="display:none;"><span class="error_msg invalid_language<?php echo $spoken_language_value['id']; ?>"></span></div>
								</div>	
								<?php
									}
								}
								?>
								</div>
								 
								<div id="addSpokenLanguage" class="pmcsa mobileEditTxt" style="<?php if(!empty($spoken_languages_data) ){ echo "display:none"; }else{ "display:block";} ?>">
									<div class="row">
										<div class="col-md-11 alCenter">
											<div class="row">
												<div class="col-md-3 col-sm-3 col-3 categoryPart">
													<div class="default_black_bold_medium mobTitleText"><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title'); ?></div>
													<div class="pmAeSelect">
														<div class="form-group default_dropdown_select">
															<select name="oLName0" id="oLName0" onchange="chooseSpokenLanguage(this.value, '0')">
																<option value=""><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_select_language_initial_selection'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_select_language_initial_selection'); ?></option>
															</select>
														</div>
													</div>
												</div>
												<!-- <div class="subcategoryPart">
													<div class="row"> -->
												<div class="col-md-3 col-sm-3 col-3 sflSection">
													<div class="default_black_bold_medium mobTitleText"><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title_understanding'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title_understanding'); ?></div>
													<div class="subCatPart">
														<div class="form-group default_dropdown_select">
															<select name="oLUnderstanding0" id="oLUnderstanding0" disabled="disabled" onchange="chooseSlUnderstanding(this.value, '0')">
																<option value=""><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_select_level_initial_selection'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_select_level_initial_selection'); ?></option>
																<?php
																$spoken_lang_understanding_options = ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_languages_understanding_drop_down_options'):$this->config->item('pa_profile_management_spoken_languages_understanding_drop_down_options');
																foreach($spoken_lang_understanding_options as $value){
																	echo '<option value="'.$value.'">'.$value.'</option>';
																}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="col-md-3 col-sm-3 col-3 sflSection">
													<div class="default_black_bold_medium mobTitleText"><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title_speaking'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title_speaking'); ?></div>
													<div class="subCatPart">
														<div class="form-group default_dropdown_select">
															<select name="oLSpeaking0" id="oLSpeaking0" disabled="disabled" onchange="chooseSlSpeaking(this.value, '0')">
																<option value=""><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_select_level_initial_selection'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_select_level_initial_selection'); ?></option>
																<?php
																$spoken_lang_speaking_options = ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_languages_speaking_drop_down_options'):$this->config->item('pa_profile_management_spoken_languages_speaking_drop_down_options');
																foreach($spoken_lang_speaking_options as $value){
																	echo '<option value="'.$value.'">'.$value.'</option>';
																}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="col-md-3 col-sm-3 col-3 sflSection">
													<div class="default_black_bold_medium mobTitleText"><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title_writing'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title_writing'); ?></div>
													<div class="subCatPart">
														<div class="form-group default_dropdown_select">
															<select name="oLWriting0" id="oLWriting0" disabled="disabled" onchange="chooseSlWriting(this.value, '0')">
																<option value=""><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_select_level_initial_selection'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_select_level_initial_selection'); ?></option>
																<?php
																$spoken_lang_writing_options = ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_languages_writing_drop_down_options'):$this->config->item('pa_profile_management_spoken_languages_writing_drop_down_options');
																foreach($spoken_lang_writing_options as $value){
																	echo '<option value="'.$value.'">'.$value.'</option>';
																}
																?>
															</select>
														</div>
													</div>
												</div>
												<!-- <div class="pmAeMob pmAeDesk_editText">
													<div class="pmAeSelect">
														<div class="pmAction">
															<button class="btn blue_btn default_btn pmSave saveSpokenLanguageBtn" data-action-type="add" disabled data-section-id = "0" id="addSlSaveBtn0" ><?php echo $this->config->item('save_btn_txt'); ?></button><button class="btn default_btn red_btn pmCheck" id="addSlCancelBtn0" onclick="cancelSpokenLanguage('0', '0')"><?php echo $this->config->item('cancel_btn_txt'); ?></button>
														</div>
													</div>
												</div> -->
											</div>
											<div class="error_div_sectn invalidLanguage clearfix" id="error_div0" style="display:none"><span class="error_msg invalid_language0"></span></div>
										</div>
										<!-- <div class="col-md-1 pmAeMob pmAeMob_editText"> -->
										<div class="col-md-1 pmAeMob pmAeDesk_editText">
											<div class="pmAeSelect">
												<div class="pmAction">
													<button class="btn pmCheck default_icon_red_btn" id="addSlCancelBtn0" onclick="cancelSpokenLanguage('0', '0')"><span><?php echo $this->config->item('cancel_btn_txt'); ?></span><i class="fas fa-times"></i></button><button class="btn pmSave default_icon_blue_btn saveSpokenLanguageBtn" data-action-type="add" disabled data-section-id = "0" id="addSlSaveBtn0" ><span><?php echo $this->config->item('save_btn_txt'); ?></span><i class="fas fa-save"></i></button>
												</div>
											</div>
										</div>
									</div>
									
									
									
									<!-- <div class="mobileEditTxt">
										<div class="row">
											<div class="col-md-12 col-sm-12 col-12 alCenter">
												<div class="row">
													<div class="col-md-3 col-sm-3 col-3 sflSection">
														<div class="mobileEditTitle default_black_bold_medium"><?php echo $this->config->item('pa_profile_management_spoken_foreign_languages_section_title'); ?></div>
														<div class="categoryPart">
															<div class="pmAeSelect">
																<div class="form-group default_dropdown_select">
																	<select name="oLName0" class="oLName0" onchange="chooseSpokenLanguage(this.value, '0')">
																		<option value=""><?php echo $this->config->item('pa_profile_management_spoken_foreign_languages_section_select_language_initial_selection'); ?></option>
																	</select>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-3 col-sm-3 col-3 sflSection">
														<div class="mobileEditTitle default_black_bold_medium"><?php echo $this->config->item('pa_profile_management_spoken_foreign_languages_section_title_understanding'); ?></div>
														<div class="subCatPart">
															<div class="form-group default_dropdown_select">
																<select name="oLUnderstanding0" class="oLUnderstanding0" disabled="disabled" onchange="chooseSlUnderstanding(this.value, '0')">
																	<option value=""><?php echo $this->config->item('pa_profile_management_spoken_foreign_languages_section_select_level_initial_selection'); ?></option>
																	<?php
																	$spoken_lang_understanding_options = $this->config->item('pa_profile_management_spoken_languages_understanding_drop_down_options');
																	foreach($spoken_lang_understanding_options as $value){
																		echo '<option value="'.$value.'">'.$value.'</option>';
																	}
																	?>
																</select>
															</div>
														</div>
													</div>
													<div class="col-md-3 col-sm-3 col-3 sflSection">
														<div class="mobileEditTitle default_black_bold_medium"><?php echo $this->config->item('pa_profile_management_spoken_foreign_languages_section_title_speaking'); ?></div>
														<div class="subCatPart">
															<div class="form-group default_dropdown_select">
																<select name="oLSpeaking0" class="oLSpeaking0" disabled="disabled" onchange="chooseSlSpeaking(this.value, '0')">
																	<option value=""><?php echo $this->config->item('pa_profile_management_spoken_foreign_languages_section_select_level_initial_selection'); ?></option>
																	<?php
																	$spoken_lang_speaking_options = $this->config->item('pa_profile_management_spoken_languages_speaking_drop_down_options');
																	foreach($spoken_lang_speaking_options as $value){
																		echo '<option value="'.$value.'">'.$value.'</option>';
																	}
																	?>
																</select>
															</div>
														</div>
													</div>
													<div class="col-md-3 col-sm-3 col-3 sflSection">
														<div class="mobileEditTitle default_black_bold_medium"><?php echo $this->config->item('pa_profile_management_spoken_foreign_languages_section_title_writing'); ?></div>
														<div class="subCatPart">
															<div class="form-group default_dropdown_select">
																<select name="oLWriting0" class="oLWriting0" disabled="disabled" onchange="chooseSlWriting(this.value, '0')">
																	<option value=""><?php echo $this->config->item('pa_profile_management_spoken_foreign_languages_section_select_level_initial_selection'); ?></option>
																	<?php
																	$spoken_lang_writing_options = $this->config->item('pa_profile_management_spoken_languages_writing_drop_down_options');
																	foreach($spoken_lang_writing_options as $value){
																		echo '<option value="'.$value.'">'.$value.'</option>';
																	}
																	?>
																</select>
															</div>
														</div>
													</div>
												</div>								
												
											</div>
											<div class="col-md-12 col-sm-12 col-12 pmAeMob">
												<div class="pmAeSelect">
													<div class="pmAction">
														<button type="button" class="btn blue_btn default_btn pmSave saveSpokenLanguageBtn" data-action-type="add" disabled data-section-id = "0" id="addSlSaveBtn0"><?php echo $this->config->item('save_btn_txt'); ?></button><button type="button" class="btn default_btn red_btn pmCheck" id="addSlCancelBtn0" onclick="cancelSpokenLanguage('0', '0')"><?php echo $this->config->item('cancel_btn_txt'); ?></button>
													</div>
												</div>
											</div>
										</div>
									</div> -->
								</div>
								
								
							</div>
							<!--- Desktop View End --->
							<!--- Mobile View Start --->
							<div class="aoe_Mobile">
								<!-- Edit Section Start -->
								<div class="pmcsa mobSFL_Head">									
									<div class="row">
										<div class="col-sm-12">
											<div class="catPart">
												<div class="categoryPart">
													<div class="default_black_bold_medium"><?php  echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title'); ?></div>
												</div>
												<div class="categoryPart">
													<div class="pmAeSelect">
														<div class="form-group default_dropdown_select">
															<select name="category0" id="category0">
																<option>Vybrat kategori</option>
																<option>Accounting Consulting</option>
																<option>Auto moto, doprava</option>
																<option>Cooking</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="catPart">
												<div class="categoryPart">
													<div class="default_black_bold_medium"><?php  echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title_understanding'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title_understanding'); ?></div>
												</div>
												<div class="categoryPart">
													<div class="pmAeSelect">
														<div class="form-group default_dropdown_select">
															<select name="category0" id="category0">
																<option>Vybrat kategori</option>
																<option>Accounting Consulting</option>
																<option>Auto moto, doprava</option>
																<option>Cooking</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="catPart">
												<div class="categoryPart">
													<div class="default_black_bold_medium"><?php  echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title_speaking'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title_speaking'); ?></div>
												</div>
												<div class="categoryPart">
													<div class="pmAeSelect">
														<div class="form-group default_dropdown_select">
															<select name="category0" id="category0">
																<option>Vybrat kategori</option>
																<option>Accounting Consulting</option>
																<option>Auto moto, doprava</option>
																<option>Cooking</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="catPart">
												<div class="categoryPart">
													<div class="default_black_bold_medium"><?php  echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_title_writing'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_title_writing'); ?></div>
												</div>
												<div class="categoryPart">
													<div class="pmAeSelect">
														<div class="form-group default_dropdown_select">
															<select name="category0" id="category0">
																<option>Vybrat kategori</option>
																<option>Accounting Consulting</option>
																<option>Auto moto, doprava</option>
																<option>Cooking</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="catPart">
												<div class="pmAeMob">
													<div class="pmAeSelect">
														<div class="pmAction">
															<button type="button" class="btn default_btn red_btn"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" class="btn blue_btn default_btn disabled"><?php echo $this->config->item('save_btn_txt'); ?></button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- Edit Section End -->
								<!-- Save Section Start -->
								<div class="pmcsa mobSFL_Body">
									<div class="row">
										<div class="col-sm-12 alCenter">
											<div class="saveCat">
												<div class="categoryPart">
													<div class="pmAeSelect">
														<div class="pmAExpt default_black_bold_medium">Catalan</div>
													</div>
												</div>
												<div class="subcategoryPart saveTxt aoeText">
													<div class="pmAeSelect">
														<div class="pmAExpt default_black_regular_medium">
															<span><small><?php echo $this->config->item('pa_profile_management_base_information_section_spoken_languages_title'); ?>:</small>C1</span>
															<span><small><?php echo $this->config->item('pa_profile_management_base_information_section_spoken_languages_title_understanding'); ?>:</small>C1</span>
															<span><small><?php echo $this->config->item('pa_profile_management_base_information_section_spoken_languages_title_speaking'); ?>:</small>C1</span>
															<div class="clearfix"></div>
														</div>
													</div>
												</div>
												<div class="pmAeMob">
													<div class="pmAeSelect">
														<div class="pmAction">
															<button type="button" class="btn default_btn red_btn"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn"><?php echo $this->config->item('edit_btn_txt'); ?></button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- Save Section End -->
								<!-- Save Section Start -->
								<div class="pmcsa mobSFL_Body">
									<div class="row">
										<div class="col-sm-12 alCenter">
											<div class="saveCat">
												<div class="categoryPart">
													<div class="pmAeSelect">
														<div class="pmAExpt default_black_bold_medium">Catalan</div>
													</div>
												</div>
												<div class="subcategoryPart saveTxt aoeText">
													<div class="pmAeSelect">
														<div class="pmAExpt default_black_regular_medium">
															<span><small><?php echo $this->config->item('pa_profile_management_base_information_section_spoken_languages_title'); ?>:</small>C1</span>
															<span><small><?php echo $this->config->item('pa_profile_management_base_information_section_spoken_languages_title_understanding'); ?>:</small>C1</span>
															<span><small><?php echo $this->config->item('pa_profile_management_base_information_section_spoken_languages_title_speaking'); ?>:</small>C1</span>
															<div class="clearfix"></div>
														</div>
													</div>
												</div>
												<div class="pmAeMob">
													<div class="pmAeSelect">
														<div class="pmAction">
															<button type="button" class="btn default_btn red_btn"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn"><?php echo $this->config->item('edit_btn_txt'); ?></button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- Save Section End -->
								<!-- Save Section Start -->
								<div class="pmcsa mobSFL_Body">
									<div class="row">
										<div class="col-sm-12 alCenter">
											<div class="saveCat">
												<div class="categoryPart">
													<div class="pmAeSelect">
														<div class="pmAExpt default_black_bold_medium">Catalan</div>
													</div>
												</div>
												<div class="subcategoryPart saveTxt aoeText">
													<div class="pmAeSelect">
														<div class="pmAExpt default_black_regular_medium">
															<span><small><?php echo $this->config->item('pa_profile_management_base_information_section_spoken_languages_title'); ?>:</small>C1</span>
															<span><small><?php echo $this->config->item('pa_profile_management_base_information_section_spoken_languages_title_understanding'); ?>:</small>C1</span>
															<span><small><?php echo $this->config->item('pa_profile_management_base_information_section_spoken_languages_title_speaking'); ?>:</small>C1</span>
															<div class="clearfix"></div>
														</div>
													</div>
												</div>
												<div class="pmAeMob">
													<div class="pmAeSelect">
														<div class="pmAction">
															<button type="button" class="btn default_btn red_btn"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn"><?php echo $this->config->item('edit_btn_txt'); ?></button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- Save Section End -->
							</div>
							<!--- Mobile View End --->
							
							<div class="terms_border">
							<span class="default_terms_text"><?php echo $this->config->item('pa_user_profile_page_spoken_languages_levels_description_msg'); ?></span>
							</div>
							<div class="row lhNormal" id="addSpokenLanguageBtnSec" style="<?php echo $add_spoken_language_button_style; ?>">
								<div class="col-md-12 col-sm-12 col-12">
									<div class="pmaebtn">
										<button type="button" id="addSpokenLanguageBtn" class="btn default_btn blue_btn"><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_add_another_category_btn'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_add_another_category_btn'); ?></button>
									</div>
								</div>
							</div>
							<div class="row lhNormal" id="SpokenLanguageLevelDesc" style="<?php echo $add_spoken_language_button_free_member_style; ?>">
								<div class="col-md-12 col-sm-12 col-12">
									<div class="alertCheck noChkBox foreignAlertChkbox"><span class=" free_subscriber_max_entries_membership_upgrade_calltoaction">
										<?php  
											if($user_detail['is_authorized_physical_person'] == 'Y'){
												$spoken_languages_entries_membership_upgrade_calltoaction = $this->config->item('ca_app_user_profile_management_spoken_languages_page_free_membership_subscriber_max_spoken_languages_entries_membership_upgrade_calltoaction');
											}else{
												$spoken_languages_entries_membership_upgrade_calltoaction = $this->config->item('pa_user_profile_management_spoken_languages_page_free_membership_subscriber_max_spoken_languages_entries_membership_upgrade_calltoaction');
											}
											
											echo str_replace("{membership_page_url}",VPATH. $this->config->item("membership_page_url"),$spoken_languages_entries_membership_upgrade_calltoaction); ?>
										</span>
									</div>
								</div>
							</div>
						</div>
						
						<!-- Step 2nd End -->									
					</div>				
				</div>
				<!-- Content End -->			
			</div>
        <!-- Right Section End -->
		</div>
    <!-- Middle Section End -->	
	</div>
   
	
<script type="text/javascript">
var uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
//start spoken language section
var profile_management_base_information_select_language_initial_selection = "<?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_select_language_initial_selection'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_select_language_initial_selection'); ?>";
var profile_management_base_information_select_level_initial_selection = "<?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_spoken_foreign_languages_section_select_level_initial_selection'):$this->config->item('pa_profile_management_spoken_foreign_languages_section_select_level_initial_selection'); ?>";
</script>
<script src="<?= ASSETS ?>js/modules/user_profile_management_spoken_foreign_languages.js"></script>