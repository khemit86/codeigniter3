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
                <li class="active"><a href="<?= base_url('finances/withdraw_funds') ?>">Withdraw Funds</a></li>
            </ul>
        </div>


        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-table-2"></i> Withdraw Funds</h1>
            </div>
            <div class="row">

                <div class="col-lg-12">
                    <?php
                    if ($this->session->flashdata('succ_msg')) {
                        ?>
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?= $this->session->flashdata('succ_msg') ?>
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

        			<!-- <div class="col-lg-12">
                    
                      <form id="validate" action="<?php echo base_url(); ?>finances/deposit_funds" class="form-horizontal" role="form" name="state" method="get" enctype="multipart/form-data">
                        <div class="input-group-btn">

                        <input type="text" placeholder="Enter project title ..." class="searchfield " name="search_element" id="srch" size="30" value="<?= $search ?>">
                        <input type="submit" name='submit' id="submit" class="btn" value="SEARCH" onclick="hdd();">
                        </div>
                      </form>
                    
                    </br>
              </div> -->
              <div id="prod " class="table-responsive">
                   <table class="table table-hover table-bordered adminmenu_list" id="example1">
                        <thead>
                            <tr>
                                <th style="text-align:left;">User</th>
                                <th style="text-align:left;">Profile Name</th>
                                <th style="text-align:left;">Withdraw To</th>
                                <th style="text-align:left;">Withdraw to paypal Account</th>
                                <th style="text-align:left;">Withdraw requested Amount</th>
                                <th style="text-align:left;">Withdraw request submited Date</th>
                                <th style="text-align:left;">Withdraw request rejected Date</th>
                                <th style="text-align:left;">Request Status</th>
                                <th style="text-align:left;">Transaction Id</th>
                                <th style="text-align:left;">Transaction Status</th>
                                <th style="text-align:left;">Transaction Date</th>
                                <th style="text-align:left;">Failure Reason</th>
                                <th style="text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <div id="prodlist">
                              <?php
                                if(count($withdraw_funds) > 0) {
                                  foreach($withdraw_funds as $val) {
                              ?>
                              <tr>
                                    <td>
                                      <?php
                                        if($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
                                          echo $val['first_name'].' '.$val['last_name'];
                                        } else {
                                          echo $val['company_name'];
                                        }
                                      ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo HTTP_WEBSITE_HOST.$val['profile_name']; ?>"><?php echo $val['profile_name']; ?></a>
                                    </td>
                                    <td>
                                        <?php echo ucwords($val['withdraw_to']); ?>
                                    </td>
                                    <td>
                                        <?php echo $val['withdraw_to_paypal_account']; ?>
                                    </td>
                                    <td>
                                        <?php echo format_money_amount_display($val['withdrawal_requested_amount']).' '.CURRENCY; ?>
                                    </td>
                                    <td>
                                        <?php echo !empty($val['withdrawal_request_submit_date']) ? date(DATE_TIME_FORMAT, strtotime($val['withdrawal_request_submit_date'])) : ''; ?>
                                    </td>
                                    <td>
                                        <?php echo !empty($val['request_rejection_date']) ? date(DATE_TIME_FORMAT, strtotime($val['request_rejection_date'])) : ''; ?>
                                    </td>
                                    <td>
                                        <?php echo ucfirst($val['request_status']); ?>
                                    </td>
                                    <td><?php echo $val['transaction_id']; ?></td>
                                    <td><?php echo $val['transaction_status']; ?></td>
                                    <td><?php echo !empty($val['transaction_date']) ? date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])) : ''; ?></td>
                                    <td><?php echo $val['failure_reason']; ?></td>
                                    <td align="center" id="ac">
                                        <?php
                                            if($val['request_status'] == 'pending') {
                                        ?>
                                        <a class="btn btn-danger" href="<?php echo base_url('finances/manage_withdraw_requests/approve/'.$val['id']); ?>?per_page=<?php echo $this->input->get('per_page'); ?>">Approve</a>
                                        <a class="btn btn-danger" href="<?php echo base_url('finances/manage_withdraw_requests/reject/'.$val['id']); ?>?per_page=<?php echo $this->input->get('per_page'); ?>">Reject</a>
                                        <a class="btn btn-danger" href="<?php echo base_url('finances/manage_withdraw_requests/delete/'.$val['id']); ?>?per_page=<?php echo $this->input->get('per_page'); ?>">Delete</a>
                                        <?php
                                            }
                                        ?>
                                    </td>
                              </tr>
                              <?php    
                                  }
                                } else {
                              ?>
                              <tr>
                                  <td colspan="6" align="center" style="color:#F00;">No records found...</td>
                              </tr>
                              <?php
                                }
                              ?>
                            </div>
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