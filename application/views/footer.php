<div class="modal alert-popup" id="error_popup" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
	  <!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close popup_close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
			  <div class="modal-header-inner">
				<h4 class="modal-title" id="error_popup_heading"></h4>
			  </div>
			</div>
			<div class="modal-body text-center">
			  <p id="error_popup_body"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn default_btn red_btn popup_close" data-dismiss="modal"><?php
				echo $this->config->item('close_btn_txt'); ?></button>
			</div>
		</div>
	</div>
</div>
<?php
if(empty($current_page)) {
	$current_page = '';
}
$footer_display = '';
if (isset($current_page) && $current_page == 'find_professionals') { 
    $footer_display = 'style="display:none"';
}
$footer_position = 'footerFixed';
if (isset($current_page) && $current_page == 'dashboard' && $this->config->item('dashboard_left_projects')>0) {
    $footer_position = 'footerVisible';
}
?>
<?php if (isset($current_page) && $current_page !== 'signup' && $current_page !== 'signin' && $current_page !== 'reset_login_password' && $current_page !== 'send_password_reset_confirmation' && $current_page !== 'reset_password' && $current_page !== 'successfull_password_reset' && $current_page !== 'successful_signup_confirmation' && $current_page !== 'signup_verify_page' && $current_page !== 'successful_signup_verification'  && $current_page !== 'post_project' && $current_page !== 'edit_temporary_project_preview' && $current_page !== 'edit_draft_project' && $current_page !== 'edit_project' && $current_page !== 'chat_room' && $current_page !== 'chat' && $current_page !== 'chat-no-record' && $current_page !== 'chat-single-record' && $current_page !== 'initial-view-when-contact-from-contact-me-now' && $current_page !== 'chat-initial' && $current_page !== 'test' && $current_page !== 'hidden_project'): ?>


<footer id="footer" class="zIndex1 footerOnly <?php echo $footer_position; ?>" <?php echo $footer_display; ?>>
	<div class="full-footer">
		<div id="footerContent" style="opacity:1;">
			<div class="container">
				<div class="row cstm-marg-top">
					<div class="col-md-12 first_col_foot"><div class="footer-title">SPOLEČNOST</div><ul class="footer-list"><li><a href="<?php echo site_url($this->config->item('about_us_page_url')); ?>">O NÁS</a></li><li><a href="<?php echo site_url($this->config->item('terms_and_conditions_page_url')) ?>">OBCHODNÍ PODMÍNKY</a></li><li><a href="<?php echo site_url($this->config->item('privacy_policy_page_url')) ?>">ZÁSADY OCHRANY OSOBNÍCH ÚDAJŮ</a></li><li><a href="<?php echo site_url($this->config->item('code_of_conduct_page_url')) ?>">KODEX CHOVÁNÍ</a></li></ul></div>
					<div class="col-md-12 second_col_foot">
						<div class="footer-title">O TRAVAI</div>
						<ul class="footer-list">
							<?php 
							if($this->session->userdata ('user')){
							?><li><a href="<?php echo site_url($this->config->item('membership_page_url'));?>">ČLENSTVÍ</a></li><?php } ?>
							<li><a href="<?php echo site_url($this->config->item('trust_and_safety_page_url')) ?>">DŮVĚRA A BEZPEČNOST</a></li>
							<li><a href="<?php echo site_url($this->config->item('referral_program_page_url')) ?>">PARTNERSKÝ PROGRAM</a></li>
							<li><a href="<?php echo site_url($this->config->item('secure_payments_process_page_url')) ?>">TRAVAI BEZPEČNÁ PLATBA</a></li>
							<li><a href="<?php echo site_url($this->config->item('fees_and_charges_page_url')) ?>">POPLATKY A CENY</a></li>
							<li><a href="<?php echo site_url($this->config->item('we_vs_them_page_url')) ?>">MY VS OSTATNÍ</a></li>
						</ul>	
					</div>
					<div class="col-md-12 third_col_foot">
						<div class="footer-title">PODPORA</div>
						<ul class="footer-list">
							<li><a href="<?php echo site_url($this->config->item('contact_us_page_url'));?>">KONTAKTY</a></li>
							<li><a href="<?php echo site_url($this->config->item('faq_page_url'));?>">ČASTO KLADENÉ OTÁZKY (F.A.Q.)</a></li>					   
						</ul>		
					</div>
					<div class="col-md-12 fourth_col_foot">
						<div class="footer-title">TRAVAI SPOLEČENSTVÍ</div>	
						<ul class="footer-list">
							<li><a href="<?php echo site_url($this->config->item('find_projects_page_url')) ?>">PRACOVNÍ POZICE & PROJEKTY</a></li>
							<li><a href="<?php echo site_url($this->config->item('find_professionals_page_url')) ?>">SEZNAM ODBORNÍKŮ</a></li>
						</ul>	
					</div>
					<div class="col-md-12 fifth_col_foot">
						<div class="footer-title">SOCIÁLNÍ SÍTĚ</div>	
						<ul class="footer-list">
							<li><a target="_blank" rel="nofollow" href="<?php echo $this->config->item('footer_company_facebook_page_url'); ?>">FACEBOOK</a></li>
							<li><a target="_blank" rel="nofollow"  href="<?php echo $this->config->item('footer_company_twitter_page_url'); ?>">TWITTER</a></li>
							<li><a target="_blank" rel="nofollow"  href="<?php echo $this->config->item('footer_company_linkedin_page_url'); ?>">LINKEDIN</a></li>
						</ul>
					</div>
					<div class="col-md-12 third_col_foot third_col_foot_only"></div>
				</div>
                <div class="row copyright-marg-top rowBottom0">
                    <div class="col-md-12 col-sm-12 col-12 fcopyR">
                        <div class="fcRight">
                            <h5>© Travai agentura, s.r.o. <?php echo date('Y'); ?></h5>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</footer>
<!-- Apply Now Button on Find Projects Modal Start -->
<div class="modal fade anModal" id="applyNow">
	<div class="modal-dialog modal-dialog">
		<div class="modal-content">
			
		</div>
	</div>
</div>
<!-- Apply Now Button on Find Projects Modal End -->
<!-- Hire Me Button Find Talents Modal Start -->
<div class="modal fade hireModal hireMe_modalOnly" id="hireMe">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header popup_header popup_header_without_text">
				<h4 class="modal-title popup_header_title"></h4>
				<button type="button" class="close close_reload hire_me_close" data-dismiss="modal"> <span aria-hidden="true">&times;</span></button>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
				<div class="popupRadio margin_top6">
					<section class="row">
						<div id="send_project_invite" class="col-6 contactBtn_popup_twoRadioBtn">
							<input type="radio" id="inlineRadio1" name="inlineRadioOptions" value="1" onclick="openHire(1)">
							<label for="inlineRadio1">
								<span><?php echo $this->config->item('get_in_contact_popup_invite_to_project_option')?></span>
							</label>
						</div>
						<div id="send_contact" class="col-6 contactBtn_popup_twoRadioBtn">
							<input type="radio" id="inlineRadio2" name="inlineRadioOptions" value="2" >
							<label for="inlineRadio2">
                                <span><?php echo $this->config->item('get_in_contact_popup_send_get_in_contact_request_option')?></span>
							</label>
						</div>
                        <div class=" d-none col-6 contactBtn_popup_twoRadioBtn">
                            <input type="radio" id="inlineRadio3" name="inlineRadioOptions" value="3" >
                            <label for="inlineRadio3" class="contact-bidder">
                                <span><?php echo $this->config->item('get_in_contact_popup_contact_via_chat_option')?></span>
                            </label>
                        </div>
					</section>
				</div>
				<div class="row no_project" style="display:none;">
					<div class="col-md-6 col-sm-6 col-12 modalProjectLeft">
						<div class="modal_no_Project" style="display:none;font-size: 12px;color:#ff0000;" id="no_project"></div>
					</div>
					<div class="col-md-6 col-sm-6 col-12 modalProjectright">
						<div class="default_blank_message modal_no_contact" id="no_contact" style="display:none;">
                            
						</div>
					</div>
				</div>
				<div class="hire_invite_project" id="hire1" style="display: none;">
					<div class="default_dropdown_select">
							<select name="open_projects_drop_down" id="invite_project_section_projects_drop_down" style="display:none" class="invite_project_section">
							</select>
					</div>
					<div class="error_msg required" id="invite_project_error"></div>
					<div class="default_terms_text form-group delimeter_top" id="invite_project_section_delimiter" style="display:none;">
                        <div class="default_checkbox default_small_checkbox">
                            <input class="checked_input" value="1" name="" type="checkbox" checked="">
                            <small class="checkmark"></small>
                        </div><?php echo str_replace(array('{terms_and_conditions_page_url}','{privacy_policy_page_url}'),array(site_url($this->config->item('terms_and_conditions_page_url')),site_url($this->config->item('privacy_policy_page_url'))),$this->config->item('get_in_contact_popup_send_project_invitation_disclaimer')); ?>
                    </div>
					<div class="text-right default_popup_close">
						<button type="button" class="btn default_btn red_btn hire_me_close default_popup_width_btn" data-dismiss="modal"><?php echo $this->config->item('close_btn_txt'); ?></button>
						<button type="button" class="btn default_btn blue_btn default_popup_width_btn" disabled id="send_invitation_project" style="display:none;"><?php echo $this->config->item('send_btn_txt'); ?></button>
					</div>
				</div>
				
				<div class="hire_send_request" id="hire2" style="display: none;">
					<div class="popup_body_regular_title hire_request_text">
						
					</div>
					<div class="default_terms_text form-group delimeter_top">
                        <div class="default_checkbox default_small_checkbox">
                            <input class="checked_input" value="1" name="" type="checkbox" checked="">
                            <small class="checkmark"></small>
                        </div><?php echo str_replace(array('{terms_and_conditions_page_url}','{privacy_policy_page_url}'),array(site_url($this->config->item('terms_and_conditions_page_url')),site_url($this->config->item('privacy_policy_page_url'))),$this->config->item('get_in_contact_popup_send_contact_request_disclaimer')); ?>
                    </div>
					<div class="text-center default_popup_close">
						<button type="button" class="btn default_btn blue_btn profile_another_loginBtn" id="send_invitation"><?php echo $this->config->item('get_in_contact_popup_send_invitation_btn_txt') ?></button>
					</div>
				</div>
				<div class="text-right default_popup_close" id="default_close_btn">
					<div class="popupBtn_adjust">
						<button class="btn default_btn red_btn default_popup_width_btn" id="pending_reject_request" style="display:none"><?php echo $this->config->item('get_in_contact_request_reject_btn_txt'); ?></button>
                        <button type="button" id="close_btn" class="btn default_btn red_btn close_reload default_popup_width_btn " data-dismiss="modal"><?php echo $this->config->item('close_btn_txt') ?></button>
						<button type="button" id="cancel_btn" class="btn default_btn red_btn default_popup_width_btn" style="display:none" data-dismiss="modal"><?php echo $this->config->item('close_btn_txt') ?></button>
                        <button class="btn default_btn green_btn default_popup_width_btn" id="pending_accept_request" style="display:none"><?php echo $this->config->item('accept_btn_txt'); ?></button>
                        <button class="btn default_btn green_btn request_action default_popup_width_btn" id="accept_request" style="display:none"><?php echo $this->config->item('accept_btn_txt'); ?></button>
						<button type="button" id="yes_btn" class="btn default_btn blue_btn" style="display:none"><?php echo $this->config->item('get_in_contact_modal_blocker_contact_blocked_user_unblock_btn_txt') ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Hire Me Button Find Talents Modal End -->

<!-- Send Feedback Modal Start -->
<div class="modal fade sndFbck" id="sendFeedback">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header popup_header popup_header_without_text">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
				<div class="popup_body_bold_title close_popup_title"><?php echo $this->config->item('send_feedback_popup_heading_title_modal_body'); ?></div>		
				<div class="popup_body_regular_title popup_body_border_bottom close_popup_body"><?php echo $this->config->item('send_feedback_popup_description_modal_body'); ?></div>		
				<div class="form-group mb-0 pb-0 popup_body_border_bottom">
					<textarea class="form-control default_textarea_field" id="send_feedback_description" rows="5" maxlength="<?php echo $this->config->item('send_feedback_popup_description_max_character_limit'); ?>" id="comment"></textarea>
                    <div class="error_div_sectn clearfix default_error_div_sectn">
                        <span class="content-count content_cnt"><span><?php echo $this->config->item('send_feedback_popup_description_max_character_limit'); ?></span>&nbsp;<?php echo $this->config->item('characters_remaining_message'); ?></span>
                        <span id="description_error" class="error_msg"></span>
                    </div>
				</div>	
                <div class="default_upload_btn drop_zone>">
                    <div id="overlay" class="d-none">
                        <div class="text"><?php echo $this->config->item('drop_zone_drag_and_drop_files_area_message_txt') ?></div>
                    </div>
                    <div class="send_feedback_upload_file_wrapper" style="display:inline-block">
                        <input type="file" class="send_feedback_imgupload" accept="<?php echo $this->config->item('send_feedback_popup_attachment_allowed_file_types'); ?>" style="display:none"/>
                        <button type="button" class="OpenImgUpload btn default_btn blue_btn"><i class="fa fa-cloud-upload"></i><?php echo $this->config->item('send_feedback_popup_upload_files_txt'); ?></button></div><div class="send_feedback_attachment_wrapper"></div>
                </div>	
                <div class="error_div_sectn clearfix feedback_img_error"><span id="send_feedback_image_error" style="display:flex;" class="error_msg"></span></div>		
				<div class="disclaimer default_terms_text disclaimer_separator">
					<div><?php echo str_replace(array('{terms_and_conditions_page_url}','{privacy_policy_page_url}'),array(site_url($this->config->item('terms_and_conditions_page_url')),site_url($this->config->item('privacy_policy_page_url'))),$this->config->item('send_feedback_popup_disclaimer_modal_body')); ?></div>
				</div>
			</div>
			<!-- Modal footer -->
			<div class="modal-footer text-right">
                <div class="row">
                    <div class="col-md-12 col-sm-12 rightButton">
                        <button type="button" class="btn default_btn red_btn default_popup_width_btn" data-dismiss="modal"><?php echo $this->config->item('send_feedback_popup_cancel_btn_modal_footer'); ?></button>
                        <button type="button" class="btn default_btn blue_btn btnSave submit_user_feedback default_popup_width_btn"><?php echo $this->config->item('send_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
                    </div>
                </div>	
			</div>
		</div>
	</div>
</div>
<!-- Send Feedback Modal End -->

<!-- Project Details Log Out View in Project Owner Details Section Contact Me Button Modal Start -->
<div class="modal fade hireModal contactme_modalOnly" id="contactme">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header popup_header popup_header_without_text">
				<h4 class="modal-title popup_header_title"></h4>
				<button type="button" class="close close_reload" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
				<div class="popupRadio margin_top6">
					<section>
						<div id="send_contact" class="col-6 contactBtn_popup_radioBtn">
							<input type="radio" id="inlineRadio4" name="inlineRadioOptionsOnly" value="4" >
							<label id="show" for="inlineRadio4">
								<span><?php echo $this->config->item('get_in_contact_popup_send_get_in_contact_request_option'); ?></span>
							</label>
						</div>
                        
					</section>
				</div>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-12">
						<div class="popup_body_semibold_title" id="no_contact" style="display:none"></div>
					</div>
				</div>
				
				<div class="hire_send_request" id="hire2" style="display: none;">
					<div class="popup_body_regular_title hire_request_text">
                        
                    </div>
					<div class="default_terms_text form-group delimeter_top">
                        <div class="default_checkbox default_small_checkbox">
                            <input class="checked_input" value="1" name="" type="checkbox" checked="">
                            <small class="checkmark"></small>
                        </div><?php echo str_replace(array('{terms_and_conditions_page_url}','{privacy_policy_page_url}'),array(site_url($this->config->item('terms_and_conditions_page_url')),site_url($this->config->item('privacy_policy_page_url'))),$this->config->item('get_in_contact_popup_send_contact_request_disclaimer')); ?>
						</div>
					<div class="text-center default_popup_close">
						<button type="button" class="btn default_btn blue_btn" id="send_invitation" ><?php echo $this->config->item('get_in_contact_popup_send_invitation_btn_txt'); ?></button>
					</div>
				</div>
                <div class="text-right default_popup_close " id="default_close_btn" style="display:none">
					<div class="popupBtn_adjust">
                        <button class="btn default_btn red_btn" id="pending_reject_request" style="display:none"><?php echo $this->config->item('get_in_contact_request_reject_btn_txt'); ?></button>
                        <button class="btn default_btn green_btn" id="pending_accept_request" style="display:none"><?php echo $this->config->item('accept_btn_txt'); ?></button>
                        <button type="button" id="close_btn" class="btn default_btn red_btn close_reload" data-dismiss="modal"><?php echo $this->config->item('close_btn_txt') ?></button>
						<button type="button" id="cancel_btn" class="btn default_btn red_btn" data-dismiss="modal" style="display:none"><?php echo $this->config->item('close_btn_txt') ?></button>
                        <button class="btn default_btn green_btn" id="contact_accept_request" style="display:none"><?php echo $this->config->item('accept_btn_txt'); ?></button>
						<button type="button" id="yes_btn" class="btn default_btn blue_btn" style="display:none"><?php echo $this->config->item('get_in_contact_modal_blocker_contact_blocked_user_unblock_btn_txt') ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Project Details Log Out View in Project Owner Details Section Contact Me Button Modal End -->
<!-- Modal Start for edit upgrade-->
<div class="modal fade" id="sendInvitationModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content sndInvt">
			<div class="modal-header popup_header popup_header_without_text">
				<h4 class="modal-title popup_header_title" id="user_block_modal_title"></h4>
				<button type="button" class="close reload_modal" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="popup_body_semibold_title"></div>
				<div class="disclaimer default_disclaimer_text">
					<div>
						<label class="default_checkbox">
							<input type="checkbox" id="user_block_checkbox_po">
							<span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text"><?php echo $this->config->item('user_confirmation_check_box_txt'); ?></span>
						</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="row">				
					<div class="col-md-12 col-sm-12 col-12 footerAction">
						<button type="button" class="btn default_btn red_btn" data-dismiss="modal"><?php echo $this->config->item('cancel_btn_txt'); ?></button>	
						<button type="button" class="btn default_btn blue_btn send_invitation" disabled><?php echo $this->config->item('invite_friends_send_invitations_btn_txt'); ?> <i id="send_invitation_spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal End -->
<!-- Dahboard Chat Start -->
<div class="dashboardChat bubble_chat_list" style="display:none;" onclick="myChatFunction()"><i class="fas fa-comments"></i><span class="chatCounter" ><small class="bubble_counter" style="display:none">99+</small></span></div>
<div id="myChatName" class="chatDetailsName bubble_chat_content"></div>
<!-- Dahboard Chat End -->

<?php endif; ?>
<script type="text/javascript">
    $(window).resize(function() {    
        if($(window).width()<650) {
                $("#invite_friends_modal").modal("hide");
        }
        var fh = $("#footer").height();
        if($(window).width()>749 && $(window).width()<974) {
                fh = $("#footer").height()+100;
        }
        $("#showLoader").css({
                height: window.innerHeight -fh- $("#myNavbar").height(),
                width: $(window).width()
        });
    });	
    //footer position manage
    $(document).ready(function (){
		
        <?php if (isset($current_page) && $current_page == 'dashboard' && $this->config->item('dashboard_left_projects')>0) { ?>
            setTimeout(function() {
                var wheight = $(window).height();  
                var theight = $("body").height()+$("#headerContent").height()+$("#footer").height(); 
                if(theight >= wheight) {
                     $("#footer").removeClass("footerFixed").addClass("footerVisible");
                } else {
                    $("#footer").removeClass("footerVisible").addClass("footerFixed");
                }
            }, 1500);
        <?php } ?>
		//start for common tooltip for all pages
		<?php if (isset($current_page) && ($current_page == 'dashboard' || $current_page == 'signup' || $current_page == 'invite_friends' || $current_page == 'account-management-address' || $current_page == 'account-management-company-address' || $current_page == 'deposit-funds' || $current_page == 'withdraw-funds' || $current_page == 'membership_design' || $current_page == 'project_detail')) { ?>
		$('[data-toggle="tooltip"]').tooltip();  
		$('.tooltipAuto').on('show.bs.tooltip', function () {
			$("body").css("overflow-x","hidden");
		});
		$('.tooltipAuto').on('hidden.bs.tooltip', function () {
			$("body").removeAttr("style");
		});
		
		<?php } ?>
		//end for common tooltip for all pages
    })
	// @sid Remove referral cookie from browser when user close browser.
	<?php
	if(!$this->session->userdata('user')){
	?>
	
    $(document).ready(function(){    
        
        var validNavigation = false;
        // Attach the event keypress to exclude the F5 refresh (includes normal refresh)
        $(document).bind('keypress', function(e) {
            if (e.keyCode == 116){
                validNavigation = true;
				
            }
        });
        // Attach the event click for all links in the page
        $("a").bind("click", function() {
            validNavigation = true;
        });
        // Attach the event submit for all forms in the page
        $("form").bind("submit", function() {
          validNavigation = true;
        });
        // Attach the event click for all inputs in the page
        $("input[type=submit]").bind("click", function() {
          validNavigation = true;
        }); 
        window.onbeforeunload = function() {                
            if (!validNavigation) {     
                document.cookie = 'referral_code=;expires=Thu, 01 Jan 1970 00:00:01 GMT;samesite=strict';
            }
        };
  });
  <?php
  }
  ?>

</script>
<div class="chat_container">
    <!-- Contact Chat End -->
    <div class="chatBtnDetails" style="display:none">
        <div class="myChat">
            
            <div class="activePerson">
                <div class="row">
                    <div class="col-md-10 col-sm-10 col-12 pLR0">
                        <h6 class="chat_user"><!-- <i class="fas fa-circle"></i> -->
                            <span data-toggle="tooltip" data-placement="top" title=""></span>
                        </h6>
                    </div>
                    <div class="col-md-2 col-sm-2 col-12 pR0">
                        <div class="restoreClose">
                            <span>
                                <i class="fas fa-window-minimize minimize-chat-window"></i>
                                <input type="hidden" id="chatMinMax" value="0">
                            </span>
                            <span><i class="fas fa-times close-chat" ></i></span>
                        </div>					
                    </div>
                    <div class="col-md-12 col-sm-12 col-12 pLR0">
                        <a href="#" class="chat_project" target="_blank" ><span data-toggle="tooltip" data-placement="top" title="" class="project-title"></span></a>
                    </div>
                </div>
            </div>
            <div class="btnChat drop_zone">
                <div id="overlay" class="d-none">
                    <div class="text"><?php echo $this->config->item('drop_zone_drag_and_drop_files_area_message_txt'); ?></div>
                </div>
                <div class="noMsg">
                    <div class="mesgChat">														
                        <div class="imgTwo">								
                            <div class="twoImage default_avatar_image"></div>
                            <div class="twoImage default_avatar_image"></div>
                        </div>
                        <div class="message-wrapper"></div>
                    </div>

					<div id="msgSection">
						<!-- Bottom Attachment Start -->
						<div class="bottomAttachment">
							
						</div>
                        <div class="default_error_red_message ml-2 d-none"></div>
                        <div class="default_success_green_message ml-2 d-none"></div>
						<!-- Bottom Attachment End -->
					</div>
                </div>
            </div>
            <div class="mesgSend">
                <div class="input-group auto-resize-div small_chat_window">
                    <textarea class="textarea form-control chat_text default_textarea_field" placeholder="<?php echo $this->config->item('chat_default_type_here_message_placeholder_txt'); ?>" tabindex="1"></textarea>
                    <span class="input-group-btn default_chat_action_btn">
						<input type="file" class="imgupload file_upload" style="display:none"/>
						<button class="OpenImgUpload btn green_btn xyz"><i class="fa fa-file-text" aria-hidden="true"></i></button>
                        <button class="btn blue_btn send_message default_btn" type="submit"><?php echo $this->config->item('send_btn_txt');?></button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Chat End -->
</div>
<!-- Popup Script Start -->
<div class="disable_content d-none"></div>
<script>
	function openHire(ID)
    {
		$("#send_invitation_project").css('display','none');
		$('#default_close_btn').hide();
        if (ID == 2)
        {
            $('#hire1').hide();
            $('#hire2').show();
        }
        else
        {
			$("#send_project_invite label").css({ 'pointer-events': 'none' });
			sp_id = $('#send_invitation_project').attr('data-id');
			$("#invite_project_error").html('');
			$("#invite_project_section_projects_drop_down, #invite_project_section_delimiter").css('display','none');
			$.ajax({
			
				url: SITE_URL + "projects/get_user_open_bidding_projects_send_project_invitation",
				type:"POST",
				dataType: "json",
				data:{'sp_id':sp_id,'uid':uid},
				success: function(response) {
					 if (response["status"] == 200){
						 $("#invite_project_error").show();
						$("#invite_project_section_projects_drop_down, #invite_project_section_delimiter").css('display','block');
						$("#invite_project_section_projects_drop_down").html(response['projects_options']);
						 $('#invite_project_section_projects_drop_down option[value=""]').css('display','none');
						$("#send_invitation_project").css('display','');
						
						
					 }
					 if (response["status"] == 400){
						
						if(response['error'] != ''){
							//alert("ffd");
							$("#send_invitation_project").css('display','none');
							$("#inlineRadio1").prop("checked", false);
							$("#invite_project_section_projects_drop_down, #invite_project_section_delimiter").css('display','none');
							
							$("#invite_project_error").hide();
							
							$("#no_project,.no_project").css('display','block');
                            $("#no_project").html(response['error']);
                            if(timeout) {
                                clearTimeout(timeout);
                            }
							timeout = setTimeout(function() { $('#no_project').html('');$('#no_project,.no_project').css('display','none'); }, notification_messages_timeout_interval*1000);
						}else{
							window.location.reload();
						}
					 }
				}
			});
            $('#hire1').show();
            $('#hire2').hide();
        }
    }
</script>
<script>
	$(document).ready(function(){
		$("#show").click(function(){
			$("#sendRequest").show();
		});
	});	
</script>
<!-- Popup Script End -->
<?php
    if($this->session->userdata('user') && $this->session->userdata('is_authorized')) :
        $user = $this->session->userdata('user');
        $excluded_page_list_for_socket = [
            'post_project',
            'edit_project',
            'edit_draft_project',
            'preview_draft_project',
            'temporary_project_preview',
            'edit_temporary_project_preview'
        ];
?>
<script>
	var notification_messages_timeout_interval = "<?php echo $this->config->item('notification_messages_timeout_interval'); ?>";
    var currency = "<?php echo CURRENCY; ?>";
    var current_page = "<?php echo $current_page; ?>";
    var chat_room_page_url = '<?php echo $this->config->item('chat_room_page_url'); ?>';
    var chat_room_dashboard_general_chat_label_text = '';
    var user_id = "<?php echo $user[0]->user_id; ?>";
    var user_log_id = "<?php echo $user_log_id; ?>";
    var meta_tag = '<?php $tag = $meta_tag; echo addslashes ($tag); ?>';
    var user_profile_picture = "<?php echo $user_data['user_profile_picture'] ?>";
    var username = '<?php echo $user_data['user_display_name']; ?>';
		var unread_channel_msg_count = '<?php echo $channel_unread_messages_count; ?>';
		
    var connection_issue_for_sender_display_error_message = '<?php echo $this->config->item('connection_issue_for_sender_display_error_message'); ?>';
    var connection_issue_for_sender_display_error_message_timeout = '<?php echo $this->config->item('connection_issue_for_sender_display_error_message_timeout'); ?>';
    var dashboard_user_not_connected_websocket_display_error_message = '<?php echo $this->config->item('dashboard_user_not_connected_websocket_display_error_message'); ?>';
    var dashboard_see_more_in_chat_room_label_text = '<?php echo $this->config->item('dashboard_see_more_in_chat_room_label_text'); ?>';
    var sender_session_not_exist_display_error_message = '<?php echo $this->config->item('sender_session_not_exist_display_error_message'); ?>';
    var sender_session_not_exist_display_error_message_timeout = '<?php echo $this->config->item('sender_session_not_exist_display_error_message_timeout'); ?>';
    var connection_issue_for_sender_display_error_message_chat_room_button_clicked = '<?php echo $this->config->item('connection_issue_for_sender_display_error_message_chat_room_button_clicked'); ?>';
    var connection_issue_popup_error_heading = "<?php echo $this->config->item('popup_alert_heading'); ?>";
    var chat_room_progressbar_min_display_time = "<?php echo $this->config->item('chat_room_loaderprogressbar_display_time'); ?>";
    var users_chat_new_message_text = "<?php echo $this->config->item('users_chat_new_message_text'); ?>";
    var connection_issue_for_sender_display_error_message_on_chat_room_page = "<?php echo $this->config->item('connection_issue_for_sender_display_error_message_on_chat_room_page'); ?>";
    var project_detail_page_url = "<?php echo $this->config->item('project_detail_page_url'); ?>";
    var contacts_management_page_url = "<?php echo $this->config->item('contacts_management_page_url'); ?>";
    var get_in_contact_popup_send_contact_request_info_male = '<?php echo $this->config->item('get_in_contact_popup_send_contact_request_info_male'); ?>';
    var get_in_contact_popup_send_contact_request_info_female = '<?php echo $this->config->item('get_in_contact_popup_send_contact_request_info_female'); ?>';
    var get_in_contact_popup_send_contact_request_info_company = '<?php echo $this->config->item('get_in_contact_popup_send_contact_request_info_company'); ?>';
    var get_in_contact_request_accept_btn_txt = '<?php echo $this->config->item('accept_btn_txt'); ?>';
    var get_in_contact_request_accepted_btn_txt = '<?php echo $this->config->item('get_in_contact_request_accepted_btn_txt'); ?>';
    var get_in_contact_request_reject_btn_txt = '<?php echo $this->config->item('get_in_contact_request_reject_btn_txt'); ?>';
    var get_in_contact_request_rejected_btn_txt = '<?php echo $this->config->item('get_in_contact_request_rejected_btn_txt'); ?>';
    var get_in_contact_request_unblock_btn_txt = '<?php echo $this->config->item('get_in_contact_request_unblock_btn_txt'); ?>';
    var blocked_contact_message_sending_failed_error_message = '<?php echo $this->config->item('blocked_contact_message_sending_failed_error_message'); ?>';
    var blocker_contact_blocked_message_sending_failed_error_message = '<?php echo $this->config->item('blocker_contact_already_blocked_message_sending_failed_error_message'); ?>';
		
		var chat_attachment_maximum_size_limit = "<?php echo $this->config->item('chat_attachment_maximum_size_limit'); ?>";
    var chat_attachment_allowed_number_of_files_validation_message = "<?php echo $this->config->item('chat_attachment_allowed_number_of_files_validation_message'); ?>";
    var chat_attachment_allowed_number_of_files_validation_small_chat_window_message = "<?php echo $this->config->item('chat_attachment_allowed_number_of_files_validation_small_chat_window_message'); ?>";
    var chat_attachment_maximum_size_validation_message = "<?php echo $this->config->item('chat_attachment_maximum_size_validation_message'); ?>";
    var maximum_allowed_number_of_attachments_on_chat_room_and_project_detail = "<?php echo $this->config->item('maximum_allowed_number_of_attachments_on_chat_room_and_project_detail'); ?>";
    var maximum_allowed_number_of_attachments_on_small_chat_window = "<?php echo $this->config->item('maximum_allowed_number_of_attachments_on_small_chat_window'); ?>";
		var chat_attachment_invalid_file_extension_validation_message = "<?php echo $this->config->item('chat_attachment_invalid_file_extension_validation_message'); ?>";
    var chat_attachment_display_error_timeout = "<?php echo $this->config->item('chat_attachment_display_error_timeout'); ?>";
    var chat_attachment_uploading_progress_text = "<?php echo $this->config->item('chat_attachment_uploading_progress_text'); ?>";
    var chat_attachment_file_uploading_failed_message = "<?php echo $this->config->item('chat_attachment_file_uploading_failed_message'); ?>";
    var chat_attachments_download_failed_error_message_small_chat_window = "<?php echo $this->config->item('chat_attachments_download_failed_error_message_small_chat_window'); ?>";
    var chat_room_search_filter_no_result_found_message = "<?php echo $this->config->item('chat_room_search_filter_no_result_found_message'); ?>";
    var chat_room_starting_position_to_search = "<?php echo $this->config->item('chat_room_starting_position_to_search'); ?>";
		var chat_room_search_keyword_placeholder = "<?php echo $this->config->item('chat_room_search_keyword_placeholder'); ?>";
		var chat_messages_grouping_time_limit = "<?php echo $this->config->item('chat_messages_grouping_time_limit'); ?>";

    var withdraw_funds_min_amount = "<?php echo $this->config->item('withdraw_funds_via_paypal_min_amount'); ?>";
    var withdraw_funds_max_amount = "<?php echo $this->config->item('withdraw_funds_via_paypal_max_amount'); ?>";
    
    var withdraw_funds_min_max_amount_error_msg = "<?php echo $this->config->item('withdraw_funds_via_paypal_min_max_amount_error_msg'); ?>";
    var deposit_funds_success_message_timeout = "<?php echo $this->config->item('deposit_funds_via_paypal_success_message_timeout'); ?>";
    var withdraw_funds_invalid_email_error_message = "<?php echo $this->config->item('withdraw_funds_via_paypal_invalid_email_error_message'); ?>";
    var withdraw_funds_with_amount_btn_txt = "<?php echo $this->config->item('confirm_withdraw_funds_via_paypal_withdraw_amount_btn_txt'); ?>";
    var withdraw_funds_btn_txt = "<?php echo $this->config->item('withdraw_funds_via_paypal_btn_txt'); ?>";
    var withdrawal_request_amount_label_txt = "<?php echo $this->config->item('withdrawal_request_amount_label_txt'); ?>";
    var withdrawal_request_submit_date_label_txt = "<?php echo $this->config->item('withdrawal_request_submit_date_label_txt'); ?>";
    var withdrawal_to_paypal_account_label_txt = "<?php echo $this->config->item('withdrawal_to_paypal_account_label_txt'); ?>";
    var withdrawal_request_status_label_txt = "<?php echo $this->config->item('withdrawal_request_status_label_txt'); ?>";
    var withdraw_amount_label_txt = "<?php echo $this->config->item('withdraw_amount_label_txt'); ?>";
    var transaction_date_label_txt = "<?php echo $this->config->item('transaction_date_label_txt'); ?>";
    var paypal_account_label_txt = "<?php echo $this->config->item('paypal_account_label_txt'); ?>";
    var transaction_id_label_txt = "<?php echo $this->config->item('transaction_id_label_txt'); ?>";
    var transaction_status_label_txt = "<?php echo $this->config->item('transaction_status_label_txt'); ?>";
    var transaction_failed_reason_label_txt = "<?php echo $this->config->item('transaction_failure_reason_label_txt'); ?>";
    var withdrawal_request_rejected_date_label_txt = "<?php echo $this->config->item('withdrawal_request_rejection_date_label_txt'); ?>";
    var withdrawal_request_approve_date_label_txt = "<?php echo $this->config->item('withdrawal_request_approval_date_label_txt'); ?>";
    var withdraw_funds_max_amount_error_msg = "<?php echo $this->config->item('withdraw_funds_via_paypal_max_amount_error_msg'); ?>";
    var withdraw_funds_processing_fees_percentage_charge = "<?php echo $this->config->item('withdraw_funds_processing_fees_percentage_charge'); ?>";
    var withdraw_funds_processing_fees_flat_charge = "<?php echo $this->config->item('withdraw_funds_processing_fees_flat_charge'); ?>";
    var communication_disabled_on_chat_project_channel_project_deleted_by_admin_txt = "<?php echo $this->config->item('communication_disabled_on_chat_project_channel_project_deleted_by_admin_txt'); ?>";
    var timeout;
     

</script>
<?php
    if(!in_array($current_page, $excluded_page_list_for_socket)) {
?>
<script src="<?php echo ASSETS.'js/modules/chat_attachments.js?'.time(); ?>"></script>
<script src="<?php echo NODE_SERVERS; ?>websocket/users_chat_websocket.js"></script>

<?php
        }
	endif;
?>
</body>

<!-- this script is used for create project id and redirect to post project page -->
<script>
	$(document).on('click', '.create_project', function () {
		
		$.ajax({
			type: "POST",
			url: SITE_URL + "post_project/create_project_id",
			dataType: "json",
			cache: false,
			success: function (msg)
			{	
				if(msg['status'] == 200){
					window.location.href = msg['location'];
				}
				else if(msg['status'] == 400){
					if (msg['location'] != '')
					{
						window.location.href = msg['location'];
					}else{
						if ($("#error_popup").css('display') == 'none') {
							$("#error_popup_heading").html(msg['popup_heading']);
							$("#error_popup_body").html(msg['error']);
							$('#error_popup').modal('show');
						}
					}
				}
			},
			error: function (msg)
			{

			}
		});
	});
    /* this script is used for redirect to find project page */
	$(document).on('click', '.browse_find_project', function () {
		window.location.href = "<?php echo $this->config->item('find_projects_page_url') ?>";
	});

	/* this script is used for redirect to find professionals page */
	$(document).on('click', '.browse_find_professionals', function () {
		window.location.href = "<?php echo $this->config->item('find_professionals_page_url') ?>";
	}); 

	/* this script is used for redirect to contact us page */
	$(document).on('click', '.write_a_message', function () {
		window.location.href = "<?php echo $this->config->item('contact_us_page_url') ?>";
	});	
	/* this script is used for redirect to sign in page */
	$(document).on('click', '.login_link', function () {
		window.location.href = "<?php echo $this->config->item('signin_page_url') ?>";
	});
	/* this script is used for redirect to sign up page */
	$(document).on('click', '.signup_link', function () {
		window.location.href = "<?php echo $this->config->item('signup_page_url') ?>";
	});
        
	$(document).on('change', '.default_dropdown_select select', function () {
		$(".default_dropdown_select select").blur();
	})
</script>
<?php if(isset($current_page) && in_array($current_page, array('user_profile','find_professionals','portfolio_standalone_page')) && $this->session->userdata ('user')) { ?>
<script src="<?php echo JS; ?>modules/projects_send_invitations.js"></script>
<?php
}	
?>	

<?php if(isset($current_page) && in_array($current_page, array('find_project','project_detail','user_profile','find_professionals', 'project-notification-feed','portfolio_standalone_page','hidden_project')) && !$this->session->userdata ('user')) { ?>
<script src="<?php echo JS; ?>signin_popup.js"></script>
<?php } ?>
<?php if(isset($current_page) && $current_page=='signin' && !$this->session->userdata ('user')) { ?>
<script src="<?php echo JS; ?>modules/signin.js"></script>
<?php } ?>
<script>
var post_project_page_url = '<?php echo $this->config->item('post_project_page_url') ?>';
</script>
<?php if(isset($current_page) && in_array($current_page, array('find_project','project_detail','user_profile','find_professionals', 'project-notification-feed','signin','portfolio_standalone_page')) && !$this->session->userdata ('user')) { ?>
<script>
	document.body.addEventListener('DOMSubtreeModified', function () {
            $('#fb-root').next('span').hide();
    }, true);
</script>
<?php
}	
?>	

<!-- Dahboard Chat Script Start -->
<script>
    function myChatFunction() {
        var x = document.getElementById("myChatName");
        if (x.style.display === "block") {
            x.style.display = "none";
        }
        else {
            x.style.display = "block";
        }
    }
</script>
<!-- Dahboard Chat Script End -->

<!-- User logout Script Start -->
<script>
    $(document).on('click', '.header_logout a', function() {
			if(['find_professionals', 'find_project'].indexOf(current_page) == -1) {
				try{
					window.ws.send('message', JSON.stringify({ type: "logout"}));
				} catch(err){}
				var link = $(this).data('link');
				window.location.href = link;
			}
			
		});
</script>
<!-- User logout Script End -->
</html>