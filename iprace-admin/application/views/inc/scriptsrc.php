<!DOCTYPE html>
<html lang="en">
    <?php
      $controller_name = $this->router->fetch_class();
    ?>
    <head>
     <script> SITE_URL = "<?php echo SITE_URL; ?>";</script>
        <meta charset="utf-8">
        <title>Travai Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="SuggeElson" />
        <meta name="description" content="Travai Admin" />
        <meta name="keywords" content="Travai Admin" />
        <meta name="application-name" content="Travai Admin" />

        <!-- Headings -->
        <link href='<?php echo CSS ?>OpenSans400_800_700.css' rel='stylesheet' type='text/css'>
        <!-- Text -->
        <link href='<?php echo CSS ?>DroidSans400_700.css' rel='stylesheet' type='text/css' />

        <!-- Fav and touch icons -->
        <!-- <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo IMAGE ?>ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo IMAGE ?>ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo IMAGE ?>ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="<?php echo IMAGE ?>ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="<?php echo IMAGE ?>ico/favicon.png"> -->


        <!-- Core stylesheets do not remove -->
        <link href="<?php echo CSS ?>bootstrap/bootstrap.min.css" rel="stylesheet" />
        <link href="<?php echo CSS ?>bootstrap/bootstrap-theme.min.css" rel="stylesheet" />
        <link href="<?php echo CSS ?>icons.css" rel="stylesheet" />

         <!-- app stylesheets -->
        <link href="<?php echo JS ?>plugins/forms/uniform/uniform.default.css" rel="stylesheet" />
        <link href="<?php echo CSS ?>app.css" rel="stylesheet" />

        <!-- Custom stylesheets ( Put your own changes here ) -->
        <link href="<?php echo CSS ?>custom.css" rel="stylesheet" />
        
        <script src="<?php echo SITE_URL;?>assets/js/jquery.min.js"></script>
        <script src="<?php echo JS ?>bootstrap/bootstrap.js"></script>
        <script src="<?php echo JS ?>plugins/core/nicescroll/jquery.nicescroll.min.js"></script>

        <?php 
          if(in_array($controller_name, ['dashboard'])) {
        ?>
          <link href="<?php echo CSS ?>ui/jquery.ui.all.css" rel="stylesheet" />
          <!-- Important plugins put in all pages -->
          <script src="<?php echo JS ?>jquery-ui-1.9.2.min.js"></script>
          <script src="<?php echo JS ?>plugins/charts/sparklines/jquery.sparkline.min.js"></script>
          <!-- Misc plugins -->
          <script src="<?php echo JS ?>plugins/misc/fullcalendar/fullcalendar.min.js"></script>
        <?php
          }
        ?>

        
        <?php 
          if(in_array($controller_name, ['member']) || in_array($controller_name, ['project'])){
        ?>
          <link href="<?php echo CSS ?>cdn/fontawesome_5.13.0_all.css" rel="stylesheet" />
          <link href="<?php echo CSS ?>modules/member.css" rel="stylesheet" />
        <?php
          }
        ?>
        
        <script>
          var action = '<?php echo $this->uri->segment(1); ?>';
        </script>
        
        <script src="<?php echo JS ?>plugins/forms/uniform/jquery.uniform.min.js"></script>
        <script src="<?php echo JS ?>plugins/core/jrespond/jRespond.min.js"></script>
        <script src="<?php echo JS ?>jquery.genyxAdmin.js"></script>
        <script src="<?php echo JS ?>app.js"></script>
        
    </head>
