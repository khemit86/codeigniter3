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
                <li class="active"><a href="<?= base_url() ?>finances/direct_bank_transfer_withdraws">Direct Bank Transfer Users Withdraw Funds Requests</a></li>
            </ul>
        </div>


        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-table-2"></i>Direct Bank Transfer Users Withdraw Funds Request</h1>
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

              <div id="prod" class="table-responsive" style="overflow-x:auto">
                   <table class="table table-hover table-bordered adminmenu_list" id="example1">
                        <thead>
                            <tr>
                                <th style="text-align:left;">User</th>
                                <th style="text-align:left;">Profile Name</th>
                                <th style="text-align:left;">Withdraw Amount</th>
                                <th style="text-align:left;">Transaction Request Date</th>
                                <th style="text-align:left;">Bank Transaction id</th>
                                <th style="text-align:left;">Bank Transaction date</th>
                                <th style="text-align:left;">Account Owner</th>
                                <th style="text-align:left;">Account Number</th>
                                <th style="text-align:left;">Bank Name</th>
                                <th style="text-align:left;">Bank Code</th>
                                <th style="text-align:left;">Variable Symbol</th>
                                <th style="text-align:left;">IBAN Code</th>
                                <th style="text-align:left;">BIC / Swift code</th>
                                <th style="text-align:left;">Country</th>
                                <th style="text-align:left;">Status</th>
                                <th style="text-align:left;">Action</th>
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
                                        if($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) {
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
                                        <?php echo format_money_amount_display($val['withdraw_amount']).' '.CURRENCY; ?>
                                    </td>
                                     <td>
                                        <?php echo date(DATE_FORMAT, strtotime($val['user_withdraw_request_date'])); ?>
                                    </td>
                                     <td>
                                        <?php echo $val['bank_transaction_id']; ?>
                                    </td>
                                    <td>
                                        <?php echo !empty($val['bank_transaction_date']) ? date(DATE_FORMAT, strtotime($val['bank_transaction_date'])) : ''; ?>
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
                                        <?php echo $val['bank_code']; ?>
                                    </td>
                                    <td>
                                        <?php echo $val['user_bank_withdrawal_variable_symbol']; ?>
                                    </td>
                                    <td>
                                        <?php echo $val['bank_account_iban_code']; ?>
                                    </td>
                                    <td>
                                        <?php echo $val['bank_account_bic_swift_code']; ?>
                                    </td>
                                    <td>
                                        <?php echo $val['country_name']; ?>
                                    </td>
                                    <td>
                                        <?php 
                                          if($val['status'] == 'request_pending_admin_confirmation') {
                                            echo 'Pending';
                                          } else if($val['status'] == 'request_confirmed_by_admin') {
                                            echo 'Confirmed'.($val['admin_manual_entry'] ? '(Added By Admin)' : '');
                                          } else {
                                            echo 'Rejected';                                            
                                          }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                          if($val['status'] == 'request_pending_admin_confirmation') {
                                        ?>
                                        <a class="btn btn-danger approve_confirm" data-id="<?php echo $val['id']; ?>" href="">Approve</a>
                                        <a class="btn btn-danger" href="<?php echo base_url('finances/direct_bank_transfer_withdraws/reject/'.$val['id']); ?>?per_page=<?php echo $this->input->get('per_page'); ?>">Reject</a>
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
                                  <td colspan="16" align="center" style="color:#F00;">No records found...</td>
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
            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-danger deposit_funds">withdraw Funds</button>
                </div>
                <div class="col-sm-12">
                  <!-- Bank Details Start -->						
                  <div class="transactionDetails" style="display:none;">						
                    <form id="fm_bank_transfer">	
                    <div class="row">
                      <div class="col-md-12 col-sm-12 col-12 default_country fontSize0">
                        <div class="form-group default_dropdown_select">
                          <label class="default_black_bold">User</label>
                          <select id="user_id" name="user">
                            <option value="" style="display:none;">Select User</option>
                            <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user['user_id']; ?>" data-symbol="<?php echo $user['user_bank_withdrawal_variable_symbol']; ?>"><?php echo ($user['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user['is_authorized_physical_person'] == 'Y')) ?  $user['first_name'].' '.$user['last_name'] : $user['company_name']; ?></option>
                            <?php endforeach; ?>
                          </select>
                          <span class="error_msg user_err"></span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                          
                            <label class="default_black_bold">withdraw Amount</label>
                            <input type="text" class="form-control avoid_space default_input_field" maxlength="25" id="withdraw_amount" name="withdraw_amount">
                            <span class="error_msg withdraw_amount_err"></span>
                        </div>
                        
                      </div>
                      <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                          <label class="default_black_bold">Account Owner</label>
                          <input type="text" class="form-control avoid_space default_input_field" id="account_owner" name="account_owner">
                          <span class="error_msg account_owner_err"></span>
                        </div>
                        
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                          <label class="default_black_bold">Account Number</label>
                          <input type="text" class="form-control avoid_space default_input_field" name="account_number">
                          <span class="error_msg account_number_err"></span>
                        </div>
                        
                      </div>
                      <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                          <label class="default_black_bold">Bank Name</label>
                          <input type="text" class="form-control avoid_space default_input_field" name="bank_name">
                          <span class="error_msg bank_name_err"></span>
                        </div>
                        
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                          <label class="default_black_bold">Bank code</label>
                          <input type="text" class="form-control avoid_space default_input_field" name="bank_code">
                          <span class="error_msg bank_code_err"></span>
                        </div>
                        
                      </div>
                      <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group" style="display:none" id="variable_symbol">
                          <label class="default_black_bold">Variable Symbol</label>
                          <input type="text" class="form-control avoid_space default_input_field" >
                          <span class="error_msg"></span>
                        </div>
                        
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-12 default_country fontSize0">
                          <div class="form-group default_dropdown_select">
                            <label class="default_black_bold">Country</label>
                            <select id="company_country" name="country">
                              <option value="" style="display:none;">Select Country</option>
                              <?php foreach ($countries as $country): ?>
                              <option value="<?php echo $country['id']; ?>" ><?php echo $country['country_name'] ?></option>
                              <?php endforeach; ?>
                            </select>
                            <span class="error_msg country_err"></span>
                          </div>
                        
                      </div>
                    </div>
                    <div class="row country_cz" style="display:none">
											<div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                          <label class="default_black_bold">IBAN</label>
                          <input type="text" class="form-control avoid_space default_input_field" name="iban">
                          <span class="error_msg iban_err"></span>
                        </div>
                        
                      </div>
                      <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                          <label class="default_black_bold">BIC / SWIFT Code</label>
                          <input type="text" class="form-control avoid_space default_input_field" name="swift_code">
                          <span class="error_msg bic_swift_code_err"></span>
                        </div>
                        
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                          <label class="default_black_bold">Transaction Date</label>
                          <input type="text" class="form-control avoid_space default_input_field" name="transaction_date">
                          <span class="error_msg transaction_date_err"></span>
                        </div>
                        
                      </div>
                      <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                          <label class="default_black_bold">Transaction Id</label>
                          <input type="text" class="form-control avoid_space default_input_field" name="transaction_id">
                          <span class="error_msg transaction_id_err"></span>
                        </div>
                        
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-sm-12 col-12 text-center">
                        <button class="btn btn-success register_transaction">Register Transaction</button>
                        <button class="btn btn-danger cancel">Cancel</button>
                      </div>
                    </div>
                    </form>
                  </div>
                  <!-- Bank Details End -->
                </div>
            </div>
        </div> <!-- End .container-fluid  -->
    </div> <!-- End .wrapper  -->
</section>
<div class="modal alert-popup" id="confirm_popup" role="dialog">
	<div class="modal-dialog modal-dialog-centered" style="margin:20% 0 0 40%">
	  <!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close popup_close" data-dismiss="modal">&times;</button>
			  <div class="modal-header-inner">
				  <h4 class="modal-title" id="error_popup_heading">Withdraw Funds Request Confirmation</h4>
			  </div>
			</div>
			<div class="modal-body text-center">
				<form id="fm">
			  <div class="row">
						<div class="col-md-6 col-sm-6 col-12">
							<div class="form-group">
								<label class="default_black_bold">Transaction Id</label>
								<input type="text" class="form-control avoid_space default_input_field" name="transaction_id">
							</div>
							<span class="error_msg transaction_id_err"></span>
						</div>
            <div class="col-md-6 col-sm-6 col-12">
							<div class="form-group">
								<label class="default_black_bold">Transaction Date</label>
								<input type="text" class="form-control avoid_space default_input_field" name="transaction_date">
							</div>
							<span class="error_msg transaction_date_err"></span>
						</div>
        </div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn default_btn btn-danger withdraw_confirm">Confirm</button>
				<button type="button" class="btn default_btn red_btn popup_close" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- <script type="text/javascript" src="<?php echo JS; ?>jquery-1.11.0.min.js"></script>
<script src="<?php echo JS; ?>bootstrap/bootstrap.js"></script> -->
<script>
  $(function() {
    $(document).on('click', '.approve_confirm', function(e) {
      e.preventDefault();
			var id = $(this).attr('data-id');
			$('.withdraw_confirm').attr('data-id', id);
			$('input').val('');
			$('.error_msg').html('');
      $('#confirm_popup').modal({backdrop : true});
      
    });
		
		$(document).on('click', '.withdraw_confirm', function() {
			var id = $(this).attr('data-id');
			$.ajax({
				url : ''+'?id='+id,
				method : 'POST',
				dataType : 'json',
				data : $('#fm').serialize(),
				success : function(res) {
					if(res['status'] == 200) {
						$('#confirm_popup').modal('hide');
						window.location.reload();
					} else if(res['status'] == 'FAILED') {
						$('.error_msg').html('');
						$.each(res['error'], function(index, val) {
							$('.'+val['id']).html(val['message']);
						});
					}
				}
			});
		});
  });
</script>
<script>
  $(function(){

    number_length_validate('#withdraw_amount', 25);

    $('.deposit_funds,.cancel').click(function(e){
      if($(e.target).hasClass('deposit_funds')) {
        $(this).hide();
      } else {
        $('.deposit_funds').show();
      }
			 $('#fm_bank_transfer')[0].reset();
			 $('#variable_symbol').hide();
        $('#variable_symbol input').val('');
        $('.country_cz').hide();
      $('.error_msg').html('');
       $('select option[value=""]').prop('selected', true);
       $.uniform.update();
      $('.transactionDetails').toggle();
      return false;
    });
    $(document).on('click', '.register_transaction', function(e){
			e.preventDefault();
			var thi = $(this);
			$(thi).prop('disabled', true);
      var fm_data = $('#fm_bank_transfer').serialize();
      $.ajax({
        url : SITE_URL+'finances/ajax_withdraw_funds_via_direct_bank_transfer',
        method : 'POST',
        dataType : 'json',
        data : fm_data,
        success : function(res) {
					$(thi).prop('disabled', false);
          if(res['status'] == 'FAILED') {
            $('.error_msg').html('');
            $.each(res['error'], function (index, val) {
              $('.' + val['id']).html(val['message']);
            });
          } else {
            window.location.reload(true);
          }
        }
      });
    });
    $('input').on('keypress', function () {
      $(this).parent().find('span.error_msg').html('');
    });
    $('select').on('click', function () {
      $(this).parent().next('span.error_msg').html('');
		});
		$('#company_country').on('change', function () {
      var val = $(this).val();
      if(val != '<?php echo $reference_country_id; ?>') {
        $('.country_cz').show();
        $('.country_cz .error_msg').html('');
      } else {
        $('.country_cz').hide();
      }
    });
    $('#user_id').on('change', function() {
      var symbol = $('option:selected', $(this)).data('symbol');
      if(symbol) {
        $('#variable_symbol').show();
        $('#variable_symbol input').val(symbol);
      } else {
        $('#variable_symbol').hide();
        $('#variable_symbol input').val('');
      }
    });

    $('#withdraw_amount').bind('input propertychange', function () {
      $("#withdraw_amount").val(function (index, value) {
        var tmp = value;
        var val = value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        if (val.length > $(this).attr('maxlength')) {
          tmp = tmp.substring(0, tmp.length - 1);
          return tmp.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        } else {
          return val;
        }
      });
    });
  });

  //This function is used to validate the input field(numbers and max length validation)
  function number_length_validate(selector, maxLength = 999999999999) {
    var specialKeys = new Array();
    specialKeys.push(8); //Backspace

    //$(selector).bind('copy paste cut drop',function(e) { 
    $(document).on('copy paste cut drop', selector, function (e) {
      e.preventDefault();
    });

    //$(selector).bind("keypress", function (e) {
    $(document).on('keypress', selector, function (e) {
      var keyCode = e.which ? e.which : e.keyCode
      var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
      if (this.value.length == 0 && e.which == 48) {
        return false;
      }
      if (this.value.length == 0 && e.which == 48) {
        return false;
      }
      return ret;
    });

    //$(selector).keydown(function(e){
    $(document).on('keydown', selector, function (e) {
      if ($(selector).val().length > maxLength) {
        $(selector).val($(selector).val().substr(0, maxLength));
      }
    });

    $(document).on('keyup', selector, function (e) {
      //$(selector).keyup(function(e){
      if ($(selector).val().length > maxLength) {
        $(selector).val($(selector).val().substr(0, maxLength));
      }
    });
  }
</script>