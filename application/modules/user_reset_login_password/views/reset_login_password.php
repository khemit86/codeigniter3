<div class="container-fluid" id="forgot-password-page">
    <div class="headerTop row">
        <div class="col-xl-12 col-lg-12" id="forgot-password-inner">
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

                <!-- h1>Resetujte heslo pro svůj účet <strong style="color: #132c78">travai.cz</strong>< !--Reset the Password of your Travai Account </h1 -->
                <h1 class="default_black_regular_xxl">Resetujte heslo pro svůj účet<h1>
            </header>
		</div>
	</div>
		<div class="content">
		
			<div class="content-inner">
			
				<?php
				$attributes = [
					'id' => 'forgot-password-form',
					'class' => 'reply',
					'role' => 'form',
					'name' => 'forgot_password',
					'onsubmit' => "return false;",
					'novalidate' => "true",
				];
				echo form_open ('', $attributes);
				?>
				<?php
				
				if($this->session->flashdata('error'))
				{
					echo '<div class="alert alert-danger">'.$this->session->flashdata('error').'</div>';
				}
				?>
				<div class="row">
                    <div class="col-md-12 col-sm-12 col-12">
                        <h4 class="default_black_regular_xl">Pojďme zjistit účet<!--Let's find out your account--></h4>
                    </div>
					<div class="col-md-2 col-sm-2 col-2 lock">
						<figure>
							<img class="img-fluid" src="<?php echo site_url ('assets/images/reset-password.png'); ?>" alt="reset password icon">
						</figure>
					</div>
					<div class="col-md-10 col-sm-10 col-10 input" style="padding-left:0">
						<div class="form-group">
							<label class="default_black_bold_medium">Vložte emailovou adresu<!--Please enter your email address--></label>
                            <input type="text" class="form-control avoid_space login_register_input_field" name="email" id="email">
							<span id="usernameError" class="error-msg13"></span>
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-12">
						<button type="submit" id="submit-check" class="btn blue_btn default_btn btn-block">Resetovat heslo<!--Reset Password--></button>
			 <?php echo form_close (); ?>
					</div>
				</div>
				
		</div>		
		</div>
   
</div>
<script>

var email_validation_forgot_password_message = "<?php echo $this->config->item('email_validation_forgot_password_message'); ?>";
var email_address_not_exists_forgot_password_message = "<?php echo $this->config->item('email_address_not_exists_forgot_password_message'); ?>";
var valid_email_validation_forgot_password_message = "<?php echo $this->config->item('valid_email_validation_forgot_password_message'); ?>";





</script>
 <script src="<?php echo JS ?>modules/reset_login_password.js"></script>