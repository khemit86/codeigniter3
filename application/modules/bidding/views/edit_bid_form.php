<!---- bid update form start ---->
<?php	
$project_delivery_period = '';
$amount_disabled = '';
$project_delivery_period_disabled = '';
/* if($project_data['project_type'] == 'fulltime'){ */
if(isset($project_data['fulltime_project_id'])){
$project_id = $project_data['fulltime_project_id'];
}else{
	$project_id = $project_data['project_id'];
}
/* }else{
	$project_id = $project_data['project_id'];
}
 */

if($project_data['project_type'] == 'fixed'){
	$bid_amount = str_replace(".00","",number_format(floatval($bid_data['initial_bidded_amount']),  2, '.', ' '));
	$bid_description = $bid_data['bid_description'];
	$project_delivery_period =  str_replace(".00","",number_format($bid_data['project_delivery_period'],  2, '.', ' '));
	
	
}else if($project_data['project_type'] == 'hourly'){
	$bid_amount =  str_replace(".00","",number_format(floatval($bid_data['initial_bidded_hourly_rate']),  2, '.', ' '));
	$bid_description = $bid_data['bid_description'];
	$project_delivery_period =  str_replace(".00","",number_format($bid_data['project_delivery_hours'],  2, '.', ' '));
	
	 	
}else if($project_data['project_type'] == 'fulltime'){
	$bid_amount = str_replace(".00","",number_format(floatval($bid_data['initial_requested_salary']),  2, '.', ' '));
	$bid_description = $bid_data['application_description'];	
}
if($bid_data['bidding_dropdown_option'] != 'NA'){
	$project_delivery_period = '';
	$bid_amount = '';
	$amount_disabled = "disabled";
	$project_delivery_period_disabled = "disabled";
}
$name = $login_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $login_user_detail['first_name'] . ' ' . $login_user_detail['last_name'] : $login_user_detail['company_name'];
?>
<?php
/* <div class="default_user_name usernameBdr">
    <a class="default_user_name_link" href="<?php echo site_url ($login_user_detail['profile_name']); ?>"><?php echo $name; ?></a>
</div> */
?>
<div class="proDn">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-12 padding0">
			<?php
			$attributes = [
				 'id' => 'update_bid_form',
				 'class' => 'bid_form',
				 'role' => 'form',
				 'name' => 'update_bid_form',
				 'enctype' => 'multipart/form-data',
			 ];
			 //echo form_open('', $attributes);
			?>
			<form name="update_bid_form" enctype="multipart/form-data" id="update_bid_form" onsubmit="return false;" class="bid_form">
			<input type="hidden" id = "bid_section_id" value="<?php echo $bid_data['id']; ?>" name="section_id"/>
			<input type="hidden" id = "project_type" value="<?php echo $project_data['project_type']; ?>" name="project_type"/>
			<div class="bidDesc bidNomargin">
				<?php
				$tooltip_message_delivery_in = '';
				
				if($project_data['project_type'] == 'fulltime'){
				
					$bid_text = $this->config->item('project_details_page_fulltime_project_bid_form_bid');
					$tooltip_message_bid_amount = $this->config->item('project_details_page_fulltime_project_bid_form_tooltip_message_bid_amount');
					
				}else if($project_data['project_type'] == 'hourly'){
				
					$bid_text = $this->config->item('project_details_page_hourly_project_bid_form_bid');
					$tooltip_message_bid_amount = $this->config->item('project_details_page_hourly_project_bid_form_tooltip_message_sp_hourly_rate_bidded_amount');
					$tooltip_message_delivery_in = $this->config->item('project_details_page_hourly_project_bid_form_tooltip_message_sp_bidded_number_of_hours');
					
					
					
				}else if($project_data['project_type'] == 'fixed'){
					$bid_text = $this->config->item('project_details_page_fixed_budget_project_bid_form_bid');
					$tooltip_message_bid_amount = $this->config->item('project_details_page_fixed_budget_project_bid_form_tooltip_message_bid_amount');
					$tooltip_message_delivery_in = $this->config->item('project_details_page_fixed_budget_project_bid_form_tooltip_message_delivery_in');
				}
				?>
				 
				<div class="bidDays <?php echo $project_data['project_type'].'_bid_form_row'; ?>">
					<label>
						<div class="form-group">
						<?php
							if($project_data['project_type'] != 'fulltime'){ 
							?>
								<div class="Delivery_In">
									<b class="default_black_bold"><?php 
									if($project_data['project_type'] == 'fixed') {
										echo $this->config->item('project_details_page_project_bid_form_delivery_in'); 
									} else if($project_data['project_type'] == 'hourly') {
										echo $this->config->item('project_details_page_hourly_project_bid_form_number_of_hours');
									}
									if($project_data['project_type'] == 'fixed') {
										$delivery_period_maxlength = $this->config->item('project_details_page_fixed_budget_project_delivery_period_input_field_length_bid_form');
									} else if($project_data['project_type'] == 'hourly') {
										$delivery_period_maxlength = $this->config->item('project_details_page_project_delivery_hours_input_field_length_bid_form');
									}
								?></b><i class="fa fa-question-circle tooltipAuto default_icon_help" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $tooltip_message_delivery_in; ?>"></i><span class="touch_line_break"><input type="text" class="form-control avoid_space login_register_input_field" <?php echo $project_delivery_period_disabled;?> id="delivery_period" name="delivery_period" maxlength="<?php echo $delivery_period_maxlength; ?>" value="<?php echo $project_delivery_period; ?>" style="text-align:right;"><?php
									if($project_data['project_type'] == 'fixed'){ 
										echo '<b class="default_black_bold">'.$this->config->item('project_details_page_fixed_budget_project_bid_form_days').'</b>';
									}/*else if($project_data['project_type'] == 'hourly'){
										echo '<b class="default_black_bold">'.$this->config->item('project_details_page_hourly_project_bid_form_hours').'</b>';
									}*/ ?></span>
									<div class="error_div_sectn clearfix">
										<span id="delivery_period_error" class="error_msg"></span>
									</div>
								</div>
							<?php
							}
							if($project_data['project_type'] == 'fixed') {
								$amount_maxlength = $this->config->item('project_details_page_fixed_budget_project_bid_amount_input_field_length_bid_form');
							} else if($project_data['project_type'] == 'hourly') {
								$amount_maxlength = $this->config->item('project_details_page_bidded_hourly_rate_amount_input_field_length_bid_form');
							} else if($project_data['project_type'] == 'fulltime') {
								$amount_maxlength = $this->config->item('project_details_page_fulltime_project_salary_amount_input_field_length_bid_form');
							}
							?>
							<div class="Bid_Amount">
								<b class="default_black_bold"><?php echo $bid_text; ?></b><i class="fa fa-question-circle tooltipAuto default_icon_help" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $tooltip_message_bid_amount; ?>"></i><span class="touch_line_break"><input type="text" class="form-control avoid_space login_register_input_field" <?php echo $amount_disabled;?> id="bid_amount" name="bid_amount" maxlength="<?php echo $amount_maxlength; ?>" value="<?php echo $bid_amount; ?>" style="text-align:right;"><b class="default_black_bold"><?php
								if($project_data['project_type'] == 'fixed'){
									echo CURRENCY;
								}else if($project_data['project_type'] == 'hourly'){
									echo CURRENCY.$this->config->item('post_project_budget_per_hour');
								}else if($project_data['project_type'] == 'fulltime'){
									echo CURRENCY.$this->config->item('post_project_budget_per_month');
								}
								?></b></span>
								<div class="error_div_sectn clearfix">
								<span id="bid_amount_error" class="error_msg"></span>
								</div>
							</div>
							
						</div>
					</label><label class="orBid"><div><small><?php echo $this->config->item('or'); ?></small></div></label><label>
						<div class="form-group default_dropdown_select">
							<?php 
								$bidding_dropdown_selected_option = '';
								$bidding_dropdown_disabled_option = 'disabled';
								$bidding_checbox_option = '';
								if($bid_data['bidding_dropdown_option'] != 'NA'){
									$bidding_checbox_option = "checked";
									$bidding_dropdown_disabled_option = '';
								}
							?><div class="default_checkbox boxPosition">
							<input type="checkbox" name="biding_form_checkbox" id="biding_form_checkbox" value="1" class="CheckAllClass" <?php echo $bidding_checbox_option; ?>>
							<span class="checkmark"></span>
							<!-- <input type="checkbox" value="1" class="form-control" name="biding_form_checkbox" id="biding_form_checkbox" <?php //echo $bidding_checbox_option; ?>> -->
							<select name="biding_form_drop_down_options" id="biding_form_drop_down_options" <?php echo $bidding_dropdown_disabled_option ?>>
								<?php
								  $biding_form_drop_down_options = $this->config->item('project_details_page_biding_form_drop_down_options');
								  foreach($biding_form_drop_down_options as $key=>$value){
									$bidding_dropdown_selected_option = '';
									if($bid_data['bidding_dropdown_option'] != 'NA' && $key == $bid_data['bidding_dropdown_option']){
										$bidding_dropdown_selected_option = "selected";
									}
									echo '<option value="'.$key.'" '.$bidding_dropdown_selected_option.'>'.$value.'</option>';
									}
								?>
							</select></div>
						</div></label>
					
				</div>
				<div class="bidTArea">
					<?php
					if($project_data['project_type'] == 'fulltime'){ 
						echo '<b class="default_black_bold">'.$this->config->item('project_details_page_fulltime_bid_form_description_section_heading').'</b>';
					}else{
						echo '<b class="default_black_bold">'.$this->config->item('project_details_page_bid_form_description_section_heading').'</b>';
					}
					?>
					<div class="form-group">
					
						<textarea class="avoid_space_textarea default_textarea_field" id="bid_description" name="bid_description" maxlength="<?php echo $this->config->item('project_details_page_bid_description_maximum_length_character_limit'); ?>"><?php echo $bid_description; ?></textarea>
						
					<div class="row margin_top5 edit_bid_row_adjust editBidBtn editRowBid">
						<div class="col-md-5 col-sm-5 col-12 uploadBtn_editBid upldEditBidBtn">
						
						<?php
						
					
						if($this->config->item('project_details_page_bid_description_maximum_length_character_limit') - mb_strlen(trim($bid_description)) >= 0){
							$bid_description_remaining_characters = $this->config->item('project_details_page_bid_description_maximum_length_character_limit') - mb_strlen(trim($bid_description));
							}else{
						
							$bid_description_remaining_characters = 0;
						}
						?>
						<div class="error_div_sectn clearfix default_error_div_sectn">
							<div id="bid_description_length_count_message_mobile" class="content-count editBid_contentCount"><?php echo $this->config->item('project_details_page_bid_description_maximum_length_character_limit')."&nbsp;".$this->config->item('characters_remaining_message'); ?></div>
							<span id="bid_description_error" class="error_msg editBid_errorMsg"></span>
							<span id="bid_attachment_container" class="editBid_attachment">
							<!-- <div class="col-md-12 col-sm-12 col-12 attachment_editBid" id="bid_attachment_container"> -->
								<?php
								if(!empty($bid_attachment_data)){
									foreach($bid_attachment_data as $bid_attachment_key=>$bid_attachment_value){
										echo '<div class="default_download_attachment upValue bid_attachment_row" id="bid_attachment_row_'.$bid_attachment_value['id'].'"><a  data-section-id = "'.$bid_data['id'].'" class="download_attachment  download_text download_bid_form_attachment" style="cursor:pointer;" data-action-type="update_bid"  data-attr="'.$bid_attachment_value['bid_attachment_encrypt_id'].'" ><label>'.$bid_attachment_value['bid_attachment_name'].'</label></a><label class="delete_icon"><a style="cursor:pointer" class="bid_attachment_row_delete" data-action-type="update_bid" data-file-attr = "'.$bid_attachment_value['bid_attachment_name'].'" id="'.$bid_attachment_value['id'].'"><i class="fa fa-trash-o delete-btn" aria-hidden="true" ></i></a></label><div class="clearfix"></div></div>';
									}
								}
								?>
							<!-- </div> -->
							</span>
						</div>
						
						
						
						
							<?php
							$show_bid_attachment_upload_button_style= "display:none;";	
							if(($login_user_detail['current_membership_plan_id'] == 1 && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0 &&  $no_bid_attachment_uploaded_user < $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid')) || ($login_user_detail['current_membership_plan_id'] == 4 && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0 &&  $no_bid_attachment_uploaded_user < $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid'))){
								$show_bid_attachment_upload_button_style= "display:block;";
							}
							?>
							<div class="upBtn upload-document-update-bid drop_zone"  style="<?php echo $show_bid_attachment_upload_button_style; ?>">
								 <div id="overlay" class="d-none">
									<div class="text"><?php echo $this->config->item('drop_zone_drag_and_drop_files_area_message_txt') ?></div>
								</div>
								<label for="files" class="btn default_btn blue_btn fileinput-button fileinput-button-update-bid">
									<i class="fa fa-cloud-upload" aria-hidden="true"></i><?php echo $this->config->item('project_details_page_bid_form_upload_file_button_txt'); ?>
								</label>
								<input type="file" class="bid_imgupload" accept="<?php echo $this->config->item('project_details_page_bid_attachment_allowed_file_types'); ?>" style="display:none"/>
								<!--<input id="files" style="display: none;" type="file">-->
							</div>
						</div>
						<div class="col-md-7 col-sm-7 col-12 canPB editPlaceBtn">
							<div id="bid_description_length_count_message" class="content-count editBid_contentCount"><?php echo $this->config->item('project_details_page_bid_description_maximum_length_character_limit')."&nbsp;".$this->config->item('characters_remaining_message'); ?></div>
							<?php
							if($project_data['project_type'] == 'fulltime'){
								
								$bid_place_button_text = $this->config->item('project_details_page_fulltime_project_bid_form_edit_application_btn_txt');
								$bid_cancel_button_text = $this->config->item('project_details_page_fulltime_project_bid_form_cancel_application_btn_txt');
							}else{
								$bid_place_button_text = $this->config->item('project_details_page_project_bid_form_edit_bid_btn_txt');
								$bid_cancel_button_text = $this->config->item('project_details_page_project_bid_form_cancel_btn_txt');
							}
							?>
							<input type = "hidden" name="project_id" value="<?php echo $project_id; ?>" />
							<button type="button" data-attr = "<?php echo $bid_data['id']; ?>" class="btn default_btn red_btn cancelUpdateApply"><?php echo $bid_cancel_button_text; ?></button>
							<button type="button" id="update_bid" class="btn default_btn green_btn"><?php echo $bid_place_button_text; ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
						</div>
						
					</div>
						
						
						
						
						
						
						
						
						
						
					</div>
					
					<div class="clearfix"></div>
					<?php
					/* if(($login_user_detail['current_membership_plan_id'] == 1 && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0) || ($login_user_detail['current_membership_plan_id'] == 4 && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0)){ */
					?>
					<!-- <div class="row margin_top5 edit_bid_row_adjust editBidBtn">
						<div class="col-md-5 col-sm-5 col-12 uploadBtn_editBid">
							<?php
							$show_bid_attachment_upload_button_style= "display:none;";	
							if(($login_user_detail['current_membership_plan_id'] == 1 && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0 &&  $no_bid_attachment_uploaded_user < $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid')) || ($login_user_detail['current_membership_plan_id'] == 4 && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0 &&  $no_bid_attachment_uploaded_user < $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid'))){
								$show_bid_attachment_upload_button_style= "display:block;";
							}
							?>
							<div class="upBtn upload-document-update-bid drop_zone"  style="<?php echo $show_bid_attachment_upload_button_style; ?>">
								 <div id="overlay" class="d-none">
									<div class="text"><?php echo $this->config->item('drop_zone_drag_and_drop_files_area_message_txt') ?></div>
								</div>
								<label for="files" class="btn default_btn blue_btn fileinput-button fileinput-button-update-bid">
									<i class="fa fa-cloud-upload" aria-hidden="true"></i><?php echo $this->config->item('project_details_page_bid_form_upload_file_button_txt'); ?>
								</label>
								<input type="file" class="bid_imgupload" accept="<?php echo $this->config->item('project_details_page_bid_attachment_allowed_file_types'); ?>" style="display:none"/>
							</div>
						</div>
						<div class="col-md-7 col-sm-7 col-12 canPB editPlaceBtn">
							<?php
							if($project_data['project_type'] == 'fulltime'){
								
								$bid_place_button_text = $this->config->item('project_details_page_fulltime_project_bid_form_edit_application_btn_txt');
								$bid_cancel_button_text = $this->config->item('project_details_page_fulltime_project_bid_form_cancel_application_btn_txt');
							}else{
								$bid_place_button_text = $this->config->item('project_details_page_project_bid_form_edit_bid_btn_txt');
								$bid_cancel_button_text = $this->config->item('project_details_page_project_bid_form_cancel_btn_txt');
							}
							?>
							<input type = "hidden" name="project_id" value="<?php echo $project_id; ?>" />
							<button type="button" data-attr = "<?php echo $bid_data['id']; ?>" class="btn default_btn red_btn cancelUpdateApply"><?php echo $bid_cancel_button_text; ?></button>
							<button type="button" id="update_bid" class="btn default_btn green_btn"><?php echo $bid_place_button_text; ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
						</div>
						
					</div> -->
					<?php
					//}
					?>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
