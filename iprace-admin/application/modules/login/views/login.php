<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <!-- title>Travai Admin</title -->
    <!-- title></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="author" content="SuggeElson"/>

    <meta name="description" content="Travai Admin"/>

    <meta name="keywords" content="Travai Admin"/>

    <meta name="application-name" content="Travai Admin"/ -->


    <!-- Headings -->

    <link href='<?= CSS ?>OpenSans400_800_700.css' rel='stylesheet' type='text/css'>

    <!-- Text -->

    <link href='<?= CSS ?>DroidSans400_700.css' rel='stylesheet' type='text/css'/>


    <!--[if lt IE 9]>

    <link href="<?=CSS?>OpenSans400.css" rel="stylesheet" type="text/css"/>

    <link href="<?=CSS?>OpenSans700.css" rel="stylesheet" type="text/css"/>

    <link href="<?=CSS?>OpenSans800.css" rel="stylesheet" type="text/css"/>

    <link href="<?=CSS?>DroidSans400.css" rel="stylesheet" type="text/css"/>

    <link href="<?=CSS?>DroidSans700.css" rel="stylesheet" type="text/css"/>

    <![endif]-->


    <!-- Core stylesheets do not remove -->

    <link href="<?= CSS ?>bootstrap/bootstrap.min.css" rel="stylesheet"/>

    <link href="<?= CSS ?>bootstrap/bootstrap-theme.min.css" rel="stylesheet"/>

    <link href="<?= CSS ?>icons.css" rel="stylesheet"/>


    <!-- Plugins stylesheets -->

    <link href="<?= JS ?>plugins/forms/uniform/uniform.default.css" rel="stylesheet"/>


    <!-- app stylesheets -->

    <link href="<?= CSS ?>app.css" rel="stylesheet"/>


    <!-- Custom stylesheets ( Put your own changes here ) -->

    <link href="<?= CSS ?>custom.css" rel="stylesheet"/>


    <!--[if IE 8]>
    <link href="css/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->


    <!-- Force IE9 to render in normal mode -->

    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->

    <!--[if lt IE 9]>

    <script src="js/html5shiv.js"></script>

    <script src="js/respond.min.js"></script>

    <![endif]-->


    <!-- Fav and touch icons -->

    <!-- <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?= IMAGE ?>ico/apple-touch-icon-144-precomposed.png">

    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?= IMAGE ?>ico/apple-touch-icon-114-precomposed.png">

    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?= IMAGE ?>ico/apple-touch-icon-72-precomposed.png">

    <link rel="apple-touch-icon-precomposed" href="<?= IMAGE ?>ico/apple-touch-icon-57-precomposed.png">

    <link rel="shortcut icon" href="<?= IMAGE ?>ico/favicon.png"> -->


    <!-- Le javascript

    ================================================== -->

    <!-- Important plugins put in all pages -->

    <script src="<?php echo SITE_URL;?>assets/js/jquery.min.js"></script>

    <script src="<?php echo JS ?>bootstrap/bootstrap.js"></script>
    <!-- <script src="<?= JS ?>conditionizr.min.js"></script> -->

    <script src="<?= JS ?>plugins/core/nicescroll/jquery.nicescroll.min.js"></script>

    <script src="<?= JS ?>plugins/core/jrespond/jRespond.min.js"></script>

    <script src="<?= JS ?>jquery.genyxAdmin.js"></script>


    <!-- Form plugins -->

    <script src="<?= JS ?>plugins/forms/uniform/jquery.uniform.min.js"></script>

    <script src="<?= JS ?>plugins/forms/validation/jquery.validate.js"></script>

    <script src="<?= JS ?>jquery.form.js"></script>

    <!-- Init plugins -->

    <script src="<?= JS ?>app.js"></script>
    <!-- Core js functions -->

    <script src="<?= JS ?>pages/login.js"></script>
    <!-- Init plugins only for page -->


</head>

<body>


<div class="container-fluid">
<?php

// print_r($_SESSION);

?>
    <div id="login">

        <div class="login-wrapper" data-active="log">


            <!-- a class="navbar-brand" href="dashboard.html" -->

                <!-- h2 class="center"><?= ucwords('Travai') ?> Administration</h2></a -->
                <!-- commented Catalin 27.09.2020) -->

            <div id="log">

                <!-- div id="avatar">

                    <img style="width:78px; height:78px;" src="<?php echo SITE_URL . "assets/images/user1.png"; ?>" alt="SuggeElson" class="img-responsive">

                </div -->

                <!-- div class="page-header">


                    <h3 class="center">Please login</h3 --> <!-- commented Catalin 27.09.2020)>

                </div -->

                <?php

				echo validation_errors('<label class="error">', '</label>'); 
				
				if(isset($error) && !empty($error) && isset($ch_res['status']) && !empty($ch_res['status'])){
					// echo "<label class='error' id='previewregister'>" . $error .' :::: '.$ch_res['status']. "</label>";
					echo "<label class='error' id='previewregister'>" . $error ."</label>";
				}
                $attributes = ['role' => 'form', 'id' => 'login-form', 'class' => 'form-horizontal'];

                echo form_open('login/loginprocess', $attributes);


                $remember = '';

             

                    if (isset($username) && $username != '' && $password != '') {

                        $remember = 'checked="checked"';

                    }


                    if (set_value('username') != '') {

                        $username = set_value('username');

                    }


                    if (set_value('password') != '') {

                        $password = set_value('password');

                    }

                ?>


                <div class="row">

                    <div class="form-group relative">

                        <div class="icon"><i class="icon20 i-user"></i></div>

                        <input class="form-control" type="text" name="username" id="username" placeholder="Username" value="<?php echo $username; ?>">


                    </div>
                    <!-- End .control-group  -->

                    <div class="form-group relative">

                        <div class="icon"><i class="icon20 i-key"></i></div>

                        <input class="form-control" type="password" name="password" id="password" placeholder="Password" value="<?php echo $password; ?>">

                        <input type="hidden" name="login_val" id="login_val" value="0"/>

                    </div>
                    <!-- End .control-group  -->

                    <div class="form-group relative">

                        <!-- label class="control-label" class="checkbox pull-left">

                            <input type="checkbox" value="1" name="remember" <?php echo $remember; ?>>

                            Remember me ?

                        </label -->

                        <button id="loginBtn" type="submit" class="btn btn-primary pull-right col-lg-5">Login</button>

                    </div>

                </div>
                <!-- End .row-fluid  -->

                </form>


            </div>

            <div id="forgot">


                <!-- div id="avatar">

                    <img style="width:78px; height:78px;" src="<?php echo SITE_URL . "assets/images/user1.png"; ?>" alt="SuggeElson" class="img-responsive">

                </div --><!-- commented Catalin 27.09.2020) -->

                <!-- div class="page-header">


                    <h3 class="center">Forgot password</h3>

                </div -->

                <?php

                echo validation_errors('<label class="error">', '</label>');

                echo "<label class='error' id='previewrforgot'>" . $error . "</label>";


                $attributes = ['id' => 'forgot-form', 'class' => 'form-horizontal'];

                echo form_open('login/forgotpass', $attributes);

                ?>


                <div class="row">

                    <div class="form-group relative">

                        <div class="icon"><i class="icon20 i-user"></i></div>

                        <input class="form-control" type="text" name="user" id="user" placeholder="Username">

                    </div>
                    <!-- End .control-group  -->

                    <div class="form-group relative">

                        <div class="icon"><i class="icon20 i-envelop-2"></i></div>

                        <input class="form-control" type="text" name="email" id="email-field" placeholder="Your email">

                        <input type="hidden" name="forgot_val" id="forgot_val" value="0"/>

                    </div>
                    <!-- End .control-group  -->

                    <div class="form-group">

                        <button type="submit" id="forgotBtn" class="btn btn-lg btn-block btn-primary">Recover my
                            password
                        </button>

                    </div>

                </div>
                <!-- End .row-fluid  -->

                </form>

            </div>

        </div>

        <div id="bar" data-active="log">

            <div class="btn-group btn-group-vertical">

                <a id="log" href="#" class="btn tipR" title="Login"><i class="icon16 i-key"></i></a>


                <a id="forgot" href="#" class="btn tipR" title="Forgout password"><i class="icon16 i-question"></i></a>

            </div>

        </div>

        <div class="clearfix"></div>

    </div>

</div>

</body>

</html>