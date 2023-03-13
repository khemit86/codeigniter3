<div class="row" id="onCApNw">
	<div class="col-md-12 col-sm-12 col-12">
		<?php
		$attributes = [
			 'id' => 'post_bid_form',
			 'class' => 'bid_form',
			 'role' => 'form',
			 'name' => 'post_bid_form',
			 'enctype' => 'multipart/form-data',
		 ];
		//  echo form_open('', $attributes);
		?>
		<form name="post_bid_form" enctype="multipart/form-data" id="post_bid_form" onsubmit="return false;" class="bid_form">
		<div class="bidDesc">
			<?php /* <h3><?php
			if($project_data['project_type'] == 'fulltime'){
				echo $this->config->item('project_details_page_fulltime_bid_form_heading');
			}else{
				echo $this->config->item('project_details_page_project_bid_form_heading');
			}
			?></h3> */
			?>
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
						?><div class="Delivery_In">
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
								?></b><i class="fa fa-question-circle default_icon_help tooltipAuto" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $tooltip_message_delivery_in; ?>"></i><span class="touch_line_break"><input type="text" class="form-control avoid_space login_register_input_field" id="delivery_period" name="delivery_period" maxlength="<?php echo $delivery_period_maxlength; ?>" style="text-align:right;"><?php
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
						?>
						<div class="Bid_Amount bidAmoutnOnly">
							<?php 
								if($project_data['project_type'] == 'fixed') {
									$amount_maxlength = $this->config->item('project_details_page_fixed_budget_project_bid_amount_input_field_length_bid_form');
								} else if($project_data['project_type'] == 'hourly') {
									$amount_maxlength = $this->config->item('project_details_page_bidded_hourly_rate_amount_input_field_length_bid_form');
								} else if($project_data['project_type'] == 'fulltime') {
									$amount_maxlength = $this->config->item('project_details_page_fulltime_project_salary_amount_input_field_length_bid_form');
								}
							?>
							<b class="default_black_bold"><?php echo $bid_text; ?></b><i class="fa fa-question-circle default_icon_help tooltipAuto" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $tooltip_message_bid_amount; ?>"></i><span class="touch_line_break"><input type="text" class="form-control avoid_space login_register_input_field" id="bid_amount" name="bid_amount" maxlength="<?php echo $amount_maxlength; ?>" style="text-align:right;"><b class="default_black_bold"><?php
							if($project_data['project_type'] == 'fixed'){
								echo CURRENCY;
							}else if($project_data['project_type'] == 'hourly'){
								echo CURRENCY.$this->config->item('post_project_budget_per_hour');
							}else if($project_data['project_type'] == 'fulltime'){
								echo CURRENCY.$this->config->item('post_project_budget_per_month');
							}
							?></b></span><div class="error_div_sectn clearfix">
							<span id="bid_amount_error" class="error_msg"></span>
							</div>
						</div>
						
					</div>
				</label><label class="orBid"><div><small><?php echo $this->config->item('or'); ?></small></div></label><label>
					<div class="form-group default_dropdown_select"><div class="default_checkbox boxPosition">
						<input type="checkbox" name="biding_form_checkbox" id="biding_form_checkbox" value="1" class="CheckAllClass">
						<span class="checkmark"></span><!-- <input type="checkbox" value="1" class="form-control" name="biding_form_checkbox" id="biding_form_checkbox"> --><!-- <select name="biding_form_drop_down_options" id="biding_form_drop_down_options" disabled>
						  <?php
						  //$biding_form_drop_down_options = $this->config->item('project_details_page_biding_form_drop_down_options');
						  /*foreach($biding_form_drop_down_options as $key=>$value){
							echo '<option value="'.$key.'">'.$value.'</option>';
						  }*/
						  ?>
						</select> -->
						<select name="biding_form_drop_down_options" id="biding_form_drop_down_options" disabled>
							<?php
						  $biding_form_drop_down_options = $this->config->item('project_details_page_biding_form_drop_down_options');
						  foreach($biding_form_drop_down_options as $key=>$value){
							echo '<option value="'.$key.'">'.$value.'</option>';
						  }
						  ?>
						</select></div>
					</div>
				</label>
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
				
					<textarea class="avoid_space_textarea default_textarea_field"  id="bid_description" name="bid_description" maxlength="<?php echo $this->config->item('project_details_page_bid_description_maximum_length_character_limit'); ?>"></textarea>
					
					<div class="row margin_top5 edit_bid_row_adjust">
					<div class="col-md-5 col-sm-5 col-12 uploadBtn_editBid upldPlaceBidBtn">
					<div class="error_div_sectn clearfix">
						<div id="bid_description_length_count_message_mobile" class="content-count editBid_contentCount"><?php echo $this->config->item('project_details_page_bid_description_maximum_length_character_limit')."&nbsp;".$this->config->item('characters_remaining_message'); ?></div>
					
					
						<span id="bid_description_error" class="error_msg editBid_errorMsg"></span>
						<span id="bid_attachment_container" class="editBid_attachment">
						<?php
						$show_bid_attachment_upload_button_style= "display:none;";
						if(($login_user_detail['current_membership_plan_id'] == 1 && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0 &&  $no_bid_attachment_uploaded_user < $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid')) || ($login_user_detail['current_membership_plan_id'] == 4 && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0 &&  $no_bid_attachment_uploaded_user < $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid'))){
							$show_bid_attachment_upload_button_style= "display:block;";
						}
						?>
						</span>
					</div>
					
						<?php
						$show_bid_attachment_upload_button_style= "display:none;";
						if(($login_user_detail['current_membership_plan_id'] == 1 && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0 &&  $no_bid_attachment_uploaded_user < $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid')) || ($login_user_detail['current_membership_plan_id'] == 4 && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0 &&  $no_bid_attachment_uploaded_user < $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid'))){
							$show_bid_attachment_upload_button_style= "display:block;";
						}
						?>
						<div class="upBtn upload-document drop_zone" style="<?php echo $show_bid_attachment_upload_button_style; ?>">
							 <div id="overlay" class="d-none">
								<div class="text"><?php echo $this->config->item('drop_zone_drag_and_drop_files_area_message_txt') ?></div>
							</div>
							
							<label for="files" class="btn default_btn blue_btn fileinput-button fileinput-button-place-bid">
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
							
							$bid_place_button_text = $this->config->item('project_details_page_fulltime_project_bid_form_place_application_btn_txt');
							$bid_cancel_button_text = $this->config->item('project_details_page_fulltime_project_bid_form_cancel_application_btn_txt');
						}else{
							$bid_place_button_text = $this->config->item('project_details_page_project_bid_form_place_bid_btn_txt');
							$bid_cancel_button_text = $this->config->item('project_details_page_project_bid_form_cancel_btn_txt');
						}
						?>
						<input type = "hidden" name="project_id" value="<?php echo $project_data['project_id'] ?>" />
						<input type = "hidden" name="po_id" value="<?php echo Cryptor::doEncrypt($project_data['project_owner_id']); ?>" />
						<button type="button" class="btn default_btn red_btn cancelApply"><?php echo $bid_cancel_button_text; ?></button>
						<button type="button" id="place_bid" class="btn default_btn blue_btn"><?php echo $bid_place_button_text; ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
					</div>
				</div>
					
					<!-- <div class="error_div_sectn clearfix default_error_div_sectn">
						<span id="bid_description_length_count_message" class="content-count editBid_contentCount"><?php echo $this->config->item('project_details_page_bid_description_maximum_length_character_limit')."&nbsp;".$this->config->item('characters_remaining_message'); ?></span>
						<span id="bid_description_error" class="error_msg editBid_errorMsg"></span>
						<span id="bid_attachment_container" class="editBid_attachment">
						<?php
						$show_bid_attachment_upload_button_style= "display:none;";
						if(($login_user_detail['current_membership_plan_id'] == 1 && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0 &&  $no_bid_attachment_uploaded_user < $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid')) || ($login_user_detail['current_membership_plan_id'] == 4 && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0 &&  $no_bid_attachment_uploaded_user < $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid'))){
							$show_bid_attachment_upload_button_style= "display:block;";
						}
						?>
						</span>
					</div> -->
				</div>
				<div class="clearfix"></div>
				
				<!-- <div class="row margin_top5 edit_bid_row_adjust">
					<div class="col-md-5 col-sm-5 col-12 uploadBtn_editBid upldPlaceBidBtn">
						<?php
						$show_bid_attachment_upload_button_style= "display:none;";
						if(($login_user_detail['current_membership_plan_id'] == 1 && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0 &&  $no_bid_attachment_uploaded_user < $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid')) || ($login_user_detail['current_membership_plan_id'] == 4 && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0 &&  $no_bid_attachment_uploaded_user < $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid'))){
							$show_bid_attachment_upload_button_style= "display:block;";
						}
						?>
						<div class="upBtn upload-document drop_zone" style="<?php echo $show_bid_attachment_upload_button_style; ?>">
							 <div id="overlay" class="d-none">
								<div class="text"><?php echo $this->config->item('drop_zone_drag_and_drop_files_area_message_txt') ?></div>
							</div>
							
							<label for="files" class="btn default_btn blue_btn fileinput-button fileinput-button-place-bid">
								<i class="fa fa-cloud-upload" aria-hidden="true"></i><?php echo $this->config->item('project_details_page_bid_form_upload_file_button_txt'); ?>
							</label>
							<input type="file" class="bid_imgupload" accept="<?php echo $this->config->item('project_details_page_bid_attachment_allowed_file_types'); ?>" style="display:none"/>
						</div>
						
					</div>
					<div class="col-md-7 col-sm-7 col-12 canPB editPlaceBtn">
						<?php
						if($project_data['project_type'] == 'fulltime'){
							
							$bid_place_button_text = $this->config->item('project_details_page_fulltime_project_bid_form_place_application_btn_txt');
							$bid_cancel_button_text = $this->config->item('project_details_page_fulltime_project_bid_form_cancel_application_btn_txt');
						}else{
							$bid_place_button_text = $this->config->item('project_details_page_project_bid_form_place_bid_btn_txt');
							$bid_cancel_button_text = $this->config->item('project_details_page_project_bid_form_cancel_btn_txt');
						}
						?>
						<input type = "hidden" name="project_id" value="<?php echo $project_data['project_id'] ?>" />
						<input type = "hidden" name="po_id" value="<?php echo Cryptor::doEncrypt($project_data['project_owner_id']); ?>" />
						<button type="button" class="btn default_btn red_btn cancelApply"><?php echo $bid_cancel_button_text; ?></button>
						<button type="button" id="place_bid" class="btn default_btn blue_btn"><?php echo $bid_place_button_text; ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
					</div>
				</div> -->
                <!-- <div class="row margin_top5">
					<div class="col-md-12 col-sm-12 col-12" id="bid_attachment_container">
						<?php
						/* $show_bid_attachment_upload_button_style= "display:none;";
						if(($login_user_detail['current_membership_plan_id'] == 1 && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0 &&  $no_bid_attachment_uploaded_user < $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid')) || ($login_user_detail['current_membership_plan_id'] == 4 && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') && $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid') != 0 &&  $no_bid_attachment_uploaded_user < $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid'))){
							$show_bid_attachment_upload_button_style= "display:block;";
						} */
						?>
						
					</div>
                </div> -->
				
			</div>
		</div>
		</form>
	</div>
</div>




