<?php

if($dispute_message_data['message_sent_by_user_id'] == $dispute_initiated_by){
$message_row_class = 'workspace';
}else{
$message_row_class = 'workspace optimizareseo';
}
?>	

<div class="<?php echo $message_row_class; ?>">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-12">
			<div class="media">
				<?php
				if(!empty($dispute_message_data['sender_avatar']) ) {
					$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$dispute_message_data['sender_profile_name'].USER_AVATAR.$dispute_message_data['sender_avatar'];
				} else {
					if($dispute_message_data['sender_account_type'] == 1){
						if($dispute_message_data['sender_gender'] == 'M'){
								$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
						}if($dispute_message_data['sender_gender'] == 'F'){
						   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
						}
					} else {
							$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
					}
				}
				?>
			
			
				<div class="rChat" style="background-image: url('<?php echo $user_profile_picture;?>')"><span class="activeON"></span></div>
				<div class="media-body">
					<div class="mBody">
						<div class="mBodyLR">
							<?php
							$sender_name = $dispute_message_data['sender_account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $dispute_message_data['sender_first_name'] . ' ' . $dispute_message_data['sender_last_name'] : $dispute_message_data['sender_company_name'];
							?>
							<div class="mBodyL"><?php echo $sender_name; ?></div>
							<div class="mBodyR"><?php echo date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($dispute_message_data['message_sent_date'])); ?></div>
							<div class="clearfix"></div>
						</div>
						<p><?php echo htmlspecialchars(trim($dispute_message_data['description']), ENT_QUOTES); ?></p>
						<?php
						if(!empty($dispute_message_data['attachments'])){
							foreach($dispute_message_data['attachments'] as $attachment_key=> $attachment_data){
						?>
							<div class="dwnlFile"><a style="cursor:pointer;" class="download_attachment" data-dispute-ref-id="<?php echo $dispute_message_data['dispute_reference_id']; ?>" data-project-id ="<?php echo $dispute_message_data['disputed_project_id']; ?>"  data-row-id ="<?php echo $attachment_data['id']; ?>"data-type = "active"><i class="fas fa-check"></i><?php echo $attachment_data['dispute_attachment_name']; ?></a></div>
						<?php
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>