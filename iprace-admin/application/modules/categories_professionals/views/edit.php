<section id="content">
    <div class="wrapper">
        <div class="crumb">
            <ul class="breadcrumb">
                <li class="active"><a href="<?= base_url()?>"><i class="icon16 i-home-4"></i>Home</a></li>
                <li class="active"><a onclick="redirect_to('<?php echo base_url() . 'categories_professionals/'; ?>');">Talents Category list</a> </li>
                <li class="active">Modify Talents Category</a></li>
            </ul>
        </div>

        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-list-4"></i> Add/Modify Talents Category </h1>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="icon"><i class="icon20 i-stack-checkmark"></i></div> 
                            <h4>Add/Modify Talents Category</h4>
                            <a href="#" class="minimize2"></a>
                        </div><!-- End .panel-heading -->

                        <div class="panel-body">
                        	<?php echo validation_errors('<div class=" red alert ">', '</div>'); ?>
                            <form id="validate" action="<?php echo base_url(); ?>categories_professionals/edit/" class="form-horizontal" role="form" name="adminmenu" method="post">


                                <?php if ($parent_name != '') { ?>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label" for="required">Parent Name </label>
                                        <div class="col-lg-6">
                                            <input type="text" readonly="readonly" value="<?php echo $parent_name; ?>" name="parent_name" class=" form-control">

                                            <input type="hidden" name="parent" value="1" />
                                        </div>
                                    </div>
                                <?php } ?>
                                <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
                                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                               
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="required">Name</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="required" value="<?php echo $name; ?>" name="name" class="required form-control avoid_space">
                                       
                                    </div>
                                </div><!-- End .control-group  -->
                               <?php
							   if($parent_id == 0)
							   {
							   ?>
                                <div class="form-group">
                                            <label for="radio" class="col-lg-2 control-label">Status</label>
                                            <label class="radio-inline">
                                                <div class="radio"><span><input type="radio" id="status" name="status" value="Y" checked="checked"  /></span></div> Active
                                            </label>
                                            <label class="radio-inline">
                                                <div class="radio"><span class="checked"><input <?php if ($status == 'N') { echo 'checked'; } ?> type="radio" id="status" name="status" Value="N"></span></div> Inactive
                                            </label>                                           
                                        </div>
								<?php
								}else{
									if($parent_status == 'Y')
									{
									?>
									 <div class="form-group">
										<label for="radio" class="col-lg-2 control-label">Status</label>
										<label class="radio-inline">
											<div class="radio"><span><input type="radio" id="status" name="status" value="Y" checked="checked"  /></span></div> Active
										</label>
										<label class="radio-inline">
											<div class="radio"><span class="checked"><input <?php if ($status == 'N') { echo 'checked'; } ?> type="radio" id="status" name="status" Value="N"></span></div> Inactive
											</label>                                           
									</div>
									<?php
									}
								}
								?>
                                <div class="form-group">
                                    <div class="col-lg-offset-2">
                                        <div class="pad-left15">
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                            <button type="button" onclick="redirect_to('<?php echo base_url() . 'categories_professionals/'; ?>');" class="btn">Cancel</button>
                                        </div>
                                    </div>
                                </div><!-- End .form-group  -->

                            </form>
                        </div><!-- End .panel-body -->
                    </div><!-- End .widget -->
                </div><!-- End .col-lg-12  --> 
            </div><!-- End .row-fluid  -->

        </div> <!-- End .container-fluid  -->
    </div> <!-- End .wrapper  -->
</section>
