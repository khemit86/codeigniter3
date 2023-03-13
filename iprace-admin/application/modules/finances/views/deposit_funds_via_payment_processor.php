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
                <li class="active"><a href="<?= base_url() ?>finances/direct_bank_transfer_deposits">Deposit Funds via payment processor</a></li>
            </ul>
        </div>


        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-table-2"></i>Deposit funds via payment processor</h1>
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

            <div class="">
              <form id="validate" action="" class="form-horizontal" role="form" name="state" method="post" >
                <div class="input-group-btn">
                <input type="text" placeholder="Enter project title ..." class="searchfield " name="search_element" id="srch" size="30" value="">
                <button type="submit" name="submit" id="submit" class="btn" >SEARCH</button>
                </div>
              </form>			
            </div>

            <div id="prod" class="table-responsive">
                  <table class="table table-hover table-bordered adminmenu_list" id="example1">
                      <thead>
                          <tr>
                              <th style="text-align:left;">User</th>
                              <th style="text-align:left;">Profile Name</th>
                              <th style="text-align:left;">Transaction Amount</th>
                              <th style="text-align:left;">Transaction Reference id</th>
                              <th style="text-align:left;">Transaction date</th>
                              <th style="text-align:left;">Account Owner</th>
                              <th style="text-align:left;">Account Number</th>
                              <th style="text-align:left;">Bank Name</th>
                              <th style="text-align:left;">Card Number</th>
                              <th style="text-align:left;">Card Brand</th>
                              <th style="text-align:left;">Country code</th>
                              <th style="text-align:left;">Card Bank Name</th>
                              <th style="text-align:left;">Card Type</th>
                              <th style="text-align:left;">Status</th>
                          </tr>
                      </thead>
                      <tbody>
                          <div id="prodlist">
                            <?php
                              if(count($deposit_funds) > 0) {
                                foreach($deposit_funds as $val) {
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
                                      <?php echo format_money_amount_display($val['amount']).' '.CURRENCY; ?>
                                  </td>
                                    <td>
                                      <?php echo $val['payment_id']; ?>
                                  </td>
                                  <td>
                                      <?php echo date(DATE_TIME_FORMAT, strtotime($val['transaction_date'])); ?>
                                  </td>
                                  <td>
                                      <?php echo $val['bank_account_owner_name']; ?>
                                  </td>
                                  <td>
                                      <?php echo $val['bank_account_number']; ?>
                                  </td>
                                  <td>
                                      <?php echo $val['bank_name']; ?>
                                  </td>
                                  <td>
                                      <?php echo $val['card_number']; ?>
                                  </td>
                                  <td>
                                      <?php echo $val['card_brand']; ?>
                                  </td>
                                  <td>
                                      <?php echo $val['country_code']; ?>
                                  </td>
                                  <td>
                                      <?php echo $val['card_bank_name']; ?>
                                  </td>
                                  <td>
                                      <?php echo $val['card_type']; ?>
                                  </td>
                                  <td>
                                      <?php 
                                        if($val['status_code'] == 2 || $val['status_code'] == 6) {
                                          echo 'Successfull';
                                        } else if($val['status_code'] == 3) {
                                          echo 'Cancelled';                                            
                                        } else if($val['status_code'] == 4) {
                                          echo 'Failed';
                                        } else if($val['status_code'] == 7) {
                                          echo 'Waiting for confirmation';
                                        }
                                      ?>
                                  </td>
                            </tr>
                            <?php    
                                }
                              } else {
                            ?>
                            <tr>
                                <td colspan="15" align="center" style="color:#F00;">No records found...</td>
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