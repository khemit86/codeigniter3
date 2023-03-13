
<section id="content">
<div class="wrapper">
<div class="crumb">
<ul class="breadcrumb">
<li><a href="#"><i class="icon16 i-home-4"></i>Home</a></li>
<li><a href="#">Admin settings</a></li>
<li class="active">modify</li>
</ul>
</div>

<div class="container-fluid">
<div id="heading" class="page-header">
<h1><i class="icon20 i-list-4"></i>settings</h1>
</div>


<div class="row">

<div class="col-lg-12">
	<div class="panel panel-default">
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
		<div class="panel-heading">
			<div class="icon"><i class="icon20 i-stack-checkmark"></i></div> 
			<h4>Modify admin settings</h4>
			<a href="#" class="minimize2"></a>
		</div><!-- End .panel-heading -->
	
		<div class="panel-body">
		
		<?php echo validation_errors('<div class=" red alert ">', '</div>'); ?>
			<form id="validate" action="<?php echo base_url(); ?>adminsettings/index" class="form-horizontal" role="form" name="user" method="post">
		   
			<?php 
				
			  //$ses_data =$this->session->userdata('user');
			 //echo $id = $ses_data->admin_id.'=this is user id';
			  
			?>
			
			
			<input type="hidden" name="admin_id" value="<?php $ses_data =$this->session->userdata('user');
			 echo $id = $ses_data->admin_id;?>" />
			
			  
			   
		   
		        
		    
			   <div class="form-group">
					<label class="col-lg-2 control-label" for="required">Username</label>
					<div class="col-lg-6">
						
						<input type="text" id="required" value="<?php echo $username; ?>"  name="user_name" class="required form-control">
						
					</div>
				</div><!-- End .control-group  -->
			
			
				
				<div class="form-group">
					<label class="col-lg-2 control-label" for="required">Email</label>
					<div class="col-lg-6">
						
						<input type="text" id="required" value="<?php echo $email; ?>"  name="email" class="required form-control">
						
					</div>
				</div>
				
				
				
				<div class="form-group">
					<label class="col-lg-2 control-label">Old Password</label>
					<div class="col-lg-3">
						
						<input type="password" name="oldpass"  class=" form-control">
						
						
						
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-lg-2 control-label">New Password</label>
					<div class="col-lg-3">
						
						<input type="password" name="newpass"  class="form-control">
						
						
						
					</div>
				</div>
                
                <div class="form-group">
					<label class="col-lg-2 control-label">Confirm Password</label>
					<div class="col-lg-3">
						
						<input type="password" name="confpass"  class=" form-control"/>
						
						
					</div>
				</div>
				
				
				
				
				
				
				
				 <div class="form-group">
					<label class="col-lg-2 control-label" for="required">Registration Date</label>
					<div class="col-lg-3">
						
					<input type="text" id="required"  value="<?php echo $reg_date; ?>" readonly  name="does_task" class="required form-control">	
						
						
					</div>
				</div>
				
				<?php
					 if($type=="S")
					 {
					 $type="Single";
					 }
					 else
					 {
					 $type="Business";
					 }
					 ?>
				
				
			
				
				<!-- End .control-group  -->
				<div class="form-group">
					<div class="col-lg-offset-2">
						<div class="pad-left15">
<button type="submit" class="btn btn-primary">Save changes</button>
							<button type="button" onclick="redirect_to('<?php echo base_url().'adminsettings'; ?>');" class="btn">Cancel</button>
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
