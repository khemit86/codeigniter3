<?php
  if($section_name == 'pending') {
?>
<?php
  foreach($invitations as $val) {
?>
<!-- Pending Invitation Field Start -->
<div class="pendingI">
  <div class="invitationName">
    <div class="default_user_name"><a class="default_user_name_link" href="#"><?php echo $val['invitee_email_address'];?></a></div>
    <div class="default_black_regular_medium"><b class="default_black_bold_medium"><?php echo $this->config->item('sent_on_lbl'); ?></b><?php echo date(DATE_TIME_FORMAT, strtotime($val['initial_invitation_sent_date'])); ?></div>
  </div>
  <div class="invitationCount">
    <?php 
      if($val['resends_available'] > 0) {
    ?>
    <div class="resendLeft resend_count" data-id="<?php echo $val['id']; ?>">
		<?php 
			$dt1 = new DateTime($val['next_resent_available_date']);
			$dt2 = new DateTime();
			$interval = dateDifference(date('Y-m-d H:i:s', strtotime($val['next_resent_available_date'])), date('Y-m-d H:i:s'));
			// $interval = $dt1->diff($dt2);
		?>
		<p class="default_black_regular_medium time_left_label" data-id="<?php echo $val['id']; ?>" style="display:<?php echo ($dt2 <= $dt1) ? 'block' : 'none'; ?>" ><?php echo $this->config->item('time_left_till_next_resent_txt'); ?>: <span class="time_left" data-id="<?php echo $val['id']; ?>"><?php echo $interval; ?></span></p>
		<p class="default_black_regular_medium">
		<?php
			$available_resend = $this->config->item('pending_resend_available');
			$available_resend = str_replace(['{resend_available}', '{invitation_id}'], [$val['resends_available'], $val['id']], $available_resend);
			echo $available_resend;
		?></p>
    </div>
    <?php
      }
    ?>
  </div>
  <div class="invitationBtn">
    <div class="bttnMiddle">
      <button type="button" class="btn default_btn red_btn confirm_revoke_invitation"   data-id="<?php echo $val['id']; ?>" data-invite-id="<?php echo $val['invitation_id']; ?>"><?php echo $this->config->item('revoke_invitation_btn'); ?> <i id="revoke_spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button><button type="button" class="btn green_btn default_btn confirm_resend_invitation" <?php echo ($dt2 <= $dt1) ? 'disabled' : '' ?> style="display: <?php echo $val['resends_available'] > 0 ? '' : 'none'; ?>" data-id="<?php echo $val['id']; ?>" data-invite-id="<?php echo $val['invitation_id']; ?>"><?php echo $this->config->item('resend_invitation_btn'); ?> <i id="resend_spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>
    </div>
  </div>
  <div class="clearfix"></div>
</div>
<!-- Pending Invitation Field End -->				
<?php
  }
?>		
<?php
  }
?>
<?php
  if($section_name == 'revoked') {
?>
<?php
  foreach($invitations as $val) {
?>
<!-- Revoked Invitation Field Start -->
<div class="pendingI revInvit">
  <div class="invitationName">
    <div class="default_user_name"><a class="default_user_name_link" href="#"><?php echo $val['invitee_email_address']; ?></a></div>
    
  </div>
  <div class="invitationCount">
    <div class="resendLeft">
      <div class="default_black_regular_medium"><b class="default_black_bold_medium"><?php echo $this->config->item('sent_on_lbl'); ?></b><?php echo date(DATE_TIME_FORMAT, strtotime($val['initial_invitation_sent_date'])) ?></div>
    </div>
  </div>
  <div class="invitationBtn">
    <div class="bttnMiddle">
      <div class="default_black_regular_medium"><b class="default_black_bold_medium"><?php echo $this->config->item('revoked_on_lbl'); ?></b><?php echo date(DATE_TIME_FORMAT, strtotime($val['invitation_revoke_date'])) ?></div>
    </div>
  </div>
  <div class="clearfix"></div>
</div>
<!-- Revoked Invitation Field End -->
<?php
  }
?>
<?php
  }
?>
<?php
  if($section_name == 'accepted') {
?>
<?php
  foreach($invitations as $val) {
?>
<!-- Accepted Invitation Field Start -->
<div class="pendingI accInvt">
  <div class="invitationName">
    <div class="default_user_name"><a class="default_user_name_link" href="#"><?php echo $val['invitee_email_address']; ?></a></div>
  </div>
  <div class="invitationCount">
    <div class="default_black_regular_medium"><b class="default_black_bold_medium"><?php echo $this->config->item('accepted_on_lbl'); ?></b><?php echo date(DATE_TIME_FORMAT, strtotime($val['invitee_account_validation_date'])); ?></div>
  </div>
  <div class="clearfix"></div>
</div>
<!-- Accepted Invitation Field End -->
<?php
  }
?>
<?php
  }
?>
<?php 
  if(!empty($invitations)) {
?>
<!-- Pagination Start -->
<div class="pagnOnly">
  <div class="row">
    <div class="<?php echo !empty($pagination_links) ? 'col-md-7 col-sm-7 col-12' : 'col-md-12 col-12'; ?>">
      <div class="pageOf">
        <label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no"><?php echo $page_no; ?></span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $invitations_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
      </div>
    </div>
    <div class="col-md-5 col-sm-5 col-12" style="display:<?php echo !empty($pagination_links) ? 'block' : 'none'; ?>">
      <div class="modePage">
        <?php echo $pagination_links; ?>
      </div>
    </div>
  </div>
</div>
<!-- Pagination End -->
<?php
  }
?>