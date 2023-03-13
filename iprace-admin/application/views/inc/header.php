<body>
    <header id="header">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <a class="navbar-brand" href="<?=VPATH?>"><h1><span style="color:#6C6"><?=ucwords("Travai")?></span> Administration</h1></a>            
            <button type="button" class="navbar-toggle btn-danger" data-toggle="collapse" data-target="#navbar-to-collapse">
                <span class="sr-only">Toggle right menu</span>
                <i class="icon16 i-arrow-8"></i>
            </button>          
            <div class="collapse navbar-collapse" id="navbar-to-collapse">  
                
                <ul class="nav navbar-nav pull-right">
                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                    </li>
                    <li class="divider-vertical"></li>
                  
                    <li class="divider-vertical"></li>
                    <li class="dropdown user">
                         <a href="#" class="dropdown-toggle avatar" data-toggle="dropdown">
                           
							 <!-- <img src="<?=IMAGE?>admin.png"  > -->
                            <span class="more"><i class="icon16 i-arrow-down-2"></i></span>
                        </a>
						
						
                        <ul class="dropdown-menu" role="menu">
						
                           <!-- <li role="presentation"><a href="<?php echo base_url();?>adminsettings" class=""><i class="icon16 i-cogs"></i> Settings</a></li>-->
						    <li role="presentation"><a href="<?=SITE_URL?>" target="_blank" class=""><i class="icon20 i-earth"></i> View Site</a></li>
							
                            <li role="presentation"><a href="<?=VPATH?>logout" class=""><i class="icon16 i-exit"></i> Logout</a></li>
                        </ul>
                    </li>
                    <li class="divider-vertical"></li>
                </ul>
            </div><!--/.nav-collapse -->
        </nav>
    </header> <!-- End #header  -->
    
    <div class="main">
