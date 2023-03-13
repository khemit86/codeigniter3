<section id="content">
    <div class="wrapper">
        <div class="crumb">
            <ul class="breadcrumb">
                <li class="active"><a href="<?= base_url() ?>"><i class="icon16 i-home-4"></i>Home</a></li>
                <li class="active"><a href="<?= base_url() ?>membership_plan/membership_plan_list/">Membership Plan List</a></li>

            </ul>
        </div>

        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-list-4"></i>Membership Management </h1>
            </div>
            <div class="row">
                <div class="col-lg-12">
                <?php
                    if ($this->session->flashdata('succ_msg')) {
                        ?>
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="icon24 i-checkmark-circle"></i> Well done!</strong> <?= $this->session->flashdata('succ_msg') ?>
                        </div> 
                        <?php
                    }
                    if ($this->session->flashdata('error_msg')) {
                        ?>
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="icon24 i-close-4"></i> Oh snap!</strong> <?= $this->session->flashdata('error_msg') ?>
                        </div>
    <?php
}
?>
                    <div class="panel panel-default">
                        <div class="panel-heading">

                            <div class="icon"><i class="icon20 i-stack-checkmark"></i></div> 
                            <h4>Add New Membership Plan</h4>
                            <a href="#" class="minimize2"></a>
                        </div><!-- End .panel-heading -->

      <div class="panel-body">
        <form id="validate" action="<?php echo base_url(); ?>membership_plan/add_membership_plan/" class="form-horizontal" role="form" name="state" method="post" enctype="multipart/form-data">



            <div class="form-group">
				<label class="col-lg-2 control-label" for="required">Plan Name</label>
				<div class="col-lg-6">
					<input type="text" id="required" value="" name="name" class="required form-control">
					<?php echo form_error('name', '<label class="error" for="required">', '</label>'); ?>
				</div>
            </div><!-- End .control-group  -->
            
            <div class="form-group">
				<label class="col-lg-2 control-label" for="required">Plan Price</label>
				<div class="col-lg-6">
					<input type="text" id="required" value="" name="price" class="required form-control">
					<?php echo form_error('name', '<label class="error" for="required">', '</label>'); ?>
				</div>
            </div>
            
            <div class="form-group">
				<label class="col-lg-2 control-label" for="required">No of Skill</label>
				<div class="col-lg-6">
					<input type="text" id="required" value="" name="skills" class="required form-control">
					<?php echo form_error('name', '<label class="error" for="required">', '</label>'); ?>
				</div>
            </div>
            
             <div class="form-group">
				<label class="col-lg-2 control-label" for="required">No of Bids</label>
				<div class="col-lg-6">
					<input type="text" id="required" value="" name="bids" class="required form-control">
					<?php echo form_error('name', '<label class="error" for="required">', '</label>'); ?>
				</div>
            </div>
            
             <div class="form-group">
				<label class="col-lg-2 control-label" for="required">No of Portfolio</label>
				<div class="col-lg-6">
					<input type="text" id="required" value="" name="portfolio" class="required form-control">
					<?php echo form_error('name', '<label class="error" for="required">', '</label>'); ?>
				</div>
            </div> <div class="form-group">
				<label class="col-lg-2 control-label" for="required">Days</label>
				<div class="col-lg-6">
					<input type="text" id="required" value="" name="days" class="required form-control">
					<?php echo form_error('name', '<label class="error" for="required">', '</label>'); ?>
				</div>
            </div>
           
            <div class="form-group">
              <label class="col-lg-2 control-label" for="agree">Status</label>
              <label class="checkbox-inline">
              <input class="form-control" type="radio" id="status" name="status" value="Y" />Online
              <input class="form-control" type="radio" id="status" name="status" Value="N" />Offline
              </label>
            </div>

                                <!-- End .control-group  -->
                                <div class="form-group">
                                    <div class="col-lg-offset-2">
                                        <div class="pad-left15">
                                            <input type="submit" name="submit" class="btn btn-primary" value="Add">
                                            <button type="button" onclick="redirect_to('<?php echo base_url() . 'membership_plan/membership_plan_list/'; ?>');" class="btn">Cancel</button>
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