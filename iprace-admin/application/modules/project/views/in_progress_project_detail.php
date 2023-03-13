<?php
$payment_method = '';
$location = '';
if($project_data[0]['escrow_payment_method'] == 'Y'){
	$payment_method = 'via Escrow system';
	}
if($project_data[0]['offline_payment_method'] == 'Y'){
	$payment_method = 'via Offline system';
}
if(!empty($project_data[0]['county_name'])){
	if(!empty($project_data[0]['locality_name'])){
		$location .= '&nbsp'.$project_data[0]['locality_name'];
	}
	if(!empty($project_data[0]['postal_code'])){
	$location .= '&nbsp'.$project_data[0]['postal_code'] .',&nbsp';
	}else{
		$location .= ',&nbsp';
	}
	$location .= $project_data[0]['county_name'];
}

?>
<section id="content">
    <div class="wrapper">
        <div class="crumb">
            <ul class="breadcrumb">
                <li class="active"><a href="http://localhost/services_portal/iprace-admin/"><i class="icon16 i-home-4"></i>Home</a></li>
               <li class="active"><a onclick="redirect_to('http://localhost/services_portal/iprace-admin/categories');">Project Detail</a> </li>
            <li class="active">Project Detail</li>
            </ul>
        </div>

        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-list-4"></i>Project Detail </h1>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="icon"><i class="icon20 i-stack-checkmark"></i></div> 
                            <h4>Project Detail (In Progress)</h4>
                            <a href="#" class="minimize2"></a>
                        </div><!-- End .panel-heading -->

                        <div class="panel-body"> 
						<div class="panel-inner">
							<ul>
								<li> 
									<p>
										<strong>Project Title:</strong>
										<?php echo htmlspecialchars($project_data[0]['project_title'], ENT_QUOTES); ?>
									</p>
								</li>
								<li>
									<p>
										<strong>Project Description:</strong>
										<?php echo (nl2br(htmlspecialchars($project_data[0]['project_description'], ENT_QUOTES))); ?>
									</p>
								</li>
							</ul>
							<div class="Project-details">
								<ul>
									<li>
										<p>
											<strong>
											<?php 
											echo $project_data[0]['project_type']== 'fulltime' ? 'Fulltime Job' : "Project Type&nbsp;:";
											?> 
											</strong>
											<?php 
											if($project_data[0]['project_type'] != 'fulltime'){
												if($project_data[0]['project_type']=='fixed'){
													$type = "Fixed";
												}elseif($project_data[0]['project_type']=='hourly'){
													$type = "Hourly";
												}	
												echo $type;	
											}
											?>
										</p> 
									</li>
									<li>
										<p>
											<strong>
											<?php
											echo $project_data[0]['project_type']== 'fulltime' ? 'Salary&nbsp;:' : 'Project Budget&nbsp;:';
											?>
											</strong>
											<?php
											if($project_data[0]['confidential_dropdown_option_selected'] == 'Y'){
												echo 'Confidential';
											}else if($project_data[0]['not_sure_dropdown_option_selected'] == 'Y'){
												echo 'Not Sure';
											}else{
												$budget_range = '';
												if($project_data[0]['max_budget'] != 'All'){
													if($project_data[0]['project_type'] == 'hourly'){
														$budget_range = 'between&nbsp;'.number_format($project_data[0]['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'/hour&nbsp;and&nbsp;'.number_format($project_data[0]['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY .'/hour';
													
													}else if($project_data[0]['project_type'] == 'fulltime'){
														$budget_range = 'between&nbsp;'.number_format($project_data[0]['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'/mo&nbsp;and&nbsp;'.number_format($project_data[0]['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY .'/mo';
													
													}else{
												
														$budget_range = 'between&nbsp;'.number_format($project_data[0]['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'&nbsp;and&nbsp;'.number_format($project_data[0]['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY;
													}
												}else{
													if($project_data[0]['project_type'] == 'hourly'){
														$budget_range = 'more then&nbsp;'.number_format($project_data[0]['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .'/hour';
													}else if($project_data[0]['project_type'] == 'fulltime'){
														$budget_range = 'more then&nbsp;'.number_format($project_data[0]['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .'/mo';
													}else{
														$budget_range = 'more then&nbsp;'.number_format($project_data[0]['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY;
													}
												}
												echo $budget_range;
											}
											
											?>
										</p>
									</li>
									<li>
									<p>
										<strong>Project Owner:</strong>
										<?php
										if($project_data[0]['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($project_data[0]['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data[0]['is_authorized_physical_person'] == 'Y')){
											echo ucwords($project_data[0]['first_name']." ".$project_data[0]['last_name']);
										
										}else{
											echo ucwords($project_data[0]['company_name']);
										}
										?>
									</p>
								</li>
								<?php
								if(!empty($payment_method)){
								?>
								<li>
									<p>
										<strong>Payment Method:</strong>
										<?php
										echo $payment_method;
										?>
									</p>
								</li>
								<?php
								}
								if(!empty($location)){
								?>
								<li>
									<p>
										<strong>Location:</strong>
										<?php echo $location; ?>
									</p>
								</li>
								<?php
								}
								?>
								</ul>
							</div>
							<?php
							if(!empty($project_category_data)){
							?>
							<div class="category">
								<h3>Category:</h3>
								<ul>
									<?php
									foreach($project_category_data as $category_key=>$category_value){
										if(!empty($category_value['parent_category_name']) && !empty($category_value['category_name'])){
									?>
										<li>
										<div class="project-category">
											<span><?php echo $category_value['category_name']; ?></span>
											<a href="javascript:void(0);"><?php echo $category_value['parent_category_name']; ?></a>
										</div>
										</li>
									<?php
										}else{
									?>
										<li>
											<span><?php echo $category_value['category_name']; ?></span>
										</li>
									<?php
										}
									}
									?>
									
								</ul>	
							</div>
							<?php
							}
							?>
							
							<?php
							if(!empty($project_tag_data)){
							?>
							<div class="tags-list">
								<ul> 
									<?php
									foreach($project_tag_data as $project_tag_key=>$project_tag_value){
									?>
										<li> <span><?php echo $project_tag_value['project_tag_name'] ?></span></li>
									<?php
									}
									?>
								</ul>
							</div>
							<?php
							}
							if(!empty($project_attachment_data)){
							?>
							<div class="project-attachments">
								<h3>Project Attachments</h3>
								<ul><?php
									foreach($project_attachment_data as $project_attachment_key=>$project_attachment_value){
									
										$attachment_id = $project_attachment_value['id'];
										$download_url = base_url()."project/download_project_attachment/".$attachment_id."/".$project_data[0]['profile_name']."/in_progress";
										echo '<li><a href="'.$download_url.'" class="download_attachment">'.$project_attachment_value['project_attachment_name'].'</a></li>';
									}
									?>
								</ul>
							</div>
							<?php
							}
							?>
							<div class="bottom-btn text-center">
								<a href="<?php echo base_url(); ?>project/in_progress" class="btn btn-primary">Back</a>
							</div>
						</div>
                   
                        </div><!-- End .panel-body -->
                    </div><!-- End .widget -->
                </div><!-- End .col-lg-12  --> 
            </div><!-- End .row-fluid  -->

        </div> <!-- End .container-fluid  -->
    </div> <!-- End .wrapper  -->
</section>