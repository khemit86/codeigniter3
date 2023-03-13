<!-- <div id="mobTop">
	<div class="mRI <?php //if (isset($current_page) && $current_page != 'dashboard') { echo 'pageMenuSmall'; } ?>">
	<div class="mRI">
		<div id="mobMenu" class="expand"><i class="fas fa-bars"></i></div>
		<div id="mobMenu" class="expand">
			<i class="fas fa-bars"></i>
		</div>
		<div class="mobImg">
			<?php
				if($user_data['user_avatar_exist_status']) {
					$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_data['profile_name'].USER_AVATAR.$user_data['user_avatar'];
				} else {
					if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
						if($user_data['gender'] == 'M'){
							$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
						}if($user_data['gender'] == 'F'){
						   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
						}
					} else {
						if($user_data['is_authorized_physical_person'] == 'Y'){
							if($user_data['gender'] == 'M'){
							$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
							}if($user_data['gender'] == 'F'){
							   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
							}
						
						
						}else{
							$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
						}
					}
				}
			
			?>
			<div id="profile-picture" class="profile-picture default_avatar_image" style="background-image: url('<?php echo $user_profile_picture;?>')"></div>
			<div class="sRate">
				<span><?php echo show_dynamic_rating_stars($user_data['user_total_avg_rating_as_sp']);?></span>
				<span class="default_avatar_review Rating--labeled" data-star_rating="<?php echo $user_data['user_total_avg_rating_as_sp']; ?>"><?php echo $user_data['user_total_avg_rating_as_sp']; ?></span>
			</div>						
			<div class="rvw">
				<span class="default_avatar_total_review">
					<?php
						$totalReviews = $user_data['fulltime_project_user_total_reviews']+$user_data['project_user_total_reviews']; 
						if($totalReviews == 0){
							$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_0_reviews_received');
						}else if($totalReviews == 1) {
							$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_1_review_received');
						} else if($totalReviews > 1) {
							$trGiven = number_format($totalReviews,0, '.', ' ') . ' ' . $this->config->item('user_2_or_more_reviews_received');
						}
						echo $trGiven;?></span>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
	<?php if (isset($current_page) && $current_page != 'dashboard') { ?>
	<div class="pageHeading">
		<?php if (isset($current_page) && $current_page == 'account-management-account-overview') { ?>
		<div class="default_page_heading leftMenu_pageHeading_account_overview">
			<h4><?php echo $this->config->item('account_management_account_overview_page_headline_title'); ?></h4>
		</div>
		<?php } if (isset($current_page) && $current_page == 'account-management-avatar') { ?>
		<div class="default_page_heading leftMenu_pageHeading_avatar">
			<h4><?php echo $this->config->item('account_management_avatar_page_headline_title'); ?></h4>
		</div>
		<?php } ?>	
	</div>
	<?php } ?>
</div> -->