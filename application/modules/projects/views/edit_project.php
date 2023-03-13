<?php
$CI = & get_instance ();
$CI->load->library('Cryptor');
$selected_category_array = array();

if(!empty($project_category_data)){
	foreach($project_category_data as $category_key){
		$selected_category_array[] = $category_key['project_category_id'];
	}
}
$count_project_attachments= count($project_attachment_array);
$project_upgrade_type_box_show_status = false;	
$featured_max = 0;
$urgent_max = 0;
$expiration_featured_upgrade_date_array = array();
$expiration_urgent_upgrade_date_array = array();

if(!empty($project_data['featured_upgrade_end_date'])){
	$expiration_featured_upgrade_date_array[] = $project_data['featured_upgrade_end_date'];
}
if(!empty($project_data['bonus_featured_upgrade_end_date'])){
	$expiration_featured_upgrade_date_array[] = $project_data['bonus_featured_upgrade_end_date'];
}
if(!empty($project_data['membership_include_featured_upgrade_end_date'])){
	$expiration_featured_upgrade_date_array[] = $project_data['membership_include_featured_upgrade_end_date'];
}
if(!empty($expiration_featured_upgrade_date_array)){
	$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
}

if(!empty($project_data['urgent_upgrade_end_date'])){
	$expiration_urgent_upgrade_date_array[] = $project_data['urgent_upgrade_end_date'];
}
if(!empty($project_data['bonus_urgent_upgrade_end_date'])){
	$expiration_urgent_upgrade_date_array[] = $project_data['bonus_urgent_upgrade_end_date'];
}
if(!empty($project_data['membership_include_urgent_upgrade_end_date'])){
	$expiration_urgent_upgrade_date_array[] = $project_data['membership_include_urgent_upgrade_end_date'];
}
if(!empty($expiration_urgent_upgrade_date_array)){
	$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
}



if($project_data['featured'] == 'N' && $featured_max < time() && $project_data['hidden'] != 'Y'){
	$project_upgrade_type_box_show_status = true;	
}
	
if($project_data['urgent'] == 'N' && $urgent_max < time() && $project_data['hidden'] != 'Y'){
	$project_upgrade_type_box_show_status = true;	
}	
$project_featured_upgrade_availability_array = explode(':',$this->config->item('project_upgrade_availability_featured'));
$total_project_featured_upgrade_availability_seconds = ($project_featured_upgrade_availability_array[0] * 3600)+($project_featured_upgrade_availability_array[1] * 60)+$project_featured_upgrade_availability_array[2];
$featured_availabity_days = trim(secondsToWords($total_project_featured_upgrade_availability_seconds));

$project_urgent_upgrade_availability_array = explode(':',$this->config->item('project_upgrade_availability_urgent'));
$total_project_urgent_upgrade_availability_seconds = ($project_urgent_upgrade_availability_array[0] * 3600)+($project_urgent_upgrade_availability_array[1] * 60)+$project_urgent_upgrade_availability_array[2];
$urgent_availabity_days = trim(secondsToWords($total_project_urgent_upgrade_availability_seconds));	
##########

?>
<main>
	<!-- header section -->
	<div class="top-hedaer-sectn logoSection editProject_headerSection">
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
				<div class="pProjectLeft editProject_mainSection">
					<?php
					 $attributes = [
						 'id' => 'edit_project_form',
						 'class' => '',
						 'role' => 'form',
						 'name' => 'edit_project_form',
						 'enctype' => 'multipart/form-data',
					];
					 echo form_open('', $attributes);
					?>
						<input type="hidden" name="project_id" value="<?php echo $project_id; ?>"/>
						<input type="hidden" name="page_type" value="form"/>
					<div class="default_block_header_transparent nBorder post_project_header">
						<div class="transparent transparent_heading"><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('edit_fulltime_project_page_heading') : $this->config->item('edit_project_page_heading');?></div>

						<!-- Choose the most relevant categories for your position -->
						<div class="block-sectn categories-select-sectn pCategory_first">
							<h4 class="inner-block-heading default_black_bold_large"><span id="project_category_section_heading"><?php
							if($project_data['project_type'] == 'fulltime'){
								echo $this->config->item('post_fulltime_project_category_section_heading');
							}else{
								echo $this->config->item('post_project_category_section_heading');
							}
							?></span></h4>
							<div class="row">
								<div class="col-md-12" id="category_listing_block">
									<?php
									if(!empty($project_category_data))
									{
										$category_counter = 0;
										foreach($project_category_data as $project_category)
										{
									?>
											<div class="row category_row" id="<?php echo "project_category_row_".$project_category['id']."_".$project_category['project_id']; ?>">	
											<?php
											if(empty($project_category['project_parent_category_id'])){
												$get_project_child_categories = $CI->Post_project_model->get_project_child_categories($project_category['project_category_id']);
												}else{
												$get_project_child_categories = $CI->Post_project_model->get_project_child_categories($project_category['project_parent_category_id']);
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
																	if($project_category['project_parent_category_id'] == 0){
																		$selected_parent_category_id = $project_category['project_category_id'];
																		
																	}else{
																		$selected_parent_category_id = $project_category['project_parent_category_id'];
																	}
																	

																	if($selected_parent_category_id == $project_parent_category_row['id']){
																		$selected_parent_category_selected = "selected";
																	}
																	
																	if(!empty($selected_category_array)){
																		$array_diff = array();
																		$array_diff = array_diff($selected_category_array,array($project_category['project_category_id']));
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
													  
														<?php  if(!empty($get_project_child_categories)){ ?>
														<option value=""><?php echo $this->config->item('post_project_page_sub_category_drop_down_option_select_sub_category'); ?></option>
														<?php
														}
														if(!empty($get_project_child_categories )){
															foreach ($get_project_child_categories as $project_child_category_row) {
															
															$selected_child_category_id = 0;
																$selected_child_category_selected = "";
																if($project_category['project_parent_category_id'] != 0){
																	$selected_child_category_id = $project_category['project_category_id'];
																	
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
											<a style="cursor:pointer" data-id = "<?php echo $project_category['project_category_id'] ?>"  id="<?php echo $project_category['id']."_".$project_category['project_id'] ?>" class="default_icon_red_btn delete-category-row delete_project_category_row_data"><i class="fas fa-trash-alt"></i></a>
											 
											  <?php } ?>
											</div>
									<?php
										$category_counter++;
										}
									}?>
									
								</div>
								<?php
								$show_category_button_status = 'display:none';
								$catgory_button_status = 'disabled';
								if($this->config->item('number_project_category_post_project') > count($project_category_data)  &&  $count_available_project_parent_category_count > count($project_category_data)   ){
									$show_category_button_status = 'display:block';
									$catgory_button_status = '';
								}
								?>
								<div class="col-sm-6 add_more_project_category_section addCategory_btn editProjectAddCategory"  style="<?php echo $show_category_button_status; ?>">
									<button type="button" class="btn default_btn btn-block blue_btn addAnCat" id="add_more_project_category" <?php echo $catgory_button_status; ?>>
									<?php
									if(empty($project_category_data)){
										echo $this->config->item('post_project_page_add_category_button_txt');
									}else{
										echo $this->config->item('post_project_page_add_another_category_button_txt');
									}
									?>	
									</button>
								</div>
								<div class="col-sm-6">
									
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

						<!-- check box button -->
						<!-- <div class="checkbox-btn-sectn project_post_checkbox block-sectn">
							<div class="row">
								<div class="col-sm-6">
								  <div class="form-group" style="margin:0;">
									<div class="checkbox-btn-inner">
									  <input id="post_project" name="project_type_main" class="post_project_input" style="position: absolute;top: 0;width: 100%;" type="radio" value="post_project" disabled>
									  <div class="checkbox-inner-div">
										<label for="post_project">I'm a toggle</label>
										<div class="checkbox-content align-middle">
											<h6><?php //echo $this->config->item('post_project_option_heading'); ?></h6>
										  </div>
									  </div>
									</div>
								  </div>
								</div>
								<div class="col-sm-6">
									<div class="form-group" style="margin:0;">
										<div class="checkbox-btn-inner">
											<input id="post_fulltime_position" class="post_project_input" name="project_type_main" style="position: absolute;top: 0;width: 100%;" type="radio" value="post_fulltime_position" disabled>
											<div class="checkbox-inner-div">
												<label for="post_fulltime_position">I'm a toggle</label>
												<div class="checkbox-content align-middle">
													<h6><?php //echo $this->config->item('post_fulltime_project_option_heading'); ?></h6>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div> -->
						<!-- end check box button -->

						<!-- check box button -->
						<!-- <div class="checkbox-btn-sectn block-sectn project_post_checkbox" id="project_type_block">
						  <div class="row">
							<div class="col-sm-6">
							  <div class="form-group" style="margin:0;">
								<div class="checkbox-btn-inner">
								  <input name="project_type" class="post_project_type_input" style="position: absolute;top: 0;width: 100%;" type="radio" value="fixed" id="project_type_fixed" disabled>
								  <div class="checkbox-inner-div">
									<label for="project_type_fixed">I'm a toggle</label>
									<div class="checkbox-content align-middle">
									  <h6><?php //echo $this->config->item('post_project_option_fixed_budget_project_heading'); ?></h6>
									</div>
								  </div>
								</div>
							  </div>
							</div>
							<div class="col-sm-6">
							  <div class="form-group" style="margin:0;">
								<div class="checkbox-btn-inner">
								  <input  name="project_type" style="position: absolute;top: 0;width: 100%;" type="radio" class="post_project_type_input" value="hourly" id="project_type_hourly" disabled>
								  <div class="checkbox-inner-div">
									<label for="project_type_hourly">I'm a toggle</label>
									<div class="checkbox-content align-middle">
									  <h6><?php //echo $this->config->item('post_project_option_hourly_budget_project_heading'); ?></h6>
									</div>
								  </div>
								</div>
							  </div>
							</div>
						  </div>
						</div> -->
						<!-- end check box button -->

						<!-- What budget do you have in mind ? -->
						<div class="block-sectn select-budget project_show_block scope_budget"  id="project_budget">
						  <div class="row">
							<div class="col-md-12">
							  <h4 class="inner-block-heading default_black_bold_large"><span id="project_budget_section_heading">
								<?php
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
									if($project_data['project_type'] == 'fixed'){
									?>
									<select name="project_budget" id="fixed_budget">
										<!--<option value="">Select Budget</option>-->
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
									<?php
									}else if($project_data['project_type'] == 'hourly'){
									?>
									<select name="project_budget" id="hourly_rate_based_budget">
										<!--<option value="">Select Budget</option>-->
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
									<?php
									}else if($project_data['project_type'] == 'fulltime'){
									?>
									<select name="project_budget" id="fulltime_salary_range">
									<!--<option value="">Select Salary</option>-->
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
									<?php
									}
									?>
									<div class="error_div_sectn clearfix">
										<span id="project_budget_error" class="error_msg"></span>
									</div>

								</div>
							</div>
							<div class="col-sm-6"></div>
						  </div>
						</div>
						<!--end What budget do you have in mind ? -->

						<!-- Project name -->
						<div class="block-sectn file-project-name project_show_block projectName_section oBiddingEdit" >
							<div class="row">
								<div class="col-md-12">
								  <h4 class="inner-block-heading default_black_bold_large"><span id="project_title_section_heading">
									<?php
									if($project_data['project_type'] == 'fulltime'){ 
										echo $this->config->item('post_fulltime_position_name_section_heading');
									}else{
										echo $this->config->item('post_project_title_section_heading');
									}
									?></span></h4> 
								</div>
								<div class="col-md-12">
									<div class="form-group backC margin_bottom0"> 
										<?php
										$project_title = $project_data['project_title'];
										?>
										<input type="text" name="project_title" id="project_title" disabled   class="avoid_space default_input_field" maxlength="<?php echo $this->config->item('project_title_maximum_length_characters_limit_post_project'); ?>" value="<?php echo $project_title; ?>"> 
										<div class="error_div_sectn clearfix">
											<span id="project_title_error" class="error_msg"></span>
											
										</div>      
									</div>
								</div>
								<div class="col-sm-6"></div>
							</div>
						</div>
						<!-- end Project name -->
						<!-- Describe your project in detail: -->
						<div class="block-sectn project-describe project_show_block projectDescription_section">
						  <div class="row">
								<div class="col-md-12">
									<h4 class="inner-block-heading default_black_bold_large"><span id="project_description_section_heading">
										<?php
										if($project_data['project_type'] == 'fulltime'){ 
											echo $this->config->item('post_fulltime_position_description_section_heading');
										}else{
											echo $this->config->item('post_project_description_section_heading');
										}	
										?></span></h4> 
								</div>
								<div class="col-md-12">
								  <div class="form-group backC margin_bottom0"> 
									<?php
										$project_description = $project_data['project_description'];
									?>
									  <div class="bgEP">
											<div id="project_description" class="proCommon default_textarea_field"><?php echo nl2br(str_replace("  "," &nbsp;",htmlspecialchars($project_description, ENT_QUOTES))); ?></div>
										</div>
									  <script>
										$(document).ready(function (){
												if($("#project_description").height()>50) {
													$("#project_description").css({
														"overflow-y": "scroll",
														"height": "200"
													});
												}
												else
												{
													$("#project_description").css({
															"overflow-y": "none",
															"height": "50"
													});
												}
										})
										</script>
									<!--<textarea name="project_description" disabled id="project_description" class="avoid_space_textarea" maxlength="<?php echo $this->config->item('project_description_maximum_length_characters_limit_post_project'); ?>"><?php echo trim($project_description); ?></textarea>
									<div class="error_div_sectn clearfix">
									<span id="project_description_error" class="error_msg"></span>
									</div>-->
								  </div>
								</div>
								<div class="col-md-6"></div>
							  </div>
						</div>
						<!-- Describe your project in detail -->
						<!-- Describe additional information of project: -->
						<div class="block-sectn project-describe project_show_block noBorder_block_sectn projectDescription_section">
						  <div class="row">
								<div class="col-md-12">
									<h4 class="inner-block-heading default_black_bold_large"><span id="project_additional_information_section_heading"><?php
									if($project_data['project_type'] == 'fulltime'){ 
										echo $this->config->item('post_fulltime_position_additional_information_section_heading');
									}else{
										echo $this->config->item('post_project_additional_information_section_heading');
									}	
									?></span></h4> 
								</div>
								<div class="col-md-12">
								  <div class="form-group margin0"> 
									
									<?php
									if(!empty($project_additional_information_data)){
										foreach($project_additional_information_data as $additional_information_key=>$additional_information_value){
									?>
										<div class="bgEP">
											<div id="aid<?php echo $additional_information_key?>" class="proCommon default_textarea_field"><?php echo nl2br(str_replace("  "," &nbsp;",htmlspecialchars($additional_information_value['additional_information'], ENT_QUOTES))); ?></div>
										</div>
										
										<script>
										$(document).ready(function (){
											if($("#aid<?php echo $additional_information_key?>").height()>50) {
												$("#aid<?php echo $additional_information_key?>").css({
													"overflow-y": "scroll",
													"height": "200"
												});
											}
											else
											{
												$("#aid<?php echo $additional_information_key?>").css({
													"overflow-y": "none",
													"height": "50"
												});
											}
										})
										</script>
									<?php
										}
									}
									$project_additional_information = $project_data['additional_information'];
									if($this->config->item('project_additional_information_maximum_length_character_limit_post_project') - mb_strlen(trim($project_additional_information)) >= 0 ){
									$project_additional_information_remaining_characters = $this->config->item('project_additional_information_maximum_length_character_limit_post_project') - mb_strlen(trim($project_additional_information));
									}else{
										$project_additional_information_remaining_characters = 0;
									}
									?>
									<textarea name="project_additional_information" id="project_additional_information" class="avoid_space_textarea default_textarea_field" maxlength="<?php echo $this->config->item('project_additional_information_maximum_length_character_limit_post_project'); ?>"></textarea>
									<div class="error_div_sectn clearfix">
									<span id="project_additional_information_error" class="error_msg"></span>
									<span class="content-count project_additional_information_length_count_message"><?php echo $project_additional_information_remaining_characters."&nbsp;".$this->config->item('characters_remaining_message'); ?></span> 
									</div>
								  </div>
								</div>
								<div class="col-md-6"></div>
							  </div>
						</div>
						<!-- Describe additional information of project: -->

						<!-- Upload your document -->
						<div class="block-sectn upload-document project_show_block uploadDoc">
							<div class="row">
								<div class="col-md-12">
									<div class="upload-btn-wrapper pPfileInput_btn">
										<!--<button class="btn btn-default"><i class="fa fa-cloud-upload"></i> Upload a file</button>
										<input type="file" name="myfile" />-->
										<span class="btn blue_btn default_btn fileinput-button">
											<i class="fa fa-cloud-upload"></i><?php
											echo $this->config->item('post_project_page_upload_file_button_txt');
											?>
										</span><!-- </div> --><div class="upload_attachment_error errAttachment"></div><!-- </div><div class="col-md-9"> --><?php
										$show_attachment_table = "";
										if($count_project_attachments == 0 ){
											$show_attachment_table = "display:none;";
										}
										?><div id="project_attachment_container" class="attachment_inline" style="<?php echo $show_attachment_table; ?>"><!-- <tr>
												<th><?php
												//echo $this->config->item('post_project_page_project_attachment_table_heading_file_name');
												?></th>
												<th align="center"><?php
												//echo $this->config->item('post_project_page_project_attachment_table_heading_file_size');
												?></th>
												<th align="center"><?php
												//echo $this->config->item('post_project_page_project_attachment_table_heading_remove');
												?></th>
											</tr> --><?php
											if(!empty($project_attachment_array)){
												foreach($project_attachment_array as $project_attachment){
													//$attachment_id = Cryptor::doEncrypt($project_attachment['id']);
													$attachment_id = $project_attachment['project_attachment_name'];
											?><!-- <tr class="project_attachment_row" id="<?php //echo "project_attachment_row".$project_attachment['id']; ?>">
													<td ><a class="download_attachment" style="cursor:pointer;" data-attr="<?php //echo $attachment_id; ?>" ><?php //echo $project_attachment['project_attachment_name']; ?></a></td>
													<td align="center"><?php //echo $project_attachment['size']; ?></td>
													<td  align="center">
														<a style="cursor:pointer;" class="btn default_icon_red_btn project_attachment_row_delete uploadDel" id="<?php //echo $project_attachment['id'];?>"> <i class="fas fa-trash-alt"></i> </a>	  
													</td>
												</tr> --><div class="default_download_attachment project_attachment_row" id="<?php echo "project_attachment_row".$project_attachment['id']; ?>"><a class="download_attachment download_text " data-attr="<?php echo $attachment_id; ?>"><label><?php echo $project_attachment['project_attachment_name']; ?></label></a><label class="delete_icon"><a style="cursor:pointer;" class="project_attachment_row_delete" data-file-attr = "<?php echo $project_attachment['project_attachment_name']; ?>" id="<?php echo $project_attachment['id'];?>"><i class="fa fa-trash-o delete-btn" aria-hidden="true" ></i></a></label><div class="clearfix"></div></div>
											<?php
												}
											}	
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Upload your document -->

						<!-- tags -->
						<div class="block-sectn tags-sectn project_show_block">
							<div class="row">
								<div class="col-md-12">
									<div class="receive_notification" id="tagSL">
										<a class="rcv_notfy_btn" onclick="showMoreTags()" id="project_tag_heading_section"><?php
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
									</span></h4>
									</div> -->
									<div id="more_tags" class="<?php if(empty($project_tag_data)){
											echo "collapse"; }?> row">
										<div class="tagBottom">
											<input type="text" id="input_tags" name="" placeholder="" class="avoid_space default_input_field" maxlength="<?php echo $this->config->item('project_tag_maximum_length_characters_limit_post_project'); ?>" >
											<div class="error_div_sectn clearfix tagyError">
                                               <span class="error_save">
												<span id="tag_error" class="error_msg" style="display:none;"></span>
													<div class="saveTAg_responsive" id="save_tag_button_section_responsive" ><button type="button" class="btn default_btn blue_btn save_tag_button" disabled><?php echo $this->config->item('save_btn_txt'); ?></button></div>
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
													<li class="tag_name" id="<?php echo 'project_tag_'.$project_tag['id']."_".$project_tag['project_id'] ?>"> <span><small><?php echo $project_tag['project_tag_name']; ?></small><input type="hidden" name="project_tag[<?php echo $tag_counter; ?>][tag_name]" value="<?php echo $project_tag['project_tag_name']; ?>" /><i class="fa fa-times delete_project_tag_row_data" data-attr="<?php echo 'project_tag_'.$project_tag['id']."_".$project_tag['project_id'] ?>"></i> </span></li>
												
												<?php
														$tag_counter ++;
													}
												}	
												?>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- end tags -->

						<!-- Where do you want this done? -->
						<div class="block-sectn county-details project_show_block" id="location_block">
							<div class="row">
								<div class="col-md-12">
									<div class="receive_notification" id="locationSL">					
										<a id="location_heading" class="rcv_notfy_btn location_option chk-btn" onclick="showMorePow()"><?php
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
										?></a>
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
														  <?php
															/*if($project_data['project_type'] == 'fulltime'){ 
																echo $this->config->item('post_fulltime_project_page_location_heading');
															}else{
																echo $this->config->item('post_project_page_location_heading');
															}*/
															?>
														</div>
													</div>
												</div>
											</div>
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
						<div class="payment-methods block-sectn project_show_block">
						  <div class="row">
							<div class="col-md-12">
								<div class="receive_notification" id="pmethodSL">					
									<a class="rcv_notfy_btn inner-block-heading default_black_bold_large" id="project_payment_method_section_heading" onclick="showMorePmethod()"><?php
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
							  <!-- <h4 class="inner-block-heading default_black_bold_large"><span id="project_payment_method_section_heading">
								<?php
								/*if($project_data['project_type'] == 'fulltime'){ 
									echo $this->config->item('post_project_project_optional_upgrades_section_heading');
								}else{
									echo $this->config->item('post_project_payment_method_section_heading');
								}*/	
								?>
								</span></h4>
								</div>
								<div class="col-md-12"> -->
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
							</div>
						  </div>
						</div>
						<!-- end Payment Methods -->
						<?php
						if($project_upgrade_type_box_show_status){
						?>
						<!-- Get most from your project! (optional) -->
							<div class="most-project block-sectn-without-blue-tooltip block-sectn project_show_block">
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
									<?php
									if($project_data['featured'] == 'N' && $featured_max < time() ){
									
									?>
									  <div class="form-group">
										<div class="checkbox-btn-inner">
											<input id="most-project1" style="position: absolute;top: 0;width: 100%;" type="checkbox" class="upgrade_type_featured upgrade_type" name="upgrade_type_featured" value="Y">
											<div class="checkbox-inner-div">
												<label for="most-project1">
													<div class="row">
														<div class="checkbox-title"> <span class="upgrade_feature"><?php
														echo $this->config->item('post_project_page_upgrade_type_featured'); ?></span></div>
														<div class="pay-sectn"><span><span id="upgrade_type_featured_amount" data-attr="<?php echo str_replace(" ","",$this->config->item('project_upgrade_price_featured')); ?>"><?php echo number_format($this->config->item('project_upgrade_price_featured'), 0, '', ' '); ?></span> <?php echo CURRENCY; ?></span></div>  
													</div>
													<div class="checkbox-content">
														<p class="upgrade_badge_description badge_bigger_view"><?php echo $project_data['project_type']== 'fulltime' ? str_replace(array('{project_featured_upgrade_availability}'),array($featured_availabity_days),$this->config->item('post_fulltime_project_page_project_upgrade_description_featured')) : str_replace(array('{project_featured_upgrade_availability}'),array($featured_availabity_days),$this->config->item('post_project_page_project_upgrade_description_featured'));?></p>
                                                         <p class="upgrade_badge_description badge_smaller_view"><?php echo $project_data['project_type']== 'fulltime' ? str_replace(array('{project_featured_upgrade_availability}'),array($featured_availabity_days),$this->config->item('post_fulltime_project_page_project_upgrade_description_featured_small_resolution_view')) : str_replace(array('{project_featured_upgrade_availability}'),array($featured_availabity_days),$this->config->item('post_project_page_project_upgrade_description_featured_small_resolution_view'));?></p>
													</div>
												</label>
											</div>
										  </div>
									  </div>
									<?php
									}
									if($project_data['urgent'] == 'N' && $urgent_max < time() ){
									
									?>
									<div class="form-group">
										<div class="checkbox-btn-inner">
											<input id="most-project2"  class="upgrade_type_urgent upgrade_type" style="position: absolute;top: 0;width: 100%;" type="checkbox" name="upgrade_type_urgent"  value="Y">
											<div class="checkbox-inner-div">
												<label for="most-project2">
													<div class="row">
														<div class="checkbox-title"> <span class="upgrade_urgent"><?php
														echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></span></div>
														<div class="pay-sectn"> <span><span id="upgrade_type_urgent_amount" data-attr="<?php echo str_replace(" ","",$this->config->item('project_upgrade_price_urgent')); ?>"><?php echo number_format($this->config->item('project_upgrade_price_urgent'), 0, '', ' '); ?></span> <?php echo CURRENCY; ?></span></div>
													</div>
													<div class="checkbox-content">
														<p class="upgrade_badge_description badge_bigger_view"><?php echo $project_data['project_type']== 'fulltime' ? str_replace(array('{project_urgent_upgrade_availability}'),array($urgent_availabity_days),$this->config->item('post_fulltime_project_page_project_upgrade_description_urgent')) : str_replace(array('{project_urgent_upgrade_availability}'),array($urgent_availabity_days),$this->config->item('post_project_page_project_upgrade_description_urgent'));?></p>
                                                        <p class="upgrade_badge_description badge_smaller_view"><?php echo $project_data['project_type']== 'fulltime' ? str_replace(array('{project_urgent_upgrade_availability}'),array($urgent_availabity_days),$this->config->item('post_fulltime_project_page_project_upgrade_description_urgent_small_resolution_view')) : str_replace(array('{project_urgent_upgrade_availability}'),array($urgent_availabity_days),$this->config->item('post_project_page_project_upgrade_description_urgent_small_resolution_view'));?></p>
													</div>
												</label>
											</div>
										</div>
									</div>
									<?php
									}
									?>
									<div class="total-price" style="display:none;">
										<p><?php echo $this->config->item('post_project_page_upgrade_total_txt'); ?>   <span id="total_upgrade_amount" data-attr="0">0</span>  <?php echo CURRENCY; ?></p>
									</div>
									<div id="upgrade_message"></div>
										
								</div>
							  </div>
							</div>
						<!-- Get most from your project! (optional) -->
						<?php
						}
						?>

						<!-- project relative buttons -->
						<div class="project-relative-btn block-sectn project_show_block editProject_btn_section">
						  <div class="row">
							<div class="col-md-12 text-center pPBtn_gap editProject_btn">
								<button type="button" class="btn default_btn red_btn" id="cancel_project"><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('post_fulltime_project_page_cancel_project_button_txt') : $this->config->item('post_project_page_cancel_project_button_txt');?></button>
								<button type="button" class="btn blue_btn default_btn" id="update_project"><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('edit_fulltime_project_page_update_project_button_txt') : $this->config->item('edit_project_page_update_project_button_txt');?></button>
							</div>
						  </div>
						</div>
					</div>
						
					 <?php echo form_close(); ?>  
				</div><!-- col md 8 -->
				<!-- <div class="col-md-4 hidden-mobile" style="opacity: inherit;z-index: -1;">
					<div class="right-sectn-bar">
					</div>
				</div> -->
			</div><!-- row -->
		</div><!-- container fluid -->
	</div>
 </main>
<script type="text/javascript">
var post_project_project_tags_section_heading = "<?php echo $this->config->item('post_project_project_tags_section_heading'); ?>";
var post_fulltime_position_tags_section_heading = "<?php echo $this->config->item('post_fulltime_position_tags_section_heading'); ?>";
var post_project_payment_method_section_heading = "<?php echo $this->config->item('post_project_payment_method_section_heading'); ?>";
var post_fulltime_position_payment_method_section_heading = "<?php echo $this->config->item('post_fulltime_position_payment_method_section_heading'); ?>";

var post_project_page_location_heading = "<?php echo $this->config->item('post_project_page_location_heading'); ?>";
var post_fulltime_project_page_location_heading = "<?php echo $this->config->item('post_fulltime_project_page_location_heading'); ?>";

var custom_project_attachment_allowed_file_extensions = [<?php echo $this->config->item('custom_project_attachment_allowed_file_extensions'); ?>];

var plugin_project_attachment_allowed_file_extensions = '<?php echo $this->config->item('plugin_project_attachment_allowed_file_extensions'); ?>';	

var project_characters_remaining_message = "<?php echo $this->config->item('characters_remaining_message'); ?>";

var project_additional_information_maximum_length_character_limit_post_project = "<?php echo $this->config->item('project_additional_information_maximum_length_character_limit_post_project'); ?>";	
var project_additional_information_minimum_length_character_limit_post_project = "<?php echo $this->config->item('project_additional_information_minimum_length_character_limit_post_project'); ?>";

var project_additional_information_minimum_length_words_limit_post_project = "<?php echo $this->config->item('project_additional_information_minimum_length_words_limit_post_project'); ?>";

var project_tag_maximum_length_characters_limit_post_project = "<?php echo $this->config->item('project_tag_maximum_length_characters_limit_post_project'); ?>";	
var project_tag_minimum_length_characters_limit_post_project = "<?php echo $this->config->item('project_tag_minimum_length_characters_limit_post_project'); ?>";	
var project_tag_characters_minimum_length_validation_post_project_message = "<?php echo $this->config->item('project_tag_characters_minimum_length_validation_post_project_message'); ?>";	
var number_tag_allowed_post_project = "<?php echo $this->config->item('number_tag_allowed_post_project'); ?>";	
var number_project_category_post_project = "<?php echo $this->config->item('number_project_category_post_project'); ?>";	
var project_attachment_maximum_size_limit = "<?php echo $this->config->item('project_attachment_maximum_size_limit'); ?>";
project_attachment_maximum_size_limit = project_attachment_maximum_size_limit * 1048576;
var project_attachment_maximum_size_validation_post_project_message = "<?php echo $this->config->item('project_attachment_maximum_size_validation_post_project_message'); ?>";	
var project_attachment_allowed_files_validation_post_project_message = "<?php echo $this->config->item('project_attachment_allowed_files_validation_post_project_message'); ?>";	


var project_attachment_invalid_file_extension_validation_post_project_message = "<?php echo $this->config->item('project_attachment_invalid_file_extension_validation_post_project_message'); ?>";
var select_locality = "<?php echo $this->config->item('post_project_page_locality_drop_down_option_select_locality'); ?>";
var select_postal_code = "<?php echo $this->config->item('post_project_page_postal_code_drop_down_option_select_postal_code'); ?>";
var select_sub_category = "<?php echo $this->config->item('post_project_page_sub_category_drop_down_option_select_sub_category'); ?>";
var add_another_category = "<?php echo $this->config->item('post_project_page_add_another_category_button_txt'); ?>";
var delete_text = "<?php echo $this->config->item('post_project_page_delete_button_txt'); ?>";
// config vaiables for section tooltip messages start //
var popup_alert_heading = "<?php echo $this->config->item('popup_alert_heading'); ?>";

var maximum_allowed_number_of_attachments_on_projects = "<?php echo $this->config->item('maximum_allowed_number_of_attachments_on_projects'); ?>";
var dashboard_page_url = "<?php echo $this->config->item('dashboard_page_url'); ?>";
var project_detail_page_url = "<?php echo $this->config->item('project_detail_page_url');?>";

var category_options = "";
var	project_id = '<?php echo $project_id ?>';
var project_status = "<?php echo 'open_for_bidding'; ?>";
var page_type = "edit_project";
var show_project_upgrade_amount_staus = false;
$("#hourly_rate_based_budget").attr('disabled','disabled');	
var post_project_page_location_heading = "<?php echo $this->config->item('post_project_page_location_heading'); ?>";
var post_project_payment_method_section_heading = "<?php echo $this->config->item('post_project_payment_method_section_heading'); ?>";

	
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
<?php
	}else{
?>
		
		$("#post_fulltime_position").prop("checked", false);
<?php
		if($project_data['project_type'] == 'fixed'){
?>	
		$("#project_type_fixed").prop("checked", true);
		$("#post_project").prop("checked", true);			
<?php
		}else if($project_data['project_type'] == 'hourly'){
?>
		$("#project_type_hourly").prop("checked", true);
		$("#post_project").prop("checked", true);
		$("#hourly_rate_based_budget").removeAttr('disabled');	
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
		//$("#project_county_id").hide();
		//$("#project_locality_id").hide();
		//$("#project_postal_code_id").hide();
		$(".location_section").hide();
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
	}
		
	?>
	
	<?php
	
	//if( $count_project_categories >= $this->config->item('number_project_category_post_project')){
	?>
		//$("#add_more_project_category").hide(); // check number of category block if there are more less then 5 it will show the add more category option
		//$("#add_more_project_category").attr('disabled','disabled');// 
	<?php
	//}
	if( $count_project_attachments >= $this->config->item('maximum_allowed_number_of_attachments_on_projects')){
	?>
	//$(".upload-btn-wrapper").hide();
	$(".fileinput-button").hide();
	<?php
	}if($count_project_tags >= $this->config->item('number_tag_allowed_post_project')){
	?>
		$("#input_tags").hide();
		$("#input_tags").next().hide();
		$("#save_tag_button_section,#save_tag_button_section_responsive").css('display','none');
		$("#save_tag_button_section .btn, #save_tag_button_section_responsive .btn").prop('disabled',true);
		
	<?php
	}
	if($count_project_postal_codes  == '1'){
	?>
		//$('.county-details #project_postal_code_id option[value=""]').hide();	
	<?php
	}
	if(!empty($project_data['postal_code_id'])){
	?>
		//$('#project_postal_code_id option[value=""]').hide();
	<?php
	}
	if($project_data['postal_code_id'] == 0){
	?>
	$('#project_postal_code_id option[value=""]').css('display','none');
	<?php	
	}	
	if(!empty($project_data['county_id'])){
	?>
	//$('#project_county_id option[value=""]').hide();
	<?php
	}
	if($project_data['county_id'] == 0){
	?>
	$('#project_county_id option[value=""]').css('display','none');
	<?php	
	}	
	if($project_data['locality_id']){
	?>
	//$('#project_locality_id option[value=""]').hide();
	<?php
	}
	if($project_data['locality_id'] == 0){
	?>
	$('#project_locality_id option[value=""]').css('display','none');
	<?php	
	}	
	?>
		// project availability settings
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
?>
var upload_blank_attachment_alert_message = "<?php echo $this->config->item('upload_blank_attachment_alert_message'); ?>";	
standard_time_valid = "<?php echo !empty($standard_valid_time_arr) ? true : false; ?>";
featured_time_valid = "<?php echo !empty($featured_valid_time_arr) ? true : false; ?>";
urgent_time_valid = "<?php echo !empty($urgent_valid_time_arr) ? true : false; ?>";
sealed_time_valid = "<?php echo !empty($sealed_valid_time_arr) ? true : false; ?>";
hidden_time_valid = "<?php echo !empty($hidden_valid_time_arr) ? true : false; ?>";
upgrades = "<?php echo count($upgrades); ?>";
project_type = "<?php echo $project_data['project_type']; ?>";
</script>
<script src="<?=ASSETS?>js/dropzone.js"></script>
<script src="<?php echo JS; ?>modules/edit_project.js"></script>



