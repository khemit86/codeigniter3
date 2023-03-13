<?php
$user = $this->session->userdata('user');

$sp_id = ($project_type =='fulltime')?$projects_disputes_data['employee_winner_id_of_disputed_fulltime_project']:$projects_disputes_data['sp_winner_id_of_disputed_project'];

$po_id = ($project_type =='fulltime')?$projects_disputes_data['employer_id_of_disputed_fulltime_project']:$projects_disputes_data['project_owner_id_of_disputed_project'];


	
$counter_section_style = "display:none";
if((empty($latest_counter_offer_data)  && $sp_id == $user[0]->user_id && isset($projects_disputes_data['dispute_status']) && ($projects_disputes_data['dispute_status'] == 'active' || ($projects_disputes_data['dispute_status'] == 'under_admin_review' && !empty($latest_counter_offer_data) ))) || !empty($latest_counter_offer_data)){
	$counter_section_style = "display:block";
	
	
}
/* echo "khemit".$latest_counter_offer_data."verma";
echo $counter_section_style;
echo "<pre>";
print_r($projects_disputes_data);
die; */
?>
<div class="offerOnly" style="<?php echo $counter_section_style; ?>">
<div class="row" style="display:<?php echo ($disputed_initiated_status == 1 && isset($projects_disputes_data['dispute_status']) && $projects_disputes_data['dispute_status'] == 'active') ? "block" : "none"; ?>">
	<div class="col-md-12 col-sm-12 col-12 empBack">
		<h5>Disputed amount: <?php echo str_replace(".00","",number_format($disputed_amount,  2, '.', ' ')); ?> <?php echo CURRENCY; ?></h5>
	</div>
</div>
<hr style="display:<?php echo ($disputed_initiated_status == 1 && isset($projects_disputes_data['dispute_status']) && $projects_disputes_data['dispute_status'] == 'active') ? "block" : "none"; ?>">

<div class="row" style="display:<?php echo ($disputed_initiated_status == 1 && isset($projects_disputes_data['dispute_status']) && $projects_disputes_data['dispute_status'] == 'active') ? "block" : "none"; ?>">
	<div class="col-md-12 col-sm-12 col-12 empBack">
		<h4 style="color:#f77d0e"><?php echo secondsToWords(strtotime($projects_disputes_data['dispute_negotiation_end_date']) -time()); ?></h4>
	</div>
</div>
<hr style="display:<?php echo ($disputed_initiated_status == 1 && isset($projects_disputes_data['dispute_status']) && $projects_disputes_data['dispute_status'] == 'active') ? "block" : "none"; ?>"> 
	
<div class="row" id="counter_offer_section" style="display:<?php echo empty($latest_counter_offer_data) ? "none" : "block"; ?>">
	<div class="col-md-12 col-sm-12 col-12 empBack ansBack" style="box-shadow:none;border:none;">
		<h5 id="latest_counter_offer_msg" style="display:<?php echo ($disputed_initiated_status == 1 && isset($projects_disputes_data['dispute_status']) && ($projects_disputes_data['dispute_status'] == 'active' || $projects_disputes_data['dispute_status'] == 'active')) ? "block" : "none"; ?>">
		<?php
		if($latest_counter_offer_data['countered_to_user_id'] == $dispute_initiated_by && $user[0]->user_id == $latest_counter_offer_data['countered_to_user_id']){
		 
		 echo $disputed_against_user_name." created the counter offer to you ".str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ".CURRENCY;
		
		}
		else if($latest_counter_offer_data['countered_to_user_id'] == $disputed_against_user_id && $user[0]->user_id == $latest_counter_offer_data['countered_to_user_id']){
		 
		 echo $dispute_initiated_by_user_name." created the counter offer to you ".str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ".CURRENCY;
		
		}
		else if($latest_counter_offer_data['countered_by_user_id'] == $disputed_against_user_id && $user[0]->user_id == $latest_counter_offer_data['countered_by_user_id']){
		 
		 //echo "you created the counter offer for ". $dispute_initiated_by_user_name." of ".str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' ')).CURRENCY .' on '. date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($latest_counter_offer_data['counter_date']));
		 
		 echo "you sent the counter offer of ".str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' ')).' '.CURRENCY .' on '. date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($latest_counter_offer_data['counter_date']));
		
		}
		else if($latest_counter_offer_data['countered_by_user_id'] == $dispute_initiated_by && $user[0]->user_id == $latest_counter_offer_data['countered_by_user_id']){
		 
		// echo "you created the counter offer for ". $disputed_against_user_name." of ".str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' ')).CURRENCY .' on '. date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($latest_counter_offer_data['counter_date']));
		
		echo "you sent the counter offer of ".str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' ')).' '.CURRENCY .' on '. date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($latest_counter_offer_data['counter_date']));
		
		}
		
		?></h5>
		
		<div id="counter_offers_history_section" style="display:<?php echo empty($counter_offer_listing_other_party) ? "none" : "block"; ?>" >
			<div class="default_radio_button radio_bttmBdr radio_left_side payDTls">
				<span>
				<?php
				if($dispute_initiated_by == $user[0]->user_id){
					echo "Previous counter offers from ".$disputed_against_user_name;
				}
				if($disputed_against_user_id == $user[0]->user_id){
					echo "Previous counter offers from ".$dispute_initiated_by_user_name;
				}
				?>
				</span>
				<small class="receive_notification expand_notification_area421"><a class="rcv_notfy_btn" onclick="showMoreReview(421)">(<sup>+</sup>)</a><input type="hidden" id="moreReview421" value="1"></small>
			</div>
			
			<div class="col-md-12 col-sm-12 col-12 empBack"  id="rcv_notfy421" style="display:none;" >
				<?php
				if(!empty($counter_offer_listing_other_party)){
				
					foreach($counter_offer_listing_other_party as $key=>$value){
				?>
					<div class="kcDate">
						<p><?php echo str_replace(".00","",number_format($value['counter_amount_value'],  2, '.', ' '))." ". CURRENCY; ?></p>
						<div><?php echo date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($value['counter_date'])); ?></div>
					</div>
				<?php
					}
				}
				?>
			</div>
		</div>
		<div id="counter_offers_history_section" style="display:<?php echo empty($counter_offer_listing_login_user) ? "none" : "block"; ?>" >
			<div class="default_radio_button radio_bttmBdr radio_left_side payDTls">
				<span>
				<?php
				echo "your counter offers history";
				?>
				</span>
				<small class="receive_notification expand_notification_area422"><a class="rcv_notfy_btn" onclick="showMoreReview(422)">(<sup>+</sup>)</a><input type="hidden" id="moreReview422" value="1"></small>
			</div>
			
			<div class="col-md-12 col-sm-12 col-12 empBack"  id="rcv_notfy422" style="display:none;" >
				<?php
				if(!empty($counter_offer_listing_login_user)){
				
					foreach($counter_offer_listing_login_user as $key=>$value){
				?>
					<div class="kcDate">
						<p><?php echo str_replace(".00","",number_format($value['counter_amount_value'],  2, '.', ' '))." ". CURRENCY; ?></p>
						<div><?php echo date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($value['counter_date'])); ?></div>
					</div>
				<?php
					}
				}
				?>
			</div>
		</div>
		
		<?php
		if($disputed_initiated_status == 1 && isset($projects_disputes_data['dispute_status']) && $projects_disputes_data['dispute_status'] == 'active' && $user[0]->user_id == $latest_counter_offer_data['countered_to_user_id']){
		?>
		<button class="btn aOBtn" id="accept_counter_offer_confirmation" data-dispute-ref-id="<?php echo $latest_counter_offer_data['dispute_reference_id']; ?>"><?php echo $this->config->item('accept_btn_txt'); ?></button>
		<?php
		}
		?>
	</div>
</div>
<?php
if($disputed_initiated_status == 1 && isset($projects_disputes_data['dispute_status']) && $projects_disputes_data['dispute_status'] == 'active'){
	if((empty($latest_counter_offer_data) && $sp_id == $user[0]->user_id) || ($user[0]->user_id == $latest_counter_offer_data['countered_to_user_id']) && $show_create_counter_offer_section == 1){
?>
	<div id="make_counter_offer_section">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12 empBack">
				<h6>offered the amount you prepared</h6>
			</div>
		</div>
		<div class="payOffer">
			<div class="input-group">
			   <input type="text" class="form-control counter_offer_input" name="counter_offer" id="counter_offer">
			   <div class="input-group-prepend">
				   <span class="input-group-text">Kc</span>
			   </div>
			   <div class="error_div_sectn clearfix">
					<span id="counter_offer_error" class="error_msg errMesgOnly required"></span>
				</div>
			</div>
			<h6><strong><?php echo $validation_msg; ?></strong></h6>
			<h6><strong>Caution:</strong> please ensure that this is the amount where you happy</h6>
			<?php
			if($user[0]->user_id == $sp_id){
			?>
				<h6><strong>you may decrease your offer in the future but you may not raise it</strong></h6>
			<?php
			}
			if($user[0]->user_id == $po){ ?>
				<h6><strong>you may increase your offer in the future but you may not decrease it </strong></h6>
			<?php
			}
			?>
			

			<button class="btn mOBtn" id="save_counter_offer"><?php echo $this->config->item('send_btn_txt');?></button>
		</div>
	</div>
<?php
	}
}
?>
</div>