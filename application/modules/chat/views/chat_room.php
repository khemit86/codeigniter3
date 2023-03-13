<div class="dashTop">	
    <div class="chatLtRt">
        <div class="chat_room">
            <!-- html from chat_no_record.php   Start -->
            <div class="divMiddle <?php echo!empty($user_contacts_list) ? 'd-none' : ''; ?>" id="chatroom_no_data">
                <div class="default_blank_message_block">
                    <i class="far fa-bell"></i>
                    <?php echo $this->config->item('dashboard_contacts_list_no_record'); ?>
                </div>
            </div>
            <!-- End -->
            <!-- Html from [chat_initial.php / chat_record / chat_signle_record]  Start -->
            <div class="noBigger <?php echo empty($user_contacts_list) ? 'd-none' : ''; ?>" id="chatroom_data">
			
                <div class="chatMesg">
				
				<div>
                                    <div class="chatResponsive">
                                        <div class="receive_notification expand_notification_area"><a class="rcv_notfy_btn" id="showMoreLeftChatRoomList"><?php echo $this->config->item('chat_room_contacts_list_show_more_text'); ?></a><span class="chatMesgCounter default_counter_notification_red" style="display:none">2</span>
                                        <input type="hidden" id="moreReview" value="1"></div>
                                    </div>		
                                    <div class="clearfix"></div>
									
										
				
                    <div class="row">
                        <!-- Collapse Section Start -->
						
                        <div class="col-md-2 col-sm-2 col-12 srcRight chatSecLeft searchBarheight" id="rcv_notfy">
                            <div class="searchBar">
                                <div class="input-group">
                                    <input id="chat_room_search" type="text" class="form-control default_input_field" data-role="tagsinput" placeholder="<?php echo $this->config->item('chat_room_search_keyword_placeholder'); ?>">
                                    <div class="input-group-prepend">
                                        <button class="btn" type="button">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <div class="msegPTop">
                                    <div class="mesgDisplay1">
                                        <div class="search_filter_wrapper d-none"></div>
                                        <div class="default_wrapper">
                                        <?php
                                            // set user timezone to get date in his localtime
                                            date_default_timezone_set($this->session->userdata('user_timezone'));
                                            if(!empty($user_contacts_list)) {
                                                foreach ($user_contacts_list as $key => $arr) {
                                                    $general_chat = [];
                                                    $general_channel = array_column($arr, 'project_id');
                                                    if(in_array(0, $general_channel)) {
                                                        if(count($general_channel) == 1) {
                                                            $general_chat = $arr[0];
                                                        } else {
                                                            $index = array_search(0, $general_channel);
                                                            if (array_key_exists($index, $general_channel)) {
                                                                $general_chat = $arr[$index];
                                                                unset($arr[$index]);
                                                            }
                                                        }
                                                        
                                                    }                                             
                                                    if (count($arr) == 1) {
                                                        foreach ($arr as $val) {
                                                            $is_active = ($user_data['user_id'] != $val['latest_message_sender_id'] && $val['unread_msg_count'] != 0) ? 'active' : '';
                                                            if (!empty($general_chat)) {
                                                                if ($is_active == '') {
                                                                    $is_active = ($user_data['user_id'] != $general_chat['latest_message_sender_id'] && $general_chat['unread_msg_count'] != 0) ? 'active' : '';
                                                                }
                                                            }
                                                            ?>
                                                            <div class="chatSingle <?php echo $is_active; ?>" data-id="<?php echo $val['user_id']; ?>">
                                                                <?php
                                                                    if (!empty($general_chat)) {
                                                                                                                        ?>
                                                                    <div class="media chat_room_user_contact generalChatBgHover" style="cursor:pointer;pointer-events:auto;"
                                                                                                                                        data-profile-name="<?php echo $general_chat['profile_name'] ?>"
                                                                                                                                        data-name="<?php echo $general_chat['user_name']; ?>"
                                                                                                                                        data-id="<?php echo $general_chat['user_id']; ?>"
                                                                                                                                        data-project-title=""
                                                                                                                                        data-project-id="0"
                                                                                                                                        data-profile="<?php echo $general_chat['profile_pic_url']; ?>"
                                                                                                                                        data-project-owner=""
                                                                                                                                >											
                                                                                                                        <?php
                                                                                                                                } else {
                                                                                                                        ?>
                                                                                                                                <div class="media chat_room_user_contact generalChatBgHover" style="cursor:pointer;pointer-events:auto;"
                                                                                                                                    data-profile-name="<?php echo $val['profile_name'] ?>"
                                                                                                                                    data-name="<?php echo $val['user_name']; ?>"
                                                                                                                                    data-id="<?php echo $val['user_id']; ?>"
                                                                                                                                    data-project-title=""
                                                                                                                                    data-project-id="0"
                                                                                                                                    data-profile="<?php echo $val['profile_pic_url']; ?>"
                                                                                                                                    data-project-owner="">
                                                                                                                        <?php
                                                                                                                                }
                                                                                                                        ?>		
                                                                        <div class="rChat default_avatar_image" style="background-image: url('<?php echo $val['profile_pic_url'] ?>');"></div>	
                                                                        <div class="media-body">
                                                                            <div class="cLft">
                                                                                <div class="default_user_name">
                                                                                    <a class="default_user_name_link"><?php echo $val['user_name'] ?></a>
                                                                                    <?php
                                                                                    if (!empty($general_chat)) {
                                                                                        ?>
                                                                                        <div class="mRDT  <?php echo $general_chat['unread_msg_count'] != 0 ? 'active' : ''; ?>">
                                                                                            <?php
                                                                                                if(empty($general_chat['latest_message_sent_timestamp'])) {
                                                                                            ?>
                                                                                            <span><?php echo !empty($general_chat['latest_message_sent_time']) ?  date(DATE_TIME_FORMAT_EXCLUDE_SECOND, strtotime($general_chat['latest_message_sent_time'])) : '' ?></span>			
                                                                                            <?php
                                                                                                } else {
                                                                                            ?>
                                                                                            <span><?php echo $general_chat['display_latest_message_sent_time']; ?></span>
                                                                                            <?php
                                                                                                }
                                                                                            ?>
                                                                                            <small class="chat_count default_counter_notification_red <?php echo ($user_data['user_id'] == $general_chat['latest_message_sender_id'] || $general_chat['unread_msg_count'] == 0) ? 'd-none' : '' ?>"><?php echo $general_chat['unread_msg_count']; ?></small>
                                                                                            <div class="clearfix"></div>
                                                                                        </div>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                                                                                        </div>
                                                                    </div>
                                                                    <?php 
                                                                        if($val['id'] != $general_chat['id']) {
                                                                    ?>
                                                                    <div class="headDTCount chatBgHover chat_room_user_contact" style="cursor:pointer;pointer-events:auto;"
                                                                        data-profile-name="<?php echo $val['profile_name'] ?>"
                                                                        data-name="<?php echo $val['user_name']; ?>"
                                                                        data-id="<?php echo $val['user_id']; ?>"
                                                                        data-project-title="<?php echo htmlspecialchars($val['project_detail']['project_title'], ENT_QUOTES); ?>"
                                                                        data-project-id="<?php echo $val['project_detail']['project_id']; ?>"
                                                                        data-profile="<?php echo $val['profile_pic_url']; ?>"
                                                                        data-project-owner="<?php echo $val['project_detail']['project_owner_id']; ?>">
                                                                        <div class="headline_title txt_color_black"><span><?php echo !empty($val['project_detail']) ? $val['project_detail']['project_title'] : $this->config->item('chat_room_dashboard_general_chat_label_text'); ?></span></div>
                                                                        <div class="cRgt">												
                                                                            <div class="mRDT  <?php echo $val['unread_msg_count'] != 0 ? 'active' : ''; ?>">
                                                                                <?php 
                                                                                    if(empty($val['latest_message_sent_timestamp'])) {
                                                                                ?>
                                                                                <span><?php echo !empty($val['latest_message_sent_time']) ? date(DATE_TIME_FORMAT_EXCLUDE_SECOND, strtotime($val['latest_message_sent_time'])) : '' ?></span>			
                                                                                <?php
                                                                                    } else {
                                                                                ?>
                                                                                <span><?php echo $val['display_latest_message_sent_time']; ?></span>
                                                                                <?php
                                                                                    }
                                                                                ?>
                                                                                <small class="chat_count default_counter_notification_red <?php echo ($user_data['user_id'] == $val['latest_message_sender_id'] || $val['unread_msg_count'] == 0) ? 'd-none' : '' ?>"><?php echo $val['unread_msg_count']; ?></small>
                                                                                <div class="clearfix"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                    <?php 
                                                                        }
                                                                    ?>
                                                                </div>

                                                                <?php
                                                        }
                                                    } else {
                                                            $curr = current($arr);
                                                            $grand_total = array_sum(array_column($arr, 'unread_msg_count'));
                                                            if (!empty($general_chat)) {
                                                                $grand_total += $general_chat['unread_msg_count'];
                                                            }
                                                            ?>
                                                            <!-- When Multiple Chat Record Start -->
                                                            <div class="chatMultiple <?php echo $grand_total != 0 ? 'active' : ''; ?>" data-id="<?php echo $curr['user_id']; ?>">
                                                                <?php
                                                                    if (empty($general_chat)) {
                                                                                                                        ?>
                                                                    <div class="media chat_room_user_contact generalChatBgHover" style="cursor:pointer;pointer-events:auto;"
                                                                                                                                    data-profile-name="<?php echo $curr['profile_name'] ?>"
                                                                                                                                    data-name="<?php echo $curr['user_name']; ?>"
                                                                                                                                    data-id="<?php echo $curr['user_id']; ?>"
                                                                                                                                    data-project-title=""
                                                                                                                                    data-project-id="0"
                                                                                                                                    data-profile="<?php echo $curr['profile_pic_url']; ?>"
                                                                                                                                    data-project-owner=""
                                                                                                                                >
                                                                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <div class="media chat_room_user_contact generalChatBgHover" style="cursor:pointer;pointer-events:auto;"
                                                                                                                                            data-profile-name="<?php echo $general_chat['profile_name'] ?>"
                                                                                                                                            data-name="<?php echo $general_chat['user_name']; ?>"
                                                                                                                                            data-id="<?php echo $general_chat['user_id']; ?>"
                                                                                                                                            data-project-title=""
                                                                                                                                            data-project-id="0"
                                                                                                                                            data-profile="<?php echo $general_chat['profile_pic_url']; ?>"
                                                                                                                                            data-project-owner=""
                                                                                                                                        >
                                                                <?php
                                                                    }
                                                                ?>
                                                                        <div class="rChat default_avatar_image" style="background-image: url('<?php echo $curr['profile_pic_url'] ?>');"></div>	
                                                                        <div class="media-body">	
                                                                            <div class="cLft">
                                                                                <div class="default_user_name">
                                                                                    <a class="default_user_name_link"><?php echo $curr['user_name']; ?></a>
                                                                                    <?php
                                                                                    if (!empty($general_chat)) {
                                                                                        ?>
                                                                                        <small class="chat_count default_counter_notification_red mt-1 <?php echo ($user_data['user_id'] == $general_chat['latest_message_sender_id'] || $general_chat['unread_msg_count'] == 0) ? 'd-none' : '' ?>"><?php echo $general_chat['unread_msg_count']; ?></small>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                                <div class="mRDT  <?php echo $grand_total != 0 ? 'active' : '' ?>">
                                                                                    <?php
                                                                                    if(empty($general_chat['latest_message_sent_timestamp'])) {
                                                                                        $latest_date = !empty($curr['latest_message_sent_time']) ? strtotime($curr['latest_message_sent_time']) : '';
                                                                                        if (!empty($general_chat)) {
                                                                                            if(!empty($latest_date)) {
                                                                                                $latest_date = strtotime($general_chat['latest_message_sent_time']) > strtotime($curr['latest_message_sent_time']) ? strtotime($general_chat['latest_message_sent_time']) : strtotime($curr['latest_message_sent_time']);
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                    <span><?php echo !empty($latest_date) ? date(DATE_TIME_FORMAT_EXCLUDE_SECOND, $latest_date) : '' ?></span>
                                                                                    <?php
                                                                                        } else {
                                                                                            $latest_date = !empty($curr['latest_message_sent_timestamp']) ? $curr['latest_message_sent_timestamp'] : '';
                                                                                            if (!empty($general_chat)) {
                                                                                                if(!empty($latest_date)) {
                                                                                                    $latest_date = $general_chat['latest_message_sent_timestamp'] > $curr['latest_message_sent_timestamp'] ? $general_chat['latest_message_sent_timestamp'] : $curr['latest_message_sent_timestamp'];
                                                                                                }
                                                                                            }
                                                                                    ?>
                                                                                    <span><?php echo !empty($latest_date) ? date(DATE_TIME_FORMAT_EXCLUDE_SECOND, $latest_date) : '' ?></span>
                                                                                    <?php        
                                                                                        }
                                                                                    ?>
                                                                                    <small class="grand_total default_counter_notification_red <?php echo $grand_total == 0 ? 'd-none' : '' ?>"><?php echo $grand_total; ?></small>
                                                                                    <div class="clearfix"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="default_small_square_block_header">
                                                                        <div class="chatMulti">
                                                                            <div class="squareHeader">
                                                                                <!-- <h3>
                                                                                    <span><i class="fa fa-angle-down"></i></span>
                                                                                </h3> -->
                                                                            </div>
                                                                            <div class="amUDBody">
                                                                                <?php
                                                                                foreach ($arr as $val) {
                                                                                    ?>
                                                                                    <div class="headDTCount chatBgHover chat_room_user_contact <?php echo empty($val['project_detail']) ? 'd-none' : '' ?>"
                                                                                        data-profile-name="<?php echo $val['profile_name'] ?>"
                                                                                        data-name="<?php echo $val['user_name']; ?>"
                                                                                        data-id="<?php echo $val['user_id']; ?>"
                                                                                        data-project-title="<?php echo htmlspecialchars($val['project_detail']['project_title'], ENT_QUOTES); ?>"
                                                                                        data-project-id="<?php echo $val['project_detail']['project_id']; ?>"
                                                                                        data-profile="<?php echo $val['profile_pic_url']; ?>"
                                                                                        data-project-owner="<?php echo $val['project_detail']['project_owner_id']; ?>"
                                                                                        >
                                                                                        <div class="headline_title txt_color_black"><span><?php echo!empty($val['project_detail']) ? $val['project_detail']['project_title'] : $this->config->item('chat_room_dashboard_general_chat_label_text'); ?></span></div>
                                                                                        <div class="cRgt">												
                                                                                            <div class="mRDT  <?php echo $val['unread_msg_count'] != 0 ? 'active' : '' ?>">
                                                                                                <?php
                                                                                                    if(empty($val['latest_message_sent_timestamp'])) {
                                                                                                ?>
                                                                                                <span><?php echo !empty($val['latest_message_sent_time']) ? date(DATE_TIME_FORMAT_EXCLUDE_SECOND, strtotime($val['latest_message_sent_time'])) : '' ?></span>			
                                                                                                <?php
                                                                                                    } else {
                                                                                                ?>
                                                                                                <span><?php echo $val['display_latest_message_sent_time'];?></span>
                                                                                                <?php
                                                                                                    }
                                                                                                ?>
                                                                                                <small class="chat_count default_counter_notification_red <?php echo ($user_data['user_id'] == $val['latest_message_sender_id'] || $val['unread_msg_count'] == 0) ? 'd-none' : '' ?>"><?php echo $val['unread_msg_count']; ?></small>
                                                                                                <div class="clearfix"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                                if (!empty($general_chat)) {
                                                                                    $val = $general_chat;
                                                                                    ?>
                                                                                    <div class="headDTCount chatBgHover chat_room_user_contact d-none"
                                                                                        data-project-id="0"
                                                                                        >
                                                                                        <div class="headline_title txt_color_black"><span><?php echo!empty($val['project_detail']) ? $val['project_detail']['project_title'] : $this->config->item('chat_room_dashboard_general_chat_label_text'); ?></span></div>
                                                                                        <div class="cRgt">											
                                                                                            <div class="mRDT  <?php echo $val['unread_msg_count'] != 0 ? 'active' : '' ?>">
                                                                                                <?php
                                                                                                    if(empty($val['latest_message_sent_timestamp'])){
                                                                                                ?>
                                                                                                <span><?php echo !empty($val['latest_message_sent_time']) ? date(DATE_TIME_FORMAT_EXCLUDE_SECOND, strtotime($val['latest_message_sent_time'])) : '' ?></span>			
                                                                                                <?php
                                                                                                    } else {
                                                                                                ?>
                                                                                                <span><?php echo $val['display_latest_message_sent_time']; ?></span>
                                                                                                <?php
                                                                                                    }
                                                                                                ?>
                                                                                                <small class="chat_count default_counter_notification_red <?php echo ($user_data['user_id'] == $val['latest_message_sender_id'] || $val['unread_msg_count'] == 0) ? 'd-none' : '' ?>"><?php echo $val['unread_msg_count']; ?></small>
                                                                                                <div class="clearfix"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                                <div class="clearfix"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                    }
                                                }
                                            }
                                        ?>	
                                        </div>								
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!--</div>
                                
                        </div>-->

								
                                <div class="col-md-10 col-sm-10 col-12 chatSecRight d-none">
								
                                    
                                    <div class="row userDetails">
                                        <div class="col-md-9 col-sm-9 col-12">
                                            <div class="textBar topAdjust">
                                                <div class="default_user_name">
                                                    <a href="#" target="_blank" class="default_user_name_link">Bessie Berry</a>
                                                </div>
                                                <div class="headline_title"><a href="#" target="_blank">Contrary to popular belief.</a></div>
                                                <!-- <h3>Bessie Berry<i class="fa fa-circle online" aria-hidden="true"></i></h3>
                                                <p>Contrary to popular belief,Lorem Ipsum is not simply random text.It has roots in a piece of classical</p> -->
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-12 leftP0">
                                            <div class="chatBlock">
                                                <button class="btn default_btn red_btn btn-block block_btn" type="button" data-toggle="modal" data-target="#userBlockModal"><?php echo $this->config->item('chat_room_page_block_contact_btn_txt');?></button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mesgDisplay drop_zone default_headline_gap">
                                        <div id="overlay" class="d-none">
                                            <div class="text"><?php echo $this->config->item('drop_zone_drag_and_drop_files_area_message_txt'); ?></div>
                                        </div>
                                        <div class="rightDisplay">
											<div class="mesgDisplay2">
												<div>
													<div class="mesgChat">							
														<div class="imgTwo d-none">								
															<div class="twoImage default_avatar_image" style="background-image: url('<?php echo URL ?>assets/images/chat/rChat2.png');" alt="Chat Image"></div>
															<div class="twoImage default_avatar_image" style="background-image: url('<?php echo URL ?>assets/images/chat/rChat3.png');" alt="Chat Image"></div>
														</div>
														<div class="message-wrapper"></div>
													</div>
													<div id="msgSection">
														<!-- Bottom Attachment Start -->
														<div class="bottomAttachment">
															
														</div>
														<!-- Bottom Attachment End -->
														<div class="default_error_red_message ml-2 d-none"></div>
														<div class="default_success_green_message ml-2 d-none"></div>
													</div>
												</div>
											</div>
                                        </div>
                                        <!-- Bottom Chat Box Start -->
                                        <div class="mesgSendBox">
                                            <div class="mesgFixed" id="contact-form">

                                                <div class="input-group auto-resize-div">
                                                    <textarea id="message" class="form-control default_textarea_field chat_room_chat_text" tabindex="1" placeholder="<?php echo $this->config->item('chat_default_type_here_message_placeholder_txt'); ?>"></textarea>
                                                    <span class="input-group-btn default_chat_action_btn">
                                                        <input type="file" class="imgupload file_upload" accept="<?php echo $this->config->item('plugin_chat_attachment_allowed_file_extensions'); ?>" style="display:none"/>
                                                        <button class="OpenImgUpload btn green_btn"><i class="fa fa-file-text" aria-hidden="true"></i></button>
                                                        <button class="btn blue_btn default_btn chat_room_send_message" tabindex="2" type="button"><span><?php echo $this->config->item('send_btn_txt');?></span></button>
                                                    </span>
                                                </div>
                                                <button type="button" class="btn bProject d-none"><?php echo $this->config->item('accept_btn_txt');?></button>
                                                <button type="button" class="btn bSProvider d-none">Block</button>
                                                <div class="clearfix d-none"></div>
                                            </div>
                                        </div>
                                        <!-- Bottom Chat Box End -->
                                            
                                    </div>
                                    
                                </div>
                                <div class="logo_chat_page">
									<div class="default-log-img d-none" >
										<div class="fix-loader" >
											<img style="" src="<?= ASSETS ?>images/chat-room-default-logo.png" class="default-img">	
											<div id="loading">
												<div id="nlpt"></div>
												<div class="msg"><?php echo $this->config->item('chat_room_loader_display_text'); ?></div>
											</div>
										</div>
									</div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Start for edit upgrade-->
            <div class="modal fade" id="userBlockModal" tabindex="-1" role="dialog"  aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content etModal">
                        <div class="modal-header popup_header popup_header_without_text">
                            <h4 class="modal-title popup_header_title" id="user_block_modal_title"></h4>
                            <button type="button" class="close reload_modal" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="popup_body_semibold_title" id="user_block_modal_body">
                            </div>
                            <div class="disclaimer default_disclaimer_text radio_modal_separator">
                                <div>
                                    <label class="default_checkbox">
                                        <input type="checkbox" id="user_block_checkbox_po">
                                        <span class="checkmark"></span><span id="user_block_disclaimer" class="popup_body_regular_checkbox_text"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
							<div class="row">				
								<div class="col-md-12 col-sm-12 col-12 chatFooter">
									<button type="button" data-dismiss="modal" class="btn default_btn red_btn project_cancel_button width-auto reload_modal"><?php echo $this->config->item('close_btn_txt'); ?></button>	
									<button type="button" disabled style="opacity:0.65" class="btn default_btn blue_btn project_cancel_button width-auto" id="user_block_modal_block_button_txt"></button>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal End -->
			<script>
			var chat_room_contacts_list_show_more_text = "<?php echo $this->config->item('chat_room_contacts_list_show_more_text'); ?>";
			var chat_room_contacts_list_show_less_text = "<?php echo $this->config->item('chat_room_contacts_list_show_less_text'); ?>";
			</script>
            <!-- Chat Scroll Script Start -->
            <script src="<?php echo JS; ?>modules/find_project_tagsinput_logged_off.js"></script>
            <script src="<?php echo JS; ?>modules/chat_room_search_filter.js"></script>
            <script>
                
                $(window).resize(function () {
                    var filter_left_chat_height = 0;
                    if ($('.chatResponsive').is(":visible")) {
                        filter_left_chat_height = parseInt($(".chatResponsive").height())+8;
                    }
                    var rbh =
                    $(window).height() -
                    (parseInt($("#headerContent").height()) +
                      parseInt($(".userDetails").height()) +
                      parseInt($(".mesgSendBox").height()) +
                      filter_left_chat_height +
                      7);
                    $(".rightDisplay").css({
                        height: rbh,
                        "overflow-y": "hidden"
                    });

                    
                    var bh = rbh - (parseInt($("#msgSection").height()) + filter_left_chat_height + parseInt($(".mesgSendBox").height()));
                    var lh =
                        $(window).height() -
                        (parseInt($("#headerContent").height()) +
                          parseInt($(".searchBar").height()) +
                          filter_left_chat_height +
                          33);
                    $(".mesgDisplay2").css({
                      height: bh
                    });
                    $(".mesgDisplay1").css({
                        height: lh
                      });
                    if ($(".mesgDisplay2").height() > bh) {
                      $(".mesgDisplay2").css({
                        "overflow-y": "scroll"
                      });
                    }
                   
                    if($(window).outerWidth() > 950) {
						$("#rcv_notfy").removeClass('d-none').addClass('d-block');
                        
                    }if($(window).outerWidth() <= 950) {
                        $("#moreReview").val(1);
                        $(".expand_notification_area" + " a").html(chat_room_contacts_list_show_more_text);
						$("#rcv_notfy").removeClass('d-block').addClass('d-none');
						$("#showMoreLeftChatRoomList").removeClass('on').addClass('off');
                        
                    }
                    $(".mesgDisplay1").getNiceScroll().resize();
                    
                });
                $(document).ready(function () {
                    var filter_left_chat_height = 0;
                    if ($('.chatResponsive').is(":visible")) {
                        filter_left_chat_height = parseInt($(".chatResponsive").height())+8;
                    }
    
                    setTimeout(() => {
                        $('.default-log-img').removeClass('d-none');
                    }, 200);
                    var rbh =
                        $(window).height() -
                        (parseInt($("#headerContent").height()) +
                          parseInt($(".userDetails").height()) +
                          parseInt($(".mesgSendBox").height()) +
                          filter_left_chat_height +
                          7);
                        $(".rightDisplay").css({
                            height: rbh,
                            "overflow-y": "hidden"
                        });
                       var bh = rbh - (parseInt($("#msgSection").height()) + filter_left_chat_height + parseInt($(".mesgSendBox").height()));
                      var lh =
                        $(window).height() -
                        (parseInt($("#headerContent").height()) +
                          parseInt($(".searchBar").height()) +
                          filter_left_chat_height +
                          33);
                      //alert($(window).height()+'=='+$("#headerContent").height()+'=='+$(".searchBar").height()+'=='+filter_left_chat_height);
                      $(".mesgDisplay2").css({
                        height: bh
                      });
                      $(".mesgDisplay1").css({
                        height: lh
                      });
                      if ($(".mesgDisplay2").height() > bh) {
                        $(".mesgDisplay2").css({
                          "overflow-y": "scroll"
                        });
                      }
                    $(".mesgDisplay1").niceScroll({
                        cursorcolor: "#c0bfbf",
                        cursoropacitymax: 0.7,
                        cursorwidth: 8,
                        cursorborder: "1px solid #e1e1e1",
                        cursorborderradius: "8px",
                        background: "#F0F0F0",
                        autohidemode: "leave",
                    });
                    
                });
              
            </script>

            <!-- Bottom Send Button Textarea End -->
            <script>

                $(".chatMulti").accordion({
                    heightStyle: "content",
                    active: false,
                    collapsible: true,
                    header: "div.squareHeader",
					activate: function(event, ui) {
						$(".mesgDisplay1").getNiceScroll().resize();
						$('.mesgDisplay1').getNiceScroll().show();
					}
                });
                $(document).on('click', '.chatMulti', function () {
                
                });
            </script>