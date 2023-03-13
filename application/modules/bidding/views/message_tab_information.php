<?php
  if($project_status == 'in-progress') {
    $comp_inprogress_bidder_data = [];
    $comp_profile_pic = [];
  }
  if(!empty($comp_inprogress_bidder_data)) {
    $inprogress_bidder_data = $comp_inprogress_bidder_data;
	}
	if(!empty($active_dispute_bidder_data)) {
		$inprogress_bidder_data = $active_dispute_bidder_data;
	}
	if(!empty($incomplete_bidder_data)) {
		$inprogress_bidder_data = $incomplete_bidder_data;
	}
  if(!empty($comp_profile_pic)) {
    $profile_pic = $comp_profile_pic;
	}
	if(!empty($active_disputes_profile_pic)) {
		$profile_pic = $active_disputes_profile_pic;
	}
	if(!empty($incomplete_profile_pic)) {
		$profile_pic = $incomplete_profile_pic;
	}
  if($this->session->userdata ('user') && $user[0]->user_id == $inprogress_bidder_data['project_owner_id']) {
    $receiver_id = $inprogress_bidder_data['winner_id'];
  } else {
    $receiver_id = $inprogress_bidder_data['project_owner_id'];
  }
  
  
?>
<div class="mesgDisplay drop_zone row" data-id="<?php echo $receiver_id ?>" data-project-id="<?php echo $inprogress_bidder_data['project_id'] ?>">
  <div id="overlay" class="d-none">
      <div class="text"><?php echo $this->config->item('drop_zone_drag_and_drop_files_area_message_txt'); ?></div>
  </div>
	<div class="rightDisplay chatMesg_Radio w-100">
		<div class="mesgDisplay2">
			<div>
				<div class="mesgChat mesgChat_Radio">							
				  <div class="imgTwo">								
					<div class="twoImage default_avatar_image" style="background-image: url('<?php echo ((int)$user_data['user_id'] != (int)$inprogress_bidder_data['winner_id']) ? $profile_pic[$inprogress_bidder_data['winner_id']]['avatar'] : $profile_pic[$inprogress_bidder_data['project_owner_id']]['avatar']; ?>');" alt="Chat Image"></div>
					<div class="twoImage default_avatar_image" style="background-image: url('<?php echo $user_data['user_profile_picture']; ?>');" alt="Chat Image"></div>
				  </div>
				  <div class="message-wrapper">

				  </div>
				</div>
				<div id="msgSection">
				  <!-- Bottom Attachment Start -->
				  <div class="bottomAttachment" style="display:none;">
					
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
		<div class="mesgFixed">
			<div class="input-group auto-resize-div" id="auto-resize-div">
				<textarea id="chat_text" class="form-control default_textarea_field messages_tab_chat_text" tabindex="1" placeholder="<?php echo $this->config->item('chat_default_type_here_message_placeholder_txt'); ?>"></textarea>
				<span class="input-group-btn default_chat_action_btn">
					<input type="file" class="imgupload file_upload" accept="<?php echo $this->config->item('plugin_chat_attachment_allowed_file_extensions'); ?>" style="display:none"/>
					<button class="OpenImgUpload btn green_btn"><i class="fa fa-file-text" aria-hidden="true"></i></button>
					<button class="btn blue_btn default_btn messages_tab_send_chat_message" tabindex="2" type="button" data-timestamp="" data-receiver="<?php echo $receiver_id;?>"><span><?php echo $this->config->item('send_btn_txt');?></span></button>
				</span>
			</div>
		</div>
	</div>
	<!-- Bottom Chat Box End -->								
</div>