<!-- Open Project Start -->
<?php
$user = $this->session->userdata('user');
if ($this->config->item('dashboard_latest_projects_section_number_of_displayed_listings') != 0 && !empty($open_bidding_latest_projects)) {?>
<div class="default_block_header_transparent nBorder padding_top10">
   <div class="transparent"><?php echo $this->config->item('dashboard_latest_projects_section_heading'); ?><button type="button" id="loadmore_projects" class="btn default_btn blue_btn pull-right" "><?php echo $this->config->item('dashboard_latest_projects_section_refresh_list_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
   </div>
   <div class="clearfix"></div>
   <div class="row transparent_body">
      <?php
		if ($this->config->item('dashboard_latest_projects_section_number_of_displayed_listings') != 0 && !empty($open_bidding_latest_projects)) { ?>
      <div class="col-md-12 col-sm-12 col-12 opL">
         <?php
            if ($this->config->item('dashboard_latest_projects_section_number_of_displayed_listings') != 0 && !empty($open_bidding_latest_projects)) {
                $project_cnt = 1;
                
                foreach ($open_bidding_latest_projects as $key => $left_data) {
                   
                    $featured_max = 0;
            $urgent_max = 0;
            $expiration_featured_upgrade_date_array = array();
            $expiration_urgent_upgrade_date_array = array();
            if(!empty($left_data['featured_upgrade_end_date'])){
            $expiration_featured_upgrade_date_array[] = $left_data['featured_upgrade_end_date'];
            }
            if(!empty($left_data['bonus_featured_upgrade_end_date'])){
            $expiration_featured_upgrade_date_array[] = $left_data['bonus_featured_upgrade_end_date'];
            }
            if(!empty($left_data['membership_include_featured_upgrade_end_date'])){
            $expiration_featured_upgrade_date_array[] = $left_data['membership_include_featured_upgrade_end_date'];
            }
            if(!empty($expiration_featured_upgrade_date_array)){
            $featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
            }
            
            ##########
            
            if(!empty($left_data['urgent_upgrade_end_date'])){
            $expiration_urgent_upgrade_date_array[] = $left_data['urgent_upgrade_end_date'];
            }
            if(!empty($left_data['bonus_urgent_upgrade_end_date'])){
            $expiration_urgent_upgrade_date_array[] = $left_data['bonus_urgent_upgrade_end_date'];
            }
            if(!empty($left_data['membership_include_urgent_upgrade_end_date'])){
            $expiration_urgent_upgrade_date_array[] = $left_data['membership_include_urgent_upgrade_end_date'];
            }
            if(!empty($expiration_urgent_upgrade_date_array)){
            $urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
            }
            
                    $featured_class = '';
                    if ($left_data['featured'] == 'Y' && $featured_max > time()) {
                        $featured_class = 'opBg';
                    }
                    $location = '';
            if($left_data['project_type'] == 'fulltime'){
				$apply_now_button_text = $this->config->item('fulltime_project_apply_now_button_txt');
            }else{
				$apply_now_button_text = $this->config->item('project_apply_now_button_txt');
            }
            
			if (!empty($left_data['county_name'])) {
				if (!empty($left_data['locality_name'])) {
					$location .= $left_data['locality_name'];
				}
				if (!empty($left_data['postal_code'])) {
					$location .= '&nbsp' . $left_data['postal_code'] . ',&nbsp';
				} else if(!empty($left_data['locality_name']) && empty($left_data['postal_code'])) {
					$location .= ',&nbsp';
				}
				$location .= $left_data['county_name'];
			}
            if (!empty($user) && $user[0]->user_id != $left_data['project_owner_id']) { ?>
         <!--<div class="opLBttm <?php echo $featured_class; ?> <?php echo ((count($open_bidding_latest_projects)) == $project_cnt) ? 'bbNo' : '' ?>">-->
		 <div class="opLBttm <?php echo $featured_class; ?>">
            <div class="default_project_title">
               <a class="default_project_title_link" target="_blank" href="<?php echo base_url() . $this->config->item('project_detail_page_url') . '?id=' . $left_data['project_id'] ?>"><?php echo htmlspecialchars($left_data['project_title'], ENT_QUOTES); ?></a>
            </div>
            <label class="default_short_details_field">
            <small><i class="far fa-clock"></i><?php echo $left_data['project_type'] == 'fulltime' ? $this->config->item('fulltime_project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT, strtotime($left_data['project_posting_date'])).'</span>' : $this->config->item('project_posted_on').'<span class="touch_line_break">'.date(DATE_TIME_FORMAT, strtotime($left_data['project_posting_date'])).'</span>'; ?></small><small><i class="fa fa-file-text-o"></i><?php
               if ($left_data['project_type'] == 'fixed') {
               	echo $this->config->item('project_listing_window_snippet_fixed_budget_project');
               } else if ($left_data['project_type'] == 'hourly') {
               	echo $this->config->item('project_listing_window_snippet_hourly_based_budget_project');
               } else if ($left_data['project_type'] == 'fulltime') {
               	echo $this->config->item('project_listing_window_snippet_fulltime_project');
               }
               
               if ($left_data['confidential_dropdown_option_selected'] == 'Y') {
               	if ($left_data['project_type'] == 'fixed') {
               		echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_confidential_option_selected').'</span>';
               	} else if ($left_data['project_type'] == 'hourly') {
               		echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_confidential_option_selected').'</span>';
               	} else if ($left_data['project_type'] == 'fulltime') {
               		echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_confidential_option_selected').'</span>';
               	}
               } else if ($left_data['not_sure_dropdown_option_selected'] == 'Y') {
               	if ($left_data['project_type'] == 'fixed') {
               		echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fixed_budget_project_details_page_budget_not_sure_option_selected').'</span>';
               	} else if ($left_data['project_type'] == 'hourly') {
               		echo '<span class="touch_line_break">'.$this->config->item('displayed_text_hourly_rate_based_project_details_page_budget_not_sure_option_selected').'</span>';
               	} else if ($left_data['project_type'] == 'fulltime') {
               		echo '<span class="touch_line_break">'.$this->config->item('displayed_text_fulltime_project_details_page_salary_not_sure_option_selected').'</span>';
               	}
               } else {
               	$budget_range = '';
               	if ($left_data['max_budget'] != 'All') {
               		if ($left_data['project_type'] == 'hourly') {
               		
               			$budget_range = '';
               			if($this->config->item('post_project_budget_range_between')){
               				$budget_range .= $this->config->item('post_project_budget_range_between');
               			}
               			$budget_range .=  '<span class="touch_line_break">'.number_format($left_data['min_budget'], 0, '', ' ') . '&nbsp;' . CURRENCY . $this->config->item('post_project_budget_per_hour').'</span><span class="touch_line_break">' . $this->config->item('post_project_budget_range_and') .'</span><span class="touch_line_break">' . number_format($left_data['max_budget'], 0, '', ' ') . '&nbsp' . CURRENCY . $this->config->item('post_project_budget_per_hour').'</span>';
               		} else if ($left_data['project_type'] == 'fulltime') {
               			$budget_range = '';
               			if($this->config->item('post_project_budget_range_between')){
               				$budget_range .= $this->config->item('post_project_budget_range_between');
               			}
               			$budget_range .= '<span class="touch_line_break">'.number_format($left_data['min_budget'], 0, '', ' ') . '&nbsp;' . CURRENCY . $this->config->item('post_project_budget_per_month') .'</span><span class="touch_line_break">' . $this->config->item('post_project_budget_range_and') .'</span><span class="touch_line_break">' . number_format($left_data['max_budget'], 0, '', ' ') . '&nbsp' . CURRENCY . $this->config->item('post_project_budget_per_month').'</span>';
               		} else {
               			$budget_range = '';
               			if($this->config->item('post_project_budget_range_between')){
               				$budget_range .= $this->config->item('post_project_budget_range_between');
               			}
               			$budget_range .= '<span class="touch_line_break">'.number_format($left_data['min_budget'], 0, '', ' ') . '&nbsp;' . CURRENCY .'</span><span class="touch_line_break">' . $this->config->item('post_project_budget_range_and') .'</span><span class="touch_line_break">' . number_format($left_data['max_budget'], 0, '', ' ') . '&nbsp' . CURRENCY.'</span>';
               		}
               	} else {
               		if ($left_data['project_type'] == 'hourly') {
               			$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then') . '</span><span class="touch_line_break">' . number_format($left_data['min_budget'], 0, '', ' ') . '&nbsp' . CURRENCY . $this->config->item('post_project_budget_per_hour').'</span>';
               		} else if ($left_data['project_type'] == 'fulltime') {
               			$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then') . '</span><span class="touch_line_break">'. number_format($left_data['min_budget'], 0, '', ' ') . '&nbsp' . CURRENCY . $this->config->item('post_project_budget_per_month').'</span>';
               		} else {
               			$budget_range = '<span class="touch_line_break">'.$this->config->item('post_project_budget_range_more_then') . '</span><span class="touch_line_break">'. number_format($left_data['min_budget'], 0, '', ' ') . '&nbsp' . CURRENCY.'</span>';
               		}
               	}
               	echo $budget_range;
               }
               
               if ($left_data['escrow_payment_method'] == 'Y') {
               	echo '<span class="touch_line_break">'.$this->config->item('find_projects_project_payment_method_escrow_system').'</span>';
               }
               if ($left_data['offline_payment_method'] == 'Y') {
               	echo '<span class="touch_line_break">'.$this->config->item('find_projects_project_payment_method_offline_system').'</span>';
               }
                                     ?></small><?php
               if (!empty($location)) :
                   ?><small><i class="fas fa-map-marker-alt"></i><?= $location ?></small><?php
               endif;
               ?><?php
               if ($left_data['sealed'] == 'N' || ($this->session->userdata('user') && ($user[0]->user_id == $left_data['project_owner_id']))) {
                   ?><small><i class="fas fa-bullhorn"></i><?php
               $project_bid_count = get_project_bid_count($left_data['project_id'], $left_data['project_type']);
               $bid_history_total_bids = $project_bid_count . "&nbsp;";
               if ($left_data['project_type'] == 'fulltime') {
               	if($project_bid_count == 0){
               		$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_0_applications_received');
               	}else if($project_bid_count == 1){
               		$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_1_application_received');
               	}else if($project_bid_count >= 2 && $project_bid_count <= 4){
               		$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_2_to_4_applications_received');
               	}else {
               		$bid_history_total_bids = $bid_history_total_bids . $this->config->item('fulltime_project_description_snippet_bid_history_5_or_more_applications_received');
               	}
               } else {
                  if($project_bid_count == 0){
               		$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_0_bids_received');
               	}else if($project_bid_count == 1){
               		$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_1_bid_received');
               	}else if($project_bid_count >=2 && $project_bid_count <=4){
               		$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_2_to_4_bids_received');
               	}else {
               		$bid_history_total_bids = $bid_history_total_bids . $this->config->item('project_description_snippet_bid_history_5_or_more_bids_received');
               	}
               }
               echo $bid_history_total_bids;
               ?></small><?php
               }
               ?></label>
            <!-- <div class="osu1"> -->
            <?php
               //$description = htmlspecialchars($left_data['project_description'], ENT_QUOTES);
               ?>
            <div class="default_project_description desktop-secreen">
               <p><?php
                  echo character_limiter($left_data['project_description'],$this->config->item('dashboard_latest_projects_section_project_description_character_limit_desktop'));
                  ?></p>
            </div>
            <div class="default_project_description ipad-screen">
               <p><?php
                  echo character_limiter($left_data['project_description'],$this->config->item('dashboard_latest_projects_section_project_description_character_limit_tablet'));
                  ?>
               </p>
            </div>
            <div class="default_project_description mobile-screen">
               <p><?php
                  echo character_limiter($left_data['project_description'],$this->config->item('dashboard_latest_projects_section_project_description_character_limit_mobile'));
                  ?>
               </p>
            </div>
            <!-- </div> -->  
            <?php
               $badgeCount = 0;
               if ($left_data['featured'] == 'Y') {
                       $badgeCount += 1;
               }if ($left_data['urgent'] == 'Y') {
                       $badgeCount += 1;
               }if ($left_data['sealed'] == 'Y') {
                       $badgeCount += 1;
               }
               if (!empty($left_data['categories'])) { ?>
            <div class="row">
               <div class="col-md-12 col-sm-12 col-12 padding0">
                  <div class="default_project_category text-center">
                     <input type="checkbox" class="read-more-state" id="post-<?php echo $left_data['project_id'] ?>"/>
                     <div class="read-more-wrap clearfix text-left">
                        <?php
                           foreach ($left_data['categories'] as $category_key => $category_value) {
                               if ($category_key < $this->config->item('dashboard_latest_projects_categories_show_more_less')) {
                                   if (!empty($category_value['parent_category_name']) && !empty($category_value['category_name'])) {
                           ?>
                        <div class="clearfix catSub12">
                           <small class="pSmnu"><?php echo $category_value['parent_category_name']; ?></small>
                           <a href="#">
                           <span><?php echo $category_value['category_name']; ?></span>
                           </a>
                        </div>
                        <?php
                           } else if (!empty($category_value['category_name'])) {
                               echo '<small>' . $category_value['category_name'] . '</small>';
                           } else if (!empty($category_value['parent_category_name'])) {
                               echo '<small>' . $category_value['parent_category_name'] . '</small>';
                           }
                           } else {
                           if (!empty($category_value['parent_category_name']) && !empty($category_value['category_name'])) {
                           ?>
                        <div class="clearfix catSub12 read-more-target">
                           <small class="pSmnu"><?php echo $category_value['parent_category_name']; ?></small>
                           <a href="#">
                           <span><?php echo $category_value['category_name']; ?></span>
                           </a>
                        </div>
                        <?php } else if (!empty($category_value['category_name'])) { ?>
                        <small class="read-more-target"><?php echo $category_value['category_name']; ?></small>
                        <?php } else if (!empty($category_value['parent_category_name'])) { ?>
                        <small class="read-more-target"><?php echo $category_value['parent_category_name']; ?></small>
                        <?php
                           }
                           }
                           }
                           ?>
                     </div>
                     <?php if (count($left_data['categories']) > ($this->config->item('dashboard_latest_projects_categories_show_more_less'))) { ?>
                     <label for="post-<?php echo $left_data['project_id'] ?>" class="read-more-trigger"></label>
                     <?php
                        }
                        //}
                        ?>	
                  </div>
               </div>
            </div>
            <?php } ?>
            <?php //if (($badgeCount >= 0 && count($left_data['categories']) <= $this->config->item('dashboard_latest_projects_categories_show_more_less')) || ($badgeCount > 1 && count($left_data['categories']) > $this->config->item('dashboard_latest_projects_categories_show_more_less'))) { ?>
            <div class="row social_badge_btn">
               <!-- Only Mobile Version Uses Start -->
			   <?php
			   if(($left_data['featured'] == 'Y' && $featured_max != 0 && $featured_max > time()) || ($left_data['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time()) || $left_data['sealed'] == 'Y'){
			   ?>
               <div class="col-md-12 col-sm-12 col-12 <?php echo $badgeCount > 0 ? 'badgeWidthMob' : 'fontSize0' ?>">
                  <div class="default_project_badge">
                     <?php
                        if ($left_data['featured'] == 'Y' && $featured_max != 0 && $featured_max > time()) {
                      ?>
                     <button type="button" class="btn badge_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></button>
                     <?php
                        }
                        ?>
                     <?php
                        if ($left_data['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time()) {
                      ?>
                     <button type="button" class="btn badge_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></button>
                     <?php
                        }
                        ?>
                     <?php
                        if ($left_data['sealed'] == 'Y') {
                            ?>
                     <button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
                     <?php
                        }
                        ?>
                     <div class="clearfix"></div>
                  </div>
               </div>
			   <?php
			   }
			   ?>
               <!-- Only Mobile Version Uses End -->
               <div class="col-md-2 col-sm-2 col-12 padding0 dbSocial">
                  <div class="default_project_socialicon">
                     <a href="" class="fb_share_project" data-referral-url="<?php echo $left_data['fb_share_url']; ?>"><i class="fa fa-facebook"></i></a>
                     <a href="" class="twitter_share_project" data-referral-url="<?php echo $left_data['twitter_share_url']; ?>" data-title="<?php echo htmlspecialchars($left_data['project_title'], ENT_QUOTES); ?>" data-description="<?php echo get_correct_string_based_on_limit(htmlspecialchars($left_data['project_description'], ENT_QUOTES), $this->config->item('twitter_share_project_description_character_limit')); ?>"><i class="fa fa-twitter"></i></a>
                     <a href="" class="ln_share_project" data-referral-url="<?php echo $left_data['ln_share_url']; ?>" data-title="<?php echo htmlspecialchars($left_data['project_title'], ENT_QUOTES); ?>" data-description="<?php echo get_correct_string_based_on_limit(htmlspecialchars($left_data['project_description'], ENT_QUOTES), $this->config->item('facebook_and_linkedin_share_project_description_character_limit')) ?>"><i class="fa fa-linkedin"></i></a>
                     <a href="" class="email_share_project" data-link="<?php echo $left_data['email_share_url'];?>" data-title="<?php echo htmlspecialchars($left_data['project_title'], ENT_QUOTES);?>" data-description="<?php echo get_correct_string_based_on_limit((htmlspecialchars(nl2br($left_data['project_description']), ENT_QUOTES)), $this->config->item('dashboard_latest_projects_section_email_share_project_description_character_limit'));?>"><i class="fas fa-envelope"></i></a>
                  </div>
               </div>
               <div class="col-md-7 col-sm-7 col-12 badgeWidth">
					<?php
					if(($left_data['featured'] == 'Y' && $featured_max != 0 && $featured_max > time()) || ($left_data['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time()) || $left_data['sealed'] == 'Y'){
					?>
                  <div class="default_project_badge">
                     <?php
                        if ($left_data['featured'] == 'Y' && $featured_max != 0 && $featured_max > time()) {
                        	?>
                     <button type="button" class="btn badge_feature"><?php echo $this->config->item('post_project_page_upgrade_type_featured'); ?></button>
                     <?php
                        }
                        ?>
                     <?php
                        if ($left_data['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time()) {
                            ?>
                     <button type="button" class="btn badge_urgent"><?php echo $this->config->item('post_project_page_upgrade_type_urgent'); ?></button>
                     <?php
                        }
                        ?>
                     <?php
                        if ($left_data['sealed'] == 'Y') {
                            ?>
                     <button type="button" class="btn badge_sealed"><?php echo $this->config->item('post_project_page_upgrade_type_sealed'); ?></button>
                     <?php
                        }
                        ?>
                     <div class="clearfix"></div>
                  </div>
				  <?php
				  }
				  ?>
               </div>
               <div class="col-md-3 col-sm-3 col-12 applyNowRight dbApplyNow">
                  <?php
                     if (!empty($user) && $user[0]->user_id != $left_data['project_owner_id'] && check_sp_active_bid_exists_project($left_data['project_id'], $user[0]->user_id, $left_data['project_type']) == 0) :
                         ?>
                  <div class="default_applyNow_btn">
                     <button type="button" data-attr="<?php echo $left_data['project_id']; ?>" class="btn default_btn blue_btn apply_now_logged_in"><?php echo $apply_now_button_text;?></button>
                  </div>
                  <?php
                     endif;
                     ?>
               </div>
            </div>
            <?php //} ?>
         </div>
         <div class="clearfix"></div>
         <?php
            }
            $project_cnt++;
            }
            } ?>
           
      </div>
      <?php
         } ?>
   </div>
</div>
<?php
}
?>
<!-- Open Project End -->