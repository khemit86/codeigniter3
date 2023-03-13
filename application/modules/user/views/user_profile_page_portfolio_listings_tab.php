<?php
$user = $this->session->userdata('user');
if(!empty($user_portfolio_data)){
	?>
<div class="portfolio_list">
<?php
foreach($user_portfolio_data as $portfolio_key => $portfolio_value){
?>
	<div class="portFolio">
		<div class="default_user_name"><a class="default_user_name_link" data-section-id = "<?php echo $portfolio_value['portfolio_id']; ?>" href="<?php echo site_url ($this->config->item('portfolio_standalone_page_url')."?id=".$portfolio_value['portfolio_id']); ?>" target="_blank"><?php echo $portfolio_value['title']; ?></a></div>
		<?php
	/* 	<div class="default_user_description">
			<p><?php echo $portfolio_value['description']; ?></p>
		</div> */
		?>
		<div class="default_user_description desktop-secreen">
			<?php
				$desktop_cnt            =	0;
				$desktop_descLeng	=	strlen($portfolio_value['description']);  
				if($desktop_descLeng <= $this->config->item('user_profile_portfolio_section_description_display_minimum_length_character_limit_desktop')) {
				 $desktop_description	=  htmlspecialchars($portfolio_value['description'], ENT_QUOTES);
				 
				 } else {
					$desktop_description	= character_limiter($portfolio_value['description'],$this->config->item('user_profile_portfolio_section_description_display_minimum_length_character_limit_desktop'));
					$desktop_restdescription = nl2br(str_replace("  "," &nbsp;",htmlspecialchars($portfolio_value['description'], ENT_QUOTES)));
					$desktop_cnt = 1;
				}
			?>
			<p id="<?php echo "desktop_we_".$portfolio_value['portfolio_id'].$portfolio_value['id'] ?>_lessD">
				<?php echo $desktop_description; ?><?php if($desktop_cnt==1) {?><span id="<?php echo "desktop_we_".$portfolio_value['portfolio_id'].$portfolio_value['id'] ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "desktop_we_".$portfolio_value['portfolio_id'].$portfolio_value['id'] ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
				<?php } ?></p><p id="<?php echo "desktop_we_".$portfolio_value['portfolio_id'].$portfolio_value['id'] ?>_moreD" class="moreD">
				<?php echo $desktop_restdescription;?><button onclick="showMoreDescription('<?php echo "desktop_we_".$portfolio_value['portfolio_id'].$portfolio_value['id'] ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
			</p>
		</div>
		<div class="descTxt default_user_description ipad-screen">
			<?php
			$tablet_cnt            =	0;
			$tablet_descLeng	=	strlen($portfolio_value['description']);
			if($tablet_descLeng <= $this->config->item('user_profile_portfolio_section_description_display_minimum_length_character_limit_tablet')) {
			$tablet_description	= htmlspecialchars($portfolio_value['description'], ENT_QUOTES);
			} else {
				//$tablet_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_description_display_minimum_length_character_limit_tablet'));
				  $tablet_description	= character_limiter($portfolio_value['description'],$this->config->item('user_profile_portfolio_section_description_display_minimum_length_character_limit_tablet'));
				$tablet_restdescription = nl2br(str_replace("  "," &nbsp;",htmlspecialchars($portfolio_value['description'], ENT_QUOTES)));
				$tablet_cnt = 1;
			}
			?>
			<p id="<?php echo "tablet_we_".$portfolio_value['portfolio_id'].$portfolio_value['id'] ?>_lessD">
			<?php echo $tablet_description; ?><?php if($tablet_cnt==1) {?><span id="<?php echo "tablet_we_".$portfolio_value['portfolio_id'].$portfolio_value['id'] ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "tablet_we_".$portfolio_value['portfolio_id'].$portfolio_value['id'] ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
			<?php } ?></p><p id="<?php echo "tablet_we_".$portfolio_value['portfolio_id'].$portfolio_value['id'] ?>_moreD" class="moreD">
			<?php echo $tablet_restdescription;?><button onclick="showMoreDescription('<?php echo "tablet_we_".$portfolio_value['portfolio_id'].$portfolio_value['id'] ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
			</p>
		</div>
		 <div class="descTxt default_user_description mobile-screen">
			<?php
				$mobile_cnt            =	0;
				$mobile_descLeng	=	strlen($portfolio_value['description']);
				if($mobile_descLeng <= $this->config->item('user_profile_portfolio_section_description_display_minimum_length_character_limit_mobile')) {
					$mobile_description	= htmlspecialchars($portfolio_value['description'], ENT_QUOTES);
				} else {
					//$mobile_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_portfolio_section_description_display_minimum_length_character_limit_mobile'));
					$mobile_description	= character_limiter($portfolio_value['description'],$this->config->item('user_profile_portfolio_section_description_display_minimum_length_character_limit_mobile'));
					$mobile_restdescription = nl2br(str_replace("  "," &nbsp;",htmlspecialchars($portfolio_value['description'], ENT_QUOTES)));
					$mobile_cnt = 1;
				}
			?>
			<p id="<?php echo "mobile_we_".$portfolio_value['portfolio_id'].$portfolio_value['id'] ?>_lessD">
				<?php echo $mobile_description; ?><?php if($mobile_cnt==1) {?><span id="<?php echo "mobile_we_".$portfolio_value['portfolio_id'].$portfolio_value['id'] ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "mobile_we_".$portfolio_value['portfolio_id'].$portfolio_value['id'] ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
				<?php } ?></p><p id="<?php echo "mobile_we_".$portfolio_value['portfolio_id'].$portfolio_value['id'] ?>_moreD" class="moreD">
				<?php echo $mobile_restdescription ;?><button onclick="showMoreDescription('<?php echo "mobile_we_".$portfolio_value['portfolio_id'].$portfolio_value['id'] ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
			</p>
		</div>
		<?php
		if(!empty($portfolio_value['reference_url'])){
		?>
		<div class="reference"><a href="<?php echo $portfolio_value['reference_url']; ?>" target="_blank" rel="nofollow"><?php echo $portfolio_value['reference_url']; ?><a/></div>	
		<?php
		}
		?>
		<?php
		$get_portfolio_images = get_portfolio_images(array('portfolio_id'=>$portfolio_value['portfolio_id']));
		
		?>
		<?php
		if(!empty($get_portfolio_images)){
		?>
			<div class="portPic">
			<!-- Slider Start -->
			<div class="row">
				<div class="col-md-12 col-sm-12 col-12">
					<div class="container portfolio_listing_container">
						<div class="row">
							<?php foreach($get_portfolio_images as $portfolio_image_key=>$portfolio_image_value){ 
							$thumb_image_name = explode('.',$portfolio_image_value['portfolio_image_name']);
							$thumb_image_path= CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_PORTFOLIO.$portfolio_image_value['portfolio_id'].'/'.$thumb_image_name[0].'_thumb.jpg';
							?>
							<div class="porfolio_slider_width default_uploaded_image_border">
								<div class="portSlider">
									<div class="portSliImg" style="background-image: url('<?php echo $thumb_image_path ?>');"></div>
								</div>
							</div>
							<?php
							}
							?>
						</div>			
					</div>
				</div>
			</div>
			<!-- Slider End -->
			</div>
		<?php
		}
		?>
		<?php
		//}
		?>
		<?php
		$portfolio_tags = get_portfolio_tags(array('portfolio_id'=>$portfolio_value['portfolio_id']));
		if(!empty($portfolio_tags)){
		?>
		<div class="portTags fontSize0">
			<?php
				foreach($portfolio_tags as $portfolio_key=>$portfolio_tag){
					echo '<label class="defaultTag"><span class="tagFirst">'.$portfolio_tag['portfolio_tag_name'].'</span></label>';
				}
			?>
		</div>
		<?php
		}
		?>
		<div class="clearfix"></div>
	</div>
<?php
	}
?>
	</div>
<?php
	if($user_portfolio_count > $this->config->item('user_profile_page_portfolio_tab_limit')){
	?>
	<div class="row">
		<div class="col-md-12 text-center portfolio_viewMore">
			<input type="hidden" id="pageno_porfolio" value="1">
			<button type="button" id="loadmore_portfolio" class="btn default_btn blue_btn" style="height:35px;"><?php echo $this->config->item('load_more_results'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
		</div>
	</div>
<?php
	}
}else{
	if($this->session->userdata('user') && $user[0]->user_id == $user_detail['user_id']){
         $no_data_msg =  $this->config->item('user_profile_page_portfolio_tab_no_data_same_user_view');
	}else{
		
		if(($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')){	
			if($user_detail['gender'] == 'M'){
				if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
					$no_data_msg = $this->config->item('user_profile_page_portfolio_tab_no_data_male_visitor_view');
				}else{
					$no_data_msg = $this->config->item('user_profile_page_portfolio_tab_no_data_company_app_male_visitor_view');
				}
			}else if($user_detail['gender'] == 'F'){
				if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
					$no_data_msg = $this->config->item('user_profile_page_portfolio_tab_no_data_female_visitor_view');
				}else{
					$no_data_msg = $this->config->item('user_profile_page_portfolio_tab_no_data_company_app_female_visitor_view');
				}
			}
			$no_data_msg = str_replace(array('{user_first_name_last_name}'),array($user_detail['first_name']." ".$user_detail['last_name']),$no_data_msg);
		}else{
			$no_data_msg = $this->config->item('user_profile_page_portfolio_tab_no_data_company_visitor_view');
			$no_data_msg = str_replace(array('{user_company_name}'),array($user_detail['company_name']),$no_data_msg);
		}
	}	
	echo '<div class="default_blank_message">'.$no_data_msg.'</div>';

}
?>