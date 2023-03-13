<?php 
$user = $this->session->userdata('user');
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
        <div id="content" class="profile_mother_tongue_page body_distance_adjust">
			<!-- Step 1st Start -->
			<div class="displayMiddle" id="initialViewMothertongue" style="<?php echo !empty($mother_tongue_language_id) ? 'display:none;' : 'display:inline-flex;'; ?>">
				<div class="pmFirstStep">
					<div  class="default_hover_section_iconText emailNew mrgBtm0 closeHourlyrate" id="initial_view_mother_toungue">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 fontSize0 default_bottom_border">
								<i class="fas fa-globe"></i>
								<h6><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_mother_tongue_section_initial_view_title'):$this->config->item('pa_profile_management_mother_tongue_section_initial_view_title'); ?></h6>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<p><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_mother_tongue_section_initial_view_content'):$this->config->item('pa_profile_management_mother_tongue_section_initial_view_content'); ?></p>
							</div>
						</div>
					</div>
					</div>
			</div>
			<!-- Step 1st End -->
			<div class="etSecond_step" id="work_experience_listing_data"  >
				<!-- Profile Management Text Start -->
				<div class="default_page_heading" id="mother_tongue_language_heading" style="<?php echo !empty($mother_tongue_language_id) ? 'display:block;' : 'display:none;'; ?>">
					<h4><div><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_mother_tongue_page_headline_title'):$this->config->item('pa_profile_management_mother_tongue_page_headline_title'); ?></div></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
				<!-- Content Start -->
				<div class="cmFieldText">
					<div id="native_language_container" class="cmField">
						<!-- Step 1st Start -->
						<?php
						/* <div class="pmFirstStep" id="initialViewMothertongue" style="<?php if(empty($mother_tongue_language_id)){ echo "display:block;";} else { echo "display:none;";} ?>">
							<div  class="default_hover_section_iconText emailNew mrgBtm0 closeMothertongue">
								<div class="row">
									<div class="col-md-12 col-sm-12 col-12 fontSize0 default_bottom_border">
										<i class="fas fa-globe"></i>
										<h6><?php echo $this->config->item('pa_profile_management_base_information_section_mother_tongue_initial_view_title'); ?></h6>
									</div>
									<div class="col-md-12 col-sm-12 col-12">
										<p><?php echo $this->config->item('pa_profile_management_base_information_section_mother_tongue_initial_view_content'); ?></p>
									</div>
								</div>
							</div>
						</div> */
						?>
						<!-- Step 1st End -->
						<!-- Step 2nd Start -->
						<div class="pmdonotSection pmFirstStep" id="addeditMothertongue" style="display:none;">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-12 tlp0">
									<div class="natvLanguage">
										<div class="pmMT">											
											<div class="form-group default_dropdown_select" id="user_mother_tongue_save_ajax_div">
												<select class="form-control"  name="user_mother_tongue" id="user_mother_tongue_input">
												<option value=""><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_mother_tongue_section_select_mother_tongue_initial_selection'):$this->config->item('pa_profile_management_mother_tongue_section_select_mother_tongue_initial_selection'); ?></option>
												<?php /* <option value="12"><?php echo $this->config->item('mother_tongue_languages_drop_down_top_displayed_option_value'); ?></option> */
												?>
												<?php
												foreach ($mother_tongue as $val) {
													if($val['id'] != '12'){
													?>
													<option value="<?php echo $val['id']; ?>"><?php echo $val['language']; ?></option>
												<?php } } ?>
												</select>
											</div>
											<div class="error_div_sectn clearfix">
												<span id="user_mother_tongue_error" class="error_msg"></span>
											</div>
										</div>
										<div class="amBtn">
											<button type="button" class="btn default_btn red_btn cancel_mother_tongue_language" id="user_mother_tongue_cancel" data-action-type ="cancel" data-section-name="user_mother_tongue" data-view-section="viewMothertongue" data-add-edit-section="addeditMothertongue"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" class="btn default_btn blue_btn save_mother_tongue_language" id="user_mother_tongue_save" data-section-name="user_mother_tongue" data-view-section="viewMothertongue" data-add-edit-section="addeditMothertongue"><?php echo $this->config->item('save_btn_txt'); ?></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Step 2nd End -->
						<!-- Step 3rd Start -->
						<div class="pmdonotSection pmFirstStep" id="viewMothertongue" style="<?php if(!empty($mother_tongue_language_id)){ echo "display:block;"; }else{ echo "display:none;"; }?>">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-12 tlp0">
									<div class="natvLanguage">
										<div class="pmMT">	
											<div class="form-group">
											<input type="text" id="user_mother_tongue_input_view" class="form-control default_input_field" disabled="disabled" value="<?php echo $mother_tongue_language_view; ?>" placeholder="">
											</div>
											
											<!--<div class="form-group default_dropdown_select" id="user_mother_tongue_save_ajax_div">
												<select class="form-control" id="user_mother_tongue_input_view" disabled="disabled">
													<option value=""><?php echo $this->config->item('pa_profile_management_mother_tongue_section_select_mother_tongue_initial_selection'); ?></option>
													<option value="12" <?php echo ('12' == $mother_tongue_language_id) ? 'selected' : '' ?>><?php echo $this->config->item('mother_tongue_languages_drop_down_top_displayed_option_value'); ?></option>
													<?php
														foreach ($mother_tongue as $val) {
														if($val['id'] != '12'){
														?>
														<option value="<?php echo $val['id']; ?>" <?php echo ($val['id'] == $mother_tongue_language_id) ? 'selected' : '' ?>><?php echo $val['language']; } ?></option>
												<?php } ?>
												</select>
												
											</div>-->
										</div>
										<div class="amBtn">
											<button type="button" class="btn default_btn red_btn delete_mother_tongue_language" id="user_mother_tongue_remove" data-section-name="user_mother_tongue" data-view-section="viewMothertongue" data-add-edit-section="addeditMothertongue"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn default_btn green_btn edit_mother_tongue_language" id="user_mother_tongue_edit_btn" data-section-name="user_mother_tongue" data-view-section="viewMothertongue" data-add-edit-section="addeditMothertongue" data-action-type ="edit"><?php echo $this->config->item('edit_btn_txt'); ?></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Step 3rd End -->								
					</div>				
				</div>
				<!-- Content End -->			
			</div>
        <!-- Right Section End -->
		</div>
    <!-- Middle Section End -->	
	</div>
<script>
var uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
</script>	
<script type="text/javascript"></script>
<script src="<?= ASSETS ?>js/modules/user_profile_management_mother_tongue.js"></script>