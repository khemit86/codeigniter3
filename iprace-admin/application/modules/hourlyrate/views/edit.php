<section id="content">
    <div class="wrapper">
        <div class="crumb">
            <ul class="breadcrumb">
                <li class="active"><a href="<?= base_url()?>"><i class="icon16 i-home-4"></i>Home</a></li>
                <li class="active"><a onclick="redirect_to('<?php echo base_url() . 'hourlyrate'; ?>');">Hourly rate list</a> </li>
                <li class="active">Modify Hourly Rate</a></li>
            </ul>
        </div>

        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-list-4"></i> Add/Modify Hourly Rate </h1>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="icon"><i class="icon20 i-stack-checkmark"></i></div> 
                            <h4>Add/Modify Hourly Rate</h4>
                            <a href="#" class="minimize2"></a>
                        </div><!-- End .panel-heading -->

                        <div class="panel-body">
                        	<?php echo validation_errors('<div class=" red alert ">', '</div>'); ?>
                            <form id="validate" action="<?php echo base_url(); ?>hourlyrate/edit/" class="form-horizontal" role="form" name="hourlyrate" method="post">
                                <input type="hidden" value="<?php echo $id ?>" name="id">
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="required">Min</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="required" value="<?php echo $min; ?>" name="min" class="required form-control">                                       
                                    </div>
                                </div><!-- End .control-group  -->
                                
                                <?php if($max != "All"){ ?>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="required">Max</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="required" value="<?php echo $max; ?>" name="max" class="required form-control">                                       
                                    </div>
                                </div><!-- End .control-group  -->                                                           
                                <?php } ?>
                                <div class="form-group">
                                    <div class="col-lg-offset-2">
                                        <div class="pad-left15">
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                            <button type="button" onclick="redirect_to('<?php echo base_url() . 'hourlyrate'; ?>');" class="btn">Cancel</button>
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
