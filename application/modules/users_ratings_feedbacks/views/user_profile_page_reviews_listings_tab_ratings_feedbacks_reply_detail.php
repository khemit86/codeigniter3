<!-- <strong><?php //echo $this->config->item('user_profile_page_users_ratings_feedbacks_reply_txt'); ?></strong> -->
<div class="bgDescText">
	<div class="descTxt default_user_description desktop-secreen">
		<?php
			$desktop_cnt            =	0;
			$desktop_descLeng	=	strlen($feedback_reply);  
			if($desktop_descLeng <= $this->config->item('user_profile_page_feedbacks_section_reply_display_minimum_length_character_limit_desktop')) {
			 $desktop_description	= nl2br(htmlspecialchars($feedback_reply, ENT_QUOTES));
			} else {
				$desktop_description	= character_limiter(nl2br($feedback_reply),$this->config->item('user_profile_page_feedbacks_section_reply_display_minimum_length_character_limit_desktop'));
				$desktop_restdescription = nl2br(htmlspecialchars($feedback_reply, ENT_QUOTES));
				$desktop_cnt = 1;
				}
		?>
		<p id="<?php echo "desktop_reply_".$section_id ?>_lessD">
			<?php echo $desktop_description; ?><?php if($desktop_cnt==1) {?><span id="<?php echo "desktop_reply_".$section_id ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "desktop_reply_".$section_id ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
			<?php } ?>
		</p>
		<p id="<?php echo "desktop_reply_".$section_id ?>_moreD" class="moreD">
			<?php echo $desktop_restdescription;?><button onclick="showMoreDescription('<?php echo "desktop_reply_".$section_id ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
		</p>
	</div>
	<div class="descTxt default_user_description ipad-screen">
		<?php
		$tablet_cnt            =	0;
		$tablet_descLeng	=	strlen($feedback_reply);
		if($tablet_descLeng <= $this->config->item('user_profile_page_feedbacks_section_reply_display_minimum_length_character_limit_tablet')) {
		$tablet_description	= nl2br(htmlspecialchars($feedback_reply, ENT_QUOTES));
		} else {
			//$tablet_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_page_feedbacks_section_reply_display_minimum_length_character_limit_tablet'));
			  $tablet_description	= character_limiter(nl2br($feedback_reply),$this->config->item('user_profile_page_feedbacks_section_reply_display_minimum_length_character_limit_tablet'));
			$tablet_restdescription = nl2br(htmlspecialchars($feedback_reply, ENT_QUOTES));
			$tablet_cnt = 1;
		}
		?>
		<p id="<?php echo "tablet_reply_".$section_id ?>_lessD">
		<?php echo $tablet_description; ?><?php if($tablet_cnt==1) {?><span id="<?php echo "tablet_reply_".$section_id ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "tablet_reply_".$section_id ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
		<?php } ?>
		</p>
		<p id="<?php echo "tablet_reply_".$section_id ?>_moreD" class="moreD">
		<?php echo $tablet_restdescription;?><button onclick="showMoreDescription('<?php echo "tablet_reply_".$section_id ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
		</p>
	</div>
	<div class="descTxt default_user_description mobile-screen">
		<?php
			$mobile_cnt            =	0;
			$mobile_descLeng	=	strlen($feedback_reply);
			if($mobile_descLeng <= $this->config->item('user_profile_page_feedbacks_section_reply_display_minimum_length_character_limit_mobile')) {
				$mobile_description	= nl2br(htmlspecialchars($feedback_reply, ENT_QUOTES));
			} else {
				//$mobile_description	= substr(nl2br(htmlspecialchars($user_detail['description'], ENT_QUOTES)),0,$this->config->item('user_profile_page_feedbacks_section_reply_display_minimum_length_character_limit_mobile'));
				$mobile_description	= character_limiter(nl2br($feedback_reply),$this->config->item('user_profile_page_feedbacks_section_reply_display_minimum_length_character_limit_mobile'));
				$mobile_restdescription = nl2br(htmlspecialchars($feedback_reply, ENT_QUOTES));
				$mobile_cnt = 1;
			}
		?>
		<p id="<?php echo "mobile_reply_".$section_id ?>_lessD">
			<?php echo $mobile_description; ?><?php if($mobile_cnt==1) {?><span id="<?php echo "mobile_reply_".$section_id ?>_dotsD"></span><button onclick="showMoreDescription('<?php echo "mobile_reply_".$section_id ?>')"><?php echo $this->config->item('show_more_txt'); ?></button>
			<?php } ?>
		</p>
		<p id="<?php echo "mobile_reply_".$section_id ?>_moreD" class="moreD">
			<?php echo $mobile_restdescription ;?><button onclick="showMoreDescription('<?php echo "mobile_reply_".$section_id ?>')"><?php echo $this->config->item('show_less_txt'); ?></button>
		</p>
	</div>



	<div class="replyTime"><strong><?php echo str_replace(array('{user_first_name_last_name_or_company_name}'),array($reply_by_user),$this->config->item('user_profile_page_users_ratings_feedbacks_reply_on_txt')); ?></strong><?php echo date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_reply_on_date));?></div>
</div>