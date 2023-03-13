<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		 <?php
            if (isset($meta_tag)) {
                echo $meta_tag;
            } else {
				echo "travai";
			}
        ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">  
		<!-- Favicons -->
        <link rel="shortcut icon" href="<?php echo SITE_URL; ?>assets/favicon/logo.png">
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="<?php echo ASSETS?>bootstrap-4/dist/css/bootstrap.css">
		<!-- Local CSS -->
		<link rel="stylesheet" href="<?php echo ASSETS?>css/main.css">
		<link rel="stylesheet" href="<?php echo ASSETS?>css/error_default_404_page.css">
		<!-- JS -->
        <script src="<?php echo ASSETS;?>js/cdn/jquery-3.3.1.js"></script>
        <link rel="stylesheet" href="<?= CSS ?>cdn/fontawesome_5.0.13_all.css"/>
        <script src="<?php echo ASSETS;?>bootstrap-4/dist/js/bootstrap.js"></script>
	</head>

	<body>
		<!-- Header Start -->
		<div id="header" style="visibility:visible;">		
			<nav id="headerContent" class="navbar fixed-top navbar-expand-lg navbar-light bg-primary hTop appView404">				
				<a class="navbar-brand navLogo" href="/"><img style="" src="<?php echo  ASSETS ?>images/logo.png"></a>
				<div id="mainmenu" class="menuIcon expand">
					<i class="fas fa-bars"></i>
				</div>
						
				<div class="navbar-collapse aftHead" id="myNavbar">
					<ul class="navbar-nav mr-auto headerFP_mobile">
						<li class="nav-item">
							<button class="btn default_btn blue_btn browse_find_project"><?php echo $this->config->item('browse_projects_txt') ?> <i class="fas fa-tasks"></i></button>
						</li>
						<li class="nav-item">
							<button class="btn default_btn purple_btn browse_find_professionals" ><?php echo $this->config->item('browse_service_providers_txt') ?> <i class="fas fa-id-card"></i></button>
						</li>
					</ul>
				</div>
			</nav>
		</div>
		<!-- Header End -->
		 <script>
		 //start header bottom border come when scroll
		$(document).scroll(function(){
			if($(this).scrollTop() > 1) { 
				$('#headerContent').addClass('hTop_bottomBdr');
			} else {				
				$('#headerContent').removeClass('hTop_bottomBdr');
			}
		});
		//end header bottom border come when scroll
		 $(document).click(function (e) {
                //top mobilemenu close when click on outside log out.
                if($('#myNavbar.active').is(':visible') && !$(e.target).closest('#mainmenu.close').length && !$(e.target).closest('#myNavbar.active').length) {
                    $('#myNavbar').toggleClass('active');
                    $('#myNavbar').removeClass('d-block').addClass('d-none');
                    $("#mainmenu.close").removeClass('close').addClass('expand');
                    $("#mainmenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
                    $('#myNavbar ul').hide();
                }
            });
		//topmenu open mobile view logout
            $("#mainmenu").click(function(){
                $('#myNavbar').toggleClass('active');
                var mv = $("#mainmenu").hasClass('expand');
                if(mv===true) {
                    $('#myNavbar').removeClass('d-none').addClass('d-block');
                    $("#mainmenu.expand").removeClass('expand').addClass('close');
                    $("#mainmenu.close i").removeClass('fas fa-bars').addClass('fas fa-times');
                    $('#myNavbar ul').fadeIn("slow");
                    if($(window).outerWidth()>744 && $(window).outerWidth()<=1150) {
                        $('.headerFP_mobile').hide();
                    }
                } else {
                    $('#myNavbar').removeClass('d-block').addClass('d-none');
                    $("#mainmenu.close").removeClass('close').addClass('expand');
                    $("#mainmenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
                    $('#myNavbar ul').hide();
                }
            });
            $(window).resize(function() {
                $('#myNavbar').removeClass('active');
                $('#myNavbar').removeClass('d-block').addClass('d-none');
                $("#mainmenu.close").removeClass('close').addClass('expand');
                $("#mainmenu.expand i").removeClass('fas fa-times').addClass('fas fa-bars');
                $('#myNavbar ul').hide();
                
                if($(window).outerWidth()>744 && $(window).outerWidth()<=1150) {
                    $('.headerFP_mobile').hide();
                }
                if($(window).outerWidth()>744) {
                    $('#myNavbar ul').show();
                }
                console.log($(window).outerWidth());
                
            });
            $(window).ready(function() {
                /*if($(window).outerWidth()>=991 && $(window).outerWidth()<=550) {
                    $('#myNavbar ul').hide();
                }
                if($(window).outerWidth()>645 && $(window).outerWidth()<=550) {
                    $('.headerFP_mobile').hide();
                }
                if($(window).outerWidth()>550) {
                    $('#myNavbar ul').show();
                }*/
                
                //console.log($(window).outerWidth());
            });
            //end of logout top menu
			/* this script is used for redirect to find project page */
	$(document).on('click', '.browse_find_project', function () {
		window.location.href = "<?php echo $this->config->item('find_projects_page_url') ?>";
	});

	/* this script is used for redirect to find professionals page */
	$(document).on('click', '.browse_find_professionals', function () {
		window.location.href = "<?php echo $this->config->item('find_professionals_page_url') ?>";
	}); 
			</script>