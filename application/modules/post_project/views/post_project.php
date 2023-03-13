<?php
$CI =& get_instance();
?>
<?php
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

if($user_detail['current_membership_plan_id'] == 1){
	$po_max_draft_projects_number = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
	$po_max_open_projects_number =  $this->config->item('free_membership_subscriber_max_number_of_open_projects');

	$po_max_draft_fulltime_projects_number = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
	$po_max_open_fulltime_projects_number =  $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
}
if($user_detail['current_membership_plan_id'] == 4){
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
				'id' => 'post_project_form',
				'class' => '',
				'role' => 'form',
				'name' => 'post_project_form',
				'onsubmit' => "return post_project();",
				'enctype' => 'multipart/form-data',
			];
			echo form_open('', $attributes);
		?>
		<input type="hidden" name="temp_project_id" value="<?php echo $temp_project_id; ?>"/>
		<input type="hidden" name="page_type" value="form"/>
			<div class="default_block_header_transparent nBorder post_project_header">
				<div class="transparent transparent_heading" id="post_project_page_heading"><?php echo $this->config->item('post_project_page_default_heading'); ?></div>
				<!-- check box button -->
				<div class="checkbox-btn-sectn block-sectn postProject_checkboxBtn create_project_job" style="display:block" id="project_based_option" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php echo $this->config->item('post_project_page_hover_tooltip_message_post_project_type_option_description_section'); ?>">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12">
							<div class="default_radio_button">
								<section>
									<div>
										<input type="radio" id="post_project" name="project_type_main" value="post_project" class="post_project_input">
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
				<div class="checkbox-btn-sectn block-sectn postProject_checkboxBtn paymnt_perhour" id="project_type_block" style="display:none" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php echo $this->config->item('post_project_page_hover_tooltip_message_post_project_fixed_hourly_type_option_description_section'); ?>">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12">
							<div class="default_radio_button">
								<section>
									<div>
										<input type="radio" id="checkbox_3" name="project_type" value="fixed" class="post_project_type_input">
										<label for="checkbox_3">
											<span><?php echo $this->config->item('post_project_option_fixed_budget_project_heading'); ?></span>
										</label>
									</div>
									<div>
										<input type="radio" id="checkbox_4" name="project_type" value="hourly" class="post_project_type_input">
										<label for="checkbox_4">
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
				<div class="block-sectn categories-select-sectn pCategory_first" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php echo $this->config->item('post_project_page_hover_tooltip_message_category_section'); ?>" style="display:none">
					<div class="row">
						<div class="col-md-12" id="category_listing_block">
							<h4 class="inner-block-heading"><span id="project_category_section_heading" class="default_black_bold_large"><?php echo $this->config->item('post_project_category_section_heading'); ?></span></h4>
							<div class="row category_row" id ="project_category_row_0">
								<div class="col-sm-6 categoryFirst">
									<div class="form-group default_dropdown_select"> 
										<select name="project_category[0][project_parent_category]" id="<?php echo "project_parent_category_0" ?>" class="project_parent_category">
											<option value=""><?php echo $this->config->item('post_project_page_category_drop_down_option_select_category'); ?></option>
											<?php
											if(!empty($project_parent_categories)){
												foreach ($project_parent_categories as $project_parent_category_row){ 
													
											?>
												<option  value="<?php echo $project_parent_category_row['id']; ?>"><?php echo $project_parent_category_row['name']."";?></option>
											<?php
												}
											}	
											?>		   
										</select>                    
									</div>
								</div>
								<div class="col-sm-6 categorySecond project_child_category_0" style="display:none;">
									<div class="form-group default_dropdown_select">
										<select name="project_category[0][project_child_category]"  id="<?php echo "project_child_category_0" ?>" disabled class="project_child_category">
											<option value=""><?php echo $this->config->item('post_project_page_sub_category_drop_down_option_select_sub_category'); ?></option>
										</select>                    
									</div>
								</div>
							</div>
						</div>
						<?php
						$show_category_button_status = 'display:none';
						if($this->config->item('number_project_category_post_project') > 1 &&   $count_available_project_parent_category_count > 1 ){
							$show_category_button_status = 'display:block';
						}
						
						?>
						<div class="col-sm-6 addCategory_btn" id="add_more_project_category_col" style="<?php echo $show_category_button_status; ?>">
							<button type="button" class="btn blue_btn btn-block default_btn addAnCat" id="add_more_project_category" disabled>
							<?php
							echo $this->config->item('post_project_page_add_another_category_button_txt');
							?>	
							</button>
						</div>
						<div class="col-sm-12 error_div_height_auto" id="project_parent_category_0_col">
							<div class="form-group" id="project_parent_category_0">
								<div class="error_div_sectn clearfix"  style="display:none;">
									<span id="project_parent_category_0_error" class="error_msg"></span>
								</div>
							</div>
						</div>			
					</div>
				</div>
				<!--end Choose the most relevant categories for your position -->
				
				
				

				<!-- What budget do you have in mind ? -->
				<div class="block-sectn select-budget scope_budget" style="display:none" id="project_budget" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php echo $this->config->item('post_project_page_hover_tooltip_message_budget_range_section'); ?>">
					<div class="row">
						<div class="col-md-12">
							<h4 class="inner-block-heading default_black_bold_large"><span id="project_budget_section_heading"><?php echo $this->config->item('post_project_budget_range_section_heading'); ?></span></h4> 
						</div>
						<div class="col-md-6 project_select_budget">
							<div class="form-group default_dropdown_select margin_bottom0 error_div_height_auto"> 
								<select name="project_budget" id="fixed_budget">
									<option value=""><?php echo $this->config->item('post_project_page_budget_drop_down_option_select_budget'); ?></option>
									<?php 
									if(!empty($fixed_budget_projects_budget_range))
									{
										foreach ($fixed_budget_projects_budget_range as $fixed_budget_projects_budget_range_row){ 
									?>
										<option value="<?php echo $fixed_budget_projects_budget_range_row['fixed_budget_range_key']; ?>"><?php echo $fixed_budget_projects_budget_range_row['fixed_budget_range_value'];?></option>
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
										<option value="<?php echo $hourly_rate_based_budget_projects_budget_range_row['hourly_rate_based_budget_range_key']; ?>"><?php echo $hourly_rate_based_budget_projects_budget_range_row['hourly_rate_based_budget_range_value'];?></option>
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
										<option value="<?php echo $fulltime_projects_salary_range_row['fulltime_salary_range_key']; ?>"><?php echo $fulltime_projects_salary_range_row['fulltime_salary_range_value'];?></option>
									<?php 
										} 
									}
									?>
								</select> 
								<div class="error_div_sectn clearfix">
									<span id="project_budget_error" class="error_msg"></span>
								</div>
							</div>
						</div>
						<div class="col-md-6 project_select_continue">
							<!--<button type="button" class="btn default_btn blue_btn btn-block addAnCat" id="project_next_button" disabled ><?php //echo $this->config->item('post_project_page_next_button_txt'); ?></button>-->
						</div>
						<!-- <div class="hover-tooltip-sectn" id="project_budget_section_tooltip" style="display:none;">
						  <?php //echo $this->config->item('post_project_page_hover_tooltip_message_budget_range_section'); ?>
						</div> -->
					</div>
				</div>
				<!--end What budget do you have in mind ? -->

				<!-- Project name -->
				<div class="block-sectn file-project-name project_show_block projectName_section"  id="project_title_section" style="display:none" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php echo $this->config->item('post_project_page_hover_tooltip_message_project_title_section'); ?>">
					<div class="row">
						<div class="col-md-12">
							<h4 class="inner-block-heading default_black_bold_large"><span id="project_title_section_heading"><?php echo $this->config->item('post_project_title_section_heading'); ?></span></h4> 
						</div>
						<div class="col-md-12">
							<div class="form-group projectName"> 
								<input type="text" name="project_title" id="project_title" class="avoid_space default_input_field" maxlength="<?php echo $this->config->item('project_title_maximum_length_characters_limit_post_project'); ?>"> 
								<div class="error_div_sectn clearfix default_error_div_sectn">
                                    <!-- <span class="error_title">
										<span id="project_title_error" class="error_msg"></span>
                                    </span> -->
									<span class="content-count project_title_length_count_message"><?php echo $this->config->item('project_title_maximum_length_characters_limit_post_project')."&nbsp;".$this->config->item('characters_remaining_message'); ?></span>
									<span id="project_title_error" class="error_msg"></span>
								</div>   
							</div>
						</div>
						<div class="col-sm-6"></div>
						<!-- <div class="hover-tooltip-sectn" style="display:none;" id="project_title_section_tooltip">
							<?php //echo $this->config->item('post_project_page_hover_tooltip_message_project_title_section'); ?>
						</div> -->
					</div>
				</div>
				<!-- end Project name -->

				<!-- Describe your project in detail: -->
				<div id="project_description_section" class="block-sectn project-describe project_show_block noBorder_block_sectn projectDescription_section" style="display:none" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php echo $this->config->item('post_project_page_hover_tooltip_message_project_description_section'); ?>">
					<div class="row">
						<div class="col-md-12">
							<h4 class="inner-block-heading default_black_bold_large"><span id="project_description_section_heading"><?php echo $this->config->item('post_project_description_section_heading'); ?></span></h4> 
						</div>
						<div class="col-md-12">
							<div class="form-group margin0"> 
								<textarea name="project_description" id="project_description" class="avoid_space_textarea default_textarea_field" maxlength="<?php echo $this->config->item('project_description_maximum_length_characters_limit_post_project'); ?>"></textarea>
								<div class="error_div_sectn clearfix postProjectUpload">
									<span class="content-count project_description_length_count_message"><?php echo $this->config->item('project_description_maximum_length_characters_limit_post_project')."&nbsp;".$this->config->item('characters_remaining_message'); ?></span>
									<span class="error_description">
										<span id="project_description_error" class="error_msg errMesgOnly"></span>
										<span id="project_attachment_section" class="block-sectn upload-document project_show_block uploadDoc" style="display:none;" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php echo $this->config->item('post_project_page_hover_tooltip_message_project_attachment_upload_section'); ?>">
											<div class="upload-btn-wrapper pPfileInput_btn">
												<span class="btn fileinput-button default_btn blue_btn">
													<i class="fa fa-cloud-upload"></i><?php echo $this->config->item('post_project_page_upload_file_button_txt'); ?>
												</span><div class="upload_attachment_error errAttachment"></div><div id="project_attachment_container" class="attachment_inline" style="display:none;"></div>
											</div>
										</span>
									</span>
								</div>
							</div>
						</div>
						<!-- <div class="col-md-6"></div> -->
						<!-- <div class="hover-tooltip-sectn" style="display:none;" id="project_description_section_tooltip">
						<?php //echo $this->config->item('post_project_page_hover_tooltip_message_project_description_section'); ?>
						</div> -->
						</div>
				</div>
				<!-- Describe your project in detail -->
				
				<!-- Upload your document -->
				<!-- <div id="project_attachment_section" class="block-sectn upload-document project_show_block uploadDoc" style="display:none;" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php //echo $this->config->item('post_project_page_hover_tooltip_message_project_attachment_upload_section'); ?>">
					<div class="row">
						<div class="col-md-12">
							<div class="upload-btn-wrapper pPfileInput_btn">
								<span class="btn fileinput-button default_btn blue_btn">
									<i class="fa fa-cloud-upload"></i><?php //echo $this->config->item('post_project_page_upload_file_button_txt'); ?>
								</span><div class="upload_attachment_error errAttachment"></div><div id="project_attachment_container" class="attachment_inline" style="display:none;"></div>
							</div>
						</div>
					</div>
				</div> -->
				<!-- Upload your document -->
				
				<!-- tags -->
				<div class="block-sectn tags-sectn project_show_block" id="project_tag_section" style="display:none" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php echo $this->config->item('post_project_page_hover_tooltip_message_project_tag_section'); ?>">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12">
							<div class="receive_notification" id="tagSL">
								<a class="rcv_notfy_btn" onclick="showMoreTags()" id="project_tag_heading_section"><?php echo $this->config->item('post_project_project_tags_section_heading'); ?> <small>( + )</small></a>
								<input type="hidden" id="moreTags" value="1">
							</div>
							<div id="more_tags" class="collapse row">
								<!-- <div class="col-md-12">
									<h4 class="inner-block-heading default_black_bold_large"><span id="project_tag_heading_section"><?php //echo $this->config->item('post_project_project_tags_section_heading'); ?></span></h4> 
								</div> -->
								<div class="tagBottom">
									<input type="text" id="input_tags" name=""  class="avoid_space default_input_field" maxlength="<?php echo $this->config->item('project_tag_maximum_length_characters_limit_post_project'); ?>" >
									<div class="error_div_sectn clearfix tagyError">
										<span class="error_save">
											<span id="tag_error" class="error_msg" style="display:none;"></span>
											<div class="saveTAg_responsive" id="save_tag_button_section_responsive" style="display: none;"><button type="button" class="btn default_btn blue_btn save_tag_button" disabled><?php echo $this->config->item('save_btn_txt'); ?></button></div>
										</span>
										<span class="content-count project_tag_length_count_message"><?php echo $this->config->item('project_tag_maximum_length_characters_limit_post_project')."&nbsp;".$this->config->item('characters_remaining_message'); ?></span>
									</div>
									
									</div>
									<div class="saveTAg" id="save_tag_button_section"><button type="button" disabled class="btn default_btn blue_btn save_tag_button"><?php echo $this->config->item('save_btn_txt'); ?></button></div>
									<div class="clearfix"></div>
									<div class="col-md-12">
										<ul id="tags-list" class="default_cross_tag"></ul>
									</div>
								
							</div>
						</div>
						<!-- <div class="hover-tooltip-sectn" style="display: none;" id="project_tag_section_tooltip">
							<?php //echo $this->config->item('post_project_page_hover_tooltip_message_project_tag_section'); ?>
						</div> -->
					</div>
				</div>
				<!-- end tags -->

				<!-- Where do you want this done? -->
				<div class="block-sectn county-details project_show_block" style="display:none" id="location_block" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php echo $this->config->item('post_project_page_hover_tooltip_message_project_location_section'); ?>">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12">
							<div class="receive_notification" id="locationSL">					
								<a class="rcv_notfy_btn location_option chk-btn" onclick="showMorePow()" id="location_heading"><?php echo $this->config->item('post_project_page_location_heading'); ?> <small>( + )</small></a>
								<input type="hidden" id="morePow" value="1" name="location_option">
							</div>
							<!-- <div id="more_pow" class="collapse row">
								<div class="col-md-12">
									<div class="default_checkbox_button">
										<input type="checkbox" id="this-check" name="location_option" value="location" class="location_option chk-btn">
										<label for="this-check"><?php //echo $this->config->item('post_project_page_location_heading'); ?></label>
									</div>
									<div class="form-group" style="margin:0;">
										<div class="checkbox custom_check_box">  
											<div class="checkbox-btn-inner">
												<input id="this-check" style="position: absolute;top: 0;width: 100%;" type="checkbox" class="location_option" value="location" name="location_option">
												<div class="checkbox-inner-div d-inline-block">
												  <label for="this-check"></label>
												 <span id="location_heading"><?php //echo $this->config->item('post_project_page_location_heading'); ?><span>
												</div>
											</div>
										</div>
									</div>
								</div> -->
							<!-- </div> -->
							<div id="more_pow" class="collapse location_section">
								<div class="row">
									<div class="col-md-5 col-sm-5 col-12 placeWork">
										<div class="form-group default_dropdown_select"> 
											<select id="project_county_id" name="project_county_id">
												<option value="" ><?php echo $this->config->item('post_project_page_county_drop_down_option_select_county'); ?></option>
												<?php foreach ($counties as $county): ?>
												<option value="<?php echo $county['id']; ?>"><?php echo $county['name'] ?></option>
												<?php endforeach; ?>
											</select> 
											<div class="error_div_sectn clearfix">
												<span id="project_county_id_error" class="error_msg"></span>
											</div>
										</div>
									</div>
									<div class="col-md-5 col-sm-5 col-12 selectMunicipility" id="selectMunicipility" style="display:none">
										<div class="form-group default_dropdown_select"> 
											<select  name="project_locality_id" id="project_locality_id" disabled>
												<option value=""><?php echo $this->config->item('post_project_page_locality_drop_down_option_select_locality'); ?></option>
											</select>
											<div class="error_div_sectn clearfix">
												<span id="project_locality_id_error" class="error_msg"></span>
											</div>
										</div>
									</div>
									<div class="col-md-2 col-sm-2 col-12 selectPostcode" id="selectPostcode" style="display:none">
										<div class="form-group default_dropdown_select"> 
											<select name="project_postal_code_id" id="project_postal_code_id" disabled="disabled" >
												<option value=""><?php echo $this->config->item('post_project_page_postal_code_drop_down_option_select_postal_code'); ?></option>
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
						<!-- <div class="hover-tooltip-sectn" style="display: none;" id="project_location_section_tooltip"> 
						<?php //echo $this->config->item('post_project_page_hover_tooltip_message_project_location_section'); ?>
						</div> -->
					</div>
				</div>
				<!-- Where do you want this done? -->

				<!-- Payment Methods -->
				<div class="payment-methods block-sectn project_show_block" id="payment_method_section" style="display:none" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php echo $this->config->item('post_project_page_hover_tooltip_message_payment_method_section'); ?>">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12">
							<div class="receive_notification" id="pmethodSL">					
								<a class="rcv_notfy_btn inner-block-heading default_black_bold_large" onclick="showMorePmethod()" id="project_payment_method_section_heading"><?php echo $this->config->item('post_project_payment_method_section_heading'); ?> <small>( + )</small></a>
								<input type="hidden" id="morePmethod" value="1">
							</div>
							<div id="more_pmethod" class="collapse row paymentMethod">
								<!-- <div class="col-md-12">
									<h4 class="inner-block-heading default_black_bold_large"><span id="project_payment_method_section_heading"><?php //echo $this->config->item('post_project_payment_method_section_heading'); ?></span></h4>
								</div> -->
								<div class="col-md-12 fontSize0">
									<div class="drpChk form-group">
										<label for="payment-methods1" class="default_checkbox">
											<input type="checkbox" id="payment-methods1" name="escrow_payment_method" value="Y" class="escrow_payment_method chk-btn">
											<span class="checkmark"></span>
										<small id="payment_method_escrow_text"><?php echo $this->config->item('post_project_page_payment_method_section_text_escrow_payment'); ?></small>
										</label>
									</div>
									<!-- <div class="form-group">
										<div class="default_checkbox_button">
											<input type="checkbox" id="payment-methods1" name="escrow_payment_method" value="Y" class="escrow_payment_method chk-btn">
											<label id="payment_method_escrow_text" for="payment-methods1"><?php //echo $this->config->item('post_project_page_payment_method_section_text_escrow_payment'); ?></label>
										</div>
									</div> -->
									<!-- <div class="drpChk">
										<label for="seven" class="default_checkbox">
											<input type="checkbox" id="seven" value="all"/>
											<span class="checkmark"></span>
										</label>
										<small>All</small>
									</div> -->
								
								  <!-- <div class="form-group">
									<div class="checkbox custom_check_box">  
									  <div class="checkbox-btn-inner">
										<input id="payment-methods1" class="escrow_payment_method" name="escrow_payment_method" value="Y" style="position: absolute;top: 0;width: 100%;" type="checkbox">
										<div class="checkbox-inner-div">
											<label for="payment-methods1"></label>
											<span id="payment_method_escrow_text">
											<?php //echo $this->config->item('post_project_page_payment_method_section_text_escrow_payment'); ?>
											</span>
										</div>
									  </div>  
									</div>
								  </div> -->
									<div class="drpChk form-group">
										<label for="payment-methods2" class="default_checkbox">
											<input type="checkbox" id="payment-methods2" name="offline_payment_method" value="Y" class="offline_payment_method chk-btn">
											<span class="checkmark"></span>
										<small id="payment_method_offline_text"><?php echo $this->config->item('post_project_page_payment_method_section_text_offline_payment'); ?></small>
										</label>
									</div>
									<!-- <div class="form-group">
										<div class="default_checkbox_button">
											<input type="checkbox" id="payment-methods2" name="offline_payment_method" value="Y" class="offline_payment_method chk-btn">
											<label id="payment_method_offline_text" for="payment-methods2"><?php //echo $this->config->item('post_project_page_payment_method_section_text_offline_payment'); ?></label>
										</div>
									</div> -->
									
								  <!-- <div class="form-group">
									<div class="checkbox custom_check_box">  
									  <div class="checkbox-btn-inner">
										<input id="payment-methods2" class="offline_payment_method" name="offline_payment_method" value="Y" style="position: absolute;top: 0;width: 100%;" type="checkbox">
										<div class="checkbox-inner-div">
											<label for="payment-methods2"></label>
											<span id="payment_method_offline_text">	
											<?php //echo $this->config->item('post_project_page_payment_method_section_text_offline_payment'); ?>
											</span>
										</div>
									  </div>
									</div>
								  </div> -->
								</div>
							</div>
						</div>
						<!-- <div class="hover-tooltip-sectn" style="display:none;" id="project_payment_method_section_tooltip">
							<?php //echo $this->config->item('post_project_page_hover_tooltip_message_payment_method_section'); ?>
						</div> -->
					</div>
				</div>
				<!-- end Payment Methods -->
				
				<!-- Get most from your project! (optional) -->
				<div class="most-project block-sectn-without-blue-tooltip project_show_block" style="display:none">
				  <div class="row">
					  <div class="col-md-12">
					  <h4 class="inner-block-heading default_black_bold_large improveAD"><span id="project_optional_upgrades_section_heading"><?php echo $this->config->item('post_project_optional_upgrades_section_heading'); ?></span></h4>
					  <div class="form-group highlighted_project">
						<div class="checkbox-btn-inner">
							<input id="most-project1" style="position: absolute;top: 0;width: 100%;" type="checkbox" class="upgrade_type_featured upgrade_type" name="upgrade_type_featured" value="Y">
							<div class="checkbox-inner-div">
								<label for="most-project1">
									<div class="row">
										<div class="checkbox-title"> <span class="upgrade_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></span></div>
										<div class="pay-sectn" id="upgrade_type_featured_amount_container">
										
											<?php
											if(($count_user_featured_membership_included_upgrades_monthly <$user_membership_plan_details->included_number_featured_upgrades) || $user_membership_plan_details->included_number_featured_upgrades == '-1'){
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
										<p id="featured_description" class="upgrade_badge_description badge_bigger_view"><?php echo str_replace(array('{project_featured_upgrade_availability}'),array($featured_availabity_days),$this->config->item('post_project_page_project_upgrade_description_featured')); ?></p>
                                         <p id="featured_description" class="upgrade_badge_description badge_smaller_view"><?php echo str_replace(array('{project_featured_upgrade_availability}'),array($featured_availabity_days),$this->config->item('post_project_page_project_upgrade_description_featured_small_resolution_view')); ?></p>
									</div>
								</label>
							</div>
						  </div>
					  </div>
					 <div class="form-group urgent_project">
					  <div class="checkbox-btn-inner">
						  <input id="most-project2"  class="upgrade_type_urgent upgrade_type" style="position: absolute;top: 0;width: 100%;" type="checkbox" name="upgrade_type_urgent"  value="Y">
							<div class="checkbox-inner-div">
								<label for="most-project2">
									<div class="row">
										<div class="checkbox-title"> <span class="upgrade_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></span></div>
										<div class="pay-sectn" id="upgrade_type_urgent_amount_container">
											<?php

											if(($count_user_urgent_membership_included_upgrades_monthly <$user_membership_plan_details->included_number_urgent_upgrades) || $user_membership_plan_details->included_number_urgent_upgrades== '-1'){
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
									  <p id="urgent_description" class="upgrade_badge_description badge_bigger_view"><?php echo str_replace(array('{project_urgent_upgrade_availability}'),array($urgent_availabity_days),$this->config->item('post_project_page_project_upgrade_description_urgent')); ?></p>
                                      <p id="urgent_description" class="upgrade_badge_description badge_smaller_view"><?php echo str_replace(array('{project_urgent_upgrade_availability}'),array($urgent_availabity_days),$this->config->item('post_project_page_project_upgrade_description_urgent_small_resolution_view')); ?></p>
									</div>
								</label>
							</div>
						</div>
					  </div>
					  <div class="form-group notpublic_project">
						<div class="checkbox-btn-inner">
						  <input id="most-project3"  class="upgrade_type_sealed upgrade_type" style="position: absolute;top: 0;width: 100%;" type="checkbox" name="upgrade_type_sealed" value="Y" >
							<div class="checkbox-inner-div">
								<label for="most-project3">
									<div class="row">
									  <div class="checkbox-title"> <span class="upgrade_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></span> </div>
										<div class="pay-sectn" id="upgrade_type_sealed_amount_container"> 
										
											<?php
											if(($count_user_sealed_membership_included_upgrades_monthly <$user_membership_plan_details->included_number_sealed_upgrades) || $user_membership_plan_details->included_number_sealed_upgrades== '-1'){
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
									  <p id="sealed_description" class="upgrade_badge_description badge_bigger_view"><?php echo $this->config->item('post_project_page_project_upgrade_description_sealed'); ?></p>
                                       <p id="sealed_description" class="upgrade_badge_description badge_smaller_view"><?php echo $this->config->item('post_project_page_project_upgrade_description_sealed_small_resolution_view'); ?></p>
									</div>
								</label>
							</div>
						</div>
					  </div>
					  
						<div class="form-group hidden_project">
						<div class="checkbox-btn-inner">
						  <input id="most-project4"  class="upgrade_type_hidden upgrade_type" style="position: absolute;top: 0;width: 100%;" type="checkbox" name="upgrade_type_hidden" value="Y">
							<div class="checkbox-inner-div">
								<label for="most-project4">
									<div class="row">
									  <div class="checkbox-title"> <span class="upgrade_hidden"><?php echo $this->config->item('post_project_page_upgrade_type_hidden'); ?></span></div>
										<div class="pay-sectn" id="upgrade_type_hidden_amount_container">
										
											<?php
											if(($count_user_hidden_membership_included_upgrades_monthly <$user_membership_plan_details->included_number_hidden_upgrades) || $user_membership_plan_details->included_number_hidden_upgrades== '-1'){
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
									  <p id="hidden_description" class="upgrade_badge_description badge_bigger_view"><?php echo $this->config->item('post_project_page_project_upgrade_description_hidden'); ?></p>
                                      <p id="hidden_description" class="upgrade_badge_description badge_smaller_view"><?php echo $this->config->item('post_project_page_project_upgrade_description_hidden_small_resolution_view'); ?></p>
									</div>
								</label>
							</div>
						</div>
						</div>
						<div id="upgrade_disclaimer_separator"style="display:none;border-top:1px solid #d7d8e0;margin"></div>
						<div class="total-price" style="display:none;">
						<p><?php echo $this->config->item('post_project_page_upgrade_total_txt'); ?>   <span id="total_upgrade_amount" data-attr="0">0</span>  <?php echo CURRENCY; ?></p>
						</div>
						<div id="upgrade_message"></div>
					</div>
				  </div>
				</div>
				<!-- Get most from your project! (optional) -->

				<!-- project relative buttons -->
				<div class="project-relative-btn block-sectn-without-blue-tooltip  project_show_block" style="display:none">
				  <div class="row">
					<div class="col-md-12 text-center pPBtn_gap">
						<button type="button" class="btn default_btn red_btn" id="post_project_cancel"><?php echo $this->config->item('post_project_page_cancel_project_button_txt'); ?></button>
						<button type="button" class="btn default_btn blue_btn" id="post_project_preview"><?php echo $this->config->item('post_project_page_preview_project_button_txt'); ?></button>						
						<button type="button" class="btn default_btn blue_btn" id="post_project_draft"><?php echo $this->config->item('post_project_page_save_project_as_draft_button_txt'); ?></button>
						<button type="button" class="btn green_btn default_btn" id="post_project_publish"><?php echo $this->config->item('post_project_page_publish_project_button_txt'); ?></button>
					</div>
				  </div>
				</div>
				<!-- project relative buttons -->
				
				<!-- Publish Project content -->
				<div class="publish-project-text project_show_block" style="display:none">
				  <div class="row">
						<div class="col-md-12 default_disclaimer_small_text" id="terms_condition_text">
						<?php echo str_replace(array('{terms_and_conditions_page_url}','{privacy_policy_page_url}'),array(site_url($this->config->item('terms_and_conditions_page_url')),site_url($this->config->item('privacy_policy_page_url'))),$this->config->item('post_project_page_accept_terms_and_condition_policy_txt')); ?>
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
				  <?php echo $this->config->item('post_project_major_benefits_section_text'); ?>
				</div>
				<div class="box-overly" id="project_how_it_works_text_container">
				   <?php echo $this->config->item('post_project_how_it_works_section_text'); ?>
				</div>
			</div>
		</div>-->
	  </div><!-- row -->
	</div><!-- container fluid -->
  </div>
 </main>
<script type="text/javascript">

<?php
if(!empty($standard_valid_time_arr) && ($po_max_open_projects_number != '0' && $open_bidding_cnt < $po_max_open_projects_number)){
?>
var disclaimer_show = true;
<?php
}
?>

<?php
if(!empty($standard_valid_time_arr) && ($po_max_open_fulltime_projects_number != '0' && $fulltime_open_bidding_cnt < $po_max_open_fulltime_projects_number)){
?>
var disclaimer_show = true;
<?php
}
?>

<?php					  
	if(!empty($standard_valid_time_arr) && ($po_max_open_projects_number != '0' && $open_bidding_cnt < $po_max_open_projects_number)){
?>
var project_publish_btn_show = true;
<?php
	}
?>

<?php					  
	if(!empty($standard_valid_time_arr) && ($po_max_open_fulltime_projects_number != '0' && $fulltime_open_bidding_cnt < $po_max_open_fulltime_projects_number)){
?>
var fulltime_project_publish_btn_show = true;
<?php
	}
?>

<?php
if(($draft_cnt < $po_max_draft_projects_number && $po_max_draft_projects_number != 0) || ($open_bidding_cnt < $po_max_open_projects_number && (!empty($standard_valid_time_arr) || $po_max_open_projects_number != '0'))){
?>
var project_preview_btn_show = true;
<?php 
		}
?>

<?php
if(($fulltime_draft_cnt < $po_max_draft_fulltime_projects_number && $po_max_draft_fulltime_projects_number != 0) || ($fulltime_open_bidding_cnt < $po_max_open_fulltime_projects_number && (!empty($standard_valid_time_arr) || $po_max_open_fulltime_projects_number != '0'))){
?>
var fulltime_project_preview_btn_show = true;
<?php 
		}
?>

<?php
if($draft_cnt < $po_max_draft_projects_number && $po_max_draft_projects_number != 0){
?>
var project_draft_btn_show = true;
<?php
	}
?>
<?php
if($fulltime_draft_cnt < $po_max_draft_fulltime_projects_number && $po_max_draft_fulltime_projects_number != 0){
?>
var fulltime_project_draft_btn_show = true;
<?php
	}
?>
var signup_page_url = "<?php echo $this->config->item('signup_page_url'); ?>";
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
var post_project_payment_method_section_heading = "<?php echo $this->config->item('post_project_payment_method_section_heading'); ?>" ;
var post_fulltime_position_payment_method_section_heading = "<?php echo $this->config->item('post_fulltime_position_payment_method_section_heading'); ?>";


/* adding on 02-04-2019 */
var post_project_page_heading_post_project = "<?php echo $this->config->item('post_project_page_heading_post_project'); ?>";

var post_project_page_heading_post_fulltime_project = "<?php echo $this->config->item('post_project_page_heading_post_fulltime'); ?>";
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



var terms_condition_fulltime_project_text = "<?php echo str_replace(array('{terms_and_conditions_page_url}','{privacy_policy_page_url}'),array(site_url($this->config->item('terms_and_conditions_page_url')),site_url($this->config->item('privacy_policy_page_url'))),$this->config->item('post_fulltime_project_page_accept_terms_and_condition_policy_txt')); ?>";
//alert(terms_condition_fulltime_project_text);


var terms_condition_project_text = "<?php echo str_replace(array('{terms_and_conditions_page_url}','{privacy_policy_page_url}'),array(site_url($this->config->item('terms_and_conditions_page_url')),site_url($this->config->item('privacy_policy_page_url'))),$this->config->item('post_project_page_accept_terms_and_condition_policy_txt')); ?>";




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
var project_detail_page_url = "<?php echo $this->config->item('project_detail_page_url');?>";

var category_options = "";
var	temp_project_id = '<?php echo $temp_project_id ?>';
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
// project availability settings
var upload_blank_attachment_alert_message = "<?php echo $this->config->item('upload_blank_attachment_alert_message'); ?>";	
standard_time_valid = "<?php echo !empty($standard_valid_time_arr) ? true : false; ?>";
featured_time_valid = "<?php echo !empty($featured_valid_time_arr) ? true : false; ?>";
urgent_time_valid = "<?php echo !empty($urgent_valid_time_arr) ? true : false; ?>";
sealed_time_valid = "<?php echo !empty($sealed_valid_time_arr) ? true : false; ?>";
hidden_time_valid = "<?php echo !empty($hidden_valid_time_arr) ? true : false; ?>";
/*$(function () {
	$('[data-toggle="popover"]').popover({
		container: 'body'
  })
})*/

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
<script src="<?php echo JS; ?>modules/post_project.js"></script>

