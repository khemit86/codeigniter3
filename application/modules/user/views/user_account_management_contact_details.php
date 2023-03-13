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
        <div id="content" class="account_management_contactDetails_content body_distance_adjust">
			<div class="etSecond_step" id="work_experience_listing_data" style="display:block;">
				<!-- Profile Management Text Start -->
				<div class="default_page_heading">
					<h4><div><?php echo $this->config->item('account_management_contact_page_headline_title'); ?></div></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
				<!-- Content Start -->				
				<div class="cmFieldText">
					<div id="contact_container" class="pmdonotSection cmField">
						<!-- Step 1st Start -->
						<div class="pmAllStep">
							<div class="contDetails">
								<div class="row contdRow">
									<div class="col-md-4 col-sm-4 col-12 cdPhoneNo">
										<div class="default_checkbox_button">
											<div class="singleLine_chkBtn">
												<input type="checkbox" id="phoneTab" name="" value="phoneTab" class="chk-btn contact_detail_tab" data-target="phoneTab_container">
												<label class="singleLine_radioBtn <?php echo (trim(!empty($contact_detail['phone_number'])))?'savedRecord':'';?>" for="phoneTab" id="phone_number_label">
													<span><i class="fa fa-phone iconCont" aria-hidden="true"></i><?php echo $this->config->item('account_management_contact_details_page_phone_number_tab_txt'); ?></span>
												</label>
											</div>
										</div>
										
										<div id="phoneTab_container" class="cdField" style="display:none;">
											<?php
											echo $this->load->view('user/user_account_management_contact_details_phone_number', array('phone_number'=>trim($contact_detail['phone_number'])), true);
											?>
										</div>
									</div>
									<div class="col-md-4 col-sm-4 col-12 cdMobileNo">
										<div class="default_checkbox_button">
											<div class="singleLine_chkBtn">
												<input type="checkbox" id="mobileTab" name="" value="mobileTab" class="chk-btn contact_detail_tab" data-target="mobileTab_container">
												<label class="singleLine_radioBtn <?php echo (trim(!empty($contact_detail['mobile_phone_number'])))?'savedRecord':'';?>" for="mobileTab" id="mobile_phone_number_label">
													<span><i class="fas fa-mobile-alt iconCont" aria-hidden="true"></i><?php echo $this->config->item('account_management_contact_details_page_mobile_phone_number_tab_txt'); ?></span>
												</label>
											</div>
										</div>
										
										<div id="mobileTab_container" class="cdField1" style="display:none;">
											<?php
											echo $this->load->view('user/user_account_management_contact_details_mobile_phone_number', array('mobile_phone_number'=>trim($contact_detail['mobile_phone_number'])), true);
											?>
										</div>
									</div>
									<div class="col-md-4 col-sm-4 col-12 cdAltPhoneNo">
										<div class="default_checkbox_button">
											<div class="singleLine_chkBtn">
												<input type="checkbox" id="altphTab" name="" value="altphTab" class="chk-btn contact_detail_tab" data-target="altphTab_container">
												<label class="singleLine_radioBtn <?php echo (trim(!empty($contact_detail['additional_phone_number'])))?'savedRecord':'';?>" for="altphTab" id="additional_phone_number_label">
													<span><i class="fas fa-fax iconCont" aria-hidden="true"></i><?php echo $this->config->item('account_management_contact_details_page_addtional_phone_number_tab_txt'); ?></span>
												</label>
											</div>
										</div>
										
										<div id="altphTab_container" class="cdField2" style="display:none;">
											<?php
											echo $this->load->view('user/user_account_management_contact_details_additional_phone_number', array('additional_phone_number'=>trim($contact_detail['additional_phone_number'])), true);
											?>
										</div>
									</div>
									<div class="ctBttmBdr"></div>								
									<div class="col-md-4 col-sm-4 col-12 cdEmail">
										<div class="default_checkbox_button">
											<div class="singleLine_chkBtn">
												<input type="checkbox" id="envelopTab" name="" value="envelopTab" class="chk-btn contact_detail_tab" data-target="envelopTab_container">
												<label class="singleLine_radioBtn <?php echo (trim(!empty($contact_detail['contact_email'])))?'savedRecord':'';?>" for="envelopTab" id="contact_email_label">
													<span><i class="fas fa-envelope iconCont" aria-hidden="true"></i><?php echo $this->config->item('account_management_contact_details_page_contact_email_tab_txt'); ?></span>
												</label>
											</div>
										</div>
										
										<div id="envelopTab_container" class="cdField3" style="display:none;">
											<?php
											echo $this->load->view('user/user_account_management_contact_details_contact_email', array('contact_email'=>trim($contact_detail['contact_email'])), true);
											?>
										</div>
									</div>
									<div class="col-md-4 col-sm-4 col-12 cdSkype">
										<div class="default_checkbox_button">
											<div class="singleLine_chkBtn">
												<input type="checkbox" id="skypeTab" name="" value="skypeTab" class="chk-btn contact_detail_tab" data-target="skypeTab_container">
												<label class="singleLine_radioBtn <?php echo (trim(!empty($contact_detail['skype_id'])))?'savedRecord':'';?>" for="skypeTab" id="skype_id_label">
													<span><i class="fab fa-skype iconCont" aria-hidden="true"></i><?php echo $this->config->item('account_management_contact_details_page_skype_id_tab_txt'); ?></span>
												</label>
											</div>
										</div>
										
										<div id="skypeTab_container" class="cdField5" style="display:none;">
											<?php
											echo $this->load->view('user/user_account_management_contact_details_skype_id', array('skype_id'=>trim($contact_detail['skype_id'])), true);
											?>
										</div>
									</div>
                                    <div class="col-md-4 col-sm-4 col-12 cdWebsite">
										<div class="default_checkbox_button">
											<div class="singleLine_chkBtn">
												<input type="checkbox" id="websiteTab" name="" value="websiteTab" class="chk-btn contact_detail_tab" data-target="websiteTab_container">
												<label class="singleLine_radioBtn <?php echo (trim(!empty($contact_detail['website_url'])))?'savedRecord':'';?>" for="websiteTab" id="website_url_label">
													<span><i class="fas fa-globe iconCont" aria-hidden="true"></i><?php echo $this->config->item('account_management_contact_details_page_website_url_tab_txt'); ?></span>
												</label>
											</div>
										</div>
										
										<div id="websiteTab_container" class="cdField4" style="display:none">
											<?php
											echo $this->load->view('user/user_account_management_contact_details_website_url', array('website_url'=>trim($contact_detail['website_url'])), true);
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
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
<script src="<?= ASSETS ?>js/modules/user_account_management_contact_details.js"></script>