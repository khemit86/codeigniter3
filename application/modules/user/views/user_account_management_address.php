<?php $user = $this->session->userdata('user');	?>
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
        <div id="content" class="account_management_address_content body_distance_adjust">
			<!-- Step 1st Start -->
				<div class="displayMiddle" id="initial_view_address_container" style="<?php echo !empty($address_details) ? 'display:none;' : 'display:inline-flex;'; ?>">
					<div class="pmFirstStep">
						<div  class="default_hover_section_iconText emailNew mrgBtm0 closeHourlyrate">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 fontSize0 default_bottom_border">
									<i class="fas fa-home"></i>
									<h6><?php echo $this->config->item('pa_account_management_address_details_view_title'); ?></h6>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<p><?php echo $this->config->item('pa_account_management_address_details_initial_view_content'); ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
		<!-- Step 1st End -->
			<div class="etSecond_step">
				<!-- Profile Management Text Start -->
				<div class="default_page_heading" id="address_heading" style="<?php echo !empty($address_details) ? 'display:block;' : 'display:none;'; ?>">
					<h4><div><?php echo $this->config->item('pa_account_management_headline_title_address'); ?></div></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
				<!-- Content Start -->
				
				<div class="cmFieldText">
					<div class="cmField">
						
						<!-- Step 2nd Start -->
						<div class="pmdonotSection pmAllStep" style="display:none;" id="address_edit_container">
							<?php
							$attribute_address_details = [
								'id' => 'address_details_form',
								'class' => '',
								'role' => 'form',
								'name' => 'address_details_form',
								'enctype' => 'multipart/form-data',
							];
							echo form_open('', $attribute_address_details);
							?>
							<input type="hidden" name="u_id" value="<?php echo Cryptor::doEncrypt($user[0]->user_id);  ?>"/>
							<div class="default_country default_bottom_border">
								<label class="default_black_bold_medium countryText"><div><?php echo $this->config->item('account_management_address_details_tab_country'); ?></div></label>
								<label class="sltCountry">
									<div class="default_dropdown_select">
										<select name="address_country_id" id ="address_country_id" >
											<option value="" ><?php echo $this->config->item('select_country'); ?></option><?php foreach ($countries as $country){ ?><option value="<?php echo $country['id']; ?>"><?php echo $country['country_name'] ?></option><?php } ?></select>
										<div class="error_div_sectn clearfix"><span class="error_msg" id="address_country_id_error"></span></div>
									</div>
								</label>
								<label class="countryReset">
									<div class="reset_btn"><button <?php echo empty($user_detail['country_id'])? "disabled":""; ?> class="btn btnRefresh red_btn default_btn" id="reset_country" type="button"><?php echo $this->config->item('reset_btn_txt'); ?></button></div>
								</label>
								<div class="clearfix"></div>
							</div>
							<div class="streetAddress default_bottom_border">
								<label class="default_black_bold_medium pointer_events_auto streetTitle tooltipAuto" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo $this->config->item('account_management_address_details_street_address_tooltip'); ?>"><?php echo $this->config->item('account_management_address_details_tab_street_address'); ?></label><label id="addressText" class="streetInput"><input type="text" class="form-control avoid_space_street_address default_input_field" name="address_details_street_address" id="address_details_street_address" maxlength="<?php echo $this->config->item('account_management_address_details_street_address_maximum_length_character_limit'); ?>"></label><div class="error_div_sectn clearfix">
									<span id="street_address_error" class="error_msg"></span>
									<span class="content-count address_details_length_count_message"><?php echo ($this->config->item('account_management_address_details_street_address_maximum_length_character_limit') - strlen($user_detail['address_details_street_address'])) . '<span class="touch_line_break">'.$this->config->item('account_management_address_details_street_address_maximum_length_characters_remaining_txt').'</span>'; ?></span>
								</div>
								<div class="streetReset"><button class="btn btnRefresh red_btn default_btn" disabled id="refresh_street_address" type="button"><?php echo $this->config->item('account_management_address_details_tab_reset_field'); ?></button></div>
							</div>
							<div class="localitySection default_bottom_border czech_address_detail" <?php echo empty($user_detail['country_id'])? "style='display:none;'":"" ?>>
								<div class="default_user_address_details czech_address_detail">
									<div class="default_county" style="display:none;">
										<div class="form-group default_dropdown_select"><select class="user_address_details_county_id" id="address_county_id" name="address_county_id"><option value="" ><?php echo $this->config->item('select_county'); ?></option>
												<?php 
													foreach ($counties as $county){ 
														$countySelected = '';
														if($user_detail['county_id']==$county['id']) {
															$countySelected = 'selected';
														}
														echo '<option value="'.$county['id'].'" '.$countySelected.'>'.$county['name'].'</option>';
													}
												?></select><!--
											<div class="error_div_sectn clearfix">
												<span id="address_county_id_error" class="error_msg"></span>
											</div>--></div>
									</div>
									<div class="default_locality" style="display:none;">
										<div class="form-group default_dropdown_select"><select name="address_locality_id" id="address_locality_id" disabled><option value=""><?php echo $this->config->item('select_locality'); ?></option></select><!--
											<div class="error_div_sectn clearfix">
												<span id="address_locality_id_error" class="error_msg"></span>
											</div>--></div>
									</div>
									<div class="default_postalCode" style="display:none;">
										<div class="form-group default_dropdown_select"><select name="address_postal_code_id" id="address_postal_code_id" disabled ><option value=""><?php echo $this->config->item('select_postal_code'); ?></option></select><!--
											<div class="error_div_sectn clearfix">
												<span id="address_postal_code_id_error" class="error_msg"></span>
											</div>--></div>
									</div>
									<div class="localityReset"><button disabled class="btn btnRefresh red_btn default_btn" id="refresh_county"><?php echo $this->config->item('account_management_address_details_tab_reset_selection'); ?></button></div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="amBtn">
								<button type="button" class="btn default_btn red_btn" id="address_details_cancel"><?php echo $this->config->item('cancel_btn_txt'); ?></button>
								<button type="button" class="btn default_btn blue_btn" id="address_details_save"><?php echo $this->config->item('save_btn_txt'); ?></button>
							</div>
							<?php echo form_close(); ?> 
						</div>
						<!-- Step 2nd End -->						
						<!-- Step 3rd Start -->
						<div class="pmdonotSection pmAllStep" id="address_view_container" style="<?php echo !empty($address_details) ? 'display:block;' : 'display:none;'; ?>">
							<div class="addViewOnly">
								<div class="default_user_location adEdit default_bottom_border" id="locationVal"><span><i class="fas fa-map-marker-alt"></i></span><?php echo $address_details; ?></div>
								<div class="amBtn amEdDe">
									<button type="button" class="btn default_btn red_btn address_details_remove" id="address_details_remove"><?php echo $this->config->item('delete_btn_txt'); ?></button>
									<button type="button" class="btn default_btn green_btn address_details_edit" id=""><?php echo $this->config->item('edit_btn_txt'); ?></button>
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

var select_county = "<?php echo $this->config->item('select_county'); ?>";
var select_locality = "<?php echo $this->config->item('select_locality'); ?>";
var select_postal_code = "<?php echo $this->config->item('select_postal_code'); ?>";

// for start street address
var account_management_address_details_street_address_minimum_length_character_limit = "<?php echo $this->config->item('account_management_address_details_street_address_minimum_length_character_limit'); ?>";
var account_management_address_details_street_address_minimum_length_error_message = "<?php echo $this->config->item('account_management_address_details_street_address_minimum_length_error_message'); ?>";
var account_management_address_details_street_address_maximum_length_character_limit = "<?php echo $this->config->item('account_management_address_details_street_address_maximum_length_character_limit'); ?>";

var account_management_address_details_street_address_maximum_length_characters_remaining_txt = "<?php echo '<span class=\"touch_line_break\">'.$this->config->item('account_management_address_details_street_address_maximum_length_characters_remaining_txt').'</span>'; ?>";
var czech_republic_option_table_id = "<?php echo $this->config->item('reference_country_id') ?>";
</script>
<script src="<?= ASSETS ?>js/modules/user_account_management_address.js"></script>
<script>
$('#address_country_id option[value=""]').css('display','none');
$('#address_county_id option[value=""]').css('display','none');
$('#address_locality_id option[value=""]').css('display','none');
$('#address_postal_code_id option[value=""]').css('display','none');

</script>	