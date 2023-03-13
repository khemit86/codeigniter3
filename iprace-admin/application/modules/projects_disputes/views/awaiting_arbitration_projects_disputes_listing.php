<style>
    .mybox{
        background-color: #F7F7F7;
        border: 1px solid #C9C9C9;
        margin: 0;
        padding: 4px;

    }
    .mybox:hover{
        background-color: #C9C9C9;
        color:#fff;
    }
    .myactiveclass{
        background-color: #C9C9C9;
        color:#fff;
    }

</style>

<section id="content">
    <div class="wrapper">
        <div class="crumb">
            
            <ul class="breadcrumb">
                <li class="active"><a href="<?= base_url() ?>"><i class="icon16 i-home-4"></i>Home</a></li>
                <li class="active"><a href="<?= base_url() ?>projects_disputes/awating_arbitration_projects_disputes_listing">Awaiting Arbitration Disputes List</a></li>
            </ul>
        </div>


        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-table-2"></i> Disputes Management (Awaiting Arbitration)</h1>
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

        			<div class="col-lg-12">
					
						 <form id="validate" action="<?php echo base_url(); ?>projects_disputes/awaiting_arbitration_projects_disputes_listing" class="form-horizontal" role="form" name="state" method="get" enctype="multipart/form-data">
							<div class="input-group-btn">

							<input type="text" placeholder="Enter Search Text ..." class="searchfield " name="search_element" id="srch" size="30" value="<?= $search ?>">
							<input type="submit" name='submit' id="submit" class="btn" value="SEARCH" onclick="hdd();">
							</div>
						</form>
					
					</br>
                    </div>
                
						                    
                     <div id="prod">
                    <table class="table table-hover table-bordered adminmenu_list" id="example1">
                        <thead>
                            <tr>
								<th style="text-align:left;">Dipute Id</th>
                                <th style="text-align:left;">Project Title</th>
                                <th style="text-align:left;">Project Type</th>
                                <th style="text-align:left;" width="5%">Dispute Initiator</th>
                                <th style="text-align:left;" width="5%">Dispute against</th>
								<th style="text-align:left;" width="5%">Disputed Amount</th>
								<th style="text-align:left;" width="5%">Service Fees</th>
								<th style="text-align:left;">Dispute Start date</th>
                                <th style="text-align:left;">Dispute negotation End date</th>
								<th style="text-align:left;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <div id="prodlist">
                            <?php
							
                            if (count($projects_disputes_listing) > 0) { 
                                foreach ($projects_disputes_listing as $key => $val) {
									
                                   ?>

                                    <tr>  
                                        <td><?php echo $val['dispute_reference_id']; ?></td>
										
                                        <td>
											<?php echo $val['project_title']; ?>
										</td>
										<?php 
										if($val['project_type']=='fixed'){
											$type = "Fixed";
										}elseif($val['project_type']=='hourly'){
											$type = "Hourly";
										}elseif($val['project_type']=='fulltime'){
											$type = "Fulltime";
										} 
										?>
                                        <td><?php echo $type; ?></td>
                                        <td width="5%">
                                        <?php 
										$dispute_initiated_by_user_name = $val['initiator_account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $val['initiator_first_name'] . ' ' . $val['initiator_last_name'] : $val['initiator_company_name'];
									
										echo $dispute_initiated_by_user_name;
										?>
                                        </td>
                                        <td width="5%">
											<?php
											$disputed_against_user_name = $val['disputee_account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $val['disputee_first_name'] . ' ' . $val['disputee_last_name'] : $val['disputee_company_name'];
											echo $disputed_against_user_name ;
											?>
										</td>
										<td><?php echo str_replace(".00","",$val['disputed_amount']). " ". CURRENCY; ?></td>
										<td><?php echo str_replace(".00","",$val['disputed_service_fees']). " ". CURRENCY; ?></td>
										
										<td><?= date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($val['dispute_start_date'])) ?></td>
										<td><?= date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($val['dispute_negotiation_end_date'])) ?></td>
										
                                        <td>
										<a class="btn btn-danger" href="<?php echo base_url(); ?>projects_disputes/closed_awaiting_arbitration_dispute/<?php echo $val['project_type'].'/'.$val['dispute_reference_id']."/".$val['dispute_initiated_by_user_id']."?search_element=" .$search."&per_page=".$this->input->get('per_page'); ?>">Initiator Win</a>
										<a class="btn btn-danger" href="<?php echo base_url(); ?>projects_disputes/closed_awaiting_arbitration_dispute/<?php echo $val['project_type'].'/'.$val['dispute_reference_id']."/".$val['disputed_against_user_id']."?search_element=" .$search."&per_page=".$this->input->get('per_page'); ?>">Disputee Win</a></td>
                                      
                                    </tr>
									</div>

                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7" align="center" style="color:#F00;">No records found...</td>
                                </tr>
							
    <?php
}
?>
						
                        </tbody>
                    </table>
              </div>
                  <?php     
						echo "<div class='pagin'>".$links."</div>";  
                      ?>
                </div><!-- End .col-lg-6  -->
            </div><!-- End .row-fluid  -->

        </div> <!-- End .container-fluid  -->
    </div> <!-- End .wrapper  -->
</section>