<div id="prod">
                    <table class="table table-hover table-bordered adminmenu_list" id="example1">
                        <thead>
                            <tr>
								<th style="text-align:left;">Project Id</th>
                                <th style="text-align:left;">Project Title</th>
                                <th style="text-align:left;">Project Type</th>
                                <th style="text-align:left;">Budget</th>
                                <th style="text-align:left;" width="5%">Project Owner</th>
                                <th style="text-align:left;">Expiration Date</th>
                                <th style="text-align:center;" id="acc">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <div id="prodlist">
                            <?php
							
                            if (count($project_listing) > 0) { 
                                foreach ($project_listing as $key => $val) {
                                    ?>

                                    <tr>  
                                        <td><?php echo $val['project_id']; ?></td>
                                        <td>
											<a href="<?php echo base_url(); ?>project/project_detail/<?php echo $val['project_id']; ?>" target="_blank">
                                            <?php echo ucwords(get_correct_string_based_on_limit(htmlspecialchars($val['project_title'], ENT_QUOTES),"40")."....") ?>
                                            </a>
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
                                        <td>
                                        <?php 
										if($val['confidential_dropdown_option_selected'] == 'Y'){
											echo 'Confidential';
										}else if($val['not_sure_dropdown_option_selected'] == 'Y'){
											echo 'Not sure';
										}else{
											$budget_range = '';
											if($val['max_budget'] != 'All'){
												if($val['project_type'] == 'hourly'){
													$budget_range = 'between&nbsp;'.number_format($val['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'/hour&nbsp;and&nbsp;'.number_format($val['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY .'/hour';
												
												}else if($val['project_type'] == 'fulltime'){
													$budget_range = 'between&nbsp;'.number_format($val['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'/mo&nbsp;and&nbsp;'.number_format($val['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY .'/mo';
												
												}else{
											
													$budget_range = 'between&nbsp;'.number_format($val['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'&nbsp;and&nbsp;'.number_format($val['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY;
												}
											}else{
												if($val['project_type'] == 'hourly'){
													$budget_range = 'more then&nbsp;'.number_format($val['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .'/hour';
												}else if($val['project_type'] == 'fulltime'){
													$budget_range = 'more then&nbsp;'.number_format($val['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .'/mo';
												}else{
													$budget_range = 'more then&nbsp;'.number_format($val['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY;
												}
											}
											echo $budget_range;
										}
										
										?>
                                        </td>
                                        <td width="5%">
											<?php
											if($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')){
												echo ucwords($val['first_name']." ".$val['last_name']);
                                            } else {
                                                echo ucwords($val['company_name']);
											}
											?>
										</td>
                                        <td><?= date(DATE_TIME_FORMAT,strtotime($val['project_expiration_date'])) ?></td>
                                        <td align="center" id="ac">
											<?php
											if($val['project_type'] == 'fulltime'){
											$hires_count_fulltime_project = get_hires_count_fulltime_project($val['project_id']);
												if($hires_count_fulltime_project == 0){
											?>
											<a class="btn btn-danger" href="<?php echo base_url(); ?>project/delete_expired_project/<?php echo $val['project_id']; ?>">Delete</a>
											<a class="btn btn-danger" href="<?php echo base_url(); ?>project/cancel_expired_project/<?php echo $val['project_id']; ?>">Cancel</a>
											<?php
												}
											}else{
											?>
											<a class="btn btn-danger" href="<?php echo base_url(); ?>project/delete_expired_project/<?php echo $val['project_id']; ?>">Delete</a>
											<a class="btn btn-danger" href="<?php echo base_url(); ?>project/cancel_expired_project/<?php echo $val['project_id']; ?>">Cancel</a>
											<?php
											}
											?>
                                        </td>
                                      
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
                  <!-- Pagination Start -->			
                  <div class="pagnOnly" style="display:<?php echo !empty($project_listing) ? 'block' : 'none';?>">
                    <div class="row">
                    <div class="no_page_links <?php echo !empty($links) ? 'col-md-7 col-sm-7 col-12' : 'col-md-12 col-12'; ?>">
                        <?php
                        if(empty($rec_per_page)) {
                            $rec_per_page = ($total_rows > PAGING_LIMIT) ? PAGING_LIMIT : $total_rows;
                        }
                        ?>
                        <div class="pageOf">
                        <label><?php echo 'showing' ?> <span class="page_no"><?php echo !empty($page_no) ? $page_no : '1'; ?></span> - <span class="rec_per_page"><?php echo !empty($record_per_page) ? $record_per_page  : $rec_per_page; ?></span> <?php echo 'out of' ?> <span class="total_rec"><?php echo $total_rows; ?></span> <?php echo 'listing' ?></label>
                        </div>
                    </div>
                    <div class="page_links col-md-5 col-sm-5 col-12" style="display:<?php echo !empty($links) ? 'block' : 'none'; ?>">
                        <div class="modePage">
                        <?php
                            echo $links;
                        ?>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Pagination End -->
                </div><!-- End .col-lg-6  -->
            </div><!-- End .row-fluid  -->

        </div> <!-- End .container-fluid  -->
    </div> <!-- End .wrapper  -->