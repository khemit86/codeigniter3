<?php
$user = $this->session->userdata('user');
if(!empty($user)) {
		$user = $user[0];
}	
?>	
<div class="dashTop">
	<?php
		$filter_arr = $this->session->userdata('filter_arr');
		$this->session->unset_userdata('filter_arr');
	?>
	<!-- Top Section Start -->	
	<!-- Middle Section Start -->
	<div id="find_project_page" class="leftRightAdjust">
		<div class="row">		
			<!-- Left Section Start -->
			<?php
				if(!empty($categories)) {
			?>
			<div class="col-md-4 col-sm-4 col-12 fjLft">
				<div class="fjLeft">
					<div class="proCat topResetSec">
						<div class="caFilter">
							<button type="button" class="btn default_btn blue_btn btnBold btn_style_5_10 btnClearAll"><?php echo $this->config->item('find_project_clear_category_btn_text'); ?></button>
							<input type="hidden" id="moreCatExpand" value="1">
						</div>
						<h4><a class="rcv_notfy_btn" onclick="showMoreCatExpand()"><?php echo $this->config->item('find_project_show_more_project_categories_menu_name'); ?></a></h4>
					</div>
					<div class="mobileModalFixed">
						<div id="deskFilter" class="mobileModalCenter">
							<div>
								<div class="mobileModalHeader"><span onclick="showMoreCatExpand()">×</span></div>
								<div class="proCat" id="leftSideCatSec">
									<?php
										if(count($categories) > 2) {
											$checked = '';
											if(empty($filter_arr) || ((!is_array($filter_arr['categories']) && !is_array($filter_arr['parent_categories'])))) {
												$checked = 'checked';
											}
									?>
									<div class="proCat topResetSec mobFilter">
										<div class="caFilter">
											<button type="button" class="btn default_btn blue_btn btnBold btn_style_5_10 btnClearAll"><?php echo $this->config->item('find_project_clear_category_btn_text'); ?></button>
										</div>
										<h4><span class="rcv_notfy_btn"><?php echo $this->config->item('find_project_show_more_project_categories_menu_name'); ?></span></h4>
									</div>
									<div id="mainCategory" class="chkSameLine"><label class="default_checkbox"><input type="checkbox" id="allCategory" <?php echo $checked; ?>><span class="checkmark"></span><small class="mainCatText <?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $this->config->item('find_project_project_categories_all_categories_txt'); ?></small></label></div><?php
										}
									?><?php
															$remainingCat = count($categories)-$this->config->item('find_project_maximum_categories_show');
															$showmore_category = str_replace('{remaining_category}', $remainingCat, $this->config->item('find_project_show_more_categories_text'));
															$m = 0;
										foreach($categories as $key => $parent) {
																	$m++;
																if($m == ($this->config->item('find_project_maximum_categories_show')+1)) { echo '<input type="hidden" class="moreCat" value="1"><input type="hidden" class="rCat" value="'.$remainingCat.'"><section id="catDiv" class="chkSameLine" style="display:none">'; }
											$child_ids = array_column($parent['child'], 'id');
											$flag = false;
											if(!empty($child_ids)) {
												foreach($child_ids as $val) {
													if((!empty($filter_arr) && is_array($filter_arr['categories']) && in_array($val, $filter_arr['categories']))) {
														$flag = true;
														break;
													}
												}
											}
									?><div class="pmTxt chkSameLine">
										<label class="default_checkbox"><input type="checkbox" id="ckbCheckAlltoggle<?=$key+1?>" value="<?=$parent['id']?>" <?php echo (!empty($filter_arr) && is_array($filter_arr['parent_categories']) && in_array($parent['id'], $filter_arr['parent_categories'])) || $flag ? 'checked' : '';?> class="CheckAllClass1 parent"><span class="checkmark"></span><small class="mainCatText <?php echo (!empty($filter_arr) && is_array($filter_arr['parent_categories']) && in_array($parent['id'], $filter_arr['parent_categories'])) || $flag ? 'boldCat' : '';?>"><?php echo $parent['name']; ?></small></label><?php
											if(!empty($parent['child'])) {
												$remainingSubCat = count($parent['child'])-$this->config->item('find_project_maximum_subcategories_show');
												
												$showmore_subcategory = str_replace('{remaining_subcategory}', $remainingSubCat, $this->config->item('find_project_show_more_subcategories_text'));
												$showless_subcategory = $this->config->item('find_project_show_less_subcategories_text');
												
												$c = 0;
												$subflag = false;
												$shmorflg = false;
												foreach($parent['child'] as $val) {
													$c++;
													if(!$subflag) {
														$display_child = 'none';
														if((!empty($filter_arr) && is_array($filter_arr['parent_categories']) && in_array($parent['id'], $filter_arr['parent_categories'])) || $flag ) {
															$display_child = 'block';
															$shmorflg = true;
														}
													}
													if($c == ($this->config->item('find_project_maximum_subcategories_show')+1)) { 
														$display_child = 'none';
														$subflag = true;
														echo '<input type="hidden" class="moreScat" id="moreScat'.$parent['id'].'" value="1"><input type="hidden" class="rScat'.$parent['id'].'" value="'.$remainingSubCat.'"><aside id="parent'.$parent['id'].'">'; 
													}
													
													
										?><div class="colTxt1 child" style="display:<?php echo $display_child;?>"><div class="parTab" id="child<?=$val['id']?>"><label class="default_checkbox"><input type="checkbox" class="checkBoxClass1" <?php echo (!empty($filter_arr) && is_array($filter_arr['categories']) && in_array($val['id'], $filter_arr['categories'])) ? 'checked' : '';?> data-parent="<?=$parent['id']?>" value="<?=$val['id']?>"> <span class="checkmark"></span><span class="subCatText <?php echo (!empty($filter_arr) && is_array($filter_arr['categories']) && in_array($val['id'], $filter_arr['categories'])) ? 'boldCat' : '';?>"><?=$val['name']?></span></label></div></div><?php
										if($c == count($parent['child'])) { echo '</aside>';}
											}
										if(count($parent['child']) >$this->config->item('find_project_maximum_subcategories_show') ) { echo '<div><label style="display:'.($shmorflg ? 'block' : 'none').'" class="catSeeAll subcat'.$parent['id'].'" onclick="toogleCat('.$parent['id'].', '.$remainingSubCat.')"><i class="fas fa-angle-down"></i> '.$showmore_subcategory.'</label></div>'; }		
											}
										?></div><?php
										if($m == count($categories)) { echo '</section>';}
										}
										if(count($categories) >$this->config->item('find_project_maximum_categories_show') ) { echo '<div class="showmore_category"><label class="catAll" onclick="toogleMcat('.$remainingCat.')"><i class="fas fa-angle-down"></i> '.$showmore_category. '</label></div>'; } ?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
				<?php
					}
				?>	
			<!-- Left Section End -->
			<div class="<?php if(!empty($categories)) { echo 'col-md-8 col-sm-8 '; } else { echo 'col-md-8 col-sm-8 offset-md-2 offset-sm-2 '; } ?>col-12 fjRgt">			
				<!-- Search Type Start -->
				<div class="srcType">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-12 searchSection">
							<div class="form-group">							
								<?php
									$search_txt = '';
									if(!empty($filter_arr) && !empty($filter_arr['searchtxt_arr'])) {
										$search_txt = implode(',', $filter_arr['searchtxt_arr']);
									} else if(!empty($filter_arr) && empty($filter_arr['searchtxt_arr']) && !empty($filter_arr['real_time_search_txt'])) {
										$search_txt = $filter_arr['real_time_search_txt'];
									}
								?>
								<input type="text" id="search_keyword" class="form-control default_input_field hideField" value="<?php echo !empty($search_txt) ? $search_txt : ''  ?>" data-role="tagsinput" placeholder="<?php echo empty($search_txt) ? $this->config->item('find_project_search_keyword_placeholder') : ''; ?>" />
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
											?><input type="checkbox" id="searchTitle" <?php echo $checked; ?>><span class="checkmark"></span><label class="typeSrch default_black_bold textGap"><?php echo $this->config->item('find_project_search_project_title'); ?></label></label>
									</div></span>
								</div>
								<div class="receive_notification">
									<!-- <span class="rcv_notfy_btn" data-toggle="collapse" data-target="#rcv_notfy">show more search options <small><i class="fas fa-plus"></i></small></span></span> -->
									
									<a class="rcv_notfy_btn" onclick="showMoreSearch()">
										<?php 
											if( empty($filter_arr) ) {
												echo $this->config->item('find_project_show_more_search_options_text'); 
											}	else if((!empty($filter_arr['search_more_option']) && $filter_arr['search_more_option'])) {
												echo $this->config->item('find_project_show_more_search_options_text'); 
											} else {
												echo $this->config->item('find_project_hide_extra_search_options_text'); 
											}	
										?>
									</a>
									<?php 
										if((!empty($filter_arr['search_more_option']) && $filter_arr['search_more_option']) || empty($filter_arr)) {
											echo '<input type="hidden" id="moreSearch" value="1">';
										} else {
											echo '<input type="hidden" id="moreSearch" value="0">';
										}
									?>
								</div>
							</div>
							<div class="col-md-2 col-sm-2 col-12 pLt0 srchBtn">
								<button type="button" class="btn btn-block default_btn blue_btn search_clear"><?php echo $this->config->item('find_project_clear_search_btn_text'); ?></button>
							</div>
							<div class="col-md-2 col-sm-2 col-12 pLt0 srchBtn">
								<button type="button" class="btn btn-block orange_btn default_btn search_btn"><?php echo $this->config->item('find_project_search_btn_txt'); ?></button>
							</div>
						</div>
					</div>
				
				<!-- Search Type End -->
				<!-- Project Details Start -->				
				<div class="proDtls">
					<?php
						if( (!empty($filter_arr['search_more_option']) && $filter_arr['search_more_option']) || empty($filter_arr)) { 
							echo '<div id="rcv_notfy" class="pDtls" style="display:none">';
						} else {
							echo '<div id="rcv_notfy" class="pDtls">';
						}
					?>
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
									<div class="selectBox" >
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
												<input type="checkbox" id="<?php echo $key?>" value="<?php echo $key ?>" <?php echo $checked; ?>/>
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
									<div class="selectBox" >
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
												<input type="checkbox" id="<?php echo $key; ?>" value="<?php echo  $key; ?>" <?php echo $checked; ?> />
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?> "><?php echo $val; ?></small></label>
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
										<select class="<?php echo !empty($filter_arr['fulltime_salary_range']) ? 'boldCat' : ''; ?> ">
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
												<input type="checkbox" id="fulltime_salary_range_<?php echo $key; ?>" <?php echo  $checked;?> data-min="<?php echo $val['fulltime_salary_min_range_key'];?>" data-max="<?php echo $val['fulltime_salary_max_range_key'];?>" value="<?php echo 'fulltime_salary_range_'.$key?>" />
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?> "><?php echo $val['fulltime_salary_range_value']; ?></small></label>
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
									<div class="selectBox" >
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
												<input type="checkbox" id="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?>/>
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?> "><?php echo $val;?></small></label>
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
									<div class="selectBox" >
										<select class="<?php echo !empty($filter_arr['fixed_budget_range']) ? 'boldCat' : ''; ?> ">
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
												<input type="checkbox" id="fixed_budget_range_<?php echo $key; ?>" <?php echo $checked; ?> data-min="<?php echo $val['fixed_budget_min_key'];?>" data-max="<?php echo $val['fixed_budget_max_key'];?>" value="<?php echo 'fixed_budget_range_'.$key?>" />
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?> "><?php echo $val['fixed_budget_range_value']; ?></small></label>
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
									<div class="selectBox" >
										<select class="<?php echo !empty($filter_arr['hourly_rate_range']) ? 'boldCat' : ''; ?> ">
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
												<input type="checkbox" id="hourly_budget_range_<?php echo $key; ?>" <?php echo $checked; ?> data-min="<?php echo $val['hourly_rate_min_key'];?>" data-max="<?php echo $val['hourly_rate_max_key'];?>" value="<?php echo 'hourly_budget_range_'.$key?>" />
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?> "><?php echo $val['hourly_rate_range_value']; ?></small></label>
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
									<div class="selectBox" >
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
												<input type="checkbox" id="<?php echo $key; ?>" value="<?php echo $key; ?>"  <?php echo $checked; ?>/>
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
							<label class="defaultTag"><label class="checkboxes"><span class="tagFirst"><?php echo $this->config->item('find_project_project_upgrades_dropdown_option_name'); ?></span><?php
										$upgrades = $this->config->item('find_project_upgrade_list');
									?><small class="tagSecond" style="display:<?php echo (empty($filter_arr) || empty($filter_arr['upgrades']) ? 'block' : 'none !important' ) ?>"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?><i class="fa fa-times" style="display:none" aria-hidden="true"></i></small><?php
										if(!empty($filter_arr) && !empty($filter_arr['upgrades'])) {
											foreach($filter_arr['upgrades'] as $val) {
									?><small class="tagSecond" data-id="<?php echo $val; ?>"><?php echo $upgrades[$val]; ?><i class="fa fa-times remove_filter" data-parent="checkboxes" data-id="<?php echo $val ?>" aria-hidden="true"></i></small><?php
											}
										}
									?></label></label><label class="defaultTag"><label class="checkboxes1"><span class="tagFirst"><?php echo $this->config->item('find_project_agreement_dropdown_option_name'); ?></span><small class="tagSecond" style="display:<?php echo (empty($filter_arr) || $filter_arr['agreement'] == 'agreement_all') ? 'block' : 'none !important' ; ?>"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?></small><?php
										$agreement = $this->config->item('find_project_agreement_list');
										if(!empty($filter_arr) && !empty($filter_arr['agreement']) && $filter_arr['agreement'] != 'agreement_all') {
									?><small class="tagSecond" data-id="<?php echo $filter_arr['agreement']; ?>"><?php echo $agreement[$filter_arr['agreement']]; ?><i class="fa fa-times remove_filter" data-parent="checkboxes1" data-id="<?php echo $filter_arr['agreement'] ?>" aria-hidden="true"></i></small><?php		
										}
									?></label></label><?php
								$display = '';
								if(!empty($filter_arr) && (!empty($filter_arr['agreement']) && $filter_arr['agreement'] == 'project_based')) {
									$display = 'style="display:none"';
								}
							?><label class="defaultTag" <?php echo $display?>><label class="checkboxes5"><span class="tagFirst"><?php echo $this->config->item('find_project_full_time_jobs_salaries_ranges_dropdown_option_name'); ?></span><small class="tagSecond" style="display:<?php echo (empty($filter_arr) || empty($filter_arr['fulltime_salary_range']) ? 'block' : 'none !important' ) ?>"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?></small><?php
										if(!empty($filter_arr) && !empty($filter_arr['fulltime_salary_range'])) {
											$min = array_column($filter_arr['fulltime_salary_range'], 'min');
											foreach($fulltime_salary_range as $key => $val) {
												if(!empty($min) && in_array($val['fulltime_salary_min_range_key'], $min)) {
									?><small class="tagSecond" data-id="<?php echo 'fulltime_salary_range_'.$key; ?>"><?php echo $val['fulltime_salary_range_value']; ?><i class="fa fa-times remove_filter" data-parent="checkboxes5" data-id="<?php echo 'fulltime_salary_range_'.$key ?>" aria-hidden="true"></i></small><?php
												}
											}
										}
									?></label></label><?php
								$display = '';
								if(!empty($filter_arr) && (!empty($filter_arr['agreement']) && $filter_arr['agreement'] == 'contract_fulltime')) {
									$display = 'style="display:none"';
								}
							?><label class="defaultTag" <?php echo $display?>><label class="checkboxes4"><span class="tagFirst"><?php echo $this->config->item('find_project_project_based_options_dropdown_option_name'); ?></span><small class="tagSecond" style="display:<?php echo (empty($filter_arr) || empty($filter_arr['project_types']) ? 'block' : 'none !important' ) ?>"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?></small><?php
										$project_based_options = $this->config->item('find_project_based_option_list');
										if(!empty($filter_arr) && !empty($filter_arr['project_types'])) {
									?><small class="tagSecond" data-id="<?php echo $filter_arr['project_types'][0]; ?>"><?php echo $project_based_options[$filter_arr['project_types'][0]]; ?><i class="fa fa-times remove_filter" data-parent="checkboxes4" data-id="<?php echo $filter_arr['project_types'][0] ?>" aria-hidden="true"></i></small><?php		
										}
									?></label></label><?php
								//$display = 'inline-block';
								$display = '';
								if(!empty($filter_arr) && (!empty($filter_arr['project_types']) && $filter_arr['project_types'][0] == 'hourly') || (!empty($filter_arr['agreement']) && $filter_arr['agreement'] == 'contract_fulltime')) {
									$display = 'style="display:none"';
								}
							?><label class="defaultTag" <?php echo $display?>><label class="checkboxes2"><span class="tagFirst"><?php echo $this->config->item('find_project_fixed_budget_ranges_dropdown_option_name'); ?></span><small class="tagSecond" style="display:<?php echo (empty($filter_arr) || empty($filter_arr['fixed_budget_range']) ? 'block' : 'none !important' ) ?>"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?></small><?php
											if(!empty($filter_arr) && !empty($filter_arr['fixed_budget_range'])) {
													$min = array_column($filter_arr['fixed_budget_range'], 'min');
													foreach($fixed_budget_range as $key => $val) {
															if(!empty($min) && in_array($val['fixed_budget_min_key'], $min)) {
									?><small class="tagSecond" data-id="<?php echo 'fixed_budget_range_'.$key; ?>"><?php echo $val['fixed_budget_range_value']; ?><i class="fa fa-times remove_filter" data-parent="checkboxes2" data-id="<?php echo 'fixed_budget_range_'.$key ?>" aria-hidden="true"></i></small><?php
												}
											}
										}
									?></label></label><?php
								//$display = 'inline-block';
								$display = '';
								if(!empty($filter_arr) && (!empty($filter_arr['project_types']) && $filter_arr['project_types'][0] == 'fixed') || (!empty($filter_arr['agreement']) && $filter_arr['agreement'] == 'contract_fulltime')) {
									//$display = 'none';
									$display = 'style="display:none !important"';
								}
							?><label class="defaultTag" <?php echo $display?>><label class="checkboxes3"><span class="tagFirst"><?php echo $this->config->item('find_project_hourly_rate_budgets_ranges_dropdown_option_name'); ?></span><small class="tagSecond" style="display:<?php echo (empty($filter_arr) || empty($filter_arr['hourly_rate_range']) ? 'block' : 'none !important' ) ?>"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?></small><?php
										if(!empty($filter_arr) && !empty($filter_arr['hourly_rate_range'])) {
											$min = array_column($filter_arr['hourly_rate_range'], 'min');
											foreach($hourly_rate_budget_range as $key => $val) {
												if(!empty($min) && in_array($val['hourly_rate_min_key'], $min)) {
									?><small class="tagSecond" data-id="<?php echo 'hourly_budget_range_'.$key; ?>"><?php echo $val['hourly_rate_range_value']; ?><i class="fa fa-times remove_filter" data-parent="checkboxes3" data-id="<?php echo 'hourly_budget_range_'.$key ?>" aria-hidden="true"></i></small><?php
												}
											}
										}
									?></label></label><label class="defaultTag"><label class="checkboxes6"><span class="tagFirst"><?php echo $this->config->item('find_project_publication_date_dropdown_option_name'); ?></span><small class="tagSecond" style="display:<?php echo (empty($filter_arr) || empty($filter_arr['publication_dates']) ? 'block' : 'none !important' ) ?>"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?></small><?php
										$publication_date_list = $this->config->item('find_project_publication_date');
										if(!empty($filter_arr) && !empty($filter_arr['publication_dates'])) {
									?><small class="tagSecond" data-id="<?php echo $filter_arr['publication_dates'][0]; ?>"><?php echo $publication_date_list[$filter_arr['publication_dates'][0]]; ?><i class="fa fa-times remove_filter" data-parent="checkboxes6" data-id="<?php echo $filter_arr['publication_dates'][0] ?>" aria-hidden="true"></i></small><?php		
											}
									?></label></label><label class="defaultTag"><button class="btn default_btn blue_btn btnBold clear_all_filters clear_all_filter"><?php echo $this->config->item('find_project_clear_filter_btn_text'); ?></button></label>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				</div>
				<!-- Project Details End -->
				<div class="fix-loader" style="display:<?php echo empty($open_bidding_project_data) ? 'flex' : 'none' ?>">
					<div id="loading">
							<div id="nlpt"></div>
							<div class="msg"><?php echo $this->config->item('find_project_loader_display_text'); ?></div>
					</div>
					<div class="no_filter_record" style="display:<?php echo empty($open_bidding_project_data) ? 'block' : 'none' ?>">
						<?php
							if(empty($open_bidding_project_data) && empty($filter_arr)) {
						?>
							<div class="initialViewNorecord">
								<?php echo $this->config->item('find_project_no_project_available_message'); ?>
							</div>
						<?php
							} else if(empty($open_bidding_project_data) && !empty($filter_arr)) {
						?>
							<div class="initialViewNorecord">
								<?php echo $this->config->item('find_project_search_no_results_returned_message'); ?>
							</div>
						<?php 		
							}
						?>
					</div>
				</div>
				<!-- Description Start -->
				<div id="find_project" class="filtered_data">
				<?php
					if(!empty($open_bidding_project_data)) {
						foreach($open_bidding_project_data as $open_bidding_project_key => $open_bidding_project_value){
							if($open_bidding_project_value['project_type'] == 'fulltime'){
								$apply_now_button_text = $this->config->item('fulltime_project_apply_now_button_txt');
							}else{
								$apply_now_button_text = $this->config->item('project_apply_now_button_txt');
							}
							$featured_max = 0;
							$urgent_max = 0;
							$expiration_featured_upgrade_date_array = array();
							$expiration_urgent_upgrade_date_array = array();
							if(!empty($open_bidding_project_value['featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $open_bidding_project_value['featured_upgrade_end_date'];
							}
							if(!empty($open_bidding_project_value['bonus_featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $open_bidding_project_value['bonus_featured_upgrade_end_date'];
							}
							if(!empty($open_bidding_project_value['membership_include_featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $open_bidding_project_value['membership_include_featured_upgrade_end_date'];
							}
							if(!empty($expiration_featured_upgrade_date_array)){
								$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
							}
							
							##########
							
							if(!empty($open_bidding_project_value['urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $open_bidding_project_value['urgent_upgrade_end_date'];
							}
							if(!empty($open_bidding_project_value['bonus_urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $open_bidding_project_value['bonus_urgent_upgrade_end_date'];
							}
							if(!empty($open_bidding_project_value['membership_include_urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $open_bidding_project_value['membership_include_urgent_upgrade_end_date'];
							}
							if(!empty($expiration_urgent_upgrade_date_array)){
								$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
							}
							
							$featured_class = '';
							if($open_bidding_project_value['featured'] == 'Y' && $featured_max > time()){
								$featured_class = 'opBg';
							}
							$location = '';
							if(!empty($open_bidding_project_value['county_name'])){
							if(!empty($open_bidding_project_value['locality_name'])){
								$location .= $open_bidding_project_value['locality_name'];
							}
							if(!empty($open_bidding_project_value['postal_code'])){
								$location .= '&nbsp;'.$open_bidding_project_value['postal_code'] .',&nbsp;';
							}else if(!empty($open_bidding_project_value['locality_name']) && empty($open_bidding_project_value['postal_code'])){
								$location .= ',&nbsp';
							}
								$location .= $open_bidding_project_value['county_name'];
							}
				?>
				<!-- <div class="pDtls "> -->
				<div class="default_project_row <?php echo $featured_class?>">
						<div class="default_project_title">
						<a class="default_project_title_link" target="_blank" href="<?php echo base_url().$this->config->item('project_detail_page_url')."?id=".$open_bidding_project_value['project_id']; ?>">
							<?php echo htmlspecialchars($open_bidding_project_value['project_title'], ENT_QUOTES);?></a>
						</div>
						<label class="default_short_details_field">
							<small><i class="far fa-clock"></i><?php echo $open_bidding_project_value['project_type']== 'fulltime' ? $this->config->item('fulltime_project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($open_bidding_project_value['project_posting_date'])).'</span>' : $this->config->item('project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT,strtotime($open_bidding_project_value['project_posting_date'])).'</span>';?></small><small><i class="fa fa-file-text-o"></i><?php 
							if($open_bidding_project_value['project_type'] == 'fixed'){
								echo $this->config->item('project_listing_window_snippet_fixed_budget_project');
							}else if($open_bidding_project_value['project_type'] == 'hourly'){
								echo $this->config->item('project_listing_window_snippet_hourly_based_budget_project');
							}else if($open_bidding_project_value['project_type'] == 'fulltime'){
								echo $this->config->item('project_listing_window_snippet_fulltime_project');
							}		
								
							if($open_bidding_project_value['confidential_dropdown_option_selected'] == 'Y'){
								if($open_bidding_project_value['project_type'] == 'fixed'){
									echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected').'</span>';
									}else if($open_bidding_project_value['project_type'] == 'hourly'){
									echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_confidential_option_selected').'</span>';
								}else if($open_bidding_project_value['project_type'] == 'fulltime'){
									echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_confidential_option_selected').'</span>';
								}
							}else if($open_bidding_project_value['not_sure_dropdown_option_selected'] == 'Y'){
								if($open_bidding_project_value['project_type'] == 'fixed'){
								echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected').'</span>';
								}else if($open_bidding_project_value['project_type'] == 'hourly'){
								echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_not_sure_option_selected').'</span>';
								}else if($open_bidding_project_value['project_type'] == 'fulltime'){
									echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_not_sure_option_selected').'</span>';
								}
							}else{
								if($open_bidding_project_value['max_budget'] != 'All'){
									if($open_bidding_project_value['project_type'] == 'hourly'){
									
										$budget_range = '';
										if($this->config->item('post_project_budget_range_between')){
											$budget_range .= $this->config->item('post_project_budget_range_between');
										}
										$budget_range .= '<span class="touch_line_break">'.number_format($open_bidding_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($open_bidding_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour').'</span>';
									}else if($open_bidding_project_value['project_type'] == 'fulltime'){
									
										$budget_range = '';
										if($this->config->item('post_project_budget_range_between')){
											$budget_range .= $this->config->item('post_project_budget_range_between');
										}
									
										$budget_range .= '<span class="touch_line_break">'.number_format($open_bidding_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span><span class="touch_line_break">'. $this->config->item('post_project_budget_range_and').'</span><span class="touch_line_break">'.number_format($open_bidding_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_month').'</span>';
									}else{
										$budget_range = '';
										if($this->config->item('post_project_budget_range_between')){
											$budget_range .= $this->config->item('post_project_budget_range_between');
										}
										$budget_range .= '<span class="touch_line_break">'.number_format($open_bidding_project_value['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($open_bidding_project_value['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
									}
								}else{
									if($open_bidding_project_value['project_type'] == 'hourly'){
										$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($open_bidding_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span>';
									}else if($open_bidding_project_value['project_type'] == 'fulltime'){
										$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($open_bidding_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span>';
									}else{
										$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then').'</span><span class="touch_line_break">'.number_format($open_bidding_project_value['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>';
									}
								}
								echo $budget_range;
							}
							
							if($open_bidding_project_value['escrow_payment_method'] == 'Y') {
						echo '<span class="touch_line_break">'.$this->config->item('find_projects_project_payment_method_escrow_system').'</span>';
							}
							if($open_bidding_project_value['offline_payment_method'] == 'Y') {
						echo '<span class="touch_line_break">'.$this->config->item('find_projects_project_payment_method_offline_system').'</span>';
							} ?></small><?php
							if(!empty($location)){
							?><small><i class="fas fa-map-marker-alt"></i><?php echo $location;?></small><?php
							}
							?><?php
							if((!empty($user) && $user->user_id == $open_bidding_project_value['project_owner_id']) || $open_bidding_project_value['sealed'] == 'N' ){
							?><small><i class="fas fa-bullhorn"></i><?php
							$project_bid_count = get_project_bid_count($open_bidding_project_value['project_id'],$open_bidding_project_value['project_type']);
							$bid_history_total_bids = $project_bid_count."&nbsp;";
							if ($open_bidding_project_value['project_type'] == 'fulltime') {
								if($project_bid_count == 0){
									$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_0_applications_received');
								}else if($project_bid_count == 1){
									$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_1_application_received');
								}else if($project_bid_count >= 2 && $project_bid_count <= 4){
									$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_2_to_4_applications_received');
								}else {
									$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_5_or_more_applications_received');
								}
							} else {
								if($project_bid_count == 0){
									$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_0_bids_received');
								}else if($project_bid_count == 1){
									$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_1_bid_received');
								}else if($project_bid_count >=2 && $project_bid_count <=4){
									$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_2_to_4_bids_received');
								}else {
									$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_5_or_more_bids_received');
								}
							}echo $bid_history_total_bids; ?></small><?php
							}
							?></label>
						<?php
						//$description = htmlspecialchars($open_bidding_project_value['project_description'], ENT_QUOTES);
						?>
						<div class="default_project_description desktop-secreen">
							<p><?php 
							echo character_limiter($open_bidding_project_value['project_description'],$this->config->item('find_project_description_character_limit_desktop'));?></p>
						</div>
						<div class="default_project_description ipad-screen">
							<p><?php 
							echo character_limiter($open_bidding_project_value['project_description'],$this->config->item('find_project_description_character_limit_tablet'));?></p>
						</div>
						<div class="default_project_description mobile-screen">
							<p><?php 
							echo character_limiter($open_bidding_project_value['project_description'],$this->config->item('find_project_description_character_limit_mobile'));?></p>
						</div>
										<!-- </div>
					<div class="clearfix"></div> -->
					<?php
						$badgeCount = 0;
						if($open_bidding_project_value['featured'] == 'Y' ) {
							$badgeCount += 1;
						}if($open_bidding_project_value['urgent'] == 'Y') {
							$badgeCount += 1;
						}if($open_bidding_project_value['sealed'] == 'Y') {
							$badgeCount += 1;
						}
						if(!empty($open_bidding_project_value['categories'])) {
					?>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-12">
						<div class="default_project_category text-center">
							<input type="checkbox" onclick="chkFooterSL()" class="read-more-state" id="post-<?php echo $open_bidding_project_value['project_id']; ?>"/>
							<div class="read-more-wrap text-left">                     
								<?php	
								foreach($open_bidding_project_value['categories'] as $category_key=>$category_value){
									if($category_key < $this->config->item('find_projects_categories_show_more_less')){
										if(!empty($category_value['parent_category_name']) && !empty($category_value['category_name'])) {
										?>
										<div class="clearfix catSub12">
											<small class="pSmnu"><?php echo $category_value['parent_category_name']; ?></small>
											<a href="#">
												<span><?php echo $category_value['category_name']; ?></span>
											</a>
										</div>
										<?php
										} else if (!empty($category_value['category_name'])) {
											echo '<small>'.$category_value['category_name'].'</small>'; 
										} else if(!empty($category_value['parent_category_name'])) {
											echo '<small>'.$category_value['parent_category_name'].'</small>'; 
										}
								} else {
									//if($category_key == 2){
									?>
									<!--<div  class="collapse clearfix details-<?php echo $open_bidding_project_value['project_id']; ?>" >-->
									<?php //} ?>
									<?php if(!empty($category_value['parent_category_name']) && !empty($category_value['category_name'])){ ?>
									<div class="clearfix catSub12 read-more-target">
										<small class="pSmnu"><?php echo $category_value['parent_category_name']; ?></small>
										<a href="#">
										<span><?php echo $category_value['category_name']; ?></span>
										</a>
									</div>
									<?php } else if(!empty($category_value['category_name'])) { ?>
										<small class="read-more-target"><?php echo $category_value['category_name']; ?></small>
									<?php } else if(!empty($category_value['parent_category_name'])) { ?>
										<small class="read-more-target"><?php echo $category_value['parent_category_name']; ?></small>
									<?php } ?> 

								<?php

										}
									}
								?>
								</div>
							<?php
									if(count($open_bidding_project_value['categories']) > $this->config->item('find_projects_categories_show_more_less')) {
							?>
							
								<label for="post-<?php echo $open_bidding_project_value['project_id']; ?>" class="read-more-trigger"></label>
															
								
							<?php
									}
							?>
							<div class="clearfix"></div>
						</div>
						
					</div>
					<!--<div class="col-md-6 col-sm-6 col-12 default_applyNow_adjust">
						<?php if($badgeCount<2 && count($open_bidding_project_value['categories'])>$this->config->item('find_projects_categories_show_more_less')) {  ?>
						<div class="row default_applyNow_btn">
							<div class="col-md-6 col-sm-6 col-12">
								<?php if($open_bidding_project_value['featured'] == 'Y'){ ?>
								<button type="button" class="btn badge_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></button>
														<?php } if($open_bidding_project_value['urgent'] == 'Y' ){ ?>
								<button type="button" class="btn badge_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></button>
														<?php } if($open_bidding_project_value['sealed'] == 'Y'){ ?>
								<button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
								<?php } ?>
							</div>
							<div class="col-md-6 col-sm-6 col-12 applyNowRight">
							<?php
								if(!empty($user) && $user->user_id != $open_bidding_project_value['project_owner_id'] && check_sp_active_bid_exists_project($open_bidding_project_value['project_id'],$user->user_id,$open_bidding_project_value['project_type']) == 0) :
							?> 
								<button type="button" class="btn default_btn blue_btn apply_now_button apply_now_logged_in" data-attr="<?php echo $open_bidding_project_value['project_id']; ?>" data-page-type-attr = "listing" ><?php echo $apply_now_button_text; ?></button>
							<?php
								endif;
							?>
							</div>
						</div>
						<?php } ?>
					</div>-->
				</div>
				<?php
				}
				?>
					<?php //if(($badgeCount>=0 && count($open_bidding_project_value['categories'])<=$this->config->item('find_projects_categories_show_more_less')) || ($badgeCount>1 && count($open_bidding_project_value['categories'])>$this->config->item('find_projects_categories_show_more_less'))) {  ?>
					<div class="row social_badge_btn">
						<?php
						if(($open_bidding_project_value['featured'] == 'Y' && $featured_max != 0 && $featured_max > time()) || ($open_bidding_project_value['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time()) || $open_bidding_project_value['sealed'] == 'Y'){
						?>
						<!-- Only Mobile Version Uses Start -->
						<div class="col-md-12 col-sm-12 col-12 <?php echo $badgeCount > 0 ? 'badgeWidthMob' : 'fontSize0' ?>">
							<div class="default_project_badge">
								<?php if($open_bidding_project_value['featured'] == 'Y' && $featured_max != 0 && $featured_max > time()){ ?>
									<button type="button" class="btn badge_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></button>
								<?php } if($open_bidding_project_value['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time() ){ ?>
									<button type="button" class="btn badge_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></button>
								<?php } if($open_bidding_project_value['sealed'] == 'Y'){ ?>
									<button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
								<?php } ?>
							</div>
						</div>
						<!-- Only Mobile Version Uses End -->
						<?php
						}
						?>
						<div class="col-md-2 col-sm-2 col-12 dbSocial">
							<div class="default_project_socialicon">
								<a href="" class="fb_share_project" data-link="<?php echo $open_bidding_project_value['fb_share_url']; ?>"><i class="fa fa-facebook"></i></a>
								<a href="" class="twitter_share_project" data-link="<?php echo $open_bidding_project_value['twitter_share_url']; ?>" data-title="<?php echo htmlspecialchars($open_bidding_project_value['project_title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit(htmlspecialchars($open_bidding_project_value['project_description'], ENT_QUOTES), $this->config->item('twitter_share_project_description_character_limit'));?>"><i class="fa fa-twitter"></i></a>
								<a href="" class="ln_share_project" data-link="<?php echo $open_bidding_project_value['ln_share_url']; ?>" data-title="<?php echo htmlspecialchars($open_bidding_project_value['project_title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit(htmlspecialchars($open_bidding_project_value['project_description'], ENT_QUOTES), $this->config->item('facebook_and_linkedin_share_project_description_character_limit'));?>"><i class="fa fa-linkedin"></i></a>
								<a href="" class="email_share_project" data-link="<?php echo $open_bidding_project_value['email_share_url']; ?>" data-title="<?php echo htmlspecialchars($open_bidding_project_value['project_title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(nl2br($open_bidding_project_value['project_description']), ENT_QUOTES)), $this->config->item('find_project_email_share_project_description_character_limit'));?>"><i class="fas fa-envelope"></i></a>
							</div>
						</div>
						<div class="col-md-7 col-sm-7 col-12 badgeWidth">
							<?php
							if(($open_bidding_project_value['featured'] == 'Y' && $featured_max != 0 && $featured_max > time()) || ($open_bidding_project_value['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time()) || $open_bidding_project_value['sealed'] == 'Y'){
							?>
							<div class="default_project_badge">
								<?php if($open_bidding_project_value['featured'] == 'Y' && $featured_max != 0 && $featured_max > time()){ ?>
									<button type="button" class="btn badge_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></button>
								<?php } if($open_bidding_project_value['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time()){ ?>
									<button type="button" class="btn badge_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></button>
								<?php } if($open_bidding_project_value['sealed'] == 'Y'){ ?>
									<button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
								<?php } ?>
							</div>
							<?php
							}
							?>
						</div>
						<?php
						 if(!empty($user) && $user->user_id != $open_bidding_project_value['project_owner_id'] && check_sp_active_bid_exists_project($open_bidding_project_value['project_id'],$user->user_id,$open_bidding_project_value['project_type']) == 0) :
						?>
						<div class="col-md-3 col-sm-3 col-12 dbApplyNow">
							<div class="default_applyNow_btn">
								<button type="button" class="btn default_btn blue_btn apply_now_button apply_now_logged_in" data-attr="<?php echo $open_bidding_project_value['project_id']; ?>" data-page-type-attr = "listing" ><?php echo $apply_now_button_text; ?></button>
								
							</div>
						</div>
						<?php
							endif;
							?>
					</div>
					<?php //} ?>
				</div>
				<?php
						}
					}
				?>
				
				</div>
				
				<!-- Description End -->
				<!-- Pagination Start -->
				<?php
					// if($open_bidding_project_count > 0) {
				?>
				<div class="pagnOnly" style="display:<?php echo !empty($open_bidding_project_data) ? 'block' : 'none' ?>">
					<div class="row">
						<div class="no_page_links <?php echo !empty($pagination_links) ? 'col-md-7 col-sm-7 col-12' : 'col-md-12 col-12'; ?>">
							<?php
								$rec_per_page = ($open_bidding_project_count > $this->config->item('find_project_listing_limit_per_page')) ? $this->config->item('find_project_listing_limit_per_page') : $open_bidding_project_count;
							?>
							<div class="pageOf">
								<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no"><?php echo !empty($page_no) ? $page_no : '1'; ?></span> - <span class="rec_per_page"><?php echo !empty($record_per_page) ? $record_per_page : $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $open_bidding_project_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
							</div>
						</div>
						<div class="page_links col-md-5 col-sm-5 col-12" style="display:<?php echo !empty($pagination_links) ? 'block' : 'none'; ?>">
							<div class="modePage">
								<?php
									echo $pagination_links;
								?>
							</div>
						</div>
					</div>
				</div>
				<?php
					// }
				?>
				<!-- Pagination End -->
				
			</div>
			<!-- <div class="col-md-1 col-sm-1 col-12"></div> -->
		</div>
	</div>
	<!-- Middle Section End -->
</div>
<div class="disable_content_category"></div>
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

var find_project_loader_progressbar_display_time = '<?php echo $this->config->item("find_project_loader_progressbar_display_time");?>';

var open_bidding_project_count = '<?php echo (empty($open_bidding_project_data) && empty($filter_arr)) ? $open_bidding_project_count : 1;?>';

var find_project_show_more_project_categories_menu_name = '<?php echo $this->config->item("find_project_show_more_project_categories_menu_name");?>';
var find_project_hide_extra_project_categories_menu_name = '<?php echo $this->config->item("find_project_hide_extra_project_categories_menu_name");?>';
<?php
	if(!empty($filter_arr)) {
		
		if(is_array($filter_arr['categories'])) {
?>
		category_arr = JSON.parse('<?php echo json_encode($filter_arr["categories"]); ?>');
<?php			
		} 
?>
	
<?php
		if(is_array($filter_arr['parent_categories'])) {
?>
		parent_cate_arr	= JSON.parse('<?php echo json_encode($filter_arr["parent_categories"]); ?>');
<?php
	}
}
?>
</script>

<script src="<?php echo JS; ?>modules/find_project.js"></script>
<!-- Bootstrap Tag Input JS Start -->
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
	<?php
		if(!empty($filter_arr) && !empty($filter_arr['location'])) {
			foreach($filter_arr['location'] as $val) {
	?>
		elt.tagsinput('add', JSON.parse('<?php echo json_encode($val)?>'));
	<?php
			}
		}
	?>
</script>