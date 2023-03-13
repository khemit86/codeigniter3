<?php

$portfolio_description = $portfolio_value['description'];
$descLeng	=	strlen($portfolio_description);
/*----------- description show for desktop screen start----*/
$desktop_cnt            =	0;
if($descLeng <= $this->config->item('user_portfolio_section_description_character_limit_desktop')) {
    $desktop_description	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($portfolio_description, ENT_QUOTES))));
} else {
    $desktop_description	= character_limiter($portfolio_description,$this->config->item('user_portfolio_section_description_character_limit_desktop'));
    $desktop_restdescription	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($portfolio_description, ENT_QUOTES))));
    $desktop_cnt = 1;
}
/*----------- description show for desktop screen end----*/

/*----------- description show for ipad screen start----*/
$tablet_cnt            =	0;
if($descLeng <= $this->config->item('user_portfolio_section_description_character_limit_tablet')) {
    $tablet_description	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($portfolio_description, ENT_QUOTES))));
} else {
    $tablet_description	= character_limiter($portfolio_description,$this->config->item('user_portfolio_section_description_character_limit_tablet'));
    $tablet_restdescription	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($portfolio_description, ENT_QUOTES))));
    $tablet_cnt = 1;
}
/*----------- description show for ipad screen end----*/

/*----------- description show for mobile screen start----*/
$mobile_cnt            =	0;
if($descLeng <= $this->config->item('user_portfolio_section_description_character_limit_mobile')) {
        $mobile_description	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($portfolio_description, ENT_QUOTES))));
} else {
    $mobile_description	= character_limiter($portfolio_description,$this->config->item('user_portfolio_section_description_character_limit_mobile'));
    $mobile_restdescription	= nl2br(trim(str_replace("  "," &nbsp;",htmlspecialchars($portfolio_description, ENT_QUOTES))));
    $mobile_cnt = 1;
}
/*----------- description show for mobile screen end----*/
?>

<div class="userLeft_section">
	<div class="default_user_name"><a class="default_user_name_link" data-section-id = "<?php echo $portfolio_value['portfolio_id']; ?>" href="<?php echo site_url ($this->config->item('portfolio_standalone_page_url')."?id=".$portfolio_value['portfolio_id']); ?>" target="_blank"><?php echo htmlspecialchars($portfolio_value['title'], ENT_QUOTES); ?></a></div>
</div>
<div class="userRight_section">
	<button data-uid="<?php echo Cryptor::doEncrypt($portfolio_value['user_id']); ?>" data-section-id = "<?php echo $portfolio_value['portfolio_id']; ?>" type="button" class="btn default_icon_red_btn delete_portfolio_confirmation" data-dismiss="modal"><i class="fas fa-trash-alt"></i></button><button type="button" class="btn default_icon_green_btn edit_portfolio" data-attr ="edit" data-uid="<?php echo Cryptor::doEncrypt($portfolio_value['user_id']); ?>"  data-section-id = "<?php echo $portfolio_value['portfolio_id']; ?>" ><i class="fas fa-edit"></i></button>
</div>
<div class="clearfix"></div>
<div class="default_user_description desktop-secreen">
    <p id="desktop_lessD<?php echo $portfolio_value['id']; ?>">
        <?php echo $desktop_description;?><?php if($desktop_cnt==1) {?><span id="desktop_dotsD<?php echo $portfolio_value['id']; ?>"></span><button onclick="showMoreDescription('desktop', <?php echo $portfolio_value['id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
        <?php } ?></p>
    <p id="desktop_moreD<?php echo $portfolio_value['id']; ?>" class="moreD">
        <?php echo $desktop_restdescription;?><button onclick="showMoreDescription('desktop', <?php echo $portfolio_value['id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button></p>
</div>
<div class="default_user_description ipad-screen">
    <p id="tablet_lessD<?php echo $portfolio_value['id']; ?>">
        <?php echo $tablet_description;?><?php if($tablet_cnt==1) {?><span id="tablet_dotsD<?php echo $portfolio_value['id']; ?>"></span><button onclick="showMoreDescription('tablet', <?php echo $portfolio_value['id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
        <?php } ?></p>
    <p id="tablet_moreD<?php echo $portfolio_value['id']; ?>" class="moreD">
        <?php echo $tablet_restdescription;?><button onclick="showMoreDescription('tablet', <?php echo $portfolio_value['id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button></p>
</div>
<div class="default_user_description mobile-screen">
    <p id="mobile_lessD<?php echo $portfolio_value['id']; ?>">
        <?php echo $mobile_description;?><?php if($mobile_cnt==1) {?><span id="mobile_dotsD<?php echo $portfolio_value['id']; ?>"></span><button onclick="showMoreDescription('mobile', <?php echo $portfolio_value['id']; ?>)"><?php echo $this->config->item('show_more_txt'); ?></button>
        <?php } ?></p>
    <p id="mobile_moreD<?php echo $portfolio_value['id']; ?>" class="moreD">
        <?php echo $mobile_restdescription;?><button onclick="showMoreDescription('mobile', <?php echo $portfolio_value['id']; ?>)"><?php echo $this->config->item('show_less_txt'); ?></button></p>
</div>
<?php
if(!empty($portfolio_value['reference_url'])){	
?>
<div class="reference"><a target="_blank" rel="nofollow" href="<?php echo $portfolio_value['reference_url']; ?>"><?php echo $portfolio_value['reference_url']; ?></a></div> 
<?php
}
?>
<?php
$get_portfolio_images = get_portfolio_images(array('portfolio_id'=>$portfolio_value['portfolio_id']));	
if(!empty($get_portfolio_images)){
?>	
<div id="<?php echo "portfolio_image_container".$portfolio_value['portfolio_id']; ?>">
<?php
echo $this->load->view('user_portfolio_listing_entry_detail_images',array('get_portfolio_images'=>$get_portfolio_images,'portfolio_id'=>$portfolio_value['portfolio_id'],'user_detail'=>$user_detail)); 
?>
<div class="clearfix"></div>
</div>
<?php
}
?>

<?php
$portfolio_tags = get_portfolio_tags(array('portfolio_id'=>$portfolio_value['portfolio_id']));

if(!empty($portfolio_tags)){
?>	
<div class="portTags">
<?php
	foreach($portfolio_tags as $portfolio_key=>$portfolio_tag){
		echo '<label class="defaultTag portfolio_tag_'.$portfolio_tag['id'].'_'.$portfolio_tag['portfolio_id'].'"><span class="tagFirst">'.$portfolio_tag['portfolio_tag_name'].'</span></label>';
	}
?>
</div>
<?php
}
?>
<div class="weMobile">
	<div class="pmAction">
		<button type="button" class="btn default_btn red_btn delete_portfolio_confirmation" data-uid="<?php echo Cryptor::doEncrypt($portfolio_value['user_id']); ?>" data-section-id = "<?php echo $portfolio_value['portfolio_id']; ?>"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn edit_portfolio" data-attr ="edit" data-uid="<?php echo Cryptor::doEncrypt($portfolio_value['user_id']); ?>"  data-section-id = "<?php echo $portfolio_value['portfolio_id']; ?>"><?php echo $this->config->item('edit_btn_txt'); ?></button>
	</div>
</div>