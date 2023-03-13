<div class="container-fluid" id="reset-password-page">
    <div class="headerTop row">
        <div class="col-xl-12 col-lg-12" id="reset-password-inner">
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
                <h1 class="default_black_regular_xxl"><!--Reset the Password of your Travai Account-->Resetování hesla pro váš účet</h1>
            </header>
        </div>
    </div>
    <div class="content">
        <div class="content-inner">
            <?php
            $attributes = [
                'id' => 'reset-password-form',
                'class' => 'reply',
                'role' => 'form',
                'name' => 'reset_password',
                'onsubmit' => "return false;",
                'novalidate' => "true",
            ];
            echo form_open('', $attributes);
            ?>
            <span id="agree_termsError" class="error-msg5 error alert-error alert" style="display:none"></span>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12 text-center">
                    <!--					<h4>Password must contain at least -->
                    <?php //echo $this->config->item('password_min_length_character_limit_validation_reset_password'); ?><!-- characters and <br /> should be unique to you </h4>-->
                    <h3 class="resetPasswordText">Heslo musí obsahovat
                        nejméně <?php echo $this->config->item('password_min_length_character_limit_validation_reset_password'); ?>
                        znaků a mělo by být jedinečné (kombinace písmen a čísel)</h3>
                </div>
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group default_login_input">
                        <label class="default_black_bold_medium"><!--New password-->Nové heslo</label>
						<div class="input-group">
							<input type="password" class="form-control avoid_space login_register_input_field" name="password" id="password">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fa fa-eye" aria-hidden="true" id="passwordReset"></i></span>
							</div>
						</div>
						<div class="error-msg13 clearfix" id="usernameError"><span class="error-msg"></span></div>
                        <!--<input type="password" class="form-control" name="password" id="password">
                        <i class="fa fa-eye" onclick="passwordEyeReset('password','passwordReset')"
                           id="passwordReset"></i>
						   <i class="fa fa-eye" 
                           id="passwordReset"></i>
                        <span id="usernameError" class="error-msg13"></span>-->
                    </div>
                    <div class="form-group default_login_input">
                        <label class="default_black_bold_medium"><!--Confirm password-->Potvrzení hesla</label>
						<div class="input-group">
							<input type="password" class="form-control avoid_space login_register_input_field" name="confirm_password" id="confirm_password">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fa fa-eye" aria-hidden="true" id="confirmPasswordReset"></i></span>
							</div>
						</div>
						<div class="error-msg13 clearfix" id="usernameError"><span class="error-msg"></span></div>
                        <!--<input type="password" class="form-control" name="confirm_password" id="confirm_password">
                        <i class="fa fa-eye" onclick="passwordEyeReset('confirm_password','confirmPasswordReset')"
                           id="confirmPasswordReset"></i>
						   <i class="fa fa-eye" 
                           id="confirmPasswordReset"></i>
                        <span id="usernameError" class="error-msg13"></span>-->
                    </div>

                </div>
                <div class="col-md-12 col-sm-12 col-12 text-center">
                    <button type="submit" id="submit-check" class="btn blue_btn default_btn btn-block">
                        <!--Reset password-->Resetovat heslo
                    </button>
                    <?php echo form_close(); ?>
                </div>
            </div>

        </div>
    </div>

</div>
<script>
    var password_validation_reset_password_message = "<?php echo $this->config->item('password_validation_reset_password_message'); ?>";
    var password_characters_min_length_validation_reset_password_message = "<?php echo $this->config->item('password_characters_min_length_validation_reset_password_message'); ?>";
    var confirm_password_validation_reset_password_message = "<?php echo $this->config->item('confirm_password_validation_reset_password_message'); ?>";
    var confirm_password_characters_min_length_validation_reset_password_message = "<?php echo $this->config->item('confirm_password_characters_min_length_validation_reset_password_message'); ?>";
    var password_and_confirm_password_equal_validation_reset_password_message = "<?php echo $this->config->item('password_and_confirm_password_equal_validation_reset_password_message'); ?>";
    var email = "<?php echo $email; ?>";
    var password_min_length_character_limit_validation_reset_password = "<?php echo $this->config->item('password_min_length_character_limit_validation_reset_password'); ?>";



</script>
<script src="<?php echo JS ?>modules/reset_password.js"></script>
