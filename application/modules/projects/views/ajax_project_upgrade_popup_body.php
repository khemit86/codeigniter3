<?php
// featured project availability
$featured_time_arr = explode(":", $this->config->item('project_upgrade_availability_featured'));
$featured_check_valid_arr = array_map('getInt', $featured_time_arr); 
$featured_valid_time_arr = array_filter($featured_check_valid_arr);
// urgent project availability
$urgent_time_arr = explode(":", $this->config->item('project_upgrade_availability_urgent'));
$urgent_check_valid_arr = array_map('getInt', $urgent_time_arr); 
$urgent_valid_time_arr = array_filter($urgent_check_valid_arr);


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
##########

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
	
/* $featured_availabity_days = convert_time_to_project_upgrade_availability_days($this->config->item('project_upgrade_availability_featured'));
$urgent_availabity_days = convert_time_to_project_upgrade_availability_days($this->config->item('project_upgrade_availability_urgent')); */



$project_featured_upgrade_availability_array = explode(':',$this->config->item('project_upgrade_availability_featured'));
$total_project_featured_upgrade_availability_seconds = ($project_featured_upgrade_availability_array[0] * 3600)+($project_featured_upgrade_availability_array[1] * 60)+$project_featured_upgrade_availability_array[2];
$featured_availabity_days = trim(secondsToWords($total_project_featured_upgrade_availability_seconds));

$project_urgent_upgrade_availability_array = explode(':',$this->config->item('project_upgrade_availability_urgent'));
$total_project_urgent_upgrade_availability_seconds = ($project_urgent_upgrade_availability_array[0] * 3600)+($project_urgent_upgrade_availability_array[1] * 60)+$project_urgent_upgrade_availability_array[2];
$urgent_availabity_days = trim(secondsToWords($total_project_urgent_upgrade_availability_seconds));



$project_title = trim($project_data['project_title']);
$project_title = '<a href="#" class="default_popup_blue_text">'.$project_data['project_title'].'</a>';

$proceed_btn_txt = $this->config->item('project_upgrade_modal_proceed_btn_txt');
$cancel_btn_txt = $this->config->item('cancel_btn_txt');
if(in_array($action_type, array('prolong_availability_featured','prolong_availability_urgent'))){
	
	$proceed_btn_txt = $this->config->item('project_upgrade_prolong_modal_proceed_btn_txt');
	
}


// latest featured upgrade date	
?>	
<div class="row">
	<div class="col-md-12">
		<div id="error_user_no_balance" class="alert alert-danger" style="display:none;"></div>
		<?php
		$attributes = [
		 'id' => 'project_upgrade_form',
		 'class' => '',
		 'role' => 'form',
		 'name' => 'project_upgrade_form',
		 'enctype' => 'multipart/form-data',
		];
		echo form_open('', $attributes);
		?>
		<input name="project_id" type="hidden" value="<?php echo $project_data['project_id'] ?>" />
		<input name="action_type" type="hidden" value="<?php echo $action_type; ?>" />
		<input name="page_type" type="hidden" value="<?php echo $page_type; ?>" />
		<input name="project_type" type="hidden" value="<?php echo $project_type; ?>" />
		<input name="po_id" type="hidden" value="<?php echo $po_id; ?>" />
		<?php
		$featured_exists = '';
		if(($action_type == 'upgrade_project' || $action_type == 'prolong_availability_featured' || $action_type == 'upgrade_as_featured_project') && !empty($featured_valid_time_arr)){
			
			$featured_exists = 'margin_top10';
		
			if($project_data['featured'] == 'Y' && $action_type == 'prolong_availability_featured'){
			
				$time_arr = explode(':', $this->config->item('project_upgrade_availability_featured'));
				$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
				
				$featured_availability_expire_date = date(DATE_TIME_FORMAT,$featured_max);
				$featured_availability_extended_date = date(DATE_TIME_FORMAT,strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds',$featured_max));
				if($project_data['project_type'] == 'fulltime'){
					$featured_upgrade_heading = $this->config->item('fulltime_project_upgrade_popup_prolong_featured_upgrade_heading');
					$featured_upgrade_description = $this->config->item('fulltime_project_upgrade_popup_prolong_featured_upgrade_description');
					$featured_upgrade_heading = str_replace(array('{project_title}','{project_featured_upgrade_prolong_availability}','{project_featured_upgrade_price}'),array($project_title,$featured_availabity_days,str_replace(".00","",number_format($this->config->item('project_upgrade_price_featured'),  2, '.', ' '))." ".CURRENCY),$featured_upgrade_heading);
					
				}else{
					$featured_upgrade_heading = $this->config->item('project_upgrade_popup_prolong_featured_upgrade_heading');
					$featured_upgrade_description = $this->config->item('project_upgrade_popup_prolong_featured_upgrade_description');
					$featured_upgrade_heading = str_replace(array('{project_title}','{project_featured_upgrade_prolong_availability}','{project_featured_upgrade_price}'),array($project_title,$featured_availabity_days,str_replace(".00","",number_format($this->config->item('project_upgrade_price_featured'),  2, '.', ' '))." ".CURRENCY),$featured_upgrade_heading);
				}
				
				
					
				$featured_upgrade_description = str_replace(array('{featured_upgrade_availability_expire_date}','{featured_upgrade_availability_extended_date}'),array($featured_availability_expire_date,$featured_availability_extended_date),$featured_upgrade_description);
			}else{
				if($project_data['project_type'] == 'fulltime'){
					$featured_upgrade_heading = $this->config->item('fulltime_project_upgrade_popup_featured_upgrade_heading');
					$featured_upgrade_description = $this->config->item('fulltime_project_upgrade_popup_featured_upgrade_description');	
					$featured_upgrade_heading = str_replace(array('{project_title}','{project_featured_upgrade_availability}'),array($project_title,$featured_availabity_days),$featured_upgrade_heading);
					
				}else{
					$featured_upgrade_heading = $this->config->item('project_upgrade_popup_featured_upgrade_heading');
					$featured_upgrade_description = $this->config->item('project_upgrade_popup_featured_upgrade_description');
					$featured_upgrade_heading = str_replace(array('{project_title}','{project_featured_upgrade_availability}'),array($project_title,$featured_availabity_days),$featured_upgrade_heading);
				}
				
			
				
			}
		
		?>
		
				<div class="checkbox-btn-inner">
					<input id="most-project1" style="position: absolute;top: 0;width: 100%;" type="checkbox" class="upgrade_type_featured upgrade_type" name="upgrade_type_featured" value="Y">
					<div class="checkbox-inner-div">
						<label for="most-project1">
							<div class="row">
								<div class="checkbox-title"> <span class="upgrade_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></span></div>
								<div class="pay-sectn"><span><span id="upgrade_type_featured_amount" data-attr="<?php echo str_replace(" ","",$this->config->item('project_upgrade_price_featured')); ?>"><?php echo number_format($this->config->item('project_upgrade_price_featured'), 0, '', ' '); ?></span> <?php echo CURRENCY; ?></span></div>  
							</div>
							<div class="checkbox-content">
								<p>
									<div class="popup_body_semibold_title"><?php echo $featured_upgrade_heading; ?></div>
									<?php if($featured_upgrade_description!='') { ?>
									<div class="popup_upgrade_description upgrade_badge_description"><?php echo $featured_upgrade_description; ?></div>
									<?php } ?>
								</p>
							</div>
						</label>
					</div>
				</div>
		
		<?php	
		}
		if(($action_type == 'upgrade_project' || $action_type == 'prolong_availability_urgent' || $action_type == 'upgrade_as_urgent_project') && !empty($urgent_valid_time_arr)){
			if($project_data['urgent'] == 'Y' && $action_type == 'prolong_availability_urgent'){
				$time_arr = explode(':', $this->config->item('project_upgrade_availability_urgent'));
				$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
				
				$urgent_availability_expire_date = date(DATE_TIME_FORMAT,$urgent_max);
				$urgent_availability_extended_date = date(DATE_TIME_FORMAT,strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds',$urgent_max));
				
				if($project_data['project_type'] == 'fulltime'){
					$urgent_upgrade_heading = $this->config->item('fulltime_project_upgrade_popup_prolong_urgent_upgrade_heading');
					$urgent_upgrade_description = $this->config->item('fulltime_project_upgrade_popup_prolong_urgent_upgrade_description');
					$urgent_upgrade_heading = str_replace(array('{project_title}','{project_urgent_upgrade_prolong_availability}','{project_urgent_upgrade_price}'),array($project_title,$urgent_availabity_days,str_replace(".00","",number_format($this->config->item('project_upgrade_price_urgent'),  2, '.', ' '))." ".CURRENCY),$urgent_upgrade_heading);
					
				}else{
					$urgent_upgrade_heading = $this->config->item('project_upgrade_popup_prolong_urgent_upgrade_heading');
					$urgent_upgrade_description = $this->config->item('project_upgrade_popup_prolong_urgent_upgrade_description');
					$urgent_upgrade_heading = str_replace(array('{project_title}','{project_urgent_upgrade_prolong_availability}','{project_urgent_upgrade_price}'),array($project_title,$urgent_availabity_days,str_replace(".00","",number_format($this->config->item('project_upgrade_price_urgent'),  2, '.', ' '))." ".CURRENCY),$urgent_upgrade_heading);
				}
				
				
					
				$urgent_upgrade_description = str_replace(array('{urgent_upgrade_availability_expire_date}','{urgent_upgrade_availability_extended_date}'),array($urgent_availability_expire_date,$urgent_availability_extended_date),$urgent_upgrade_description);
			}else{
			
				if($project_data['project_type'] == 'fulltime'){
					$urgent_upgrade_heading = $this->config->item('fulltime_project_upgrade_popup_urgent_upgrade_heading');
					$urgent_upgrade_description = $this->config->item('fulltime_project_upgrade_popup_urgent_upgrade_description');	
					$urgent_upgrade_heading = str_replace(array('{project_title}','{project_urgent_upgrade_availability}'),array($project_title,$urgent_availabity_days),$urgent_upgrade_heading);
				}else{
					$urgent_upgrade_heading = $this->config->item('project_upgrade_popup_urgent_upgrade_heading');
					$urgent_upgrade_description = $this->config->item('project_upgrade_popup_urgent_upgrade_description');
					$urgent_upgrade_heading = str_replace(array('{project_title}','{project_urgent_upgrade_availability}'),array($project_title,$urgent_availabity_days),$urgent_upgrade_heading);
				}
				
			
			}
			?>
			
			
				<div class="checkbox-btn-inner <?php echo $featured_exists; ?>">
					<input id="most-project2"  class="upgrade_type_urgent upgrade_type" style="position: absolute;top: 0;width: 100%;" type="checkbox" name="upgrade_type_urgent"  value="Y">
					<div class="checkbox-inner-div">
						<label for="most-project2">
							<div class="row">
								<div class="checkbox-title"> <span class="upgrade_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></span></div>
								<div class="pay-sectn"> <span><span id="upgrade_type_urgent_amount" data-attr="<?php echo str_replace(" ","",$this->config->item('project_upgrade_price_urgent')); ?>"><?php echo number_format($this->config->item('project_upgrade_price_urgent'), 0, '', ' '); ?></span> <?php echo CURRENCY; ?></span></div>
							</div>
							<div class="checkbox-content">
								<p>
									<div class="popup_body_semibold_title"><?php echo $urgent_upgrade_heading; ?></div>
									<?php if($urgent_upgrade_description!='') { ?>
									<div class="popup_upgrade_description upgrade_badge_description"><?php echo $urgent_upgrade_description; ?></div>
									<?php } ?>
								</p>
							</div>
						</label>
					</div>
				</div>
		
			
		<?php	
		}
		?>
		
		<div class="total-price" style="display:none;">
			<p><?php echo $this->config->item('post_project_page_upgrade_total_txt'); ?> <span id="total_upgrade_amount" data-attr="0">0</span>  <?php echo CURRENCY; ?></p>
		</div>
		<div id="upgrade_message"></div>
		
		
		
		<?php echo form_close(); ?>  
	</div>
</div>
<div class="text-right default_popup_close default_popup_close_adjust">
	<div class="popupBtn_adjust">
		<button type="button" class="btn default_btn red_btn" data-dismiss="modal"><?php echo $cancel_btn_txt; ?></button>
		<button type="button" class="btn default_btn blue_btn update_project_upgrade_button" disabled ><?php echo $proceed_btn_txt; ?></button>
	</div>
</div>