<?php
$user = $this->session->userdata('user');
$calendar_months = 	 $this->config->item('calendar_months');	
?>
<?php
$noRecDiv = '';
$noBorder = '';
if(empty($user_detail['description']) && empty($user_skills_data) && empty($recordArr) && empty($user_services_data) && empty($user_work_experience_data) && empty($user_education_training_data) && empty($language_name) && empty($user_spoken_languages_data) && empty($user_certifications_data)){

if($this->session->userdata('user') && $user[0]->user_id == $user_detail['user_id']){
		$no_data_msg =  $this->config->item('user_profile_page_information_tab_no_data_same_user_view');
}else{
		$user_name = $user_detail['first_name'] . ' ' . $user_detail['last_name'] ;
		if($user_detail['gender'] == 'M'){
		  $no_data_msg = $this->config->item('user_profile_page_information_tab_no_data_male_visitor_view');
		} else if($user_detail['gender'] == 'F'){
		  $no_data_msg = $this->config->item('user_profile_page_information_tab_no_data_female_visitor_view');
		}
		$no_data_msg = str_replace(array('{user_first_name_last_name}'),array($user_name),$no_data_msg);

}
$noRecDiv = '<div class="default_blank_message">'.$no_data_msg.'</div>';
$noBorder = 'noBorder';
}
?>
<div>
	<div class="userRight_Section fjRgt">
		<!-- Project Details Start -->				
		<div class="proDtls <?php echo $noBorder;?>">
			<div class="pDtls">
				<?php echo $noRecDiv; ?>
				<!-- Description Start -->
				<?php 
				if($user_detail['description']!='')  {
				?>
				<div class="usrTab Ppink_section">
					<div class="bagTAg Ppink">
				   <p><?php echo $this->config->item('pa_user_profile_page_description');  ?></p>
					</div>
					<div class="descTxt default_user_description desktop-secreen">
						<?php
							$desktop_cnt            =	0;
							$desktop_descLeng	=	strlen($user_detail['description']);  
							if($desktop_descLeng <= $this->config->item('user_profile_description_display_minimum_length_character_limit_desktop')) {
							 $desktop_description	= nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES));
							} else {
								$desktop_description	= character_limiter(nl2br($user_detail['description']),$this->config->item('user_profile_description_display_minimum_length_character_limit_desktop'));
								$desktop_restdescription = nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES));
								$desktop_cnt = 1;
								}
						?>
						<p id="desktop_lessD">
							<?php echo $desktop_description; ?><?php if($desktop_cnt==1) {?><span id="desktop_dotsD"></span><button onclick="showMoreDescription('desktop')"><?php echo $this->config->item('show_more_txt'); ?></button>
							<?php } ?>
						</p>
						<p id="desktop_moreD" class="moreD">
							<?php echo $desktop_restdescription;?><button onclick="showMoreDescription('desktop')"><?php echo $this->config->item('show_less_txt'); ?></button>
						</p>
					</div>
					<div class="descTxt default_user_description ipad-screen">
						<?php
						$tablet_cnt            =	0;
						$tablet_descLeng	=	strlen($user_detail['description']);
						if($tablet_descLeng <= $this->config->item('user_profile_description_display_minimum_length_character_limit_tablet')) {
						$tablet_description	= nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES));
						} else {
							//$tablet_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_description_display_minimum_length_character_limit_tablet'));
							  $tablet_description	= character_limiter(nl2br($user_detail['description']),$this->config->item('user_profile_description_display_minimum_length_character_limit_tablet'));
							$tablet_restdescription = nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES));
							$tablet_cnt = 1;
						}
						?>
						<p id="tablet_lessD">
						<?php echo $tablet_description; ?><?php if($tablet_cnt==1) {?><span id="tablet_dotsD"></span><button onclick="showMoreDescription('tablet')"><?php echo $this->config->item('show_more_txt'); ?></button>
						<?php } ?>
						</p>
						<p id="tablet_moreD" class="moreD">
						<?php echo $tablet_restdescription;?><button onclick="showMoreDescription('tablet')"><?php echo $this->config->item('show_less_txt'); ?></button>
						</p>
					</div>
					<div class="descTxt default_user_description mobile-screen">
						<?php
							$mobile_cnt            =	0;
							$mobile_descLeng	=	strlen($user_detail['description']);
							if($mobile_descLeng <= $this->config->item('user_profile_description_display_minimum_length_character_limit_mobile')) {
								$mobile_description	= nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES));
							} else {
								//$mobile_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_description_display_minimum_length_character_limit_mobile'));
								$mobile_description	= character_limiter(nl2br($user_detail['description']),$this->config->item('user_profile_description_display_minimum_length_character_limit_mobile'));
								$mobile_restdescription = nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES));
								$mobile_cnt = 1;
							}
						?>
						<p id="mobile_lessD">
							<?php echo $mobile_description; ?><?php if($mobile_cnt==1) {?><span id="mobile_dotsD"></span><button onclick="showMoreDescription('mobile')"><?php echo $this->config->item('show_more_txt'); ?></button>
							<?php } ?>
						</p>
						<p id="mobile_moreD" class="moreD">
							<?php echo $mobile_restdescription ;?><button onclick="showMoreDescription('mobile')"><?php echo $this->config->item('show_less_txt'); ?></button>
						</p>
					</div>
				</div>
				<div class="userHeightAdjust"></div>
				<?php } ?>
				<!-- Description End -->
				<!-- Areas of Expertise Start -->
				<?php if(!empty($recordArr)) { ?>
				<div class="usrTab Pred_section">
					<div class="bagTAg Pred">
						<p><?php echo $this->config->item('pa_user_profile_page_areas_of_expertise');  ?></p>
					</div>
					<div class="aoExpt">
					   <?php //echo $areas_of_expertise; ?>
					   <?php
						$remainingCat = count($recordArr)-$this->config->item('user_profile_page_maximum_area_of_expertise_show');
						
					   $m = 0;
					   foreach ($recordArr as $catKey=>$catArr) { 
						$m++;   
						if($m == $this->config->item('user_profile_page_maximum_area_of_expertise_show')+1) { echo '<input type="hidden" class="moreCat" value="1"><section id="catDiv" class="chkSameLine" style="display:none">'; }   
						?>
							<p><label class="defaultTag"><label>
								<span class="tagFirst"><?php echo $catKey; ?></span>
								<?php
								if(is_array($catArr)){
									foreach ($catArr as $scat) { ?>
									<small class="tagSecond"><?php echo $scat; ?></small>
								<?php		
									}
								} ?>
							</label></label></p>
					 <?php //$m++; 
							if($m == count($recordArr)) { echo '</section>';}
						} ?>
					</div>
					<?php
					if(count($recordArr) >$this->config->item('user_profile_page_maximum_area_of_expertise_show') ) {
					?>
					
					<div class="showmore_category"><label class="catAll catAllCategory" onclick="toogleMore('<?php echo $remainingCat; ?>','area_of_expertise')"><i class="fas fa-angle-down"></i> <?php echo str_replace('{remaining_category}', $remainingCat, $this->config->item('user_profile_page_show_more_area_of_expertise_text')) ;?></label></div>
					<?php
					}
					?>
				</div>
				<div class="userHeightAdjust"></div>
				 <?php } ?>
				<!-- Areas of Expertise End -->
				<!-- Services Provided Start -->
				<?php
				if(!empty($user_services_data)){
				$remainingServices = count($user_services_data)-$this->config->item('user_profile_page_maximum_services_provided_show');
				?>
				<div class="usrTab Predpink_section">
					<div class="bagTAg Predpink">
						<p><?php echo $this->config->item('pa_user_profile_page_services_provided');  ?></p>
					</div>
					<div class="userSProvide fontSize0">
						<?php
						 $m = 0;	
						foreach($user_services_data as $services_key=>$services_value){ 
						 $m++;
						 if($m == $this->config->item('user_profile_page_maximum_services_provided_show')+1) { echo '<input type="hidden" class="moreServicesProvided" value="1"><section id="serviceProvidedDiv" class="chkSameLine" style="display:none">'; }   
						?>
						<label class="defaultTag"><span class="tagFirst"><?php echo htmlspecialchars($services_value['service_provided'], ENT_QUOTES); ?></span></label>
						<?php
						if($m == count($user_services_data)) { echo '</section>';}
						}
						?>
					</div>
					<?php
					if(count($user_services_data) >$this->config->item('user_profile_page_maximum_services_provided_show') ) {
					?>
					<div class="showmore_category"><label class="AllServicesProvided catAll" onclick="toogleMore('<?php echo $remainingServices; ?>','services_provided')"><i class="fas fa-angle-down"></i> <?php echo str_replace('{remaining_services_provided}', $remainingServices, $this->config->item('user_profile_page_show_more_services_provided_text')) ;?></label></div>
					<?php
					}
					?>
				</div>
				<div class="userHeightAdjust"></div>
				<?php
				}
				?>
				<!-- Services Provided End -->
				<!-- Skills Start -->
				<?php
				if(!empty($user_skills_data)){
				$remainingSkills = count($user_skills_data)-$this->config->item('user_profile_page_maximum_skills_show');
				?>
				<div class="usrTab PlightGreen_section">
					<div class="bagTAg PlightGreen">
						<p><?php echo $this->config->item('pa_user_profile_page_skills');  ?></p>
					</div>
					<div class="fontSize0">
						<?php
						 $m = 0;
						foreach($user_skills_data as $skills_key=>$skills_value){ 
						 $m++;	
						  if($m == $this->config->item('user_profile_page_maximum_skills_show')+1) { echo '<input type="hidden" class="moreSkills" value="1"><section id="skillsDiv" class="chkSameLine" style="display:none">'; }   
						?>
						<label class="defaultTag"><span class="tagFirst"><?php echo htmlspecialchars(trim($skills_value['user_skill']), ENT_QUOTES); ?></span></label>
						<?php 
						
							if($m == count($user_skills_data)) { echo '</section>';}
						} ?>
					</div>
					<?php
					if(count($user_skills_data) >$this->config->item('user_profile_page_maximum_skills_show') ) {
					?>
					<div class="showmore_category"><label class="AllSkills catAll" onclick="toogleMore('<?php echo $remainingSkills; ?>','skills')"><i class="fas fa-angle-down"></i> <?php echo str_replace('{remaining_skills}', $remainingSkills, $this->config->item('user_profile_page_show_more_skills_text')) ;?></label></div>
					<?php
					}
					?>
				</div>
				<div class="userHeightAdjust"></div>
				<?php
				}
				?>
				<!-- Skills End -->
				
				<!-- Work Experience End -->
				<?php
				if(!empty($user_work_experience_data)){

					$total_work_experience = count($user_work_experience_data);
					$work_experience_record_counter = 1;
					
					$remainingWorkExperience = count($user_work_experience_data)-$this->config->item('user_profile_page_maximum_work_experience_show');
					
				?>
				<div class="usrTab Pyellow_section">
					<div class="bagTAg Pyellow">
						<p><?php echo $this->config->item('pa_user_profile_page_work_experience') ?></p>
					</div>
					<?php
						 $m = 0;
						foreach($user_work_experience_data as $work_experience_key=>$work_experience_value){
							 $m++;	
							$work_experience_last_record_class_remove_border_bottom = '';
							if($work_experience_record_counter == $total_work_experience){
								$work_experience_last_record_class_remove_border_bottom = 'default_noborder';
							}
							if($m == $this->config->item('user_profile_page_maximum_work_experience_show')+1) { echo '<input type="hidden" class="moreWorkExperience" value="1"><section id="workExperienceDiv" class="chkSameLine" style="display:none">'; }  
						?>
						<div class="wkExp default_bottom_border <?php echo $work_experience_last_record_class_remove_border_bottom; ?>">
							<div class="wkExp_title"><a href="#"><?php echo htmlspecialchars($work_experience_value['position_name'], ENT_QUOTES); ?></a></div>
							<div class="headline_title"><?php echo htmlspecialchars($work_experience_value['position_company_name'], ENT_QUOTES); ?></div>					
							<div class="default_user_location">
								<span><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span><small class="<?php echo (!preg_match('/\s/',$work_experience_value['position_company_address'])) ? 'street_address_nospace' : ''; ?>"><?php echo htmlspecialchars($work_experience_value['position_company_address'], ENT_QUOTES);if(!empty($work_experience_value['country_name'])){ echo ","; } ?></small><?php if(!empty($work_experience_value['country_name'])){ ?><small><?php echo htmlspecialchars($work_experience_value['country_name'], ENT_QUOTES); ?><div class="default_user_location_flag" style="background-image: url('<?php echo ASSETS.'images/countries_flags/'.strtolower($work_experience_value['country_code']).'.png'; ?>')"></div></small><?php } ?>     
							</div>
							<div class="clearfix"></div>
							<div class="etDate"><?php echo $this->config->item('personal_account_work_experience_section_from')." ".$calendar_months[$work_experience_value['position_from_month']]." ".$work_experience_value['position_from_year']." "; ?><?php 
							if($work_experience_value['position_still_work'] == 0){	
								echo $this->config->item('personal_account_work_experience_section_to')." ".$calendar_months[$work_experience_value['position_to_month']]." ".$work_experience_value['position_to_year'];
							}else{
								echo $this->config->item('personal_account_work_experience_section_to')." ".$this->config->item('present_txt');
							}
							$from_year = $work_experience_value['position_from_year'];
							$from_month = $work_experience_value['position_from_month'];
							if($work_experience_value['position_still_work'] == 1){
								
								$to_year = date('Y');
								$to_month = date('n');
							}else{
								$to_year = $work_experience_value['position_to_year'];
								$to_month = $work_experience_value['position_to_month'];
							}

							$user_work_experience_diff  = calculate_user_work_experience($from_year,$from_month,$to_year,$to_month,$work_experience_value['position_still_work']);
							if(!empty($user_work_experience_diff)){
								echo " (".$user_work_experience_diff.")";	
								
							}	
							?></div><div class="default_user_description desktop-secreen">
								<?php
									$desktop_cnt            =	0;
									$desktop_descLeng	=	strlen($work_experience_value['position_description']);  
									if($desktop_descLeng <= $this->config->item('user_profile_work_experience_section_comments_display_minimum_length_character_limit_desktop')) {
									 $desktop_description	=  htmlspecialchars($work_experience_value['position_description'], ENT_QUOTES);
									 
									 } else {
										$desktop_description	= character_limiter($work_experience_value['position_description'],$this->config->item('user_profile_work_experience_section_comments_display_minimum_length_character_limit_desktop'));
										$desktop_restdescription = nl2br(str_replace("  "," &nbsp;",htmlspecialchars($work_experience_value['position_description'], ENT_QUOTES)));
										$desktop_cnt = 1;
									}
								?>
								<p id="<?php echo "desktop_we_".$work_experience_value['user_id'].$work_experience_value['id'] ?>_lessD">
									<?php echo $desktop_description; ?><?php if($desktop_cnt==1) {?><span id="<?php echo "desktop_we_".$work_experience_value['user_id'].$work_experience_value['id'] ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "desktop_we_".$work_experience_value['user_id'].$work_experience_value['id'] ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
									<?php } ?></p>
								<p id="<?php echo "desktop_we_".$work_experience_value['user_id'].$work_experience_value['id'] ?>_moreD" class="moreD">
									<?php echo $desktop_restdescription;?><button onclick="showMoreDescription('<?php echo "desktop_we_".$work_experience_value['user_id'].$work_experience_value['id'] ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
								</p>
							</div>
							<div class="descTxt default_user_description ipad-screen">
							<?php
							$tablet_cnt            =	0;
							$tablet_descLeng	=	strlen($work_experience_value['position_description']);
							if($tablet_descLeng <= $this->config->item('user_profile_work_experience_section_comments_display_minimum_length_character_limit_tablet')) {
							$tablet_description	= htmlspecialchars($work_experience_value['position_description'], ENT_QUOTES);
							} else {
								//$tablet_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_description_display_minimum_length_character_limit_tablet'));
								  $tablet_description	= character_limiter($work_experience_value['position_description'],$this->config->item('user_profile_work_experience_section_comments_display_minimum_length_character_limit_tablet'));
								$tablet_restdescription = nl2br(str_replace("  "," &nbsp;",htmlspecialchars($user_detail['description'], ENT_QUOTES)));
								$tablet_cnt = 1;
							}
							?>
							<p id="<?php echo "tablet_we_".$work_experience_value['user_id'].$work_experience_value['id'] ?>_lessD">
							<?php echo $tablet_description; ?><?php if($tablet_cnt==1) {?><span id="<?php echo "tablet_we_".$work_experience_value['user_id'].$work_experience_value['id'] ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "tablet_we_".$work_experience_value['user_id'].$work_experience_value['id'] ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
							<?php } ?>
							</p>
							<p id="<?php echo "tablet_we_".$work_experience_value['user_id'].$work_experience_value['id'] ?>_moreD" class="moreD">
							<?php echo $tablet_restdescription;?><button onclick="showMoreDescription('<?php echo "tablet_we_".$work_experience_value['user_id'].$work_experience_value['id'] ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
							</p>
						</div>
						    <div class="descTxt default_user_description mobile-screen">
							<?php
								$mobile_cnt            =	0;
								$mobile_descLeng	=	strlen($work_experience_value['position_description']);
								if($mobile_descLeng <= $this->config->item('user_profile_work_experience_section_comments_display_minimum_length_character_limit_mobile')) {
									$mobile_description	= htmlspecialchars($work_experience_value['position_description'], ENT_QUOTES);
								} else {
									//$mobile_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_work_experience_section_comments_display_minimum_length_character_limit_mobile'));
									$mobile_description	= character_limiter($work_experience_value['position_description'],$this->config->item('user_profile_work_experience_section_comments_display_minimum_length_character_limit_mobile'));
									$mobile_restdescription = nl2br(str_replace("  "," &nbsp;",htmlspecialchars($work_experience_value['position_description'], ENT_QUOTES)));
									$mobile_cnt = 1;
								}
							?>
							<p id="<?php echo "mobile_we_".$work_experience_value['user_id'].$work_experience_value['id'] ?>_lessD">
								<?php echo $mobile_description; ?><?php if($mobile_cnt==1) {?><span id="<?php echo "mobile_we_".$work_experience_value['user_id'].$work_experience_value['id'] ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "mobile_we_".$work_experience_value['user_id'].$work_experience_value['id'] ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
								<?php } ?>
							</p>
							<p id="<?php echo "mobile_we_".$work_experience_value['user_id'].$work_experience_value['id'] ?>_moreD" class="moreD">
								<?php echo $mobile_restdescription ;?><button onclick="showMoreDescription('<?php echo "mobile_we_".$work_experience_value['user_id'].$work_experience_value['id'] ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
							</p>
						</div>
					</div>
				<?php
					if($m == count($user_work_experience_data)) { echo '</section>';}
					$work_experience_record_counter++;
					}
					?>
					<?php
					if(count($user_work_experience_data) >$this->config->item('user_profile_page_maximum_work_experience_show') ) {
					?>
					<div class="showmore_category"><label class="AllWorkExperience catAll" onclick="toogleMore('<?php echo $remainingWorkExperience; ?>','work_experience')"><i class="fas fa-angle-down"></i> <?php echo str_replace('{remaining_work_experience}', $remainingWorkExperience, $this->config->item('user_profile_page_show_more_work_experience_text')) ;?></label></div>
					<?php
					}
					?>
				</div>
				<div class="userHeightAdjust"></div>
                <?php
				}
				?>
				
				<!-- Work Experience End -->
				<!-- Education Training End -->
				<?php
				if(!empty($user_education_training_data)){
					$remainingEducationTraining = count($user_education_training_data)-$this->config->item('user_profile_page_maximum_education_training_show');
				
					$total_education_training = count($user_education_training_data);
					$education_training_record_counter = 1;
				?>
				<div class="usrTab Pseasaw_section">
					<div class="bagTAg Pseasaw">
						<p><?php echo  $this->config->item('pa_user_profile_page_education_training') ?></p>
					</div>
					<?php
					$m = 0;
					foreach($user_education_training_data as $education_training_key=>$education_training_value){
					 $m++;
					  if($m == $this->config->item('user_profile_page_maximum_education_training_show')+1) { echo '<input type="hidden" class="moreEducationTraining" value="1"><section id="educationTrainingDiv" class="chkSameLine" style="display:none">'; } 
						$education_training_last_record_class_remove_border_bottom = '';
						if($education_training_record_counter == $total_education_training){
						$education_training_last_record_class_remove_border_bottom = 'default_noborder';
						}
						
						
					?>
					<div class="wkExp default_bottom_border <?php echo $education_training_last_record_class_remove_border_bottom; ?>">
						<div class="wkExp_title"><a href="#"><?php echo htmlspecialchars($education_training_value['education_diploma_degree_name'], ENT_QUOTES); ?></a></div>
						<div class="headline_title"><?php echo htmlspecialchars($education_training_value['education_school_name'], ENT_QUOTES); ?></div>
						<div class="default_user_location">
							<span><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span><small class="<?php echo (!preg_match('/\s/',$education_training_value['education_school_address'])) ? 'street_address_nospace' : ''; ?>"><?php echo htmlspecialchars($education_training_value['education_school_address'], ENT_QUOTES);if(!empty($education_training_value['country_name'])){ echo ","; } ?></small><?php if(!empty($education_training_value['country_name'])){ ?><small><?php echo htmlspecialchars($education_training_value['country_name'], ENT_QUOTES); ?><div class="default_user_location_flag" style="background-image: url('<?php echo ASSETS.'images/countries_flags/'.strtolower($education_training_value['country_code']).'.png'; ?>')"></div></small><?php } ?></div>
						<div class="clearfix"></div>
						<?php if($education_training_value['education_progress'] == '0'){ ?>
						<div class="etDate retrieved_section"><?php echo $this->config->item('personal_account_education_section_graduated_in')." "; 
						if($education_training_value['education_progress'] == '0'){ echo $education_training_value['education_graduate_year']; } else { echo $this->config->item('personal_account_education_section_graduated_in_progress') ; } ?></div> 
						<?php }else{  echo '<div class="etDate retrieved_section">'.$this->config->item('personal_account_education_section_graduated_in_progress').'</div>'; } ?>
						
							<div class="default_user_description desktop-secreen">
								<?php
									$desktop_cnt            =	0;
									$desktop_descLeng	=	strlen($education_training_value['education_comments']);  
									if($desktop_descLeng <= $this->config->item('user_profile_education_training_section_comments_display_minimum_length_character_limit_desktop')) {
									 $desktop_description	= 
									 htmlspecialchars($education_training_value['education_comments'], ENT_QUOTES);
									 } else {
										$desktop_description	= character_limiter($education_training_value['education_comments'],$this->config->item('user_profile_education_training_section_comments_display_minimum_length_character_limit_desktop'));
										$desktop_restdescription = nl2br(str_replace("  "," &nbsp;",htmlspecialchars($education_training_value['education_comments'], ENT_QUOTES)));
										$desktop_cnt = 1;
										
									}
								?>
								<p id="<?php echo "desktop_et_".$education_training_value['user_id'].$education_training_value['id'] ?>_lessD">
									<?php echo $desktop_description; ?><?php if($desktop_cnt==1) {?><span id="<?php echo "desktop_et_".$education_training_value['user_id'].$education_training_value['id'] ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "desktop_et_".$education_training_value['user_id'].$education_training_value['id'] ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
									<?php } ?></p>
								<p id="<?php echo "desktop_et_".$education_training_value['user_id'].$education_training_value['id'] ?>_moreD" class="moreD">
									<?php echo $desktop_restdescription;?><button onclick="showMoreDescription('<?php echo "desktop_et_".$education_training_value['user_id'].$education_training_value['id'] ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
								</p>
							</div>
							<div class="descTxt default_user_description ipad-screen">
							<?php
							$tablet_cnt            =	0;
							$tablet_descLeng	=	strlen($education_training_value['education_comments']);
							if($tablet_descLeng <= $this->config->item('user_profile_education_training_section_comments_display_minimum_length_character_limit_tablet')) {
							$tablet_description	=   htmlspecialchars($education_training_value['education_comments'], ENT_QUOTES);
							} else {
								//$tablet_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_description_display_minimum_length_character_limit_tablet'));
								  $tablet_description	= character_limiter($user_detail['description'],$this->config->item('user_profile_education_training_section_comments_display_minimum_length_character_limit_tablet'));
								$tablet_restdescription = nl2br(str_replace("  "," &nbsp;",htmlspecialchars($user_detail['description'], ENT_QUOTES)));
								$tablet_cnt = 1;
							}
							?>
							<p id="<?php echo "tablet_et_".$education_training_value['user_id'].$education_training_value['id'] ?>_lessD">
							<?php echo $tablet_description; ?><?php if($tablet_cnt==1) {?><span id="<?php echo "tablet_et_".$education_training_value['user_id'].$education_training_value['id'] ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "tablet_et_".$education_training_value['user_id'].$education_training_value['id'] ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
							<?php } ?>
							</p>
							<p id="<?php echo "tablet_et_".$education_training_value['user_id'].$education_training_value['id'] ?>_moreD" class="moreD">
							<?php echo $tablet_restdescription;?><button onclick="showMoreDescription('<?php echo "tablet_et_".$education_training_value['user_id'].$education_training_value['id'] ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
							</p>
						</div>
						   <div class="descTxt default_user_description mobile-screen">
							<?php
								$mobile_cnt            =	0;
								$mobile_descLeng	=	strlen($education_training_value['education_comments']);
								if($mobile_descLeng <= $this->config->item('user_profile_education_training_section_comments_display_minimum_length_character_limit_mobile')) {
									$mobile_description	=   htmlspecialchars($education_training_value['education_comments'], ENT_QUOTES);
								} else {
									//$mobile_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_education_training_section_comments_display_minimum_length_character_limit_mobile'));
									$mobile_description	= character_limiter($education_training_value['education_comments'],$this->config->item('user_profile_education_training_section_comments_display_minimum_length_character_limit_mobile'));
									$mobile_restdescription = nl2br(str_replace("  "," &nbsp;",htmlspecialchars($education_training_value['education_comments'], ENT_QUOTES)));
									$mobile_cnt = 1;
								}
							?>
							<p id="<?php echo "mobile_et_".$education_training_value['user_id'].$education_training_value['id'] ?>_lessD">
								<?php echo $mobile_description; ?><?php if($mobile_cnt==1) {?><span id="<?php echo "mobile_et_".$education_training_value['user_id'].$education_training_value['id'] ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "mobile_et_".$education_training_value['user_id'].$education_training_value['id'] ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
								<?php } ?>
							</p>
							<p id="<?php echo "mobile_et_".$education_training_value['user_id'].$education_training_value['id'] ?>_moreD" class="moreD">
								<?php echo $mobile_restdescription ;?><button onclick="showMoreDescription('<?php echo "mobile_et_".$education_training_value['user_id'].$education_training_value['id'] ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
							</p>
						</div>
					</div>
				<?php
					if($m == count($user_education_training_data)) { echo '</section>';}
					$education_training_record_counter++;
				}
				?>
				<?php
				if(count($user_education_training_data) >$this->config->item('user_profile_page_maximum_education_training_show') ) {
				?>
				<div class="showmore_category"><label class="AllEducationTraining catAll" onclick="toogleMore('<?php echo $remainingEducationTraining; ?>','education_training')"><i class="fas fa-angle-down"></i> <?php echo str_replace('{remaining_education_training}', $remainingEducationTraining, $this->config->item('user_profile_page_show_more_education_training_text')) ;?></label></div>
				<?php
				}
				?>
				</div>
				<div class="userHeightAdjust"></div>
                <?php
				}
				?>
				<!-- Education Training End -->
				<!-- Personal Skills End -->
				<?php if(!empty($language_name) || !empty($user_spoken_languages_data)){ ?>
				<div class="usrTab Plightcherry_section">
					<div class="bagTAg Plightcherry">
						<p><?php echo $this->config->item('pa_user_profile_page_spoken_languages') ?></p>
					</div>
					<?php  
					if(!empty($language_name))
					{
					?>
					<div class="mTongue">
						<label>
							<span><?php echo $this->config->item('pa_user_profile_page_mother_tongue'); ?></span><small><?php echo $language_name['language']; ?></small>
						</label>
					</div>
					<?php } ?>
					<?php
					if(!empty($user_spoken_languages_data)){
					
						$remainingSpokenLanguages = count($user_spoken_languages_data)-$this->config->item('user_profile_page_maximum_spoken_languages_show');
					
					?>
					<div class="othLan">
						<p><span><?php
						if(count($user_spoken_languages_data) > 1){
							echo $this->config->item('pa_user_profile_page_spoken_languages_txt_plural');
						}else{
							echo $this->config->item('pa_user_profile_page_spoken_languages_txt_singular');
						}?></span></p>
					</div>
					<?php
					$m = 0;
					foreach($user_spoken_languages_data as $spoken_language_key=>$spoken_language_value) {
					 $m++;
					 if($m == $this->config->item('user_profile_page_maximum_spoken_languages_show')+1) { echo '<input type="hidden" class="moreSpokenLanguages" value="1"><section id="spokenLanguagesDiv" class="chkSameLine" style="display:none">'; } 
					?>
					<div class="pSkills">
						<div class="row">
							<div class="col-md-3 col-sm-3 col-12 stateDetails">
								<span><?php echo $spoken_language_value['language']; ?></span>
							</div>
							<div class="col-md-9 col-sm-9 col-12 fontSize0 skillLanguage">
								<label><i class="far fa-thumbs-up"></i><b><?php echo $this->config->item('pa_profile_management_spoken_foreign_languages_section_title_understanding'); ?></b><?php echo $spoken_language_value['understanding']; ?></label>
								<label><i class="fa fa-bullhorn" aria-hidden="true"></i><b><?php echo $this->config->item('pa_profile_management_spoken_foreign_languages_section_title_speaking'); ?></b><?php echo $spoken_language_value['speaking']; ?></label>
								<label><i class="fa fa-pencil-square-o" aria-hidden="true"></i><b><?php echo $this->config->item('pa_profile_management_spoken_foreign_languages_section_title_writing'); ?></b><?php echo $spoken_language_value['writing']; ?></label>
							</div>
						</div>
					</div>
					<?php
						if($m == count($user_spoken_languages_data)) { echo '</section>';}
						}
					?>
					<?php
					if(count($user_spoken_languages_data) >$this->config->item('user_profile_page_maximum_spoken_languages_show') ) {
					?>
					<div class="showmore_category"><label class="AllSpokenLanguages catAll" onclick="toogleMore('<?php echo $remainingSpokenLanguages; ?>','spoken_languages')"><i class="fas fa-angle-down"></i> <?php echo str_replace('{remaining_spoken_languages}', $remainingSpokenLanguages, $this->config->item('user_profile_page_show_more_spoken_languages_text')) ;?></label></div>
					<?php
					}
					?>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12">
							<div class="psLevel">
								<label><?php echo $this->config->item('pa_user_profile_page_spoken_languages_levels_description_msg'); ?></label>
							</div>
						</div>
						<!-- <div class="col-md-6 col-sm-6 col-12">
							<div class="psLink">
								<a href="#">Common Curopean Framework of Reference for languages</a>
							</div>
						</div> -->
					</div>
				<?php } ?>
				</div>
				<div class="userHeightAdjust"></div>
				<?php } ?>
				<!-- Personal Skills End -->
				<!-- Certifications Start -->
				<?php
				if(!empty($user_certifications_data)){
					$remainingCertifications = count($user_certifications_data)-$this->config->item('user_profile_page_maximum_certifications_show');
					
					$total_certifications = count($user_certifications_data);
					$certifications_record_counter = 1;
				?>
					<div class="usrTab Plightblue_section">
						<div class="bagTAg Plightblue">
							<p><?php echo  $this->config->item('pa_user_profile_page_certifications') ?></p>
						</div>
						<div class="cerDtls">
						<?php
						$m = 0;
						foreach($user_certifications_data as $certifications_key => $certifications_value){
						$m++;
							 if($m == $this->config->item('user_profile_page_maximum_certifications_show')+1) { echo '<input type="hidden" class="moreCertifications" value="1"><section id="certificationsDiv" class="chkSameLine" style="display:none">'; } 
							$certifications_last_record_class_remove_border_bottom = '';
							if($certifications_record_counter == $total_certifications){
								$certifications_last_record_class_remove_border_bottom = 'default_noborder';
							}
						?>
							<span class="default_bottom_border <?php echo $certifications_last_record_class_remove_border_bottom; ?> user_profile_page_certificates">
								<div class="certiText"><span class="certiSection"><b class="default_black_bold"><?php echo htmlspecialchars($certifications_value['certification_name'], ENT_QUOTES); ?></b></span><span class="acquiredOn"><small class="default_black_regular"><?php echo $this->config->item('pa_user_certifications_section_acquired_on'); ?></small><b class="default_black_bold"><?php echo $calendar_months [$certifications_value['certification_month']]; ?> <?php echo $certifications_value['certification_year']; ?></b></span><div class="clearfix"></div></div>
								<?php
									if(!empty($certifications_value['attachments'])) {
										foreach($certifications_value['attachments'] as $file) {
											$filename = $this->config->item('user_certifications_section_uploaded_attachment_txt');
											$filearr = explode('.', $file['attachment_name']);
											$filename .= '.'.end($filearr);
								?><div class="downloadPdf">
										<?php
											if($this->session->userdata('user')) {
										?><button class="download_certificate_attachments" data-uid="<?php echo Cryptor::doEncrypt($certifications_value['user_id']); ?>" data-id="<?php echo $file['id']; ?>"><i class="fas fa-download"></i> <?php echo $filename; ?></button><?php
											} else {
										?><button class="login_popup" data-page-id-attr = "<?php echo $user_detail['profile_name'] ?>" data-page-type-attr = "<?php echo $current_page; ?>"><i class="fas fa-download"></i> <?php echo $filename; ?></button><?php 
											}
										?></div><?php 
								}
							}
								?></span>                                                    
						<!--<span class="default_bottom_border <?php echo $certifications_last_record_class_remove_border_bottom; ?>"><label><b class="default_black_bold"><?php echo $certifications_value['certification_name']; ?></b><small class="default_black_regular"><?php echo $this->config->item('user_certifications_section_acquired_on'); ?></small></label><label><b class="default_black_bold"><?php echo $calendar_months [$certifications_value['certification_month']]; ?> <?php echo $certifications_value['certification_year']; ?></b></label></span>-->
						<?php
							if($m == count($user_certifications_data)) { echo '</section>';}
							$certifications_record_counter++;
						}
						?>
						
						<?php
						if(count($user_certifications_data) >$this->config->item('user_profile_page_maximum_certifications_show') ) {
						?>
						<div class="showmore_category"><label class="AllCertifications catAll" onclick="toogleMore('<?php echo $remainingCertifications; ?>','certifications')"><i class="fas fa-angle-down"></i> <?php echo str_replace('{certifications}', $remainingCertifications, $this->config->item('user_profile_page_show_more_certifications_text')) ;?></label></div>
						<?php
							}
						?>
						</div>
					</div>
					<div class="userHeightAdjust"></div>
					<?php
					}
					?>
				
				<!-- Certifications End -->
			</div>
		</div>
		<!-- Project Details End -->
	</div>		
	<!-- Left Section Start -->
	<div class="userLeft_Section">
		<div class="fjLeft">
			<div>
				<div class="srcTyp">
					<h4><?php echo $this->config->item('user_profile_page_statistics_as_service_provider');?></h4>
					<?php 
					if($user_detail['hourly_rate']!='' && $user_detail['hourly_rate']!=0){
					?>
					<div class="staPro user_rightSection_bottombdr">				
						<label class="projectText"><?php echo $this->config->item('user_profile_page_sp_statistics_hourly_rate');?></label><label class="projectCost"><?php  echo str_replace(".00","",number_format($user_detail['hourly_rate'], 0, '', ' ')).' '.CURRENCY.$this->config->item('profile_management_user_hourly_rate_per_hour') ;?></label><div class="clearfix"></div>
					</div>
					<?php } ?>
					<div class="staPro">				
						<label><?php echo $this->config->item('user_profile_page_sp_statistics_total_won_projects');?></label><label><?php echo $sp_won_projects_count; ?></label><div class="clearfix"></div>
					</div>
					<div class="staPro">				
						<label><?php echo $this->config->item('user_profile_page_sp_statistics_projects_in_progress');?></label><label><?php echo $sp_in_progress_projects_count; ?></label><div class="clearfix"></div>
					</div>
					<div class="staPro">				
						<label><?php echo $this->config->item('user_profile_page_sp_statistics_total_completed_projects');?></label><label><?php echo $sp_completed_projects_count; ?></label><div class="clearfix"></div>
					</div>
					<div class="staPro">				
						<label><?php echo $this->config->item('user_profile_page_sp_statistics_projects_completed_via_portal');?></label><label><?php echo $sp_completed_projects_count_via_portal; ?></label><div class="clearfix"></div>
					</div>
					<div class="staPro user_rightSection_topbdr">				
						<label><?php echo $this->config->item('user_profile_page_sp_statistics_hires_on_fulltime_projects_via_portal');?></label><label><?php echo $sp_hires_fulltime_projects_count; ?></label><div class="clearfix"></div>
					</div>
				</div>
				<div class="srcTyp">
					<h4><?php echo $this->config->item('user_profile_page_statistics_as_project_owner'); ?></h4>
					<div class="staPro user_rightSection_bottombdr">				
						<label><?php echo $this->config->item('user_profile_page_po_statistics_total_published_listings');?></label><label><?php echo $po_total_posted_projects; ?></label><div class="clearfix"></div>
					</div>
					<div class="staPro">				
						<label><?php echo $this->config->item('user_profile_page_po_statistics_total_published_projects');?></label><label><?php echo $po_published_projects; ?></label><div class="clearfix"></div>
					</div>
					<div class="staPro">				
						<label><?php echo $this->config->item('user_profile_page_po_statistics_projects_in_progress');?></label><label><?php echo $po_in_progress_projects_count; ?></label><div class="clearfix"></div>
					</div>
					<div class="staPro">				
						<label><?php echo $this->config->item('user_profile_page_po_statistics_total_completed_projects');?></label><label><?php echo $po_completed_projects_count; ?></label><div class="clearfix"></div>
					</div>
					<div class="staPro">				
						<label><?php echo $this->config->item('user_profile_page_po_statistics_projects_completed_via_portal');?></label><label><?php echo $po_completed_projects_count_via_portal; ?></label><div class="clearfix"></div>
					</div>
					<div class="staPro big_text_value user_rightSection_topbdr">				
						<label><?php echo $this->config->item('user_profile_page_po_statistics_total_published_fulltime_projects');?></label><label><?php echo $po_published_fulltime_projects_count; ?></label><div class="clearfix"></div>
					</div>
					<div class="staPro">				
						<label><?php echo $this->config->item('user_profile_page_po_statistics_hires_on_fulltime_projects_via_portal');?></label><label><?php echo $get_po_hires_sp_on_fulltime_projects_count; ?></label><div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div>
			<?php
			if(!empty($user_detail['phone_number']) || !empty($user_detail['mobile_phone_number']) || !empty($user_detail['additional_phone_number']) || !empty($user_detail['contact_email']) || !empty($user_detail['skype_id']) || !empty($user_detail['website_url'])){
			?>
				<div class="srcTyp">
					<h4><?php echo $this->config->item('user_profile_page_user_contact_details');?></h4>
					<div class="fontSize0 contactGap_adjust">
						<?php
						if(!empty($user_detail['phone_number'])){
						$phone_number_array = explode("##",$user_detail['phone_number']);	
						?>
						<span class="userPart userPhone">
							<div class="nmAddress">
								<label><span class="oneLine"><i class="fa fa-phone lSd" aria-hidden="true"></i></span><img src="<?php echo URL . 'assets/images/countries_flags/'.$phone_number_array[0].'.png'; ?>"><span class="nextLine"><?php echo $phone_number_array[1]." ".$phone_number_array[2] ?></span></label>
							</div>
						</span>
						<?php
						}
						if(!empty($user_detail['mobile_phone_number'])){
						$mobile_phone_number_array = explode("##",$user_detail['mobile_phone_number']);
						?>
						<span class="userPart">
							<div class="nmAddress">
								<label><span class="oneLine"><i class="fa fa-mobile lSd onlyMob" aria-hidden="true"></i></span><img src="<?php echo URL . 'assets/images/countries_flags/'.$mobile_phone_number_array[0].'.png'; ?>"><span class="nextLine"><?php echo $mobile_phone_number_array[1]." ".$mobile_phone_number_array[2] ?></span></label>
							</div>
						</span>
						<?php
						}
						if(!empty($user_detail['additional_phone_number'])){
						$additional_phone_number_array = explode("##",$user_detail['additional_phone_number']);	
						?>
						<span class="userPart">
							<div class="nmAddress">
								<label><span class="oneLine"><i class="fa fa-fax lSd" aria-hidden="true"></i></span><img src="<?php echo URL . 'assets/images/countries_flags/'.$additional_phone_number_array[0].'.png'; ?>"><span class="nextLine"><?php echo $additional_phone_number_array[1]." ".$additional_phone_number_array[2] ?></span></label>
							</div>
						</span>
						<?php
						}
						if(!empty($user_detail['contact_email'])){
						?>
						<span class="userPart">
							<div class="nmAddress">
								<label><span class="oneLine"><i class="fa fa-envelope lSd" aria-hidden="true"></i></span><span class="nextLine"><?php echo $user_detail['contact_email']; ?></span></label>
							</div>
						</span>
						<?php
						}
						if(!empty($user_detail['skype_id'])){
						?>
						<span class="userPart">
							<div class="nmAddress">
								<label><span class="oneLine"><i class="fa fa-skype lSd" aria-hidden="true"></i></span><span class="nextLine"><?php echo htmlspecialchars($user_detail['skype_id'], ENT_QUOTES); ?></span></label>
							</div>
						</span>
						<?php
						}if(!empty($user_detail['website_url'])){
						?>
						<span class="userPart">
							<div class="nmAddress">
								<label><span class="oneLine"><i class="fa fa-dribbble lSd" aria-hidden="true"></i></span><span class="nextLine"><a href="<?php echo $user_detail['website_url'] ?>" target= "_blank"  style="color:#232323" rel="nofollow"><?php echo $user_detail['website_url'] ?></a></span></label>
							</div>
						</span>
						<?php
						}
						?>
					</div>
				</div>
				<?php
				}
				?>
				 <?php if($address_detail_exists) { ?>
				<div class="srcTypOL">
					<h4><?php echo $this->config->item('pa_user_profile_page_user_address');?></h4><?php echo $address_details; ?>
				</div>
				 <?php } ?>
				<div class="srcTypO">
					<h4><?php echo $this->config->item('pa_user_profile_page_informations');?></h4>
					<div class="staProO">				
						<label><i class="fa fa-clock-o default_green" aria-hidden="true"></i><?php echo $this->config->item('user_profile_page_user_member_since');?> <?php echo date(DATE_FORMAT,strtotime($user_detail['account_validation_date']));?></label><div class="clearfix"></div>
					</div>
					<div class="staProO">				
						<label><i class="fa fa-sign-in default_red" aria-hidden="true"></i><?php echo $this->config->item('user_profile_page_user_last_login');?> <?php echo date(DATE_TIME_FORMAT,strtotime($user_detail['latest_login_date']));?></label><div class="clearfix"></div>
					</div>
					<div class="staProO">			
						<?php 
							$view_txt = '';
							if($profile_views_cnt > 1) {
								if($this->config->item('user_profile_page_user_profile_viewed_times')) {
									$view_txt = $profile_views_cnt.' '.$this->config->item('user_profile_page_user_profile_viewed_times');
								} else {
									$view_txt = $profile_views_cnt;
								}
							} else {
								if($this->config->item('user_profile_page_user_profile_viewed_time')) {
									$view_txt = $profile_views_cnt.' '.$this->config->item('user_profile_page_user_profile_viewed_time');
								} else {
									$view_txt = $profile_views_cnt;
								}
							}
						?>	
						<label><i class="fa fa-eye" aria-hidden="true"></i><?php echo $this->config->item('user_profile_page_user_profile_viewed');?> <?php echo $view_txt;?></label><div class="clearfix"></div>
					</div>
					<?php 
						if($profile_followers_cnt != 0) {
					?>
					<div class="staProO">				
						<label><i class="fas fa-users" aria-hidden="true"></i><?php echo $this->config->item('user_profile_page_user_number_of_followers'); ?> <?php echo $profile_followers_cnt;?></label>
						<div class="clearfix"></div>
					</div>
					<?php
						}
					?>
					<?php 
						if($profile_contacts_cnt != 0) {
					?>
					<div class="staProO">				
						<label><i class="fas fa-address-book" aria-hidden="true"></i><?php echo $this->config->item('user_profile_page_user_number_of_contacts'); ?> <?php echo $profile_contacts_cnt;?></label>
						<div class="clearfix"></div>
					</div>
					<?php
						}
					?>
				</div>				
			</div>				
		</div>
	</div>		
	<!-- Left Section End -->
	<div class="clearfix"></div>
</div>