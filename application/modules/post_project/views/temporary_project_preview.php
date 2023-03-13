<?php
$CI = & get_instance ();
$CI->load->library('Cryptor');
$payment_method = '';
$location = '';
if($project_data['escrow_payment_method'] == 'Y'){
	$payment_method = $this->config->item('project_details_page_payment_method_escrow_system');
	}
if($project_data['offline_payment_method'] == 'Y'){
	$payment_method = $this->config->item('project_details_page_payment_method_offline_system');
}
/* if(!empty($project_data['county_name'])){
	if(!empty($project_data['locality_name'])){
		$location .= '<span class="locaName">'.$project_data['locality_name'].'</span>';
	}
	if(!empty($project_data['postal_code'])){
		$location .= '<span class="postCode">'.$project_data['postal_code'] .',</span>';
	}else{
		$location .= ',';
	}
	$location .= '<span class="loca">'.$project_data['county_name'].'</span>';
} */
if(!empty($project_data['county_name']) && !empty($project_data['locality_name']) && !empty($project_data['postal_code'])){
	if(!empty($project_data['locality_name'])){
		$location .= '<span class="locaName">'.$project_data['locality_name'].'</span>';
	}
	if(!empty($project_data['postal_code'])){
		$location .= '<span class="postCode">'.$project_data['postal_code'] .',</span>';
	}else{
		$location .= ',';
	}
	$location .= '<span class="loca">'.$project_data['county_name'].'</span>';
}else if (!empty($project_data['county_name']) && !empty($project_data['locality_name']) && empty($project_data['postal_code'])){
	$location .= '<span class="locaName" style="margin-right:0px;">'.$project_data['locality_name'].'</span>,';
	$location .= '<span class="loca" style="margin-left:5px;">'.$project_data['county_name'].'</span>';

}else if(!empty($project_data['county_name']) && empty($project_data['locality_name']) && empty($project_data['postal_code']))
{
	$location .= '<span class="loca">'.$project_data['county_name'].'</span>';
}

// standard project availability
$standard_time_arr = explode(":", $this->config->item('standard_project_availability'));
$standard_check_valid_arr = array_map('getInt', $standard_time_arr); 
$standard_valid_time_arr = array_filter($standard_check_valid_arr);
$upgrades = [];
if($this->session->userdata('user')){
	
	if($project_data['featured'] == 'Y' ) {
		array_push($upgrades, 'featured');
	}
	if($project_data['urgent'] == 'Y' ) {
		array_push($upgrades, 'urgent');
	}
	if($project_data['sealed'] == 'Y' ) {
		array_push($upgrades, 'sealed');
	}
	if($project_data['hidden'] == 'Y' ) {
		array_push($upgrades, 'hidden');
	}
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
}
?>
<div class="dashTop">
	<div class="pdLRadjust">
		<div class="row">
			<div class="col-md-10 col-sm-10 col-12 pojDet">
				<!-- Project Details Start -->				
				<div class="default_block_header_transparent nBorder">
					<div class="transparent">
						<?php
						echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_details') : $this->config->item('project_details_page_project_details');?><span><?php echo $this->config->item('project_details_page_preview'); ?></span><div class="clearfix"></div>
					</div>
					<div class="pDtls transparent_body">
						<h4><?php echo htmlspecialchars(trim($project_data['project_title']), ENT_QUOTES); ?></h4>
						<div class="row">
							<div class="col-md-6 col-sm-12 col-xs-12 pDetailsL">
								<div class="pDSheduled"><label class="default_black_regular"><span class="default_black_bold">
											<i class="fa fa-file-text-o" aria-hidden="true"></i><?php 
											echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_project') : $this->config->item('project_details_page_project_type');
											?></span><?php
										if($project_data['project_type'] != 'fulltime'){
										?><?php
										if($project_data['project_type'] == 'fixed'){
											echo $this->config->item('project_details_page_fixed_budget');
										}else if($project_data['project_type'] == 'hourly'){
											echo $this->config->item('project_details_page_hourly_budget');
										}
										}
										?></label><label class="default_black_regular"><span class="default_black_bold"><i class="fa fa-credit-card" aria-hidden="true"></i><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_salary'): $this->config->item('project_details_page_project_budget'); ?></span><?php if($project_data['confidential_dropdown_option_selected'] == 'Y'){ if($project_data['project_type'] == 'fixed'){echo $this->config->item('displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected'); }else if($project_data['project_type'] == 'hourly'){echo $this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_confidential_option_selected'); }else if($project_data['project_type'] == 'fulltime'){echo $this->config->item('displayed_text_fulltime_project_details_page_salary_confidential_option_selected'); } }else if($project_data['not_sure_dropdown_option_selected'] == 'Y'){if($project_data['project_type'] == 'fixed'){echo $this->config->item('displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected');}else if($project_data['project_type'] == 'hourly'){echo $this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_not_sure_option_selected');}else if($project_data['project_type'] == 'fulltime'){echo $this->config->item('displayed_text_fulltime_project_details_page_salary_not_sure_option_selected'); }}else{if($project_data['max_budget'] != 'All'){if($project_data['project_type'] == 'hourly'){$budget_range = '';$budget_range .='<span class="word_set">'.number_format($project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'</span><span class="word_set">'. $this->config->item('post_project_budget_range_and').'</span><span class="word_set">'.number_format($project_data['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour').'</span>'; }else if($project_data['project_type'] == 'fulltime'){ $budget_range = '';$budget_range .='<span class="word_set">'.number_format($project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month').'</span><span class="word_set">'. $this->config->item('post_project_budget_range_and').'</span><span class="word_set">'.number_format($project_data['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_month').'</span>'; }else{ $budget_range = '';$budget_range .='<span class="word_set">'.number_format($project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'</span><span class="word_set">'. $this->config->item('post_project_budget_range_and').'</span><span class="word_set">'.number_format($project_data['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.'</span>'; } }else{if($project_data['project_type'] == 'hourly'){$budget_range = $this->config->item('post_project_budget_range_more_then').'&nbsp'.number_format($project_data['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_hour'); }else if($project_data['project_type'] == 'fulltime'){ $budget_range = $this->config->item('post_project_budget_range_more_then').'&nbsp'.number_format($project_data['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY .$this->config->item('post_project_budget_per_month'); }else{$budget_range = $this->config->item('post_project_budget_range_more_then').'&nbsp'.number_format($project_data['min_budget'], 0, '', ' ').'&nbsp'.CURRENCY; } } } ?><?php echo $budget_range; ?></label><?php if(!empty($payment_method)){ ?><label class="default_black_regular"><span class="default_black_bold"><i class="fa fa-credit-card" aria-hidden="true"></i><?php echo $this->config->item('project_details_page_payment_method'); ?></span><?php echo $payment_method; ?></label><?php } if(!empty($location)){ ?><label><span class="default_black_bold"><i class="fa fa-map-marker" aria-hidden="true"></i><?php echo $this->config->item('project_details_page_location'); ?></span><small class="oneLine default_black_regular"><?php echo $location; ?></small></label> <?php } ?>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="col-md-6 col-sm-12 col-xs-12 pDetailsR">
								<div class="pProject pBNone default_project_category">
									<?php
									if(!empty($category_data)){
										foreach($category_data as $category_key=>$category_value){
											if(!empty($category_value['parent_category_name']) && !empty($category_value['category_name'])){
									?>
											<div class="clearfix">
												<small class="pSmnu"><?php echo $category_value['parent_category_name']; ?></small>
												<a href="#">
													<span><?php echo $category_value['category_name']; ?></span>
												</a>
											</div>
									<?php
										
											}else{
												echo '<small>'.$category_value['category_name'].'</small>'; 
											}
										}	
									}
									?>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
						<?php
						//$remove_bottom_border_class_badge_not_exist = '';
						//if($project_data['featured'] == 'N' && $project_data['urgent'] == 'N' && $project_data['sealed'] == 'N' && $project_data['hidden'] == 'N'){
							//$remove_bottom_border_class_badge_not_exist = 'pdPMB';
						//}
						 if($project_data['featured'] == 'Y' || $project_data['urgent'] == 'Y' || $project_data['sealed'] == 'Y' || $project_data['hidden'] == 'Y'){
						?>
						<div class="pDBttm <?php //echo $remove_bottom_border_class_badge_not_exist; ?>">
							<div class="badgeAction">
								<label class="badgeOnly">							
									<div class="default_project_badge">
										<?php
										if($project_data['featured'] == 'Y'){
											echo '<button type="button" class="btn badge_feature">'.$this->config->item('post_project_page_upgrade_type_featured').'</button>';
										}if($project_data['urgent'] == 'Y'){
											echo '<button type="button" class="btn badge_urgent">'.$this->config->item('post_project_page_upgrade_type_urgent').'</button>';
											}
										if($project_data['sealed'] == 'Y'){
											echo '<button type="button" class="btn badge_sealed">'.$this->config->item('post_project_page_upgrade_type_sealed').'</button>';
										}
										if($project_data['hidden'] == 'Y'){
											echo '<button type="button" class="btn badge_hidden">'.$this->config->item('post_project_page_upgrade_type_hidden').'</button>';
										}
										?>	
									</div>
								</label>
							</div>
						</div>
						  <?php } ?>
					</div>
				</div>
				<!-- Project Details End -->
				<!-- Description Start -->
				
				<div class="default_block_header_transparent nBorder margin_top20">
					<div class="transparent">
						<?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('project_details_page_fulltime_project_description') : $this->config->item('project_details_page_project_description');?><div class="clearfix"></div>
					</div>
					<div class="proDn transparent_body">
						<div class="proPart">
							<p class="line-break"><?php echo convert_url_to_anchor(nl2br(str_replace("  "," &nbsp;",htmlspecialchars($project_data['project_description'], ENT_QUOTES)))); ?></p>
						</div>
				
						
							<?php
							if(!empty($project_attachment_data)){
								$attachMultiple='';
								if(!empty($project_tag_data)){
									$attachMultiple=' attachMultiple';
								}
							?>
							
							<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 aNowBorder attachHr">
								<hr/>
								<div class="pDAttach<?php echo $attachMultiple; ?>">
									<?php
									foreach($project_attachment_data as $project_attachment_key=>$project_attachment_value){
										$attachment_id = Cryptor::doEncrypt($project_attachment_value['id']);
										echo '<label class="attachFile" id="af'.($project_attachment_key+1).'"><span><a style="cursor:pointer;" class="download_attachment" data-attr="'.$attachment_id.'"><i class="fas fa-paperclip"></i>'.$project_attachment_value['temp_project_attachment_name'].'</a></span></label>';
									}
									?>
								</div>
							</div>
							</div>
							<?php
							}
							?>
							<?php
							if(!empty($project_tag_data)){
							?>
							
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 aNowBorder">
									<hr/>
									<div class="portTags">
										<?php foreach($project_tag_data as $project_tag_key=>$project_tag_value){ echo '<label class="defaultTag"><span class="tagFirst">'.$project_tag_value['temp_project_tag_name'].'</span></label>';} ?>
									</div>
								</div>
							</div>
							<?php
							}
							?>
						
					</div>
				</div>
				
			
				<div class="project-relative-btn <?php
echo $project_data['project_type']== 'fulltime' ? 'tempFulltimeBtn' : ''; ?>">
					<div class="row">
						<div class="col-md-12 text-center">
							<button type="button" class="btn default_btn red_btn" id="project_preview_cancel"><?php echo $project_data['project_type']== 'fulltime' ? $this->config->item('post_fulltime_project_page_cancel_project_button_txt') : $this->config->item('post_project_page_cancel_project_button_txt');?></button>
							<?php
							if($this->session->userdata('user')){
							?>
								<?php
								if($project_data['project_type'] != 'fulltime' && (($draft_cnt < $po_max_draft_projects_number && $po_max_draft_projects_number != 0) || ($open_bidding_cnt<$po_max_open_projects_number && (!empty($standard_valid_time_arr) || $po_max_open_projects_number != '0')))){
								?>
								<button type="button" class="btn default_btn blue_btn" id="edit_project_preview"><?php echo $this->config->item('project_details_page_project_continue_editing_button_txt');?></button>
								<?php
								}if($project_data['project_type'] != 'fulltime' && ($draft_cnt < $po_max_draft_projects_number && $po_max_draft_projects_number != 0 && (!empty($standard_valid_time_arr) || $po_max_open_projects_number != '0'))){
								?>
								<button type="button" class="btn default_btn blue_btn" data-attr="preview" id="post_project_draft"><?php echo $this->config->item('post_project_page_save_project_as_draft_button_txt');?></button>
								<?php
								}
								?>

								<?php
								  if($project_data['project_type'] == 'fulltime' && (!empty($standard_valid_time_arr) && $po_max_open_fulltime_projects_number != '0' && $fulltime_open_bidding_cnt < $po_max_open_fulltime_projects_number)){
								  ?>
								<button type="button" class="btn green_btn default_btn" data-attr="preview" id="post_project_publish"><?php echo $this->config->item('post_fulltime_project_page_publish_project_button_txt');?></button>
								<?php
								}
								if($project_data['project_type'] == 'fulltime' && (($fulltime_draft_cnt < $po_max_draft_fulltime_projects_number && $po_max_draft_fulltime_projects_number != 0) || ($fulltime_open_bidding_cnt < $po_max_open_fulltime_projects_number && (!empty($standard_valid_time_arr) || $po_max_open_fulltime_projects_number != '0')))){
								?>
								<button type="button" class="btn default_btn blue_btn" id="edit_project_preview"><?php echo $this->config->item('project_details_page_fulltime_project_continue_editing_button_txt');?></button>
								<?php
								}if($project_data['project_type'] == 'fulltime' && ($fulltime_draft_cnt < $po_max_draft_fulltime_projects_number && $po_max_draft_fulltime_projects_number != 0 && (!empty($standard_valid_time_arr) || $po_max_open_fulltime_projects_number != '0'))){
								?>
								<button type="button" class="btn default_btn blue_btn" data-attr="preview" id="post_project_draft"><?php echo $this->config->item('post_fulltime_project_page_save_project_as_draft_button_txt');?></button>
								<?php
								}
								?>
								<?php
								if($project_data['project_type'] != 'fulltime' && (!empty($standard_valid_time_arr) && $po_max_open_projects_number != '0' && $open_bidding_cnt<$po_max_open_projects_number)){
								?>
								<button type="button" class="btn green_btn default_btn" data-attr="preview" id="post_project_publish"><?php echo $this->config->item('post_project_page_publish_project_button_txt');?></button>
								<?php
								}
								?>
								
								
								
							<?php
							}else{
								if($project_data['project_type'] != 'fulltime'){
							?>
									<button type="button" class="btn default_btn blue_btn" id="edit_project_preview"><?php echo $this->config->item('project_details_page_project_continue_editing_button_txt');?></button>
									<button type="button" class="btn default_btn blue_btn" data-attr="preview" id="post_project_draft"><?php echo $this->config->item('post_project_page_save_project_as_draft_button_txt');?></button>
									<button type="button" class="btn green_btn default_btn" data-attr="preview" id="post_project_publish"><?php echo $this->config->item('post_project_page_publish_project_button_txt');?></button>
							<?php }else{ ?>
									<button type="button" class="btn default_btn blue_btn" id="edit_project_preview"><?php echo $this->config->item('project_details_page_fulltime_project_continue_editing_button_txt');?></button>
									<button type="button" class="btn default_btn blue_btn" data-attr="preview" id="post_project_draft"><?php echo $this->config->item('post_fulltime_project_page_save_project_as_draft_button_txt');?></button>
									<button type="button" class="btn green_btn default_btn" data-attr="preview" id="post_project_publish"><?php echo $this->config->item('post_fulltime_project_page_publish_project_button_txt');?></button>
							<?php	
								}
								
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Middle Section End -->
</div>
<script>
var signup_page_url = "<?php echo $this->config->item('signup_page_url'); ?>";
var project_type = "<?php  echo $project_data['project_type']; ?>";	
var popup_alert_heading = "<?php echo $this->config->item('popup_alert_heading'); ?>";
//var project_attachment_not_exist_temporary_project_preview_validation_post_project_message = "<?php echo $this->config->item('project_attachment_not_exist_temporary_project_preview_validation_post_project_message'); ?>";
var post_project_edit_temporary_project_preview_page_url = "<?php echo $this->config->item('post_project_edit_temporary_project_preview_page_url'); ?>";
var dashboard_page_url = "<?php echo $this->config->item('dashboard_page_url'); ?>";
var project_detail_page_url = "<?php echo $this->config->item('project_detail_page_url');?>";
var temp_project_id = "<?php echo $temp_project_id; ?>";
// project availability settings

standard_time_valid = "<?php echo !empty($standard_valid_time_arr) ? true : false; ?>";
upgrades = "<?php echo count($upgrades); ?>";

</script>
<script src="<?php echo JS; ?>modules/temporary_project_preview.js"></script>