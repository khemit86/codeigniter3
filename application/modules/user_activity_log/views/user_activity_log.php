<div class="dashTop">
	<div class="usrAct">
		<div class="">
			<div class="uABody" id="searchData">
						<?php
							if(count($activities)>= 1) {
						?>
				<!-- When Project is Updated Start -->
				<div class="uaActive">
					<div class="userSearch">
						<div class="row">
							<div class="col-md-8 col-sm-8 col-12 uaLeft">
								<div class="uAtxt default_page_heading">
									<h4><?php echo $this->config->item('activity_heading'); ?></h4>
									<!--
									<span class="bookman_font_regular">You currently receive 1 invoice a month. The invoice will be created on the following day: 13.12.2018</span>-->
								</div>
							</div>
							<div class="col-md-4 col-sm-4 col-12 uaRight">
								<div class="uAsrch">
									<div class="form-group">
										<input type="hidden" id="URL" value="<?php echo base_url('dashboard').'/search_activity'; ?>" />
										<input type="text" class="form-control default_input_field" id="activity_search" placeholder="<?php echo $this->config->item('activity_search_text'); ?>" aria-label="Recipient's username" aria-describedby="basic-addon2">
										<!--<div class="input-group-append">
											<button class="btn" type="button"><?php //echo $this->config->item('activity_search_text'); ?></button>
										</div>-->
									</div>
								</div>
							</div>				
						</div>
					</div>
					
					<div class="row" id="user_activity_log">
						<div class="col-md-12 col-sm-12 co-12">
							<div class="uAmt">
								<div class="fix-loader">
									<div id="loading">
											<div id="nlpt"></div>
											<div class="msg"><?php echo $this->config->item('user_activity_log_loader_display_text'); ?></div>
									</div>
									<div class="no_filter_record"></div>
								</div>
								<div class="uAtop">
									<div class="row">
										<div class="col-md-9 col-sm-12 col-12 uaLeft">
											<h6><?php echo $this->config->item('activity_column_heading'); ?></h6>
										</div>
										<div class="col-md-3 col-sm-12 col-12 uaRight">
											<h5><?php echo $this->config->item('activity_date_time'); ?></h5>
										</div>
									</div>
								</div>
								<div id="listData" class="dataList">
									<?php
										foreach($activities as $val) {
									?>
									<div class="uAmid">
										<div class="row">
											<div class="col-md-10 col-sm-10 col-12 uaLeft">
												<h6><?php echo $val['activity_description']; ?></h6>
											</div>
											<div class="col-md-2 col-sm-2 col-12 uaRight">
												<h5><?php echo date(DATE_TIME_FORMAT,strtotime($val['activity_log_record_time']));?></h5>
											</div>
										</div>
									 </div>
									<?php
										}
									?>
								</div>
								<div class="uaP pagnOnly <?php echo $activities_count == 0 ? 'bmNone' : '';?>">
									<div class="row">
										<div class="<?php echo !empty($activities_count) ? 'col-md-7 col-sm-7 col-12' : 'col-md-12 col-sm-12 col-12' ?>">
											<?php
												$rec_per_page = ($activities_count > $this->config->item('user_display_activity_listing_limit_per_page')) ? $this->config->item('user_display_activity_listing_limit_per_page') : $activities_count;
											?>
											<div class="pageOf">
												<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $activities_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
											</div>
										</div>
										<div id="pag" class="col-md-5 col-sm-5 col-12" style="display:<?php echo !empty($pagination) ? 'block' : 'none'; ?>">
											<div class="modePage" aria-label="Page navigation example">
												<?php echo $pagination; ?>
											</div>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				<!-- When Project is Updated End -->
							<?php } else { ?>
				<!-- When No Project is Updated Start -->
				<div class="uaIntive">
					<div class="wPU">
						<i class="far fa-bell"></i>
						<?php echo $this->config->item('activity_no_record'); ?>
					</div>
				</div>
				<!-- When No Project is Updated End -->
							<?php } ?>
			</div>
		</div>
	</div>
</div>
<script>
	var user_activity_log_starting_position_to_search = '<?php echo $this->config->item("user_activity_log_starting_position_to_search");?>';
	var user_activity_log_loader_progressbar_display_time = '<?php echo $this->config->item("user_activity_log_loader_progressbar_display_time");?>';
</script>
<script src="<?php echo JS; ?>modules/user_display_activity.js"></script>