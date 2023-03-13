<div class="container-fluid successful-registration-confimation-page" id="successful-registration-confimation-page">
    <div class="row">
        <div class="col-xl-12 col-lg-12" id="successful-registration-inner">
            <header>
                <a class="navLogo" href="
					<?php
                if (validate_session()) {
                    echo base_url() . $this->config->item('dashboard_page_url');
                } else {
                    echo base_url();
                }
                ?>">

                    <img src="<?php echo site_url('assets/images/site-inner-logo.png'); ?>"/>
                </a>
            </header>
        </div>
    </div>
    <div class="content">
        <div class="content-inner">
            <div id="reset" class="reset text-center">
                <h1 class="default_bigger_heading_blue"><?php echo $this->config->item('successful_signup_confirmation_page_heading_txt'); ?></h1>
                <p><?php echo $this->config->item('successful_signup_confirmation_page_sub_heading_txt'); ?></p>
                <?php
                 $account_expiration_time = $this->config->item('signup_unverified_user_remove_set_interval');
                 $account_expiration_time = $account_expiration_time / 60;
				$successful_signup_confirmation_page_message_txt = $this->config->item('successful_signup_confirmation_page_message_txt');
				$successful_signup_confirmation_page_message_txt = str_replace(array("{newly_registered_account_confirmation_expiration_time}","{newly_registered_account_confirmation_expiration_date}"),array($account_expiration_time,date(DATE_TIME_FORMAT, strtotime($account_expiration_date))),$successful_signup_confirmation_page_message_txt);
				echo $successful_signup_confirmation_page_message_txt;	
                ?>
            </div>
			
            <div class="form-actions text-center">
               <?php /*  <a class="btn blue_btn default_btn" href="<?php echo base_url($this->config->item('signin_page_url')) ?>"><?php echo $this->config->item('successful_signup_confirmation_page_login_btn_txt'); ?></a> */ ?>
            </div>
        </div>
    </div>
</div>