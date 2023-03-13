<?php
$user = $this->session->userdata('user');
$calendar_months = 	 $this->config->item('calendar_months');
$position_description = $work_experience_value['position_description'];
$descLeng	=	strlen($position_description);
/*----------- description show for desktop screen start----*/
$desktop_cnt            =	0;


$description_character_limit_desktop = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_description_character_limit_desktop'):$this->config->item('personal_account_work_experience_section_description_character_limit_desktop');


$description_character_limit_tablet = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_description_character_limit_tablet'):$this->config->item('personal_account_work_experience_section_description_character_limit_tablet');

$description_character_limit_mobile = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_description_character_limit_mobile'):$this->config->item('personal_account_work_experience_section_description_character_limit_mobile');


if($descLeng <= $description_character_limit_desktop) {
    $desktop_description	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($position_description, ENT_QUOTES))));
} else {
    $desktop_description	= character_limiter($position_description,$description_character_limit_desktop);
    $desktop_restdescription	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($position_description, ENT_QUOTES))));
    $desktop_cnt = 1;
}
/*----------- description show for desktop screen end----*/

/*----------- description show for ipad screen start----*/
$tablet_cnt            =	0;
if($descLeng <= $description_character_limit_tablet) {
    $tablet_description	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($position_description, ENT_QUOTES))));
} else {
        $tablet_description	= character_limiter($position_description,$description_character_limit_tablet);
    $tablet_restdescription	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($position_description, ENT_QUOTES))));
    $tablet_cnt = 1;
}
/*----------- description show for ipad screen end----*/

/*----------- description show for mobile screen start----*/
$mobile_cnt            =	0;
if($descLeng <= $description_character_limit_mobile) {
        $mobile_description	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($position_description, ENT_QUOTES))));
} else {
    $mobile_description	= character_limiter($position_description,$description_character_limit_mobile);
    $mobile_restdescription	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($position_description, ENT_QUOTES))));
    $mobile_cnt = 1;
}
/*----------- description show for mobile screen end----*/
?>

<div class="userLeft_section"><div class="default_user_name"><a class="default_user_name_link" href="#"><?php echo htmlspecialchars($work_experience_value['position_name'], ENT_QUOTES); ?></a></div>
<div class="headline_title"><?php echo htmlspecialchars($work_experience_value['position_company_name'], ENT_QUOTES); ?></div></div>
<div class="userRight_section"><button data-uid="<?php echo Cryptor::doEncrypt($work_experience_value['user_id']); ?>" data-section-id = "<?php echo $work_experience_value['id']; ?>" type="button" class="btn default_icon_red_btn delete_work_experience_confirmation" data-dismiss="modal"><i class="fas fa-trash-alt"></i></button><button type="button" class="btn default_icon_green_btn edit_work_experience" data-attr ="edit" data-uid="<?php echo Cryptor::doEncrypt($work_experience_value['user_id']); ?>"  data-section-id = "<?php echo $work_experience_value['id']; ?>"><i class="fas fa-edit"></i></button></div>
<div class="clearfix"></div>
<div class="default_user_location">
    <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span><small class="<?php echo (!preg_match('/\s/',$work_experience_value['position_company_address'])) ? 'street_address_nospace' : ''; ?>"><?php echo htmlspecialchars($work_experience_value['position_company_address'], ENT_QUOTES);if(!empty($work_experience_value['country_name'])){ echo ","; } ?></small><?php if(!empty($work_experience_value['country_name'])){ ?><small><?php echo htmlspecialchars($work_experience_value['country_name'], ENT_QUOTES); ?><div class="default_user_location_flag" style="background-image: url('<?php echo ASSETS.'images/countries_flags/'.strtolower($work_experience_value['country_code']).'.png'; ?>')"></div></small><?php } ?>
</div>

<div class="clearfix"></div>
<div class="etDate"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_from')." ".$calendar_months[$work_experience_value['position_from_month']]." ":$this->config->item('personal_account_work_experience_section_from')." ".$calendar_months[$work_experience_value['position_from_month']]." ";
echo $work_experience_value['position_from_year']." "; 
if($work_experience_value['position_still_work'] == 0){	
echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_to')." ":$this->config->item('personal_account_work_experience_section_to')." ";
echo $calendar_months[$work_experience_value['position_to_month']]." ".$work_experience_value['position_to_year'];
}else{
echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_to')." ":$this->config->item('personal_account_work_experience_section_to')." ";
echo $this->config->item('present_txt');
}
$from_year = $work_experience_value['position_from_year'];
$from_month = $work_experience_value['position_from_month'];
if($work_experience_value['position_still_work'] == 1){
	
	$to_year = date('Y');
	$to_month = date('n');
}else{
	$to_year = $work_experience_value['position_to_year'];
	$to_month = $work_experience_value['position_to_month'];
}$user_work_experience_diff  = calculate_user_work_experience($from_year,$from_month,$to_year,$to_month,$work_experience_value['position_still_work']);
if(!empty($user_work_experience_diff)){
	echo " (".$user_work_experience_diff.")";	
	
}?></div><div class="default_user_description desktop-secreen">
    <p id="desktop_lessD<?php echo $work_experience_value['id']; ?>">
        <?php echo $desktop_description;?><?php if($desktop_cnt==1) {?><span id="desktop_dotsD<?php echo $work_experience_value['id']; ?>"></span><button onclick="showMoreDescription('desktop', <?php echo $work_experience_value['id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
        <?php } ?>
    </p>
    <p id="desktop_moreD<?php echo $work_experience_value['id']; ?>" class="moreD">
        <?php echo $desktop_restdescription;?><button onclick="showMoreDescription('desktop', <?php echo $work_experience_value['id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
    </p>
</div>
<div class="default_user_description ipad-screen">
    <p id="tablet_lessD<?php echo $work_experience_value['id']; ?>">
        <?php echo $tablet_description;?><?php if($tablet_cnt==1) {?><span id="tablet_dotsD<?php echo $work_experience_value['id']; ?>"></span><button onclick="showMoreDescription('tablet', <?php echo $work_experience_value['id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
        <?php } ?>
    </p>
    <p id="tablet_moreD<?php echo $work_experience_value['id']; ?>" class="moreD">
        <?php echo $tablet_restdescription;?><button onclick="showMoreDescription('tablet', <?php echo $work_experience_value['id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
    </p>
</div>
<div class="default_user_description mobile-screen">
    <p id="mobile_lessD<?php echo $work_experience_value['id']; ?>">
        <?php echo $mobile_description;?><?php if($mobile_cnt==1) {?><span id="mobile_dotsD<?php echo $work_experience_value['id']; ?>"></span><button onclick="showMoreDescription('mobile', <?php echo $work_experience_value['id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
        <?php } ?>
    </p>
    <p id="mobile_moreD<?php echo $work_experience_value['id']; ?>" class="moreD">
        <?php echo $mobile_restdescription;?><button onclick="showMoreDescription('mobile', <?php echo $work_experience_value['id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
    </p>
</div>
<div class="weMobile">
	<div class="pmAction">
		<button type="button" class="btn default_btn red_btn delete_work_experience_confirmation" data-uid="<?php echo Cryptor::doEncrypt($work_experience_value['user_id']); ?>" data-section-id = "<?php echo $work_experience_value['id']; ?>"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn edit_work_experience"  data-attr ="edit" data-uid="<?php echo Cryptor::doEncrypt($work_experience_value['user_id']); ?>"  data-section-id = "<?php echo $work_experience_value['id']; ?>"><?php echo $this->config->item('edit_btn_txt'); ?></button>
	</div>
</div>