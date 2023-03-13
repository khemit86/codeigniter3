<!-- <link href="<?php //echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet"> -->
<!--<div class="leftMenuLoad"></div>-->
<nav id="sidebar">
	<div id="sidebarL" class="sidebarL">
		<!-- <div class="sidebar-header" id="sidebarHeader">
			<div class="leftMenu">
				<div class="avtOnly">
					<?php
						if($user_data['user_avatar_exist_status']) {
							$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_data['profile_name'].USER_AVATAR.$user_data['user_avatar'];
						} else {
							if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
								if($user_data['gender'] == 'M'){
									$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
								}if($user_data['gender'] == 'F'){
								   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
								}
							} else {
								if($user_data['is_authorized_physical_person'] == 'Y'){
									if($user_data['gender'] == 'M'){
									$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
									}if($user_data['gender'] == 'F'){
									   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
									}
								
								
								}else{
									$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
								}
							}
						}
					
					?>
					<div id="profile-picture" class="profile-picture default_avatar_image" style="background-image: url('<?php echo $user_profile_picture;?>')">
					</div>
				</div>
				<div class="sRate avatar_review_star_resize">
					<span><?php echo show_dynamic_rating_stars($user_data['user_total_avg_rating_as_sp']);?></span>
					<span class="default_avatar_review Rating--labeled" data-star_rating="<?php echo $user_data['user_total_avg_rating_as_sp']; ?>"><?php echo $user_data['user_total_avg_rating_as_sp']; ?></span>
				</div>						
				<div class="rvw">
					<span class="default_avatar_total_review">
						<?php
							 $totalReviews = $user_data['fulltime_project_user_total_reviews']+$user_data['project_user_total_reviews'];
							if($totalReviews == 0){
								$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
							}else if($totalReviews == 1) {
								$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
							} else if($totalReviews > 1) {
								$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
							}
							echo $trGiven;
						?></span>
				</div>
			</div>
		</div> -->

		<ul class="list-unstyled components" id="menuUL">
			<li></li>
			<li <?php echo $current_page=='dashboard' ? 'class="active"' : ''; ?>>
				<a href="<?php echo base_url($this->config->item('dashboard_page_url')) ; ?>"><small class="leftImage" style="background-image: url('<?php echo URL ?>assets/images/dashboard.png');"></small><span><?php 
				echo $user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $this->config->item('pa_user_left_nav_dashboard') : $this->config->item('ca_user_left_nav_dashboard');
				?></span></a>
			</li>
			<li <?php echo ($current_page=='account-management-account-overview' || $current_page=='account-management-avatar' || $current_page=='account-management-address' || $current_page=='account-management-company-address' || $current_page=='account-management-contact-details' || $current_page=='account-management-account-login-details' || $current_page=='account-management-close-account') ? 'class="active"' : ''; ?>>
				<div class="dropdown dropRightAdj">
					<a class="dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-wrench ln_blue" aria-hidden="true"></i><span><?php 
							echo $user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $this->config->item('pa_user_left_nav_account_management') : $this->config->item('ca_user_left_nav_account_management');
							?></span>
					</a>
					<div class="dropdown-menu">
						<a class="dropdown-item drop_item<?php echo $current_page=='account-management-account-overview' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('account_management_account_overview_page_url')) ; ?>"><?php echo $this->config->item('account_management_left_nav_account_overview'); ?></a>
						<a class="dropdown-item drop_item<?php echo $current_page=='account-management-avatar' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('account_management_avatar_details_page_url')) ; ?>"><?php echo $this->config->item('account_management_left_nav_avatar'); ?></a>
                        <?php if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){ ?>
						<a class="dropdown-item drop_item<?php echo $current_page=='account-management-address' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('account_management_address_details_page_url')) ; ?>"><?php echo $this->config->item('pa_user_account_management_left_nav_address'); ?></a>
                        <?php } else {?>
                        <a class="dropdown-item drop_item<?php echo $current_page=='account-management-company-address' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('account_management_address_details_page_url')) ; ?>"><?php echo $this->config->item('ca_user_account_management_left_nav_address'); ?></a>
                        <?php } ?>
						<a class="dropdown-item drop_item<?php echo $current_page=='account-management-contact-details' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('account_management_contact_details_page_url')) ; ?>"><?php echo $this->config->item('account_management_left_nav_contact_details'); ?></a>
						<a class="dropdown-item drop_item<?php echo $current_page=='account-management-account-login-details' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('account_management_account_login_details_page_url')) ; ?>"><?php echo $this->config->item('account_management_left_nav_account_login_details'); ?></a>
						<a class="dropdown-item drop_item<?php echo $current_page=='account-management-close-account' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('account_management_close_account_page_url')) ; ?>"><?php echo $this->config->item('account_management_left_nav_close_account'); ?></a>
					</div>
				</div>
			</li>  
             <li <?php echo ($current_page=='profile-management-profile-definitions' || $current_page=='profile-management-company-base-information' || $current_page=='profile-management-competencies' || $current_page=='profile-management-mother-tongue' || $current_page=='profile-management-spoken-foreign-languages' || $current_page=='profile-management-company-values-and-principles') ? 'class="active"' : ''; ?>>
				<div class="dropdown dropRightAdj">
					<a class="dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-user ln_deepblue" aria-hidden="true"></i><span><?php 
				echo $user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $this->config->item('pa_user_left_nav_profile_management') : $this->config->item('ca_user_left_nav_profile_management');
				?></span>
					</a>
					<div class="dropdown-menu">
						<?php if($user_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE){ ?>
						<a class="dropdown-item drop_item<?php echo $current_page=='profile-management-company-base-information' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('profile_management_company_base_information_page_url')) ; ?>"><?php echo $this->config->item('ca_user_profile_management_left_nav_company_base_information'); ?></a>
						<?php } ?>
						<a class="dropdown-item drop_item<?php echo $current_page=='profile-management-profile-definitions' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('profile_management_profile_definitions_page_url')) ; ?>"><?php echo $this->config->item('profile_management_left_nav_profile_definitions') ; ?></a>
						
						<a class="dropdown-item drop_item<?php echo $current_page=='profile-management-competencies' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('profile_management_competencies_page_url')) ; ?>"><?php 
						if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
							echo $this->config->item('pa_user_profile_management_left_nav_competencies');
						}else if($user_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_data['is_authorized_physical_person'] == 'Y'){
							echo $this->config->item('ca_app_user_profile_management_left_nav_competencies');
						}else{
							echo $this->config->item('ca_user_profile_management_left_nav_competencies');
						}?></a>
                        <?php if(($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_data['is_authorized_physical_person'] =='Y')){ ?>
						<a class="dropdown-item drop_item<?php echo $current_page=='profile-management-mother-tongue' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('profile_management_mother_tongue_page_url')) ; ?>"><?php echo ($user_data['is_authorized_physical_person'] =='Y')?$this->config->item('ca_app_user_profile_management_left_nav_mother_tongue'):$this->config->item('pa_user_profile_management_left_nav_mother_tongue'); ?></a>
						<a class="dropdown-item drop_item<?php echo $current_page=='profile-management-spoken-foreign-languages' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('profile_management_spoken_foreign_languages_page_url')) ; ?>"><?php echo ($user_data['is_authorized_physical_person'] =='Y')?$this->config->item('ca_app_user_profile_management_left_nav_spoken_foreign_languages'):$this->config->item('pa_user_profile_management_left_nav_spoken_foreign_languages'); ?></a>
						<?php } if($user_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE){ ?>
							<a class="dropdown-item drop_item<?php echo $current_page=='profile-management-company-values-and-principles' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('profile_management_company_values_and_principles_page_url')) ; ?>"><?php echo ($user_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_data['is_authorized_physical_person'] =='Y')?$this->config->item('ca_app_user_profile_management_left_nav_company_values_and_principles'):$this->config->item('ca_user_profile_management_left_nav_company_values_and_principles'); ?></a>
						<?php } ?>
					</div>
				</div>
			</li> 
                        
                        
			<?php
			if(($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_data['is_authorized_physical_person'] == 'Y')){
			?>
			<li <?PHP echo $current_page=='work_experience' ? 'class="active"' : ''; ?>>
				<a href="<?php echo base_url($this->config->item('work_experience_page_url')) ; ?>"><i class="fas fa-book-open ln_clay"></i><span><?php echo $user_data['is_authorized_physical_person'] == 'Y'?$this->config->item('company_account_app_user_left_nav_work_experience'):$this->config->item('personal_account_user_left_nav_work_experience'); ?></span></a>
			</li>
			<li <?PHP echo $current_page=='education_training' ? 'class="active"' : ''; ?>>
				<a href="<?php echo base_url($this->config->item('education_training_page_url')) ; ?>"><i class="fa fa-graduation-cap ln_yellow" aria-hidden="true"></i><span><?php echo $user_data['is_authorized_physical_person'] == 'Y'?$this->config->item('company_account_app_user_left_nav_education_and_training'):$this->config->item('personal_account_user_left_nav_education_and_training'); ?></span></a>
			</li>
			<?php
			}
			?>
			<li <?PHP echo $current_page=='certifications' ? 'class="active"' : ''; ?>>
				<a href="<?php echo base_url($this->config->item('certifications_page_url')); ?>"><i class="fa fa-certificate ln_pink" aria-hidden="true"></i><span><?php
				if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
					echo $this->config->item('pa_user_left_nav_certifications');
				}else{
					if($user_data['is_authorized_physical_person'] == 'Y'){
						echo $this->config->item('ca_app_user_left_nav_certifications');
					}else{
						echo $this->config->item('ca_user_left_nav_certifications');
					}
				}?></span></a>
			</li>
			<li <?PHP echo $current_page=='portfolio' ? 'class="active"' : ''; ?>>
				<a href="<?php echo base_url($this->config->item('portfolio_page_url')) ; ?>"><i class="fa fa-briefcase ln_lightblue" aria-hidden="true"></i><span><?php
				if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
					echo $this->config->item('pa_user_left_nav_portfolio');
				}else{
					if($user_data['is_authorized_physical_person'] == 'Y'){
						echo $this->config->item('ca_app_user_left_nav_portfolio');
					}else{
						echo $this->config->item('ca_user_left_nav_portfolio');
					}
				}?></span></a>
			</li>
			<!-- <li>
				<a href="#"><i class="fa fa-level-up" aria-hidden="true"></i><span><?php 
				//echo $user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $this->config->item('pa_user_left_nav_external_recommendation') : $this->config->item('ca_user_left_nav_external_recommendation');?></span></a>
			</li> -->
			<li <?php echo ($current_page=='my_projects' || $current_page=='user-projects-payments-overview' || $current_page=='favorite_employers' || $current_page=='user_projects_pending_ratings_feedbacks' || $current_page=='projects-disputes' || $current_page=='disputes') ? 'class="active"' : ''; ?>>
				<div class="dropdown dropRightAdj">
					<a class="dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-cog ln_lightGreen"></i><span><?php 
						echo $user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $this->config->item('pa_user_left_nav_project_management') : $this->config->item('ca_user_left_nav_project_management');?></span>
					</a>
					<div class="dropdown-menu">
						<a class="dropdown-item drop_item<?php echo $current_page=='my_projects' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('myprojects_page_url')); ?>"><?php echo $this->config->item('projects_management_left_nav_my_projects');?></a>
						<a class="dropdown-item drop_item<?php echo $current_page=='user-projects-payments-overview' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('user_projects_payments_overview_page_url')); ?>"><?php echo $this->config->item('projects_left_nav_payments_overview'); ?></a>
						<a class="dropdown-item drop_item<?php echo $current_page=='favorite_employers' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $this->config->item('pa_favorite_employers_page_url') : $this->config->item('ca_favorite_employers_page_url')); ?>"><?php
						if($user_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_data['is_authorized_physical_person'] == 'Y'){
							echo  $this->config->item('ca_app_projects_management_left_nav_favorite_partners');
						}else if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ){
							echo  $this->config->item('pa_projects_management_left_nav_favorite_employers');
						}else{
							echo  $this->config->item('ca_projects_management_left_nav_favorite_partners');
						}?></a>
						<a class="dropdown-item drop_item<?php echo $current_page=='user_projects_pending_ratings_feedbacks' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('pending_feedbacks_management_page_url')); ?>"><?php echo $this->config->item('projects_management_left_nav_projects_pending_feedbacks'); ?></a>
                        <a class="dropdown-item drop_item<?php echo $current_page=='projects-disputes' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('projects_disputes_page_url')); ?>"><?php echo $this->config->item('projects_left_nav_projects_disputes'); ?></a>
						<?php
						/* <a class="dropdown-item drop_item<?php echo $current_page=='disputes' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('project_dispute_details_page_url')); ?>">Disputes</a> */ ?>
					</div>
				</div>
			</li>
			<li <?php echo ($current_page=='deposit-funds' || $current_page=='withdraw-funds' || $current_page=='transaction-history' || $current_page=='invoices' || $current_page=='invoicing-details') ? 'class="active"' : ''; ?>>
				<div class="dropdown dropRightAdj" id="finance_menu">
					<a class="dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-usd ln_green"></i><span><?php 
						if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
							echo $this->config->item('pa_user_left_nav_finance');
						} else {
							if($user_data['is_authorized_physical_person'] == 'Y') {
								echo $this->config->item('ca_app_user_left_nav_finance');
							} else {
								echo $this->config->item('ca_user_left_nav_finance');
							}
						}
						?></span>
					</a>
					<div class="dropdown-menu">
						<a class="dropdown-item drop_item<?php echo $current_page=='deposit-funds' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('finance_deposit_funds_page_url')); ?>" ><?php echo $this->config->item('finance_left_nav_deposit_funds'); ?></a>
						
						<a class="dropdown-item drop_item<?php echo $current_page=='withdraw-funds' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('finance_withdraw_funds_page_url')); ?>" ><?php echo $this->config->item('finance_left_nav_withdraw_funds'); ?></a>
						
						<a class="dropdown-item drop_item<?php echo $current_page=='transaction-history' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('finance_transactions_history_page_url')); ?>" ><?php echo $this->config->item('finance_left_nav_transactions_history'); ?></a>
						
						<a class="dropdown-item drop_item<?php echo $current_page=='invoices' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('finance_invoices_page_url')); ?>" ><?php echo $this->config->item('finance_left_nav_invoices'); ?></a>
                        <?php
                            if($user_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE) {
                        ?>
						<a class="dropdown-item drop_item<?php echo $current_page=='invoicing-details' ? ' subMenuActive' : ''; ?>" href="<?php echo base_url($this->config->item('finance_invoicing_details_page_url')); ?>" ><?php echo $this->config->item('finance_left_nav_invoicing_details'); ?></a>
                        <?php
                            }
                        ?>
					</div>
				</div>
			</li>
			<li>
				<a style="cursor:pointer;" class="sendFeedback"><i class="fa fa-level-up ln_red" aria-hidden="true"></i><span><?php 
				echo $user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $this->config->item('pa_user_left_nav_send_feedback') : $this->config->item('ca_user_left_nav_send_feedback');?></span></a>
			</li><li></li>
		</ul>
	</div>
</nav>
<script>
var send_feedback_popup_description_max_character_limit = "<?php echo $this->config->item('send_feedback_popup_description_max_character_limit'); ?>";

var send_feedback_popup_attachment_allowed_file_types_js = '<?php echo $this->config->item('send_feedback_popup_attachment_allowed_file_types_js'); ?>';
var send_feedback_popup_attachment_allowed_file_types = "<?php echo $this->config->item('send_feedback_popup_attachment_allowed_file_types'); ?>";
var send_feedback_popup_attachment_invalid_file_extension_validation_message = "<?php echo $this->config->item('send_feedback_popup_attachment_invalid_file_extension_validation_message'); ?>";

var send_feedback_popup_attachment_maximum_size_limit = "<?php echo $this->config->item('send_feedback_popup_attachment_maximum_size_limit'); ?>";
var send_feedback_popup_attachment_maximum_size_validation_message = "<?php echo $this->config->item('send_feedback_popup_attachment_maximum_size_validation_message'); ?>";

var send_feedback_popup_maximum_allowed_number_of_attachments = "<?php echo $this->config->item('send_feedback_popup_maximum_allowed_number_of_attachments'); ?>";
var send_feedback_popup_allowed_number_of_files_validation_message = "<?php echo $this->config->item('send_feedback_popup_allowed_number_of_files_validation_message'); ?>";
var send_feedback_popup_user_upload_blank_attachment_alert_message = "<?php echo $this->config->item('send_feedback_popup_user_upload_blank_attachment_alert_message'); ?>";

var send_feedback_popup_attachment_name_character_length_limit = "<?php echo $this->config->item('send_feedback_popup_attachment_name_character_length_limit'); ?>";
</script>
<script src="<?php echo ASSETS.'js/modules/user_send_feedback.js' ?>" type="text/javascript"></script>
<script>
	
	//var dDwidth = 1348;
	//var dMwidth = 1347;
	//var dDwidthPlus  = parseInt(dDwidth)+1;
	//var scrollheightMenu;
	//var scrollheightMenuT;
	
	//$("#mobMenu").on("click", function(e){
    $(document).on('click', '#mobMenu', function () {
        $('#sidebar').toggleClass('active');
        //$('#content').hide();
        var mv = $("#mobMenu").hasClass('expand');
        $("body").removeClass('overflow-hidden');
		if($(window).outerWidth()<=dMwidth) {
			$(".disable_content").removeClass('d-block position-absolute position-fixed').addClass('d-none');
		} else {
			$(".disable_content").removeClass('d-block position-absolute position-fixed').addClass('d-none');
		}
		
		//alert(mv);
		
        if(mv===true) {
            $("#mobMenu.expand").removeClass('expand').addClass('close');
            $("#mobMenu.close i").removeClass('fas fa-bars').addClass('fas fa-times');
            if($(window).outerWidth()<=dMwidth) {
                $('#sidebar ul').fadeIn("slow");
            } else {
               // $('#sidebar span').fadeIn("slow");
            }
            var wmHeight = $(window).height()-($("#headerContent").height()+4);
            //alert($("#content").height()+"==="+wmHeight);
            if(wmHeight>=$("#sidebar").height()) {
				/*$("#sidebarL ul").height($("#sidebar").height());*/
                $(".sidebarL").css({
                    /*"min-height": $("#sidebar").height(),*/
                    'width':"235px"
                });
            } else {
				/*$("#sidebarL ul").height(wmHeight);*/
                $(".sidebarL").css({
                    /*"min-height": wmHeight,*/
                    'width':"235px"
                });
            }
			
			
			
            
				
                <?php if (isset($current_page) && $current_page == 'dashboard') { ?>
				var scrollbarWidth = ($(window).outerWidth()-$(window).width());
				//alert(scrollbarWidth);
				/*$("#sidebar").css({
					"min-height": 'auto'
				});*/
				$(".dbMiddle").width($(".dbMiddle").width());
				$(".dbChat").width($(".dbChat").width()+scrollbarWidth);
				<?php } ?>
				//$('#sidebar span').show();
				//$("#sidebarL ul").height(wmHeight);
				
				setTimeout(function () {
					if($(window).outerWidth()<=dMwidth) {
						$("#sidebarL ul").height(wmHeight);
						scrollheightMenu = $(window).scrollTop();
						//alert(scrollheightMenu);
						$("#sidebarL ul").getNiceScroll().remove();
						$("#sidebarL ul").niceScroll({
							cursorcolor: "#c0bfbf",
							cursoropacitymax: 0.7,
							cursorwidth: 8,
							cursorborder: "1px solid #e1e1e1",
							cursorborderradius: "8px",
							background: "#F0F0F0",
							autohidemode: "leave",
						});
						$("#sidebarL ul").scrollTop(0);
						
						$("body").addClass('overflow-hidden');
						$(".disable_content").removeClass('d-none').addClass('d-block position-fixed');
						$(".disable_content").css({'left':0});
					}
				}, 1);
            
            //top menu off if open
            $('#myNavbar').removeClass('active');
            $('#myNavbar').removeClass('d-block').addClass('d-none');
            $("#mainmenu.close").removeClass('close').addClass('expand');
            $("#mainmenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
            $('#myNavbar ul').hide();
            if($(window).outerWidth()>640 && $(window).outerWidth()<=1150) {
                $('.headerFP_mobile').hide();
            }
            if($(window).outerWidth()>1150) {
                $('#myNavbar ul').show();
            }
        } else {
			<?php if (isset($current_page) && $current_page == 'dashboard') { ?>
			//$(".dbMiddle").removeAttr('style');
			$(".dbMiddle").css('width', 'auto');
			<?php } ?>
			
			//$("#sidebarL ul").removeAttr('style');
			//alert(scrollheightMenu);
			if ( typeof scrollheightMenu === 'undefined' ) {
				scrollheightMenu = 0; 
			}
			$(window).scrollTop(scrollheightMenu);
			
			$("#mobMenu").removeClass('close').addClass('expand');
            $("#mobMenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
           // $('#sidebar ul').hide();
            $('#sidebar').removeClass('active');
			
			$("body").removeClass('overflow-hidden');
			//$("body").css({'top':0});
			if($(window).outerWidth()<=dMwidth) {
				$(".disable_content").removeClass('d-block position-absolute position-fixed').addClass('d-none');
			} else {
				$(".disable_content").removeClass('d-block position-absolute position-fixed').addClass('d-none');
			}
			
			$("#sidebarL ul").getNiceScroll().resize();
			$('#sidebarL ul').getNiceScroll().show();
			
            /*$("#mobMenu.close").removeClass('close').addClass('expand');
            $("#mobMenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
            if($(window).outerWidth()<=dMwidth) {
                $('#sidebar ul').hide();
            } else {
                $('#sidebar span').hide();
            }	
            $(".sidebarL").removeAttr('style');*/
        }
        if($(window).outerWidth()<=1360) {
            //$('#sidebar').show();
        } else {
           // $('#sidebar.active').hide();
        }
        //$(".hOnly").toggle();
	<?php if (isset($current_page) && ($current_page == 'my_projects' || $current_page == 'dashboard')) { ?>
		$("#myProjecttab1 i, #myProjecttab2 i").removeClass('fa-times').addClass('fa-bars');
	<?php } ?>
	
		
    });   
	
    $(document).ready(function (){
		
        if ($(window).outerWidth() >= dDwidth) {
            $('#sidebar ul').show();
            $('#sidebar').removeClass('active');
            //$('#sidebar span').fadeIn();
			
			//$('#menuUL li a').tooltip('disable');
			
        } /*if ($(window).outerWidth() < dDwidthPlus && $(window).outerWidth()>dMwidth){
            $("#mobMenu").removeClass('close').addClass('expand');
            $("#mobMenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
            $('#sidebar ul').show();
            $('#sidebar').addClass('active');
            $('#sidebar span').hide();
            $(".sidebarL").css({'width':"auto"});
			
			$('#menuUL li a').tooltip('enable');
        } */if($(window).outerWidth()<=dMwidth) {
            $("#mobMenu").removeClass('close').addClass('expand');
            $("#mobMenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
            $('#sidebar ul').hide();
            $('#sidebar').removeClass('active');
			
			$("#sidebarL ul").getNiceScroll().resize();
			$('#sidebarL ul').getNiceScroll().show();
			
			//$('#menuUL li a').tooltip('disable');
        }
		/*$(document).on('click', '.dropRightAdj a', function () {
			var mv = $(this).parent().hasClass('show');
			$('#footer').addClass('zIndex1');
			if(mv===false) {
				$('#footer').removeClass('zIndex1');
			}			
		});*/
		//$(document).on('click', '.dropRightAdj a', function () {
		$(".dropRightAdj a").on("click", function(e){
			$('.hActTxt').hide();
                $('.hNotTxt').hide();
                $('.hHireTxt').hide();
			if($(window).outerWidth()>dMwidth) {
				$("body").removeClass('overflow-hidden');
				$(".disable_content").removeClass('d-block position-absolute position-fixed').addClass('d-none');
			}
			/*if($(window).outerWidth()<=dMwidth) {
				$(".disable_content").removeClass('d-block position-absolute position-fixed').addClass('d-none');
			} else {
				$(".disable_content").removeClass('d-block position-absolute position-fixed').addClass('d-none');
			}*/
			
			/*if ($(window).outerWidth() < dDwidthPlus && $(window).outerWidth()>dMwidth){
				$("#menuUL li a").tooltip('enable');
			}if($(window).outerWidth()<=dMwidth || $(window).outerWidth() > dDwidth) {
				$('#menuUL li a').tooltip('disable');
			}*/
		   
		   var el = $(this);
		   el.next('div').removeAttr('style');
		   setTimeout(function () {
			   //el.tooltip('disable');
			  // el.tooltip('hide');
			   //alert(el.next('div').hasClass("dropdown-menu show"));
				if(el.next('div').hasClass("dropdown-menu show") && el.next('div').attr('x-placement') == 'top-start') {
					el.next('div').css({
						"margin-top": "41px"
					});
				}else {
					el.next('div').css({
						"margin-top": "-38px"
					});
				}
				
				var mv = el.parent().hasClass('show');
				
				//alert(mv);
				if(mv===false) {
					
					if($(window).outerWidth()>dMwidth) {
						$("body").removeClass('overflow-hidden');
						$(".disable_content").removeClass('d-block position-absolute position-fixed').addClass('d-none');
						if ( typeof scrollheightMenuT === 'undefined' ) {
							scrollheightMenuT = 0; 
						}
						$(window).scrollTop(scrollheightMenuT);
					}
					/*if($(window).outerWidth()<=dMwidth) {
						$(".disable_content").removeClass('d-block position-absolute position-fixed').addClass('d-none');
					} else {
						$(".disable_content").removeClass('d-block position-absolute position-fixed').addClass('d-none');
					}*/
					//alert(scrollheightMenuT);
					
					
					//$(".dbMiddle, .dbChat").removeAttr('style');
					$(".dbMiddle, .dbChat").css('width', '');
				}if(mv===true) {
					if($(window).outerWidth()>dMwidth) {

						var scrollbarWidth = ($(window).outerWidth()-$(window).width());
						//alert(scrollbarWidth)
						//var lMenuWidth = (parseInt($(window).outerWidth())+scrollbarWidth) - $(window).outerWidth()*84/100;
						
						<?php if (isset($current_page) && $current_page == 'dashboard') { ?>
						$("#sidebar").css({
							//"min-height": 'auto'
						});
						$(".dbMiddle").width($(".dbMiddle").width());
						$(".dbChat").width($(".dbChat").width()+scrollbarWidth);
						<?php } ?>
						//alert(lMenuWidth);
						$("body").addClass('overflow-hidden');
						$(".disable_content").removeClass('d-none').addClass('d-block position-absolute');
						$(".disable_content").css({'left':$("#sidebar").width()});
						
						scrollheightMenuT = $(window).scrollTop();
				
						//alert(scrollheightMenuT);
					}
				}
			
			}, 1);
			var mv = el.parent().hasClass('show');
			$('#footer').addClass('zIndex1');
			if(mv===false) {
				$('#footer').removeClass('zIndex1');
			}
			
			var mContent = parseInt($("#content").outerHeight());
			<?php if (isset($current_page) && $current_page == 'dashboard') { ?>
				mContent = parseInt($(".dbMiddle").height());
			<?php } ?>
			/*if(mContent > $(window).height() || $(window).outerWidth()<=dMwidth) {
				$("#sidebar").css({'min-height':'auto'});
			}*/
			var outerHeight = 0;
			$('#sidebarL ul li').each(function() {
			  outerHeight += $(this).outerHeight();
			});
			$('#sidebarL ul li').next('.dropRightAdj show').next('div a').each(function() {
			  outerHeight += $(this).outerHeight();
			});
			
			$("#sidebarL ul").getNiceScroll().remove();
			
				$("#sidebarL ul").niceScroll({
					cursorcolor: "#c0bfbf",
					cursoropacitymax: 0.7,
					cursorwidth: 8,
					cursorborder: "1px solid #e1e1e1",
					cursorborderradius: "8px",
					background: "#F0F0F0",
					autohidemode: "leave",
				});
			
			
			
			
			
		});
    });
	 
	$(window).scroll(function(e){ 
		
			
	});
	
    $(window).resize(function() {
		
		
         
		  
		
			
        if ($(window).outerWidth() >= dDwidth) {
            $('#sidebar ul').show();
            $('#sidebar').removeClass('active');
            
            $(".sidebarL").css({'width':"auto"});

			
			var outerHeight = 0;
			$('#sidebarL ul li').each(function() {
			  outerHeight += $(this).outerHeight()+10;
			});
			$('#sidebarL ul li').next('.dropRightAdj show').next('div a').each(function() {
			  outerHeight += $(this).outerHeight();
			});
			var leftmenu_height = $(window).height() - parseInt($("#headerContent").height());
			
			var leftMenuHeight = parseInt(outerHeight)-9;
			
			if(leftmenu_height <= leftMenuHeight) {
				leftMenuHeight = leftmenu_height;
				$("#sidebarL").removeAttr('style');
			}
			
			$('#sidebar ul').height(leftMenuHeight);	
				
			
			
			
        } if($(window).outerWidth()<=dMwidth) {
            $("#mobMenu").removeClass('close').addClass('expand');
            $("#mobMenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
            $('#sidebar ul').hide();
            $('#sidebar').removeClass('active');
			
			
        }
		$("body").removeClass('overflow-hidden');
		if($(window).outerWidth()<=dMwidth) {
			$(".disable_content").removeClass('d-block position-absolute position-fixed').addClass('d-none');
		} else {
			$(".disable_content").removeClass('d-block position-absolute position-fixed').addClass('d-none');
		}
					
        //top menu off if open
		$('#myNavbar').removeClass('active');
        $('#myNavbar').removeClass('d-block').addClass('d-none');
        $("#mainmenu.close").removeClass('close').addClass('expand');
        $("#mainmenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
        $('#myNavbar ul').hide();
        if($(window).outerWidth()>640 && $(window).outerWidth()<=1150) {
            $('.headerFP_mobile').hide();
        }
        if($(window).outerWidth()>1150) {
            $('#myNavbar ul').show();
        }
		
		$('.dropRightAdj a').next('div').each(function (key, dropdown) {       
			if($(dropdown).hasClass("dropdown-menu show")){
				$(dropdown).removeClass('show');
				$(dropdown).parent().removeClass('show');
			}
		});
		 
		/*if($(window).outerWidth()==screenWidth) {
			alert($(window).outerWidth()+"==="+screenWidth);
			var mContent = parseInt($("#content").outerHeight());
			//console.log($("#content").height()+"===="+$("#content").outerHeight()+"==="+$(window).height());
			<?php if (isset($current_page) && $current_page == 'dashboard') { ?>
				mContent = parseInt($(".dbMiddle").outerHeight())+25;
			<?php } ?>
			//alert(mContent+"==="+mh);
			var sidebar_min_height = 'auto';
			if(mContent<mh && $(window).outerWidth() > dDwidth) {
				sidebar_min_height = mh-18;
			}if ($(window).outerWidth() < dDwidthPlus){
				sidebar_min_height = mh+47;
			}
			$("#sidebar").css({
				"min-height": sidebar_min_height,
			});
		}*/
		//console.log("sh=="+screenHeight+"==="+$(window).height()+"==="+screenWidth+"==="+$(window).outerWidth());
		/*setTimeout(function () {
			$("#sidebarL ul").getNiceScroll().resize();
			$('#sidebarL ul').getNiceScroll().show();
		}, 100);*/
		$("#sidebarL ul").getNiceScroll().resize();
		//$('#sidebarL ul').getNiceScroll().show();
    });
	/*$("#sidebarL ul").niceScroll().scrollstart(function(info){
		$("#sidebarL ul").getNiceScroll().remove();
		$("#sidebarL ul").niceScroll({
					cursorcolor: "#c0bfbf",
					cursoropacitymax: 0.7,
					cursorwidth: 8,
					cursorborder: "1px solid #e1e1e1",
					cursorborderradius: "8px",
					background: "#F0F0F0",
					autohidemode: true,
				});
		$('#sidebarL ul').getNiceScroll().show();
	})*/
	$(window).on("load scroll touchmove", function() {
        setTimeout(function () {
			$("#sidebarL ul").getNiceScroll().resize();
			$('#sidebarL ul').getNiceScroll().show();
		}, 100);
    });
	function setMenuHeight(mh) {
		var screenHeight = screen.height; 
        var screenWidth = screen.width;
	
		var mContent = parseInt($("#content").outerHeight());
		//console.log($("#content").height()+"===="+$("#content").outerHeight()+"==="+$(window).height());
		<?php if (isset($current_page) && $current_page == 'dashboard') { ?>
			mContent = parseInt($(".dbMiddle").outerHeight())+25;
		<?php } ?>
		
		console.log("mmmm==="+mContent+"==="+$(window).height());
		var leftmenu_height = mContent+45;
		if(mContent > $(window).height()) {
			leftmenu_height = $(window).height() - parseInt($("#headerContent").height());
		}
		if($(window).outerWidth()>dMwidth) {
			setTimeout(function () {
				var outerHeight = 0;
				$('#sidebarL ul li').each(function() {
				  outerHeight += $(this).outerHeight()+10;
				});
				$('#sidebarL ul li').next('.dropRightAdj show').next('div a').each(function() {
				  outerHeight += $(this).outerHeight();
				});
				
				var leftMenuHeight = parseInt(outerHeight)-9;
				
				//var leftMenuHeight = $("#sidebarL ul").height();
				if(leftmenu_height <= leftMenuHeight) {
					leftMenuHeight = leftmenu_height;
					$("#sidebarL").removeAttr('style');
				}
				
				$("#sidebarL ul").height(leftMenuHeight);
			}, 1);
		}
		$("#sidebarL ul").niceScroll({
			cursorcolor: "#c0bfbf",
			cursoropacitymax: 0.7,
			cursorwidth: 8,
			cursorborder: "1px solid #e1e1e1",
			cursorborderradius: "8px",
			background: "#F0F0F0",
			autohidemode: "leave",
		});
	}
	
</script>