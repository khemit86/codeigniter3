<?php
$CI = & get_instance ();
$CI->load->library('Cryptor');
$project_upgrade_type_total_amount = 0;	

if($project_data['featured'] == 'Y'){
	if($user_membership_plan_details->included_number_featured_upgrades != '-1'  && $count_user_featured_membership_included_upgrades_monthly >= $user_membership_plan_details->included_number_featured_upgrades ){
		$project_upgrade_type_total_amount +=  $this->config->item('project_upgrade_price_featured');
	}
}
if($project_data['urgent'] == 'Y'){
	if($user_membership_plan_details->included_number_urgent_upgrades != '-1'  && $count_user_urgent_membership_included_upgrades_monthly >= $user_membership_plan_details->included_number_urgent_upgrades){
		$project_upgrade_type_total_amount +=  $this->config->item('project_upgrade_price_urgent');
	}
}

if($project_data['sealed'] == 'Y'){
	
	if($user_membership_plan_details->included_number_sealed_upgrades != '-1'  && $count_user_sealed_membership_included_upgrades_monthly >= $user_membership_plan_details->included_number_sealed_upgrades){
		$project_upgrade_type_total_amount +=  $this->config->item('project_upgrade_price_sealed');
	}
	
	//$project_upgrade_type_total_amount +=  $this->config->item('project_upgrade_price_sealed');
}

if($project_data['hidden'] == 'Y'){
	//$project_upgrade_type_total_amount += $this->config->item('project_upgrade_price_hidden');
	if($user_membership_plan_details->included_number_hidden_upgrades != '-1'  && $count_user_hidden_membership_included_upgrades_monthly >= $user_membership_plan_details->included_number_sealed_upgrades){
		$project_upgrade_type_total_amount +=  $this->config->item('project_upgrade_price_hidden');
	}
}

$selected_category_array = array();

if(!empty($project_category_data)){
	foreach($project_category_data as $category_key){
		$selected_category_array[] = $category_key['draft_project_category_id'];
	}
}
// standard project availability
$standard_time_arr = explode(":", $this->config->item('standard_project_availability'));
$standard_check_valid_arr = array_map('getInt', $standard_time_arr); 
$standard_valid_time_arr = array_filter($standard_check_valid_arr);
// featured project availability
$featured_time_arr = explode(":", $this->config->item('project_upgrade_availability_featured'));
$featured_check_valid_arr = array_map('getInt', $featured_time_arr); 
$featured_valid_time_arr = array_filter($featured_check_valid_arr);
// urgent project availability
$urgent_time_arr = explode(":", $this->config->item('project_upgrade_availability_urgent'));
$urgent_check_valid_arr = array_map('getInt', $urgent_time_arr); 
$urgent_valid_time_arr = array_filter($urgent_check_valid_arr);
// sealed project availability
$sealed_time_arr = explode(":", $this->config->item('project_upgrade_availability_sealed'));
$sealed_check_valid_arr = array_map('getInt', $sealed_time_arr); 
$sealed_valid_time_arr = array_filter($sealed_check_valid_arr);
// hidden project availability
$hidden_time_arr = explode(":", $this->config->item('project_upgrade_availability_hidden'));
$hidden_check_valid_arr = array_map('getInt', $hidden_time_arr); 
$hidden_valid_time_arr = array_filter($hidden_check_valid_arr);

$upgrades = [];
if(!empty($project_data['featured']) &&  $project_data['featured'] == 'Y') {
	array_push($upgrades, 'featured');
}
if(!empty($project_data['urgent']) &&  $project_data['urgent'] == 'Y' ) {
	array_push($upgrades, 'urgent');
}
if(!empty($project_data['sealed']) &&  $project_data['sealed'] == 'Y') {
	array_push($upgrades, 'sealed');
}
if(!empty($project_data['hidden']) && $project_data['hidden'] == 'Y' ) {
	array_push($upgrades, 'hidden');
}
if($user_details->current_membership_plan_id == 1){
	$po_max_draft_projects_number = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
	$po_max_open_projects_number =  $this->config->item('free_membership_subscriber_max_number_of_open_projects');

	$po_max_draft_fulltime_projects_number = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
	$po_max_open_fulltime_projects_number =  $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
}
if($user_details->current_membership_plan_id == 4){
	$po_max_draft_projects_number =$this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
	$po_max_open_projects_number = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');

	$po_max_draft_fulltime_projects_number =$this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
	$po_max_open_fulltime_projects_number = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
}

$project_featured_upgrade_availability_array = explode(':',$this->config->item('project_upgrade_availability_featured'));
$total_project_featured_upgrade_availability_seconds = ($project_featured_upgrade_availability_array[0] * 3600)+($project_featured_upgrade_availability_array[1] * 60)+$project_featured_upgrade_availability_array[2];
$featured_availabity_days = trim(secondsToWords($total_project_featured_upgrade_availability_seconds));

$project_urgent_upgrade_availability_array = explode(':',$this->config->item('project_upgrade_availability_urgent'));
$total_project_urgent_upgrade_availability_seconds = ($project_urgent_upgrade_availability_array[0] * 3600)+($project_urgent_upgrade_availability_array[1] * 60)+$project_urgent_upgrade_availability_array[2];
$urgent_availabity_days = trim(secondsToWords($total_project_urgent_upgrade_availability_seconds));
?>
<main>
	<!-- header section -->
	<div class="top-hedaer-sectn logoSection">
		<div class="container-fluid">
			<div class="row">
				<div>
					<figure>
						<?php 
						if($this->session->userdata ('user')){ 
							$logo_redirect_url = base_url().$this->config->item('dashboard_page_url');
						}else{ 
							$logo_redirect_url = base_url().$this->config->item('signin_page_url');
						}
						?>
						<a href="<?php echo $logo_redirect_url ; ?>"><img src="<?=ASSETS?>images/site-inner-logo.png" alt="logo"></a>
					</figure>
				</div>
			</div>
		</div>
	</div>
	<!-- end header section -->

  <div class="create-your-project">
	<div class="container-fluid">
	  <div class="row">
		<div class="pProjectLeft">
			<?php
			 $attributes = [
				 'id' => 'draft_project_form',
				 'class' => '',
				 'role' => 'form',
				 'name' => 'draft_project_form',
				 'enctype' => 'multipart/form-data',
			 ];
			 echo form_open('', $attributes);
			 ?>
				<input type="hidden" name="project_id" value="<?php echo $project_id; ?>"/>
				<input type="hidden" name="page_type" value="form"/>
			<!-- <div class="box-overly"> -->
			<div class="default_block_header_transparent nBorder post_project_header">
				<div class="transparent transparent_heading" id="draft_page_heading"><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('edit_draft_fulltime_project_page_heading') : $this->config->item('edit_draft_project_page_heading');?></div>

				

				<!-- check box button -->
				<div class="checkbox-btn-sectn block-sectn create_project_job" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php echo $this->config->item('post_project_page_hover_tooltip_message_post_project_type_option_description_section'); ?>">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12">					
							<div class="default_radio_button">
								<section>
									<div>
										<input type="radio" id="post_project" name="project_type_main" value="post_project" class="post_project_input" checked">
										<label for="post_project">
											<span><?php echo $this->config->item('post_project_option_heading'); ?></span>
										</label>
									</div>
									<div>
										<input type="radio" id="post_fulltime_position" name="project_type_main" value="post_fulltime_position" class="post_project_input post_project_fulltime">
										<label for="post_fulltime_position">
											<span><?php echo $this->config->item('post_fulltime_project_option_heading'); ?></span>
										</label>
									</div>
								</section>
							</div>
						
						</div>
					</div>
				</div>
				<!-- end check box button -->

				<!-- check box button -->
				<div class="checkbox-btn-sectn block-sectn paymnt_perhour" id="project_type_block" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php echo $this->config->item('post_project_page_hover_tooltip_message_post_project_fixed_hourly_type_option_description_section'); ?>">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12">
							<div class="default_radio_button">
								<section>
									<div>
										<input type="radio" id="project_type_fixed" name="project_type" value="fixed" class="post_project_type_input">
										<label for="project_type_fixed">
											<span><?php echo $this->config->item('post_project_option_fixed_budget_project_heading'); ?></span>
										</label>
									</div>
									<div>
										<input type="radio" id="project_type_hourly" name="project_type" value="hourly" class="post_project_type_input">
										<label for="project_type_hourly">
											<span><?php echo $this->config->item('post_project_option_hourly_budget_project_heading'); ?></span>
										</label>
									</div>
								</section>
							</div>
						</div>
					</div>
				</div>
				<!-- end check box button -->
				<!-- Choose the most relevant categories for your position -->
				<div class="block-sectn categories-select-sectn pCategory_first" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php 
						if($project_data['project_type'] == 'fulltime'){ 
							echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_category_section');
						}else{
							echo $this->config->item('post_project_page_hover_tooltip_message_category_section');
							}
					?>">
					<div class="row">
						<div class="col-md-12" id="category_listing_block">
							<h4 class="inner-block-heading default_black_bold_large"><span id="project_category_section_heading"><?php
							if($project_data['project_type'] == 'fulltime'){
								echo $this->config->item('post_fulltime_project_category_section_heading');
							}else{
								echo $this->config->item('post_project_category_section_heading');
							}
							?></span></h4>
							<?php
							
							if(!empty($project_category_data))
							{
								$category_counter = 0;
								foreach($project_category_data as $project_category)
								{
							?>
									<div class="row category_row" id="<?php echo "project_category_row_".$project_category['id']."_".$project_category['project_id']; ?>">	
							<?php
									if(empty($project_category['draft_project_parent_category_id'])){
										$get_project_child_categories = $CI->Post_project_model->get_project_child_categories($project_category['draft_project_category_id']);
									
									}else{
										$get_project_child_categories = $CI->Post_project_model->get_project_child_categories($project_category['draft_project_parent_category_id']);
									}
									
							?>
										<div class="col-sm-6 categoryFirst">
										  <div class="form-group default_dropdown_select">
													
												<select name="project_category[<?php echo $category_counter ?>][project_parent_category]" id="<?php echo "project_parent_category_".$category_counter ?>" class="project_parent_category">
												<!--<option>Select Category</option>-->
												<?php
												
												if(!empty($project_parent_categories)){
													foreach ($project_parent_categories as $project_parent_category_row){ 
														$selected_parent_category_id = 0;
														$selected_parent_category_selected = "";
														if($project_category['draft_project_parent_category_id'] == 0){
															$selected_parent_category_id = $project_category['draft_project_category_id'];
														}else{
															$selected_parent_category_id = $project_category['draft_project_parent_category_id'];
														}

														if($selected_parent_category_id == $project_parent_category_row['id']){
															$selected_parent_category_selected = "selected";
														}
														
														if(!empty($selected_category_array)){
															$array_diff = array();
															$array_diff = array_diff($selected_category_array,array($project_category['draft_project_category_id']));
															if(in_array($project_parent_category_row['id'], $array_diff)){
																$style_display = "display:none;";
															}else{
																$style_display = "display:block;";
															}
															
														}
														
														
												?>
														<option  style="<?php echo $style_display; ?>" <?php echo $selected_parent_category_selected; ?> value="<?php echo $project_parent_category_row['id']; ?>"><?php echo $project_parent_category_row['name']."";?></option>
												<?php
													}
												}	
												?>		   
											  </select>                    
										  </div>
										</div>
										<div class="col-sm-6 categorySecond <?php echo "project_child_category_".$category_counter; ?>" style="<?php if(empty($get_project_child_categories)){ echo "display:none;";} else { echo "display:block;";} ?>">
										  <div class="form-group default_dropdown_select">
											<?php
											$project_child_category_disabled = "";
											if(empty($get_project_child_categories)){
												$project_child_category_disabled = "disabled";
											}
											?>
											  <select name="project_category[<?php echo $category_counter ?>][project_child_category]"  id="<?php echo "project_child_category_".$category_counter ?>"  <?php echo $project_child_category_disabled; ?> class="project_child_category">
											  
												<?php
												if(!empty($get_project_child_categories)){
												?>
												<option value=""><?php echo $this->config->item('post_project_page_sub_category_drop_down_option_select_sub_category'); ?></option>
												<?php
												}
												?>
												<?php
												if(!empty($get_project_child_categories )){
													foreach ($get_project_child_categories as $project_child_category_row) {
													
														$selected_child_category_id = 0;
														$selected_child_category_selected = "";
														if($project_category['draft_project_parent_category_id'] != 0){
															$selected_child_category_id = $project_category['draft_project_category_id'];
														}
														if($selected_child_category_id == $project_child_category_row['id']){
															$selected_child_category_selected = "selected";
														}
												?>
														<option <?php echo $selected_child_category_selected; ?> value="<?php echo $project_child_category_row['id']; ?>"><?php echo $project_child_category_row['name'];?></option>
												<?php
													
													}
												}		
												?>			
											  </select>                    
										  </div>
										</div>
									<?php if($category_counter != 0){ ?>
									 <a style="cursor:pointer" data-id = "<?php echo $project_category['draft_project_category_id'] ?>"  id="<?php echo $project_category['id']."_".$project_category['project_id'] ?>" class="default_icon_red_btn delete-category-row delete_project_category_row_data"><i class="fas fa-trash-alt"></i></a>
									 
									  <?php } ?>
									</div>
							<?php
								$category_counter++;
								}
							}
							?>
						</div>
						<?php
						$show_category_button_status = 'display:none';
						$catgory_button_status = 'disabled';
						if($this->config->item('number_project_category_post_project') > count($project_category_data)  &&  $count_available_project_parent_category_count > count($project_category_data)   ){
							$show_category_button_status = 'display:block';
							$catgory_button_status = '';
						}
						?>
						<div class="col-sm-6 addCategory_btn add_more_project_category_section" style="<?php echo $show_category_button_status; ?> ">
							<button type="button" class="btn blue_btn btn-block default_btn addAnCat" id="add_more_project_category" <?php echo $catgory_button_status; ?>>
							<?php
							if(empty($project_category_data)){
								echo $this->config->item('post_project_page_add_category_button_txt');
							}else{
								echo $this->config->item('post_project_page_add_another_category_button_txt');
							}
							?>		
							</button>
						</div>
						<div class="col-sm-6"></div>
						<div class="col-sm-12 error_div_height_auto" id="project_parent_category_0_col">
							<div class="form-group" id="project_parent_category_0">
								<div class="error_div_sectn clearfix">
									<span id="project_parent_category_0_error" class="error_msg"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end Choose the most relevant categories for your position -->

				<!-- What budget do you have in mind ? -->
				<div class="block-sectn select-budget project_show_block scope_budget" id="project_budget" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php
					if($project_data['project_type'] == 'fulltime'){ 
						echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_salary_range_section');
					}else{
						echo $this->config->item('post_project_page_hover_tooltip_message_budget_range_section');
					}
					?>">
					<div class="row">
						<div class="col-md-12">
							<h4 class="inner-block-heading default_black_bold_large"><span id="project_budget_section_heading"><?php
							if($project_data['project_type'] == 'fulltime'){ 
								echo $this->config->item('post_fulltime_project_salary_range_section_heading');
							}else{
								echo $this->config->item('post_project_budget_range_section_heading');
							}
							?></span></h4> 
						</div>
						<div class="col-sm-6 project_select_budget">
							<div class="form-group default_dropdown_select margin_bottom0 error_div_height_auto">
								<?php
								$budget_selected_value = '';
								if($project_data['confidential_dropdown_option_selected'] == 'Y'){
								
									$budget_selected_value = key($this->config->item('fixed_budget_projects_confidential_dropdown_option'));
								
								}else if($project_data['not_sure_dropdown_option_selected'] == 'Y'){
									$budget_selected_value = key($this->config->item('fixed_budget_projects_not_sure_dropdown_option'));
								}
								else if(!empty($project_data['min_budget'])){
									$budget_selected_value = $project_data['min_budget']."_".$project_data['max_budget'];
								}
								?>
								<select name="project_budget" id="fixed_budget">
									<option value=""><?php echo $this->config->item('post_project_page_budget_drop_down_option_select_budget'); ?></option>
									<?php 
									if(!empty($fixed_budget_projects_budget_range))
									{
										foreach ($fixed_budget_projects_budget_range as $fixed_budget_projects_budget_range_row){ 
									?>
										<option 
									<?php echo isset ($budget_selected_value) && $budget_selected_value == $fixed_budget_projects_budget_range_row['fixed_budget_range_key'] ? 'selected' : ''; ?>
									value="<?php echo $fixed_budget_projects_budget_range_row['fixed_budget_range_key']; ?>"><?php echo $fixed_budget_projects_budget_range_row['fixed_budget_range_value'];?></option>
									<?php 
										} 
									}
									?>
								</select>
								<select name="project_budget" id="hourly_rate_based_budget">
									<option value=""><?php echo $this->config->item('post_project_page_budget_drop_down_option_select_budget'); ?></option>
									<?php 
									if(!empty($hourly_rate_based_budget_projects_budget_range))
									{
										foreach ($hourly_rate_based_budget_projects_budget_range as $hourly_rate_based_budget_projects_budget_range_row){ 
									?>
										<option <?php echo isset ($budget_selected_value) && $budget_selected_value == $hourly_rate_based_budget_projects_budget_range_row['hourly_rate_based_budget_range_key'] ? 'selected' : ''; ?>
										value="<?php echo $hourly_rate_based_budget_projects_budget_range_row['hourly_rate_based_budget_range_key']; ?>"><?php echo $hourly_rate_based_budget_projects_budget_range_row['hourly_rate_based_budget_range_value'];?></option>
									<?php 
										} 
									}
									?>
								</select>
								<select name="project_budget" id="fulltime_salary_range">
								<option value=""><?php echo $this->config->item('post_project_page_salary_drop_down_option_select_salary'); ?></option>
								<?php 
								if(!empty($fulltime_project_salary_range))
								{
									foreach ($fulltime_project_salary_range as $fulltime_projects_salary_range_row){ 
								?>
									<option <?php echo isset ($budget_selected_value) && $budget_selected_value == $fulltime_projects_salary_range_row['fulltime_salary_range_key'] ? 'selected' : ''; ?> value="<?php echo $fulltime_projects_salary_range_row['fulltime_salary_range_key']; ?>"><?php echo $fulltime_projects_salary_range_row['fulltime_salary_range_value'];?></option>
								<?php 
									} 
								}
								?>
								</select> 
								<div class="error_div_sectn clearfix">
									<span id="project_budget_error" class="error_msg"></span>
								</div>
							</div>
							<!--
							<button type="button" class="btn btn-default" id="project_next_button" disabled style="display:none;">Next</button>-->
						</div>
						<div class="col-sm-6"></div>
						<!-- <div class="hover-tooltip-sectn" style="display:none;" id="project_budget_section_tooltip">
							<?php
							/*if($project_data['project_type'] == 'fulltime'){ 
								echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_salary_range_section');
							}else{
								echo $this->config->item('post_project_page_hover_tooltip_message_budget_range_section');
							}*/
							?>
						</div> -->
					</div>
				</div>
				<!--end What budget do you have in mind ? -->

				<!-- Project name -->
				<div class="block-sectn file-project-name project_show_block projectName_section" id="project_title_section" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php
						if($project_data['project_type'] == 'fulltime'){ 
							echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_position_name_section');
						}else{
							echo $this->config->item('post_project_page_hover_tooltip_message_project_title_section');
						}	
						?>">
					<div class="row">
						<div class="col-md-12">
							<h4 class="inner-block-heading default_black_bold_large"><span id="project_title_section_heading"><?php
							if($project_data['project_type'] == 'fulltime'){ 
								echo $this->config->item('post_fulltime_position_name_section_heading');
							}else{
								echo $this->config->item('post_project_title_section_heading');
							}
							?></span></h4> 
						</div>
						<div class="col-md-12">
							<div class="form-group projectName"> 
								<?php
								$project_title = $project_data['project_title'];
								if($this->config->item('project_title_maximum_length_characters_limit_post_project') - mb_strlen(trim($project_title)) >= 0){
								$project_title_remaining_characters = $this->config->item('project_title_maximum_length_characters_limit_post_project') - mb_strlen(trim($project_title));
								}else{
								$project_title_remaining_characters = 0;
								}
								?>
								<input type="text" name="project_title" id="project_title" class="avoid_space default_input_field" maxlength="<?php echo $this->config->item('project_title_maximum_length_characters_limit_post_project'); ?>" value="<?php echo $project_title; ?>"> 
								<div class="error_div_sectn clearfix default_error_div_sectn">
									<span class="content-count project_title_length_count_message"><?php echo $project_title_remaining_characters." ".$this->config->item('characters_remaining_message'); ?></span> 
									<span id="project_title_error" class="error_msg"></span>
								</div>      
							</div>
							</div>
						<div class="col-sm-6"></div>
						<!-- <div class="hover-tooltip-sectn" style="display:none;" id="project_title_section_tooltip">
							<?php
							/*if($project_data['project_type'] == 'fulltime'){ 
								echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_position_name_section');
							}else{
								echo $this->config->item('post_project_page_hover_tooltip_message_project_title_section');
							}*/	
							?>
						</div> -->
					</div>
				</div>
				<!-- end Project name -->
				
				<!-- Describe your project in detail: -->
				<div id="project_description_section" class="block-sectn project-describe project_show_block noBorder_block_sectn projectDescription_section" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php if($project_data['project_type'] == 'fulltime'){ 
						echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_position_description_section');}else{
						echo $this->config->item('post_project_page_hover_tooltip_message_project_description_section');}	
					?>">
					<div class="row">
						<div class="col-md-12">
							<h4 class="inner-block-heading default_black_bold_large"><span id="project_description_section_heading"><?php
								if($project_data['project_type'] == 'fulltime'){ 
									echo $this->config->item('post_fulltime_position_description_section_heading');
								}else{
									echo $this->config->item('post_project_description_section_heading');
								}	
								?></span></h4> 
						</div>
						<div class="col-md-12">
							<div class="form-group margin0"> 
								<?php
									$project_description = $project_data['project_description'];
									if($this->config->item('project_description_maximum_length_characters_limit_post_project') - mb_strlen(trim($project_description)) >= 0){
									$project_description_remaining_characters = $this->config->item('project_description_maximum_length_characters_limit_post_project') - mb_strlen(trim($project_description));
									}else{
									$project_description_remaining_characters = 0;
									}
								?>
								<textarea  name="project_description" id="project_description" class="avoid_space_textarea default_textarea_field" maxlength="<?php echo $this->config->item('project_description_maximum_length_characters_limit_post_project'); ?>"><?php echo trim($project_description); ?></textarea>
								<div class="error_div_sectn clearfix">
									<span class="error_description">
									<span id="project_description_error" class="error_msg errMesgOnly"></span>
									</span>
									<span class="content-count project_description_length_count_message"><?php echo $project_description_remaining_characters."&nbsp;".$this->config->item('characters_remaining_message'); ?></span> 
								</div>
							</div>
						</div>
						<!-- <div class="col-md-6"></div> -->
						<!-- <div class="hover-tooltip-sectn" style="display:none;" id="project_description_section_tooltip">
						<?php
						/*if($project_data['project_type'] == 'fulltime'){ 
							echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_position_description_section');
						}else{
							echo $this->config->item('post_project_page_hover_tooltip_message_project_description_section');
						}*/	
						?>
						</div> -->
					</div>
				</div>
				<!-- Describe your project in detail -->

				<!-- Upload your document -->
				<div id="project_attachment_section" class="block-sectn upload-document project_show_block uploadDoc" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php
					if($project_data['project_type'] == 'fulltime'){ 
						echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_attachment_upload_section');
					}else{
						echo $this->config->item('post_project_page_hover_tooltip_message_project_attachment_upload_section');
					}	
					?>">
					<div class="row">
						<div class="col-md-12">
							<div class="upload-btn-wrapper pPfileInput_btn">
								<!--<button class="btn btn-default"><i class="fa fa-cloud-upload"></i> Upload a file</button>
								<input type="file" name="myfile" />-->
								<span class="btn blue_btn default_btn fileinput-button">
									<i class="fa fa-cloud-upload"></i><?php echo $this->config->item('post_project_page_upload_file_button_txt'); ?>
								</span><div class="upload_attachment_error errAttachment"></div><?php
								$show_attachment_table = "";
								if($count_project_attachments == 0 ){
									$show_attachment_table = "display:none;";
								}
								?><div id="project_attachment_container" class="attachment_inline" style="<?php echo $show_attachment_table; ?>"><?php
								if(!empty($project_attachment_array)){
									foreach($project_attachment_array as $project_attachment){
										//$attachment_id = Cryptor::doEncrypt($project_attachment['id']);
										$attachment_id = $project_attachment['project_attachment_name'];
								?><div class="default_download_attachment project_attachment_row" id="<?php echo "project_attachment_row".$project_attachment['id']; ?>"><a class="download_attachment download_text" data-attr="<?php echo $attachment_id; ?>"><label><?php echo $project_attachment['project_attachment_name']; ?></label></a><label class="delete_icon"><a style="cursor:pointer;" class="project_attachment_row_delete" data-file-attr = "<?php echo $project_attachment['project_attachment_name']; ?>" id="<?php echo $project_attachment['id'];?>"><i class="fa fa-trash-o delete-btn" aria-hidden="true" ></i></a></label><div class="clearfix"></div></div>
								<?php
										}
									}	
								?>
								</div>
							</div>
						</div>
						<!-- <div class="col-md-9">
							<?php
							
							/*$show_attachment_table = "";
							if($count_project_attachments == 0 ){
								$show_attachment_table = "display:none;";
							}*/
							?>
							<table class="table" id="project_attachment_container" style="<?php echo $show_attachment_table; ?>">
								<tr>
									<th><?php //echo $this->config->item('post_project_page_project_attachment_table_heading_file_name'); ?></th>
									<th align="center"><?php //echo $this->config->item('post_project_page_project_attachment_table_heading_file_size'); ?></th>
									<th align="center"><?php //echo $this->config->item('post_project_page_project_attachment_table_heading_remove'); ?></th>
								</tr>
								<?php
								/*if(!empty($project_attachment_array)){
									foreach($project_attachment_array as $project_attachment){
										$attachment_id = Cryptor::doEncrypt($project_attachment['id']);*/
								?>
									<tr class="project_attachment_row" id="<?php //echo "project_attachment_row".$project_attachment['id']; ?>">
										<td ><a class="download_attachment" style="cursor:pointer;" data-attr="<?php //echo $attachment_id; ?>" ><?php //echo $project_attachment['project_attachment_name']; ?></a></td>
										<td align="center"><?php //echo $project_attachment['size']; ?></td>
										<td  align="center">
											<a style="cursor:pointer;" class="btn default_icon_red_btn project_attachment_row_delete" id="<?php //echo $project_attachment['id'];?>"> <i class="fas fa-trash-alt"></i> </a>	  
										</td>
									</tr>
								<?php
									/*}
								}*/	
								?>
							</table>
						</div> -->
						<!-- <div class="hover-tooltip-sectn" style="display:none;" id="project_attachment_section_tooltip">
							<?php
							/*if($project_data['project_type'] == 'fulltime'){ 
								echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_attachment_upload_section');
							}else{
								echo $this->config->item('post_project_page_hover_tooltip_message_project_attachment_upload_section');
							}*/	
							?>
						</div> -->
					</div>
				</div>
				<!-- Upload your document -->

				<!-- tags -->
				<div id="project_tag_section" class="block-sectn tags-sectn project_show_block" data-toggle="popover" data-placement="right" data-content="<?php
					if($project_data['project_type'] == 'fulltime'){ 
						echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_tag_section');
					}else{
						echo $this->config->item('post_project_page_hover_tooltip_message_project_tag_section');
					}	
					?>">
					<div class="row">
						<div class="col-md-12">
							<div class="receive_notification" id="tagSL">
								<a class="rcv_notfy_btn" onclick="showMoreTags()" id="project_tag_heading_section">
								<?php
								if($project_data['project_type'] == 'fulltime'){ 
									echo $this->config->item('post_fulltime_position_tags_section_heading');
								}else{
									echo $this->config->item('post_project_project_tags_section_heading');
								}?><small>
								<?php if(!empty($project_tag_data)){
									echo "( - )"; } else {echo "( + )";}
								?></small></a><input type="hidden" id="moreTags" value="<?php if(!empty($project_tag_data)){
								echo "0"; } else {echo "1";}?>">
							</div>
							<!-- <h4 class="inner-block-heading default_black_bold_large"><span id="project_tag_heading_section">
							<?php
							/*if($project_data['project_type'] == 'fulltime'){ 
								echo $this->config->item('post_fulltime_position_tags_section_heading');
							}else{
								echo $this->config->item('post_project_project_tags_section_heading');
							}*/	
							?>
							</span></h4> -->
							<!-- </div>
							<div class="col-md-12"> -->
							<div id="more_tags" class="row" style="<?php if(empty($project_tag_data)){
											echo "display:none;"; }else{ "display:block;";}?>">
								<div class="tagBottom">
                                                                    <input type="text" id="input_tags" name=""  class="avoid_space  default_input_field" maxlength="<?php echo $this->config->item('project_tag_maximum_length_characters_limit_post_project'); ?>" >
                                                                    <div class="error_div_sectn clearfix tagyError">
                                                                        <span class="error_save">
                                                                            <span id="tag_error" style="display:none" class="error_msg"></span>
                                                                            <div class="saveTAg_responsive" id="save_tag_button_section_responsive"><button type="button" class="btn default_btn blue_btn" save_tag_button disabled><?php echo $this->config->item('save_btn_txt'); ?></button></div>
                                                                        </span>
                                                                            <span class="content-count project_tag_length_count_message"><?php echo $this->config->item('project_tag_maximum_length_characters_limit_post_project')."&nbsp;".$this->config->item('characters_remaining_message'); ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="saveTAg" id="save_tag_button_section"><button type="button" class="btn default_btn blue_btn save_tag_button" disabled><?php echo $this->config->item('save_btn_txt'); ?></button></div>
                                                                <div class="clearfix"></div>
                                                                <div class="col-md-12">
                                                                    <ul id="tags-list" class="default_cross_tag"> 
                                                                            <?php
                                                                            if(!empty($project_tag_data)){
                                                                                    $tag_counter = 0;
                                                                                    foreach($project_tag_data as $project_tag){

                                                                            ?>
                                                                                    <li class="tag_name" id="<?php echo 'project_tag_'.$project_tag['id']."_".$project_tag['project_id'] ?>"> <span><small><?php echo $project_tag['draft_project_tag_name']; ?></small><input type="hidden" name="project_tag[<?php echo $tag_counter; ?>][tag_name]" value="<?php echo $project_tag['draft_project_tag_name']; ?>" /><i class="fa fa-times delete_project_tag_row_data" data-attr="<?php echo 'project_tag_'.$project_tag['id']."_".$project_tag['project_id'] ?>"></i> </span></li>

                                                                            <?php
                                                                                            $tag_counter ++;
                                                                                    }
                                                                            }	
                                                                            ?>
                                                                    </ul>
								</div>
							</div>
						</div>
					<!-- <div class="hover-tooltip-sectn" style="display: none;" id="project_tag_section_tooltip">
					  <?php
						/*if($project_data['project_type'] == 'fulltime'){ 
							echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_tag_section');
						}else{
							echo $this->config->item('post_project_page_hover_tooltip_message_project_tag_section');
						}*/	
						?>
					</div> -->
					</div>
				</div>
				<!-- end tags -->

				<!-- Where do you want this done? -->
				<div class="block-sectn county-details project_show_block" id="location_block" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php
					if($project_data['project_type'] == 'fulltime'){ 
						echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_position_location_section');
					}else{
						echo $this->config->item('post_project_page_hover_tooltip_message_project_location_section');
					}	
					?>">
					<div class="row">
						<div class="col-md-12">
							<div class="receive_notification" id="locationSL">					
								<a class="rcv_notfy_btn location_option chk-btn" onclick="showMorePow()" id="location_heading"><?php
									if($project_data['project_type'] == 'fulltime'){ 
										echo $this->config->item('post_fulltime_project_page_location_heading');
									}else{
										echo $this->config->item('post_project_page_location_heading');
									}?><small><?php
									if(!empty($project_data['county_id'])){
										echo " ( - )";
									}else{
										echo " ( + )";
									}
									?></small></a>
								<input type="hidden" name="location_option" id="morePow" value="<?php
								if(!empty($project_data['county_id'])){
									echo "0";
								}else{
									echo "1";
								}
								?>">
							</div>
							<!-- <div class="form-group" style="margin:0;">
								<div class="checkbox custom_check_box">  
									<div class="checkbox-btn-inner">
										<input id="this-check" style="position: absolute;top: 0;width: 100%;" type="checkbox" class="location_option" value="location" name="location_option">
										<div class="checkbox-inner-div d-inline-block">
										  <label for="this-check"></label>
										   <span id="location_heading">
										  <?php
											/*if($project_data['project_type'] == 'fulltime'){ 
												echo $this->config->item('post_fulltime_project_page_location_heading');
											}else{
												echo $this->config->item('post_project_page_location_heading');
											}*/	
										?>
											</span>
										</div>
									</div>
								</div>
								</div>
								</div> -->
								<!-- <div class="hover-tooltip-sectn" style="display: none;" id="project_location_section_tooltip"> 
								<?php
								/*if($project_data['project_type'] == 'fulltime'){ 
									echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_position_location_section');
								}else{
									echo $this->config->item('post_project_page_hover_tooltip_message_project_location_section');
								}*/	
								?>
								</div>
							</div> -->
							<div id="more_pow" class="collapse location_section">
								<div class="row">
									<div class="col-md-5 col-sm-5 col-12 placeWork">
										<div class="form-group default_dropdown_select"> 
											<select id="project_county_id" name="project_county_id">
												<option value="" ><?php echo $this->config->item('post_project_page_county_drop_down_option_select_county'); ?></option>
												<?php foreach ($counties as $county): ?>
												<option <?php echo isset ($project_data['county_id']) && $project_data['county_id'] == $county['id'] ? 'selected' : ''; ?> value="<?php echo $county['id']; ?>"><?php echo $county['name'] ?></option>
												<?php endforeach; ?>
											</select> 
											<div class="error_div_sectn clearfix">
												<span id="project_county_id_error" class="error_msg"></span>
											</div>
										</div>
									</div>
									<div class="col-md-5 col-sm-5 col-12 selectMunicipility" id="selectMunicipility" style="<?php if($project_data['county_id'] != 0 && !empty($localities)){ echo "display:block;";} else { echo "display:none;";} ?>">
										<div class="form-group default_dropdown_select"> 
											<?php
											$lacality_disabled = "disabled";
											if(!empty($project_data['county_id'])){
												$lacality_disabled = "";
											}
											?>
											<select  name="project_locality_id" id="project_locality_id" <?php echo $lacality_disabled;?> >
												<option value=""><?php echo $this->config->item('post_project_page_locality_drop_down_option_select_locality'); ?></option>
												<?php foreach ($localities as $locality): ?>
												<option <?php echo isset ($project_data['locality_id']) && $project_data['locality_id'] == $locality['id'] ? 'selected' : ''; ?>
												value="<?php echo $locality['id']; ?>"><?php echo $locality['name'] ?></option>
												<?php endforeach; ?>
											</select>
											<div class="error_div_sectn clearfix">
												<span id="project_locality_id_error" class="error_msg"></span>
											</div>
										</div>
									</div>
									<div class="col-md-2 col-sm-2 col-12 selectPostcode" id="selectPostcode" style="<?php if($project_data['locality_id'] != 0 && !empty($postal_codes)){ echo "display:block;";} else { echo "display:none;";} ?>">
										<div class="form-group default_dropdown_select"> 
											<?php
											$postal_code_disabled = "disabled";
											if(!empty($project_data['locality_id'])){
												$postal_code_disabled = "";
											}
											?>
											<select name="project_postal_code_id" id="project_postal_code_id" <?php echo $postal_code_disabled;?>>
												<option value=""><?php echo $this->config->item('post_project_page_postal_code_drop_down_option_select_postal_code'); ?></option>
												<?php foreach ($postal_codes as $postal_code): ?>
												<option <?php echo isset ($project_data['postal_code_id']) && $project_data['postal_code_id'] == $postal_code['id'] ? 'selected' : ''; ?>
												value="<?php echo $postal_code['id']; ?>"><?php echo $postal_code['postal_code']; ?></option>
												<?php endforeach; ?>
											</select>	
											<div class="error_div_sectn clearfix">
												<span id="project_postal_code_id_error" class="error_msg"></span>
											</div>
										</div>
									</div>
									<!-- <div class="col-md-6"></div> -->
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Where do you want this done? -->

				<!-- Payment Methods -->
				<div id="payment_method_section"  class="payment-methods block-sectn project_show_block" data-toggle="popover" data-placement="right" data-content="<?php
					if($project_data['project_type'] == 'fulltime'){ 
						echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_payment_method_section');
					}else{
						echo $this->config->item('post_project_page_hover_tooltip_message_payment_method_section');
					}	
					?>">
					<div class="row">
						<div class="col-md-12">
							<div class="receive_notification" id="pmethodSL">					
								<a class="rcv_notfy_btn inner-block-heading default_black_bold_large"  id="project_payment_method_section_heading" onclick="showMorePmethod()"><?php
										if($project_data['project_type'] == 'fulltime'){ 
											echo $this->config->item('post_fulltime_position_payment_method_section_heading');
										}else{
											echo $this->config->item('post_project_payment_method_section_heading');
										}?><small>
										<?php if($project_data['escrow_payment_method'] == 'Y' || $project_data['offline_payment_method'] == 'Y'){
											echo "( - )";
										}else{
											echo "( + )";
										}?></small></a>
								<input type="hidden" id="morePmethod" value="<?php if($project_data['escrow_payment_method'] == 'Y' || $project_data['offline_payment_method'] == 'Y'){
									echo "0";
								}else{
									echo "1";
								}
								?>">
							</div>
							<!-- <div class="col-md-12">
							<h4 class="inner-block-heading default_black_bold_large"><span id="project_payment_method_section_heading">
							<?php
							/*if($project_data['project_type'] == 'fulltime'){ 
								echo $this->config->item('post_project_project_optional_upgrades_section_heading');
							}else{
								echo $this->config->item('post_project_payment_method_section_heading');
							}*/	
							?>
							</span></h4>
							</div> -->
							<div id="more_pmethod" class="<?php if($project_data['escrow_payment_method'] == 'N' && $project_data['offline_payment_method'] == 'N'){
											echo "collapse";
										}
										?> row paymentMethod">
								<div class="col-md-12 fontSize0">
									<div class="drpChk form-group">
										<label for="payment-methods1" class="default_checkbox">
											<input type="checkbox" id="payment-methods1" name="escrow_payment_method" value="Y" class="escrow_payment_method chk-btn">
											<span class="checkmark"></span>
										<small id="payment_method_escrow_text"><?php
											if($project_data['project_type'] == 'fulltime'){ 
												echo $this->config->item('post_fulltime_project_page_payment_method_section_text_escrow_payment');
											}else{
												echo $this->config->item('post_project_page_payment_method_section_text_escrow_payment');
											}	
											?></small>
										</label>
									</div>
									
									<!-- <div class="form-group">
										<div class="checkbox custom_check_box">  
											<div class="checkbox-btn-inner">
												<input id="payment-methods1" class="escrow_payment_method" name="escrow_payment_method" value="Y" style="position: absolute;top: 0;width: 100%;" type="checkbox">
												<div class="checkbox-inner-div">
													<label for="payment-methods1"></label>
													<span id="payment_method_escrow_text">
													<?php
													/*if($project_data['project_type'] == 'fulltime'){ 
														echo $this->config->item('post_fulltime_project_page_payment_method_section_text_escrow_payment');
													}else{
														echo $this->config->item('post_project_page_payment_method_section_text_escrow_payment');
													}*/	
													?>
													</span>
												</div>
											</div>  
										</div>
									</div> -->
									<div class="drpChk form-group">
										<label for="payment-methods2" class="default_checkbox">
											<input type="checkbox" id="payment-methods2" name="offline_payment_method" value="Y" class="offline_payment_method chk-btn">
											<span class="checkmark"></span>
										<small id="payment_method_offline_text"><?php
											if($project_data['project_type'] == 'fulltime'){ 
												echo $this->config->item('post_fulltime_project_page_payment_method_section_text_offline_payment');
											}else{
												echo $this->config->item('post_project_page_payment_method_section_text_offline_payment');
											}	
											?></small>
										</label>
									</div>
										
									<!-- <div class="form-group">
										<div class="checkbox custom_check_box">  
											<div class="checkbox-btn-inner">
												<input id="payment-methods2" class="offline_payment_method" name="offline_payment_method" value="Y" style="position: absolute;top: 0;width: 100%;" type="checkbox">
												<div class="checkbox-inner-div">
													<label for="payment-methods2"></label>
													<span id="payment_method_offline_text">	
													<?php
													/*if($project_data['project_type'] == 'fulltime'){ 
														echo $this->config->item('post_fulltime_project_page_payment_method_section_text_offline_payment');
													}else{
														echo $this->config->item('post_project_page_payment_method_section_text_offline_payment');
													}*/	
													?>
													</span>
												</div>
											</div>
										</div>
									</div> -->
								</div>
							</div>
							<!-- <div class="hover-tooltip-sectn" style="display:none;" id="project_payment_method_section_tooltip">
								<?php
								/*if($project_data['project_type'] == 'fulltime'){ 
									echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_payment_method_section');
								}else{
									echo $this->config->item('post_project_page_hover_tooltip_message_payment_method_section');
								}*/	
								?> -->
						</div>
					</div>
				</div>
				<!-- end Payment Methods -->
				<!-- Get most from your project! (optional) -->
				<div class="most-project block-sectn-without-blue-tooltip project_show_block">
				  <div class="row">
					  <div class="col-md-12">
					  <h4 class="inner-block-heading default_black_bold_large improveAD"><span id="project_optional_upgrades_section_heading">
							<?php
							if($project_data['project_type'] == 'fulltime'){ 
								echo $this->config->item('post_fulltime_position_optional_upgrades_section_heading');
							}else{
								echo $this->config->item('post_project_optional_upgrades_section_heading');
							}	
							?></span></h4>
					  <div class="form-group">
						<div class="checkbox-btn-inner">
							<input id="most-project1" style="position: absolute;top: 0;width: 100%;" type="checkbox" class="upgrade_type_featured upgrade_type" name="upgrade_type_featured" value="Y">
							<div class="checkbox-inner-div">
								<label for="most-project1">
									<div class="row">
										<div class="checkbox-title"> <span class="upgrade_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></span></div>
										<div class="pay-sectn" id="upgrade_type_featured_amount_container">
											<?php
											if($count_user_featured_membership_included_upgrades_monthly <$user_membership_plan_details->included_number_featured_upgrades || $user_membership_plan_details->included_number_featured_upgrades == '-1' ){
											?>
												<span><span id="upgrade_type_featured_amount" data-attr="0"><?php echo $this->config->item('post_project_page_upgrade_free_txt'); ?></span></span>
											<?php
											}else{
											?>
												<span><span id="upgrade_type_featured_amount" data-attr="<?php echo str_replace(" ","",$this->config->item('project_upgrade_price_featured')); ?>"><?php echo number_format($this->config->item('project_upgrade_price_featured'), 0, '', ' '); ?></span>  <?php echo CURRENCY; ?></span>
											<?php
											}
											?>
										</div>   
									</div>
									<div class="checkbox-content">
										<p id="featured_description" class="upgrade_badge_description badge_bigger_view"><?php echo $project_data['project_type']== 'fulltime' ? str_replace(array('{project_featured_upgrade_availability}'),array($featured_availabity_days),$this->config->item('post_fulltime_project_page_project_upgrade_description_featured')) : str_replace(array('{project_featured_upgrade_availability}'),array($featured_availabity_days),$this->config->item('post_project_page_project_upgrade_description_featured'));?></p>
                                        <p id="featured_description" class="upgrade_badge_description badge_smaller_view"><?php echo $project_data['project_type']== 'fulltime' ? str_replace(array('{project_featured_upgrade_availability}'),array($featured_availabity_days),$this->config->item('post_fulltime_project_page_project_upgrade_description_featured_small_resolution_view')) : str_replace(array('{project_featured_upgrade_availability}'),array($featured_availabity_days),$this->config->item('post_project_page_project_upgrade_description_featured_small_resolution_view'));?></p>
									</div>
								</label>
							</div>
						</div>
					  </div>
					  <div class="form-group">
					  <div class="checkbox-btn-inner">
						  <input id="most-project2"  class="upgrade_type_urgent upgrade_type" style="position: absolute;top: 0;width: 100%;" type="checkbox" name="upgrade_type_urgent"  value="Y">
							<div class="checkbox-inner-div">
								<label for="most-project2">
									<div class="row">
									  <div class="checkbox-title"> <span class="upgrade_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></span></div>
										<div class="pay-sectn" id="upgrade_type_urgent_amount_container">
											<?php

											if($count_user_urgent_membership_included_upgrades_monthly <$user_membership_plan_details->included_number_urgent_upgrades | $user_membership_plan_details->included_number_urgent_upgrades == '-1'){
											?>
												<span><span id="upgrade_type_urgent_amount" data-attr="0"><?php echo $this->config->item('post_project_page_upgrade_free_txt'); ?></span></span>
											<?php
											}else{
											?>
												<span><span id="upgrade_type_urgent_amount" data-attr="<?php echo str_replace(" ","",$this->config->item('project_upgrade_price_urgent')); ?>"><?php echo number_format($this->config->item('project_upgrade_price_urgent'), 0, '', ' '); ?></span>  <?php echo CURRENCY; ?></span>
											<?php
											}
											?>
										</div>
									</div>
									<div class="checkbox-content">
									   <p id="urgent_description" class="upgrade_badge_description badge_bigger_view"><?php echo $project_data['project_type']== 'fulltime' ? str_replace(array('{project_urgent_upgrade_availability}'),array($urgent_availabity_days),$this->config->item('post_fulltime_project_page_project_upgrade_description_urgent')) : str_replace(array('{project_urgent_upgrade_availability}'),array($urgent_availabity_days),$this->config->item('post_project_page_project_upgrade_description_urgent'));?></p>
                                        <p id="urgent_description" class="upgrade_badge_description badge_smaller_view"><?php echo $project_data['project_type']== 'fulltime' ? str_replace(array('{project_urgent_upgrade_availability}'),array($urgent_availabity_days),$this->config->item('post_fulltime_project_page_project_upgrade_description_urgent_small_resolution_view')) : str_replace(array('{project_urgent_upgrade_availability}'),array($urgent_availabity_days),$this->config->item('post_project_page_project_upgrade_description_urgent_small_resolution_view'));?></p>
									</div>
								</label>
							</div>
						</div>
					  </div>
					  <div class="form-group">
						<div class="checkbox-btn-inner">
						  <input id="most-project3"  class="upgrade_type_sealed upgrade_type" style="position: absolute;top: 0;width: 100%;" type="checkbox" name="upgrade_type_sealed" value="Y" >
							<div class="checkbox-inner-div">
								<label for="most-project3">
									<div class="row">
									  <div class="checkbox-title"> <span class="upgrade_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></span> </div>
										<div class="pay-sectn" id="upgrade_type_sealed_amount_container">
											<?php
											if($count_user_sealed_membership_included_upgrades_monthly <$user_membership_plan_details->included_number_sealed_upgrades || $user_membership_plan_details->included_number_sealed_upgrades == '-1' ){
											?>
												<span><span id="upgrade_type_sealed_amount" data-attr="0"><?php echo $this->config->item('post_project_page_upgrade_free_txt'); ?></span></span>
											<?php
											}else{
											?>
												<span><span id="upgrade_type_sealed_amount" data-attr="<?php echo str_replace(" ","",$this->config->item('project_upgrade_price_sealed')); ?>"><?php echo number_format($this->config->item('project_upgrade_price_sealed'), 0, '', ' '); ?></span>  <?php echo CURRENCY; ?></span>
											<?php
											}
											?>
										</div>
									</div>
									<div class="checkbox-content">
									  <p id="sealed_description" class="upgrade_badge_description badge_bigger_view"><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('post_fulltime_project_page_project_upgrade_description_sealed') : $this->config->item('post_project_page_project_upgrade_description_sealed');?></p>
                                                                          <p id="sealed_description" class="upgrade_badge_description badge_smaller_view"><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('post_fulltime_project_page_project_upgrade_description_sealed_small_resolution_view') : $this->config->item('post_project_page_project_upgrade_description_sealed_small_resolution_view');?></p>
									</div>
								</label>
							</div>
						</div>
					  </div>
					  
					  <div class="form-group">
						<div class="checkbox-btn-inner">
						  <input id="most-project4"  class="upgrade_type_hidden upgrade_type" style="position: absolute;top: 0;width: 100%;" type="checkbox" name="upgrade_type_hidden" value="Y">
							<div class="checkbox-inner-div">
								<label for="most-project4">
									<div class="row">
									  <div class="checkbox-title"> <span class="upgrade_hidden"> <?php echo $this->config->item('post_project_page_upgrade_type_hidden'); ?></span></div>
										<div class="pay-sectn" id="upgrade_type_hidden_amount_container">
											<?php
											if($count_user_featured_membership_included_upgrades_monthly <$user_membership_plan_details->included_number_hidden_upgrades || $user_membership_plan_details->included_number_hidden_upgrades == '-1' ){
											?>
												<span><span id="upgrade_type_hidden_amount" data-attr="0"><?php echo $this->config->item('post_project_page_upgrade_free_txt'); ?></span></span>
											<?php
											}else{
											?>
												<span><span id="upgrade_type_hidden_amount" data-attr="<?php echo str_replace(" ","",$this->config->item('project_upgrade_price_hidden')); ?>"><?php echo number_format($this->config->item('project_upgrade_price_hidden'), 0, '', ' '); ?></span>  <?php echo CURRENCY; ?></span>
											<?php
											}
											?>
										</div>
									</div>
									<div class="checkbox-content">
									   <p id="hidden_description" class="upgrade_badge_description badge_bigger_view"><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('post_fulltime_project_page_project_upgrade_description_hidden') : $this->config->item('post_project_page_project_upgrade_description_hidden');?></p>
                                         <p id="hidden_description" class="upgrade_badge_description badge_smaller_view"><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('post_fulltime_project_page_project_upgrade_description_hidden_small_resolution_view') : $this->config->item('post_project_page_project_upgrade_description_hidden_small_resolution_view');?></p>
									</div>
								</label>
							</div>
						</div>
					  </div>
					  <div id="upgrade_disclaimer_separator" style="display:none;border-top:1px solid #d7d8e0;margin"></div>
					  <div class="total-price" style="display:none;border-top:none;">
						<p><?php echo $this->config->item('post_project_page_upgrade_total_txt'); ?>  <span id="total_upgrade_amount" data-attr="<?php echo str_replace(" ","",$project_upgrade_type_total_amount); ?>"><?php echo number_format($project_upgrade_type_total_amount, 0, '', ' '); ?></span>  <?php echo CURRENCY; ?></p>
					  </div>
						<div id="upgrade_message">
						<?php
						if($project_upgrade_type_total_amount > 0){
							echo '<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>';
							
							if($project_data['project_type']== 'fulltime'){
								echo $this->config->item('post_fulltime_project_disclaimer_payments_for_upgrades_are_final');
								}else{
								echo $this->config->item('post_project_disclaimer_payments_for_upgrades_are_final');
							}
							echo '</div>';
							
							
							$total_bonus_balance =  $user_details->bonus_balance + $user_details->signup_bonus_balance;

							if(floatval($total_bonus_balance) > 0 ){
								
								if($project_data['project_type']== 'fulltime'){
									$disclaimer_user_agreement_payment_bonus_project_upgrades = $this->config->item('post_fulltime_project_disclaimer_user_agreement_for_payment_from_bonus_for_project_upgrades');
								}else{
							
							
									$disclaimer_user_agreement_payment_bonus_project_upgrades = $this->config->item('post_project_disclaimer_user_agreement_for_payment_from_bonus_for_project_upgrades');
								}
								$disclaimer_user_agreement_payment_bonus_project_upgrades = str_replace('{bonus_balance}',str_replace(".00","",number_format($total_bonus_balance,  2, '.', ' '))."&nbsp;".CURRENCY,$disclaimer_user_agreement_payment_bonus_project_upgrades);
							
							
								echo '<div class="form-group col-md-12 bonus_balance default_terms_text">
								<div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$disclaimer_user_agreement_payment_bonus_project_upgrades.'</div>';
							}
						}
						// featured
						if($project_data['featured'] == 'Y' && ($user_membership_plan_details->included_number_featured_upgrades != '-1' && $count_user_featured_membership_included_upgrades_monthly < $user_membership_plan_details->included_number_featured_upgrades )){
							$remaining_featured_upgrades = $user_membership_plan_details->included_number_featured_upgrades -$count_user_featured_membership_included_upgrades_monthly;
							
							if($remaining_featured_upgrades > 0){
								
								if(($remaining_featured_upgrades-1) > 1){
									if($project_data['project_type']== 'fulltime'){
										$featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_featured_upgrades_included_membership_available_disclaimer');
									}else{	
										$featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_featured_upgrades_included_membership_available_disclaimer');
									}
								
									$featured_upgrades_included_membership_available_disclaimer = str_replace('{remaining_featured_upgrades}',($remaining_featured_upgrades-1),$featured_upgrades_included_membership_available_disclaimer);
								
								}elseif(($remaining_featured_upgrades-1) == 1){
									if($project_data['project_type']== 'fulltime'){
										$featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_featured_upgrade_included_membership_available_disclaimer');
									}else{
										$featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_featured_upgrade_included_membership_available_disclaimer');
									}
								
									$featured_upgrades_included_membership_available_disclaimer = str_replace('{remaining_featured_upgrade}',($remaining_featured_upgrades-1),$featured_upgrades_included_membership_available_disclaimer); 
								
								}else if(($remaining_featured_upgrades-1) == 0){
									if($project_data['project_type']== 'fulltime'){
										$featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_disclaimer_last_free_featured_upgrade_included_membership_available');
									}else{
										$featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_disclaimer_last_free_featured_upgrade_included_membership_available');
									}
								}
								echo '<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$featured_upgrades_included_membership_available_disclaimer.'</div>';
							}

						}
						if($project_data['featured'] == 'Y' && $user_membership_plan_details->included_number_featured_upgrades == '-1'){
							if($project_data['project_type']== 'fulltime'){
								$unlimited_featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_unlimited_featured_upgrades_included_membership_available_disclaimer');
								}else{
								$unlimited_featured_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_unlimited_featured_upgrades_included_membership_available_disclaimer');
							}
						
							echo '<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$unlimited_featured_upgrades_included_membership_available_disclaimer.'</div>';
						}
						//urgent
						if($project_data['urgent'] == 'Y' && ($user_membership_plan_details->included_number_urgent_upgrades != '-1' && $count_user_urgent_membership_included_upgrades_monthly < $user_membership_plan_details->included_number_urgent_upgrades)){
							
							$remaining_urgent_upgrades = $user_membership_plan_details->included_number_urgent_upgrades -$count_user_urgent_membership_included_upgrades_monthly;
							if($remaining_urgent_upgrades > 0){
								if(($remaining_urgent_upgrades-1) > 1){
									if($project_data['project_type']== 'fulltime'){
										$urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_urgent_upgrades_included_membership_available_disclaimer');
									
									}else{
										$urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_urgent_upgrades_included_membership_available_disclaimer');
									}
								
								$urgent_upgrades_included_membership_available_disclaimer = str_replace('{remaining_urgent_upgrades}',($remaining_urgent_upgrades-1),$urgent_upgrades_included_membership_available_disclaimer);
								}
								else if(($remaining_urgent_upgrades-1) == 1){
								
									if($project_data['project_type']== 'fulltime'){
										$urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_urgent_upgrade_included_membership_available_disclaimer');
									}else{
										$urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_urgent_upgrade_included_membership_available_disclaimer');
									}
								
									$urgent_upgrades_included_membership_available_disclaimer = str_replace('{remaining_urgent_upgrade}',($remaining_urgent_upgrades-1),$urgent_upgrades_included_membership_available_disclaimer);
								}else if(($remaining_urgent_upgrades-1) == 0){
									if($project_data['project_type']== 'fulltime'){
										$urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_disclaimer_last_free_urgent_upgrade_included_membership_available');
									}else{
										$urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_disclaimer_last_free_urgent_upgrade_included_membership_available');
									}
								}
								echo '<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$urgent_upgrades_included_membership_available_disclaimer.'</div>';
								
							}
						}
						if($project_data['urgent'] == 'Y' && $user_membership_plan_details->included_number_urgent_upgrades == '-1'){
							if($project_data['project_type']== 'fulltime'){
								$unlimited_urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_unlimited_urgent_upgrades_included_membership_available_disclaimer');
								}else{
								$unlimited_urgent_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_unlimited_urgent_upgrades_included_membership_available_disclaimer');
							}
						
							echo '<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$unlimited_urgent_upgrades_included_membership_available_disclaimer.'</div>';
						}
						//sealed
						
						if($project_data['sealed'] == 'Y' && ($user_membership_plan_details->included_number_sealed_upgrades != '-1' && $count_user_sealed_membership_included_upgrades_monthly < $user_membership_plan_details->included_number_sealed_upgrades)){
							
							$remaining_sealed_upgrades = $user_membership_plan_details->included_number_sealed_upgrades -$count_user_sealed_membership_included_upgrades_monthly;
							if($remaining_sealed_upgrades > 0){
								if(($remaining_sealed_upgrades-1) > 1){
									if($project_data['project_type']== 'fulltime'){
										$sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_sealed_upgrades_included_membership_available_disclaimer');
									
									}else{
										$sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_sealed_upgrades_included_membership_available_disclaimer');
									}
								
								$sealed_upgrades_included_membership_available_disclaimer = str_replace('{remaining_sealed_upgrades}',($remaining_sealed_upgrades-1),$sealed_upgrades_included_membership_available_disclaimer);
								}
								else if(($remaining_sealed_upgrades-1) == 1){
								
									if($project_data['project_type']== 'fulltime'){
										$sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_sealed_upgrade_included_membership_available_disclaimer');
									}else{
										$sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_sealed_upgrade_included_membership_available_disclaimer');
									}
								
									$sealed_upgrades_included_membership_available_disclaimer = str_replace('{remaining_sealed_upgrade}',($remaining_sealed_upgrades-1),$sealed_upgrades_included_membership_available_disclaimer);
								}else if(($remaining_sealed_upgrades-1) == 0){
									if($project_data['project_type']== 'fulltime'){
										$sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_disclaimer_last_free_sealed_upgrade_included_membership_available');
									}else{
										$sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_disclaimer_last_free_sealed_upgrade_included_membership_available');
									}
								}
								echo '<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$sealed_upgrades_included_membership_available_disclaimer.'</div>';
								
							}
						}
						if($project_data['sealed'] == 'Y' && $user_membership_plan_details->included_number_sealed_upgrades == '-1'){
							if($project_data['project_type']== 'fulltime'){
								$unlimited_sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_unlimited_sealed_upgrades_included_membership_available_disclaimer');
								}else{
								$unlimited_sealed_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_unlimited_sealed_upgrades_included_membership_available_disclaimer');
							}
						
							echo '<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$unlimited_sealed_upgrades_included_membership_available_disclaimer.'</div>';
						}
						//hidden
						
						if($project_data['hidden'] == 'Y' && ($user_membership_plan_details->included_number_hidden_upgrades != '-1' && $count_user_hidden_membership_included_upgrades_monthly < $user_membership_plan_details->included_number_hidden_upgrades)){
							
							$remaining_hidden_upgrades = $user_membership_plan_details->included_number_hidden_upgrades -$count_user_hidden_membership_included_upgrades_monthly;
							if($remaining_hidden_upgrades > 0){
								if(($remaining_hidden_upgrades-1) > 1){
									if($project_data['project_type']== 'fulltime'){
										$hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_hidden_upgrades_included_membership_available_disclaimer');
									
									}else{
										$hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_hidden_upgrades_included_membership_available_disclaimer');
									}
								
								$hidden_upgrades_included_membership_available_disclaimer = str_replace('{remaining_hidden_upgrades}',($remaining_hidden_upgrades-1),$hidden_upgrades_included_membership_available_disclaimer);
								}
								else if(($remaining_hidden_upgrades-1) == 1){
								
									if($project_data['project_type']== 'fulltime'){
										$hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_free_hidden_upgrade_included_membership_available_disclaimer');
									}else{
										$hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_free_hidden_upgrade_included_membership_available_disclaimer');
									}
								
									$hidden_upgrades_included_membership_available_disclaimer = str_replace('{remaining_hidden_upgrade}',($remaining_hidden_upgrades-1),$hidden_upgrades_included_membership_available_disclaimer);
								}else if(($remaining_hidden_upgrades-1) == 0){
									if($project_data['project_type']== 'fulltime'){
										$hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_disclaimer_last_free_hidden_upgrade_included_membership_available');
									}else{
										$hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_disclaimer_last_free_hidden_upgrade_included_membership_available');
									}
								}
								echo '<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$hidden_upgrades_included_membership_available_disclaimer.'</div>';
								
							}
						}
						if($project_data['hidden'] == 'Y' && $user_membership_plan_details->included_number_hidden_upgrades == '-1'){
							if($project_data['project_type']== 'fulltime'){
								$unlimited_hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_fulltime_project_unlimited_hidden_upgrades_included_membership_available_disclaimer');
								}else{
								$unlimited_hidden_upgrades_included_membership_available_disclaimer = $this->config->item('post_project_unlimited_hidden_upgrades_included_membership_available_disclaimer');
							}
						
							echo '<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>'.$unlimited_hidden_upgrades_included_membership_available_disclaimer.'</div>';
						}
						?>
						</div>
					</div>
				  </div>
				</div>
				<!-- Get most from your project! (optional) -->

				<!-- project relative buttons -->
				<div class="project-relative-btn block-sectn-without-blue-tooltip project_show_block">
					<div class="row">
						<div class="col-md-12 text-center pPBtn_gap">
							
							<button type="button" class="btn red_btn default_btn" id="cancel_draft_project"><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('post_fulltime_project_page_cancel_project_button_txt') : $this->config->item('post_project_page_cancel_project_button_txt');?></button>
							
							<button type="button" class="btn blue_btn default_btn" id="preview_draft_project"><?php echo $project_data['project_type'] == 'fulltime' ? $this->config->item('post_fulltime_project_page_preview_project_button_txt') : $this->config->item('post_project_page_preview_project_button_txt');?></button>
							
							<button type="button" class="btn blue_btn default_btn" id="update_draft_project"><?php echo $project_data['project_type'] == 'fulltime' ? $this->config->item('post_fulltime_project_page_save_project_as_draft_button_txt')  : $this->config->item('post_project_page_save_project_as_draft_button_txt');?></button>
							
							<button type="button" class="btn green_btn default_btn" id="publish_draft_project"><?php echo $project_data['project_type'] == 'fulltime' ? $this->config->item('post_fulltime_project_page_publish_project_button_txt')  : $this->config->item('post_project_page_publish_project_button_txt');?></button>
						</div>
					</div>
				</div>
				<!-- project relative buttons -->

				<!-- Publish Project content -->
				<div class="publish-project-text project_show_block" style="display:none">
				  <div class="row">
					<div class="col-md-12 default_disclaimer_small_text" id="terms_condition_text">
					 <?php echo $project_data['project_type']== 'fulltime' ? str_replace(array('{terms_and_conditions_page_url}','{privacy_policy_page_url}'),array(site_url($this->config->item('terms_and_conditions_page_url')),site_url($this->config->item('privacy_policy_page_url'))),$this->config->item('post_fulltime_project_page_accept_terms_and_condition_policy_txt')) : str_replace(array('{terms_and_conditions_page_url}','{privacy_policy_page_url}'),array(site_url($this->config->item('terms_and_conditions_page_url')),site_url($this->config->item('privacy_policy_page_url'))),$this->config->item('post_project_page_accept_terms_and_condition_policy_txt'));?>
					</div>
				  </div>
				</div>
				<!-- end Publish Project content -->
			</div>
			 <?php echo form_close(); ?>  
		</div><!-- col md 8 -->
		<!--<div class="hidden-mobile pProjectRight" style="opacity: inherit;z-index: -1;">
			<div class="right-sectn-bar">
				<div class="box-overly" id="project_major_benefits_text_container">
					<?php 
					if($project_data['project_type'] == 'fulltime'){ 
						echo $this->config->item('post_fulltime_project_major_benefits_section_text'); 
					}else{
						echo $this->config->item('post_project_major_benefits_section_text');
					}
					?>
				</div>
				<div class="box-overly" id="project_how_it_works_text_container">
					<?php
					if($project_data['project_type'] == 'fulltime'){ 
						echo $this->config->item('post_fulltime_project_how_it_works_section_text'); 
					}else{
						echo $this->config->item('post_project_how_it_works_section_text'); 
					}
					?>
				</div>
			</div>
		</div>-->
	  </div><!-- row -->
	</div><!-- container fluid -->
  </div>
 </main>
<script type="text/javascript">

<?php
if(!empty($standard_valid_time_arr) && $po_max_open_projects_number != '0' && $open_bidding_cnt < $po_max_open_projects_number){
?>
var disclaimer_show = true;
<?php
}
?>

<?php
if(!empty($standard_valid_time_arr) && $po_max_open_fulltime_projects_number != '0' && $fulltime_open_bidding_cnt < $po_max_open_fulltime_projects_number){
?>
var fulltime_disclaimer_show = true;
<?php
}
?>


<?php
if((!empty($standard_valid_time_arr) && $po_max_open_projects_number != '0' && $open_bidding_cnt < $po_max_open_projects_number)){
?>
var project_publish_btn_show = true;
<?php
}
?>
<?php
if((!empty($standard_valid_time_arr) && $po_max_open_fulltime_projects_number != '0' && $fulltime_open_bidding_cnt < $po_max_open_fulltime_projects_number)){
?>
var fulltime_project_publish_btn_show = true;
<?php
}
?>
<?php 
if((!empty($standard_valid_time_arr) || $po_max_open_projects_number != '0' || $po_max_draft_projects_number != '0' )){
?>
var project_preview_btn_show = true;
<?php
}
?>
<?php
if((!empty($standard_valid_time_arr) || $po_max_open_fulltime_projects_number != '0' || $po_max_draft_fulltime_projects_number != '0' )){
?>
var fulltime_project_preview_btn_show = true;
<?php
}
?>
<?php
if((!empty($standard_valid_time_arr) || $po_max_open_projects_number != '0' || $po_max_draft_projects_number != '0')){
?>
var project_draft_btn_show = true;
<?php
}
?>
<?php 
if((!empty($standard_valid_time_arr) || $po_max_open_fulltime_projects_number != '0' || $po_max_draft_fulltime_projects_number != '0')){
?>
var fulltime_project_draft_btn_show = true;
<?php
}
?>

var custom_project_attachment_allowed_file_extensions = [<?php echo $this->config->item('custom_project_attachment_allowed_file_extensions'); ?>];


var plugin_project_attachment_allowed_file_extensions = '<?php echo $this->config->item('plugin_project_attachment_allowed_file_extensions'); ?>';	

var project_characters_remaining_message = "<?php echo $this->config->item('characters_remaining_message'); ?>";

var project_title_maximum_length_characters_limit_post_project = "<?php echo $this->config->item('project_title_maximum_length_characters_limit_post_project'); ?>";	
var project_description_maximum_length_characters_limit_post_project = "<?php echo $this->config->item('project_description_maximum_length_characters_limit_post_project'); ?>";	
var project_tag_maximum_length_characters_limit_post_project = "<?php echo $this->config->item('project_tag_maximum_length_characters_limit_post_project'); ?>";	

var project_tag_minimum_length_characters_limit_post_project = "<?php echo $this->config->item('project_tag_minimum_length_characters_limit_post_project'); ?>";	
var project_tag_characters_minimum_length_validation_post_project_message = "<?php echo $this->config->item('project_tag_characters_minimum_length_validation_post_project_message'); ?>";	


var number_tag_allowed_post_project = "<?php echo $this->config->item('number_tag_allowed_post_project'); ?>";	
var number_project_category_post_project = "<?php echo $this->config->item('number_project_category_post_project'); ?>";	
var project_attachment_maximum_size_limit = "<?php echo $this->config->item('project_attachment_maximum_size_limit'); ?>";
project_attachment_maximum_size_limit = project_attachment_maximum_size_limit * 1048576;
var project_attachment_maximum_size_validation_post_project_message = "<?php echo $this->config->item('project_attachment_maximum_size_validation_post_project_message'); ?>";	
var project_attachment_allowed_files_validation_post_project_message = "<?php echo $this->config->item('project_attachment_allowed_files_validation_post_project_message'); ?>";	

var project_title_minimum_length_characters_limit_post_project = "<?php echo $this->config->item('project_title_minimum_length_characters_limit_post_project'); ?>";

var project_description_minimum_length_characters_limit_post_project = "<?php echo $this->config->item('project_description_minimum_length_characters_limit_post_project'); ?>";

var project_description_minimum_length_words_limit_post_project = "<?php echo $this->config->item('project_description_minimum_length_words_limit_post_project'); ?>";

var project_attachment_invalid_file_extension_validation_post_project_message = "<?php echo $this->config->item('project_attachment_invalid_file_extension_validation_post_project_message'); ?>";



// config vaiables for section headings start //

var post_project_category_section_heading = "<?php echo $this->config->item('post_project_category_section_heading'); ?>";
var post_fulltime_project_category_section_heading = "<?php echo $this->config->item('post_fulltime_project_category_section_heading'); ?>";
var post_project_budget_range_section_heading = "<?php echo $this->config->item('post_project_budget_range_section_heading'); ?>";
var post_fulltime_project_salary_range_section_heading = "<?php echo $this->config->item('post_fulltime_project_salary_range_section_heading'); ?>";
var post_project_title_section_heading = "<?php echo $this->config->item('post_project_title_section_heading'); ?>";
var post_fulltime_position_name_section_heading = "<?php echo $this->config->item('post_fulltime_position_name_section_heading'); ?>";
var post_project_description_section_heading = "<?php echo $this->config->item('post_project_description_section_heading'); ?>";
var post_fulltime_position_description_section_heading = "<?php echo $this->config->item('post_fulltime_position_description_section_heading'); ?>";
var post_project_project_tags_section_heading = "<?php echo $this->config->item('post_project_project_tags_section_heading'); ?>";
var post_fulltime_position_tags_section_heading = "<?php echo $this->config->item('post_fulltime_position_tags_section_heading'); ?>";
var post_project_payment_method_section_heading = "<?php echo $this->config->item('post_project_payment_method_section_heading'); ?>";
var post_fulltime_position_payment_method_section_heading = "<?php echo $this->config->item('post_fulltime_position_payment_method_section_heading'); ?>";

/* adding on 02-04-2019 */

var post_project_page_heading_post_project = "<?php echo $this->config->item('edit_draft_project_page_heading'); ?>";

var post_project_page_heading_post_fulltime_project = "<?php echo $this->config->item('edit_draft_fulltime_project_page_heading'); ?>";

var post_project_page_location_heading = "<?php echo $this->config->item('post_project_page_location_heading'); ?>";
var post_fulltime_project_page_location_heading = "<?php echo $this->config->item('post_fulltime_project_page_location_heading'); ?>";

var post_project_featured_upgrade_description = "<?php echo str_replace(array('{project_featured_upgrade_availability}'),array($featured_availabity_days),$this->config->item('post_project_page_project_upgrade_description_featured')); ?>";

var post_fulltime_project_featured_upgrade_description = "<?php echo str_replace(array('{project_featured_upgrade_availability}'),array($featured_availabity_days),$this->config->item('post_fulltime_project_page_project_upgrade_description_featured')); ?>";

var post_project_featured_upgrade_description_small_resolution_view = "<?php echo str_replace(array('{project_featured_upgrade_availability}'),array($featured_availabity_days),$this->config->item('post_project_page_project_upgrade_description_featured_small_resolution_view')); ?>";

var post_fulltime_project_featured_upgrade_description_small_resolution_view = "<?php echo str_replace(array('{project_featured_upgrade_availability}'),array($featured_availabity_days),$this->config->item('post_fulltime_project_page_project_upgrade_description_featured_small_resolution_view')); ?>";


var post_project_urgent_upgrade_description = "<?php echo str_replace(array('{project_urgent_upgrade_availability}'),array($urgent_availabity_days),$this->config->item('post_project_page_project_upgrade_description_urgent')); ?>";

var post_fulltime_project_urgent_upgrade_description = "<?php echo str_replace(array('{project_urgent_upgrade_availability}'),array($urgent_availabity_days),$this->config->item('post_fulltime_project_page_project_upgrade_description_urgent')); ?>";

var post_project_urgent_upgrade_description_small_resolution_view = "<?php echo str_replace(array('{project_urgent_upgrade_availability}'),array($urgent_availabity_days),$this->config->item('post_project_page_project_upgrade_description_urgent_small_resolution_view')); ?>";

var post_fulltime_project_urgent_upgrade_description_small_resolution_view = "<?php echo str_replace(array('{project_urgent_upgrade_availability}'),array($urgent_availabity_days),$this->config->item('post_fulltime_project_page_project_upgrade_description_urgent_small_resolution_view')); ?>";

var post_project_sealed_upgrade_description = "<?php echo $this->config->item('post_project_page_project_upgrade_description_sealed'); ?>";
var post_fulltime_project_sealed_upgrade_description = "<?php echo $this->config->item('post_fulltime_project_page_project_upgrade_description_sealed'); ?>";
var post_project_sealed_upgrade_description_small_resolution_view = "<?php echo $this->config->item('post_project_page_project_upgrade_description_sealed_small_resolution_view'); ?>";
var post_fulltime_project_sealed_upgrade_description_small_resolution_view = "<?php echo $this->config->item('post_fulltime_project_page_project_upgrade_description_sealed_small_resolution_view'); ?>";

var post_project_hidden_upgrade_description = "<?php echo $this->config->item('post_project_page_project_upgrade_description_hidden'); ?>";
var post_fulltime_project_hidden_upgrade_description = "<?php echo $this->config->item('post_fulltime_project_page_project_upgrade_description_hidden'); ?>";
var post_project_hidden_upgrade_description_small_resolution_view = "<?php echo $this->config->item('post_project_page_project_upgrade_description_hidden_small_resolution_view'); ?>";
var post_fulltime_project_hidden_upgrade_description_small_resolution_view = "<?php echo $this->config->item('post_fulltime_project_page_project_upgrade_description_hidden_small_resolution_view'); ?>";

var publish_project_button_text = "<?php echo $this->config->item('post_project_page_publish_project_button_txt'); ?>";
var publish_fulltime_project_button_text = "<?php echo $this->config->item('post_fulltime_project_page_publish_project_button_txt'); ?>";

var preview_project_button_text = "<?php echo $this->config->item('post_project_page_preview_project_button_txt'); ?>";
var preview_fulltime_project_button_text = "<?php echo $this->config->item('post_fulltime_project_page_preview_project_button_txt'); ?>";


var draft_project_button_text = "<?php echo $this->config->item('post_project_page_save_project_as_draft_button_txt'); ?>";
var draft_fulltime_project_button_text = "<?php echo $this->config->item('post_fulltime_project_page_save_project_as_draft_button_txt'); ?>";

var cancel_project_button_text = "<?php echo $this->config->item('post_project_page_cancel_project_button_txt'); ?>";
var cancel_fulltime_project_button_text = "<?php echo $this->config->item('post_fulltime_project_page_cancel_project_button_txt'); ?>";


var terms_condition_fulltime_project_text = "<?php echo $this->config->item('post_fulltime_project_page_accept_terms_and_condition_policy_txt'); ?>";
var terms_condition_project_text = "<?php echo $this->config->item('post_project_page_accept_terms_and_condition_policy_txt'); ?>";

var select_locality = "<?php echo $this->config->item('post_project_page_locality_drop_down_option_select_locality'); ?>";
var select_postal_code = "<?php echo $this->config->item('post_project_page_postal_code_drop_down_option_select_postal_code'); ?>";
var select_sub_category = "<?php echo $this->config->item('post_project_page_sub_category_drop_down_option_select_sub_category'); ?>";
var add_another_category = "<?php echo $this->config->item('post_project_page_add_another_category_button_txt'); ?>";
var delete_text = "<?php echo $this->config->item('post_project_page_delete_button_txt'); ?>";

/* adding on 02-04-2019 */




var post_project_page_payment_method_section_text_escrow_payment = "<?php echo $this->config->item('post_project_page_payment_method_section_text_escrow_payment'); ?>";
var post_project_page_payment_method_section_text_offline_payment = "<?php echo $this->config->item('post_project_page_payment_method_section_text_offline_payment'); ?>";
var post_fulltime_project_page_payment_method_section_text_escrow_payment = "<?php echo $this->config->item('post_fulltime_project_page_payment_method_section_text_escrow_payment'); ?>";
var post_fulltime_project_page_payment_method_section_text_offline_payment = "<?php echo $this->config->item('post_fulltime_project_page_payment_method_section_text_offline_payment'); ?>";

var post_project_optional_upgrades_section_heading = "<?php echo $this->config->item('post_project_optional_upgrades_section_heading'); ?>";
var post_fulltime_position_optional_upgrades_section_heading = "<?php echo $this->config->item('post_fulltime_position_optional_upgrades_section_heading'); ?>";


var post_project_major_benefits_section_text = "<?php echo $this->config->item('post_project_major_benefits_section_text'); ?>";
var post_project_how_it_works_section_text = "<?php echo $this->config->item('post_project_how_it_works_section_text'); ?>";
var post_fulltime_project_major_benefits_section_text = "<?php echo $this->config->item('post_fulltime_project_major_benefits_section_text'); ?>";
var post_fulltime_project_how_it_works_section_text = "<?php echo $this->config->item('post_fulltime_project_how_it_works_section_text'); ?>";




// config vaiables for section tooltip messages start //

var post_project_page_hover_tooltip_message_category_section = "<?php echo $this->config->item('post_project_page_hover_tooltip_message_category_section'); ?>";

var post_fulltime_project_page_hover_tooltip_message_category_section = "<?php echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_category_section'); ?>";

var post_project_page_hover_tooltip_message_budget_range_section = "<?php echo $this->config->item('post_project_page_hover_tooltip_message_budget_range_section'); ?>";

var post_fulltime_project_page_hover_tooltip_message_salary_range_section = "<?php echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_salary_range_section'); ?>";

var post_project_page_hover_tooltip_message_project_title_section = "<?php echo $this->config->item('post_project_page_hover_tooltip_message_project_title_section'); ?>";

var post_fulltime_project_page_hover_tooltip_message_position_name_section = "<?php echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_position_name_section'); ?>";

var post_project_page_hover_tooltip_message_project_description_section = "<?php echo $this->config->item('post_project_page_hover_tooltip_message_project_description_section'); ?>";

var post_fulltime_project_page_hover_tooltip_message_position_description_section = "<?php echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_position_description_section'); ?>";

var post_project_page_hover_tooltip_message_project_attachment_upload_section = "<?php echo $this->config->item('post_project_page_hover_tooltip_message_project_attachment_upload_section'); ?>";

var post_fulltime_project_page_hover_tooltip_message_attachment_upload_section = "<?php echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_attachment_upload_section'); ?>";

var post_project_page_hover_tooltip_message_project_tag_section = "<?php echo $this->config->item('post_project_page_hover_tooltip_message_project_tag_section'); ?>";

var post_fulltime_project_page_hover_tooltip_message_tag_section = "<?php echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_tag_section'); ?>";

var post_project_page_hover_tooltip_message_project_location_section = "<?php echo $this->config->item('post_project_page_hover_tooltip_message_project_location_section'); ?>";

var post_fulltime_project_page_hover_tooltip_message_position_location_section = "<?php echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_position_location_section'); ?>";

var post_project_page_hover_tooltip_message_payment_method_section = "<?php echo $this->config->item('post_project_page_hover_tooltip_message_payment_method_section'); ?>";

var post_fulltime_project_page_hover_tooltip_message_payment_method_section = "<?php echo $this->config->item('post_fulltime_project_page_hover_tooltip_message_payment_method_section'); ?>";





var popup_alert_heading = "<?php echo $this->config->item('popup_alert_heading'); ?>";
var maximum_allowed_number_of_attachments_on_projects = "<?php echo $this->config->item('maximum_allowed_number_of_attachments_on_projects'); ?>";
//var post_project_page_url = "<?php echo $this->config->item('post_project_page_url'); ?>";
var dashboard_page_url = "<?php echo $this->config->item('dashboard_page_url'); ?>";
var preview_draft_project_page_url = "<?php echo $this->config->item('preview_draft_project_page_url'); ?>";
var project_detail_page_url = "<?php echo $this->config->item('project_detail_page_url');?>";
var upload_blank_attachment_alert_message = "<?php echo $this->config->item('upload_blank_attachment_alert_message'); ?>";	

var category_options = "";
var	project_id = '<?php echo $project_id ?>';
var show_project_upgrade_amount_staus = false;
//$("#project_type_fixed").prop("checked", true);
$("#post_project").prop("checked", true);
<?php 
	if(!empty($project_parent_categories))
	{
		foreach ($project_parent_categories as $project_parent_category_row){ 
?>	
		category_options += '<option value="'+<?php echo $project_parent_category_row['id']; ?>+'">'+'<?php echo $project_parent_category_row['name']; ?>'+'</option>';
<?php 
		} 
	}
?>
<?php
	if($project_data['project_type'] == 'fulltime'){
?>	
	$("#fulltime_salary_range").css('display','block');	
	$("#fulltime_salary_range").removeAttr('disabled');
	$("#hourly_rate_based_budget").css('display','none');	
	$("#hourly_rate_based_budget").attr('disabled','disabled');	
	$("#fixed_budget").attr('disabled','disabled');	
	$("#fixed_budget").css('display','none');
	$("#project_type_block").css('display','none');
	$("#project_budget").css('display','block');
	$("#post_fulltime_position").prop("checked", true);
	$("#project_type_fixed").prop("checked", false);
	$("#post_project").prop("checked", false);
	$("#project_type_hourly").prop("checked", false);
	$('#post_fulltime_position').next('label').css({ 'pointer-events': 'none' });
<?php
	}else{
?>
		$('#post_project').next('label').css({ 'pointer-events': 'none' });	
		$("#fulltime_salary_range").css('display','none');	
		$("#fulltime_salary_range").attr('disabled','disabled');
		$("#post_fulltime_position").prop("checked", false);
<?php
		if($project_data['project_type'] == 'fixed'){
?>	
		$('#project_type_fixed').next('label').css({ 'pointer-events': 'none' });
		$("#project_type_fixed").prop("checked", true);
		$("#post_project").prop("checked", true);
		$("#hourly_rate_based_budget").css('display','none');	
		$("#hourly_rate_based_budget").attr('disabled','disabled');			
<?php
		}else if($project_data['project_type'] == 'hourly'){
?>
		$('#project_type_hourly').next('label').css({ 'pointer-events': 'none' });
		$("#project_type_hourly").prop("checked", true);
		$("#post_project").prop("checked", true);
		$("#fixed_budget").css('display','none');	
		$("#fixed_budget").attr('disabled','disabled');
<?php
		}
	
	}
?>	
<?php
	if(!empty($project_data['county_id'])){
?>
		//$(".location_option").prop("checked", true);
		$(".location_section").css('display','block');
	<?php
	}else{
	?>
		
		$(".location_section").css('display','none');
		$(".county-details #project_county_id").prop('selectedIndex',0);
		$(".county-details #project_locality_id").prop('selectedIndex',0);
		$(".county-details #project_locality_id").attr('disabled','disabled');
		$(".county-details #project_locality_id").html('<option value="">'+select_locality+'</option>');
		$(".county-details #project_postal_code_id").prop('selectedIndex',0);
		$(".county-details #project_postal_code_id").attr('disabled','disabled');
		$(".county-details #project_postal_code_id").html('<option value="">'+select_postal_code+'</option>');
		
	<?php
	} 
	if($project_data['escrow_payment_method'] == 'Y'){
	?>
		$(".escrow_payment_method").prop("checked", true);	
	<?php
	}if($project_data['offline_payment_method'] == 'Y'){
	?>
		$(".offline_payment_method").prop("checked", true);	
	<?php
	}if($project_data['featured'] == 'Y'){
		$this->config->item('project_upgrade_price_hidden')
	?>
		$(".upgrade_type_featured").prop("checked", true);
		//show_project_upgrade_amount_staus = true;
	<?php
	}if($project_data['urgent'] == 'Y'){
	?>
		$(".upgrade_type_urgent").prop("checked", true);
		//show_project_upgrade_amount_staus = true;
	<?php
	}if($project_data['sealed'] == 'Y'){
	?>
		$(".upgrade_type_sealed").prop("checked", true);
		//show_project_upgrade_amount_staus = true;
	<?php
		
	}if($project_data['hidden'] == 'Y'){
	?>
		$(".upgrade_type_hidden").prop("checked", true);	
		//show_project_upgrade_amount_staus = true;
	<?php
	}
	//if( $count_project_categories >= $this->config->item('number_project_category_post_project')){
	?>
		//$("#add_more_project_category").css('display','none'); // check number of category block if there are more less then 5 it will show the add more category option
		//$("#add_more_project_category").attr('disabled','disabled');// 
	<?php
	//}
	if( $count_project_attachments >= $this->config->item('maximum_allowed_number_of_attachments_on_projects')){
	?>
		//$(".upload-btn-wrapper").css('display','none');
		$(".fileinput-button").hide();
	<?php
	}if($count_project_tags >= $this->config->item('number_tag_allowed_post_project')){
	?>
		$("#input_tags").css('display','none');
		$("#input_tags").next().css('display','none');
		$("#save_tag_button_section,#save_tag_button_section_responsive").css('display','none');
		$("#save_tag_button_section .btn, #save_tag_button_section_responsive .btn").prop('disabled',true);
	<?php
	}
	if($count_project_postal_codes  == '1'){
	?>
		$('.county-details #project_postal_code_id option[value=""]').css('display','none');	
	<?php
	}
	if(!empty($project_data['postal_code_id'])){
	?>
		//$('#project_postal_code_id option[value=""]').css('display','none');
	<?php
	}
	if($project_data['postal_code_id'] == 0){
	?>
	$('#project_postal_code_id option[value=""]').css('display','none');
	<?php	
	}	
	if(!empty($project_data['min_budget'])){
	?>
		$('#fixed_budget option[value=""]').css('display','none');
	<?php
	}
	if(!empty($project_data['county_id'])){
	?>
	//$('#project_county_id option[value=""]').css('display','none');
	<?php
	}
	if($project_data['county_id'] == 0){
	?>
	$('#project_county_id option[value=""]').css('display','none');
	<?php	
	}	
	if($project_data['locality_id']){
	?>
	//$('#project_locality_id option[value=""]').css('display','none');
	<?php
	}
	if($project_data['locality_id'] == 0){
	?>
	$('#project_locality_id option[value=""]').css('display','none');
	<?php	
	}	
	if($project_upgrade_type_total_amount > 0){
	?>
		$(".total-price").css("display","block");	
	<?php
	}
	?>
	if($("#upgrade_message .default_terms_text").length > 0){
		$("#upgrade_disclaimer_separator").css('display','block');
	}
	if($("#upgrade_message .default_terms_text").length == 0){
		$("#upgrade_disclaimer_separator").css('display','none');
	}
featured_time_valid = "<?php echo !empty($featured_valid_time_arr) ? true : false; ?>";
urgent_time_valid = "<?php echo !empty($urgent_valid_time_arr) ? true : false; ?>";
sealed_time_valid = "<?php echo !empty($sealed_valid_time_arr) ? true : false; ?>";
hidden_time_valid = "<?php echo !empty($hidden_valid_time_arr) ? true : false; ?>";
upgrades = "<?php echo count($upgrades); ?>";

$(".block-sectn").popover({ trigger: "manual" , html: true, animation:false})
    .on("mouseenter", function () {
        var _this = this;
        $(this).popover("show");
		$(".hidden-mobile").css('opacity',0.3);
        $(".popover").on("mouseleave", function () {
            $(_this).popover('hide');
			$(".hidden-mobile").css('opacity',1);
        });
    }).on("mouseleave", function () {
        var _this = this;
        //setTimeout(function () {
            if (!$(".popover:hover").length) {
                $(_this).popover("hide");
				$(".hidden-mobile").css('opacity',1);
            }
       /* }, 300);*/
});
</script>
<script src="<?=ASSETS?>js/dropzone.js"></script>
<script src="<?php echo JS; ?>modules/edit_draft_project.js"></script>



