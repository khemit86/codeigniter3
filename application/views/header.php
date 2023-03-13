<?php if (empty($current_page)) {
$current_page = '';
}
?><!DOCTYPE html>
<html>
    <head>
        <script> SITE_URL = "<?php echo SITE_URL; ?>";</script>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta charset="UTF-8">
        <?php
        
            if (isset($meta_tag)) {
                echo $meta_tag;
            } else {
        ?>
        <title><?php if (isset($current_page)) { echo $current_page; } else { echo "travai"; } ?></title>
	    <meta name="description" content="">
        <?php } ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">                   
        <?php if (isset($current_page) && in_array($current_page, ['project_detail', 'user_profile', 'signup', 'portfolio_standalone_page'])) {
            $str = '';
            if (isset($_SESSION["provider"])) {
                if (isset($_SESSION['share_description'])) {
                    $str = $_SESSION['share_description'];
                }
            } else {
                $chars = 150;
                $str = "";
                $exp = array();
                if (isset($_SESSION['share_description'])) {
                    $exp = explode(" ", $_SESSION['share_description']);
                }
                foreach ($exp as $e) {
                    $tmp = $str . " " . trim($e);
                    if (strlen($tmp) < 150) {
                        $str = $tmp;
                    } else {
                        break;
                    }
                }
            }
            $str .= "...";
            $first_name = '';
            $last_name = '';
            if (isset($first_name)) {
                $first_name = $first_name;
            } if (isset($last_name)) {
                $last_name = $last_name;
            }
            $name = $first_name . ' ' . $last_name;
            
        ?>

        <meta prefix="og: https://ogp.me/ns#" property="og:url" content="<?php echo isset($_SESSION['share_url']) ? $_SESSION['share_url'] : ''; ?>" />
        <meta prefix="og: https://ogp.me/ns#" property="og:type" content="article" />
        <?php if (!isset($_SESSION['share_title_short'])) { ?> 
            <meta prefix="og: https://ogp.me/ns#" property="og:title" content="<?php echo isset($_SESSION['share_title']) ? $_SESSION['share_title'] : ''; ?> | <?php echo $name; ?>" />
        <?php } else { ?>
            <meta prefix="og: https://ogp.me/ns#" property="og:title" content="<?php echo isset($_SESSION['share_title_short']) ? $_SESSION['share_title_short'] : ''; ?>"  />
        <?php } ?>
        <meta prefix="og: https://ogp.me/ns#" property="og:description" content="<?php echo isset($_SESSION['share_description']) ? $_SESSION['share_description'] : ''; ?>" />
        <meta prefix="og: https://ogp.me/ns#" property="og:image" content="<?php echo isset($_SESSION['share_image']) ? $_SESSION['share_image'] : '' ?>" />
        <meta prefix="og: https://ogp.me/ns#" property="og:image:width" content="<?php echo isset($_SESSION['share_image_width']) ? $_SESSION['share_image_width'] : ''; ?>" />
        <meta prefix="og: https://ogp.me/ns#" property="og:image:height" content="<?php echo isset($_SESSION['share_image_height']) ? $_SESSION['share_image_height'] : ''; ?>" />

        <meta name="twitter:card" content="summary" />
        <?php if (!isset($_SESSION['share_title_short'])) { ?> 
            <meta property="twitter:title" content="<?= isset($_SESSION['share_title']) ? $_SESSION['share_title'] : ''; ?> | <?= $name ?>" />
        <?php } else { ?>
            <meta property="twitter:title" content="<?= isset($_SESSION['share_title_short']) ? $_SESSION['share_title_short'] : ''; ?>"  />
        <?php } ?>
        <meta name="twitter:description" content="<?= isset($_SESSION['share_description']) ? $_SESSION['share_description'] : ''; ?>" />
        <meta name="twitter:image" content="<?= isset($_SESSION['share_image']) ? $_SESSION['share_image'] : ''; ?>" />
        <?php if (isset($share)) { if (!isset($_SESSION['share_title_short'])) { ?> 
            <title><?= $_SESSION['share_title'] ?> | <?= $first_name . ' ' . $last_name ?></title>
        <?php } else { ?> 
            <title><?= $_SESSION['share_title_short'] ?></title>
        <?php }
            }
            unset($_SESSION["share_image"]);
            unset($_SESSION["share_url"]);
            unset($_SESSION["share_title"]);
            unset($_SESSION["share_title_short"]);
            unset($_SESSION["share_description"]);
        ?>
        <meta name="description" content="<?= $str ?>">
        <?php
            }
        ?>
        <!-- end: scriptsrc.php -->
	    <!-- Favicons -->
        <link rel="shortcut icon" href="<?php echo ASSETS ?>favicon/logo.png">
        <!-- header.php start -->

        <link rel="stylesheet" href="<?= CSS ?>cdn/font-awesome.4.4.0.min.css">	
	    <link rel="stylesheet" href="<?= CSS ?>cdn/fontawesome_5.13.0_all.css"/>
        <link rel="stylesheet" href="<?php echo ASSETS ?>bootstrap-4/dist/css/bootstrap.css">
        
        <link rel="stylesheet" href="<?php echo CSS ?>main.css?v=<?= time() ?>" media="screen" type="text/css"/>
	    <script src="<?= ASSETS ?>js/cdn/jquery-3.3.1.js" ></script>	
        <script>
            var  current_page  =  "<?php echo $current_page; ?>";
            var logout_page_url = '<?= $this->config->item('logout_page_url') ?>';
            var sign_url = '<?= $this->config->item('signin_page_url') ?>';
            var project_detail_page_url = '<?= $this->config->item('project_detail_page_url') ?>';
			var accept_cookies_banner_lifetime = '<?php echo $this->config->item('accept_cookies_banner_lifetime'); ?>';
        </script>
        <?php
            if($this->session->userdata('user') && $this->session->userdata('is_authorized')) {
                $user = $this->session->userdata('user');
                				
				$excluded_page_list_for_project_management_socket = [
                    'post_project',
                    'edit_project',
                    'edit_draft_project',
                    'preview_draft_project',
                    'temporary_project_preview',
                    'edit_temporary_project_preview'
                ];
        ?>
        <script>
            // <!-- Assing config logout url defined in signin_custom_config.php file to script variable to access in js file -->
            var user_id = "<?php echo $user[0]->user_id; ?>";
            var user_log_id = "<?php echo $user_log_id; ?>";
            var dashboard_page_url = '<?= $this->config->item('dashboard_page_url') ?>';
            var send_realtime_notification_popup_type = '<?php echo $this->config->item('send_realtime_notification_popup_type'); ?>';
            var send_realtime_notification_popup_allow_dismiss = '<?php echo $this->config->item('send_realtime_notification_popup_allow_dismiss'); ?>';
            var send_realtime_notification_popup_allow_delay = '<?php echo $this->config->item('send_realtime_notification_popup_allow_delay'); ?>';
            var send_realtime_notification_popup_placement_from = '<?php echo $this->config->item('send_realtime_notification_popup_placement_from'); ?>';
            var send_realtime_notification_popup_placement_align = '<?php echo $this->config->item('send_realtime_notification_popup_placement_align'); ?>';
            var send_realtime_notification_popup_animate_enter = '<?php echo $this->config->item('send_realtime_notification_popup_animate_enter'); ?>';
            var send_realtime_notification_popup_animate_exit = '<?php echo $this->config->item('send_realtime_notification_popup_animate_exit'); ?>';
            var send_realtime_notification_popup_url_traget = '<?php echo $this->config->item('send_realtime_notification_popup_url_traget'); ?>';
        </script>
		<script src="<?php echo NODE_SERVERS; ?>websocket_connection.js"></script>
        <script  src="<?php echo NODE_SERVERS; ?>websocket/websocket_config.js"></script> <!-- File related to socket by sid -->
       
        <script id="sockettag" src="<?php echo NODE_SERVERS; ?>websocket/projects_management_websocket.js"></script> <!-- File related to socket by sid-->
        
        <script src="<?= ASSETS ?>js/cdn/jquery-ui.1.12.1.js"></script>
        <script src="<?php echo ASSETS; ?>js/cdn/bootstrap-notify.js"></script>
        <script src="<?= ASSETS ?>js/modules/newly_posted_projects_realtime_notifications_node.js"></script> 
        <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
        <?php
            if(!in_array($current_page, $excluded_page_list_for_project_management_socket)) {
        ?>
        <script src="<?= ASSETS ?>js/modules/projects_management_realtime_notifications.js"></script> 
        <?php
                }
            }
        ?>
        
        <?php if (isset($current_page) && ($current_page == 'signin')) { ?>
            <link href="<?php echo CSS ?>modules/signin.css" rel="stylesheet" type="text/css" />
        <?php } ?>
         
        <?php if (isset($current_page) && ($current_page == 'signup')) { ?>
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
            <script src="<?php echo JS; ?>jquery_validate/jquery.validate.js"></script>
            <link rel="stylesheet" href="<?php echo CSS ?>modules/signup.css" media="screen" type="text/css" />
        <?php }if (isset($current_page) && ($current_page == 'successful_signup_confirmation' || $current_page == 'signup_verify_page' || $current_page == 'successful_signup_verification' || $current_page == 'send_password_reset_confirmation' || $current_page == 'reset_password' || $current_page == 'successfull_password_reset')) { ?>
            <link rel="stylesheet" href="<?php echo CSS ?>modules/inner_signin_signup_resetpassword_pages.css" media="screen" type="text/css" />
        <?php } if (isset($current_page) && ($current_page == 'reset_login_password')) { ?>
            <script src="<?php echo JS; ?>jquery_validate/jquery.validate.js"></script>
            <link rel="stylesheet" href="<?php echo CSS ?>modules/forgot_password.css" media="screen" type="text/css" />	
        <?php }if (isset($current_page) && ($current_page == 'reset_password')) { ?>
            <script src="<?php echo JS; ?>jquery_validate/jquery.validate.js"></script>
	<?php } if (isset($current_page) && ($current_page == 'find_project' || $current_page =='find_professionals' )){ ?>        
            <link href="<?php echo CSS; ?>bootstrap-multiselect.css" rel="stylesheet" />
        <?php } if (isset($current_page) && $current_page == 'home') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/home.css" rel="stylesheet">
			<!--<script src="<?= ASSETS ?>js/modules/home.js"></script>-->
		<?php } if (isset($current_page) && $current_page == 'referral_program') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/referral_program.css" rel="stylesheet">
			<script src="<?= ASSETS ?>js/modules/referral_program.js"></script>
		<?php } if (isset($current_page) && $current_page == 'secure_payments_process') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/secure_payments_process.css" rel="stylesheet">
			<script src="<?= ASSETS ?>js/modules/secure_payments_process.js"></script>
		<?php } if (isset($current_page) && $current_page == 'contact_us') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/contact_us.css" rel="stylesheet">
			<link rel="stylesheet" href="<?php echo CSS ?>countries_phone_codes/intlTelInput.css" media="screen" type="text/css" />
			
			<script src="<?= ASSETS ?>js/countries_phone_codes/<?php echo SITE_LANGUAGE."_" ?>intlTelInput.js"></script>
		<?php } if (isset($current_page) && $current_page == 'we_vs_them') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/we_vs_them.css" rel="stylesheet">
			<script src="<?= ASSETS ?>js/modules/we_vs_them.js"></script>
		<?php } if (isset($current_page) && $current_page == 'privacy_policy') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/privacy_policy.css" rel="stylesheet">
			<script src="<?= ASSETS ?>js/modules/privacy_policy.js"></script>
		<?php } if (isset($current_page) && $current_page == 'terms_and_conditions') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/terms_and_conditions.css" rel="stylesheet">
			<script src="<?= ASSETS ?>js/modules/terms_and_conditions.js"></script>
		<?php } if (isset($current_page) && $current_page == 'code_of_conduct') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/code_of_conduct.css" rel="stylesheet">
			<script src="<?= ASSETS ?>js/modules/code_of_conduct.js"></script>
		<?php } if (isset($current_page) && $current_page == 'trust_and_safety') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/trust_and_safety.css" rel="stylesheet">
			<script src="<?= ASSETS ?>js/modules/trust_and_safety.js"></script>
		<?php } if (isset($current_page) && $current_page == 'faq') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/faq.css" rel="stylesheet">
			<script src="<?= ASSETS ?>js/modules/faq.js"></script>
		<?php } if (isset($current_page) && $current_page == 'fees_and_charges') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/fees_and_charges.css" rel="stylesheet">
			<script src="<?= ASSETS ?>js/modules/fees_and_charges.js"></script>
		<?php } if (isset($current_page) && $current_page == 'about_us') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/about_us.css" rel="stylesheet">
			<script src="<?= ASSETS ?>js/modules/about_us.js"></script>
        <?php } if (isset($current_page) && $current_page == 'dashboard') { ?>
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo ASSETS; ?>css/modules/user_dashboard.css" rel="stylesheet">
            <link href="<?php echo ASSETS; ?>css/modules/user_dashboard_projects_list.css" rel="stylesheet">
            <link href="<?php echo ASSETS; ?>css/modules/user_dashboard_invitefriends.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/user_dashboard_my_projects.css" rel="stylesheet">
            <link href="<?php echo ASSETS; ?>css/scrolling_tab/jquery.scrolling-tabs.css" rel="stylesheet">
            
            <script src="<?= ASSETS ?>js/scrolling_tab/jquery.scrolling-tabs.js"></script>
            <!--To be reviewed once Invite section complete  17-12-2019-->
            <script src="<?= ASSETS ?>js/bootstrap_tagit.js?v=<?= time() ?>"></script>
            <link href="<?php echo CSS; ?>invite_styles.css" rel="stylesheet">
        <?php } if (isset($current_page) && $current_page == 'activity') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_activity.css" rel="stylesheet">
        <?php } if (isset($current_page) && $current_page == 'invite_friends') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/invite_friends.css" rel="stylesheet">	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>	
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
            <script src="<?= ASSETS ?>js/bootstrap_tagit.js?v=<?= time() ?>"></script>
        <?php } if (isset($current_page) && $current_page == 'user_projects_pending_ratings_feedbacks') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/user_projects_pending_ratings_feedbacks.css" rel="stylesheet">
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
            <link rel="stylesheet" href="<?php echo CSS ?>range/rangeSlider.css" media="screen" type="text/css" />
            <script src="<?= ASSETS ?>js/range/rangeSlider.js"></script>			
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
        <?php } if (isset($current_page) && $current_page == 'project_dispute_details') { ?>
            <link href="<?php echo CSS; ?>modules/project_dispute_details.css" rel="stylesheet">
        <?php } if (isset($current_page) && $current_page == 'release_funds') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">	
        <?php } if (isset($current_page) && $current_page == 'deposit-funds') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/deposit_funds.css" rel="stylesheet">	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>	
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'withdraw-funds') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/withdraw_funds.css" rel="stylesheet">	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'transaction-history') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/transactions_history.css" rel="stylesheet">	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'invoices') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/invoices.css" rel="stylesheet">	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'invoicing-details') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/company_invoicing_details.css" rel="stylesheet">	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
            <script src="<?= ASSETS ?>js/cdn/stickyfill.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'user-projects-payments-overview') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/user_projects_payments_overview.css" rel="stylesheet">	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'projects-disputes') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/projects_disputes.css" rel="stylesheet">	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
            <script src="<?= ASSETS ?>js/cdn/stickyfill.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'account-management') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/account_management.css" rel="stylesheet">
            <link rel="stylesheet" href="<?php echo CSS ?>countries_phone_codes/intlTelInput.css" media="screen" type="text/css" />
            <script src="<?= ASSETS ?>js/countries_phone_codes/<?php echo SITE_LANGUAGE."_" ?>intlTelInput.js"></script>	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
        <?php } if (isset($current_page) && $current_page == 'account-management-account-overview') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/account_management.css" rel="stylesheet">
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'account-management-avatar') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/account_management.css" rel="stylesheet">
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
            <link href="<?php echo ASSETS; ?>css/croppie/croppie.css" rel="stylesheet">
            <script src="<?= ASSETS ?>js/croppie/croppie.js"></script>
        <?php } ?>
        <?php  if (isset($current_page) && $current_page == 'account-management-address') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/account_management.css" rel="stylesheet">
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'account-management-company-address') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/account_management.css" rel="stylesheet">
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'account-management-contact-details') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/account_management.css" rel="stylesheet">
            <link rel="stylesheet" href="<?php echo CSS ?>countries_phone_codes/intlTelInput.css" media="screen" type="text/css" />
            <script src="<?= ASSETS ?>js/countries_phone_codes/<?php echo SITE_LANGUAGE."_" ?>intlTelInput.js"></script>	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'account-management-account-login-details') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/account_management.css" rel="stylesheet">
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'account-management-close-account') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/account_management.css" rel="stylesheet">
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php }  ?>
        <?php  if (isset($current_page) && $current_page == 'profile-management-profile-definitions') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/profile_management.css" rel="stylesheet">	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'profile-management-company-base-information') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/profile_management.css" rel="stylesheet">	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
            <script src="<?= ASSETS ?>js/cdn/stickyfill.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'profile-management-competencies') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/profile_management.css" rel="stylesheet">	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'profile-management-company-values-and-principles') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/profile_management.css" rel="stylesheet">	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
            <script src="<?= ASSETS ?>js/cdn/stickyfill.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'profile-management-mother-tongue') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/profile_management.css" rel="stylesheet">	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'profile-management-spoken-foreign-languages') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/profile_management.css" rel="stylesheet">	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'profile-management') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo CSS; ?>modules/profile_management.css" rel="stylesheet">	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
        <?php }  ?>
        <?php if (isset($current_page) && $current_page == 'work_experience') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link rel="stylesheet" href="<?php echo CSS ?>modules/work_experience.css" media="screen" type="text/css" />
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if(isset($current_page) && $current_page == 'project-notification-feed' ) { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link href="<?php echo ASSETS; ?>css/modules/project_notification_feed.css" rel="stylesheet">
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
            <script src="<?= ASSETS ?>js/cdn/stickyfill.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
            <script src="<?= ASSETS ?>js/typeahead.bundle.js"></script> 
            <script src="<?= ASSETS ?>js/modules/newly_posted_projects_realtime_notifications.js"></script> 
        <?php } if (isset($current_page) && $current_page == 'user_profile') { ?>
            <link rel="stylesheet" href="<?php echo CSS ?>modules/user_profile_page.css" media="screen" type="text/css" />
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
        <?php } if (isset($current_page) && $current_page == 'find_project') { ?>
            <link rel="stylesheet" href="<?php echo CSS ?>modules/find_project.css" media="screen" type="text/css" />
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
            <!--<script src="<?= ASSETS ?>js/cdn/stickyfill.min.js"></script>-->
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
            <script src="<?= ASSETS ?>js/typeahead.bundle.js"></script>
        <?php } if (isset($current_page) && $current_page == 'find_professionals') { ?>
            <link rel="stylesheet" href="<?php echo CSS ?>modules/find_professionals.css" media="screen" type="text/css" />
            <!--<script src="<?= ASSETS ?>js/cdn/stickyfill.min.js"></script>-->
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
            <script src="<?= ASSETS ?>js/typeahead.bundle.js"></script>
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'portfolio_standalone_page') { ?> 
            <link rel="stylesheet" href="<?php echo CSS ?>modules/portfolio_standalone_page.css" media="screen" type="text/css" />	
            <link rel="stylesheet" href="<?php echo CSS ?>lightgallery/lightgallery.css" media="screen" type="text/css" />
            <script src="<?= ASSETS ?>js/lightgallery/lightgallery.js"></script>
            <script src="<?= ASSETS ?>js/lightgallery/lg-fullscreen.js"></script>
            <script src="<?= ASSETS ?>js/lightgallery/lg-thumbnail.js"></script>
            <script src="<?= ASSETS ?>js/lightgallery/lg-autoplay.js"></script>
            <script src="<?= ASSETS ?>js/lightgallery/lg-zoom.js"></script>
            <script src="<?= ASSETS ?>js/lightgallery/jquery.mousewheel.min.js"></script>
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
        <?php } if (isset($current_page) && $current_page == 'education_training') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link rel="stylesheet" href="<?php echo CSS ?>modules/education_training.css" media="screen" type="text/css" />
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'certifications') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link rel="stylesheet" href="<?php echo CSS ?>modules/certifications.css" media="screen" type="text/css" />
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'portfolio') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link rel="stylesheet" href="<?php echo CSS ?>modules/portfolio.css" media="screen" type="text/css" />
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>            
        <?php } if (isset($current_page) && $current_page == 'favorite_employers') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link rel="stylesheet" href="<?php echo CSS ?>modules/favorite_employers.css" media="screen" type="text/css" />
			<script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'contacts-management') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link rel="stylesheet" href="<?php echo CSS ?>modules/contacts_management.css" media="screen" type="text/css" />
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
            <script src="<?= ASSETS ?>js/cdn/stickyfill.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
        <?php } if (isset($current_page) && $current_page == 'my_projects') { ?>
            <link href="<?php echo ASSETS; ?>css/modules/user_leftmenu.css" rel="stylesheet">
            <link rel="stylesheet" href="<?php echo CSS ?>modules/my_projects.css" media="screen" type="text/css" />
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
			<script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
            <link href="<?php echo ASSETS; ?>css/scrolling_tab/jquery.scrolling-tabs.css" rel="stylesheet">
            <script src="<?= ASSETS ?>js/scrolling_tab/jquery.scrolling-tabs.js"></script>
        <?php } if (isset($current_page) && $current_page == 'project_detail'){ ?>
            <link rel="stylesheet" type="text/css" href="<?php echo CSS ?>modules/project_detail.css" media="screen" />
			<link rel="stylesheet" href="<?php echo CSS ?>range/rangeSlider.css" media="screen" type="text/css" />
			<script src="<?= ASSETS ?>js/range/rangeSlider.js"></script>	
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
            <?php if($this->session->userdata('user') && $this->session->userdata('is_authorized')) { ?>
                <link rel="stylesheet" href="<?= CSS ?>cdn/dropzone.5.1.1.css" />
                <script src="<?= ASSETS ?>js/dropzone.js"></script>
            <?php } ?>
        <?php } if (isset($current_page) && ($current_page == 'temporary_project_preview' || $current_page == 'preview_draft_project')){ ?>
            <link rel="stylesheet" type="text/css" href="<?php echo CSS ?>modules/project_detail.css" media="screen" />
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
        <?php } if (isset($current_page) && $current_page == 'post_project'){ ?>
            <link rel="stylesheet" type="text/css" href="<?php echo CSS ?>modules/project_manage_post_edit_forms.css" />
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
        <?php }if (isset($current_page) && $current_page == 'edit_temporary_project_preview'){ ?>
            <link rel="stylesheet" type="text/css" href="<?php echo CSS ?>modules/project_manage_post_edit_forms.css" />
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
        <?php }if (isset($current_page) && $current_page == 'edit_draft_project'){ ?>
            <link rel="stylesheet" type="text/css" href="<?php echo CSS ?>modules/project_manage_post_edit_forms.css" />
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
        <?php }if (isset($current_page) && $current_page == 'edit_project'){ ?>
            <link rel="stylesheet" type="text/css" href="<?php echo CSS ?>modules/project_manage_post_edit_forms.css" />
        <?php } if (isset($current_page) && ($current_page == 'post_project' || $current_page =='edit_temporary_project_preview' || $current_page =='edit_draft_project' || $current_page =='edit_draft_project' || $current_page == 'edit_project') && ($this->session->userdata('user') && $this->session->userdata('is_authorized') )) { ?> 
            <link rel="stylesheet" href="<?= CSS ?>cdn/dropzone.5.1.1.css" />
            <script src="<?= ASSETS ?>js/dropzone.js"></script>
        <?php }?>
		<?php if (isset($current_page) && $current_page == 'membership'){ ?>
            <link rel="stylesheet" type="text/css" href="<?php echo CSS ?>modules/membership.css" />
			<script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
            <script src="<?php echo JS; ?>modules/membership.js" type="text/javascript"></script>
        <?php } if (isset($current_page) && ( $current_page == 'chat_room' || $current_page == 'chat' || $current_page == 'chat-no-record' || $current_page == 'chat-single-record' || $current_page == 'chat-initial' || $current_page == 'initial-view-when-contact-from-contact-me-now')) { ?>
            <link rel="stylesheet" href="<?php echo CSS ?>modules/chat.css" media="screen" type="text/css" />
            <script src="<?= ASSETS ?>js/cdn/popper.1.14.0.min.js"></script>
            <script src="<?= ASSETS ?>js/jquery.nicescroll.js"></script>
            <!--Start Need to be delete when error header load on below pages (hidden_project need this) 17-12-2019-->
        <?php }if (isset($current_page) && ($current_page == 'hidden_project')) { ?>
            <link rel="stylesheet" href="<?php echo ASSETS ?>css/error_default_404_page.css">
            <!--End Need to be delete when error header load on below pages 17-12-2019-->
        <?php } ?>
    
        <!--Always stay here, don't change position-->
        <script src="<?= ASSETS ?>bootstrap-4/dist/js/bootstrap.js"></script>  
        <!--Always stay here, don't change position-->		

        <?php if(isset($current_page) && in_array($current_page, array('post_project', 'temporary_project_preview', 'edit_temporary_project_preview','find_project','project_detail','user_profile','find_professionals', 'project-notification-feed','signin','portfolio_standalone_page')) && !$this->session->userdata ('user')) { ?>
        <!-- Configuration for linked in login -->

        <!-- Configuration for facebook Login -->
        <script>
            window.fbAsyncInit = function () {
                FB.init({
                    appId: '<?php echo $this->config->item('FB_APPID') ?>',
                    cookie: true,
                    xfbml: true,
                    version: '<?php echo $this->config->item('FB_API_VERSION') ?>',
                    oauth: true
                });
                FB.AppEvents.logPageView();
            };
            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {
                    return;
                }
                js = d.createElement(s);
                js.id = id;
                js.src = "https://connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        
        <?php
            }
        ?>

    <?php 
        if(isset($current_page) && in_array($current_page, ['dashboard', 'invite_friends', 'project_detail','user_profile', 'find_professionals', 'find_project', 'project-notification-feed', 'portfolio_standalone_page', 'account-management-account-login-details']) && ($this->session->userdata('user') && $this->session->userdata('is_authorized') )) {
    ?>
    <!-- Configuration for facebook Login -->
    <script>
        window.fbAsyncInit = function () {
            FB.init({
                appId: '<?php echo $this->config->item('FB_APPID') ?>',
                cookie: true,
                xfbml: true,
                version: '<?php echo $this->config->item('FB_API_VERSION') ?>',
                oauth: true
            });
            FB.AppEvents.logPageView();
        };
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    

        <?php        
        }
        ?>

        <base href="<?= (substr(base_url(), 0, strlen(base_url()) - 1)) ?>" />
		
        <!-- for show more / show less button text - not sure its used by Dib-->        
        <style>
            .read-more-state ~ .read-more-trigger:before {
                content: '<?php echo $this->config->item('show_more_txt'); ?>';
            }
            .read-more-state:checked ~ .read-more-trigger:before {
                content: '<?php echo $this->config->item('show_less_txt'); ?>';
            }
        </style>
        <!-- for show more / show less button text - not sure its used by Dib --> 

    <!--  This script used to set user timezone on server -->
    <?php
        if($this->session->userdata('user') && !$this->session->userdata('user_timezone')) {
            $timezone == $this->session->userdata('user_timezone');
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            Date.prototype.stdTimezoneOffset = function () {
                var jan = new Date(this.getFullYear(), 0, 1);
                var jul = new Date(this.getFullYear(), 6, 1);
                return Math.max(jan.getTimezoneOffset(), jul.getTimezoneOffset());
            }

            Date.prototype.isDstObserved = function () {
                return this.getTimezoneOffset() < this.stdTimezoneOffset();
            }

            var today = new Date();
            
            if("<?php echo $timezone; ?>".length==0){
                var timezone_offset_minutes = new Date().getTimezoneOffset();
                timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
                var dst = '';
                if (today.isDstObserved()) { 
                    dst = 1;
                }
                $.ajax({
                    type: "GET",
                    url: SITE_URL+"chat/get_user_timezone",
                    data: 'time='+ timezone_offset_minutes+'&dst='+dst,
                    success: function(){
                        
                    }
                });
            }
        });
    </script>
    <?php
        }
    ?>
    <!-- End -->

    </head>
    <body id="body" >

        <!-- header.php end -->

        <!-- top navigation.php start -->
        <?php
        $CI = & get_instance();
        if (!empty($CI->session->userdata('user'))) {
            $user = $CI->session->userdata('user');
			
        }
        ?>
        <?php if (isset($current_page) &&  $current_page !== 'signup' && $current_page !== 'signin' && $current_page !== 'reset_login_password' && $current_page !== 'send_password_reset_confirmation' && $current_page !== 'reset_password' && $current_page !== 'successfull_password_reset' && $current_page !== 'successful_signup_confirmation' && $current_page !== 'signup_verify_page' && $current_page !== 'successful_signup_verification' && $current_page !== 'post_project' && $current_page !== 'edit_temporary_project_preview' && $current_page !== 'edit_draft_project' && $current_page !== 'edit_project') { ?>
        <div id="header" class="" <?php if (isset($current_page) && $current_page == 'dashboard') { echo 'style="visibility:visible;"'; } ?>>
			<!-- Cookies Section Start -->
			<?php if (isset($current_page) && !$this->session->userdata('user') && ($current_page == 'home' || $current_page == 'project_detail' || $current_page == 'user_profile' ||  $current_page == 'find_professionals' || $current_page == 'find_project' || $current_page == 'referral_program' || $current_page == 'secure_payments_process' || $current_page == 'contact_us' || $current_page == 'we_vs_them' || $current_page == 'privacy_policy' || $current_page == 'terms_and_conditions' || $current_page == 'trust_and_safety' || $current_page == 'faq' || $current_page == 'fees_and_charges' || $current_page == 'about_us' || $current_page == 'code_of_conduct' || $current_page == 'portfolio_standalone_page')) { ?> 
			<div class="cookiesHeader cookiealert" id="cookiesWrapper" style="display:none;">
				<span class="closeDivCookie acceptcookies"><i class="fa fa-times" aria-hidden="true"></i></span>
				<div class="cookiesBody">
					<i class="fas fa-exclamation-circle"></i><?php echo str_replace("{privacy_policy_page_url}",site_url($this->config->item('privacy_policy_page_url')),$this->config->item('accept_cookies_banner_txt')); ?>
				</div>
			</div>
			<script src="<?= ASSETS ?>js/cookie/cookiealert.js"></script>
			<?php } ?>
			<!-- Cookies Section End -->	
            <nav id="headerContent" class="navbar navbar-expand-lg navbar-light bg-primary hTop <?php if (!validate_session()) { echo 'logOffLogo'; } ?>">
			<?php
                if (validate_session() && isset($current_page) && ($current_page == 'dashboard' || $current_page == 'invite_friends' || $current_page == 'user_projects_pending_ratings_feedbacks' || $current_page == 'release_funds' || $current_page == 'deposit-funds' || $current_page == 'withdraw-funds' || $current_page == 'transaction-history' || $current_page == 'invoices' || $current_page == 'invoicing-details' || $current_page == 'user-projects-payments-overview' || $current_page == 'projects-disputes' || $current_page == 'account-management' || $current_page == 'account-management-account-overview' || $current_page == 'account-management-avatar' || $current_page == 'account-management-address' || $current_page == 'account-management-company-address' || $current_page == 'account-management-contact-details' || $current_page == 'account-management-account-login-details' || $current_page == 'account-management-close-account' || $current_page == 'profile-management-profile-definitions' || $current_page == 'profile-management-company-base-information' || $current_page == 'profile-management-competencies' || $current_page == 'profile-management-company-values-and-principles' || $current_page == 'profile-management-mother-tongue' || $current_page == 'profile-management-spoken-foreign-languages' || $current_page == 'profile-management' || $current_page == 'work_experience' || $current_page == 'project-notification-feed' || $current_page == 'education_training' || $current_page == 'certifications' || $current_page == 'portfolio' || $current_page == 'favorite_employers' || $current_page == 'contacts-management' || $current_page == 'my_projects')) { 
					$user = $this->session->userdata('user'); 
					if (empty($is_postjob)) {
				?>
                <div id="mobMenu" class="expand"><i class="fas fa-bars"></i></div>
			<?php 
					}
				}
			?>
				<a class="navbar-brand navLogo" href="
                    <?php
                    if (validate_session()) {
                        echo base_url() . $this->config->item('dashboard_page_url');
                    } else {
                        echo base_url();
                    }
                    ?>" style="font-style: italic;font-weight: bold;color: #fff;text-decoration: none !important;">
                    <img style="" src="<?= ASSETS ?>images/logo.png" />
                </a>
                <?php
                if (validate_session()) { ?>
		            <?php if (empty($is_postjob)) { ?>
						<div class="mobImg">
							<?php
								if(!empty($user_data['user_avatar_exist_status'])) {
									$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_data['profile_name'].USER_AVATAR.$user_data['user_avatar'];
								} else {
									if(($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_data['is_authorized_physical_person'] == 'Y')){
										if($user_data['gender'] == 'M'){
											$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
										}if($user_data['gender'] == 'F'){
										   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
										}
									} else {
										$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
									}
								}
							
							?>

							<a href="<?php echo base_url ($user[0]->profile_name); ?>" id="profile-picture" class="profile-picture default_avatar_image" style="background-image: url('<?php echo $user_profile_picture;?>')"></a>
						</div>
						<ul class="navbar-nav mr-auto headerFP">
                            <?php if (!validate_session()) { ?>
                            <?php } ?>
                            <li class="nav-item">
                                <!-- <a class="nav-link" href="<?php echo site_url($this->config->item('find_projects_page_url')) ?>"><?php echo $this->config->item('browse_projects_txt') ?></a> -->
								<button class="btn default_btn blue_btn browse_find_project" ><?php echo $this->config->item('browse_projects_txt') ?> <i class="fas fa-tasks"></i></button>
                            </li>
                            <li class="nav-item">
                                <!-- <a class="nav-link" href="<?php echo site_url($this->config->item('find_professionals_page_url')) ?>"><?php echo $this->config->item('browse_service_providers_txt') ?></a> -->
								<button class="btn default_btn purple_btn browse_find_professionals"> <?php echo $this->config->item('browse_service_providers_txt') ?> <i class="fas fa-id-card"></i></button>
                            </li>
							<li class="nav-item header_chatRoom">
                                        <button class="btn yellow_btn default_btn chat-room" ><?php echo $this->config->item('header_top_navigation_chat_room_menu_name'); ?> <i class="far fa-comments"></i></button>
                                    </li>
                                    <li class="dropdown nav-item contactRequestList">
                                        <a class="nav-link dropdown-toggle" style="cursor:pointer;" id="notifyHire" role="button" data-toggle="dropdown">
                                            <span id="get_in_contact_unseen_count" class="default_counter_notification_red hire_me_badge badge <?php echo $get_in_contact_unseen_pending_request_count > 0 ? '' : 'd-none'; ?>"><?php echo ($get_in_contact_unseen_pending_request_count > 99 ? '99+' : $get_in_contact_unseen_pending_request_count ); ?></span>
                                            <i class="fas fa-user Rblockon"></i>
                                        </a>
                                        <div class="dropdown-menu hHireTxt">
                                                <div class="hNta"></div>
                                                <h5><?php echo $this->config->item('top_navigation_small_window_get_in_contact_requests_notifications_heading'); ?></h5>
                                                <!-- When Page Uploaded Start -->
                                                <div class="hndrm">
                                                        <div class="<?php echo count($get_in_contact_requests) <= 3 ? 'hNoSc' : 'hNoSc hNoScroll'; ?> <?php echo!empty($get_in_contact_requests) ? '' : 'd-none'; ?>" id="get_in_contact_list">
                                                        <?php
                                                        foreach ($get_in_contact_requests as $key => $val) {
                                                                ?>
                                                                        <div class="userDiv get_in_contact_request_list" data-id="<?php echo $val['user_id'] ?>">
                                                                                <div class="row">
                                                                                        <div class="col-md-12 col-sm-12 col-12 hireRowAdjust">
                                                                                                <div class="hireLeft">
                                                                                                        <div class="hireAvtProfile">
                                                                                                                <div class="default_avatar_image hireAvtPpicture margin_bottom0" style="background-image: url('<?php echo $val['user_avatar']; ?>')">
                                                                                                                </div>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="hireRight">
                                                                                                        <div class="hireRightTop">
                                                                                                                <div class="hireMname">
                                                                                                                        <a href="<?php echo base_url($val['profile_name']); ?>" class="Rblock"><?php echo $val['display_name']; ?></a>
                                                                                                                </div>
                                                                                                                <p><?php echo $val['headline']; ?></p></div>			
                                                                                                        <div class="row lineHeight">
                                                                                                                <div class="col-md-5 col-sm-5 col-12 padding_right0"><div class="hireMdate"><?php echo date(DATE_TIME_FORMAT_EXCLUDE_SECOND, strtotime($val['get_in_contact_request_send_date'])); ?></div></div>
                                                                                                                <div class="col-md-7 col-sm-7 col-12 contactRequestBtn">
                                                                                                                        <div class="get_in_contact_action_button">
                                                                                                                                <button type="button" class="btn green_btn default_btn hireBtnAdjust" data-profile-name="<?php echo $val['display_name']; ?>" data-profile-url="<?php echo base_url($val['profile_name']); ?>" data-id="<?php echo $val['user_id']; ?>" data-action="accept"><?php echo $this->config->item('accept_btn_txt'); ?></button>
                                                                                                                        </div>
                                                                                                                        <div class="padding_left5 get_in_contact_action_button">
                                                                                                                                <button type="button" class="btn red_btn default_btn hireBtnAdjust" data-profile-name="<?php echo $val['display_name']; ?>" data-profile-url="<?php echo base_url($val['profile_name']); ?>" data-id="<?php echo $val['user_id']; ?>" data-action="reject"><?php echo $this->config->item('get_in_contact_request_reject_btn_txt'); ?></button>		
                                                                                                                        </div>
                                                                                                                        <div class="pull-right get_in_contact_response_button d-none">
                                                                                                                                <button type="button" class="btn green_btn default_btn hireBtnAdjust d-none" id="get_in_contact_accepted"><i class="fa fa-check"></i><?php echo $this->config->item('get_in_contact_request_accepted_btn_txt') ?></button>
                                                                                                                                <button type="button" class="btn red_btn default_btn hireBtnAdjust d-none" id="get_in_contact_rejected"><i class="fa fa-check"></i><?php echo $this->config->item('get_in_contact_request_rejected_btn_txt') ?></button>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                        </div>
                                                                <?php
                                                        }
                                                        ?>
                                                        </div>
                                                        <h6 class="<?php echo!empty($get_in_contact_requests) ? '' : 'd-none'; ?>" id="get_in_contact_view_all"><a href="<?php echo VPATH . $this->config->item('contacts_management_page_url'); ?>" class="Rblock"><?php echo $this->config->item('get_in_contact_request_view_all_btn_txt'); ?></a></h6>
                                                </div>
                                                <!-- When Page Uploaded End -->

                                            <!-- When No Page Uploaded Start -->
                                            <div class="hnwPU <?php echo empty($get_in_contact_requests) ? '' : 'd-none' ?>" id="get_in_contact_no_contact_requests_record">
                                                <div class="wPU">
                                                    <i class="far fa-bell"></i>
                                                    <?php echo $this->config->item('get_in_contact_no_contact_requests_record'); ?>
                                                    <div class="noPage_found"><h6><a class="btn default_btn red_btn Rblock" href="<?php echo VPATH . $this->config->item('contacts_management_page_url'); ?>"><?php echo $this->config->item('get_in_contact_request_contacts_mgt_btn_txt'); ?></a></h6></div>
                                                </div>
                                            </div>
                                            <!-- When No Page Uploaded End -->	
                                        </div>
                                    </li>
                            <li class="dropdown nav-item projectNotify">
                                <a class="nav-link dropdown-toggle" style="cursor:pointer;" id="notifyProject" role="button" data-toggle="dropdown">
                                <?php
                                    $unread_newly_posted_message_count = get_user_unread_notification_message_count($user[0]->user_id, 'newly_posted_project');
                                    if ($unread_newly_posted_message_count > 0) {
                                ?>
                                    <span class="default_counter_notification_red new_posted_project_badge badge"><?php echo $unread_newly_posted_message_count > 99 ? "99+" : $unread_newly_posted_message_count; ?></span><?php } ?><i class="fas fa-bullhorn hornIcon"></i></a>
                                    <div class="dropdown-menu hActTxt">
                                        <div class="hNta"></div>
                                        <h5><?php echo $this->config->item('top_navigation_small_window_projects_notifications_heading'); ?></h5>
                                        <?php if (count($project_notification) >= 1) { ?> 
                                        <!-- When Page Uploaded Start -->
                                        <div class="hndrm">
                                            <div id="nHeight" <?php echo count($project_notification) <= 3 ? 'class="hNoSc"' : 'class="hNoSc hNoScroll"' ?>>
                                            <?php
                                                foreach ($project_notification as $key => $val) {
                                                if (is_array($val) && !empty($val)) :
                                            ?>
                                                <div class="hNDiv">
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-12">
                                                            <div class="row">
                                                                <div class="col-md-12 col-sm-12 col-12">
                                                                    <div class="hNLeft">
                                                                        <p>
                                                                            <span class="d-block Rblock"><?php echo trim($val['project_title']); ?></span>
                                                                            <label class="mb-0 d-block">
                                                                                <b class="default_black_bold">
                                                                                        <?php
                                                                                        if ($val['project_type'] == 'fulltime') {
                                                                                                echo trim($this->config->item('realtime_notification_fulltime_project_salary_txt'));
                                                                                        } else {
                                                                                                echo trim($this->config->item('realtime_notification_project_budget_txt'));
                                                                                        }
                                                                                        ?></b><span><?php echo trim($val['budget']); ?></span></label>
                                                                                <label class="mb-0 d-block">
                                                                                    <b class="default_black_bold"><?php echo trim($this->config->item('realtime_notification_project_posting_date_txt')); ?></b><span><?php echo $val['posting_date']; ?></span></label>
                                                                                <label class="mb-0 d-block">
                                                                                    <b class="default_black_bold">
                                                                                    <?php
                                                                                    if ($val['total_category'] > 1) {
                                                                                    echo trim($this->config->item('realtime_notification_project_posted_in_following_categories_plural_txt'));
                                                                                    } else {
                                                                                    echo trim($this->config->item('realtime_notification_project_posted_in_following_category_singular_txt'));
                                                                                    }
                                                                                    ?></b><span><?php echo trim($val['category']); ?></span></label>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                    endif;
                                                }
                                                ?>
                                                </div>
                                                <h6><a class="Rblock" href="<?php echo VPATH . $this->config->item('projects_realtime_notification_feed_page_url'); ?>"><?php echo $this->config->item('view_all_btn_txt'); ?></a></h6>
                                            </div>
                                                    <!-- When Page Uploaded End -->
                                            <?php } else { ?>
                                            <!-- When No Page Uploaded Start -->
                                            <div class="hnwPU">
                                                <div class="wPU">
                                                    <i class="far fa-bell"></i>
                                                    <?php
                                                        if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
                                                            echo $this->config->item('pa_projects_realtime_notification_no_record');
                                                        } else {
                                                            echo $this->config->item('ca_projects_realtime_notification_no_record');
                                                        }
                                                    ?>    
                                                </div>
                                            </div>
                                            <!-- When No Page Uploaded End -->	
                                            <?php } ?>
                                    </div>
                            </li>
                            <li class="dropdown nav-item latestNotify">
                                <a class="nav-link dropdown-toggle" id="notifyDrop" role="button" data-toggle="dropdown">
                                    <?php
                                        $unread_log_message_count = get_user_unread_notification_message_count($user[0]->user_id, 'activity_log');
                                        if ($unread_log_message_count > 0) {
                                    ?>
                                    <span class="default_counter_notification_red badge activity_log_badge"><?php echo $unread_log_message_count > 99 ? "99+" : $unread_log_message_count; ?></span>
                                    <?php 
                                        } 
                                    ?> <i class="fas fa-newspaper"></i></a>
                                <div class="dropdown-menu hNotTxt">
                                    <div class="hNta"></div>
                                    <h5><?php echo $this->config->item('top_navigation_small_window_activities_notifications_heading'); ?></h5>
                                    <?php
                                        $activities = $user_activity_display_notification;
                                        if (count($activities) >= 1) {
                                    ?> 
                                    <!-- When Page Uploaded Start -->
                                    <div class="hndrm">
                                        <div  class="activity_log_window hNoSc">
                                        <?php
                                            foreach ($activities as $key => $val) {
                                                if (is_array($val) && !empty($val)) :
                                        ?>
                                            <div class="hNDiv">
                                                    <div class="row">
                                                            <div class="col-md-12 col-sm-12 col-12">
                                                                    <div class="row">
                                                                            <div class="col-md-12 col-sm-12 col-12">
                                                                                    <div class="hNLeft activityNotify closeBox">
                                                                                            <h4></h4>
                                                                                            <p><?php echo $val['activity_description']; ?></p>
                                                                                    </div>
                                                                            </div>												
                                                                            <div class="col-md-3 col-sm-3 col-3"></div>
                                                                            <div class="col-md-9 col-sm-9 col-9">
                                                                                    <div class="hNdt"><?php echo date(DATE_TIME_FORMAT, strtotime($val['activity_log_record_time'])); ?></div>
                                                                            </div>
                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>
                                            <?php
                                                endif;
                                        }
                                        ?>
                                                </div>
                                                <h6><a class="Rblock" href="<?php echo VPATH . $this->config->item('activity_page_url'); ?>"><?php echo $this->config->item('view_all_btn_txt'); ?></a></h6>
                                        </div>
                                        <!-- When Page Uploaded End -->
                                        <?php } else { ?>
                                            <!-- When No Page Uploaded Start -->
                                            <div class="hnwPU">
                                                    <div class="wPU">
                                                            <i class="far fa-bell"></i>
                                                            <?php echo $this->config->item('activity_no_record'); ?>
                                                    </div>
                                            </div>
                                            <!-- When No Page Uploaded End -->	
                                        <?php } ?>
                                </div>
                            </li>
                        </ul>
                        <div id="mainmenu" class="menuIcon expand">
                            <i class="fas fa-bars"></i>
                        </div>
                    
                            
                            <div class="navbar-collapse aftHead" id="myNavbar">
                                <ul class="navbar-nav mr-auto headerFP_mobile">
                                    <li class="nav-item">
                                        <!-- <a class="nav-link" href="<?php echo site_url($this->config->item('find_projects_page_url')) ?>"><?php echo $this->config->item('browse_projects_txt') ?></a> -->
                                        <button class="btn default_btn blue_btn browse_find_project"><?php echo $this->config->item('browse_projects_txt') ?> <i class="fas fa-tasks"></i></button>
                                    </li>
                                    <li class="nav-item">
                                        <!-- <a class="nav-link" href="<?php echo site_url($this->config->item('find_professionals_page_url')) ?>"><?php echo $this->config->item('browse_service_providers_txt') ?></a> -->
                                        <button class="btn default_btn purple_btn browse_find_professionals" ><?php echo $this->config->item('browse_service_providers_txt') ?> <i class="fas fa-id-card"></i></button>
                                    </li>
                                </ul>
                                <ul class="navbar-nav ml-auto hNot">	
                                    <li class="nav-item header_chatRoom">
                                        <button class="btn yellow_btn default_btn chat-room" ><?php echo $this->config->item('header_top_navigation_chat_room_menu_name'); ?> <i class="far fa-comments"></i></button>
                                    </li>
                                    <li class="dropdown nav-item contactRequestList">
                                        <a class="nav-link dropdown-toggle" style="cursor:pointer;" id="notifyHireMobile" role="button" data-toggle="dropdown">
                                            <span id="get_in_contact_unseen_count" class="default_counter_notification_red hire_me_badge badge <?php echo $get_in_contact_unseen_pending_request_count > 0 ? '' : 'd-none'; ?>"><?php echo ($get_in_contact_unseen_pending_request_count > 99 ? '99+' : $get_in_contact_unseen_pending_request_count ); ?></span>
                                            <i class="fas fa-user Rblockon"></i>
                                        </a>
                                        <div class="dropdown-menu hHireTxt">
                                                <div class="hNta"></div>
                                                <h5><?php echo $this->config->item('top_navigation_small_window_get_in_contact_requests_notifications_heading'); ?></h5>
                                                <!-- When Page Uploaded Start -->
                                                <div class="hndrm">
                                                        <div class="<?php echo count($get_in_contact_requests) <= 3 ? 'hNoSc' : 'hNoSc hNoScroll'; ?> <?php echo!empty($get_in_contact_requests) ? '' : 'd-none'; ?>" id="get_in_contact_list">
                                                        <?php
                                                        foreach ($get_in_contact_requests as $key => $val) {
                                                                ?>
                                                                        <div class="userDiv get_in_contact_request_list" data-id="<?php echo $val['user_id'] ?>">
                                                                                <div class="row">
                                                                                        <div class="col-md-12 col-sm-12 col-12 hireRowAdjust">
                                                                                                <div class="hireLeft">
                                                                                                        <div class="hireAvtProfile">
                                                                                                                <div class="default_avatar_image hireAvtPpicture margin_bottom0" style="background-image: url('<?php echo $val['user_avatar']; ?>')">
                                                                                                                </div>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="hireRight">
                                                                                                        <div class="hireRightTop">
                                                                                                                <div class="hireMname">
                                                                                                                        <a href="<?php echo base_url($val['profile_name']); ?>" class="Rblock"><?php echo $val['display_name']; ?></a>
                                                                                                                </div>
                                                                                                                <p><?php echo $val['headline']; ?></p></div>			
                                                                                                        <div class="row lineHeight">
                                                                                                                <div class="col-md-5 col-sm-5 col-12 padding_right0"><div class="hireMdate"><?php echo date(DATE_TIME_FORMAT_EXCLUDE_SECOND, strtotime($val['get_in_contact_request_send_date'])); ?></div></div>
                                                                                                                <div class="col-md-7 col-sm-7 col-12 contactRequestBtn">
                                                                                                                        <div class="get_in_contact_action_button">
                                                                                                                                <button type="button" class="btn green_btn default_btn hireBtnAdjust" data-profile-name="<?php echo $val['display_name']; ?>" data-profile-url="<?php echo base_url($val['profile_name']); ?>" data-id="<?php echo $val['user_id']; ?>" data-action="accept"><?php echo $this->config->item('accept_btn_txt'); ?></button>
                                                                                                                        </div>
                                                                                                                        <div class="padding_left5 get_in_contact_action_button">
                                                                                                                                <button type="button" class="btn red_btn default_btn hireBtnAdjust" data-profile-name="<?php echo $val['display_name']; ?>" data-profile-url="<?php echo base_url($val['profile_name']); ?>" data-id="<?php echo $val['user_id']; ?>" data-action="reject"><?php echo $this->config->item('get_in_contact_request_reject_btn_txt'); ?></button>		
                                                                                                                        </div>
                                                                                                                        <div class="pull-right get_in_contact_response_button d-none">
                                                                                                                                <button type="button" class="btn green_btn default_btn hireBtnAdjust d-none" id="get_in_contact_accepted"><i class="fa fa-check"></i><?php echo $this->config->item('get_in_contact_request_accepted_btn_txt') ?></button>
                                                                                                                                <button type="button" class="btn red_btn default_btn hireBtnAdjust d-none" id="get_in_contact_rejected"><i class="fa fa-check"></i><?php echo $this->config->item('get_in_contact_request_rejected_btn_txt') ?></button>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                        </div>
                                                                <?php
                                                        }
                                                        ?>
                                                        </div>
                                                        <h6 class="<?php echo!empty($get_in_contact_requests) ? '' : 'd-none'; ?>" id="get_in_contact_view_all"><a href="<?php echo VPATH . $this->config->item('contacts_management_page_url'); ?>" class="Rblock"><?php echo $this->config->item('get_in_contact_request_view_all_btn_txt'); ?></a></h6>
                                                </div>
                                                <!-- When Page Uploaded End -->

                                            <!-- When No Page Uploaded Start -->
                                            <div class="hnwPU <?php echo empty($get_in_contact_requests) ? '' : 'd-none' ?>" id="get_in_contact_no_contact_requests_record">
                                                <div class="wPU">
                                                    <i class="far fa-bell"></i>
                                                    <?php echo $this->config->item('get_in_contact_no_contact_requests_record'); ?>
                                                    <div class="noPage_found"><h6><a class="btn default_btn red_btn Rblock" href="<?php echo VPATH . $this->config->item('contacts_management_page_url'); ?>"><?php echo $this->config->item('get_in_contact_request_contacts_mgt_btn_txt'); ?></a></h6></div>
                                                </div>
                                            </div>
                                            <!-- When No Page Uploaded End -->	
                                        </div>
                                    </li>
                            <li class="dropdown nav-item projectNotify">
                                <a class="nav-link dropdown-toggle" style="cursor:pointer;" id="notifyProjectMobile" role="button" data-toggle="dropdown">
                                <?php
                                    $unread_newly_posted_message_count = get_user_unread_notification_message_count($user[0]->user_id, 'newly_posted_project');
                                    if ($unread_newly_posted_message_count > 0) {
                                ?>
                                    <span class="default_counter_notification_red new_posted_project_badge badge"><?php echo $unread_newly_posted_message_count > 99 ? "99+" : $unread_newly_posted_message_count; ?></span><?php } ?><i class="fas fa-bullhorn hornIcon"></i></a>
                                    <div class="dropdown-menu hActTxt">
                                        <div class="hNta"></div>
                                        <h5><?php echo $this->config->item('top_navigation_small_window_projects_notifications_heading'); ?></h5>
                                        <?php if (count($project_notification) >= 1) { ?> 
                                        <!-- When Page Uploaded Start -->
                                        <div class="hndrm">
                                            <div id="nHeight" <?php echo count($project_notification) <= 3 ? 'class="hNoSc"' : 'class="hNoSc hNoScroll"' ?>>
                                            <?php
                                                foreach ($project_notification as $key => $val) {
                                                if (is_array($val) && !empty($val)) :
                                            ?>
                                                <div class="hNDiv">
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-12">
                                                            <div class="row">
                                                                <div class="col-md-12 col-sm-12 col-12">
                                                                    <div class="hNLeft">
                                                                        <p>
                                                                            <span class="d-block Rblock"><?php echo trim($val['project_title']); ?></span>
                                                                            <label class="mb-0 d-block">
                                                                                <b class="default_black_bold">
                                                                                        <?php
                                                                                        if ($val['project_type'] == 'fulltime') {
                                                                                                echo trim($this->config->item('realtime_notification_fulltime_project_salary_txt'));
                                                                                        } else {
                                                                                                echo trim($this->config->item('realtime_notification_project_budget_txt'));
                                                                                        }
                                                                                        ?></b><span><?php echo trim($val['budget']); ?></span></label>
                                                                                <label class="mb-0 d-block">
                                                                                    <b class="default_black_bold"><?php echo trim($this->config->item('realtime_notification_project_posting_date_txt')); ?></b><span><?php echo $val['posting_date']; ?></span></label>
                                                                                <label class="mb-0 d-block">
                                                                                    <b class="default_black_bold">
                                                                                    <?php
                                                                                    if ($val['total_category'] > 1) {
                                                                                    echo trim($this->config->item('realtime_notification_project_posted_in_following_categories_plural_txt'));
                                                                                    } else {
                                                                                    echo trim($this->config->item('realtime_notification_project_posted_in_following_category_singular_txt'));
                                                                                    }
                                                                                    ?></b><span><?php echo trim($val['category']); ?></span></label>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                    endif;
                                                }
                                                ?>
                                                </div>
                                                <h6><a class="Rblock" href="<?php echo VPATH . $this->config->item('projects_realtime_notification_feed_page_url'); ?>"><?php echo $this->config->item('view_all_btn_txt'); ?></a></h6>
                                            </div>
                                                    <!-- When Page Uploaded End -->
                                            <?php } else { ?>
                                            <!-- When No Page Uploaded Start -->
                                            <div class="hnwPU">
                                                <div class="wPU">
                                                    <i class="far fa-bell"></i>
                                                    <?php
                                                        if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
                                                            echo $this->config->item('pa_projects_realtime_notification_no_record');
                                                        } else {
                                                            echo $this->config->item('ca_projects_realtime_notification_no_record');
                                                        }
                                                    ?>    
                                                </div>
                                            </div>
                                            <!-- When No Page Uploaded End -->	
                                            <?php } ?>
                                    </div>
                            </li>
                            <li class="dropdown nav-item latestNotify">
                                <a class="nav-link dropdown-toggle" id="notifyDropMobile" role="button" data-toggle="dropdown">
                                    <?php
                                        $unread_log_message_count = get_user_unread_notification_message_count($user[0]->user_id, 'activity_log');
                                        if ($unread_log_message_count > 0) {
                                    ?>
                                    <span class="default_counter_notification_red badge activity_log_badge"><?php echo $unread_log_message_count > 99 ? "99+" : $unread_log_message_count; ?></span>
                                    <?php 
                                        } 
                                    ?> <i class="fas fa-newspaper"></i></a>
                                <div class="dropdown-menu hNotTxt">
                                    <div class="hNta"></div>
                                    <h5><?php echo $this->config->item('top_navigation_small_window_activities_notifications_heading'); ?></h5>
                                    <?php
                                        $activities = $user_activity_display_notification;
                                        if (count($activities) >= 1) {
                                    ?> 
                                    <!-- When Page Uploaded Start -->
                                    <div class="hndrm">
                                        <div  class="activity_log_window hNoSc">
                                        <?php
                                            foreach ($activities as $key => $val) {
                                                if (is_array($val) && !empty($val)) :
                                        ?>
                                            <div class="hNDiv">
                                                    <div class="row">
                                                            <div class="col-md-12 col-sm-12 col-12">
                                                                    <div class="row">
                                                                            <div class="col-md-12 col-sm-12 col-12">
                                                                                    <div class="hNLeft activityNotify closeBox">
                                                                                            <h4></h4>
                                                                                            <p><?php echo $val['activity_description']; ?></p>
                                                                                    </div>
                                                                            </div>												
                                                                            <div class="col-md-3 col-sm-3 col-3"></div>
                                                                            <div class="col-md-9 col-sm-9 col-9">
                                                                                    <div class="hNdt"><?php echo date(DATE_TIME_FORMAT, strtotime($val['activity_log_record_time'])); ?></div>
                                                                            </div>
                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>
                                            <?php
                                                endif;
                                        }
                                        ?>
                                                </div>
                                                <h6><a class="Rblock" href="<?php echo VPATH . $this->config->item('activity_page_url'); ?>"><?php echo $this->config->item('view_all_btn_txt'); ?></a></h6>
                                        </div>
                                        <!-- When Page Uploaded End -->
                                        <?php } else { ?>
                                            <!-- When No Page Uploaded Start -->
                                            <div class="hnwPU">
                                                    <div class="wPU">
                                                            <i class="far fa-bell"></i>
                                                            <?php echo $this->config->item('activity_no_record'); ?>
                                                    </div>
                                            </div>
                                            <!-- When No Page Uploaded End -->	
                                        <?php } ?>
                                </div>
                            </li>
                                <li class="nav-item header_postProject">
                                        <a class="btn blue_btn default_btn create_project"><?php echo $this->config->item('header_top_navigation_post_project_menu_name'); ?></a>
                                </li>
                                <li class="nav-item header_logout">
                                    <?php

                                        $controller_name = $this->router->fetch_class(); // class = controller
                                        $action_name = $this->router->fetch_method();
                                        $logout_version_page_array = array(array('controller' => 'user', 'action' => 'user_profile'), array('controller' => 'Find_project', 'action' => 'index'), array('controller' => 'Find_professionals', 'action' => 'index'), array('controller' => 'Projects', 'action' => 'project_detail'), array('controller' => 'user', 'action' => 'portfolio_standalone_page'));
                                        $check_redirection = '0';
                                        foreach ($logout_version_page_array as $page_key => $page_value) {
                                                if ($page_value['controller'] == $controller_name && $page_value['action'] == $action_name) {
                                                        $check_redirection = '1';
                                                        break;
                                                }
                                        }
                                        
                                        if (in_array($current_page, ['find_professionals', 'find_project'])) {
                                    ?>
                                                <a class="btn default_btn red_btn"><?php echo $this->config->item('signout_btn_txt'); ?></a>
                                    <?php
                                        } else {
                                                if ($page_value['action'] == 'project_detail') {
                                    ?>
                                                    <a class="btn default_btn red_btn" data-link="<?php echo VPATH . $this->config->item('logout_page_url') . '?isred=' . $check_redirection . "&project_id=" . $this->input->get('id'); ?>"><?php echo $this->config->item('signout_btn_txt'); ?></a>
                                    <?php } else { ?>
                                                    <a class="btn default_btn red_btn" data-link="<?php echo VPATH . $this->config->item('logout_page_url') . '?isred=' . $check_redirection; ?>"><?php echo $this->config->item('signout_btn_txt'); ?></a>
                                    <?php
                                                }
                                        }
                                    ?>

                                </li>
                        </ul>
                    </div>
        <?php } else { ?>
						
        <?php } ?>
    <?php } else { ?>
        <?php if (empty($is_postjob)) { ?>
						<ul class="navbar-nav mr-auto header_logOffFP">
							<li class="nav-item logOffFP">
								<!-- <a class="nav-link" href="<?php echo site_url($this->config->item('find_projects_page_url')) ?>"><?php echo $this->config->item('browse_projects_txt') ?></a> -->								
								<button class="btn default_btn blue_btn browse_find_project"><?php echo $this->config->item('browse_projects_txt') ?> <i class="fas fa-tasks"></i></button>
							</li>
							<li class="nav-item logOffFP">
								<!-- <a class="nav-link" href="<?php echo site_url($this->config->item('find_professionals_page_url')) ?>"><?php echo $this->config->item('browse_service_providers_txt') ?></a> -->								
								<button class="btn default_btn purple_btn browse_find_professionals" ><?php echo $this->config->item('browse_service_providers_txt') ?> <i class="fas fa-id-card"></i></button>
							</li>
							<li class="nav-item nlpr0 header_signin">
								<?php
								$page_type_attr = '';
								$page_id_attr = '';

								$pages_array = array('project_detail', 'user_profile', 'find_professionals', 'find_project','portfolio_standalone_page','hidden_project');
								if (in_array($current_page, $pages_array)) {
									$page_type_attr = $current_page;
									$page_id_attr = '';
									if ($current_page == 'project_detail' || $current_page == 'hidden_project') {
										$page_id_attr = $_GET['id'];
									}
									if ($current_page == 'portfolio_standalone_page') {
										$page_id_attr = $_GET['id'];
									}
									if ($current_page == 'user_profile') {
										$page_id_attr = $this->uri->segment(1);
									}
									?>

									<a data-page-id-attr = "<?php echo $page_id_attr ?>" data-page-no="<?php echo $page; ?>" data-page-type-attr = "<?php echo $page_type_attr ?>" class="nav-link login_popup" style="cursor:pointer;" <?php if (isset($current_page) && $current_page == $this->config->item('signup_page_url')) { ?>id="current"<?php } ?>></i><?php echo $this->config->item('signin_btn_txt'); ?></a>
										<?php
									} else {
										?>
									<a class="nav-link" href="<?php echo site_url($this->config->item('signin_page_url')) ?>" <?php if (isset($current_page) && $current_page == $this->config->item('signup_page_url')) { ?>id="current"<?php } ?>></i><?php echo $this->config->item('signin_btn_txt'); ?></a>
										<?php
									}
									?>

							</li>
							<li class="nav-item header_register">
								<a class="nav-link" href="<?php echo site_url($this->config->item('signup_page_url')) ?>" <?php if (isset($current_page) && $current_page == $this->config->item('signin_page_url')) { ?> id="current"<?php } ?>><?php echo $this->config->item('signup_btn_txt'); ?></a>
							</li>
						</ul>						
						<div id="mainmenuOff" class="menuIcon menuIcon_logout expand">
							<i class="fas fa-bars"></i>
						</div>
						<div class="navbar-collapse preHead" id="myNavbarOff">
							<ul class="navbar-nav mr-auto headerOffFP">
		
								<li class="nav-item logOffFP">
									<!-- <a class="nav-link" href="<?php echo site_url($this->config->item('find_projects_page_url')) ?>"><?php echo $this->config->item('browse_projects_txt') ?></a> -->
									<button class="btn default_btn blue_btn browse_find_project"><?php echo $this->config->item('browse_projects_txt') ?> <i class="fas fa-tasks"></i></button>
								</li>
								<li class="nav-item logOffFP">
									<!-- <a class="nav-link" href="<?php echo site_url($this->config->item('find_professionals_page_url')) ?>"><?php echo $this->config->item('browse_service_providers_txt') ?></a> -->
									<button class="btn default_btn purple_btn browse_find_professionals" ><?php echo $this->config->item('browse_service_providers_txt') ?> <i class="fas fa-id-card"></i></button>
								</li>
							</ul>
							<ul class="navbar-nav openRight" id="signin">
								<li class="nav-item logOffFP_mobile">
									<!-- <a class="nav-link" href="<?php echo site_url($this->config->item('find_projects_page_url')) ?>"><?php echo $this->config->item('browse_projects_txt') ?></a> -->
									<button class="btn default_btn blue_btn browse_find_project"><?php echo $this->config->item('browse_projects_txt') ?> <i class="fas fa-tasks"></i></button>
								</li>
								<li class="nav-item logOffFP_mobile">
									<!-- <a class="nav-link" href="<?php echo site_url($this->config->item('find_professionals_page_url')) ?>"><?php echo $this->config->item('browse_service_providers_txt') ?></a> -->
									<button class="btn default_btn purple_btn browse_find_professionals" ><?php echo $this->config->item('browse_service_providers_txt') ?> <i class="fas fa-id-card"></i></button>
								</li>							
								<li class="nav-item nlpr0 header_signin">
									<?php
									$page_type_attr = '';
									$page_id_attr = '';

									$pages_array = array('project_detail', 'user_profile', 'find_professionals', 'find_project','portfolio_standalone_page','hidden_project');
									if (in_array($current_page, $pages_array)) {
										$page_type_attr = $current_page;
										$page_id_attr = '';
										if ($current_page == 'project_detail' || $current_page == 'hidden_project') {
											$page_id_attr = $_GET['id'];
										}
										if ($current_page == 'portfolio_standalone_page') {
											$page_id_attr = $_GET['id'];
										}
										if ($current_page == 'user_profile') {
											$page_id_attr = $this->uri->segment(1);
										}
										?>

										<a data-page-id-attr = "<?php echo $page_id_attr ?>" data-page-no="<?php echo $page; ?>" data-page-type-attr = "<?php echo $page_type_attr ?>" class="nav-link login_popup" style="cursor:pointer;" <?php if (isset($current_page) && $current_page == $this->config->item('signup_page_url')) { ?>id="current"<?php } ?>></i><?php echo $this->config->item('signin_btn_txt'); ?></a>
			<?php
		} else {
			?>
										<a class="nav-link" href="<?php echo site_url($this->config->item('signin_page_url')) ?>" <?php if (isset($current_page) && $current_page == $this->config->item('signup_page_url')) { ?>id="current"<?php } ?>></i><?php echo $this->config->item('signin_btn_txt'); ?></a>
			<?php
		}
		?>

								</li>
								<li class="nav-item header_register">
									<a class="nav-link" href="<?php echo site_url($this->config->item('signup_page_url')) ?>" <?php if (isset($current_page) && $current_page == $this->config->item('signin_page_url')) { ?> id="current"<?php } ?>><?php echo $this->config->item('signup_btn_txt'); ?></a>
								</li>
								<li class="nav-item btn-post-project">
									<a class="btn blue_btn default_btn create_project"><?php echo $this->config->item('header_top_navigation_post_project_menu_name'); ?></a>
								</li>
							</ul>
						</div>
                <?php } ?>
    <?php } ?>
			</nav>
		</div>
    <?php
} elseif (isset($current_page) && ($current_page == 'successful_signup_verification' )) {
    
} else {
    
    if ($current_page != 'signup_verify_page') {
        validate_session(); // check session on 404 page,sigin,signup page for unverfied user
    }
}
?>

        <script>
		//start header bottom border come when scroll
		$(document).scroll(function(){
			<?php if (isset($current_page) && ($current_page == 'find_project' || $current_page =='find_professionals' )){ ?> 
				if($(window).outerWidth() <= 1200) {
					if($(this).scrollTop() > 1) { 
						$('#headerContent').addClass('hTop_bottomBdr');
					} else {				
						$('#headerContent').removeClass('hTop_bottomBdr');
					}
				}
			<?php } else { ?>
			if($(this).scrollTop() > 1) { 
				$('#headerContent').addClass('hTop_bottomBdr');
			} else {				
				$('#headerContent').removeClass('hTop_bottomBdr');
			}
			<?php } ?>
		});
		//signin / register hover top line exist
		$(".header_signin a, .header_register a").hover(function(){
		  $(this).parent().addClass("hoverTopBorder");
		  }, function(){
		  $(this).parent().removeClass("hoverTopBorder");
		});
		//bottom gap used in footer script
		var margin_bottom_content = 20;
		//end header bottom border come when scroll
		        var dDwidth = 1100;
                var dMwidth = 1099;
                var dDwidthPlus  = parseInt(dDwidth)+1;
            // ################################################# get in contact ###############################################################
            // This method is used to update unseen status of receive request when user click on get in contact header menu
            function update_user_get_in_contact_seen_by_receiver_status() {
                var counter = parseInt($('#get_in_contact_unseen_count').html());
                if (counter > 0) {
                    $.ajax({
                        url: SITE_URL + 'chat/ajax_update_user_get_in_contact_seen_by_receiver_status',
                        method: 'POST',
                        dataType: 'json',
                        success: function (res) {
                            if (res['status'] == 200) {
                                if (!$('#get_in_contact_unseen_count').hasClass('d-none')) {
                                    $('#get_in_contact_unseen_count').addClass('d-none');
                                }
                                $('#get_in_contact_unseen_count').html(0);
                                var obj = {
                                    type: 'UPDATE_USER_UNSEEN_RECEIVED_REQUEST_COUNTER',
                                    sender_id: user_id
                                };
                                try {
                                    Server.send('message', JSON.stringify(obj));
                                } catch (err) {
                                }
                            }
                        }
                    });
                }
            }

            $('#notifyHireMobile').on('click', function (e) {
				if($(window).outerWidth()>1405) {
					//left mobilemenu close when click on outside.
				$("body").removeClass('overflow-hidden');
				$(".disable_content").removeClass('d-block').addClass('d-none');
				
					var obj = this;
					$('.hActTxt, .hNotTxt').hide();
					$(this).next().toggle();
					update_user_get_in_contact_seen_by_receiver_status();
				}else{
                    $('#notifyHireMobile').removeAttr('data-toggle').attr('href','<?php echo VPATH . $this->config->item('contacts_management_page_url'); ?>');
				}
			});
            $('#notifyHire').on('click', function (e) {
				//$('#notifyHire').removeAttr('href').attr('data-toggle','dropdown');
				//left mobilemenu close when click on outside.
				$("body").removeClass('overflow-hidden');
				$(".disable_content").removeClass('d-block').addClass('d-none');
				var obj = this;
				$('.hActTxt, .hNotTxt').hide();
				$(this).next().toggle();
				update_user_get_in_contact_seen_by_receiver_status();
            });

            // ################################################# end ##########################################################################

            //close popup when user click on view all
            $('.Rblock').on('click', function (e) {
                e.stopPropagation();
                $('.hActTxt').hide();
                $('.hNotTxt').hide();
                $('.hHireTxt').hide();
            });
               
             //close popup when user click project activity details
            $('.closeBox a').on('click', function (e) {
                e.stopPropagation();
                $('.hNotTxt').hide();
            }); 

            
            $('.dropdown-menu.hHireTxt').on('click', function (e) {
                if ($(e.target).hasClass('hireBtnAdjust')) {
                    return;
                }
                e.stopPropagation();
                $('.hHireTxt').show();
            });

            $(document).click(function (event) {
                if ($(event.target).hasClass('hireBtnAdjust')) {
                    $('.hHireTxt').show();
                    return;
                }

                $('.hHireTxt').hide();
                // check if the clicked element is a descendent of navigation 
                if ($(event.target).closest('.navbar-toggler').length) {
                    $('.collapse').collapse('hide');

                    return; //do nothing if event target is within the navigation
                } else {
                    $('.collapse').collapse('hide');
                    // do something if the event target is outside the navigation
                    // code for collapsing menu here...
                }
            });
            //-----------------------  notify hire me-----------------
            $('#notifyDropMobile').on('click', function (e) {
				if($(window).outerWidth()>1405) {
					//left mobilemenu close when click on outside.
					$("body").removeClass('overflow-hidden');
					$(".disable_content").removeClass('d-block').addClass('d-none');
				
					var obj = this;
					$('.hActTxt, .hHireTxt').hide();
					$(this).next().toggle();
					$.ajax({
						type: "POST",
						url: SITE_URL + "user_activity_log/reset_user_unread_activity_log_messages_count",
						dataType: "json",
						cache: false,
						success: function (msg)
						{
							if (msg['status'] === 200)
							{
								$(obj).find('.activity_log_badge').remove();
							}
						},
						error: function (msg)
						{

						}
					});
					if($(".activity_log_window").height() >= 305){
						$(".activity_log_window").addClass("hNoScroll");
					}else{
						$(".activity_log_window").removeClass("hNoScroll");
					}
				} else {
					$('#notifyDropMobile').removeAttr('data-toggle').attr('href','<?php echo VPATH . $this->config->item('activity_page_url'); ?>');
				}
			});
            $('#notifyDrop').on('click', function (e) {
			   // $('#notifyDrop').removeAttr('href').attr('data-toggle','dropdown');
				//left mobilemenu close when click on outside.
				$("body").removeClass('overflow-hidden');
				$(".disable_content").removeClass('d-block').addClass('d-none');
				
				var obj = this;
				$('.hActTxt, .hHireTxt').hide();
				$(this).next().toggle();
				$.ajax({
					type: "POST",
					url: SITE_URL + "user_activity_log/reset_user_unread_activity_log_messages_count",
					dataType: "json",
					cache: false,
					success: function (msg)
					{
						if (msg['status'] === 200)
						{
							$(obj).find('.activity_log_badge').remove();
						}
					},
					error: function (msg)
					{

					}
				});
				if($(".activity_log_window").height() >= 305){
					$(".activity_log_window").addClass("hNoScroll");
				}else{
					$(".activity_log_window").removeClass("hNoScroll");
				}
            });
            $('.dropdown-menu.hNotTxt').on('click', function (e) {
                e.stopPropagation();
                $('.hNotTxt').show();
            });
            $(document).click(function (event) {
                $('.hNotTxt').hide(); 
                // check if the clicked element is a descendent of navigation 
                if ($(event.target).closest('.navbar-toggler').length) {
                    $('.collapse').collapse('hide');

                    return; //do nothing if event target is within the navigation
                } else {
                    $('.collapse').collapse('hide');
                    // do something if the event target is outside the navigation
                    // code for collapsing menu here...
                }
            });
            //-----------------------  notify drop end-----------------

            //-----------------------  notify project  start-----------
            $('#notifyProjectMobile').on('click', function (e) {
				if($(window).outerWidth()>1405) {
					//left mobilemenu close when click on outside.
					$("body").removeClass('overflow-hidden');
					$(".disable_content").removeClass('d-block').addClass('d-none');
				
					var obj = this;
					$('.hNotTxt, .hHireTxt').hide();
					$(this).next().toggle();
					$.ajax({
						type: "POST",
						url: SITE_URL + "newly_posted_projects_realtime_notifications/reset_user_unread_newly_posted_project_messages_count",
						dataType: "json",
						cache: false,
						success: function (msg)
						{
							if (msg['status'] === 200)
							{
								$(obj).find('.new_posted_project_badge').remove();
							}
						},
						error: function (msg)
						{

						}
					});
				} else {
					$('#notifyProjectMobile').removeAttr('data-toggle').attr('href','<?php echo VPATH . $this->config->item('projects_realtime_notification_feed_page_url'); ?>');
				}
			});
            $('#notifyProject').on('click', function (e) {
				//$('#notifyProject').removeAttr('href').attr('data-toggle','dropdown');
				//left mobilemenu close when click on outside.
				$("body").removeClass('overflow-hidden');
				$(".disable_content").removeClass('d-block').addClass('d-none');
				
				var obj = this;
				$('.hNotTxt, .hHireTxt').hide();
				$(this).next().toggle();
				$.ajax({
					type: "POST",
					url: SITE_URL + "newly_posted_projects_realtime_notifications/reset_user_unread_newly_posted_project_messages_count",
					dataType: "json",
					cache: false,
					success: function (msg)
					{
						if (msg['status'] === 200)
						{
							$(obj).find('.new_posted_project_badge').remove();
						}
					},
					error: function (msg)
					{

					}
				});
            });
             
            
            
            $('.dropdown-menu.hActTxt').on('click', function (e) {
                var obj = this;

                e.stopPropagation();
                $('.hActTxt').show();
            });
            $(document).click(function (event) {
                $('.hActTxt').hide();
                // check if the clicked element is a descendent of navigation 
                if ($(event.target).closest('.navbar-toggler').length) {
                    $('.collapse').collapse('hide');

                    return; //do nothing if event target is within the navigation
                } else {
                    $('.collapse').collapse('hide');
                    // do something if the event target is outside the navigation
                    // code for collapsing menu here...
                }
            });
			
            //top mobilemenu close when click on menu link logged in.
            $(document).on('click', '#myNavbar a, #myNavbar button', function () {
               //top menu off if open
                $('#myNavbar').removeClass('active');
                $('#myNavbar').removeClass('d-block').addClass('d-none');
                $("#mainmenu.close").removeClass('close').addClass('expand');
                $("#mainmenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
                $('#myNavbar ul').hide();  
                if($(window).outerWidth()>767 && $(window).outerWidth()<=1150) {
                    $('.headerFP_mobile').hide();
                }
                if($(window).outerWidth()>1150) {
                    $('#myNavbar ul').show();
                }
            });
            //top mobilemenu close when click on menu link logged out.
            $(document).on('click', '#myNavbarOff a, #myNavbarOff button', function () {
                $('#myNavbarOff').toggleClass('active');
                $('#myNavbarOff').removeClass('d-block').addClass('d-none');
                $("#mainmenuOff.close").removeClass('close').addClass('expand');
                $("#mainmenuOff.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
                $('#myNavbarOff ul').hide();
                if($(window).outerWidth()>772 && $(window).outerWidth()<=1255) {
                    $('.headerOffFP').hide();
                }
                if($(window).outerWidth()>1255) {
                    $('#myNavbarOff ul').show();
                }
            });

            $(document).click(function (e) {
                //top mobilemenu close when click on outside logged in.
                if($('#myNavbar.active').is(':visible') && !$(e.target).closest('#mainmenu.close').length && !$(e.target).closest('#myNavbar.active').length) {
                    $('#myNavbar').toggleClass('active');
                    $('#myNavbar').removeClass('d-block').addClass('d-none');
                    $("#mainmenu.close").removeClass('close').addClass('expand');
                    $("#mainmenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
                    $('#myNavbar ul').hide();
                }
                //top mobilemenu close when click on outside log out.
                if($('#myNavbarOff.active').is(':visible') && !$(e.target).closest('#mainmenuOff.close').length && !$(e.target).closest('#myNavbarOff.active').length) {
                    $('#myNavbarOff').toggleClass('active');
                    $('#myNavbarOff').removeClass('d-block').addClass('d-none');
                    $("#mainmenuOff.close").removeClass('close').addClass('expand');
                    $("#mainmenuOff.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
                    $('#myNavbarOff ul').hide();
                }
                //left mobilemenu close when click on outside.
				$("body").removeClass('overflow-hidden');
				$(".disable_content").removeClass('d-block').addClass('d-none');
				
                if(!$(e.target).closest('#mobMenu.close').length && !$(e.target).closest('#sidebar.active').length) {

					<?php if (isset($current_page) && $current_page == 'dashboard') { ?>
					//$(".dbMiddle, .dbChat").removeAttr('style');
					$(".dbMiddle, .dbChat").css('width', '');
					<?php } ?>
                    $('#sidebar').toggleClass('active');
                    if ($(window).outerWidth() >= dDwidth) {
                        $('#sidebar ul').show();
                        $('#sidebar').removeClass('active');
                        $('#sidebar span').fadeIn();
						if ( typeof scrollheightMenuT !== 'undefined' ) {
							$(window).scrollTop(scrollheightMenuT);
							delete scrollheightMenuT; 
						}
                    } 
                    if($(window).outerWidth()<=dMwidth) {
                        $("#mobMenu").removeClass('close').addClass('expand');
                        $("#mobMenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
                        $('#sidebar ul').hide();
                        $('#sidebar').removeClass('active');
						if ( typeof scrollheightMenu !== 'undefined' ) {
							$(window).scrollTop(scrollheightMenu);
							delete scrollheightMenu; 
						}
						
                    }
                }
				if($(e.target).closest('#sidebarL ul').length && $(e.target).closest('#sidebar.active').length) {
					if($(window).outerWidth()<=dMwidth) {
						$("body").addClass('overflow-hidden');
						$(".disable_content").removeClass('d-none').addClass('d-block position-fixed');
						$(".disable_content").css({'left':0});
					}
				}
				
                <?php if (isset($current_page) && ($current_page == 'my_projects' || $current_page == 'dashboard')) { ?>
                    //my projects tab
                    var as = $("#myProjecttab2").hasClass('collapsed');
                    if(as === true && !$(e.target).closest('#myProjecttab2.collapsed').length && !$(e.target).closest('#homeNav6.show').length) {
                            $("#myProjecttab2 i").removeClass('fa-times').addClass('fa-bars');
                    }
                    var ae = $("#myProjecttab1").hasClass('collapsed');
                    if(ae === true && !$(e.target).closest('#myProjecttab1.collapsed').length && !$(e.target).closest('#homeNav5.show').length) {
                            $("#myProjecttab1 i").removeClass('fa-times').addClass('fa-bars');
                    }
                    // my projects action drop-down
                    
                        
                <?php } ?>
            });
           
            //topmenu open mobile view logged in
            $(document).on('click', '#mainmenu', function () {
                $('#myNavbar').toggleClass('active');
                var mv = $("#mainmenu").hasClass('expand');
                if(mv===true) {
                    $('#myNavbar').removeClass('d-none').addClass('d-block');
                    $("#mainmenu.expand").removeClass('expand').addClass('close');
                    $("#mainmenu.close i").removeClass('fas fa-bars').addClass('fas fa-times');
                    $('#myNavbar ul').fadeIn("slow");
                    if($(window).outerWidth()>767 && $(window).outerWidth()<=1150) {
                        $('.headerFP_mobile').hide();
                    }
                    //left menu off if open
                    if ($(window).outerWidth() >= dDwidth) {
                        $('#sidebar ul').show();
                        $('#sidebar').removeClass('active');
                        $('#sidebar span').fadeIn();
                    } 
                    if($(window).outerWidth()<=dMwidth) {
                        $("#mobMenu").removeClass('close').addClass('expand');
                        $("#mobMenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
                        $('#sidebar ul').hide();
                        $('#sidebar').removeClass('active');
                    }
                    
                } else {
                    $('#myNavbar').removeClass('d-block').addClass('d-none');
                    $("#mainmenu.close").removeClass('close').addClass('expand');
                    $("#mainmenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
                    $('#myNavbar ul').hide();
                }
                <?php if (isset($current_page) && ($current_page == 'my_projects' || $current_page == 'dashboard')) { ?>
                        $("#myProjecttab2 i, #myProjecttab1 i").removeClass('fa-times').addClass('fa-bars');
                <?php } ?>
            });
            $(window).resize(function() {
                $('#myNavbar').removeClass('active');
                $('#myNavbar').removeClass('d-block').addClass('d-none');
                $("#mainmenu.close").removeClass('close').addClass('expand');
                $("#mainmenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
                $('#myNavbar ul').hide();
                    
                if($(window).outerWidth()>767 && $(window).outerWidth()<=1150) {
                    $('.headerFP_mobile').hide();
                }
                if($(window).outerWidth()>1150) {
                    $('#myNavbar ul').show();
                }
                <?php if (isset($current_page) && ($current_page == 'my_projects' || $current_page == 'dashboard')) { ?>
                        $('#myProjecttab1, #myProjecttab2').addClass('collapsed');
                        $('#homeNav5, #homeNav6').removeClass('show');
                        $("#myProjecttab1 i, #myProjecttab2 i").removeClass('fa-times').addClass('fa-bars');
                <?php } ?>
            });
            $(window).ready(function() {
                if($(window).outerWidth()>=991 && $(window).outerWidth()<=1150) {
                    $('#myNavbar ul').hide();
                }
                if($(window).outerWidth()>767 && $(window).outerWidth()<=1150) {
                    $('.headerFP_mobile').hide();
                }
                if($(window).outerWidth()>1150) {
                    $('#myNavbar ul').show();
                }
            });
            //end of logged in top menu
            //topmenu open mobile view logout
            $(document).on('click', '#mainmenuOff', function () {
                $('#myNavbarOff').toggleClass('active');
                var mv = $("#mainmenuOff").hasClass('expand');
                if(mv===true) {
                    $('#myNavbarOff').removeClass('d-none').addClass('d-block');
                    $("#mainmenuOff.expand").removeClass('expand').addClass('close');
                    $("#mainmenuOff.close i").removeClass('fas fa-bars').addClass('fas fa-times');
                    $('#myNavbarOff ul').fadeIn("slow");
                    if($(window).outerWidth()>670 && $(window).outerWidth()<=1255) {
                        $('.headerOffFP').hide();
                    }
                } else {
                    $('#myNavbarOff').removeClass('d-block').addClass('d-none');
                    $("#mainmenuOff.close").removeClass('close').addClass('expand');
                    $("#mainmenuOff.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
                    $('#myNavbarOff ul').hide();
					
                }
            });
            $(window).resize(function() {
                $('#myNavbarOff').removeClass('active');
                $('#myNavbarOff').removeClass('d-block').addClass('d-none');
                $("#mainmenuOff.close").removeClass('close').addClass('expand');
                $("#mainmenuOff.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
                $('#myNavbarOff ul').hide();
                
                if($(window).outerWidth()>670 && $(window).outerWidth()<=1255) {
                    $('.headerOffFP').hide();
                }
                if($(window).outerWidth()>1255) {
                    $('#myNavbarOff ul').show();
					$('#signin').removeAttr('style');
                }
            });
            $(window).ready(function() {
                if($(window).outerWidth()>=991 && $(window).outerWidth()<=1255) {
                    $('#myNavbarOff ul').hide();
                }
                if($(window).outerWidth()>670 && $(window).outerWidth()<=1255) {
                    $('.headerOffFP').hide();
                }
                if($(window).outerWidth()>1255) {
                    $('#myNavbarOff ul').show();
                }
            });
            //end of logout top menu
            
            //start action button drop-down
            $(window).ready(function() {
            
                $(".dropdown").on("show.bs.dropdown", function(){    
                    if ($(window).outerWidth() >= dDwidth) {
                        $('#sidebar ul').show();
                        $('#sidebar').removeClass('active');
                        $('#sidebar span').fadeIn();
                    }
                    <?php if (isset($current_page) && ($current_page == 'my_projects' || $current_page == 'dashboard')) { ?>
                        $('#myProjecttab1, #myProjecttab2').addClass('collapsed');
                        $('#homeNav5, #homeNav6').removeClass('show');
                        $("#myProjecttab1 i, #myProjecttab2 i").removeClass('fa-times').addClass('fa-bars');
                    <?php } ?>
                    //top menu off if open
                    $('#myNavbar').removeClass('active');
                    $('#myNavbar').removeClass('d-block').addClass('d-none');
                    $("#mainmenu.close").removeClass('close').addClass('expand');
                    $("#mainmenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
                    $('#myNavbar ul').hide();
                    if($(window).outerWidth()>767 && $(window).outerWidth()<=1150) {
                        $('.headerFP_mobile').hide();
                    }
                    if($(window).outerWidth()>1150) {
                        $('#myNavbar ul').show();
                    }
                });
            });
            //end action button drop-down
        </script>
<!-- top navigation.php end -->