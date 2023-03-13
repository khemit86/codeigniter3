<?php
	
$user = $this->session->userdata ('user');		
?>	
<!-- <hr> -->
<?php if($mode!='decline') { ?>
<div class="<?php echo "maCoRq mark_complete_request_".$mark_complete_project_request_value['id']; ?>">
<?php } ?>
    <div class="row">
		<!-- <div class="col-md-8 col-sm-8 col-12 mpcSection"> -->
		<div class="mpcSection">
			<label class="mpcRequestOn"><b class="default_black_bold">
			<?php
			if($user[0]->user_id == $po_id){
				echo $this->config->item('project_details_page_mark_complete_project_request_listing_requested_on_txt_po_view');
			}
			if($user[0]->user_id == $sp_id){
				echo $this->config->item('project_details_page_mark_complete_project_request_listing_request_received_on_txt_sp_view');
			}
			?></b><span class="default_black_regular"><?php echo date(DATE_TIME_FORMAT,strtotime($mark_complete_project_request_value['request_sent_on'])); ?></span></label>
			<label class="mpcExpiresOn">
				<?php
				if($mark_complete_project_request_value['request_status'] == 'active' && strtotime($mark_complete_project_request_value['request_expires_on']) >= time()){
						echo '<b class="default_black_bold">'.$this->config->item('project_details_page_mark_complete_project_request_listing_request_expires_on_txt').'</b><span class="default_black_regular">'.date(DATE_TIME_FORMAT,strtotime($mark_complete_project_request_value['request_expires_on'])).'</span>'; 
				}
				else if(strtotime($mark_complete_project_request_value['request_expires_on']) <= time()){
						echo '<b class="default_black_bold">'.$this->config->item('project_details_page_mark_complete_project_request_listing_request_expired_on_txt').'</b><span class="default_black_regular">'.date(DATE_TIME_FORMAT,strtotime($mark_complete_project_request_value['request_expires_on'])).'</span>'; 
				}
				else if($mark_complete_project_request_value['request_status'] == 'declined'){
						echo '<b class="default_black_bold">'.$this->config->item('project_details_page_mark_complete_project_request_listing_request_declined_on_txt').'</b><span class="default_black_regular">'.date(DATE_TIME_FORMAT,strtotime($mark_complete_project_request_value['request_declined_on'])).'</span>';
				}
				else if($mark_complete_project_request_value['request_status'] == 'accepted'){
						echo '<b class="default_black_bold">'.$this->config->item('project_details_page_mark_complete_project_request_listing_request_accepted_on_txt').'</b><span class="default_black_regular">'.date(DATE_TIME_FORMAT,strtotime($mark_complete_project_request_value['request_accepted_on'])).'</span>';
				}
				?>
			</label>
		<!-- </div>
		<div class="col-md-4 col-sm-4 col-12"> -->
			<label class="wMesg default_black_bold">
				<?php
				//echo strtotime($mark_complete_project_request_value['request_expires_on']).'|||'.time();
				if($mark_complete_project_request_value['request_status'] == 'active' && strtotime($mark_complete_project_request_value['request_expires_on']) >= time() && $user[0]->user_id == $sp_id){
				?>
				<button type="button" class="btn green_btn default_btn accept_mark_complete_request_confirmation" data-section-name="<?php echo $section_name ?>" date-row-id="<?php echo $mark_complete_project_request_value['id']; ?>" data-section-id="<?php echo $sp_id; ?>"  data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id) ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id) ?>"><?php echo $this->config->item('accept_btn_txt'); ?></button><button type="button" class="btn red_btn default_btn decline_mark_complete_request_confirmation" data-section-name="<?php echo $section_name ?>" date-row-id="<?php echo $mark_complete_project_request_value['id']; ?>" data-section-id="<?php echo $sp_id; ?>"  data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id) ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id) ?>"><?php echo $this->config->item('decline_btn_txt'); ?></button>
				<?php
				}
				else if(strtotime($mark_complete_project_request_value['request_expires_on']) <= time()){
					echo $this->config->item('project_details_page_mark_complete_project_request_listing_expired_txt');
				}
				else if($mark_complete_project_request_value['request_status'] == 'active' && strtotime($mark_complete_project_request_value['request_expires_on']) >= time() && $user[0]->user_id == $po_id){
					echo $this->config->item('project_details_page_mark_complete_project_request_listing_waiting_for_acceptance_txt_po_view');
				}
				else if($mark_complete_project_request_value['request_status'] == 'declined'){
					echo $this->config->item('project_details_page_mark_complete_project_request_listing_request_declined_txt');
				}
				else if($mark_complete_project_request_value['request_status'] == 'accepted'){
					echo $this->config->item('project_details_page_mark_complete_project_request_listing_request_accepted_txt');
				}?></label>
		</div>
    </div>
<?php if($mode!='decline') { ?>
</div>
<?php } ?>