  <section id="content">
            <div class="wrapper">
                <div class="crumb">
                   <!-- <ul class="breadcrumb">
                      <li><a href="#"><i class="icon16 i-home-4"></i>Home</a></li>
                      <li><a href="#">Library</a></li>
                      <li class="active">Data</li>
                    </ul>-->
                </div>
                
                <div class="container-fluid">
                    <div id="heading" class="page-header">
                        <h1><i class="icon20 i-dashboard"></i> Dashboard</h1>
                    </div>
                    
                <!--    <div class="row">
                        <div class="col-lg-8">
                            <div class="panel panel-default">
                         <div class="panel-heading">
                                    <div class="icon"><i class="icon20 i-stats"></i></div>
                                    <h4>Newsletter Statistic</h4>
                                    <a href="#" class="minimize"></a>
                                </div><!-- End .panel-heading -->
                            
                             <!--   <div class="panel-body">

                                    <div class="chart" style="width: 100%; height:250px; margin-top: 10px;">
                                        
                                    </div>

                                    <div class="campaign-stats center">
                                        <div class="items">
                                            <div class="percentage" data-percent="100"><span>357</span></div>
                                            <div class="txt">Total</div>
                                        </div>
                                        <div class="items">
                                            <div class="percentage" data-percent="78"><span>78</span>%</div>
                                            <div class="txt">Opens</div>
                                        </div>
                                        <div class="items">
                                            <div class="percentage" data-percent="42"><span>42</span>%</div>
                                            <div class="txt">Clicks</div>
                                        </div>
                                        <div class="items">
                                            <div class="percentage" data-percent="17"><span>17</span>%</div>
                                            <div class="txt">Bounces</div>
                                        </div>
                                        <div class="items red">
                                            <div class="percentage-red" data-percent="2"><span>2</span>%</div>
                                            <div class="txt">Unsubscribes</div>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                    
                               <!-- End .panel-body -->
                         <!--   </div>
							 </div>
								<div class="items red">
								<div class="percentage-red" style="float:right;" data-percent="2">
								<div class="txt" >Unregistered Fabtask Rabbits (<?echo $count;?>)</div>
								<input type="button" value="view rabbit" name="view_rabbit" 
								onclick="redirect_to('<?php echo base_url().'rabbit'; ?>');" />
								</div>
								</div>
								<div class="items red">
								<div class="percentage-red" style="float:right;" data-percent="2">
								<div class="txt" >registered Fabtask Rabbits (<?echo $count_reg;?>)</div>
								<input type="button" value="view rabbit" name="view_rabbit" 
								onclick="redirect_to('<?php echo base_url().'rabbit/reg_user'; ?>');" />
								</div>
								</div>
								<div class="items red">
								<div class="percentage-red" style="float:right;" data-percent="2">
								<div class="txt" >Posted task (<?echo $post_task;?>)</div>
								<input type="button" value="view tasks" name="view_rabbit" 
								onclick="redirect_to('<?php echo base_url().'task/getpost'; ?>');" />
								</div>
								</div>-->
								
							<div class="row">
							<div class="col-lg-6">
                            <div class="panel plain">
                                <div class="panel-heading">
                                    <i class="icon20 i-mail-send"></i>
                                    <h4>Overview</h4>
                                    <a href="#" class="minimize"></a>
                                </div><!-- End .panel-heading -->
                            
                                <div class="panel-body center">
                                    <div class="stats-buttons">
                                        <ul class="list-unstyled">
										 <li>
                                                <a href="<?php echo base_url().'member/page/premium'; ?>" class="clearfix">
                                                    <span class="icon green"><i class="icon24 i-user-plus"></i></span>
                                                    <span class="number"><?echo $p_count;?></span>
                                                    <span class="txt">Premium user</span>
                                                </a>
                                            </li>
                                            <li>
                                              <a href="<?php echo base_url().'member/page/free'; ?>" class="clearfix">
                                                    <span class="icon yellow"><i class="icon24 i-user-plus"></i></span>
                                                    <span class="number"><?echo $f_count;?></span>
                                                    <span class="txt">Free user</span>
                                                </a>
                                            </li>
											<li>
                                                <a href="<?php echo base_url().'member/page/lifetime'; ?>" class="clearfix">
                                                    <span class="icon blue"><i class="icon24 i-user-plus"></i></span>
                                                    <span class="number"><?echo $l_count;?></span>
                                                    <span class="txt">Lifetime user</span>
                                                </a>
                                            </li>
											
											<li>
                                    
                                           
                                        </ul>
                                    </div><!-- End .stats-buttons  -->
                                    <div class="clearfix"></div>
                                </div><!-- End .panel-body -->
                            </div><!-- End .widget -->
                        </div><!-- End .col-lg-6  -->
                    <!-- End .col-lg-8  --> 
                           <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="icon"><i class="icon20 i-file-8"></i></div>
                                    <h4>Locations</h4>
                                    <a href="#" class="minimize"></a>
                                </div><!-- End .panel-heading -->
                            
                                <div class="panel-body">

                                    <div class="toDo">
                                      <!--  <h4 class="period">City </h4>-->
                                        <ul class="todo-list">
                                            <li class="task-item clearfix">
                                               <span class="priority medium tip" title="Medium priority"><i class="icon12 i-circle-2"></i></span>
                                                <span class="label category"> <? echo $count_city;?> </span>
                                                <span class="task">
                                                   Cities
                                                </span>
                                                <a href="#" class="act"><i class="icon12 i-close-2"></i></a>
                                            </li>
                                            <li class="task-item clearfix">
                                                
                                                <span class="priority high tip" title="High priority"><i class="icon12 i-circle-2"></i></span>
                                                <span class="label label-info category"> <? echo $count_state;?> </span>
                                                <span class="task">
                                                    States
                                                </span>
                                                <a href="#" class="act"><i class="icon12 i-close-2"></i></a>
                                            </li>
                                            <li class="task-item clearfix">
                                              
                                                <span class="priority normal tip" title="Normal priority"><i class="icon12 i-circle-2"></i></span>
                                                <span class="label label-inverse category"> <? echo $count_coun;?> </span>
                                                <span class="task">
                                                   Countries
                                                </span>
                                                <a href="#" class="act"><i class="icon12 i-close-2"></i></a>
                                            </li>
                                        </ul>
                                        <!--<h4 class="period">Tomorrow</h4>
                                        <ul class="todo-list">
                                             <li class="task-item clearfix">
                                               
                                                <span class="priority tip" title="Priority none"><i class="icon12 i-circle-2"></i></span>
                                                <span class="label label-important category"> php </span>
                                                <span class="task">
                                                    Create category controller
                                                </span>
                                                <a href="#" class="act"><i class="icon12 i-close-2"></i></a>
                                            </li>
                                        </ul>-->
                                    </div>
                                    
                                </div><!-- End .panel-body -->
                            </div><!-- End .widget -->
                        </div><!-- End .col-lg-4  --> 

                    </div><!-- End .row-fluid  -->

                </div> <!-- End .container-fluid  -->
            </div> <!-- End .wrapper  -->
        </section>
   