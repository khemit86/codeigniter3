<?php
$user = $this->session->userdata('user');
$comment = $education_training_value['education_comments'];
$descLeng	=	strlen($comment);
/*----------- description show for desktop screen start----*/
$desktop_cnt            =	0;

$character_limit_desktop = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_comment_character_limit_desktop'):$this->config->item('personal_account_education_section_comment_character_limit_desktop');

$character_limit_tablet = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_comment_character_limit_tablet'):$this->config->item('personal_account_education_section_comment_character_limit_tablet');

$character_limit_mobile = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_comment_character_limit_mobile'):$this->config->item('personal_account_education_section_comment_character_limit_mobile');


if($descLeng <= $character_limit_desktop) {
    $desktop_description	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($comment, ENT_QUOTES))));
} else {
    $desktop_description	= character_limiter($comment,$character_limit_desktop);
    $desktop_restdescription	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($comment, ENT_QUOTES))));
    $desktop_cnt = 1;
}
/*----------- description show for desktop screen end----*/

/*----------- description show for ipad screen start----*/
$tablet_cnt            =	0;
if($descLeng <= $character_limit_tablet) {
    $tablet_description	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($comment, ENT_QUOTES))));
} else {
     $tablet_description	= character_limiter($comment,$character_limit_tablet);
    $tablet_restdescription	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($comment, ENT_QUOTES))));
    $tablet_cnt = 1;
}
/*----------- description show for ipad screen end----*/

/*----------- description show for mobile screen start----*/
$mobile_cnt            =	0;
if($descLeng <= $character_limit_mobile) {
        $mobile_description	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($comment, ENT_QUOTES))));
} else {
    $mobile_description	= character_limiter($comment,$character_limit_mobile);
    $mobile_restdescription	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($comment, ENT_QUOTES))));
    $mobile_cnt = 1;
}
/*----------- description show for mobile screen end----*/
?>
<div class="userLeft_section"><div class="default_user_name"><a class="default_user_name_link" href="#"><?php echo htmlspecialchars($education_training_value['education_diploma_degree_name'], ENT_QUOTES); ?></a></div>
<div class="headline_title"><?php echo htmlspecialchars($education_training_value['education_school_name'], ENT_QUOTES); ?></div></div>
<div class="userRight_section"><button data-uid="<?php echo Cryptor::doEncrypt($education_training_value['user_id']); ?>" data-section-id = "<?php echo $education_training_value['id']; ?>" type="button" class="btn default_icon_red_btn delete_education_confirmation" data-dismiss="modal"><i class="fas fa-trash-alt"></i></button><button type="button" class="btn default_icon_green_btn edit_education_training" data-attr ="edit" data-uid="<?php echo Cryptor::doEncrypt($education_training_value['user_id']); ?>"  data-section-id = "<?php echo $education_training_value['id']; ?>"><i class="fas fa-edit"></i></button></div>
<div class="clearfix"></div>
<div class="default_user_location">
    <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span><small class="<?php echo (!preg_match('/\s/',$education_training_value['education_school_address'])) ? 'street_address_nospace' : ''; ?>"><?php echo htmlspecialchars($education_training_value['education_school_address'], ENT_QUOTES);if(!empty($education_training_value['country_name'])){ echo ","; } ?></small><?php if(!empty($education_training_value['country_name'])){ ?><small><?php echo htmlspecialchars($education_training_value['country_name'], ENT_QUOTES); ?><div class="default_user_location_flag" style="background-image: url('<?php echo ASSETS.'images/countries_flags/'.strtolower($education_training_value['country_code']).'.png'; ?>')"></div></small><?php } ?>     
</div>
<div class="clearfix"></div>
<?php
if($education_training_value['education_progress'] == '0'){	
?>
<div class="etDate"><?php echo (($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_graduated_in'):$this->config->item('personal_account_education_section_graduated_in'))." "; 
if($education_training_value['education_progress'] == '0'){ echo $education_training_value['education_graduate_year']; } else { echo (($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_graduated_in_progress'):$this->config->item('personal_account_education_section_graduated_in_progress')) ; } ?></div> 
<?php }else{  echo '<div class="etDate">'.(($user[0]->is_authorized_physical_person == 'Y') ? $this->config->item('company_account_app_education_section_graduated_in_progress'): $this->config->item('personal_account_education_section_graduated_in_progress')).'</div>'; } ?>
<div class="default_user_description desktop-secreen">
    <p id="desktop_lessD<?php echo $education_training_value['id']; ?>">
        <?php echo $desktop_description;?><?php if($desktop_cnt==1) {?><span id="desktop_dotsD<?php echo $education_training_value['id']; ?>"></span><button onclick="showMoreDescription('desktop', <?php echo $education_training_value['id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
        <?php } ?>
    </p>
    <p id="desktop_moreD<?php echo $education_training_value['id']; ?>" class="moreD">
        <?php echo $desktop_restdescription;?><button onclick="showMoreDescription('desktop', <?php echo $education_training_value['id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
    </p>
</div>
<div class="default_user_description ipad-screen">
    <p id="tablet_lessD<?php echo $education_training_value['id']; ?>">
        <?php echo $tablet_description;?><?php if($tablet_cnt==1) {?><span id="tablet_dotsD<?php echo $education_training_value['id']; ?>"></span><button onclick="showMoreDescription('tablet', <?php echo $education_training_value['id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
        <?php } ?>
    </p>
    <p id="tablet_moreD<?php echo $education_training_value['id']; ?>" class="moreD">
        <?php echo $tablet_restdescription;?><button onclick="showMoreDescription('tablet', <?php echo $education_training_value['id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
    </p>
</div>
<div class="default_user_description mobile-screen">
    <p id="mobile_lessD<?php echo $education_training_value['id']; ?>">
        <?php echo $mobile_description;?><?php if($mobile_cnt==1) {?><span id="mobile_dotsD<?php echo $education_training_value['id']; ?>"></span><button onclick="showMoreDescription('mobile', <?php echo $education_training_value['id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
        <?php } ?>
    </p>
    <p id="mobile_moreD<?php echo $education_training_value['id']; ?>" class="moreD">
        <?php echo $mobile_restdescription;?><button onclick="showMoreDescription('mobile', <?php echo $education_training_value['id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button>
    </p>
</div>
<div class="weMobile">
	<div class="pmAction">
		<button type="button" class="btn default_btn red_btn delete_education_confirmation" data-uid="<?php echo Cryptor::doEncrypt($education_training_value['user_id']); ?>" data-section-id = "<?php echo $education_training_value['id']; ?>"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn edit_education_training" data-attr ="edit" data-uid="<?php echo Cryptor::doEncrypt($education_training_value['user_id']); ?>"  data-section-id = "<?php echo $education_training_value['id']; ?>"><?php echo $this->config->item('edit_btn_txt'); ?></button>
	</div>
</div>