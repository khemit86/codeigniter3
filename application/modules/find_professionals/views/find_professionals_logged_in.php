<?php
$user = $this->session->userdata('user');
?>	
<div class="dashTop">
	<?php
		$filter_arr = $this->session->userdata('filter_arr');
		$this->session->unset_userdata('filter_arr');
		
		$hire_me_user_id = $this->session->userdata('hire_me_user_id');
		$this->session->unset_userdata('hire_me_user_id');
		
		$page = $this->session->userdata('page');
    $this->session->unset_userdata('page');
	?>
	<!-- Middle Section Start -->
	<div id="find_professional_page" class="leftRightAdjust">
		<div class="row">		
			<!-- Left Section Start -->
					<?php
						if(!empty($categories)) {
					?>
			<div class="col-md-4 col-sm-4 col-12 fjLft">						
				<div class="fjLeft">
					<div class="proCat topResetSec">
						<div class="caFilter">
							<button type="button" class="btn default_btn blue_btn btnBold btn_style_5_10 btnClearAll"><?php echo $this->config->item('find_professionals_clear_category_btn_text'); ?></button>
							<input type="hidden" id="moreCatExpand" value="1">
						</div>
						<h4><a class="rcv_notfy_btn" onclick="showMoreCatExpand()"><?php echo $this->config->item('find_professionals_show_more_professionals_categories_menu_name'); ?></a></h4>
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
								<button type="button" class="btn default_btn blue_btn btnBold btn_style_5_10 btnClearAll"><?php echo $this->config->item('find_professionals_clear_category_btn_text'); ?></button>
							</div>
							<h4><span class="rcv_notfy_btn"><?php echo $this->config->item('find_professionals_show_more_professionals_categories_menu_name'); ?></span></h4>
						</div>
						<div id="mainCategory" class="chkSameLine">						
							<label class="default_checkbox"><input type="checkbox" id="allCategory" <?php echo $checked; ?>><span class="checkmark"></span><small class="mainCatText <?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $this->config->item('find_professionals_professionals_categories_all_categories_txt'); ?></small></label></div><?php
							}
						?><?php
							$remainingCat = count($categories)-$this->config->item('find_professionals_maximum_categories_show');
							$showmore_category = str_replace('{remaining_category}', $remainingCat, $this->config->item('find_professionals_show_more_categories_text'));
							$m = 0;
							foreach($categories as $key => $parent) {
								$m++;
								if($m == ($this->config->item('find_professionals_maximum_categories_show')+1)) { 
									echo '<input type="hidden" class="moreCat" value="1"><input type="hidden" class="rCat" value="'.$remainingCat.'"><section id="catDiv" class="chkSameLine" style="display:none">'; 
								}
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
							<label class="default_checkbox"><input type="checkbox" id="ckbCheckAlltoggle<?php echo $key+1; ?>" value="<?php echo $parent['id'];?>" <?php echo (!empty($filter_arr) && is_array($filter_arr['parent_categories']) && in_array($parent['id'], $filter_arr['parent_categories'])) || $flag ? 'checked' : '';?> class="CheckAllClass1 parent"><span class="checkmark"></span><small class="mainCatText <?php echo (!empty($filter_arr) && is_array($filter_arr['parent_categories']) && in_array($parent['id'], $filter_arr['parent_categories'])) || $flag ? 'boldCat' : '';?>"><?php echo $parent['name']; ?></small></label><?php
								if(!empty($parent['child'])) {
									$remainingSubCat = count($parent['child'])-$this->config->item('find_professionals_maximum_subcategories_show');
									
									$showmore_subcategory = str_replace('{remaining_subcategory}', $remainingSubCat, $this->config->item('find_professionals_show_more_subcategories_text'));
									
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
										if($c == ($this->config->item('find_professionals_maximum_subcategories_show')+1)) {
											$display_child = 'none';
											$subflag = true;
											echo '<input type="hidden" class="moreScat" id="moreScat'.$parent['id'].'" value="1"><input type="hidden" class="rScat'.$parent['id'].'" value="'.$remainingSubCat.'"><aside id="parent'.$parent['id'].'">'; 
										}
										
										// $display_child = 'none';
										// if((!empty($filter_arr) && is_array($filter_arr['parent_categories']) && in_array($parent['id'], $filter_arr['parent_categories'])) || $flag ) {
										// 	$display_child = 'block';
										// }
							?><div class="colTxt1 child" style="display:<?php echo $display_child;?>"><div class="parTab" id="child<?=$val['id']?>"><label class="default_checkbox"><input type="checkbox" class="checkBoxClass1" <?php echo (!empty($filter_arr) && is_array($filter_arr['categories']) && in_array($val['id'], $filter_arr['categories'])) ? 'checked' : '';?> data-parent="<?php echo $parent['id']; ?>" value="<?php echo $val['id']; ?>"><span class="checkmark"></span><span class="subCatText <?php echo (!empty($filter_arr) && is_array($filter_arr['categories']) && in_array($val['id'], $filter_arr['categories'])) ? 'boldCat' : '';?>"><?php echo $val['name']; ?></span></label></div></div><?php
							if($c == count($parent['child'])) { echo '</aside>';}
								}
							if(count($parent['child']) >$this->config->item('find_professionals_maximum_subcategories_show') ) { echo '<div><label style="display:'.($shmorflg ? 'block' : 'none').'" class="catSeeAll subcat'.$parent['id'].'" onclick="toogleCat('.$parent['id'].', '.$remainingSubCat.')"><i class="fas fa-angle-down"></i> '.$showmore_subcategory.'</label></div>'; }	
							  }
							?>
						</div><?php
							if($m == count($categories)) { echo '</section>';}
							}
							if(count($categories) >$this->config->item('find_professionals_maximum_categories_show') ) { echo '<div class="showmore_category"><label class="catAll" onclick="toogleMcat('.$remainingCat.')"><i class="fas fa-angle-down"></i> '.$showmore_category. '</label></div>'; } 
						?></div>
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
				<!-- Project Details Start -->
				<!-- Search Type Start -->
				<div class="srcType">				
					<div class="row">
						<div class="col-md-6 col-sm-6 col-12 searchSection">
							<div class="form-group">
								<?php
									$search_txt = '';
									if(!empty($filter_arr) && !empty($filter_arr['searchtxt_arr'])) {
										$search_txt = trim(implode(',', $filter_arr['searchtxt_arr']));
									} else if(!empty($filter_arr) && empty($filter_arr['searchtxt_arr']) && !empty($filter_arr['real_time_search_txt'])) {
										$search_txt = trim($filter_arr['real_time_search_txt']);
									}
								?>
								<input type="text" id="search_keyword" class="form-control default_input_field hideField" data-role="tagsinput" value="<?php echo $search_txt; ?>" placeholder="<?php echo empty($search_txt) ? $this->config->item('find_professionals_search_keyword_placeholder') : ''; ?>" />
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-12 pLt0 searchSection">
							<div id="search_area" class="input-group">
								<input type="text" class="form-control default_input_field" id="autocomplete" placeholder="<?php echo $this->config->item('find_professionals_search_locality_placeholder'); ?>">
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
									<label class="typeSrch default_black_bold srcfristLabel"><?php echo $this->config->item('find_professionals_searching_type'); ?></label><span class="chkAdjust"><div class="form-check"><label class="default_checkbox"><?php
												$checked = '';
												if(empty($filter_arr) || (!empty($filter_arr) && !empty($filter_arr['search_type']) && $filter_arr['search_type'] == 'include')) {
													$checked = 'checked';
												}
											?><input type="checkbox" id="include" class="search_type" <?php echo $checked; ?> value="include" ><span class="checkmark"></span><span class="textGap boldCat"><?php echo $this->config->item('find_professionals_searching_type_include_txt'); ?></span></label></div><div class="form-check"><label class="default_checkbox"><?php
												$checked = '';
												if(!empty($filter_arr) && !empty($filter_arr['search_type']) && $filter_arr['search_type'] == 'exclude') {
													$checked = 'checked';
												}
											?><input type="checkbox" id="exclude" class="search_type" <?php echo $checked; ?> value="exclude"><span class="checkmark"></span><span class="textGap"><?php echo $this->config->item('find_professionals_searching_type_exclude_txt'); ?></span></label></div></span><label class="typeSrchLabel">|</label><span class="chkAdjust chkBoxRight"><div class="form-check"><label class="default_checkbox"><?php
												$checked = '';
												if(!empty($filter_arr) && !empty($filter_arr['search_title_flag']) && $filter_arr['search_title_flag'] === "true") {
													$checked = 'checked';
												}
											?><input type="checkbox" id="searchTitle" <?php echo $checked; ?>><span class="checkmark"></span><label class="typeSrch default_black_bold textGap"><?php echo $this->config->item('find_professionals_search_professionals_name'); ?></label></label></div></span>
								</div>
								<div class="receive_notification">
									<a class="rcv_notfy_btn" onclick="showMoreSearch()">
										<?php
											if( empty($filter_arr) ) {
												echo $this->config->item('find_professionals_show_more_search_options_text'); 
											}	else if((!empty($filter_arr['search_more_option']) && $filter_arr['search_more_option'])) {
												echo $this->config->item('find_professionals_show_more_search_options_text'); 
											} else {
												echo $this->config->item('find_professionals_hide_extra_search_options_text'); 
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
								<button type="button" class="btn btn-block default_btn blue_btn search_clear"><?php echo $this->config->item('find_professionals_clear_search_btn_text'); ?></button>
							</div>
							<div class="col-md-2 col-sm-2 col-12 pLt0 srchBtn">
								<button type="button" class="btn btn-block orange_btn default_btn search_btn"><?php echo $this->config->item('find_professionals_search_btn_txt'); ?></button>
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
							<div class="fProfessionallr">
								<div class="multiselect pSelect">
									<?php 
										$account_types = $this->config->item('find_professionals_account_type_dropdown_list_options');
										$bold_cat = '';
											if (!empty($filter_arr) && !empty($filter_arr['account_type'])) {
													if (in_array($filter_arr['account_type'] , [1,2])) {
														$bold_cat = 'boldCat';
													}
											}
									?>
									<div class="selectBox">
										<select class="<?php echo !empty($bold_cat) ? $bold_cat : '' ?>">
											<option><?php echo $this->config->item('find_professionals_account_type_dropdown_option_name'); ?></option>
										</select>
										<div class="overSelect"></div>
									</div>
									<div id="checkboxes2" class="visible_option select_flex_width">
										<?php
											foreach($account_types as $key => $val) {
												$checked = '';
												if(!empty($filter_arr) && !empty($filter_arr['account_type'])) {
													if($key == $filter_arr['account_type'])
														$checked = 'checked';
												} else { 
													if(strpos($key, 'all') !== false) {
														$checked = 'checked';
													}
												}
										?>
										<div class="drpChk">
											<label for="<?php echo $key; ?>" class="default_checkbox">
												<input type="checkbox" id="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> />
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : '' ?>"><?php echo $val ?></small></label>
										</div>
										<?php
											}
										?>
									</div>
								</div>
							</div>
							
							<div class="fProfessionallr">
								<div class="multiselect pSelect">
									<div class="selectBox" >
										<select class="<?php echo (!empty($filter_arr['hourly_rate_range'])) ? 'boldCat' : ''; ?>">
											<option><?php echo $this->config->item('find_professionals_hourly_rate_dropdown_option_name'); ?></option>
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
											foreach($hourly_rate_range as $key => $val) {
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
												<input type="checkbox" id="hourly_budget_range_<?php echo $key; ?>" <?php echo $checked; ?> data-min="<?php echo $val['hourly_rate_min_key'];?>" data-max="<?php echo $val['hourly_rate_max_key'];?>" value="<?php echo 'hourly_budget_range_'.$key?>"  />
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : '' ?>"><?php echo $val['hourly_rate_range_value']; ?></small></label>
										</div>
										<?php		
											}
										?>
									</div>
								</div>
							</div>
							
							<?php 
								if(!empty($user_additional_settings) && $user_additional_settings['additional_dropdpwn_on_find_professionals_page'] == 'Y') {
							?>
							<div class="fProfessionallr">
								<div class="multiselect pSelect">
									<div class="selectBox" >
										<select >
											<option><?php echo $this->config->item('find_professionals_profile_last_update_time_dropdown_option_name'); ?></option>
										</select>
										<div class="overSelect"></div>
									</div>
									<div id="checkboxes4" class="visible_option select_full_width">
										<?php 
											$update_times = $this->config->item('find_professionals_profile_last_update_time_dropdown_list_options');
											foreach($update_times as $key => $val) {
												$checked = '';
												if(strpos($key, 'all') !== false) {
													$checked = 'checked';
												}
										?>
										<div class="drpChk">
											<label for="uptime_<?php echo $key; ?>" class="default_checkbox">
												<input type="checkbox" id="uptime_<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> />
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $val ?></small></label>
										</div>
										<?php
											}
										?>
									</div>
								</div>
							</div>

							<div class="fProfessionallr">
								<div class="multiselect pSelect">
									<div class="selectBox" >
										<select >
											<option><?php echo $this->config->item('find_professionals_user_registration_time_dropdown_option_name'); ?></option>
										</select>
										<div class="overSelect"></div>
									</div>
									<div id="checkboxes5" class="visible_option select_flex_width">
										<?php 
											$registration_times = $this->config->item('find_professionals_user_registration_time_dropdown_list_options');
											foreach($registration_times as $key => $val) {
												$checked = '';
												if(strpos($key, 'all') !== false) {
													$checked = 'checked';
												}
										?>
										<div class="drpChk">
											<label for="regitime_<?php echo $key; ?>" class="default_checkbox">
												<input type="checkbox" id="regitime_<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> />
												<span class="checkmark"></span><small class="<?php echo !empty($checked) ? 'boldCat' : ''; ?>"><?php echo $val ?></small></label>
										</div>
										<?php
											}
										?>
									</div>
								</div>
							</div>
							<?php 
								}
							?>

							<div class="clearfix"></div>
						</div>
						
						<div class="filter_wrapper">
							<label class="defaultTag"><label class="checkboxes2"><?php
										$account_types = $this->config->item('find_professionals_account_type_dropdown_list_options');
									?><span class="tagFirst"><?php echo  $this->config->item('find_professionals_account_type_dropdown_option_name');?></span><small class="tagSecond" style="display:<?php echo (empty($filter_arr) || empty($filter_arr['account_type']) || (!empty($filter_arr['account_type']) && !in_array($filter_arr['account_type'], [1,2])) ? 'block' : 'none !important' ) ?>"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?><i class="fa fa-times" aria-hidden="true" style="display:none"></i></small><?php
									if(!empty($filter_arr) && !empty($filter_arr['account_type']) && in_array($filter_arr['account_type'], [1,2])) {
									?><small class="tagSecond" data-id="<?php echo $filter_arr['account_type']; ?>"><?php echo $account_types[$filter_arr['account_type']]; ?><i class="fa fa-times remove_filter" data-id="<?php echo $filter_arr['account_type'] ?>" data-parent="checkboxes2" aria-hidden="true"></i></small><?php
											
										}
									?></label></label><label class="defaultTag"><label class="checkboxes3"><span class="tagFirst"><?php echo  $this->config->item('find_professionals_hourly_rate_dropdown_option_name');?></span><small class="tagSecond" style="display:<?php echo (empty($filter_arr) || empty($filter_arr['hourly_rate_range']) ? 'block' : 'none !important' ) ?>"><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?><i class="fa fa-times" aria-hidden="true" style="display:none"></i></small><?php 
											if(!empty($filter_arr) && !empty($filter_arr['hourly_rate_range'])) 
											{ 
												$min = array_column($filter_arr['hourly_rate_range'], 'min'); 
												foreach($hourly_rate_range as $key => $val) { 
													if(!empty($min) && in_array($val['hourly_rate_min_key'], $min)) { 
										?><small class="tagSecond" data-id="<?php echo 'hourly_budget_range_'.$key; ?>"><?php echo $val['hourly_rate_range_value']; ?><i class="fa fa-times remove_filter" data-id="<?php echo 'hourly_budget_range_'.$key ?>" data-parent="checkboxes3" aria-hidden="true"></i></small><?php
													}
												}
											}
										?></label></label><?php 
								if(!empty($user_additional_settings) && $user_additional_settings['additional_dropdpwn_on_find_professionals_page'] == 'Y') {
							?><label class="defaultTag">
								<label class="checkboxes4"><span class="tagFirst"><?php echo  $this->config->item('find_professionals_profile_last_update_time_dropdown_option_name');?></span><small class="tagSecond" ><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?><i class="fa fa-times" aria-hidden="true" style="display:none"></i></small></label>
							</label><label class="defaultTag">
								<label class="checkboxes5"><span class="tagFirst"><?php echo  $this->config->item('find_professionals_user_registration_time_dropdown_option_name');?></span><small class="tagSecond" ><?php echo $this->config->item('find_project_professionals_all_option_txt'); ?><i class="fa fa-times" aria-hidden="true" style="display:none"></i></small></label>
							</label><?php 
								}
							?><label class="defaultTag"><button class="btn default_btn blue_btn btnBold clear_all_filters clear_all_filter"><?php echo $this->config->item('find_professionals_clear_filter_btn_text'); ?></button></label>
						</div>
						
					</div>
				</div>
				</div>
				<!-- Description Start -->
				<div class="fix-loader" style="display:<?php echo empty($professionals) ? 'flex' : 'none' ?>">
					<div id="loading">
							<div id="nlpt"></div>
							<div class="msg"><?php echo $this->config->item('find_professionals_loader_display_text'); ?></div>
					</div>
					<div class="no_filter_record" style="display:<?php echo empty($professionals) ? 'block' : 'none' ?>">
						<?php
							if(empty($professionals) && empty($filter_arr)) {
						?>
							<div class="initialViewNorecord">
								<?php echo $this->config->item('find_professionals_no_professionals_available_message'); ?>
							</div>
						<?php
							} else if(empty($professionals) && !empty($filter_arr)) {
						?>
							<div class="initialViewNorecord">
								<?php echo $this->config->item('find_professionals_search_no_results_returned_message'); ?>
							</div>
						<?php 		
							}
						?>
					</div>
				</div>
				<div id="find_professional" class="filtered_data">
				<!--<div class="fjDnone">
					<h3>There are no user profiles available at the moment11</h3>
				</div>-->
				<?php
				if(!empty($professionals)){
					foreach($professionals  as $key=>$val)
					{
					/*--------------- for rating and reviews------------- */
					$totalReviews = $val['total_reviews'];
						
					/*--------------- for rating and reviews end------------- */
					
									$descLeng	=	strlen($val['description']);
					/*----------- description show for desktop screen start----*/
					$desktop_cnt            =	0;
					
					if($descLeng <= $this->config->item('find_professionals_user_description_character_limit_dekstop')) {
						$desktop_description	= htmlspecialchars($val['description'], ENT_QUOTES);
					} else {
						//$desktop_description	= substr((htmlspecialchars($val['description'], ENT_QUOTES)),0,$this->config->item('find_professionals_user_description_character_limit_dekstop'));
											$desktop_description	= character_limiter($val['description'],$this->config->item('find_professionals_user_description_character_limit_dekstop'));
											$desktop_restdescription	= nl2br(htmlspecialchars($val['description'], ENT_QUOTES));
										$desktop_cnt = 1;
					}
					/*----------- description show for desktop screen end----*/
					
					/*----------- description show for ipad screen start----*/
					$tablet_cnt            =	0;
					if($descLeng <= $this->config->item('find_professionals_user_description_character_limit_tablet')) {
						$tablet_description	= htmlspecialchars($val['description'], ENT_QUOTES);
					} else {
						//$tablet_description	= substr((htmlspecialchars($val['description'], ENT_QUOTES)),0,$this->config->item('find_professionals_user_description_character_limit_tablet'));
											$tablet_description	= character_limiter($val['description'],$this->config->item('find_professionals_user_description_character_limit_tablet'));
											$tablet_restdescription	= nl2br(htmlspecialchars($val['description'], ENT_QUOTES));
						$tablet_cnt = 1;
					}
					/*----------- description show for ipad screen end----*/
					
					/*----------- description show for mobile screen start----*/
					$mobile_cnt            =	0;
					if($descLeng <= $this->config->item('find_professionals_user_description_character_limit_mobile')) {
						$mobile_description	= htmlspecialchars($val['description'], ENT_QUOTES);
					} else {
										//$mobile_description	= substr((htmlspecialchars($val['description'], ENT_QUOTES)),0,$this->config->item('find_professionals_user_description_character_limit_mobile'));
										$mobile_description	= character_limiter($val['description'],$this->config->item('find_professionals_user_description_character_limit_mobile'));
										$mobile_restdescription	= nl2br(htmlspecialchars($val['description'], ENT_QUOTES));
										$mobile_cnt = 1;
					}
					/*----------- description show for mobile screen end----*/
					?>
					
					<div class="pDtls fTalent <?php echo (!empty($val['featured_user_profile'])) ? 'featured_profile' : ''; ?>">
						<div class="row">
							<div class="col-md-2 col-sm-2 col-12 default_user_avatar_left_adjust">
								<div class="fTProfile">							
									<div class="avtOnly">
										<!--<div id="profile-picture" class="profile-picture" style="background-image: url('<?php //echo URL ?>assets/images/chat/rChat7.png');">
											<span class="actON"></span>
										</div>-->
										<div id="profile-picture" class="default_avatar_image default_avatar_image_size" style="background-image: url('<?php echo $val['user_profile_picture'] ?>');" data-url="<?php echo URL . $val['profile_name']; ?>">
											<!-- <span class="actON"></span> -->
										</div>
									</div>
									<div class="sRate">
										<span><?php echo show_dynamic_rating_stars($val['user_total_avg_rating_as_sp']); ?></span>
										<span class="default_avatar_review Rating Rating--labeled" data-star_rating="<?php echo $val['user_total_avg_rating_as_sp']; ?>"><?php echo $val['user_total_avg_rating_as_sp']; ?></span>
									</div>						
									<div class="rvw">
										<span class="default_avatar_total_review"><?php
												if($totalReviews == 0){
													$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
												}else if($totalReviews == 1) {
													$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
												} else if($totalReviews > 1) {
													$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
												}
												echo $trGiven;
											?>
										</span>
										<?php if(!empty($val['hourly_rate'])) {  ?>
										<small class="default_avatar_complete_project"><?php echo str_replace(".00","",number_format($val['hourly_rate'], 0, '', ' ')).' '.CURRENCY.$this->config->item('find_professionals_user_hourly_rate_per_hour');   ?></small>
										<?php } ?>
										<?php
										if($val['completed_projects_as_sp'] > 0){
										?>
										<small class="default_avatar_complete_project"><?php
										echo $this->config->item('user_completed_projects')." ".number_format($val['completed_projects_as_sp'],0, '.', ' '); ?></small> <?php } ?>
										<?php
										if($val['completed_projects_as_employee'] > 0){
										?>
										<small class="default_avatar_complete_project"><?php
											if(($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y' )){
												if($val['gender'] == 'M'){
													echo $this->config->item('project_details_page_male_user_hires_on_fulltime_projects_as_employee')." ".number_format($val['completed_projects_as_employee'],0, '.', ' ');
												}else{
													echo $this->config->item('project_details_page_female_user_hires_on_fulltime_projects_as_employee')." ".number_format($val['completed_projects_as_employee'],0, '.', ' ');
												}
											
											}else{
												echo $this->config->item('project_details_page_company_user_hires_on_fulltime_projects_as_employee')." ".number_format($val['completed_projects_as_employee'],0, '.', ' ');
											} ?></small> <?php } ?>
										<?php
												if($user_data['profile_name'] != $val['profile_name']) {
										?>
										<div class="fjApply default_applyNow_btn">
											<button type="button" class="btn default_btn blue_btn" data-profile-name="<?php echo $val['profile_name']; ?>"  data-gender="<?php echo $val['gender'] ?>" data-name="<?php echo $val['account_type'] == 1 ? $val['name'] : $val['company_name']; ?>" data-id="<?php echo $key; ?>" data-profile-pic="<?php echo $val['user_profile_picture'] ?>" data-is-in-contact="<?php echo !empty($val['is_in_contact']) ? $val['is_in_contact'] : ''; ?>" id="contactMe" data-toggle="modal" data-target="#hireMe"><?php echo $this->config->item('contactme_button'); ?></button>
										</div>
										<?php
												}
										?>
										<div class="default_project_socialicon">
											<a class="fb_share_profile" data-link="<?php echo $val['fb_share_url']; ?>"><i class="fa fa-facebook"></i></a>
											<a class="twitter_share_profile" data-link="<?php echo $val['twitter_share_url']; ?>" data-title="<?php echo $val['account_type'] == 1 || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y') ? $val['name'] : $val['company_name']; ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(($val['description']), ENT_QUOTES)),$this->config->item('twitter_share_user_profile_description_character_limit')); ?>"><i class="fa fa-twitter"></i></a>
											<a class="ln_share_profile" data-link="<?php echo $val['ln_share_url']; ?>"><i class="fa fa-linkedin"></i></a>
											<a class="share_via_email" data-link="<?php echo $val['email_share_url']; ?>" data-name="<?php echo $val['account_type'] == 1 || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')? $val['name'] : $val['company_name']; ?>" data-profile="<?php echo $val['profile_name']; ?>" data-headline="<?php echo (htmlspecialchars($val['headline'], ENT_QUOTES));	 ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(nl2br($val['description']), ENT_QUOTES)),$this->config->item('find_professionals_email_share_user_descripition_character_limit')); ?>" ><i class="fas fa-envelope"></i></a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-10 col-sm-10 col-12 default_user_details_right_adjust">
								<div class="opLBttm opBg">
									<div class="fpBdr">
										<div class="row">
											<!-- Mobile Version Start -->
											<!-- Avatar Only Start -->
											<div class="col-sm-2 col-12 avatarMobile">
												<div id="profile-picture" class="default_avatar_image default_avatar_image_size" style="background-image: url('<?php echo $val['user_profile_picture'] ?>');"data-url="<?php echo URL . $val['profile_name']; ?>"></div>
											</div>
											<!-- Avatar Only End -->
											<!-- Review Only Start -->
											<div class="col-sm-3 col-12 reviewMobile">
												<div class="sRate">
													<?php
														/* $rVal = explode('.', $reviews);
																$rValInt = $rVal[0];
																$rValFlt = $rVal[1];
																$tStar   = $rValInt;
																if ($rValFlt > 0) {
																	$tStar = $rValInt + 1;
																}
																$rStar = 5 - $tStar; */
													?>
													<span><?php
														echo show_dynamic_rating_stars($val['user_total_avg_rating_as_sp']);
													?></span><span class="default_avatar_review Rating Rating--labeled" data-star_rating="<?php echo $val['user_total_avg_rating_as_sp']; ?>"><?php echo $val['user_total_avg_rating_as_sp']; ?></span>
												</div>
												<div class="rvw">
													<span class="default_avatar_total_review">
														<?php
															
															if($totalReviews == 0){
																$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
															}else if($totalReviews == 1) {
																$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
															} else if($totalReviews > 1) {
																$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
															}
															echo $trGiven;
													?></span>
													<?php if($val['completed_projects_as_sp'] > 0){ ?>
													<small class="default_avatar_complete_project"><?php
											echo $this->config->item('user_completed_projects')." ".number_format($val['completed_projects_as_sp'],0, '.', ' '); ?></small> <?php } ?>
											<?php if($val['completed_projects_as_employee'] > 0){ ?>
											<small class="default_avatar_complete_project"><?php
											if(($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y' )){
												if($val['gender'] == 'M'){
													echo $this->config->item('project_details_page_male_user_hires_on_fulltime_projects_as_employee')." ".number_format($val['completed_projects_as_employee'],0, '.', ' ');
												}else{
													echo $this->config->item('project_details_page_female_user_hires_on_fulltime_projects_as_employee')." ".number_format($val['completed_projects_as_employee'],0, '.', ' ');
												}
											
											}else{
												echo $this->config->item('project_details_page_company_user_hires_on_fulltime_projects_as_employee')." ".number_format($val['completed_projects_as_employee'],0, '.', ' ');
											} ?></small> <?php } ?>
												</div>

												<div class="default_project_socialicon socialTab">
													<a class="fb_share_profile" data-link="<?php echo $val['fb_share_url']; ?>"><i class="fa fa-facebook"></i></a>
													<a class="twitter_share_profile" data-link="<?php echo $val['twitter_share_url']; ?>" data-title="<?php echo $val['account_type'] == 1 || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y') ? $val['name'] : $val['company_name']; ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(($val['description']), ENT_QUOTES)),$this->config->item('twitter_share_user_profile_description_character_limit')); ?>"><i class="fa fa-twitter"></i></a>
													<a class="ln_share_profile" data-link="<?php echo $val['ln_share_url']; ?>"><i class="fa fa-linkedin"></i></a>
													<a class="share_via_email" data-link="<?php echo $val['email_share_url']; ?>" data-name="<?php echo $val['account_type'] == 1 || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')? $val['name'] : $val['company_name']; ?>" data-profile="<?php echo $val['profile_name']; ?>" data-headline="<?php echo (htmlspecialchars($val['headline'], ENT_QUOTES));	 ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(nl2br($val['description']), ENT_QUOTES)),$this->config->item('find_professionals_email_share_user_descripition_character_limit')); ?>" ><i class="fas fa-envelope"></i></a>
												</div>
											</div>
											<!-- Review Only End -->
											<!-- Contact Only Start -->
											<div class="col-sm-7 col-12 contactTab">
												<!-- <div class="fjApply default_applyNow_btn">
													<button type="button" class="btn default_btn blue_btn login_popup" data-page-no="<?php echo $page; ?>" data-id="<?php echo $key; ?>" data-page-type-attr="<?php echo $current_page; ?>" ><?php echo $this->config->item('contactme_button'); ?></button>
												</div> -->
												<?php
														if($user_data['profile_name'] != $val['profile_name']) {
												?>
												<div class="fjApply default_applyNow_btn">
													<button type="button" class="btn default_btn blue_btn" data-profile-name="<?php echo $val['profile_name']; ?>"  data-gender="<?php echo $val['gender'] ?>" data-name="<?php echo $val['account_type'] == 1 || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y') ? $val['name'] : $val['company_name']; ?>" data-id="<?php echo $key; ?>" data-profile-pic="<?php echo $val['user_profile_picture'] ?>" data-is-in-contact="<?php echo !empty($val['is_in_contact']) ? $val['is_in_contact'] : ''; ?>" id="contactMe" ><?php echo $this->config->item('contactme_button'); ?></button>
												</div>
												<?php
														}
												?>
											</div>
											<!-- Contact Only End -->
											<!-- Mobile Version End -->
											<div class="col-md-12 col-sm-12 col-12 userMobile">
												<p class="default_user_name">
													<a class="default_user_name_link" target="_blank" href="<?php echo URL.$val['profile_name']; ?>"><?php echo (($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) ?  $val['name'] : $val['company_name'];?></a>
												</p>
												<?php if($val['headline']!='') { ?>
												<div class="headline_title"><?php echo htmlspecialchars($val['headline'], ENT_QUOTES);?></div>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="default_user_description desktop-secreen fpBtop default_headline_gap">
										<p id="desktop_lessD<?php echo $key; ?>">
											<?php echo $desktop_description;?><?php if($desktop_cnt==1) {?><span id="desktop_dotsD<?php echo $key; ?>"></span><button onclick="showMoreDescription('desktop', <?php echo $key; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
											<?php } ?></p><p id="desktop_moreD<?php echo $key; ?>" class="moreD">
											<?php echo $desktop_restdescription;?>
											<button onclick="showMoreDescription('desktop', <?php echo $key; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
										</p>									
									</div>
									<div class="default_user_description ipad-screen fpBtop default_headline_gap">
										<p id="tablet_lessD<?php echo $key; ?>">
											<?php echo $tablet_description;?><?php if($tablet_cnt==1) {?><span id="tablet_dotsD<?php echo $key; ?>"></span><button onclick="showMoreDescription('tablet', <?php echo $key; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
											<?php } ?></p><p id="tablet_moreD<?php echo $key; ?>" class="moreD">
											<?php echo $tablet_restdescription;?>
											<button onclick="showMoreDescription('tablet', <?php echo $key; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
										</p>																	   
									</div>
									<div class="default_user_description mobile-screen fpBtop default_headline_gap">
										<p id="mobile_lessD<?php echo $key; ?>">
											<?php echo $mobile_description;?><?php if($mobile_cnt==1) {?><span id="mobile_dotsD<?php echo $key; ?>"></span><button onclick="showMoreDescription('mobile', <?php echo $key; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
											<?php } ?></p><p id="mobile_moreD<?php echo $key; ?>" class="moreD">
											<?php echo $mobile_restdescription;?>
											<button onclick="showMoreDescription('mobile', <?php echo $key; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
										</p>
									</div>
									<!-- Hourly Rate Only Mobile Version Start -->
									<?php if (!empty($val['hourly_rate'])) {?>
									<div class="hrateMobile">
										<i class="fa fa-money" aria-hidden="true"></i><small class="default_avatar_complete_project"><?php echo str_replace(".00","",number_format($val['hourly_rate'], 0, '', ' ')) . ' ' . CURRENCY . $this->config->item('find_professionals_user_hourly_rate_per_hour'); ?></small>
									</div>
									<?php }?>
									<!-- Hourly Rate Only Mobile Version End -->
								</div>
								<div class="clearfix"></div>
								<!-- Hourly Rate Only Desktop Version Start -->
								<?php if (!empty($val['hourly_rate'])) {?>
									<div class="hrateDesktop">
										<i class="fa fa-money" aria-hidden="true"></i><small class="default_avatar_complete_project"><?php echo str_replace(".00","",number_format($val['hourly_rate'], 0, '', ' ')) . ' ' . CURRENCY . $this->config->item('find_professionals_user_hourly_rate_per_hour'); ?></small>
									</div>
								<?php }?>
									<!-- Hourly Rate Only Desktop Version End -->
								<?php 
								if(!empty($val['country_name'])) {
								$country_flag = ASSETS .'images/countries_flags/'.strtolower($val['country_code']).'.png';
								?>
								<div class="default_user_location">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-12">
											<span><i class="<?php echo $val['account_type'] == 1 || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y') ? 'fas fa-map-marker-alt' : 'far fa-building'; ?>" aria-hidden="true"></i></span><?php
											$address_details = '';
											if(!empty($val['street_address'])){
												if(!preg_match('/\s/',$val['street_address'])) {
													$address_details .= '<small class="street_address_nospace default_black_bold_bigger">'.htmlspecialchars($val['street_address'], ENT_QUOTES).',</small>';
												} else {
													$address_details .= '<small class="default_black_bold_bigger">'.htmlspecialchars($val['street_address'], ENT_QUOTES).',</small>';
												}
											}
											
											if(!empty($val['locality']) && !empty($val['postal_code'])){
												$address_details .= '<small class="default_black_bold_bigger">'.$val['locality'].' '.$val['postal_code'].',</small>';
											}
											if(empty($val['locality']) && !empty($val['postal_code'])){
												$address_details .= '<small class="default_black_bold_bigger"> '.$val['postal_code'].',</small>';
											}
											if(!empty($val['locality']) && empty($val['postal_code'])){
												$address_details .= '<small class="default_black_bold_bigger">'.$val['locality'].',</small>';
											}
											if(!empty($val['county'])){
												$address_details .= '<small class="default_black_bold_bigger">'.$val['county'].',</small>';
											}
											$address_details  .= '<small class="default_black_bold_bigger">'.$val['country_name'].'<div class="default_user_location_flag" style="background-image: url('.$country_flag.')"></div></small>';
											echo $address_details;
											?>
											
											
											<?php
											/* <small><?php echo $val['locality']; ?></small><small><?php echo $val['postal_code']; ?>,</small><small><?php echo $val['county'] ?></small> 
												
											*/
											?>
											
											
										</div>
									</div>
								</div>
								<?php
									}
								?>
								<?php if(count($val['professional_category']) > 0) { ?>
								<div class="row">
									<div class="col-md-12 col-sm-12 col-12">
										<div class="default_project_category" id="cat<?php echo $key; ?>">
										<?php
											$catRowVal = 0;
											$c = 0;
											foreach($val['professional_category'] as $cat=>$scatArr)
											{
												$c++;
												$class = '';
												if(count($val['professional_category']) > $this->config->item('find_professionals_areas_of_expertise_show_more_less') && $c > $this->config->item('find_professionals_areas_of_expertise_show_more_less')) {
													$class = ' moreCat';
												}
												if(is_array($scatArr)) {
													$sclass = '';
													if(count($scatArr)<2) {
														$sclass = ' catSub12';
													}
													if(count($scatArr)==2) {
														$sclass = ' catSub3';
													}
												} else {
													$sclass = ' catSub12';
												}
												
										?>
										<div class="clearfix<?php echo $class.$sclass; ?>">
											<small class="pSmnu"><?php echo $cat  ;?></small>
											<?php 
												if(is_array($scatArr)) {
												foreach($scatArr as $subcat) { 
											?>
												<a href="#">
													<span><?php echo is_array($subcat) ? $subcat[1] : $subcat; ?></span>
												</a>
											<?php 
													} 
												} 
											?>
										</div>
										<?php 
											}
											if(count($val['professional_category']) > $this->config->item('find_professionals_areas_of_expertise_show_more_less') && $c > $this->config->item('find_professionals_areas_of_expertise_show_more_less')) 
											{
												$catRowVal = 1;
										?>
											
											<div class="show_more_less"><button onclick="showMoreCat(<?php echo $key; ?>)" id="cat_myBtnD<?php echo $key; ?>"><?php echo $this->config->item('show_more_txt'); ?></button></div>
											<?php
												}
											?>
											<input type="hidden" id="catRow<?php echo $key; ?>" value="<?php echo $catRowVal;?>">
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
								<?php } ?>
								<!-- Contact Only Mobile Version Start -->
								<div class="row">
									<div class="col-12 socialMobile">
										<div class="default_project_socialicon">
											<a class="fb_share_profile" data-link="<?php echo $val['fb_share_url']; ?>"><i class="fa fa-facebook"></i></a>
											<a class="twitter_share_profile" data-link="<?php echo $val['twitter_share_url']; ?>" data-title="<?php echo $val['account_type'] == 1 ? $val['name'] : $val['company_name']; ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(($val['description']), ENT_QUOTES)),$this->config->item('twitter_share_user_profile_description_character_limit')); ?>"><i class="fa fa-twitter"></i></a>
											<a class="ln_share_profile" data-link="<?php echo $val['ln_share_url']; ?>"><i class="fa fa-linkedin"></i></a>
											<a class="share_via_email" data-link="<?php echo $val['email_share_url']; ?>" data-name="<?php echo $val['account_type'] == 1 ? $val['name'] : $val['company_name']; ?>" data-profile="<?php echo $val['profile_name']; ?>" data-headline="<?php echo (htmlspecialchars($val['headline'], ENT_QUOTES));	 ?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(nl2br($val['description']), ENT_QUOTES)),$this->config->item('find_professionals_email_share_user_descripition_character_limit')); ?>" ><i class="fas fa-envelope"></i></a>
										</div>
									</div>
									<div class="col-12 contactMobile">
									<?php									
									if($user_data['profile_name'] != $val['profile_name']) {
										?>
										<div class="fjApply default_applyNow_btn">
											<button type="button" class="btn default_btn blue_btn login_popup" data-page-no="<?php echo $page; ?>" data-id="<?php echo $key; ?>" data-page-type-attr="<?php echo $current_page; ?>"	data-profile-name="<?php echo $val['profile_name']; ?>"  data-gender="<?php echo $val['gender'] ?>" data-name="<?php echo $val['account_type'] == 1 || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y') ? $val['name'] : $val['company_name']; ?>"  data-profile-pic="<?php echo $val['user_profile_picture'] ?>" data-is-in-contact="<?php echo !empty($val['is_in_contact']) ? $val['is_in_contact'] : ''; ?>" id="contactMe"><?php echo $this->config->item('contactme_button'); ?></button>
										</div>
										<?php
										 }
									    ?> 
									</div>
								</div>
								<!-- Contact Only Mobile Version End -->
							</div>
						</div>
					</div>
					<?php  }
				}
				else{
				?>
				
				<?php } ?>

				
				</div>
				<!-- Description End -->
				<!-- Project Details End -->
				<!-- Pagination Start -->
				
				<div class="pagnOnly" style="display:<?php echo !empty($professionals) ? 'block' : 'none';?>">
					<div class="row">
						<div class="no_page_links <?php echo !empty($pagination_links) ? 'col-md-7 col-sm-7 col-12' : 'col-md-12 col-12'; ?>">
							<?php
								$rec_per_page = ($professionals_count > $this->config->item('find_professionals_listing_limit_per_page')) ? $this->config->item('find_professionals_listing_limit_per_page') : $professionals_count;
							?>
							<div class="pageOf">
								<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no"><?php echo !empty($page_no) ? $page_no : '1'; ?></span> - <span class="rec_per_page"><?php echo !empty($record_per_page) ? $record_per_page  : $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $professionals_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
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
				
				<!-- Pagination End -->
			</div>
			<!-- <div class="col-md-1 col-sm-1 col-12"></div> -->
		</div>
	</div>
	<!-- Middle Section End -->
</div>
<div class="disable_content_category"></div>
<script>
	
var show_more_txt = '<?php echo $this->config->item('show_more_txt'); ?>';
var show_less_txt = '<?php echo $this->config->item('show_less_txt'); ?>';
var find_professionals_show_more_search_options_text = '<?php echo $this->config->item("find_professionals_show_more_search_options_text");?>';
var find_professionals_hide_extra_search_options_text = '<?php echo $this->config->item("find_professionals_hide_extra_search_options_text");?>';

var find_professional_starting_position_to_search = '<?php echo $this->config->item("find_professionals_starting_position_to_search");?>';
var find_professional_starting_position_to_location_search = '<?php echo $this->config->item("find_professionals_starting_position_to_location_search");?>';
var find_professional_location_search_dropdown_results_suggestion_limit = '<?php echo $this->config->item("find_professionals_location_search_dropdown_results_suggestion_limit");?>';
var find_professionals_search_keyword_placeholder = '<?php echo $this->config->item('find_professionals_search_keyword_placeholder');?>';
var find_professionals_search_locality_placeholder = '<?php echo $this->config->item('find_professionals_search_locality_placeholder') ?>';
var data = '<?php echo json_encode($locations); ?>';
var category_arr = [];
var parent_cate_arr = [];
var real_search_txt = '';
var page = '<?php echo !empty($page) ? $page : '1' ?>';
var find_professionals_show_more_subcategories_text = '<?php echo $this->config->item("find_professionals_show_more_subcategories_text");?>';
var find_professionals_show_less_subcategories_text = '<?php echo $this->config->item("find_professionals_show_less_subcategories_text");?>';
var find_professionals_show_more_categories_text = '<?php echo $this->config->item("find_professionals_show_more_categories_text");?>';
var find_professionals_show_less_categories_text = '<?php echo $this->config->item("find_professionals_show_less_categories_text");?>';
var find_professionals_contact_popup_title_text = '<?php echo $this->config->item("find_professionals_contact_popup_title_text");?>';

var find_professionals_loader_progressbar_display_time = '<?php echo $this->config->item("find_professionals_loader_progressbar_display_time");?>';

var professionals_count = '<?php echo (empty($professionals) && empty($filter_arr)) ? $professionals_count : 1;?>';

var find_professionals_show_more_professionals_categories_menu_name = '<?php echo $this->config->item("find_professionals_show_more_professionals_categories_menu_name");?>';
var find_professionals_hide_extra_professionals_categories_menu_name = '<?php echo $this->config->item("find_professionals_hide_extra_professionals_categories_menu_name");?>';

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
<script src="<?php echo JS; ?>modules/find_professionals.js"></script>
<script>
var uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';	
//get data pass to json
var task = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace("text"),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  local: jQuery.parseJSON(data) //your can use json type
});

task.initialize();

var elt = $("#autocomplete");
elt.tagsinput({
  itemValue: "value",
  itemText: "text",
  typeaheadjs: {
	minlength : parseInt(find_professional_starting_position_to_search),
    name: "task",
    displayKey: "text",
    source: task.ttAdapter(),
	limit : parseInt(find_professional_location_search_dropdown_results_suggestion_limit)
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
<?php
	$search_txt = '';
	if(!empty($filter_arr) && !empty($filter_arr['searchtxt_arr'])) {
		$search_txt = implode(',', $filter_arr['searchtxt_arr']);
	} else if(!empty($filter_arr) && empty($filter_arr['searchtxt_arr']) && !empty($filter_arr['real_time_search_txt'])) {
		$search_txt = $filter_arr['real_time_search_txt'];
	}
?>
$(function() {
	<?php
		if(!empty($hire_me_user_id)) {
	?>
	hire_me_user_id = '<?php echo $hire_me_user_id; ?>';
	
	if($(".default_applyNow_btn #contactMe[data-id='"+hire_me_user_id+"']").length == 1 && $(".default_applyNow_btn #contactMe[data-id='"+hire_me_user_id+"']").css("display") == 'block') {
		$(".default_applyNow_btn #contactMe[data-id='"+hire_me_user_id+"']").trigger('click');
	}
	if($(".contactTab #contactMe[data-id='"+hire_me_user_id+"']").length == 1 && $(".contactTab #contactMe[data-id='"+hire_me_user_id+"']").css("display") == 'block') {
		$(".contactTab #contactMe[data-id='"+hire_me_user_id+"']").trigger('click');
	}
	<?php
		}
	?>

});

</script>