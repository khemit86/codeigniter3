<section id="content">
    <div class="wrapper">
        <div class="crumb">
            <ul class="breadcrumb">
                <li class="active"><a href="<?= base_url()?>"><i class="icon16 i-home-4"></i>Home</a></li>
               <li class="active"><a onclick="redirect_to('<?php echo base_url() . 'hourlyrate'; ?>');">Hourly rate list</a> </li>
            <li class="active">Add hourly rate</a></li>
            </ul>
        </div>
        <div class="alert alert-error" id="error_max" style="display: none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong><i class="icon24 i-close-4"></i> Oh snap!</strong> Value max is required!
        </div>
        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-list-4"></i> Add hourly rate </h1>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="icon"><i class="icon20 i-stack-checkmark"></i></div> 
                            <h4>Add hourly rate</h4>
                            <a href="#" class="minimize2"></a>
                        </div><!-- End .panel-heading -->

                        <div class="panel-body"> 
                            <?php echo validation_errors('<div class=" red alert ">', '</div>'); ?>

                            <form id="validate" action="<?php echo base_url(); ?>hourlyrate/add" class="form-horizontal" role="form" name="hourlyrate" method="post">
                                
                                <div class="form-group">
                                    <label for="radio" class="col-lg-2 control-label">Value Type</label>
                                    <label class="radio-inline">
                                      <div class="radio"><span>
                                              <input type="radio" onclick="choose_tyle_();" id="type" name="type" value="0"  checked="checked">
                                          </span>
                                      </div> Between and
                                    </label>
                                    <label class="radio-inline">
                                      <div class="radio"><span class="checked">
                                              <input <?php echo set_checkbox('type', '1'); ?> id="type" type="radio" name="type" onclick="choose_tyle();" value="1" >
                                          </span>
                                      </div> More then
                                    </label>               
                                </div>
                                                            
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="required">Min</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="required" value="<?php echo set_value('min'); ?>" name="min" class="required form-control">

                                    </div>
                                </div><!-- End .control-group  -->
                                
                                <div class="form-group" id="hid_max">
                                    <label class="col-lg-2 control-label" for="required">Max</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="required" value="<?php echo set_value('max'); ?>" name="max" class="max required form-control">

                                    </div>
                                </div><!-- End .control-group  -->
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
<script type="text/javascript">     
    
    function choose_tyle_(){
        $(this).prop("checked",true);
        $('#hid_max').show(1000);  
        $('.max').val('');      
    }

    function choose_tyle(){        
        $(this).prop("checked",true);                
        $('#hid_max').hide(1000);   
        $('.max').val('All');     
    }
</script>