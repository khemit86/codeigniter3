<section id="content">
    <div class="wrapper">
        <div class="crumb">
            <ul class="breadcrumb">
                <li class="active"><a href="<?= base_url() ?>"><i class="icon16 i-home-4"></i>Home</a></li>
                <li class="active"><a href="<?= base_url() ?>adminuser/user_list/">Admin User List</a></li>

            </ul>
        </div>

        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-list-4"></i>Admin User Management </h1>
            </div>
			<?php
			$id = $this->uri->segment(3);
            ?>
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
                            <h4>Add/Modify Knowledge </h4>
                            <a href="#" class="minimize2"></a>
                        </div><!-- End .panel-heading -->

                        <div class="panel-body">
                            <form id="validate" action="<?php echo base_url(); ?>adminuser/edit_user/<?php echo $id;?>/" class="form-horizontal" role="form" name="state" method="post">

                                <input type="hidden" name="id" value="<?php echo $id;?>" />


                                	<div class="form-group">
				<label class="col-lg-2 control-label">Username</label>
				<div class="col-lg-9">
					<input type="text" value="<?php echo $all_data['username']; ?>" name="username" class="form-control">
					<?php echo form_error('username', '<label class="error" >', '</label>'); ?>
				</div>
			</div><!-- End .control-group  -->
				
			<div class="form-group">
				<label class="col-lg-2 control-label">Email</label>
				<div class="col-lg-6">
			<input type="text" value="<?php echo $all_data['email']; ?>" name="email" class="form-control">
            <?php echo form_error('email', '<label class="error">', '</label>'); ?>
			</div>
			</div><!-- End .control-group  -->

			
                                
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="agree">Status</label>
                                    <label class="checkbox-inline">
                                        <input class="form-control" type="radio" id="status" name="status" value="Y" checked="checked" <?php if (isset($all_data['status']) && $all_data['status'] == 'Y') {
    echo "checked";
} ?> />Online<input class="form-control" <?php if (isset($all_data['status']) && $all_data['status'] == 'N') {
    echo "checked";
} ?> type="radio" id="status" name="status" Value="N">Offline
                                    </label>
                                </div>

                                <!-- End .control-group  -->
                                <div class="form-group">
                                    <div class="col-lg-offset-2">
                                        <div class="pad-left15">
                                            <input type="submit" name="submit" class="btn btn-primary" value="Add">
                                            <button type="button" onclick="redirect_to('<?php echo base_url() . 'adminuser/user_list/'; ?>');" class="btn">Cancel</button>
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
