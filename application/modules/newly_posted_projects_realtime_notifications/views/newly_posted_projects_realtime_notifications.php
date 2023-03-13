<div class="dashTop p-0">
    <!-- Menu Icon on Responsive View Start -->	
	<?php echo $this->load->view('user_left_menu_mobile.php'); ?>
    <!-- Menu Icon on Responsive View End -->
	<div class="wrapper wrapper1">
	<!-- Left Menu Start -->
	<?php echo $this->load->view('user_left_nav.php'); ?>
	<!-- Left Menu End -->
	<!-- Right Section Start -->
		<div id="content">
		<div class="newly_posted_projects <?php echo $project_notifications_count == 0 ? 'no_data_msg_display_center' : '' ?>">
			<div class="rightSec">
					<!-- Middle Section Start -->
				<div class="default_page_heading" style="display:<?php echo $project_notifications_count > 0 ? 'block' : 'none' ?>">
					<h4><?php echo $this->config->item('newly_posted_projects_headline_title_projects_notification_feed'); ?></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<div class="proNoFeed">			
				<!-- Search Type Start -->
				<div class="srcType" style="display:<?php echo $project_notifications_count > 0 ? 'block' : 'none' ?>">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-12 searchSection">
							<div class="form-group">							
								<input type="text" id="search_keyword" class="default_input_field" data-role="tagsinput" placeholder="<?php echo $this->config->item('find_project_search_keyword_placeholder'); ?>" />
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-12 pLt0 searchSection">
							<div class="input-group">
								<input type="text" class="form-control" id="autocomplete" placeholder="<?php echo $this->config->item('find_project_search_locality_placeholder'); ?>">
								<div class="input-group-append">
									<span class="input-group-text"><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span>
								</div>
							</div>
						</div>
					</div>
					<div class="srchName">
						<div class="row">
							<div class="col-md-8 col-sm-8 col-12 tySrch">
								<div class="topSearch">
									<label class="typeSrch default_black_bold srcfristLabel"><?php echo $this->config->item('find_project_searching_type'); ?></label><span class="chkAdjust"><div class="form-check"><label class="default_checkbox">
											<?php
												$checked = '';
												if(empty($filter_arr) || (!empty($filter_arr) && !empty($filter_arr['search_type']) && $filter_arr['search_type'] == 'include')) {
													$checked = 'checked';
												}
											?><input type="checkbox" id="include" class="search_type" <?php echo $checked; ?> value="include"><span class="checkmark"></span><span class="textGap boldCat"><?php echo $this->config->item('find_project_searching_type_include_txt'); ?></span></label></div><div class="form-check"><label class="default_checkbox"><?php
												$checked = '';
												if(!empty($filter_arr) && !empty($filter_arr['search_type']) && $filter_arr['search_type'] == 'exclude') {
													$checked = 'checked';
												}
											?><input type="checkbox" id="exclude" class="search_type" <?php echo $checked; ?> value="exclude"><span class="checkmark"></span><span class="textGap"><?php echo $this->config->item('find_project_searching_type_exclude_txt'); ?></span></label></div></span><label class="typeSrchLabel">|</label><span class="chkAdjust chkBoxRight"><div class="form-check"><label class="default_checkbox">
											<?php
												$checked = '';
												if(!empty($filter_arr) && !empty($filter_arr['search_title_flag']) && $filter_arr['search_title_flag'] === "true") {
													$checked = 'checked';
												}
											?><input type="checkbox" id="searchTitle" <?php echo $checked; ?>><span class="checkmark"></span><label class="typeSrch default_black_bold textGap srcfristLabel"><?php echo $this->config->item('find_project_search_project_title'); ?></label></label></div></span>
								</div>
								<div class="receive_notification">
								<a class="rcv_notfy_btn" onclick="showMoreSearch()"><?php echo $this->config->item('find_project_show_more_search_options_text'); ?></a>
								<input type="hidden" id="moreSearch" value="1"></div>
							</div>
							<div class="col-md-2 col-sm-2 col-12 pLt0 srchBtn">
								<button type="button" class="btn btn-block default_btn blue_btn search_clear"><?php echo $this->config->item('find_project_clear_search_btn_text'); ?></button>
							</div>
							<div class="col-md-2 col-sm-2 col-12 pLt0 srchBtn">
								<button type="button" class="btn btn-block orange_btn default_btn search_btn"><?php echo $this->config->item('find_project_search_btn_txt'); ?></button>
							</div>
						</div>
					</div>
				<!-- </div> -->
				<!-- Search Type End -->
				<!-- Project Details Start -->				
				<div class="proDtls" style="display:<?php echo $project_notifications_count > 0 ? 'block' : 'none' ?>">
					<div id="rcv_notfy" class="pDtls" style="display: none;">
						<div class="fbSelect">
							<div class="fProjectlr">
								<div class="multiselect pSelect">
									<?php
										$bold_cat = '';
										$upgrades = $this->config->item('find_project_upgrade_list');
										foreach($upgrades as $key => $val) {
											if(!empty($filter_arr) && !empty($filter_arr['upgrades'])) {
												if(in_array($key, $filter_arr['upgrades'])) 
													$bold_cat = 'boldCat';
											}
										}
									?>
									<div class="selectBox">
										<select class="<?php echo !empty($bold_cat) ? $bold_cat : ''; ?>">
											<option><?php echo $this->config->item('find_project_project_upgrades_dropdown_option_name'); ?></option>
										</select>
										<div class="overSelect"></div>
									</div>
									<div id="checkboxes" class="visible_option select_flex_width">
										<?php											
											foreach($upgrades as $key => $val) {
												$checked = '';
												if(!empty($filter_arr) && !empty($filter_arr['upgrades'])) {
													if(in_array($key, $filter_arr['upgrades']))
														$checked = 'checked';
												} else {
													if(strpos($key, 'all') !== false) {
														$checked = 'checked';
													}
												}
										?>
										<div class="drpChk">
											<label for="<?php echo $key ?>" class="default_checkbox">
												<input type="checkbox" id="<?php echo $key?>" value="<?php echo $key ?>" <?php echo $checked; ?>>
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?> "><?php echo $val; ?></small></label>
										</div>		
										<?php
											}
										?>																		
									</div>
								</div>
							</div>
							<div class="fProjectlr">
								<div class="multiselect pSelect">
									<?php
										$bold_cat = '';
										$agreement = $this->config->item('find_project_agreement_list');
										if(!empty($filter_arr) && !empty($filter_arr['agreement'])) {
											if(in_array($filter_arr['agreement'], ['project_based','contract_fulltime'])) {
												$bold_cat = 'boldCat';
											}
										}
									?>
									<div class="selectBox">
										<select class="<?php echo !empty($bold_cat) ? $bold_cat : ''; ?>">
											<option><?php echo $this->config->item('find_project_agreement_dropdown_option_name'); ?></option>
										</select>
										<div class="overSelect"></div>
									</div>
									<div id="checkboxes1" class="visible_option select_flex_width">
										<?php
											
											foreach($agreement as $key => $val) {
												$checked = '';
												if(!empty($filter_arr) && !empty($filter_arr['agreement'])) {
													if($filter_arr['agreement'] == $key) {
														$checked = 'checked';
													}
												} else {
													if(strpos($key, 'all') !== false) {
														$checked = 'checked';
													}
												}
										?>
										<div class="drpChk">
											<label for="<?php echo $key; ?>" class="default_checkbox">
												<input type="checkbox" id="<?php echo $key; ?>" value="<?php echo  $key; ?>" <?php echo $checked; ?>>
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $val; ?></small></label>
										</div>
										<?php		
											}
										?>
									</div>
								</div>
							</div>
							<div class="fProjectlr">
								<?php
									$disbox = '';
									if(!empty($filter_arr) && (!empty($filter_arr['agreement']) && $filter_arr['agreement'] == 'project_based')) {
										$disbox = 'disSbox';
									}
								?>
								<div class="multiselect pSelect <?php echo $disbox; ?>">
									<div class="selectBox">
										<select class="<?php echo !empty($filter_arr['fulltime_salary_range']) ? 'boldCat' : ''; ?>">
											<option><?php echo $this->config->item('find_project_full_time_jobs_salaries_ranges_dropdown_option_name'); ?></option>
										</select>
										<div class="overSelect"></div>
									</div>
									<div id="checkboxes5" class="visible_option select_flex_width">
										<div class="drpChk">
											<label for="fulltime_salary_range_all" class="default_checkbox">
												<input type="checkbox" id="fulltime_salary_range_all" value="fulltime_salary_range_all" 
												<?php echo (empty($filter_arr) || empty($filter_arr['fulltime_salary_range']) ) ? 'checked' : ''; ?>
												/>
												<span class="checkmark"></span><small class="<?php echo (empty($filter_arr) || empty($filter_arr['fulltime_salary_range']) ) ? 'boldCat' : ''; ?>"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?></small></label>
										</div>					
										<?php
											foreach($fulltime_salary_range as $key => $val) {
												$checked = '';
												if(!empty($filter_arr) && !empty($filter_arr['fulltime_salary_range'])) {
													$min = array_column($filter_arr['fulltime_salary_range'], 'min');
													if(!empty($min) && in_array($val['fulltime_salary_min_range_key'], $min)) {
														$checked = 'checked';
													}
												}
										?>															
										<div class="drpChk">
											<label for="fulltime_salary_range_<?php echo $key; ?>" class="default_checkbox">
												<input type="checkbox" id="fulltime_salary_range_<?php echo $key; ?>" <?php echo  $checked;?> data-min="<?php echo $val['fulltime_salary_min_range_key'];?>" data-max="<?php echo $val['fulltime_salary_max_range_key'];?>" value="<?php echo 'fulltime_salary_range_'.$key?>">
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $val['fulltime_salary_range_value']; ?></small></label>
										</div>																				
										<?php
											}
										?>
									</div>
								</div>
							</div>
							<div class="fProjectlr">
								<?php
									$disbox = '';
									if(!empty($filter_arr) && (!empty($filter_arr['agreement']) && $filter_arr['agreement'] == 'contract_fulltime')) {
										$disbox = 'disSbox';
									}
								?>
								<div class="multiselect pSelect <?php echo $disbox; ?>">
									<?php
											$bold_cat = '';
											$project_based_options = $this->config->item('find_project_based_option_list');
											foreach($project_based_options as $key => $val) {
												if(!empty($filter_arr) && !empty($filter_arr['project_types'])) {
													if($filter_arr['project_types'][0] == $key) {
														$bold_cat = 'boldCat';
													}
												}
											}
									?>
									<div class="selectBox">
										<select class="<?php echo !empty($bold_cat) ? $bold_cat : '' ?>">
											<option><?php echo $this->config->item('find_project_project_based_options_dropdown_option_name'); ?></option>
										</select>
										<div class="overSelect"></div>
									</div>
									<div id="checkboxes4" class="visible_option select_flex_width">
										<?php	
											foreach($project_based_options as $key => $val) {
												$checked = '';
												if(!empty($filter_arr) && !empty($filter_arr['project_types'])) {
													if($filter_arr['project_types'][0] == $key) {
														$checked = 'checked';
													}
												} else {
													if(strpos($key, 'all') !== false) {
														$checked = 'checked';
													}
												}
										?>
										<div class="drpChk">
											<label for="<?php echo $key; ?>" class="default_checkbox">
												<input type="checkbox" id="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?>>
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $val;?></small></label>
										</div>																				
										<?php		
											}
										?>
									</div>
								</div>
							</div>
							<div class="fProjectlr">
								<?php
									$disbox = '';
									if(!empty($filter_arr) && (!empty($filter_arr['project_types']) && $filter_arr['project_types'][0] == 'hourly') || (!empty($filter_arr['agreement']) && $filter_arr['agreement'] == 'contract_fulltime')) {
										$disbox = 'disSbox';
									}
								?>
								<div class="multiselect pSelect <?php echo $disbox; ?>">
									<div class="selectBox">
										<select class="<?php echo !empty($filter_arr['fixed_budget_range']) ? 'boldCat' : ''; ?>">
											<option><?php echo $this->config->item('find_project_fixed_budget_ranges_dropdown_option_name'); ?></option>
										</select>
										<div class="overSelect"></div>
									</div>
									<div id="checkboxes2" class="visible_option select_flex_width">
										<div class="drpChk">
											<label for="fixed_budget_range_all" class="default_checkbox">
												<input type="checkbox" id="fixed_budget_range_all" value="fixed_budget_range_all" 
													<?php echo (empty($filter_arr) || empty($filter_arr['fixed_budget_range'])) ? 'checked' : ''; ?>
												/>
												<span class="checkmark"></span><small class="<?php echo (empty($filter_arr) || empty($filter_arr['fixed_budget_range'])) ? 'boldCat' : ''; ?>"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?></small></label>
										</div>		
										<?php
											foreach($fixed_budget_range as $key => $val) {		
												$checked = '';
												if(!empty($filter_arr) && !empty($filter_arr['fixed_budget_range'])) {
													$min = array_column($filter_arr['fixed_budget_range'], 'min');
													if(!empty($min) && in_array($val['fixed_budget_min_key'], $min)) {
														$checked = 'checked';
													}
												}										
												
										?>																		
										<div class="drpChk">
											<label for="fixed_budget_range_<?php echo $key; ?>" class="default_checkbox">
												<input type="checkbox" id="fixed_budget_range_<?php echo $key; ?>"  <?php echo $checked; ?> data-min="<?php echo $val['fixed_budget_min_key'];?>" data-max="<?php echo $val['fixed_budget_max_key'];?>" value="<?php echo 'fixed_budget_range_'.$key?>">
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $val['fixed_budget_range_value']; ?></small></label>
										</div>																				
										<?php
											}
										?>
									</div>
								</div>
							</div>
							<div class="fProjectlr">
								<?php
									$disbox = '';
									if(!empty($filter_arr) && (!empty($filter_arr['project_types']) && $filter_arr['project_types'][0] == 'fixed') || (!empty($filter_arr['agreement']) && $filter_arr['agreement'] == 'contract_fulltime')) {
										$disbox = 'disSbox';
									}
								?>
								<div class="multiselect pSelect <?php echo $disbox;?>">
									<div class="selectBox">
										<select class="<?php echo !empty($filter_arr['hourly_rate_range']) ? 'boldCat' : ''; ?>">
											<option><?php echo $this->config->item('find_project_hourly_rate_budgets_ranges_dropdown_option_name'); ?></option>
										</select>
										<div class="overSelect"></div>
									</div>
									<div id="checkboxes3" class="visible_option select_flex_width">
										<div class="drpChk">
											<label for="hourly_budget_range_all" class="default_checkbox">
												<input type="checkbox" id="hourly_budget_range_all" value="hourly_budget_range_all" 
													<?php echo (empty($filter_arr) || empty($filter_arr['hourly_rate_range'])) ? 'checked' : ''; ?>
												/>
												<span class="checkmark"></span><small class="<?php echo (empty($filter_arr) || empty($filter_arr['hourly_rate_range'])) ? 'boldCat' : ''; ?>"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?></small></label>
										</div>				
										<?php
											foreach($hourly_rate_budget_range as $key => $val) {
												$checked = '';
												if(!empty($filter_arr) && !empty($filter_arr['hourly_rate_range'])) {
													$min = array_column($filter_arr['hourly_rate_range'], 'min');
													if(!empty($min) && in_array($val['hourly_rate_min_key'], $min)) {
														$checked = 'checked';
													}
												}
										?>																
										<div class="drpChk">
											<label for="hourly_budget_range_<?php echo $key; ?>" class="default_checkbox">
												<input type="checkbox" id="hourly_budget_range_<?php echo $key; ?>" <?php echo $checked; ?> data-min="<?php echo $val['hourly_rate_min_key'];?>" data-max="<?php echo $val['hourly_rate_max_key'];?>" value="<?php echo 'hourly_budget_range_'.$key?>">
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $val['hourly_rate_range_value']; ?></small></label>
										</div>																				
										<?php
											}
										?>
									</div>
								</div>
							</div>
							<div class="fProjectlr">
								<div class="multiselect pSelect">
								<?php
											$bold_cat = '';
											$publication_date_list = $this->config->item('find_project_publication_date');
											foreach($publication_date_list as $key => $val) {
												if(!empty($filter_arr) && !empty($filter_arr['publication_dates'])) {
													if($filter_arr['publication_dates'][0] == $key) {
														$bold_cat = 'boldCat';
													}
												} 
											}
									?>
									<div class="selectBox">
										<select class="<?php echo !empty($bold_cat) ? $bold_cat : ''; ?>">
											<option><?php echo $this->config->item('find_project_publication_date_dropdown_option_name'); ?></option>
										</select>
										<div class="overSelect"></div>
									</div>
									<div id="checkboxes6" class="visible_option select_flex_width">
										<?php
											foreach($publication_date_list as $key => $val) {
												$checked = '';
												if(!empty($filter_arr) && !empty($filter_arr['publication_dates'])) {
													if($filter_arr['publication_dates'][0] == $key) {
														$checked = 'checked';
													}
												} else {
													if(strpos($key, 'all') !== false) {
														$checked = 'checked';
													}
												}
										?>
										<div class="drpChk">
											<label for="<?php echo $key; ?>" class="default_checkbox">
												<input type="checkbox" id="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?>>
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?> "><?php echo $val;?></small></label>
										</div>																				
										<?php		
											}
										?>
									</div>
								</div> 
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="filter_wrapper">
							<label class="defaultTag"><label class="checkboxes"><span class="tagFirst"><?php echo $this->config->item('find_project_project_upgrades_dropdown_option_name'); ?></span><small class="tagSecond" style="display:block"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?><i class="fa fa-times" style="display:none" aria-hidden="true"></i></small></label></label><label class="defaultTag"><label class="checkboxes1"><span class="tagFirst"><?php echo $this->config->item('find_project_agreement_dropdown_option_name'); ?></span><small class="tagSecond" style="display:block"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?></small></label></label><label class="defaultTag"><label class="checkboxes5"><span class="tagFirst"><?php echo $this->config->item('find_project_full_time_jobs_salaries_ranges_dropdown_option_name'); ?></span><small class="tagSecond" style="display:block"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?></small></label></label>
							<label class="defaultTag"><label class="checkboxes4"><span class="tagFirst"><?php echo $this->config->item('find_project_project_based_options_dropdown_option_name'); ?></span><small class="tagSecond" style="display:block"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?></small></label></label><label class="defaultTag"><label class="checkboxes2"><span class="tagFirst"><?php echo $this->config->item('find_project_fixed_budget_ranges_dropdown_option_name'); ?></span><small class="tagSecond" style="display:block"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?></small></label></label><label class="defaultTag"><label class="checkboxes3"><span class="tagFirst"><?php echo $this->config->item('find_project_hourly_rate_budgets_ranges_dropdown_option_name'); ?></span><small class="tagSecond" style="display:block"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?></small></label></label><label class="defaultTag"><label class="checkboxes6"><span class="tagFirst"><?php echo $this->config->item('find_project_publication_date_dropdown_option_name'); ?></span><small class="tagSecond" style="display:block"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?></small></label></label><label class="defaultTag"><button class="btn default_btn blue_btn btnBold clear_all_filters clear_all_filter"><?php echo $this->config->item('find_project_clear_filter_btn_text'); ?></button></label>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				</div>
				<!-- Project Details End -->
				<div class="fix-loader">
					<div id="loading">
							<div id="nlpt"></div>
							<div class="msg"><?php echo $this->config->item('newly_posted_projects_loader_display_text'); ?></div>
					</div>
					<div class="no_filter_record" style="display:none;"></div>
				</div>
				<!-- Description Start -->
				<div class="filtered_data">
				<?php 
						if($project_notifications_count > 0) {
							echo $this->load->view('ajax_filtered_listing', ['project_notifications' => $project_notifications, 'project_notifications_count' => $project_notifications_count], true); 
						}
				?>
				<?php
					if(empty($project_notifications) && empty($filter_arr)) {
				?>
				<div class="initialViewNorecord">
					<h4><?php echo $this->config->item('projects_notification_feed_page_no_project_available_message'); ?></h4>
				</div>
				<?php
					}
				?>
				
				</div>
				
				<!-- Description End -->
				<!-- Pagination Start -->
				<div class="pagnOnly" style="display:<?php echo $project_notifications_count > 0 ? 'block' : 'none' ?>">
					<div class="row">
						<div class="no_page_links <?php echo !empty($pagination_links) ? 'col-md-7 col-sm-7 col-12' : 'col-md-12 col-12'; ?>">
							<?php
								$rec_per_page = ($project_notifications_count > $this->config->item('newly_posted_projects_realtime_notification_listing_limit_per_page')) ? $this->config->item('newly_posted_projects_realtime_notification_listing_limit_per_page') : $project_notifications_count;
							?>
							<div class="pageOf">
								<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no"><?php echo !empty($page_no) ? $page_no : '1'; ?></span> - <span class="rec_per_page"><?php echo !empty($record_per_page) ? $record_per_page : $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $project_notifications_count; ?></span><?php echo $this->config->item('listings_pagination_txt') ?></label>
							</div>
						</div>
						<div class="page_links col-md-5 col-sm-5 col-12" style="display:<?php echo !empty($pagination_links) ? 'block' : 'none'; ?>">
							<div class="modePage"><?php echo $pagination_links; ?></div>
						</div>
					</div>
				</div>
				<!-- Pagination End -->				
			</div>
			</div>
		</div>
		</div>
	</div>
</div>
<!-- Script Start -->
		<script>
		var find_project_starting_position_to_search = '<?php echo $this->config->item("find_project_starting_position_to_search");?>';
		var find_project_starting_position_to_location_search = '<?php echo $this->config->item("find_project_starting_position_to_location_search");?>';
		var find_project_location_search_dropdown_results_suggestion_limit = '<?php echo $this->config->item("find_project_location_search_dropdown_results_suggestion_limit");?>';
		var find_project_search_keyword_placeholder = '<?php echo $this->config->item("find_project_search_keyword_placeholder");?>';
		var find_project_search_locality_placeholder = '<?php echo $this->config->item("find_project_search_locality_placeholder");?>';
		var task;
		var data = '<?php echo json_encode($locations); ?>';
		var category_arr = [];
		var parent_cate_arr = [];
		var real_search_txt = '';
		var page = 1;
		var find_project_show_more_subcategories_text = '<?php echo $this->config->item("find_project_show_more_subcategories_text");?>';
		var find_project_show_less_subcategories_text = '<?php echo $this->config->item("find_project_show_less_subcategories_text");?>';
		var find_project_show_more_categories_text = '<?php echo $this->config->item("find_project_show_more_categories_text");?>';
		var find_project_show_less_categories_text = '<?php echo $this->config->item("find_project_show_less_categories_text");?>';
		var find_project_show_more_search_options_text = '<?php echo $this->config->item("find_project_show_more_search_options_text");?>';
		var find_project_hide_extra_search_options_text = '<?php echo $this->config->item("find_project_hide_extra_search_options_text");?>';

		var newly_posted_projects_loader_progressbar_display_time = '<?php echo $this->config->item("newly_posted_projects_loader_progressbar_display_time");?>';
		</script>
		<script src="<?php echo JS; ?>modules/find_project_tagsinput_logged_off.js"></script>
    <!-- <script src="<?php echo JS.'modules/newly_posted_projects_realtime_notifications.js'?>" type="text/javascript"></script> -->
	<!-- Bottom Div Closed Script Start -->
	<script>
		//get data pass to json
		task = new Bloodhound({
			// initialize: false,
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace("text"),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			local: JSON.parse(data) //your can use json type
		});
		task.initialize();
		var elt = $('#autocomplete');
		elt.tagsinput({
			itemValue: "value",
			itemText: "text",
			typeaheadjs: {
				minlength : parseInt(find_project_starting_position_to_location_search),
				name: "task",
				displayKey: "text",
				source: task.ttAdapter(),
				limit : parseInt(find_project_location_search_dropdown_results_suggestion_limit)
			}
		});
		$('.closeDiv').on('click', function(){
			$(this).closest("#clientsWrapper").remove();
		});

		
		
	</script>
	<!-- Bottom Div Closed Script End -->
	
<!-- Script End -->