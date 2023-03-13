<?php
$user = $this->session->userdata('user');

if ($user_detail['current_membership_plan_id'] == 1) {
	$areas_of_expertise_category_limit = $this->config->item('user_profile_management_competencies_page_free_membership_subscriber_number_category_slots_allowed');
	$areas_of_expertise_subcategory_limit = $this->config->item('user_profile_management_competencies_page_free_membership_subscriber_number_subcategory_slots_allowed');
}if ($user_detail['current_membership_plan_id'] == 4) {
	$areas_of_expertise_category_limit = $this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_category_slots_allowed');
	$areas_of_expertise_subcategory_limit = $this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_subcategory_slots_allowed');
}	
?>
<!-- Step 1st Start -->
<div class="pmFirstStep" id="initialViewExpertise" style="<?php if($count_user_expertise ==0){ echo "display:block;";} else { echo "display:none;";} ?>" >
	<div class="default_hover_section_iconText emailNew mrgBtm0 closeAreaexpert">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12 fontSize0 default_bottom_border">
				<i class="fas fa-cog"></i>
				<h6><?php
				if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
					echo $this->config->item('pa_profile_management_areas_of_expertise_section_initial_view_title');
				}else if($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y'){
					echo $this->config->item('ca_app_profile_management_areas_of_expertise_section_initial_view_title');
				} else {
					echo $this->config->item('ca_profile_management_areas_of_expertise_section_initial_view_title');
				}
				?></h6>
			</div>
			<div class="col-md-12 col-sm-12 col-12">
				<p><?php
				if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
					echo $this->config->item('pa_profile_management_areas_of_expertise_section_initial_view_content');
				}else if($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y'){
					echo $this->config->item('ca_app_profile_management_areas_of_expertise_section_initial_view_content');
				} else {
					echo $this->config->item('ca_profile_management_areas_of_expertise_section_initial_view_content');
				}
				?></p>
			</div>
		</div>
	</div>
</div>
<!-- Step 1st End -->

<div id="editAreaexpert1" class="pmdonotSection pmarEp rowHide" style="<?php if($count_user_expertise ==0){ echo "display:none;";} else { echo "display:block;";} ?>">							
	<!--- Desktop View Start --->
	<div class="aoe_desktop">
		<!-- <div class="pmAeH">
			<div class="row">
				<div class="col-md-12 categoryPartHead">
					<div class="categoryPart">
						<div class="default_black_bold_medium"><?php
						if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
							echo $this->config->item('pa_profile_management_areas_of_expertise_section_title_categories');
						} else {
							echo $this->config->item('ca_profile_management_areas_of_expertise_section_title_categories');
						}
						?></div>
					</div>
					<div class="subcategoryPart aoeText">
						<div class="default_black_bold_medium"><?php
						if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
							echo $this->config->item('pa_profile_management_areas_of_expertise_section_title_subcategories');
						} else {
							echo $this->config->item('ca_profile_management_areas_of_expertise_section_title_subcategories');
						}
						?></div>
					</div>
				</div>
				<div class="col-md-1"></div>
			</div>
		</div> -->
		<input type="hidden" id="usedCategory" value="<?php echo $used_category; ?>">
        <input type="hidden" id="categorylimit" value="<?php echo $areas_of_expertise_category_added; ?>">
		
		<div id="mainRow">
			<?php echo $record; ?>
		</div>
		<div class="pmcsa" id="addCategory" style="display:none;">
			<div class="row">
				<div class="col-md-11 areaExpert_section editAoE">
					<div class="categoryPart">
							<div class="form-group default_dropdown_select">
								<select name="category0" id="category0" onchange="chooseCategory(this.value, '0', <?php echo $areas_of_expertise_subcategory_limit; ?>)">
									<option value=""><?php
									if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
										echo $this->config->item('pa_profile_management_areas_of_expertise_section_select_areas_of_expertise_category_initial_selection');
									} else {
										echo $this->config->item('ca_profile_management_areas_of_expertise_section_select_areas_of_expertise_category_initial_selection');
									}
									?></option>
											<?php
											foreach ($areas_of_expertise_category as $category) {
												if (!in_array($category['id'], $used_category_arr)) {
													echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
												}
											}
											?>
								</select>
							</div>
					</div>
						<?php for ($s = 1; $s <= $areas_of_expertise_subcategory_limit; $s++) { ?>   
						<div class="subCatPart subcategory0_<?php echo $s; ?>" style="display:none">
							<div class="form-group default_dropdown_select">
								 <select name="subcategory0_<?php echo $s; ?>" id="subcategory0_<?php echo $s; ?>" onchange="chooseSubcategory(this.value, '0', <?php echo $s; ?>, <?php echo $areas_of_expertise_subcategory_limit; ?>)" disabled="disabled">
                                    <option value=""><?php
									if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
										echo $this->config->item('pa_profile_management_areas_of_expertise_section_select_areas_of_expertise_subcategory_initial_selection');
									} else {
									echo $this->config->item('ca_profile_management_areas_of_expertise_section_select_areas_of_expertise_subcategory_initial_selection');
									}
									?></option>
                                    </select>
								<input type="hidden"  class="subcategory0_<?php echo $s; ?>"/>
							</div>
						</div>
						<?php } ?>
				</div>
				<div class="col-md-1 pmAeMob areaExpert_Btnsection">
					<div class="pmAeSelect">
						<div class="pmAction saveMode deskTopView">
							<button class="btn pmCheck default_icon_red_btn" id="addCancelBtn" onclick="cancelAreaOfExpertise('0', '0', <?php echo $areas_of_expertise_subcategory_limit; ?>)"><i class="fas fa-times"></i></button><button class="btn pmSave default_icon_blue_btn" id="addSaveBtn" onclick="saveAreaOfExpertise('0', '0', <?php echo $areas_of_expertise_subcategory_limit; ?>,'0','add')"><i class="fas fa-save"></i></button>
						</div>
						<div class="pmAction saveMode mobView">
							<button class="btn pmCheck default_btn red_btn" id="addCancelBtn" onclick="cancelAreaOfExpertise('0', '0', <?php echo $areas_of_expertise_subcategory_limit; ?>)"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button class="btn pmSave default_btn blue_btn" id="addSaveBtnM" onclick="saveAreaOfExpertise('0', '0', <?php echo $areas_of_expertise_subcategory_limit; ?>,'0','add')"><?php echo $this->config->item('save_btn_txt'); ?></button>
						</div>
					</div>
				</div>
			</div>
			<div class="error_div_sectn clearfix" id="invalid_area_expertise_error_div" style="display: none;"><span class="error_msg invalid_area_expertise_error"></span></div>
		</div>
		
	</div>
	<!--- Desktop View End --->
	<!--- Mobile View Start --->
	<div class="aoe_Mobile">
		<div class="pmAeH">
			<div class="row">
				<div class="col-md-12">
					<div class="categoryPart">
						<div class="default_black_bold_medium">Kategorie(p)</div>
					</div>
					<!-- <div class="subcategoryPart aoeText">
						<div class="default_black_bold_medium">Podkategorie(p)</div>
					</div> -->
				</div>
				<!-- <div class="col-md-1"></div> -->
			</div>
		</div>
		<!-- Edit Section Start -->
		<div class="pmcsa">									
			<div class="row">
				<div class="col-sm-12">
					<div class="catPart">
						<div class="categoryPart">
							<div class="default_black_bold_medium">Kategorie(p)</div>
						</div>
						<div class="categoryPart">
							<div class="pmAeSelect">
								<div class="form-group default_dropdown_select">
									<select class="form-control" name="category0" id="category0">
										<option>Vybrat kategori</option>
										<option>Accounting Consulting</option>
										<option>Auto moto, doprava</option>
										<option>Cooking</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="procatPart">
						<div class="categoryPart">
							<div class="default_black_bold_medium">Podkategorie(p)</div>
						</div>
						<div class="subcategoryPart">
							<div class="subCatPart">
								<div class="form-group default_dropdown_select">
									<select class="form-control">							
									<option value="">Vybrat podkategorii(p)</option></select>
									<input type="hidden" class="subcategory0_2">
								</div>
							</div>
							<div class="subCatPart">
								<div class="form-group default_dropdown_select">
									<select class="form-control" disabled="disabled">
									<option value="">Vybrat podkategorii(p)</option></select>
									<input type="hidden" class="subcategory0_2">
								</div>
							</div>
							<div class="subCatPart">
								<div class="form-group default_dropdown_select">
									<select class="form-control" disabled="disabled">		
									<option value="">Vybrat podkategorii(p)</option></select>
									<input type="hidden" class="subcategory0_2">
								</div>
							</div>
						</div>
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
		<div class="pmcsa">
			<div class="row">
				<div class="col-sm-12 alCenter">
					<div class="saveCat">
						<div class="categoryPart">
							<div class="pmAeSelect">
								<div class="pmAExpt default_black_bold_medium">Legal</div>
							</div>
						</div>
						<div class="subcategoryPart saveTxt aoeText">
							<div class="pmAeSelect">
								<div class="pmAExpt default_black_regular_medium">
									<span><i class="fas fa-bars"></i>Other - Legal Related Tasks</span>
									<span><i class="fas fa-bars"></i>Lbw</span>
									<span><i class="fas fa-bars"></i>Family Law</span>
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
		<div class="pmcsa">
			<div class="row">
				<div class="col-sm-12 alCenter">
					<div class="saveCat">
						<div class="categoryPart">
							<div class="pmAeSelect">
								<div class="pmAExpt default_black_bold_medium">Legal</div>
							</div>
						</div>
						<div class="subcategoryPart saveTxt aoeText">
							<div class="pmAeSelect">
								<div class="pmAExpt default_black_regular_medium">
									<span><i class="fas fa-bars"></i>Other - Legal Related Tasks</span>
									<span><i class="fas fa-bars"></i>Lbw</span>
									<span><i class="fas fa-bars"></i>Family Law</span>
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
		<div class="pmcsa">
			<div class="row">
				<div class="col-sm-12 alCenter">
					<div class="saveCat">
						<div class="categoryPart">
							<div class="pmAeSelect">
								<div class="pmAExpt default_black_bold_medium">Legal</div>
							</div>
						</div>
						<div class="subcategoryPart saveTxt aoeText">
							<div class="pmAeSelect">
								<div class="pmAExpt default_black_regular_medium">
									<span><i class="fas fa-bars"></i>Other - Legal Related Tasks</span>
									<span><i class="fas fa-bars"></i>Lbw</span>
									<span><i class="fas fa-bars"></i>Family Law</span>
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
	<?php
	if($user_detail['current_membership_plan_id'] == '1'){ // for free
			
		
		if ($areas_of_expertise_category_limit > $areas_of_expertise_category_added){
			$add_category_button_style = "";
			$add_category_section_style = "";
			$add_category_button_free_member_style = "display:none";
		
		}else{
			
			$add_category_button_style = "display:none";
			$add_category_section_style = "display:none";
			$add_category_button_free_member_style = "";
		}
		if($areas_of_expertise_category_added >= $this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_category_slots_allowed')){
			$add_category_section_style = "display:none";
			$add_category_button_style = "display:none";
			$add_category_button_free_member_style = "display:none";
		}
		
	}else{	
		$add_category_section_style = "display:none";
		$add_category_button_style = "display:none";
		$add_category_button_free_member_style = "display:none";
		
		if ($areas_of_expertise_category_limit > $areas_of_expertise_category_added){
			$add_category_section_style = "";
			$add_category_button_style = "";
		}
	}
	?>
	<div class="row lhNormal" id="addCategoryBtnSec" style="<?php echo $add_category_section_style; ?>">
		<div class="col-md-12 col-sm-12 col-12">
			<div class="pmaebtn">
				<button type="button" id="addCategoryBtn" class="btn default_btn blue_btn" style="<?php echo $add_category_button_style; ?>"><?php
									if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
										echo $this->config->item('pa_profile_management_areas_of_expertise_section_title_add_another_category_btn');
									} else {
										echo $this->config->item('ca_profile_management_areas_of_expertise_section_title_add_another_category_btn');
									}
									?></button>
				<!--
				<button type="button" id="addCategoryBtnFreeSubscriber" class="btn default_btn orange_btn" style="display:none">Upgrade gold membership</button>-->
			</div>
		</div>
	</div>
	<div class="row lhNormal" id="areas_of_expertise_notification" style="display:<?php echo $areas_of_expertise_category_added > 0 ? 'block' : 'none' ?>">
		<div class="col-md-12 col-sm-12 col-12 pl-0 areaNotification">										
			<div>
				<label class="default_checkbox default_small_checkbox">
					<input type="checkbox" class="receive_notification" data-id="newly_posted" <?php echo !empty($newly_posted_notification) ? 'checked': '';?>>
					<span class="checkmark"></span>
				<span class="default_terms_text <?php echo !empty($newly_posted_notification) ? 'boldCat': '';?>"><?php echo $this->config->item('profile_management_areas_of_expertise_newly_posted_projects_user_notifications_consent_txt'); ?></span></label>
			</div>
		</div>
	</div>
	<div class="row lhNormal" id="addCategoryBtnFreeSubscriber" style="<?php echo $add_category_button_free_member_style; ?>">
		<div class="col-md-12 col-sm-12 col-12 areaNotification">
			<div class="alertCheck noChkBox compAreaChkbox"><span class=" free_subscriber_max_entries_membership_upgrade_calltoaction">
				<?php  echo str_replace("{membership_page_url}",VPATH. $this->config->item("membership_page_url"),$this->config->item('user_profile_management_areas_of_expertise_page_free_membership_subscriber_max_categories_entries_membership_upgrade_calltoaction')); ?>
				</span>
			</div>
		</div>
	</div>
	
</div>