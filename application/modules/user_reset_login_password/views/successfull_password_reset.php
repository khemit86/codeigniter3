<div class="container-fluid successfull-password-reset-page" id="successfull-password-reset-page">
    <div class="headerTop row">
        <div class="col-xl-12 col-lg-12" id="successfull-password-reset-inner">
            <header>
				<a class="navLogo" href="
					<?php 
					if(validate_session ()) 
					{ 
					echo base_url().$this->config->item('dashboard_page_url'); 
					} else {
					echo base_url();
					} 
					?>">

					<img src="<?php echo site_url ('assets/images/site-inner-logo.png'); ?>" />
				</a>
            </header>
		</div>
	</div>
	<div class="content">
		<div class="content-inner">
			<div id="reset" class="reset text-center">
                <h1 class="successMainText default_bigger_heading_blue"><?php echo str_replace(array('{user_first_name_or_company_name}'),array($name),$this->config->item('successful_password_reset_page_heading_txt'))?></h1>
				<h3 class="successSubText"><?php echo $this->config->item('successful_password_reset_page_sub_heading_txt'); ?></h3>
				<h3 class="successLink"><?php echo str_replace(array('{signin_page_url}'),array($this->config->item('signin_page_url')),$this->config->item('successful_password_reset_page_continue_click_here_txt'))?></h3>
			</div>
		</div> 
	</div>
</div>