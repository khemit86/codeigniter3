
<section id="content">
<div class="wrapper">
<div class="crumb">
<ul class="breadcrumb">
<li class="active"><a href="<?= base_url()?>"><i class="icon16 i-home-4"></i>Home</a></li>
<!--<li class="active"><a onclick="redirect_to('<?php //echo base_url() . 'state'; ?>');">State List</a></li>-->
<li class="active">Modify Paasword</li>
</ul>
</div>

<div class="container-fluid">
<div id="heading" class="page-header">
	<h1><i class="icon20 i-list-4"></i>Password Setting Management </h1>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default"> 
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-stack-checkmark"></i></div> 
				<h4>Modify Password </h4>
				<a href="#" class="minimize2"></a>
			</div><!-- End .panel-heading -->
		     <?php
                        if ($this->session->flashdata('succ_msg')) {
                            echo '<div class="succ_msg alert-success">';
                            echo $this->session->flashdata('succ_msg');
                            echo '</div>';
                        }
                        if ($this->session->flashdata('error_msg')) {
                            echo '<div class="alert-error error_msg">';
                            echo $this->session->flashdata('error_msg');
                            echo '</div>';
                        }
                        ?>
		
		
		
		
		
		
			<div class="panel-body">
				<form id="validate" action="<?php echo base_url(); ?>settings/pass_edit/<?php echo $id;?>/" class="form-horizontal" role="form" name="password" method="post">
			   	<?php
                $user=$this->auto_model->getFeild('username','admin','admin_id',$id);
				?>
                <div class="form-group">
						<label class="col-lg-2 control-label" for="required">Userename</label>
						<div class="col-lg-6">
							
							<input type="text" id="required" name="username" class="form-control" value="<?php echo ucwords($user);?>" readonly="readonly">
							
						</div>
					</div><!-- End .control-group  -->
			   
			      <div class="form-group">
						<label class="col-lg-2 control-label" for="required">Old Password</label>
						<div class="col-lg-6">
							
							<input type="password" id="required" name="old_pass" class="required form-control">
							<?php echo form_error('old_pass', '<label class="error" for="required">', '</label>'); ?>
						</div>
					</div><!-- End .control-group  -->
			   
			         <div class="form-group">
						<label class="col-lg-2 control-label" for="required">New Password</label>
						<div class="col-lg-6">
							
							<input type="password" id="required" name="new_pass" class="required form-control">
							<?php echo form_error('new_pass', '<label class="error" for="required">', '</label>'); ?>
						</div>
					</div>
			   
					<!--<div class="form-group">
						<label class="col-lg-2 control-label" for="required">Confirm Password</label>
						<div class="col-lg-6">
							
							<input type="text" id="required" name="con_pass" class="required form-control">
							<?php //echo form_error('con_pass', '<label class="error" for="required">', '</label>'); ?>
						</div>
					</div>--><!-- End .control-group  -->
				
					<div class="form-group">
						<div class="col-lg-offset-2">
							<div class="pad-left15">
							<button type="submit" name="change_pass" class="btn btn-primary">Change Password</button>
								<button type="button" onclick="redirect_to('<?php echo base_url().'settings/pass_edit'; ?>');" class="btn">Cancel</button>
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
